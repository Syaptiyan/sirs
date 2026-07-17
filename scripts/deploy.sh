#!/bin/bash

set -e

ENVIRONMENT="${1:-production}"
COMPOSE_FILE="docker-compose.yml"

echo "========================================="
echo "Deploying SIRS to ${ENVIRONMENT}"
echo "========================================="

echo "[1/6] Pulling latest changes..."
git pull origin main

echo "[2/6] Building containers..."
docker compose -f "$COMPOSE_FILE" build --no-cache

echo "[3/6] Stopping existing containers..."
docker compose -f "$COMPOSE_FILE" down

echo "[4/6] Starting containers..."
docker compose -f "$COMPOSE_FILE" up -d

echo "[5/6] Running migrations..."
docker compose -f "$COMPOSE_FILE" exec -T app php spark migrate --all

echo "[6/6] Clearing caches..."
docker compose -f "$COMPOSE_FILE" exec -T app php spark cache:clear

echo "========================================="
echo "Running health check..."
echo "========================================="

sleep 5
bash scripts/health-check.sh

echo "========================================="
echo "Deployment completed successfully!"
echo "========================================="
