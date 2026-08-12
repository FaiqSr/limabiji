# Plan: Perbaikan Landing Pages — Lima Biji

> **Target:** Layouting fix + Color matching to logo  
> **Scope:** Landing pages only (bukan admin dashboard)  
> **Status:** Draft — menunggu konfirmasi warna logo

---

## A. Color Matching — Mencocokkan Warna ke Logo

### A1. Kondisi Saat Ini

Palet warna landing page didefinisikan di `resources/css/app.css:8-26`:

| Token | Hex | Deskripsi |
|---|---|---|
| `--color-primary` | `#079f81` | Nordic green |
| `--color-primary-hover` | `#08b894` | Green hover |
| `--color-secondary` | `#2a5c4a` | Deep forest green |
| `--color-surface` | `#FAFAF8` | Warm off-white |
| `--color-surface-alt` | `#F0EDE8` | Warm beige |
| `--color-dark` | `#1a1a1a` | Near-black |
| `--color-accent-gold` | `#d4924f` | Gold (testimonial stars) |
| `--color-mint` | `#7ed4bf` | Soft mint |
| `--color-border` | `#E5E2DC` | Warm border grey |
| `--color-border-subtle` | `#EDE9E3` | Subtle border |

Hardcoded values di luar `@theme`:
- `nav-bar.blade.php:96`: Sidebar background `#0f1d19` (dark green/teal)

### A2. ⚠️ Butuh Konfirmasi

**Logo file:** `public/assets/images/logo/logo-lima-biji.webp`  
**Favicon:** `public/favicon.ico`

Agent tidak bisa membaca warna dari file gambar. **Mohon sebutkan warna dominan logo** (hex code jika tahu, atau deskripsi: hijau tua, cokelat, krem, emas, dll.) agar palet warna landing page bisa disesuaikan dengan tepat.

### A3. File yang Akan Diubah (setelah konfirmasi warna)

| File | Perubahan |
|---|---|
| `resources/css/app.css` | Update `--color-primary`, `--color-primary-hover`, `--color-secondary`, `--color-mint`, `--color-accent-gold` sesuai warna logo |
| `resources/views/components/nav-bar.blade.php` | Update warna sidebar mobile `#0f1d19` menjadi warna dari palet baru (gunakan CSS custom property, bukan hardcode) |

---

## B. Layouting Fixes

### B1. HIGH — Section Padding Inconsistency

**File:** `resources/views/landingpages/index.blade.php:173`

**Gejala:** Section "OUR ORIGINS" heading menggunakan `py-10` (2.5rem), sementara semua section lain di seluruh halaman landing menggunakan `py-24` (6rem). Section ini terlihat cramped.

**Fix:**
```blade
{{-- BEFORE --}}
<div data-animate="fade-up" class="container mx-auto px-5 py-10">

{{-- AFTER --}}
<div data-animate="fade-up" class="container mx-auto px-5 py-24">
```

### B2. HIGH — Inconsistent Hero Heading Sizes

**File:** `resources/views/landingpages/origins.blade.php:67`

**Gejala:** Tiga ukuran heading berbeda:
- Home: `lg:text-[12rem]`
- Origins: `lg:text-[11rem]`
- Inner pages (Innovation, News, Testimonials, Contact): `lg:text-[10rem]`

**Fix:** Seragamkan Origins ke `lg:text-[10rem]` (sama dengan inner pages). Home page tetap `lg:text-[12rem]` karena memang hero utama.

```blade
{{-- BEFORE --}}
<h1 ... class="font-display text-7xl sm:text-9xl lg:text-[11rem] text-dark leading-[0.85] uppercase">

{{-- AFTER --}}
<h1 ... class="font-display text-7xl sm:text-9xl lg:text-[10rem] text-dark leading-[0.85] uppercase">
```

### B3. MEDIUM — Ineffective `lg:flex-row-reverse` on Grid

**File:** `resources/views/landingpages/innovation.blade.php:82`

**Gejala:** `lg:flex-row-reverse` adalah utility flexbox yang diaplikasikan ke container grid (`grid grid-cols-1 lg:grid-cols-2`). Tidak berpengaruh apa pun. Reversal sudah di-handle oleh `lg:order-2`/`lg:order-1` pada child elements.

