FROM php:8.2-fpm

# system deps
RUN apt-get update && apt-get install -y \
    git curl libzip-dev zip unzip libonig-dev libpng-dev libjpeg-dev libfreetype6-dev \
    libicu-dev libxml2-dev libpq-dev default-mysql-client nodejs npm

# php extensions
RUN docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl zip

# Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Create www user
RUN useradd -G www-data,root -u 1000 -m developer
RUN mkdir -p /var/www/html
WORKDIR /var/www/html

# install npm globally if needed (optional)
# RUN npm install -g npm@latest

# expose
EXPOSE 9000

CMD ["php-fpm"]
