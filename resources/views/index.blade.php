<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Client HTTP</title>
    <link rel="stylesheet" href="{{asset('assets/css/styles.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="app-container">
        <!-- Sidebar Esquerda -->
        <div class="sidebar">
            <div class="sidebar-header">
                <a href="#" class="logo">
                    <div class="logo-icon">A</div>
                    <span class="logo-text">API Client</span>
                </a>
            </div>
            
            <div class="sidebar-nav">
                <a href="#" class="nav-item active" id="new-request-tab">
                    <i class="nav-icon fas fa-rocket"></i>
                    <span class="nav-text">Nova Requisição</span>
                </a>
                <a href="#" class="nav-item" id="history-toggle">
                    <i class="nav-icon fas fa-history"></i>
                    <span class="nav-text">Histórico</span>
                </a>
                <a href="#" class="nav-item" id="collections-toggle">
                    <i class="nav-icon fas fa-folder"></i>
                    <span class="nav-text">Coleções</span>
                    <span class="sidebar-badge" id="collections-count">0</span>
                </a>
                <a href="#" class="nav-item" id="settings-tab">
                    <i class="nav-icon fas fa-cog"></i>
                    <span class="nav-text">Configurações</span>
                </a>
                <a href="#" class="nav-item" id="team-tab">
                    <i class="nav-icon fas fa-users"></i>
                    <span class="nav-text">Equipe</span>
                </a>
                <a href="#" class="nav-item" id="sync-tab">
                    <i class="nav-icon fas fa-cloud"></i>
                    <span class="nav-text">Sync</span>
                </a>
            </div>
            
            <div style="padding: 20px 16px; border-top: 1px solid #1F2430;">
                <div class="user-avatar">
                    {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                </div>
                <div class="text-xs text-muted mt-2" style="text-align: center;">
                    v2.1.0
                </div>
            </div>
        </div>
        
        <!-- Conteúdo Principal -->
        <div class="main-content">
            <!-- Topbar -->
            <div class="topbar">
                <div class="workspace-selector">
                    <i class="fas fa-briefcase"></i>
                    <span>My Workspace</span>
                    <i class="fas fa-chevron-down" style="font-size: 12px;"></i>
                </div>
                
                <div class="user-menu">
                    <button class="btn btn-icon" id="save-collection-btn">
                        <i class="fas fa-save"></i>
                    </button>
                    <button class="btn btn-icon" id="share-btn">
                        <i class="fas fa-share"></i>
                    </button>
                    <button class="btn btn-icon" id="import-btn">
                        <i class="fas fa-upload"></i>
                    </button>
                    <button class="btn btn-icon" id="export-btn">
                        <i class="fas fa-download"></i>
                    </button>
                    @if(auth()->check())
                    <form method="POST" action="{{ route('api.v1.auth.logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-sign-out-alt"></i> Sair
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            
            <!-- Área de Trabalho -->
            <div class="workspace">
                <!-- Card de Request -->
                <div class="request-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fas fa-paper-plane"></i>
                            <span>Enviar Requisição</span>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-sm" id="save-request-btn">
                                <i class="fas fa-save"></i> Salvar
                            </button>
                            <button class="btn btn-sm" id="duplicate-btn">
                                <i class="fas fa-copy"></i> Duplicar
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-content">
                        <!-- URL Bar -->
                        <div class="url-bar">
                            <div class="method-select">
                                <div class="method-dropdown" id="method-dropdown">
                                    <span class="method-indicator method-get" id="method-indicator">GET</span>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                                <div class="method-options" id="method-options">
                                    <div class="method-option" data-method="GET">
                                        <span class="method-indicator method-get">GET</span>
                                        <span>GET</span>
                                    </div>
                                    <div class="method-option" data-method="POST">
                                        <span class="method-indicator method-post">POST</span>
                                        <span>POST</span>
                                    </div>
                                    <div class="method-option" data-method="PUT">
                                        <span class="method-indicator method-put">PUT</span>
                                        <span>PUT</span>
                                    </div>
                                    <div class="method-option" data-method="PATCH">
                                        <span class="method-indicator method-patch">PATCH</span>
                                        <span>PATCH</span>
                                    </div>
                                    <div class="method-option" data-method="DELETE">
                                        <span class="method-indicator method-delete">DELETE</span>
                                        <span>DELETE</span>
                                    </div>
                                    <div class="method-option" data-method="HEAD">
                                        <span class="method-indicator method-head">HEAD</span>
                                        <span>HEAD</span>
                                    </div>
                                    <div class="method-option" data-method="OPTIONS">
                                        <span class="method-indicator method-options">OPTIONS</span>
                                        <span>OPTIONS</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="url-input-container">
                                <input type="text" 
                                       class="url-input" 
                                       id="url-input" 
                                       placeholder="https://api.exemplo.com/endpoint" 
                                       value="http://127.0.0.1:8000/api/v1/auth/login">
                            </div>
                            
                            <button class="send-button" id="send-btn">
                                <i class="fas fa-paper-plane"></i>
                                <span>Enviar</span>
                            </button>
                        </div>
                        
                        <!-- Tabs -->
                        <div class="client-http-tabs">
                            <div class="client-http-tab active" data-tab="params">
                                <span>Params</span>
                                <span class="tab-count" id="params-count">0</span>
                            </div>
                            <div class="client-http-tab" data-tab="authorization">
                                <span>Authorization</span>
                            </div>
                            <div class="client-http-tab" data-tab="headers">
                                <span>Headers</span>
                                <span class="tab-count" id="headers-count">1</span>
                            </div>
                            <div class="client-http-tab" data-tab="body">
                                <span>Body</span>
                            </div>
                            <div class="client-http-tab" data-tab="tests">
                                <span>Tests</span>
                            </div>
                            <div class="client-http-tab" data-tab="settings">
                                <span>Settings</span>
                            </div>
                        </div>
                        
                        <!-- Tab Contents -->
                        <div id="params-tab" class="tab-content">
                            <div class="headers-editor">
                                <table class="headers-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 40px;">✓</th>
                                            <th>KEY</th>
                                            <th>VALUE</th>
                                            <th style="width: 120px;">DESCRIPTION</th>
                                            <th style="width: 40px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="params-table">
                                        <!-- Params serão adicionados aqui -->
                                    </tbody>
                                </table>
                                <div style="padding: 12px 16px; border-top: 1px solid #3A4252;">
                                    <button class="btn btn-sm" id="add-param-btn">
                                        <i class="fas fa-plus"></i> Adicionar
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div id="authorization-tab" class="tab-content">
                            <div class="auth-panel">
                                <div class="auth-method-select">
                                    <select id="auth-method">
                                        <option value="none">No Auth</option>
                                        <option value="bearer" selected>Bearer Token</option>
                                        <option value="basic">Basic Auth</option>
                                        <option value="apikey">API Key</option>
                                        <option value="oauth2">OAuth 2.0</option>
                                    </select>
                                </div>
                                
                                <div id="bearer-auth" class="auth-content">
                                    <div style="margin-bottom: 12px;">
                                        <label style="display: block; margin-bottom: 6px; font-size: 13px;">Token</label>
                                        <input type="text" 
                                               id="bearer-token" 
                                               placeholder="Insira seu token"
                                               class="url-input"
                                               style="background-color: #1E222A; border: 1px solid #3A4252;">
                                    </div>
                                    <div class="token-display hidden" id="token-display">
                                        <strong>Token Atual:</strong> <span id="token-value"></span>
                                    </div>
                                    <div class="flex gap-2 mt-4">
                                        <button class="btn btn-sm" id="add-auth-header-btn">
                                            <i class="fas fa-plus"></i> Adicionar ao Header
                                        </button>
                                        <button class="btn btn-sm btn-success" id="extract-token-btn">
                                            <i class="fas fa-magic"></i> Extrair da Resposta
                                        </button>
                                        <button class="btn btn-sm btn-danger" id="clear-token-btn">
                                            <i class="fas fa-trash"></i> Limpar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="headers-tab" class="tab-content active">
                            <div class="headers-editor">
                                <table class="headers-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 40px;">✓</th>
                                            <th>KEY</th>
                                            <th>VALUE</th>
                                            <th style="width: 120px;">DESCRIPTION</th>
                                            <th style="width: 40px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="headers-table">
                                        <tr class="header-row">
                                            <td>
                                                <input type="checkbox" checked>
                                            </td>
                                            <td>
                                                <input type="text" value="Content-Type" placeholder="Key">
                                            </td>
                                            <td>
                                                <input type="text" value="application/json" placeholder="Value">
                                            </td>
                                            <td>
                                                <input type="text" placeholder="Description">
                                            </td>
                                            <td>
                                                <button class="btn btn-icon btn-sm delete-header-btn">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div style="padding: 12px 16px; border-top: 1px solid #3A4252;">
                                    <button class="btn btn-sm" id="add-header-btn">
                                        <i class="fas fa-plus"></i> Adicionar
                                    </button>
                                    <button class="btn btn-sm" id="bulk-edit-btn">
                                        <i class="fas fa-edit"></i> Editar em Lote
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div id="body-tab" class="tab-content">
                            <div class="body-editor">
                                <div class="editor-toolbar">
                                    <div class="editor-format">
                                        <div style="font-size: 13px; color: var(--text-secondary);">Body</div>
                                        <select id="body-type" style="background-color: #2D3440; border: 1px solid #3A4252; color: var(--text-primary); padding: 4px 8px; border-radius: 3px; font-size: 13px;">
                                            <option value="none">none</option>
                                            <option value="raw" selected>raw</option>
                                            <option value="form-data">form-data</option>
                                            <option value="x-www-form-urlencoded">x-www-form-urlencoded</option>
                                            <option value="binary">binary</option>
                                            <option value="graphql">GraphQL</option>
                                        </select>
                                        <select id="body-format" style="background-color: #2D3440; border: 1px solid #3A4252; color: var(--text-primary); padding: 4px 8px; border-radius: 3px; font-size: 13px;">
                                            <option value="json" selected>JSON</option>
                                            <option value="text">Text</option>
                                            <option value="javascript">JavaScript</option>
                                            <option value="html">HTML</option>
                                            <option value="xml">XML</option>
                                        </select>
                                    </div>
                                    <div class="editor-buttons">
                                        <button class="btn btn-sm" id="prettify-body-btn">
                                            <i class="fas fa-align-left"></i> Formatar
                                        </button>
                                        <button class="btn btn-sm" id="clear-body-btn">
                                            <i class="fas fa-trash"></i> Limpar
                                        </button>
                                    </div>
                                </div>
                                <textarea class="editor-textarea" 
                                          id="body-input" 
                                          rows="10" 
                                          placeholder='{
  "email": "admin@exemplo.com",
  "password": "senha123",
  "device_name": "api-client"
}'>{
  "email": "admin@ash.elf.eng.br",
  "password": "@dmin#2026",
  "device_name": "curl"
}</textarea>
                            </div>
                        </div>
                        
                        <div id="tests-tab" class="tab-content">
                            <div class="body-editor">
                                <div class="editor-toolbar">
                                    <div style="font-size: 13px; color: var(--text-secondary);">
                                        <i class="fas fa-vial"></i> Testes (JavaScript)
                                    </div>
                                </div>
                                <textarea class="editor-textarea" 
                                          id="tests-input"
                                          placeholder="// Escreva seus testes aqui
