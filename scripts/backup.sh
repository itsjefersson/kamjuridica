#!/usr/bin/env bash
#
# Backup de KAMJuridica (producción, Linux)
# Hace un volcado de MySQL y copia las imágenes subidas (storage/app/public).
# Conserva los últimos N respaldos.
#
# Uso:
#   bash scripts/backup.sh
#
# Para automatizarlo diariamente a las 02:30 con cron:
#   30 2 * * * /ruta/al/proyecto/scripts/backup.sh >> /ruta/al/proyecto/storage/logs/backup.log 2>&1
#
# Ajusta las variables de abajo con los valores reales del servidor.
# No guardes este archivo con contraseñas si el repo es público; usa variables
# de entorno o un archivo .env.backup fuera del control de versiones.

set -euo pipefail

# ---- Configuración (ajustar) ----
APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"  # raíz del proyecto (backend)
DB_HOST="127.0.0.1"
DB_PORT="3306"
DB_DATABASE="kamjuridica"
DB_USERNAME="root"
DB_PASSWORD=""

# Directorio donde se guardan los respaldos
BACKUP_DIR="${APP_DIR}/storage/backups"

# Días de retención
KEEP_DAYS=14
# --------------------------------

TIMESTAMP="$(date +%F-%H%M%S)"
DEST="${BACKUP_DIR}/${TIMESTAMP}"
mkdir -p "${DEST}"

echo "[$(date '+%F %T')] Iniciando backup en ${DEST}"

# 1) Volcado de la base de datos
DUMP_FILE="${DEST}/database.sql.gz"
if [ -n "${DB_PASSWORD}" ]; then
    MYSQL_PWD="${DB_PASSWORD}" mysqldump \
        -h "${DB_HOST}" -P "${DB_PORT}" -u "${DB_USERNAME}" \
        --single-transaction --quick --routines \
        "${DB_DATABASE}" | gzip > "${DUMP_FILE}"
else
    mysqldump \
        -h "${DB_HOST}" -P "${DB_PORT}" -u "${DB_USERNAME}" \
        --single-transaction --quick --routines \
        "${DB_DATABASE}" | gzip > "${DUMP_FILE}"
fi
echo "  - Base de datos: ${DUMP_FILE}"

# 2) Imágenes y archivos subidos (storage/app/public)
STORAGE_DIR="${APP_DIR}/storage/app/public"
if [ -d "${STORAGE_DIR}" ]; then
    tar -czf "${DEST}/storage-public.tar.gz" -C "${APP_DIR}/storage/app" public
    echo "  - Imágenes: ${DEST}/storage-public.tar.gz"
fi

# 3) Copia del .env (configuración sensible, se guarda junto al backup)
if [ -f "${APP_DIR}/.env" ]; then
    cp "${APP_DIR}/.env" "${DEST}/.env"
    echo "  - Configuración: ${DEST}/.env"
fi

# 4) Rotación: eliminar respaldos más antiguos que KEEP_DAYS
find "${BACKUP_DIR}" -mindepth 1 -maxdepth 1 -type d -mtime "+${KEEP_DAYS}" -exec rm -rf {} +

echo "[$(date '+%F %T')] Backup completado. Respaldos en ${BACKUP_DIR}"
