FROM php:8.2-apache

# PostgreSQL ড্রাইভার ইনস্টল করা
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# অ্যাপাচি রিরাইট মডিউল এনাবল করা
RUN a2enmod rewrite

# ওয়ার্কিং ডিরেক্টরি সেট করা
WORKDIR /var/www/html

# সব ফাইল ডকার কন্টেইনারের রুট ফোল্ডারে কপি করা
COPY . /var/www/html/

# অ্যাপাচির ডিফল্ট ডকুমেন্ট রুট পরিবর্তন করে /var/www/html নিশ্চিত করা
RUN sed -ri -e 's!/var/www/html!/var/www/html!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/html!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# সঠিক পারমিশন সেট করা
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80