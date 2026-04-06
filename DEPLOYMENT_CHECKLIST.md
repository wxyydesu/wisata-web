# 🚀 DEPLOYMENT CHECKLIST - Wisata-Web Features

## Pre-Deployment ✅

### Database
- [ ] Backup existing database
- [ ] Run migrations: `php artisan migrate`
- [ ] Verify tables created:
  - `penginapans` (new columns: harga_per_malam, kapasitas, lokasi, status)
  - `penginapan_reservasis` (new table)
  - `ulasan` (new columns: paket_wisata_id, reservasi_id, penginapan_reservasi_id)

### Environment Configuration
- [ ] Set Midtrans SERVER_KEY in .env or config/midtrans.php
- [ ] Set Midtrans CLIENT_KEY in .env or config/midtrans.php
- [ ] Verify Mail config for payment notifications
- [ ] Check timezone in config/app.php matches server timezone

### File System
- [ ] Verify storage/public directory exists and is writable
- [ ] Check public/storage symlink or direct path for bukti_tf uploads

### Cron Job (if not using Kernel scheduler)
Add to crontab:
```bash
* * * * * cd /path/to/wisata-web && php artisan schedule:run >> /dev/null 2>&1
```
Or use system task scheduler:
```bash
# Windows Task Scheduler
# Mac/Linux crontab or systemd timer
```

---

## Feature 1: Booking Penginapan ✅

### Backend Admin Interface
- [ ] Access `/dashboard/penginapan-reservasi`
- [ ] Test Create reservation:
  - [ ] Select pelanggan
  - [ ] Select penginapan
  - [ ] Pick check-in/check-out dates
  - [ ] Price calculates automatically
  - [ ] Apply discount
  - [ ] Submit form
- [ ] Test Edit reservation
- [ ] Test pay in Midtrans payment gateway (use sandbox)
- [ ] Test Delete with confirmation

### Booking Fields in Penginapan Master Data
- [ ] Edit penginapan to set:
  - [ ] Harga per malam
  - [ ] Kapasitas kamar
  - [ ] Lokasi
  - [ ] Status (tersedia/tidak tersedia)

### Midtrans Integration
- [ ] Test payment with Midtrans sandbox card
  - [ ] Settlement success → status updates to 'booking'
  - [ ] Pending payment → status stays 'menunggu konfirmasi'
  - [ ] Failed payment → callback handled gracefully
- [ ] Verify transaction details stored:
  - [ ] midtrans_order_id
  - [ ] midtrans_transaction_id
  - [ ] midtrans_status
  - [ ] midtrans_payment_type

---

## Feature 2: Review System ✅

### Frontend - Both Detail Pages

#### Detail Paket (Tour Package):
- [ ] Scroll to reviews tab
- [ ] If NOT logged in:
  - [ ] See "Login to leave a review" prompt
- [ ] If logged in:
  - [ ] See 5-star rating input (interactive stars)
  - [ ] See comment textarea
  - [ ] Submit review via AJAX
  - [ ] Page doesn't reload
  - [ ] Success notification appears (SweetAlert)
- [ ] Verify reviews display:
  - [ ] User name
  - [ ] Rating stars
  - [ ] Comment text
  - [ ] Time ago (e.g., "2 hours ago")
  - [ ] Average rating calculated correctly

#### Detail Penginapan (Accommodation):
- [ ] Same review flow as paket
- [ ] Verify accommodation-specific reviews show correctly
- [ ] Test mixing paket and penginapan reviews doesn't interfere

### Backend Management
- [ ] Admin can see all reviews in detail page
- [ ] User can edit own review (update rating/comment)
- [ ] User can delete own review
- [ ] Admin can delete any review
- [ ] Unauthorized users get 403 error

---

## Feature 3: Expiration System ✅

### Manual Command Testing
```bash
# Run command to expire past bookings
php artisan bookings:expire --type=all

# Run only for tour packages
php artisan bookings:expire --type=reservasi

# Run only for accommodations  
php artisan bookings:expire --type=penginapan
```

