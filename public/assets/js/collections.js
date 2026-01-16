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
        
        // Collection modal elements
        this.newCollectionBtn = document.getElementById('new-collection-btn');
        this.collectionModal = document.getElementById('collection-modal');
        this.closeModalBtn = document.getElementById('close-modal-btn');
        this.cancelCollectionBtn = document.getElementById('cancel-collection-btn');
        this.saveCollectionBtn = document.getElementById('save-collection-btn');
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
    }
    
    setupEventListeners() {
        // Toggle sidebar
        this.collectionsToggle.addEventListener('click', () => this.toggleCollectionsSidebar());
        
        // New collection button
        this.newCollectionBtn.addEventListener('click', () => this.openCollectionModal());
        
        // Collection modal
        this.closeModalBtn.addEventListener('click', () => this.closeCollectionModal());
        this.cancelCollectionBtn.addEventListener('click', () => this.closeCollectionModal());
        this.saveCollectionBtn.addEventListener('click', () => this.saveCollection());
        
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

// Initialize collections manager
document.addEventListener('DOMContentLoaded', () => {
    window.collectionsManager = new CollectionsManager();
});