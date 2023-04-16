FROM php:apache

RUN apt update && apt upgrade -y && apt install git -y

RUN rm -rf /var/www/html/index.php

RUN cd /tmp && git clone https://github.com/sideka-cloud/web-test.git && \
cp -R web-test/* /var/www/html

WORKDIR /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
