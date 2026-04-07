#!/bin/sh
set -eu

echo "Waiting for PostgreSQL..."
until npx prisma db push >/dev/null 2>&1; do
  sleep 2
done

echo "Applying schema and seed..."
npx prisma db push
npm run prisma:seed

echo "Starting backend..."
npm run start
