@props([
    'data' => [],
    'locale' => 'en',
    'page' => null,
])

@php
    $hidden = $data['field_hidden'] ?? [];
    $heading = $data['heading'] ?? ($locale === 'id' ? 'HUBUNGI KAMI' : 'GET IN TOUCH');
    $subtitle = $data['subtitle'] ?? ($locale === 'id' ? 'Masing-masing dari kami ahli di pasar yang berbeda dan dapat membantu kebutuhan roastery Anda.' : "Each one of us is an expert in a different market and can help you with all of your roastery's requirements.");
    $email = $data['email'] ?? 'export@limabijiagritech.com';
    $phone = $data['phone'] ?? '+62 812 3456 7890';
    $address = $data['address'] ?? ($locale === 'id' ? 'Bogor, Jawa Barat, Indonesia' : 'Bogor, West Java, Indonesia');
@endphp

<div data-animate="fade-up" class="container mx-auto px-5 py-24">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
        {{-- Contact Info Column --}}
        <div class="lg:col-span-5 space-y-8">
            <div>
                @if(empty($hidden['heading']) && $heading)
                    <h2 class="section-heading">{{ $heading }}</h2>
                @endif
                @if(empty($hidden['subtitle']) && $subtitle)
                    <p class="section-subtitle mt-4">{{ $subtitle }}</p>
                @endif
            </div>

            <div class="space-y-6 pt-4">
                @if(empty($hidden['email']) && $email)
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center text-primary flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-dark font-bold text-base mb-1">{{ $locale === 'id' ? 'Email Ekspor' : 'Export Email' }}</h4>
                        <a href="mailto:{{ $email }}" class="text-light-grey hover:text-primary transition-colors text-sm font-medium">{{ $email }}</a>
                    </div>
                </div>
                @endif

                @if(empty($hidden['phone']) && $phone)
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center text-primary flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-dark font-bold text-base mb-1">{{ $locale === 'id' ? 'Telepon / WhatsApp' : 'Phone / WhatsApp' }}</h4>
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}" class="text-light-grey hover:text-primary transition-colors text-sm font-medium">{{ $phone }}</a>
                    </div>
                </div>
                @endif

                @if(empty($hidden['address']) && $address)
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center text-primary flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-dark font-bold text-base mb-1">{{ $locale === 'id' ? 'Kantor Ekspor' : 'Export Office' }}</h4>
                        <p class="text-light-grey text-sm leading-relaxed">{{ $address }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Contact Form Column --}}
        <div class="lg:col-span-7">
            <div class="card-solid p-8 sm:p-10">
                <form action="#" method="POST" onsubmit="event.preventDefault(); alert('{{ $locale === 'id' ? 'Pesan Anda berhasil terkirim. Tim ekspor kami akan segera menghubungi Anda!' : 'Thank you! Your message has been sent. Our export team will contact you shortly.' }}');" class="space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-dark font-semibold text-xs uppercase tracking-wider mb-2">{{ $locale === 'id' ? 'Nama Lengkap' : 'Full Name' }} *</label>
                            <input type="text" required class="w-full bg-surface border border-border rounded-lg p-3 text-dark text-sm focus:border-primary focus:outline-none transition-colors" placeholder="John Doe">
                        </div>
                        <div>
                            <label class="block text-dark font-semibold text-xs uppercase tracking-wider mb-2">{{ $locale === 'id' ? 'Alamat Email' : 'Email Address' }} *</label>
                            <input type="email" required class="w-full bg-surface border border-border rounded-lg p-3 text-dark text-sm focus:border-primary focus:outline-none transition-colors" placeholder="john@example.com">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-dark font-semibold text-xs uppercase tracking-wider mb-2">{{ $locale === 'id' ? 'Nama Perusahaan / Roastery' : 'Company / Roastery' }}</label>
                            <input type="text" class="w-full bg-surface border border-border rounded-lg p-3 text-dark text-sm focus:border-primary focus:outline-none transition-colors" placeholder="Specialty Roastery Co.">
                        </div>
                        <div>
                            <label class="block text-dark font-semibold text-xs uppercase tracking-wider mb-2">{{ $locale === 'id' ? 'Subjek Pertanyaan' : 'Subject' }}</label>
                            <select class="w-full bg-surface border border-border rounded-lg p-3 text-dark text-sm focus:border-primary focus:outline-none transition-colors">
                                <option>{{ $locale === 'id' ? 'Permintaan Sampel Biji Hijau' : 'Green Bean Sample Request' }}</option>
                                <option>{{ $locale === 'id' ? 'Penawaran Ekspor Curah (FCL/LCL)' : 'Bulk Export Inquiry (FCL/LCL)' }}</option>
                                <option>{{ $locale === 'id' ? 'Kemitraan Pemrosesan Enzimatik' : 'Enzymatic Processing Partnership' }}</option>
                                <option>{{ $locale === 'id' ? 'Pertanyaan Umum' : 'General Inquiry' }}</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-dark font-semibold text-xs uppercase tracking-wider mb-2">{{ $locale === 'id' ? 'Pesan' : 'Message' }} *</label>
                        <textarea required rows="4" class="w-full bg-surface border border-border rounded-lg p-3 text-dark text-sm focus:border-primary focus:outline-none transition-colors" placeholder="{{ $locale === 'id' ? 'Tuliskan detail kebutuhan kopi specialty Anda...' : 'Tell us about your specialty coffee requirements...' }}"></textarea>
                    </div>

                    <button type="submit" class="inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary-hover text-white px-8 py-3 rounded-lg font-medium text-base transition-all duration-300 shadow-md hover:shadow-lg w-full sm:w-auto">
                        <span>{{ $locale === 'id' ? 'Kirim Pesan' : 'Send Message' }}</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
