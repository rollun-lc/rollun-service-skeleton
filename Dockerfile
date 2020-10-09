FROM maxrollundev/php-fpm-7.2

USER root

WORKDIR /var/www/app

COPY ./ /var/www/app/

RUN composer install

COPY ./config/php-fpm.conf  /usr/local/etc/php-fpm.conf
COPY ./config/php.ini  /usr/local/etc/php/conf.d/php.ini

# -R - allow run as root
CMD ["php-fpm", "-R"]
