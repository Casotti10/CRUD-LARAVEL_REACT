# Gestor de Produtos — Documentação do Projeto

> Trilha de aprendizagem guiada (mentor Tech Lead → estagiário).
> Objetivo final: um CRUD funcional, organizado, versionado no GitHub e publicado em produção, entendendo cada peça.

---

## 1. Visão geral

**O que vamos construir:** um sistema de gerenciamento de produtos de uma pequena loja.

**Por que esse exemplo:**
- É um CRUD clássico, presente em praticamente todo sistema real (ERP, e-commerce, CRM).
- Tem campos de **tipos variados** (texto, número decimal, inteiro, booleano, texto longo) → ótimo para aprender tipos de dados, validação e formulários.
- Tem **um relacionamento simples** (Produto pertence a uma Categoria) → aprendemos chave estrangeira e Eloquent sem complicar.
- É diferente do CRUD de clientes do `login-crm`, então não há mistura de código entre os dois projetos.

**Escopo (adequado para estagiário):**

| Funcionalidade | Incluído |
|---|---|
| Listar produtos com paginação | ✅ |
| Busca simples por nome | ✅ |
| Cadastrar produto | ✅ |
| Visualizar detalhes | ✅ |
| Editar produto | ✅ |
| Excluir produto (com confirmação) | ✅ |
| Validação no Laravel + erros no React | ✅ |
| Mensagens flash de sucesso/erro | ✅ |
| Estados de carregamento (`processing`) | ✅ |
| Categorias (pré-cadastradas via seeder) | ✅ |
| Autenticação | ✅ já vem pronta no starter kit (Fortify); estudada na Etapa 7 |
| Upload de imagem, carrinho, pedidos | ❌ fora do escopo |

### Modelo de dados (planejado)

```text
categorias                     produtos
-----------                    -----------------------------
id            PK               id             PK
nome          string           categoria_id   FK → categorias.id
timestamps                     nome           string(120)
                               sku            string(30) único
                               descricao      text (opcional)
                               preco          decimal(10,2)
                               estoque        unsigned int
                               ativo          boolean
                               timestamps
```

Relação: **1 Categoria → N Produtos** (`hasMany` / `belongsTo`).

---

## 2. Stack

| Camada | Tecnologia | Papel |
|---|---|---|
| Linguagem backend | PHP 8.3+ | Executa o Laravel |
| Framework backend | Laravel 13 | Rotas, controllers, validação, Eloquent |
| Ponte front/back | Inertia.js | Liga o Laravel ao React sem precisar de API REST |
| Interface | React 19 com TypeScript (`.tsx`) — vindo do starter kit | Páginas e componentes |
| Estilo | Tailwind CSS 4 | Classes utilitárias |
| Build do front | Vite | Compila TSX/CSS |
| Autenticação | Laravel Fortify (starter kit) | Login, cadastro, 2FA, passkeys — já prontos |
| Rotas no front | Laravel Wayfinder (starter kit) | Gera funções TypeScript para as rotas do Laravel |
| Banco | MySQL 8.4 (LTS) em container Docker | Armazena os dados |
| Servidor local | Laravel Herd (Windows) | PHP, Composer e servidor `.test` |
| Versionamento | Git + GitHub | Histórico e repositório remoto |

Decisões:
- **Starter kit oficial React** (pasta `crud`, decidido em 2026-11-17, substituindo o plano inicial de um projeto limpo em `gestor-produtos`): Inertia, React, Tailwind, autenticação e componentes já vêm configurados. Nas Etapas 4 e 6 o foco passa a ser **ler e entender** o que o kit entrega, antes de construir o CRUD em cima dele.
- **TypeScript** (padrão do kit): aprendemos a tipagem básica conforme for aparecendo.
- **MySQL no Docker** (e não instalado no Windows) para aprender containers e manter o ambiente descartável.

---

## 3. Arquitetura

```text
 Navegador (React + Inertia)
        │  clique / envio de formulário
        ▼
 HTTP request (header X-Inertia: true)
        │
        ▼
 Laravel Herd (servidor local — crud.test)
        │
        ▼
 routes/web.php ──► ProdutoController
                        │   valida (Form Request)
                        ▼
                   Model Produto (Eloquent)
                        │   SQL
                        ▼
                   MySQL (container Docker, porta 3306)
                        │
        ◄───────────────┘
 Inertia::render('Produtos/Index', [props])
        │  JSON com as props
        ▼
 React re-renderiza a página (sem recarregar o navegador)
```

