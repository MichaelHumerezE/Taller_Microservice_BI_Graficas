FROM php:7.4-apache

# Instalamos dependencias requeridas por Laravel y extensiones de PHP necesarias
RUN apt-get update \
    && apt-get install -y \
        git \
        zip \
        unzip \
        libpq-dev \
        libzip-dev \
        libgd-dev \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        bcmath \
        zip \
        gd

# Habilitamos el módulo de Apache necesario para Laravel
RUN a2enmod rewrite

# Copiamos el código fuente de Laravel a la carpeta de trabajo en el contenedor
COPY . /var/www/html

# Configuramos los permisos de almacenamiento en caché y registro de Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Agregamos la configuración al archivo apache2.conf
RUN echo "<Directory /var/www/html/>\n\
  Options Indexes FollowSymLinks\n\
  AllowOverride all\n\
  Require all granted\n\
</Directory>" >> /etc/apache2/apache2.conf

# Instalamos las dependencias de Composer
WORKDIR /var/www/html
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-interaction --optimize-autoloader



# Configuramos las variables de entorno para la conexión a la base de datos PostgreSQL
# ENV DB_CONNECTION=pgsql
# ENV DB_HOST=35.198.4.237
# ENV DB_PORT=5432
# ENV DB_DATABASE=laravel
# ENV DB_USERNAME=postgres
# ENV DB_PASSWORD=0000

# Ejecutamos las migraciones y generamos la clave de la aplicación
RUN php artisan key:generate
#RUN php artisan migrate:fresh --seed
RUN php artisan config:clear
RUN php artisan cache:clear
# RUN php artisan config:cache
RUN php artisan storage:link

# Exponemos el puerto 8000 para acceder al servidor de desarrollo de Laravel
EXPOSE 8000

# Ejecutamos el servidor de desarrollo con `php artisan serve`
CMD ["php","artisan","serve","--host","0.0.0.0","--port","8000"]