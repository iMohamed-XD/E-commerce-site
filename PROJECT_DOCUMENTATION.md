# Mahly (محلي) - E-commerce Platform Documentation

## Project Overview

**Mahly** is a premium, multi-vendor e-commerce platform designed for small businesses and individual sellers in Arabic-speaking markets. The platform allows sellers to create personalized online stores with unique URLs, manage products with discount systems, handle orders, and customize their storefront themes.

### Key Differentiators
- **Multi-vendor Architecture**: Each user can become a seller with their own shop
- **Localized for Arabic Markets**: Full RTL support with premium Arabic typography
- **Theme Customization**: Sellers can choose from multiple color themes
- **Advanced Discount System**: Manual toggles for product discounts and promo codes

---

## Technology Stack

### Backend
- **Framework**: Laravel 12.x
- **Authentication**: Laravel Jetstream (preferred over Breeze)
- **Database**: PostgreSQL support
- **Email**: Configurable mailers with email verification
- **OAuth**: Google login via Laravel Socialite

### Frontend
- **CSS Framework**: Tailwind CSS v4 (latest)
- **JavaScript**: Alpine.js for interactivity
- **Build Tool**: Vite
- **Icons**: Heroicons
- **Typography**: Tajawal font (Arabic-optimized)

### Additional Packages
- `laravel/socialite` - Google OAuth integration
- `resend/resend-php` - Email service integration
- `laravel/jetstream` - Advanced authentication scaffolding with teams, 2FA, profile management

---

## Why Laravel Jetstream + Tailwind CSS v4?

### Laravel Jetstream vs Breeze

**Laravel Jetstream is better because:**

| Feature | Jetstream | Breeze |
|---------|-----------|--------|
| **Authentication** | Full-featured (login, register, 2FA, profile) | Basic only |
| **Teams Support** | Built-in team management | Not available |
| **2FA Support** | Native two-factor authentication | Not available |
| **Profile Photos** | Built-in avatar management | Not available |
| **Browser Sessions** | Session management UI | Not available |
| **API Support** | Optional API scaffolding | Not available |
| **Livewire/Inertia** | Supports both stacks | Blade only |
| **Starter Views** | Premium design out of the box | Basic Bootstrap-like |

**Jetstream provides everything Breeze offers plus:**
- Two-factor authentication (2FA) with recovery codes
- Team and organization management
- Profile photo uploads
- Browser session management
- Optional REST API scaffolding
- More polished UI components

### Tailwind CSS v4 Improvements

**Tailwind CSS v4 is better than v3 because:**

1. **New Engine**: Written in Rust for 10x faster builds
2. **CSS-first Configuration**: No JavaScript config needed (optional)
3. **Improved Performance**: Faster cold starts and incremental builds
4. **Better IntelliSense**: Enhanced IDE support
5. **Container Queries**: Native support for component-based layouts
6. **New Color System**: Better opacity modifier syntax

**Key v4 Changes:**
- Configuration via `@theme` directive instead of JS config
- CSS variables for customization
- Improved dark mode with `@media (prefers-color-scheme)`
- Better arbitrary value support

### Installation Comparison

**Jetstream Installation:**
```bash
# With Livewire (recommended for dynamic UIs)
composer require laravel/jetstream
php artisan jetstream:install livewire --teams

# With Inertia (Vue/React frontend)
php artisan jetstream:install inertia --teams

# Run migrations
php artisan migrate
```

**Tailwind v4 Installation:**
```bash
# Install Tailwind CSS v4
npm install tailwindcss @tailwindcss/vite

# Update vite.config.js
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
})

# Update app.css - New v4 syntax
@import "tailwindcss";
```

---

## Core Features

### 1. User Authentication & Authorization

**Authentication System**:
- Email/password registration with **email verification required** (implements `MustVerifyEmail`)
- Google OAuth login via Socialite
- Password reset functionality
- Session management with database driver

**Authorization**:
- Role-based access control (customer/seller roles)
- Middleware protection for seller dashboard
- Email verification middleware on protected routes

**Key Implementation**:
```php
// User model implements email verification
class User extends Authenticatable implements MustVerifyEmail
{
    // Role field determines seller status
    public function isSeller()
    {
        return $this->role === 'seller';
    }
}
```

### 2. Shop Management

