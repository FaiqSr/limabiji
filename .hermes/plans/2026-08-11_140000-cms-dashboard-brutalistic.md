# CMS Dashboard — Brutalistic-Organic Style (v2 — Blade-Only)

> **For Hermes:** Plan only. No implementation yet. Updated based on user answers to open questions.

**Goal:** Build a pure Blade CMS dashboard to manage all landing page content (headings, paragraphs, images, origins, news, testimonials) + analytics dashboard. Bilingual (EN/ID), auto-generated SEO, version control, approval workflow, scheduled publishing. Prepare architecture for future B2C e-commerce. Style: Brutalistic-Organic.

**Architecture:** Server-rendered Blade dashboard with Alpine.js for lightweight interactivity. No SPA framework. Content stored in database. Laravel localization for bilingual. Multi-tenant ready (B2B now, B2C later).

**Tech Stack:** Laravel 13, SQLite → MySQL (production), Tailwind CSS v4, Alpine.js (lightweight, inline interactivity), Dropzone.js (media upload), Chart.js (analytics), Tiptap (rich text editor via CDN), Spatie Media Library, Laravel Sanctum (auth), Laravel Localization (EN/ID).

---

## User Answers (from plan review)

| # | Question | Answer |
|---|---|---|
| 1 | User roles? | 2 roles: **admin**, **editor** |
| 2 | SEO? | Auto-generate from content, editable in CMS |
| 3 | Multilingual? | Yes — **English + Indonesian** (2 languages) |
| 4 | Version control? | Yes — keep **5 versions**, restore any |
| 5 | Approval workflow? | Yes — editor edits → admin approves |
| 6 | Scheduled publishing? | Yes — articles have `publish_at` |
| 7 | B2C timeline? | Unknown — prepare architecture, build later |

---

## Architecture (Blade-Only)

### Why Blade-Only?

- **Zero JS framework overhead:** No Inertia, Vue, React, Svelte
- **Familiar:** Team already uses Blade for landing pages
- **Fast:** Server-rendered HTML, no SPA hydration
- **Alpine.js:** 15KB library for dropdowns, modals, toggles, drag-drop — inline in HTML, no build step

### Dashboard Structure

```
/admin                    → Dashboard overview (analytics)
/admin/content            → Pages list
/admin/content/{id}/edit  → Page editor (block builder)
/admin/origins            → Origins list
/admin/origins/{id}/edit  → Origin editor
/admin/news               → Articles list
/admin/news/create        → New article
/admin/news/{id}/edit     → Article editor
/admin/testimonials       → Testimonials list
/admin/testimonials/{id}/edit → Testimonial editor
/admin/media              → Media library
/admin/analytics          → Analytics dashboard
/admin/settings           → Site settings + SEO
/admin/users              → User management (admin only)
/admin/approvals          → Pending approval queue (admin only)
```

### Blade Component Structure

```
resources/views/admin/
├── layouts/
│   └── app.blade.php          ← Dashboard shell (sidebar, header, fonts)
├── components/
│   ├── sidebar.blade.php      ← Navigation
│   ├── header.blade.php       ← Top bar (user dropdown, language switcher)
│   ├── block-editor.blade.php ← Drag-drop block builder
│   ├── blocks/
│   │   ├── hero.blade.php     ← Hero block editor
│   │   ├── text.blade.php     ← Text block editor
│   │   ├── stats.blade.php    ← Stats block editor
│   │   ├── faq.blade.php      ← FAQ block editor
│   │   ├── cta.blade.php      ← CTA block editor
│   │   └── card-grid.blade.php← Card grid block editor
│   ├── media-picker.blade.php ← Image picker modal
│   ├── rich-editor.blade.php  ← Tiptap wrapper (CDN)
│   ├── language-tabs.blade.php← EN/ID tab switcher
│   ├── seo-panel.blade.php    ← Auto-generated + editable SEO
│   ├── version-history.blade.php ← Timeline + diff + restore
│   ├── approval-badge.blade.php  ← Status indicator
│   └── toast.blade.php        ← Flash message notifications
├── dashboard/
│   └── index.blade.php        ← Analytics overview
├── content/
│   ├── index.blade.php        ← Pages list
│   └── edit.blade.php         ← Page editor
├── origins/
│   ├── index.blade.php
│   └── edit.blade.php
├── news/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
├── testimonials/
│   ├── index.blade.php
│   └── edit.blade.php
├── media/
│   └── index.blade.php
├── analytics/
│   └── index.blade.php
├── settings/
│   └── index.blade.php
├── users/
│   ├── index.blade.php
│   └── edit.blade.php
└── approvals/
    └── index.blade.php
```

