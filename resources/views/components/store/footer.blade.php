{{-- resources/views/components/store/footer.blade.php --}}
@php
    $locale = app()->getLocale();
@endphp

<footer class="bg-[#fafaf9] text-slate-600 pt-16 pb-10">
    <div class="container mx-auto px-5 lg:px-8 max-w-7xl">


        {{-- Footer Main Columns --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-10 pb-16 border-b border-slate-100">
            {{-- Brand Description --}}
            <div class="lg:col-span-4 space-y-4">
                <a href="{{ route('store.index') }}" class="inline-block">
                    <img src="{{ asset('assets/images/logo/logo-lima-biji.webp') }}"
                        alt="Lima Biji Roastery" class="h-10 w-auto">
                </a>
                <p class="text-slate-600 text-sm leading-relaxed max-w-sm">
                    {{ $locale === 'id'
                        ? 'Roastery kopi spesialti berbasis agriteknologi. Menghadirkan biji kopi kualitas ekspor langsung dari petani mitra ke seduhan harian Anda.'
                        : 'Agritech specialty coffee roastery producing ethical enzymatic civet and micro-lot beans directly from origin to your home brewer.' }}
                </p>
                <div class="text-xs text-slate-400 space-y-1 font-mono">
                    <p>Lima Biji Roastery Hub</p>
                    <p>Bogor, West Java, Indonesia</p>
                </div>
            </div>

            {{-- Catalog Navigation --}}
            <div class="lg:col-span-3 space-y-3">
                <h5 class="font-bold text-slate-900 text-xs uppercase tracking-wider">{{ $locale === 'id' ? 'Kategori Biji Kopi' : 'Coffee Categories' }}</h5>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('store.index', ['category' => 'all']) }}" class="text-slate-600 hover:text-primary transition-colors">{{ __('store.filter_all') }}</a></li>
                    <li><a href="{{ route('store.index', ['category' => 'arabika']) }}" class="text-slate-600 hover:text-primary transition-colors">{{ __('store.filter_arabika') }}</a></li>
                    <li><a href="{{ route('store.index', ['category' => 'robusta']) }}" class="text-slate-600 hover:text-primary transition-colors">{{ __('store.filter_robusta') }}</a></li>
                    <li><a href="{{ route('store.index', ['category' => 'blend']) }}" class="text-slate-600 hover:text-primary transition-colors">{{ __('store.filter_blend') }}</a></li>
                    <li><a href="{{ route('store.index', ['category' => 'experimental']) }}" class="text-slate-600 hover:text-primary transition-colors">{{ __('store.filter_experimental') }}</a></li>
                </ul>
            </div>

            {{-- Support & Orders --}}
            <div class="lg:col-span-2 space-y-3">
                <h5 class="font-bold text-slate-900 text-xs uppercase tracking-wider">{{ $locale === 'id' ? 'Layanan & Bantuan' : 'Help & Orders' }}</h5>
                <ul class="space-y-2 text-sm">
                    <li><button type="button" data-open-quiz class="text-slate-600 hover:text-primary transition-colors text-left cursor-pointer">{{ $locale === 'id' ? 'Kuis Pilihan Kopi' : 'Coffee Matcher' }}</button></li>
                    <li><button type="button" data-open-cart class="text-slate-600 hover:text-primary transition-colors text-left cursor-pointer">{{ $locale === 'id' ? 'Lihat Keranjang' : 'Shopping Cart' }}</button></li>
                    <li><a href="{{ url('/about') }}" class="text-slate-600 hover:text-primary transition-colors">{{ __('nav.about') }}</a></li>
                    <li><a href="{{ url('/contact') }}" class="text-slate-600 hover:text-primary transition-colors">{{ __('nav.contact') }}</a></li>
                </ul>
            </div>

            {{-- Payments & Delivery --}}
            <div class="lg:col-span-3 space-y-3">
                <h5 class="font-bold text-slate-900 text-xs uppercase tracking-wider">{{ $locale === 'id' ? 'Metode Pembayaran' : 'Secure Payments' }}</h5>
                <p class="text-xs text-slate-500 leading-relaxed">
                    {{ $locale === 'id'
                        ? 'Pembayaran instan otomatis via Midtrans: QRIS, BCA, Mandiri, BNI, BRI, Kartu Kredit, GoPay, dan ShopeePay.'
                        : 'Encrypted transactions via Midtrans Snap: QRIS, Cards, Virtual Accounts, and e-Wallets.' }}
                </p>
            </div>
        </div>

        {{-- Bottom Copyright Bar --}}
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
            <p>© {{ date('Y') }} Lima Biji Agritech. All rights reserved.</p>
            <div class="flex items-center gap-6">
                <a href="{{ url('/') }}" class="hover:text-primary transition-colors">Lima Biji Home</a>
                <a href="{{ route('store.index') }}" class="hover:text-primary transition-colors">Coffee Store</a>
                <a href="{{ url('/contact') }}" class="hover:text-primary transition-colors">Wholesale & Contact</a>
            </div>
        </div>
    </div>
</footer>
