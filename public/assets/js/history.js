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

// Initialize history manager and export addToHistory function
document.addEventListener('DOMContentLoaded', () => {
    window.historyManager = new HistoryManager();
    
    // Override the addToHistory function in the main script if it exists
    if (window.apiClient) {
        const originalAddToHistory = window.apiClient.addToHistory;
        window.apiClient.addToHistory = function(request) {
            originalAddToHistory.call(this, request);
            if (window.historyManager) {
                window.historyManager.addToHistory(request);
            }
        };
    }
});