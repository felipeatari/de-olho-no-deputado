FROM php:8.2-fpm

LABEL maintainer="F12 Rifas <mr.robot.felipe@gmail.com>"

# Argumentos para o usuário e grupo (UID e GID)
ARG WWWUSER=1000
ARG WWWGROUP=1000

# Instalar dependências e extensões PHP necessárias
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev zip \
    libpq-dev libjpeg-dev libfreetype6-dev libssl-dev \
    && docker-php-ext-install pdo pdo_mysql zip exif pcntl bcmath gd \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar Node e npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm

# Ajustar usuário www-data para o UID/GID do host para evitar problemas com volumes montados
RUN usermod -u ${WWWUSER} www-data && groupmod -g ${WWWGROUP} www-data

# Definir diretório de trabalho
WORKDIR /var/www/html

# Criar pastas Laravel que precisam ser graváveis pelo PHP
RUN mkdir -p storage/logs bootstrap/cache

# Ajustar dono e permissões destas pastas para www-data com UID/GID correto
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Recomendar não alterar demais permissões da raiz, para evitar conflito com volumes
RUN chown -R www-data:www-data /var/www/html

# Evita erro de permissão ao rodar comandos npm como www-data (ex: npm ci, npm run build)
RUN mkdir -p /var/www/.npm && chown -R www-data:www-data /var/www/.npm

# Cria o diretório de configuração do PsySH e define permissões para o usuário www-data
RUN mkdir -p /var/www/.config/psysh && chown -R www-data:www-data /var/www/.config

# Cria diretório vendor e libera permissão
RUN mkdir -p /var/www/html/vendor && \
    chown -R www-data:www-data /var/www/html

# Cria diretório node_modules e libera permissão
RUN mkdir -p /var/www/html/node_modules && \
    chown -R www-data:www-data /var/www/html

# Rodar PHP-FPM como www-data (com UID/GID ajustado)
USER www-data

# Expor a porta padrão do PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