**Features**:
- Custom shop creation with unique slug validation
- Live slug preview during shop creation
- Shop branding (name, description, logo, hero image)
- Theme customization with 5 preset themes
- Public storefront accessible via `/shop/{slug}`

**Theme Presets**:
1. **Royal Navy** (`royal_navy`) - Professional blue with gold accents
2. **Emerald Forest** (`emerald_forest`) - Natural green tones
3. **Sunset Coral** (`sunset_coral`) - Warm coral/orange
4. **Twilight Indigo** (`twilight_indigo`) - Modern purple
5. **Desert Amber** (`desert_amber`) - Classic warm tones

**Database Schema**:
```php
Schema::create('shops', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->string('logo_path')->nullable();
    $table->string('hero_image_path')->nullable();
    $table->string('theme')->default('royal_navy');
    $table->timestamps();
});
```

### 3. Product Management

**Features**:
- Full CRUD operations for products
- Product categories with hierarchical structure
- Manual discount toggle system
- Product visibility toggle (is_active)
- Image upload support
- Bulk actions for multiple products

**Discount System**:
- `discount_percent`: Percentage discount (0-100)
- `discount_active`: Manual toggle to enable/disable discount
- `effectivePrice()` method calculates final price

**Key Implementation**:
```php
public function effectivePrice(): float
{
    if ($this->hasActiveDiscount()) {
        return round($this->price * (1 - $this->discount_percent / 100), 2);
    }
    return (float) $this->price;
}

public function hasActiveDiscount(): bool
{
    return (bool) ($this->discount_active && $this->discount_percent > 0);
}
```

### 4. Order Management

**Features**:
- Order creation during checkout
- Order status management (pending, processing, completed, cancelled)
- Order items with quantity tracking
- Buyer email collection
- Promo code application

**Database Structure**:
- `orders` table: shop_id, buyer_email, promo_code_id, total, status
- `order_items` table: order_id, product_id, quantity, price

### 5. Promo Code System

**Features**:
- Create/manage promo codes
- Manual toggle activation
- Discount percentage application
- Usage tracking
- Rate-limited application endpoint

**Key Implementation**:
```php
// Promo codes have manual toggle
Schema::create('promo_codes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
    $table->string('code')->unique();
    $table->decimal('discount_percent', 5, 2);
    $table->boolean('is_active')->default(false); // Manual toggle
    $table->timestamps();
});
```

### 6. Category Management

**Features**:
- Hierarchical category structure
- Category assignment to products
- Bulk migration support for existing data

### 7. Rate Limiting & Security

**Protected Endpoints**:
- Checkout: `throttle:5,1` (5 requests per minute)
- Apply promo: `throttle:10,1` (10 requests per minute)
- Email verification: `throttle:6,1`

**CSRF Protection**: All forms include CSRF tokens

---

## UI/UX Design System

### Design Philosophy
- **Glassmorphism**: Semi-transparent backgrounds with blur effects
- **Premium Dark Theme**: Dark blue/navy color scheme
- **Arabic Typography**: Tajawal font with RTL support
- **Modern Icons**: Heroicons integration

### Color Scheme (Default - Royal Navy)
```javascript
primary: '#0d1b4b'      // Dark navy
primary_hover: '#1a2d6b' // Lighter navy
accent: '#d4af37'       // Gold
accent_soft: '#fff4cf'  // Soft gold
```

### Tailwind CSS v4 Configuration

**Option 1: CSS-first (Recommended for v4):**

```css
/* resources/css/app.css */
@import "tailwindcss";

/* Custom theme using @theme directive */
@theme {
    --font-sans: 'Tajawal', ui-sans-serif, system-ui, sans-serif;
    --color-primary: #0d1b4b;
    --color-primary-hover: #1a2d6b;
    --color-accent: #d4af37;
    --color-accent-soft: #fff4cf;
}

/* Custom utilities */
@utility glassmorphism {
    backdrop-filter: blur(12px);
    background-color: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

@utility hero-dotgrid {
    background-image: radial-gradient(circle, #0d1b4b12 1px, transparent 1px);
    background-size: 28px 28px;
}
```

**Option 2: JavaScript config (if needed):**

```javascript
// tailwind.config.js (optional in v4)
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Tajawal', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: '#0d1b4b',
                    hover: '#1a2d6b',
                },
                accent: {
                    DEFAULT: '#d4af37',
                    soft: '#fff4cf',
                },
            },
        },
    },
};
```

### Key CSS Patterns (Tailwind v4)

