#!/bin/bash

# Script de Deploy para Produção - Sistema Integrar
# Este script automatiza todo o processo de deploy

set -e  # Para o script se houver erro

echo "🚀 Iniciando deploy para produção..."
echo "=================================="

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Função para log colorido
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

# Verificar se o Docker está rodando
check_docker() {
    log_info "Verificando se o Docker está rodando..."
    if ! docker info > /dev/null 2>&1; then
        log_error "Docker não está rodando. Inicie o Docker e tente novamente."
        exit 1
    fi
    log_success "Docker está rodando"
}

# Backup do banco de dados
backup_database() {
    log_info "Fazendo backup do banco de dados..."
    TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
    BACKUP_FILE="backup-integrar-${TIMESTAMP}.sql"
    
    docker-compose exec -T db mysqldump -u root -proot integrar > "backups/${BACKUP_FILE}"
    
    if [ $? -eq 0 ]; then
        log_success "Backup criado: backups/${BACKUP_FILE}"
    else
        log_error "Erro ao criar backup do banco de dados"
        exit 1
    fi
}

# Instalar/atualizar dependências do Composer
install_composer_dependencies() {
    log_info "Instalando dependências do Composer..."
    docker-compose exec app composer install --no-dev --optimize-autoloader
    
    if [ $? -eq 0 ]; then
        log_success "Dependências do Composer instaladas"
    else
        log_error "Erro ao instalar dependências do Composer"
        exit 1
    fi
}

# Executar migrations
run_migrations() {
    log_info "Executando migrations..."
    docker-compose exec app php artisan migrate --force
    
    if [ $? -eq 0 ]; then
        log_success "Migrations executadas com sucesso"
    else
        log_error "Erro ao executar migrations"
        exit 1
    fi
}

# Limpar caches
clear_caches() {
    log_info "Limpando caches..."
    
    docker-compose exec app php artisan config:clear
    docker-compose exec app php artisan route:clear
    docker-compose exec app php artisan view:clear
    docker-compose exec app php artisan cache:clear
    
    log_success "Caches limpos"
}

# Otimizar para produção
optimize_production() {
    log_info "Otimizando para produção..."
    
    docker-compose exec app php artisan config:cache
    docker-compose exec app php artisan route:cache
    docker-compose exec app php artisan view:cache
    
    log_success "Otimizações aplicadas"
}

# Compilar assets (se necessário)
compile_assets() {
    log_info "Compilando assets..."
    
    # Verificar se existe package.json
    if [ -f "package.json" ]; then
        docker-compose exec app npm install
        docker-compose exec app npm run build
        log_success "Assets compilados"
    else
        log_warning "package.json não encontrado, pulando compilação de assets"
    fi
}

# Verificar permissões
fix_permissions() {
    log_info "Ajustando permissões..."
    
    # Ajustar permissões para storage e bootstrap/cache
    docker-compose exec app chmod -R 775 storage
    docker-compose exec app chmod -R 775 bootstrap/cache
    
    log_success "Permissões ajustadas"
}

# Verificar saúde da aplicação
health_check() {
    log_info "Verificando saúde da aplicação..."
    
    # Verificar se a aplicação está respondendo
    if curl -f http://localhost > /dev/null 2>&1; then
        log_success "Aplicação está respondendo"
    else
        log_warning "Aplicação pode não estar respondendo corretamente"
    fi
}

# Verificar logs de erro
check_error_logs() {
    log_info "Verificando logs de erro..."
    
    # Verificar se há erros recentes nos logs
    ERROR_COUNT=$(docker-compose exec app tail -n 100 storage/logs/laravel.log | grep -c "ERROR\|FATAL" || true)
    
    if [ "$ERROR_COUNT" -gt 0 ]; then
        log_warning "Encontrados $ERROR_COUNT erros nos logs recentes"
        docker-compose exec app tail -n 20 storage/logs/laravel.log | grep "ERROR\|FATAL" || true
    else
        log_success "Nenhum erro encontrado nos logs recentes"
    fi
}

# Função principal
main() {
    echo "📋 Checklist de Deploy:"
    echo "1. ✅ Verificar Docker"
    echo "2. 🔄 Backup do banco"
    echo "3. 📦 Instalar dependências"
    echo "4. 🗄️ Executar migrations"
    echo "5. 🧹 Limpar caches"
    echo "6. ⚡ Otimizar produção"
    echo "7. 🎨 Compilar assets"
    echo "8. 🔐 Ajustar permissões"
    echo "9. 🏥 Verificar saúde"
    echo "10. 📝 Verificar logs"
    echo ""
    
    # Executar etapas
    check_docker
    backup_database
    install_composer_dependencies
    run_migrations
    clear_caches
    optimize_production
    compile_assets
    fix_permissions
    health_check
    check_error_logs
    
    echo ""
    echo "🎉 Deploy concluído com sucesso!"
    echo "=================================="
    echo "A aplicação está pronta para uso em produção."
    echo ""
    echo "📊 Resumo:"
    echo "- Backup criado em backups/"
    echo "- Dependências atualizadas"
    echo "- Caches otimizados"
    echo "- Permissões ajustadas"
    echo ""
    echo "🔗 Acesse: http://localhost"
}

# Executar função principal
main "$@" 