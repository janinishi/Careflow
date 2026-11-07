FROM php:8.2-apache

# PostgreSQL ড্রাইভার এবং প্রয়োজনীয় লাইব্রেরি ইনস্টল করা
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# বর্তমান ফোল্ডারের সব ফাইল অ্যাপাচির ডিরেক্টরিতে কপি করা
COPY . /var/www/html/

# সঠিক পারমিশন সেট করা
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80