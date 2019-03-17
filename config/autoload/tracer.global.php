<?php
/**
 * @copyright Copyright © 2014 Rollun LC (http://rollun.com/)
 * @license LICENSE.md New BSD License
 */

use Psr\Log\LoggerInterface;
use rollun\logger\Formatter\ContextToString;
use rollun\logger\Processor\ExceptionBacktrace;
use rollun\logger\Processor\IdMaker;
use rollun\logger\Processor\LifeCycleTokenInjector;
use Zend\Log\Writer\Db as DbWriter;
use Zend\Log\Writer\Stream;

return [
    Jaeger\Tracer\Tracer::class => [
        'serviceName' => getenv('SERVICE_NAME') ?? 'application'
    ]
];
