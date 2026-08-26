# Dashboard Redesign — Modern, Intuitive, High-Performance, Secure

> **For Hermes:** Implement task-by-task. Each task is bite-sized (2-5 min). Run `npm run build` after CSS/JS changes. Run `php artisan test` after PHP changes.

**Goal:** Redesign the admin dashboard from a basic stat-card layout into a modern, data-driven command center with KPI trend indicators, interactive charts, activity feed, global filters, caching, and hardened security — while preserving the Brutalistic-Organic design language.

**Architecture:** Laravel 13 (PHP 8.3+) + Blade + Tailwind CSS v4 + Alpine.js + Chart.js. Service layer for dashboard data aggregation. Redis/file cache for heavy queries. Form Request validation on all filter inputs. Gates/Policies for widget-level authorization.

**Tech Stack:** Laravel Blade, Tailwind CSS v4, Alpine.js 3.x, Chart.js 4.x (CDN), Laravel Cache (file/Redis)

---

## PASAL 1: AUDIT & ARSITEKTUR INFORMASI DASHBOARD

### 1.1 Analisis UI/UX Saat Ini

| Area | Current State | Problem |
|---|---|---|
| **Layout** | Fixed sidebar `w-64`, no collapse, no mobile drawer | Sidebar invisible on mobile — `mobileOpen` state declared but never wired to a toggle button |
| **KPI Cards** | 4 plain stat-cards (Pages, Articles, Origins, Testimonials) | No trend indicators (↑/↓ %), no period comparison, no sparklines |
| **Charts** | None — analytics page uses CSS bar divs | No real chart library, no interactive tooltips, no time-series visualization |
| **Header** | Language switcher non-functional (both links go to `/admin`), static page title | EN/ID toggle does nothing, no breadcrumb, no global search, no notification bell |
| **Filtering** | Analytics page has `?days=7\|30\|90` buttons only | No date range picker, no category filter, no quick action buttons on dashboard |
| **Activity Feed** | None | No recent activity log (articles created, approvals, logins) |
| **Responsiveness** | `lg:ml-64` on main content, but sidebar never hides on mobile | Dashboard is unusable on mobile/tablet |
| **Dark Mode** | Not implemented | Design tokens are light-only |
| **Empty States** | Basic "No data yet" text | No illustrations, no CTA in empty state |
| **Loading States** | None | No skeleton loaders, no spinner during data fetch |

### 1.2 Arsitektur Informasi Baru

```
┌─────────────────────────────────────────────────────┐
│ Sidebar (collapsible)  │  Sticky Header (search,    │
│ ┌─────────────────┐    │  notifications, user menu) │
│ │ ⚡ Lima Biji     │    ├────────────────────────────┤
│ │ Admin Panel      │    │  FilterBar (date range,    │
│ ├─────────────────┤    │  status, quick actions)    │
│ │ 📊 Dashboard     │    ├────────────────────────────┤
│ │ 📝 Content       │    │  KPI Row (4 cards w/ trend)│
│ │ 🌍 Origins       │    ├──────────┬─────────────────┤
│ │ 📰 News    [2]   │    │  Chart   │  Activity Feed  │
│ │ 💬 Testimonials  │    │  (Chart  │  (recent        │
│ │ 🖼️ Media         │    │   .js)   │   actions)      │
│ │ 📈 Analytics     │    ├──────────┴─────────────────┤
│ │ ─────────        │    │  Recent Articles Table     │
│ │ ⚙️ Settings      │    │  (status badges, pagination)│
│ │ ✅ Approvals [2] │    ├────────────────────────────┤
│ │ 👥 Users         │    │  Pending Approvals Alert   │
│ └─────────────────┘    │                            │
└────────────────────────┴────────────────────────────┘
```

**Komponen Utama Dashboard Baru:**
1. **Summary Metric Cards (KPI)** — 4 cards: Total Page Views, Unique Visitors, Published Articles, Pending Approvals — each with trend indicator (↑/↓ % vs previous period)
2. **Chart Widget** — Chart.js line chart for daily page views (7/30/90 day toggle)
3. **Activity Feed** — Last 10 admin actions (article created/updated, approval, login)
4. **Recent Articles Table** — Last 5 articles with status badges + pagination
5. **FilterBar** — Date range picker (7d/30d/90d/custom), status dropdown, quick action buttons
6. **Pending Approvals Alert** — Conditional banner if pending > 0

---

## PASAL 2: IMPLEMENTATION PLAN DETAILED

### 2.1 Design & UI Consistency Strategy

**Layout:** Collapsible sidebar (icon-only on collapse) + sticky header + 12-column grid main content.