---

## Database Schema (Updated)

### Core Content Models

#### 1. `pages`
```sql
id, slug, title, template, is_published, 
published_at (nullable, for scheduled publishing),
approved_by (nullable, user_id), approved_at (nullable),
created_by, updated_by, created_at, updated_at
```

#### 2. `page_blocks`
```sql
id, page_id, block_type, order, content (JSON for EN), 
content_id (JSON for Indonesian), is_visible, 
created_at, updated_at
```

#### 3. `page_translations` (NEW — bilingual)
```sql
id, page_block_id, locale (en|id), content (JSON),
created_at, updated_at
```
*Alternative: store both languages in content JSON: `{"en": {...}, "id": {...}}`*

#### 4. `origins`
```sql
id, name, slug, province, image, altitude, varietals, 
process, harvest, score, overview (EN), overview_id (ID),
flavor (JSON), farms (JSON), 
is_active, order, created_at, updated_at
```

#### 5. `articles`
```sql
id, title, slug, category, excerpt, content (EN), 
content_id (ID), image, author_id, 
status (draft|pending|approved|published|rejected),
published_at, approved_by, approved_at,
views, created_at, updated_at
```

#### 6. `testimonials`
```sql
id, name, company, content (EN), content_id (ID), 
rating, image, is_featured, order, 
created_at, updated_at
```

#### 7. `page_versions` (NEW)
```sql
id, page_id, user_id, version_number, content_snapshot (JSON),
created_at
```
- **Max 5 versions per page** — auto-delete oldest when creating 6th
- **Restore:** Copy snapshot back to page_blocks

#### 8. `media` — Spatie Media Library
```sql
id, model_type, model_id, uuid, collection_name, 
name, file_name, mime_type, disk, size, 
manipulations (JSON), custom_properties (JSON), 
order_column, created_at, updated_at
```

#### 9. `analytics_events`
```sql
id, event_type, page, user_agent, ip_address, 
referrer, session_id, properties (JSON), 
created_at
```

#### 10. `site_settings`
```sql
id, key, value (JSON), locale (en|id|null), 
group, created_at, updated_at
```

#### 11. `seo_metadata` (NEW)
```sql
id, page_id, locale (en|id), 
meta_title, meta_description, og_image, 
og_type, canonical_url, 
is_auto_generated (boolean),
created_at, updated_at
```

---

## User Roles & Permissions

### Admin
- Full access: all CRUD, user management, settings, analytics
- Approve/reject editor submissions
- Manage roles
- Publish content

### Editor
- Edit content (pages, origins, articles, testimonials)
- Upload media
- View analytics (read-only)
- Submit for approval (cannot publish directly)
- Cannot access user management or settings

### Middleware
```php
// app/Http/Middleware/AdminOnly.php
// app/Http/Middleware/EditorOrAdmin.php
// Route::middleware('auth', 'admin')->group(...)
```

---

## Bilingual Implementation (EN + ID)

### Approach: JSON-based content storage

Each block stores translations inline:
```json
{
  "hero": {
    "label": {"en": "LIMA BIJI AGRITECH", "id": "LIMA BIJI AGRITECH"},
    "heading": {"en": "SPECIALTY ENZYMATIC CIVET COFFEE", "id": "KOPI LUWAK ENZIMATIK SPECIALTY"},
    "subheading": {"en": "Cruelty-free...", "id": "Bebas eksploitasi..."}
  }
}
```

### Language Switcher (Dashboard)
- Tabs at top of each editor: **EN** | **ID**
- Switching tabs shows the respective language's content
- Save button saves both languages

### Frontend Language Detection
- Laravel localization: `app()->setLocale($locale)`
- URL prefix: `/` (default EN), `/id` (Indonesian)
- Cookie-based persistence
- Fallback: browser language detection

### Files to Create
```
lang/en/landing.php
lang/id/landing.php
app/Http/Middleware/SetLocale.php
```

---

## SEO Auto-Generation

### Rules
- **meta_title:** `{page_title} — Lima Biji Agritech` (auto)
- **meta_description:** First 160 chars of page content (auto)
- **og_image:** First image in page blocks (auto)
- **canonical_url:** Current page URL (auto)