**Onde cada coisa roda no Windows:**
- **Herd** → roda o PHP e serve o Laravel.
- **Docker Desktop** → roda só o MySQL.
- **Node/Vite** → compila o front (`npm run dev`).
- O Laravel conversa com o MySQL pela porta configurada no `.env` (`DB_HOST=127.0.0.1`, `DB_PORT=3306`).

---

## 4. Roteiro de aprendizagem

Cada etapa segue: **Conceito → Por que → Preparação → Prática → Explicação do código → Testes → Desafio → Revisão**. Só avançamos após confirmação.

| # | Etapa | Conteúdo principal | Status |
|---|---|---|---|
| 1 | Preparação do ambiente | Herd, PHP, Composer, versões, Docker, container MySQL, portas/volumes/env, conexão Laravel↔MySQL | ✅ concluída (conexão com o `.env` feita no início da Etapa 2) |
| 2 | Fundamentos do Laravel | Projeto (`crud`, starter kit), estrutura de pastas, `.env`, rotas, controllers, models, migrations, ciclo de requisição | 🟡 em andamento |
| 3 | Banco de dados | Tabelas, tipos de dados, migrations, relacionamento categoria→produto, Eloquent, consultas, seeders | 🟡 em andamento |
| 4 | React + Inertia | Componentes, props, estado, como o Inertia funciona, Laravel → props, layouts e componentes reutilizáveis | ⬜ |
| 5 | CRUD | Listagem, cadastro, edição, exclusão, validação, erros no React, flash messages, paginação | ⬜ |
| 6 | Tailwind CSS | Classes utilitárias, layout, formulários, tabelas, responsividade, UX | ⬜ |
| 7 | Boas práticas | Organização, responsabilidades, erros, segurança, CSRF, `.env`, auth/authorization, testes | ⬜ |
| 8 | Git e GitHub | Repositório, commits, branches, push, `.gitignore`, proteção de segredos | ⬜ |
| 9 | Deploy | Local × produção, pesquisa de hospedagem gratuita (verificada na época), banco, env, migrations, build, permissões, publicação | ⬜ |

> Observação: o Git será **inicializado já na Etapa 2** (logo após criar o projeto) para termos histórico desde o início. A Etapa 8 aprofunda branches, GitHub e segurança.

---

## 5. Pré-requisitos

- Windows 10/11 com virtualização habilitada na BIOS (necessária para o Docker/WSL2).
- Laravel Herd instalado.
- Docker Desktop (instalaremos na Etapa 1).
- Node.js LTS + npm.
- Git.
- Editor (VS Code recomendado) e um cliente de banco (opcional: DBeaver, TablePlus ou HeidiSQL).
- Conhecimentos: HTML básico, noção de PHP e JavaScript. O resto aprendemos no caminho.

⚠️ **Atenção ao ambiente:** o Laragon também está instalado e já causou conflito de porta com o Herd. Ele também pode ocupar a porta **3306** do MySQL. Deve ficar **desligado** durante este projeto.

---

## 6. Diário de progresso

