{{-- resources/views/components/store/cart-drawer.blade.php --}}
@php
    $locale = app()->getLocale();
@endphp

{{-- Cart Drawer Backdrop --}}
<div id="cart-drawer-overlay"
    class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-[70] opacity-0 pointer-events-none transition-opacity duration-300">
</div>

{{-- Cart Slide-over Panel (Clean White Theme) --}}
<div id="cart-drawer"
    class="fixed top-0 right-0 bottom-0 w-full sm:w-[420px] max-w-full bg-white border-l border-slate-200 shadow-2xl z-[75] translate-x-full transition-transform duration-300 ease-out flex flex-col text-slate-800">

    {{-- Drawer Header --}}
    <div class="px-6 py-5 border-b border-slate-200 flex items-center justify-between bg-slate-50/80">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-primary/10 border border-primary/20 flex items-center justify-center text-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <h3 class="font-display text-xl sm:text-2xl text-slate-900 uppercase tracking-wide">
                {{ __('store.cart_title') }}
            </h3>
        </div>
        <button id="cart-drawer-close" aria-label="Close cart"
            class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:text-slate-900 hover:border-primary/40 flex items-center justify-center transition-colors cursor-pointer active:scale-95 shadow-xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Drawer Body (Scrollable List) --}}
    <div id="cart-drawer-items" class="flex-1 overflow-y-auto p-6 space-y-3.5 bg-white">
        {{-- Filled dynamically via JS --}}
        <div id="cart-loading-indicator" class="flex items-center justify-center py-16 text-slate-500 text-sm">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            Loading cart...
        </div>
    </div>

    {{-- Empty State (Hidden when items exist) --}}
    <div id="cart-empty-state" class="hidden flex-1 flex-col items-center justify-center p-8 text-center bg-white">
        <div class="w-16 h-16 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
        </div>
        <h4 class="font-display text-2xl text-slate-900 uppercase mb-2">{{ __('store.cart_empty') }}</h4>
        <p class="text-slate-500 text-xs leading-relaxed max-w-xs mb-6">{{ __('store.cart_empty_sub') }}</p>
        <a href="{{ route('store.index') }}" id="cart-empty-browse-btn"
            class="px-5 py-2.5 rounded-xl bg-primary hover:bg-primary-hover text-white text-xs font-semibold uppercase tracking-wider transition-all duration-200 active:scale-95 shadow-sm">
            {{ __('store.cart_start_shopping') }}
        </a>
    </div>

    {{-- Drawer Footer (Checkout Actions) --}}
    <div id="cart-drawer-footer" class="p-6 border-t border-slate-200 bg-slate-50/90 space-y-4">
        <div class="flex items-center justify-between text-sm">
            <span class="text-slate-500 uppercase text-xs font-bold tracking-wider">{{ __('store.cart_subtotal') }}</span>
            <span id="cart-drawer-subtotal" class="font-display text-2xl text-slate-900">Rp 0</span>
        </div>
        <p class="text-[11px] text-slate-500 leading-tight">
            {{ __('store.cart_shipping_notice') }}
        </p>
        <a href="{{ route('store.checkout') }}" id="cart-drawer-checkout-btn"
            class="w-full flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-primary hover:bg-primary-hover text-white font-semibold text-sm transition-all duration-200 shadow-md active:scale-[0.98] cursor-pointer">
            <span>{{ __('store.cart_checkout_btn') }}</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
        </a>
    </div>
</div>

{{-- Toast Feedback Notification --}}
<div id="store-toast"
    class="fixed bottom-6 right-6 z-[99] max-w-sm bg-slate-900/95 backdrop-blur-md border border-slate-700 text-white px-5 py-3.5 rounded-xl shadow-2xl flex items-center gap-3 translate-y-20 opacity-0 pointer-events-none transition-all duration-300">
    <div class="w-6 h-6 rounded-full bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center text-emerald-400 shrink-0">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
        </svg>
    </div>
    <span id="store-toast-message" class="text-xs font-medium leading-snug">Item added to cart</span>
</div>

