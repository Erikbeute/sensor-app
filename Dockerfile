FROM php:8.4-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpq-dev \
    libsqlite3-dev \
    postgresql-client \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_sqlite pdo_pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy application files
COPY . /app

# Create necessary directories
RUN mkdir -p var/cache var/log && chmod -R 777 var

# Install PHP dependencies (including dev for dev environment)
ARG APP_ENV=dev
RUN if [ "$APP_ENV" = "dev" ]; then \
      composer install --optimize-autoloader; \
    else \
      composer install --no-dev --optimize-autoloader; \
    fi

# Expose port (if needed, though we'll use nginx as reverse proxy)
EXPOSE 9000

CMD ["php-fpm"]
