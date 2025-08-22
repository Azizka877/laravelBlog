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
    libpng \
    libzip \
    oniguruma \
    postgresql-libs \
    sqlite \
    sqlite-dev

# Installer les extensions PHP
RUN docker-php-ext-install \
    pdo_mysql \
    pdo_pgsql \
    pdo_sqlite \
    bcmath \
    gd \
    zip \
    mbstring

# Configuration PHP
RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/errors.ini && \
    echo "display_errors = On" >> /usr/local/etc/php/conf.d/errors.ini && \
    echo "log_errors = On" >> /usr/local/etc/php/conf.d/errors.ini

# Configuration Nginx
RUN mkdir -p /etc/nginx/conf.d
RUN echo 'events {}' > /etc/nginx/nginx.conf && \
    echo 'http {' >> /etc/nginx/nginx.conf && \
    echo '    include /etc/nginx/mime.types;' >> /etc/nginx/nginx.conf && \
    echo '    default_type application/octet-stream;' >> /etc/nginx/nginx.conf && \
    echo '    include /etc/nginx/conf.d/*.conf;' >> /etc/nginx/nginx.conf && \
    echo '}' >> /etc/nginx/nginx.conf

RUN echo 'server {' > /etc/nginx/conf.d/default.conf && \
    echo '    listen 8000;' >> /etc/nginx/conf.d/default.conf && \
    echo '    server_name _;' >> /etc/nginx/conf.d/default.conf && \
    echo '    root /var/www/html/public;' >> /etc/nginx/conf.d/default.conf && \
    echo '    index index.php index.html;' >> /etc/nginx/conf.d/default.conf && \
    echo '' >> /etc/nginx/conf.d/default.conf && \
    echo '    location / {' >> /etc/nginx/conf.d/default.conf && \
    echo '        try_files $uri $uri/ /index.php?$query_string;' >> /etc/nginx/conf.d/default.conf && \
    echo '    }' >> /etc/nginx/conf.d/default.conf && \
    echo '' >> /etc/nginx/conf.d/default.conf && \
    echo '    location ~ \.php$ {' >> /etc/nginx/conf.d/default.conf && \
    echo '        fastcgi_pass 127.0.0.1:9000;' >> /etc/nginx/conf.d/default.conf && \
    echo '        fastcgi_index index.php;' >> /etc/nginx/conf.d/default.conf && \
    echo '        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;' >> /etc/nginx/conf.d/default.conf && \
    echo '        include fastcgi_params;' >> /etc/nginx/conf.d/default.conf && \
    echo '    }' >> /etc/nginx/conf.d/default.conf && \
    echo '}' >> /etc/nginx/conf.d/default.conf

# Copier l'application
COPY --from=builder /var/www/html /var/www/html

# Créer le répertoire database et configurer les permissions
RUN mkdir -p /var/www/html/database && \
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Script de démarrage
RUN echo '#!/bin/sh' > /start.sh && \
    echo 'set -e' >> /start.sh && \
    echo '' >> /start.sh && \
    echo '# Créer la base de données SQLite si elle n'\''existe pas' >> /start.sh && \
    echo 'if [ ! -f /var/www/html/database/database.sqlite ]; then' >> /start.sh && \
    echo '    echo "Creating SQLite database..."' >> /start.sh && \
    echo '    touch /var/www/html/database/database.sqlite' >> /start.sh && \
    echo '    chown www-data:www-data /var/www/html/database/database.sqlite' >> /start.sh && \
    echo '    chmod 666 /var/www/html/database/database.sqlite' >> /start.sh && \
    echo 'fi' >> /start.sh && \
    echo '' >> /start.sh && \
    echo '# Configurer les permissions' >> /start.sh && \
    echo 'chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database' >> /start.sh && \
    echo 'chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache' >> /start.sh && \
    echo '' >> /start.sh && \
    echo '# Générer la clé application si nécessaire' >> /start.sh && \
    echo 'if ! grep -q "APP_KEY=base64:" /var/www/html/.env; then' >> /start.sh && \
    echo '    echo "Generating application key..."' >> /start.sh && \
    echo '    php /var/www/html/artisan key:generate --force' >> /start.sh && \
    echo 'fi' >> /start.sh && \
    echo '' >> /start.sh && \
    echo '# Exécuter les migrations' >> /start.sh && \
    echo 'echo "Running migrations..."' >> /start.sh && \
    echo 'php /var/www/html/artisan migrate --force' >> /start.sh && \
    echo '' >> /start.sh && \
    echo '# Exécuter les seeders' >> /start.sh && \
    echo 'echo "Running seeders..."' >> /start.sh && \
    echo 'php /var/www/html/artisan db:seed --force' >> /start.sh && \
    echo '' >> /start.sh && \
    echo '# Optimiser l'\''application' >> /start.sh && \
    echo 'echo "Optimizing application..."' >> /start.sh && \
    echo 'php /var/www/html/artisan optimize' >> /start.sh && \
    echo '' >> /start.sh && \
    echo '# Démarrer les services' >> /start.sh && \
    echo 'echo "Starting PHP-FPM and Nginx..."' >> /start.sh && \
    echo 'php-fpm -D' >> /start.sh && \
    echo 'nginx -g "daemon off;"' >> /start.sh

RUN chmod +x /start.sh

EXPOSE 8000

CMD ["/start.sh"]