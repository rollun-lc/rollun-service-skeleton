<?php
/**
 * @copyright Copyright © 2014 Rollun LC (http://rollun.com/)
 * @license LICENSE.md New BSD License
 */

declare(strict_types=1);

use Jaeger\Tag\StringTag;
use rollun\dic\InsideConstruct;
use rollun\logger\LifeCycleToken;
use rollun\utils\Json\Serializer;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Expressive\Application;
use Zend\Expressive\MiddlewareFactory;
use Zend\ServiceManager\ServiceManager;

error_reporting(E_ALL ^ E_USER_DEPRECATED);

// Delegate static file requests back to the PHP built-in webserver
if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
    return false;
}

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/**
 * Self-called anonymous function that creates its own scope and keep the global namespace clean.
 */
(function () {
    /** @var ServiceManager $container */
    $container = require 'config/container.php';

    InsideConstruct::setContainer($container);

    /**
     * @var \Jaeger\Tracer\Tracer $tracer
     */
    $tracer = $container->get(\Jaeger\Tracer\Tracer::class);
    $span = $tracer->start('index');

    /**
     * Self-called anonymous function that creates its own scope and keep the top lamda clean.
     */
    /**
     *
     */
    (function () use ($span) {
        $serverRequest = ServerRequestFactory::fromGlobals();

        $span->addTag(new StringTag('request.attributes.json',
            Serializer::jsonSerialize($serverRequest->getAttributes())));

        foreach ($serverRequest->getHeaders() as $headerName => $headerValues) {
            $span->addTag(new StringTag("request.header.$headerName", implode(' ,', $headerValues)));
        }

        $span->addTag(new StringTag('request.method', $serverRequest->getMethod()));

        $span->addTag(new StringTag('request.protocolVersion', $serverRequest->getProtocolVersion()));

        $span->addTag(new StringTag('request.target', $serverRequest->getRequestTarget()));

        $span->addTag(new StringTag('request.uri', $serverRequest->getUri()->__toString()));

        $span->addTag(new StringTag('request.get.raw', $serverRequest->getUri()->getQuery()));
        $span->addTag(new StringTag('request.get.json',
            Serializer::jsonSerialize($serverRequest->getQueryParams())));


        $span->addTag(new StringTag('request.body', $serverRequest->getBody()->__toString()));

        foreach ($serverRequest->getHeaders() as $headerName => $headerValues) {
            $span->addTag(new StringTag("request.header.$headerName", implode(' ,', $headerValues)));
        }

        foreach ($serverRequest->getCookieParams() as $cookieName => $cookieValue) {
            $span->addTag(new StringTag("request.cookie.$cookieName",
                (is_array($cookieValue) ? implode(' ,', $cookieValue) : $cookieValue)));
        }

    })();


    /** @var Application $app */
    $app = $container->get(Application::class);
    $factory = $container->get(MiddlewareFactory::class);

    // Execute programmatic/declarative middleware pipeline and routing
    // configuration statements
    (require 'config/pipeline.php')($app, $factory, $container);
    (require 'config/routes.php')($app, $factory, $container);

    // Init lifecycle token
    $lifeCycleToken = LifeCycleToken::generateToken();

    if (LifeCycleToken::getAllHeaders() && array_key_exists("LifeCycleToken", LifeCycleToken::getAllHeaders())) {
        $lifeCycleToken->unserialize(LifeCycleToken::getAllHeaders()["LifeCycleToken"]);
    }

    $container->setService(LifeCycleToken::class, $lifeCycleToken);

    $app->run();
    $tracer->finish($span);
    $tracer->flush();
})();
