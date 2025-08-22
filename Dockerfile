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

# Installer les dépendances PHP (INCLURE faker pour les seeders)
RUN composer install --optimize-autoloader --no-interaction \
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
    sqlite

# Installer les extensions PHP
RUN apk add --no-cache --virtual .build-deps \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    postgresql-dev \
    sqlite-dev \
    && docker-php-ext-install \
    pdo_mysql \
    pdo_pgsql \
    pdo_sqlite \
    bcmath \
    gd \
    zip \
    mbstring \
    && apk del .build-deps

# Configuration Nginx CORRIGÉE
RUN mkdir -p /etc/nginx/conf.d && \
    echo 'events {}' > /etc/nginx/nginx.conf && \
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

# Copier l'application builder
COPY --from=builder /var/www/html /var/www/html

# Configurer les permissions
RUN mkdir -p /var/www/html/database && \
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 8000

# Commande de démarrage CORRIGÉE (sans tinker qui cause des erreurs)
CMD sh -c "\
    echo '🚀 Démarrage de l'\''application...' && \
    \
    # Créer la base de données SQLite\
    if [ ! -f /var/www/html/database/database.sqlite ]; then \
        echo 'Creating SQLite database...' && \
        touch /var/www/html/database/database.sqlite && \
        chown www-data:www-data /var/www/html/database/database.sqlite && \
        chmod 666 /var/www/html/database/database.sqlite; \
    fi && \
    \
    # Permissions\
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache && \
    \
    # Effacer les caches (IMPORTANT)\
    echo 'Clearing caches...' && \
    php artisan config:clear && \
    php artisan cache:clear && \
    php artisan route:clear && \
    php artisan view:clear && \
    \
    # Générer la clé application si nécessaire\
    echo 'Checking application key...' && \
    if ! grep -q 'APP_KEY=base64:' /var/www/html/.env; then \
        php artisan key:generate --force; \
    fi && \
    \
    # Migrations (ESSENTIEL)\
    echo 'Running migrations...' && \
    php artisan migrate --force && \
    \
    # Seeders (pour les données de démo)\
    echo 'Running seeders...' && \
    php artisan db:seed --force && \
    \
    # Optimiser l'application\
    echo 'Optimizing application...' && \
    php artisan optimize && \
    \
    # Démarrer les services\
    echo 'Starting PHP-FPM and Nginx...' && \
    php-fpm -D && \
    nginx -g 'daemon off;'"