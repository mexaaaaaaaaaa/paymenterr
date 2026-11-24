# ZYPERNODES SMM HUB — Installation & Rollback Documentation

## Overview
ZyperNodes SMM Hub is a Paymenter extension (`extensions/Others/Smm`) that adds a Social Media Marketplace (EasySMMPanel) to the existing Paymenter billing website. It reuses the existing cart, checkout, invoices, payment gateways, authentication, and Filament admin.

## Detected Environment
- Paymenter version: 1.2.x (composer name `paymenter/paymenter`)
- Laravel Framework: 12.65.0
- PHP: 8.4.26 (CLI), PHP-FPM 8.4 + 8.3
- Database: MariaDB 10.11.14 (database: `paymenter`)
- Web server: nginx + php8.4-fpm
- Queue worker: `paymenter-queue.service` (database queue)
- Scheduler: `cron.service` + Laravel schedule
- Site: https://zypernodes.cloud (Paymenter at root), billing at billing.zypernodes.cloud

## What was NOT done (safety rules respected)
- Paymenter was NOT reinstalled.
- Existing website was NOT replaced.
- No existing products, users, orders, invoices, or database records were deleted/modified.
- Existing hosting products and checkout remain intact.
- No API keys/secrets exposed in frontend.
- No test orders placed using real provider credits (mocked HTTP used).

## Prerequisites
- bcmath PHP extension (used for decimal-safe pricing) — verify `php -m | grep bcmath`.
- The EasySMMPanel API key must be configured (see Settings below).

## Installation
1. **Backup** application files and database (see `/root/backups_20261106_smm_deploy/`).
2. Copy the `extensions/Others/Smm` directory into the Paymenter installation.
3. Copy `app/Admin/Resources/Smm` into the Paymenter installation.
4. Regenerate autoloader:
   ```
   cd /var/www/paymenter
   composer dump-autoload
   ```
5. Run the SMM migrations:
   ```
   php artisan smm:sync-services   # also runs migrations via extension boot
   ```
   Or run migrations directly:
   ```
   php -r "require 'vendor/autoload.php'; \$app=require 'bootstrap/app.php'; \$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap(); App\Helpers\ExtensionHelper::runMigrations('extensions/Others/Smm/database/migrations');"
   ```
6. Register the extension in the `extensions` table (enabled, type `other`, extension `Smm`) so `boot()` runs on each request:
   ```sql
   INSERT INTO extensions (name, enabled, extension, type, created_at, updated_at)
   VALUES ('SMM Hub', 1, 'Smm', 'other', NOW(), NOW());
   ```
7. Configure the provider: in Filament Admin → SMM Hub → Providers, ensure EasySMMPanel is active & default, and enter the API key (encrypted). Or seed via the `smm_providers` table.
8. Seed default categories (Instagram, YouTube, Facebook, Telegram, TikTok, Twitter, Spotify, Discord, Other) — provided in migration data or created via Admin → SMM Hub → Categories.
9. Sync services from EasySMMPanel:
   ```
   php artisan smm:sync-services
   ```
10. Clear caches:
    ```
    php artisan config:clear
    php artisan view:clear
    php artisan route:clear
    systemctl restart php8.4-fpm
    ```
11. Verify queue worker & scheduler are running:
    ```
    systemctl status paymenter-queue.service
    systemctl status cron.service
    ```

## Scheduler (already added to `routes/console.php`)
- `smm:sync-services` — hourly (service catalogue sync)
- `smm:sync-orders` — every 10 minutes (order status sync)
Both use `->withoutOverlapping()->onOneServer()` to avoid duplicate workers.

## Routes added
Storefront: `/category/smm`, `/category/smm/{slug}`, `/productslist/{slug}`, `/smm/service/{slug}`, `/smm/cart`, `/smm/checkout`
Customer: `/account/smm-orders`, `/account/smm-orders/{order}`
Webhook: `POST /extensions/smm/webhook`
Admin (Filament): `/admin/smm/smm-services`, `/admin/smm/smm-orders`, `/admin/smm/smm-providers`, `/admin/smm/smm-categories`

## Rollback
1. Put the site into maintenance or take the SMM extension offline:
   ```sql
   UPDATE extensions SET enabled = 0 WHERE extension = 'Smm' AND type = 'other';
   ```
2. Clear caches and restart php-fpm.
3. To fully remove, drop the `smm_*` tables and delete the extension row, then remove the `extensions/Others/Smm` and `app/Admin/Resources/Smm` directories and run `composer dump-autoload`.
4. Restore from backup if needed:
   - App files: `tar xzf /root/backups_20261106_smm_deploy/paymenter_app_*.tar.gz -C /var/www/paymenter`
   - Database: `mysql -u root paymenter < /root/backups_20261106_smm_deploy/paymenter_db_*.sql`

Backup location: `/root/backups_20261106_smm_deploy/`
