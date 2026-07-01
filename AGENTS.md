# Agents.md — Laravel + Inertia.js + Vue + shadcn-vue

> Architecture reference and development standards document for **Laravel + Inertia.js + Vue 3 + shadcn-vue + Tailwind CSS v4** projects.

---

## 1. Tech Stack

| Layer | Technology | Target Version |
|-------|-----------|----------------|
| Backend | Laravel | 12.x |
| Frontend Framework | Vue 3 | 3.5+ (Composition API) |
| Bridge | Inertia.js | 2.x |
| UI Components | shadcn-vue | latest |
| Styling | Tailwind CSS | v4 |
| Build Tool | Vite | 6.x |
| Package Manager | npm / pnpm | — |
| Language | TypeScript | 5.7+ (recommended) |

---

## 2. Folder Structure

```
scrapping-marketplace/
app
 ┣ Http
 ┃ ┣ Controllers
 ┃ ┃ ┣ Api
 ┃ ┃ ┃ ┣ ProductController.php
 ┃ ┃ ┃ ┗ SpkController.php
 ┃ ┃ ┣ Auth
 ┃ ┃ ┃ ┣ AuthenticatedSessionController.php
 ┃ ┃ ┃ ┣ ConfirmablePasswordController.php
 ┃ ┃ ┃ ┣ EmailVerificationNotificationController.php
 ┃ ┃ ┃ ┣ EmailVerificationPromptController.php
 ┃ ┃ ┃ ┣ NewPasswordController.php
 ┃ ┃ ┃ ┣ PasswordController.php
 ┃ ┃ ┃ ┣ PasswordResetLinkController.php
 ┃ ┃ ┃ ┣ RegisteredUserController.php
 ┃ ┃ ┃ ┗ VerifyEmailController.php
 ┃ ┃ ┣ Controller.php
 ┃ ┃ ┗ ProfileController.php
 ┃ ┣ Middleware
 ┃ ┃ ┗ HandleInertiaRequests.php
 ┃ ┗ Requests
 ┃ ┃ ┣ Auth
 ┃ ┃ ┃ ┗ LoginRequest.php
 ┃ ┃ ┗ ProfileUpdateRequest.php
 ┣ Models
 ┃ ┣ MarketplaceData.php
 ┃ ┣ PriceHistory.php
 ┃ ┣ Product.php
 ┃ ┗ User.php
 ┣ Providers
 ┃ ┗ AppServiceProvider.php
 ┗ Services
 ┃ ┣ SAWService.php
 ┃ ┗ WPService.php
bootstrap
 ┣ cache
 ┃ ┣ .gitignore
 ┃ ┣ packages.php
 ┃ ┗ services.php
 ┣ app.php
 ┗ providers.php                 
config
 ┣ app.php
 ┣ auth.php
 ┣ cache.php
 ┣ database.php
 ┣ filesystems.php
 ┣ logging.php
 ┣ mail.php
 ┣ queue.php
 ┣ sanctum.php
 ┣ services.php
 ┣ session.php
 ┗ spk.php
database
 ┣ factories
 ┃ ┗ UserFactory.php
 ┣ migrations
 ┃ ┣ 0001_01_01_000000_create_users_table.php
 ┃ ┣ 0001_01_01_000001_create_cache_table.php
 ┃ ┣ 0001_01_01_000002_create_jobs_table.php
 ┃ ┣ 2026_06_22_101013_create_products_table.php
 ┃ ┣ 2026_06_22_101632_create_marketplace_data_table.php
 ┃ ┣ 2026_06_22_101756_create_price_histories_table.php
 ┃ ┗ 2026_06_22_102547_create_personal_access_tokens_table.php
 ┣ seeders
 ┃ ┗ DatabaseSeeder.php
 ┣ .gitignore
 ┗ database.sqlite
 public
 ┣ build
 ┃ ┣ assets
 ┃ ┃ ┣ app-BOBpeTOx.css
 ┃ ┃ ┣ app-D8i0pD7Y.js
 ┃ ┃ ┣ ApplicationLogo-D8Ky7xC8.js
 ┃ ┃ ┣ AuthenticatedLayout-CbWxRjBq.js
 ┃ ┃ ┣ ConfirmPassword-hfpd7_OD.js
 ┃ ┃ ┣ Dashboard-C3-kulcJ.js
 ┃ ┃ ┣ DeleteUserForm-DIzErTvn.js
 ┃ ┃ ┣ Edit-CsoU_YqB.js
 ┃ ┃ ┣ ForgotPassword-CvVPYpOJ.js
 ┃ ┃ ┣ GuestLayout-CfUL-Q5T.js
 ┃ ┃ ┣ Login-BdSWQjRJ.js
 ┃ ┃ ┣ PrimaryButton-F0c5Bn9c.js
 ┃ ┃ ┣ Register-BDcYP4CE.js
 ┃ ┃ ┣ ResetPassword-CwmtV5Cc.js
 ┃ ┃ ┣ TextInput-DlPtQJWm.js
 ┃ ┃ ┣ UpdatePasswordForm-CLuheKD8.js
 ┃ ┃ ┣ UpdateProfileInformationForm-CqUggegl.js
 ┃ ┃ ┣ VerifyEmail-BXCNo51A.js
 ┃ ┃ ┣ Welcome-DLdnGt5r.js
 ┃ ┃ ┗ _plugin-vue_export-helper-DlAUqK2U.js
 ┃ ┗ manifest.json
 ┣ .htaccess
 ┣ favicon.ico
 ┣ hot
 ┣ index.php
 ┗ robots.txt
resources
 ┣ css
 ┃ ┗ app.css
 ┣ js
 ┃ ┣ api
 ┃ ┃ ┗ client.js
 ┃ ┣ Components
 ┃ ┃ ┣ ui
 ┃ ┃ ┃ ┣ button
 ┃ ┃ ┃ ┃ ┣ Button.vue
 ┃ ┃ ┃ ┃ ┗ index.js
 ┃ ┃ ┃ ┣ card
 ┃ ┃ ┃ ┃ ┣ Card.vue
 ┃ ┃ ┃ ┃ ┣ CardAction.vue
 ┃ ┃ ┃ ┃ ┣ CardContent.vue
 ┃ ┃ ┃ ┃ ┣ CardDescription.vue
 ┃ ┃ ┃ ┃ ┣ CardFooter.vue
 ┃ ┃ ┃ ┃ ┣ CardHeader.vue
 ┃ ┃ ┃ ┃ ┣ CardTitle.vue
 ┃ ┃ ┃ ┃ ┗ index.js
 ┃ ┃ ┃ ┣ Badge.vue
 ┃ ┃ ┃ ┣ DataTable.vue
 ┃ ┃ ┃ ┣ Dialog.vue
 ┃ ┃ ┃ ┣ Input.vue
 ┃ ┃ ┃ ┣ Pagination.vue
 ┃ ┃ ┃ ┣ Select.vue
 ┃ ┃ ┃ ┣ Slider.vue
 ┃ ┃ ┃ ┣ Table.vue
 ┃ ┃ ┃ ┗ Tabs.vue
 ┃ ┃ ┣ ApplicationLogo.vue
 ┃ ┃ ┣ Checkbox.vue
 ┃ ┃ ┣ DangerButton.vue
 ┃ ┃ ┣ Dropdown.vue
 ┃ ┃ ┣ DropdownLink.vue
 ┃ ┃ ┣ InputError.vue
 ┃ ┃ ┣ InputLabel.vue
 ┃ ┃ ┣ Layout.vue
 ┃ ┃ ┣ Modal.vue
 ┃ ┃ ┣ NavLink.vue
 ┃ ┃ ┣ PrimaryButton.vue
 ┃ ┃ ┣ ResponsiveNavLink.vue
 ┃ ┃ ┣ SecondaryButton.vue
 ┃ ┃ ┗ TextInput.vue
 ┃ ┣ Layouts
 ┃ ┃ ┣ AuthenticatedLayout.vue
 ┃ ┃ ┗ GuestLayout.vue
 ┃ ┣ lib
 ┃ ┃ ┗ utils.js
 ┃ ┣ Pages
 ┃ ┃ ┣ Auth
 ┃ ┃ ┃ ┣ ConfirmPassword.vue
 ┃ ┃ ┃ ┣ ForgotPassword.vue
 ┃ ┃ ┃ ┣ Login.vue
 ┃ ┃ ┃ ┣ Register.vue
 ┃ ┃ ┃ ┣ ResetPassword.vue
 ┃ ┃ ┃ ┗ VerifyEmail.vue
 ┃ ┃ ┣ Profile
 ┃ ┃ ┃ ┣ Partials
 ┃ ┃ ┃ ┃ ┣ DeleteUserForm.vue
 ┃ ┃ ┃ ┃ ┣ UpdatePasswordForm.vue
 ┃ ┃ ┃ ┃ ┗ UpdateProfileInformationForm.vue
 ┃ ┃ ┃ ┗ Edit.vue
 ┃ ┃ ┣ Dashboard.vue
 ┃ ┃ ┣ ProductDetail.vue
 ┃ ┃ ┣ Products.vue
 ┃ ┃ ┣ SpkCalc.vue
 ┃ ┃ ┗ Welcome.vue
 ┃ ┣ app.js
 ┃ ┣ App.vue
 ┃ ┗ bootstrap.js
 ┗ views
 ┃ ┗ app.blade.php
routes
 ┣ api.php
 ┣ auth.php
 ┣ console.php
 ┗ web.php
tests
 ┣ Feature
 ┃ ┣ Auth
 ┃ ┃ ┣ AuthenticationTest.php
 ┃ ┃ ┣ EmailVerificationTest.php
 ┃ ┃ ┣ PasswordConfirmationTest.php
 ┃ ┃ ┣ PasswordResetTest.php
 ┃ ┃ ┣ PasswordUpdateTest.php
 ┃ ┃ ┗ RegistrationTest.php
 ┃ ┣ ExampleTest.php
 ┃ ┗ ProfileTest.php
 ┣ Unit
 ┃ ┗ ExampleTest.php
 ┗ TestCase.php
 ┣ .editorconfig
 ┣ .env
 ┣ .env.example
 ┣ .gitattributes
 ┣ .gitignore
 ┣ AGENTS.md
 ┣ artisan
 ┣ components.json
 ┣ composer.json
 ┣ composer.lock
 ┣ jsconfig.json
 ┣ LICENSE
 ┣ package-lock.json
 ┣ package.json
 ┣ phpunit.xml
 ┣ postcss.config.js
 ┣ README.md
 ┣ tailwind.config.js
 ┗ vite.config.js
```