**Design System (Brutalistic-Organic v2):**
- **Colors:** Keep existing tokens, add `--color-brutal-info: #1976D2` for charts
- **Dark mode:** Add `@variant dark` overrides for all tokens — `prefers-color-scheme` + manual toggle stored in localStorage
- **Typography:** Space Grotesk (display), Inter (body), JetBrains Mono (data) — already in place
- **Spacing:** Tailwind default scale, consistent `gap-6` between cards
- **Shadows:** Keep `shadow-brutal` for cards, add `shadow-brutal-hover` for interactive elements

**Responsiveness:**
- **Mobile (<768px):** Sidebar hidden, hamburger toggle opens drawer overlay. KPI cards stack 1-column. Chart full-width. Table horizontal scroll.
- **Tablet (768-1024px):** Sidebar collapsed to icon-only (w-20). KPI cards 2-column. Chart + activity feed side-by-side.
- **Desktop (>1024px):** Sidebar expanded (w-64). KPI cards 4-column. Full grid layout.

### 2.2 Component Breakdown

**Re-usable Components (keep existing):**
- `x-admin.nav-link` — keep, add collapse support
- `card-brutal` / `stat-card` CSS utilities — keep, extend
- `badge-*` CSS utilities — keep
- `btn-brutal-*` CSS utilities — keep
- `table-brutal` CSS — keep

**New UI Components to Create:**
| Component | Type | File |
|---|---|---|
| `StatCard` | Blade component | `resources/views/components/admin/stat-card.blade.php` |
| `ChartWidget` | Blade component | `resources/views/components/admin/chart-widget.blade.php` |
| `ActivityFeed` | Blade component | `resources/views/components/admin/activity-feed.blade.php` |
| `FilterBar` | Blade component | `resources/views/components/admin/filter-bar.blade.php` |
| `EmptyState` | Blade component | `resources/views/components/admin/empty-state.blade.php` |
| `SkeletonLoader` | Blade component | `resources/views/components/admin/skeleton.blade.php` |
| `SidebarToggle` | Blade component | `resources/views/components/admin/sidebar-toggle.blade.php` |

### 2.3 Backend, Performance & Data Layer Plan

**Query Optimization:**
- Extract dashboard queries from controller into `App\Services\DashboardService`
- Use `Cache::remember()` for aggregate queries (5-minute TTL)
- Eliminate N+1: sidebar queries `Article::where('status','pending')->count()` twice — cache it
- Use `selectRaw` for aggregations instead of collection methods

**Caching Strategy:**
```php
$stats = Cache::remember('dashboard.stats', 300, function () {
    return [
        'page_views' => AnalyticsEvent::pageViews(7),
        'unique_visitors' => AnalyticsEvent::uniqueVisitors(7),
        // ...
    ];
});
```
- Cache key includes date range: `dashboard.stats.{start}.{end}`
- Invalidate on article create/update/delete via model observer

**Data Fetching:**
- Dashboard: server-rendered (fast initial load)
- Chart data: AJAX endpoint `/admin/api/chart-data?days=30` returning JSON (Chart.js fetches async)
- Activity feed: server-rendered, no polling (keep simple)

### 2.4 Security Hardening

**Authorization (RBAC):**
- Existing `AdminOnly` and `EditorOrAdmin` middleware on routes — keep
- Add `Gate::define('view-analytics')` — editors can view, not export
- Add `Gate::define('manage-users')` — admin only (already enforced via middleware)
- Widget-level: hide Approvals/Users widgets for editors in sidebar (already done)

**Data Protection & Sanitization:**
- Blade `{{ }}` auto-escapes all output — already in place
- Chart data JSON: use `json_encode` with `JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP` flags
- Activity feed: sanitize action descriptions, never log sensitive data (passwords, tokens)

**Secure Filtering:**
- Create `App\Http\Requests\DashboardFilterRequest` Form Request:
  ```php
  'days' => ['required', 'integer', 'min:1', 'max:365'],
  'status' => ['nullable', 'string', 'in:draft,pending,published,approved,rejected'],
  'start_date' => ['nullable', 'date', 'before_or_equal:today'],
  'end_date' => ['nullable', 'date', 'after_or_equal:start_date', 'before_or_equal:today'],
  ```
- All filter inputs validated server-side, never trust client

**Error Handling:**
- Wrap dashboard queries in try-catch, return empty states on failure
- Log errors with context: `Log::error('Dashboard query failed', ['exception' => $e])`
- Never expose SQL errors to frontend — generic "Data temporarily unavailable" message
- Chart AJAX endpoint returns `{ error: false, data: [...] }` shape, never raw exceptions

---

## PASAL 3: STEP-BY-STEP TASK CHECKLIST

### Task 1: Create DashboardService (Backend Query Optimization)

**Objective:** Extract all dashboard queries into a cached service class.

**Files:**
- Create: `app/Services/DashboardService.php`

**Step 1: Create the service class**

