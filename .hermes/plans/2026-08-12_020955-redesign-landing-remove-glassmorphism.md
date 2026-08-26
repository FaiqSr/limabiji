# Redesign Landing Pages — Hapus Glassmorphism (Solid Brutalist-Organic)

> **For Hermes:** Implement plan ini task-by-task (bisa via subagent-driven-development). Semua task kecil (2–5 menit) dan berurutan.

**Goal:** Menghapus seluruh efek glassmorphism (frosted/translusen + backdrop-blur) di landing pages dan menggantinya dengan style solid yang profesional, konsisten dengan design language **Brutalist-Organic** + Nordic green yang sudah dipakai proyek (referensi: `sketches/003-brutalist-organic/index.html`).

**Architecture:** Satu sumber kebenaran di `resources/css/app.css` (token warna + component class). Blade file hanya menukar utility/component class — tanpa perubahan JS, tanpa perubahan struktur HTML.

**Tech Stack:** Tailwind CSS v4 (`@theme`, `@layer components`), Blade components (anonymous + class-based), Vite, Laravel 13.

---

## Konteks & Temuan (sudah diverifikasi via rg)

Style referensi `003-brutalist-organic` memakai: **solid `surface-card`**, **`border-structural`** (border tipis pemisah panel), grid rapat, tanpa `backdrop-filter`. Implementasi live sekarang justru memakai `.glass-card` (frosted) di mana-mana.

**Inventory pengguna glassmorphism di landing (file:baris):**

| # | File:Baris | Elemen | Class sekarang |
|---|---|---|---|
| 1 | `resources/css/app.css:54-57` | Component `.glass-card` | `bg-card/60 backdrop-blur-sm border border-white/5 rounded-3xl` |
| 2 | `landingpages/index.blade.php:137, 295` | Panel statement & CTA | `glass-card p-10 sm:p-16` |
| 3 | `landingpages/origins.blade.php:84, 162` | Panel statistik & CTA | `glass-card p-10 sm:p-16 (text-center)` |
| 4 | `landingpages/origins.blade.php:118` | Figure galeri | `bg-white/5 border border-white/5` |
| 5 | `landingpages/origins.blade.php:136` | Chip flavor/region | `bg-white/5 border border-white/10` |
| 6 | `landingpages/innovation.blade.php:115` | Panel "Why Cruelty-Free" | `glass-card p-8 sm:p-12 lg:p-16` |
| 7 | `landingpages/contact.blade.php:66, 71, 75, 79` | Panel form + 3 input | `glass-card` / `bg-white/5 border border-white/10` |
| 8 | `landingpages/news.blade.php:37` | Filter kategori | `bg-white/5 border border-white/10` |
| 9 | `components/news-card.blade.php:10` | Kartu berita | `glass-card` |
| 10 | `components/testimonial-card.blade.php:9` | Kartu testimoni | `glass-card` |
| 11 | `components/btn-outline.blade.php:5` | Tombol outline | `border-white/30 hover:bg-white/10` |
| 12 | `components/nav-bar.blade.php:76-77` | Overlay sidebar mobile | `backdrop-filter: blur(4px)` |
| 13 | `components/admin/sidebar.blade.php:10` | Overlay admin (opsional) | `backdrop-blur-xs` |
| 14 | `admin/media/index.blade.php` | Pakai `.glass-card` (opsional) | ikut class lama |

**TETAP dipertahankan** (bukan glassmorphism, tidak disentuh): gradient overlay gambar di `news-card.blade.php:14` (`from-dark via-transparent`), avatar `bg-primary/20`, `hover:bg-white/5` di `.nav-link` (app.css:37), popup map & `reset-view-btn` (sudah solid).

---

## Langkah-langkah

### Task 1: Tambah token warna `structural` di app.css

**Objective:** Menyediakan warna border struktural (versi Nordic dari `#222` di sketch) supaya utility `border-structural` tersedia di seluruh template.

