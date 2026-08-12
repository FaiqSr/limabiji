# Lima Biji — B2B Design System Redesign (Eliminasi Glassmorphism) Implementation Plan

> **For Hermes:** Gunakan skill `subagent-driven-development` untuk implementasi plan ini task-by-task.

**Goal:** Mentransisikan UI/UX website Lima Biji dari gaya glassmorphism (transparan/blur) ke
estetika material B2B yang profesional — tanpa mengubah identitas merek kopi (palet Nordic green,
tipografi Bebas Neue + Rubik, tema gelap) — dan memperkuat hierarki data produk serta jalur
konversi lead B2B (sampel, katalog wholesale, kontak sales).

**Architecture:** Perubahan berbasis **design token** di `resources/css/app.css` (Tailwind v4 `@theme`)
dan `resources/css/admin.css`, diikuti migrasi komponen Blade satu per satu dari pola
`bg-*/alpha + backdrop-blur` ke permukaan solid berlapis + border tegas + elevasi halus.
Konten tetap CMS-driven (model `Page`, `PageBlock`, `Origin`, `Article`, `Testimonial`).

**Tech Stack:** Laravel 13 (Blade), Tailwind CSS v4 (`@tailwindcss/vite`), daisyUI 5 (admin),
Vite 8, SQLite, motion.js (animasi). **Tidak ada dependency baru yang ditambahkan.**

---

## Bagian 1 — Hasil Audit Desain Saat Ini

### 1.1 Design tokens yang ada (`resources/css/app.css`)

| Token | Nilai | Keterangan |
|---|---|---|
| `--color-dark` | `#0f1d19` | Background hero/depth |
| `--color-surface` | `#142520` | Background halaman |
| `--color-card` | `#162e27` | Background card (dipakai translusen: `bg-card/60`) |
| `--color-primary` | `#079f81` | Nordic green (aksi/CTA) |
| `--color-primary-hover` | `#08b894` | Hover |
| `--color-secondary` | `#1a3a32` | Sekunder |
| `--color-mint` | `#7ed4bf` | Aksen terang |
| `--color-light-grey` | `#b8c5c0` | Teks sekunder |
| `--color-accent-gold` | `#e0a873` | Aksen emas (rating/badge) |
| Font | `Bebas Neue` (display) + `Rubik` (body) | |

### 1.2 Inventory penggunaan glassmorphism (semua lokasi)

| # | Lokasi | Pola glassmorphism | File |
|---|---|---|---|
| 1 | `.glass-card` (definisi) | `bg-card/60 backdrop-blur-sm border-white/5 rounded-3xl` | `resources/css/app.css` |
| 2 | News card | `glass-card` + hover lift + `hover:border-primary/30` | `resources/views/components/news-card.blade.php` |
| 3 | Testimonial card | `glass-card` + border `white/5` | `resources/views/components/testimonial-card.blade.php` |
| 4 | Hero CTA (index) | `glass-card p-10 sm:p-16` | `resources/views/landingpages/index.blade.php:137` |
| 5 | CTA partner (index) | `glass-card p-10 sm:p-16` | `resources/views/landingpages/index.blade.php:295` |
| 6 | FAQ items | `border-white/5`, `[&[open]]:bg-primary/5` | `resources/views/landingpages/index.blade.php:350` |
| 7 | CTA innovation | `glass-card p-8 sm:p-12 lg:p-16` | `resources/views/landingpages/innovation.blade.php:115` |
| 8 | Stats origin (spec grid) | `glass-card p-10 sm:p-16` — altitude, SCA score, process, harvest, varietals | `resources/views/landingpages/origins.blade.php:84` |
| 9 | CTA origin | `glass-card p-10 sm:p-16 text-center` | `resources/views/landingpages/origins.blade.php:162` |
| 10 | Gallery figure | `bg-white/5 border-white/5` | `resources/views/landingpages/origins.blade.php:118` |
| 11 | Chip origin | `bg-white/5 border-white/10` | `resources/views/landingpages/origins.blade.php:136` |
| 12 | Info strip origin | `bg-white/[0.02] border-white/5` | `resources/views/landingpages/origins.blade.php:151` |
| 13 | Filter chip news | `bg-white/5 border-white/10` | `resources/views/landingpages/news.blade.php:37` |
| 14 | Form kontak + inputs | `glass-card` + `bg-white/5 border-white/10 placeholder-white/20` | `resources/views/landingpages/contact.blade.php:66-79` |
| 15 | Nav links (pill) | `hover:bg-white/5 hover:border-white/20` | `resources/views/components/nav-bar.blade.php` (class `.nav-link`) |
| 16 | Sidebar mobile | `backdrop-filter: blur(4px)` pada overlay | `resources/views/components/nav-bar.blade.php:76` |
| 17 | Footer | `border-white/5` | `resources/views/components/footer.blade.php:4` |
| 18 | Overlay admin mobile | `bg-slate-900/60 backdrop-blur-xs` | `resources/views/components/admin/sidebar.blade.php:10` |

