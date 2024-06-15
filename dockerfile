FROM php:8.0-apache


WORKDIR /var/www/html

COPY . /var/www/html


COPY 000-default.conf /etc/apache2/sites-available/000-default.conf


EXPOSE 80

# Start the Apache server
CMD ["apache2-foreground"]