**Glassmorphism:**
```html
<div class="glassmorphism rounded-xl p-6">
    <!-- Or using arbitrary values -->
    <div class="backdrop-blur-md bg-white/80 border border-white/20 rounded-xl">
```

**RTL Support:**
```html
<html dir="rtl" lang="ar" class="scroll-smooth">
```

**Theme Colors (v4 syntax):**
```html
<!-- Using CSS variables from @theme -->
<div class="bg-primary text-accent">
<div class="bg-primary-hover hover:bg-primary">

<!-- Or arbitrary values -->
<div class="bg-[#0d1b4b] text-[#d4af37]">
```

**Gradient Backgrounds:**
```html
<div class="bg-gradient-to-b from-[#eef2ff] via-white to-[#fdfbf4]">
```

**Blur Effects:**
```html
<div class="absolute top-0 right-0 w-[600px] h-[400px] bg-accent/8 rounded-full blur-[120px]">
```

**Container Queries (v4 feature):**
```html
<div class="@container">
    <div class="flex flex-col @md:flex-row">
        <!-- Responsive based on parent container -->
    </div>
</div>
```

### CSS Files Structure
- `resources/css/app.css` - Tailwind imports and custom styles
- `tailwind.config.js` - Optional JS configuration
- `vite.config.js` - Vite plugins including Tailwind v4

### Vite Configuration for Tailwind v4
```javascript
// vite.config.js
import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
})
```

---

## Laravel Jetstream Features

### Built-in Authentication Features

Jetstream provides these features out of the box:

**1. Two-Factor Authentication (2FA):**
```php
// In Fortify actions (app/Actions/Fortify/EnableTwoFactorAuthentication.php)
class EnableTwoFactorAuthentication implements EnableTwoFactorAuthenticationContract
{
    public function execute($user)
    {
        $user->forceFill([
            'two_factor_secret' => encrypt(Totp::generateSecret()),
            'two_factor_recovery_codes' => encrypt(json_encode(Collection::times(8, function () {
                return RecoveryCode::generate();
            })->all())),
        ])->save();
    }
}
```

**2. Profile Photo Upload:**
```php
// ProfileController handles photo uploads
public function updateProfilePhotos(Request $request)
{
    $request->validate([
        'photo' => ['required', 'image', 'max:1024'],
    ]);

    $request->user()->updateProfilePhoto($request->file('photo'));
}
```

**3. Browser Session Management:**
```blade
<!-- Resources/views/profile/logout-other-browser-sessions-form.blade.php -->
<x-action-section>
    <x-slot name="title">Browser Sessions</x-slot>
    <x-slot name="description">Manage and log out active sessions.</x-slot>
</x-action-section>
```

**4. Teams/Organizations (Optional):**
```php
// Team model with membership
class Team extends JetstreamTeam
{
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function members()
    {
        return $this->hasMany(Membership::class)->orderBy('created_at');
    }
}
```

### Jetstream Routes Structure
```php
// routes/web.php - Jetstream routes
Fortify::loginUsing(function (Request $request) {
    // Custom login logic
});

Fortify::authenticateUsing(function (Request $request) {
    $user = User::where('email', $request->email)->first();

    if ($user && Hash::check($request->password, $user->password)) {
        return $user;
    }
});
```

---

## Email Verification Implementation

### With Jetstream (Built-in)

Jetstream includes email verification automatically when you install with the verification option:

```bash
php artisan jetstream:install livewire --verification
```

**This automatically creates:**
- Email verification routes
- Verification email templates
- `MustVerifyEmail` middleware integration
- Notification classes

### User Model
```php
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;
    // Jetstream also adds: TwoFactorAuthenticatable, HasProfilePhoto
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'google_id',
        'email_verified_at',
    ];
}
```

### Jetstream Email Verification Routes
```php
// Jetstream handles these automatically
// Located in vendor/laravel/fortify/routes/routes.php

// Manual override if needed (routes/web.php)
Route::get('email/verify', [EmailVerificationPromptController::class, 'show'])
    ->middleware(['auth'])
    ->name('verification.notice');

Route::get('email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');
```

### Middleware Usage
```php
// Protected routes require verification
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'show'])
        ->name('dashboard');
    
    Route::get('/seller/dashboard', [SellerDashboardController::class, 'show'])
        ->middleware('seller')
        ->name('seller.dashboard');
});

// Jetstream profile routes (auto-verified)
Route::middleware(['auth'])->group(function () {
    Route::get('/user/profile', [ProfileController::class, 'show'])
        ->name('profile.show');
});
```