### 1.3 Temuan lain (audit koherensi)

1. **Dual design system:** Landing gelap Nordic green (`#079f81`) vs admin terang Indigo
   (`#4F46E5`) di `resources/css/admin.css`. Dua bahasa visual berbeda — perlu disatukan.
2. **Tombol primary** memakai glow berwarna: `shadow-lg shadow-primary/20 hover:shadow-primary/30`
   (`components/btn-primary.blade.php`) — efek "neon" khas glassmorphism, bukan B2B.
3. **Hierarki data origin lemah:** spesifikasi (altitude, SCA, process, harvest, varietals)
   ditampilkan sebagai grid angka sama besar tanpa label struktur, tanpa badge sertifikasi,
   tanpa info MOQ/lead time — padahal ini aset utama pembeli B2B.
4. **Form kontak generik:** hanya Name + Email + Textarea, tanpa field bisnis (company, country,
   inquiry type, volume). Belum ada endpoint backend (`routes/web.php` tidak punya route POST
   kontak) — form saat ini statis.
5. **Kontras rendah:** teks memakai `white/40–70` di atas surface gelap; border `white/5` hampir
   tak terlihat → keterbacaan data enterprise kurang.
6. **Motion berlebihan:** `scale-in` diterapkan ke konten data (stats), plus hover lift pada kartu
   data; `animate.css` di-load tapi tidak terpakai.

---

## Bagian 2 — Visual Direction Proposal

### Nama arah: **"Roastery Lab / Spec-Sheet"** (Material Dark, Enterprise-grade)

**Rasional strategis (lahir dari karakter merek + data audit):**
- **Materialitas kopi:** biji kopi, karung goni, roasting drum — benda padat, bukan kaca.
  Permukaan **solid berlapis** (surface → raised → card) menggantikan transparansi/blur:
  "seperti membaca spec sheet di meja cupping lab".
- **Kepercayaan B2B:** border 1px tegas + elevasi halus memberi batas informasi yang jelas —
  pembeli (roastery/importer) perlu memindai banyak data tanpa tebak-tebakan.
- **Kontinuitas merek:** palet Nordic green gelap + Bebas Neue dipertahankan sebagai identitas
  (dark roast = premium). Yang berubah adalah *material rendering*, bukan *brand character*.
- **Keahlian/craftsmanship:** emas `#e0a873` dinaikkan perannya dari sekadar rating bintang
  menjadi **token semantik** untuk sertifikasi/penghargaan/keunggulan (SCA score, sertifikat).
- **Kejelasan enterprise:** teks memakai skala opasitas eksplisit (`text-primary`, `text-muted`,
  `text-faint`) menggantikan `white/40-70` acak; data produk memakai pola **spec-sheet**
  (label kecil uppercase + nilai tegas + tabel border).

