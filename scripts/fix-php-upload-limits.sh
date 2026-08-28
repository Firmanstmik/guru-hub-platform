#!/usr/bin/env bash
# Apply PHP upload limits for GuruHub production (run on VPS as root).
set -euo pipefail

PHP_INI="/etc/php/8.4/fpm/php.ini"

if [[ ! -f "$PHP_INI" ]]; then
  PHP_INI="$(ls /etc/php/*/fpm/php.ini 2>/dev/null | head -1)"
fi

if [[ ! -f "$PHP_INI" ]]; then
  echo "PHP-FPM php.ini not found" >&2
  exit 1
fi

sed -i 's/^upload_max_filesize = .*/upload_max_filesize = 12M/' "$PHP_INI"
sed -i 's/^post_max_size = .*/post_max_size = 12M/' "$PHP_INI"

mkdir -p /var/www/guruhub/storage/app/public/courses/covers
chown -R www-data:www-data /var/www/guruhub/storage/app/public/courses

systemctl restart php8.4-fpm 2>/dev/null || systemctl restart php-fpm

php -i | grep -E 'upload_max_filesize|post_max_size'
echo "PHP upload limits updated."
