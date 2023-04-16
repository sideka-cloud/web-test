FROM php:apache

RUN apt update && apt upgrade -y && apt install nano net-tools git -y

RUN rm -rf /var/www/html/index.php

RUN cd /tmp && git clone https://github.com/sideka-cloud/web-test.git && \
<<<<<<< HEAD
cp -R web-test/* /var/www/html 
=======
cp -R web-test/* /var/www/html
>>>>>>> d8d278cae63e5d68e0de174867235ee89ffd5c36

WORKDIR /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
<<<<<<< HEAD


=======
>>>>>>> d8d278cae63e5d68e0de174867235ee89ffd5c36
