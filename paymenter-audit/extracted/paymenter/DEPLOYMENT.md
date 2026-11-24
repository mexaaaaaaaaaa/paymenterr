# Deployment Guide - ZyperNodes Paymenter

## Pre-Deployment Checklist
- [ ] Verify all code changes are committed and pushed
- [ ] Run `php artisan test` to ensure tests pass
- [ ] Check for any PHP syntax errors: `php -l` on all modified PHP/Blade files
- [ ] Verify database migrations are up to date
- [ ] Clear application caches
- [ ] Check file permissions and ownership
- [ ] Verify backup procedures are working
- [ ] Test in staging environment if available

## Deployment Steps
1. **Maintenance Mode** (if applicable):
   ```bash
   php artisan down
   ```

2. **Pull Latest Changes**:
   ```bash
   git pull origin main
   ```

3. **Install Dependencies** (if any changed):
   ```bash
   composer install --no-dev --optimize-autoloader
   npm install --production
   ```

4. **Run Database Migrations**:
   ```bash
   php artisan migrate --force
   ```

5. **Clear Caches**:
   ```bash
   php artisan cache:clear
   php artisan view:clear
   php artisan config:clear
   php artisan route:clear
   ```

6. **Compile Assets** (if applicable):
   ```bash
   npm run production
   ```

7. **Requeue Any Failed Jobs** (if applicable):
   ```bash
   php artisan queue:retry all
   ```

8. **Exit Maintenance Mode**:
   ```bash
   php artisan up
   ```

## Post-Deployment Verification
- [ ] Test homepage loads correctly
- [ ] Test user registration and login
- [ ] Test product browsing and search
- [ ] Test cart and checkout process (using test payment mode)
- [ ] Test invoice generation and viewing
- [ ] Test dashboard access
- [ ] Test support ticket creation
- [ ] Check for any JavaScript console errors
- [ ] Verify responsive design on mobile/tablet/desktop
- [ ] Check server logs for any errors

## Rollback Procedure
1. Enter maintenance mode: `php artisan down`
2. Restore file system from backup
3. Restore database from backup
4. Clear caches: `php artisan cache:clear`
5. Exit maintenance mode: `php artisan up`
6. Verify application is functioning correctly

## Environment Variables
Ensure the following are set correctly in .env:
- APP_URL=https://zypernodes.cloud
- DB_CONNECTION=mysql
- DB_HOST=127.0.0.1
- DB_PORT=3306
- DB_DATABASE=paymenter_db
- DB_USERNAME=root
- DB_PASSWORD=
- CACHE_DRIVER=file
- QUEUE_CONNECTION=sync
- SESSION_DRIVER=file