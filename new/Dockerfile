FROM php:8.4-apache

MAINTAINER sys-ops.id

RUN apt update && apt upgrade -y && apt install nano net-tools iproute2 iputils-ping -y

WORKDIR /var/www/html

RUN rm -rf /var/www/html/index.php
RUN rm -rf /var/www/html/index.html

COPY . .

EXPOSE 80

CMD ["apache2-foreground"]