---

## 3. Rules & Conventions

### 3.1 Backend (Laravel)

- **All web routes** must return `Inertia::render()`, not Blade views.
- Use **Form Requests** for validation; never validate in controllers.
- Middleware `HandleInertiaRequests` must be registered in `bootstrap/app.php`.
- Shared props (user, flash messages, permissions) are sent via `HandleInertiaRequests::share()`.
- Use **Eloquent API Resources** if complex data transformation is needed before sending to frontend.
- Never send sensitive data (passwords, tokens) in shared props.

### 3.2 Frontend (Vue + Inertia)

- **Composition API with `<script setup>`** is the standard. Do not use Options API.
- Every page is a **single file component (SFC)** in `resources/js/Pages/`.
- Page filenames must be **PascalCase** and match the Inertia route name.
- Use **Inertia `<Form>` component** or `useForm()` for form handling.
- For non-native shadcn-vue components (Checkbox, Switch, Radio), add a hidden native input or use manual `useForm()` tracking so dirty state and validation errors still work.
- Import shadcn components from `@/Components/ui/[component]`.
- Use the `cn()` utility from `@/lib/utils.ts` for merging Tailwind classes.

### 3.3 shadcn-vue Components

- Install components via CLI: `npx shadcn-vue@latest add <component>`.
- Do not manually modify files in `resources/js/Components/ui/` if avoidable; use `class` props or wrapper components instead.
- If drastic customization is needed, **copy the component to a custom folder** and modify it there.
- Ensure all required Radix UI primitives are installed.

