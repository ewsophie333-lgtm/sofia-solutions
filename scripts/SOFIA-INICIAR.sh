#!/usr/bin/env sh
set -eu

MODE="${1:-secure}"
REBUILD="${2:-}"

case "$MODE" in
  secure|vulnerable) ;;
  *)
    echo "Uso: ./scripts/levantar-docker.sh [secure|vulnerable] [--build]"
    exit 1
    ;;
esac

if ! docker info > /dev/null 2>&1; then
  echo "Error: Docker no está en ejecución. Por favor, arranca Docker Desktop o el demonio de Docker."
  exit 1
fi

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
echo "  Grafana:  http://localhost:3000/grafana/ (admin/admin)"
echo "  n8n:      http://localhost:5678"

echo ""
echo "Accesos Públicos (Túnel Serveo):"
echo "  Frontend: https://sofia-solutions.serveo.net"
echo "  n8n:      https://sofia-n8n.serveo.net"
echo ""
echo "Nota: Si estás en un instituto y el túnel no funciona, usa la redirección de puertos de Codespaces."
