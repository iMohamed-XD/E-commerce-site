# ARCHITECTURE.md

Last updated: 2026-04-08
Project: Mahly E-commerce Platform (Laravel 12)

## 1) System Overview
Mahly is a multi-role marketplace app with three major surfaces:
1. Seller surface: create/manage one shop, products, categories, orders, promo codes, feedback.
2. Buyer surface: public storefront per shop slug, cart + checkout flow.
3. Admin surface: monitor and manage sellers, shops, products, promo codes, feedback, and support payment methods.

Core framework stack:
- Backend: Laravel 12, PHP 8.2+
- Auth: Laravel Breeze + email verification + Google OAuth (Socialite)
- Frontend: Blade + Alpine.js + Tailwind + Vite
- Storage: Laravel public disk for media files

## 2) Runtime Entry Points
- App bootstrap: `bootstrap/app.php`
- Main web routes: `routes/web.php`
- Auth routes: `routes/auth.php`

Middleware aliases (registered in `bootstrap/app.php`):
- `seller` -> `App\Http\Middleware\EnsureUserIsSeller`
- `admin` -> `App\Http\Middleware\EnsureUserIsAdmin`

## 3) Domain Model (Core Entities)
Main models in `app/Models`:
- `User`: role-based account (`seller`, `simple_buyer`, `admin`), email verification, optional google_id.
- `Shop`: belongs to one seller. Holds branding and storefront settings.
- `Category`: shop-level category.
- `Product`: shop product with visibility + manual discount toggles.
- `ProductImage`: additional product gallery images.
- `PromoCode`: shop-level promo with manual active toggle.
- `Order`: buyer checkout order for one shop.
- `OrderItem`: line items snapshot for order.
- `Feedback`: seller feedback entry.
- `PaymentMethod`: admin-managed support/donation methods.
- `BlockedEmail`: admin/support utility list.

### 3.1 Shop payment fields (seller-owned)
`Shop` now contains seller-configured ShamCash fields:
- `shamcash_account_number` (nullable)
- `shamcash_qr_path` (nullable)
- `shamcash_is_active` (boolean)

### 3.2 Order payment fields (buyer checkout)
`Order` now stores checkout payment details:
- `payment_method` (`cod` or `shamcash`)
- `shamcash_transaction_number` (nullable; required when method is `shamcash`)

## 4) Database / Migration Strategy
Migrations are the source of truth under `database/migrations`.
Important table groups:
- Identity/auth: users, password reset metadata
- Commerce core: shops, categories, products, orders, order_items
- Promotions: promo_codes + discount flags
- Media: product_images, logo/hero storage paths
- Admin/support: payment_methods, feedbacks, blocked_emails

Recent payment addition:
- `2026_04_08_170000_add_shamcash_fields_to_shops_and_orders.php`

## 5) Route Architecture
Defined in `routes/web.php`:

Public routes:
- `/` -> landing page
- `/shop/{slug}` -> storefront
- `/shops/{shop:slug}/products/{product}` -> buyer product details
- `/shop/{slug}/apply-promo` -> promo validation (throttled)
- `/shop/{slug}/checkout` -> place order (throttled)

Seller routes (`auth + verified + seller`):
- Shop create/update + slug checker
- Products resource + bulk actions + discount toggle + image removal
- Categories CRUD-lite
- Promo code CRUD-lite + toggle
- Orders index + status update
- Feedback create/update

Admin routes (`/admin`, `auth + verified + admin`):
- Dashboard metrics
- Sellers, shops, products, promo codes, feedbacks management
- Payment methods management

## 6) Main Business Flows

### 6.1 Seller onboarding flow
1. Seller registers/logs in.
2. Creates shop from dashboard (`ShopController@store`).
3. Adds categories/products.
4. Publishes storefront via unique slug.

### 6.2 Buyer purchase flow
1. Buyer opens `/shop/{slug}`.
2. Adds items to Alpine local cart.
3. Optional promo code check via AJAX.
4. Checkout POST creates `Order` + `OrderItem` snapshots.

### 6.3 ShamCash flow
Seller side:
1. Seller can set ShamCash account number + QR image.
2. Seller can activate/deactivate ShamCash.
3. Data is editable and nullable.

Buyer side:
1. Payment options always include COD.
2. ShamCash option appears only if seller has active + complete ShamCash setup.
3. If buyer chooses ShamCash, account number + QR are shown.
4. Buyer must submit transfer transaction number.

