FROM php:apache

MAINTAINER sys-ops.id

RUN apt update && apt upgrade -y && apt install nano net-tools iproute2 iputils-ping git -y

RUN rm -rf /var/www/html/index.php
RUN rm -rf /var/www/html/index.html

RUN cd /tmp && git clone https://github.com/sideka-cloud/web-test.git && \
cp -R web-test/* /var/www/html 

WORKDIR /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
