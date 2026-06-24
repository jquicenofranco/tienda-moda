# =============================================================
#  Tienda Moda — Imagen PHP/Apache para desarrollo y producción
# =============================================================
FROM php:8.2-apache

# Argumentos (pueden venir del compose)
ARG APP_ENV=production

# 1) Extensiones del sistema + librerías necesarias
RUN apt-get update && apt-get install -y --no-install-recommends \
        libzip-dev \
        unzip \
        git \
        curl \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libicu-dev \
        libonig-dev \
        default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

# 2) Extensiones PHP requeridas por el proyecto:
#    - pdo_mysql   -> conexión a MySQL
#    - mbstring    -> requerido por picqer/php-barcode-generator
#    - gd          -> requerido por FPDF / generación de imágenes
#    - zip         -> operaciones con archivos
#    - intl        -> i18n
#    - opcache     -> rendimiento
RUN docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        gd \
        zip \
        intl \
        opcache

# 3) Composer (multi-stage para mantener tamaño reducido)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 4) Habilitar mod_rewrite de Apache (necesario para .htaccess)
RUN a2enmod rewrite headers

# 5) Configuración Apache -> DocumentRoot en /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Permitir .htaccess (AllowOverride All) en el DocumentRoot
RUN echo '<Directory ${APACHE_DOCUMENT_ROOT}>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/app-overrides.conf \
    && a2enconf app-overrides

# 6) Copiar código y permisos
WORKDIR /var/www/html
COPY . /var/www/html

# 7) Instalar dependencias de Composer
#    --no-dev en producción para reducir tamaño
#    --optimize-autoloader para mejor rendimiento
RUN if [ "$APP_ENV" = "production" ]; then \
        composer install --no-dev --no-interaction --no-progress --optimize-autoloader; \
    else \
        composer install --no-interaction --no-progress; \
    fi

# 8) Crear carpetas con permisos de escritura (sesiones, uploads, cache)
RUN mkdir -p \
        /var/www/html/public/uploads \
        /var/www/html/uploads \
        /var/www/html/storage \
    && chown -R www-data:www-data \
        /var/www/html \
    && chmod -R 755 /var/www/html

# 9) PHP.ini optimizado
COPY docker/php.ini /usr/local/etc/php/conf.d/app.ini

# 10) Script de entrada: espera a la DB y luego arranca Apache
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["apache2-foreground"]