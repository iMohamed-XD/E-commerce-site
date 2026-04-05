# Mahly.org — Laravel Feature Implementation Prompt

## Project Context

**Stack:** Laravel (latest), Blade templating, Tailwind CSS, Alpine.js, Vite, PostgreSQL, Docker
**Storage:** AWS S3-compatible bucket named `media` — all file uploads use `Storage::disk('media')`
**Design System:** White backgrounds, navy `#0d1b4b`, gold `#d4af37`, glass-morphism cards,
  dot-grid backgrounds, Tajawal font, RTL Arabic layout
**Auth:** Laravel Sanctum / Socialite (Google OAuth)
**Roles:** `users.role` is a string column. Default value is `'seller'`. Admin users have `role = 'admin'`.

Do NOT break any existing routes, controllers, migrations, or Blade layouts unless a task
explicitly requires modifying them. Preserve all existing architecture. Add new files;
extend existing ones minimally and surgically.

---

## Task 1 — Product Secondary Images with Auto-Slider

### Database
Create migration: add table `product_images` with columns:
- `id` (bigIncrements PK)
- `product_id` (FK → `products.id`, onDelete cascade)
- `path` (string) — S3 key stored via `Storage::disk('media')`
- `sort_order` (unsignedTinyInteger, default 0)
- `timestamps`

Enforce a maximum of 3 secondary images per product at the application layer
(total including main product image = 4 max).

### Model
- Create `ProductImage` model with `$fillable = ['product_id', 'path', 'sort_order']`
- Add `hasMany(ProductImage::class)` relationship on the existing `Product` model
- Add an `allImages()` method on `Product` that returns a merged array:
  main image path first, then `productImages` ordered by `sort_order` ascending,
  capped at 4 total entries

### Seller — Upload UI (product create/edit form)
- Add a "صور إضافية" section below the existing main image field
- Use Alpine.js + `<input type="file" multiple accept="image/*">` capped at 3 files client-side
- Show live preview thumbnails (FileReader API) before upload
- On form submit, upload each file via the existing form POST to S3 disk `media`
  under the path `products/{product_id}/secondary/{filename}`
- Store the returned S3 path in `product_images` table
- On edit page: display existing secondary images in a thumbnail row, each with a
  delete (×) button that calls `DELETE /seller/products/{product}/images/{image}` via
  Alpine.js fetch and removes the thumbnail from the DOM on success
- Server-side validation: max 3 files, each max 2MB, mimetypes: jpeg, png, webp

### Buyer — Shop Listing Slider
- On the buyer-facing product card in the shop listing, replace the static `<img>`
  with an Alpine.js slider component inline in the card
- Slider behavior:
  - Auto-advances every 5000ms using `setInterval` initialized in `x-init`
  - Loops back to index 0 after the last image
  - Pauses `setInterval` on `@mouseenter`, resumes on `@mouseleave`
  - Dot indicators at the bottom of the image area, gold filled dot = active
  - CSS `opacity` transition 300ms ease-in-out between images
- If a product has only 1 image: render a plain `<img>` tag with no Alpine.js overhead
- Resolve image URLs via `Storage::disk('media')->temporaryUrl($path, now()->addHours(2))`
  and pass a pre-resolved array to the Blade view — do not call Storage inside a loop
- Add `loading="lazy"` on all secondary image tags

---

## Task 2 — Product Detail Page

### Route
GET /shops/{shop}/products/{product}
Named: `buyer.product.show`
Uses default route model binding by `id` for both `{shop}` and `{product}`.
The `{product}` must belong to the `{shop}` — validate this in the controller
and abort 404 if the product's `shop_id` does not match the resolved shop.

### Controller — `BuyerProductController@show`
- Eager-load: `product->load(['shop', 'productImages', 'category'])`
- Resolve all image URLs (main + secondary, max 4) into a plain PHP array using
  `Storage::disk('media')->temporaryUrl()` before passing to the view
- Pass: `$product`, `$shop`, `$images` (resolved URL array), `$shop` active status check

### Blade View — `resources/views/buyer/products/show.blade.php`
Extend the existing buyer/guest layout.

**Left column (60% on desktop, full width on mobile):**
- Main image display: large rounded-xl image, switching via Alpine.js `activeIndex` state
- Thumbnail strip below: up to 4 small images, clicking sets `activeIndex`
- Active thumbnail has a gold border ring