```php
<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Origin;
use App\Models\Page;
use App\Models\Testimonial;
use App\Models\AnalyticsEvent;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    public function getStats(int $days = 7): array
    {
        return Cache::remember("dashboard.stats.{$days}", 300, function () use ($days) {
            return [
                'pages_count' => Page::count(),
                'articles_count' => Article::count(),
                'origins_count' => Origin::count(),
                'testimonials_count' => Testimonial::count(),
                'pending_articles' => Article::where('status', 'pending')->count(),
                'published_articles' => Article::where('status', 'published')->count(),
                'page_views' => AnalyticsEvent::pageViews($days),
                'unique_visitors' => AnalyticsEvent::uniqueVisitors($days),
                'top_pages' => AnalyticsEvent::topPages($days, 5),
                'page_views_prev' => AnalyticsEvent::pageViews($days * 2) - AnalyticsEvent::pageViews($days),
                'unique_visitors_prev' => AnalyticsEvent::uniqueVisitors($days * 2) - AnalyticsEvent::uniqueVisitors($days),
            ];
        });
    }

    public function getChartData(int $days = 30): array
    {
        return Cache::remember("dashboard.chart.{$days}", 300, function () use ($days) {
            return AnalyticsEvent::where('event_type', 'page_view')
                ->where('created_at', '>=', now()->subDays($days))
                ->selectRaw('DATE(created_at) as date, COUNT(*) as views')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->toArray();
        });
    }

    public function getRecentActivity(int $limit = 10): array
    {
        return Cache::remember("dashboard.activity.{$limit}", 120, function () use ($limit) {
            return Article::with('author')
                ->latest()
                ->limit($limit)
                ->get()
                ->map(fn ($a) => [
                    'type' => 'article',
                    'action' => $a->created_at == $a->updated_at ? 'created' : 'updated',
                    'title' => $a->title,
                    'author' => $a->author?->name ?? 'System',
                    'time' => $a->updated_at,
                ])
                ->toArray();
        });
    }

    public function clearCache(): void
    {
        Cache::forget('dashboard.stats.7');
        Cache::forget('dashboard.stats.30');
        Cache::forget('dashboard.chart.7');
        Cache::forget('dashboard.chart.30');
        Cache::forget('dashboard.chart.90');
        Cache::forget('dashboard.activity.10');
    }
}
```

**Step 2: Register in AppServiceProvider (optional — or use `new` directly)**

No registration needed — instantiate directly in controller.

**Step 3: Verify**

Run: `php artisan tinker` → `app(App\Services\DashboardService::class)->getStats(7);`
Expected: array with all keys, no errors.

---

### Task 2: Create DashboardFilterRequest (Security — Form Request Validation)

**Objective:** Validate all filter inputs server-side.

**Files:**
- Create: `app/Http/Requests/DashboardFilterRequest.php`

**Step 1: Create the Form Request**

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DashboardFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'editor']);
    }

    public function rules(): array
    {
        return [
            'days' => ['nullable', 'integer', 'min:1', 'max:365'],
            'status' => ['nullable', 'string', 'in:draft,pending,published,approved,rejected'],
        ];
    }

    public function getDays(): int
    {
        return min((int) $this->get('days', 7), 365);
    }
}
```

**Step 2: Verify**

Run: `php artisan tinker` → `App\Http\Requests\DashboardFilterRequest::create('/admin?days=999', 'GET')` — should fail validation on `days > 365`.

---

### Task 3: Update DashboardController to use DashboardService

**Objective:** Replace inline queries with cached service calls.

**Files:**
- Modify: `app/Http/Controllers/Admin/DashboardController.php`

**Step 1: Rewrite the controller**

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DashboardFilterRequest;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService
    ) {}

    public function index(DashboardFilterRequest $request)
    {
        $days = $request->getDays();
        $stats = $this->dashboardService->getStats($days);
        $activity = $this->dashboardService->getRecentActivity(8);
        $recentArticles = \App\Models\Article::with('author')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard.index', compact('stats', 'activity', 'recentArticles', 'days'));
    }

    public function chartData(DashboardFilterRequest $request): JsonResponse
    {
        try {
            $days = $request->getDays();
            $data = $this->dashboardService->getChartData($days);

            return response()->json([
                'error' => false,
                'labels' => array_column($data, 'date'),
                'values' => array_map('intval', array_column($data, 'views')),
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Dashboard chart data failed', [
                'exception' => $e->getMessage(),
                'days' => $request->getDays(),
            ]);

            return response()->json([
                'error' => true,
                'message' => 'Data temporarily unavailable.',
            ], 500);
        }
    }
}
```

**Step 2: Add chart data route**

In `routes/admin.php`, inside the admin group:
```php
Route::get('/api/chart-data', [DashboardController::class, 'chartData'])->name('api.chart-data');
```

