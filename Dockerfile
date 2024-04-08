# Utiliza la imagen base de PHP con Apache
FROM php:8.2-apache

# Instala las dependencias necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip

# Establece el directorio de trabajo en /var/www/html
WORKDIR /var/www/html

# Copia los archivos del proyecto de Laravel al contenedor
COPY . .

# Instala las dependencias de Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

# Copia el archivo de configuración .env
COPY .env.example .env

# Genera la clave de aplicación de Laravel
RUN php artisan key:generate

# Ejecuta las migraciones de la base de datos
RUN php artisan migrate

# Instala y configura Laravel Passport
RUN php artisan passport:install
RUN php artisan passport:keys

# Cambia los permisos adecuadamente (solo si es necesario)
# RUN chown -R www-data:www-data storage
# RUN chown -R www-data:www-data bootstrap/cache

# Expone el puerto 80 para que el servidor web de Apache pueda ser accedido
EXPOSE 80

# Comando predeterminado que ejecutará Apache
CMD ["apache2-foreground"]