### Editable Override
- SEO panel in page editor shows current auto-generated values
- Editor can override any field
- `is_auto_generated` flag tracks whether values are auto or manual
- Toggle: "Auto-generate" / "Custom"

### Implementation
```php
// app/Services/SeoService.php
class SeoService {
    public function generate(Page $page): array {
        $blocks = $page->blocks;
        $title = $page->title;
        $firstText = $blocks->firstWhere('block_type', 'text')?->content['body'] ?? '';
        $firstImage = $blocks->firstWhere('block_type', 'hero')?->content['image'] ?? '';
        
        return [
            'meta_title' => "$title — Lima Biji Agritech",
            'meta_description' => Str::limit(strip_tags($firstText), 160),
            'og_image' => $firstImage,
            'canonical_url' => url($page->slug),
        ];
    }
}
```

---

## Version Control

### Rules
- **Max 5 versions per page**
- Auto-save on every publish (not on draft edit)
- When creating 6th version → delete oldest
- **Restore:** Copy snapshot back to page_blocks, create new version

### Implementation
```php
// app/Models/PageVersion.php
public static function createSnapshot(Page $page, int $userId): void {
    $blocks = $page->blocks()->ordered()->get()->toJson();
    
    PageVersion::create([
        'page_id' => $page->id,
        'user_id' => $userId,
        'version_number' => self::nextVersion($page->id),
        'content_snapshot' => $blocks,
    ]);
    
    // Keep only latest 5
    self::where('page_id', $page->id)
        ->orderByDesc('version_number')
        ->skip(5)
        ->delete();
}
```

### UI
- Timeline view: version number, date, user, "Restore" button
- Diff view: side-by-side comparison (old vs new)
- Restore: confirm modal → copy snapshot → redirect to editor

---

## Approval Workflow

### Flow
```
Editor edits → submits for review → status: "pending"
Admin reviews → approves → status: "approved" → published
Admin reviews → rejects → status: "rejected" → editor revises
```

### Article Statuses
```
draft → pending → approved → published
                    ↓
                  rejected → draft
```

### Types of Content Requiring Approval
- **Articles:** Always requires approval (editor → admin)
- **Pages:** Always requires approval
- **Origins:** Always requires approval
- **Testimonials:** Always requires approval
- **Settings:** Admin only (no approval needed)

### Notification
- Flash message: "Content submitted for review"
- Admin sees badge count on sidebar: "Approvals (3)"

### Implementation
```php
// app/Models/Article.php
protected $casts = [
    'status' => 'enum:draft,pending,approved,published,rejected',
];

public function approve(int $adminId): void {
    $this->status = 'approved';
    $this->approved_by = $adminId;
    $this->approved_at = now();
    $this->save();
}

public function reject(int $adminId): void {
    $this->status = 'rejected';
    $this->approved_by = $adminId;
    $this->save();
}
```

---

## Scheduled Publishing

### Rules
- Articles have `published_at` (datetime, nullable)
- If `published_at` is in the future → status stays "approved" but not visible on frontend
- Laravel scheduler checks every minute: if `published_at <= now()` → set status to "published"
- Frontend query: `where('status', 'published')->where('published_at', '<=', now())`

### Implementation
```php
// app/Console/Commands/PublishScheduledArticles.php
// Runs every minute via Laravel scheduler
Artisan::command('articles:publish-scheduled', function () {
    Article::where('status', 'approved')
        ->where('published_at', '<=', now())
        ->update(['status' => 'published']);
})->everyMinute();
```

### UI
- DateTime picker in article editor
- "Publish now" button (sets `published_at = now()`)
- "Schedule" button (sets `published_at = future date`)
- Status badge shows: "Scheduled for Aug 15, 2026"

---

## Brutalistic-Organic Dashboard Design (Blade)

### Design Tokens