**Step 3: Verify**

Run: `php artisan route:list --name=api.chart-data`
Expected: route listed.

---

### Task 4: Create StatCard Blade Component (KPI with Trend)

**Objective:** Reusable KPI card with value, label, trend indicator, and icon.

**Files:**
- Create: `resources/views/components/admin/stat-card.blade.php`

**Step 1: Create the component**

```blade
@props([
    'value' => 0,
    'label' => '',
    'icon' => '📊',
    'trend' => null,       // positive/negative number or null
    'trendLabel' => '',    // "vs last 7 days"
    'href' => null,
])

@php
    $hasTrend = $trend !== null;
    $isPositive = $hasTrend && $trend >= 0;
    $trendColor = $isPositive ? 'text-brutal-success' : 'text-brutal-error';
    $trendIcon = $isPositive ? '↑' : '↓';
    $trendValue = $hasTrend ? abs($trend) : 0;
@endphp

<div class="stat-card relative overflow-hidden">
    @if ($href)
    <a href="{{ $href }}" class="absolute inset-0 z-10" aria-label="{{ $label }}"></a>
    @endif

    <div class="flex items-start justify-between">
        <div>
            <p class="stat-value">{{ $value }}</p>
            <p class="stat-label">{{ $label }}</p>
        </div>
        <span class="text-2xl">{{ $icon }}</span>
    </div>

    @if ($hasTrend)
    <div class="mt-3 flex items-center gap-1 text-sm {{ $trendColor }} font-display">
        <span>{{ $trendIcon }} {{ $trendValue }}%</span>
        @if ($trendLabel)
        <span class="text-brutal-charcoal/40 text-xs font-mono">{{ $trendLabel }}</span>
        @endif
    </div>
    @endif
</div>
```

**Step 2: Verify**

Run: `npm run build`
Expected: PASS, no errors.

---

### Task 5: Create ChartWidget Blade Component (Chart.js Integration)

**Objective:** Reusable chart container with Chart.js initialization.

**Files:**
- Create: `resources/views/components/admin/chart-widget.blade.php`

**Step 1: Create the component**

```blade
@props([
    'title' => 'Chart',
    'id' => 'chart',
    'dataUrl' => '',
    'height' => '300px',
])

<div class="card-brutal" x-data="chartWidget('{{ $id }}', '{{ $dataUrl }}')">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-xl font-display">{{ $title }}</h3>
        <div class="flex gap-1">
            <button @click="loadData(7)" :class="days === 7 ? 'bg-brutal-charcoal text-white' : ''"
                    class="border-2 border-brutal-charcoal px-3 py-1 text-xs font-display">7D</button>
            <button @click="loadData(30)" :class="days === 30 ? 'bg-brutal-charcoal text-white' : ''"
                    class="border-2 border-brutal-charcoal px-3 py-1 text-xs font-display">30D</button>
            <button @click="loadData(90)" :class="days === 90 ? 'bg-brutal-charcoal text-white' : ''"
                    class="border-2 border-brutal-charcoal px-3 py-1 text-xs font-display">90D</button>
        </div>
    </div>

    <div style="height: {{ $height }}; position: relative;">
        <template x-if="loading">
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-brutal-charcoal/40 font-display animate-pulse">Loading chart...</div>
            </div>
        </template>
        <template x-if="error">
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-brutal-error font-display">Data temporarily unavailable.</div>
            </div>
        </template>
        <canvas :id="'canvas-' + id" x-show="!loading && !error"></canvas>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    function chartWidget(id, dataUrl) {
        return {
            id: id,
            dataUrl: dataUrl,
            days: 30,
            loading: true,
            error: false,
            chart: null,

            init() {
                this.loadData(30);
            },

            async loadData(days) {
                this.days = days;
                this.loading = true;
                this.error = false;

                try {
                    const url = this.dataUrl + '?days=' + days;
                    const res = await fetch(url);
                    const json = await res.json();

                    if (json.error) {
                        this.error = true;
                        this.loading = false;
                        return;
                    }

                    this.loading = false;

                    this.$nextTick(() => {
                        if (this.chart) {
                            this.chart.destroy();
                        }

                        const ctx = document.getElementById('canvas-' + this.id);
                        if (!ctx) return;

                        this.chart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: json.labels,
                                datasets: [{
                                    label: 'Page Views',
                                    data: json.values,
                                    borderColor: '#C1502E',
                                    backgroundColor: 'rgba(193, 80, 46, 0.1)',
                                    fill: true,
                                    tension: 0.3,
                                    pointRadius: 2,
                                    pointHoverRadius: 6,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        backgroundColor: '#2A2A2A',
                                        titleFont: { family: 'Space Grotesk' },
                                        bodyFont: { family: 'Inter' },
                                    }
                                },
                                scales: {
                                    x: {
                                        grid: { display: false },
                                        ticks: {
                                            font: { family: 'JetBrains Mono', size: 10 },
                                            color: '#2A2A2A80',
                                            maxRotation: 0,
                                        }
                                    },
                                    y: {
                                        grid: { color: 'rgba(42,42,42,0.08)' },
                                        ticks: {
                                            font: { family: 'JetBrains Mono', size: 10 },
                                            color: '#2A2A2A80',
                                        }
                                    }
                                }
                            }
                        });
                    });
                } catch (e) {
                    this.error = true;
                    this.loading = false;
                }
            }
        }
    }
</script>
@endpush
```