**Right column (40% on desktop):**
- Product name — large, navy `#0d1b4b`, Tajawal font-bold
- Price — gold `#d4af37`, text-2xl, font-bold
- Shop name as a link back to the shop page
- Category badge — navy bg, white text, rounded-full, text-sm
- Full description text (no truncation), RTL
- Stock status badge:
  - In Stock → green bg
  - Out of Stock → red bg, text "غير متوفر حالياً"
- Quantity selector (only if in stock):
  - Alpine.js `x-data="{ qty: 1, max: {{ $product->stock }} }"`
  - `−` button (disabled when qty = 1), number display, `+` button (disabled when qty = max)
- "أضف إلى السلة" button:
  - POSTs to the existing `cart.add` route with `product_id` and `quantity`
  - Gold background, navy text, rounded-lg, w-full, py-3
  - Alpine.js loading state: shows spinner SVG on click, reverts after response
  - On success: show inline toast notification "تمت الإضافة إلى السلة ✓"
  - Disabled + greyed out if product is out of stock

**Breadcrumb (top of page):**
الرئيسية → {Shop Name} → {Product Name}
Each segment is a link except the last.

---

## Task 3 — Support / Donation Page & Button

### Route
GET /support    →  SupportController@index    named: support.index
Public route — add to `web.php` outside any auth middleware group.

### Controller — `SupportController@index`
- Query: `PaymentMethod::where('is_active', true)->orderBy('sort_order')->get()`
- Resolve logo and QR URLs from S3 via `temporaryUrl()` before passing to view
- Pass collection to view

### Blade View — `resources/views/support/index.blade.php`
Extend the guest layout.

**Structure:**
- Page header: mahly.org logo centered, title "ادعم فريق محلي"
- Gold alert banner (full width, rounded-xl):
  "جميع التبرعات تذهب حصراً لفريق mahly.org وليس للبائعين أو المتاجر"
- Payment method cards grid (2 columns on desktop, 1 on mobile):
  Each card (glass-morphism, white bg, navy border, rounded-2xl, shadow-md) contains:
  - Service logo image (from S3, max-h-12, object-contain) or service name if no logo
  - Service name, Tajawal font-semibold, navy
  - Account ID row: monospace text + "نسخ" button
    - Copy button uses Alpine.js: `navigator.clipboard.writeText('{{ $pm->account_id }}')`
    - On copy: button text changes to "تم النسخ ✓" for 2 seconds then reverts
  - QR code image (from S3) if `qr_path` is not null, displayed at max-w-[160px] centered
    with a "تحميل" (download) anchor tag with `download` attribute pointing to the URL
  - Optional `details` text in text-sm text-gray-500 if not null
- If no active payment methods: centered placeholder "سيتم إضافة طرق الدفع قريباً"

### Support Button Blade Component
File: `resources/views/components/support-button.blade.php`

Renders an anchor tag styled as a button:
- Text: "ادعم محلي ♥"
- Style: gold background `#d4af37`, navy text `#0d1b4b`, font-semibold, rounded-full,
  px-4 py-2, shadow-md, hover:brightness-110 transition
- `href="{{ route('support.index') }}"`

Include this component `<x-support-button />` in the following existing files:
1. The main dashboard layout — in the sidebar below the nav links or in the header bar
2. The guest/landing layout — in the navbar alongside existing nav links
3. `resources/views/buyer/shops/show.blade.php` — below the shop header section

---

## Task 4 — Seller Feedback System

### Database
Migration: `feedbacks` table
- `id` (bigIncrements)
- `user_id` (FK → `users.id`, onDelete cascade)
- `rating` (unsignedTinyInteger) — add DB-level check: `rating >= 1 AND rating <= 5`
- `content` (text)
- `timestamps`

Add unique constraint on `user_id` — one feedback record per seller.
Sellers may update their existing feedback but cannot insert a second row.

### Model — `Feedback`
```php
protected $fillable = ['user_id', 'rating', 'content'];
protected $casts = ['rating' => 'integer'];

public function user(): BelongsTo { return $this->belongsTo(User::class); }
```
Add `hasOne(Feedback::class)` to the `User` model.

