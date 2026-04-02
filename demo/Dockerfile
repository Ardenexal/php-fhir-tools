FROM dunglas/frankenphp:1-php8.5-bookworm

# Install extensions not already present in the base image
# (dom, xml, xmlwriter, xmlreader, simplexml, mbstring, ctype, iconv are pre-installed)
RUN install-php-extensions bcmath intl opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Install dependencies first for better layer caching
# (invalidated only when composer.json or composer.lock change)
COPY demo/composer.json demo/composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copy monorepo source required by ../src/* PSR-4 autoload paths in demo/composer.json
COPY src/ /src/

# Copy rest of demo application
COPY demo/ .

# Regenerate classmap now that all source directories are present,
# then run post-install scripts (cache:clear, assets:install, importmap:install)
RUN composer dump-autoload --optimize --no-dev \
    && composer run-script post-install-cmd --no-interaction || true

ENV FRANKENPHP_CONFIG="worker ./public/index.php"
ENV SERVER_NAME="http://:80"

EXPOSE 80