**Step 2: Verify**

Run: `npm run build`
Expected: PASS, no errors.

---

### Task 6: Create ActivityFeed Blade Component

**Objective:** Timeline-style activity feed showing recent admin actions.

**Files:**
- Create: `resources/views/components/admin/activity-feed.blade.php`

**Step 1: Create the component**

```blade
@props([
    'activities' => [],
    'limit' => 8,
])

<div class="card-brutal">
    <h3 class="text-xl font-display mb-4">🕐 Recent Activity</h3>

    @forelse ($activities as $activity)
    <div class="flex items-start gap-3 py-3 border-b-2 border-brutal-charcoal/10 last:border-0">
        <div class="w-8 h-8 border-2 border-brutal-charcoal flex items-center justify-center text-sm flex-shrink-0 bg-brutal-concrete">
            @switch($activity['type'])
                @case('article') 📝@break
                @case('approval') ✅@break
                @case('login') 🔑@break
                @default 📋
            @endswitch
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-display text-brutal-charcoal truncate">
                {{ $activity['author'] }} {{ $activity['action'] }} "{{ $activity['title'] }}"
            </p>
            <p class="text-xs font-mono text-brutal-charcoal/40">
                {{ \Carbon\Carbon::parse($activity['time'])->diffForHumans() }}
            </p>
        </div>
    </div>
    @empty
    <div class="text-center py-8">
        <p class="text-4xl mb-2">📭</p>
        <p class="font-display text-brutal-charcoal/50">No recent activity.</p>
    </div>
    @endforelse
</div>
```

**Step 2: Verify**

Run: `npm run build`
Expected: PASS, no errors.

---

### Task 7: Create FilterBar Blade Component

**Objective:** Global filter bar with date range and quick actions.

**Files:**
- Create: `resources/views/components/admin/filter-bar.blade.php`

**Step 1: Create the component**

```blade
@props([
    'days' => 7,
    'actions' => [],
])

<div class="card-brutal mb-6">
    <div class="flex flex-wrap items-center gap-4">
        <div class="flex items-center gap-2">
            <span class="text-sm font-display text-brutal-charcoal/60">Period:</span>
            <div class="flex border-2 border-brutal-charcoal">
                @foreach ([7, 30, 90] as $d)
                <a href="{{ request()->fullUrlWithQuery(['days' => $d]) }}"
                   class="px-3 py-1 text-sm font-display {{ $days === $d ? 'bg-brutal-charcoal text-white' : 'bg-transparent text-brutal-charcoal' }}">
                    {{ $d }}D
                </a>
                @endforeach
            </div>
        </div>

        @if (!empty($actions))
        <div class="flex items-center gap-2 ml-auto">
            @foreach ($actions as $action)
            <a href="{{ $action['url'] }}" class="btn-brutal-{{ $action['variant'] ?? 'secondary' }} text-sm px-4 py-2">
                {{ $action['label'] }}
            </a>
            @endforeach
        </div>
        @endif
    </div>
</div>
```

**Step 2: Verify**

Run: `npm run build`
Expected: PASS, no errors.

---

### Task 8: Create EmptyState Blade Component

**Objective:** Reusable empty state with icon, message, and optional CTA.

**Files:**
- Create: `resources/views/components/admin/empty-state.blade.php`

**Step 1: Create the component**

```blade
@props([
    'icon' => '📭',
    'title' => 'Nothing here yet',
    'description' => '',
    'actionUrl' => null,
    'actionLabel' => null,
])

<div class="card-brutal text-center py-12">
    <div class="text-5xl mb-4">{{ $icon }}</div>
    <h3 class="text-xl font-display text-brutal-charcoal mb-2">{{ $title }}</h3>
    @if ($description)
    <p class="text-brutal-charcoal/50 font-body max-w-md mx-auto">{{ $description }}</p>
    @endif
    @if ($actionUrl && $actionLabel)
    <a href="{{ $actionUrl }}" class="btn-brutal-primary mt-6 inline-block">{{ $actionLabel }}</a>
    @endif
</div>
```

**Step 2: Verify**

Run: `npm run build`
Expected: PASS, no errors.

---

