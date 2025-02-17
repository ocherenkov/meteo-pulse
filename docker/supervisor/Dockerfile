FROM php:8.2-cli

# Install Supervisor
RUN apt-get update && apt-get install -y supervisor && apt-get clean

# Install PHP extensions
RUN docker-php-ext-install bcmath pdo_mysql

# Copy Laravel
WORKDIR /var/www
COPY . /var/www

# Create directory for Supervisor and Worker logs
RUN mkdir -p /etc/supervisor/conf.d
RUN mkdir -p /var/www/storage/logs

# Copy config files
COPY docker/supervisor/laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf
COPY docker/supervisor/laravel-scheduler.conf /etc/supervisor/conf.d/laravel-scheduler.conf

CMD ["supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]