### Customizing Email Verification
```php
// app/Providers/FortifyServiceProvider.php
public function boot(): void
{
    // Custom email verification view
    Fortify::verifyEmailView(fn () => view('auth.verify-email'));
    
    // Custom verification notification
    Fortify::verifyEmailUsing(function ($user) {
        $user->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    });
}
```

---

## Key Directories Structure

```
app/
├── Actions/
│   └── Fortify/            # Jetstream Fortify actions
│       ├── CreateNewUser.php
│       ├── UpdateUserPassword.php
│       ├── UpdateUserProfileInformation.php
│       ├── EnableTwoFactorAuthentication.php
│       └── DisableTwoFactorAuthentication.php
├── Http/
│   └── Controllers/
│       ├── Auth/           # Authentication controllers
│       ├── ShopController.php
│       ├── ProductController.php
│       ├── OrderController.php
│       ├── PromoCodeController.php
│       └── CategoryController.php
├── Models/
│   ├── User.php
│   ├── Shop.php
│   ├── Product.php
│   ├── Order.php
│   ├── OrderItem.php
│   ├── PromoCode.php
│   ├── Category.php
│   └── Team.php            # Jetstream team model
└── Policies/
    └── Jetstream/          # Jetstream policies

database/
└── migrations/             # All migration files

resources/
├── css/
│   └── app.css             # Tailwind v4 styles
├── js/
│   └── app.js
└── views/
    ├── layouts/
    │   ├── app.blade.php
    │   ├── guest.blade.php
    │   └── navigation.blade.php
    ├── auth/
    ├── profile/            # Jetstream profile views
    ├── products/
    ├── orders/
    ├── shop/
    └── promo_codes/

routes/
├── auth.php               # Authentication routes
├── web.php                # Main application routes
└── console.php
```

---

## Setup Instructions for AI Models

### Prerequisites
To replicate this project, ensure:

1. **PHP 8.2+** with required extensions
2. **Composer** for PHP dependency management
3. **Node.js 18+ & NPM** for frontend assets
4. **Database**: PostgreSQL (recommended) or SQLite for development

### Step-by-Step Setup

#### 1. Create Laravel Project with Jetstream
```bash
# Create new Laravel project
composer create-project laravel/laravel mahly
cd mahly

# Install Jetstream with Livewire (recommended for dynamic UIs)
composer require laravel/jetstream
php artisan jetstream:install livewire --teams --verification

# Alternative: With Inertia (Vue/React)
php artisan jetstream:install inertia --teams --verification
```

#### 2. Install Tailwind CSS v4
```bash
# Install Tailwind CSS v4 with Vite plugin
npm install tailwindcss @tailwindcss/vite

# Create/update vite.config.js for Tailwind v4
cat > vite.config.js << 'EOF'
import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
})
EOF

# Update app.css with Tailwind v4 syntax
cat > resources/css/app.css << 'EOF'
@import "tailwindcss";

/* Custom theme for Mahly */
@theme {
    --font-sans: 'Tajawal', ui-sans-serif, system-ui, sans-serif;
    --color-primary: #0d1b4b;
    --color-primary-hover: #1a2d6b;
    --color-accent: #d4af37;
    --color-accent-soft: #fff4cf;
}

/* Custom utilities */
@utility glassmorphism {
    backdrop-filter: blur(12px);
    background-color: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

@utility hero-dotgrid {
    background-image: radial-gradient(circle, #0d1b4b12 1px, transparent 1px);
    background-size: 28px 28px;
}
EOF
```

#### 3. Additional Dependencies
```bash
# Install required packages
composer require laravel/socialite resend/resend-php

# Install dev dependencies
npm install alpinejs @alpinejs/focus
```

#### 4. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

**Required .env Configuration:**
```env
# Application
APP_NAME="Mahly"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=mahly
DB_USERNAME=postgres
DB_PASSWORD=secret

# Mail (for email verification - Jetstream uses this)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS="noreply@mahly.com"
MAIL_FROM_NAME="${APP_NAME}"

# Jetstream Features
JETSTREAMFeatures=registration,profiler,api,teams
JETSTREAMREGISTERATION=true
JETSTREAM_EMAIL_LOGIN=false

# Google OAuth (optional)
GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

#### 5. Database Setup
```bash
# Run migrations (includes Jetstream tables)
php artisan migrate

