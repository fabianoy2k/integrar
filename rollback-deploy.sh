#!/bin/bash

# Script de Rollback - Sistema Integrar
# Este script reverte mudanças em caso de problemas no deploy

set -e

echo "🔄 Iniciando rollback..."
echo "========================"

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

log_info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

log_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

log_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Verificar se foi fornecido um arquivo de backup
if [ -z "$1" ]; then
    log_error "Uso: $0 <arquivo_backup.sql>"
    echo "Exemplo: $0 backups/backup-integrar-20250723_120000.sql"
    echo ""
    echo "Backups disponíveis:"
    ls -la backups/backup-integrar-*.sql 2>/dev/null || echo "Nenhum backup encontrado"
    exit 1
fi

BACKUP_FILE="$1"

# Verificar se o arquivo de backup existe
if [ ! -f "$BACKUP_FILE" ]; then
    log_error "Arquivo de backup não encontrado: $BACKUP_FILE"
    exit 1
fi

log_info "Arquivo de backup: $BACKUP_FILE"

# Confirmar rollback
echo ""
echo "⚠️  ATENÇÃO: Este processo irá:"
echo "   - Restaurar o banco de dados do backup"
echo "   - Limpar todos os caches"
echo "   - Reverter para o estado anterior"
echo ""
read -p "Tem certeza que deseja continuar? (s/N): " -n 1 -r
echo

if [[ ! $REPLY =~ ^[Ss]$ ]]; then
    log_info "Rollback cancelado pelo usuário"
    exit 0
fi

# Fazer backup do estado atual antes do rollback
log_info "Fazendo backup do estado atual..."
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
CURRENT_BACKUP="backup-pre-rollback-${TIMESTAMP}.sql"

docker-compose exec -T db mysqldump -u root -proot integrar > "backups/${CURRENT_BACKUP}"
log_success "Backup atual criado: backups/${CURRENT_BACKUP}"

# Restaurar banco de dados
log_info "Restaurando banco de dados..."
docker-compose exec -T db mysql -u root -proot integrar < "$BACKUP_FILE"

if [ $? -eq 0 ]; then
    log_success "Banco de dados restaurado com sucesso"
else
    log_error "Erro ao restaurar banco de dados"
    exit 1
fi

# Limpar caches
log_info "Limpando caches..."
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
docker-compose app php artisan cache:clear

log_success "Caches limpos"

# Verificar saúde da aplicação
log_info "Verificando saúde da aplicação..."
sleep 5

if curl -f http://localhost > /dev/null 2>&1; then
    log_success "Aplicação está respondendo após rollback"
else
    log_warning "Aplicação pode não estar respondendo corretamente"
fi

echo ""
echo "✅ Rollback concluído!"
echo "====================="
echo "Banco de dados restaurado de: $BACKUP_FILE"
echo "Backup do estado anterior: backups/${CURRENT_BACKUP}"
echo ""
echo "🔗 Acesse: http://localhost" 