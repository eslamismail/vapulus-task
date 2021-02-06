FROM webdevops/php-apache:7.4

WORKDIR /app
ADD . .
RUN composer install
RUN mkdir -p /app/storage
RUN chmod -R 777 /app/storage/logs
RUN chmod -R 777  bootstrap/cache
RUN a2enmod rewrite
RUN chmod -R 777 storage/framework/sessions
RUN chmod -R 777 storage/framework/views
RUN php artisan optimize:clear
RUN composer dump-autoload
