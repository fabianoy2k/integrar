FROM laravelsail/php82-composer

# Instalar e habilitar a extensão pdo_mysql
RUN docker-php-ext-install pdo_mysql && \
    docker-php-ext-enable pdo_mysql

# Instalar e habilitar a extensão pdo_mysql
RUN apt-get update && apt-get install -y python3 python3-pip python3-pandas python3-pypdf2

# Instalar Node.js e npm (LTS)
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - \
    && apt-get install -y nodejs

# Configurar PHP para processamento de arquivos grandes
RUN echo "max_execution_time = 300" >> /usr/local/etc/php/conf.d/custom.ini && \
    echo "memory_limit = 512M" >> /usr/local/etc/php/conf.d/custom.ini && \
    echo "upload_max_filesize = 50M" >> /usr/local/etc/php/conf.d/custom.ini && \
    echo "post_max_size = 50M" >> /usr/local/etc/php/conf.d/custom.ini

# Definir diretório de trabalho
WORKDIR /var/www/html

# Expor porta 8000
EXPOSE 8000

# Comando padrão
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"] 