### 3.4 Styling (Tailwind CSS v4)

- Use **Tailwind CSS v4** with the Vite plugin (`@tailwindcss/vite`).
- The `resources/css/app.css` file should only contain `@import "tailwindcss"` and custom CSS variables.
- Avoid writing custom CSS; prioritize Tailwind utility classes.
- Use `dark:` prefix for dark mode. Enable via `class` strategy in Tailwind config.

### 3.5 TypeScript

- All Vue and TS files must use TypeScript.
- Define interfaces for props, emits, and Inertia data.
- Use `defineProps<>()` and `defineEmits<>()` with explicit types.

---

## 4. Setup & Installation (Quick Start)

### 4.1 Project Initialization

```bash
# 1. Create a new Laravel project with Vue starter
laravel new my-app --vue

# 2. Enter directory
cd my-app

# 3. Install dependencies
composer install
npm install
```

### 4.2 Install & Configure Inertia.js

```bash
# Install Inertia Laravel
composer require inertiajs/inertia-laravel

# Generate middleware
php artisan inertia:middleware
```

Register middleware in `bootstrap/app.php`:

```php
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Configuration\Middleware;

->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        HandleInertiaRequests::class,
    ]);
})
```

### 4.3 Install shadcn-vue

```bash
# Initialize shadcn-vue
npx shadcn-vue@latest init

# Answer the setup questions:
# - Style: Default / New York
# - Base color: Slate / Zinc / Neutral
# - CSS variables: Yes (recommended)
# - TypeScript: Yes
```

