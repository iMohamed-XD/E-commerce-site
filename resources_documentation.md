# Mahly E-commerce: Exhaustive Resources Documentation

This technical manual provides a granular, file-by-file breakdown of the `resources` directory. Every folder and file is listed with its specific role in the application's architecture.

---

## 📁 `resources/css`
*Purpose: Contains the global styling definitions and design tokens for the platform.*

- **[app.css](file:///c:/AntiGravity/E-commerce-site/resources/css/app.css)**: The primary entry point for Tailwind CSS v4. Defines the base system, component classes (like glassmorphism), and utility layers. It also sets the "Tajawal" font as the default for Arabic-optimized typography.

---

## 📁 `resources/js`
*Purpose: Contains the client-side logic and bootstrapping code.*

- **[app.js](file:///c:/AntiGravity/E-commerce-site/resources/js/app.js)**: Initializes Alpine.js and attaches it to the global `window` object. This is the main bundle that powers all frontend interactivity.
- **[bootstrap.js](file:///c:/AntiGravity/E-commerce-site/resources/js/bootstrap.js)**: Configures helper libraries like Axios for HTTP requests and sets up CSRF headers for secure communication with the Laravel backend.

---

## 📁 `resources/views` (Root)
*Purpose: Main page templates and complex interactive dashboards.*

- **[dashboard.blade.php](file:///c:/AntiGravity/E-commerce-site/resources/views/dashboard.blade.php)**: The central hub for sellers. It includes the "Shop Setup Wizard" logic (using Alpine.js and Cropper.js) for initial account configuration.
- **[landing.blade.php](file:///c:/AntiGravity/E-commerce-site/resources/views/landing.blade.php)**: A premium, dark-themed promotional page for visitors. Features high-end animations and glassmorphism.
- **[welcome.blade.php](file:///c:/AntiGravity/E-commerce-site/resources/views/welcome.blade.php)**: The original Laravel entry page, maintained as a fallback or reference.

---

## 📁 `resources/views/auth`
*Purpose: User authentication and security views.*

- **confirm-password.blade.php**: A security gate that requires a password before accessing sensitive settings.
- **forgot-password.blade.php**: The "Request Reset Link" form for users who lost their credentials.
- **login.blade.php**: The primary sign-in portal.
- **register.blade.php**: The account creation page.
- **reset-password.blade.php**: The form where users set their new password after clicking a recovery link.
- **verify-email.blade.php**: A notice shown to new users requesting they check their inbox for a verification link.

---

## 📁 `resources/views/categories`
*Purpose: Product taxonomy management.*

- **index.blade.php**: A dedicated management view for sellers to create, view, and delete product categories.

---

## 📁 `resources/views/components`
*Purpose: Reusable UI building blocks.*

| File | Purpose |
| :--- | :--- |
| `application-logo.blade.php` | Renders the primary Mahly SVG brand asset. |
| `auth-session-status.blade.php` | Displays status feedback during login/registration. |
| `danger-button.blade.php` | Specialized red button for destructive actions. |
| `dropdown-link.blade.php` | Sub-component for items within a menu. |
| `dropdown.blade.php` | Generic Alpine.js-powered dropdown container. |
| `input-error.blade.php` | Maps Laravel validation errors to standard red text UI. |
| `input-label.blade.php` | Standardized form label with support for ARIA labels. |
| `modal.blade.php` | **Complex**: Accessibility-focused dialog with focus-trapping logic. |
| `nav-link.blade.php` | Navigation items that automatically highlight when active. |
| `primary-button.blade.php` | The core "Signature" button style (Gold/Black). |
| `responsive-nav-link.blade.php` | Mobile-friendly versions of navigation links. |
| `secondary-button.blade.php` | Subtle neutral button for non-primary actions. |
| `text-input.blade.php` | The base form field component used throughout the app. |

---

## 📁 `resources/views/layouts`
*Purpose: Structural page wrappers.*

- **[app.blade.php](file:///c:/AntiGravity/E-commerce-site/resources/views/layouts/app.blade.php)**: The master layout for all authenticated views. Manages fonts, scripts, and the Sidebar structure.
- **[guest.blade.php](file:///c:/AntiGravity/E-commerce-site/resources/views/layouts/guest.blade.php)**: Layout for unauthenticated pages (Auth). Uses a centered "Container" design.
- **[navigation.blade.php](file:///c:/AntiGravity/E-commerce-site/resources/views/layouts/navigation.blade.php)**: The top/side navigation bar logic, including user dropdowns and responsive toggles.

---

## 📁 `resources/views/orders`
*Purpose: Sales and fulfillment tracking.*

- **index.blade.php**: A comprehensive dashboard showing buyer details, order status badges, and price calculations.

---

## 📁 `resources/views/products`
*Purpose: Inventory and product data CRUD.*

- **create.blade.php**: Form for adding new items to the shop (includes image upload).
- **edit.blade.php**: Interface for updating existing product details.
- **index.blade.php**: The inventory grid. Includes Alpine.js logic for **Bulk Actions** (delete multiple, discount multiple).

---

## 📁 `resources/views/profile`
*Purpose: Personal user account settings.*

- **edit.blade.php**: The main settings assembly page.
- **partials/delete-user-form.blade.php**: Logic for secure account removal.
- **partials/update-password-form.blade.php**: Form for rotating security credentials.
- **partials/update-profile-information-form.blade.php**: Basic data management (Name/Email).

---

## 📁 `resources/views/promo_codes`
*Purpose: Incentive management.*

- **index.blade.php**: Interface for creating and toggling discount codes.

---

## 📁 `resources/views/shop`
*Purpose: The public-facing storefront.*

- **[show.blade.php](file:///c:/AntiGravity/E-commerce-site/resources/views/shop/show.blade.php)**: The "Face" of the shop. Technical logic includes a **Persistent Alpine.js Shopping Cart** (stored in `localStorage`) and a built-in checkout modal.

---

## 💡 Summary of Interactivity
- **Alpine.js**: Powers nearly 90% of client-side state (Carts, Modals, Bulk Selection).
- **Tailwind v4**: Powers the entire visual identity without custom CSS files.
- **Blade Components**: Ensure that changes to a button or modal reflect globally across all 39 files.
