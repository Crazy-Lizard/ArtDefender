# # Используем официальный образ PHP с расширениями для Laravel
# FROM php:8.2-fpm

# # Установите зависимости
# RUN apt-get update && apt-get install -y \
#     git \
#     curl \
#     libpng-dev \
#     libonig-dev \
#     libxml2-dev \
#     zip \
#     unzip

# # Очистите кэш
# RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# # Установите расширения PHP
# RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# # Установите Composer
# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# # Укажите рабочую директорию
# WORKDIR /var/www/html

# # Скопируйте исходный код (кроме указанного в .dockerignore)
# COPY . .

# # Установите права (Laravel требует права на запись в storage и bootstrap/cache)
# RUN chown -R www-data:www-data /var/www/html/storage
# RUN chown -R www-data:www-data /var/www/html/bootstrap/cache

# Используем официальный образ PHP с расширениями для Laravel
FROM php:8.2-fpm

# Установите зависимости
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    # Add PostgreSQL dev files
    libpq-dev

# Очистите кэш
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Установите расширения PHP
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    # Add PostgreSQL PDO driver
    pdo_pgsql

# Установите Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Укажите рабочую директорию
WORKDIR /var/www/html

# Скопируйте исходный код (кроме указанного в .dockerignore)
COPY . .

# Установите права (Laravel требует права на запись в storage и bootstrap/cache)
RUN chown -R www-data:www-data /var/www/html/storage
RUN chown -R www-data:www-data /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage/framework/views
RUN chown -R www-data:www-data /var/www/html/storage
RUN chown -R www-data:www-data /var/www/html/storage/framework/views
