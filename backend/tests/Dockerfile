FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y unzip git curl

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHPUnit globally
RUN composer global require phpunit/phpunit --prefer-dist --no-interaction

# Make sure global Composer binaries are in the PATH
ENV PATH="$PATH:/root/.composer/vendor/bin"

# Set working directory
WORKDIR /var/www

CMD ["php", "-a"]
