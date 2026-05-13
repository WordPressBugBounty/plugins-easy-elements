class StarterTemplates {
    constructor() {
        this.currentTemplate = {};
        this.previewTemplate = {};
        this.currentCategory = 'all';
        this.searchQuery    = '';
        this.typeFilter     = 'all';
        this.showFavourites = false;
        this.favourites     = JSON.parse(localStorage.getItem('easyel_favourites') || '[]');

        this.allItems       = [];
        this.filteredItems  = [];
        this.renderedCount  = 0;
        this.initialBatch   = 12;
        this.batchSize      = 12;
        this.isLoading      = false;
        this.grid           = null;
        this.loadTarget     = null;
        this.io             = null;

        this.init();
    }

    init() {
        this.initCategoryFilters();
        this.initImporterModal();
        this.initSearch();
        this.initFavouritesToggle();
        this.initFavBtns();
        this.initTypeFilter();
        this.initPreviewModal();
        this.initSync();
        this.initPluginActivateBtns();
        this.initThemeActivateBtns();
        this.initProLockModal();

        this.cacheItems();
        this.applyFilters();
        this.initInfiniteScroll();
    }

    initPluginActivateBtns() {
        document.addEventListener('click', async (e) => {
            const btn = e.target.closest('.easyel-plugin-activate-btn');
            if (!btn) return;

            e.preventDefault();

            const item = btn.closest('.easyel-plugin-item');
            if (!item) return;

            const slug        = item.getAttribute('data-slug');
            const source      = item.getAttribute('data-source');
            const isInstalled = item.getAttribute('data-installed') === 'true';

            const originalText = btn.textContent;
            btn.disabled = true;
            btn.classList.add('is-loading');
            btn.textContent = isInstalled ? 'Activating…' : 'Installing…';

            try {
                if (!isInstalled) {
                    await this.installPlugin(slug, source);
                    item.setAttribute('data-installed', 'true');
                    btn.textContent = 'Activating…';
                }
                await this.activatePlugin(slug);

                item.setAttribute('data-active', 'true');
                const statusEl = item.querySelector('.easyel-importer-step-status');
                if (statusEl) {
                    statusEl.classList.remove('inactive');
                    statusEl.classList.add('active');
                    statusEl.textContent = 'Active';
                }
                btn.remove();

                this.refreshActivationWarning();
            } catch (err) {
                console.error(`Failed to install/activate ${slug}:`, err);
                btn.disabled = false;
                btn.classList.remove('is-loading');
                btn.textContent = originalText;
                alert('Error: ' + err.message);
            }
        });
    }

    initThemeActivateBtns() {
        document.addEventListener('click', async (e) => {
            const btn = e.target.closest('.easyel-theme-activate-btn');
            if (!btn) return;

            e.preventDefault();

            const item = btn.closest('.easyel-theme-item');
            if (!item) return;

            const slug        = item.getAttribute('data-slug');
            const source      = item.getAttribute('data-source');
            const isInstalled = item.getAttribute('data-installed') === 'true';

            const originalText = btn.textContent;
            btn.disabled = true;
            btn.classList.add('is-loading');
            btn.textContent = isInstalled ? 'Activating…' : 'Installing…';

            try {
                if (!isInstalled) {
                    await this.installTheme(slug, source);
                    item.setAttribute('data-installed', 'true');
                    btn.textContent = 'Activating…';
                }
                await this.activateTheme(slug);

                item.setAttribute('data-active', 'true');
                const checkbox = item.querySelector('input[type="checkbox"]');
                if (checkbox) {
                    checkbox.checked = true;
                    checkbox.setAttribute('data-installed', 'true');
                }
                const statusEl = item.querySelector('.easyel-importer-step-status');
                if (statusEl) {
                    statusEl.classList.remove('inactive');
                    statusEl.classList.add('active');
                    statusEl.textContent = 'Active';
                }
                btn.remove();
            } catch (err) {
                console.error(`Failed to install/activate theme ${slug}:`, err);
                btn.disabled = false;
                btn.classList.remove('is-loading');
                btn.textContent = originalText;
                alert('Error: ' + err.message);
            }
        });
    }

    refreshActivationWarning() {
        const anyInactive = document.querySelector('.easyel-plugin-item[data-active="false"]');
        const warning = document.querySelector('.easyel-importer-step-checkbox-warning');
        if (!anyInactive && warning) {
            warning.style.display = 'none';
        }
    }

    cacheItems() {
        this.grid       = document.querySelector('#easyel-importer-steps-grid');
        this.loadTarget = document.querySelector('.easyel-load-target');
        if (!this.grid) return;

        this.allItems = Array.from(this.grid.querySelectorAll('.easyel-item'));
        this.allItems.forEach(item => item.parentNode.removeChild(item));
    }

    applyFilters() {
        if (!this.grid) return;

        const search     = this.searchQuery.toLowerCase();
        const category   = this.currentCategory;
        const typeFilter = this.typeFilter;
        const showFav    = this.showFavourites;
        const favourites = this.favourites;

        this.filteredItems = this.allItems.filter(item => {
            const cats  = (item.getAttribute('data-category') || '').split(' ');
            const title = (item.getAttribute('data-title') || '').toLowerCase();
            const type  = item.getAttribute('data-type') || 'free';

            return (!search || title.includes(search))
                && (typeFilter === 'all' || type === typeFilter)
                && (category === 'all' || cats.includes(category))
                && (!showFav || favourites.includes(item.getAttribute('data-title')));
        });

        this.grid.innerHTML = '';
        this.renderedCount = 0;
        this.renderBatch(this.initialBatch);
    }

    renderBatch(size) {
        if (!this.grid || this.renderedCount >= this.filteredItems.length) {
            this.updateLoadTarget();
            return;
        }

        const next = this.filteredItems.slice(this.renderedCount, this.renderedCount + size);
        const frag = document.createDocumentFragment();
        next.forEach(item => frag.appendChild(item));
        this.grid.appendChild(frag);
        this.renderedCount += next.length;

        this.updateLoadTarget();
    }

    updateLoadTarget() {
        if (!this.loadTarget) return;
        const hasMore = this.renderedCount < this.filteredItems.length;
        this.loadTarget.style.display = hasMore ? 'block' : 'none';
    }

    initInfiniteScroll() {
        if (!this.loadTarget || typeof IntersectionObserver === 'undefined') return;

        this.io = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (!entry.isIntersecting || this.isLoading) return;
                if (this.renderedCount >= this.filteredItems.length) return;

                this.isLoading = true;
                this.grid.classList.add('loading');

                setTimeout(() => {
                    this.renderBatch(this.batchSize);
                    this.grid.classList.remove('loading');
                    this.isLoading = false;
                }, 250);
            });
        }, { rootMargin: '300px 0px' });

        this.io.observe(this.loadTarget);
    }

    initCategoryFilters() {
        const btns = document.querySelectorAll('.easyel-grid-filters .easyel-tab');
        btns.forEach(btn => {
            btn.addEventListener('click', () => {
                btns.forEach(b => b.classList.remove('is-active'));
                btn.classList.add('is-active');
                this.currentCategory = btn.getAttribute('data-category');
                this.applyFilters();
            });
        });
    }

    initSearch() {
        const input = document.getElementById('easyel-search-input');
        if (!input) return;
        input.addEventListener('input', e => {
            this.searchQuery = e.target.value;
            this.applyFilters();
        });
    }

    initTypeFilter() {
        const select = document.getElementById('easyel-type-filter');
        if (!select) return;
        select.addEventListener('change', () => {
            this.typeFilter = select.value;
            this.applyFilters();
        });
    }

    initFavouritesToggle() {
        const btn = document.getElementById('easyel-favorites-toggle');
        if (!btn) return;

        this.renderFavouriteStates();

        btn.addEventListener('click', () => {
            this.showFavourites = !this.showFavourites;
            btn.setAttribute('aria-pressed', String(this.showFavourites));
            btn.classList.toggle('active', this.showFavourites);
            this.applyFilters();
        });
    }

    initFavBtns() {
        document.querySelectorAll('.easyel-fav-btn').forEach(btn => {
            btn.addEventListener('click', e => {
                e.stopPropagation();
                const name = btn.getAttribute('data-template-name');
                const idx  = this.favourites.indexOf(name);
                if (idx > -1) {
                    this.favourites.splice(idx, 1);
                    btn.classList.remove('active');
                } else {
                    this.favourites.push(name);
                    btn.classList.add('active');
                }
                localStorage.setItem('easyel_favourites', JSON.stringify(this.favourites));
                if (this.showFavourites) {
                    this.applyFilters();
                }
            });
        });
    }

    renderFavouriteStates() {
        document.querySelectorAll('.easyel-fav-btn').forEach(btn => {
            if (this.favourites.includes(btn.getAttribute('data-template-name'))) {
                btn.classList.add('active');
            }
        });
    }

    initPreviewModal() {
        const modal = document.getElementById('easyel-preview-modal');
        if (!modal) return;

        const iframe    = document.getElementById('easyel-preview-iframe');
        const titleEl   = modal.querySelector('.easyel-preview-template-title');
        const container = modal.querySelector('.easyel-preview-iframe-container');
        const loader    = document.getElementById('easyel-preview-loader');
        const backBtn   = modal.querySelector('.easyel-preview-back-btn');
        const importCta = modal.querySelector('.easyel-preview-import-cta');

        const showLoader = () => { if (loader) loader.style.display = 'flex'; };
        const hideLoader = () => { if (loader) loader.style.display = 'none'; };

        iframe.addEventListener('load', () => {
            if (iframe.src !== 'about:blank') hideLoader();
        });

        document.querySelectorAll('.easyel-preview-btn').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                this.previewTemplate = {
                    importFileUrl:              btn.getAttribute('data-import-file-url'),
                    importFileKitUrl:           btn.getAttribute('data-import-file-kit-url'),
                    importFileEasyelSettingsUrl: btn.getAttribute('data-import-file-easyel-settings-url'),
                    importFileFormUrl:          btn.getAttribute('data-import-file-form-url'),
                    defaultHomepage:            btn.getAttribute('data-default-homepage'),
                    isPro:                      btn.getAttribute('data-import-file-is-pro') === 'true'
                };

                titleEl.textContent = btn.getAttribute('data-template-name');
                showLoader();
                iframe.src = btn.getAttribute('data-preview-url');
                container.setAttribute('data-device', 'desktop');
                modal.querySelectorAll('.easyel-device-btn').forEach(b => b.classList.remove('active'));
                modal.querySelector('[data-device="desktop"]').classList.add('active');
                modal.style.display = 'flex';
            });
        });

        backBtn.addEventListener('click', () => {
            modal.style.display = 'none';
            hideLoader();
            iframe.src = 'about:blank';
        });

        modal.querySelectorAll('.easyel-device-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                modal.querySelectorAll('.easyel-device-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                container.setAttribute('data-device', btn.getAttribute('data-device'));
            });
        });

        if (importCta) {
            importCta.addEventListener('click', () => {
                if (this.previewTemplate.isPro && !this.canImportPro()) {
                    modal.style.display = 'none';
                    hideLoader();
                    iframe.src = 'about:blank';
                    this.showProLockModal();
                    return;
                }
                modal.style.display = 'none';
                iframe.src = 'about:blank';
                if (this.previewTemplate) {
                    this.currentTemplate = this.previewTemplate;
                }
                this.showModal();
            });
        }
    }

    canImportPro() {
        const cfg = easyElementsStarterTemplatesajax || {};
        return Boolean(cfg.hasPro) && Boolean(cfg.licenseValid);
    }

    initProLockModal() {
        const modal = document.getElementById('easyel-pro-lock-modal');
        if (!modal) return;
        const close = () => { modal.style.display = 'none'; };
        const closer = modal.querySelector('.easyel-pro-lock-closer');
        const overlay = modal.querySelector('.easyel-pro-lock-overlay');
        if (closer) closer.addEventListener('click', close);
        if (overlay) overlay.addEventListener('click', close);
    }

    showProLockModal() {
        const modal = document.getElementById('easyel-pro-lock-modal');
        if (!modal) return;
        const cfg = easyElementsStarterTemplatesajax || {};
        const i18n = cfg.i18n || {};
        const cta = modal.querySelector('.easyel-pro-lock-cta');
        const ctaText = modal.querySelector('.easyel-pro-lock-cta-text');
        const ctaIcon = modal.querySelector('.easyel-pro-lock-cta .dashicons');

        if (cta && ctaText) {
            if (!cfg.hasPro) {
                cta.href = cfg.proUpgradeUrl || '#';
                cta.target = '_blank';
                cta.rel = 'noopener noreferrer';
                ctaText.textContent = i18n.upgradeBtn || 'Upgrade to Pro';
                if (ctaIcon) {
                    ctaIcon.classList.remove('dashicons-admin-network');
                    ctaIcon.classList.add('dashicons-star-filled');
                }
            } else {
                cta.href = cfg.licenseActivateUrl || '#';
                cta.target = '_self';
                cta.removeAttribute('rel');
                ctaText.textContent = i18n.activateLicenseBtn || 'Activate License';
                if (ctaIcon) {
                    ctaIcon.classList.remove('dashicons-star-filled');
                    ctaIcon.classList.add('dashicons-admin-network');
                }
            }
        }

        modal.style.display = 'flex';
    }

    initSync() {
        const btn = document.getElementById('easyel-sync-btn');
        if (!btn) return;
        btn.addEventListener('click', () => {
            const icon = btn.querySelector('.dashicons');
            icon.classList.add('easyel-spin');
            btn.disabled = true;

            jQuery.ajax({
                url: easyElementsStarterTemplatesajax.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'easyel_sync_library',
                    nonce: easyElementsStarterTemplatesajax.nonce,
                },
            }).always(() => {
                window.location.reload();
            });
        });
    }

    showModal() {
        document.querySelector('#easyel-importer-modal').style.display = 'flex';
        document.querySelector('.easyel-importer-modal-step-1').style.display = 'flex';
        document.querySelector('.easyel-importer-modal-step-2').style.display = 'none';
        document.querySelector('.easyel-importer-modal-step-3').style.display = 'none';
    }

    closeModal() {
        document.querySelector('#easyel-importer-modal').style.display = 'none';
    }

    initImporterModal() {
        document.querySelectorAll('.easyel-import-btn').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                this.currentTemplate = {
                    importFileUrl:              btn.getAttribute('data-import-file-url'),
                    importFileKitUrl:           btn.getAttribute('data-import-file-kit-url'),
                    importFileEasyelSettingsUrl: btn.getAttribute('data-import-file-easyel-settings-url'),
                    importFileFormUrl:          btn.getAttribute('data-import-file-form-url'),
                    defaultHomepage:            btn.getAttribute('data-default-homepage')
                };
                this.showModal();
            });
        });

        document.querySelector('.easyel-importer-modal-closer').addEventListener('click', () => {
            this.closeModal();
        });

        const nextBtn = document.querySelector('.easyel-importer-step-next-btn');
        if (nextBtn) {
            nextBtn.onclick = () => this.startImport();
        }
    }

    async startImport() {
        document.querySelector('.easyel-importer-modal-step-1').style.display = 'none';
        document.querySelector('.easyel-importer-modal-step-2').style.display = 'flex';

        const fill = document.querySelector('.easyel-importer-step-progress-fill');
        const text = document.querySelector('.easyel-importer-step-progress-text');

        const updateProgress = (percent, message) => {
            fill.style.width = percent + '%';
            text.textContent = message;
        };

        try {
            updateProgress(5,  'Checking Plugins...');
            await this.processPlugins(updateProgress);

            updateProgress(40, 'Checking Theme...');
            await this.processTheme(updateProgress);

            updateProgress(60, 'Importing Content...');
            await this.importContent(updateProgress);

            updateProgress(100, 'Completed!');

            setTimeout(() => {
                document.querySelector('.easyel-importer-modal-step-2').style.display = 'none';
                document.querySelector('.easyel-importer-modal-step-3').style.display = 'flex';
            }, 500);
        } catch (error) {
            alert('Error: ' + error.message);
            this.closeModal();
            document.querySelector('.easyel-importer-modal-step-1').style.display = 'flex';
            document.querySelector('.easyel-importer-modal-step-2').style.display = 'none';
        }
    }

    async processPlugins(updateProgress) {
        const plugins = document.querySelectorAll('.easyel-plugin-item');
        const total   = plugins.length;
        let current   = 0;
        const step    = 30 / (total || 1);

        for (const plugin of plugins) {
            const slug      = plugin.getAttribute('data-slug');
            const source    = plugin.getAttribute('data-source');
            const isActive  = plugin.getAttribute('data-active') === 'true';

            if (!isActive) {
                const isInstalled = plugin.getAttribute('data-installed') === 'true';
                try {
                    if (!isInstalled) {
                        updateProgress(10 + (current * step), `Installing ${slug}...`);
                        await this.installPlugin(slug, source);
                    }
                    updateProgress(10 + (current * step) + (step / 2), `Activating ${slug}...`);
                    await this.activatePlugin(slug);
                } catch (e) {
                    console.error(`Failed to install/activate ${slug}:`, e);
                }
            }
            current++;
        }
    }

    async installPlugin(slug, source) {
        return this.ajaxRequest('easyel_install_plugin', { slug, source });
    }

    async activatePlugin(slug) {
        return this.ajaxRequest('easyel_activate_plugin', { slug });
    }

    async processTheme(updateProgress) {
        const themeCheckbox = document.querySelector('input[name="recommened-theme"]');
        if (themeCheckbox && themeCheckbox.checked) {
            const slug        = themeCheckbox.value;
            const source      = themeCheckbox.getAttribute('data-source');
            const isInstalled = themeCheckbox.getAttribute('data-installed') === 'true';

            if (!isInstalled) {
                updateProgress(45, `Installing Theme ${slug}...`);
                await this.installTheme(slug, source);
            }
            updateProgress(50, `Activating Theme ${slug}...`);
            await this.activateTheme(slug);
        }
    }

    async installTheme(slug, source) {
        return this.ajaxRequest('easyel_install_theme', { slug, source });
    }

    async activateTheme(slug) {
        return this.ajaxRequest('easyel_activate_theme', { slug });
    }

    async importContent(updateProgress) {
        if (!this.currentTemplate.importFileUrl) return;

        updateProgress(70, 'Importing Content...');

        let activatedTheme = '';
        const themeCheckbox = document.querySelector('input[name="recommened-theme"]');
        if (themeCheckbox && themeCheckbox.checked) {
            activatedTheme = themeCheckbox.value;
        }

        await this.ajaxRequest('easyel_import_content', {
            import_file_url:                    this.currentTemplate.importFileUrl,
            import_file_kit_url:                this.currentTemplate.importFileKitUrl,
            import_file_easyel_settings_url:    this.currentTemplate.importFileEasyelSettingsUrl,
            import_file_form_url:               this.currentTemplate.importFileFormUrl,
            default_homepage:                   this.currentTemplate.defaultHomepage,
            activated_theme:                    activatedTheme
        });
    }

    ajaxRequest(action, data) {
        return new Promise((resolve, reject) => {
            jQuery.ajax({
                url:  easyElementsStarterTemplatesajax.ajaxUrl,
                type: 'POST',
                data: {
                    action,
                    security: easyElementsStarterTemplatesajax.nonce,
                    ...data
                },
                success: (response) => {
                    if (response.success) {
                        resolve(response);
                    } else {
                        const message = (response.data && response.data.message) ? response.data.message : 'Unknown error';
                        if (message.includes('already installed') || message.includes('Destination folder already exists')) {
                            resolve(response);
                        } else {
                            console.error('Import Error:', response);
                            reject(new Error(message));
                        }
                    }
                },
                error: (err) => {
                    console.error('AJAX Error:', err);
                    reject(new Error('AJAX Error: ' + err.statusText));
                }
            });
        });
    }
}

document.addEventListener('DOMContentLoaded', () => new StarterTemplates());
