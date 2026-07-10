# ============================================================
# FORJA GYM — Dockerfile para Render
#
# Render no soporta PHP de forma nativa (solo Node, Python, Go,
# Ruby, Rust, Elixir), así que para correr un proyecto PHP ahí
# hace falta un Dockerfile. Este arma un contenedor con Apache +
# PHP 8.2 + el driver de PostgreSQL (pdo_pgsql), sirve el
# proyecto completo, y ajusta el puerto al que Render exija
# ($PORT), ya que Render nunca usa el 80 fijo.
# ============================================================

FROM php:8.2-apache

# Driver de PostgreSQL para PDO (para MySQL en XAMPP/InfinityFree
# ya viene incluido pdo_mysql en la mayoría de imágenes, pero acá
# solo hace falta pgsql porque Render solo da Postgres gratis)
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copia todo el proyecto (todo lo que está junto a este Dockerfile)
# a la raíz web de Apache
COPY . /var/www/html/

# Apache normalmente escucha el puerto 80, pero Render asigna un
# puerto distinto en cada despliegue (variable $PORT). Este script
# de arranque reemplaza el 80 por el puerto real antes de iniciar
# Apache en primer plano.
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 10000
CMD ["/entrypoint.sh"]
