# Plan: Perbaikan Multi-Bahasa + Rich Text Editor — Admin Content

> **Topik:** Bug data overwriting bilingual + Rich Text Editor (Trix)  
> **Scope:** Admin `/admin/content` + Backend `PageController` + Landing page views  
> **Status:** Draft — siap implementasi

---

## 1. Analisis Penyebab Bug (Multi-Bahasa)

### Root Cause

**File:** `app/Http/Controllers/Admin/PageController.php:58`

```php
// Line 58 — BUG: full replace, tidak merge dengan existing content
$content = $this->normalizeBlockContent($blockData['block_type'], $blockData['content']);
```

**Cara kerja form saat ini:**

Form admin di `edit.blade.php` menggunakan Alpine.js tabs EN/ID. Setiap field di-render dengan key `locale`:

```html
<input :name="'blocks['+index+'][content]['+locale+'][label]'">
```

Saat user membuka tab **ID** dan mengisi konten, yang dikirim ke server HANYA:

```json
{
  "blocks": [{
    "content": {
      "id": { "heading": "Judul ID", "body": "Isi ID" }
    }
  }]
}
```

**`content.en` TIDAK dikirim** karena form field EN tidak dirender/diisi saat tab ID aktif.

### Alur Bug

```
1. DB: content = {"en": {"heading": "English Title"}, "id": {"heading": "Judul ID"}}
2. User edit tab ID, submit → payload = {"id": {"heading": "Judul Baru"}}
3. PageController::update() → $blockAttrs['content'] = {"id": {"heading": "Judul Baru"}}
4. $page->blocks()->update($blockAttrs) → FULL REPLACE
5. DB: content = {"id": {"heading": "Judul Baru"}}  ← "en" HILANG!
```

**Kesimpulan:** `PageController::update()` tidak melakukan **merge** dengan data existing. Ia langsung menimpa seluruh kolom `content` dengan payload yang hanya berisi satu locale.

---

## 2. Solusi

### Backend: Merge Content (Partial Update)

**File:** `app/Http/Controllers/Admin/PageController.php:56-58`

**Before:**
```php
$content = $this->normalizeBlockContent($blockData['block_type'], $blockData['content']);
```

**After:**
```php
// Ambil existing content dari DB
$existingContent = [];
if ($blockId && in_array($blockId, $existingIds)) {
    $existingBlock = $page->blocks()->find($blockId);
    $existingContent = $existingBlock ? (is_array($existingBlock->content) ? $existingBlock->content : []) : [];
}
// Merge submitted dengan existing (preserve locale yang tidak diedit)
$mergedContent = array_merge_recursive($existingContent, $blockData['content']);
$content = $this->normalizeBlockContent($blockData['block_type'], $mergedContent);
```

**Kelebihan `array_merge_recursive`:**
- Menggabungkan nested keys (`en.heading`, `id.body`, dll.)
- String values di-submitted akan menimpa string existing (perilaku yang diinginkan)
- Array `items` akan digabung secara rekursif

### Schema Data — Sudah Benar

Schema JSON `page_blocks.content` sudah ideal:

```json
{
  "en": {
    "heading": "Innovation",
    "body": "English body text...",
    "items": [{"label": "Stat 1", "value": "100"}]
  },
  "id": {
    "heading": "Inovasi",
    "body": "Teks body Indonesia...",
    "items": [{"label": "Stat 1", "value": "100"}]
  }
}
```

- **Tidak perlu migration baru** — struktur `{"en": {...}, "id": {...}}` sudah benar
- **Tidak perlu model baru** — `PageBlock::getContent($locale)` sudah bekerja dengan baik

---

## 3. Rencana Implementasi (Step-by-Step)

### Step 1: Backend — Merge Content (prioritas: KRITIS)

**File:** `app/Http/Controllers/Admin/PageController.php`

Ubah method `update()` pada bagian sync blocks (~line 52-80):