<script>
(function() {
    window.LimaBijiCart = {
        isOpen: false,

        init() {
            this.bindEvents();
            this.fetchCart();
        },

        bindEvents() {
            const overlay = document.getElementById('cart-drawer-overlay');
            const closeBtn = document.getElementById('cart-drawer-close');
            const emptyBrowseBtn = document.getElementById('cart-empty-browse-btn');

            if (overlay) overlay.addEventListener('click', () => this.close());
            if (closeBtn) closeBtn.addEventListener('click', () => this.close());
            if (emptyBrowseBtn) emptyBrowseBtn.addEventListener('click', () => this.close());

            // Listen for open drawer clicks
            document.querySelectorAll('[data-open-cart]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.open();
                });
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.isOpen) {
                    this.close();
                }
            });
        },

        open() {
            const drawer = document.getElementById('cart-drawer');
            const overlay = document.getElementById('cart-drawer-overlay');
            if (drawer && overlay) {
                overlay.classList.remove('opacity-0', 'pointer-events-none');
                drawer.classList.remove('translate-x-full');
                this.isOpen = true;
                this.fetchCart();
            }
        },

        close() {
            const drawer = document.getElementById('cart-drawer');
            const overlay = document.getElementById('cart-drawer-overlay');
            if (drawer && overlay) {
                overlay.classList.add('opacity-0', 'pointer-events-none');
                drawer.classList.add('translate-x-full');
                this.isOpen = false;
            }
        },

        async fetchCart() {
            try {
                const res = await fetch('{{ route("cart.index") }}', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.status === 'success') {
                    this.render(data);
                }
            } catch (err) {
                console.error('Failed to fetch cart:', err);
            }
        },

        async addItem(productId, weight, grindSize, quantity = 1) {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const res = await fetch('{{ route("cart.add") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || ''
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        weight: weight,
                        grind_size: grindSize,
                        quantity: quantity
                    })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    this.render(data);
                    this.showToast(data.message || '{{ __("store.cart_added_success") }}');
                    this.open();
                } else {
                    alert(data.message || 'Failed to add item to cart.');
                }
            } catch (err) {
                console.error('Error adding to cart:', err);
            }
        },

        async updateQty(key, quantity) {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const res = await fetch('{{ route("cart.update") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || ''
                    },
                    body: JSON.stringify({ key, quantity })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    this.render(data);
                }
            } catch (err) {
                console.error('Error updating quantity:', err);
            }
        },

        async removeItem(key) {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const res = await fetch('{{ route("cart.remove") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || ''
                    },
                    body: JSON.stringify({ key })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    this.render(data);
                    this.showToast(data.message || '{{ __("store.cart_removed_success") }}');
                }
            } catch (err) {
                console.error('Error removing item:', err);
            }
        },

        render(data) {
            const itemsContainer = document.getElementById('cart-drawer-items');
            const emptyState = document.getElementById('cart-empty-state');
            const footer = document.getElementById('cart-drawer-footer');
            const subtotalEl = document.getElementById('cart-drawer-subtotal');
            const badges = document.querySelectorAll('.cart-badge-count');

            // Update badge counts
            badges.forEach(b => {
                b.textContent = data.item_count || 0;
                if (data.item_count > 0) {
                    b.classList.remove('hidden');
                } else {
                    b.classList.add('hidden');
                }
            });

            if (subtotalEl) {
                subtotalEl.textContent = data.formatted_subtotal || 'Rp 0';
            }

            const items = Object.values(data.cart || {});

            if (items.length === 0) {
                if (itemsContainer) itemsContainer.classList.add('hidden');
                if (emptyState) emptyState.classList.remove('hidden');
                if (footer) footer.classList.add('opacity-40', 'pointer-events-none');
                return;
            }

            if (itemsContainer) itemsContainer.classList.remove('hidden');
            if (emptyState) emptyState.classList.add('hidden');
            if (footer) footer.classList.remove('opacity-40', 'pointer-events-none');

            const currentLocale = '{{ $locale }}';
            const html = items.map(item => {
                const displayName = (currentLocale === 'id' && item.name_id) ? item.name_id : item.name;
                const formattedPrice = 'Rp ' + Number(item.unit_price).toLocaleString('id-ID');
                const grindLabel = this.formatGrind(item.grind_size);

                return `
                    <div class="flex items-start gap-3.5 p-3.5 rounded-xl bg-slate-50 border border-slate-200/90 hover:border-slate-300 transition-all">
                        <img src="${item.image || '{{ asset('assets/images/limabiji/toko.webp') }}'}" alt="${displayName}" class="w-16 h-16 object-cover rounded-lg border border-slate-200 shrink-0">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <h4 class="font-display text-sm text-slate-900 uppercase tracking-tight truncate leading-tight">${displayName}</h4>
                                <button onclick="LimaBijiCart.removeItem('${item.key}')" class="text-slate-400 hover:text-rose-600 p-1 transition-colors" title="Remove">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-[11px] font-mono text-primary font-bold bg-primary/10 border border-primary/20 px-2 py-0.5 rounded-md">${item.weight}</span>
                                <span class="text-[11px] text-slate-500 truncate">• ${grindLabel}</span>
                            </div>
                            <div class="flex items-center justify-between mt-3 pt-2 border-t border-slate-200/60">
                                <span class="text-xs font-semibold text-slate-900">${formattedPrice}</span>
                                <div class="flex items-center gap-1.5 bg-white border border-slate-200 rounded-lg p-0.5 shadow-xs">
                                    <button onclick="LimaBijiCart.updateQty('${item.key}', ${item.quantity - 1})" class="w-6 h-6 rounded flex items-center justify-center text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors active:scale-95 cursor-pointer">-</button>
                                    <span class="text-xs font-mono text-slate-900 font-medium px-1.5">${item.quantity}</span>
                                    <button onclick="LimaBijiCart.updateQty('${item.key}', ${item.quantity + 1})" class="w-6 h-6 rounded flex items-center justify-center text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors active:scale-95 cursor-pointer">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            if (itemsContainer) {
                itemsContainer.innerHTML = html;
            }
        },

        formatGrind(grind) {
            const labels = {
                'whole_bean': 'Whole Bean',
                'coarse': 'Coarse',
                'medium': 'Medium',
                'fine': 'Fine'
            };
            return labels[grind] || grind;
        },

        showToast(message) {
            const toast = document.getElementById('store-toast');
            const msgEl = document.getElementById('store-toast-message');
            if (toast && msgEl) {
                msgEl.textContent = message;
                toast.classList.remove('translate-y-20', 'opacity-0', 'pointer-events-none');
                setTimeout(() => {
                    toast.classList.add('translate-y-20', 'opacity-0', 'pointer-events-none');
                }, 3500);
            }
        }
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => window.LimaBijiCart.init());
    } else {
        window.LimaBijiCart.init();
    }
})();
</script>
