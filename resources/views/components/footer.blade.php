{{-- resources/views/components/footer.blade.php --}}
<footer class=" text-dark pt-16 pb-10">
    <div class="container mx-auto px-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-10 py-16 border-b border-border">
            {{-- Brand --}}
            <div class="lg:col-span-4">
                <h4 class="font-display text-2xl text-dark mb-4 tracking-wide">LIMA BIJI AGRITECH</h4>
                <p class="text-light-grey text-sm leading-relaxed mb-6">
                    Pioneering biotechnology fermentation for premium Indonesian specialty green beans.
                </p>
                <p class="text-xs text-dark/40">Bogor • West Java, Indonesia</p>
            </div>

            {{-- Navigation --}}
            <div class="lg:col-span-2">
                <h5 class="text-dark font-medium text-sm mb-5 uppercase tracking-wider">Navigation</h5>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ url('/') }}" class="text-light-grey hover:text-dark transition-colors">Home</a></li>
                    <li><a href="{{ url('/about') }}" class="text-light-grey hover:text-dark transition-colors">About</a></li>
                    <li><a href="{{ url('/innovation') }}" class="text-light-grey hover:text-dark transition-colors">Innovation</a></li>
                    <li><a href="{{ url('/news') }}" class="text-light-grey hover:text-dark transition-colors">News</a></li>
                    <li><a href="{{ url('/testimonials') }}" class="text-light-grey hover:text-dark transition-colors">Testimonials</a></li>
                    <li><a href="{{ url('/contact') }}" class="text-light-grey hover:text-dark transition-colors">Contact</a></li>
                </ul>
            </div>

            {{-- Origins --}}
            <div class="lg:col-span-3">
                <h5 class="text-dark font-medium text-sm mb-5 uppercase tracking-wider">Partner Origins</h5>
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
                <h5 class="text-dark font-medium text-sm mb-5 uppercase tracking-wider">Export Office</h5>
                <p class="text-light-grey text-sm mb-2">Bogor, West Java, Indonesia</p>
                <p class="text-dark/70 font-medium text-sm">export@limabijiagritech.com</p>
            </div>
        </div>

        {{-- Bottom --}}
        <div class="pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-light-grey/50">
            <p>&copy; {{ date('Y') }} Lima Biji Agritech. All rights reserved.</p>
            <div class="flex items-center gap-6">
                <a href="#" class="hover:text-light-grey transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-light-grey transition-colors">Terms of Use</a>
            </div>
        </div>
    </div>
</footer>
