#!/usr/bin/env sh
set -eu

MODE="${1:-vulnerable}"

case "$MODE" in
  vulnerable)
    npm run attack:sqli:vuln
    npm run attack:xss:vuln
    npm run attack:traversal:vuln
    npm run attack:payment:vuln
    npm run attack:bruteforce:vuln
    npm run services:matrix:vuln
    ;;
  secure)
    npm run attack:sqli:secure
    npm run attack:xss:secure
    npm run attack:traversal:secure
    npm run attack:payment:secure
    npm run attack:bruteforce:secure
    npm run services:matrix:secure
    ;;
  *)
    echo "Uso: ./scripts/run-attacks.sh [vulnerable|secure]"
    exit 1
    ;;
esac

