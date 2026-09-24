FROM php:8.3-apache

# Instalar dependencias del sistema y Node.js (para compilar Tailwind/Vite)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Limpiar caché de apt
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP requeridas por Laravel
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd zip

# Habilitar el módulo rewrite de Apache (necesario para las URLs amigables de Laravel)
RUN a2enmod rewrite

# Configurar el directorio de trabajo
WORKDIR /var/www/html

# Copiar los archivos del proyecto al contenedor
COPY . /var/www/html

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalar dependencias de PHP (Laravel)
RUN composer install --no-dev --optimize-autoloader

# Instalar dependencias de Node.js y compilar el frontend (Vite/Tailwind)
RUN npm install && npm run build

# Crear archivo de base de datos SQLite vacío (ya que no se sube a GitHub)
RUN touch /var/www/html/database/database.sqlite

# Modificar Apache para que apunte a la carpeta "public" de Laravel
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Modificar Apache para que escuche en el puerto que Render asigne dinámicamente ($PORT)
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Dar permisos a las carpetas que Laravel necesita modificar (incluyendo la base de datos)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Puerto por defecto (Render inyectará el suyo)
ENV PORT=8000
EXPOSE ${PORT}

# Iniciar Apache
CMD ["apache2-foreground"]
