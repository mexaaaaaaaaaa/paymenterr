# Backup and Restore Procedures - ZyperNodes Paymenter

## Backup Procedure

### File System Backup
1. Paymenter application files:
   ```bash
   tar -czf paymenter-files-backup-$(date +%Y%m%d-%H%M%S).tar.gz /var/www/paymenter/
   ```

### Database Backup
1. MariaDB database backup:
   ```bash
   mysqldump -u root -p paymenter_db > paymenter-db-backup-$(date +%Y%m%d-%H%M%S).sql
   ```
   Or if using a specific user:
   ```bash
   mysqldump -u paymenter_user -p paymenter_db > paymenter-db-backup-$(date +%Y%m%d-%H%M%S).sql
   ```

### Environment and Configuration Backup
1. Environment file:
   ```bash
   cp /var/www/paymenter/.env /var/www/paymenter/.env.backup-$(date +%Y%m%d-%H%M%S)
   ```

2. Configuration files (if applicable):
   ```bash
   cp -r /var/www/paymenter/config /var/www/paymenter/config.backup-$(date +%Y%m%d-%H%M%S)
   ```

## Restore Procedure

### File System Restore
1. Extract the backup:
   ```bash
   tar -xzf paymenter-files-backup-$(date +%Y%m%d-%H%M%S).tar.gz -C /
   ```

### Database Restore
1. Restore the database:
   ```bash
   mysql -u root -p paymenter_db < paymenter-db-backup-$(date +%Y%m%d-%H%M%S).sql
   ```

### Environment and Configuration Restore
1. Restore environment file:
   ```bash
   cp /var/www/paymenter/.env.backup-$(date +%Y%m%d-%H%M%S) /var/www/paymenter/.env
   ```

2. Restore configuration files (if applicable):
   ```bash
   cp -r /var/www/paymenter/config.backup-$(date +%Y%m%d-%H%M%S)/* /var/www/paymenter/config/
   ```

## Verification After Restore
1. Check file permissions:
   ```bash
   chown -R www-data:www-data /var/www/paymenter/
   find /var/www/paymenter -type f -exec chmod 644 {} \;
   find /var/www/paymenter -type d -exec chmod 755 {} \;
   ```

2. Clear caches:
   ```bash
   php artisan cache:clear
   php artisan view:clear
   php artisan config:clear
   ```

3. Test the application by accessing the homepage and key functionality.

## Automated Backup Script (Example)
```bash
#!/bin/bash
TIMESTAMP=$(date +%Y%m%d-%H%M%S)
BACKUP_DIR="/var/backups/paymenter"
mkdir -p $BACKUP_DIR

# Backup files
tar -czf $BACKUP_DIR/paymenter-files-$TIMESTAMP.tar.gz /var/www/paymenter/

# Backup database
mysqldump -u paymenter_user -p"your_password" paymenter_db > $BACKUP_DIR/paymenter-db-$TIMESTAMP.sql

# Backup environment
cp /var/www/paymenter/.env $BACKUP_DIR/paymenter-env-$TIMESTAMP

# Keep only last 7 days of backups
find $BACKUP_DIR -type f -mtime +7 -delete
```

## Notes
- Always verify backups by restoring to a test environment before relying on them
- Store backups in a secure, off-site location when possible
- Test restore procedures regularly
- Include backup verification in deployment checklists