Server guardrails:
- If ShamCash is requested but seller setup is invalid/inactive, checkout falls back to COD.
- ShamCash transaction number is validated when ShamCash is used.

## 7) Controller Responsibilities (High-Level)
`app/Http/Controllers`:
- `ShopController`: shop create/update/show, promo apply, checkout, slug availability.
- `ProductController`: seller product lifecycle, discount toggles, images, bulk actions.
- `OrderController`: seller order list + status updates.
- `PromoCodeController`, `CategoryController`: seller organization + marketing.
- `BuyerProductController`: public product detail page.
- `SupportController`: support/donation page + payment methods.
- `FeedbackController`: seller feedback capture.
- `LandingController`: homepage content.

`app/Http/Controllers/Admin`:
- Admin dashboards and moderation/management controllers for each domain.

## 8) View Architecture
Blade templates under `resources/views` are grouped by area:
- `dashboard/` + root `dashboard.blade.php`: seller and buyer dashboards
- `shop/`: public storefront and checkout modal
- `products/`, `categories/`, `orders/`, `promo_codes/`: seller operational pages
- `admin/`: admin panels
- `auth/`, `layouts/`, `components/`: auth and shared UI primitives

Frontend behavior pattern:
- Alpine.js manages client state for cart and checkout interactions.
- Styling is Tailwind-first with custom CSS variables per shop theme.

## 9) File Storage Conventions (public disk)
Typical paths:
- Shop logos: `shops/logos/*`
- Shop hero images: `shops/heroes/*`
- Shop ShamCash QR: `shops/shamcash/*`
- Product images: `products/*`

When replacing media, controllers attempt to delete old files to avoid orphaned storage.

## 10) Authorization and Security Model
- Route-level role gates via middleware aliases (`seller`, `admin`).
- Email verification required for dashboard/admin areas.
- Throttle on checkout and promo apply endpoints.
- Model ownership protections are enforced with policies for product/order/category/promo operations.

## 11) Config and Theming
- Shop color presets in `config/shop_colors.php`.
- Shop chosen color drives storefront CSS variables (`color_hex` accessor on `Shop`).

## 12) Quick "Where to edit" Guide
- Add/change checkout logic: `app/Http/Controllers/ShopController.php`
- Add/change seller shop settings form: `resources/views/dashboard.blade.php`
- Add/change buyer checkout UI: `resources/views/shop/show.blade.php`
- Add/change seller order display fields: `resources/views/orders/index.blade.php`
- Add/change DB fields: `database/migrations/*`
- Add/change role access: `app/Http/Middleware/*` + `bootstrap/app.php`

## 13) Local Dev Commands
- Install deps: `composer install` and `npm install`
- App key: `php artisan key:generate`
- Migrate DB: `php artisan migrate`
- Storage symlink: `php artisan storage:link`
- Run app: `php artisan serve`
- Run frontend: `npm run dev`

## 14) Notes for Future Sessions
If you are starting work on this codebase, read in this order:
1. `ARCHITECTURE.md` (this file)
2. `routes/web.php`
3. `app/Http/Controllers/ShopController.php`
4. `resources/views/dashboard.blade.php`
5. `resources/views/shop/show.blade.php`
6. Latest migrations affecting your feature area

This gives fast context without scanning the full repository each time.

## 15) Delta Log (Change Journal)
Use this section to append architecture-impacting changes so future sessions can understand recent work in under 1 minute.

Entry format (copy/paste):

```md
### YYYY-MM-DD - <Short Title>
- Summary:
- Scope:
- Backend changes:
- Frontend changes:
- Database changes:
- Routes changed:
- Breaking changes: (Yes/No + details)
- Migration required: (Yes/No)
- Manual verification done:
- Follow-ups / TODO:
```

Example:

```md
### 2026-04-08 - Seller ShamCash Checkout
- Summary: Added seller-configurable ShamCash payment option with buyer transaction number capture.
- Scope: Seller shop settings, storefront checkout modal, order persistence, seller order view.
- Backend changes: ShopController validation + checkout payment logic.
- Frontend changes: dashboard.blade.php and shop/show.blade.php payment UI updates.
- Database changes: shamcash fields on shops + payment fields on orders.
- Routes changed: None (reused existing shop store/update/checkout routes).
- Breaking changes: No.
- Migration required: Yes (`2026_04_08_170000_add_shamcash_fields_to_shops_and_orders.php`).
- Manual verification done: php -l checks + `php artisan view:cache`.
- Follow-ups / TODO: Add feature tests for ShamCash validation path.
```
