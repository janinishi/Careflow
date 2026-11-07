FROM php:8.2-apache

# PostgreSQL ড্রাইভার এবং লাইব্রেরি ইনস্টল করা
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# প্রজেক্টের সব ফাইল অ্যাপাচির পাবলিক ফোল্ডারে কপি করা
COPY . /var/www/html/

# সঠিক পারমিশন দেওয়া
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80