| Data | Etapa | O que foi feito | Pendências |
|---|---|---|---|
| 2026-11-17 | 1 | Projeto definido, documentação iniciada, início da Etapa 1 (verificação de Herd/PHP/Composer) | Enviar saída dos comandos de verificação |
| 2026-11-17 | 1 | Versões verificadas: PHP 8.5.12 (Herd), Composer 2.11.1, Node 26.13.1, npm 11.20.0, Git 2.45.1, Docker 29.9.3. Decidido seguir este documento: projeto novo em `Herd\gestor-produtos` (a pasta `crud` tem starter kit com TypeScript e fica só como referência) | Verificar Docker rodando, porta 3306 livre e criar o container MySQL |
| 2026-11-17 | 2 | Decisão: **usar o projeto `crud` existente** (starter kit React + TypeScript + Fortify + Wayfinder) em vez de criar `gestor-produtos`. Tabela `teste` confirmada como apagada. Diagnóstico do `.env` do `crud`: `DB_DATABASE=laravel` (banco que não existe no container) e `APP_URL=http://localhost:8000` | Etapa 2, Parte 1: ler o erro de conexão, ajustar o `.env`, rodar migrations, `git init` + primeiro commit |
| 2026-11-17 | 2 | `.env` ajustado: `DB_DATABASE=gestor_produtos`, `APP_URL=http://crud.test`, `APP_LOCALE`/`APP_FAKER_LOCALE=pt_BR`, `APP_NAME=mysql-gestor` (escolha do aluno). `database.sqlite` removido. `db:show` OK (MySQL 8.4.13). `migrate` executado → 10 tabelas (explicado `Schema::create` × `Schema::table` e a tabela `migrations`). **Sessão encerrada aqui** | **Retomar:** `npm run dev`, abrir `http://crud.test`, registrar conta e logar (conferir tabela `users`). Depois: desafio (`config/database.php` + quebrar `DB_PORT=3307`), revisão da Parte 1, commit do `docs/`. Em seguida, Parte 2: estrutura de pastas |
| 2026-11-18 | 2 | Teste ponta a ponta OK (2 usuários em `users`, senha em hash bcrypt `$2y$12$`, sessão no banco). Verificação de e-mail: `MAIL_MAILER=log` → link fica em `storage/logs/laravel.log` (rotas com middleware `verified` exigem isso). A pedido do aluno, a verificação foi pulada: usuários marcados como verificados direto no banco (`email_verified_at = NOW()`) | Desafio e revisão da Parte 1, commit do `docs/`, depois Parte 2 (estrutura de pastas) |
| 2026-11-18 | 2 | Desafio feito: `env('VAR', 'padrão')` entendido; erro provocado com `DB_PORT=3307` → `[2002]` (recusou ativamente). Tabela de erros `[2002]`/`[1049]`/`[1045]`. Revisão respondida. Container tem `RestartPolicy: no` → precisa de `docker start mysql-gestor` após reiniciar o PC. **Parte 1 concluída** | Commit do `docs/`. Parte 2: estrutura de pastas |
| 2026-11-18 | 2 | Commit `fd1e313`. Parte 2: mapa de pastas, caminho de uma requisição (`public/index.php` → rota → middleware → controller → `Inertia::render` → `pages/`). Aluno travou em namespace → caminho do arquivo (PSR-4) e no papel do Form Request × middleware: explicado passo a passo. **Reforçar PSR-4 na prática** ao criar arquivos | Parte 3: migration, model e seeder de `Categoria` |
| 2026-11-18 | 3 | Etapa 3, Parte 1 iniciada: `php artisan make:model Categoria -ms` executado (model, migration `2026_11_18_201701_create_categorias_table.php` e `CategoriaSeeder` criados). Aluno adicionou comentários de estudo em `ProfileValidationRules.php` e uma linha em branco em `ProfileController.php` (não commitados). **Sessão encerrada aqui** | **Retomar:** conferir migration/model/seeder (Passos 2 a 5), `DatabaseSeeder` chamando `CategoriaSeeder`, `migrate` + `db:seed`, testes (`migrate:status`, `db:table categorias`, Tinker), desafio do `unique` (erro 1062) e revisão. Decidir se os comentários de estudo vão para o commit |
| 2026-11-17 | 1 | Container `mysql-gestor` criado e rodando (imagem `mysql:8.4`, porta `3306:3306`, volume `mysql-gestor-data`, banco `gestor_produtos`). Atenção: container antigo `mysql` (projeto CRM) também usa a 3306 — manter parado | Testes do container (conexão, persistência), desafio e revisão da Etapa 1. A configuração do `.env` acontece logo após criar o projeto (início da Etapa 2) |
| 2026-11-17 | 1 | Testes do container OK (logs com `ready for connections` na porta 3306). Desafio: tabela `teste` criada e mantida após `stop`/`start` → volume validado. Revisão respondida (imagem × container × banco, mapeamento de portas, senha gravada no volume, `127.0.0.1` × nome do container). **Etapa 1 concluída** | Apagar a tabela `teste`. Iniciar a Etapa 2: criar o projeto `gestor-produtos` e configurar o `.env` |

---

## 7. Comandos de referência (vai crescer a cada etapa)

```bash
php -v                 # versão do PHP
composer -V            # versão do Composer
herd --version         # versão do Herd
herd which-php         # qual PHP o Herd está usando
node -v && npm -v      # Node e npm
git --version          # Git

# Docker / MySQL
docker ps                                   # containers rodando
docker ps -a                                # todos os containers (inclusive parados)
docker start mysql-gestor                   # liga o container
docker stop mysql-gestor                    # desliga o container (dados ficam no volume)
docker logs mysql-gestor                    # logs do MySQL (primeiro lugar a olhar em caso de erro)
docker exec -it mysql-gestor mysql -uroot -p   # abre o cliente MySQL dentro do container
docker volume ls                            # lista os volumes
```