### Task 9: Redesign Sidebar — Collapsible + Mobile Drawer

**Objective:** Make sidebar collapsible on desktop and toggleable on mobile.

**Files:**
- Modify: `resources/views/components/admin/sidebar.blade.php`

**Step 1: Rewrite sidebar with Alpine.js collapse + mobile drawer**

Key changes:
- Add `x-data="{ collapsed: localStorage.getItem('sidebarCollapsed') === 'true', mobileOpen: false }"`
- Desktop: `:class="collapsed ? 'lg:w-20' : 'lg:w-64'"` with transition
- Mobile: overlay + drawer that slides in from left
- Hamburger button in header triggers `mobileOpen = true`
- Labels hide when collapsed: `x-show="!collapsed"` on text spans
- Nav links: show icon always, show text only when expanded

**Step 2: Add mobile overlay**

```blade
<!-- Mobile Overlay -->
<div x-show="mobileOpen" x-cloak
     @click="mobileOpen = false"
     class="fixed inset-0 bg-brutal-charcoal/60 z-30 lg:hidden"
     x-transition:enter="transition-opacity"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
</div>

<!-- Sidebar -->
<aside :class="[
    'fixed top-0 left-0 z-40 h-full bg-brutal-charcoal text-white border-r-4 border-brutal-charcoal flex flex-col transition-all duration-200',
    collapsed ? 'lg:w-20' : 'lg:w-64',
    mobileOpen ? 'w-64 translate-x-0' : '-translate-x-full lg:translate-x-0'
]">
```

**Step 3: Verify**

Run: `npm run build`
Expected: PASS.

---

### Task 10: Redesign Header — Add Hamburger Toggle + Breadcrumb

**Objective:** Add hamburger menu button for mobile, breadcrumb support.

**Files:**
- Modify: `resources/views/components/admin/header.blade.php`

**Step 1: Add hamburger button (dispatches Alpine event to sidebar)**

```blade
<header class="sticky top-0 z-30 bg-brutal-paper border-b-4 border-brutal-charcoal px-4 lg:px-6 py-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <!-- Mobile hamburger -->
        <button @click="$dispatch('toggle-sidebar')"
                class="lg:hidden border-2 border-brutal-charcoal p-2 font-display"
                aria-label="Toggle menu">
            ☰
        </button>
        <h1 class="text-lg lg:text-2xl font-display text-brutal-charcoal tracking-tight">
            @yield('page_title', 'Dashboard')
        </h1>
    </div>
    <!-- ... rest stays same ... -->
</header>
```

**Step 2: Wire sidebar to listen for event**

In sidebar `x-data`, add:
```js
init() {
    window.addEventListener('toggle-sidebar', () => this.mobileOpen = !this.mobileOpen);
}
```

**Step 3: Verify**

Run: `npm run build`
Expected: PASS.

---

### Task 11: Rewrite Dashboard Index View — Full Redesign

**Objective:** Assemble all new components into the dashboard page.

**Files:**
- Modify: `resources/views/admin/dashboard/index.blade.php`

**Step 1: Rewrite the view**

```blade
@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
{{-- Filter Bar --}}
<x-admin.filter-bar :days="$days" :actions="[
    ['label' => '+ New Article', 'url' => route('admin.news.create'), 'variant' => 'primary'],
    ['label' => 'View Analytics', 'url' => route('admin.analytics.index'), 'variant' => 'secondary'],
]" />

{{-- KPI Row --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <x-admin.stat-card
        :value="$stats['page_views']"
        label="Page Views ({$days}d)"
        icon="👁"
        :trend="$stats['page_views_prev'] > 0 ? round(($stats['page_views'] - $stats['page_views_prev']) / max(1, $stats['page_views_prev']) * 100) : null"
        trend-label="vs prev"
        :href="route('admin.analytics.index')"
    />
    <x-admin.stat-card
        :value="$stats['unique_visitors']"
        label="Unique Visitors ({$days}d)"
        icon="👥"
        :trend="$stats['unique_visitors_prev'] > 0 ? round(($stats['unique_visitors'] - $stats['unique_visitors_prev']) / max(1, $stats['unique_visitors_prev']) * 100) : null"
        trend-label="vs prev"
        :href="route('admin.analytics.index')"
    />
    <x-admin.stat-card
        :value="$stats['published_articles']"
        label="Published Articles"
        icon="📰"
        :href="route('admin.news.index')"
    />
    <x-admin.stat-card
        :value="$stats['pending_articles']"
        label="Pending Approvals"
        icon="⏳"
        :href="$stats['pending_articles'] > 0 ? route('admin.approvals.index') : null"
    />
</div>

{{-- Chart + Activity Feed --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2">
        <x-admin.chart-widget
            title="Page Views Over Time"
            id="dashboard-chart"
            :data-url="route('admin.api.chart-data')"
            height="320px"
        />
    </div>
    <div class="lg:col-span-1">
        <x-admin.activity-feed :activities="$activity" />
    </div>
</div>

{{-- Recent Articles Table --}}
<div class="card-brutal mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-xl font-display">📰 Recent Articles</h3>
        <a href="{{ route('admin.news.index') }}" class="btn-brutal-secondary text-sm px-4 py-2">View All</a>
    </div>
    <div class="overflow-x-auto">
        <table class="table-brutal">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Author</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentArticles as $article)
                <tr>
                    <td class="font-display">{{ \Illuminate\Support\Str::limit($article->title, 50) }}</td>
                    <td><span class="badge-{{ $article->status }}">{{ ucfirst($article->status) }}</span></td>
                    <td class="text-sm">{{ $article->author?->name ?? '—' }}</td>
                    <td class="text-sm font-mono">{{ $article->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.news.edit', $article) }}" class="btn-brutal-secondary text-xs px-3 py-1">Edit</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <x-admin.empty-state icon="📝" title="No articles yet" description="Create your first article." :action-url="route('admin.news.create')" action-label="+ New Article" />
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Pending Alert --}}
@if ($stats['pending_articles'] > 0)
<div class="card-brutal border-brutal-rust mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-xl font-display text-brutal-rust mb-1">⚠️ Pending Approvals</h3>
            <p class="text-lg">{{ $stats['pending_articles'] }} article(s) awaiting review.</p>
        </div>
        <a href="{{ route('admin.approvals.index') }}" class="btn-brutal-primary">Review Now</a>
    </div>
</div>
@endif
@endsection
```

