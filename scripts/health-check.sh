#!/bin/bash

set -e

HEALTH_URL="${HEALTH_URL:-http://localhost/health}"
TIMEOUT="${TIMEOUT:-10}"
MAX_RETRIES="${MAX_RETRIES:-3}"
RETRY_DELAY="${RETRY_DELAY:-5}"

echo "Running health check on ${HEALTH_URL}..."

check_health() {
    local response
    local http_code

    response=$(curl -s -o /dev/null -w "%{http_code}" --max-time "$TIMEOUT" "$HEALTH_URL" 2>/dev/null)
    http_code=$?

    if [ $http_code -eq 0 ]; then
        return 0
    else
        return 1
    fi
}

for i in $(seq 1 "$MAX_RETRIES"); do
    echo "Attempt ${i}/${MAX_RETRIES}..."

    if check_health; then
        echo "Health check passed!"
        exit 0
    fi

    if [ "$i" -lt "$MAX_RETRIES" ]; then
        echo "Retrying in ${RETRY_DELAY} seconds..."
        sleep "$RETRY_DELAY"
    fi
done

echo "Health check failed after ${MAX_RETRIES} attempts!"
exit 1
