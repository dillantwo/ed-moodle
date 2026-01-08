# Use the official PHP 8.4 FPM image as a base
FROM php:8.4-fpm

# Build argument to control whether to include Moodle code
ARG INCLUDE_MOODLE=true

# Install necessary extensions, Nginx, PostgreSQL extension, OPcache, Redis, and cron
RUN apt-get update && \
    apt-get install -y --no-install-recommends nginx unzip git curl libzip-dev libjpeg-dev libpng-dev \
    libfreetype6-dev libicu-dev libxml2-dev libpq-dev cron && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install zip gd intl soap exif pgsql pdo_pgsql opcache && \
    pecl install redis && \
    docker-php-ext-enable redis && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Download Moodle code (only if INCLUDE_MOODLE=true)
RUN if [ "$INCLUDE_MOODLE" = "true" ]; then \
        git clone git://git.moodle.org/moodle.git && cd moodle && \
        git branch --track MOODLE_501_STABLE origin/MOODLE_501_STABLE && \
        git checkout MOODLE_501_STABLE && \
        cp -rf ./* /var/www/html/ && \
        cd .. && rm -rf moodle; \
    fi

# Set PHP settings for Moodle: max_input_vars, upload limits, and OPcache
RUN echo "max_input_vars=5000" >> /usr/local/etc/php/conf.d/docker-php-moodle.ini && \
    echo "upload_max_filesize=512M" >> /usr/local/etc/php/conf.d/docker-php-moodle.ini && \
    echo "post_max_size=512M" >> /usr/local/etc/php/conf.d/docker-php-moodle.ini && \
    echo "max_execution_time=300" >> /usr/local/etc/php/conf.d/docker-php-moodle.ini && \
    echo "max_input_time=300" >> /usr/local/etc/php/conf.d/docker-php-moodle.ini && \
    echo "memory_limit=256M" >> /usr/local/etc/php/conf.d/docker-php-moodle.ini && \
    echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/docker-php-opcache.ini && \
    echo "opcache.enable_cli=1" >> /usr/local/etc/php/conf.d/docker-php-opcache.ini && \
    echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/docker-php-opcache.ini && \
    echo "opcache.interned_strings_buffer=8" >> /usr/local/etc/php/conf.d/docker-php-opcache.ini && \
    echo "opcache.max_accelerated_files=10000" >> /usr/local/etc/php/conf.d/docker-php-opcache.ini && \
    echo "opcache.revalidate_freq=60" >> /usr/local/etc/php/conf.d/docker-php-opcache.ini && \
    echo "opcache.validate_timestamps=1" >> /usr/local/etc/php/conf.d/docker-php-opcache.ini

# Create moodledata directory
RUN mkdir -p /var/www/moodledata

# Configure Nginx to point to Moodle 5.1 public directory
RUN mkdir -p /var/log/nginx && \
    mkdir -p /var/cache/nginx && \
    mkdir -p /run

# Configure PHP-FPM for better performance
RUN echo "[www]" > /usr/local/etc/php-fpm.d/zz-moodle.conf && \
    echo "pm = dynamic" >> /usr/local/etc/php-fpm.d/zz-moodle.conf && \
    echo "pm.max_children = 50" >> /usr/local/etc/php-fpm.d/zz-moodle.conf && \
    echo "pm.start_servers = 10" >> /usr/local/etc/php-fpm.d/zz-moodle.conf && \
    echo "pm.min_spare_servers = 5" >> /usr/local/etc/php-fpm.d/zz-moodle.conf && \
    echo "pm.max_spare_servers = 20" >> /usr/local/etc/php-fpm.d/zz-moodle.conf && \
    echo "pm.max_requests = 500" >> /usr/local/etc/php-fpm.d/zz-moodle.conf && \
    echo "request_terminate_timeout = 300" >> /usr/local/etc/php-fpm.d/zz-moodle.conf

# Set up cron job for Moodle
RUN echo "* * * * * www-data /usr/local/bin/php /var/www/html/admin/cli/cron.php > /dev/null" > /etc/cron.d/moodle-cron && \
    chmod 0644 /etc/cron.d/moodle-cron

# Set working directory
WORKDIR /var/www/html/public

# Set the correct permissions
RUN chown -R www-data:www-data /var/www/ && chmod -R 755 /var/www

# Copy entrypoint script to start both Apache and cron
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Expose ports 80 and 443
EXPOSE 80 443

# Use entrypoint to start cron, Nginx and PHP-FPM
ENTRYPOINT ["/entrypoint.sh"]