```css
:root {
    /* Brutalistic-Organic Palette */
    --brutal-concrete: #E8E4DF;
    --brutal-charcoal: #2A2A2A;
    --brutal-rust:     #C1502E;
    --brutal-moss:     #4A5D4A;
    --brutal-sand:     #D4C5AA;
    --brutal-ink:      #1A1A1A;
    --brutal-paper:    #F5F2ED;
    --brutal-error:    #D32F2F;
    --brutal-success:  #2E7D32;
    --brutal-warning:  #F9A825;

    /* Typography */
    --font-display: 'Space Grotesk', sans-serif;
    --font-body: 'Inter', sans-serif;
    --font-mono: 'JetBrains Mono', monospace;

    /* Borders */
    --border-thick: 4px solid var(--brutal-charcoal);
    --border-raw: 2px solid var(--brutal-charcoal);

    /* Shadows — hard, no blur (brutalist) */
    --shadow-brutal: 8px 8px 0 var(--brutal-charcoal);

    /* Organic shapes */
    --radius-organic: 24px 8px 24px 8px;
    --radius-blob: 60% 40% 50% 50% / 50% 60% 40% 50%;
}
```

### Dashboard Layout

```
┌──────────────────────────────────────────────────────┐
│  [SIDEBAR]          │  [HEADER BAR]                  │
│                     │  ┌────────────────────────────┐│
│  ┌───────────────┐  │  │                            ││
│  │ ⚡ Lima Biji  │  │  │                            ││
│  │   Admin       │  │  │    CONTENT AREA            ││
│  └───────────────┘  │  │                            ││
│                     │  │    (Brutalistic-Organic     ││
│  ▸ Dashboard        │  │     styled forms,          ││
│  ▸ Content          │  │     tables, cards)         ││
│  ▸ Origins          │  │                            ││
│  ▸ News             │  │                            ││
│  ▸ Testimonials     │  │                            ││
│  ▸ Media            │  │                            ││
│  ▸ Analytics        │  │                            ││
│  ▸ Settings         │  │                            ││
│  ▸ Approvals (3)    │  │                            ││
│  ▸ Users            │  │                            ││
│                     │  └────────────────────────────┘│
│  ─────────────────  │                                │
│  ← Back to Site     │                                │
└──────────────────────────────────────────────────────┘
```

### Key Styling Elements

**Tables:**
```html
<table class="w-full border-collapse">
  <thead>
    <tr class="border-b-4 border-brutal-charcoal text-left">
      <th class="p-4 font-display text-lg">Title</th>
      <th class="p-4 font-display text-lg">Status</th>
    </tr>
  </thead>
  <tbody>
    <tr class="border-b-2 border-brutal-charcoal/20 hover:bg-brutal-concrete/50">
      <td class="p-4 font-medium">Homepage</td>
      <td class="p-4">
        <span class="px-3 py-1 border-2 border-brutal-success text-brutal-success font-bold text-xs uppercase">
          Published
        </span>
      </td>
    </tr>
  </tbody>
</table>
```

**Cards:**
```html
<div class="border-4 border-brutal-charcoal p-6 shadow-brutal bg-brutal-paper"
     style="border-radius: 24px 8px 24px 8px;">
  <h3 class="font-display text-2xl">Page Title</h3>
  <p class="font-body text-brutal-ink/70">Description</p>
</div>
```

**Buttons:**
```html
<!-- Primary -->
<button class="border-4 border-brutal-charcoal bg-brutal-rust text-white font-display text-lg px-8 py-3 shadow-brutal hover:translate-x-1 hover:translate-y-1 hover:shadow-none transition-all">
  Save Changes
</button>

<!-- Secondary -->
<button class="border-4 border-brutal-charcoal bg-transparent text-brutal-charcoal font-display text-lg px-8 py-3 hover:bg-brutal-charcoal hover:text-white transition-all">
  Cancel
</button>
```

**Forms:**
```html
<label class="block font-display text-lg mb-2 text-brutal-charcoal">
  Heading
</label>
<input type="text"
       class="w-full border-4 border-brutal-charcoal p-4 font-body text-lg bg-white focus:outline-none focus:border-brutal-rust transition-colors"
       placeholder="Enter page heading...">
```

**Organic shapes (decorative):**
```html
<!-- Floating blob behind cards -->
<div class="absolute -z-10 w-64 h-64 bg-brutal-rust/10"
     style="border-radius: 60% 40% 50% 50% / 50% 60% 40% 50%;"></div>
```

---

## Vanilla JS / Alpine.js Interactivity

### What Alpine.js Handles
- Dropdown menus (sidebar toggle, user menu)
- Modal open/close (media picker, confirm dialogs)
- Tab switching (EN/ID language tabs, content sections)
- Toggle visibility (SEO panel, advanced settings)
- Drag-drop reorder (block editor — Alpine.js + SortableJS)
- Toast notifications (flash messages)
- Character counter (SEO description)
- Inline editing (click to edit text)

