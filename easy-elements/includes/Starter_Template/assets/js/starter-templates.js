class StarterTemplates {
    constructor() {
        this.currentTemplate = {};
        this.previewTemplate = {};
        this.selectedCategories = [];
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
            const isInstalled = item.getAttribute('data-installed') === 'true';

            const originalText = btn.textContent;
            btn.disabled = true;
            btn.classList.add('is-loading');
            btn.textContent = isInstalled ? 'Activating…' : 'Installing…';

            try {
                if (!isInstalled) {
                    await this.installPlugin(slug);
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
            const isInstalled = item.getAttribute('data-installed') === 'true';

            const originalText = btn.textContent;
            btn.disabled = true;
            btn.classList.add('is-loading');
            btn.textContent = isInstalled ? 'Activating…' : 'Installing…';

            try {
                if (!isInstalled) {
                    await this.installTheme(slug);
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
        const selected   = this.selectedCategories;
        const typeFilter = this.typeFilter;
        const showFav    = this.showFavourites;
        const favourites = this.favourites;

        this.filteredItems = this.allItems.filter(item => {
            const cats  = (item.getAttribute('data-category') || '').split(' ').filter(Boolean);
            const title = (item.getAttribute('data-title') || '').toLowerCase();
            const type  = item.getAttribute('data-type') || 'free';

            return (!search || title.includes(search))
                && (typeFilter === 'all' || type === typeFilter)
                && (!selected.length || selected.some(c => cats.includes(c)))
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
        const mega = document.querySelector('.easyel-mega');
        if (!mega) return;

        // Parent trigger (has children): toggle the bucket of parent slug + every child slug.
        // Cards tagged with either the parent or any child match, so this acts as "select all in group".
        mega.addEventListener('click', (e) => {
            const trigger = e.target.closest('.easyel-mega-item.has-children > .easyel-mega-trigger');
            if (!trigger) return;
            e.preventDefault();
            e.stopPropagation();

            const item = trigger.closest('.easyel-mega-item');
            const parentSlug = item.getAttribute('data-parent');
            const childBtns = item.querySelectorAll('.easyel-mega-child.easyel-cat-item');
            const childSlugs = Array.from(childBtns).map(c => c.getAttribute('data-cat'));
            const bucket = parentSlug ? [parentSlug].concat(childSlugs) : childSlugs;
            if (!bucket.length) return;

            const anyActive = (parentSlug && this.selectedCategories.indexOf(parentSlug) !== -1)
                || Array.from(childBtns).some(c => c.classList.contains('is-active'));

            this.selectedCategories = this.selectedCategories.filter(c => bucket.indexOf(c) === -1);

            if (anyActive) {
                childBtns.forEach(c => {
                    c.classList.remove('is-active');
                    c.setAttribute('aria-checked', 'false');
                });
            } else {
                bucket.forEach(slug => this.selectedCategories.push(slug));
                childBtns.forEach(c => {
                    c.classList.add('is-active');
                    c.setAttribute('aria-checked', 'true');
                });
            }

            this.updateMegaState();
            this.applyFilters();
        });

        // Child row click: additive toggle. Leaf parents (no children) act as a single-select chip.
        mega.addEventListener('click', (e) => {
            const item = e.target.closest('.easyel-cat-item');
            if (!item) return;
            // Skip — the parent trigger handler above already handles has-children parents
            if (item.matches('.easyel-mega-item.has-children > .easyel-mega-trigger')) return;

            e.preventDefault();
            e.stopPropagation();
            const cat = item.getAttribute('data-cat');
            if (!cat) return;

            const isLeaf = item.classList.contains('easyel-mega-leaf');
            const idx = this.selectedCategories.indexOf(cat);
            const wasActive = idx !== -1;

            if (isLeaf) {
                document.querySelectorAll('.easyel-cat-item.is-active').forEach(el => {
                    el.classList.remove('is-active');
                    el.setAttribute('aria-checked', 'false');
                });
                if (wasActive) {
                    this.selectedCategories = [];
                } else {
                    this.selectedCategories = [cat];
                    document.querySelectorAll(`.easyel-cat-item[data-cat="${cat}"]`).forEach(el => {
                        el.classList.add('is-active');
                        el.setAttribute('aria-checked', 'true');
                    });
                }
            } else {
                if (wasActive) {
                    this.selectedCategories.splice(idx, 1);
                    document.querySelectorAll(`.easyel-cat-item[data-cat="${cat}"]`).forEach(el => {
                        el.classList.remove('is-active');
                        el.setAttribute('aria-checked', 'false');
                    });
                } else {
                    this.selectedCategories.push(cat);
                    document.querySelectorAll(`.easyel-cat-item[data-cat="${cat}"]`).forEach(el => {
                        el.classList.add('is-active');
                        el.setAttribute('aria-checked', 'true');
                    });
                }
            }

            this.updateMegaState();
            this.applyFilters();
        });

        // Per-panel "Uncheck all" — clears every selected child under that parent.
        mega.addEventListener('click', (e) => {
            const btn = e.target.closest('.easyel-mega-deselect-all');
            if (!btn) return;
            e.preventDefault();
            e.stopPropagation();

            const parent = btn.getAttribute('data-parent');
            if (!parent) return;

            const children = document.querySelectorAll(`.easyel-cat-item[data-parent="${parent}"]`);
            const slugs = Array.from(children).map(c => c.getAttribute('data-cat'));

            this.selectedCategories = this.selectedCategories.filter(c => slugs.indexOf(c) === -1 && c !== parent);

            children.forEach(c => {
                c.classList.remove('is-active');
                c.setAttribute('aria-checked', 'false');
            });

            this.updateMegaState();
            this.applyFilters();
        });

        const reset = document.getElementById('easyel-cat-reset');
        if (reset) {
            reset.addEventListener('click', (e) => {
                e.stopPropagation();
                document.querySelectorAll('.easyel-cat-item.is-active').forEach(el => {
                    el.classList.remove('is-active');
                    el.setAttribute('aria-checked', 'false');
                });
                this.selectedCategories = [];
                this.updateMegaState();
                this.applyFilters();
            });
        }

        this.updateMegaState();
        this.initMegaPanelPositioning();
    }

    initMegaPanelPositioning() {
        const items = document.querySelectorAll('.easyel-mega-item.has-children');
        if (!items.length) return;

        const positionAll = () => items.forEach(item => this.positionMegaPanel(item));

        items.forEach(item => {
            item.addEventListener('mouseenter', () => this.positionMegaPanel(item));
            item.addEventListener('focusin', () => this.positionMegaPanel(item));
        });

        // Initial pass + recalc on resize (debounced).
        positionAll();
        let resizeTimer = null;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(positionAll, 100);
        });
    }

    positionMegaPanel(item) {
        const panel = item.querySelector('.easyel-mega-panel');
        if (!panel) return;

        item.classList.remove('is-right-aligned');

        const itemRect = item.getBoundingClientRect();
        // panel.offsetWidth is reliable even while visibility:hidden because the
        // element still participates in layout. min-width fallback for safety.
        const panelWidth = panel.offsetWidth || 360;
        const viewportWidth = document.documentElement.clientWidth;
        const safety = 16;

        // Default left-aligned: panel.left === item.left. Would it overflow right?
        const leftAlignedRight = itemRect.left + panelWidth;

        if (leftAlignedRight + safety > viewportWidth) {
            // Try right-align (panel.right === item.right). Does it fit on the left?
            const rightAlignedLeft = itemRect.right - panelWidth;
            if (rightAlignedLeft >= safety) {
                item.classList.add('is-right-aligned');
            }
            // else: panel is wider than the viewport — leave left-aligned so it stays
            // visible from the left edge instead of disappearing off-screen on the right.
        }
    }

    updateMegaState() {
        document.querySelectorAll('.easyel-mega-item').forEach(item => {
            const active = item.querySelectorAll('.easyel-cat-item.is-active').length;
            item.classList.toggle('has-selected', active > 0);

            const deselect = item.querySelector('.easyel-mega-deselect-all');
            if (deselect) {
                if (active > 0) deselect.removeAttribute('hidden');
                else deselect.setAttribute('hidden', '');
            }
        });

        const reset = document.getElementById('easyel-cat-reset');
        const resetCount = document.getElementById('easyel-mega-reset-count');
        const count = this.selectedCategories.length;
        if (reset) {
            if (count > 0) {
                reset.removeAttribute('hidden');
                if (resetCount) resetCount.textContent = String(count);
            } else {
                reset.setAttribute('hidden', '');
            }
        }
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
        const group = document.getElementById('easyel-type-filter');
        if (!group) return;

        const pills = group.querySelectorAll('.easyel-type-pill');
        const radios = group.querySelectorAll('input[name="easyel-type-filter"]');

        radios.forEach(radio => {
            radio.addEventListener('change', () => {
                if (!radio.checked) return;
                this.typeFilter = radio.value;
                pills.forEach(p => p.classList.toggle('is-active', p.dataset.type === radio.value));
                this.applyFilters();
            });
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
                    importable:                 btn.getAttribute('data-importable') !== 'false',
                    comingSoon:                 btn.getAttribute('data-coming-soon') === 'true',
                    proUrl:                     btn.getAttribute('data-pro-url') || '',
                    ctaLabel:                   btn.getAttribute('data-cta-label') || '',
                    // Action + nonce are per-button so the Pro add-on can route a
                    // licensed premium import to its own license-gated endpoint.
                    importAction:               btn.getAttribute('data-import-action') || 'easyel_import_content',
                    importNonce:                btn.getAttribute('data-import-nonce') || easyElementsStarterTemplatesajax.nonce
                };

                // The footer CTA imports free templates, becomes a marketing /
                // "Activate License" link for premium (preview-only) templates, and
                // shows a disabled "Coming Soon" state for licensed premium
                // templates whose demo data isn't published yet.
                this.updatePreviewCta(importCta, this.previewTemplate.importable, this.previewTemplate.comingSoon, this.previewTemplate.ctaLabel);

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
                // Licensed premium template without published demo data: nothing
                // to import yet, so the CTA is inert.
                if (this.previewTemplate && this.previewTemplate.comingSoon) {
                    return;
                }
                // Premium / preview-only template: there is no import data, so
                // send the user to the add-on instead of starting an import.
                if (this.previewTemplate && this.previewTemplate.importable === false) {
                    const url = this.previewTemplate.proUrl || (easyElementsStarterTemplatesajax || {}).proUrl;
                    if (url) window.open(url, '_blank', 'noopener');
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

    updatePreviewCta(cta, importable, comingSoon, ctaLabel) {
        if (!cta) return;
        let icon, label;
        if (importable) {
            icon  = 'dashicons-download';
            label = 'Import';
        } else if (comingSoon) {
            icon  = 'dashicons-clock';
            label = 'Coming Soon';
        } else {
            // Label is overridable so add-ons can match it to the URL
            // (e.g. "Activate License" when a Pro licence is inactive).
            icon  = 'dashicons-star-filled';
            label = ctaLabel || 'Get Pro';
        }
        cta.innerHTML = '<span class="dashicons ' + icon + '"></span> ' + label;
        cta.classList.toggle('is-pro-cta', !importable && !comingSoon);
        cta.classList.toggle('is-coming-soon', !!comingSoon);
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
                    defaultHomepage:            btn.getAttribute('data-default-homepage'),
                    templateId:                 btn.getAttribute('data-template-id') || '',
                    // Action + nonce are per-button so the Pro add-on can route a
                    // licensed premium import to its own license-gated endpoint.
                    importAction:               btn.getAttribute('data-import-action') || 'easyel_import_content',
                    importNonce:                btn.getAttribute('data-import-nonce') || easyElementsStarterTemplatesajax.nonce
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
        // Verify only — never install/activate without an explicit user click.
        // The per-plugin "Activate" buttons are the only sanctioned path to
        // changing plugin state, because each click is an explicit user action.
        const plugins = document.querySelectorAll('.easyel-plugin-item');
        const inactive = [];

        for (const plugin of plugins) {
            const isActive = plugin.getAttribute('data-active') === 'true';
            if (!isActive) {
                inactive.push(plugin.getAttribute('data-slug'));
            }
        }

        if (inactive.length) {
            const err = new Error(
                `Please activate the required plugin(s) before importing: ${inactive.join(', ')}`
            );
            err.code = 'inactive_required_plugins';
            throw err;
        }

        updateProgress(35, 'Required plugins are active.');
    }

    async installPlugin(slug) {
        return this.ajaxRequest('easyel_install_plugin', { slug });
    }

    async activatePlugin(slug) {
        return this.ajaxRequest('easyel_activate_plugin', { slug });
    }

    async processTheme(updateProgress) {
        // Verify only — the user activates the theme via the explicit
        // "Activate" button on the theme row. We never switch the theme
        // silently from this step.
        const themeCheckbox = document.querySelector('input[name="recommened-theme"]');
        if (!themeCheckbox || !themeCheckbox.checked) {
            return;
        }

        const themeItem = themeCheckbox.closest('.easyel-theme-item');
        const isActive  = themeItem && themeItem.getAttribute('data-active') === 'true';

        if (!isActive) {
            const err = new Error(
                `Please activate the recommended theme (${themeCheckbox.value}) before importing.`
            );
            err.code = 'inactive_recommended_theme';
            throw err;
        }

        updateProgress(50, 'Recommended theme is active.');
    }

    async installTheme(slug) {
        return this.ajaxRequest('easyel_install_theme', { slug });
    }

    async activateTheme(slug) {
        return this.ajaxRequest('easyel_activate_theme', { slug });
    }

    async importContent(updateProgress) {
        if (!this.currentTemplate.importFileUrl) return;

        updateProgress(70, 'Importing Content...');

        // Action + nonce default to the free endpoint but can be overridden
        // per-button (the Pro add-on points premium imports at its own
        // license-gated endpoint). template_id lets a license-gated endpoint
        // re-derive the trusted import URLs server-side.
        await this.ajaxRequest(
            this.currentTemplate.importAction || 'easyel_import_content',
            {
                template_id:                        this.currentTemplate.templateId || '',
                import_file_url:                    this.currentTemplate.importFileUrl,
                import_file_kit_url:                this.currentTemplate.importFileKitUrl,
                import_file_easyel_settings_url:    this.currentTemplate.importFileEasyelSettingsUrl,
                import_file_form_url:               this.currentTemplate.importFileFormUrl,
                default_homepage:                   this.currentTemplate.defaultHomepage
            },
            this.currentTemplate.importNonce || easyElementsStarterTemplatesajax.nonce
        );
    }

    ajaxRequest(action, data, nonce = easyElementsStarterTemplatesajax.nonce) {
        return new Promise((resolve, reject) => {
            jQuery.ajax({
                url:  easyElementsStarterTemplatesajax.ajaxUrl,
                type: 'POST',
                data: {
                    action,
                    security: nonce,
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