# Link storage for images
php artisan storage:link

# Optional: Seed database
php artisan db:seed
```

#### 6. Start Development Server
```bash
# Start all services (recommended)
composer run dev

# Or start individually:
# Terminal 1: PHP server
php artisan serve

# Terminal 2: Vite (Tailwind v4)
npm run dev
```

### Jetstream Configuration Files

**config/jetstream.php:**
```php
return [
    'stack' => 'livewire', // or 'inertia'
    'features' => [
        Features::termsAndPrivacyPolicy(),
        Features::profilePhotos(),
        Features::api(),
        Features::teams(['invitations' => true]),
        Features::accountDeletion(),
    ],
    'profile' => [
        'avatar' => [
            'disk' => 'public',
            'path' => 'profile-photos',
            'rules' => ['image', 'mimes:jpg,png,jpeg', 'max:1024'],
        ],
    ],
];
```

### Customizing Jetstream

**1. Override Default Views:**
```bash
# Publish Jetstream views
php artisan vendor:publish --tag=jetstream-views

# Views will be in resources/views/auth/
```

**2. Customize Authentication:**
```php
// app/Providers/JetstreamServiceProvider.php
protected function configurePermissions()
{
    Jetstream::defaultApiTokenPermissions(['read']);

    Jetstream::role('admin', 'Administrator', [
        'create',
        'read',
        'update',
        'delete',
    ])->description('Administrator users can perform any action.');
}
```

**3. Add Seller Role:**
```php
// In User model
public function isSeller()
{
    return $this->role === 'seller';
}

// Create middleware
php artisan make:middleware EnsureUserIsSeller
```

### Testing
```bash
# Run Jetstream tests
php artisan test --filter=Jetstream

# Run all tests
php artisan test
```

---

## Deployment Considerations

### Environment Requirements
- PHP 8.2+
- PostgreSQL 13+
- Node.js 18+
- SSL certificate for production

### Build Commands
```bash
# Production build
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Queue Configuration
Use database queue driver for background jobs:
```env
QUEUE_CONNECTION=database
```

---

## Common Issues & Solutions

### 1. Email Verification Not Sending
- Check MAIL_ configuration in .env
- Verify mail driver is properly configured
- Check spam folder

### 2. Images Not Loading
- Run `php artisan storage:link`
- Check FILESYSTEM_DISK in .env

### 3. Slug Validation Fails
- Ensure unique constraint on shops.slug
- Check validation rules in ShopController

### 4. Theme Not Applying
- Verify theme key exists in `Shop::themePresets()`
- Check theme column in shops table

---

## Future Enhancement Ideas

1. **Inventory Management**: Stock tracking per product
2. **Shipping Calculator**: Weight/location-based shipping
3. **Review System**: Product reviews and ratings
4. **Wishlist**: Save products for later
5. **Analytics Dashboard**: Sales and traffic metrics
6. **Multi-language**: Support for English alongside Arabic
7. **API Endpoints**: RESTful API for mobile apps
8. **Payment Gateway**: Stripe/PayPal integration

---

## Summary

**Mahly** is a production-ready e-commerce platform that demonstrates:

### Core Stack
- **Laravel 12** with **Jetstream** authentication (includes 2FA, teams, profile management)
- **Tailwind CSS v4** with CSS-first configuration and custom utilities
- **Alpine.js** for lightweight interactivity

### Key Features
- Multi-vendor marketplace pattern with seller roles
- Email verification with Jetstream's built-in system
- Arabic RTL support with premium Tajawal typography
- Glassmorphic UI with premium dark theme
- Advanced discount and promo code systems with manual toggles
- Theme customization with 5 preset color schemes
- Rate limiting for security on sensitive endpoints

### Why This Stack is Better

**Laravel Jetstream > Breeze:**
- Two-factor authentication (2FA) built-in
- Team/organization management
- Profile photo uploads
- Browser session management
- More polished UI components

**Tailwind CSS v4 > v3:**
- 10x faster builds (Rust-based engine)
- CSS-first configuration with `@theme` directive
- Container queries for component-based layouts
- Better IntelliSense and IDE support
- Native CSS variables support

The codebase follows Laravel best practices and can serve as a reference for building modern, secure, multi-vendor e-commerce applications with the latest technologies.