### Routes (inside existing seller auth middleware group)
GET    /dashboard/feedback    FeedbackController@show      feedback.show
POST   /dashboard/feedback    FeedbackController@store     feedback.store
PUT    /dashboard/feedback    FeedbackController@update    feedback.update

### Controller — `FeedbackController`
- `show`: load `auth()->user()->feedback` (nullable), pass to view
- `store`:
  - Validate: `rating` integer required min:1 max:5, `content` string required min:20 max:2000
  - If user already has a feedback record: abort with validation error "لقد أرسلت رأيك مسبقاً"
  - Create record, redirect back to `feedback.show` with success flash
- `update`:
  - Same validation rules
  - Find the authenticated user's feedback or abort 404
  - Update and redirect with success flash

### Blade View — `resources/views/dashboard/feedback.blade.php`
Extend the existing dashboard layout. Match the existing UI exactly.

**Layout:**
- Page title: "رأيك يهمنا" (navy, Tajawal bold, text-2xl)
- Subtitle: "ساعدنا في تحسين تجربتك على منصة محلي" (gray-500, text-sm)
- Divider line
- If seller has existing feedback: show submission date as "آخر تحديث: {date}"
  and use PUT method with `@method('PUT')` in the form
- Star rating widget (Alpine.js):
x-data="{ rating: {{ $feedback->rating ?? 0 }}, hover: 0 }"
  5 star SVGs in a flex row, each:
  - `@mouseenter="hover = n"` `@mouseleave="hover = 0"` `@click="rating = n"`
  - Fill gold if `n <= (hover || rating)`, else navy outline
  - Hidden input: `<input type="hidden" name="rating" :value="rating">`
  - Show validation error below if rating is 0 on submit attempt
- Textarea: name="content", rows=6, RTL, Tajawal, navy border,
  focus:ring-gold, placeholder="شاركنا رأيك وملاحظاتك..."
  Pre-filled with `{{ old('content', $feedback->content ?? '') }}`
- Character counter below textarea (Alpine.js, gray text-xs)
- Submit button: gold bg, navy text, "إرسال رأيك" (store) or "تحديث رأيك" (update)
- Closing note: "شكراً لمساعدتك في تطوير محلي 🙏" text-center text-sm text-gray-400

### Dashboard Header Link
In the existing dashboard header/nav: add "ملاحظاتك" link pointing to `route('feedback.show')`.
If `auth()->user()->feedback === null`: show a small gold dot indicator next to the link text.

---

## Task 5 — Admin Dashboard

### Middleware — `EnsureUserIsAdmin`
```php
public function handle(Request $request, Closure $next): Response
{
    if (!auth()->check() || auth()->user()->role !== 'admin') {
        abort(403);
    }
    return $next($request);
}
```
Register it in `bootstrap/app.php` (Laravel 11) or `app/Http/Kernel.php` (Laravel 10)
under the alias `'admin'`.

### Routes
Add to `web.php` inside a route group:
```php
Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {

    Route::get('/',                              [AdminDashboardController::class, 'index'])->name('dashboard');

    // Sellers
    Route::get('/sellers',                       [AdminSellerController::class, 'index'])->name('sellers.index');
    Route::get('/sellers/{user}',                [AdminSellerController::class, 'show'])->name('sellers.show');
    Route::delete('/sellers/{user}',             [AdminSellerController::class, 'destroy'])->name('sellers.destroy');

    // Shops
    Route::get('/shops',                         [AdminShopController::class, 'index'])->name('shops.index');
    Route::get('/shops/{shop}',                  [AdminShopController::class, 'show'])->name('shops.show');
    Route::patch('/shops/{shop}',                [AdminShopController::class, 'update'])->name('shops.update');
    Route::delete('/shops/{shop}',               [AdminShopController::class, 'destroy'])->name('shops.destroy');

    // Products
    Route::get('/products',                      [AdminProductController::class, 'index'])->name('products.index');
    Route::delete('/products/{product}',         [AdminProductController::class, 'destroy'])->name('products.destroy');

    // Promo Codes
    Route::get('/promo-codes',                   [AdminPromoCodeController::class, 'index'])->name('promo-codes.index');
    Route::delete('/promo-codes/{promoCode}',    [AdminPromoCodeController::class, 'destroy'])->name('promo-codes.destroy');

    // Feedbacks
    Route::get('/feedbacks',                     [AdminFeedbackController::class, 'index'])->name('feedbacks.index');

    // Payment Methods
    Route::get('/payment-methods',               [AdminPaymentMethodController::class, 'index'])->name('payment-methods.index');
    Route::post('/payment-methods',              [AdminPaymentMethodController::class, 'store'])->name('payment-methods.store');
    Route::patch('/payment-methods/{pm}',        [AdminPaymentMethodController::class, 'update'])->name('payment-methods.update');
    Route::delete('/payment-methods/{pm}',       [AdminPaymentMethodController::class, 'destroy'])->name('payment-methods.destroy');
});
```