**Prinsip desain yang dipegang (dari skill claude-design):**
1. Start from context — token & komponen yang sudah ada adalah basis, bukan diganti total.
2. Satu ide per section — setiap section punya satu fokus (data, CTA, atau cerita).
3. Motion sebagai disiplin — animasi hanya untuk transisi state, bukan dekorasi.
4. Hindari AI slop — tidak ada gradien neon, glow, atau ikon dekoratif berlebihan.

---

## Bagian 3 — Design Token Changes (rekomendasi konkret)

### 3.1 `resources/css/app.css` — blok `@theme` baru

```css
@theme {
    /* Font (tidak berubah) */
    --font-display: "Bebas Neue", sans-serif;
    --font-body: "Rubik", sans-serif;

    /* Lima Biji Green Palette (tidak berubah — brand anchor) */
    --color-dark: #0f1d19;
    --color-surface: #142520;
    --color-primary: #079f81;
    --color-primary-hover: #08b894;
    --color-secondary: #1a3a32;
    --color-mint: #7ed4bf;
    --color-light-grey: #b8c5c0;
    --color-accent-gold: #e0a873;

    /* BARU — Material surfaces (solid, pengganti alpha/glass) */
    --color-surface-raised: #1b3029;   /* permukaan terangkat di atas surface */
    --color-card-solid: #192d26;       /* pengganti bg-card/60 → solid */
    --color-card-hover: #1e352d;       /* hover state kartu */

    /* BARU — Border tokens (tegas, bukan white/5) */
    --color-border-subtle: #243b33;    /* pengganti border-white/5  */
    --color-border-default: #2c463d;   /* pengganti border-white/10 */
    --color-border-strong: #3a5a4f;    /* pengganti border-white/25 */

    /* BARU — Teks eksplisit (pengganti white/40-70) */
    --color-text-primary: #f4faf8;
    --color-text-secondary: #b8c5c0;   /* = light-grey */
    --color-text-muted: #7e968e;
    --color-text-faint: #5c726a;

    /* BARU — Elevation (bayangan gelap halus, bukan glow berwarna) */
    --shadow-soft: 0 1px 2px 0 rgb(0 0 0 / 0.25);
    --shadow-raised: 0 8px 24px -8px rgb(0 0 0 / 0.45);
    --shadow-focus: 0 0 0 3px rgb(7 159 129 / 0.25);
}
```

### 3.2 Perubahan aturan `.glass-card` → `.panel` (solid)

```css
@layer components {
    /* Pengganti .glass-card — permukaan solid, border tegas, tanpa blur */
    .panel {
        @apply bg-card-solid border border-border-default rounded-3xl
               shadow-soft transition-all duration-300;
    }
    .panel:hover {
        @apply border-border-strong shadow-raised;
    }
}
```

**Catatan:** `.glass-card` dihapus total — tidak ada lagi `backdrop-blur`, `bg-card/60`,
`bg-white/5`, `border-white/5` di seluruh `resources/views/`.

### 3.3 `resources/css/admin.css` — unifikasi token

- Ganti primary indigo → **green Nordic**:
  ```css
  --color-primary-500: #079f81;
  --color-primary-600: #06917a;
  --color-primary-700: #057a67;
  ```
- `--color-surface-*` slate tetap (admin light theme dipertahankan — kontras tinggi untuk
  tabel/CRUD), tapi aksen/aksi menyatu dengan landing.
- `.btn-primary:hover` shadow `rgba(79,70,229,…)` → `rgba(7,159,129,…)`.
- Focus ring form: `rgba(99,102,241,…)` → `rgba(7,159,129,…)`.

### 3.4 Ringkasan mapping token lama → baru