```php
// Sync blocks
if (isset($validated['blocks'])) {
    $existingIds = $page->blocks()->pluck('id')->toArray();
    $submittedIds = [];

    foreach ($validated['blocks'] as $blockData) {
        $blockId = $blockData['id'] ?? null;

        // NEW: Merge with existing content to preserve other locale
        $existingContent = [];
        if ($blockId && in_array($blockId, $existingIds)) {
            $existingBlock = $page->blocks()->find($blockId);
            $existingContent = $existingBlock && is_array($existingBlock->content) ? $existingBlock->content : [];
        }
        $mergedContent = array_merge_recursive($existingContent, $blockData['content']);
        $content = $this->normalizeBlockContent($blockData['block_type'], $mergedContent);

        $blockAttrs = [
            'block_type' => $blockData['block_type'],
            'order' => $blockData['order'],
            'content' => $content,
            'is_visible' => $blockData['is_visible'] ?? true,
        ];

        if ($blockId && in_array($blockId, $existingIds)) {
            $page->blocks()->where('id', $blockId)->update($blockAttrs);
            $submittedIds[] = $blockId;
        } else {
            $newBlock = $page->blocks()->create($blockAttrs);
            $submittedIds[] = $newBlock->id;
        }
    }
    // ... delete removed blocks (unchanged)
}
```

### Step 2: Frontend — Submit Both Locales (prioritas: TINGGI)

**File:** `resources/views/admin/content/edit.blade.php`

**Masalah:** Saat ini form hanya mengirim field untuk locale yang sedang aktif. Solusi: tambahkan hidden inputs untuk memastikan SEMUA locale tetap terkirim.

**Opsi A — Hidden inputs (simpel, minimal perubahan):**

Tambahkan di dalam setiap block template (setelah language switcher, sebelum form fields):

```html
{{-- Pastikan kedua locale selalu terkirim --}}
<input type="hidden" :name="'blocks['+index+'][content][en][_preserve]'" value="1">
<input type="hidden" :name="'blocks['+index+'][content][id][_preserve]'" value="1">
```

**Opsi B — Mirror fields (lebih robust):**

Render field untuk KEDUA locale secara bersamaan dengan `x-show`:

```html
<div x-show="locale === 'en'">
    <input :name="'blocks['+index+'][content][en][heading]'" ...>
</div>
<div x-show="locale === 'id'">
    <input :name="'blocks['+index+'][content][id][heading]'" ...>
</div>
```

**Rekomendasi:** Opsi A (hidden inputs) + backend merge. Hidden inputs memastikan `content.en` dan `content.id` selalu ada di payload, sehingga `array_merge_recursive` bisa bekerja dengan benar.

### Step 3: Validation — Relax EN/ID Rules

**File:** `app/Http/Controllers/Admin/PageController.php:36-37`

**Before:**
```php
'blocks.*.content.en' => ['array'],
'blocks.*.content.id' => ['array'],
```

**After (tidak perlu diubah):**
Rules sudah `['array']` (optional). Tidak perlu `required` karena kedua locale tidak wajib diisi.

### Step 4: Testing Checklist

- [ ] **Test 1:** Edit EN → save → ID tetap ada
- [ ] **Test 2:** Edit ID → save → EN tetap ada
- [ ] **Test 3:** Edit kedua locale bersamaan → save → keduanya tersimpan
- [ ] **Test 4:** Tambah block baru → isi EN saja → save → ID tetap kosong (tidak error)
- [ ] **Test 5:** Hapus block → save → tidak ada orphan data
- [ ] **Test 6:** Landing page EN → tampil konten EN
- [ ] **Test 7:** Landing page ID → tampil konten ID
- [ ] **Test 8:** SEO: `<html lang="id">` di halaman ID, `<html lang="en">` di halaman EN
- [ ] **Test 9:** SEO: `<link rel="alternate" hreflang="en">` dan `hreflang="id"` di `<head>`
- [ ] **Test 10:** Version history restore → tidak corrupt data bilingual

---

## 4. SEO untuk Multi-Bahasa

### Rekomendasi

**File:** `resources/views/layouts/landingpages.blade.php`

Tambahkan `hreflang` tags di `<head>`:

