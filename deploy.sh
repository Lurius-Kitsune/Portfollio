#!/bin/sh

set -e # exit on error

SQL_FILE="./sql/update.sql"

# --------------------------------------------------
# Chargement du .env
# --------------------------------------------------

if [ ! -f ".env" ]; then
    echo "ERREUR : fichier .env introuvable dans $PROJECT_DIR"
    exit 1
fi

echo "==> Chargement du fichier .env"

set -a
. ./.env
set +a

# Vérification des variables nécessaires
if [ -z "$POSTGRES_USER" ]; then
    echo "ERREUR : POSTGRES_USER n'est pas défini dans .env"
    exit 1
fi

if [ -z "$POSTGRES_DB" ]; then
    echo "ERREUR : POSTGRES_DB n'est pas défini dans .env"
    exit 1
fi

# --------------------------------------------------
# Mise à jour du code
# --------------------------------------------------

echo "==> Git pull"

git pull origin main

# --------------------------------------------------
# Docker
# --------------------------------------------------

echo "==> Pull des nouvelles images"

docker compose -f docker-compose.prod.yml pull

echo "==> Démarrage des conteneurs"

docker compose -f docker-compose.prod.yml up -d --remove-orphans

# --------------------------------------------------
# Attente PostgreSQL
# --------------------------------------------------

echo "==> Attente du démarrage de PostgreSQL..."

sleep 5

# --------------------------------------------------
# Vérification du fichier SQL
# --------------------------------------------------

if [ ! -f "$SQL_FILE" ]; then
    echo "ERREUR : fichier SQL introuvable : $SQL_FILE"
    exit 1
fi

# --------------------------------------------------
# Exécution du SQL
# --------------------------------------------------

echo "==> Exécution de $SQL_FILE"

docker compose -f "docker-compose.prod.yml" exec -T "database" \
    psql \
    -U "$POSTGRES_USER" \
    -d "$POSTGRES_DB" \
    < "$SQL_FILE"

echo "==> Déploiement terminé avec succès !"

echo "=> Build Tailswind with class on DB"

docker compose -f "docker-compose.prod.yml" exec -T "php" \
    php bin/console app:tailwind:extract-classes 

docker compose -f "docker-compose.prod.yml" exec -T "php" \
    npm run build