| Lama (glass) | Baru (material) | Efek |
|---|---|---|
| `bg-card/60` | `bg-card-solid` | Opaque, kontras stabil |
| `backdrop-blur-sm` | *(dihapus)* | Tidak ada lagi blur latar |
| `border-white/5` | `border-border-subtle` | Batas terlihat |
| `border-white/10` | `border-border-default` | Input/chip terbaca |
| `border-white/20–30` | `border-border-strong` | Outline button tegas |
| `bg-white/5` (hover) | `bg-card-hover` / `bg-surface-raised` | Hover solid |
| `text-white/40–70` | `text-text-muted` / `text-text-secondary` | Hierarki teks eksplisit |
| `shadow-primary/20` (glow) | `shadow-raised` (netral) | Tanpa neon |
| `bg-primary/5` (tint) | `bg-primary/10` solid tint token baru | Tint terkontrol |
| `placeholder-white/20` | `placeholder:text-text-faint` | Placeholder jelas |

---

## Bagian 4 — Audit Komponen (daftar perubahan per komponen)

| # | Komponen | Perubahan | File |
|---|---|---|---|
| C1 | `.glass-card` → `.panel` | Definisi baru solid + border + shadow-soft | `resources/css/app.css` |
| C2 | Nav bar | Header `bg-surface` solid; `.nav-link` hover `bg-surface-raised border-border-strong`; overlay mobile **tanpa blur** (`rgba(0,0,0,0.7)`) | `resources/views/components/nav-bar.blade.php` |
| C3 | `btn-primary` | Hapus `shadow-primary/20` glow → `shadow-soft`; tetap pill + solid green | `resources/views/components/btn-primary.blade.php` |
| C4 | `btn-outline` | `border-white/30` → `border-border-strong`; hover solid `bg-surface-raised` (bukan `bg-white/10`) | `resources/views/components/btn-outline.blade.php` |
| C5 | News card | `glass-card` → `panel`; badge kategori solid (tetap); hover lift dipertahankan halus | `resources/views/components/news-card.blade.php` |
| C6 | Testimonial card | `glass-card` → `panel`; bintang gold tetap; border-t `border-border-subtle` | `resources/views/components/testimonial-card.blade.php` |
| C7 | FAQ (index) | `border-white/5` → `border-border-subtle`; `bg-primary/5` → tint solid | `resources/views/landingpages/index.blade.php` |
| C8 | Stats origin → **Spec Sheet** | Grid angka → tabel spec: baris `label: nilai` (Altimeter, SCA Score, Process, Harvest, Varietals) + **badge sertifikasi** + baris MOQ/lead time; container `panel` | `resources/views/landingpages/origins.blade.php` |
| C9 | Gallery figure | `bg-white/5 border-white/5` → `bg-card-solid border-border-subtle` | `resources/views/landingpages/origins.blade.php` |
| C10 | Chip/pill info | `bg-white/5 border-white/10` → solid + `border-border-default` | `origins`, `news` |
| C11 | Contact form → **B2B Lead Form** | Field baru: Company, Country (select), Inquiry Type (Sample Kit 250g–1kg / Wholesale FCL-LCL / Partnership), Estimated Volume (select), Message; inputs solid `bg-surface-raised border-border-default`; tombol submit `btn-primary` | `resources/views/landingpages/contact.blade.php` |
| C12 | Footer | `border-white/5` → `border-border-subtle`; teks `white/50` → `text-text-muted` | `resources/views/components/footer.blade.php` |
| C13 | Admin sidebar overlay | `backdrop-blur-xs` dihapus → scrim solid | `resources/views/components/admin/sidebar.blade.php` |
| C14 | Admin CSS tokens | Indigo → green; shadow/focus ring disesuaikan | `resources/css/admin.css` |
| C15 | Map popup (index) | Border `#333` → `var(--color-border-strong)` (minor) | `resources/views/landingpages/index.blade.php` |
| C16 | Motion | Hapus `data-animate="scale-in"` pada blok data (stats/CTA) → `fade-up`; drop `animate.css`; hormati `prefers-reduced-motion` | `layouts/landingpages.blade.php` + halaman |

