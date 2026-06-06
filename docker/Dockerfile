# 1. Escolha da imagem oficial do PHP com FPM (FastCGI Process Manager)
FROM php:8.4-fpm-bookworm

# 2. Define o diretório onde o código do Laravel vai morar dentro do container
WORKDIR /var/www

# 3. Atualiza o sistema e instala as dependências nativas do Linux que o PHP precisa para compilar as extensões
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl

# 4. Limpa o cache do gerenciador de pacotes para manter a imagem o mais leve possível
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 5. Configura e instala as extensões do PHP obrigatórias para o Filament e Laravel
# - gd: essencial para o Filament manipular uploads de fotos e avatars
# - intl: necessária para formatação de moedas e datas locais no painel
# - pdo_mysql: para conectar ao banco MySQL que configuramos no compose
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo_mysql zip exif pcntl gd intl

# 6. Copia a versão mais recente e oficial do Composer direto para dentro da nossa imagem
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 7. Expõe a porta 9000 (porta padrão que o PHP-FPM usa para ouvir o Nginx)
EXPOSE 9000

# 8. Comando que mantém o container vivo e processando as requisições PHP
CMD ["php-fpm"]
