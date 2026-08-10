# Debug Log: Homepage CSS and Vite Development Server Issues

## Issue 1: Missing Custom CSS / Unstyled Components
**Symptoms:** The homepage lacked all custom colors (e.g., `bg-vander-navy`, `text-vander-text`), causing components to appear unstyled.
**Root Cause:** The project is using **Tailwind CSS v4**. In Tailwind v4, the framework no longer relies on the legacy `tailwind.config.js` file for theme customization. As a result, the custom color palette defined in `tailwind.config.js` was completely ignored during the Vite build process.
**Resolution:** Migrated the custom color configurations from `tailwind.config.js` directly into `resources/css/app.css` utilizing Tailwind v4's new `@theme` CSS directive. This correctly registered the colors with the Tailwind build engine.

## Issue 2: Vite Assets and External Fonts Blocked by CSP
**Symptoms:** When running `npm run dev` and `php artisan serve`, the browser console reported that `app.css`, `app.js`, the Vite HMR `client`, and `font-awesome.min.css` were blocked by the `Content-Security-Policy` (CSP).
**Root Cause:** The Laravel application had a strict CSP enforced via the `app/Http/Middleware/SecurityHeaders.php` middleware. The Vite development server relies on dynamic local ports (e.g., `http://[::1]:5173`) and inline scripts for Hot Module Replacement (HMR). Additionally, the CSP did not whitelist `cdnjs.cloudflare.com` for fonts. The strict production CSP rules blocked these local development asset injections.
**Resolution:** Updated `SecurityHeaders.php` to conditionally disable the strict CSP when the application environment is set to `local` (`app()->environment('local')`). This is standard practice, as it safely allows Vite's HMR to function seamlessly during local development while maintaining robust security in production.