- [ ] Create test booking with past date
- [ ] Run command
- [ ] Verify status changed from 'booking' to 'selesai'

### Helper Methods Testing
- [ ] In blade template: `@if($reservasi->isExpired())`
- [ ] Display remaining days: `{{ $reservasi->getRemainingDays() }}` days left

### Scheduler Setup (Choose One)

**Option A: Laravel Kernel Scheduler**
If Kernel.php exists:
- [ ] Open app/Console/Kernel.php
- [ ] Add to schedule() method:
  ```php
  $schedule->command('bookings:expire --type=all')->daily();
  ```
- [ ] Verify cron job runs: `* * * * * cd /path && php artisan schedule:run`

**Option B: Manual Cron Job** (if no Kernel supported)
- [ ] Setup system cron to run daily:
  ```bash
  0 2 * * * cd /path/to/wisata-web && php artisan bookings:expire
  ```

**Option C: Middleware-based** (real-time)
- [ ] Create middleware to check & expire on each request (not recommended for performance)

---

## Post-Deployment Testing 🧪

### Integration Tests
- [ ] Create new pelanggan account
- [ ] Complete penginapan booking flow with payment
- [ ] Leave review on completed booking
- [ ] Verify booking expires automatically after date passes

### Email Notifications (if configured)
- [ ] New booking confirmation email received
- [ ] Payment receipt email received after Midtrans success
- [ ] Expiration notification (optional)

### Performance Check
- [ ] Load `/dashboard/penginapan-reservasi` with 100+ records → no timeout
- [ ] Load detail page with 50+ reviews → renders quickly
- [ ] Midtrans callback takes <5 seconds

### Security Audit
- [ ] Try accessing reservasi without auth → redirected to login
- [ ] Try accessing another user's reservasi edit → 403 or redirected
- [ ] Try SQL injection in filters → sanitized/escaped
- [ ] CSRF tokens present on all forms

---

## Rollback Plan 🔙

If issues occur:

```bash
# Rollback database (remove new tables/columns)
php artisan migrate:rollback

# Revert code changes (git)
git revert <commit_hash>

# Restore from backup
# Use your backup tool to restore pre-deployment database
```

---

## Common Issues & Solutions

### 1. Midtrans Payment Fails
**Solution**: 
- [ ] Verify SERVER_KEY and CLIENT_KEY correct
- [ ] Check Midtrans account status (active/suspended)
- [ ] Review Midtrans dashboard for error logs
- [ ] Test with sandbox credentials first

### 2. Expiration Command Doesn't Run
**Solution**:
- [ ] Verify cron job installed: `crontab -l` (Linux/Mac)
- [ ] Check Laravel scheduler is enabled in Kernel.php
- [ ] Run manually: `php artisan bookings:expire` to verify command works
- [ ] Check logs: `storage/logs/laravel.log`

### 3. Reviews Not Showing
**Solution**:
- [ ] Verify migration ran: `php artisan migrate --refresh`
- [ ] Check Ulasan table has new columns
- [ ] Verify foreign keys created
- [ ] Check pagination/query in controller

### 4. Upload Bukti Transfer Fails
**Solution**:
- [ ] Check storage/public writable: `chmod -R 755 storage/public`
- [ ] Verify storage symlink: `php artisan storage:link`
- [ ] Check file size limit in php.ini (upload_max_filesize)

---

## Monitoring Post-Launch

- [ ] Monitor Laravel logs: `tail -f storage/logs/laravel.log`
- [ ] Check Midtrans transaction dashboard daily
- [ ] Verify cron executions: Check system log or setup monitoring
- [ ] Monitor database growth: `penginapan_reservasis` table size
- [ ] Setup alerts for failed payments or expired bookings

---

## Sign-Off

- [ ] All tests passed
- [ ] Stakeholders approved features
- [ ] Deployment documentation complete
- [ ] Team briefed on maintenance tasks
- [ ] Monitoring setup complete

**Deployed By**: _________________  
**Date**: _________________  
**Notes**: _________________

---

**Next Review Date**: 1 week after deployment

