#!/bin/bash

echo "🚀 Deploy para Produção - Integrar"
echo "=================================="

# Configurações
CONTAINER_NAME="integrar_app_1"
BACKUP_DIR="/backup"
PROJECT_DIR="/ico/fabiano/ft/integrar"

# Verificar se estamos no diretório correto
if [ ! -f "docker-compose.yml" ]; then
    echo "❌ Erro: Execute este script no diretório do projeto"
    exit 1
fi

# Backup automático antes do deploy
echo "📦 Criando backup automático..."
./backup-automatico.sh

# Parar containers
echo "🛑 Parando containers..."
docker-compose down

# Reconstruir imagem com novas dependências Python
echo "🔨 Reconstruindo imagem Docker com dependências Python..."
docker-compose build --no-cache

# Iniciar containers
echo "▶️ Iniciando containers..."
docker-compose up -d

# Aguardar container estar pronto
echo "⏳ Aguardando container estar pronto..."
sleep 10

# Instalar dependências Python (fallback se não estiverem no Dockerfile)
echo "🐍 Verificando dependências Python..."
docker-compose exec -T app bash -c "
    if ! python3 -c 'import pandas, openpyxl, xlrd, numpy' 2>/dev/null; then
        echo 'Instalando dependências Python via apt...'
        apt update && apt install -y python3-pandas python3-openpyxl python3-xlrd python3-numpy
    else
        echo '✅ Dependências Python já estão instaladas'
    fi
"

# Limpar caches
echo "🧹 Limpando caches..."
docker-compose exec -T app php artisan view:clear
docker-compose exec -T app php artisan config:clear
docker-compose exec -T app php artisan route:clear

# Verificar status
echo "🔍 Verificando status dos serviços..."
docker-compose ps

# Testar conversor Python
echo "🧪 Testando conversor Python..."
docker-compose exec -T app python3 scripts/conversor_laravel.py --help

echo "✅ Deploy concluído com sucesso!"
echo "🌐 Acesse: http://localhost:8081"
echo "📊 Conversor Python: ✅ Ativo" 