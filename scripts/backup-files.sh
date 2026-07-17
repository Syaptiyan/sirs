#!/bin/bash

set -e

BACKUP_DIR="${BACKUP_DIR:-./backups/files}"
SOURCE_DIR="${SOURCE_DIR:-.}"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="${BACKUP_DIR}/files_${TIMESTAMP}.tar.gz"
RETENTION_DAYS="${RETENTION_DAYS:-30}"

mkdir -p "$BACKUP_DIR"

echo "Starting files backup from ${SOURCE_DIR}..."

tar -czf "$BACKUP_FILE" \
    --exclude='vendor' \
    --exclude='node_modules' \
    --exclude='.git' \
    --exclude='backups' \
    --exclude='writable/cache' \
    --exclude='writable/logs' \
    --exclude='writable/session' \
    -C "$SOURCE_DIR" .

if [ $? -eq 0 ]; then
    echo "Backup completed: ${BACKUP_FILE}"
    echo "Size: $(du -h "$BACKUP_FILE" | cut -f1)"
else
    echo "Backup failed!"
    exit 1
fi

echo "Cleaning backups older than ${RETENTION_DAYS} days..."
find "$BACKUP_DIR" -name "files_*.tar.gz" -mtime +"$RETENTION_DAYS" -delete

echo "Files backup process finished."
