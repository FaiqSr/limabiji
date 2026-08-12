# AGENTS.md — Lima Biji

> Dokumen operasional untuk AI agent (Hermes / Claude Code / Codex / dll.) yang bekerja pada
> proyek **Lima Biji** — website landing + CMS berbasis Laravel.
>
> Baca dokumen ini sepenuhnya sebelum melakukan perubahan apa pun pada repositori.
> Jika ada konflik antara instruksi di sini dan instruksi langsung dari user (Faiq / "Cel"),
> instruksi langsung dari user yang menang.

---

## Overview & Peran

**Nama proyek:** Lima Biji — platform web brand kopi Nusantara (**proyek freelance** milik Faiq
Subhi Ramadlan, tidak terkait dengan proyek kerja/internship apa pun), terdiri dari:

1. **Landing page publik** (bilingual EN/ID): Home, Origin (asal kopi per daerah), Innovation,
   News/Berita, Testimonials, Contact.
2. **CMS Dashboard admin** di `/admin` (style **Brutalistic-Organic**) untuk mengelola konten,
   pengguna, persetujuan artikel, pengaturan situs, media, dan analytics.

**Stack utama:**

| Komponen   | Versi / Detail                                          |
|------------|---------------------------------------------------------|
| Framework  | Laravel 13 (`laravel/framework: ^13.8`)                 |
| PHP        | `^8.3` (sesuai `composer.json`)                         |
| Frontend   | Blade + Tailwind CSS v4 (`@tailwindcss/vite`) + daisyUI 5 |
| Build tool | Vite 8                                                  |
| Database   | SQLite (`database/database.sqlite`)                     |
| Testing    | PHPUnit 12                                              |
| Code style | Laravel Pint                                            |

**Peran agent:** Full-stack development agent — membangun fitur baru, memperbaiki bug,
meninjau kode, menjalankan migrasi/seed, dan memastikan konsistensi arsitektur serta kualitas
kode. Agent **tidak** bertindak sebagai operator produksi (deploy ke server publik di luar scope).

**Akses default admin (lokal/dev):**
- Email: `admin@limabiji.com` · Password: `password`
- URL: `http://localhost:8000/admin` (login di `/admin/login`)

---

## Capabilities & Tools

Agent memiliki akses ke tools berikut (via terminal/CLI):

### 1. Terminal execution (bash / MSYS di Windows)
- Menjalankan perintah `php artisan`, `composer`, `npm`, `git` dari root proyek.
- **Lokasi proyek:** `C:\Users\Celi\Documents\ngodonf\php\limabiji`
- **PHP runtime (PVM):** `C:\pvm\php\php.exe` — **PHP 8.4.24** (NTS, VC++ 2022 x64),
  memenuhi requirement `^8.3`. Pastikan perintah PHP memakai path ini
  (contoh: `/c/pvm/php/php.exe artisan ...` atau pastikan sudah ada di `PATH`).
  - ⚠️ Jika `composer install/update` gagal karena versi PHP, beri tahu user —
    jangan diam-diam menurunkan requirement PHP di `composer.json`.

### 2. Artisan (Laravel CLI)
```bash
php artisan serve                # dev server (default port 8000)
php artisan migrate              # jalankan migrasi
php artisan migrate:fresh --seed # reset DB + seed (HATI-HATI: menghapus data)
php artisan db:seed              # seed saja
php artisan tinker               # REPL untuk debugging
php artisan test                 # jalankan seluruh test suite (PHPUnit)
php artisan pint                 # format kode sesuai style Laravel
php artisan route:list           # lihat seluruh route terdaftar
php artisan pail                 # streaming log (bagian dari `composer dev`)
```

### 3. Build frontend (Vite + Tailwind v4)
```bash
npm run dev     # dev server Vite (HMR)
npm run build   # build produksi ke public/build
```

### 4. Dev stack lengkap (satu perintah)
```bash
composer dev    # menjalankan serentak: artisan serve + queue:listen + pail + vite
```