### Alpine.js Example: Language Tabs
```html
<div x-data="{ locale: 'en' }">
  <div class="flex gap-2 mb-4">
    <button @click="locale = 'en'"
            :class="locale === 'en' ? 'border-brutal-rust' : 'border-transparent'"
            class="border-b-4 px-4 py-2 font-display text-lg transition-all">
      🇬🇧 English
    </button>
    <button @click="locale = 'id'"
            :class="locale === 'id' ? 'border-brutal-rust' : 'border-transparent'"
            class="border-b-4 px-4 py-2 font-display text-lg transition-all">
      🇮🇩 Indonesia
    </button>
  </div>

  <div x-show="locale === 'en'">
    <input type="text" name="content[en][heading]" placeholder="English heading...">
  </div>
  <div x-show="locale === 'id'">
    <input type="text" name="content[id][heading]" placeholder="Judul bahasa Indonesia...">
  </div>
</div>
```

### Alpine.js Example: Block Reorder (with SortableJS CDN)
```html
<div x-data="blockReorder()" x-init="init()">
  <template x-for="(block, index) in blocks" :key="block.id">
    <div class="border-4 border-brutal-charcoal p-4 cursor-move"
         :data-id="block.id">
      <span x-text="block.type"></span>
      <button @click="removeBlock(index)">×</button>
    </div>
  </template>
  <button @click="addBlock('text')">+ Add Text Block</button>
</div>

<script>
  function blockReorder() {
    return {
      blocks: [],
      init() {
        // Load from existing data
        this.blocks = JSON.parse(document.getElementById('blocks-data').textContent);
        // Initialize SortableJS
        new Sortable(this.$el, {
          handle: '.cursor-move',
          onEnd: (evt) => {
            const moved = this.blocks.splice(evt.oldIndex, 1)[0];
            this.blocks.splice(evt.newIndex, 0, moved);
          }
        });
      },
      addBlock(type) { this.blocks.push({ id: Date.now(), type, content: {} }); },
      removeBlock(index) { this.blocks.splice(index, 1); }
    }
  }
</script>
```

### Libraries (CDN, no build step)
```html
<!-- Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- SortableJS (drag-drop) -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.x.x/Sortable.min.js"></script>

<!-- Chart.js (analytics) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.x.x/dist/chart.umd.min.js"></script>

<!-- Dropzone.js (media upload) -->
<script src="https://unpkg.com/dropzone@6.x.x/dist/dropzone-min.js"></script>

<!-- Tiptap (rich text) -->
<script src="https://cdn.jsdelivr.net/npm/@tiptap/core@2.x.x/dist/tiptap.umd.min.js"></script>
```

---

## Implementation Phases (Updated)

### Phase 1: Core CMS Infrastructure (18 tasks)

1. Install dependencies (Alpine.js via CDN, Spatie Media, Sanctum)
2. Create Page model + migration
3. Create PageBlock model + migration
4. Create Origin model + migration (with bilingual fields)
5. Create Article model + migration (with status, published_at)
6. Create Testimonial model + migration (with bilingual fields)
7. Create SiteSetting model + migration
8. Create SeoMetadata model + migration
9. Create PageVersion model + migration
10. Create AnalyticsEvent model + migration
11. Create language files (lang/en/landing.php, lang/id/landing.php)
12. Create SetLocale middleware
13. Seed origins, articles, testimonials tables (migrate hardcoded EN + ID)
14. Seed page_blocks table (homepage, innovation, contact)
15. Create Admin/PageController (CRUD)
16. Create Admin/OriginController (CRUD)
17. Create Admin/ArticleController (CRUD with approval)
18. Create Admin/TestimonialController (CRUD)

### Phase 2: Admin Dashboard UI (Blade + Alpine.js) (22 tasks)

19. Create admin layout (sidebar, header, brutalistic-organic CSS)
20. Create admin login page (brutalistic style)
21. Create dashboard overview (analytics summary cards)
22. Create content/pages list (table with status badges)
23. Create page editor (block builder with drag-drop)
24. Create block components (hero, text, stats, faq, cta, card-grid)
25. Create language tabs component (EN/ID switcher)
26. Create SEO panel component (auto-generate + editable)
27. Create origins list (grid cards)
28. Create origin editor form (with image upload)
29. Create articles list (table with filters: draft, pending, approved, published)
30. Create article editor (Tiptap, scheduled publish, submit for review)
31. Create testimonials list
32. Create testimonial editor form
33. Create media library (Dropzone upload, grid view)
34. Create media picker component (modal, select from library)
35. Create settings page (site info, contact, social links)
36. Create user management (list, edit roles — admin only)
37. Create approvals queue (pending list, approve/reject buttons)
38. Create version history panel (timeline, diff, restore)
39. Add toast notifications (flash messages)
40. Add loading states (skeleton cards)

