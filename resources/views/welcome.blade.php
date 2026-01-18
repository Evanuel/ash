<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentação da API AshLite - ERP Soys</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #64748b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --light: #f8fafc;
            --dark: #1e293b;
            --gray: #e2e8f0;
            --gray-dark: #94a3b8;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f1f5f9;
            color: var(--dark);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header */
        header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 2rem 0;
            box-shadow: var(--box-shadow);
            margin-bottom: 2rem;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo i {
            font-size: 2.5rem;
        }

        .logo h1 {
            font-size: 2rem;
            font-weight: 700;
        }

        .logo span {
            color: #c7d2fe;
            font-weight: 300;
        }

        .version {
            background-color: rgba(255, 255, 255, 0.2);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-left: 10px;
        }

        /* Layout */
        .main-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 30px;
            margin-bottom: 3rem;
        }

        /* Sidebar */
        .sidebar {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 1.5rem;
            height: fit-content;
            position: sticky;
            top: 20px;
        }

        .sidebar h3 {
            color: var(--primary);
            margin-bottom: 1rem;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--gray);
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar li {
            margin-bottom: 10px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--dark);
            text-decoration: none;
            padding: 10px 15px;
            border-radius: var(--border-radius);
            transition: var(--transition);
        }

        .sidebar a:hover {
            background-color: var(--gray);
            color: var(--primary);
        }

        .sidebar a.active {
            background-color: #dbeafe;
            color: var(--primary);
            font-weight: 600;
        }

        .sidebar i {
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 2rem;
        }

        .section {
            margin-bottom: 3rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--gray);
        }

        .section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .section h2 {
            color: var(--primary);
            margin-bottom: 1.5rem;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--gray);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section h2 i {
            background-color: #dbeafe;
            padding: 10px;
            border-radius: 50%;
        }

        .section p {
            color: var(--secondary);
            margin-bottom: 1.5rem;
        }

        /* Endpoint Cards */
        .endpoint-card {
            background-color: #f8fafc;
            border-radius: var(--border-radius);
            border-left: 4px solid var(--primary);
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .endpoint-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .method {
            display: inline-block;
            padding: 5px 15px;
            border-radius: var(--border-radius);
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .get {
            background-color: #dbeafe;
            color: var(--primary);
        }

        .post {
            background-color: #dcfce7;
            color: var(--success);
        }

        .endpoint-url {
            background-color: #1e293b;
            color: white;
            padding: 12px 20px;
            border-radius: var(--border-radius);
            font-family: monospace;
            font-size: 1rem;
            word-break: break-all;
            margin-bottom: 1.5rem;
        }

        .endpoint-description {
            color: var(--secondary);
            margin-bottom: 1.5rem;
        }

        /* Tables */
        .table-container {
            overflow-x: auto;
            margin-bottom: 1.5rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }

        th {
            background-color: #f1f5f9;
            color: var(--dark);
            font-weight: 600;
            text-align: left;
            padding: 12px 15px;
            border-bottom: 2px solid var(--gray);
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--gray);
        }

        tr:last-child td {
            border-bottom: none;
        }

        .required {
            color: var(--danger);
            font-weight: 600;
        }

        /* Code blocks */
        pre {
            background-color: #1e293b;
            color: #e2e8f0;
            padding: 1.5rem;
            border-radius: var(--border-radius);
            overflow-x: auto;
            margin-bottom: 1.5rem;
            font-family: 'Courier New', Courier, monospace;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        code {
            background-color: #f1f5f9;
            color: var(--danger);
            padding: 3px 6px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 0.9rem;
        }

        .note {
            background-color: #fef3c7;
            border-left: 4px solid var(--warning);
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius);
            margin-bottom: 1.5rem;
        }

        .note h4 {
            color: var(--warning);
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Footer */
        footer {
            background-color: var(--dark);
            color: white;
            padding: 2rem 0;
            text-align: center;
            margin-top: 3rem;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .footer-links {
            display: flex;
            gap: 20px;
        }

        .footer-links a {
            color: var(--gray-dark);
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-links a:hover {
            color: white;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .main-layout {
                grid-template-columns: 1fr;
            }
            
            .sidebar {
                position: static;
                margin-bottom: 2rem;
            }
            
            .header-content {
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }
            
            .footer-content {
                flex-direction: column;
                gap: 20px;
            }
        }

        @media (max-width: 768px) {
            .endpoint-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .section h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <i class="fas fa-cloud"></i>
                    <div>
                        <h1>AshLite API <span>ERP Soys</span></h1>
                        <p>Documentação completa da API do sistema ERP Soys</p>
                    </div>
                </div>
                <div class="version">
                    <span>v1.0</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <div class="main-layout">
            <!-- Sidebar -->
            <aside class="sidebar">
                <h3>Navegação</h3>
                <ul>
                    <li><a href="{{ route('api-client') }}"><i class="fas fa-http"></i> Cliente Http</a></li>
                    <li><a href="#introducao" class="active"><i class="fas fa-home"></i> Introdução</a></li>
                    <li><a href="#autenticacao"><i class="fas fa-key"></i> Autenticação</a></li>
                    <li><a href="#usuarios"><i class="fas fa-users"></i> Usuários</a></li>
                    <li><a href="#exemplos"><i class="fas fa-code"></i> Exemplos</a></li>
                    <li><a href="#erros"><i class="fas fa-exclamation-triangle"></i> Códigos de Erro</a></li>
                </ul>
                
                <h3>Endpoints</h3>
                <ul>
                    <li><a href="#login"><i class="fas fa-sign-in-alt"></i> POST /auth/login</a></li>
                    <li><a href="#logout"><i class="fas fa-sign-out-alt"></i> POST /auth/logout</a></li>
                    <li><a href="#list-users"><i class="fas fa-list"></i> GET /users</a></li>
                    <li><a href="#get-user"><i class="fas fa-user"></i> GET /users/{id}</a></li>
                </ul>
            </aside>

            <!-- Main Content -->
            <main class="main-content">
                <!-- Introdução -->
                <section id="introducao" class="section">
                    <h2><i class="fas fa-home"></i> Introdução</h2>
                    <p>Bem-vindo à documentação da API do AshLite, o sistema ERP Soys. Esta API RESTful fornece acesso programático às funcionalidades do sistema, permitindo integração com outros sistemas e desenvolvimento de aplicações customizadas.</p>
                    
                    <div class="note">
                        <h4><i class="fas fa-info-circle"></i> Informações Importantes</h4>
                        <p>Todas as requisições devem ser feitas para a URL base: <code>http://127.0.0.1:8000/api/v1/</code></p>
                        <p>Os dados são enviados e recebidos no formato JSON. Para acessar endpoints protegidos, é necessário incluir um token de autenticação no cabeçalho das requisições.</p>
                    </div>
                </section>

                <!-- Autenticação -->
                <section id="autenticacao" class="section">
                    <h2><i class="fas fa-key"></i> Autenticação</h2>
                    <p>A API utiliza autenticação baseada em token. Para acessar os endpoints protegidos, você deve primeiro obter um token de autenticação através do endpoint de login, e então incluí-lo no cabeçalho <code>Authorization</code> das requisições subsequentes.</p>
                    
                    <!-- Login -->
                    <div id="login" class="endpoint-card">
                        <div class="endpoint-header">
                            <h3>Login</h3>
                            <span class="method post">POST</span>
                        </div>
                        <div class="endpoint-url">http://127.0.0.1:8000/api/v1/auth/login</div>
                        <p class="endpoint-description">Autentica um usuário no sistema e retorna um token de acesso.</p>
                        
                        <h4>Cabeçalhos</h4>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Cabeçalho</th>
                                        <th>Tipo</th>
                                        <th>Obrigatório</th>
                                        <th>Descrição</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Content-Type</td>
                                        <td>string</td>
                                        <td><span class="required">Sim</span></td>
                                        <td>Deve ser definido como <code>application/json</code></td>
                                    </tr>
                                    <tr>
                                        <td>Accept</td>
                                        <td>string</td>
                                        <td>Não</td>
                                        <td>Define o tipo de resposta esperada (padrão: <code>application/json</code>)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <h4>Corpo da Requisição</h4>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Parâmetro</th>
                                        <th>Tipo</th>
                                        <th>Obrigatório</th>
                                        <th>Descrição</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>email</td>
                                        <td>string</td>
                                        <td><span class="required">Sim</span></td>
                                        <td>Email do usuário</td>
                                    </tr>
                                    <tr>
                                        <td>password</td>
                                        <td>string</td>
                                        <td><span class="required">Sim</span></td>
                                        <td>Senha do usuário</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <h4>Exemplo de Requisição</h4>
                        <pre><code>{
  "email": "usuario@exemplo.com",
  "password": "senha123"
}</code></pre>
                    </div>
                    
                    <!-- Logout -->
                    <div id="logout" class="endpoint-card">
                        <div class="endpoint-header">
                            <h3>Logout</h3>
                            <span class="method post">POST</span>
                        </div>
                        <div class="endpoint-url">http://127.0.0.1:8000/api/v1/auth/logout</div>
                        <p class="endpoint-description">Encerra a sessão do usuário atual e invalida o token de acesso.</p>
                        
                        <h4>Cabeçalhos</h4>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Cabeçalho</th>
                                        <th>Tipo</th>
                                        <th>Obrigatório</th>
                                        <th>Descrição</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Authorization</td>
                                        <td>string</td>
                                        <td><span class="required">Sim</span></td>
                                        <td>Token de autenticação no formato <code>Bearer {token}</code></td>
                                    </tr>
                                    <tr>
                                        <td>Content-Type</td>
                                        <td>string</td>
                                        <td>Não</td>
                                        <td>Deve ser definido como <code>application/json</code></td>
                                    </tr>
                                    <tr>
                                        <td>Accept</td>
                                        <td>string</td>
                                        <td>Não</td>
                                        <td>Define o tipo de resposta esperada</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <!-- Usuários -->
                <section id="usuarios" class="section">
                    <h2><i class="fas fa-users"></i> Usuários</h2>
                    <p>Endpoints para gerenciamento de usuários do sistema. Todos os endpoints desta seção requerem autenticação.</p>
                    
                    <!-- Listar Usuários -->
                    <div id="list-users" class="endpoint-card">
                        <div class="endpoint-header">
                            <h3>Listar Usuários</h3>
                            <span class="method get">GET</span>
                        </div>
                        <div class="endpoint-url">http://127.0.0.1:8000/api/v1/users</div>
                        <p class="endpoint-description">Retorna uma lista de todos os usuários do sistema.</p>
                        
                        <h4>Cabeçalhos</h4>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Cabeçalho</th>
                                        <th>Tipo</th>
                                        <th>Obrigatório</th>
                                        <th>Descrição</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Authorization</td>
                                        <td>string</td>
                                        <td><span class="required">Sim</span></td>
                                        <td>Token de autenticação no formato <code>Bearer {token}</code></td>
                                    </tr>
                                    <tr>
                                        <td>Content-Type</td>
                                        <td>string</td>
                                        <td>Não</td>
                                        <td>Deve ser definido como <code>application/json</code></td>
                                    </tr>
                                    <tr>
                                        <td>Accept</td>
                                        <td>string</td>
                                        <td>Não</td>
                                        <td>Define o tipo de resposta esperada</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Obter Usuário -->
                    <div id="get-user" class="endpoint-card">
                        <div class="endpoint-header">
                            <h3>Obter Usuário</h3>
                            <span class="method get">GET</span>
                        </div>
                        <div class="endpoint-url">http://127.0.0.1:8000/api/v1/users/{id}</div>
                        <p class="endpoint-description">Retorna informações detalhadas de um usuário específico.</p>
                        
                        <h4>Parâmetros de URL</h4>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Parâmetro</th>
                                        <th>Tipo</th>
                                        <th>Obrigatório</th>
                                        <th>Descrição</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>id</td>
                                        <td>integer</td>
                                        <td><span class="required">Sim</span></td>
                                        <td>ID do usuário a ser recuperado</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <h4>Cabeçalhos</h4>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Cabeçalho</th>
                                        <th>Tipo</th>
                                        <th>Obrigatório</th>
                                        <th>Descrição</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Authorization</td>
                                        <td>string</td>
                                        <td><span class="required">Sim</span></td>
                                        <td>Token de autenticação no formato <code>Bearer {token}</code></td>
                                    </tr>
                                    <tr>
                                        <td>Accept</td>
                                        <td>string</td>
                                        <td>Não</td>
                                        <td>Define o tipo de resposta esperada</td>
                                    </tr>
                                    <tr>
                                        <td>Content-Type</td>
                                        <td>string</td>
                                        <td>Não</td>
                                        <td>Deve ser definido como <code>application/json</code></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <h4>Exemplo de Requisição</h4>
                        <pre><code>GET http://127.0.0.1:8000/api/v1/users/2
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...</code></pre>
                    </div>
                </section>

                <!-- Exemplos -->
                <section id="exemplos" class="section">
                    <h2><i class="fas fa-code"></i> Exemplos de Uso</h2>
                    <p>Abaixo estão exemplos completos de como utilizar a API em diferentes linguagens de programação.</p>
                    
                    <h3>Exemplo 1: Login em Python</h3>
                    <pre><code>import requests

url = "http://127.0.0.1:8000/api/v1/auth/login"
payload = {
    "email": "usuario@exemplo.com",
    "password": "senha123"
}
headers = {
    "Content-Type": "application/json",
    "Accept": "application/json"
}

response = requests.post(url, json=payload, headers=headers)
data = response.json()

if response.status_code == 200:
    token = data.get("token")
    print(f"Token de autenticação: {token}")
else:
    print(f"Erro: {data.get('message')}")</code></pre>
                    
                    <h3>Exemplo 2: Listar Usuários em JavaScript (Fetch)</h3>
                    <pre><code>const token = "SEU_TOKEN_AQUI";
const url = "http://127.0.0.1:8000/api/v1/users";

fetch(url, {
    method: "GET",
    headers: {
        "Authorization": `Bearer ${token}`,
        "Content-Type": "application/json",
        "Accept": "application/json"
    }
})
.then(response => response.json())
.then(data => {
    console.log("Usuários:", data);
})
.catch(error => {
    console.error("Erro:", error);
});</code></pre>
                    
                    <h3>Exemplo 3: cURL - Fluxo Completo</h3>
                    <pre><code># 1. Autenticação
curl -X POST http://127.0.0.1:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"usuario@exemplo.com","password":"senha123"}'

# 2. Listar usuários (com token obtido no passo 1)
curl -X GET http://127.0.0.1:8000/api/v1/users \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  -H "Accept: application/json"

# 3. Obter usuário específico
curl -X GET http://127.0.0.1:8000/api/v1/users/2 \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  -H "Accept: application/json"

# 4. Logout
curl -X POST http://127.0.0.1:8000/api/v1/auth/logout \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  -H "Content-Type: application/json"</code></pre>
                </section>

                <!-- Códigos de Erro -->
                <section id="erros" class="section">
                    <h2><i class="fas fa-exclamation-triangle"></i> Códigos de Erro</h2>
                    <p>A API retorna códigos de status HTTP padrão para indicar o resultado das requisições.</p>
                    
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Nome</th>
                                    <th>Descrição</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>200</td>
                                    <td>OK</td>
                                    <td>A requisição foi bem-sucedida</td>
                                </tr>
                                <tr>
                                    <td>201</td>
                                    <td>Created</td>
                                    <td>Recurso criado com sucesso</td>
                                </tr>
                                <tr>
                                    <td>400</td>
                                    <td>Bad Request</td>
                                    <td>A requisição está malformada ou contém parâmetros inválidos</td>
                                </tr>
                                <tr>
                                    <td>401</td>
                                    <td>Unauthorized</td>
                                    <td>Autenticação necessária ou token inválido</td>
                                </tr>
                                <tr>
                                    <td>403</td>
                                    <td>Forbidden</td>
                                    <td>Usuário não tem permissão para acessar o recurso</td>
                                </tr>
                                <tr>
                                    <td>404</td>
                                    <td>Not Found</td>
                                    <td>Recurso não encontrado</td>
                                </tr>
                                <tr>
                                    <td>500</td>
                                    <td>Internal Server Error</td>
                                    <td>Erro interno do servidor</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="note">
                        <h4><i class="fas fa-lightbulb"></i> Boas Práticas</h4>
                        <p>Sempre verifique o código de status HTTP antes de processar a resposta da API. Em caso de erro (códigos 4xx ou 5xx), a resposta geralmente incluirá um objeto JSON com detalhes sobre o erro.</p>
                    </div>
                </section>
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div>
                    <p>&copy; 2023 AshLite ERP Soys. Todos os direitos reservados.</p>
                </div>
                <div class="footer-links">
                    <a href="#"><i class="fab fa-github"></i> GitHub</a>
                    <a href="#"><i class="fas fa-question-circle"></i> Suporte</a>
                    <a href="#"><i class="fas fa-envelope"></i> Contato</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if(targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if(targetElement) {
                    // Update active link in sidebar
                    document.querySelectorAll('.sidebar a').forEach(link => {
                        link.classList.remove('active');
                    });
                    this.classList.add('active');
                    
                    // Scroll to target
                    window.scrollTo({
                        top: targetElement.offsetTop - 20,
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // Highlight active section in sidebar on scroll
        window.addEventListener('scroll', function() {
            const sections = document.querySelectorAll('.section');
            const sidebarLinks = document.querySelectorAll('.sidebar a[href^="#"]');
            
            let currentSectionId = '';
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop - 100;
                const sectionHeight = section.clientHeight;
                
                if(window.scrollY >= sectionTop && window.scrollY < sectionTop + sectionHeight) {
                    currentSectionId = '#' + section.getAttribute('id');
                }
            });
            
            sidebarLinks.forEach(link => {
                link.classList.remove('active');
                if(link.getAttribute('href') === currentSectionId) {
                    link.classList.add('active');
                }
            });
        });
        
        // Copy code blocks functionality
        document.querySelectorAll('pre').forEach(preBlock => {
            const copyButton = document.createElement('button');
            copyButton.innerHTML = '<i class="far fa-copy"></i>';
            copyButton.className = 'copy-button';
            copyButton.style.cssText = `
                position: absolute;
                top: 10px;
                right: 10px;
                background: rgba(255,255,255,0.2);
                border: none;
                color: white;
                padding: 5px 10px;
                border-radius: 4px;
                cursor: pointer;
                font-size: 12px;
            `;
            
            preBlock.style.position = 'relative';
            preBlock.appendChild(copyButton);
            
            copyButton.addEventListener('click', function() {
                const code = preBlock.querySelector('code').innerText;
                navigator.clipboard.writeText(code).then(() => {
                    const originalText = copyButton.innerHTML;
                    copyButton.innerHTML = '<i class="fas fa-check"></i>';
                    copyButton.style.background = '#10b981';
                    
                    setTimeout(() => {
                        copyButton.innerHTML = originalText;
                        copyButton.style.background = 'rgba(255,255,255,0.2)';
                    }, 2000);
                });
            });
        });
    </script>
</body>
</html>