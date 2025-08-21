# Étape 1 : Builder l'application
FROM php:8.2-fpm-alpine AS builder

# Installer les dépendances système
RUN apk update && apk add --no-cache \
    git \
    unzip \
    curl \
    libpng-dev \
    libzip-dev \
    zip \
    oniguruma-dev \
    postgresql-dev \
    && docker-php-ext-install \
    pdo_mysql \
    pdo_pgsql \
    bcmath \
    gd \
    zip \
    mbstring

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copier les fichiers de l'application
WORKDIR /var/www/html
COPY . .

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && php artisan optimize:clear \
    && chown -R www-data:www-data storage bootstrap/cache

# Étape 2 : Image finale
FROM php:8.2-fpm-alpine

# Installer les dépendances système
RUN apk update && apk add --no-cache \
    nginx \
    supervisor \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    postgresql-dev \
    && docker-php-ext-install \
    pdo_mysql \
    pdo_pgsql \
    bcmath \
    gd \
    zip \
    mbstring

# Configurer Nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/site.conf /etc/nginx/conf.d/default.conf

# Configurer Supervisor
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copier l'application builder
COPY --from=builder /var/www/html /var/www/html

# Configurer les permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Exposer le port
EXPOSE 8000

# Commande de démarrage
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]