---

## Bagian 5 — Migration Steps (task-by-task, TDD-friendly)

> Semua task: 2–5 menit kerja, commit setelah tiap task, verifikasi dengan
> `npm run build` dan pengecekan browser (screenshot) setelah task besar.
> Mulai dari token → komponen bersama → halaman → admin → QA.

### Task 1: Overhaul design tokens `app.css`

**Objective:** Token material baru + hapus `.glass-card`.

**Files:**
- Modify: `resources/css/app.css` (`@theme` + `@layer components`)

**Step 1:** Ganti blok `@theme` dengan versi di Bagian 3.1; tambahkan token surface/border/text/shadow.
**Step 2:** Ganti definisi `.glass-card` dengan `.panel` (Bagian 3.2). Hapus class `glass-card`.
**Step 3:** Perbarui `.nav-link` hover:
```css
.nav-link {
    @apply font-medium text-sm px-5 py-2.5 rounded-full border border-transparent
           text-text-secondary hover:text-text-primary hover:border-border-strong
           hover:bg-surface-raised transition-all duration-300;
}
```
**Step 4:** Verifikasi build: `npm run build` → expected: `built in` tanpa error.
**Step 5:** Commit: `git add resources/css/app.css && git commit -m "style: add material design tokens, replace glass-card with panel"`

---

### Task 2: Migrasi komponen bersama (nav, buttons, footer)

**Objective:** Tidak ada lagi glass/blur di komponen global.

**Files:**
- Modify: `resources/views/components/nav-bar.blade.php` (hover class + overlay `backdrop-filter` dihapus → `background: rgba(0,0,0,0.7)`)
- Modify: `resources/views/components/btn-primary.blade.php`
- Modify: `resources/views/components/btn-outline.blade.php`
- Modify: `resources/views/components/footer.blade.php`

**Step 1:** Nav: `hover:bg-white/5 hover:border-white/20` → `hover:bg-surface-raised hover:border-border-strong` (di class `.nav-link` app.css sudah; pastikan tak ada override inline). Sidebar CSS: hapus `backdrop-filter: blur(4px)`.
**Step 2:** btn-primary: `shadow-lg shadow-primary/20 hover:shadow-primary/30` → `shadow-soft hover:shadow-raised`; hapus `hover:-translate-y-0.5` (jaga materialitas).
**Step 3:** btn-outline: `border-white/30 hover:border-white hover:bg-white/10` → `border-border-strong hover:border-text-primary hover:bg-surface-raised`.
**Step 4:** Footer: `border-white/5` → `border-border-subtle`; `text-white/50` → `text-text-muted`.
**Step 5:** Verifikasi: `npm run build`; buka `http://localhost:8000` — cek navbar/footer solid, tidak ada blur.
**Step 6:** Commit: `git commit -m "style: migrate nav, buttons, footer to solid material surfaces"`

---

### Task 3: Migrasi kartu konten (news, testimonial, FAQ)

**Files:**
- Modify: `resources/views/components/news-card.blade.php`
- Modify: `resources/views/components/testimonial-card.blade.php`
- Modify: `resources/views/landingpages/index.blade.php` (FAQ section + hero/CTA panels)
- Modify: `resources/views/landingpages/innovation.blade.php` (CTA panel)

**Step 1:** Ganti `glass-card` → `panel` di news-card & testimonial-card; hapus `hover:border-primary/30` (panel sudah punya hover border-strong) — atau pertahankan `hover:border-primary/40` sebagai sinyal interaktif yang *solid*.
**Step 2:** FAQ: `border-y border-white/5` → `border-y border-border-subtle`; `[&[open]]:bg-primary/5` → `[&[open]]:bg-primary/10` (tint solid).
**Step 3:** Hero CTA & CTA partner (index:137, 295) dan CTA innovation: `glass-card` → `panel`.
**Step 4:** Verifikasi: `npm run build`; screenshot section CTA & FAQ (kontras border terlihat).
**Step 5:** Commit: `git commit -m "style: migrate content cards and FAQ to solid panels"`

