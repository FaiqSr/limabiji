<header class="flex justify-between px-30 py-5 items-center bg-dark">
    <nav>
        <a href="{{ route('landingpages.index') }}">
            <img src="{{ asset('assets/images/logo/logo-lima-biji.webp') }}" alt="Logo Lima Biji" class="h-20 w-auto">
        </a>
    </nav>
    <nav class="flex gap-10">
        <a href="" class="navbar-text">About</a>
        <a href="" class="navbar-text">Testimoni</a>
        <a href="" class="navbar-text">Contact</a>
        <a href="" class="navbar-text">Inovations</a>
    </nav>
    <div>
        <button>
            <a href="" class="px-5 py-2 text-white font-bold text-2xl">Menu</a>
        </button>
    </div>
</header>

@push('scripts')
@endpush