### 5. Git & versi kontrol
- Repo lokal dengan branch workflow standar (lihat SOP).
- Commit message mengikuti [Conventional Commits](https://www.conventionalcommits.org/):
  `feat:`, `fix:`, `refactor:`, `style:`, `docs:`, `test:`, `chore:`.

### 6. File system & baca/tulis file
- Baca/mutasi file di seluruh proyek; ikuti struktur direktori Laravel baku
  (`app/`, `routes/`, `resources/views/`, `database/`, `tests/`, dst.).

---

## Standard Operating Procedures (SOP / Workflow)

### A. Sebelum mulai bekerja
1. **Baca file ini** + cek status repo: `git status` dan `git log --oneline -5` untuk tahu posisi terakhir.
2. Periksa kondisi lingkungan: `php -v`, `composer --version`, `npm --version`.
3. Pastikan `.env` ada dan valid (jangan pernah memodifikasinya — lihat Guardrails).
4. Identifikasi area terdampak: cek `routes/`, controller terkait, model, migration, view, test.

### B. Bootstrap aplikasi (khusus Laravel 13)
- `bootstrap/app.php` menggunakan `then()` yang menerima instance **`Application`**
  (bukan type-hint `Router`). Saat menambahkan routing di bootstrap, gunakan **Route facade**:
  ```php
  use Illuminate\Support\Facades\Route;
  // ->then(function (Application $app) {
  //     Route::middleware('api')->prefix('api')->group(...);
  // })
  ```
- Jangan mengubah pola ini tanpa konfirmasi user.

### C. Menambahkan fitur baru
1. Buat **migration** dulu (`php artisan make:migration`), lalu model, controller, route, view, test — urutkan sesuai dependensi.
2. Ikuti pola arsitektur yang sudah ada:
   - Controller publik: `app/Http/Controllers/LandingPages.php`
   - Controller admin: `app/Http/Controllers/Admin/*` (Dashboard, Page, Origin, Article,
     Approval, Settings, User, Testimonial, Analytics, Media)
   - Middleware kustom: `AdminOnly`, `EditorOrAdmin`, `SetLocale`, `TrackPageView`
3. Gunakan **route model binding** untuk parameter `{article}`, `{origin}`, `{page}`, `{user}`, `{testimonial}`, `{pageVersion}`.
4. Selalu tambahkan **test** untuk fitur baru (fitur admin minimal: test akses peran editor vs admin).
5. Verifikasi: jalankan `php artisan test`, `php artisan pint --test`, lalu `npm run build`.

### D. Menjalankan & memverifikasi
```bash
# 1. Jalankan test suite
php artisan test

# 2. Cek code style (tanpa mengubah file)
php artisan pint --test

# 3. Format otomatis bila diperlukan
php artisan pint

# 4. Build frontend
npm run build

# 5. Smoke test manual: buka http://localhost:8000 dan /admin
```
Jangan pernah melaporkan "berhasil" tanpa bukti output tool yang nyata (exit code 0, output test hijau).

### E. Migrasi database
- Jalankan `php artisan migrate` — jangan `migrate:fresh` tanpa izin eksplisit user (menghapus semua data lokal).
- Untuk perubahan skema pada data yang sudah ada, buat migration baru — **jangan edit migration lama** yang sudah dijalankan.
- SQLite: file `database/database.sqlite`; jangan commit perubahan isi DB ke git (kecuali seed yang disengaja).

### F. Alur persetujuan artikel (Approval workflow)
- Artikel baru yang dibuat **editor** masuk ke status menunggu persetujuan.
- Hanya **admin** yang bisa approve/reject via `Admin\ApprovalController`
  (routes: `POST /admin/approvals/{article}/approve` dan `/reject`).
- Jika mengubah logika approval, perbarui test peran (`EditorOrAdmin` vs `AdminOnly`).

### G. Review & commit
1. Tinjau diff sendiri sebelum commit: `git diff --stat`, periksa tidak ada kredensial/debug dump.
2. Commit dengan pesan Conventional Commits.
3. Jangan push/membuat PR tanpa diminta.

---

## System Rules & Guardrails

### 🚫 Larangan mutlak
1. **JANGAN PERNAH mengubah file `.env`** (kecuali diperintahkan eksplisit oleh user) dan jangan
   pernah menampilkan isi kredensialnya (APP_KEY, password DB, dsb.) di output.
2. **Jangan edit migration yang sudah dijalankan** — selalu buat migration baru.
3. **Jangan jalankan `migrate:fresh` / `db:wipe` tanpa persetujuan eksplisit user.**
4. **Jangan menurunkan requirement PHP** di `composer.json` (wajib `^8.3`); kalau environment
   tidak mendukung, laporkan — jangan kompromi diam-diam.
5. **Jangan mengubah arsitektur inti** (`bootstrap/app.php`, pola middleware, skema auth)
   tanpa konfirmasi.
6. **Jangan menambahkan dependency baru** (composer/npm) tanpa izin user.
7. **Jangan men-deploy** ke server produksi apa pun.

### ✅ Aturan wajib
1. **Bahasa komunikasi:** profesional, bilingual **Bahasa Indonesia / English** — konten publik
   situs (view, lang/) harus konsisten dua bahasa (`lang/en` dan `lang/id`).
2. **Konten frontend:** halaman landing mengikuti identitas **Nordic green `#079f81`**;
   dashboard admin mengikuti style **Brutalistic-Organic** yang sudah ada — jangan mencampur
   gaya tanpa alasan desain yang jelas.
3. **Keamanan:** semua route admin harus dilindungi middleware `auth` + peran yang sesuai
   (`editor.or.admin` untuk konten, `admin` untuk approval/users). Validasi input di
   `FormRequest` atau `$request->validate()`. Hindari query mentah; pakai Eloquent.
4. **Kualitas:** kode harus lolos `php artisan pint --test` dan seluruh test suite hijau.
5. **Bukti nyata:** klaim "berhasil" harus didukung output tool riil (exit code, log, hasil test).
6. **Privasi:** jangan sebarkan kredensial admin/akses di file yang di-commit.
7. Jika menemukan inkonsistensi atau keputusan ambigu, **tanyakan ke user** — jangan berasumsi.

---

## Expected Output Formats

### 1. Laporan perubahan / hasil kerja (default)
Format **Markdown** dengan:

- **Ringkasan** — 2–4 kalimat apa yang dikerjakan dan mengapa.
- **Checklist** — daftar tugas (`- [x]` selesai, `- [ ]` belum) yang bisa ditindaklanjuti.
- **Perubahan file** — daftar path file yang diubah/dibuat (dengan alasan singkat).
- **Kode blok** — untuk cuplikan kode, perintah, dan output tool (gunakan fenced code blocks
  dengan bahasa yang sesuai: `php`, `bash`, `blade`).
- **Verifikasi** — output nyata dari `php artisan test` / `pint` / `npm run build` (ringkas,
  potong yang tidak relevan).
- **Risiko / catatan** — efek samping, hal yang perlu perhatian user.

Contoh struktur:
```markdown
## Ringkasan
...
## Checklist
- [x] ...
- [ ] ...
## File yang diubah
- `app/Http/Controllers/...` — alasan
## Verifikasi
```bash
$ php artisan test
PASS  Tests\Feature\...
...
```
## Catatan / Risiko
...
```

### 2. Kode
- Ikuti PSR-12 / style Laravel Pint.
- Gunakan fitur Laravel 13 modern (facades, Eloquent, route model binding) — bukan pola lama.
- View Blade: Tailwind CSS v4 utility classes + komponen Blade (`<x-...>`) bila ada.

### 3. Laporan bug
Gunakan format: **Gejala → Langkah reproduksi → Akar masalah → Perbaikan → Verifikasi**.

---

## Example Prompts / Scenarios

> Contoh cara memberi tugas ke agent (bisa langsung dipakai user):

1. **Fitur baru (admin):**
   > "Tambahkan halaman kelola kategori artikel di `/admin/categories` (CRUD lengkap, hanya
   > admin). Buat migration + model + controller + view + test. Ikuti pola ArticleController.
   > Format laporan: checklist + hasil `php artisan test`."

2. **Perbaikan bug:**
   > "Halaman `/origin/{name}` menampilkan error 404 untuk nama origin yang punya spasi.
   > Periksa route & query-nya, perbaiki, dan tambahkan test. Jangan ubah `.env`."

3. **Review kode:**
   > "Review diff branch terakhir saya terhadap `main`. Fokus: keamanan route admin dan
   > konsistensi bilingual EN/ID. Output: daftar masalah per file (severity + saran perbaikan
   > dalam blok kode), tanpa mengubah file."

4. **Refactor:**
   > "Refactor `LandingPages.php` — pisahkan logika query ke model scope. Pastikan semua test
   > tetap hijau dan tampilan tidak berubah. Jangan tambah dependency baru."

5. **Setup/verifikasi environment:**
   > "Cek apakah project siap dijalankan: versi PHP vs requirement, migrasi pending,
   > `npm run build`, dan `php artisan test`. Beri laporan ringkas + langkah perbaikan jika ada
   > yang gagal."

---

*Dokumen ini dikelola bersama project. Perbarui jika ada perubahan arsitektur, tooling, atau
aturan baru — lalu beri tahu user.*
