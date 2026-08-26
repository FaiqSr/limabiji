{{-- resources/views/components/store/quiz-modal.blade.php --}}
@php
    $locale = app()->getLocale();
@endphp

{{-- Quiz Modal Backdrop --}}
<div id="quiz-modal-overlay"
    class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-[80] opacity-0 pointer-events-none transition-opacity duration-300 flex items-center justify-center p-4 sm:p-6">

    {{-- Modal Box (Clean White Theme) --}}
    <div id="quiz-modal-card"
        class="w-full max-w-2xl bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden scale-95 opacity-0 transition-all duration-300 flex flex-col max-h-[90vh] text-slate-800">

        {{-- Header --}}
        <div class="px-6 py-5 border-b border-slate-200 flex items-center justify-between bg-slate-50/80">
            <div class="flex items-center gap-3">
                <div>
                    <h3 class="font-display text-xl sm:text-2xl text-slate-900 uppercase tracking-wide leading-none">
                        {{ __('store.quiz_modal_title') }}
                    </h3>
                </div>
            </div>
            <button id="quiz-modal-close" aria-label="Close quiz"
                class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:text-slate-900 hover:border-primary/40 flex items-center justify-center transition-colors cursor-pointer active:scale-95 shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Step Progress Bar --}}
        <div id="quiz-progress-wrapper" class="w-full bg-slate-100 h-1.5 overflow-hidden">
            <div id="quiz-progress-bar" class="bg-primary h-full transition-all duration-300 w-1/3"></div>
        </div>

        {{-- Quiz Body --}}
        <div class="p-6 sm:p-8 overflow-y-auto flex-1 bg-white">

            {{-- Step 1: Brew Method --}}
            <div id="quiz-step-1" class="quiz-step">
                <div class="mb-6">
                    <span class="text-xs font-mono font-bold text-primary uppercase tracking-widest block mb-1">Step 1 of 3</span>
                    <h4 class="font-display text-2xl sm:text-3xl text-slate-900 uppercase">{{ __('store.quiz_step_1_title') }}</h4>
                    <p class="text-slate-500 text-xs sm:text-sm mt-1">{{ __('store.quiz_step_1_sub') }}</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <button type="button" data-quiz-choice="brew" data-value="v60"
                        class="quiz-choice-btn p-4 rounded-xl bg-slate-50 border border-slate-200/90 hover:border-primary/50 text-left transition-all group flex items-start gap-3 active:scale-[0.98] shadow-xs">
                        <div class="w-8 h-8 rounded-lg bg-primary/10 border border-primary/20 flex items-center justify-center text-primary shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900 group-hover:text-primary transition-colors">{{ __('store.quiz_brew_v60') }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">Filter, clean, high clarity</p>
                        </div>
                    </button>

                    <button type="button" data-quiz-choice="brew" data-value="espresso"
                        class="quiz-choice-btn p-4 rounded-xl bg-slate-50 border border-slate-200/90 hover:border-primary/50 text-left transition-all group flex items-start gap-3 active:scale-[0.98] shadow-xs">
                        <div class="w-8 h-8 rounded-lg bg-primary/10 border border-primary/20 flex items-center justify-center text-primary shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900 group-hover:text-primary transition-colors">{{ __('store.quiz_brew_espresso') }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">Rich pressure, thick crema</p>
                        </div>
                    </button>

                    <button type="button" data-quiz-choice="brew" data-value="tubruk"
                        class="quiz-choice-btn p-4 rounded-xl bg-slate-50 border border-slate-200/90 hover:border-primary/50 text-left transition-all group flex items-start gap-3 active:scale-[0.98] shadow-xs">
                        <div class="w-8 h-8 rounded-lg bg-primary/10 border border-primary/20 flex items-center justify-center text-primary shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900 group-hover:text-primary transition-colors">{{ __('store.quiz_brew_tubruk') }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">Traditional steep method</p>
                        </div>
                    </button>

                    <button type="button" data-quiz-choice="brew" data-value="cold_brew"
                        class="quiz-choice-btn p-4 rounded-xl bg-slate-50 border border-slate-200/90 hover:border-primary/50 text-left transition-all group flex items-start gap-3 active:scale-[0.98] shadow-xs">
                        <div class="w-8 h-8 rounded-lg bg-primary/10 border border-primary/20 flex items-center justify-center text-primary shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900 group-hover:text-primary transition-colors">{{ __('store.quiz_brew_cold_brew') }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">Smooth, low acid, refreshing</p>
                        </div>
                    </button>

                    <button type="button" data-quiz-choice="brew" data-value="french_press"
                        class="quiz-choice-btn p-4 rounded-xl bg-slate-50 border border-slate-200/90 hover:border-primary/50 text-left transition-all group flex items-start gap-3 active:scale-[0.98] shadow-xs sm:col-span-2">
                        <div class="w-8 h-8 rounded-lg bg-primary/10 border border-primary/20 flex items-center justify-center text-primary shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900 group-hover:text-primary transition-colors">{{ __('store.quiz_brew_french_press') }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">Heavy body, immersion brewing</p>
                        </div>
                    </button>
                </div>
            </div>

            {{-- Step 2: Flavor Profile --}}
            <div id="quiz-step-2" class="quiz-step hidden">
                <div class="mb-6">
                    <span class="text-xs font-mono font-bold text-primary uppercase tracking-widest block mb-1">Step 2 of 3</span>
                    <h4 class="font-display text-2xl sm:text-3xl text-slate-900 uppercase">{{ __('store.quiz_step_2_title') }}</h4>
                    <p class="text-slate-500 text-xs sm:text-sm mt-1">{{ __('store.quiz_step_2_sub') }}</p>
                </div>

                <div class="space-y-3.5">
                    <button type="button" data-quiz-choice="flavor" data-value="fruity"
                        class="w-full quiz-choice-btn p-4 rounded-xl bg-slate-50 border border-slate-200/90 hover:border-primary/50 text-left transition-all group flex items-start gap-3.5 active:scale-[0.98] shadow-xs">
                        <div class="w-10 h-10 rounded-lg bg-primary/10 border border-primary/20 flex items-center justify-center text-primary shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900 group-hover:text-primary transition-colors">{{ __('store.quiz_flavor_fruity') }}</p>
                            <p class="text-xs text-slate-500 mt-1">Lively acidity, floral jasmine, juicy stone fruit</p>
                        </div>
                    </button>

                    <button type="button" data-quiz-choice="flavor" data-value="sweet"
                        class="w-full quiz-choice-btn p-4 rounded-xl bg-slate-50 border border-slate-200/90 hover:border-primary/50 text-left transition-all group flex items-start gap-3.5 active:scale-[0.98] shadow-xs">
                        <div class="w-10 h-10 rounded-lg bg-secondary/10 border border-secondary/20 flex items-center justify-center text-secondary shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900 group-hover:text-secondary transition-colors">{{ __('store.quiz_flavor_sweet') }}</p>
                            <p class="text-xs text-slate-500 mt-1">Harmonious honey, brown sugar, peach, butterscotch</p>
                        </div>
                    </button>

                    <button type="button" data-quiz-choice="flavor" data-value="bold"
                        class="w-full quiz-choice-btn p-4 rounded-xl bg-slate-50 border border-slate-200/90 hover:border-primary/50 text-left transition-all group flex items-start gap-3.5 active:scale-[0.98] shadow-xs">
                        <div class="w-10 h-10 rounded-lg bg-primary/10 border border-primary/20 flex items-center justify-center text-primary shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900 group-hover:text-primary transition-colors">{{ __('store.quiz_flavor_bold') }}</p>
                            <p class="text-xs text-slate-500 mt-1">Dark cocoa, toasted hazelnut, molasses, heavy mouthfeel</p>
                        </div>
                    </button>
                </div>
            </div>

            {{-- Step 3: Roast Level --}}
            <div id="quiz-step-3" class="quiz-step hidden">
                <div class="mb-6">
                    <span class="text-xs font-mono font-bold text-primary uppercase tracking-widest block mb-1">Step 3 of 3</span>
                    <h4 class="font-display text-2xl sm:text-3xl text-slate-900 uppercase">{{ __('store.quiz_step_3_title') }}</h4>
                    <p class="text-slate-500 text-xs sm:text-sm mt-1">{{ __('store.quiz_step_3_sub') }}</p>
                </div>

                <div class="space-y-3.5">
                    <button type="button" data-quiz-choice="roast" data-value="light"
                        class="w-full quiz-choice-btn p-4 rounded-xl bg-slate-50 border border-slate-200/90 hover:border-primary/50 text-left transition-all group flex items-start gap-3.5 active:scale-[0.98] shadow-xs">
                        <div class="w-10 h-10 rounded-lg bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-600 shrink-0 font-display text-lg">
                            L
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900 group-hover:text-primary transition-colors">{{ __('store.quiz_roast_light') }}</p>
                            <p class="text-xs text-slate-500 mt-1">Cinnamon hue, bright sparkling cup</p>
                        </div>
                    </button>

                    <button type="button" data-quiz-choice="roast" data-value="medium"
                        class="w-full quiz-choice-btn p-4 rounded-xl bg-slate-50 border border-slate-200/90 hover:border-primary/50 text-left transition-all group flex items-start gap-3.5 active:scale-[0.98] shadow-xs">
                        <div class="w-10 h-10 rounded-lg bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-600 shrink-0 font-display text-lg">
                            M
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900 group-hover:text-primary transition-colors">{{ __('store.quiz_roast_medium') }}</p>
                            <p class="text-xs text-slate-500 mt-1">Balanced sweetness and natural acidity</p>
                        </div>
                    </button>

                    <button type="button" data-quiz-choice="roast" data-value="dark"
                        class="w-full quiz-choice-btn p-4 rounded-xl bg-slate-50 border border-slate-200/90 hover:border-primary/50 text-left transition-all group flex items-start gap-3.5 active:scale-[0.98] shadow-xs">
                        <div class="w-10 h-10 rounded-lg bg-rose-500/10 border border-rose-500/20 flex items-center justify-center text-rose-600 shrink-0 font-display text-lg">
                            D
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900 group-hover:text-primary transition-colors">{{ __('store.quiz_roast_dark') }}</p>
                            <p class="text-xs text-slate-500 mt-1">Deep roast oils, smokey chocolate, low acidity</p>
                        </div>
                    </button>
                </div>
            </div>

            {{-- Step 4: Results --}}
            <div id="quiz-step-results" class="quiz-step hidden">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-primary/10 border border-primary/20 text-primary mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h4 class="font-display text-2xl sm:text-3xl text-slate-900 uppercase">{{ __('store.quiz_results_title') }}</h4>
                    <p class="text-slate-500 text-xs sm:text-sm mt-1 max-w-md mx-auto">{{ __('store.quiz_results_sub') }}</p>
                </div>

                <div id="quiz-results-container" class="space-y-4 mb-6">
                    {{-- Dynamically populated --}}
                </div>

                <div class="flex items-center justify-center">
                    <button type="button" id="quiz-retake-btn"
                        class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 text-xs font-semibold uppercase tracking-wider transition-all active:scale-95 cursor-pointer flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>{{ __('store.quiz_retake') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    window.LimaBijiQuiz = {
        currentStep: 1,
        answers: {
            brew: null,
            flavor: null,
            roast: null
        },

        init() {
            this.bindEvents();
        },

        bindEvents() {
            const overlay = document.getElementById('quiz-modal-overlay');
            const closeBtn = document.getElementById('quiz-modal-close');
            const card = document.getElementById('quiz-modal-card');
            const retakeBtn = document.getElementById('quiz-retake-btn');

            if (overlay) overlay.addEventListener('click', (e) => {
                if (e.target === overlay) this.close();
            });
            if (closeBtn) closeBtn.addEventListener('click', () => this.close());
            if (retakeBtn) retakeBtn.addEventListener('click', () => this.reset());

            // Open quiz modal triggers
            document.querySelectorAll('[data-open-quiz]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.open();
                });
            });

            // Choice button clicks
            document.querySelectorAll('.quiz-choice-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const type = btn.getAttribute('data-quiz-choice');
                    const value = btn.getAttribute('data-value');
                    this.selectChoice(type, value);
                });
            });
        },

        open() {
            const overlay = document.getElementById('quiz-modal-overlay');
            const card = document.getElementById('quiz-modal-card');
            if (overlay && card) {
                overlay.classList.remove('opacity-0', 'pointer-events-none');
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            }
        },

        close() {
            const overlay = document.getElementById('quiz-modal-overlay');
            const card = document.getElementById('quiz-modal-card');
            if (overlay && card) {
                overlay.classList.add('opacity-0', 'pointer-events-none');
                card.classList.add('scale-95', 'opacity-0');
                card.classList.remove('scale-100', 'opacity-100');
            }
        },

        selectChoice(type, value) {
            this.answers[type] = value;

            if (this.currentStep === 1) {
                this.goToStep(2);
            } else if (this.currentStep === 2) {
                this.goToStep(3);
            } else if (this.currentStep === 3) {
                this.submitQuiz();
            }
        },

        goToStep(step) {
            this.currentStep = step;
            const progress = document.getElementById('quiz-progress-bar');
            if (progress) {
                progress.style.width = (step * 33.33) + '%';
            }

            document.querySelectorAll('.quiz-step').forEach(el => el.classList.add('hidden'));

            const target = document.getElementById(`quiz-step-${step}`);
            if (target) {
                target.classList.remove('hidden');
            }
        },

        async submitQuiz() {
            const progress = document.getElementById('quiz-progress-bar');
            if (progress) progress.style.width = '100%';

            document.querySelectorAll('.quiz-step').forEach(el => el.classList.add('hidden'));
            const resultsStep = document.getElementById('quiz-step-results');
            const container = document.getElementById('quiz-results-container');

            if (container) {
                container.innerHTML = `
                    <div class="text-center py-8 text-slate-500 text-sm">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-primary inline" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        Matching flavor profiles with our roastery inventory...
                    </div>
                `;
            }

            if (resultsStep) resultsStep.classList.remove('hidden');

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const res = await fetch('{{ route("store.quiz.recommend") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || ''
                    },
                    body: JSON.stringify(this.answers)
                });
                const data = await res.json();
                if (data.status === 'success') {
                    this.renderResults(data.recommendations);
                }
            } catch (err) {
                console.error('Quiz matching error:', err);
                if (container) {
                    container.innerHTML = '<p class="text-center text-rose-500 text-sm">Failed to generate matches. Please retry.</p>';
                }
            }
        },

        renderResults(items) {
            const container = document.getElementById('quiz-results-container');
            if (!container) return;

            if (!items || items.length === 0) {
                container.innerHTML = '<p class="text-center text-slate-500 text-sm">No matches found.</p>';
                return;
            }

            container.innerHTML = items.map(item => `
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 hover:border-primary/40 transition-all flex flex-col sm:flex-row items-center gap-4 shadow-xs">
                    <img src="${item.image}" alt="${item.name}" class="w-20 h-20 sm:w-24 sm:h-24 object-cover rounded-xl border border-slate-200 shrink-0">
                    <div class="flex-1 text-center sm:text-left min-w-0">
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mb-1">
                            <span class="px-2 py-0.5 rounded-md bg-primary/10 border border-primary/20 text-primary text-[10px] font-mono font-bold uppercase">
                                ${item.match_percentage}% {{ __('store.quiz_match_badge') }}
                            </span>
                            <span class="text-[10px] text-slate-500 font-mono">${item.origin || ''}</span>
                        </div>
                        <h5 class="font-display text-lg sm:text-xl text-slate-900 uppercase tracking-tight truncate">${item.name}</h5>
                        <p class="text-slate-500 text-xs truncate mt-0.5">${(item.tasting_notes || []).join(' • ')}</p>
                        <p class="font-semibold text-primary text-sm mt-1.5">${item.formatted_price} <span class="text-[10px] text-slate-400 font-normal">/ 200g</span></p>
                    </div>
                    <div class="shrink-0 flex items-center gap-2 w-full sm:w-auto">
                        <a href="${item.url}" class="flex-1 sm:flex-none text-center px-4 py-2 rounded-lg bg-white hover:bg-slate-100 border border-slate-200 text-slate-800 text-xs font-semibold transition-colors shadow-xs">
                            {{ __('store.view_detail') }}
                        </a>
                        <button onclick="LimaBijiCart.addItem(${item.id}, '200g', 'whole_bean', 1); LimaBijiQuiz.close();"
                            class="flex-1 sm:flex-none px-4 py-2 rounded-lg bg-primary hover:bg-primary-hover text-white text-xs font-semibold transition-all active:scale-95 shadow-sm cursor-pointer">
                            {{ __('store.add_to_cart_quick') }}
                        </button>
                    </div>
                </div>
            `).join('');
        },

        reset() {
            this.answers = { brew: null, flavor: null, roast: null };
            this.goToStep(1);
        }
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => window.LimaBijiQuiz.init());
    } else {
        window.LimaBijiQuiz.init();
    }
})();
</script>