**Fix:** Hapus `lg:flex-row-reverse` sepenuhnya.

```blade
{{-- BEFORE --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center {{ $index % 2 === 1 ? 'lg:flex-row-reverse' : '' }}">

{{-- AFTER --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">
```

### B4. MEDIUM — Grid Gap Inconsistency

**Files:**
- `resources/views/landingpages/news.blade.php:44` — `gap-8`
- `resources/views/landingpages/testimonials.blade.php:27` — `gap-6`

**Gejala:** Kedua halaman menggunakan grid 3 kolom di desktop, tapi gap berbeda (32px vs 24px).

**Fix:** Seragamkan ke `gap-8` (32px).

```blade
{{-- BEFORE (testimonials.blade.php) --}}
<div data-animate="stagger" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

{{-- AFTER --}}
<div data-animate="stagger" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
```

### B5. MEDIUM — Border Radius Inconsistency (Contact Form)

**File:** `resources/views/landingpages/contact.blade.php:71,75,79`

**Gejala:** Form input menggunakan `rounded-xl` (12px), sementara card container menggunakan `card-solid` dengan `rounded-3xl` (24px). Elemen interaktif lain (nav-links, filter tabs, flavor notes) menggunakan `rounded-full`.

**Fix:** Ganti `rounded-xl` → `rounded-2xl` (16px) untuk middle ground yang lebih konsisten.

```blade
{{-- BEFORE --}}
<input type="text" class="w-full bg-surface-alt border border-border rounded-xl px-4 py-3 ...">
{{-- (3 occurrences: name, email, message) --}}

{{-- AFTER --}}
<input type="text" class="w-full bg-surface-alt border border-border rounded-2xl px-4 py-3 ...">
```

### B6. MEDIUM — Grid Wrapping on Medium Screens

**File:** `resources/views/landingpages/origins.blade.php:85`

**Gejala:** Stats grid menggunakan `grid-cols-2 md:grid-cols-3 lg:grid-cols-5`. Dengan 5 item, `md:grid-cols-3` menghasilkan layout 3+2 yang tidak seimbang.

**Fix:** Gunakan `flex` dengan `flex-wrap justify-center` agar distribusi item lebih rapi, atau naikkan ke `md:grid-cols-5`.

```blade
{{-- BEFORE --}}
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8 text-center">

{{-- AFTER (opsi flexbox) --}}
<div class="flex flex-wrap justify-center gap-8 text-center">
    {{-- Wrap each stat in a div with min-w-[120px] --}}
```

### B7. MEDIUM — Missing `overflow-y: auto` on Sidebar

**File:** `resources/views/views/components/nav-bar.blade.php:88-105`

**Gejala:** Sidebar mobile memiliki `height: 100%` tapi tidak ada `overflow-y: auto`. Di viewport pendek (landscape mobile), konten sidebar bisa overflow tanpa bisa di-scroll.

**Fix:** Tambahkan `overflow-y: auto` ke `#sidebar` styles.

```css
/* BEFORE */
#sidebar {
    position: fixed;
    top: 0;
    right: 0;
    ...
    height: 100%;
    background: #0f1d19;
    ...
}

/* AFTER */
#sidebar {
    position: fixed;
    top: 0;
    right: 0;
    ...
    height: 100%;
    overflow-y: auto;
    background: #0f1d19;
    ...
}
```

### B8. LOW — Hover Text Size Layout Shift

**File:** `resources/views/landingpages/index.blade.php:187`

**Gejala:** Origin name berubah dari `text-4xl` ke `text-5xl` saat hover, bersamaan dengan repositioning ke center. Font-size transition tidak GPU-accelerated dan menyebabkan jank.

**Fix:** Hapus perubahan font-size pada hover, cukup repositioning saja.

