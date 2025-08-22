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

# Installer les dépendances système avec les dépendances de build
RUN apk update && apk add --no-cache \
    nginx \
    libpng \
    libzip \
    oniguruma \
    postgresql-libs \
    sqlite

# Installer les dépendances de développement pour compiler les extensions PHP
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

# Créer les dossiers de configuration manquants
RUN mkdir -p /etc/nginx/conf.d

# Créer les fichiers de configuration Nginx avec la bonne syntaxe
RUN echo -e 'events {}\nhttp {\n    include /etc/nginx/mime.types;\n    default_type application/octet-stream;\n    \n    log_format main '\''$remote_addr - $remote_user [$time_local] "$request" '\''\n                   '\''$status $body_bytes_sent "$http_referer" '\''\n                   '\''"$http_user_agent" "$http_x_forwarded_for"'\'';\n    \n    access_log /var/log/nginx/access.log main;\n    error_log /var/log/nginx/error.log warn;\n    \n    sendfile on;\n    keepalive_timeout 65;\n    \n    include /etc/nginx/conf.d/*.conf;\n}' > /etc/nginx/nginx.conf

RUN echo -e 'server {\n    listen 8000;\n    server_name _;\n    root /var/www/html/public;\n    index index.php index.html;\n\n    location / {\n        try_files $uri $uri/ /index.php?$query_string;\n    }\n\n    location ~ \\.php$ {\n        fastcgi_pass 127.0.0.1:9000;\n        fastcgi_index index.php;\n        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;\n        include fastcgi_params;\n    }\n\n    location ~ /\\.ht {\n        deny all;\n    }\n\n    error_log /var/log/nginx/error.log;\n    access_log /var/log/nginx/access.log;\n}' > /etc/nginx/conf.d/default.conf

# Copier l'application builder
COPY --from=builder /var/www/html /var/www/html

# Configurer les permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Créer le répertoire database s'il n'existe pas
RUN mkdir -p /var/www/html/database

# Créer le script de démarrage avec des commandes simples
RUN echo '#!/bin/sh' > /usr/local/bin/start.sh && \
    echo 'if [ ! -f /var/www/html/database/database.sqlite ]; then' >> /usr/local/bin/start.sh && \
    echo '    echo "Creating SQLite database..."' >> /usr/local/bin/start.sh && \
    echo '    touch /var/www/html/database/database.sqlite' >> /usr/local/bin/start.sh && \
    echo '    chown www-data:www-data /var/www/html/database/database.sqlite' >> /usr/local/bin/start.sh && \
    echo 'fi' >> /usr/local/bin/start.sh && \
    echo 'echo "Running migrations..."' >> /usr/local/bin/start.sh && \
    echo 'php /var/www/html/artisan migrate --force' >> /usr/local/bin/start.sh && \
    echo 'echo "Running seeders..."' >> /usr/local/bin/start.sh && \
    echo 'php /var/www/html/artisan db:seed --force' >> /usr/local/bin/start.sh && \
    echo 'echo "Generating application key..."' >> /usr/local/bin/start.sh && \
    echo 'php /var/www/html/artisan key:generate --force' >> /usr/local/bin/start.sh && \
    echo 'echo "Optimizing application..."' >> /usr/local/bin/start.sh && \
    echo 'php /var/www/html/artisan optimize' >> /usr/local/bin/start.sh && \
    echo 'echo "Starting PHP-FPM and Nginx..."' >> /usr/local/bin/start.sh && \
    echo 'php-fpm -D' >> /usr/local/bin/start.sh && \
    echo 'nginx -g "daemon off;"' >> /usr/local/bin/start.sh

RUN chmod +x /usr/local/bin/start.sh

# Exposer le port
EXPOSE 8000

# Commande de démarrage
CMD ["/usr/local/bin/start.sh"]