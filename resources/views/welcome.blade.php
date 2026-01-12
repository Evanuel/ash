<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>API Client - HTTP Request Tool</title>
    <style>
        :root {
            --primary-color: #4a6fa5;
            --secondary-color: #166088;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --border-radius: 0.5rem;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
        }
        
        /* Header/Navigation */
        .navbar {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }
        
        .logo-icon {
            font-size: 1.75rem;
        }
        
        .logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .nav-link {
            text-decoration: none;
            color: #555;
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            transition: var(--transition);
            font-weight: 500;
        }
        
        .nav-link:hover {
            background-color: #f0f7ff;
            color: var(--primary-color);
        }
        
        .nav-link.active {
            background-color: #f0f7ff;
            color: var(--primary-color);
        }
        
        .btn-logout {
            background: none;
            border: none;
            color: #555;
            cursor: pointer;
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
        }
        
        .btn-logout:hover {
            background-color: #fee;
            color: var(--danger-color);
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1.5rem;
        }
        
        header {
            text-align: center;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
        }
        
        h1 {
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            font-size: 2rem;
        }
        
        .subtitle {
            color: #666;
            font-size: 1.1rem;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .app-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .card {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            padding: 1.25rem 1.5rem;
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .card-header:hover {
            background-color: #f0f7ff;
        }
        
        .card-header.collapsed {
            border-bottom: none;
        }
        
        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--secondary-color);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .card-title-icon {
            font-size: 1.1em;
        }
        
        .collapse-toggle {
            background: none;
            border: none;
            font-size: 1.25rem;
            color: #6c757d;
            cursor: pointer;
            transition: var(--transition);
            padding: 0.25rem 0.5rem;
            border-radius: var(--border-radius);
        }
        
        .collapse-toggle:hover {
            background-color: #e9ecef;
            color: var(--primary-color);
        }
        
        .card-content {
            padding: 1.5rem;
            max-height: 1000px;
            overflow: visible;
            transition: var(--transition);
        }
        
        .card-content.collapsed {
            max-height: 0;
            padding-top: 0;
            padding-bottom: 0;
            overflow: hidden;
        }
        
        .form-group {
            margin-bottom: 1.25rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #555;
        }
        
        input, select, textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
        }
        
        input:focus, select:focus, textarea:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(74, 111, 165, 0.2);
        }
        
        .method-selector {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }
        
        .method-btn {
            padding: 0.625rem 1.25rem;
            border: 2px solid #ddd;
            border-radius: var(--border-radius);
            background-color: white;
            cursor: pointer;
            font-weight: 600;
            transition: var(--transition);
            flex: 1;
            min-width: 70px;
        }
        
        .method-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .method-btn.active {
            border-color: transparent;
            color: white;
        }
        
        .method-btn.get {
            border-color: #28a745;
            color: #28a745;
        }
        
        .method-btn.get.active {
            background-color: #28a745;
        }
        
        .method-btn.post {
            border-color: #ffc107;
            color: #ffc107;
        }
        
        .method-btn.post.active {
            background-color: #ffc107;
        }
        
        .method-btn.put {
            border-color: #007bff;
            color: #007bff;
        }
        
        .method-btn.put.active {
            background-color: #007bff;
        }
        
        .method-btn.patch {
            border-color: #6f42c1;
            color: #6f42c1;
        }
        
        .method-btn.patch.active {
            background-color: #6f42c1;
        }
        
        .method-btn.delete {
            border-color: #dc3545;
            color: #dc3545;
        }
        
        .method-btn.delete.active {
            background-color: #dc3545;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .btn:active {
            transform: translateY(0);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
        }
        
        .btn-success {
            background-color: var(--success-color);
            color: white;
        }
        
        .btn-success:hover {
            background-color: #218838;
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #c82333;
        }
        
        .btn-light {
            background-color: #f8f9fa;
            color: #333;
            border: 1px solid #dee2e6;
        }
        
        .btn-light:hover {
            background-color: #e9ecef;
        }
        
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
        
        .actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.5rem;
            flex-wrap: wrap;
        }
        
        .response-section {
            margin-top: 1.5rem;
        }
        
        .response-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.875rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        
        .status-2xx {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-3xx {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        .status-4xx {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .status-5xx {
            background-color: #f5c6cb;
            color: #721c24;
        }
        
        .response-content {
            background-color: #f8f9fa;
            border-radius: var(--border-radius);
            padding: 1.25rem;
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
            font-size: 0.875rem;
            white-space: pre-wrap;
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #e9ecef;
        }
        
        .response-iframe-container {
            background-color: white;
            border-radius: var(--border-radius);
            border: 1px solid #e9ecef;
            height: 400px;
            overflow: hidden;
            position: relative;
        }
        
        .response-iframe-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        
        .iframe-placeholder {
            padding: 3rem 1.5rem;
            text-align: center;
            color: #777;
            background-color: #f8f9fa;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        .tabs {
            display: flex;
            border-bottom: 2px solid #e9ecef;
            margin-bottom: 1.25rem;
            overflow-x: auto;
            scrollbar-width: none;
        }
        
        .tabs::-webkit-scrollbar {
            display: none;
        }
        
        .tab {
            padding: 0.875rem 1.5rem;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: var(--transition);
            white-space: nowrap;
            font-weight: 500;
            color: #6c757d;
        }
        
        .tab:hover {
            color: var(--primary-color);
            background-color: #f8f9fa;
        }
        
        .tab.active {
            border-bottom-color: var(--primary-color);
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .tab-content.active {
            display: block;
        }
        
        .auth-section {
            background-color: #f0f7ff;
            padding: 1.25rem;
            border-radius: var(--border-radius);
            border-left: 4px solid var(--primary-color);
            margin-bottom: 1.5rem;
        }
        
        .auth-section h3 {
            margin-bottom: 0.75rem;
            color: var(--secondary-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .token-display {
            background-color: white;
            padding: 1rem;
            border-radius: var(--border-radius);
            font-family: monospace;
            font-size: 0.8125rem;
            word-break: break-all;
            margin: 0.75rem 0;
            border: 1px solid #d1e7ff;
        }
        
        .history-section {
            margin-top: 2rem;
        }
        
        .history-list {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #e9ecef;
            border-radius: var(--border-radius);
            background: white;
        }
        
        .history-item {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f1f3f4;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: var(--transition);
        }
        
        .history-item:hover {
            background-color: #f8f9fa;
        }
        
        .history-item:last-child {
            border-bottom: none;
        }
        
        .history-method {
            font-weight: 700;
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-size: 0.75rem;
            color: white;
            min-width: 60px;
            text-align: center;
        }
        
        .history-method.get { background-color: #28a745; }
        .history-method.post { background-color: #ffc107; }
        .history-method.put { background-color: #007bff; }
        .history-method.delete { background-color: #dc3545; }
        .history-method.patch { background-color: #6f42c1; }
        
        .clear-history {
            margin-top: 1rem;
            text-align: right;
        }
        
        .loading {
            display: none;
            text-align: center;
            padding: 3rem 1.5rem;
        }
        
        .loading.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        
        .spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top: 4px solid var(--primary-color);
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        footer {
            text-align: center;
            margin-top: 3rem;
            color: #777;
            font-size: 0.875rem;
            border-top: 1px solid #e9ecef;
            padding-top: 2rem;
            background: white;
            padding: 2rem 1.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
        }
        
        .json-key {
            color: #d32f2f;
        }
        
        .json-string {
            color: #388e3c;
        }
        
        .json-number {
            color: #1976d2;
        }
        
        .json-boolean {
            color: #7b1fa2;
        }
        
        .json-null {
            color: #455a64;
        }
        
        .preview-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .preview-info {
            font-size: 0.875rem;
            color: #666;
        }
        
        .help-text {
            font-size: 0.8125rem;
            color: #6c757d;
            margin-top: 0.5rem;
        }
        
        .url-display {
            background-color: #f8f9fa;
            padding: 0.75rem 1rem;
            border-radius: var(--border-radius);
            font-family: monospace;
            font-size: 0.875rem;
            word-break: break-all;
            margin-top: 0.5rem;
            border: 1px solid #e9ecef;
        }
        
        /* Responsividade */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            .nav-container {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .nav-links {
                justify-content: center;
            }
            
            .card-content {
                padding: 1.25rem;
            }
            
            .method-btn {
                min-width: 60px;
                padding: 0.5rem 0.75rem;
                font-size: 0.875rem;
            }
            
            .btn {
                padding: 0.625rem 1.25rem;
                font-size: 0.875rem;
                width: 100%;
            }
            
            .actions {
                flex-direction: column;
            }
            
            .response-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .tabs {
                flex-wrap: nowrap;
                overflow-x: auto;
            }
            
            .tab {
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
            }
        }
        
        @media (max-width: 480px) {
            h1 {
                font-size: 1.5rem;
            }
            
            .subtitle {
                font-size: 1rem;
            }
            
            .card-title {
                font-size: 1.125rem;
            }
            
            .logo-text {
                font-size: 1.25rem;
            }
        }
        
        /* Animações */
        .fade-in {
            animation: fadeIn 0.5s ease;
        }
        
        .slide-up {
            animation: slideUp 0.3s ease;
        }
        
        @keyframes slideUp {
            from {
                transform: translateY(10px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        /* Scrollbar personalizada */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        /* Utilitários */
        .d-none {
            display: none !important;
        }
        
        .d-flex {
            display: flex !important;
        }
        
        .w-100 {
            width: 100%;
        }
        
        .mt-2 {
            margin-top: 0.5rem;
        }
        
        .mt-3 {
            margin-top: 1rem;
        }
        
        .mb-2 {
            margin-bottom: 0.5rem;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-muted {
            color: #6c757d !important;
        }
        
        /* Alertas */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: var(--border-radius);
            margin-bottom: 1rem;
            border-left: 4px solid transparent;
        }
        
        .alert-success {
            background-color: #d4edda;
            border-color: #28a745;
            color: #155724;
        }
        
        .alert-warning {
            background-color: #fff3cd;
            border-color: #ffc107;
            color: #856404;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
        }
        
        .alert-info {
            background-color: #d1ecf1;
            border-color: #17a2b8;
            color: #0c5460;
        }
        
        .auth-actions {
            margin-top: 1rem;
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="{{ url('/') }}" class="logo">
                <span class="logo-icon">🌐</span>
                <span class="logo-text">API Client</span>
            </a>
            
            <div class="nav-links">
                <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                    Dashboard
                </a>
                <a href="#" class="nav-link active">
                    API Client
                </a>
                @if(auth()->check())
                <form method="POST" action="{{ route('api.v1.auth.logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-logout">
                        Logout ({{ auth()->user()->name ?? 'User' }})
                    </button>
                </form>
                @else
                <a href="{{ route('api.v1.auth.login') }}" class="nav-link">
                    Login
                </a>
                @endif
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container">
        <header class="fade-in">
            <h1>🌐 API Client</h1>
            <p class="subtitle">Ferramenta para fazer requisições HTTP similar ao Postman/CURL - Tudo em um único arquivo</p>
        </header>
        
        <div class="app-container">
            <!-- Card 1: Configuração da Requisição (com collapse) -->
            <div class="card slide-up">
                <div class="card-header" id="request-header">
                    <h2 class="card-title">
                        <span class="card-title-icon">⚙️</span>
                        Configurar Requisição
                    </h2>
                    <button class="collapse-toggle" id="collapse-toggle">
                        <span id="collapse-icon">▼</span>
                    </button>
                </div>
                <div class="card-content" id="request-content">
                    <!-- Método HTTP -->
                    <div class="form-group">
                        <label>Método HTTP</label>
                        <div class="method-selector">
                            <button type="button" class="method-btn get" data-method="GET">GET</button>
                            <button type="button" class="method-btn post" data-method="POST">POST</button>
                            <button type="button" class="method-btn put" data-method="PUT">PUT</button>
                            <button type="button" class="method-btn patch" data-method="PATCH">PATCH</button>
                            <button type="button" class="method-btn delete" data-method="DELETE">DELETE</button>
                        </div>
                        <input type="text" id="method-input" value="GET" readonly>
                    </div>
                    
                    <!-- URL -->
                    <div class="form-group">
                        <label for="url-input">URL / Endpoint</label>
                        <input type="text" id="url-input" placeholder="https://api.exemplo.com/endpoint" value="http://127.0.0.1:8000/api/v1/auth/login">
                        <div class="url-display d-none" id="url-preview"></div>
                    </div>
                    
                    <!-- Tabs (Headers, Body, Params, Auth) -->
                    <div class="tabs">
                        <div class="tab active" data-tab="headers">Headers</div>
                        <div class="tab" data-tab="body">Body</div>
                        <div class="tab" data-tab="params">Variáveis de Ambiente</div>
                        <div class="tab" data-tab="auth">Autenticação</div>
                    </div>
                    
                    <!-- Headers Tab -->
                    <div id="headers-tab" class="tab-content active">
                        <div class="form-group">
                            <label for="headers-input">Headers (JSON)</label>
                            <textarea id="headers-input" rows="6" placeholder='{
  "Content-Type": "application/json",
  "Authorization": "Bearer token_aqui"
}'>{
  "Content-Type": "application/json"
}</textarea>
                        </div>
                        <div class="actions">
                            <button id="prettify-headers-btn" class="btn btn-light">Formatar JSON</button>
                        </div>
                    </div>
                    
                    <!-- Body Tab -->
                    <div id="body-tab" class="tab-content">
                        <div class="form-group">
                            <label for="body-input">Body (JSON ou texto)</label>
                            <textarea id="body-input" rows="10" placeholder='{
  "email": "admin@exemplo.com",
  "password": "senha123",
  "device_name": "api-client"
}'>{
  "email": "admin@ash.elf.eng.br",
  "password": "@dmin#2026",
  "device_name": "curl"
}</textarea>
                        </div>
                        <div class="actions">
                            <button id="prettify-body-btn" class="btn btn-light">Formatar JSON</button>
                            <button id="clear-body-btn" class="btn btn-light">Limpar Body</button>
                        </div>
                    </div>
                    
                    <!-- Environment Variables Tab -->
                    <div id="params-tab" class="tab-content">
                        <div class="form-group">
                            <label for="env-vars-input">Variáveis de Ambiente (JSON)</label>
                            <textarea id="env-vars-input" rows="6" placeholder='{
  "base_url": "http://127.0.0.1:8000",
  "api_version": "v1"
}'>{
  "base_url": "http://127.0.0.1:8000",
  "api_version": "v1"
}</textarea>
                            <p class="help-text">Use no URL como [[base_url]]/api/[[api_version]]/endpoint</p>
                        </div>
                    </div>
                    
                    <!-- Authentication Tab -->
                    <div id="auth-tab" class="tab-content">
                        <div class="auth-section">
                            <h3>🔐 Status de Autenticação</h3>
                            <p id="auth-status">Nenhum token de autenticação armazenado.</p>
                            <div id="token-display" class="token-display d-none">
                                <strong>Token:</strong> <span id="token-value"></span>
                            </div>
                            <div class="auth-actions">
                                <button id="add-auth-header-btn" class="btn btn-light">Adicionar ao Header</button>
                                <button id="clear-token-btn" class="btn btn-light">Limpar Token</button>
                                <button id="extract-token-btn" class="btn btn-success">Extrair Token da Resposta</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="token-type-input">Tipo de Token</label>
                            <select id="token-type-input">
                                <option value="Bearer" selected>Bearer</option>
                                <option value="Basic">Basic</option>
                                <option value="Token">Token</option>
                                <option value="JWT">JWT</option>
                                <option value="Custom">Custom</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="custom-token-input">Token Customizado</label>
                            <input type="text" id="custom-token-input" placeholder="Insira seu token manualmente">
                        </div>
                    </div>
                    
                    <!-- Botões de ação -->
                    <div class="actions">
                        <button id="send-btn" class="btn btn-primary">
                            <span>➤</span> Enviar Requisição
                        </button>
                        <button id="clear-btn" class="btn btn-light">Limpar</button>
                        <button id="example-users-btn" class="btn btn-light">Exemplo: API Usuários</button>
                        <button id="example-html-btn" class="btn btn-light">Exemplo: Site HTML</button>
                    </div>
                </div>
            </div>
            
            <!-- Card 2: Painel de Resposta -->
            <div class="card slide-up">
                <div class="card-header">
                    <h2 class="card-title">
                        <span class="card-title-icon">📨</span>
                        Resposta da API
                    </h2>
                </div>
                <div class="card-content">
                    <!-- Loading -->
                    <div class="loading" id="loading">
                        <div class="spinner"></div>
                        <p>Enviando requisição...</p>
                    </div>
                    
                    <!-- Response Section -->
                    <div id="response-section" class="response-section d-none">
                        <div class="response-header">
                            <h3>Detalhes da Resposta</h3>
                            <div id="status-badge" class="status-badge">200 OK</div>
                        </div>
                        
                        <!-- Response Tabs -->
                        <div class="tabs">
                            <div class="tab active" data-response-tab="body">Body</div>
                            <div class="tab" data-response-tab="preview">Preview</div>
                            <div class="tab" data-response-tab="headers">Headers</div>
                            <div class="tab" data-response-tab="raw">Raw</div>
                        </div>
                        
                        <!-- Body Tab -->
                        <div id="response-body-tab" class="tab-content active">
                            <div class="response-content" id="response-body"></div>
                        </div>
                        
                        <!-- Preview Tab -->
                        <div id="response-preview-tab" class="tab-content">
                            <div id="preview-controls" class="preview-controls">
                                <div class="preview-info" id="preview-info">
                                    Preview da resposta (HTML/Texto)
                                </div>
                                <div>
                                    <button id="refresh-preview-btn" class="btn btn-light btn-sm">Atualizar Preview</button>
                                </div>
                            </div>
                            <div class="response-iframe-container" id="preview-container">
                                <div id="preview-placeholder" class="iframe-placeholder">
                                    <p>O preview será exibido aqui para respostas HTML ou texto</p>
                                    <p><small>Para respostas JSON, será exibido como texto formatado</small></p>
                                </div>
                                <iframe id="preview-iframe" class="d-none"></iframe>
                                <div id="preview-text" class="d-none"></div>
                            </div>
                        </div>
                        
                        <!-- Headers Tab -->
                        <div id="response-headers-tab" class="tab-content">
                            <div class="response-content" id="response-headers"></div>
                        </div>
                        
                        <!-- Raw Tab -->
                        <div id="response-raw-tab" class="tab-content">
                            <div class="response-content" id="response-raw"></div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="actions">
                            <button id="copy-response-btn" class="btn btn-light">Copiar Resposta</button>
                            <button id="save-response-btn" class="btn btn-light">Salvar Resposta</button>
                            <button id="copy-token-btn" class="btn btn-success">Copiar Token</button>
                        </div>
                    </div>
                    
                    <!-- No Response Placeholder -->
                    <div id="no-response" class="text-center" style="padding: 3rem 1.5rem; color: #777;">
                        <p style="font-size: 1.1rem; margin-bottom: 1rem;">Nenhuma requisição enviada ainda.</p>
                        <p>Configure sua requisição e clique em "Enviar Requisição".</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Card 3: Histórico de requisições -->
        <div class="card history-section slide-up">
            <div class="card-header">
                <h2 class="card-title">
                    <span class="card-title-icon">📜</span>
                    Histórico de Requisições
                </h2>
            </div>
            <div class="card-content">
                <div id="history-list" class="history-list"></div>
                <div class="clear-history">
                    <button id="clear-history-btn" class="btn btn-light">Limpar Histórico</button>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <footer>
            <p><strong>API Client v2.0</strong> - Ferramenta para desenvolvedores API</p>
            <p class="text-muted">Desenvolvido com Laravel, HTML, CSS e JavaScript puro</p>
            <p class="text-muted mt-2">Todas as funcionalidades em um único arquivo Blade</p>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ============================================
            // ELEMENTOS DOM
            // ============================================
            const methodButtons = document.querySelectorAll('.method-btn');
            const methodInput = document.getElementById('method-input');
            const urlInput = document.getElementById('url-input');
            const urlPreview = document.getElementById('url-preview');
            const headersInput = document.getElementById('headers-input');
            const bodyInput = document.getElementById('body-input');
            const envVarsInput = document.getElementById('env-vars-input');
            
            // Botões principais
            const sendButton = document.getElementById('send-btn');
            const clearButton = document.getElementById('clear-btn');
            const exampleUsersBtn = document.getElementById('example-users-btn');
            const exampleHtmlBtn = document.getElementById('example-html-btn');
            
            // Elementos de resposta
            const loadingElement = document.getElementById('loading');
            const responseSection = document.getElementById('response-section');
            const noResponseElement = document.getElementById('no-response');
            const statusBadge = document.getElementById('status-badge');
            const responseBody = document.getElementById('response-body');
            const responseHeaders = document.getElementById('response-headers');
            const responseRaw = document.getElementById('response-raw');
            
            // Botões de resposta
            const copyResponseButton = document.getElementById('copy-response-btn');
            const saveResponseButton = document.getElementById('save-response-btn');
            const copyTokenButton = document.getElementById('copy-token-btn');
            
            // Autenticação
            const addAuthHeaderButton = document.getElementById('add-auth-header-btn');
            const clearTokenButton = document.getElementById('clear-token-btn');
            const extractTokenButton = document.getElementById('extract-token-btn');
            const authStatusElement = document.getElementById('auth-status');
            const tokenValueElement = document.getElementById('token-value');
            const tokenDisplayElement = document.getElementById('token-display');
            const tokenTypeInput = document.getElementById('token-type-input');
            const customTokenInput = document.getElementById('custom-token-input');
            
            // História
            const historyList = document.getElementById('history-list');
            const clearHistoryButton = document.getElementById('clear-history-btn');
            
            // Preview
            const previewContainer = document.getElementById('preview-container');
            const previewIframe = document.getElementById('preview-iframe');
            const previewText = document.getElementById('preview-text');
            const previewPlaceholder = document.getElementById('preview-placeholder');
            const refreshPreviewButton = document.getElementById('refresh-preview-btn');
            const previewInfo = document.getElementById('preview-info');
            
            // Botões de formatação
            const prettifyHeadersBtn = document.getElementById('prettify-headers-btn');
            const prettifyBodyBtn = document.getElementById('prettify-body-btn');
            const clearBodyBtn = document.getElementById('clear-body-btn');
            
            // Tabs
            const tabs = document.querySelectorAll('.tab');
            const responseTabs = document.querySelectorAll('[data-response-tab]');
            
            // Collapse
            const collapseToggle = document.getElementById('collapse-toggle');
            const collapseIcon = document.getElementById('collapse-icon');
            const requestHeader = document.getElementById('request-header');
            const requestContent = document.getElementById('request-content');
            
            // ============================================
            // ESTADO DA APLICAÇÃO
            // ============================================
            let authToken = localStorage.getItem('api_client_token');
            let tokenType = localStorage.getItem('api_client_token_type') || 'Bearer';
            let requestHistory = JSON.parse(localStorage.getItem('api_client_history') || '[]');
            let currentResponse = null;
            let isCollapsed = false;
            
            // ============================================
            // INICIALIZAÇÃO
            // ============================================
            init();
            
            function init() {
                updateAuthDisplay();
                renderHistory();
                setMethod('GET');
                
                // Definir tipo de token no select
                tokenTypeInput.value = tokenType;
                
                // Atualizar preview da URL quando variáveis mudam
                urlInput.addEventListener('input', updateUrlPreview);
                envVarsInput.addEventListener('input', updateUrlPreview);
                
                // Inicializar tabs
                switchTab('headers');
                switchResponseTab('body');
                
                // Atualizar preview inicial
                updateUrlPreview();
                
                // Configurar collapse
                setupCollapse();
            }
            
            function setupCollapse() {
                collapseToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleCollapse();
                });
                
                requestHeader.addEventListener('click', function() {
                    if (isCollapsed) {
                        toggleCollapse();
                    }
                });
            }
            
            function toggleCollapse() {
                isCollapsed = !isCollapsed;
                
                if (isCollapsed) {
                    requestContent.classList.add('collapsed');
                    requestHeader.classList.add('collapsed');
                    collapseIcon.textContent = '▶';
                } else {
                    requestContent.classList.remove('collapsed');
                    requestHeader.classList.remove('collapsed');
                    collapseIcon.textContent = '▼';
                }
            }
            
            // ============================================
            // EVENT LISTENERS
            // ============================================
            methodButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const method = this.getAttribute('data-method');
                    setMethod(method);
                });
            });
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');
                    switchTab(tabId);
                });
            });
            
            responseTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-response-tab');
                    switchResponseTab(tabId);
                });
            });
            
            sendButton.addEventListener('click', sendRequest);
            clearButton.addEventListener('click', clearForm);
            exampleUsersBtn.addEventListener('click', loadUsersExample);
            exampleHtmlBtn.addEventListener('click', loadHtmlExample);
            
            copyResponseButton.addEventListener('click', copyResponse);
            saveResponseButton.addEventListener('click', saveResponse);
            copyTokenButton.addEventListener('click', copyToken);
            
            addAuthHeaderButton.addEventListener('click', addAuthHeader);
            clearTokenButton.addEventListener('click', clearToken);
            extractTokenButton.addEventListener('click', extractTokenFromResponse);
            tokenTypeInput.addEventListener('change', updateTokenType);
            customTokenInput.addEventListener('input', handleCustomToken);
            
            prettifyHeadersBtn.addEventListener('click', () => prettifyJson(headersInput));
            prettifyBodyBtn.addEventListener('click', () => prettifyJson(bodyInput));
            clearBodyBtn.addEventListener('click', () => bodyInput.value = '');
            
            clearHistoryButton.addEventListener('click', clearHistory);
            refreshPreviewButton.addEventListener('click', refreshPreview);
            
            // Atalhos de teclado
            document.addEventListener('keydown', function(e) {
                // Ctrl+Enter para enviar
                if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                    e.preventDefault();
                    sendRequest();
                }
                
                // Ctrl+L para limpar
                if ((e.ctrlKey || e.metaKey) && e.key === 'l') {
                    e.preventDefault();
                    clearForm();
                }
                
                // Ctrl+H para histórico
                if ((e.ctrlKey || e.metaKey) && e.key === 'h') {
                    e.preventDefault();
                    document.querySelector('.history-section').scrollIntoView({ 
                        behavior: 'smooth' 
                    });
                }
                
                // Ctrl+1,2,3,4 para tabs
                if ((e.ctrlKey || e.metaKey) && e.key >= '1' && e.key <= '4') {
                    e.preventDefault();
                    const tabIndex = parseInt(e.key) - 1;
                    const tabsArray = Array.from(tabs).filter(t => t.getAttribute('data-tab'));
                    if (tabsArray[tabIndex]) {
                        tabsArray[tabIndex].click();
                    }
                }
            });
            
            // ============================================
            // FUNÇÕES PRINCIPAIS
            // ============================================
            function setMethod(method) {
                methodButtons.forEach(btn => {
                    btn.classList.remove('active');
                });
                
                const activeButton = document.querySelector(`.method-btn[data-method="${method}"]`);
                if (activeButton) {
                    activeButton.classList.add('active');
                }
                
                methodInput.value = method;
                
                // Limpar body para GET e HEAD
                if (method === 'GET' || method === 'HEAD') {
                    bodyInput.value = '';
                }
            }
            
            function switchTab(tabId) {
                tabs.forEach(tab => {
                    tab.classList.remove('active');
                    if (tab.getAttribute('data-tab') === tabId) {
                        tab.classList.add('active');
                    }
                });
                
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                });
                
                const tabElement = document.getElementById(`${tabId}-tab`);
                if (tabElement) {
                    tabElement.classList.add('active');
                }
            }
            
            function switchResponseTab(tabId) {
                responseTabs.forEach(tab => {
                    tab.classList.remove('active');
                    if (tab.getAttribute('data-response-tab') === tabId) {
                        tab.classList.add('active');
                    }
                });
                
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                });
                
                const tabElement = document.getElementById(`response-${tabId}-tab`);
                if (tabElement) {
                    tabElement.classList.add('active');
                }
                
                if (tabId === 'preview' && currentResponse) {
                    updatePreview(currentResponse);
                }
            }
            
            function updateUrlPreview() {
                const url = urlInput.value;
                const processedUrl = processEnvVars(url);
                
                if (processedUrl !== url) {
                    urlPreview.textContent = `URL processada: ${processedUrl}`;
                    urlPreview.classList.remove('d-none');
                } else {
                    urlPreview.classList.add('d-none');
                }
            }
            
            async function sendRequest() {
                const method = methodInput.value;
                let url = urlInput.value.trim();
                
                if (!url) {
                    showAlert('Por favor, informe uma URL', 'warning');
                    return;
                }
                
                // Processar variáveis de ambiente (usa [[ ]])
                url = processEnvVars(url);
                
                // Validar URL
                try {
                    new URL(url);
                } catch (e) {
                    showAlert('URL inválida. Certifique-se de incluir http:// ou https://', 'danger');
                    return;
                }
                
                // Preparar headers
                let headers = {};
                try {
                    headers = JSON.parse(headersInput.value || '{}');
                } catch (e) {
                    showAlert('Erro no formato dos headers. Certifique-se de que é um JSON válido.', 'danger');
                    return;
                }
                
                // Preparar body
                let body = null;
                if (method !== 'GET' && method !== 'HEAD' && bodyInput.value.trim()) {
                    body = bodyInput.value.trim();
                    
                    // Validar JSON se parece com JSON
                    if (body.startsWith('{') || body.startsWith('[')) {
                        try {
                            JSON.parse(body);
                        } catch (e) {
                            if (!confirm('O body parece ser JSON inválido. Deseja enviar como texto?')) {
                                return;
                            }
                        }
                    }
                }
                
                // Mostrar loading
                loadingElement.classList.add('active');
                noResponseElement.style.display = 'none';
                responseSection.classList.add('d-none');
                
                try {
                    const startTime = Date.now();
                    
                    // Fazer requisição
                    const response = await fetch(url, {
                        method: method,
                        headers: headers,
                        body: body,
                        credentials: 'same-origin'
                    });
                    
                    const endTime = Date.now();
                    const responseTime = endTime - startTime;
                    
                    // Obter resposta como texto
                    const responseText = await response.text();
                    
                    // Atualizar UI com resposta
                    updateResponseUI(response, responseText, responseTime);
                    
                    // Salvar no histórico
                    addToHistory({
                        method,
                        url: urlInput.value.trim(),
                        headers: headers,
                        body: body,
                        status: response.status,
                        statusText: response.statusText,
                        timestamp: new Date().toISOString(),
                        responseTime
                    });
                    
                    // Mostrar resposta
                    loadingElement.classList.remove('active');
                    responseSection.classList.remove('d-none');
                    
                    // Mostrar mensagem de sucesso
                    if (response.ok) {
                        showAlert(`Requisição enviada com sucesso! (${responseTime}ms)`, 'success');
                    } else {
                        showAlert(`Erro ${response.status}: ${response.statusText}`, 'warning');
                    }
                    
                } catch (error) {
                    loadingElement.classList.remove('active');
                    
                    // Mostrar erro
                    statusBadge.textContent = 'Erro';
                    statusBadge.className = 'status-badge status-4xx';
                    responseBody.textContent = `Erro na requisição: ${error.message}`;
                    responseHeaders.textContent = '';
                    responseRaw.textContent = '';
                    
                    responseSection.classList.remove('d-none');
                    
                    // Adicionar erro ao histórico
                    addToHistory({
                        method,
                        url: urlInput.value.trim(),
                        headers: headers,
                        body: body,
                        status: 0,
                        statusText: 'Erro de rede',
                        error: error.message,
                        timestamp: new Date().toISOString()
                    });
                    
                    showAlert(`Erro de rede: ${error.message}`, 'danger');
                }
            }
            
            function updateResponseUI(response, responseText, responseTime) {
                // Atualizar status
                statusBadge.textContent = `${response.status} ${response.statusText} (${responseTime}ms)`;
                statusBadge.className = 'status-badge';
                
                if (response.status >= 200 && response.status < 300) {
                    statusBadge.classList.add('status-2xx');
                } else if (response.status >= 300 && response.status < 400) {
                    statusBadge.classList.add('status-3xx');
                } else if (response.status >= 400 && response.status < 500) {
                    statusBadge.classList.add('status-4xx');
                } else if (response.status >= 500) {
                    statusBadge.classList.add('status-5xx');
                }
                
                // Tentar parsear como JSON para formatação
                let responseBodyText = responseText;
                try {
                    const jsonResponse = JSON.parse(responseText);
                    responseBodyText = syntaxHighlight(JSON.stringify(jsonResponse, null, 2));
                } catch (e) {
                    // Não é JSON, manter como texto
                }
                
                // Formatar headers da resposta
                const headersArray = [];
                response.headers.forEach((value, key) => {
                    headersArray.push(`${key}: ${value}`);
                });
                
                responseBody.innerHTML = responseBodyText;
                responseHeaders.textContent = headersArray.join('\n');
                responseRaw.textContent = responseText;
                
                // Armazenar resposta atual para o preview
                currentResponse = {
                    text: responseText,
                    headers: response.headers,
                    contentType: response.headers.get('content-type') || '',
                    status: response.status
                };
            }
            
            function processEnvVars(url) {
                try {
                    const envVars = JSON.parse(envVarsInput.value || '{}');
                    
                    for (const [key, value] of Object.entries(envVars)) {
                        const placeholder = `\\[\\[${key}\\]\\]`;
                        const regex = new RegExp(placeholder, 'g');
                        url = url.replace(regex, value);
                    }
                } catch (e) {
                    console.error('Erro ao processar variáveis de ambiente:', e);
                }
                
                return url;
            }
            
            function prettifyJson(textarea) {
                try {
                    const json = JSON.parse(textarea.value);
                    textarea.value = JSON.stringify(json, null, 2);
                    showAlert('JSON formatado com sucesso!', 'success');
                } catch (e) {
                    showAlert('Não é um JSON válido para formatar', 'warning');
                }
            }
            
            function syntaxHighlight(json) {
                if (typeof json != 'string') {
                    json = JSON.stringify(json, null, 2);
                }
                
                // Escape HTML
                json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                
                // Aplicar estilos
                return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function(match) {
                    let cls = 'json-number';
                    if (/^"/.test(match)) {
                        if (/:$/.test(match)) {
                            cls = 'json-key';
                        } else {
                            cls = 'json-string';
                        }
                    } else if (/true|false/.test(match)) {
                        cls = 'json-boolean';
                    } else if (/null/.test(match)) {
                        cls = 'json-null';
                    }
                    return '<span class="' + cls + '">' + match + '</span>';
                });
            }
            
            function updatePreview(response) {
                const contentType = response.contentType.toLowerCase();
                const responseText = response.text;
                
                // Limpar preview anterior
                previewIframe.classList.add('d-none');
                previewText.classList.add('d-none');
                previewPlaceholder.classList.add('d-none');
                
                // Verificar o tipo de conteúdo
                if (contentType.includes('text/html')) {
                    previewInfo.textContent = 'Preview HTML (iframe)';
                    
                    // Criar blob com o HTML
                    const blob = new Blob([responseText], { type: 'text/html;charset=utf-8' });
                    const url = URL.createObjectURL(blob);
                    
                    previewIframe.src = url;
                    previewIframe.classList.remove('d-none');
                    previewIframe.onload = function() {
                        URL.revokeObjectURL(url);
                    };
                    
                } else if (contentType.includes('application/json')) {
                    previewInfo.textContent = 'Preview JSON (formatado)';
                    
                    try {
                        const jsonObj = JSON.parse(responseText);
                        previewText.innerHTML = syntaxHighlight(JSON.stringify(jsonObj, null, 2));
                        previewText.classList.remove('d-none');
                        previewText.className = 'response-content';
                        previewText.style.height = '350px';
                    } catch (e) {
                        previewText.textContent = 'Erro ao formatar JSON: ' + e.message;
                        previewText.classList.remove('d-none');
                    }
                    
                } else if (contentType.includes('text/') || 
                          contentType.includes('application/xml') ||
                          contentType.includes('application/javascript')) {
                    previewInfo.textContent = 'Preview Texto (' + contentType + ')';
                    
                    previewText.textContent = responseText;
                    previewText.classList.remove('d-none');
                    previewText.className = 'response-content';
                    previewText.style.height = '350px';
                    
                } else {
                    previewInfo.textContent = 'Preview não disponível para este tipo de conteúdo';
                    previewPlaceholder.innerHTML = `
                        <p>Tipo de conteúdo: ${contentType || 'desconhecido'}</p>
                        <p>O preview não está disponível para este tipo de resposta.</p>
                        <p>Use a aba "Raw" para visualizar o conteúdo bruto.</p>
                    `;
                    previewPlaceholder.classList.remove('d-none');
                }
            }
            
            function refreshPreview() {
                if (currentResponse) {
                    updatePreview(currentResponse);
                }
            }
            
            function clearForm() {
                urlInput.value = '';
                headersInput.value = '{\n  "Content-Type": "application/json"\n}';
                bodyInput.value = '';
                envVarsInput.value = '{\n  "base_url": "http://127.0.0.1:8000",\n  "api_version": "v1"\n}';
                responseSection.classList.add('d-none');
                noResponseElement.style.display = 'block';
                currentResponse = null;
                updateUrlPreview();
                showAlert('Formulário limpo', 'info');
            }
            
            function loadUsersExample() {
                setMethod('GET');
                urlInput.value = 'http://127.0.0.1:8000/api/v1/users';
                
                let headers = {};
                try {
                    headers = JSON.parse(headersInput.value || '{}');
                } catch (e) {
                    headers = {};
                }
                
                if (authToken) {
                    headers['Authorization'] = `${tokenType} ${authToken}`;
                }
                
                headers['Content-Type'] = 'application/json';
                headersInput.value = JSON.stringify(headers, null, 2);
                bodyInput.value = '';
                updateUrlPreview();
                showAlert('Exemplo de API de usuários carregado', 'info');
            }
            
            function loadHtmlExample() {
                setMethod('GET');
                urlInput.value = 'https://httpbin.org/html';
                headersInput.value = '{\n  "Accept": "text/html"\n}';
                bodyInput.value = '';
                updateUrlPreview();
                showAlert('Exemplo de site HTML carregado', 'info');
            }
            
            function copyResponse() {
                const activeTab = document.querySelector('[data-response-tab].active');
                if (activeTab) {
                    const tabId = activeTab.getAttribute('data-response-tab');
                    let textToCopy = '';
                    
                    if (tabId === 'body') {
                        textToCopy = responseBody.textContent;
                    } else if (tabId === 'headers') {
                        textToCopy = responseHeaders.textContent;
                    } else if (tabId === 'raw') {
                        textToCopy = responseRaw.textContent;
                    } else if (tabId === 'preview') {
                        textToCopy = currentResponse ? currentResponse.text : '';
                    }
                    
                    navigator.clipboard.writeText(textToCopy).then(() => {
                        const originalText = copyResponseButton.textContent;
                        copyResponseButton.textContent = 'Copiado!';
                        copyResponseButton.classList.add('btn-success');
                        setTimeout(() => {
                            copyResponseButton.textContent = originalText;
                            copyResponseButton.classList.remove('btn-success');
                        }, 2000);
                    });
                }
            }
            
            function saveResponse() {
                if (!currentResponse) return;
                
                const blob = new Blob([currentResponse.text], { type: 'text/plain' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `api-response-${Date.now()}.txt`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
                showAlert('Resposta salva como arquivo', 'success');
            }
            
            function extractTokenFromResponse() {
                try {
                    const rawResponse = responseRaw.textContent;
                    const jsonResponse = JSON.parse(rawResponse);
                    
                    // Procurar token em várias posições possíveis
                    let token = null;
                    let foundTokenType = 'Bearer';
                    
                    if (jsonResponse.data && jsonResponse.data.token) {
                        token = jsonResponse.data.token;
                        if (jsonResponse.data.token_type) {
                            foundTokenType = jsonResponse.data.token_type;
                        }
                    } else if (jsonResponse.token) {
                        token = jsonResponse.token;
                        if (jsonResponse.token_type) {
                            foundTokenType = jsonResponse.token_type;
                        }
                    } else if (jsonResponse.access_token) {
                        token = jsonResponse.access_token;
                        foundTokenType = 'Bearer';
                    } else if (jsonResponse.bearer) {
                        token = jsonResponse.bearer;
                        foundTokenType = 'Bearer';
                    }
                    
                    if (token) {
                        authToken = token;
                        tokenType = foundTokenType;
                        
                        localStorage.setItem('api_client_token', authToken);
                        localStorage.setItem('api_client_token_type', tokenType);
                        tokenTypeInput.value = tokenType;
                        
                        updateAuthDisplay();
                        showAlert('Token extraído e armazenado com sucesso!', 'success');
                    } else {
                        showAlert('Não foi possível encontrar um token na resposta.', 'warning');
                    }
                } catch (e) {
                    showAlert('Erro ao processar a resposta para extrair token.', 'danger');
                }
            }
            
            function copyToken() {
                if (authToken) {
                    navigator.clipboard.writeText(authToken).then(() => {
                        const originalText = copyTokenButton.textContent;
                        copyTokenButton.textContent = 'Token Copiado!';
                        setTimeout(() => {
                            copyTokenButton.textContent = originalText;
                        }, 2000);
                    });
                } else {
                    showAlert('Nenhum token para copiar', 'warning');
                }
            }
            
            function addAuthHeader() {
                if (authToken) {
                    try {
                        let headers = JSON.parse(headersInput.value || '{}');
                        headers['Authorization'] = `${tokenType} ${authToken}`;
                        headersInput.value = JSON.stringify(headers, null, 2);
                        showAlert('Header de autenticação adicionado', 'success');
                    } catch (e) {
                        showAlert('Erro ao adicionar header de autenticação.', 'danger');
                    }
                } else {
                    showAlert('Nenhum token de autenticação armazenado.', 'warning');
                }
            }
            
            function clearToken() {
                authToken = null;
                tokenType = 'Bearer';
                localStorage.removeItem('api_client_token');
                localStorage.removeItem('api_client_token_type');
                tokenTypeInput.value = tokenType;
                customTokenInput.value = '';
                updateAuthDisplay();
                showAlert('Token removido com sucesso!', 'info');
            }
            
            function updateTokenType() {
                tokenType = tokenTypeInput.value;
                localStorage.setItem('api_client_token_type', tokenType);
            }
            
            function handleCustomToken() {
                if (customTokenInput.value.trim()) {
                    authToken = customTokenInput.value.trim();
                    localStorage.setItem('api_client_token', authToken);
                    updateAuthDisplay();
                }
            }
            
            function updateAuthDisplay() {
                if (authToken) {
                    authStatusElement.textContent = 'Token de autenticação armazenado.';
                    tokenValueElement.textContent = `${tokenType} ${authToken.substring(0, 30)}...`;
                    tokenDisplayElement.classList.remove('d-none');
                    copyTokenButton.classList.remove('d-none');
                } else {
                    authStatusElement.textContent = 'Nenhum token de autenticação armazenado.';
                    tokenDisplayElement.classList.add('d-none');
                    copyTokenButton.classList.add('d-none');
                }
            }
            
            function addToHistory(request) {
                requestHistory.unshift(request);
                
                if (requestHistory.length > 50) {
                    requestHistory = requestHistory.slice(0, 50);
                }
                
                localStorage.setItem('api_client_history', JSON.stringify(requestHistory));
                renderHistory();
            }
            
            function renderHistory() {
                historyList.innerHTML = '';
                
                if (requestHistory.length === 0) {
                    historyList.innerHTML = '<div class="history-item"><em>Nenhuma requisição no histórico</em></div>';
                    return;
                }
                
                requestHistory.forEach((item, index) => {
                    const historyItem = document.createElement('div');
                    historyItem.className = 'history-item';
                    
                    const methodClass = item.method.toLowerCase();
                    const date = new Date(item.timestamp);
                    const timeStr = date.toLocaleTimeString('pt-BR', { 
                        hour: '2-digit', 
                        minute: '2-digit' 
                    });
                    
                    historyItem.innerHTML = `
                        <div>
                            <span class="history-method ${methodClass}">${item.method}</span>
                            <span style="margin-left: 0.75rem; font-size: 0.875rem; color: #666;">
                                ${timeStr}
                            </span>
                        </div>
                        <div style="flex: 1; margin: 0 1rem; overflow: hidden;">
                            <div style="font-size: 0.875rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                ${item.url}
                            </div>
                        </div>
                        <div>
                            <span style="font-weight: 600; color: ${item.status >= 400 ? '#dc3545' : item.status >= 200 ? '#28a745' : '#6c757d'}">
                                ${item.status || 'ERR'}
                            </span>
                            ${item.responseTime ? `<span style="margin-left: 0.5rem; font-size: 0.75rem; color: #777;">${item.responseTime}ms</span>` : ''}
                        </div>
                    `;
                    
                    historyItem.addEventListener('click', () => {
                        loadRequestFromHistory(index);
                    });
                    
                    historyList.appendChild(historyItem);
                });
            }
            
            function loadRequestFromHistory(index) {
                const request = requestHistory[index];
                
                setMethod(request.method);
                urlInput.value = request.url;
                headersInput.value = JSON.stringify(request.headers, null, 2);
                bodyInput.value = request.body || '';
                
                updateUrlPreview();
                window.scrollTo(0, 0);
                showAlert('Requisição carregada do histórico', 'info');
            }
            
            function clearHistory() {
                if (confirm('Tem certeza que deseja limpar todo o histórico de requisições?')) {
                    requestHistory = [];
                    localStorage.removeItem('api_client_history');
                    renderHistory();
                    showAlert('Histórico limpo', 'info');
                }
            }
            
            function showAlert(message, type = 'info') {
                // Remover alertas anteriores
                const existingAlerts = document.querySelectorAll('.alert-toast');
                existingAlerts.forEach(alert => alert.remove());
                
                // Criar novo alerta
                const alert = document.createElement('div');
                alert.className = `alert alert-${type} alert-toast`;
                alert.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    z-index: 9999;
                    max-width: 400px;
                    animation: slideUp 0.3s ease;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                `;
                alert.innerHTML = `
                    <div style="display: flex; justify-content: space-between; align-items: start;">
                        <div>${message}</div>
                        <button onclick="this.parentElement.parentElement.remove()" 
                                style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: inherit; margin-left: 1rem;">
                            ×
                        </button>
                    </div>
                `;
                
                document.body.appendChild(alert);
                
                // Remover automaticamente após 5 segundos
                setTimeout(() => {
                    if (alert.parentElement) {
                        alert.remove();
                    }
                }, 5000);
            }
            
            // Expor funções globalmente
            window.prettifyJson = prettifyJson;
        });
    </script>
</body>
</html>