```blade
{{-- BEFORE --}}
<p class="absolute bottom-5 left-8 text-4xl group-hover:text-5xl font-display text-dark py-2 transition-all duration-500 ease-in-out
    group-hover:bottom-1/2 group-hover:left-1/2 group-hover:-translate-x-1/2
    group-hover:translate-y-1/2 pointer-events-none">

{{-- AFTER --}}
<p class="absolute bottom-5 left-8 text-4xl font-display text-dark py-2 transition-all duration-500 ease-in-out
    group-hover:bottom-1/2 group-hover:left-1/2 group-hover:-translate-x-1/2
    group-hover:translate-y-1/2 pointer-events-none">
```

### B9. LOW — Nav-Bar Not Sticky

**File:** `resources/views/components/nav-bar.blade.php:1`

**Gejala:** Header memiliki `top-0 left-0 right-0 z-50` tapi tidak ada `fixed` atau `sticky`. Nav-bar ikut scroll.

**Fix:** Tambahkan `sticky` agar nav-bar tetap di atas saat scroll.

```blade
{{-- BEFORE --}}
<header class="top-0 left-0 right-0 z-50 flex justify-between items-center px-6 lg:px-12 py-5 bg-surface">

{{-- AFTER --}}
<header class="sticky top-0 left-0 right-0 z-50 flex justify-between items-center px-6 lg:px-12 py-5 bg-surface">
```

### B10. LOW — Invalid `rounded-4xl` Utility

**File:** `resources/views/landingpages/origins.blade.php:60`

**Gejala:** `rounded-4xl` bukan Tailwind built-in class (berhenti di `rounded-3xl` = 24px). Akan diabaikan (no border-radius).

**Fix:** Ganti ke `rounded-3xl` atau `rounded-[2rem]`.

```blade
{{-- BEFORE --}}
<div class="relative h-[70vh] min-h-[500px] mx-5 my-5 rounded-4xl overflow-hidden">

{{-- AFTER --}}
<div class="relative h-[70vh] min-h-[500px] mx-5 my-5 rounded-3xl overflow-hidden">
```

---

## C. File yang Terdampak — Ringkasan

| File | Perubahan |
|---|---|
| `resources/views/landingpages/index.blade.php` | B1: `py-10` → `py-24`, B8: hapus `group-hover:text-5xl` |
| `resources/views/landingpages/origins.blade.php` | B2: `lg:text-[11rem]` → `lg:text-[10rem]`, B6: grid → flex, B10: `rounded-4xl` → `rounded-3xl` |
| `resources/views/landingpages/innovation.blade.php` | B3: hapus `lg:flex-row-reverse` |
| `resources/views/landingpages/news.blade.php` | (tidak ada perubahan — sudah `gap-8`) |
| `resources/views/landingpages/testimonials.blade.php` | B4: `gap-6` → `gap-8` |
| `resources/views/landingpages/contact.blade.php` | B5: `rounded-xl` → `rounded-2xl` (3 places) |
| `resources/views/components/nav-bar.blade.php` | B7: tambah `overflow-y: auto`, B9: tambah `sticky` |
| `resources/css/app.css` | A3: update warna (setelah konfirmasi) |

---

## D. Verifikasi

Setelah semua perubahan:

```bash
# 1. Build frontend (cek Tailwind compile)
npm run build

# 2. Format kode
php artisan pint

# 3. Test suite
php artisan test

# 4. Manual smoke test
# Buka http://localhost:8000 — cek semua halaman landing
# Buka http://localhost:8000/admin — pastikan admin tidak terpengaruh
```

---

## E. Catatan / Risiko

- **Warna logo:** Plan bagian A belum bisa dieksekusi sampai warna logo dikonfirmasi. Semua perubahan warna (A3) ditunda.
- **Admin dashboard tidak terpengaruh:** Admin menggunakan `resources/css/admin.css` dengan palette indigo/slate terpisah. Tidak ada perubahan pada admin.
- **B6 (grid → flex):** Perlu di-test bahwa 5 stat items tetap center dengan baik di semua breakpoint.
- **B8 (hapus hover text-size):** Perlu dipastikan animasi repositioning origin name tetap terlihat smooth tanpa perubahan font-size.
- **B9 (sticky nav):** Test bahwa sticky nav tidak overlap dengan konten di bawahnya (harusnya sudah aman karena `bg-surface`).