# Estamos Online

Plataforma SaaS da **Nyx Technology** voltada para MEIs e pequenas empresas. Oferece site institucional de marketing e um sistema de agendamento online que pode ser compartilhado via Instagram e WhatsApp.

## 🏗️ Arquitetura

```
http://localhost/              → Site institucional (landing page)
http://localhost/admin         → Painel administrativo (Filament)
http://localhost/agenda/{slug} → Página pública de agendamento do profissional
```

O projeto utiliza infraestrutura Docker customizada (sem Laravel Sail) com PHP-FPM, Nginx, MySQL e Node.js totalmente isolados.

---

## 📋 Pré-requisitos

Apenas **Docker** e **Docker Compose** instalados na máquina. Não é necessário ter PHP, Composer, MySQL ou Node instalados localmente.

---

## 🚀 Como Rodar do Zero

### 1. Clonar o repositório

```bash
git clone <URL_DO_SEU_REPOSITORIO_PRIVADO>
cd estamosonline
```

### 2. Configurar o ambiente

```bash
cp .env.example .env
```

Abra o `.env` e configure o banco de dados:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=estamosonline
DB_USERNAME=root
DB_PASSWORD=sua_senha
```

### 3. Subir os containers

Primeira vez (build completo):
```bash
docker compose up -d --build
```

Próximas vezes:
```bash
docker compose up -d
```

### 4. Instalar dependências e gerar chave

```bash
docker compose exec app composer install
docker compose exec app php artisan key:generate
```

### 5. Rodar as migrations

```bash
docker compose exec app php artisan migrate
```

---

## 🔐 Acessar o Painel Admin (Filament)

Crie o primeiro usuário administrador:

```bash
docker compose exec app php artisan make:filament-user
```

Acesse: 👉 **`http://localhost/admin`**

---

## 📅 Sistema de Agendamento

### Como funciona

Cada profissional (MEI) é cadastrado como um **Tenant** e recebe um link único de agendamento:

```
http://localhost/agenda/{slug-do-profissional}
```

Esse link é compartilhado na bio do Instagram ou via WhatsApp.

### Fluxo de cadastro no painel (ordem obrigatória)

1. **Profissionais** (`/admin/tenants`) — cadastre o profissional com nome, slug, WhatsApp e tipo de negócio
2. **Serviços** (`/admin/services`) — cadastre os serviços com nome, duração e preço
3. **Horários** (`/admin/availabilities`) — defina os dias e horários de atendimento
4. **Agendamentos** (`/admin/appointments`) — visualize e gerencie os agendamentos recebidos

### Fluxo do cliente

1. Acessa o link do profissional
2. Escolhe o serviço
3. Escolhe o dia e horário disponível
4. Informa nome e WhatsApp
5. Recebe confirmação na tela

### Lógica de bloqueio de horários

A duração do serviço é respeitada automaticamente. Se um serviço tem 2 horas e é marcado às 10:00, os horários de 10:00 e 11:00 ficam bloqueados para outros clientes. O sistema calcula `ends_at = scheduled_at + duration_minutes` e usa esse intervalo para detectar sobreposições.

### Estrutura do banco

| Tabela | Descrição |
|---|---|
| `tenants` | Cada profissional/negócio cadastrado |
| `services` | Serviços oferecidos por cada profissional |
| `availabilities` | Dias e horários de atendimento por profissional |
| `appointments` | Agendamentos realizados pelos clientes |

---

## 💡 Comandos Úteis

| Comando | Descrição |
|---|---|
| `docker compose up -d` | Iniciar os serviços |
| `docker compose stop` | Pausar o ambiente |
| `docker compose down` | Derrubar e destruir os containers |
| `docker compose logs -f` | Ver logs em tempo real |
| `docker compose exec app bash` | Acessar o terminal do PHP |

### 🧹 Limpar Cache

Use sempre que alterar `.env`, criar novas rotas ou views não atualizarem:

```bash
docker compose exec app php artisan config:clear
docker compose exec app php artisan cache:clear
docker compose exec app php artisan route:clear
docker compose exec app php artisan view:clear
```

Ou tudo de uma vez:

```bash
docker compose exec app php artisan optimize:clear
```

### 🔑 Corrigir permissões (se der erro 500)

```bash
docker compose exec app bash -c "chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache && chmod -R 775 /var/www/storage /var/www/bootstrap/cache"
```