---

### Task 4: Spec-Sheet origin (fokus B2B data)

**Objective:** Spesifikasi green beans terbaca enterprise-grade.

**Files:**
- Modify: `resources/views/landingpages/origins.blade.php` (stats grid → spec sheet)

**Step 1:** Ganti grid stats (lines ±84-100) dengan tabel spec:
```blade
<div data-animate="fade-up" class="panel p-8 sm:p-12">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
        @foreach ([
            'Altitude' => $origin['altitude'] ?? '—',
            'SCA Score' => $origin['score'] ?? '—',
            'Process' => $origin['process'] ?? '—',
            'Harvest' => $origin['harvest'] ?? '—',
            'Varietals' => $origin['varietals'] ?? '—',
        ] as $label => $value)
            <div class="flex items-baseline justify-between gap-6 border-b border-border-subtle pb-4">
                <dt class="text-xs font-bold uppercase tracking-wider text-text-muted">{{ $label }}</dt>
                <dd class="font-display text-2xl text-text-primary text-right">{{ $value }}</dd>
            </div>
        @endforeach
    </div>
    {{-- Badge sertifikasi (data dari model Origin bila tersedia) --}}
    <div class="flex flex-wrap gap-3 mt-8">
        <span class="badge-cert">✓ Enzimatik Fermented</span>
        <span class="badge-cert">☕ Specialty SCA 84+</span>
        <span class="badge-cert">🤝 Direct Trade</span>
    </div>
</div>
```
**Step 2:** Tambah `.badge-cert` di `app.css`:
```css
.badge-cert {
    @apply inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-semibold
           bg-secondary text-mint border border-border-strong;
}
```
**Step 3:** CTA origin (`glass-card` → `panel`) + chip & gallery solid (C9, C10).
**Step 4:** Verifikasi: `npm run build`; buka halaman origin (data dari CMS tetap tampil).
**Step 5:** Commit: `git commit -m "feat: origin spec-sheet layout with certification badges"`

---

### Task 5: B2B Lead Form (kontak)

**Objective:** Form konversi lead B2B terstruktur.

**Files:**
- Modify: `resources/views/landingpages/contact.blade.php` (form section)

**Step 1:** Ganti `glass-card` → `panel` pada container form.
**Step 2:** Input class global (ganti semua `bg-white/5 border-white/10 … placeholder-white/20`):
```
class="w-full bg-surface-raised border border-border-default rounded-xl px-4 py-3
       text-text-primary placeholder:text-text-faint focus:outline-none
       focus:border-primary focus:ring-4 focus:ring-primary/10 transition-colors"
```
**Step 3:** Tambah field bisnis sebelum Message:
- `Company Name` (text)
- `Country` (select)
- `Inquiry Type` (select: Sample Kit 250g–1kg / Wholesale FCL–LCL / Partnership & Direct Trade)
- `Estimated Volume` (select: <100 kg / 100–500 kg / 500 kg–5 t / >5 t)
- `Message` (textarea)
**Step 4:** Tombol submit: `btn` style solid green (reuse class btn-primary visual tanpa href → `<button type="submit" class="... bg-primary hover:bg-primary-hover ...">Request Quote</button>`).
**Step 5:** Verifikasi: build + cek form di browser (desktop & mobile).
**Step 6:** Commit: `git commit -m "feat: restructure contact form into B2B lead form"`

> ⚠️ **Open question:** form belum punya endpoint backend (tidak ada route POST kontak).
> Task ini hanya UI. Backend (simpan ke DB `analytics_events`/tabel leads + notifikasi email)
> dipisah jadi task opsional — konfirmasi ke user.

---

### Task 6: Unifikasi admin (design system coherence)