### 4.4 Vite Configuration (`vite.config.js`)

```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/main.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
            '@components': path.resolve(__dirname, './resources/js/components'),
            '@views': path.resolve(__dirname, './resources/js/views'),
            '@stores': path.resolve(__dirname, './resources/js/stores'),
            '@api': path.resolve(__dirname, './resources/js/api'),
            '@lib': path.resolve(__dirname, './resources/js/lib'),
        },
    },
});
```

### 4.5 Vue Entry Point (`resources/js/app.js`)

```javascript
import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

import DashboardLayout from './Layouts/DashboardLayout.vue';
import GuestLayout from './Layouts/GuestLayout.vue';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: async (name) => {
        const page = await resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        );

        // Layout otomatis:
        // - Auth/* (Login, Register, dll) → GuestLayout
        // - Selain itu → DashboardLayout (default)
        if (name.startsWith('Auth/')) {
            page.default.layout = GuestLayout;
        } else {
            page.default.layout = page.default.layout || DashboardLayout;
        }

        return page;
    },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
```

### 4.6 Root Blade Template (`resources/views/app.blade.php`)

```blade
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SPK Marketplace Teknologi</title>
    @vite(['resources/js/main.js', 'resources/css/app.css'])
</head>
<body>
    <div id="app"></div>
</body>
</html>
```

### 4.7 Tailwind CSS v4 (`resources/css/app.css`)

