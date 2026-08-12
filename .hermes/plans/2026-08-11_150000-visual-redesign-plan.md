# Visual Redesign Plan — Lima Biji Agritech B2B Landing Page

> **Untuk Hermes:** Plan ini menggunakan 3 skill: `sketch` (3 varian HTML mockup), `popular-web-designs` (design tokens dari Linear/BMW/Stripe), dan `claude-design` (surface-first + slop audit). Mode: planning only.

**Goal:** Redesign halaman utama (`index.blade.php`) menjadi **B2B landing page** — high-conversion, lead-generation focused. Semua konten existing (origin, innovation, testimonials, news, FAQ) tetap ada, tapi dibungkus ulang dengan **warna tegas, font besar, minimalist, dark theme, dan whitespace yang baik.**

**Pendekatan:** 3 fase — (1) Audit & benchmark, (2) Generate 3 varian sketch HTML (semua dark theme), (3) User pilih varian → implementasi ke codebase Laravel.

**Catatan penting:** Plan sebelumnya untuk B2B page terpisah (`/b2b`) **dibatalkan**. Halaman utama (`/`) langsung menjadi B2B landing page. Tidak ada halaman B2B terpisah.

---

## FASE 1: AUDIT — CURRENT vs. TARGET

### 1.1 Current Design Issues

| Aspek | Kondisi Saat Ini | Masalah |
|---|---|---|
| **Warna** | Gradien `from-dark to-secondary` — hijau gelap seluruh halaman | Monoton, tidak ada variasi ritme visual |
| **Font heading** | Alfa Slab One — slab serif | Kurang cocok untuk display besar; terasa "dekoratif" bukan "tegas" |
| **Font body** | Instrument Sans | OK, tapi bisa diganti font yang lebih tajam |
| **Whitespace** | `py-24` (96px) | Cukup, tapi section tidak punya ritme kontras — semua sama |
| **Kontras teks** | `text-white/60` di atas bg gelap | Readability rendah — butuh hirarki teks yang lebih jelas |
| **Border** | `border-secondary/60` — dominan | Border jadi elemen struktural utama, seharusnya subtle |
| **CTA** | Teal `bg-primary` — baik | Bisa lebih bold, lebih besar, dan lebih kontras |
| **Layout** | Semua centered, single-column | Tidak ada variasi — semua section terasa sama |

### 1.2 Target Design Principles

| Prinsip | Implementasi |
|---|---|
| **Dark theme** | Tetap — tapi ganti gradien dengan solid near-black + variasi surface levels |
| **Font besar & tegas** | Ganti Alfa Slab One → font display yang cocok untuk 48-72px (lihat kandidat di bawah) |
| **Warna tegas** | Palet terbatas: 1 background dark, 1 aksen teal, 1 kontras (bisa putih atau warna bold) |
| **Minimalist** | Hapus dekorasi — border subtle, shadow minimal, zero gradient |
| **Ruang baik** | Variasi whitespace: section hero 160px+, section biasa 120px, section compact 80px |
| **B2B focus** | Setiap section punya CTA atau lead capture, bukan sekadar informasi |

### 1.3 Font Display Kandidat

Font yang lebih cocok untuk **besar, tegas, bold:**

| Font | Karakter | Referensi | Google Fonts |
|---|---|---|---|
| **Space Grotesk** | Geometric, slightly condensed, distinctive | Sanity, Vercel-style | ✅ |
| **DM Sans** | Geometric, warm, bold weights | Uber, Coinbase, Airbnb | ✅ |
| **Syne** | Bold, experimental, wide | Creative projects | ✅ |
| **Inter** | Precise, engineered, compressed tracking | Linear, Figma, Vercel | ✅ |
| **Outfit** | Geometric, clean, modern | Branding | ✅ |

**Rekomendasi:** Space Grotesk (bold, geometric, distinctive) atau DM Sans (bold, versatile, proven di production).

---

## FASE 2: 3 VARIAN DESAIN (semua dark theme)

### Varian A: "Engineered Precision" (Linear-inspired)

**Stance:** Dark canvas, satu aksen teal, tipografi precision-engineered.