```blade
<link rel="alternate" hreflang="en" href="{{ url('/en') }}">
<link rel="alternate" hreflang="id" href="{{ url('/id') }}">
<link rel="alternate" hreflang="x-default" href="{{ url('/') }}">
```

**File:** `routes/web.php`

Pastikan route landing page mendukung prefix locale:

```php
Route::prefix('{locale?}')->middleware('set.locale')->group(function () {
    Route::get('/', [LandingPages::class, 'index'])->name('landingpages.index');
    // ...
});
```

**Catatan:** `SetLocale` middleware sudah berfungsi via session/cookie/browser detection. Tambahan `hreflang` tags memastikan Google memahami struktur bilingual.

---

## 5. Rich Text Editor (Trix) — Form Input dengan Formatting

### Editor yang Dipilih: Trix

**Alasan:**
- CDN-based — tidak perlu `npm install` (sesuai AGENTS.md guardrails)
- Ringan (~50KB gzipped)
- Sanitasi HTML bawaan (mencegah XSS)
- API sederhana: cukup tambah atribut `trix-editor` ke hidden input
- Sudah ada Alpine.js di project — bisa di-bridge via `x-trix` atau custom directive

**CDN:**
```html
<link rel="stylesheet" href="https://unpkg.com/trix@2.1.13/dist/trix.css">
<script src="https://unpkg.com/trix@2.1.13/dist/trix.umd.min.js"></script>
```

### Field yang Perlu Rich Text Editor

| Block Type | Field | Tipe Saat Ini | Output di Landing |
|---|---|---|---|
| `hero` | `subheading` | `<textarea>` | `{{ $heroSubheading }}` |
| `text` | `body` | `<textarea>` | `{{ $body }}` |
| `cta` | `body` | `<textarea>` | `{{ $ctaBody }}` |
| `process_steps` | `description` (per item) | `<textarea>` | `{{ $step['description'] }}` |
| `text_with_stats` | `body` | `<textarea>` | `{{ $whyBody }}` |
| `text_with_stats` | `body2` | `<textarea>` | `{!! $whyBody2 !!}` |
| `faq` | `answer` (per item) | `<textarea>` | `{{ $faq['answer'] }}` |

### Integrasi Trix dengan Alpine.js

**Step 1: Load Trix di admin layout**

**File:** `resources/views/admin/layouts/app.blade.php`

Tambahkan di `<head>`:
```html
<link rel="stylesheet" href="https://unpkg.com/trix@2.1.13/dist/trix.css">
<style>
    /* Brutalistic-Organic override — sesuaikan toolbar Trix */
    trix-toolbar { border-radius: 8px 8px 0 0; border-color: #e2e8f0; }
    trix-editor {
        border-radius: 0 0 8px 8px;
        border-color: #e2e8f0;
        min-height: 120px;
        font-size: 0.8125rem;
        line-height: 1.5;
        padding: 0.75rem;
    }
    trix-editor:focus { border-color: #6366f1; outline: none; }
    /* Sembunyikan tombol yang tidak diperlukan */
    trix-toolbar .trix-button-group--file-tools { display: none; }
    trix-toolbar .trix-button--icon-code { display: none; }
    trix-toolbar .trix-button--icon-quote { display: none; }
</style>
```

Di akhir `<body>` (sebelum `@stack('scripts')`):
```html
<script src="https://unpkg.com/trix@2.1.13/dist/trix.umd.min.js"></script>
```

**Step 2: Alpine.js directive untuk binding Trix**

**File:** `resources/views/admin/content/edit.blade.php`

Tambahkan custom directive di script section (sebelum `blockEditor` function):

```javascript
// Custom Alpine directive: x-trix
document.addEventListener('alpine:init', () => {
    Alpine.directive('trix', (el, { expression }, { evaluate }) => {
        // Trix membutuhkan <input type="hidden"> + <trix-editor>
        // Kita gunakan <textarea> awal lalu dikonversi oleh Trix
        el.setAttribute('x-data', `{ trixValue: ${expression} }`);
        el.setAttribute('x-effect', 'trixValue');
        
        el.addEventListener('trix-change', (e) => {
            evaluate(`${expression} = e.target.value`);
        });
    });
});
```

