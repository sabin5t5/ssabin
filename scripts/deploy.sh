#!/bin/bash

set -e

echo "Starting deployment..."

cd /home/ec2-user/ssabin

echo "Pulling latest code..."
git pull origin main

echo "Building containers..."
docker compose build

echo "Restarting containers..."
docker compose up -d

echo "Running Laravel commands..."

docker exec ssabin-app php artisan migrate --force

docker exec ssabin-app php artisan optimize

echo "Cleaning unused images..."
docker image prune -f

echo "Deployment completed!"
