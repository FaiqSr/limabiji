# Lima Biji → Nordic Approach Design Restyle Plan (Green Palette)

> **For Hermes:** Implement task-by-task. Each task is bite-sized (2-5 min).

**Goal:** Transform Lima Biji Agritech's 5 routes (`/`, `/innovation`, `/testimonials`, `/news`, `/Contact`) to the Nordic Approach premium editorial layout and typography style, while keeping the existing green color identity.

**Architecture:** Laravel + Tailwind CSS v4. Design tokens live in `resources/css/app.css`. Blade components are the reusable UI kit. Each page extends `layouts/landingpages.blade.php`.

**Design System Reference:** Nordic Approach (https://www.nordicapproach.no/) — Nordic Dark Minimalism + Editorial Brutalist typography. **Colors stay green.**

---

## Design Tokens (Nordic Approach layout + Lima Biji green palette)

### Color Palette — GREEN (kept from existing)
| Token | Value | Usage |
|---|---|---|
| `--color-dark` | `#0f1d19` | Deepest background (darker than before for depth) |
| `--color-surface` | `#142520` | Body background |
| `--color-primary` | `#079f81` | Accent: buttons, links, tags (KEPT) |
| `--color-primary-hover` | `#08b894` | Button hover |
| `--color-secondary` | `#1a3a32` | Secondary surfaces |
| `--color-mint` | `#7ed4bf` | Light accent (replaces plum role — soft highlight) |
| `--color-light-grey` | `#b8c5c0` | Secondary text (muted green-grey) |
| `--color-card` | `#162e27` | Glassmorphism card base |
| `--color-accent-gold` | `#e0a873` | Warm accent: stars, highlights (kept warm for contrast) |

### Typography
| Role | Font | CDN |
|---|---|---|
| **Headings (H1, H2)** | `Bebas Neue` (Google Fonts substitute for roc-grotesk-compressed) | `https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap` |
| **Body, nav, UI** | `Rubik` (Google Fonts — same as Nordic Approach) | `https://fonts.googleapis.com/css2?family=Rubik:wght@300..700&display=swap` |

> **Note:** Nordic Approach uses Adobe Fonts (`roc-grotesk-compressed`, `roc-grotesk`). `Bebas Neue` is the closest free Google Fonts equivalent — same condensed, bold, uppercase character. `Rubik` is already used by Nordic Approach and is available on Google Fonts.

### Spacing & Radii (Nordic Approach style)
- **Buttons:** `border-radius: 9999px` (fully pill-shaped, Tailwind `rounded-full`)
- **Cards:** `border-radius: 24px` (Tailwind `rounded-3xl`)
- **Section cards:** `border-radius: 32px` (Tailwind `rounded-4xl`)

---

## Step-by-Step Plan

### Task 1: Overhaul `app.css` — New Design Tokens (Green Palette)

**Objective:** Replace the entire color palette (deeper green), font family, and component classes.

**Files:**
- Modify: `resources/css/app.css`

**Step 1: Replace the file content**

```css
@import "tailwindcss";

@source "../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php";
@source "../../storage/framework/views/*.php";
@source "../**/*.blade.php";
@source "../**/*.js";

@theme {
    /* Font */
    --font-display: "Bebas Neue", sans-serif;
    --font-body: "Rubik", sans-serif;

    /* Lima Biji Green Palette (Nordic-depth) */
    --color-dark: #0f1d19;
    --color-surface: #142520;
    --color-primary: #079f81;
    --color-primary-hover: #08b894;
    --color-secondary: #1a3a32;
    --color-mint: #7ed4bf;
    --color-light-grey: #b8c5c0;
    --color-card: #162e27;
    --color-accent-gold: #e0a873;
}

@layer base {
    body {
        font-family: var(--font-body);
        background-color: var(--color-surface);
        color: #ffffff;
    }
}

@layer components {
    /* Pill-shaped navigation links */
    .nav-link {
        @apply font-medium text-sm px-5 py-2.5 rounded-full border border-transparent
               text-white/80 hover:text-white hover:border-white/20 hover:bg-white/5
               transition-all duration-300;
    }

    /* Section heading — small, uppercase, like Nordic Approach H2 */
    .section-heading {
        @apply text-sm font-bold tracking-widest uppercase text-white/60 mb-2;
    }

    .section-title {
        @apply font-display text-4xl sm:text-5xl lg:text-6xl text-white leading-tight;
    }

    .section-subtitle {
        @apply text-white/50 text-base sm:text-lg max-w-2xl;
    }

    /* Glassmorphism card */
    .glass-card {
        @apply bg-card/60 backdrop-blur-sm border border-white/5 rounded-3xl;
    }
}
```

**Step 2: Verify** — Run `npx @tailwindcss/cli -i resources/css/app.css -o public/build/app.css --dry-run` to check for syntax errors.

---

### Task 2: Update Layout — New Fonts, Background, Body Class

**Objective:** Replace the font CDN links, update body background gradient, and wire up the new font families.

**Files:**
- Modify: `resources/views/layouts/landingpages.blade.php`

**Step 1: Replace font CDN links**

Replace lines 14-17 (the Google Fonts link block) with:

```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Rubik:wght@300..700&display=swap" rel="stylesheet">
```

Remove the old font links: `Alfa Slab One`, `Squada One`, `Suez One`.

**Step 2: Update the body background**

Replace line 31:
```blade
<section class="bg-linear-to-b from-dark to-secondary min-h-screen">
```
With:
```blade
<section class="bg-surface min-h-screen">
```

**Step 3: Verify** — The layout now pulls `Bebas Neue` + `Rubik` from Google Fonts, and the background is `#142520` (surface).

---

### Task 3: Redesign `nav-bar.blade.php` — Transparent, Green Accent, Nordic Style

**Objective:** Match Nordic Approach's transparent nav bar with pill-shaped links and green accent.

**Files:**
- Modify: `resources/views/components/nav-bar.blade.php`

**Step 1: Replace the entire nav-bar content**

```blade
<header class="fixed top-0 left-0 right-0 z-50 flex justify-between items-center px-6 lg:px-12 py-5 bg-dark/80 backdrop-blur-md border-b border-white/5">
    <nav>
        <a href="{{ route('landingpages.index') }}">
            <img src="{{ asset('assets/images/logo/logo-lima-biji.webp') }}" alt="Lima Biji" class="h-12 sm:h-16 w-auto">
        </a>
    </nav>
    <nav class="gap-2 hidden lg:flex items-center">
        <a href="{{ route('landingpages.innovation') }}" class="nav-link">Innovation</a>
        <a href="{{ route('landingpages.news') }}" class="nav-link">News</a>
        <a href="{{ route('landingpages.testimonials') }}" class="nav-link">Testimonials</a>
        <a href="{{ route('landingpages.contact') }}" class="nav-link">Contact</a>
    </nav>
    <div>
        <button class="lg:hidden text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>
</header>
```

**Step 2: Verify** — Nav is now fixed, transparent with backdrop blur, pill-shaped nav links, hamburger for mobile.

---

### Task 4: Redesign `btn-primary.blade.php` — Pill-Shaped, Green Accent

**Objective:** Match Nordic Approach's pill-shaped buttons (`border-radius: 320px`).

**Files:**
- Modify: `resources/views/components/btn-primary.blade.php`

```blade
@props(['href' => '#', 'label' => 'Learn More'])

<a href="{{ $href }}"
    class="inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary-hover text-white
           px-8 py-3 rounded-full font-medium text-base transition-all duration-300
           shadow-lg shadow-primary/20 hover:shadow-primary/30 hover:-translate-y-0.5">
    <span>{{ $label }}</span>
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
    </svg>
</a>
```

Key changes: `rounded-full` (pill), `font-medium` (Rubik), `bg-primary` (green `#079f81`), shadow tinted green.

---

### Task 5: Redesign `btn-outline.blade.php` — Pill-Shaped, White Border

**Files:**
- Modify: `resources/views/components/btn-outline.blade.php`

```blade
@props(['href' => '#', 'label' => 'View More'])

<a href="{{ $href }}"
    class="inline-flex items-center justify-center gap-2 font-medium text-white
           border border-white/30 hover:border-white hover:bg-white/10
           px-8 py-3 rounded-full text-base transition-all duration-300">
    <span>{{ $label }}</span>
</a>
```

---

### Task 6: Redesign `section-heading.blade.php` — Small Uppercase + Large Title

**Objective:** Match Nordic Approach's heading style: small uppercase label + large display title.

**Files:**
- Modify: `resources/views/components/section-heading.blade.php`

```blade
@props(['title', 'subtitle' => null])

<div class="mb-12">
    <p class="section-heading">{{ $title }}</p>
    @if ($subtitle)
        <p class="section-subtitle mt-2">{{ $subtitle }}</p>
    @endif
</div>
```

---

### Task 7: Redesign `news-card.blade.php` — Glassmorphism, Clean Layout

**Files:**
- Modify: `resources/views/components/news-card.blade.php`

```blade
@props(['news'])

<div class="group glass-card overflow-hidden flex flex-col hover:border-primary/30 transition-all duration-500 hover:-translate-y-2">
    <div class="relative h-56 overflow-hidden">
        <img src="{{ $news['image'] }}" alt="{{ $news['title'] }}"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
        <div class="absolute inset-0 bg-gradient-to-t from-dark via-transparent to-transparent opacity-80"></div>
        <span class="absolute top-4 left-4 bg-primary/90 text-white text-xs font-bold px-4 py-1.5 rounded-full">
            {{ $news['category'] }}
        </span>
    </div>
    <div class="p-6 flex flex-col flex-1">
        <div class="flex items-center gap-2 text-xs text-light-grey/60 mb-3">
            <span>{{ $news['date'] }}</span>
        </div>
        <h3 class="text-white font-bold text-lg group-hover:text-primary transition-colors duration-300 line-clamp-2 leading-snug">
            {{ $news['title'] }}
        </h3>
        <p class="text-light-grey/60 text-sm line-clamp-2 leading-relaxed mt-2">{{ $news['excerpt'] }}</p>
    </div>
</div>
```

---

### Task 8: Redesign `testimonial-card.blade.php` — Glassmorphism, Gold Stars

**Files:**
- Modify: `resources/views/components/testimonial-card.blade.php`

```blade
@props(['testimonial'])

<div class="glass-card p-6 sm:p-8 hover:border-primary/30 transition-all duration-300 hover:-translate-y-1">
    <div class="flex items-center gap-1 mb-4">
        @for ($i = 1; $i <= 5; $i++)
            <svg class="w-4 h-4 {{ $i <= $testimonial['rating'] ? 'text-accent-gold' : 'text-white/10' }}" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
        @endfor
    </div>
    <p class="text-white/70 text-sm leading-relaxed mb-6 italic">"{{ $testimonial['content'] }}"</p>
    <div class="flex items-center gap-4 pt-4 border-t border-white/5">
        <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold text-lg">
            {{ strtoupper(substr($testimonial['name'], 0, 1)) }}
        </div>
        <div>
            <p class="text-white font-medium text-sm">{{ $testimonial['name'] }}</p>
        </div>
    </div>
</div>
```

---

### Task 9: Redesign `footer.blade.php` — Clean, Minimal, Nordic + Green

**Files:**
- Modify: `resources/views/components/footer.blade.php`

Replace the entire footer:

```blade
<footer class="border-t border-white/5 text-white pt-16 pb-10">
    <div class="container mx-auto px-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-10 py-16 border-b border-white/5">
            {{-- Brand --}}
            <div class="lg:col-span-4">
                <h4 class="font-display text-2xl text-white mb-4 tracking-wide">LIMA BIJI AGRITECH</h4>
                <p class="text-white/50 text-sm leading-relaxed mb-6">
                    Pioneering biotechnology fermentation for premium Indonesian specialty green beans.
                </p>
                <p class="text-xs text-white/40">Bogor • West Java, Indonesia</p>
            </div>

            {{-- Navigation --}}
            <div class="lg:col-span-2">
                <h5 class="text-white font-medium text-sm mb-5 uppercase tracking-wider">Navigation</h5>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('landingpages.index') }}" class="text-white/50 hover:text-white transition-colors">Home</a></li>
                    <li><a href="{{ route('landingpages.innovation') }}" class="text-white/50 hover:text-white transition-colors">Innovation</a></li>
                    <li><a href="{{ route('landingpages.news') }}" class="text-white/50 hover:text-white transition-colors">News</a></li>
                    <li><a href="{{ route('landingpages.testimonials') }}" class="text-white/50 hover:text-white transition-colors">Testimonials</a></li>
                    <li><a href="{{ route('landingpages.contact') }}" class="text-white/50 hover:text-white transition-colors">Contact</a></li>
                </ul>
            </div>

            {{-- Origins --}}
            <div class="lg:col-span-3">
                <h5 class="text-white font-medium text-sm mb-5 uppercase tracking-wider">Partner Origins</h5>
                <ul class="grid grid-cols-2 gap-2 text-sm text-white/50">
                    @foreach (['West Java', 'Toraja', 'Aceh Gayo', 'Malang', 'Bogor', 'Yogyakarta'] as $origin)
                        <li class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary"></span> {{ $origin }}
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Contact --}}
            <div class="lg:col-span-3">
                <h5 class="text-white font-medium text-sm mb-5 uppercase tracking-wider">Export Office</h5>
                <p class="text-white/50 text-sm mb-2">Bogor, West Java, Indonesia</p>
                <p class="text-white/70 font-medium text-sm">export@limabijiagritech.com</p>
            </div>
        </div>

        {{-- Bottom --}}
        <div class="pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-white/30">
            <p>&copy; {{ date('Y') }} Lima Biji Agritech. All rights reserved.</p>
            <div class="flex items-center gap-6">
                <a href="#" class="hover:text-white/60 transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-white/60 transition-colors">Terms of Use</a>
            </div>
        </div>
    </div>
</footer>
```

---

### Task 10: Redesign `index.blade.php` (Route `/`) — Hero + Origins + Blog + FAQ

**Objective:** The homepage gets the biggest transformation. Match Nordic Approach's:
- Massive compressed hero typography (Bebas Neue, 9xl+)
- Pill-shaped CTA buttons
- Origin carousel (keep existing Swiper, restyle)
- Blog preview grid (3 cards)
- FAQ accordion (keep, restyle colors)
- Stats section with glassmorphism card

**Files:**
- Modify: `resources/views/landingpages/index.blade.php`

**Step 1: Replace the hero section (lines 101-123)**

```blade
{{-- Hero — Nordic Editorial Style --}}
<div class="container mx-auto px-5 pt-40 pb-24 lg:pt-52 lg:pb-32">
    <div class="max-w-5xl">
        <p class="text-sm font-bold tracking-[0.3em] uppercase text-white/60 mb-6">LIMA BIJI AGRITECH</p>
        <h1 class="font-display text-7xl sm:text-9xl lg:text-[12rem] text-white leading-[0.85] uppercase">
            SPECIALTY<br>ENZYMATIC<br>CIVET
        </h1>
        <p class="text-white/50 text-lg mt-8 max-w-xl">
            Cruelty-free enzymatic civet coffee. Pioneering biotechnology fermentation for premium Indonesian green beans.
        </p>
        <div class="flex sm:flex-row flex-col items-start gap-4 mt-10">
            <x-btn-primary href="{{ route('landingpages.innovation') }}" label="Our Innovation" />
            <x-btn-outline href="{{ route('landingpages.news') }}" label="Latest News" />
        </div>
    </div>
</div>
```

**Step 2: Replace the stats section (lines 146-182)**

```blade
{{-- Stats — Glassmorphism --}}
<div class="container mx-auto px-5 py-24">
    <div class="glass-card p-10 sm:p-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <p class="text-white text-2xl lg:text-3xl font-medium leading-tight">
                    Redefining premium Indonesian coffee through biotechnology and ethical craftsmanship.
                </p>
                <p class="text-white/50 mt-4 text-base leading-relaxed">
                    Based in Bogor, West Java, we partner directly with local farmers to produce specialty green beans
                    processed through our proprietary enzymatic fermentation.
                </p>
                <div class="mt-8">
                    <x-btn-primary href="{{ route('landingpages.innovation') }}" label="Discover Our Process" />
                </div>
            </div>
            <div class="grid grid-cols-2 gap-8">
                <div>
                    <p class="text-white font-display text-5xl">7+</p>
                    <p class="text-white/40 text-sm mt-1">Export Countries</p>
                </div>
                <div>
                    <p class="text-white font-display text-5xl">10+</p>
                    <p class="text-white/40 text-sm mt-1">Partner Farms</p>
                </div>
                <div>
                    <p class="text-white font-display text-5xl">100%</p>
                    <p class="text-white/40 text-sm mt-1">Cruelty-Free</p>
                </div>
                <div>
                    <p class="text-white font-display text-5xl">6</p>
                    <p class="text-white/40 text-sm mt-1">Origins</p>
                </div>
            </div>
        </div>
    </div>
</div>
```

**Step 3: Origins section** — Keep the Swiper carousel, verify section-heading renders correctly with new component.

**Step 4: Replace the CTA section (lines 301-323)**

```blade
{{-- CTA — Glassmorphism --}}
<div class="container mx-auto px-5 py-24">
    <div class="glass-card p-10 sm:p-16 relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 opacity-5 pointer-events-none select-none">
            <span class="font-display text-[12rem] text-white leading-none">5</span>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center relative z-10">
            <div class="lg:col-span-7">
                <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl text-white leading-tight uppercase">
                    Ready to Elevate Your Specialty Offerings?
                </h2>
            </div>
            <div class="lg:col-span-5">
                <p class="text-white/50 text-base leading-relaxed mb-8">
                    Partner with us for direct trade, ethical sourcing, and custom enzymatic processing micro-lots.
                </p>
                <x-btn-primary href="{{ route('landingpages.contact') }}" label="Get Samples & Quote" />
            </div>
        </div>
    </div>
</div>
```

**Step 5: Restyle FAQ section (lines 326-379)**

Update the FAQ borders and hover states to use green palette:

- Replace `border-secondary/40` → `border-white/5`
- Replace `hover:border-primary/80` → `hover:border-primary/30`
- Replace `[&[open]]:border-primary` → `[&[open]]:border-primary/50`
- Replace `[&[open]]:bg-secondary/20` → `[&[open]]:bg-primary/5`
- Replace `bg-secondary/50` → `bg-primary/10`
- Replace `border-secondary/80` → `border-primary/20`
- Replace `border-secondary/30` → `border-white/5`

**Step 6: Remove the old animation scripts** (lines 413-436) — the Motion.js animations reference old element IDs (`#hero-text`, `#speciality`). Replace with a simple fade-in on the H1 or remove entirely.

---

### Task 11: Redesign `innovation.blade.php` (Route `/innovation`)

**Objective:** Restyle the 6-step process page with the Nordic layout and Bebas Neue headings.

**Files:**
- Modify: `resources/views/landingpages/innovation.blade.php`

**Step 1: Update the hero heading section**

Replace `font-alfa` → `font-display` (Bebas Neue). Update label color — already `text-primary` (green).

**Step 2: Update process step cards** (lines 73-98)

Change:
- `border-secondary/80` → `border-white/5`
- `bg-dark` → `glass-card` (use the new component class)
- `bg-primary` (step number badge) → stays (green)
- `text-primary` (detail tags) → stays
- `bg-primary/10` → stays
- `border-primary/30` → stays

**Step 3: Update the "Why Cruelty-Free" section (lines 103-161)**

Change:
- `border-secondary/80` → `border-white/5`
- `bg-dark` → `glass-card`
- `bg-secondary/20` → `bg-primary/5`
- `border-secondary/60` → `border-primary/20`

**Step 4: Update CTA section**

Replace `font-alfa` → `font-display`.

---

### Task 12: Redesign `news.blade.php` (Route `/news`)

**Objective:** Restyle the news library page — filter tabs and news grid.

**Files:**
- Modify: `resources/views/landingpages/news.blade.php`

**Step 1: Update hero heading**

Replace `font-alfa` → `font-display`.

**Step 2: Restyle filter tabs (lines 89-97)**

```blade
class="px-6 py-2 rounded-full text-sm font-medium transition-all duration-300
{{ $selectedCategory === $cat
    ? 'bg-primary text-white shadow-lg shadow-primary/20'
    : 'bg-white/5 border border-white/10 text-white/60 hover:border-primary/30 hover:text-white' }}"
```

**Step 3: The `x-news-card` component is already redesigned in Task 7** — no changes needed here.

---

### Task 13: Redesign `testimonials.blade.php` (Route `/testimonials`)

**Objective:** Restyle the testimonials page heading.

**Files:**
- Modify: `resources/views/landingpages/testimonials.blade.php`

**Step 1: Update hero heading**

Replace `font-alfa` → `font-display`.

**Step 2: The `x-testimonial-card` component is already redesigned in Task 8** — no changes needed here.

---

### Task 14: Create `/Contact` Route + Controller + View

**Objective:** Add the missing Contact route with a Nordic-style contact page.

**Files:**
- Create: `resources/views/landingpages/contact.blade.php`
- Modify: `routes/web.php`
- Modify: `app/Http/Controllers/LandingPages.php`

**Step 1: Add controller method**

In `LandingPages.php`, add after the `testimonials()` method:

```php
public function contact()
{
    return view('landingpages.contact');
}
```

**Step 2: Add route**

In `web.php`, add after line 10:

```php
Route::get('/contact', [LandingPages::class, 'contact'])->name('landingpages.contact');
```

**Step 3: Create the contact view**

Create `resources/views/landingpages/contact.blade.php`:

```blade
@extends('layouts.landingpages')

@push('title', 'Contact — Lima Biji Agritech')

@section('content')
    {{-- Hero --}}
    <div class="container mx-auto px-5 pt-40 pb-16 lg:pt-52 lg:pb-20">
        <div class="max-w-3xl">
            <p class="text-sm font-bold tracking-[0.3em] uppercase text-white/60 mb-6">Get in Touch</p>
            <h1 class="font-display text-7xl sm:text-9xl lg:text-[10rem] text-white leading-[0.85] uppercase">
                LET'S<br>TALK.
            </h1>
            <p class="text-white/50 text-lg mt-6 max-w-xl">
                Each one of us is an expert in a different market and can help you with all of your roastery's requirements.
            </p>
        </div>
    </div>

    {{-- Contact Info + Form --}}
    <div class="container mx-auto px-5 py-24">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
            {{-- Info --}}
            <div>
                <h2 class="font-display text-3xl text-white uppercase mb-6">Export Office</h2>
                <div class="space-y-6 text-white/60">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-white/40 mb-1">Address</p>
                        <p class="text-white/70">Bogor, West Java, Indonesia</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-white/40 mb-1">Email</p>
                        <a href="mailto:export@limabijiagritech.com" class="text-white hover:text-primary transition-colors">export@limabijiagritech.com</a>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-white/40 mb-1">Hours</p>
                        <p class="text-white/70">Mon – Fri, 8:00 – 16:00 WIB</p>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <div class="glass-card p-8 sm:p-10">
                <h3 class="font-display text-2xl text-white uppercase mb-8">Send a Message</h3>
                <form class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-white/40 mb-2">Name</label>
                        <input type="text" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-white/20 focus:outline-none focus:border-primary/50 transition-colors" placeholder="Your name">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-white/40 mb-2">Email</label>
                        <input type="email" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-white/20 focus:outline-none focus:border-primary/50 transition-colors" placeholder="you@roastery.com">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-white/40 mb-2">Message</label>
                        <textarea rows="5" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-white/20 focus:outline-none focus:border-primary/50 transition-colors resize-none" placeholder="Tell us about your requirements..."></textarea>
                    </div>
                    <x-btn-primary href="#" label="Send Message" />
                </form>
            </div>
        </div>
    </div>
@endsection
```

---

### Task 15: Build & Verify

**Objective:** Rebuild the CSS and verify all pages render correctly.

**Step 1: Rebuild the CSS**

```bash
cd C:\Users\Celi\Documents\ngodonf\php\limabiji
npm run build
```

**Step 2: Start the dev server**

```bash
php artisan serve
```

**Step 3: Verify each route**

Open in browser and check:
- `/` — Hero with massive Bebas Neue text, glassmorphism stats, origin carousel, blog preview, FAQ
- `/innovation` — 6 process steps with green accents, glassmorphism card
- `/news` — Filter tabs, 3-column news grid with glassmorphism cards
- `/testimonials` — 3-column testimonial grid with gold stars
- `/contact` — Two-column layout, glassmorphism form card

**Step 4: Run slop audit** (per claude-design skill):
- Score the artifact out of 10
- List which tells fired
- Repair only what the audit flags

---

## Summary of Changes

| # | File | Action | What Changes |
|---|---|---|---|
| 1 | `resources/css/app.css` | **Replace** | Green palette (deeper), Bebas Neue + Rubik fonts, glass-card component |
| 2 | `resources/views/layouts/landingpages.blade.php` | **Modify** | Font CDN (Bebas Neue + Rubik), bg-surface |
| 3 | `resources/views/components/nav-bar.blade.php` | **Replace** | Fixed transparent nav, pill links, green accent |
| 4 | `resources/views/components/btn-primary.blade.php` | **Replace** | Pill-shaped, green, shadow |
| 5 | `resources/views/components/btn-outline.blade.php` | **Replace** | Pill-shaped, white border |
| 6 | `resources/views/components/section-heading.blade.php` | **Replace** | Small uppercase + subtitle |
| 7 | `resources/views/components/news-card.blade.php` | **Replace** | Glassmorphism, green tag |
| 8 | `resources/views/components/testimonial-card.blade.php` | **Replace** | Glassmorphism, gold stars |
| 9 | `resources/views/components/footer.blade.php` | **Replace** | Clean minimal, green dots |
| 10 | `resources/views/landingpages/index.blade.php` | **Modify** | Hero, stats, CTA, FAQ restyle |
| 11 | `resources/views/landingpages/innovation.blade.php` | **Modify** | Process steps, cruelty-free section |
| 12 | `resources/views/landingpages/news.blade.php` | **Modify** | Filter tabs, heading |
| 13 | `resources/views/landingpages/testimonials.blade.php` | **Modify** | Heading |
| 14 | `resources/views/landingpages/contact.blade.php` | **Create** | New contact page |
| 15 | `routes/web.php` | **Modify** | Add `/contact` route |
| 16 | `app/Http/Controllers/LandingPages.php` | **Modify** | Add `contact()` method |

---

## Risks & Tradeoffs

1. **Font substitution:** `Bebas Neue` is not identical to `roc-grotesk-compressed`. Bebas Neue is slightly wider. If the user wants the exact compressed look, they'd need an Adobe Fonts subscription.
2. **Tailwind v4 `@theme`:** The new `@theme` block syntax is Tailwind v4. Verified from existing `app.css` — it already uses `@theme`.
3. **Motion.js scripts:** The index page has Motion.js animations referencing old element IDs (`#hero-text`, `#speciality`). These need updating or removal to avoid JS errors.
4. **Contact form:** The form is static HTML (no backend processing). The user may want to wire it up later.
5. **Origin carousel images:** Currently using placeholder images. These should be replaced with real farm/origin photos.
6. **Green palette:** Primary `#079f81` is kept. Background deepened to `#0f1d19` / `#142520` for more Nordic depth. The green stays as the brand identity.