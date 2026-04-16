#!/usr/bin/env sh
set -eu

MODE="${1:-secure}"
REBUILD="${2:-}"

case "$MODE" in
  secure|vulnerable) ;;
  *)
    echo "Uso: ./scripts/start-stack.sh [secure|vulnerable] [--build]"
    exit 1
    ;;
esac

echo "== Sofia Solutions stack =="
echo "Mode: $MODE"

export APP_MODE="$MODE"

if [ "$REBUILD" = "--build" ]; then
  docker compose down
  docker compose up -d --build
else
  docker compose up -d
fi

echo ""
echo "Accesos:"
echo "  Web:      http://localhost:8000"
echo "  API:      http://localhost:8001"
echo "  Swagger:  http://localhost:8001/docs"
echo "  Grafana:  http://localhost:3000"
echo ""
echo "Credenciales Grafana:"
echo "  usuario: admin"
echo "  clave:   admin"

