<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>API Client - Postman Style</title>
    <style>
        :root {
            --primary-color: #FF6C37;
            --primary-dark: #E55A2B;
            --primary-light: #FF8C5F;
            --sidebar-bg: #252C3A;
            --sidebar-text: #B0B7C3;
            --sidebar-active: #FF6C37;
            --main-bg: #0F1217;
            --card-bg: #1A1D24;
            --card-border: #2D3440;
            --text-primary: #FFFFFF;
            --text-secondary: #8C95A6;
            --text-muted: #5A6375;
            --success-color: #4CAF50;
            --danger-color: #F44336;
            --warning-color: #FF9800;
            --info-color: #2196F3;
            --border-radius: 4px;
            --shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            --transition: all 0.2s ease;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
        }
        
        body {
            background-color: var(--main-bg);
            color: var(--text-primary);
            line-height: 1.5;
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        /* Layout principal tipo Postman */
        .app-container {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        
        /* Sidebar Esquerda */
        .sidebar {
            width: 240px;
            background-color: var(--sidebar-bg);
            border-right: 1px solid #1F2430;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }
        
        .sidebar-header {
            padding: 20px 16px;
            border-bottom: 1px solid #1F2430;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        
        .logo-icon {
            width: 32px;
            height: 32px;
            background-color: var(--primary-color);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
        }
        
        .logo-text {
            font-size: 18px;
            font-weight: 700;
            color: white;
        }
        
        .sidebar-nav {
            padding: 20px 0;
            flex: 1;
        }
        
        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: var(--sidebar-text);
            text-decoration: none;
            transition: var(--transition);
            cursor: pointer;
        }
        
        .nav-item:hover {
            background-color: rgba(255, 108, 55, 0.1);
            color: var(--sidebar-active);
        }
        
        .nav-item.active {
            background-color: rgba(255, 108, 55, 0.15);
            color: var(--sidebar-active);
            border-right: 3px solid var(--sidebar-active);
        }
        
        .nav-icon {
            font-size: 18px;
            width: 24px;
            text-align: center;
        }
        
        /* Conteúdo Principal */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        /* Topbar */
        .topbar {
            height: 56px;
            background-color: var(--card-bg);
            border-bottom: 1px solid var(--card-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            flex-shrink: 0;
        }
        
        .workspace-selector {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background-color: #2D3440;
            border-radius: var(--border-radius);
            cursor: pointer;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
        }
        
        /* Área de Trabalho */
        .workspace {
            flex: 1;
            padding: 20px;
            overflow: auto;
            background-color: var(--main-bg);
        }
        
        /* Card de Request */
        .request-card {
            background-color: var(--card-bg);
            border-radius: 8px;
            border: 1px solid var(--card-border);
            overflow: hidden;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
        }
        
        .card-header {
            padding: 16px 20px;
            background-color: #252C3A;
            border-bottom: 1px solid var(--card-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .card-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .card-actions {
            display: flex;
            gap: 8px;
        }
        
        .card-content {
            padding: 20px;
        }
        
        /* URL Bar (tipo Postman) - Corrigido */
        .url-bar {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
            align-items: stretch;
        }
        
        .method-select {
            width: 100px;
            position: relative;
            z-index: 100;
        }
        
        .method-dropdown {
            width: 100%;
            height: 42px;
            background-color: #2D3440;
            border: 1px solid #3A4252;
            border-radius: var(--border-radius);
            color: var(--text-primary);
            padding: 0 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            user-select: none;
        }
        
        .method-indicator {
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 700;
            min-width: 60px;
            text-align: center;
        }
        
        .method-get { background-color: #0B875B; color: white; }
        .method-post { background-color: #F6C343; color: #333; }
        .method-put { background-color: #186ADE; color: white; }
        .method-patch { background-color: #6B4FBB; color: white; }
        .method-delete { background-color: #E34F4F; color: white; }
        .method-head { background-color: #9012FE; color: white; }
        .method-options { background-color: #0D5AA7; color: white; }
        
        .method-options {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background-color: #2D3440;
            border: 1px solid #3A4252;
            border-top: none;
            border-radius: 0 0 var(--border-radius) var(--border-radius);
            margin-top: -1px;
            display: none;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }
        
        .method-options.show {
            display: block;
            animation: slideDown 0.2s ease;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .method-option {
            padding: 10px 12px;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .method-option:hover {
            background-color: rgba(255, 108, 55, 0.1);
        }
        
        .method-option .method-indicator {
            width: 60px;
            flex-shrink: 0;
        }
        
        .url-input-container {
            flex: 1;
            display: flex;
            background-color: #2D3440;
            border: 1px solid #3A4252;
            border-radius: var(--border-radius);
            overflow: hidden;
        }
        
        .url-input {
            flex: 1;
            background: none;
            border: none;
            color: var(--text-primary);
            padding: 0 16px;
            font-size: 14px;
            outline: none;
        }
        
        .url-input::placeholder {
            color: var(--text-muted);
        }
        
        .send-button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0 24px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
            border-radius: var(--border-radius);
        }
        
        .send-button:hover {
            background-color: var(--primary-dark);
        }
        
        .send-button:active {
            transform: translateY(1px);
        }
        
        /* Tabs estilo Postman */
        .postman-tabs {
            display: flex;
            border-bottom: 1px solid var(--card-border);
            margin-bottom: 20px;
        }
        
        .postman-tab {
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-secondary);
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: var(--transition);
            position: relative;
        }
        
        .postman-tab:hover {
            color: var(--text-primary);
            background-color: rgba(255, 255, 255, 0.05);
        }
        
        .postman-tab.active {
            color: var(--text-primary);
            border-bottom-color: var(--primary-color);
            background-color: rgba(255, 108, 55, 0.05);
        }
        
        .tab-count {
            background-color: var(--primary-color);
            color: white;
            font-size: 12px;
            padding: 2px 6px;
            border-radius: 10px;
            margin-left: 6px;
        }
        
        /* Tab Content */
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        
        /* Headers Editor */
        .headers-editor {
            background-color: #2D3440;
            border: 1px solid #3A4252;
            border-radius: var(--border-radius);
            overflow: hidden;
        }
        
        .headers-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .headers-table th {
            background-color: #252C3A;
            padding: 12px 16px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            border-bottom: 1px solid var(--card-border);
        }
        
        .headers-table td {
            padding: 12px 16px;
            border-bottom: 1px solid #3A4252;
        }
        
        .headers-table input {
            width: 100%;
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 14px;
            padding: 4px 0;
            outline: none;
        }
        
        .headers-table input::placeholder {
            color: var(--text-muted);
        }
        
        .header-row {
            transition: var(--transition);
        }
        
        .header-row:hover {
            background-color: rgba(255, 255, 255, 0.03);
        }
        
        /* Body Editor */
        .body-editor {
            background-color: #1E222A;
            border: 1px solid #3A4252;
            border-radius: var(--border-radius);
            overflow: hidden;
        }
        
        .editor-toolbar {
            background-color: #252C3A;
            padding: 10px 16px;
            border-bottom: 1px solid #3A4252;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .editor-format {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .editor-buttons {
            display: flex;
            gap: 8px;
        }
        
        .editor-textarea {
            width: 100%;
            min-height: 200px;
            background: none;
            border: none;
            color: var(--text-primary);
            padding: 16px;
            font-family: 'Consolas', 'Monaco', monospace;
            font-size: 13px;
            line-height: 1.5;
            resize: vertical;
            outline: none;
            white-space: pre;
            overflow-wrap: normal;
            overflow-x: auto;
        }
        
        /* Response Card */
        .response-card {
            background-color: var(--card-bg);
            border-radius: 8px;
            border: 1px solid var(--card-border);
            overflow: hidden;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
        }
        
        .response-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            background-color: #252C3A;
            border-bottom: 1px solid var(--card-border);
        }
        
        .response-status {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .status-code {
            font-size: 14px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 4px;
        }
        
        .status-2xx { background-color: rgba(76, 175, 80, 0.2); color: #4CAF50; }
        .status-3xx { background-color: rgba(33, 150, 243, 0.2); color: #2196F3; }
        .status-4xx { background-color: rgba(244, 67, 54, 0.2); color: #F44336; }
        .status-5xx { background-color: rgba(244, 67, 54, 0.2); color: #F44336; }
        
        .response-time {
            font-size: 13px;
            color: var(--text-secondary);
        }
        
        .response-body {
            padding: 20px;
            max-height: 400px;
            overflow-y: auto;
        }
        
        .json-viewer {
            font-family: 'Consolas', 'Monaco', monospace;
            font-size: 13px;
            line-height: 1.5;
            white-space: pre-wrap;
        }
        
        /* Preview Styles */
        .preview-container {
            background-color: #1E222A;
            border-radius: var(--border-radius);
            padding: 20px;
            max-height: 400px;
            overflow-y: auto;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
        }
        
        .preview-container.html-preview {
            background-color: white;
            color: #333;
        }
        
        .preview-container.text-preview {
            font-family: 'Consolas', 'Monaco', monospace;
            white-space: pre-wrap;
        }
        
        .preview-iframe {
            width: 100%;
            height: 400px;
            border: none;
            background-color: white;
            border-radius: var(--border-radius);
        }
        
        .preview-placeholder {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-muted);
        }
        
        /* Auth Panel */
        .auth-panel {
            background-color: #2D3440;
            border: 1px solid #3A4252;
            border-radius: var(--border-radius);
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .auth-method-select {
            width: 200px;
            margin-bottom: 20px;
        }
        
        .auth-method-select select {
            width: 100%;
            background-color: #1E222A;
            border: 1px solid #3A4252;
            color: var(--text-primary);
            padding: 10px 12px;
            border-radius: var(--border-radius);
            font-size: 14px;
        }
        
        .token-display {
            background-color: #1E222A;
            border: 1px solid #3A4252;
            border-radius: var(--border-radius);
            padding: 12px;
            font-family: monospace;
            font-size: 13px;
            word-break: break-all;
            margin-top: 10px;
        }
        
        /* Botões e Controles */
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: var(--border-radius);
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background-color: #3A4252;
            color: var(--text-primary);
        }
        
        .btn:hover {
            background-color: #4A5468;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
        }
        
        .btn-success {
            background-color: var(--success-color);
            color: white;
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }
        
        .btn-sm {
            padding: 4px 8px;
            font-size: 12px;
        }
        
        .btn-icon {
            padding: 6px;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Loading */
        .loading {
            display: none;
            padding: 40px;
            text-align: center;
        }
        
        .loading.active {
            display: block;
        }
        
        .spinner {
            border: 3px solid rgba(255, 108, 55, 0.2);
            border-radius: 50%;
            border-top: 3px solid var(--primary-color);
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 16px;
        }
        
        /* Histórico */
        .history-sidebar {
            width: 300px;
            background-color: var(--card-bg);
            border-left: 1px solid var(--card-border);
            padding: 20px;
            overflow-y: auto;
            display: none;
        }
        
        .history-sidebar.active {
            display: block;
        }
        
        .history-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 16px;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .history-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .history-item {
            background-color: #2D3440;
            border: 1px solid #3A4252;
            border-radius: var(--border-radius);
            padding: 12px;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .history-item:hover {
            background-color: #3A4252;
            border-color: var(--primary-light);
        }
        
        .history-method {
            font-size: 11px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 3px;
            margin-right: 8px;
            display: inline-block;
        }
        
        /* Animações */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Responsividade */
        @media (max-width: 1200px) {
            .sidebar {
                width: 64px;
            }
            
            .logo-text, .nav-text {
                display: none;
            }
            
            .sidebar-header {
                justify-content: center;
                padding: 16px;
            }
            
            .nav-item {
                justify-content: center;
                padding: 16px;
            }
        }
        
        @media (max-width: 768px) {
            .app-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
                flex-direction: row;
                overflow-x: auto;
            }
            
            .sidebar-nav {
                display: flex;
                padding: 0;
            }
            
            .nav-item {
                padding: 16px;
                white-space: nowrap;
            }
            
            .url-bar {
                flex-direction: column;
            }
            
            .method-select, .send-button {
                width: 100%;
            }
        }
        
        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #1E222A;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #3A4252;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #4A5468;
        }
        
        /* Utilitários */
        .hidden {
            display: none !important;
        }
        
        .flex {
            display: flex;
        }
        
        .items-center {
            align-items: center;
        }
        
        .justify-between {
            justify-content: space-between;
        }
        
        .gap-2 {
            gap: 8px;
        }
        
        .gap-4 {
            gap: 16px;
        }
        
        .w-full {
            width: 100%;
        }
        
        .mt-4 {
            margin-top: 16px;
        }
        
        .mb-4 {
            margin-bottom: 16px;
        }
        
        .text-sm {
            font-size: 13px;
        }
        
        .text-xs {
            font-size: 12px;
        }
        
        .text-muted {
            color: var(--text-muted);
        }
        
        /* Status Badges */
        .badge {
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .badge-success {
            background-color: rgba(76, 175, 80, 0.2);
            color: #4CAF50;
        }
        
        .badge-warning {
            background-color: rgba(255, 152, 0, 0.2);
            color: #FF9800;
        }
        
        .badge-danger {
            background-color: rgba(244, 67, 54, 0.2);
            color: #F44336;
        }
        
        .badge-info {
            background-color: rgba(33, 150, 243, 0.2);
            color: #2196F3;
        }
    </style>
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
                <a href="#" class="nav-item active">
                    <i class="nav-icon fas fa-rocket"></i>
                    <span class="nav-text">Nova Requisição</span>
                </a>
                <a href="#" class="nav-item" id="history-toggle">
                    <i class="nav-icon fas fa-history"></i>
                    <span class="nav-text">Histórico</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="nav-icon fas fa-folder"></i>
                    <span class="nav-text">Coleções</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="nav-icon fas fa-cog"></i>
                    <span class="nav-text">Configurações</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="nav-icon fas fa-users"></i>
                    <span class="nav-text">Equipe</span>
                </a>
                <a href="#" class="nav-item">
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
                    <button class="btn btn-icon">
                        <i class="fas fa-save"></i>
                    </button>
                    <button class="btn btn-icon">
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
                        <!-- URL Bar - Corrigida -->
                        <div class="url-bar">
                            <div class="method-select">
                                <div class="method-dropdown" id="method-dropdown">
                                    <span class="method-indicator method-get" id="method-indicator">GET</span>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                                <div class="method-options" id="method-options">
                                    <div class="method-option" data-method="GET">
                                        <span class="method-indicator method-get">GET</span>

                                    </div>
                                    <div class="method-option" data-method="POST">
                                        <span class="method-indicator method-post">POST</span>

                                    </div>
                                    <div class="method-option" data-method="PUT">
                                        <span class="method-indicator method-put">PUT</span>

                                    </div>
                                    <div class="method-option" data-method="PATCH">
                                        <span class="method-indicator method-patch">PATCH</span>

                                    </div>
                                    <div class="method-option" data-method="DELETE">
                                        <span class="method-indicator method-delete">DELETE</span>

                                    </div>
                                    <div class="method-option" data-method="HEAD">
                                        <span class="method-indicator method-head">HEAD</span>
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
                        <div class="postman-tabs">
                            <div class="postman-tab active" data-tab="params">
                                <span>Params</span>
                                <span class="tab-count">0</span>
                            </div>
                            <div class="postman-tab" data-tab="authorization">
                                <span>Authorization</span>
                            </div>
                            <div class="postman-tab" data-tab="headers">
                                <span>Headers</span>
                                <span class="tab-count" id="headers-count">1</span>
                            </div>
                            <div class="postman-tab" data-tab="body">
                                <span>Body</span>
                            </div>
                            <div class="postman-tab" data-tab="tests">
                                <span>Tests</span>
                            </div>
                            <div class="postman-tab" data-tab="settings">
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
                                                <button class="btn btn-icon btn-sm" onclick="this.closest('tr').remove(); updateHeadersCount();">
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
                            <!-- Tabs de Resposta - Com Preview -->
                            <div style="margin-bottom: 20px;">
                                <div class="postman-tabs">
                                    <div class="postman-tab active" data-response-tab="body">
                                        Body
                                    </div>
                                    <div class="postman-tab" data-response-tab="preview">
                                        Preview
                                    </div>
                                    <div class="postman-tab" data-response-tab="headers">
                                        Headers
                                    </div>
                                    <div class="postman-tab" data-response-tab="cookies">
                                        Cookies
                                    </div>
                                    <div class="postman-tab" data-response-tab="tests">
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
                                <div class="preview-container">
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
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ============================================
            // ESTADO DA APLICAÇÃO
            // ============================================
            let authToken = localStorage.getItem('api_client_token');
            let requestHistory = JSON.parse(localStorage.getItem('api_client_history') || '[]');
            let currentResponse = null;
            let currentMethod = 'GET';
            let isMethodDropdownOpen = false;
            
            // ============================================
            // ELEMENTOS DOM
            // ============================================
            const methodIndicator = document.getElementById('method-indicator');
            const methodOptions = document.getElementById('method-options');
            const methodDropdown = document.getElementById('method-dropdown');
            const urlInput = document.getElementById('url-input');
            const sendButton = document.getElementById('send-btn');
            const loadingElement = document.getElementById('loading');
            const responseContent = document.getElementById('response-content');
            const noResponseElement = document.getElementById('no-response');
            const statusCode = document.getElementById('status-code');
            const responseTime = document.getElementById('response-time');
            const responseBody = document.getElementById('response-body');
            const responseHeaders = document.getElementById('response-headers');
            
            // Preview elements
            const previewContainer = document.getElementById('preview-container');
            const previewPlaceholder = document.getElementById('preview-placeholder');
            const previewIframe = document.getElementById('preview-iframe');
            const previewText = document.getElementById('preview-text');
            
            // Tabs
            const tabs = document.querySelectorAll('.postman-tab[data-tab]');
            const responseTabs = document.querySelectorAll('[data-response-tab]');
            
            // Headers table
            const headersTable = document.getElementById('headers-table');
            const addHeaderBtn = document.getElementById('add-header-btn');
            
            // Body
            const bodyInput = document.getElementById('body-input');
            const prettifyBodyBtn = document.getElementById('prettify-body-btn');
            const clearBodyBtn = document.getElementById('clear-body-btn');
            
            // Auth
            const authMethodSelect = document.getElementById('auth-method');
            const bearerTokenInput = document.getElementById('bearer-token');
            const tokenDisplay = document.getElementById('token-display');
            const tokenValue = document.getElementById('token-value');
            const addAuthHeaderBtn = document.getElementById('add-auth-header-btn');
            const extractTokenBtn = document.getElementById('extract-token-btn');
            const clearTokenBtn = document.getElementById('clear-token-btn');
            
            // Response actions
            const copyResponseBtn = document.getElementById('copy-response-btn');
            const saveResponseBtn = document.getElementById('save-response-btn');
            
            // History
            const historyToggle = document.getElementById('history-toggle');
            const historySidebar = document.getElementById('history-sidebar');
            const historyList = document.getElementById('history-list');
            const clearHistoryBtn = document.getElementById('clear-history-btn');
            
            // Params
            const paramsTable = document.getElementById('params-table');
            const addParamBtn = document.getElementById('add-param-btn');
            
            // Environment
            const envVarsInput = document.getElementById('env-vars-input');
            
            // Body type and format
            const bodyTypeSelect = document.getElementById('body-type');
            const bodyFormatSelect = document.getElementById('body-format');
            
            // ============================================
            // INICIALIZAÇÃO
            // ============================================
            init();
            
            function init() {
                updateAuthDisplay();
                renderHistory();
                
                // Carregar token se existir
                if (authToken) {
                    bearerTokenInput.value = authToken;
                    tokenDisplay.classList.remove('hidden');
                    tokenValue.textContent = authToken.substring(0, 50) + '...';
                }
                
                // Adicionar algumas linhas de exemplo para params
                addParamRow();
                
                // Setup event listeners
                setupEventListeners();
                
                // Initial updates
                updateHeadersCount();
                updateParamsCount();
            }
            
            function setupEventListeners() {
                // Method dropdown - Corrigido
                methodDropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleMethodDropdown();
                });
                
                document.querySelectorAll('.method-option').forEach(option => {
                    option.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const method = this.getAttribute('data-method');
                        setMethod(method);
                        methodOptions.classList.remove('show');
                        isMethodDropdownOpen = false;
                    });
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!methodDropdown.contains(e.target) && isMethodDropdownOpen) {
                        methodOptions.classList.remove('show');
                        isMethodDropdownOpen = false;
                    }
                });
                
                // Send request
                sendButton.addEventListener('click', sendRequest);
                urlInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') sendRequest();
                });
                
                // Tabs
                tabs.forEach(tab => {
                    tab.addEventListener('click', function() {
                        switchTab(this.getAttribute('data-tab'));
                    });
                });
                
                responseTabs.forEach(tab => {
                    tab.addEventListener('click', function() {
                        switchResponseTab(this.getAttribute('data-response-tab'));
                    });
                });
                
                // Headers table
                addHeaderBtn.addEventListener('click', addHeaderRow);
                
                // Body editor
                prettifyBodyBtn.addEventListener('click', () => prettifyJson(bodyInput));
                clearBodyBtn.addEventListener('click', () => bodyInput.value = '');
                
                // Body type changes
                bodyTypeSelect.addEventListener('change', updateBodyType);
                bodyFormatSelect.addEventListener('change', updateBodyFormat);
                
                // Auth
                authMethodSelect.addEventListener('change', updateAuthMethod);
                addAuthHeaderBtn.addEventListener('click', addAuthHeader);
                extractTokenBtn.addEventListener('click', extractTokenFromResponse);
                clearTokenBtn.addEventListener('click', clearToken);
                
                // Response
                copyResponseBtn.addEventListener('click', copyResponse);
                saveResponseBtn.addEventListener('click', saveResponse);
                
                // History
                historyToggle.addEventListener('click', toggleHistorySidebar);
                clearHistoryBtn.addEventListener('click', clearHistory);
                
                // Params
                addParamBtn.addEventListener('click', addParamRow);
            }
            
            // ============================================
            // FUNÇÕES PRINCIPAIS
            // ============================================
            function toggleMethodDropdown() {
                isMethodDropdownOpen = !isMethodDropdownOpen;
                if (isMethodDropdownOpen) {
                    methodOptions.classList.add('show');
                } else {
                    methodOptions.classList.remove('show');
                }
            }
            
            function setMethod(method) {
                currentMethod = method;
                methodIndicator.textContent = method;
                methodIndicator.className = `method-indicator method-${method.toLowerCase()}`;
                
                // Update method in dropdown options
                document.querySelectorAll('.method-option').forEach(option => {
                    if (option.getAttribute('data-method') === method) {
                        option.style.backgroundColor = 'rgba(255, 108, 55, 0.1)';
                    } else {
                        option.style.backgroundColor = '';
                    }
                });
                
                // Clear body for GET and HEAD
                if (method === 'GET' || method === 'HEAD') {
                    bodyInput.value = '';
                }
            }
            
            function switchTab(tabId) {
                // Update active tab
                tabs.forEach(tab => {
                    if (tab.getAttribute('data-tab') === tabId) {
                        tab.classList.add('active');
                    } else {
                        tab.classList.remove('active');
                    }
                });
                
                // Show corresponding content
                document.querySelectorAll('.tab-content').forEach(content => {
                    if (content.id === `${tabId}-tab`) {
                        content.classList.add('active');
                    } else {
                        content.classList.remove('active');
                    }
                });
            }
            
            function switchResponseTab(tabId) {
                // Update active tab
                responseTabs.forEach(tab => {
                    if (tab.getAttribute('data-response-tab') === tabId) {
                        tab.classList.add('active');
                    } else {
                        tab.classList.remove('active');
                    }
                });
                
                // Show corresponding content
                document.querySelectorAll('.tab-content').forEach(content => {
                    if (content.id === `response-${tabId}-tab`) {
                        content.classList.add('active');
                    } else {
                        content.classList.remove('active');
                    }
                });
                
                // Update preview if needed
                if (tabId === 'preview' && currentResponse) {
                    updatePreview(currentResponse);
                }
            }
            
            function addHeaderRow() {
                const row = document.createElement('tr');
                row.className = 'header-row';
                row.innerHTML = `
                    <td>
                        <input type="checkbox" checked>
                    </td>
                    <td>
                        <input type="text" placeholder="Key">
                    </td>
                    <td>
                        <input type="text" placeholder="Value">
                    </td>
                    <td>
                        <input type="text" placeholder="Description">
                    </td>
                    <td>
                        <button class="btn btn-icon btn-sm" onclick="this.closest('tr').remove(); updateHeadersCount();">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                headersTable.appendChild(row);
                updateHeadersCount();
            }
            
            function addParamRow() {
                const row = document.createElement('tr');
                row.className = 'header-row';
                row.innerHTML = `
                    <td>
                        <input type="checkbox" checked>
                    </td>
                    <td>
                        <input type="text" placeholder="key" value="${paramsTable.children.length === 0 ? 'page' : ''}">
                    </td>
                    <td>
                        <input type="text" placeholder="value" value="${paramsTable.children.length === 0 ? '1' : ''}">
                    </td>
                    <td>
                        <input type="text" placeholder="Description" value="${paramsTable.children.length === 0 ? 'Page number' : ''}">
                    </td>
                    <td>
                        <button class="btn btn-icon btn-sm" onclick="this.closest('tr').remove(); updateParamsCount();">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                paramsTable.appendChild(row);
                updateParamsCount();
            }
            
            function updateHeadersCount() {
                const count = headersTable.querySelectorAll('tr').length;
                document.getElementById('headers-count').textContent = count;
            }
            
            function updateParamsCount() {
                const count = paramsTable.querySelectorAll('tr').length;
                document.querySelector('[data-tab="params"] .tab-count').textContent = count;
            }
            
            function updateBodyType() {
                const type = bodyTypeSelect.value;
                if (type === 'none') {
                    bodyInput.style.display = 'none';
                } else {
                    bodyInput.style.display = 'block';
                }
            }
            
            function updateBodyFormat() {
                const format = bodyFormatSelect.value;
                // Could update syntax highlighting here
            }
            
            async function sendRequest() {
                const method = currentMethod;
                let url = urlInput.value.trim();
                
                if (!url) {
                    showAlert('Por favor, informe uma URL', 'warning');
                    return;
                }
                
                // Process environment variables
                url = processEnvVars(url);
                
                // Add query parameters
                const params = getQueryParams();
                if (params) {
                    url += (url.includes('?') ? '&' : '?') + params;
                }
                
                // Validate URL
                try {
                    new URL(url);
                } catch (e) {
                    showAlert('URL inválida. Certifique-se de incluir http:// ou https://', 'danger');
                    return;
                }
                
                // Prepare headers
                const headers = getHeaders();
                
                // Prepare body
                let body = null;
                if (method !== 'GET' && method !== 'HEAD' && bodyInput.value.trim()) {
                    body = bodyInput.value.trim();
                }
                
                // Show loading
                loadingElement.classList.add('active');
                responseContent.classList.add('hidden');
                noResponseElement.classList.add('hidden');
                
                try {
                    const startTime = Date.now();
                    
                    // Make request
                    const response = await fetch(url, {
                        method: method,
                        headers: headers,
                        body: body,
                        credentials: 'same-origin'
                    });
                    
                    const endTime = Date.now();
                    const responseTime = endTime - startTime;
                    
                    // Get response text
                    const responseText = await response.text();
                    
                    // Update UI with response
                    updateResponseUI(response, responseText, responseTime);
                    
                    // Add to history
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
                    
                    // Show response
                    loadingElement.classList.remove('active');
                    responseContent.classList.remove('hidden');
                    
                    // Show success message
                    showAlert(`Requisição enviada com sucesso! (${responseTime}ms)`, 'success');
                    
                } catch (error) {
                    loadingElement.classList.remove('active');
                    
                    // Show error
                    statusCode.textContent = 'Error';
                    statusCode.className = 'status-code status-4xx';
                    statusCode.classList.remove('hidden');
                    responseTime.textContent = '';
                    responseBody.textContent = `Erro de rede: ${error.message}`;
                    responseHeaders.textContent = '';
                    
                    responseContent.classList.remove('hidden');
                    
                    // Add error to history
                    addToHistory({
                        method,
                        url: urlInput.value.trim(),
                        headers: headers,
                        body: body,
                        status: 0,
                        statusText: 'Erro de Rede',
                        error: error.message,
                        timestamp: new Date().toISOString()
                    });
                    
                    showAlert(`Erro de rede: ${error.message}`, 'danger');
                }
            }
            
            function getHeaders() {
                const headers = {};
                headersTable.querySelectorAll('tr').forEach(row => {
                    const checkbox = row.querySelector('input[type="checkbox"]');
                    if (checkbox && checkbox.checked) {
                        const keyInput = row.querySelector('td:nth-child(2) input');
                        const valueInput = row.querySelector('td:nth-child(3) input');
                        if (keyInput && keyInput.value && valueInput && valueInput.value) {
                            headers[keyInput.value] = valueInput.value;
                        }
                    }
                });
                return headers;
            }
            
            function getQueryParams() {
                const params = new URLSearchParams();
                paramsTable.querySelectorAll('tr').forEach(row => {
                    const checkbox = row.querySelector('input[type="checkbox"]');
                    if (checkbox && checkbox.checked) {
                        const keyInput = row.querySelector('td:nth-child(2) input');
                        const valueInput = row.querySelector('td:nth-child(3) input');
                        if (keyInput && keyInput.value && valueInput) {
                            params.append(keyInput.value, valueInput.value);
                        }
                    }
                });
                return params.toString();
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
                    console.error('Error processing environment variables:', e);
                }
                return url;
            }
            
            function updateResponseUI(response, responseText, responseTimeMs) {
                // Update status
                statusCode.textContent = `${response.status} ${response.statusText}`;
                statusCode.className = 'status-code';
                
                if (response.status >= 200 && response.status < 300) {
                    statusCode.classList.add('status-2xx');
                } else if (response.status >= 300 && response.status < 400) {
                    statusCode.classList.add('status-3xx');
                } else if (response.status >= 400 && response.status < 500) {
                    statusCode.classList.add('status-4xx');
                } else if (response.status >= 500) {
                    statusCode.classList.add('status-5xx');
                }
                
                statusCode.classList.remove('hidden');
                responseTime.textContent = `${responseTimeMs}ms`;
                responseTime.classList.remove('hidden');
                
                // Try to parse as JSON for formatting
                let formattedBody = responseText;
                try {
                    const jsonResponse = JSON.parse(responseText);
                    formattedBody = syntaxHighlight(JSON.stringify(jsonResponse, null, 2));
                } catch (e) {
                    // Not JSON, keep as text
                }
                
                // Format response headers
                const headersArray = [];
                response.headers.forEach((value, key) => {
                    headersArray.push(`${key}: ${value}`);
                });
                
                responseBody.innerHTML = formattedBody;
                responseHeaders.textContent = headersArray.join('\n');
                
                // Store current response
                currentResponse = {
                    text: responseText,
                    headers: response.headers,
                    contentType: response.headers.get('content-type') || '',
                    status: response.status
                };
            }
            
            function updatePreview(response) {
                const contentType = response.contentType.toLowerCase();
                const responseText = response.text;
                
                // Hide all preview elements
                previewIframe.classList.add('hidden');
                previewText.classList.add('hidden');
                previewPlaceholder.classList.add('hidden');
                
                // Clear previous iframe content
                previewIframe.src = 'about:blank';
                
                if (contentType.includes('text/html')) {
                    // Create blob with the HTML
                    const blob = new Blob([responseText], { type: 'text/html;charset=utf-8' });
                    const url = URL.createObjectURL(blob);
                    
                    previewIframe.src = url;
                    previewIframe.classList.remove('hidden');
                    previewContainer.classList.add('html-preview');
                    
                    previewIframe.onload = function() {
                        URL.revokeObjectURL(url);
                    };
                    
                } else if (contentType.includes('application/json')) {
                    try {
                        const jsonObj = JSON.parse(responseText);
                        previewText.innerHTML = syntaxHighlight(JSON.stringify(jsonObj, null, 2));
                        previewText.classList.remove('hidden');
                        previewContainer.classList.remove('html-preview');
                    } catch (e) {
                        previewText.textContent = 'Erro ao formatar JSON: ' + e.message;
                        previewText.classList.remove('hidden');
                    }
                    
                } else if (contentType.includes('text/')) {
                    previewText.textContent = responseText;
                    previewText.classList.remove('hidden');
                    previewContainer.classList.add('text-preview');
                    
                } else {
                    previewPlaceholder.innerHTML = `
                        <i class="fas fa-file" style="font-size: 48px; margin-bottom: 16px;"></i>
                        <p style="font-size: 14px; margin-bottom: 8px;">Tipo de conteúdo: ${contentType || 'desconhecido'}</p>
                        <p style="font-size: 12px; color: var(--text-muted);">
                            Preview não disponível para este tipo de conteúdo
                        </p>
                    `;
                    previewPlaceholder.classList.remove('hidden');
                    previewContainer.classList.remove('html-preview');
                }
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
                
                // Apply styles
                return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function(match) {
                    let cls = 'json-number';
                    if (/^"/.test(match)) {
                        if (/:$/.test(match)) {
                            return `<span style="color: #FF6C37">${match}</span>`;
                        } else {
                            return `<span style="color: #98C379">${match}</span>`;
                        }
                    } else if (/true|false/.test(match)) {
                        return `<span style="color: #C678DD">${match}</span>`;
                    } else if (/null/.test(match)) {
                        return `<span style="color: #5C6370">${match}</span>`;
                    } else {
                        return `<span style="color: #D19A66">${match}</span>`;
                    }
                });
            }
            
            function updateAuthMethod() {
                const method = authMethodSelect.value;
                // Show/hide appropriate auth panels
                // Implementation depends on selected method
            }
            
            function addAuthHeader() {
                if (authToken) {
                    // Check if Authorization header already exists
                    let headerExists = false;
                    headersTable.querySelectorAll('tr').forEach(row => {
                        const keyInput = row.querySelector('td:nth-child(2) input');
                        if (keyInput && keyInput.value === 'Authorization') {
                            headerExists = true;
                            const valueInput = row.querySelector('td:nth-child(3) input');
                            if (valueInput) {
                                valueInput.value = `Bearer ${authToken}`;
                            }
                        }
                    });
                    
                    if (!headerExists) {
                        // Add a new header row with Authorization
                        addHeaderRow();
                        const lastRow = headersTable.lastElementChild;
                        const keyInput = lastRow.querySelector('td:nth-child(2) input');
                        const valueInput = lastRow.querySelector('td:nth-child(3) input');
                        if (keyInput) keyInput.value = 'Authorization';
                        if (valueInput) valueInput.value = `Bearer ${authToken}`;
                    }
                    
                    showAlert('Authorization header adicionado', 'success');
                } else {
                    showAlert('Nenhum token de autenticação armazenado', 'warning');
                }
            }
            
            function extractTokenFromResponse() {
                if (!currentResponse) {
                    showAlert('Nenhuma resposta disponível', 'warning');
                    return;
                }
                
                try {
                    const jsonResponse = JSON.parse(currentResponse.text);
                    
                    // Look for token in various possible locations
                    let token = null;
                    
                    if (jsonResponse.data && jsonResponse.data.token) {
                        token = jsonResponse.data.token;
                    } else if (jsonResponse.token) {
                        token = jsonResponse.token;
                    } else if (jsonResponse.access_token) {
                        token = jsonResponse.access_token;
                    } else if (jsonResponse.bearer) {
                        token = jsonResponse.bearer;
                    }
                    
                    if (token) {
                        authToken = token;
                        localStorage.setItem('api_client_token', authToken);
                        bearerTokenInput.value = authToken;
                        tokenDisplay.classList.remove('hidden');
                        tokenValue.textContent = authToken.substring(0, 50) + '...';
                        showAlert('Token extraído e armazenado com sucesso!', 'success');
                    } else {
                        showAlert('Não foi possível encontrar um token na resposta', 'warning');
                    }
                } catch (e) {
                    showAlert('Erro ao processar a resposta para extrair token', 'danger');
                }
            }
            
            function clearToken() {
                authToken = null;
                localStorage.removeItem('api_client_token');
                bearerTokenInput.value = '';
                tokenDisplay.classList.add('hidden');
                showAlert('Token removido com sucesso!', 'info');
            }
            
            function updateAuthDisplay() {
                if (authToken) {
                    tokenDisplay.classList.remove('hidden');
                    tokenValue.textContent = authToken.substring(0, 50) + '...';
                }
            }
            
            function copyResponse() {
                if (!currentResponse) return;
                
                const activeTab = document.querySelector('[data-response-tab].active');
                if (activeTab) {
                    const tabId = activeTab.getAttribute('data-response-tab');
                    let textToCopy = currentResponse.text;
                    
                    if (tabId === 'body') {
                        textToCopy = responseBody.textContent;
                    } else if (tabId === 'headers') {
                        textToCopy = responseHeaders.textContent;
                    }
                    
                    navigator.clipboard.writeText(textToCopy).then(() => {
                        showAlert('Resposta copiada para a área de transferência', 'success');
                    });
                }
            }
            
            function saveResponse() {
                if (!currentResponse) return;
                
                const blob = new Blob([currentResponse.text], { type: 'text/plain' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `response-${Date.now()}.txt`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
                showAlert('Resposta salva como arquivo', 'success');
            }
            
            function toggleHistorySidebar() {
                historySidebar.classList.toggle('active');
                renderHistory();
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
                    historyList.innerHTML = '<div style="text-align: center; color: var(--text-muted); padding: 20px;">Nenhum histórico ainda</div>';
                    return;
                }
                
                requestHistory.forEach((item, index) => {
                    const historyItem = document.createElement('div');
                    historyItem.className = 'history-item';
                    
                    const methodClass = `method-${item.method.toLowerCase()}`;
                    const date = new Date(item.timestamp);
                    const timeStr = date.toLocaleTimeString('pt-BR', { 
                        hour: '2-digit', 
                        minute: '2-digit' 
                    });
                    
                    historyItem.innerHTML = `
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                            <span class="history-method ${methodClass}">${item.method}</span>
                            <span style="font-size: 11px; color: var(--text-muted);">${timeStr}</span>
                        </div>
                        <div style="font-size: 12px; color: var(--text-secondary); margin-bottom: 4px; word-break: break-all;">
                            ${item.url}
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 11px; color: ${item.status >= 400 ? '#F44336' : item.status >= 200 ? '#4CAF50' : '#FF9800'}">
                                ${item.status || 'ERR'}
                            </span>
                            ${item.responseTime ? `<span style="font-size: 11px; color: var(--text-muted);">${item.responseTime}ms</span>` : ''}
                        </div>
                    `;
                    
                    historyItem.addEventListener('click', () => {
                        loadRequestFromHistory(index);
                        if (window.innerWidth < 1200) {
                            historySidebar.classList.remove('active');
                        }
                    });
                    
                    historyList.appendChild(historyItem);
                });
            }
            
            function loadRequestFromHistory(index) {
                const request = requestHistory[index];
                
                setMethod(request.method);
                urlInput.value = request.url;
                
                // Load headers
                headersTable.innerHTML = '';
                if (request.headers) {
                    Object.entries(request.headers).forEach(([key, value]) => {
                        const row = document.createElement('tr');
                        row.className = 'header-row';
                        row.innerHTML = `
                            <td>
                                <input type="checkbox" checked>
                            </td>
                            <td>
                                <input type="text" value="${key}" placeholder="Key">
                            </td>
                            <td>
                                <input type="text" value="${value}" placeholder="Value">
                            </td>
                            <td>
                                <input type="text" placeholder="Description">
                            </td>
                            <td>
                                <button class="btn btn-icon btn-sm" onclick="this.closest('tr').remove(); updateHeadersCount();">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        `;
                        headersTable.appendChild(row);
                    });
                }
                updateHeadersCount();
                
                // Load body
                if (request.body) {
                    bodyInput.value = request.body;
                }
                
                showAlert('Requisição carregada do histórico', 'info');
            }
            
            function clearHistory() {
                if (confirm('Limpar todo o histórico de requisições?')) {
                    requestHistory = [];
                    localStorage.removeItem('api_client_history');
                    renderHistory();
                    showAlert('Histórico limpo', 'info');
                }
            }
            
            function showAlert(message, type = 'info') {
                // Create toast notification
                const toast = document.createElement('div');
                toast.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background-color: ${type === 'success' ? '#4CAF50' : type === 'warning' ? '#FF9800' : type === 'danger' ? '#F44336' : '#2196F3'};
                    color: white;
                    padding: 12px 20px;
                    border-radius: 4px;
                    font-size: 14px;
                    z-index: 10000;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                    animation: fadeIn 0.3s ease;
                    max-width: 400px;
                `;
                toast.textContent = message;
                
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transition = 'opacity 0.3s ease';
                    setTimeout(() => {
                        if (toast.parentElement) {
                            toast.remove();
                        }
                    }, 300);
                }, 3000);
            }
        });
    </script>
</body>
</html>