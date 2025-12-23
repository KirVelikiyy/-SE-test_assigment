# Используем официальный образ PHP 8.4-fpm
FROM php:8.4-fpm-alpine AS laravel-base

# Устанавливаем системные зависимости, необходимые для расширений и Composer
RUN apk add --no-cache \
    libzip-dev \
    postgresql-dev \
    linux-headers \
    git \
    unzip

# Устанавливаем расширения PHP
RUN docker-php-ext-install pdo pdo_pgsql bcmath sockets zip

# Устанавливаем Composer
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

# Создаем пользователя и группу приложения для безопасности
RUN addgroup -g 1000 -S app && \
    adduser -u 1000 -S app -G app

# Устанавливаем рабочую директорию
WORKDIR /var/www/html

# Копируем только файлы composer, чтобы кэшировать этот слой.
# Сборка будет перезапускаться с этого места только если composer.json или composer.lock изменятся.
COPY composer.* ./

# Устанавливаем зависимости Composer.
# --no-scripts предотвращает запуск скриптов до того, как будет доступен .env файл и код приложения.
RUN composer install --no-interaction --prefer-dist --no-scripts

# Переключаемся на непривилегированного пользователя
USER app

# Команда по умолчанию из базового образа (php-fpm) будет выполнена при запуске контейнера.
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