**Step 2: Verify**

Run: `npm run build`
Expected: PASS.

---

### Task 12: Add Chart Data AJAX Route

**Objective:** Wire the AJAX endpoint for Chart.js data fetching.

**Files:**
- Modify: `routes/admin.php`

**Step 1: Add route inside admin group**

After the dashboard route:
```php
Route::get('/api/chart-data', [DashboardController::class, 'chartData'])->name('api.chart-data');
```

**Step 2: Verify**

Run: `php artisan route:list --path=admin/api`
Expected: `GET admin/api/chart-data` listed.

---

### Task 13: Cache Invalidation — Article Observer

**Objective:** Clear dashboard cache when articles are created/updated/deleted.

**Files:**
- Create: `app/Observers/ArticleObserver.php`
- Modify: `app/Providers/AppServiceProvider.php` (or `EventServiceProvider`)

**Step 1: Create the observer**

```php
<?php

namespace App\Observers;

use App\Models\Article;
use App\Services\DashboardService;

class ArticleObserver
{
    public function __construct(
        private DashboardService $dashboardService
    ) {}

    public function created(Article $article): void
    {
        $this->dashboardService->clearCache();
    }

    public function updated(Article $article): void
    {
        $this->dashboardService->clearCache();
    }

    public function deleted(Article $article): void
    {
        $this->dashboardService->clearCache();
    }
}
```

**Step 2: Register observer**

In `AppServiceProvider::boot()`:
```php
\App\Models\Article::observe(\App\Observers\ArticleObserver::class);
```

**Step 3: Verify**

Run: `php artisan tinker` → create an article → check `Cache::get('dashboard.stats.7')` is null (cleared).

---

### Task 14: Update Analytics Page with Chart.js

**Objective:** Replace CSS bar chart with real Chart.js on analytics page.

**Files:**
- Modify: `resources/views/admin/analytics/index.blade.php`

**Step 1: Rewrite the analytics view**

Use the same `chart-widget` component. Replace the CSS bar chart section with:
```blade
<x-admin.chart-widget
    title="Daily Page Views"
    id="analytics-chart"
    :data-url="route('admin.api.chart-data')"
    height="400px"
/>
```

Keep the top pages table and stat cards. Add filter-bar at top.

**Step 2: Verify**

Run: `npm run build`
Expected: PASS.

---

### Task 15: Fix Language Switcher (Header)

**Objective:** Make EN/ID toggle actually switch locale.

**Files:**
- Modify: `resources/views/components/admin/header.blade.php`

**Step 1: Fix the links**

Replace the static links with actual locale-switching URLs:
```blade
<a href="{{ route('admin.dashboard', ['locale' => 'en']) }}" ...>EN</a>
<a href="{{ route('admin.dashboard', ['locale' => 'id']) }}" ...>ID</a>
```

**Step 2: Add locale route handling**

In `routes/admin.php`, add a locale switch route:
```php
Route::get('/locale/{locale}', function (string $locale) {
    if (in_array($locale, ['en', 'id'])) {
        session()->put('locale', $locale);
    }
    return back();
})->name('locale.switch');
```

The `SetLocale` middleware already reads from session.

