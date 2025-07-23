#!/bin/bash
# Script para importar backup no container MySQL do Docker

ARQUIVO_BACKUP=${1:-backup-integrar.sql}
BANCO=${2:-integrar_dalongaro}
USUARIO=laravel
SENHA=secret
CONTAINER=integrar-db

echo "Criando banco de dados $BANCO (caso não exista)..."
docker exec -i $CONTAINER mysql -u root -proot -e "CREATE DATABASE IF NOT EXISTS $BANCO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci; GRANT ALL PRIVILEGES ON $BANCO.* TO '$USUARIO'@'%'; FLUSH PRIVILEGES;"

echo "Importando backup $ARQUIVO_BACKUP para o banco $BANCO..."
docker exec -i $CONTAINER mysql -u $USUARIO -p$SENHA $BANCO < $ARQUIVO_BACKUP

echo "Importação concluída!" 