| Token | Nilai |
|---|---|
| Background | `#08090a` (near-black canvas) |
| Surface 1 | `#0f1011` (panel/card) |
| Surface 2 | `#191a1b` (elevated card) |
| Text primary | `#f7f8f8` (off-white) |
| Text secondary | `#8a8f98` (silver-gray) |
| Text muted | `#62666d` |
| **Accent** | `#079f81` (Lima Biji teal) |
| **Font heading** | **Space Grotesk**, 72px, weight 700, tracking -1.5px, line-height 1.0 |
| **Font body** | Inter, 16px, weight 400, line-height 1.5 |
| Border | `rgba(255,255,255,0.06)` — barely visible |
| Button primary | `bg-[#079f81]`, text white, 8px radius, 12px 24px padding |
| Button ghost | `rgba(255,255,255,0.03)`, 8px radius, border `rgba(255,255,255,0.08)` |
| Card | `bg-[#0f1011]`, border `rgba(255,255,255,0.06)`, 12px radius |
| Section spacing | 160px (hero), 120px (standard), 80px (compact) |
| Container | Max-width 1200px, centered |

**Section layout:**
- **Hero:** 2-column — kiri: headline 72px + subheadline + dual CTA, kanan: stat card atau product visual
- **Trust badges:** Horizontal scroll logo grid, subtle opacity
- **Problem vs Solution:** 2-column comparison cards
- **Core features:** Zigzag 2-column (meniru `innovation.blade.php` pattern)
- **Testimonials:** 3-column grid, card dengan role + metric
- **Lead form:** Full-width CTA section dengan form di dalam card elevated
- **FAQ:** Tetap seperti existing, update styling

### Varian B: "Bold Authority" (dark-mode Uber-inspired)

**Stance:** High-contrast B&W pada dark canvas, bold typography, pill-shaped CTAs.

| Token | Nilai |
|---|---|
| Background | `#000000` (pure black) |
| Surface | `#111111` (card) |
| Text primary | `#ffffff` (pure white) |
| Text secondary | `#afafaf` (muted gray) |
| **Accent** | `#079f81` (teal — hanya untuk CTA dan highlight) |
| **Font heading** | **DM Sans**, 56px, weight 700, line-height 1.1 |
| **Font body** | DM Sans, 16px, weight 400 |
| Border | Tidak ada border dekoratif |
| Button primary | `bg-[#ffffff]` text `#000000`, 999px radius, 16px 24px padding |
| Button accent | `bg-[#079f81]`, 999px radius (untuk lead form CTA) |
| Card | `bg-[#111111]`, shadow `rgba(0,0,0,0.2) 0px 4px 16px`, 12px radius |
| Section spacing | 120px (hero), 100px (standard) |
| Container | Max-width 1136px, centered |

**Section layout:**
- **Hero:** Full-width, text centered, headline 56px, stat bar di bawah
- **Trust badges:** Grid logo + angka stat besar
- **Problem vs Solution:** 2-column, card dengan kontras tinggi
- **Core features:** 3-column card grid, padat
- **Testimonials:** Horizontal scroll atau 3-column
- **Lead form:** Full-width black background, white form fields, pill CTA
- **FAQ:** Accordion dengan styling bold

### Varian C: "Brutalist Organic" (bold + raw + coffee texture)

**Stance:** Raw, bold, typography-first. Menggabungkan ketegasan brutalist dengan warmth kopi.

| Token | Nilai |
|---|---|
| Background | `#0a0a0a` (rich black) |
| Surface | `#141414` (card) |
| Text primary | `#f5f5f0` (warm white) |
| Text secondary | `#a0a098` (warm gray) |
| **Accent** | `#079f81` (teal) + `#d4a574` (warm copper — untuk aksen kedua) |
| **Font heading** | **Syne**, 64px, weight 700, line-height 0.95, tracking -2px |
| **Font body** | Space Grotesk, 16px, weight 400 |
| Border | `1px solid #222` (visible, structural) |
| Button primary | `bg-[#079f81]`, 0px radius (sharp), 16px 32px padding |
| Button outline | `border-2 border-[#d4a574] text-[#d4a574]`, 0px radius |
| Card | `bg-[#141414]`, border `#222`, 0px radius (sharp corners) |
| Section spacing | 200px (hero), 140px (standard) |
| Container | Max-width 1280px, centered |

**Section layout:**
- **Hero:** Oversized typography — 64px headline, 120px+ whitespace, visual sebagai background subtle
- **Trust badges:** Typographic stats — angka besar, label kecil
- **Problem vs Solution:** Side-by-side, kontras background
- **Core features:** Large type + sparse description, vertical stack
- **Testimonials:** Quote-style, large italic text, nama kecil
- **Lead form:** Stark, raw form — input fields dengan border visible
- **FAQ:** Minimal, dengan divider bold

---

## FASE 3: PROSES SKETCH (via `sketch` skill)

### Step 1: Generate 3 Varian HTML

