#!/bin/bash
set -e

# Ensure moodledata directory exists and has correct permissions
echo "Setting up moodledata directory permissions..."
mkdir -p /var/www/moodledata
chown -R www-data:www-data /var/www/moodledata
chmod -R 775 /var/www/moodledata

# Ensure html directory has correct permissions
chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html

# Start cron service
service cron start

# Check if SSL certificates exist and use appropriate Nginx configuration
if [ -d "/etc/letsencrypt/live" ] && [ "$(ls -A /etc/letsencrypt/live 2>/dev/null)" ]; then
    echo "SSL certificates found, using SSL configuration..."
    NGINX_CONFIG="/etc/nginx/nginx-ssl.conf"
else
    echo "No SSL certificates found, running in HTTP mode only"
    NGINX_CONFIG="/etc/nginx/nginx.conf"
fi

# Start PHP-FPM in the background
php-fpm -D

# Start Nginx in the foreground with the selected configuration
exec nginx -c $NGINX_CONFIG -g 'daemon off;'