**Files:**
- Modify: `resources/css/app.css` — blok `@theme` (baris 8–23)

**Step 1:** Tambahkan satu baris di dalam `@theme { ... }` setelah `--color-accent-gold`:

```css
    --color-accent-gold: #e0a873;
    --color-structural: #24423a;   /* structural border — Nordic green, solid */
```

**Step 2:** Verifikasi — `npm run dev` berjalan, cek `border-structural` muncul di CSS build:
`rg -n 'structural' public/build/assets/*.css` → ada `--color-structural`.

---

### Task 2: Ganti component `.glass-card` → `.card-panel` di app.css

**Objective:** Mendefinisikan kartu solid baru (tanpa blur/transparansi) + varian aksen, lalu hapus `.glass-card`.

**Files:**
- Modify: `resources/css/app.css` — `@layer components` (baris 54–57)

**Step 1:** Ganti blok `.glass-card` dengan:

```css
    /* Solid Brutalist-Organic panel (pengganti glass-card) */
    .card-panel {
        @apply bg-card border border-structural rounded-3xl
               shadow-[0_24px_60px_-24px_rgba(0,0,0,0.6)];
    }

    /* Varian dengan aksen organik di tepi atas */
    .card-panel--accent {
        @apply border-t-2 border-t-primary/60;
    }
```

**Step 2:** Verifikasi — `rg -n 'glass-card' resources/css/app.css` → tidak ada hasil (class lama terhapus).

> Catatan: nama class diganti (bukan sekadar diubah isinya) karena nama `glass` sudah tidak menggambarkan isi. Konsekuensi: semua pemakai `.glass-card` harus di-update (Task 3 & 8).

---

### Task 3: Tukar `glass-card` → `card-panel` di landing pages & komponen

**Objective:** Semua panel landing memakai kartu solid baru (radius organik & border struktural tetap dipertahankan).

**Files:**
- Modify: `resources/views/landingpages/index.blade.php:137, 295`
- Modify: `resources/views/landingpages/origins.blade.php:84, 162`
- Modify: `resources/views/landingpages/innovation.blade.php:115`
- Modify: `resources/views/landingpages/contact.blade.php:66`
- Modify: `resources/views/components/news-card.blade.php:10`
- Modify: `resources/views/components/testimonial-card.blade.php:9`

**Step 1:** Cari & ganti semua `glass-card` → `card-panel` (6 file, 8 kemunculan). Padding (`p-8` s.d. `p-16`) dan modifier lain dipertahankan apa adanya.

**Step 2 (opsional, rekomendasi):** Beri aksen pada panel hero/statement utama agar tetap ada hierarki visual:
- `index.blade.php:137` → `class="card-panel card-panel--accent p-10 sm:p-16"`
- `origins.blade.php:84` (panel statistik) → tambah `card-panel--accent`
- `innovation.blade.php:115` (panel "Why Cruelty-Free") → tambah `card-panel--accent`

**Step 3:** Verifikasi — `rg -n 'glass-card' resources/views/landingpages resources/views/components` → hanya tersisa admin (lihat Task 8).

---

### Task 4: Solid-kan input form (contact.blade.php)

**Objective:** Input form tidak lagi transparan (`bg-white/5`) — pakai isian solid dengan border struktural.

**Files:**
- Modify: `resources/views/landingpages/contact.blade.php:71, 75, 79`

**Step 1:** Ganti ketiga class input (name/email/textarea). Sebelum:

```html
class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-white/20 focus:outline-none focus:border-primary/50 transition-colors"
```

Sesudah:

```html
class="w-full bg-card border border-structural rounded-xl px-4 py-3 text-white placeholder-white/25 focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-colors"
```

> `focus:ring-2` menggantikan kesan "kaca" dengan affordance fokus yang jelas dan tetap profesional.

**Step 2:** Verifikasi — buka `/contact`, isi form: latar input solid `#162e27`, border `#24423a`, fokus = border hijau + ring.

---

