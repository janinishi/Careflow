FROM php:8.1-apache

# PostgreSQL ড্রাইভারের জন্য প্রয়োজনীয় ডিপেন্ডেন্সি এবং এক্সটেনশন ইনস্টল করা
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# অ্যাপ্লিকেশনের ফাইলগুলো কন্টেইনারে কপি করা
COPY . /var/www/html/

# সঠিক পারমিশন সেট করা
RUN chown -R www-data:www-data /var/www/html