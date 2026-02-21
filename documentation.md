# Ash — ERP API Documentation

> **Ash** é uma API RESTful para um sistema ERP multi-tenant construída com **Laravel 12** e **PHP 8.2**, com autenticação via **Laravel Sanctum**. O sistema gerencia usuários, empresas, pessoas físicas, transações financeiras, categorias, roles e muito mais, com isolamento completo de dados por tenant (`client_id`).

---

## Sumário

- [Stack Tecnológico](#stack-tecnológico)
- [Pré-requisitos](#pré-requisitos)
- [Instalação e Configuração](#instalação-e-configuração)
- [Variáveis de Ambiente](#variáveis-de-ambiente)
- [Banco de Dados](#banco-de-dados)
- [Autenticação](#autenticação)
- [Sistema de Permissões](#sistema-de-permissões)
- [Módulos e Endpoints da API](#módulos-e-endpoints-da-api)
  - [Autenticação](#1-autenticação)
  - [Usuários](#2-usuários)
  - [Roles](#3-roles)
  - [Empresas (PJ)](#4-empresas-pj)
  - [Pessoas (PF)](#5-pessoas-pf)
  - [Transações Financeiras](#6-transações-financeiras)
  - [Pagamentos Financeiros](#7-pagamentos-financeiros)
  - [Categorias](#8-categorias)
  - [Dados Auxiliares](#9-dados-auxiliares)
- [Modelos de Dados](#modelos-de-dados)
- [Arquitetura do Projeto](#arquitetura-do-projeto)
- [Comandos Úteis](#comandos-úteis)
- [Testes](#testes)

---

## Stack Tecnológico

| Camada | Tecnologia |
|--------|-----------|
| Runtime | PHP 8.2+ |
| Framework | Laravel 12 |
| Autenticação | Laravel Sanctum 4.2 |
| Banco de Dados | MariaDB / MySQL (padrão) ou PostgreSQL |
| Cache | Database (padrão) / Redis (opcional) |
| Filas | Database (padrão) |
| Sessões | Database |
| Testes | PestPHP 3.8 |
| Code Style | Laravel Pint |
| Análise Estática | PHPStan 2.1 |
| Dev Runner | Concurrently (server + queue + vite) |

---

## Pré-requisitos

- PHP >= 8.2 com extensões: `pdo`, `mbstring`, `openssl`, `bcmath`, `json`
- Composer 2.x
- Node.js & npm (para assets Vite)
- MariaDB >= 10.6 / MySQL >= 8.0
- (Opcional) Redis para cache e filas em produção

---

## Instalação e Configuração

### Setup automático

```bash
composer run setup
```

Esse comando executa em sequência:
1. `composer install` — instala dependências PHP
2. Copia `.env.example` → `.env` (se não existir)
3. `php artisan key:generate` — gera a chave da aplicação
4. `php artisan migrate --force` — executa todas as migrations
5. `npm install` — instala dependências JavaScript
6. `npm run build` — compila assets

### Executar em desenvolvimento

```bash
composer run dev
```

Isso inicia 3 processos em paralelo:
- **server** — `php artisan serve` (API na porta 8000)
- **queue** — `php artisan queue:listen --tries=1`
- **vite** — `npm run dev`

### Executar apenas o servidor

```bash
php artisan serve
```

### Popular o banco com dados iniciais

```bash
php artisan db:seed
```

Seeders disponíveis:
- `RoleSeeder` — roles padrão (super admin, admin, usuário)
- `StatusesSeeder` — statuses pré-definidos para módulos
- `CategorySeeder` — categorias iniciais
- `PaymentMethodSeeder` — métodos de pagamento (boleto, PIX, cartão, etc.)
- `BankSeeder` / `BanksTableSeeder` — bancos brasileiros
- `StateSeeder` — todos os estados brasileiros
- `CitySeeder` — todos os municípios brasileiros
- `CompanySeeder` — empresas de exemplo
- `PersonSeeder` — pessoas físicas de exemplo
- `UserSeeder` — usuários iniciais

---

## Variáveis de Ambiente

Copie `.env.example` para `.env` e ajuste os valores:

```dotenv
APP_NAME=Laravel
APP_ENV=local           # local | production | staging
APP_DEBUG=true
APP_URL=http://localhost

# Banco de dados
DB_CONNECTION=mariadb
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ash
DB_USERNAME=root
DB_PASSWORD=

# Sessão e Cache
SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database

# Filas
QUEUE_CONNECTION=database

# Redis (opcional — para produção)
REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Storage (S3 para produção)
FILESYSTEM_DISK=local
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
```

---

## Banco de Dados

### Migrations (em ordem de execução)

| # | Tabela | Descrição |
|---|--------|-----------|
| 1 | `roles` | Perfis de acesso com permissões JSON e nível numérico |
| 2 | `types` | Tipos de entidades do sistema |
| 3 | `users` | Usuários com multi-tenancy, hierarquia e permissões |
| 4 | `cache` | Cache do framework |
| 5 | `jobs` | Fila de jobs assíncronos |
| 6 | `countries` | Países |
| 7 | `states` | Estados brasileiros |
| 8 | `cities` | Municípios brasileiros |
| 9 | `payment_methods` | Métodos de pagamento |
| 10 | `categories` | Categorias hierárquicas (parent_id) |
| 11 | `banks` | Bancos (código, nome, ISPB) |
| 12 | `companies` | Empresas (PJ) com dados fiscais |
| 13 | `people` | Pessoas físicas (PF) com CPF e crédito |
| 14 | `statuses` | Status configuráveis por tipo de entidade |
| 15 | `fiscal_documents` | Documentos fiscais (NF, boleto, etc.) |
| 16 | `financial_transactions` | Transações financeiras (A/R e A/P) |
| 17 | `personal_access_tokens` | Tokens Sanctum |
| 18 | `cost_centers` | Centros de custo |
| 19 | `bank_statements` | Extratos bancários importados |
| 20 | `financial_reconciliations` | Conciliações bancárias |
| 21 | `financial_payments` | Registros de pagamentos por transação |

### Diagrama de Relacionamentos (simplificado)

```
users ─────────── roles (N:1)
users ─────────── companies (N:1)
users ─────────── people (N:1)

financial_transactions ─── categories (N:1)
financial_transactions ─── statuses (N:1)
financial_transactions ─── people (N:1)
financial_transactions ─── companies (N:1)
financial_transactions ─── types (N:1)

financial_payments ─────── financial_transactions (N:1)
financial_reconciliations ─ financial_transactions (N:1)
financial_reconciliations ─ bank_statements (N:1)

companies ──────────────── cities / states (N:1)
people ──────────────────── cities / states (N:1)
categories ──────────────── categories (self-referential, parent_id)
```

---

## Autenticação

A API usa **Laravel Sanctum** com tokens Bearer. Todos os endpoints (exceto login e registro) requerem o header:

```
Authorization: Bearer {seu_token}
```

### Fluxo de Login

```
POST /api/v1/auth/login
→ Recebe: email, password, device_name
→ Retorna: token, user, token_type, expires_at
```

### Fluxo de Logout

```
POST /api/v1/auth/logout   (requer autenticação)
→ Revoga TODOS os tokens do usuário
```

### Token Refresh

```
POST /api/v1/auth/refresh  (requer autenticação)
→ Revoga token atual e emite novo token
```

---

## Sistema de Permissões

O sistema usa um modelo de permissões em dois níveis:

### Níveis de Acesso (Role Level)
| Level | Papel |
|-------|-------|
| 0–89 | Usuário comum |
| 90–99 | Administrador (admin) |
| 100+ | Super Administrador |

### Permissões Granulares
- Cada **Role** possui um array JSON de permissões
- Cada **User** pode ter permissões JSON individuais adicionais
- As permissões do usuário são a **união** das permissões da role + permissões individuais
- Super Admin (level >= 100) tem **todas as permissões automaticamente**

### Métodos úteis no Model `User`

```php
$user->hasPermission('financial.view');     // verifica permissão específica
$user->hasAnyPermission([...]);             // verifica qualquer das permissões
$user->hasAllPermissions([...]);            // verifica todas as permissões
$user->getAllPermissions();                 // retorna array completo
$user->isAdmin();                           // level >= 90
$user->isSuperAdmin();                      // level >= 100
$user->canManageUser($otherUser);           // controle hierárquico
```

---

## Módulos e Endpoints da API

**Base URL:** `http://localhost:8000/api/v1`

### Formato de Resposta

Todas as respostas seguem o padrão:

```json
{
  "success": true,
  "message": "Operação realizada com sucesso",
  "data": { ... }
}
```

Em caso de erro:
```json
{
  "success": false,
  "message": "Descrição do erro",
  "errors": { ... }
}
```

---

### 1. Autenticação

| Método | Endpoint | Auth | Descrição |
|--------|----------|------|-----------|
| `GET` | `/api/v1/` | ❌ | Health check público |
| `POST` | `/api/v1/` | ✅ | Health check autenticado |
| `POST` | `/api/v1/auth/login` | ❌ | Login do usuário |
| `POST` | `/api/v1/auth/register` | ❌ | Registro de novo usuário |
| `POST` | `/api/v1/auth/logout` | ✅ | Logout (revoga todos os tokens) |
| `POST` | `/api/v1/auth/refresh` | ✅ | Renova o token de acesso |
| `GET` | `/api/v1/auth/me` | ✅ | Retorna dados do usuário autenticado |

#### POST `/api/v1/auth/login`

**Request:**
```json
{
  "email": "usuario@exemplo.com",
  "password": "senha123",
  "device_name": "meu-dispositivo"
}
```

**Response 200:**
```json
{
  "success": true,
  "message": "Login realizado com sucesso",
  "data": {
    "user": { "id": 1, "name": "João Silva", "email": "..." },
    "token": "1|abc123...",
    "token_type": "Bearer",
    "expires_at": "2027-02-20T20:00:00Z"
  }
}
```

**Erros possíveis:**
- `401` — Credenciais inválidas
- `403` — Conta desativada ou arquivada

---

### 2. Usuários

> Requer autenticação

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `GET` | `/api/v1/users` | Lista todos os usuários |
| `POST` | `/api/v1/users` | Cria novo usuário |
| `GET` | `/api/v1/users/{id}` | Detalha um usuário |
| `PUT/PATCH` | `/api/v1/users/{id}` | Atualiza usuário |
| `DELETE` | `/api/v1/users/{id}` | Remove usuário (soft delete) |
| `POST` | `/api/v1/users/{id}/restore` | Restaura usuário deletado |

**Campos principais do usuário:**

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `username` | string | Username único (gerado a partir do e-mail se não informado) |
| `name` | string | Nome completo (capitalizado automaticamente) |
| `email` | string | E-mail único (minúsculas automáticas) |
| `password` | string | Senha (hash bcrypt) |
| `role_id` | integer | ID da role |
| `client_id` | integer | ID do tenant/cliente |
| `branch_id` | integer | ID da filial |
| `supervisor_id` | integer | ID do supervisor |
| `permissions` | array | Permissões individuais adicionais (JSON) |
| `active` | boolean | Se o usuário está ativo |
| `archived` | boolean | Se o usuário está arquivado |

**Atributos calculados (appends):**
- `full_name` — nome completo
- `avatar_url` — URL do avatar (gerado via ui-avatars.com se não houver foto)
- `is_active` — status ativo combinado
- `is_archived` — status de arquivamento
- `permission_list` — lista completa de permissões

---

### 3. Roles

> Requer autenticação e permissão `roles.manage` (Super Admin por padrão)

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `GET` | `/api/v1/roles` | Lista roles com paginação e estatísticas |
| `POST` | `/api/v1/roles` | Cria nova role |
| `GET` | `/api/v1/roles/{id}` | Detalha uma role específica |
| `PUT/PATCH` | `/api/v1/roles/{id}` | Atualiza role |
| `DELETE` | `/api/v1/roles/{id}` | Remove role (soft delete) |
| `POST` | `/api/v1/roles/{id}/restore` | Restaura uma role deletada |

**Campos da role:**

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `name` | string | Nome da role (único por tenant) |
| `description` | string | Descrição das responsabilidades da role |
| `level` | integer | Nível de acesso (0-89: Usuário, 90-99: Admin, 100+: Super Admin) |
| `permissions` | array | Lista de strings de permissões no padrão `modulo.acao` |
| `active` | boolean | Define se a role está disponível para uso |
| `client_id` | integer | ID do tenant (0 ou null para roles globais do sistema) |

**Exemplo de Resposta (GET `/api/v1/roles/{id}`):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Gerente Financeiro",
    "level": 50,
    "permissions": ["financial.view", "financial.create", "financial.update"],
    "active": true,
    "permission_count": 3,
    "is_admin": false,
    "is_super_admin": false,
    "created_at": "..."
  }
}
```

---

### 4. Empresas (PJ)

> Requer autenticação

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `GET` | `/api/v1/companies` | Lista empresas |
| `POST` | `/api/v1/companies` | Cadastra empresa |
| `GET` | `/api/v1/companies/{id}` | Detalha empresa |
| `PUT/PATCH` | `/api/v1/companies/{id}` | Atualiza empresa |
| `DELETE` | `/api/v1/companies/{id}` | Remove empresa (soft delete) |
| `POST` | `/api/v1/companies/{id}/restore` | Restaura empresa |

**Campos principais:**

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `cnpj` | string | CNPJ da empresa |
| `trade_name` | string | Nome fantasia |
| `company_name` | string | Razão social |
| `state_registration` | string | Inscrição estadual |
| `municipal_registration` | string | Inscrição municipal |
| `cnae` | string | Código CNAE |
| `tax_regime` | string | Regime tributário (Simples, Lucro Presumido, etc.) |
| `is_headquarters` | boolean | É a matriz |
| `is_branch` | boolean | É uma filial |
| `contacts` | array | Array de contatos (JSON) |
| `credit_limit` | decimal | Limite de crédito |
| `used_credit` | decimal | Crédito utilizado |

---

### 5. Pessoas (PF)

> Requer autenticação

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `GET` | `/api/v1/people` | Lista pessoas físicas |
| `POST` | `/api/v1/people` | Cadastra pessoa |
| `GET` | `/api/v1/people/archived` | Lista pessoas arquivadas |
| `GET` | `/api/v1/people/stats` | Estatísticas de pessoas |
| `GET` | `/api/v1/people/{id}` | Detalha pessoa |
| `PUT/PATCH` | `/api/v1/people/{id}` | Atualiza pessoa |
| `DELETE` | `/api/v1/people/{id}` | Remove pessoa (soft delete) |
| `POST` | `/api/v1/people/{id}/archive` | Arquiva pessoa |
| `POST` | `/api/v1/people/{id}/unarchive` | Desarquiva pessoa |
| `POST` | `/api/v1/people/{id}/restore` | Restaura pessoa deletada |
| `POST` | `/api/v1/people/{id}/credit` | Atualiza limite de crédito |

**Campos principais:**

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `cpf` | string | CPF |
| `rg` | string | RG |
| `first_name` | string | Nome |
| `last_name` | string | Sobrenome |
| `birthdate` | date | Data de nascimento |
| `email` | string | E-mail |
| `phone` | string | Telefone |
| `credit_limit` | decimal | Limite de crédito |
| `used_credit` | decimal | Crédito utilizado |
| `situation` | string | Situação cadastral |
| `state_id` | integer | Estado (FK) |
| `city_id` | integer | Cidade (FK) |
| `category_id` | integer | Categoria (FK) |

**Atributos calculados:**
- `full_name` — nome + sobrenome
- `full_address` — endereço formatado completo
- `available_credit` — `credit_limit - used_credit`
- `age` — idade calculada pela data de nascimento

**Métodos de negócio:**
```php
$person->useCredit($amount);       // debita do crédito disponível
$person->releaseCredit($amount);   // libera crédito
$person->updateCreditLimit($amount);
$person->archive();
$person->unarchive();
$person->activate();
$person->deactivate();
```

---

### 6. Transações Financeiras

> Requer autenticação

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `GET` | `/api/v1/financial-transactions` | Lista transações |
| `POST` | `/api/v1/financial-transactions` | Cria transação |
| `GET` | `/api/v1/financial-transactions/{id}` | Detalha transação |
| `PUT/PATCH` | `/api/v1/financial-transactions/{id}` | Atualiza transação |
| `DELETE` | `/api/v1/financial-transactions/{id}` | Remove transação (soft delete) |
| `POST` | `/api/v1/financial-transactions/{id}/pay` | Marca como pago |
| `GET` | `/api/v1/financial-transactions/{id}/receipt` | Download do comprovante |
| `POST` | `/api/v1/financial-transactions/{id}/restore` | Restaura transação |
| `GET` | `/api/v1/financial-transactions/summary` | Resumo financeiro |
| `POST` | `/api/v1/financial-transactions/import` | Importa transações em lote |

**Campos principais:**

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `type_id` | integer | Tipo: `1` = A Receber, `2` = A Pagar |
| `description` | string | Descrição da transação |
| `amount` | decimal | Valor principal |
| `due_date` | date | Data de vencimento |
| `competency_date` | date | Data de competência |
| `fiscal_document` | string | Número do documento fiscal |
| `category_id` | integer | Categoria (FK) |
| `subcategory_id` | integer | Subcategoria (FK) |
| `person_type` | integer | `1` = PF, `2` = PJ, `3` = desconhecido |
| `individual_id` | integer | FK para `people` (quando PF) |
| `company_id` | integer | FK para `companies` (quando PJ) |
| `installment` | integer | Número da parcela atual |
| `total_installments` | integer | Total de parcelas |
| `interest_amount` | decimal | Juros |
| `fine_amount` | decimal | Multa |
| `discount_amount` | decimal | Desconto |
| `paid_total` | decimal | Total já pago |
| `is_fully_paid` | boolean | Se está quitado |
| `is_fully_reconciled` | boolean | Se está conciliado |
| `approval_status` | string | Status de aprovação |
| `boleto_url` | string | URL do boleto |
| `receipt_url` | string | URL do comprovante |

**Atributos calculados:**
- `is_overdue` — se está vencido e não pago
- `remaining_amount` — `amount + juros + multa - desconto - paid_total`

**Scopes disponíveis:**
```php
FinancialTransaction::receivable()  // type_id = 1
FinancialTransaction::payable()     // type_id = 2
FinancialTransaction::pending()     // não quitado
FinancialTransaction::paid()        // quitado
FinancialTransaction::overdue()     // vencido e não pago
FinancialTransaction::search($term) // busca por descrição/doc/chave/centro
```

---

### 7. Pagamentos Financeiros

> Requer autenticação

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `GET` | `/api/v1/financial-payments` | Lista pagamentos |
| `POST` | `/api/v1/financial-payments` | Registra pagamento |
| `GET` | `/api/v1/financial-payments/{id}` | Detalha pagamento |
| `PUT/PATCH` | `/api/v1/financial-payments/{id}` | Atualiza pagamento |
| `DELETE` | `/api/v1/financial-payments/{id}` | Remove pagamento |

> Pagamentos são vinculados a uma `FinancialTransaction`. Cada pagamento registrado atualiza o `paid_total` da transação e pode marcar `is_fully_paid = true` automaticamente.

---

### 8. Categorias

> Requer autenticação

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `GET` | `/api/v1/categories` | Lista categorias (flat) |
| `POST` | `/api/v1/categories` | Cria categoria |
| `GET` | `/api/v1/categories/{id}` | Detalha categoria |
| `PUT/PATCH` | `/api/v1/categories/{id}` | Atualiza categoria |
| `DELETE` | `/api/v1/categories/{id}` | Remove categoria (soft delete) |
| `POST` | `/api/v1/categories/{id}/restore` | Restaura categoria |
| `GET` | `/api/v1/categories/tree/hierarchical` | Árvore hierárquica completa |

> Categorias suportam hierarquia multinível via `parent_id`. Use o endpoint `tree/hierarchical` para obter a árvore completa aninhada.

---

### 9. Dados Auxiliares

Endpoints somente-leitura para dados de apoio:

| Endpoint | Descrição |
|----------|-----------|
| `GET /api/v1/roles` | Roles disponíveis |

> **Obs:** Os demais dados auxiliares (bancos, métodos de pagamento, estados, cidades, tipos, statuses) estão implementados nos modelos e seeders, mas seus endpoints ainda estão em processo de expansão conforme o PRD.

---

## Modelos de Dados

### Visão Geral dos Models

| Model | Tabela | Soft Delete | Multi-tenant |
|-------|--------|-------------|--------------|
| `User` | `users` | ✅ | ✅ (`client_id`) |
| `Role` | `roles` | ✅ | ✅ (`client_id`) |
| `Company` | `companies` | ✅ | ✅ (`client_id`) |
| `Person` | `people` | ✅ | ✅ (`client_id`) |
| `FinancialTransaction` | `financial_transactions` | ✅ | ✅ (`client_id`) |
| `FinancialPayment` | `financial_payments` | ❌ | ✅ |
| `Category` | `categories` | ✅ | ✅ |
| `Bank` | `banks` | ❌ | ❌ (global) |
| `PaymentMethod` | `payment_methods` | ❌ | ❌ (global) |
| `Status` | `statuses` | ❌ | ❌ (global) |
| `Type` | `types` | ❌ | ❌ (global) |
| `Country` | `countries` | ❌ | ❌ (global) |
| `State` | `states` | ❌ | ❌ (global) |
| `City` | `cities` | ❌ | ❌ (global) |
| `BankStatement` | `bank_statements` | ❌ | ✅ |
| `FinancialReconciliation` | `financial_reconciliations` | ❌ | ✅ |

### Padrão de Audit Trail

Todos os modelos multi-tenant possuem:

```php
'created_by'  => integer  // ID do usuário que criou
'updated_by'  => integer  // ID do usuário que atualizou
'archived'    => boolean  // Se está arquivado (soft archive)
'archived_by' => integer  // Quem arquivou
'archived_at' => datetime // Quando foi arquivado
```

O model `User` preenche `created_by` e `updated_by` automaticamente via eventos `booted()`.

---

## Arquitetura do Projeto

```
app/
├── Console/               # Comandos Artisan customizados
├── Exceptions/            # Handler de exceções
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   │   ├── ApiResponse.php     # Trait com métodos success/error/validationError
│   │   │   └── V1/                 # Controllers versionados
│   │   │       ├── BaseController.php
│   │   │       ├── AuthController.php
│   │   │       ├── UserController.php
│   │   │       ├── PersonController.php
│   │   │       ├── CompanyController.php
│   │   │       ├── FinancialTransactionController.php
│   │   │       ├── FinancialTransactionImportController.php
│   │   │       ├── FinancialPaymentController.php
│   │   │       ├── CategoryController.php
│   │   │       ├── RoleController.php
│   │   │       ├── BankController.php
│   │   │       ├── PaymentMethodController.php
│   │   │       ├── StatusController.php
│   │   │       ├── TypeController.php
│   │   │       ├── CountryController.php
│   │   │       ├── StateController.php
│   │   │       └── CityController.php
│   ├── Middleware/        # Middlewares customizados
│   ├── Requests/          # Form Requests com validação
│   └── Resources/         # API Resources (transformadores de resposta)
├── Models/                # Eloquent models
├── Policies/              # Políticas de autorização
├── Providers/             # Service Providers
├── Repositories/          # Padrão Repository (em desenvolvimento)
└── Services/
    ├── AuthService.php             # Lógica de autenticação/registro
    ├── FinancialTransactionService.php
    ├── LogService.php
    ├── PermissionService.php
    └── UserService.php

routes/
├── api.php                # Todas as rotas da API (prefixo /api/v1)
├── web.php                # Rotas web
└── console.php            # Rotas de console

database/
├── migrations/            # 21 migrations
├── seeders/               # 12 seeders
└── factories/             # Factories para testes
```

### Padrão de Controllers

Todos os controllers estendem `BaseController` e usam o trait `ApiResponse`:

```php
// Resposta de sucesso
return $this->success($data, 'Mensagem de sucesso', 200);

// Resposta de erro
return $this->error('Mensagem de erro', 500);

// Erro de validação
return $this->validationError($errors, 'Validação falhou');
```

---

## Comandos Úteis

```bash
# Iniciar ambiente de desenvolvimento completo
composer run dev

# Rodar migrations
php artisan migrate

# Reverter migrations
php artisan migrate:rollback

# Resetar banco e re-migrar
php artisan migrate:fresh --seed

# Popular banco com dados iniciais
php artisan db:seed

# Limpar caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear

# Listar todas as rotas
php artisan route:list

# Rodar queue worker manualmente
php artisan queue:work

# Verificar jobs pendentes
php artisan queue:monitor

# Tinker (REPL interativo)
php artisan tinker
```

---

## Testes

O projeto usa **PestPHP 3.8** com o plugin Laravel.

```bash
# Rodar todos os testes
composer run test
# ou
php artisan test

# Rodar testes com cobertura
php artisan test --coverage

# PHPStan (análise estática)
./vendor/bin/phpstan analyse

# Laravel Pint (formatação de código)
./vendor/bin/pint
```

---

## Padrões e Convenções

### Versionamento de API
Todos os endpoints estão sob o prefixo `/api/v1/`. Mudanças breaking serão encapsuladas em `/api/v2/`.

### Multi-tenancy
- Toda query que envolve dados de negócio deve ser filtrada por `client_id`
- **Nunca** retornar dados de um tenant para outro
- O `client_id` deve ser inferido a partir do usuário autenticado, nunca aceitado diretamente do request

### Soft Deletes vs Archival
- **Soft Delete** (`SoftDeletes`): remoção reversível via `DELETE` endpoint — o dado existe no banco mas `deleted_at != null`
- **Archive**: estado de negócio separado — o registro existe e está visível para auditoria, mas não aparece em listagens ativas (`archived = true`)

### Nomenclatura das Permissões
Use o padrão `modulo.acao`:
```
financial.view
financial.create
financial.update
financial.delete
users.manage
roles.manage
people.view
...
```

### Campos Customizáveis
Todos os modelos principais possuem `custom_field1`, `custom_field2`, `custom_field3` e `notes` para extensões sem precisar de novas migrations.
