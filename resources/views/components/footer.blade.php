{{-- resources/views/components/footer.blade.php --}}
<footer class="text-white pt-16 pb-10">
    <div class="container mx-auto px-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-10 py-16 border-b border-border">
            {{-- Brand --}}
            <div class="lg:col-span-4">
                <h4 class="font-display text-2xl text-white mb-4 tracking-wide">{{ __('landing.footer_company') }}</h4>
                <p class="text-light-grey text-sm leading-relaxed mb-6">
                    {{ __('landing.footer_desc') }}
                </p>
                <p class="text-xs text-light-grey/60">{{ __('landing.footer_location') }}</p>
            </div>

            {{-- Navigation --}}
            <div class="lg:col-span-2">
                <h5 class="text-white font-medium text-sm mb-5 uppercase tracking-wider">{{ __('landing.footer_navigation') }}</h5>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ url('/') }}" class="text-light-grey hover:text-white transition-colors">{{ __('nav.home') }}</a></li>
                    <li><a href="{{ url('/about') }}" class="text-light-grey hover:text-white transition-colors">{{ __('nav.about') }}</a></li>
                    <li><a href="{{ url('/innovation') }}" class="text-light-grey hover:text-white transition-colors">{{ __('nav.innovation') }}</a></li>
                    <li><a href="{{ url('/news') }}" class="text-light-grey hover:text-white transition-colors">{{ __('nav.news') }}</a></li>
                    <li><a href="{{ url('/testimonials') }}" class="text-light-grey hover:text-white transition-colors">{{ __('nav.testimonials') }}</a></li>
                    <li><a href="{{ url('/contact') }}" class="text-light-grey hover:text-white transition-colors">{{ __('nav.contact') }}</a></li>
                </ul>
            </div>

            {{-- Origins --}}
            <div class="lg:col-span-3">
                <h5 class="text-white font-medium text-sm mb-5 uppercase tracking-wider">{{ __('landing.footer_origins') }}</h5>
                <ul class="grid grid-cols-2 gap-2 text-sm text-light-grey">
                    @foreach (['West Java', 'Toraja', 'Aceh Gayo', 'Malang', 'Bogor', 'Yogyakarta'] as $origin)
                        <li class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary"></span> {{ $origin }}
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Contact --}}
            <div class="lg:col-span-3">
                <h5 class="text-white font-medium text-sm mb-5 uppercase tracking-wider">{{ __('landing.footer_export_office') }}</h5>
                @php
                    $footerEmail = \App\Models\SiteSetting::get('contact_email', null, 'export@limabijiagritech.com');
                    $footerAddress = \App\Models\SiteSetting::get('contact_address', app()->getLocale(), __('landing.footer_address'));
                @endphp
                <p class="text-light-grey text-sm mb-2">{{ $footerAddress }}</p>
                <a href="mailto:{{ $footerEmail }}" class="text-white/80 hover:text-primary transition-colors font-medium text-sm">{{ $footerEmail }}</a>
            </div>
        </div>

        {{-- Bottom --}}
        <div class="pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-light-grey/50">
            <p>&copy; {{ date('Y') }} Lima Biji Agritech. {{ __('landing.footer_rights') }}</p>
            <div class="flex items-center gap-6">
                <a href="#" class="hover:text-white transition-colors">{{ __('landing.footer_privacy') }}</a>
                <a href="#" class="hover:text-white transition-colors">{{ __('landing.footer_terms') }}</a>
            </div>
        </div>
    </div>
</footer>
