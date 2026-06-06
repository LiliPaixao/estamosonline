# Estamos Online - Bootstrap do Projeto

Este é o repositório base para o desenvolvimento da aplicação. O projeto utiliza uma infraestrutura Docker customizada (sem a dependência do Laravel Sail) contendo PHP-FPM, Nginx, MySQL e Node.js de forma totalmente isolada.

## 📋 Pré-requisitos
A pessoa que for testar ou desenvolver este código precisa ter apenas o **Docker** e o **Docker Compose** instalados na máquina física. Não há necessidade de possuir PHP, Composer, MySQL ou Node instalados localmente.

---

## 🚀 Como Rodar o Projeto do Zero

Siga os passos abaixo no terminal para colocar a aplicação em funcionamento:

### 1. Clonar o repositório e acessar a pasta
```bash
git clone <URL_DO_SEU_REPOSITORIO_PRIVADO>
cd estamosonline
```

### 2. Configurar o arquivo de ambiente
Crie o arquivo `.env` a partir do modelo base:
```bash
cp .env.example .env
```
Abra o seu arquivo `.env` e verifique se o bloco de conexão com o banco de dados está apontando internamente para o container Docker (usando a porta padrão interna `3306` e o host `db`):
```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=estamosonline
DB_USERNAME=root
DB_PASSWORD=xxxx # Insira a senha definida no docker-compose.yml
```

### 3. Subir e construir os containers
Monte as imagens locais e inicialize os serviços (Nginx, PHP, MySQL e Node) em segundo plano:
```bash
docker compose up -d --build
```

### 4. Instalar as dependências do PHP e gerar as chaves
Rode a instalação do Composer de dentro do ambiente e gere a chave criptográfica do Laravel:
```bash
docker compose exec app composer install
docker compose exec app php artisan key:generate
```

### 5. Executar as migrações do banco de dados
Crie a estrutura de tabelas do Laravel e do Filament no MySQL:
```bash
docker compose exec app php artisan migrate
```

---

## 🔐 Como Acessar a Área de Admin (Filament)

Como o banco de dados inicial estará completamente vazio, você deve criar o primeiro usuário administrador através da linha de comando do container:

1. No seu terminal, execute:
   ```bash
   docker compose exec app php artisan make:filament-user
   ```
2. Responda às perguntas fornecendo seu **Nome**, **E-mail** e **Senha**.
3. Acesse o painel pelo seu navegador de internet através da URL:
   👉 **`http://localhost/admin`**
4. Utilize as credenciais recém-criadas para realizar o login.

---

## 💡 Comandos Úteis do Docker para o Dia a Dia

* **Iniciar os serviços:** `docker compose up -d`
* **Pausar o ambiente:** `docker compose stop`
* **Derrubar e destruir os containers:** `docker compose down`
* **Ver os logs do sistema em tempo real:** `docker compose logs -f`
* **Acessar o terminal interno do PHP:** `docker compose exec app bash`

### 🧹 Limpar Cache (use quando alterar `.env` ou views não atualizarem)

```bash
docker compose exec app php artisan config:clear
docker compose exec app php artisan cache:clear
docker compose exec app php artisan route:clear
docker compose exec app php artisan view:clear
```
