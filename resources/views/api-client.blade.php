<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>API Client - Laravel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ============================================
           VARIÁVEIS CSS E ESTILOS GERAIS
           ============================================ */
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

        /* ============================================
           LAYOUT PRINCIPAL
           ============================================ */
        .app-container {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* ============================================
           SIDEBAR ESQUERDA
           ============================================ */
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
            position: relative;
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

        /* ============================================
           CONTEÚDO PRINCIPAL
           ============================================ */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* TOPBAR */
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

        /* ÁREA DE TRABALHO */
        .workspace {
            flex: 1;
            padding: 20px;
            overflow: auto;
            background-color: var(--main-bg);
        }

        /* ============================================
           CARD DE REQUEST
           ============================================ */
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

        /* URL BAR */
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

        .method-get {
            background-color: #0B875B;
            color: white;
        }

        .method-post {
            background-color: #F6C343;
            color: #333;
        }

        .method-put {
            background-color: #186ADE;
            color: white;
        }

        .method-patch {
            background-color: #6B4FBB;
            color: white;
        }

        .method-delete {
            background-color: #E34F4F;
            color: white;
        }

        .method-head {
            background-color: #9012FE;
            color: white;
        }

        .method-options {
            background-color: #0D5AA7;
            color: white;
        }

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

        /* ============================================
           TABS ESTILO
           ============================================ */
        .client-http-tabs {
            display: flex;
            border-bottom: 1px solid var(--card-border);
            margin-bottom: 20px;
        }

        .client-http-tab {
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-secondary);
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: var(--transition);
            position: relative;
        }

        .client-http-tab:hover {
            color: var(--text-primary);
            background-color: rgba(255, 255, 255, 0.05);
        }

        .client-http-tab.active {
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

        /* TAB CONTENT */
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* ============================================
           HEADERS EDITOR
           ============================================ */
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

        /* ============================================
           BODY EDITOR COM JSON EXPANDÍVEL
           ============================================ */
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

        /* JSON EDITOR COM EXPAND/COLLAPSE */
        .json-editor-container {
            padding: 16px;
            max-height: 400px;
            overflow-y: auto;
        }

        .json-tree {
            font-family: 'Consolas', 'Monaco', monospace;
            font-size: 13px;
            line-height: 1.4;
        }

        .json-node {
            margin-left: 20px;
            position: relative;
        }

        .json-item {
            display: flex;
            align-items: flex-start;
            margin: 2px 0;
        }

        .json-toggle {
            width: 16px;
            height: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            margin-right: 4px;
            color: var(--text-secondary);
            font-size: 10px;
            transition: transform 0.2s;
            user-select: none;
            flex-shrink: 0;
        }

        .json-toggle.collapsed {
            transform: rotate(-90deg);
        }

        /* Adicionar ao CSS existente */
        .json-viewer pre {
            margin: 0;
            font-family: 'Consolas', 'Monaco', monospace;
            font-size: 13px;
            line-height: 1.5;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .json-key {
            color: #FF6C37 !important;
            font-weight: 600;
        }

        .json-string {
            color: #98C379 !important;
        }

        .json-number {
            color: #D19A66 !important;
        }

        .json-boolean {
            color: #C678DD !important;
            font-weight: 600;
        }

        .json-null {
            color: #5C6370 !important;
            font-weight: 600;
        }

        .json-bracket,
        .json-brace {
            color: var(--text-primary);
            font-weight: bold;
        }

        .json-preview {
            color: var(--text-muted);
            font-style: italic;
            margin-left: 4px;
        }

        /* ============================================
           RESPONSE CARD
           ============================================ */
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

        .status-2xx {
            background-color: rgba(76, 175, 80, 0.2);
            color: #4CAF50;
        }

        .status-3xx {
            background-color: rgba(33, 150, 243, 0.2);
            color: #2196F3;
        }

        .status-4xx {
            background-color: rgba(244, 67, 54, 0.2);
            color: #F44336;
        }

        .status-5xx {
            background-color: rgba(244, 67, 54, 0.2);
            color: #F44336;
        }

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
            background-color: #1E222A;
            padding: 16px;
            border-radius: 4px;
            max-height: 400px;
            overflow-y: auto;
        }

        /* ============================================
           PREVIEW COM TABELAS DINÂMICAS
           ============================================ */
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

        /* Tabelas dinâmicas para JSON preview */
        .json-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        .json-table th {
            background-color: #252C3A;
            color: var(--text-secondary);
            text-align: left;
            padding: 10px 12px;
            font-size: 12px;
            font-weight: 600;
            border-bottom: 1px solid #3A4252;
        }

        .json-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #3A4252;
            vertical-align: top;
        }

        .json-table tr:hover {
            background-color: rgba(255, 255, 255, 0.03);
        }

        .json-table .json-key-cell {
            font-weight: 600;
            color: #FF6C37;
            width: 30%;
            font-family: 'Consolas', 'Monaco', monospace;
        }

        .json-table .json-value-cell {
            color: var(--text-primary);
            font-family: 'Consolas', 'Monaco', monospace;
        }

        .json-table .json-type-cell {
            color: var(--text-muted);
            font-size: 11px;
            width: 15%;
            text-align: center;
        }

        .json-table .json-nested {
            background-color: #252C3A;
            border-radius: var(--border-radius);
            padding: 8px;
            margin: 4px 0;
        }

        /* ============================================
           AUTH PANEL
           ============================================ */
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

        /* ============================================
           BOTÕES E CONTROLES
           ============================================ */
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

        /* ============================================
           LOADING
           ============================================ */
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

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* ============================================
           HISTÓRICO
           ============================================ */
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

        /* ============================================
           COLEÇÕES
           ============================================ */
        .collections-sidebar {
            width: 300px;
            background-color: var(--card-bg);
            border-left: 1px solid var(--card-border);
            padding: 20px;
            overflow-y: auto;
            display: none;
        }

        .collections-sidebar.active {
            display: block;
        }

        .collections-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .collection-list {
            margin-bottom: 20px;
        }

        .collection-item {
            background-color: #2D3440;
            border: 1px solid #3A4252;
            border-radius: var(--border-radius);
            margin-bottom: 10px;
            overflow: hidden;
            cursor: pointer;
            transition: var(--transition);
        }

        .collection-item:hover {
            border-color: var(--primary-light);
            background-color: #3A4252;
        }

        .collection-item.active {
            border-color: var(--primary-color);
            background-color: rgba(255, 108, 55, 0.1);
        }

        .collection-header {
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: rgba(0, 0, 0, 0.2);
        }

        .collection-title {
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .collection-count {
            background-color: var(--primary-color);
            color: white;
            font-size: 11px;
            padding: 2px 6px;
            border-radius: 10px;
        }

        .request-list {
            display: none;
            padding: 8px;
        }

        .collection-item.active .request-list {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        .request-item {
            padding: 10px 12px;
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: var(--border-radius);
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: var(--transition);
        }

        .request-item:hover {
            background-color: rgba(255, 108, 55, 0.1);
        }

        .request-method {
            font-size: 11px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 3px;
            min-width: 50px;
            text-align: center;
        }

        .request-url {
            flex: 1;
            font-size: 13px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: var(--text-secondary);
        }

        .request-actions {
            display: flex;
            gap: 4px;
            opacity: 0;
            transition: var(--transition);
        }

        .request-item:hover .request-actions {
            opacity: 1;
        }

        /* ============================================
           MODAIS
           ============================================ */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.7);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 10000;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal {
            background-color: var(--card-bg);
            border-radius: 8px;
            border: 1px solid var(--card-border);
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--card-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-title {
            font-size: 16px;
            font-weight: 600;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            padding: 16px 20px;
            border-top: 1px solid var(--card-border);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-primary);
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            background-color: #2D3440;
            border: 1px solid #3A4252;
            border-radius: var(--border-radius);
            color: var(--text-primary);
            font-size: 14px;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .form-textarea {
            min-height: 100px;
            resize: vertical;
            font-family: 'Inter', sans-serif;
        }

        .sidebar-tabs {
            display: flex;
            border-bottom: 1px solid var(--card-border);
            margin-bottom: 15px;
        }

        .sidebar-tab {
            flex: 1;
            text-align: center;
            padding: 10px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: var(--transition);
        }

        .sidebar-tab.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
        }

        .sidebar-tab-content {
            display: none;
        }

        .sidebar-tab-content.active {
            display: block;
        }

        .quick-actions {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
        }

        .sidebar-badge {
            background-color: var(--primary-color);
            color: white;
            font-size: 12px;
            padding: 2px 6px;
            border-radius: 10px;
            margin-left: 6px;
        }

        .collection-color {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }

        .import-export-actions {
            display: flex;
            gap: 8px;
            margin-top: 20px;
        }

        /* ============================================
           RESPONSIVIDADE
           ============================================ */
        @media (max-width: 1200px) {
            .sidebar {
                width: 64px;
            }

            .logo-text,
            .nav-text {
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

            .history-sidebar,
            .collections-sidebar {
                width: 280px;
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

            .method-select,
            .send-button {
                width: 100%;
            }

            .history-sidebar,
            .collections-sidebar {
                width: 100%;
                height: 50vh;
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                border-left: none;
                border-top: 1px solid var(--card-border);
            }
        }

        /* ============================================
           SCROLLBAR
           ============================================ */
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

        /* ============================================
           UTILITÁRIOS
           ============================================ */
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

        /* ============================================
           STATUS BADGES
           ============================================ */
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

        /* ============================================
           SYNTAX HIGHLIGHTING PARA JSON
           ============================================ */
        .json-key {
            color: #FF6C37;
        }

        .json-string {
            color:rgb(176, 82, 199);
        }

        .json-number {
            color: #D19A66;
        }

        .json-boolean {
            color: #C678DD;
        }

        .json-null {
            color: #5C6370;
        }
    </style>
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
                    <button class="btn btn-icon" id="topbar-save-collection-btn">
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
                                    <span class="method-indicator method-post" id="method-indicator">POST</span>
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
                                        <div class="json-actions" style="margin-left: 10px;">
                                            <button class="btn btn-sm" id="toggle-json-view">
                                                <i class="fas fa-code"></i> Alternar Visualização
                                            </button>
                                        </div>
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

                                <!-- Editor de JSON com collapse/expand -->
                                <div class="json-editor-container hidden" id="json-editor-container">
                                    <div class="json-tree" id="json-tree"></div>
                                </div>

                                <!-- Textarea tradicional -->
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
                                    <div id="preview-table" class="hidden"></div>
                                </div>
                            </div>

                            <!-- Headers Tab -->
                            <div id="response-headers-tab" class="tab-content">
                                <div class="preview-container text-preview" id="response-headers"></div>
                            </div>

                            <!-- Cookies Tab -->
                            <div id="response-cookies-tab" class="tab-content">
                                <div class="preview-container">
                                    Não foi recebido nenhum cookie
                                </div>
                            </div>

                            <!-- Tests Tab -->
                            <div id="response-tests-tab" class="tab-content">
                                <div class="preview-container" id="test-results">
                                    Nenhum teste definido
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
                <button class="btn btn-primary" id="modal-save-collection-btn">Salvar Coleção</button>
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

    <script>
        // ============================================
        // API CLIENT - CORE FUNCTIONALITY
        // ============================================

        class APIClient {
            constructor() {
                this.authToken = localStorage.getItem('api_client_token');
                this.currentResponse = null;
                this.currentMethod = 'POST';
                this.isMethodDropdownOpen = false;
                this.isJsonViewActive = false;



                this.init();
            }

            init() {
                this.cacheElements();
                this.setupEventListeners();
                this.updateAuthDisplay();
                this.initializeDefaultParams();
                this.updateCounts();
                this.setupJsonEditor();
            }

            cacheElements() {
                // Method elements
                this.methodIndicator = document.getElementById('method-indicator');
                this.methodOptions = document.getElementById('method-options');
                this.methodDropdown = document.getElementById('method-dropdown');

                // URL elements
                this.urlInput = document.getElementById('url-input');
                this.sendButton = document.getElementById('send-btn');

                // Response elements
                this.loadingElement = document.getElementById('loading');
                this.responseContent = document.getElementById('response-content');
                this.noResponseElement = document.getElementById('no-response');
                this.statusCode = document.getElementById('status-code');
                this.responseTime = document.getElementById('response-time');
                this.responseBody = document.getElementById('response-body');
                this.responseHeaders = document.getElementById('response-headers');

                // Preview elements
                this.previewContainer = document.getElementById('preview-container');
                this.previewPlaceholder = document.getElementById('preview-placeholder');
                this.previewIframe = document.getElementById('preview-iframe');
                this.previewText = document.getElementById('preview-text');
                this.previewTable = document.getElementById('preview-table');

                // Tabs
                this.tabs = document.querySelectorAll('.client-http-tab[data-tab]');
                this.responseTabs = document.querySelectorAll('[data-response-tab]');

                // Headers table
                this.headersTable = document.getElementById('headers-table');
                this.addHeaderBtn = document.getElementById('add-header-btn');

                // Body elements
                this.bodyInput = document.getElementById('body-input');
                this.prettifyBodyBtn = document.getElementById('prettify-body-btn');
                this.clearBodyBtn = document.getElementById('clear-body-btn');
                this.toggleJsonViewBtn = document.getElementById('toggle-json-view');
                this.jsonEditorContainer = document.getElementById('json-editor-container');
                this.jsonTree = document.getElementById('json-tree');

                // Auth elements
                this.authMethodSelect = document.getElementById('auth-method');
                this.bearerTokenInput = document.getElementById('bearer-token');
                this.tokenDisplay = document.getElementById('token-display');
                this.tokenValue = document.getElementById('token-value');
                this.addAuthHeaderBtn = document.getElementById('add-auth-header-btn');
                this.extractTokenBtn = document.getElementById('extract-token-btn');
                this.clearTokenBtn = document.getElementById('clear-token-btn');

                // Response actions
                this.copyResponseBtn = document.getElementById('copy-response-btn');
                this.saveResponseBtn = document.getElementById('save-response-btn');

                // Params
                this.paramsTable = document.getElementById('params-table');
                this.addParamBtn = document.getElementById('add-param-btn');

                // Environment
                this.envVarsInput = document.getElementById('env-vars-input');

                // Body type and format
                this.bodyTypeSelect = document.getElementById('body-type');
                this.bodyFormatSelect = document.getElementById('body-format');

                // Tests
                this.testsInput = document.getElementById('tests-input');
            }

            setupEventListeners() {
                // Method dropdown
                this.methodDropdown.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.toggleMethodDropdown();
                });

                document.querySelectorAll('.method-option').forEach(option => {
                    option.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const method = option.getAttribute('data-method');
                        this.setMethod(method);
                        this.methodOptions.classList.remove('show');
                        this.isMethodDropdownOpen = false;
                    });
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', (e) => {
                    if (!this.methodDropdown.contains(e.target) && this.isMethodDropdownOpen) {
                        this.methodOptions.classList.remove('show');
                        this.isMethodDropdownOpen = false;
                    }
                });

                // Send request
                this.sendButton.addEventListener('click', () => this.sendRequest());
                this.urlInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') this.sendRequest();
                });

                // Tabs
                this.tabs.forEach(tab => {
                    tab.addEventListener('click', () => {
                        this.switchTab(tab.getAttribute('data-tab'));
                    });
                });

                this.responseTabs.forEach(tab => {
                    tab.addEventListener('click', () => {
                        this.switchResponseTab(tab.getAttribute('data-response-tab'));
                    });
                });

                // Headers table
                this.addHeaderBtn.addEventListener('click', () => this.addHeaderRow());

                // Body editor
                this.prettifyBodyBtn.addEventListener('click', () => this.prettifyJson(this.bodyInput));
                this.clearBodyBtn.addEventListener('click', () => {
                    this.bodyInput.value = '';
                    this.jsonTree.innerHTML = '';
                    this.showAlert('Body limpo', 'info');
                });

                // Toggle JSON view
                this.toggleJsonViewBtn.addEventListener('click', () => this.toggleJsonView());

                // Body type changes
                this.bodyTypeSelect.addEventListener('change', () => this.updateBodyType());
                this.bodyFormatSelect.addEventListener('change', () => this.updateBodyFormat());

                // Auth
                this.authMethodSelect.addEventListener('change', () => this.updateAuthMethod());
                this.addAuthHeaderBtn.addEventListener('click', () => this.addAuthHeader());
                this.extractTokenBtn.addEventListener('click', () => this.extractTokenFromResponse());
                this.clearTokenBtn.addEventListener('click', () => this.clearToken());

                // Response
                this.copyResponseBtn.addEventListener('click', () => this.copyResponse());
                this.saveResponseBtn.addEventListener('click', () => this.saveResponse());

                // Params
                this.addParamBtn.addEventListener('click', () => this.addParamRow());

                // Duplicate button
                document.getElementById('duplicate-btn').addEventListener('click', () => this.duplicateRequest());

                // Bulk edit button
                document.getElementById('bulk-edit-btn').addEventListener('click', () => this.bulkEditHeaders());

                // Tests
                this.testsInput.addEventListener('input', () => this.saveTests());

                // Body input change for JSON editor
                this.bodyInput.addEventListener('input', () => {
                    if (this.isJsonViewActive) {
                        this.updateJsonEditor();
                    }
                });
            }

            setupJsonEditor() {
                // Inicializar editor de JSON se o body for JSON
                this.updateJsonEditor();
            }

            toggleJsonView() {
                this.isJsonViewActive = !this.isJsonViewActive;

                if (this.isJsonViewActive) {
                    // Mostrar editor de JSON
                    this.bodyInput.classList.add('hidden');
                    this.jsonEditorContainer.classList.remove('hidden');
                    this.toggleJsonViewBtn.innerHTML = '<i class="fas fa-keyboard"></i> Alternar para Texto';
                    this.updateJsonEditor();
                } else {
                    // Mostrar textarea
                    this.bodyInput.classList.remove('hidden');
                    this.jsonEditorContainer.classList.add('hidden');
                    this.toggleJsonViewBtn.innerHTML = '<i class="fas fa-code"></i> Alternar para JSON';
                }
            }

            updateJsonEditor() {
                const text = this.bodyInput.value.trim();
                this.jsonTree.innerHTML = '';

                if (!text) {
                    const emptyMsg = document.createElement('div');
                    emptyMsg.className = 'json-item';
                    emptyMsg.innerHTML = '<span class="json-null">Empty JSON</span>';
                    this.jsonTree.appendChild(emptyMsg);
                    return;
                }

                try {
                    const json = JSON.parse(text);
                    const element = this.createJsonElement('', json, true);
                    this.jsonTree.appendChild(element);
                } catch (e) {
                    const errorMsg = document.createElement('div');
                    errorMsg.className = 'json-item';
                    errorMsg.innerHTML = `<span style="color: #F44336;">Invalid JSON: ${e.message}</span>`;
                    this.jsonTree.appendChild(errorMsg);
                }
            }

            createJsonElement(key, value, isRoot = false) {
                const item = document.createElement('div');
                item.className = 'json-item';

                const type = typeof value;
                const isArray = Array.isArray(value);
                const isObject = type === 'object' && value !== null && !isArray;
                const isPrimitive = !isObject && !isArray;

                // Para valores primitivos
                if (isPrimitive) {
                    let valueElement;
                    if (type === 'string') {
                        valueElement = `<span class="json-string">"${this.escapeHtml(value)}"</span>`;
                    } else if (type === 'number') {
                        valueElement = `<span class="json-number">${value}</span>`;
                    } else if (type === 'boolean') {
                        valueElement = `<span class="json-boolean">${value}</span>`;
                    } else if (value === null) {
                        valueElement = `<span class="json-null">null</span>`;
                    }

                    if (isRoot) {
                        item.innerHTML = valueElement;
                    } else {
                        item.innerHTML = `
                            <span class="json-key">"${key}"</span>
                            <span class="json-colon">:</span>
                            ${valueElement}
                        `;
                    }
                }
                // Para objetos e arrays
                else {
                    const isCollapsible = (isObject && Object.keys(value).length > 0) || (isArray && value.length > 0);
                    const bracket = isArray ? '[' : '{';
                    const closeBracket = isArray ? ']' : '}';
                    const itemCount = isArray ? value.length : Object.keys(value).length;

                    const toggle = document.createElement('span');
                    toggle.className = 'json-toggle';
                    toggle.innerHTML = '<i class="fas fa-chevron-right"></i>';
                    toggle.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const node = this.closest('.json-item').querySelector('.json-node');
                        if (node) {
                            node.classList.toggle('hidden');
                            this.classList.toggle('collapsed');
                        }
                    });

                    if (isRoot) {
                        item.innerHTML = `
                            ${isCollapsible ? toggle.outerHTML : ''}
                            <span class="json-brace">${bracket}</span>
                            ${isCollapsible ? `<span class="json-preview">${itemCount} item${itemCount !== 1 ? 's' : ''}</span>` : ''}
                        `;
                    } else {
                        item.innerHTML = `
                            <span class="json-key">"${key}"</span>
                            <span class="json-colon">:</span>
                            ${isCollapsible ? toggle.outerHTML : ''}
                            <span class="json-brace">${bracket}</span>
                            ${isCollapsible ? `<span class="json-preview">${itemCount} item${itemCount !== 1 ? 's' : ''}</span>` : ''}
                        `;
                    }

                    // Adicionar conteúdo colapsável
                    if (isCollapsible) {
                        const node = document.createElement('div');
                        node.className = 'json-node hidden';

                        if (isArray) {
                            value.forEach((itemValue, index) => {
                                const childElement = this.createJsonElement(index, itemValue);
                                node.appendChild(childElement);
                            });
                        } else if (isObject) {
                            Object.keys(value).forEach(childKey => {
                                const childElement = this.createJsonElement(childKey, value[childKey]);
                                node.appendChild(childElement);
                            });
                        }

                        item.appendChild(node);
                    }

                    // Fechar bracket
                    const closeElement = document.createElement('div');
                    closeElement.className = 'json-item';
                    closeElement.innerHTML = `<span class="json-brace">${closeBracket}</span>`;

                    const container = document.createElement('div');
                    container.appendChild(item);
                    if (isCollapsible) {
                        container.appendChild(closeElement);
                    }

                    return container;
                }

                return item;
            }

            toggleMethodDropdown() {
                this.isMethodDropdownOpen = !this.isMethodDropdownOpen;
                if (this.isMethodDropdownOpen) {
                    this.methodOptions.classList.add('show');
                } else {
                    this.methodOptions.classList.remove('show');
                }
            }

            setMethod(method) {
                this.currentMethod = method;
                this.methodIndicator.textContent = method;
                this.methodIndicator.className = `method-indicator method-${method.toLowerCase()}`;

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
                    this.bodyInput.value = '';
                    if (this.isJsonViewActive) {
                        this.updateJsonEditor();
                    }
                }
            }

            switchTab(tabId) {
                // Update active tab
                this.tabs.forEach(tab => {
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

            switchResponseTab(tabId) {
                // Update active tab
                this.responseTabs.forEach(tab => {
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
                if (tabId === 'preview' && this.currentResponse) {
                    this.updatePreview(this.currentResponse);
                }
            }

            addHeaderRow() {
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
                        <button class="btn btn-icon btn-sm delete-header-btn">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;

                // Add event listener to delete button
                const deleteBtn = row.querySelector('.delete-header-btn');
                deleteBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    row.remove();
                    this.updateHeadersCount();
                });

                this.headersTable.appendChild(row);
                this.updateHeadersCount();
            }

            addParamRow() {
                const row = document.createElement('tr');
                row.className = 'header-row';
                row.innerHTML = `
                    <td>
                        <input type="checkbox" checked>
                    </td>
                    <td>
                        <input type="text" placeholder="key" value="${this.paramsTable.children.length === 0 ? 'page' : ''}">
                    </td>
                    <td>
                        <input type="text" placeholder="value" value="${this.paramsTable.children.length === 0 ? '1' : ''}">
                    </td>
                    <td>
                        <input type="text" placeholder="Description" value="${this.paramsTable.children.length === 0 ? 'Page number' : ''}">
                    </td>
                    <td>
                        <button class="btn btn-icon btn-sm" onclick="this.closest('tr').remove(); updateParamsCount();">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                this.paramsTable.appendChild(row);
                this.updateParamsCount();
            }

            updateHeadersCount() {
                const count = this.headersTable.querySelectorAll('tr').length;
                document.getElementById('headers-count').textContent = count;
            }

            updateParamsCount() {
                const count = this.paramsTable.querySelectorAll('tr').length;
                document.getElementById('params-count').textContent = count;
            }

            updateCounts() {
                this.updateHeadersCount();
                this.updateParamsCount();
            }

            updateBodyType() {
                const type = this.bodyTypeSelect.value;
                if (type === 'none') {
                    this.bodyInput.style.display = 'none';
                    this.jsonEditorContainer.style.display = 'none';
                } else {
                    this.bodyInput.style.display = 'block';
                    if (this.isJsonViewActive) {
                        this.jsonEditorContainer.style.display = 'block';
                    }
                }
            }

            updateBodyFormat() {
                const format = this.bodyFormatSelect.value;
                if (format === 'json') {
                    this.toggleJsonViewBtn.style.display = 'inline-flex';
                    this.updateJsonEditor();
                } else {
                    this.toggleJsonViewBtn.style.display = 'none';
                    this.bodyInput.classList.remove('hidden');
                    this.jsonEditorContainer.classList.add('hidden');
                    this.isJsonViewActive = false;
                }
            }

            async sendRequest() {
                const method = this.currentMethod;
                let url = this.urlInput.value.trim();

                if (!url) {
                    this.showAlert('Por favor, informe uma URL', 'warning');
                    return;
                }

                // Process environment variables
                url = this.processEnvVars(url);

                // Add query parameters
                const params = this.getQueryParams();
                if (params) {
                    url += (url.includes('?') ? '&' : '?') + params;
                }

                // Validate URL
                try {
                    new URL(url);
                } catch (e) {
                    this.showAlert('URL inválida. Certifique-se de incluir http:// ou https://', 'danger');
                    return;
                }

                // Prepare headers
                const headers = this.getHeaders();

                // Prepare body
                let body = null;
                if (method !== 'GET' && method !== 'HEAD' && this.bodyInput.value.trim()) {
                    body = this.bodyInput.value.trim();
                }

                // Show loading
                this.loadingElement.classList.add('active');
                this.responseContent.classList.add('hidden');
                this.noResponseElement.classList.add('hidden');

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
                    this.updateResponseUI(response, responseText, responseTime);

                    // Run tests if defined
                    if (this.testsInput.value.trim()) {
                        this.runTests(response, responseText);
                    }

                    // Add to history
                    this.addToHistory({
                        method,
                        url: this.urlInput.value.trim(),
                        headers: headers,
                        body: body,
                        status: response.status,
                        statusText: response.statusText,
                        timestamp: new Date().toISOString(),
                        responseTime
                    });

                    // Show response
                    this.loadingElement.classList.remove('active');
                    this.responseContent.classList.remove('hidden');

                    // Show success message
                    this.showAlert(`Requisição enviada com sucesso! (${responseTime}ms)`, 'success');

                } catch (error) {
                    this.loadingElement.classList.remove('active');

                    // Show error
                    this.statusCode.textContent = 'Error';
                    this.statusCode.className = 'status-code status-4xx';
                    this.statusCode.classList.remove('hidden');
                    this.responseTime.textContent = '';
                    this.responseBody.textContent = `Erro de rede: ${error.message}`;
                    this.responseHeaders.textContent = '';

                    this.responseContent.classList.remove('hidden');

                    // Add error to history
                    this.addToHistory({
                        method,
                        url: this.urlInput.value.trim(),
                        headers: headers,
                        body: body,
                        status: 0,
                        statusText: 'Erro de Rede',
                        error: error.message,
                        timestamp: new Date().toISOString()
                    });

                    this.showAlert(`Erro de rede: ${error.message}`, 'danger');
                }
            }

            getHeaders() {
                const headers = {};
                this.headersTable.querySelectorAll('tr').forEach(row => {
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

            getQueryParams() {
                const params = new URLSearchParams();
                this.paramsTable.querySelectorAll('tr').forEach(row => {
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

            processEnvVars(url) {
                try {
                    const envVars = JSON.parse(this.envVarsInput.value || '{}');
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

            updateResponseUIOld(response, responseText, responseTimeMs) {
                // Update status
                this.statusCode.textContent = `${response.status} ${response.statusText}`;
                this.statusCode.className = 'status-code';

                if (response.status >= 200 && response.status < 300) {
                    this.statusCode.classList.add('status-2xx');
                } else if (response.status >= 300 && response.status < 400) {
                    this.statusCode.classList.add('status-3xx');
                } else if (response.status >= 400 && response.status < 500) {
                    this.statusCode.classList.add('status-4xx');
                } else if (response.status >= 500) {
                    this.statusCode.classList.add('status-5xx');
                }

                this.statusCode.classList.remove('hidden');
                this.responseTime.textContent = `${responseTimeMs}ms`;
                this.responseTime.classList.remove('hidden');

                // Try to parse as JSON for formatting
                let formattedBody = responseText;
                try {
                    const jsonResponse = JSON.parse(responseText);
                    formattedBody = this.syntaxHighlight(JSON.stringify(jsonResponse, null, 2));
                } catch (e) {
                    // Not JSON, keep as text
                    formattedBody = this.escapeHtml(responseText);
                }

                // Format response headers
                const headersArray = [];
                response.headers.forEach((value, key) => {
                    headersArray.push(`${key}: ${value}`);
                });

                this.responseBody.innerHTML = formattedBody;
                this.responseHeaders.textContent = headersArray.join('\n');

                // Store current response
                this.currentResponse = {
                    text: responseText,
                    headers: response.headers,
                    contentType: response.headers.get('content-type') || '',
                    status: response.status,
                    statusText: response.statusText
                };
            }

            syntaxHighlightOld(json) {
                if (typeof json != 'string') {
                    json = JSON.stringify(json, null, 2);
                }

                // Escape HTML
                json = this.escapeHtml(json);

                // Apply styles
                return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function(match) {
                    if (/^"/.test(match)) {
                        if (/:$/.test(match)) {
                            return `<span class="json-key">${match}</span>`;
                        } else {
                            return `<span class="json-string">${match}</span>`;
                        }
                    } else if (/true|false/.test(match)) {
                        return `<span class="json-boolean">${match}</span>`;
                    } else if (/null/.test(match)) {
                        return `<span class="json-null">${match}</span>`;
                    } else {
                        return `<span class="json-number">${match}</span>`;
                    }
                });
            }

            escapeHtml(text) {
                const map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return text.replace(/[&<>"']/g, function(m) {
                    return map[m];
                });
            }

            updateResponseUI(response, responseText, responseTimeMs) {
                // Update status
                this.statusCode.textContent = `${response.status} ${response.statusText}`;
                this.statusCode.className = 'status-code';

                if (response.status >= 200 && response.status < 300) {
                    this.statusCode.classList.add('status-2xx');
                } else if (response.status >= 300 && response.status < 400) {
                    this.statusCode.classList.add('status-3xx');
                } else if (response.status >= 400 && response.status < 500) {
                    this.statusCode.classList.add('status-4xx');
                } else if (response.status >= 500) {
                    this.statusCode.classList.add('status-5xx');
                }

                this.statusCode.classList.remove('hidden');
                this.responseTime.textContent = `${responseTimeMs}ms`;
                this.responseTime.classList.remove('hidden');

                // Format response body with syntax highlighting
                let formattedBody = responseText;
                try {
                    const jsonResponse = JSON.parse(responseText);
                    formattedBody = this.formatJsonWithHighlight(jsonResponse);
                    this.responseBody.innerHTML = formattedBody;
                } catch (e) {
                    // Not JSON, check if it's HTML
                    if (responseText.trim().startsWith('<')) {
                        formattedBody = this.escapeHtml(responseText);
                        this.responseBody.innerHTML = `<pre style="color: #E06C75;">${formattedBody}</pre>`;
                    } else if (response.headers.get('content-type')?.includes('xml')) {
                        formattedBody = this.escapeHtml(responseText);
                        this.responseBody.innerHTML = `<pre style="color: #98C379;">${formattedBody}</pre>`;
                    } else {
                        // Plain text
                        formattedBody = this.escapeHtml(responseText);
                        this.responseBody.innerHTML = `<pre style="color: #ABB2BF;">${formattedBody}</pre>`;
                    }
                }

                // Format response headers
                const headersArray = [];
                response.headers.forEach((value, key) => {
                    headersArray.push(`${key}: ${value}`);
                });

                this.responseBody.innerHTML = formattedBody;
                this.responseHeaders.textContent = headersArray.join('\n');

                // Format response headers
                // const headersArray = [];
                // response.headers.forEach((value, key) => {
                //     headersArray.push(`${key}: ${value}`);
                // });

                // this.responseHeaders.textContent = headersArray.join('\n');

                // Store current response
                this.currentResponse = {
                    text: responseText,
                    headers: response.headers,
                    contentType: response.headers.get('content-type') || '',
                    status: response.status,
                    statusText: response.statusText
                };
            }

            formatJsonWithHighlight(json) {
                const jsonString = JSON.stringify(json, null, 2);
                let result = '';
                let inString = false;
                let escapeChar = false;
                let lastChar = '';

                for (let i = 0; i < jsonString.length; i++) {
                    const char = jsonString[i];
                    const nextChar = jsonString[i + 1];

                    if (escapeChar) {
                        result += `<span class="json-string">${char}</span>`;
                        escapeChar = false;
                        continue;
                    }

                    if (char === '\\') {
                        result += `<span class="json-string">${char}</span>`;
                        escapeChar = true;
                        continue;
                    }

                    if (char === '"' && lastChar !== '\\') {
                        inString = !inString;
                        if (inString) {
                            // Start of a string
                            let keyStart = false;
                            // Look back to see if this might be a key
                            let j = i - 1;
                            while (j >= 0 && (jsonString[j] === ' ' || jsonString[j] === '\t' || jsonString[j] === '\n')) {
                                j--;
                            }
                            if (jsonString[j] === ':' || jsonString[j] === '{' || jsonString[j] === '[' || jsonString[j] === ',') {
                                // This is a value string
                                result += '<span class="json-string">"';
                            } else {
                                // This might be a key
                                keyStart = true;
                                result += '<span class="json-key">"';
                            }
                        } else {
                            // End of a string
                            if (result.endsWith('<span class="json-key">"')) {
                                result += '"</span>';
                            } else if (result.endsWith('<span class="json-string">"')) {
                                result += '"</span>';
                            }
                        }
                        lastChar = char;
                        continue;
                    }

                    if (inString) {
                        if (result.endsWith('<span class="json-key">"')) {
                            result += char;
                        } else if (result.endsWith('<span class="json-string">"')) {
                            result += char;
                        } else {
                            result += char;
                        }
                    } else {
                        // Not in a string
                        if (char.match(/\d/)) {
                            // Start of a number
                            let numStr = char;
                            while (i + 1 < jsonString.length && jsonString[i + 1].match(/[\d\.eE\+\-]/)) {
                                i++;
                                numStr += jsonString[i];
                            }
                            result += `<span class="json-number">${numStr}</span>`;
                        } else if (char.match(/[tT]/) && jsonString.substr(i, 4).toLowerCase() === 'true') {
                            result += `<span class="json-boolean">true</span>`;
                            i += 3;
                        } else if (char.match(/[fF]/) && jsonString.substr(i, 5).toLowerCase() === 'false') {
                            result += `<span class="json-boolean">false</span>`;
                            i += 4;
                        } else if (char.match(/[nN]/) && jsonString.substr(i, 4).toLowerCase() === 'null') {
                            result += `<span class="json-null">null</span>`;
                            i += 3;
                        } else if (char === ':') {
                            result += `<span style="color: #ABB2BF;">${char}</span>`;
                        } else if (char === '{' || char === '}' || char === '[' || char === ']' || char === ',') {
                            result += `<span style="color: #ABB2BF; font-weight: bold;">${char}</span>`;
                        } else {
                            result += `<span style="color: #ABB2BF;">${char}</span>`;
                        }
                    }

                    lastChar = char;
                }

                return `<pre>${result}</pre>`;
            }

            escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }
            updatePreview(response) {
                const contentType = response.contentType.toLowerCase();
                const responseText = response.text;

                // Hide all preview elements
                this.previewIframe.classList.add('hidden');
                this.previewText.classList.add('hidden');
                this.previewTable.classList.add('hidden');
                this.previewPlaceholder.classList.add('hidden');

                // Clear previous iframe content
                this.previewIframe.src = 'about:blank';

                if (contentType.includes('text/html')) {
                    // HTML preview
                    const blob = new Blob([responseText], {
                        type: 'text/html;charset=utf-8'
                    });
                    const url = URL.createObjectURL(blob);

                    this.previewIframe.src = url;
                    this.previewIframe.classList.remove('hidden');
                    this.previewContainer.classList.add('html-preview');

                    this.previewIframe.onload = function() {
                        URL.revokeObjectURL(url);
                    };

                } else if (contentType.includes('application/json')) {
                    try {
                        const jsonObj = JSON.parse(responseText);

                        // Criar tabela para preview do JSON
                        const tableHtml = this.jsonToTable(jsonObj);
                        this.previewTable.innerHTML = tableHtml;
                        this.previewTable.classList.remove('hidden');
                        this.previewContainer.classList.remove('html-preview');
                    } catch (e) {
                        // Se não conseguir fazer tabela, mostrar JSON formatado
                        this.previewText.innerHTML = this.syntaxHighlight(JSON.stringify(jsonObj, null, 2));
                        this.previewText.classList.remove('hidden');
                        this.previewContainer.classList.remove('html-preview');
                    }

                } else if (contentType.includes('text/')) {
                    this.previewText.textContent = responseText;
                    this.previewText.classList.remove('hidden');
                    this.previewContainer.classList.add('text-preview');

                } else {
                    this.previewPlaceholder.innerHTML = `
                        <i class="fas fa-file" style="font-size: 48px; margin-bottom: 16px;"></i>
                        <p style="font-size: 14px; margin-bottom: 8px;">Tipo de conteúdo: ${contentType || 'desconhecido'}</p>
                        <p style="font-size: 12px; color: var(--text-muted);">
                            Preview não disponível para este tipo de conteúdo
                        </p>
                    `;
                    this.previewPlaceholder.classList.remove('hidden');
                    this.previewContainer.classList.remove('html-preview');
                }
            }

            jsonToTable(data, depth = 0) {
                if (depth > 3) {
                    return '<div class="json-nested">[Nested data...]</div>';
                }

                const isArray = Array.isArray(data);
                const isObject = typeof data === 'object' && data !== null && !isArray;

                if (!isObject && !isArray) {
                    return `<span class="${this.getValueClass(data)}">${this.formatValue(data)}</span>`;
                }

                let html = '';
                const items = isArray ? data : Object.keys(data);

                if (items.length === 0) {
                    return `<span class="json-null">${isArray ? '[]' : '{}'}</span>`;
                }

                // Para arrays de objetos, criar tabela detalhada
                if (isArray && data.length > 0 && typeof data[0] === 'object' && data[0] !== null) {
                    // Criar tabela com colunas baseadas nas chaves do primeiro objeto
                    const firstItem = data[0];
                    const keys = Object.keys(firstItem);

                    html = '<table class="json-table">';
                    html += '<thead><tr>';
                    html += '<th>#</th>';
                    keys.forEach(key => {
                        html += `<th>${key}</th>`;
                    });
                    html += '</tr></thead>';
                    html += '<tbody>';

                    data.forEach((item, index) => {
                        html += '<tr>';
                        html += `<td class="json-type-cell">${index}</td>`;
                        keys.forEach(key => {
                            const value = item[key];
                            html += `<td class="json-value-cell">${this.jsonToTable(value, depth + 1)}</td>`;
                        });
                        html += '</tr>';
                    });

                    html += '</tbody></table>';
                } else {
                    // Tabela simples para objetos ou arrays simples
                    html = '<table class="json-table">';
                    html += '<thead><tr>';
                    html += '<th>Key</th>';
                    html += '<th>Value</th>';
                    html += '<th>Type</th>';
                    html += '</tr></thead>';
                    html += '<tbody>';

                    items.forEach((key, index) => {
                        const value = isArray ? data[index] : data[key];
                        const valueType = this.getValueType(value);

                        html += '<tr>';
                        if (isArray) {
                            html += `<td class="json-key-cell">[${index}]</td>`;
                        } else {
                            html += `<td class="json-key-cell">${key}</td>`;
                        }
                        html += `<td class="json-value-cell">${this.jsonToTable(value, depth + 1)}</td>`;
                        html += `<td class="json-type-cell"><span class="badge badge-info">${valueType}</span></td>`;
                        html += '</tr>';
                    });

                    html += '</tbody></table>';
                }

                return html;
            }

            getValueType(value) {
                if (value === null) return 'null';
                if (Array.isArray(value)) return 'array';
                if (typeof value === 'object') return 'object';
                return typeof value;
            }

            getValueClass(value) {
                if (value === null) return 'json-null';
                if (typeof value === 'string') return 'json-string';
                if (typeof value === 'number') return 'json-number';
                if (typeof value === 'boolean') return 'json-boolean';
                return '';
            }

            formatValue(value) {
                if (value === null) return 'null';
                if (typeof value === 'string') return `"${this.escapeHtml(value)}"`;
                if (typeof value === 'boolean') return value.toString();
                if (typeof value === 'number') return value.toString();
                if (Array.isArray(value)) return `[${value.length} items]`;
                if (typeof value === 'object') return `{${Object.keys(value).length} keys}`;
                return String(value);
            }

            prettifyJson(textarea) {
                try {
                    const json = JSON.parse(textarea.value);
                    textarea.value = JSON.stringify(json, null, 2);
                    if (this.isJsonViewActive) {
                        this.updateJsonEditor();
                    }
                    this.showAlert('JSON formatado com sucesso!', 'success');
                } catch (e) {
                    this.showAlert('Não é um JSON válido para formatar', 'warning');
                }
            }

            syntaxHighlight(json) {
                if (typeof json != 'string') {
                    json = JSON.stringify(json, null, 2);
                }

                // Escape HTML
                json = this.escapeHtml(json);

                // Apply styles
                return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function(match) {
                    if (/^"/.test(match)) {
                        if (/:$/.test(match)) {
                            return `<span class="json-key">${match}</span>`;
                        } else {
                            return `<span class="json-string">${match}</span>`;
                        }
                    } else if (/true|false/.test(match)) {
                        return `<span class="json-boolean">${match}</span>`;
                    } else if (/null/.test(match)) {
                        return `<span class="json-null">${match}</span>`;
                    } else {
                        return `<span class="json-number">${match}</span>`;
                    }
                });
            }

            escapeHtml(text) {
                const map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return text.replace(/[&<>"']/g, function(m) {
                    return map[m];
                });
            }

            updateAuthMethod() {
                const method = this.authMethodSelect.value;
                // Show/hide appropriate auth panels
                document.querySelectorAll('.auth-content').forEach(panel => {
                    panel.style.display = 'none';
                });

                const selectedPanel = document.getElementById(`${method}-auth`);
                if (selectedPanel) {
                    selectedPanel.style.display = 'block';
                }
            }

            addAuthHeader() {
                if (this.authToken) {
                    // Check if Authorization header already exists
                    let headerExists = false;
                    this.headersTable.querySelectorAll('tr').forEach(row => {
                        const keyInput = row.querySelector('td:nth-child(2) input');
                        if (keyInput && keyInput.value === 'Authorization') {
                            headerExists = true;
                            const valueInput = row.querySelector('td:nth-child(3) input');
                            if (valueInput) {
                                valueInput.value = `Bearer ${this.authToken}`;
                            }
                        }
                    });

                    if (!headerExists) {
                        // Add a new header row with Authorization
                        this.addHeaderRow();
                        const lastRow = this.headersTable.lastElementChild;
                        const keyInput = lastRow.querySelector('td:nth-child(2) input');
                        const valueInput = lastRow.querySelector('td:nth-child(3) input');
                        if (keyInput) keyInput.value = 'Authorization';
                        if (valueInput) valueInput.value = `Bearer ${this.authToken}`;
                    }

                    this.showAlert('Authorization header adicionado', 'success');
                } else {
                    this.showAlert('Nenhum token de autenticação armazenado', 'warning');
                }
            }

            extractTokenFromResponse() {
                if (!this.currentResponse) {
                    this.showAlert('Nenhuma resposta disponível', 'warning');
                    return;
                }

                try {
                    const jsonResponse = JSON.parse(this.currentResponse.text);

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
                        this.authToken = token;
                        localStorage.setItem('api_client_token', this.authToken);
                        this.bearerTokenInput.value = this.authToken;
                        this.tokenDisplay.classList.remove('hidden');
                        this.tokenValue.textContent = this.authToken.substring(0, 50) + '...';
                        this.showAlert('Token extraído e armazenado com sucesso!', 'success');
                    } else {
                        this.showAlert('Não foi possível encontrar um token na resposta', 'warning');
                    }
                } catch (e) {
                    this.showAlert('Erro ao processar a resposta para extrair token', 'danger');
                }
            }

            clearToken() {
                this.authToken = null;
                localStorage.removeItem('api_client_token');
                this.bearerTokenInput.value = '';
                this.tokenDisplay.classList.add('hidden');
                this.showAlert('Token removido com sucesso!', 'info');
            }

            updateAuthDisplay() {
                if (this.authToken) {
                    this.tokenDisplay.classList.remove('hidden');
                    this.tokenValue.textContent = this.authToken.substring(0, 50) + '...';
                }
            }

            copyResponse() {
                if (!this.currentResponse) return;

                const activeTab = document.querySelector('[data-response-tab].active');
                if (activeTab) {
                    const tabId = activeTab.getAttribute('data-response-tab');
                    let textToCopy = this.currentResponse.text;

                    if (tabId === 'body') {
                        textToCopy = this.responseBody.textContent;
                    } else if (tabId === 'headers') {
                        textToCopy = this.responseHeaders.textContent;
                    }

                    navigator.clipboard.writeText(textToCopy).then(() => {
                        this.showAlert('Resposta copiada para a área de transferência', 'success');
                    });
                }
            }

            saveResponse() {
                if (!this.currentResponse) return;

                const blob = new Blob([this.currentResponse.text], {
                    type: 'text/plain'
                });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `response-${Date.now()}.txt`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
                this.showAlert('Resposta salva como arquivo', 'success');
            }

            addToHistory(request) {
                let requestHistory = JSON.parse(localStorage.getItem('api_client_history') || '[]');
                requestHistory.unshift(request);

                if (requestHistory.length > 50) {
                    requestHistory = requestHistory.slice(0, 50);
                }

                localStorage.setItem('api_client_history', JSON.stringify(requestHistory));
                // Also update the history sidebar if it exists
                if (window.historyManager) {
                    window.historyManager.addToHistory(request);
                }
            }

            initializeDefaultParams() {
                // Add one default param row
                if (this.paramsTable.children.length === 0) {
                    this.addParamRow();
                }
            }

            duplicateRequest() {
                // Create a copy of current request in a new tab
                const newRequest = {
                    method: this.currentMethod,
                    url: this.urlInput.value,
                    headers: this.getHeaders(),
                    body: this.bodyInput.value,
                    params: this.getQueryParams()
                };

                this.showAlert('Requisição duplicada (funcionalidade em desenvolvimento)', 'info');
            }

            bulkEditHeaders() {
                const headersText = [];
                this.headersTable.querySelectorAll('tr').forEach(row => {
                    const keyInput = row.querySelector('td:nth-child(2) input');
                    const valueInput = row.querySelector('td:nth-child(3) input');
                    if (keyInput && keyInput.value && valueInput && valueInput.value) {
                        headersText.push(`${keyInput.value}: ${valueInput.value}`);
                    }
                });

                const textarea = document.createElement('textarea');
                textarea.value = headersText.join('\n');
                textarea.style.cssText = 'width: 100%; height: 200px; margin-bottom: 10px;';

                const modal = document.createElement('div');
                modal.className = 'modal-overlay active';
                modal.innerHTML = `
                    <div class="modal" style="max-width: 600px;">
                        <div class="modal-header">
                            <div class="modal-title">Editar Headers em Lote</div>
                            <button class="btn btn-icon btn-sm" id="close-bulk-edit">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p style="margin-bottom: 10px; font-size: 13px; color: var(--text-secondary);">
                                Formato: Key: Value (um por linha)
                            </p>
                            ${textarea.outerHTML}
                        </div>
                        <div class="modal-footer">
                            <button class="btn" id="cancel-bulk-edit">Cancelar</button>
                            <button class="btn btn-primary" id="apply-bulk-edit">Aplicar</button>
                        </div>
                    </div>
                `;

                document.body.appendChild(modal);

                const closeModal = () => modal.remove();

                modal.querySelector('#close-bulk-edit').addEventListener('click', closeModal);
                modal.querySelector('#cancel-bulk-edit').addEventListener('click', closeModal);

                modal.querySelector('#apply-bulk-edit').addEventListener('click', () => {
                    const newHeaders = textarea.value.split('\n').filter(line => line.trim());
                    this.headersTable.innerHTML = '';

                    newHeaders.forEach(line => {
                        const [key, ...valueParts] = line.split(':');
                        const value = valueParts.join(':').trim();
                        if (key && value) {
                            const row = document.createElement('tr');
                            row.className = 'header-row';
                            row.innerHTML = `
                                <td>
                                    <input type="checkbox" checked>
                                </td>
                                <td>
                                    <input type="text" value="${key.trim()}">
                                </td>
                                <td>
                                    <input type="text" value="${value}">
                                </td>
                                <td>
                                    <input type="text" placeholder="Description">
                                </td>
                                <td>
                                    <button class="btn btn-icon btn-sm delete-header-btn">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            `;

                            // Add event listener to delete button
                            const deleteBtn = row.querySelector('.delete-header-btn');
                            deleteBtn.addEventListener('click', (e) => {
                                e.preventDefault();
                                row.remove();
                                this.updateHeadersCount();
                            });

                            this.headersTable.appendChild(row);
                        }
                    });

                    this.updateHeadersCount();
                    closeModal();
                    this.showAlert('Headers atualizados em lote', 'success');
                });
            }

            runTests(response, responseText) {
                const testResultsContainer = document.getElementById('test-results');
                const tests = this.testsInput.value;

                try {
                    // Simple test execution (in a real app, you'd want a more robust solution)
                    const testResults = [];

                    // Check for common test patterns
                    if (tests.includes('pm.test')) {
                        // Very basic PM-like test syntax support
                        if (tests.includes('pm.response.to.have.status(200)')) {
                            const passed = response.status === 200;
                            testResults.push({
                                name: 'Status code is 200',
                                passed: passed,
                                message: passed ? 'Status is 200' : `Status is ${response.status}`
                            });
                        }

                        if (tests.includes('pm.response.to.be.json')) {
                            try {
                                JSON.parse(responseText);
                                testResults.push({
                                    name: 'Response is JSON',
                                    passed: true,
                                    message: 'Valid JSON response'
                                });
                            } catch (e) {
                                testResults.push({
                                    name: 'Response is JSON',
                                    passed: false,
                                    message: 'Response is not valid JSON'
                                });
                            }
                        }
                    }

                    if (testResults.length > 0) {
                        let html = '<div style="display: flex; flex-direction: column; gap: 8px;">';
                        testResults.forEach(result => {
                            html += `
                                <div style="display: flex; align-items: center; gap: 10px; padding: 8px; background-color: ${result.passed ? 'rgba(76, 175, 80, 0.1)' : 'rgba(244, 67, 54, 0.1)'}; border-radius: 4px;">
                                    <i class="fas fa-${result.passed ? 'check' : 'times'}" style="color: ${result.passed ? '#4CAF50' : '#F44336'}"></i>
                                    <div>
                                        <div style="font-weight: 500;">${result.name}</div>
                                        <div style="font-size: 12px; color: var(--text-secondary);">${result.message}</div>
                                    </div>
                                </div>
                            `;
                        });
                        html += '</div>';
                        testResultsContainer.innerHTML = html;
                    } else {
                        testResultsContainer.innerHTML = '<div style="text-align: center; padding: 20px; color: var(--text-muted);">Testes executados (sintaxe básica apenas)</div>';
                    }

                } catch (e) {
                    testResultsContainer.innerHTML = `<div style="color: #F44336;">Erro ao executar testes: ${e.message}</div>`;
                }
            }

            saveTests() {
                // Auto-save tests to localStorage
                const tests = this.testsInput.value;
                localStorage.setItem('api_client_tests', tests);
            }

            loadTests() {
                const savedTests = localStorage.getItem('api_client_tests');
                if (savedTests) {
                    this.testsInput.value = savedTests;
                }
            }

            showAlert(message, type = 'info') {
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
        }

        // ============================================
        // COLLECTIONS MANAGER
        // ============================================

        class CollectionsManager {
            constructor() {
                this.collections = JSON.parse(localStorage.getItem('api_client_collections') || '[]');
                this.currentCollectionId = null;
                this.editingCollectionId = null;
                this.editingRequestId = null;

                this.init();
            }

            init() {
                this.cacheElements();
                this.setupEventListeners();
                this.updateCollectionsCount();
                this.renderCollections();
            }

            cacheElements() {
                // Toggle buttons
                this.collectionsToggle = document.getElementById('collections-toggle');
                this.collectionsSidebar = document.getElementById('collections-sidebar');
                this.collectionsCount = document.getElementById('collections-count');
                this.collectionList = document.getElementById('collection-list');

                // Collection modal elements - CORRIGIDO: IDs duplicados
                this.newCollectionBtn = document.getElementById('new-collection-btn');
                this.collectionModal = document.getElementById('collection-modal');
                this.closeModalBtn = document.getElementById('close-modal-btn');
                this.cancelCollectionBtn = document.getElementById('cancel-collection-btn');
                this.modalSaveCollectionBtn = document.getElementById('modal-save-collection-btn'); // Corrigido
                this.collectionNameInput = document.getElementById('collection-name');
                this.collectionDescriptionInput = document.getElementById('collection-description');
                this.collectionColorInput = document.getElementById('collection-color');
                this.collectionIsPublicInput = document.getElementById('collection-is-public');

                // Request modal elements
                this.requestModal = document.getElementById('request-modal');
                this.saveRequestBtn = document.getElementById('save-request-btn');
                this.saveRequestToCollectionBtn = document.getElementById('save-request-to-collection-btn');
                this.cancelRequestBtn = document.getElementById('cancel-request-btn');
                this.closeRequestModalBtn = document.getElementById('close-request-modal-btn');

                // Import/Export elements
                this.importExportModal = document.getElementById('import-export-modal');
                this.importCollectionBtn = document.getElementById('import-collection-btn');
                this.exportCollectionBtn = document.getElementById('export-collection-btn');
                this.closeImportExportBtn = document.getElementById('close-import-export-btn');
                this.cancelImportExportBtn = document.getElementById('cancel-import-export-btn');
                this.generateExportBtn = document.getElementById('generate-export-btn');
                this.executeImportBtn = document.getElementById('execute-import-btn');
                this.exportOutput = document.getElementById('export-output');
                this.importFileInput = document.getElementById('import-file');

                // Topbar save button
                this.topbarSaveCollectionBtn = document.getElementById('topbar-save-collection-btn');
            }

            setupEventListeners() {
                // Toggle sidebar
                this.collectionsToggle.addEventListener('click', () => this.toggleCollectionsSidebar());

                // New collection button
                this.newCollectionBtn.addEventListener('click', () => this.openCollectionModal());

                // Topbar save collection button
                this.topbarSaveCollectionBtn.addEventListener('click', () => {
                    if (this.collections.length === 0) {
                        this.openCollectionModal();
                    } else {
                        this.openRequestModal();
                    }
                });

                // Collection modal
                this.closeModalBtn.addEventListener('click', () => this.closeCollectionModal());
                this.cancelCollectionBtn.addEventListener('click', () => this.closeCollectionModal());
                this.modalSaveCollectionBtn.addEventListener('click', () => this.saveCollection()); // Corrigido

                // Save request button (in main interface)
                this.saveRequestBtn.addEventListener('click', () => {
                    if (this.collections.length === 0) {
                        this.showAlert('Crie uma coleção primeiro!', 'warning');
                        this.openCollectionModal();
                        return;
                    }
                    this.openRequestModal();
                });

                // Request modal
                this.cancelRequestBtn.addEventListener('click', () => this.closeRequestModal());
                this.closeRequestModalBtn.addEventListener('click', () => this.closeRequestModal());
                this.saveRequestToCollectionBtn.addEventListener('click', () => this.saveRequestToCollection());

                // Import/Export
                this.importCollectionBtn.addEventListener('click', () => this.openImportExportModal('import'));
                this.exportCollectionBtn.addEventListener('click', () => this.openImportExportModal('export'));
                this.closeImportExportBtn.addEventListener('click', () => this.closeImportExportModal());
                this.cancelImportExportBtn.addEventListener('click', () => this.closeImportExportModal());
                this.generateExportBtn.addEventListener('click', () => this.generateExport());
                this.executeImportBtn.addEventListener('click', () => this.executeImport());
                this.importFileInput.addEventListener('change', (e) => this.previewImportFile(e));

                // Tabs in import/export modal
                document.querySelectorAll('[data-tab]').forEach(tab => {
                    tab.addEventListener('click', (e) => {
                        const tabId = e.target.getAttribute('data-tab');
                        this.switchImportExportTab(tabId);
                    });
                });
            }

            toggleCollectionsSidebar() {
                this.collectionsSidebar.classList.toggle('active');
                // Close history sidebar if open
                const historySidebar = document.getElementById('history-sidebar');
                if (historySidebar) {
                    historySidebar.classList.remove('active');
                }
                this.renderCollections();
            }

            updateCollectionsCount() {
                this.collectionsCount.textContent = this.collections.length;
            }

            renderCollections() {
                this.collectionList.innerHTML = '';

                if (this.collections.length === 0) {
                    this.collectionList.innerHTML = `
                        <div style="text-align: center; padding: 40px 20px; color: var(--text-muted);">
                            <i class="fas fa-folder-open" style="font-size: 48px; margin-bottom: 16px;"></i>
                            <p style="margin-bottom: 12px;">Nenhuma coleção criada</p>
                            <button class="btn btn-sm btn-primary" id="create-first-collection-btn">
                                <i class="fas fa-plus"></i> Criar Primeira Coleção
                            </button>
                        </div>
                    `;

                    document.getElementById('create-first-collection-btn')?.addEventListener('click', () => this.openCollectionModal());
                    return;
                }

                this.collections.forEach(collection => {
                    const collectionItem = document.createElement('div');
                    collectionItem.className = 'collection-item';
                    if (this.currentCollectionId === collection.id) {
                        collectionItem.classList.add('active');
                    }

                    collectionItem.innerHTML = `
                        <div class="collection-header">
                            <div class="collection-title">
                                <span class="collection-color" style="background-color: ${collection.color || '#FF6C37'}"></span>
                                ${collection.name}
                                <span class="collection-count">${collection.requests?.length || 0}</span>
                            </div>
                            <div>
                                <button class="btn btn-icon btn-sm edit-collection-btn" data-id="${collection.id}" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-icon btn-sm delete-collection-btn" data-id="${collection.id}" title="Excluir">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="request-list">
                            ${collection.requests?.map(request => `
                                <div class="request-item" data-collection-id="${collection.id}" data-request-id="${request.id}">
                                    <span class="request-method method-${request.method?.toLowerCase()}">
                                        ${request.method}
                                    </span>
                                    <span class="request-url" title="${request.url}">
                                        ${request.name || request.url.substring(0, 40)}${request.url.length > 40 ? '...' : ''}
                                    </span>
                                    <div class="request-actions">
                                        <button class="btn btn-icon btn-sm load-request-btn" 
                                                title="Carregar">
                                            <i class="fas fa-arrow-right"></i>
                                        </button>
                                        <button class="btn btn-icon btn-sm delete-request-btn" 
                                                title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            `).join('') || '<div style="padding: 10px; text-align: center; color: var(--text-muted);">Nenhuma requisição</div>'}
                        </div>
                        <div style="padding: 10px; border-top: 1px solid #3A4252;">
                            <button class="btn btn-sm w-full add-request-to-collection-btn" data-id="${collection.id}">
                                <i class="fas fa-plus"></i> Adicionar Requisição
                            </button>
                        </div>
                    `;

                    this.collectionList.appendChild(collectionItem);
                });

                // Add dynamic event listeners
                this.setupDynamicCollectionListeners();
            }

            setupDynamicCollectionListeners() {
                // Expand/collapse collection
                document.querySelectorAll('.collection-header').forEach(header => {
                    header.addEventListener('click', (e) => {
                        if (!e.target.closest('button')) {
                            const collectionItem = e.currentTarget.closest('.collection-item');
                            const isActive = collectionItem.classList.contains('active');

                            // Close all collections
                            document.querySelectorAll('.collection-item').forEach(item => {
                                item.classList.remove('active');
                            });

                            // Open this one if it wasn't open
                            if (!isActive) {
                                collectionItem.classList.add('active');
                                this.currentCollectionId = collectionItem.querySelector('.edit-collection-btn')?.getAttribute('data-id') || null;
                            } else {
                                this.currentCollectionId = null;
                            }
                        }
                    });
                });

                // Edit collection button
                document.querySelectorAll('.edit-collection-btn').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const collectionId = e.currentTarget.getAttribute('data-id');
                        this.openCollectionModal(collectionId);
                    });
                });

                // Delete collection button
                document.querySelectorAll('.delete-collection-btn').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const collectionId = e.currentTarget.getAttribute('data-id');
                        this.deleteCollection(collectionId);
                    });
                });

                // Add request to collection button
                document.querySelectorAll('.add-request-to-collection-btn').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const collectionId = e.currentTarget.getAttribute('data-id');
                        this.openRequestModal(collectionId);
                    });
                });

                // Load request button
                document.querySelectorAll('.load-request-btn').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const requestItem = e.currentTarget.closest('.request-item');
                        const collectionId = requestItem.getAttribute('data-collection-id');
                        const requestId = requestItem.getAttribute('data-request-id');
                        this.loadRequestFromCollection(collectionId, requestId);
                    });
                });

                // Delete request button
                document.querySelectorAll('.delete-request-btn').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const requestItem = e.currentTarget.closest('.request-item');
                        const collectionId = requestItem.getAttribute('data-collection-id');
                        const requestId = requestItem.getAttribute('data-request-id');
                        this.deleteRequestFromCollection(collectionId, requestId);
                    });
                });
            }

            openCollectionModal(collectionId = null) {
                this.editingCollectionId = collectionId;

                if (collectionId) {
                    // Edit mode
                    const collection = this.collections.find(c => c.id === collectionId);
                    if (collection) {
                        document.getElementById('collection-modal-title').textContent = 'Editar Coleção';
                        this.collectionNameInput.value = collection.name;
                        this.collectionDescriptionInput.value = collection.description || '';
                        this.collectionColorInput.value = collection.color || '#FF6C37';
                        this.collectionIsPublicInput.checked = collection.isPublic || false;
                    }
                } else {
                    // Create mode
                    document.getElementById('collection-modal-title').textContent = 'Nova Coleção';
                    this.collectionNameInput.value = '';
                    this.collectionDescriptionInput.value = '';
                    this.collectionColorInput.value = '#FF6C37';
                    this.collectionIsPublicInput.checked = false;
                }

                this.collectionModal.classList.add('active');
            }

            closeCollectionModal() {
                this.collectionModal.classList.remove('active');
                this.editingCollectionId = null;
                this.resetCollectionModal();
            }

            resetCollectionModal() {
                this.collectionNameInput.value = '';
                this.collectionDescriptionInput.value = '';
                this.collectionColorInput.value = '#FF6C37';
                this.collectionIsPublicInput.checked = false;
            }

            saveCollection() {
                const name = this.collectionNameInput.value.trim();
                if (!name) {
                    this.showAlert('Por favor, informe um nome para a coleção', 'warning');
                    return;
                }

                if (this.editingCollectionId) {
                    // Update existing collection
                    const index = this.collections.findIndex(c => c.id === this.editingCollectionId);
                    if (index !== -1) {
                        this.collections[index] = {
                            ...this.collections[index],
                            name,
                            description: this.collectionDescriptionInput.value.trim(),
                            color: this.collectionColorInput.value,
                            isPublic: this.collectionIsPublicInput.checked,
                            updatedAt: new Date().toISOString()
                        };
                    }
                } else {
                    // Create new collection
                    const newCollection = {
                        id: this.generateId(),
                        name,
                        description: this.collectionDescriptionInput.value.trim(),
                        color: this.collectionColorInput.value,
                        isPublic: this.collectionIsPublicInput.checked,
                        requests: [],
                        createdAt: new Date().toISOString(),
                        updatedAt: new Date().toISOString()
                    };

                    this.collections.unshift(newCollection);
                }

                this.saveCollectionsToStorage();
                this.renderCollections();
                this.closeCollectionModal();
                this.showAlert(`Coleção "${name}" salva com sucesso!`, 'success');
            }

            deleteCollection(collectionId) {
                if (!confirm('Tem certeza que deseja excluir esta coleção? Todas as requisições dentro dela serão perdidas.')) {
                    return;
                }

                this.collections = this.collections.filter(c => c.id !== collectionId);
                this.saveCollectionsToStorage();
                this.renderCollections();
                this.showAlert('Coleção excluída com sucesso!', 'info');
            }

            openRequestModal(collectionId = null) {
                if (collectionId) {
                    this.editingCollectionId = collectionId;
                } else if (this.currentCollectionId) {
                    this.editingCollectionId = this.currentCollectionId;
                } else if (this.collections.length > 0) {
                    this.editingCollectionId = this.collections[0].id;
                }

                if (!this.editingCollectionId) {
                    this.showAlert('Nenhuma coleção selecionada', 'warning');
                    return;
                }

                const collection = this.collections.find(c => c.id === this.editingCollectionId);
                if (!collection) return;

                // Fill folder dropdown
                const folderSelect = document.getElementById('request-folder');
                folderSelect.innerHTML = '<option value="">Sem pasta</option>';

                // Collect unique folders from existing requests
                const folders = new Set();
                collection.requests.forEach(req => {
                    if (req.folder) folders.add(req.folder);
                });

                folders.forEach(folder => {
                    const option = document.createElement('option');
                    option.value = folder;
                    option.textContent = folder;
                    folderSelect.appendChild(option);
                });

                // Suggest name based on current URL
                const urlInput = document.getElementById('url-input');
                const currentUrl = urlInput ? urlInput.value : '';
                let urlName = 'Nova Requisição';

                try {
                    if (currentUrl) {
                        const urlObj = new URL(currentUrl);
                        urlName = urlObj.pathname.split('/').pop() || 'Nova Requisição';
                    }
                } catch (e) {
                    // Invalid URL, use default name
                }

                document.getElementById('request-name').value = urlName;

                this.requestModal.classList.add('active');
            }

            closeRequestModal() {
                this.requestModal.classList.remove('active');
                this.editingCollectionId = null;
                this.editingRequestId = null;
            }

            saveRequestToCollection() {
                const name = document.getElementById('request-name').value.trim();
                if (!name) {
                    this.showAlert('Por favor, informe um nome para a requisição', 'warning');
                    return;
                }

                const collection = this.collections.find(c => c.id === this.editingCollectionId);
                if (!collection) return;

                // Get current request data from the interface
                const headers = [];
                const headersTable = document.getElementById('headers-table');
                headersTable.querySelectorAll('tr').forEach(row => {
                    const checkbox = row.querySelector('input[type="checkbox"]');
                    const keyInput = row.querySelector('td:nth-child(2) input');
                    const valueInput = row.querySelector('td:nth-child(3) input');

                    if (checkbox?.checked && keyInput?.value && valueInput?.value) {
                        headers.push({
                            key: keyInput.value,
                            value: valueInput.value,
                            enabled: true
                        });
                    }
                });

                // Get query parameters
                const params = [];
                const paramsTable = document.getElementById('params-table');
                paramsTable.querySelectorAll('tr').forEach(row => {
                    const checkbox = row.querySelector('input[type="checkbox"]');
                    const keyInput = row.querySelector('td:nth-child(2) input');
                    const valueInput = row.querySelector('td:nth-child(3) input');

                    if (checkbox?.checked && keyInput?.value && valueInput) {
                        params.push({
                            key: keyInput.value,
                            value: valueInput.value,
                            enabled: true
                        });
                    }
                });

                const bodyInput = document.getElementById('body-input');
                const urlInput = document.getElementById('url-input');
                const methodIndicator = document.getElementById('method-indicator');

                const newRequest = {
                    id: this.generateId(),
                    name,
                    description: document.getElementById('request-description').value.trim(),
                    method: methodIndicator ? methodIndicator.textContent : 'GET',
                    url: urlInput ? urlInput.value.trim() : '',
                    headers: document.getElementById('request-save-headers').checked ? headers : [],
                    params: params,
                    body: document.getElementById('request-save-body').checked ? (bodyInput ? bodyInput.value : '') : '',
                    tests: document.getElementById('request-save-tests').checked ? (document.getElementById('tests-input')?.value || '') : '',
                    folder: document.getElementById('request-folder').value,
                    createdAt: new Date().toISOString(),
                    updatedAt: new Date().toISOString()
                };

                // Add to collection
                collection.requests.unshift(newRequest);
                collection.updatedAt = new Date().toISOString();

                this.saveCollectionsToStorage();
                this.renderCollections();
                this.closeRequestModal();
                this.showAlert(`Requisição "${name}" salva na coleção!`, 'success');
            }

            loadRequestFromCollection(collectionId, requestId) {
                const collection = this.collections.find(c => c.id === collectionId);
                if (!collection) return;

                const request = collection.requests.find(r => r.id === requestId);
                if (!request) return;

                // Update interface with the request
                const methodIndicator = document.getElementById('method-indicator');
                const urlInput = document.getElementById('url-input');
                const bodyInput = document.getElementById('body-input');
                const testsInput = document.getElementById('tests-input');
                const headersTable = document.getElementById('headers-table');
                const paramsTable = document.getElementById('params-table');

                if (methodIndicator && window.apiClient) {
                    window.apiClient.setMethod(request.method);
                }

                if (urlInput) {
                    urlInput.value = request.url;
                }

                // Clear current headers
                if (headersTable) {
                    headersTable.innerHTML = '';

                    // Add headers from saved request
                    if (request.headers && request.headers.length > 0) {
                        request.headers.forEach(header => {
                            if (header.enabled) {
                                const row = document.createElement('tr');
                                row.className = 'header-row';
                                row.innerHTML = `
                                    <td>
                                        <input type="checkbox" checked>
                                    </td>
                                    <td>
                                        <input type="text" value="${header.key}" placeholder="Key">
                                    </td>
                                    <td>
                                        <input type="text" value="${header.value}" placeholder="Value">
                                    </td>
                                    <td>
                                        <input type="text" placeholder="Description">
                                    </td>
                                    <td>
                                        <button class="btn btn-icon btn-sm delete-header-btn">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                `;

                                // Add event listener to delete button
                                const deleteBtn = row.querySelector('.delete-header-btn');
                                deleteBtn.addEventListener('click', (e) => {
                                    e.preventDefault();
                                    row.remove();
                                    if (window.apiClient) {
                                        window.apiClient.updateHeadersCount();
                                    }
                                });

                                headersTable.appendChild(row);
                            }
                        });
                    } else {
                        // Add default row if no headers
                        if (window.apiClient) {
                            window.apiClient.addHeaderRow();
                        }
                    }
                }

                // Update parameters
                if (paramsTable) {
                    paramsTable.innerHTML = '';
                    if (request.params && request.params.length > 0) {
                        request.params.forEach(param => {
                            if (param.enabled) {
                                if (window.apiClient) {
                                    window.apiClient.addParamRow();
                                }
                                const lastRow = paramsTable.lastElementChild;
                                const keyInput = lastRow.querySelector('td:nth-child(2) input');
                                const valueInput = lastRow.querySelector('td:nth-child(3) input');
                                if (keyInput) keyInput.value = param.key;
                                if (valueInput) valueInput.value = param.value;
                            }
                        });
                    } else {
                        if (window.apiClient) {
                            window.apiClient.addParamRow();
                        }
                    }
                }

                // Update body
                if (bodyInput) {
                    bodyInput.value = request.body || '';
                    if (window.apiClient && window.apiClient.isJsonViewActive) {
                        window.apiClient.updateJsonEditor();
                    }
                }

                // Update tests
                if (testsInput && request.tests) {
                    testsInput.value = request.tests;
                }

                // Update counts
                if (window.apiClient) {
                    window.apiClient.updateCounts();
                }

                // Focus on body tab if there's content
                if (request.body) {
                    const bodyTab = document.querySelector('[data-tab="body"]');
                    if (bodyTab) {
                        bodyTab.click();
                    }
                }

                this.showAlert(`Requisição "${request.name}" carregada!`, 'success');

                // Update active collection
                this.currentCollectionId = collectionId;
                this.renderCollections();
            }

            deleteRequestFromCollection(collectionId, requestId) {
                if (!confirm('Tem certeza que deseja excluir esta requisição da coleção?')) {
                    return;
                }

                const collection = this.collections.find(c => c.id === collectionId);
                if (!collection) return;

                collection.requests = collection.requests.filter(r => r.id !== requestId);
                collection.updatedAt = new Date().toISOString();

                this.saveCollectionsToStorage();
                this.renderCollections();
                this.showAlert('Requisição excluída da coleção!', 'info');
            }

            // Import/Export methods
            openImportExportModal(tab = 'export') {
                this.switchImportExportTab(tab);

                if (tab === 'export') {
                    this.renderExportCollectionsList();
                } else {
                    this.importFileInput.value = '';
                    document.getElementById('import-preview').classList.add('hidden');
                }

                this.importExportModal.classList.add('active');
            }

            closeImportExportModal() {
                this.importExportModal.classList.remove('active');
            }

            switchImportExportTab(tabId) {
                // Update tabs
                document.querySelectorAll('.sidebar-tab').forEach(tab => {
                    tab.classList.toggle('active', tab.getAttribute('data-tab') === tabId);
                });

                // Update content
                document.querySelectorAll('.sidebar-tab-content').forEach(content => {
                    content.classList.toggle('active', content.id === `${tabId}-tab`);
                });

                // Update buttons
                if (tabId === 'export') {
                    this.generateExportBtn.style.display = 'block';
                    this.executeImportBtn.style.display = 'none';
                } else {
                    this.generateExportBtn.style.display = 'none';
                    this.executeImportBtn.style.display = 'block';
                }
            }

            renderExportCollectionsList() {
                const container = document.getElementById('export-collections-list');
                container.innerHTML = '';

                if (this.collections.length === 0) {
                    container.innerHTML = '<div style="text-align: center; padding: 20px; color: var(--text-muted);">Nenhuma coleção para exportar</div>';
                    return;
                }

                this.collections.forEach(collection => {
                    const div = document.createElement('div');
                    div.style.cssText = 'display: flex; align-items: center; padding: 8px; border-bottom: 1px solid #3A4252;';
                    div.innerHTML = `
                        <input type="checkbox" id="export-${collection.id}" checked style="margin-right: 10px;">
                        <label for="export-${collection.id}" style="flex: 1; cursor: pointer;">
                            <span class="collection-color" style="background-color: ${collection.color}; margin-right: 8px;"></span>
                            ${collection.name}
                            <span style="font-size: 11px; color: var(--text-muted); margin-left: 8px;">(${collection.requests?.length || 0} requisições)</span>
                        </label>
                    `;
                    container.appendChild(div);
                });
            }

            generateExport() {
                const format = document.getElementById('export-format').value;
                const selectedCollections = [];

                // Collect selected collections
                this.collections.forEach(collection => {
                    const checkbox = document.getElementById(`export-${collection.id}`);
                    if (checkbox && checkbox.checked) {
                        selectedCollections.push(collection);
                    }
                });

                if (selectedCollections.length === 0) {
                    this.showAlert('Selecione pelo menos uma coleção para exportar', 'warning');
                    return;
                }

                let exportData;

                switch (format) {
                    case 'json':
                        exportData = JSON.stringify({
                            version: '1.0',
                            exportedAt: new Date().toISOString(),
                            collections: selectedCollections
                        }, null, 2);
                        break;

                    case 'postman':
                        exportData = this.convertToPostmanFormat(selectedCollections);
                        break;

                    case 'curl':
                        exportData = this.convertToCurlCommands(selectedCollections);
                        break;
                }

                this.exportOutput.value = exportData;

                // Enable copy button
                this.exportOutput.select();
                document.execCommand('copy');
                this.showAlert('Exportação gerada e copiada para a área de transferência!', 'success');
            }

            previewImportFile(event) {
                const file = event.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = (e) => {
                    try {
                        const content = e.target.result;
                        document.getElementById('import-preview-text').value = content.substring(0, 500) + (content.length > 500 ? '...' : '');
                        document.getElementById('import-preview').classList.remove('hidden');
                    } catch (error) {
                        this.showAlert('Erro ao ler arquivo: ' + error.message, 'danger');
                    }
                };
                reader.readAsText(file);
            }

            executeImport() {
                const file = this.importFileInput.files[0];
                if (!file) {
                    this.showAlert('Selecione um arquivo para importar', 'warning');
                    return;
                }

                const action = document.getElementById('import-action').value;
                const reader = new FileReader();

                reader.onload = (e) => {
                    try {
                        const data = JSON.parse(e.target.result);
                        let importedCollections = [];

                        // Check format
                        if (data.collections) {
                            // Native format
                            importedCollections = data.collections;
                        } else if (data.info && data.item) {
                            // Postman format
                            importedCollections = this.convertFromPostmanFormat(data);
                        } else {
                            throw new Error('Formato de arquivo não suportado');
                        }

                        // Apply import action
                        switch (action) {
                            case 'merge':
                                importedCollections.forEach(collection => {
                                    // Avoid duplicates by name
                                    const exists = this.collections.find(c => c.name === collection.name);
                                    if (!exists) {
                                        collection.id = this.generateId();
                                        // Update request IDs
                                        if (collection.requests) {
                                            collection.requests.forEach(req => {
                                                req.id = this.generateId();
                                            });
                                        }
                                        this.collections.push(collection);
                                    }
                                });
                                break;

                            case 'replace':
                                this.collections = importedCollections.map(collection => ({
                                    ...collection,
                                    id: this.generateId(),
                                    requests: collection.requests?.map(req => ({
                                        ...req,
                                        id: this.generateId()
                                    })) || []
                                }));
                                break;

                            case 'new':
                                importedCollections.forEach(collection => {
                                    collection.id = this.generateId();
                                    collection.name = collection.name + ' (Importado)';
                                    if (collection.requests) {
                                        collection.requests.forEach(req => {
                                            req.id = this.generateId();
                                        });
                                    }
                                    this.collections.unshift(collection);
                                });
                                break;
                        }

                        this.saveCollectionsToStorage();
                        this.renderCollections();
                        this.closeImportExportModal();
                        this.showAlert(`Importação concluída! ${importedCollections.length} coleções importadas.`, 'success');

                    } catch (error) {
                        this.showAlert('Erro ao importar coleções: ' + error.message, 'danger');
                    }
                };

                reader.readAsText(file);
            }

            // Utility methods
            generateId() {
                return Date.now().toString(36) + Math.random().toString(36).substr(2);
            }

            saveCollectionsToStorage() {
                localStorage.setItem('api_client_collections', JSON.stringify(this.collections));
                this.updateCollectionsCount();
            }

            convertToPostmanFormat(collections) {
                const postmanCollection = {
                    info: {
                        name: 'API Client Collections',
                        schema: 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json'
                    },
                    item: []
                };

                collections.forEach(collection => {
                    const folder = {
                        name: collection.name,
                        item: []
                    };

                    collection.requests?.forEach(request => {
                        const postmanRequest = {
                            name: request.name,
                            request: {
                                method: request.method,
                                header: request.headers?.map(h => ({
                                    key: h.key,
                                    value: h.value,
                                    type: 'text'
                                })) || [],
                                url: {
                                    raw: request.url,
                                    host: this.getHostFromUrl(request.url),
                                    path: this.getPathFromUrl(request.url),
                                    query: request.params?.map(p => ({
                                        key: p.key,
                                        value: p.value
                                    })) || []
                                }
                            }
                        };

                        if (request.body) {
                            postmanRequest.request.body = {
                                mode: 'raw',
                                raw: request.body,
                                options: {
                                    raw: {
                                        language: 'json'
                                    }
                                }
                            };
                        }

                        folder.item.push(postmanRequest);
                    });

                    postmanCollection.item.push(folder);
                });

                return JSON.stringify(postmanCollection, null, 2);
            }

            convertFromPostmanFormat(postmanData) {
                const collections = [];

                postmanData.item.forEach(item => {
                    const collection = {
                        id: this.generateId(),
                        name: item.name,
                        requests: []
                    };

                    // Process requests
                    if (item.item) {
                        // It's a folder with multiple requests
                        item.item.forEach(requestItem => {
                            if (requestItem.request) {
                                collection.requests.push(this.convertPostmanRequest(requestItem));
                            }
                        });
                    } else if (item.request) {
                        // It's an individual request
                        collection.requests.push(this.convertPostmanRequest(item));
                    }

                    collections.push(collection);
                });

                return collections;
            }

            convertPostmanRequest(postmanRequest) {
                return {
                    id: this.generateId(),
                    name: postmanRequest.name,
                    method: postmanRequest.request.method,
                    url: postmanRequest.request.url?.raw || '',
                    headers: postmanRequest.request.header?.map(h => ({
                        key: h.key,
                        value: h.value,
                        enabled: true
                    })) || [],
                    body: postmanRequest.request.body?.raw || '',
                    createdAt: new Date().toISOString(),
                    updatedAt: new Date().toISOString()
                };
            }

            convertToCurlCommands(collections) {
                let curlCommands = '';

                collections.forEach(collection => {
                    curlCommands += `# Coleção: ${collection.name}\n\n`;

                    collection.requests?.forEach(request => {
                        curlCommands += `# ${request.name}\n`;

                        let curl = `curl -X ${request.method} "${request.url}"`;

                        // Add headers
                        request.headers?.forEach(header => {
                            if (header.enabled) {
                                curl += ` \\\n  -H "${header.key}: ${header.value}"`;
                            }
                        });

                        // Add body
                        if (request.body) {
                            curl += ` \\\n  -d '${request.body}'`;
                        }

                        curlCommands += curl + '\n\n';
                    });

                    curlCommands += '\n';
                });

                return curlCommands;
            }

            getHostFromUrl(url) {
                try {
                    const urlObj = new URL(url);
                    return [urlObj.hostname];
                } catch (e) {
                    return [''];
                }
            }

            getPathFromUrl(url) {
                try {
                    const urlObj = new URL(url);
                    return urlObj.pathname.split('/').filter(p => p);
                } catch (e) {
                    return [];
                }
            }

            showAlert(message, type = 'info') {
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
        }

        // ============================================
        // HISTORY MANAGER
        // ============================================

        class HistoryManager {
            constructor() {
                this.requestHistory = JSON.parse(localStorage.getItem('api_client_history') || '[]');
                this.init();
            }

            init() {
                this.cacheElements();
                this.setupEventListeners();
                this.renderHistory();
            }

            cacheElements() {
                this.historyToggle = document.getElementById('history-toggle');
                this.historySidebar = document.getElementById('history-sidebar');
                this.historyList = document.getElementById('history-list');
                this.clearHistoryBtn = document.getElementById('clear-history-btn');
            }

            setupEventListeners() {
                this.historyToggle.addEventListener('click', () => this.toggleHistorySidebar());
                this.clearHistoryBtn.addEventListener('click', () => this.clearHistory());
            }

            toggleHistorySidebar() {
                this.historySidebar.classList.toggle('active');
                // Close collections sidebar if open
                const collectionsSidebar = document.getElementById('collections-sidebar');
                if (collectionsSidebar) {
                    collectionsSidebar.classList.remove('active');
                }
                this.renderHistory();
            }

            renderHistory() {
                this.historyList.innerHTML = '';

                if (this.requestHistory.length === 0) {
                    this.historyList.innerHTML = '<div style="text-align: center; color: var(--text-muted); padding: 20px;">Nenhum histórico ainda</div>';
                    return;
                }

                this.requestHistory.forEach((item, index) => {
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
                        this.loadRequestFromHistory(index);
                        if (window.innerWidth < 1200) {
                            this.historySidebar.classList.remove('active');
                        }
                    });

                    this.historyList.appendChild(historyItem);
                });
            }

            loadRequestFromHistory(index) {
                const request = this.requestHistory[index];

                // Load method
                if (window.apiClient) {
                    window.apiClient.setMethod(request.method);
                }

                // Load URL
                const urlInput = document.getElementById('url-input');
                if (urlInput) {
                    urlInput.value = request.url;
                }

                // Load headers
                const headersTable = document.getElementById('headers-table');
                if (headersTable && request.headers) {
                    headersTable.innerHTML = '';

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
                                <button class="btn btn-icon btn-sm delete-header-btn">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        `;

                        // Add event listener to delete button
                        const deleteBtn = row.querySelector('.delete-header-btn');
                        deleteBtn.addEventListener('click', (e) => {
                            e.preventDefault();
                            row.remove();
                            if (window.apiClient) {
                                window.apiClient.updateHeadersCount();
                            }
                        });

                        headersTable.appendChild(row);
                    });

                    // Update counts
                    if (window.apiClient) {
                        window.apiClient.updateHeadersCount();
                    }
                }

                // Load body
                const bodyInput = document.getElementById('body-input');
                if (bodyInput && request.body) {
                    bodyInput.value = request.body;
                    if (window.apiClient && window.apiClient.isJsonViewActive) {
                        window.apiClient.updateJsonEditor();
                    }
                }

                this.showAlert('Requisição carregada do histórico', 'info');
            }

            clearHistory() {
                if (confirm('Limpar todo o histórico de requisições?')) {
                    this.requestHistory = [];
                    localStorage.removeItem('api_client_history');
                    this.renderHistory();
                    this.showAlert('Histórico limpo', 'info');
                }
            }

            addToHistory(request) {
                this.requestHistory.unshift(request);

                if (this.requestHistory.length > 50) {
                    this.requestHistory = this.requestHistory.slice(0, 50);
                }

                localStorage.setItem('api_client_history', JSON.stringify(this.requestHistory));
                this.renderHistory();
            }

            showAlert(message, type = 'info') {
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
        }

        // ============================================
        // INITIALIZE APPLICATION
        // ============================================

        document.addEventListener('DOMContentLoaded', () => {
            // Initialize all managers
            window.apiClient = new APIClient();
            window.collectionsManager = new CollectionsManager();
            window.historyManager = new HistoryManager();

            // Load saved tests
            window.apiClient.loadTests();

            // Função global para toggle JSON
            window.toggleJson = function(id) {
                const element = document.getElementById(id);
                if (element) {
                    element.classList.toggle('hidden');
                    const toggle = element.previousElementSibling.querySelector('.json-toggle');
                    if (toggle) {
                        toggle.classList.toggle('collapsed');
                    }
                }
            };
            // this.setMethod(this.currentMethod);
            console.log('API Client inicializado com sucesso!');
        });
    </script>
</body>

</html>