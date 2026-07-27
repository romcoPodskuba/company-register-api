#!/bin/bash
set -e

echo "⏳ Waiting for database to be ready..."
/usr/local/bin/wait-for-it.sh database:3306 --timeout=60 --strict -- echo "✅ Database is up"

exec "$@"