**Step 3: Ganti `<textarea>` dengan Trix editor**

Untuk setiap block type, ganti `<textarea>` dengan `<trix-editor>` + hidden input.

**Contoh — Text Block `body`:**
```html
<!-- Before -->
<textarea :name="'blocks['+index+'][content]['+locale+'][body]'" rows="3" class="text-xs" x-model="content(block, locale).body"></textarea>

<!-- After — Trix editor -->
<input type="hidden" :name="'blocks['+index+'][content]['+locale+'][body]'" :id="'body-' + index + '-' + locale" :value="content(block, locale).body">
<trix-editor :input="'body-' + index + '-' + locale" class="text-xs"></trix-editor>
```

**Contoh — FAQ `answer`:**
```html
<!-- Before -->
<textarea :name="'blocks['+index+'][content]['+locale+'][items]['+itemIndex+'][answer]'" x-model="item.answer" rows="2" class="text-xs" placeholder="Answer"></textarea>

<!-- After — Trix editor -->
<input type="hidden" :name="'blocks['+index+'][content]['+locale+'][items]['+itemIndex+'][answer]'" :id="'faq-answer-' + index + '-' + itemIndex + '-' + locale" :value="item.answer">
<trix-editor :input="'faq-answer-' + index + '-' + itemIndex + '-' + locale" class="text-xs"></trix-editor>
```

### Output di Landing Page — Sanitasi & `{!! !!}`

Karena editor menyimpan HTML, landing page harus render dengan `{!! !!}` (unescaped). Tapi **harus ada sanitasi** untuk mencegah XSS.

**File:** `app/Models/PageBlock.php`

Tambahkan method untuk sanitize content:

```php
/**
 * Get sanitized HTML content for a specific field.
 */
public function getHtmlContent(string $locale = 'en', string $field = 'body'): string
{
    $content = $this->getContent($locale);
    $html = $content[$field] ?? '';
    
    if (empty($html)) {
        return '';
    }
    
    // Strip dangerous tags, allow basic formatting
    return strip_tags($html, [
        'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'del',
        'a', 'ul', 'ol', 'li', 'blockquote', 'pre', 'code',
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'img', 'span', 'div', 'hr',
    ]);
}
```

**File landing page yang perlu `{!! !!}`:**

| File | Field | Current | After |
|---|---|---|---|
| `innovation.blade.php` | `$heroSubheading` | `{{ }}` | `{!! $heroSubheading !!}` |
| `innovation.blade.php` | `$step['description']` | `{{ }}` | `{!! $step['description'] !!}` |
| `innovation.blade.php` | `$whyBody` | `{{ }}` | `{!! $whyBody !!}` |
| `contact.blade.php` | `$heroSubheading` | `{{ }}` | `{!! $heroSubheading !!}` |
| `index.blade.php` | `$faq['answer']` | `{{ }}` | `{!! $faq['answer'] !!}` |

### Field yang TETAP `<input>` (tidak perlu rich text)

| Field | Alasan |
|---|---|
| `heading`, `label`, `title`, `step` | Judul/heading pendek — cukup `<input>` |
| `button_text`, `button_url` | Single line, tidak perlu formatting |
| `checklist` | Comma-separated values — bukan rich text |
| `image`, `details` | URL dan metadata — bukan rich text |
| `question` (FAQ) | Pertanyaan pendek — cukup `<input>` |

---

## 6. Ringkasan File yang Diubah