### Admin Layout — `resources/views/layouts/admin.blade.php`
- Same white/navy/gold design system and Tajawal font as the seller dashboard
- Fixed sidebar (desktop), collapsible drawer (mobile)
- Sidebar links with icons: Dashboard, Sellers, Shops, Products, Promo Codes,
  Feedbacks, Payment Methods
- Header bar: "لوحة تحكم المدير — mahly.org" + logged-in admin name + logout button
- All layout is RTL

### Controllers — implement each fully:

**`AdminDashboardController@index`**
Stat cards (navy bg, white text, rounded-2xl):
- Total sellers count
- Total active shops count
- Total products count
- Total feedbacks count
- Average rating (formatted to 1 decimal, with star icon)
Below stats: a table of the 10 most recent feedbacks (seller name, rating stars, content preview, date).

**`AdminSellerController`**
- `index`: paginated (20/page) table — seller name, email, shop name (linked),
  registration date, actions (View, Delete)
- `show`: seller profile card, their shop info card, products table, promo codes table
- `destroy`: delete the user record (cascade will handle related data via FK constraints).
  No password confirmation. On success redirect to `admin.sellers.index` with flash.

**`AdminShopController`**
- `index`: paginated table — shop name, seller name, product count, color swatch, actions
- `show`: full shop details, color displayed as swatch
- `update`: allow toggling an `is_active` boolean and editing shop name/description
- `destroy`: delete shop; also delete all its product S3 files via
  `Storage::disk('media')->deleteDirectory('products/{shop_id}')` then delete DB records

**`AdminProductController`**
- `index`: paginated table — product name, shop name, price, stock, main image thumbnail
- `destroy`: delete all `product_images` S3 files, then the product record

**`AdminPromoCodeController`**
- `index`: paginated table — code, discount value/type, usage count, expiry date, actions
- `destroy`: delete the promo code record

**`AdminFeedbackController@index`**
- Summary card: average rating across all feedbacks, total count
- Filter bar: buttons 1★ through 5★ that append `?rating=n` to the URL
- Paginated table: seller name (linked to seller show), star rating display,
  full content, submission date

**`AdminPaymentMethodController`**
- `index`: table of all payment methods + inline "Add New" form (or modal)
- `store`: validate name (required), account_id (required), logo (image, max 1MB, nullable),
  qr (image, max 1MB, nullable), details (nullable text), sort_order (integer default 0).
  Upload logo and QR to `Storage::disk('media')` under `payment-methods/` directory.
- `update`: same validation, re-upload only the files that are newly provided.
  If a new logo is uploaded and an old one exists, delete the old S3 file first.
- `destroy`: delete both S3 files (logo and QR if they exist) then delete the DB record.

---

## Task 6 — PaymentMethod Model & Migration

### Migration — create table `payment_methods`
id              bigIncrements
name            string                   // "ShamCash", "Syriatel Cash", etc.
logo_path       string nullable          // S3 path to logo image
qr_path         string nullable          // S3 path to QR code image
account_id      string                   // The copyable account number/ID
details         text nullable            // Optional instructions or notes
is_active       boolean default true
sort_order      unsignedSmallInteger default 0
timestamps

### Model — `PaymentMethod`
```php
protected $fillable = [
    'name', 'logo_path', 'qr_path', 'account_id',
    'details', 'is_active', 'sort_order'
];

protected $casts = ['is_active' => 'boolean'];

public function getLogoUrlAttribute(): ?string
{
    return $this->logo_path
        ? Storage::disk('media')->temporaryUrl($this->logo_path, now()->addHours(2))
        : null;
}

public function getQrUrlAttribute(): ?string
{
    return $this->qr_path
        ? Storage::disk('media')->temporaryUrl($this->qr_path, now()->addHours(2))
        : null;
}
```