### Task 5: Solid-kan chip/pill & figure galeri

**Objective:** Chip kategori/filter dan figure galeri tidak transparan.

**Files:**
- Modify: `resources/views/landingpages/news.blade.php:37` (filter kategori)
- Modify: `resources/views/landingpages/origins.blade.php:118` (figure galeri)
- Modify: `resources/views/landingpages/origins.blade.php:136` (chip flavor)

**Step 1:** Ganti pola class:

| Lokasi | Sebelum | Sesudah |
|---|---|---|
| news.blade.php:37 (aktif) | `bg-primary text-white shadow-lg shadow-primary/20` | tetap (sudah solid) |
| news.blade.php:37 (non-aktif) | `bg-white/5 border border-white/10 text-white/60 hover:border-primary/30 hover:text-white` | `bg-card border border-structural text-white/60 hover:border-primary/50 hover:text-white hover:bg-primary/10` |
| origins.blade.php:118 (figure) | `bg-white/5 border border-white/5` | `bg-card border border-structural` |
| origins.blade.php:136 (chip) | `bg-white/5 border border-white/10 hover:border-primary/30 hover:bg-primary/5` | `bg-card border border-structural hover:border-primary/50 hover:bg-primary/10` |

**Step 2:** Verifikasi — halaman `/news` (klik filter) & `/origin/{name}`: semua chip solid, hover memberi tint hijau Nordic.

---

### Task 6: Update tombol outline (btn-outline.blade.php)

**Objective:** Hover tombol outline memakai tint hijau Nordic, bukan putih transparan.

**Files:**
- Modify: `resources/views/components/btn-outline.blade.php:5`

**Step 1:** Sebelum:

```html
class="inline-flex items-center justify-center gap-2 font-medium text-white
       border border-white/30 hover:border-white hover:bg-white/10
       px-8 py-3 rounded-full text-base transition-all duration-300"
```

Sesudah:

```html
class="inline-flex items-center justify-center gap-2 font-medium text-white
       border border-structural hover:border-primary/60 hover:bg-primary/15
       px-8 py-3 rounded-full text-base transition-all duration-300"
```

> `border-structural` membuat tombol menyatu dengan sistem; hover = aksen hijau khas Lima Biji.

**Step 2:** Verifikasi — hover tombol "Latest News" di homepage: border & latar berubah ke hijau `#079f81`.

---

### Task 7: Hapus backdrop-filter dari overlay sidebar mobile

**Objective:** Tidak ada lagi `backdrop-filter` di landing; overlay tetap readable dengan solid yang lebih pekat.

**Files:**
- Modify: `resources/views/components/nav-bar.blade.php:71-85`

**Step 1:** Hapus dua baris blur, pertegas overlay:

```css
    #sidebar-overlay {
        position: fixed;
        inset: 0;
        z-index: 60;
        background: rgba(0,0,0,0.7);   /* 0.6 → 0.7, kompensasi tanpa blur */
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.35s ease;
    }
```

**Step 2:** Verifikasi — buka mobile viewport (≤1024px), klik hamburger: konten di belakang sidebar tetap terbaca samar, sidebar solid `#0f1d19` (sudah solid sejak awal, baris 96).

---

### Task 8: Sinkronkan admin (2 kemunculan) — konsistensi

**Objective:** Admin ikut class baru supaya tidak ada `.glass-card` tersisa di repo & design konsisten.

**Files:**
- Modify: `resources/views/components/admin/sidebar.blade.php:10` → `backdrop-blur-xs` dihapus, `bg-slate-900/60` → `bg-slate-900/80`
- Modify: `resources/views/admin/media/index.blade.php` → `glass-card` → `card-panel`

**Step 1:** Terapkan perubahan di atas.
**Step 2:** Verifikasi — `rg -rn 'glass-card|backdrop-blur|backdrop-filter' resources/` → **0 hasil** (sweep bersih, kecuali yang memang disengaja dipertahankan — tidak ada).

