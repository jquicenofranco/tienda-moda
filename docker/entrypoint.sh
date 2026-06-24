#!/bin/bash
# =============================================================================
#  entrypoint.sh — Espera a la base de datos y arranca Apache
# =============================================================================
set -e

echo "===================================================="
echo "  Tienda Moda — Iniciando aplicación PHP/Apache"
echo "===================================================="

# -----------------------------------------------------------------------------
# 1) Cargar .env si existe (las variables del compose tienen prioridad)
# -----------------------------------------------------------------------------
if [ -f /var/www/html/.env ]; then
    set -a
    # shellcheck disable=SC1091
    source /var/www/html/.env
    set +a
    echo "[OK] Variables de entorno cargadas desde .env"
fi

# -----------------------------------------------------------------------------
# 2) Esperar a que MySQL esté disponible
# -----------------------------------------------------------------------------
DB_HOST="${DB_HOST:-db}"
DB_PORT="${DB_PORT:-3306}"
DB_NAME="${DB_NAME:-sistema_moda}"
DB_USER="${DB_USER:-tienda}"
DB_PASS="${DB_PASS:-tienda_pass}"

echo "[..] Esperando a MySQL en ${DB_HOST}:${DB_PORT} ..."

ATTEMPTS=0
MAX_ATTEMPTS=60
until mysqladmin ping -h "${DB_HOST}" -P "${DB_PORT}" -u "${DB_USER}" -p"${DB_PASS}" --skip-ssl --silent 2>/dev/null; do
    ATTEMPTS=$((ATTEMPTS + 1))
    if [ "${ATTEMPTS}" -ge "${MAX_ATTEMPTS}" ]; then
        echo "[ERROR] No se pudo conectar a MySQL tras ${MAX_ATTEMPTS} intentos. Abortando."
        exit 1
    fi
    echo "[..] Intento ${ATTEMPTS}/${MAX_ATTEMPTS} — MySQL no disponible aún, reintentando en 2s"
    sleep 2
done
echo "[OK] MySQL disponible en ${DB_HOST}:${DB_PORT}"

# -----------------------------------------------------------------------------
# 3) Importar SQL si la base de datos está vacía
# -----------------------------------------------------------------------------
SQL_FILE="/var/www/html/bk_basededatos.sql"
TABLE_COUNT=$(mysql -h "${DB_HOST}" -P "${DB_PORT}" -u "${DB_USER}" -p"${DB_PASS}" --skip-ssl -N -B \
    -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='${DB_NAME}';" 2>/dev/null || echo "0")

if [ "${TABLE_COUNT}" -eq 0 ] && [ -f "${SQL_FILE}" ]; then
    echo "[..] Base de datos '${DB_NAME}' vacía — importando ${SQL_FILE}"
    # El dump crea la base con CREATE DATABASE IF NOT EXISTS y luego hace USE
    if mysql -h "${DB_HOST}" -P "${DB_PORT}" -u "${DB_USER}" -p"${DB_PASS}" --skip-ssl < "${SQL_FILE}"; then
        echo "[OK] Datos importados correctamente"
    else
        echo "[WARN] Hubo advertencias importando el SQL (continuando)"
    fi
else
    echo "[OK] La base de datos '${DB_NAME}' ya tiene ${TABLE_COUNT} tablas — se omite la importación"
fi

# -----------------------------------------------------------------------------
# 4) Ajustar permisos finales (por si el volumen montó contenido)
# -----------------------------------------------------------------------------
chown -R www-data:www-data /var/www/html 2>/dev/null || true

# -----------------------------------------------------------------------------
# 5) Reenviar al comando original (apache2-foreground)
# -----------------------------------------------------------------------------
echo "[OK] Iniciando Apache..."
exec "$@"