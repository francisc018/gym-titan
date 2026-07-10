#!/bin/bash
# ============================================================
# FORJA GYM — entrypoint.sh
# Render inyecta el puerto real a usar en la variable $PORT.
# Apache por defecto escucha el 80: acá se lo reemplazamos antes
# de arrancar, tanto en ports.conf como en el vhost por defecto.
# ============================================================
set -e

PUERTO="${PORT:-10000}"

sed -i "s/80/${PUERTO}/g" /etc/apache2/ports.conf
sed -i "s/80/${PUERTO}/g" /etc/apache2/sites-enabled/000-default.conf

exec apache2-foreground