### Phase 3: Frontend Integration (Bilingual) (10 tasks)

41. Refactor LandingPages controller (fetch from DB, pass locale)
42. Update index.blade.php (loop page_blocks with locale)
43. Update innovation.blade.php
44. Update news.blade.php (fetch articles from DB)
45. Update testimonials.blade.php (fetch from DB)
46. Update contact.blade.php (fetch settings)
47. Update origins.blade.php (fetch from origins table)
48. Add language route prefix (`/` and `/id`)
49. Add language switcher to frontend navbar
50. Test all pages in both EN and ID

### Phase 4: Analytics & Tracking (7 tasks)

51. Create AnalyticsEvent model + migration
52. Create TrackPageView middleware
53. Create AnalyticsService (aggregate stats)
54. Create analytics.js (client-side tracker via CDN)
55. Create analytics dashboard (Chart.js: page views, top pages, origins)
56. Add real-time visitors (polling every 30s)
57. Add export (CSV download)

### Phase 5: Testing & Optimization (5 tasks)

58. Write feature tests (PHPUnit: API endpoints)
59. Write browser tests (Laravel Dusk: dashboard flows)
60. Optimize queries (eager loading)
61. Add database indexes
62. Performance audit (Lighthouse)

---

## File Structure (Updated — Blade-Only)

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── DashboardController.php
│   │   │   ├── PageController.php
│   │   │   ├── OriginController.php
│   │   │   ├── ArticleController.php
│   │   │   ├── TestimonialController.php
│   │   │   ├── MediaController.php
│   │   │   ├── AnalyticsController.php
│   │   │   ├── SettingsController.php
│   │   │   ├── UserController.php
│   │   │   └── ApprovalController.php
│   │   └── LandingPages.php (refactored)
│   ├── Middleware/
│   │   ├── AdminOnly.php
│   │   ├── EditorOrAdmin.php
│   │   ├── SetLocale.php
│   │   └── TrackPageView.php
│   └── Requests/
│       ├── StorePageRequest.php
│       ├── StoreOriginRequest.php
│       ├── StoreArticleRequest.php
│       └── ...
├── Models/
│   ├── Page.php
│   ├── PageBlock.php
│   ├── PageVersion.php
│   ├── Origin.php
│   ├── Article.php
│   ├── Testimonial.php
│   ├── SiteSetting.php
│   ├── SeoMetadata.php
│   ├── AnalyticsEvent.php
│   └── User.php
└── Services/
    ├── SeoService.php
    ├── AnalyticsService.php
    └── VersionService.php

database/
├── migrations/
│   ├── 2026_08_11_100000_create_pages_table.php
│   ├── 2026_08_11_100001_create_page_blocks_table.php
│   ├── 2026_08_11_100002_create_origins_table.php
│   ├── 2026_08_11_100003_create_articles_table.php
│   ├── 2026_08_11_100004_create_testimonials_table.php
│   ├── 2026_08_11_100005_create_site_settings_table.php
│   ├── 2026_08_11_100006_create_seo_metadata_table.php
│   ├── 2026_08_11_100007_create_page_versions_table.php
│   └── 2026_08_11_100008_create_analytics_events_table.php
└── seeders/
    ├── ContentSeeder.php
    ├── UserSeeder.php (admin + editor accounts)
    └── LanguageSeeder.php (EN/ID fallback content)

