<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Gestão</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 480px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
            color: white;
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .logo i {
            font-size: 2.5rem;
            color: white;
        }

        .logo h1 {
            font-size: 2rem;
            font-weight: 600;
        }

        .subtitle {
            font-size: 1rem;
            opacity: 0.9;
            margin-top: 5px;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .login-form {
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-group label i {
            color: #667eea;
            width: 20px;
        }

        .form-group input {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .password-input {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            font-size: 1.1rem;
            padding: 5px;
        }

        .toggle-password:hover {
            color: #667eea;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            cursor: pointer;
            font-size: 0.9rem;
            color: #666;
        }

        .checkbox-container input {
            display: none;
        }

        .checkmark {
            width: 20px;
            height: 20px;
            border: 2px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
            position: relative;
            transition: all 0.3s ease;
        }

        .checkbox-container input:checked+.checkmark {
            background: #667eea;
            border-color: #667eea;
        }

        .checkbox-container input:checked+.checkmark::after {
            content: '✓';
            position: absolute;
            color: white;
            font-size: 14px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .forgot-password {
            color: #667eea;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 25px 0;
            color: #999;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e1e5e9;
        }

        .divider span {
            padding: 0 15px;
            font-size: 0.9rem;
        }

        .btn-demo {
            width: 100%;
            padding: 16px;
            background: #f0f2f5;
            color: #333;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-demo:hover {
            background: #e4e6e9;
            border-color: #667eea;
        }

        .login-footer {
            text-align: center;
            padding-top: 25px;
            border-top: 1px solid #e1e5e9;
        }

        .login-footer p {
            color: #666;
            margin-bottom: 10px;
            font-size: 0.9rem;
        }

        .login-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        .copyright {
            font-size: 0.8rem;
            color: #999;
            margin-top: 15px;
        }

        /* Toast */
        .toast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            z-index: 1000;
            animation: slideIn 0.3s ease;
            max-width: 400px;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .toast-content {
            display: flex;
            align-items: center;
            padding: 20px;
            gap: 15px;
        }

        .toast-icon {
            font-size: 1.5rem;
        }

        .toast-icon.success {
            color: #4CAF50;
        }

        .toast-icon.error {
            color: #f44336;
        }

        .toast-message {
            font-size: 0.95rem;
            color: #333;
        }

        .toast-progress {
            height: 3px;
            background: #4CAF50;
            width: 100%;
            animation: progress 3s linear forwards;
        }

        @keyframes progress {
            from {
                width: 100%;
            }

            to {
                width: 0%;
            }
        }

        /* Modal */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1001;
            padding: 20px;
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            width: 100%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            padding: 25px 30px;
            border-bottom: 1px solid #e1e5e9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #333;
            font-size: 1.5rem;
        }

        .modal-header i {
            color: #4CAF50;
            font-size: 1.8rem;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 2rem;
            color: #666;
            cursor: pointer;
            line-height: 1;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-modal:hover {
            color: #333;
        }

        .modal-body {
            padding: 30px;
        }

        .user-info {
            text-align: center;
            margin-bottom: 30px;
        }

        .user-avatar {
            font-size: 4rem;
            color: #667eea;
            margin-bottom: 15px;
        }

        .user-info h3 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 5px;
        }

        .user-info p {
            color: #666;
            font-size: 1rem;
        }

        .token-info {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .token-info h4 {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #333;
            margin-bottom: 20px;
            font-size: 1.2rem;
        }

        .token-info i {
            color: #667eea;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e1e5e9;
        }

        .info-label {
            font-weight: 500;
            color: #666;
        }

        .info-value {
            font-weight: 600;
            color: #333;
            font-family: monospace;
        }

        .response-data h4 {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #333;
            margin-bottom: 15px;
            font-size: 1.2rem;
        }

        .response-data i {
            color: #667eea;
        }

        .response-container {
            background: #1e1e1e;
            border-radius: 8px;
            padding: 20px;
            overflow-x: auto;
            max-height: 300px;
            overflow-y: auto;
        }

        .response-container pre {
            color: #d4d4d4;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            line-height: 1.5;
            margin: 0;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .modal-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .modal-actions button {
            flex: 1;
            min-width: 150px;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: none;
        }

        .btn-copy {
            background: #2196F3;
            color: white;
        }

        .btn-copy:hover {
            background: #1976D2;
        }

        .btn-clear {
            background: #f44336;
            color: white;
        }

        .btn-clear:hover {
            background: #d32f2f;
        }

        .btn-dashboard {
            background: #4CAF50;
            color: white;
        }

        .btn-dashboard:hover {
            background: #388E3C;
        }

        /* Utility Classes */
        .hidden {
            display: none !important;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .login-card {
                padding: 30px 25px;
            }

            .modal-content {
                max-height: 95vh;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .modal-actions {
                flex-direction: column;
            }

            .modal-actions button {
                min-width: 100%;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }

            .logo {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }

            .logo h1 {
                font-size: 1.5rem;
            }

            .form-options {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <div class="logo">
                <i class="fas fa-building"></i>
                <h1>Sistema de Gestão</h1>
            </div>
            <p class="subtitle">Faça login para acessar o sistema</p>
        </div>

        <div class="login-card">
            <form id="loginForm" class="login-form">
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> E-mail
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="seu@email.com"
                        required
                        value="">
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Senha
                    </label>
                    <div class="password-input">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Sua senha"
                            required
                            value="">
                        <button type="button" class="toggle-password" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="device_name">
                        <i class="fas fa-laptop"></i> Dispositivo
                    </label>
                    <input
                        type="text"
                        id="device_name"
                        name="device_name"
                        placeholder="Nome do dispositivo"
                        required
                        value="web-client">
                </div>

                <div class="form-options">
                    <label class="checkbox-container">
                        <input type="checkbox" id="rememberMe">
                        <span class="checkmark"></span>
                        Lembrar-me
                    </label>
                    <a href="#" class="forgot-password">Esqueci minha senha</a>
                </div>

                <button type="submit" class="btn-login" id="loginBtn">
                    <span id="btnText">Entrar</span>
                    <div class="spinner hidden" id="spinner"></div>
                </button>

                <div class="divider">
                    <span>OU</span>
                </div>

                <button type="button" class="btn-demo" id="demoBtn">
                    <i class="fas fa-rocket"></i> Acessar com Demo
                </button>
            </form>

            <div class="login-footer">
                <p>Não tem uma conta? <a href="#" id="registerLink">Solicitar acesso</a></p>
                <p class="copyright">© 2024 Sistema de Gestão. Todos os direitos reservados.</p>
            </div>
        </div>

        <!-- Toast de notificação -->
        <div id="toast" class="toast hidden">
            <div class="toast-content">
                <i class="fas fa-check-circle toast-icon success"></i>
                <i class="fas fa-exclamation-circle toast-icon error hidden"></i>
                <div class="toast-message"></div>
            </div>
            <div class="toast-progress"></div>
        </div>
    </div>

    <!-- Modal de sucesso -->
    <div id="successModal" class="modal hidden">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-check-circle"></i> Login realizado com sucesso!</h2>
                <button class="close-modal" id="closeModal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <h3 id="userName">Carregando...</h3>
                    <p id="userEmail">Carregando...</p>
                </div>

                <div class="token-info">
                    <h4><i class="fas fa-key"></i> Informações do Token</h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Token Type:</span>
                            <span id="tokenType" class="info-value">Bearer</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Expira em:</span>
                            <span id="tokenExpires" class="info-value">--:--:--</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Armazenado em:</span>
                            <span id="storageType" class="info-value">LocalStorage</span>
                        </div>
                    </div>
                </div>

                <div class="response-data">
                    <h4><i class="fas fa-database"></i> Dados da Resposta</h4>
                    <div class="response-container">
                        <pre id="responseJson"></pre>
                    </div>
                </div>

                <div class="modal-actions">
                    <button class="btn-copy" id="copyTokenBtn">
                        <i class="fas fa-copy"></i> Copiar Token
                    </button>
                    <button class="btn-clear" id="clearTokenBtn">
                        <i class="fas fa-trash"></i> Limpar Token
                    </button>
                    <button class="btn-dashboard" id="goToDashboardBtn">
                        <i class="fas fa-tachometer-alt"></i> Ir para Dashboard
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        class LoginSystem {
            constructor() {
                this.API_URL = 'http://127.0.0.1:8000/api/v1/auth/login';
                this.tokenKey = 'auth_token';
                this.userKey = 'user_data';
                this.tokenExpiryKey = 'token_expiry';

                this.initElements();
                this.initEventListeners();
                this.checkStoredToken();
                this.initDemoData();
            }

            initElements() {
                // Form elements
                this.loginForm = document.getElementById('loginForm');
                this.emailInput = document.getElementById('email');
                this.passwordInput = document.getElementById('password');
                this.deviceInput = document.getElementById('device_name');
                this.togglePasswordBtn = document.getElementById('togglePassword');
                this.loginBtn = document.getElementById('loginBtn');
                this.btnText = document.getElementById('btnText');
                this.spinner = document.getElementById('spinner');

                // Demo button
                this.demoBtn = document.getElementById('demoBtn');

                // Toast
                this.toast = document.getElementById('toast');
                this.toastMessage = document.querySelector('.toast-message');
                this.toastIcons = document.querySelectorAll('.toast-icon');

                // Modal
                this.successModal = document.getElementById('successModal');
                this.closeModalBtn = document.getElementById('closeModal');
                this.userName = document.getElementById('userName');
                this.userEmail = document.getElementById('userEmail');
                this.tokenType = document.getElementById('tokenType');
                this.tokenExpires = document.getElementById('tokenExpires');
                this.storageType = document.getElementById('storageType');
                this.responseJson = document.getElementById('responseJson');

                // Modal buttons
                this.copyTokenBtn = document.getElementById('copyTokenBtn');
                this.clearTokenBtn = document.getElementById('clearTokenBtn');
                this.goToDashboardBtn = document.getElementById('goToDashboardBtn');

                // Links
                this.registerLink = document.getElementById('registerLink');
                this.forgotPasswordLink = document.querySelector('.forgot-password');
            }

            initEventListeners() {
                // Form submission
                this.loginForm.addEventListener('submit', (e) => this.handleLogin(e));

                // Toggle password visibility
                this.togglePasswordBtn.addEventListener('click', () => this.togglePasswordVisibility());

                // Demo login
                this.demoBtn.addEventListener('click', () => this.useDemoCredentials());

                // Modal controls
                this.closeModalBtn.addEventListener('click', () => this.closeModal());
                this.successModal.addEventListener('click', (e) => {
                    if (e.target === this.successModal) this.closeModal();
                });

                // Modal buttons
                this.copyTokenBtn.addEventListener('click', () => this.copyTokenToClipboard());
                this.clearTokenBtn.addEventListener('click', () => this.clearStoredToken());
                this.goToDashboardBtn.addEventListener('click', () => this.goToDashboard());

                // Links
                this.registerLink.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.showToast('Funcionalidade de registro em desenvolvimento!', 'info');
                });

                this.forgotPasswordLink.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.showToast('Funcionalidade de recuperação de senha em desenvolvimento!', 'info');
                });

                // Close toast on click
                this.toast.addEventListener('click', () => this.hideToast());
            }

            initDemoData() {
                // Adiciona dados de demonstração para facilitar os testes
                const demoData = {
                    email: 'admin@ash.elf.eng.br',
                    password: '@dmin#2026',
                    device_name: 'postman'
                };

                // Preenche os campos com dados demo
                this.emailInput.value = demoData.email;
                this.passwordInput.value = demoData.password;
                this.deviceInput.value = demoData.device_name;
            }

            togglePasswordVisibility() {
                const type = this.passwordInput.type === 'password' ? 'text' : 'password';
                this.passwordInput.type = type;

                const icon = this.togglePasswordBtn.querySelector('i');
                icon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
            }

            async handleLogin(e) {
                e.preventDefault();

                const formData = {
                    email: this.emailInput.value.trim(),
                    password: this.passwordInput.value,
                    device_name: this.deviceInput.value.trim()
                };

                // Validação básica
                if (!this.validateForm(formData)) {
                    return;
                }

                this.setLoading(true);

                try {
                    const response = await this.makeLoginRequest(formData);

                    if (response.ok) {
                        const data = await response.json();
                        await this.handleSuccessfulLogin(data, formData);
                    } else {
                        await this.handleLoginError(response);
                    }
                } catch (error) {
                    this.handleNetworkError(error);
                } finally {
                    this.setLoading(false);
                }
            }

            validateForm(data) {
                if (!data.email || !data.email.includes('@')) {
                    this.showToast('Por favor, insira um email válido', 'error');
                    return false;
                }

                if (!data.password || data.password.length < 6) {
                    this.showToast('A senha deve ter pelo menos 6 caracteres', 'error');
                    return false;
                }

                if (!data.device_name || data.device_name.length < 2) {
                    this.showToast('Por favor, informe um nome para o dispositivo', 'error');
                    return false;
                }

                return true;
            }

            async makeLoginRequest(formData) {
                return fetch(this.API_URL, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });
            }

            async handleSuccessfulLogin(data, formData) {
                // Armazenar token e dados do usuário
                this.storeAuthData(data, formData);

                // Mostrar modal de sucesso
                this.showSuccessModal(data, formData);

                // Mostrar toast de sucesso
                this.showToast('Login realizado com sucesso!', 'success');
            }

            async handleLoginError(response) {
                try {
                    const errorData = await response.json();
                    const errorMessage = errorData.message ||
                        errorData.errors?.email?.[0] ||
                        errorData.errors?.password?.[0] ||
                        'Erro ao fazer login. Verifique suas credenciais.';

                    this.showToast(errorMessage, 'error');
                } catch {
                    this.showToast(`Erro ${response.status}: ${response.statusText}`, 'error');
                }
            }

            handleNetworkError(error) {
                console.error('Erro de rede:', error);
                this.showToast('Erro de conexão. Verifique sua internet e tente novamente.', 'error');
            }

            storeAuthData(data, formData) {
                // Armazenar token
                localStorage.setItem(this.tokenKey, data.token);

                // Armazenar dados do usuário
                const userData = {
                    email: formData.email,
                    name: data.user?.name || formData.email.split('@')[0],
                    device: formData.device_name,
                    loginTime: new Date().toISOString()
                };
                localStorage.setItem(this.userKey, JSON.stringify(userData));

                // Calcular e armazenar tempo de expiração (24 horas)
                const expiresAt = new Date();
                expiresAt.setHours(expiresAt.getHours() + 24);
                localStorage.setItem(this.tokenExpiryKey, expiresAt.toISOString());

                console.log('Dados armazenados no localStorage:', {
                    token: data.token.substring(0, 20) + '...',
                    userData,
                    expiresAt: expiresAt.toISOString()
                });
            }

            showSuccessModal(data, formData) {
                // Preencher informações do usuário
                this.userName.textContent = data.user?.name || formData.email.split('@')[0];
                this.userEmail.textContent = formData.email;

                // Preencher informações do token
                this.tokenType.textContent = 'Bearer';

                // Calcular e mostrar tempo restante
                this.startTokenExpiryTimer();

                // Mostrar resposta completa formatada
                this.responseJson.textContent = JSON.stringify(data, null, 2);

                // Mostrar modal
                this.successModal.classList.remove('hidden');

                // Bloquear scroll do body
                document.body.style.overflow = 'hidden';
            }

            startTokenExpiryTimer() {
                const updateExpiryTime = () => {
                    const expiresAt = localStorage.getItem(this.tokenExpiryKey);
                    if (!expiresAt) {
                        this.tokenExpires.textContent = '--:--:--';
                        return;
                    }

                    const now = new Date();
                    const expiry = new Date(expiresAt);
                    const diffMs = expiry - now;

                    if (diffMs <= 0) {
                        this.tokenExpires.textContent = 'Expirado';
                        return;
                    }

                    const hours = Math.floor(diffMs / (1000 * 60 * 60));
                    const minutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((diffMs % (1000 * 60)) / 1000);

                    this.tokenExpires.textContent = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                };

                updateExpiryTime();
                setInterval(updateExpiryTime, 1000);
            }

            closeModal() {
                this.successModal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            copyTokenToClipboard() {
                const token = localStorage.getItem(this.tokenKey);
                if (!token) {
                    this.showToast('Token não encontrado!', 'error');
                    return;
                }

                navigator.clipboard.writeText(token)
                    .then(() => {
                        this.showToast('Token copiado para a área de transferência!', 'success');

                        // Feedback visual no botão
                        const originalText = this.copyTokenBtn.innerHTML;
                        this.copyTokenBtn.innerHTML = '<i class="fas fa-check"></i> Copiado!';
                        this.copyTokenBtn.style.background = '#4CAF50';

                        setTimeout(() => {
                            this.copyTokenBtn.innerHTML = originalText;
                            this.copyTokenBtn.style.background = '';
                        }, 2000);
                    })
                    .catch(err => {
                        console.error('Erro ao copiar token:', err);
                        this.showToast('Erro ao copiar token', 'error');
                    });
            }

            clearStoredToken() {
                if (confirm('Tem certeza que deseja limpar o token armazenado?')) {
                    localStorage.removeItem(this.tokenKey);
                    localStorage.removeItem(this.userKey);
                    localStorage.removeItem(this.tokenExpiryKey);

                    this.showToast('Token removido com sucesso!', 'success');
                    this.closeModal();

                    // Feedback visual
                    this.clearTokenBtn.innerHTML = '<i class="fas fa-check"></i> Limpo!';
                    this.clearTokenBtn.style.background = '#4CAF50';

                    setTimeout(() => {
                        this.clearTokenBtn.innerHTML = '<i class="fas fa-trash"></i> Limpar Token';
                        this.clearTokenBtn.style.background = '';
                    }, 2000);
                }
            }

            goToDashboard() {
                this.showToast('Redirecionando para o dashboard...', 'success');

                // Simular redirecionamento
                setTimeout(() => {
                    alert('Dashboard em desenvolvimento! Aqui você veria todas as funcionalidades do sistema.');
                    // window.location.href = '/dashboard.html'; // Para uso real
                }, 1000);
            }

            checkStoredToken() {
                const token = localStorage.getItem(this.tokenKey);
                const userData = localStorage.getItem(this.userKey);

                if (token && userData) {
                    try {
                        const user = JSON.parse(userData);
                        const expiresAt = localStorage.getItem(this.tokenExpiryKey);
                        const expiryDate = new Date(expiresAt);

                        if (new Date() < expiryDate) {
                            this.showToast(`Bem-vindo de volta, ${user.name}!`, 'success');

                            // Preencher campos com dados armazenados
                            this.emailInput.value = user.email;
                            this.deviceInput.value = user.device || 'postman';

                            console.log('Token válido encontrado:', {
                                user: user.name,
                                expiresAt: expiryDate.toLocaleString()
                            });
                        } else {
                            this.clearStoredToken();
                            this.showToast('Sessão expirada. Faça login novamente.', 'info');
                        }
                    } catch (error) {
                        console.error('Erro ao analisar dados armazenados:', error);
                        this.clearStoredToken();
                    }
                }
            }

            useDemoCredentials() {
                // Usar dados de demonstração pré-configurados
                this.emailInput.value = 'admin@ash.elf.eng.br';
                this.passwordInput.value = '@dmin#2026';
                this.deviceInput.value = 'web-client';

                this.showToast('Credenciais de demonstração carregadas! Clique em "Entrar".', 'success');

                // Focar no botão de login
                this.loginBtn.focus();
            }

            showToast(message, type = 'success') {
                // Configurar mensagem e ícone
                this.toastMessage.textContent = message;

                // Mostrar ícone correto
                this.toastIcons.forEach(icon => icon.classList.add('hidden'));
                if (type === 'success') {
                    this.toastIcons[0].classList.remove('hidden');
                } else if (type === 'error') {
                    this.toastIcons[1].classList.remove('hidden');
                }

                // Resetar animação da barra de progresso
                const progressBar = this.toast.querySelector('.toast-progress');
                progressBar.style.animation = 'none';
                void progressBar.offsetWidth; // Trigger reflow
                progressBar.style.animation = 'progress 3s linear forwards';

                // Mostrar toast
                this.toast.classList.remove('hidden');

                // Esconder automaticamente após 3 segundos
                setTimeout(() => {
                    this.hideToast();
                }, 3000);
            }

            hideToast() {
                this.toast.classList.add('hidden');
            }

            setLoading(isLoading) {
                if (isLoading) {
                    this.btnText.textContent = 'Autenticando...';
                    this.spinner.classList.remove('hidden');
                    this.loginBtn.disabled = true;
                } else {
                    this.btnText.textContent = 'Entrar';
                    this.spinner.classList.add('hidden');
                    this.loginBtn.disabled = false;
                }
            }
        }

        // Inicializar o sistema quando o DOM estiver carregado
        document.addEventListener('DOMContentLoaded', () => {
            new LoginSystem();

            // Adicionar efeito de digitação nos campos demo
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const deviceInput = document.getElementById('device_name');

            // Animar entrada dos dados demo
            setTimeout(() => {
                emailInput.focus();
                emailInput.select();
            }, 500);
        });
    </script>
</body>

</html>