| File | Perubahan | Prioritas |
|---|---|---|
| `app/Http/Controllers/Admin/PageController.php` | Merge content dengan existing data (line 56-58) | KRITIS |
| `resources/views/admin/content/edit.blade.php` | Hidden inputs kedua locale + ganti textarea → Trix editor | TINGGI |
| `resources/views/admin/layouts/app.blade.php` | Load Trix CSS + JS via CDN + custom styling | TINGGI |
| `app/Models/PageBlock.php` | Method `getHtmlContent()` untuk sanitasi output | TINGGI |
| `resources/views/landingpages/innovation.blade.php` | `{{ }}` → `{!! !!}` untuk field rich text (3 field) | MEDIUM |
| `resources/views/landingpages/contact.blade.php` | `{{ }}` → `{!! !!}` untuk `$heroSubheading` | MEDIUM |
| `resources/views/landingpages/index.blade.php` | `{{ }}` → `{!! !!}` untuk `$faq['answer']` | MEDIUM |
| `resources/views/layouts/landingpages.blade.php` | Tambah `hreflang` tags | MEDIUM |
| `routes/web.php` | (opsional) Prefix locale di route | RENDAH |

---

## 7. Testing Checklist (Extended)

### Multi-Bahasa
- [ ] **Test 1:** Edit EN → save → ID tetap ada
- [ ] **Test 2:** Edit ID → save → EN tetap ada
- [ ] **Test 3:** Edit kedua locale bersamaan → save → keduanya tersimpan
- [ ] **Test 4:** Tambah block baru → isi EN saja → save → ID tetap kosong (tidak error)
- [ ] **Test 5:** Hapus block → save → tidak ada orphan data
- [ ] **Test 6:** Landing page EN → tampil konten EN
- [ ] **Test 7:** Landing page ID → tampil konten ID

### Rich Text Editor
- [ ] **Test 8:** Trix editor muncul di semua block type yang memerlukan (text, hero, cta, process_steps, text_with_stats, faq)
- [ ] **Test 9:** Bold, italic, link, list berfungsi di Trix editor
- [ ] **Test 10:** Simpan konten dengan formatting → tidak corrupt
- [ ] **Test 11:** Edit ulang konten → formatting tetap ada di Trix editor
- [ ] **Test 12:** Landing page render HTML formatting dengan benar (bold, italic, list, link)
- [ ] **Test 13:** Tidak ada XSS injection — `<script>alert(1)</script>` tidak tereksekusi
- [ ] **Test 14:** Trix editor tidak muncul di field yang tidak memerlukan (heading, label, button_text, dll.)

### SEO
- [ ] **Test 15:** `<html lang="id">` di halaman ID, `<html lang="en">` di halaman EN
- [ ] **Test 16:** `<link rel="alternate" hreflang="en">` dan `hreflang="id"` di `<head>`
- [ ] **Test 17:** Version history restore → tidak corrupt data bilingual

---

## 8. Risiko / Catatan

### Multi-Bahasa
- **`array_merge_recursive` vs `array_replace_recursive`:** `array_merge` cocok karena items array akan digabung. Jika ada kasus di mana user ingin menghapus item, perlu mekanisme explicit delete.
- **Hidden inputs:** Alternatif jika `_preserve` key tidak diinginkan: gunakan `array_replace_recursive` tanpa hidden inputs, tapi pastikan `content.en` dan `content.id` selalu ada di payload.
- **Tidak perlu migration baru** — struktur DB sudah benar.
- **Tidak perlu dependency baru** — semua pakai built-in Laravel.

### Rich Text Editor
- **Trix vs editor lain:** Trix dipilih karena ringan, CDN-based, dan sanitasi bawaan. Jika di masa depan butuh fitur lebih (table, image upload inline), bisa upgrade ke TipTap (npm install).
- **Sanitasi double:** Trix sudah sanitasi di client-side, `strip_tags()` di server-side sebagai lapisan kedua. Tetap ada risiko jika attacker bypass client-side sanitasi.
- **Trix CSS konflik:** Trix memiliki CSS sendiri. Perlu pastikan styling Tailwind tidak override Trix. Gunakan wrapper `trix-content` class untuk output.
- **`{!! !!}` vs `{{ }}`:** Hanya field yang berasal dari Trix editor yang perlu `{!! !!}`. Field lain (heading, label, dll.) tetap `{{ }}` karena tidak mengandung HTML.
- **Version history:** Konten dengan HTML tags akan tersimpan di `content_snapshot` JSON. Restore version harus tetap berfungsi normal.
- **Tidak perlu npm install** — Trix via CDN, tidak mengubah `package.json`.