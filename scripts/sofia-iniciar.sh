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
echo "Accesos Locales:"
echo "  Web:      http://localhost:8000"
echo "  API:      http://localhost:8001"
echo "  Grafana:  http://localhost:3000 (admin/admin)"
echo "  Prisma:   http://localhost:5556"

echo ""
echo "Accesos Públicos (Túnel):"
echo "  Frontend: https://sofiasolutions.loca.lt"
echo "  Backend:  https://sofiasolutions-api.loca.lt"
echo ""
echo "Nota: Si el túnel pide contraseña, usa tu IP pública (ver en ipify.org)"