```css

@import url('https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700&display=swap');

@tailwind base;
@tailwind components;
@tailwind utilities;

@layer base {
  .theme {
    --font-heading: var(--font-sans)}
  :root {
    --background: 0 0% 100%;
    --foreground: 0 0% 3.9%;
    --card: 0 0% 100%;
    --card-foreground: 0 0% 3.9%;
    --popover: 0 0% 100%;
    --popover-foreground: 0 0% 3.9%;
    --primary: 0 0% 9%;
    --primary-foreground: 0 0% 98%;
    --secondary: 0 0% 96.1%;
    --secondary-foreground: 0 0% 9%;
    --muted: 0 0% 96.1%;
    --muted-foreground: 0 0% 45.1%;
    --accent: 0 0% 96.1%;
    --accent-foreground: 0 0% 9%;
    --destructive: 0 84.2% 60.2%;
    --destructive-foreground: 0 0% 98%;
    --border: 0 0% 89.8%;
    --input: 0 0% 89.8%;
    --ring: 0 0% 3.9%;
    --chart-1: 12 76% 61%;
    --chart-2: 173 58% 39%;
    --chart-3: 197 37% 24%;
    --chart-4: 43 74% 66%;
    --chart-5: 27 87% 67%;
    --radius: 0.5rem
  }
  .dark {
    --background: 0 0% 3.9%;
    --foreground: 0 0% 98%;
    --card: 0 0% 3.9%;
    --card-foreground: 0 0% 98%;
    --popover: 0 0% 3.9%;
    --popover-foreground: 0 0% 98%;
    --primary: 0 0% 98%;
    --primary-foreground: 0 0% 9%;
    --secondary: 0 0% 14.9%;
    --secondary-foreground: 0 0% 98%;
    --muted: 0 0% 14.9%;
    --muted-foreground: 0 0% 63.9%;
    --accent: 0 0% 14.9%;
    --accent-foreground: 0 0% 98%;
    --destructive: 0 62.8% 30.6%;
    --destructive-foreground: 0 0% 98%;
    --border: 0 0% 14.9%;
    --input: 0 0% 14.9%;
    --ring: 0 0% 83.1%;
    --chart-1: 220 70% 50%;
    --chart-2: 160 60% 45%;
    --chart-3: 30 80% 55%;
    --chart-4: 280 65% 60%;
    --chart-5: 340 75% 55%
  }
}

@layer base {
  * {
    @apply border-border;
  }
  body {
    @apply bg-background text-foreground;
  }
}

```

---

## 5. Development Patterns

### 5.1 Route -> Controller -> Page

```php
// routes/web.php
<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Welcome page (publik)
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/products', function () {
        return Inertia::render('Products');
    })->name('products');

    Route::get('/products/{id}', function ($id) {
        return Inertia::render('ProductDetail', ['id' => $id]);
    })->name('product-detail');

    Route::get('/spk', function () {
        return Inertia::render('SpkCalc');
    })->name('spk');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth routes (login, register, logout, dll)
require __DIR__.'/auth.php';
```

---

## 6. Best Practices

### 6.1 Performance
- Use `import.meta.glob()` with `eager: false` for lazy loading pages.
- Implement server-side pagination; never load all data at once.
- Use `defer` or `lazy` on Inertia props that are not critical.

### 6.2 Security
- Always validate on the backend (Form Request).
- Use Laravel Sanctum for API tokens if there is a mobile app.
- XSS protection is automatic via Vue, but always sanitize user input on the backend.

### 6.3 Accessibility
- shadcn-vue components are accessible by default (Radix UI primitives).
- Ensure all form elements have a `<label>` connected via `for`.
- Use `aria-*` attributes if creating custom components.

### 6.4 State Management
- Avoid Pinia/Vuex unless for complex global state.
- Use Inertia shared props for auth user and flash messages.
- Use composables for reusable logic.

---

## 7. Common Troubleshooting

| Issue | Solution |
|-------|----------|
| shadcn component not showing / greyed out | Ensure `@import "tailwindcss"` is in `app.css` and there is no conflict with old PostCSS config. |
| Form dirty state not working with shadcn Checkbox/Switch | Use a hidden native input or `useForm()` with manual tracking. |
| Ziggy route not recognized | Ensure `ziggy-js` alias is in `vite.config.ts` and `ZiggyVue` plugin is installed. |
| Tailwind classes not applied | Remove old `postcss.config.js` if using Tailwind v4. |
| TypeScript error on Inertia page | Ensure `tsconfig.json` includes `resources/js/**/*.vue` and `resources/js/**/*.ts`. |

---

## 8. References

- [Laravel Documentation](https://laravel.com/docs)
- [Inertia.js Documentation](https://inertiajs.com/)
- [Vue 3 Documentation](https://vuejs.org/)
- [shadcn-vue Documentation](https://www.shadcn-vue.com/)
- [Tailwind CSS v4 Documentation](https://tailwindcss.com/docs/v4-beta)

---

*Last updated: 2026-07-01*