resources/
├── views/
│   ├── admin/
│   │   ├── layouts/
│   │   │   └── app.blade.php
│   │   ├── components/
│   │   │   ├── sidebar.blade.php
│   │   │   ├── header.blade.php
│   │   │   ├── block-editor.blade.php
│   │   │   ├── blocks/
│   │   │   │   ├── hero.blade.php
│   │   │   │   ├── text.blade.php
│   │   │   │   ├── stats.blade.php
│   │   │   │   ├── faq.blade.php
│   │   │   │   ├── cta.blade.php
│   │   │   │   └── card-grid.blade.php
│   │   │   ├── media-picker.blade.php
│   │   │   ├── rich-editor.blade.php
│   │   │   ├── language-tabs.blade.php
│   │   │   ├── seo-panel.blade.php
│   │   │   ├── version-history.blade.php
│   │   │   ├── approval-badge.blade.php
│   │   │   └── toast.blade.php
│   │   ├── dashboard/
│   │   │   └── index.blade.php
│   │   ├── content/
│   │   │   ├── index.blade.php
│   │   │   └── edit.blade.php
│   │   ├── origins/
│   │   │   ├── index.blade.php
│   │   │   └── edit.blade.php
│   │   ├── news/
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   └── edit.blade.php
│   │   ├── testimonials/
│   │   │   ├── index.blade.php
│   │   │   └── edit.blade.php
│   │   ├── media/
│   │   │   └── index.blade.php
│   │   ├── analytics/
│   │   │   └── index.blade.php
│   │   ├── settings/
│   │   │   └── index.blade.php
│   │   ├── users/
│   │   │   ├── index.blade.php
│   │   │   └── edit.blade.php
│   │   ├── approvals/
│   │   │   └── index.blade.php
│   │   └── auth/
│   │       └── login.blade.php
│   └── landingpages/ (refactored)
├── css/
│   ├── app.css (frontend — unchanged)
│   └── admin.css (Brutalistic-Organic theme)
└── lang/
    ├── en/
    │   └── landing.php
    └── id/
        └── landing.php

routes/
├── web.php (public routes)
└── admin.php (admin routes — auth + role middleware)

public/
└── js/
    └── analytics.js (compiled tracker)
```

---

## Timeline Estimate (Updated)

| Phase | Tasks | Duration |
|---|---|---|
| Phase 1: Core CMS | 18 | 2 weeks |
| Phase 2: Dashboard UI | 22 | 3 weeks |
| Phase 3: Frontend Integration | 10 | 1 week |
| Phase 4: Analytics | 7 | 1 week |
| Phase 5: Testing | 5 | 3 days |

**Total:** 62 tasks, ~7-8 weeks

**MVP (Phases 1-3):** 50 tasks, ~6 weeks

---

## Risks & Tradeoffs (Updated)

| Risk | Mitigation |
|---|---|
| Blade-only limits SPA-like UX | Alpine.js handles dropdowns, modals, tabs; server round-trips for saves |
| No hot-reload for dashboard | `npm run dev` still works for CSS; Blade changes auto-refresh |
| Tiptap via CDN may be slower | Cache CDN scripts; consider self-hosting in production |
| Bilingual content doubles editing time | Smart defaults + auto-fill (copy EN → ID with "translate later" flag) |
| Version control JSON blobs | Keep max 5 versions; compress old snapshots |

---

## Success Criteria

**Phase 1 Complete:**
- [ ] All content migrated from hardcoded arrays to database
- [ ] Admin can create/edit/delete pages, origins, articles, testimonials
- [ ] Authentication works (admin login, editor login)
- [ ] Bilingual data stored (EN + ID for all content)
- [ ] SEO auto-generated (meta titles, descriptions)

**Phase 2 Complete:**
- [ ] Dashboard UI loads (Blade + Alpine.js, Brutalistic-Organic style)
- [ ] Admin can edit homepage via block editor (drag-drop reorder)
- [ ] Admin can upload/manage images via Dropzone
- [ ] Language tabs work (EN/ID switch)
- [ ] Approval workflow works (editor submits → admin approves)
- [ ] Version history works (save, view, restore)
- [ ] Scheduled publishing works (articles go live at publish_at)

**Phase 3 Complete:**
- [ ] Frontend landing pages render CMS content in both languages
- [ ] Language switcher works on frontend (`/` vs `/id`)
- [ ] All pages load < 2s (with caching)

**Phase 4 Complete:**
- [ ] Analytics dashboard shows real data (page views, top pages, origins)
- [ ] Real-time visitors counter works
- [ ] Export analytics to CSV

**Phase 5 Complete:**
- [ ] All tests pass (PHPUnit + Dusk)
- [ ] Database structure supports B2C expansion
- [ ] Dashboard ready for B2C store section (future)

---

**Plan saved. Blade-only, Alpine.js, 2 roles, bilingual EN/ID, auto-SEO, version control, approval workflow, scheduled publishing. Ready for execution when you are, Cel.**