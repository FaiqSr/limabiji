# AGENTS.md — Lima Biji

> Compact instruction file for AI coding agents working on **Lima Biji** —
> a Laravel landing page + admin CMS for a coffee brand (bilingual EN/ID).
>
> If this file conflicts with a direct instruction from the user (Faiq / "Cel"),
> the user wins.

---

## Stack

| Component   | Detail (verified against composer.json / package.json / lockfile) |
|-------------|-------------------------------------------------------------------|
| Framework   | Laravel 13 (constraint `^13.8`; installed `v13.24.0`)             |
| PHP         | `^8.3` required; runtime **PHP 8.4.24**                          |
| Frontend    | Blade + Tailwind CSS v4 (`@tailwindcss/vite ^4.3.3`) + daisyUI 5 |
| Build       | Vite 8 (`^8.0.0`), `fontaine ^0.8.0` for font metrics           |
| Database    | SQLite at `database/database.sqlite`                              |
| Testing     | PHPUnit 12 (`^12.5.12`)                                           |
| Code style  | Laravel Pint (`^1.27`)                                            |
| Dev tools   | `laravel/pail ^1.2.5`, `laravel/pao ^1.0.6`, `concurrently ^9.0.1` |

No CI workflows exist (no `.github/workflows/`). Verification is manual.

---

## Commands

```bash
composer setup         # full bootstrap: install, .env copy, key:generate, migrate, npm install, npm build
composer dev           # concurrent: artisan serve + queue:listen + pail + vite
composer test          # config:clear then php artisan test
php artisan test       # run test suite
vendor\bin\pint --test # check code style WITHOUT changing files
vendor\bin\pint        # auto-format
npm run build          # build frontend to public/build
```

> **Gotcha:** Pint is a Composer dev-dependency, **not** an Artisan command. `php artisan pint`
> errors with `Command "pint" is not defined` — always run `vendor\bin\pint`.

### Running a single test

```bash
php artisan test --filter=ArticleTest
php artisan test tests/Feature/Admin/ArticleTest.php
php artisan test --filter=test_can_view_news_index
```

### Required command order for verification

```
php artisan pint --test  →  php artisan test  →  npm run build
```

---

## Architecture

### Routing & bootstrap

- `bootstrap/app.php` loads `routes/admin.php` inside `withRouting(... then: ...)` using the
  Route facade (no callback parameter). Do not change this pattern without user confirmation.
- `routes/web.php` — public landing pages + sitemap + locale switch.
- `routes/admin.php` — all admin routes under `/admin`, protected by `auth` + `editor.or.admin`.
  The `/admin/users/*` sub-group adds `admin` middleware.
- Auth login/logout lives in `routes/admin.php` (not `routes/web.php`), handled by
  `Auth/AuthenticatedSessionController`.

### Middleware

Only two **aliases** are registered in `bootstrap/app.php`:

| Alias             | Class              | Allows                |
|-------------------|--------------------|-----------------------|
| `admin`           | `AdminOnly`        | `role === 'admin'`    |
| `editor.or.admin` | `EditorOrAdmin`    | `role in admin/editor`|

`SetLocale` and `TrackPageView` are **not aliases** — they are appended to the `web` middleware
group globally in `bootstrap/app.php`.

### Role system

Roles are plain strings on the `users.role` column (`admin`, `editor`). No enum class, no
Spatie permission package. Middleware checks via `in_array($user->role, [...])`.

### Two CSS entry points (do not mix styles)

`vite.config.js` compiles two separate stylesheets:

| Entry                | Purpose       | Palette                                   | Fonts              |
|----------------------|---------------|-------------------------------------------|--------------------|
| `resources/css/app.css`     | Landing page  | Dark forest + roasted coffee; primary `#069F80` | Bebas Neue + Rubik |
| `resources/css/admin.css`   | Admin dashboard | Clean minimalist slate; nordic green `#079f81`   | Inter               |

Landing views use Tailwind utility classes + custom component classes from `app.css`
(`nav-link`, `section-title`, `card-solid`, `article-body`, `animate-marquee`).
Admin views use custom CSS classes from `admin.css` (`btn`, `btn-primary`, `card-modern`,
`table-modern`, `stat-card`, `badge-*`).

### Bilingual content

- `lang/en/` and `lang/id/` each contain `nav.php` and `landing.php` translation files.
- Locale is set via `SetLocale` middleware (session/cookie) and switched via `POST /locale`.
- New public-facing content must be provided in both EN and ID.

### Toggle-active pattern

Three resources use `POST /{resource}/{id}/toggle-active`:
- `InnovationStepController::toggleActive`
- `CertificateController::toggleActive`
- `FaqController::toggleActive`

Follow the same pattern when adding similar features.

---

## Testing

- `phpunit.xml` sets `DB_DATABASE=":memory:"` — tests use in-memory SQLite and never touch
  the file DB.
- Admin feature tests use `RefreshDatabase` + `$this->seed()` in `setUp()`, then act as
  the seeded admin user: `User::where('role', 'admin')->first()`.
- Test files mirror the controller structure: `tests/Feature/Admin/{Name}Test.php`.
- Always add tests for new admin features; minimum: verify editor vs admin access.

---

## Environment gotchas

- `.env.example` uses `database` driver for **session, queue, and cache**.
  This means `php artisan migrate` must run before the app serves traffic.
- **Never modify `.env`** — tell the user if something needs changing there.
- `FILESYSTEM_DISK=local` by default (media stored in `storage/app/public/`).
- A custom `GET /storage/{path}` route serves files from `storage/app/public/` (no symlink required).

---

## Dev credentials (local only)

| Role    | Email                   | Password  |
|---------|-------------------------|-----------|
| Admin   | `admin@limabiji.com`   | `password`|
| Editor  | `editor@limabiji.com`  | `password`|

Admin login: `http://localhost:8000/admin/login`

---

## Guardrails

1. **Never modify `.env`** or display its credentials in output.
2. **Never edit an existing migration** — create a new one.
3. **Never run `migrate:fresh` / `db:wipe`** without explicit user approval (destroys all data).
4. **Never lower the PHP requirement** in `composer.json` (must stay `^8.3`).
5. **Never change core architecture** (`bootstrap/app.php`, middleware aliases, auth schema)
   without user confirmation.
6. **Never add new dependencies** (composer or npm) without user approval.
7. **Never deploy** to any production server.
8. Commit messages follow [Conventional Commits](https://www.conventionalcommits.org/)
   (`feat:`, `fix:`, `refactor:`, `style:`, `docs:`, `test:`, `chore:`).
9. Do not push or create PRs unless asked.
10. Never claim "success" without real tool output as proof (exit code 0, green tests).

---

*Last verified: 2026-08-22 against commit 232265d*
