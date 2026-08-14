<script>
    function blockEditor(initialBlocks, config) {
        config = config || {};
        return {
            activeTab: 'en',
            previewDevice: 'desktop',
            isSyncing: false,
            syncError: false,
            isFullscreen: false,
            isDirty: false,
            outlineOpen: false,
            paletteModalOpen: false,
            mediaModalOpen: false,
            presetModalOpen: false,
            searchQuery: '',
            pageTitle: config.pageTitle || '',
            metaDescription: config.metaDescription || '',
            slug: config.slug || '',
            previewUrl: config.previewUrl || '/admin/content/preview',
            mediaUrl: config.mediaUrl || '/admin/media',
            mediaUploadUrl: config.mediaUploadUrl || '/admin/media/upload',
            csrfToken: config.csrfToken || '',
            activeMediaTarget: null,
            mediaFiles: [],
            syncDebounceTimer: null,
            abortController: null,
            sortableInstance: null,
            keydownHandler: null,

            // Custom Async Confirm Modal State
            confirmModal: {
                open: false,
                title: '',
                message: '',
                confirmText: 'Delete',
                isDanger: true,
                action: null,
                isProcessing: false
            },

            // Toast Feedback State
            toast: {
                open: false,
                message: '',
                type: 'success',
                timeout: null
            },

            blocks: (initialBlocks || []).map(function(b, index) {
                var content = b.content;
                if (typeof content === 'string') {
                    try {
                        content = JSON.parse(content);
                        if (typeof content === 'string') content = JSON.parse(content);
                    } catch (e) {
                        content = {};
                    }
                }
                return {
                    id: b.id || (Date.now() + index * 10 + Math.floor(Math.random() * 1000)),
                    block_type: b.block_type || 'hero',
                    order: b.order !== undefined ? b.order : index,
                    is_visible: b.is_visible !== undefined ? Boolean(b.is_visible) : true,
                    collapsed: false,
                    content: content || { en: {}, id: {} }
                };
            }),

            availableBlockTypes: [
                { type: 'hero', label: 'Hero Section', desc: 'Main banner with badge, heading, subheading, and CTA buttons.' },
                { type: 'text', label: 'Text Block', desc: 'Simple heading and paragraph body content.' },
                { type: 'stats', label: 'Stats Counter', desc: 'Heading with highlight number stat metrics.' },
                { type: 'faq', label: 'FAQ Accordion', desc: 'Frequently Asked Questions accordion list.' },
                { type: 'cta', label: 'Call to Action', desc: 'Promotional banner with button link.' },
                { type: 'process_steps', label: 'Process Steps', desc: 'Step-by-step process with images and descriptions.' },
                { type: 'text_with_stats', label: 'Text + Stats', desc: 'Paragraph text paired with checklist and stat items.' },
                { type: 'articles', label: 'Articles Grid', desc: 'Dynamic news & articles fetched automatically from database.' },
                { type: 'testimonials', label: 'Testimonials', desc: 'Client reviews and feedback fetched from database.' },
                { type: 'origins', label: 'Coffee Origins', desc: 'Regional partner origins slider fetched from database.' },
                { type: 'export_map', label: 'Export Map', desc: 'Interactive global export destinations map.' },
                { type: 'contact', label: 'Contact Form', desc: 'Interactive export inquiry form with email, phone, and office details.' }
            ],

            get filteredBlocks() {
                if (!this.searchQuery) return this.blocks;
                var q = this.searchQuery.toLowerCase();
                var self = this;
                return this.blocks.filter(function(b) {
                    var labelStr = self.label(b).toLowerCase();
                    var enHead = (self.content(b, 'en').heading || '').toLowerCase();
                    var idHead = (self.content(b, 'id').heading || '').toLowerCase();
                    return labelStr.indexOf(q) !== -1 || enHead.indexOf(q) !== -1 || idHead.indexOf(q) !== -1;
                });
            },

            get isAllExpanded() {
                return this.blocks.length > 0 && this.blocks.every(function(b) { return !b.collapsed; });
            },

            toggleExpandAll: function() {
                var expand = !this.isAllExpanded;
                this.blocks.forEach(function(b) { b.collapsed = !expand; });
            },

            defaultData: function(type) {
                var defaults = {
                    hero: {
                        en: { label: 'LIMA BIJI AGRITECH', heading: 'SPECIALTY ENZYMATIC<br>CIVET COFFEE', subheading: 'Luxury civet coffee profile — zero animal cruelty.', cta_text: 'Explore Process', cta_url: '/innovation', cta2_text: 'Our News', cta2_url: '/news' },
                        id: { label: 'LIMA BIJI AGRITECH', heading: 'KOPI LUWAK<br>ENZIMATIK SPECIALTY', subheading: 'Profil kopi luwak mewah — tanpa eksploitasi hewan.', cta_text: 'Jelajahi Proses', cta_url: '/innovation', cta2_text: 'Berita Kami', cta2_url: '/news' }
                    },
                    text: {
                        en: { heading: 'OUR PHILOSOPHY', body: 'We combine traditional Indonesian coffee heritage with modern enzymatic biotechnology.' },
                        id: { heading: 'FILOSOFI KAMI', body: 'Kami menggabungkan warisan kopi Indonesia dengan bioteknologi enzimatik modern.' }
                    },
                    stats: {
                        en: { heading: 'We provide coffee for', items: [{ label: 'Export Countries', value: '7+' }, { label: 'Partner Farms', value: '10+' }] },
                        id: { heading: 'Kami menyediakan kopi untuk', items: [{ label: 'Negara Ekspor', value: '7+' }, { label: 'Petani Mitra', value: '10+' }] }
                    },
                    faq: {
                        en: { heading: 'FREQUENTLY ASKED QUESTIONS', items: [{ question: 'How does the process work?', answer: 'Our bio-identical enzymes replicate natural civet fermentation.' }] },
                        id: { heading: 'PERTANYAAN UMUM', items: [{ question: 'Bagaimana proses ini bekerja?', answer: 'Enzim bio-identik kami mereplikasi fermentasi luwak alami.' }] }
                    },
                    cta: {
                        en: { heading: 'Ready to Elevate Your Coffee Menu?', body: 'Partner with Lima Biji for consistent specialty coffee.', button_text: 'Get in Touch', button_url: '/contact' },
                        id: { heading: 'Siap Meningkatkan Menu Kopi Anda?', body: 'Bermitra dengan Lima Biji untuk kopi specialty berkualitas.', button_text: 'Hubungi Kami', button_url: '/contact' }
                    },
                    process_steps: {
                        en: { heading: 'HOW IT WORKS', subtitle: 'Six precise steps from harvest to export.', items: [{ step: '01', title: 'Ethical Cherry Sourcing', image: 'https://images.unsplash.com/photo-1587734195503-904fca47e0e9?q=80&w=800&auto=format&fit=crop', description: 'Hand-picked ripe red cherries.', details: '20°+ Brix, High Altitude' }] },
                        id: { heading: 'CARA KERJA', subtitle: 'Enam langkah presisi dari panen hingga ekspor.', items: [{ step: '01', title: 'Pengadaan Ceri Etis', image: 'https://images.unsplash.com/photo-1587734195503-904fca47e0e9?q=80&w=800&auto=format&fit=crop', description: 'Ceri merah matang hasil petik tangan.', details: '20°+ Brix, Dataran Tinggi' }] }
                    },
                    text_with_stats: {
                        en: { heading: 'Why Cruelty-Free Matters', body: 'Traditional civet coffee relies on caged civets.', body2: 'Our enzymatic process achieves identical results.', checklist: '100% Cruelty-Free, SCA 84+, Consistent Quality', items: [{ label: 'SCA Score', value: '84+' }, { label: 'Animals Involved', value: '0' }] },
                        id: { heading: 'Mengapa Bebas Eksploitasi Itu Penting', body: 'Kopi luwak tradisional bergantung pada kurungan musang.', body2: 'Proses enzimatik kami mencapai hasil identik.', checklist: '100% Bebas Eksploitasi, SCA 84+, Kualitas Konsisten', items: [{ label: 'Skor SCA', value: '84+' }, { label: 'Hewan Terlibat', value: '0' }] }
                    },
                    articles: {
                        en: { heading: 'NEWS & STORIES', limit: 6, show_all: false },
                        id: { heading: 'BERITA & CERITA', limit: 6, show_all: false }
                    },
                    testimonials: {
                        en: { heading: 'WHAT THEY SAY', limit: 6, show_all: false },
                        id: { heading: 'APA KATA MEREKA', limit: 6, show_all: false }
                    },
                    origins: {
                        en: { heading: 'COFFEE ORIGINS', subtitle: 'Explore our partner regions across Indonesia.', limit: 6, show_all: false },
                        id: { heading: 'ASAL KOPI', subtitle: 'Jelajahi daerah mitra kami di Indonesia.', limit: 6, show_all: false }
                    },
                    export_map: {
                        en: { heading: 'EXPORT MAP', subtitle: 'Our global distribution network.' },
                        id: { heading: 'PETA EKSPOR', subtitle: 'Jaringan distribusi global kami.' }
                    },
                    contact: {
                        en: { heading: 'GET IN TOUCH', subtitle: 'Each one of us is an expert in a different market and can help you with all of your roastery\'s requirements.', email: 'export@limabijiagritech.com', phone: '+62 812 3456 7890', address: 'Bogor, West Java, Indonesia' },
                        id: { heading: 'HUBUNGI KAMI', subtitle: 'Masing-masing dari kami ahli di pasar yang berbeda dan dapat membantu kebutuhan roastery Anda.', email: 'export@limabijiagritech.com', phone: '+62 812 3456 7890', address: 'Bogor, Jawa Barat, Indonesia' }
                    }
                };
                return defaults[type] || { en: {}, id: {} };
            },

            addBlock: function(type) {
                var defaultContent = JSON.parse(JSON.stringify(this.defaultData(type)));
                this.blocks.push({
                    id: Date.now() + Math.floor(Math.random() * 1000),
                    block_type: type,
                    order: this.blocks.length,
                    is_visible: true,
                    collapsed: false,
                    content: defaultContent
                });
                this.isDirty = true;
                this.queuePreviewUpdate();
                this.showToast('Added ' + this.label({ block_type: type }) + ' block', 'success');
            },

            isFieldHidden: function(block, loc, field) {
                if (!block || !block.content || !block.content[loc]) return false;
                var hiddenMap = block.content[loc].field_hidden || {};
                var val = hiddenMap[field];
                if (val === undefined || val === null || val === 0 || val === '0' || val === false || val === 'false') {
                    return false;
                }
                return true;
            },

            toggleFieldHidden: function(block, loc, field) {
                if (!block.content) block.content = { en: {}, id: {} };
                if (!block.content[loc]) block.content[loc] = {};
                if (!block.content[loc].field_hidden) block.content[loc].field_hidden = {};
                
                var currentlyHidden = this.isFieldHidden(block, loc, field);
                block.content[loc].field_hidden[field] = currentlyHidden ? 0 : 1;
                this.isDirty = true;
                this.queuePreviewUpdate();
            },

            label: function(block) {
                var labels = {
                    hero: 'Hero',
                    text: 'Text',
                    stats: 'Stats',
                    faq: 'FAQ',
                    cta: 'CTA',
                    process_steps: 'Process Steps',
                    text_with_stats: 'Text + Stats',
                    articles: 'Articles',
                    testimonials: 'Testimonials',
                    origins: 'Origins',
                    export_map: 'Export Map',
                    contact: 'Contact Form'
                };
                return labels[block.block_type] || block.block_type;
            },

            summary: function(block) {
                var en = this.content(block, 'en') || {};
                if (block.block_type === 'hero') return en.label || en.heading || '';
                if (block.block_type === 'text') return en.heading || '';
                if (block.block_type === 'cta') return en.heading || '';
                if (block.block_type === 'stats') return (en.items || []).length + ' stats';
                if (block.block_type === 'faq') return (en.items || []).length + ' items';
                if (block.block_type === 'process_steps') return (en.items || []).length + ' steps';
                if (block.block_type === 'text_with_stats') return en.heading || '';
                if (block.block_type === 'articles') return (en.show_all || en.limit === 0 ? 'All' : (en.limit || 6)) + ' articles';
                if (block.block_type === 'testimonials') return (en.show_all || en.limit === 0 ? 'All' : (en.limit || 6)) + ' testimonials';
                if (block.block_type === 'origins') return (en.show_all || en.limit === 0 ? 'All' : (en.limit || 6)) + ' origins';
                if (block.block_type === 'export_map') return 'Database Destinations';
                if (block.block_type === 'contact') return en.heading || 'Contact Form';
                return '';
            },

            duplicateBlock: function(index) {
                var clone = JSON.parse(JSON.stringify(this.blocks[index]));
                clone.id = Date.now() + Math.floor(Math.random() * 1000);
                clone.order = this.blocks.length;
                this.blocks.splice(index + 1, 0, clone);
                this.isDirty = true;
                this.queuePreviewUpdate();
                this.showToast('Block duplicated', 'info');
            },

            // Custom Async Confirmation Modal Methods
            askConfirm: function(title, message, action, confirmText, isDanger) {
                if (confirmText === undefined) confirmText = 'Delete';
                if (isDanger === undefined) isDanger = true;
                if (this.confirmModal.open || this.confirmModal.isProcessing) return;
                this.confirmModal = {
                    open: true,
                    title: title,
                    message: message,
                    confirmText: confirmText,
                    isDanger: isDanger,
                    action: action,
                    isProcessing: false
                };
            },

            executeConfirm: async function() {
                if (!this.confirmModal.action || this.confirmModal.isProcessing) return;
                this.confirmModal.isProcessing = true;
                try {
                    await this.confirmModal.action();
                } catch (err) {
                    console.error('Confirmation action error:', err);
                    this.showToast('Action failed', 'error');
                } finally {
                    this.confirmModal.open = false;
                    this.confirmModal.action = null;
                    this.confirmModal.isProcessing = false;
                }
            },

            cancelConfirm: function() {
                if (this.confirmModal.isProcessing) return;
                this.confirmModal.open = false;
                this.confirmModal.action = null;
                this.confirmModal.isProcessing = false;
            },

            removeBlock: function(index) {
                var self = this;
                var blockName = this.label(this.blocks[index]);
                this.askConfirm(
                    'Remove ' + blockName + ' Block',
                    'Are you sure you want to remove this ' + blockName + ' block? This cannot be undone until page reload.',
                    function() {
                        self.blocks.splice(index, 1);
                        self.isDirty = true;
                        self.queuePreviewUpdate();
                        self.showToast('Block removed', 'warning');
                    },
                    'Remove Block',
                    true
                );
            },

            toggleVisibility: function(block) {
                block.is_visible = !block.is_visible;
                this.isDirty = true;
                this.queuePreviewUpdate();
                this.showToast(block.is_visible ? 'Block visible' : 'Block hidden', 'info');
            },

            switchLanguage: function(lang) {
                this.activeTab = lang;
                this.queuePreviewUpdate();
            },

            setLimit: function(block, val) {
                var parsed = parseInt(val, 10) || 0;
                this.content(block, 'en').limit = parsed;
                this.content(block, 'id').limit = parsed;
                this.isDirty = true;
                this.queuePreviewUpdate();
            },

            toggleShowAll: function(block, checked) {
                var isChecked = Boolean(checked);
                this.content(block, 'en').show_all = isChecked;
                this.content(block, 'id').show_all = isChecked;
                if (isChecked) {
                    this.content(block, 'en').limit = 0;
                    this.content(block, 'id').limit = 0;
                }
                this.isDirty = true;
                this.queuePreviewUpdate();
            },

            content: function(block, locale) {
                if (typeof block.content === 'string') {
                    try {
                        block.content = JSON.parse(block.content);
                        if (typeof block.content === 'string') block.content = JSON.parse(block.content);
                    } catch (e) {
                        block.content = {};
                    }
                }
                block.content = block.content || {};
                block.content[locale] = block.content[locale] || {};
                return block.content[locale];
            },

            items: function(block, locale) {
                var c = this.content(block, locale);
                if (typeof c.items === 'string') {
                    try {
                        c.items = JSON.parse(c.items || '[]');
                    } catch (e) {
                        c.items = [];
                    }
                }
                c.items = c.items || [];
                return c.items;
            },

            addItem: function(block, locale, item) {
                this.items(block, locale).push(item);
                this.isDirty = true;
                this.queuePreviewUpdate();
            },

            removeItem: function(block, locale, itemIndex) {
                this.items(block, locale).splice(itemIndex, 1);
                this.isDirty = true;
                this.queuePreviewUpdate();
            },

            autoTranslate: function(block, sourceLoc, fieldName) {
                var targetLoc = sourceLoc === 'en' ? 'id' : 'en';
                var val = this.content(block, sourceLoc)[fieldName] || '';
                if (!val) return;
                
                this.content(block, targetLoc)[fieldName] = val;
                this.isDirty = true;
                this.queuePreviewUpdate();
                this.showToast('Synced ' + fieldName + ' to ' + targetLoc.toUpperCase(), 'info');
            },

            scrollToBlock: function(blockId) {
                var el = document.getElementById('block-card-' + blockId);
                if (el) {
                    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    el.classList.add('ring-2', 'ring-indigo-500');
                    setTimeout(function() { el.classList.remove('ring-2', 'ring-indigo-500'); }, 1500);
                }
            },

            openMediaPicker: function(blockIndex, loc, targetPath) {
                this.activeMediaTarget = { blockIndex: blockIndex, loc: loc, targetPath: targetPath };
                this.fetchMediaFiles();
                this.mediaModalOpen = true;
            },

            fetchMediaFiles: async function() {
                try {
                    var res = await fetch(this.mediaUrl, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } });
                    if (res.ok) {
                        var data = await res.json();
                        if (Array.isArray(data) && data.length > 0) {
                            this.mediaFiles = data;
                            return;
                        }
                    }
                } catch (e) {
                    // Ignore fallback to defaults
                }

                // Fallback gallery items
                this.mediaFiles = [
                    { name: 'coffee-1', url: 'https://images.unsplash.com/photo-1587734195503-904fca47e0e9?q=80&w=800&auto=format&fit=crop' },
                    { name: 'coffee-2', url: 'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?q=80&w=800&auto=format&fit=crop' },
                    { name: 'coffee-3', url: 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?q=80&w=800&auto=format&fit=crop' }
                ];
            },

            selectMedia: function(url) {
                if (!this.activeMediaTarget) return;
                var blockIndex = this.activeMediaTarget.blockIndex;
                var loc = this.activeMediaTarget.loc;
                var targetPath = this.activeMediaTarget.targetPath;
                var block = this.blocks[blockIndex];
                if (!block) return;

                if (targetPath.indexOf('items.') === 0) {
                    var parts = targetPath.split('.');
                    var itemIdx = parseInt(parts[1], 10);
                    var keyName = parts[2];
                    var itemList = this.items(block, loc);
                    if (itemList[itemIdx]) itemList[itemIdx][keyName] = url;
                } else {
                    this.content(block, loc)[targetPath] = url;
                }

                this.mediaModalOpen = false;
                this.isDirty = true;
                this.queuePreviewUpdate();
                this.showToast('Image selected', 'success');
            },

            uploadMediaFile: async function(e) {
                var file = e.target.files[0];
                if (!file) return;
                var formData = new FormData();
                formData.append('file', file);
                if (this.csrfToken) formData.append('_token', this.csrfToken);

                try {
                    var res = await fetch(this.mediaUploadUrl, { method: 'POST', body: formData, credentials: 'same-origin' });
                    var data = await res.json();
                    if (data.url) {
                        this.mediaFiles.unshift({ name: data.name || 'Uploaded Image', url: data.url });
                        this.selectMedia(data.url);
                        this.showToast('Image uploaded', 'success');
                    } else {
                        this.showToast('Failed to upload image', 'error');
                    }
                } catch (err) {
                    this.showToast('Failed to upload image', 'error');
                }
            },

            applyPreset: function(presetKey) {
                var self = this;
                var applyLogic = function() {
                    var presets = {
                        product_landing: ['hero', 'stats', 'process_steps', 'text_with_stats', 'cta'],
                        export_hub: ['hero', 'export_map', 'origins', 'faq', 'cta'],
                        news_story: ['hero', 'text', 'articles', 'testimonials']
                    };

                    var types = presets[presetKey] || [];
                    types.forEach(function(t) { self.addBlock(t); });
                    self.presetModalOpen = false;
                    self.showToast('Page preset applied', 'success');
                };

                if (this.blocks.length > 0) {
                    this.askConfirm(
                        'Apply Layout Preset',
                        'Applying a preset will append layout blocks to your existing page content. Do you wish to continue?',
                        function() {
                            applyLogic();
                        },
                        'Apply Preset',
                        false
                    );
                } else {
                    applyLogic();
                }
            },

            showToast: function(message, type, duration) {
                if (type === undefined) type = 'success';
                if (duration === undefined) duration = 3500;
                if (this.toast.timeout) clearTimeout(this.toast.timeout);
                var self = this;
                this.toast.open = true;
                this.toast.message = message;
                this.toast.type = type;
                this.toast.timeout = setTimeout(function() {
                    self.toast.open = false;
                }, duration);
            },

            queuePreviewUpdate: function() {
                var self = this;
                clearTimeout(this.syncDebounceTimer);
                this.isSyncing = true;
                this.syncError = false;
                this.syncDebounceTimer = setTimeout(function() {
                    self.updateIframePreview();
                }, 400);
            },

            updateIframePreview: async function() {
                var iframe = this.$refs.previewIframe;
                if (!iframe) return;

                if (this.abortController) {
                    this.abortController.abort();
                }
                this.abortController = new AbortController();

                var payload = {
                    title: this.pageTitle,
                    locale: this.activeTab === 'split' ? 'en' : this.activeTab,
                    blocks: this.blocks
                };

                try {
                    var response = await fetch(this.previewUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Accept': 'text/html'
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify(payload),
                        signal: this.abortController.signal
                    });

                    if (!response.ok) {
                        throw new Error('Preview sync failed with status ' + response.status);
                    }

                    var html = await response.text();
                    var doc = iframe.contentDocument || iframe.contentWindow.document;
                    doc.open();
                    doc.write(html);
                    doc.close();
                    this.syncError = false;
                } catch (err) {
                    if (err.name === 'AbortError') {
                        return;
                    }
                    this.syncError = true;
                    console.error('Preview sync error:', err);
                } finally {
                    this.isSyncing = false;
                }
            },

            init: function() {
                var self = this;
                this.$nextTick(function() {
                    var container = document.getElementById('block-list-container');
                    if (container && typeof window.Sortable !== 'undefined') {
                        self.sortableInstance = window.Sortable.create(container, {
                            handle: '.drag-handle',
                            animation: 150,
                            forceFallback: false,
                            onChoose: function(evt) {
                                if (evt.item) evt.item.setAttribute('x-ignore', '');
                            },
                            onUnchoose: function(evt) {
                                if (evt.item) evt.item.removeAttribute('x-ignore');
                            },
                            onClone: function(evt) {
                                if (evt.clone) evt.clone.setAttribute('x-ignore', '');
                            },
                            onEnd: function(evt) {
                                if (evt.item) evt.item.removeAttribute('x-ignore');
                                if (evt.oldIndex === evt.newIndex) return;

                                var parent = evt.from;
                                var item = evt.item;
                                if (parent && item) {
                                    var siblings = Array.from(parent.children);
                                    if (evt.oldIndex < evt.newIndex) {
                                        parent.insertBefore(item, siblings[evt.oldIndex]);
                                    } else {
                                        parent.insertBefore(item, siblings[evt.oldIndex + 1] || null);
                                    }
                                }

                                var moved = self.blocks.splice(evt.oldIndex, 1)[0];
                                self.blocks.splice(evt.newIndex, 0, moved);
                                self.blocks.forEach(function(b, idx) { b.order = idx; });
                                self.isDirty = true;
                                self.queuePreviewUpdate();
                            }
                        });
                    }

                    self.keydownHandler = function(e) {
                        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                            e.preventDefault();
                            var form = document.getElementById('page-editor-form');
                            if (form) form.requestSubmit();
                        }
                    };
                    window.addEventListener('keydown', self.keydownHandler);

                    self.updateIframePreview();
                });
            },

            destroy: function() {
                if (this.keydownHandler) {
                    window.removeEventListener('keydown', this.keydownHandler);
                    this.keydownHandler = null;
                }
                if (this.sortableInstance) {
                    this.sortableInstance.destroy();
                    this.sortableInstance = null;
                }
                if (this.abortController) {
                    this.abortController.abort();
                    this.abortController = null;
                }
                if (this.syncDebounceTimer) {
                    clearTimeout(this.syncDebounceTimer);
                }
                if (this.toast.timeout) {
                    clearTimeout(this.toast.timeout);
                }
            }
        };
    }

    window.blockEditor = blockEditor;
    if (window.Alpine) {
        window.Alpine.data('blockEditor', blockEditor);
    } else {
        document.addEventListener('alpine:init', function() {
            if (window.Alpine) {
                window.Alpine.data('blockEditor', blockEditor);
            }
        });
    }
</script>
