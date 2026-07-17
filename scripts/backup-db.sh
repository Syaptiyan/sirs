#!/bin/bash

set -e

BACKUP_DIR="${BACKUP_DIR:-./backups/database}"
DB_HOST="${DB_HOST:-postgres}"
DB_PORT="${DB_PORT:-5432}"
DB_NAME="${DB_NAME:-${DB_DATABASE:-sirs}}"
DB_USER="${DB_USERNAME:-postgres}"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="${BACKUP_DIR}/${DB_NAME}_${TIMESTAMP}.sql.gz"
RETENTION_DAYS="${RETENTION_DAYS:-30}"

mkdir -p "$BACKUP_DIR"

echo "Starting database backup for ${DB_NAME}..."

PGPASSWORD="${DB_PASSWORD:-secret}" pg_dump \
    -h "$DB_HOST" \
    -p "$DB_PORT" \
    -U "$DB_USER" \
    -d "$DB_NAME" \
    --no-owner \
    --no-privileges \
    -F c \
    | gzip > "$BACKUP_FILE"

if [ $? -eq 0 ]; then
    echo "Backup completed: ${BACKUP_FILE}"
    echo "Size: $(du -h "$BACKUP_FILE" | cut -f1)"
else
    echo "Backup failed!"
    exit 1
fi

echo "Cleaning backups older than ${RETENTION_DAYS} days..."
find "$BACKUP_DIR" -name "*.sql.gz" -mtime +"$RETENTION_DAYS" -delete

echo "Database backup process finished."