```
sketches/
├── 001-engineered-precision/
│   ├── index.html
│   └── README.md
├── 002-bold-authority/
│   ├── index.html
│   └── README.md
└── 003-brutalist-organic/
    ├── index.html
    └── README.md
```

Setiap varian:
- **Self-contained HTML** — Tailwind via CDN, Google Fonts via `<link>`
- **Konten realistis** — nama, data, teks dari Lima Biji yang asli
- **Placeholder images** — gunakan `https://images.unsplash.com/...` untuk foto kopi
- **Interaktif** — hover, CTA clickable, form fields (non-functional)
- **Lead form** — ada di setiap varian sebagai section terakhir
- **Responsive** — mobile, tablet, desktop

### Step 2: Visual Verification

Setiap varian dibuka via `browser_navigate` + `browser_vision`:
- Layout tidak broken
- Font loading (Google Fonts)
- Warna sesuai token
- Kontras teks terbaca
- Mobile view OK

### Step 3: Slop Audit (via `claude-design`)

Score setiap varian dengan 10-point slop diagnostic. Target score ≤ 3.

### Step 4: Head-to-Head Comparison

| Dimensi | Engineered Precision | Bold Authority | Brutalist Organic |
|---|---|---|---|
| **Background** | `#08090a` near-black | `#000000` pure black | `#0a0a0a` rich black |
| **Font heading** | Space Grotesk 72px | DM Sans 56px | Syne 64px |
| **Aksen** | 1 (teal) | 1 (teal) | 2 (teal + copper) |
| **Border style** | Ultra-subtle | None | Visible structural |
| **Button shape** | 8px radius | 999px pill | 0px sharp |
| **Whitespace** | Generous 120-160px | Compact 100-120px | Extreme 140-200px |
| **Brand fit** | High — modern, premium | High — bold, direct | Medium — artsy, distinctive |
| **B2B tone** | Professional, precise | Confident, authoritative | Bold, memorable |

---

## FASE 4: IMPLEMENTATION (setelah user memilih varian)

### Task 1: Update `resources/css/app.css`
- Ganti `@theme` block dengan warna baru
- Ganti font configuration
- Update/hapus custom utility classes

### Task 2: Update `resources/views/layouts/landingpages.blade.php`
- Ganti background gradient → solid
- Update font CDN links
- Update container & spacing

### Task 3: Refactor Components
- `nav-bar.blade.php` — restyle
- `footer.blade.php` — restyle
- `btn-primary.blade.php` — restyle atau buat baru
- `btn-outline.blade.php` — restyle atau buat baru
- `section-heading.blade.php` — update typography
- `testimonial-card.blade.php` — update card style
- `news-card.blade.php` — update card style

### Task 4: Rewrite `index.blade.php` → B2B Landing Page
- Restruktur 7 section (hero, trust, problem/solution, features, testimonials, enterprise, lead form)
- Setiap section mengarah ke konversi
- Lead form sebagai CTA terakhir

### Task 5: Lead Capture Backend
- Migration `b2b_leads` (dari plan sebelumnya, diadaptasi)
- Model `B2BLead`
- Form Request `B2BLeadFormRequest`
- Route `Route::post('/', ...)` — submit lead dari homepage
- Controller logic — store lead, redirect dengan flash message

### Task 6: Update Link di Navbar & Footer
- "Contact" → arahkan ke `#lead-form` di homepage
- Footer CTA → arahkan ke lead form

---

## SKILLS YANG DIGUNAKAN SAAT EKSEKUSI

| Fase | Skill | Tool |
|---|---|---|
| Generate varian | `sketch` | `write_file` (HTML), `browser_navigate`, `browser_vision` |
| Design tokens | `popular-web-designs` | `skill_view(name, file_path)` untuk Linear, BMW, Stripe |
| Slop audit | `claude-design` | Manual scoring 10 tells |
| Implementasi Blade | — | `patch`, `write_file` |
| Lead backend | — | `terminal` (artisan commands), `write_file` |

---

## RINGKASAN

- **3 varian sketch** — semua dark theme, 3 font berbeda, 3 pendekatan layout berbeda
- **Placeholder images** — Unsplash untuk foto kopi
- **Font besar & tegas** — Space Grotesk (A), DM Sans (B), Syne (C)
- **Landing page = B2B** — homepage utama langsung jadi B2B page, bukan halaman terpisah
- **Lead form** — ada di setiap varian, akan di-backend-kan saat implementasi

---

**Cel, apakah 3 varian di atas sudah sesuai? Saya mulai generate mockup HTML-nya sekarang, atau ada yang mau disesuaikan dulu?**