FROM wordpress:6.4.3-php8.2-fpm

# Install system dependencies and wp-cli for managing the WordPress install.
RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        less \
        default-mysql-client \
        unzip \
        git \
    ; \
    curl -o /usr/local/bin/wp -fSL https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar; \
    chmod +x /usr/local/bin/wp; \
    rm -rf /var/lib/apt/lists/*

# Copy PHP configuration overrides (e.g. memory limits/uploads).
COPY uploads.ini /usr/local/etc/php/conf.d/uploads.ini

WORKDIR /var/www/html