**Files:**
- Modify: `resources/css/admin.css` (tokens indigo → green)
- Modify: `resources/views/components/admin/sidebar.blade.php` (hapus `backdrop-blur-xs`)

**Step 1:** Ganti `--color-primary-*` & semua `rgba(99,102,241,…)` → green (Bagian 3.3).
**Step 2:** Hapus `backdrop-blur-xs` dari overlay sidebar admin → `bg-slate-900/70`.
**Step 3:** Verifikasi: `npm run build`; login `/admin` (admin@limabiji.com / password) — cek tombol, fokus, badge berwarna green konsisten.
**Step 4:** Commit: `git commit -m "style: unify admin accent tokens with brand green"`

---

### Task 7: Motion & QA final

**Objective:** Motion disiplin + jaminan kualitas.

**Files:**
- Modify: `resources/views/layouts/landingpages.blade.php`
- Modify: halaman-halaman (hapus `data-animate="scale-in"` pada blok data)

**Step 1:** Hapus link `animate.css` (tidak terpakai); hapus handler `scale-in` dari shared engine; ganti semua `data-animate="scale-in"` → `data-animate="fade-up"`.
**Step 2:** Tambahkan guard `prefers-reduced-motion`:
```js
if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
```
**Step 3:** QA penuh:
```bash
npm run build        # zero error
php artisan test     # seluruh test hijau (jangan merusak suite)
php artisan pint --test
```
**Step 4:** Browser check semua halaman: `/`, `/origin/{name}`, `/innovation`, `/news`, `/testimonials`, `/contact`, `/admin` — screenshot tiap halaman, pastikan **tidak ada** `backdrop-blur`/`bg-white/5`/`glass` tersisa: `grep -rn "backdrop\|bg-white/\|glass-card\|border-white/" resources/views resources/css` → expected: 0 hasil.
**Step 5:** Commit: `git commit -m "chore: restrain motion, add reduced-motion guard, final QA"`

---

## Tests / Validation

- `npm run build` — wajib zero error setelah tiap task.
- `php artisan test` — suite PHPUnit harus tetap hijau (perubahan murni frontend, tapi jaga).
- `php artisan pint --test` — code style.
- **Grep regression:** `grep -rn "glass-card\|backdrop-blur\|bg-white/[0-9]\|border-white/" resources/` → 0 hasil (task 7).
- **Visual:** screenshot tiap halaman (browser) — border terlihat, kontras teks naik, tidak ada blur.
- **A11y dasar:** kontras teks `text-text-muted` vs `surface` ≥ 4.5:1 (cek dengan contrast checker).

## Risks, Tradeoffs, Open Questions

1. **Perubahan besar di satu commit** → mitigasi: task kecil per commit, tiap task build dulu.
2. **Data Origin untuk badge sertifikasi/MOQ belum tentu ada di model** → badge awal statis
   (Enzimatik, SCA 84+, Direct Trade); field baru di CMS = task terpisah, konfirmasi user.
3. **Form kontak tanpa backend** → UI dulu, endpoint menyusul; jangan janjikan "form terkirim".
4. **Admin light vs landing dark** → sengaja dipertahankan (admin butuh kontras tinggi untuk
   tabel CRUD), yang disatukan hanya **accent/token aksi** (green).
5. **Bebas Neue ukuran 10–11rem** dipertahankan (identitas brand) — hanya material yang berubah.
6. **Open question:** apakah user ingin tetap **dark theme** untuk landing, atau evaluasi varian
   **light paper** (cream + espresso ink) sebagai alternatif? (Direkomendasikan: dark dulu,
   karena brand anchor; varian light bisa dijadikan eksperimen terpisah nanti.)

## Execution Handoff

Setelah plan disetujui, eksekusi dengan `subagent-driven-development`: satu subagent fresh per
task, dua-stage review (spec compliance → code quality) sebelum lanjut ke task berikutnya.
