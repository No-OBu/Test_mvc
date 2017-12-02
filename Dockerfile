FROM php:5.6.32-cli-alpine3.4
EXPOSE 8000
RUN docker-php-ext-install mysqli pdo pdo_mysql
VOLUME /usr/src/project
WORKDIR /usr/src/project/public/
ENTRYPOINT [ "php", "-S", "0.0.0.0:8000", "index.php" ]