---

### Task 9: Verifikasi menyeluruh

**Objective:** Memastikan tidak ada regresi visual/fungsional di semua rute landing.

**Step 1:** Jalankan dev stack:
```bash
php artisan serve        # terminal 1
npm run dev              # terminal 2
```

**Step 2:** Buka & cek visual setiap rute:
- `/` (hero, panel statement, news cards, testimonial cards, CTA)
- `/innovation`
- `/news` (klik filter kategori)
- `/testimonials`
- `/contact` (fokus input, submit)
- `/origin/{slug}` (statistik, galeri, chip flavor) — mis. `/origin/Bogor`
- Mobile (≤1024px): sidebar + overlay tanpa blur

**Step 3:** Sweep kode final:
```bash
rg -rn 'glass-card|backdrop-blur|backdrop-filter|bg-white/[0-9]' resources/views resources/css
```
Hasil yang DIPERBOLEHKAN: `hover:bg-white/5` di `.nav-link` (app.css:37) dan gradient `from-dark` (news-card) — bukan glassmorphism. Sisanya harus 0.

**Step 4:** Build & test produksi:
```bash
npm run build
php artisan test        # atau: composer test
```
Expected: build sukses, semua test pass (tidak ada test yang menyentuh styling — konfirmasi tidak ada regresi).

**Step 5 (opsional):** Screenshot per rute (browser tool) untuk dokumentasi sebelum/sesudah.

---

## Files yang berubah (ringkasan)

| File | Perubahan |
|---|---|
| `resources/css/app.css` | +token `--color-structural`; `.glass-card` → `.card-panel` (+`--accent`) |
| `resources/views/landingpages/index.blade.php` | 2× `card-panel` (+aksen) |
| `resources/views/landingpages/origins.blade.php` | 2× `card-panel` (+aksen), figure & chip solid |
| `resources/views/landingpages/innovation.blade.php` | 1× `card-panel` (+aksen) |
| `resources/views/landingpages/contact.blade.php` | 1× `card-panel`; 3 input solid |
| `resources/views/landingpages/news.blade.php` | filter kategori solid |
| `resources/views/components/news-card.blade.php` | `card-panel` |
| `resources/views/components/testimonial-card.blade.php` | `card-panel` |
| `resources/views/components/btn-outline.blade.php` | border struktural + hover hijau |
| `resources/views/components/nav-bar.blade.php` | overlay tanpa blur |
| `resources/views/components/admin/sidebar.blade.php` | overlay tanpa blur |
| `resources/views/admin/media/index.blade.php` | `card-panel` |

Tidak ada perubahan: JS, routes, controllers, database, tests.

---

## Risiko & Tradeoff

| Risiko | Mitigasi |
|---|---|
| Tanpa blur, overlay sidebar kehilangan pemisahan fokus | Overlay dipertegas `rgba(0,0,0,0.7)` |
| Kartu solid bisa terlihat "datar" dibanding frosted | Border struktural + `shadow` lembut + varian aksen `border-t-primary` memberi kedalaman tanpa transparansi |
| Rename class menyentuh halaman admin | Task 8 mengupdate 2 lokasi; sweep akhir menjamin 0 sisa |
| `border-structural` belum ada di Tailwind v4 | Token ditambahkan di `@theme` (Task 1) — utility otomatis ter-generate |
| Kontras teks `text-white/40` di atas `bg-card` solid | Sudah memenuhi (kartu solid lebih gelap dari frosted → kontras justru lebih baik); tetap cek visual |

## Open Questions

1. **Aksen panel** (Task 3 Step 2): terapkan `card-panel--accent` di 3 panel utama saja, atau semua panel? — Default plan: 3 panel utama (hierarki cukup, hindari berlebihan). Bisa disesuaikan saat eksekusi.
2. **Admin ikut diubah?** — Default plan: ya (konsistensi, hanya 2 baris). Bisa di-skip jika ingin admin tidak tersentuh.