---

## Task 7 — Shop Color Palette: Replace Two-Value Theme with Single Color

### Context
The `shops` table currently has a `theme` column that stores a composite value
with two fields: `primary` and `accent_soft`. Replace this entire concept with a
single `color` string column that holds one of 10 predefined color keys.

### Migration
Create a new migration that:
1. Adds column `color` string, default `'navy'` to the `shops` table
2. Drops the existing `theme` column (or whichever column currently stores
   the two-value theme — inspect the shops table schema first and target it precisely)
3. Do NOT modify the existing migration files

### Color Config — create `config/shop_colors.php`
```php
<?php

return [
    'navy'     => ['hex' => '#0d1b4b', 'label' => 'كحلي'],
    'gold'     => ['hex' => '#d4af37', 'label' => 'ذهبي'],
    'emerald'  => ['hex' => '#065f46', 'label' => 'زمردي'],
    'rose'     => ['hex' => '#9f1239', 'label' => 'وردي غامق'],
    'slate'    => ['hex' => '#1e293b', 'label' => 'رمادي داكن'],
    'amber'    => ['hex' => '#92400e', 'label' => 'عنبري'],
    'violet'   => ['hex' => '#4c1d95', 'label' => 'بنفسجي'],
    'teal'     => ['hex' => '#134e4a', 'label' => 'فيروزي'],
    'crimson'  => ['hex' => '#7f1d1d', 'label' => 'قرمزي'],
    'charcoal' => ['hex' => '#18181b', 'label' => 'فحمي'],
];
```

### Seller — Shop Create/Edit Form
Replace the existing theme/color picker UI with a swatch grid:
- 10 circles (40×40px each), arranged in a 5-column grid
- Each circle is filled with its hex via inline `style="background-color: {hex}"`
- Clicking a circle sets Alpine.js `selected` state and adds a gold ring:
  `ring-2 ring-offset-2 ring-[#d4af37]`
- Hidden input: `<input type="hidden" name="color" :value="selected">`
- Label below each swatch: the Arabic label in text-xs text-gray-500
- Alpine.js: `x-data="{ selected: '{{ old('color', $shop->color ?? 'navy') }}' }"`
- Server-side validation: `'color' => ['required', 'string', Rule::in(array_keys(config('shop_colors')))]`

### Shop Model Update
- Add `'color'` to `$fillable`
- Remove whichever attribute(s) previously represented the theme (primary, accent_soft)
- Add accessor:
```php
  public function getColorHexAttribute(): string
  {
      return config('shop_colors')[$this->color]['hex'] ?? '#0d1b4b';
  }
```

### Blade View Updates
Search all Blade view files under `resources/views/` for any reference to the old
theme column name (primary, accent_soft, or theme). Replace each occurrence with
either `$shop->color_hex` (for inline style) or `$shop->color` (for the key).
Common replacement pattern example:
```blade
{{-- Before --}}
style="background-color: {{ $shop->theme['primary'] }}"

{{-- After --}}
style="background-color: {{ $shop->color_hex }}"
```

---

## General Implementation Rules

1. All file uploads go through `Storage::disk('media')` exclusively. Never use the
   default local disk for any user-generated content.
2. All new migrations use `php artisan make:migration` naming conventions.
   Never modify existing migration files — only add new ones.
3. All new named routes must be registered in `web.php` inside the correct
   middleware group.
4. No hardcoded hex colors in Blade files. Use Tailwind arbitrary values like
   `bg-[#0d1b4b]` or inline `style=` only when the value comes from a dynamic PHP variable.
5. All flash messages use the existing session flash pattern already in the project.
6. Alpine.js is already loaded globally — do not add any CDN `<script>` tags for it.
7. RTL layout must be preserved across all new views. All new flex/grid layouts
   must be RTL-aware (use `gap`, avoid `ml-`/`mr-` in favor of `ms-`/`me-`).
8. The Tajawal font is already loaded globally — do not re-import it.
9. After completing all tasks, output a structured summary listing:
   - Every new file created (full path)
   - Every existing file modified (full path + what changed)
   - Every migration created (migration name)
