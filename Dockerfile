# Usar la imagen oficial de PHP con Apache
FROM php:8.2-apache

# 1. Instalar dependencias del sistema (APT) para SQLite
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    sqlite3 \
 && rm -rf /var/lib/apt/lists/*

# 2. Instalar extensiones de PHP necesarias
# pdo y pdo_sqlite para la base de datos
RUN docker-php-ext-install pdo pdo_sqlite

# 3. Habilitar el módulo rewrite de Apache (para .htaccess)
RUN a2enmod rewrite

# 3. Establecer el directorio de trabajo
WORKDIR /var/www/html

# 4. Copiar todos los archivos del proyecto al contenedor
# Copiamos primero la carpeta 'public' al 'html' del contenedor
COPY public/ /var/www/html/

# 5. Configurar permisos
# El servidor Apache (www-data) necesita escribir en la carpeta 'db'
COPY db/ /var/www/html/../db/
RUN chown -R www-data:www-data /var/www/html/../db \
    && chmod -R 775 /var/www/html/../db