**Step 3: Verify**

Run: `php artisan route:list --name=locale.switch`
Expected: route listed.

---

### Task 16: Add x-cloak Directive (Alpine.js Fix)

**Objective:** Prevent Alpine.js flash of unstyled content.

**Files:**
- Modify: `resources/views/admin/layouts/app.blade.php`

**Step 1: Add x-cloak CSS**

In `<head>`, after the Vite directive:
```blade
<style>[x-cloak] { display: none !important; }</style>
```

**Step 2: Verify**

Run: `npm run build`
Expected: PASS.

---

### Task 17: Build, Test & Verify

**Objective:** Final verification of all changes.

**Step 1: Build frontend**

Run: `npm run build`
Expected: PASS, 4+ modules, no errors.

**Step 2: Test dashboard route**

Run: `php artisan tinker`
```php
auth()->loginUsingId(1);
$service = app(App\Services\DashboardService::class);
dd($service->getStats(7));
```
Expected: array with all keys including trend data.

**Step 3: Test chart AJAX endpoint**

Run: `php artisan serve` → visit `http://localhost:8000/admin/api/chart-data?days=30`
Expected: JSON with `labels` and `values` arrays.

**Step 4: Test filter validation**

Visit `http://localhost:8000/admin?days=999`
Expected: validation error (days max 365).

**Step 5: Test mobile responsiveness**

Open dashboard in browser DevTools mobile view (375px width).
Expected: sidebar hidden, hamburger visible, KPI cards stack 1-column.

**Step 6: Test cache invalidation**

```php
// In tinker
Cache::put('dashboard.stats.7', 'test', 300);
App\Models\Article::first()->update(['title' => 'Test Update']);
Cache::get('dashboard.stats.7'); // Should be null (cleared by observer)
```

---

## Files Summary

### New Files (8)
| File | Purpose |
|---|---|
| `app/Services/DashboardService.php` | Cached dashboard data aggregation |
| `app/Http/Requests/DashboardFilterRequest.php` | Filter input validation |
| `app/Observers/ArticleObserver.php` | Cache invalidation on article changes |
| `resources/views/components/admin/stat-card.blade.php` | KPI card with trend |
| `resources/views/components/admin/chart-widget.blade.php` | Chart.js container |
| `resources/views/components/admin/activity-feed.blade.php` | Activity timeline |
| `resources/views/components/admin/filter-bar.blade.php` | Global filter bar |
| `resources/views/components/admin/empty-state.blade.php` | Empty state component |

### Modified Files (7)
| File | Changes |
|---|---|
| `app/Http/Controllers/Admin/DashboardController.php` | Use DashboardService, add chartData() |
| `routes/admin.php` | Add chart-data + locale routes |
| `resources/views/admin/dashboard/index.blade.php` | Full redesign with new components |
| `resources/views/admin/analytics/index.blade.php` | Replace CSS bars with Chart.js |
| `resources/views/components/admin/sidebar.blade.php` | Collapsible + mobile drawer |
| `resources/views/components/admin/header.blade.php` | Hamburger + fixed language switcher |
| `resources/views/admin/layouts/app.blade.php` | Add x-cloak style |
| `app/Providers/AppServiceProvider.php` | Register ArticleObserver |

### Unchanged Files (keep as-is)
- `resources/css/admin.css` — design tokens already in place
- `resources/views/components/admin/nav-link.blade.php` — keep
- All CRUD views (content, origins, news, testimonials, etc.) — keep
- All other controllers — keep

---

## Risks, Tradeoffs & Open Questions

### Risks
1. **Chart.js CDN dependency** — if CDN is down, chart won't render. Mitigation: error state shows "Data temporarily unavailable."
2. **Cache staleness** — 5-minute TTL means data can be up to 5 min old. Acceptable for dashboard. Observer clears on article changes.
3. **SQLite + aggregation** — `GROUP BY DATE()` works on SQLite but may be slow on large datasets. Migration to MySQL/PostgreSQL recommended for production.
4. **Alpine.js reactivity** — Chart.js instances need manual destroy/recreate on data change. Handled in chartWidget component.

### Tradeoffs
1. **Server-rendered vs SPA** — chose server-rendered for SEO and simplicity. Chart data via AJAX for interactivity. No full SPA complexity.
2. **File cache vs Redis** — using Laravel default (file). Switch to Redis in production for better performance.
3. **No dark mode yet** — deferred to future iteration. Design tokens support it, but implementing it properly requires touching every view.

### Open Questions
1. Should the activity feed include login/logout events? (Currently only article activity.)
2. Should we add a notification bell with unread count? (Not in scope yet.)
3. Should the chart support multiple datasets (e.g., page views + unique visitors overlay)?
4. Should we add CSV export for analytics data? (Deferred — Gate::define('export-analytics') ready.)
