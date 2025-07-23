#!/bin/bash

# Script de backup automático para o projeto Integrar
# Executar diariamente via cron

DATA=$(date +%Y%m%d_%H%M%S)
DIRETORIO_BACKUP="/ico/fabiano/ft/integrar/backups"
ARQUIVO_BACKUP="$DIRETORIO_BACKUP/backup-integrar-$DATA.sql"

# Criar diretório de backup se não existir
mkdir -p "$DIRETORIO_BACKUP"

# Fazer backup do banco
echo "Iniciando backup automático em $(date)"
docker-compose exec -T db mysqldump -u root -proot integrar_dalongaro > "$ARQUIVO_BACKUP"

# Verificar se o backup foi bem-sucedido
if [ $? -eq 0 ]; then
    echo "✅ Backup criado com sucesso: $ARQUIVO_BACKUP"
    
    # Manter apenas os últimos 7 backups
    cd "$DIRETORIO_BACKUP"
    ls -t backup-integrar-*.sql | tail -n +8 | xargs -r rm
    
    echo "🗑️ Backups antigos removidos (mantidos últimos 7)"
else
    echo "❌ Erro ao criar backup!"
    exit 1
fi

echo "Backup concluído em $(date)" 