# Restaurant SaaS — AGENTS.md

## Project layout

- `restaurant-app/` — the Laravel application (everything lives here)
- `inicial.md` — base Laravel + Filament v5 SaaS stack spec
- `avanzado.md` — restaurant-specific feature requirements (menu, reservations, billing, multi-tenant)
- Root docs are **planned features**, not necessarily implemented

## Key commands (run from `restaurant-app/`)

| Command | What it does |
|---|---|
| `composer setup` | Full project setup: install, .env, key:generate, migrate, npm install, npm build |
| `composer dev` | Concurrent dev servers: `php artisan serve`, `queue:listen`, `pail` (logs), `npm run dev` |
| `composer test` | `php artisan config:clear` then `php artisan test` |
| `npm run build` / `npm run dev` | Vite asset build/dev |
| `php artisan pint` | Lint/fix code style (Laravel Pint) |

## Architecture

- **Stack**: Laravel 13, PHP 8.3+, Filament v5, Tailwind CSS 3, Alpine.js, Vite
- **Admin panel**: Filament at `/admin` (panel ID: `admin`), login required
- **Auth**: Laravel Breeze (scaffolded auth routes in `routes/auth.php`)
- **RBAC**: `spatie/laravel-permission` + `bezhansalleh/filament-shield` — Shield maps Filament resources/pages/widgets to Spatie permissions automatically; super_admin role bypasses all gates
- **Roles disponibles**: `super_admin` (bypass), `admin` (todo), `jefe_mesoneros`, `mesonero`, `jefe_cocina`, `cocinero`, `cajera` (ver `modulos-operativos.md` §3)
- **Media**: `spatie/laravel-medialibrary` (Dish, Category models implement HasMedia)
- **Audit**: `spatie/laravel-activitylog` + `pxlrbt/filament-activity-log`
- **Navigation group convention**: new Mesoneros resources use `navigationGroup = 'Mesoneros'` with `string|UnitEnum|null` typing
- **Property typing**: Filament v5 enforces exact parent property types — use `string|BackedEnum|null` for `navigationIcon`, `string|UnitEnum|null` for `navigationGroup`, and non-static `$view`

## Models (app/Models/)

| Model | Key relations | Notes |
|---|---|---|
| User | — | `HasRoles` trait (Spatie); hasMany orders/assignments/shifts/incidents as waiter |
| Category | hasMany Dish | HasMedia (icons/images) |
| Dish | belongsTo Category | HasMedia (dish photos) |
| Table | belongsTo Zone; hasMany Reservation/Order/Assignment/Incident | Now has zone_id, merged_into_id |
| Customer | hasMany Reservation | — |
| Reservation | belongsTo Customer, belongsTo Table | Status, guest_count, reservation_date |
| Zone | hasMany Table | Salón zones (Terraza, VIP, Barra) |
| Order | belongsTo Table/Waiter/Customer; hasMany OrderItems | Core comanda with status, priority, notes, internal_note |
| OrderItem | belongsTo Order/Dish | Quantity, modifiers, status, timestamps (started_at/prepared_at), kitchen_note, return_reason |
| WaiterAssignment | belongsTo Table/Waiter/Zone | Tracks active table-to-waiter mapping with is_primary |
| WaiterShift | belongsTo User (waiter) | Shift start/end, break tracking, status, is_on_break |
| Incident | belongsTo Table/Waiter/Order | Type (spill, unhappy_customer, etc.), status, resolved_by |
| WaiterProfile | belongsTo User | Extended profile (phone, active) for waiters |
| TableHistory | belongsTo Table/Waiter/Order | Log of actions (assigned, ordered, transferred, etc.) |
| WasteRecord | belongsTo Dish/User | Kitchen waste registry (reason, quantity) |

## Filament pages

| Page | Path | Group |
|---|---|---|
| MesonerosDashboard | `/admin/mesoneros-dashboard` | Mesoneros |
| EstadisticasSemanales | `/admin/estadisticas-semanales` | Mesoneros |
| CocinaDashboard | `/admin/cocina` | Cocina |

## Notifications

- `ServiceAlert` — sent to super_admin/admin for waiter help requests / abandoned tables (database)
- `KitchenAlert` — sent to super_admin/admin for kitchen overdue items, cancellations, returns, waste (database)

## Scheduled commands

| Command | Frequency |
|---|---|
| `mesoneros:detect-abandoned --minutes=15` | every 5 min |
| `cocina:check-overdue --minutes=15` | every 5 min |

## Public routes

| Route | Description |
|---|---|
| `/menu?table={id}` | Public menu with optional "Llamar mesonero" button |
| `/cocina/tv` | Read-only kitchen TV display (auto-refresh 30s) |

## Default env quirks

- **DB**: SQLite (`database/database.sqlite`), tests use `:memory:`
- **Queue**: `database` driver (not sync, except in tests)
- **Session**: `database` driver
- **Cache**: `database` store
- `.npmrc` sets `ignore-scripts=true` — npm postinstall scripts won't run

## Testing

- PHPUnit (not Pest — `phpunit.xml` at root of `restaurant-app/`)
- SQLite `:memory:` in tests, queue connection forced to `sync`
- Single test: `php artisan test --filter TestName` or `vendor/bin/phpunit tests/Path/To/Test.php`

## Style & conventions

- **Laravel Pint** for PHP CS (`composer pint`)
- **EditorConfig**: 4-space indent, UTF-8, LF endings
- **Tailwind custom colors**: `espresso`, `cream`, `gold`, `sienna` (defined in `tailwind.config.js`)
- Font stacks: `Inter` (sans), `Playfair Display` (display/serif)
- PHP 8 attributes used (`#[Fillable]`, `#[Hidden]`) over docblock annotations

## Important gotchas

- Run `composer test` (not `phpunit` directly) — it clears config first
- `composer dev` is a meta-command using `concurrently` — kills all processes when one exits
- After adding Filament resources, rerun Shield generation: `php artisan shield:generate`
- No multi-tenant package installed yet — `filament-shield.php` sets `tenant_model => null`
- Filament resource directories are organized as `Resources/{Entity}/{Entity}Resource.php` with subdirs for Pages, Schemas, Tables
