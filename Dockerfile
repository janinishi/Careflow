FROM php:8.2-apache

# PostgreSQL ড্রাইভার এবং প্রয়োজনীয় লাইব্রেরি ইনস্টল করা
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# অ্যাপাচি রিরাইট মডিউল এনাবল করা (রাউটিংয়ের জন্য জরুরি)
RUN a2enmod rewrite

# সমস্ত ফাইল অ্যাপাচির ডিফল্ট ফোল্ডারে কপি করা
COPY . /var/www/html/

# সঠিক পারমিশন সেট করা
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80