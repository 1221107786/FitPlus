
FROM php:8.0-apache


WORKDIR "C:\Users\Prasad\OneDrive\Documents\FitPlus\FitPlus"

COPY . "C:\Users\Prasad\OneDrive\Documents\FitPlus\FitPlus"


EXPOSE 80


CMD ["apache2-foreground"]