// Exemplo:
// pm.test('Status code is 200', function() {
//     pm.response.to.have.status(200);
// });" rows="6"></textarea>
                            </div>
                        </div>
                        
                        <div id="settings-tab" class="tab-content">
                            <div style="padding: 20px; background-color: #2D3440; border-radius: var(--border-radius);">
                                <div style="margin-bottom: 20px;">
                                    <h4 style="margin-bottom: 12px; font-size: 14px;">Variáveis de Ambiente</h4>
                                    <textarea class="editor-textarea" 
                                              id="env-vars-input" 
                                              rows="6" 
                                              placeholder='{
  "base_url": "http://127.0.0.1:8000",
  "api_version": "v1"
}'>{
  "base_url": "http://127.0.0.1:8000",
  "api_version": "v1"
}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Card de Response -->
                <div class="response-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fas fa-inbox"></i>
                            <span>Resposta</span>
                        </div>
                        <div class="response-status">
                            <div class="status-code status-2xx hidden" id="status-code">200 OK</div>
                            <div class="response-time hidden" id="response-time">0ms</div>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-sm" id="copy-response-btn">
                                <i class="fas fa-copy"></i> Copiar
                            </button>
                            <button class="btn btn-sm" id="save-response-btn">
                                <i class="fas fa-save"></i> Salvar
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-content">
                        <div class="loading" id="loading">
                            <div class="spinner"></div>
                            <p style="color: var(--text-secondary); margin-top: 8px;">Enviando requisição...</p>
                        </div>
                        
                        <div class="hidden" id="response-content">
                            <!-- Tabs de Resposta -->
                            <div style="margin-bottom: 20px;">
                                <div class="client-http-tabs">
                                    <div class="client-http-tab active" data-response-tab="body">
                                        Body
                                    </div>
                                    <div class="client-http-tab" data-response-tab="preview">
                                        Preview
                                    </div>
                                    <div class="client-http-tab" data-response-tab="headers">
                                        Headers
                                    </div>
                                    <div class="client-http-tab" data-response-tab="cookies">
                                        Cookies
                                    </div>
                                    <div class="client-http-tab" data-response-tab="tests">
                                        Test Results
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Body Tab -->
                            <div id="response-body-tab" class="tab-content active">
                                <div class="json-viewer" id="response-body"></div>
                            </div>
                            
                            <!-- Preview Tab -->
                            <div id="response-preview-tab" class="tab-content">
                                <div id="preview-container" class="preview-container">
                                    <div id="preview-placeholder" class="preview-placeholder">
                                        <i class="fas fa-eye" style="font-size: 48px; margin-bottom: 16px;"></i>
                                        <p style="font-size: 14px; margin-bottom: 8px;">Preview da resposta</p>
                                        <p style="font-size: 12px; color: var(--text-muted);">
                                            O preview será exibido aqui para respostas HTML, texto ou JSON formatado
                                        </p>
                                    </div>
                                    <iframe id="preview-iframe" class="preview-iframe hidden"></iframe>
                                    <div id="preview-text" class="hidden"></div>
                                </div>
                            </div>
                            
                            <!-- Headers Tab -->
                            <div id="response-headers-tab" class="tab-content">
                                <div class="preview-container text-preview" id="response-headers"></div>
                            </div>
                            
                            <!-- Cookies Tab -->
                            <div id="response-cookies-tab" class="tab-content">
                                <div class="preview-container">
                                    No cookies received
                                </div>
                            </div>
                            
                            <!-- Tests Tab -->
                            <div id="response-tests-tab" class="tab-content">
                                <div class="preview-container" id="test-results">
                                    No tests defined
                                </div>
                            </div>
                        </div>
                        
                        <div id="no-response" style="padding: 60px 20px; text-align: center; color: var(--text-muted);">
                            <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 16px;"></i>
                            <p style="font-size: 16px; margin-bottom: 8px;">Nenhuma resposta ainda</p>
                            <p style="font-size: 14px;">Envie uma requisição para ver a resposta aqui</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar de Histórico -->
        <div class="history-sidebar" id="history-sidebar">
            <div class="history-title">
                <span>Histórico</span>
                <button class="btn btn-sm" id="clear-history-btn">
                    <i class="fas fa-trash"></i> Limpar
                </button>
            </div>
            <div class="history-list" id="history-list">
                <!-- Histórico será carregado aqui -->
            </div>
        </div>
        
        <!-- Sidebar de Coleções -->
        <div class="collections-sidebar" id="collections-sidebar">
            <div class="collections-header">
                <h3 style="font-size: 14px; font-weight: 600;">Coleções</h3>
                <div>
                    <button class="btn btn-sm" id="new-collection-btn">
                        <i class="fas fa-plus"></i> Nova
                    </button>
                </div>
            </div>
            
            <div class="collection-list" id="collection-list">
                <!-- Coleções serão carregadas aqui -->
            </div>
            
            <div class="quick-actions">
                <button class="btn btn-sm" id="import-collection-btn">
                    <i class="fas fa-upload"></i> Importar
                </button>
                <button class="btn btn-sm" id="export-collection-btn">
                    <i class="fas fa-download"></i> Exportar
                </button>
            </div>
        </div>
    </div>
    
    <!-- Modais -->
    <div class="modal-overlay" id="collection-modal">
        <div class="modal">
            <div class="modal-header">
                <div class="modal-title" id="collection-modal-title">Nova Coleção</div>
                <button class="btn btn-icon btn-sm" id="close-modal-btn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="collection-name">Nome da Coleção</label>
                    <input type="text" 
                           id="collection-name" 
                           class="form-control" 
                           placeholder="Minha Coleção API">
                </div>
                
                <div class="form-group">
                    <label for="collection-description">Descrição</label>
                    <textarea id="collection-description" 
                             class="form-control form-textarea" 
                             placeholder="Descrição da coleção..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="collection-color">Cor</label>
                    <select id="collection-color" class="form-control">
                        <option value="#FF6C37">Laranja (Padrão)</option>
                        <option value="#4CAF50">Verde</option>
                        <option value="#2196F3">Azul</option>
                        <option value="#9C27B0">Roxo</option>
                        <option value="#FF9800">Âmbar</option>
                        <option value="#F44336">Vermelho</option>
                        <option value="#00BCD4">Ciano</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" id="collection-is-public">
                        <span style="margin-left: 8px;">Coleção pública (compartilhável)</span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" id="cancel-collection-btn">Cancelar</button>
                <button class="btn btn-primary" id="save-collection-btn">Salvar Coleção</button>
            </div>
        </div>
    </div>
    
    <!-- Modal para Nova Requisição na Coleção -->
    <div class="modal-overlay" id="request-modal">
        <div class="modal">
            <div class="modal-header">
                <div class="modal-title" id="request-modal-title">Nova Requisição</div>
                <button class="btn btn-icon btn-sm" id="close-request-modal-btn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="request-name">Nome da Requisição</label>
                    <input type="text" 
                           id="request-name" 
                           class="form-control" 
                           placeholder="Ex: Login de usuário">
                </div>
                
                <div class="form-group">
                    <label for="request-description">Descrição</label>
                    <textarea id="request-description" 
                             class="form-control form-textarea" 
                             placeholder="Descrição da requisição..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="request-folder">Pasta</label>
                    <select id="request-folder" class="form-control">
                        <option value="">Sem pasta</option>
                        <!-- Pastas serão preenchidas dinamicamente -->
                    </select>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" id="request-save-headers">
                        <span style="margin-left: 8px;">Salvar headers da requisição atual</span>
                    </label>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" id="request-save-body" checked>
                        <span style="margin-left: 8px;">Salvar body da requisição atual</span>
                    </label>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" id="request-save-tests">
                        <span style="margin-left: 8px;">Salvar testes da requisição atual</span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" id="cancel-request-btn">Cancelar</button>
                <button class="btn btn-primary" id="save-request-to-collection-btn">Salvar na Coleção</button>
            </div>
        </div>
    </div>
    
    <!-- Modal para Exportar/Importar Coleções -->
    <div class="modal-overlay" id="import-export-modal">
        <div class="modal">
            <div class="modal-header">
                <div class="modal-title">Exportar/Importar Coleções</div>
                <button class="btn btn-icon btn-sm" id="close-import-export-btn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="sidebar-tabs">
                    <div class="sidebar-tab active" data-tab="export">Exportar</div>
                    <div class="sidebar-tab" data-tab="import">Importar</div>
                </div>
                
                <div class="sidebar-tab-content active" id="export-tab">
                    <div class="form-group">
                        <label>Selecionar Coleções para Exportar</label>
                        <div id="export-collections-list" style="max-height: 200px; overflow-y: auto; margin-top: 10px;">
                            <!-- Lista de coleções para exportar -->
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Formato de Exportação</label>
                        <select id="export-format" class="form-control">
                            <option value="json">JSON (Completo)</option>
                            <option value="postman">Postman Collection v2.1</option>
                            <option value="curl">Comandos cURL</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Conteúdo Exportado</label>
                        <textarea id="export-output" 
                                 class="form-control form-textarea" 
                                 rows="8" 
                                 readonly 
                                 placeholder="Clique em 'Gerar Exportação' para visualizar..."></textarea>
                    </div>
                </div>
                
                <div class="sidebar-tab-content" id="import-tab">
                    <div class="form-group">
                        <label>Selecione o arquivo para importar</label>
                        <input type="file" 
                               id="import-file" 
                               class="form-control" 
                               accept=".json,.txt">
                        <div style="margin-top: 10px; font-size: 12px; color: var(--text-muted);">
                            Formatos suportados: JSON (nativo), Postman Collection v2.1
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Importar como</label>
                        <select id="import-action" class="form-control">
                            <option value="merge">Mesclar com coleções existentes</option>
                            <option value="replace">Substituir todas as coleções</option>
                            <option value="new">Nova coleção separada</option>
                        </select>
                    </div>
                    
                    <div id="import-preview" class="hidden">
                        <div class="form-group">
                            <label>Pré-visualização</label>
                            <textarea id="import-preview-text" 
                                     class="form-control form-textarea" 
                                     rows="6" 
                                     readonly></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" id="cancel-import-export-btn">Cancelar</button>
                <button class="btn btn-primary" id="generate-export-btn">Gerar Exportação</button>
                <button class="btn btn-success" id="execute-import-btn" style="display: none;">Importar</button>
            </div>
        </div>
    </div>

    <script src="{{asset('assets/js/script.js')}}"></script>
    <script src="{{asset('assets/js/collections.js')}}"></script>
    <script src="{{asset('assets/js/history.js')}}"></script>
</body>
</html>