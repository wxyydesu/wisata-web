# ⚡ QUICK TEST GUIDE - 10 Minute Verification

**Goal**: Verify all three features are working correctly in 10 minutes

---

## 🚀 Pre-Test Setup (2 minutes)

```bash
# 1. Ensure migrations are run
php artisan migrate

# 2. Start server
php artisan serve
# Server will run on http://127.0.0.1:8000
```

---

## ✅ Test 1: Booking System (3 minutes)

### Admin Interface
1. Go to: `http://localhost:8000/dashboard/penginapan-reservasi`
2. Click **"Tambah Reservasi"** (or "Create")
3. Fill form:
   - Select Pelanggan: Any customer
   - Select Penginapan: Any accommodation
   - Check In: Tomorrow's date
   - Check Out: Day after tomorrow
   - Jumlah Kamar: 2
   - Diskon: 10 %
4. ✅ **Verify Price Calculates Automatically**:
   - Should show: Harga/Malam × 2 nights × 2 rooms - 10% discount
   - Example: 500,000 × 2 × 2 - 10% = 1,800,000
5. Click **"Simpan"**
6. Go back to list: `/dashboard/penginapan-reservasi`
   - ✅ New booking should appear in table
   - ✅ Status should be "Menunggu Konfirmasi"

---

## ✅ Test 2: Review System (4 minutes)

### Customer Review on Paket Wisata
1. Go to: `http://localhost:8000/paket-wisata` or from homepage
2. Click any package detail
3. Scroll to **"Reviews"** tab/section
4. ✅ **If NOT logged in**: Should see "Login untuk memberikan ulasan"
5. **Login** if needed: Use any customer account
6. Back to package detail page
7. **Give Review**:
   - Click **5 stars** (should highlight yellow)
   - Type comment: "Paket ini sangat bagus dan sesuai budget!"
   - Click **"Kirim Ulasan"** or "Submit"
8. ✅ **Verify AJAX Response**:
   - Page should NOT reload
   - Success notification should appear (green alert)
   - Review should appear immediately in list below
   - Shows: Your name, 5 stars, comment, "just now"

### Customer Review on Penginapan
1. Go to: `http://localhost:8000/penginapan` or from homepage
2. Click any accommodation detail
3. Same process as paket - scroll to reviews section
4. ✅ **Repeat review submission**
5. ✅ **Verify reviews show for accommodation**

### Verify Average Rating
- Look at review section header
- Should show: "Rating Rata-rata: 5 ⭐" (if only 5-star review submitted)
- All stars should be filled (yellow) next to average

---

## ✅ Test 3: Expiration System (2 minutes)

### Manual Command Test
1. Open terminal/command prompt
2. Navigate to project: `cd c:\xampp\htdocs\LSP\wisata-web`
3. Run command:
   ```bash
   php artisan bookings:expire
   ```
4. ✅ **Expected Output**:
   ```
   Marked X bookings as expired.
   ```
5. Verify in database:
   ```bash
   # Optional: Check in database via admin tool
   SELECT * FROM reservasis WHERE status_reservasi = 'selesai';
   SELECT * FROM penginapan_reservasis WHERE status_reservasi = 'selesai';
   ```

### Helper Methods Test (Optional)
1. Open tinker:
   ```bash
   php artisan tinker
   ```
2. Test helper:
   ```php
   >>> $res = \App\Models\Reservasi::first()
   >>> $res->isExpired()      // Should return boolean
   >>> $res->getRemainingDays()  // Should return int
   ```
3. Exit: `exit()`

---

## 🎯 Success Criteria

| Feature | Test | Expected | Status |
|---------|------|----------|--------|
| **Booking** | Admin create | Reservation created with auto-calculation | ✅ / ❌ |
| **Booking** | Price calculation | Accurate with discount applied | ✅ / ❌ |
| **Review** | Submit review | AJAX submit, no page reload | ✅ / ❌ |
| **Review** | Display review | Shows with name, stars, comment, time | ✅ / ❌ |
| **Review** | Average rating | Calculates correctly | ✅ / ❌ |
| **Expiration** | Command runs | Returns success message | ✅ / ❌ |
| **Expiration** | Database update | Status changes to 'selesai' | ✅ / ❌ |

---

## 🐛 Troubleshooting Quick Fixes

### Problem: "404 Not Found" on penginapan-reservasi
**Fix**: Check routes registered
```bash
php artisan route:list | grep penginapan-reservasi
# Should show 7 routes
```

### Problem: Review not submitted / CSRF error
**Fix**: Check token is in form
```blade
@csrf  <!-- Must be in form -->
```

### Problem: Price calculation not working
**Fix**: Open browser console (F12) → Console tab
- Look for JavaScript errors
- Verify form IDs match JavaScript: `harga_per_malam_input`, `lama_malam_input`, etc.

### Problem: Command gives "Class not found"
**Fix**: Verify file exists
```bash
ls app/Console/Commands/ExpireBookings.php
# If not found, run: php artisan make:command ExpireBookings
```

### Problem: Migrations failed
**Fix**: Check database connection
```bash
php artisan migrate:status
# Should list all migrations
```

---

## 📱 Browser DevTools Verification

### Network Tab (For AJAX Review Submit)
1. Open F12 (DevTools)
2. Go to "Network" tab
3. Submit a review
4. ✅ Should see POST request to `/ulasan`
5. ✅ Response should be JSON: `{"success": true}`

### Console Tab
- ✅ No errors (red)
- ✅ No warnings unnecessary

### Elements/Inspector Tab
- ✅ Star rating HTML present: `<span class="star" data-rating="5">`
- ✅ Review form present: `<textarea name="komentar">`

---

## 🎬 Final Acceptance Test (2 minutes)

### Complete User Journey
1. **As Admin**:
   - ✅ Create accommodation booking
   - ✅ See it in reservasi list
   - ✅ Click "Proses Pembayaran"
   - ✅ Payment button visible

2. **As Customer**:
   - ✅ Login
   - ✅ Go to package detail
   - ✅ Submit 5-star review
   - ✅ See review appear immediately
   - ✅ Go to accommodation detail
   - ✅ Submit 4-star review
   - ✅ See average shows (4.5 stars if only these reviews)

3. **As Admin (Maintenance)**:
   - ✅ Run `php artisan bookings:expire`
   - ✅ See success message
   - ✅ Verify database updated

---

## ✨ All Green? You're Ready!

If ALL tests pass:
- ✅ Feature 1: Booking System → WORKING
- ✅ Feature 2: Review System → WORKING
- ✅ Feature 3: Expiration System → WORKING

**Next Steps**:
1. Read `DEPLOYMENT_CHECKLIST.md` for production deploy
2. Setup Midtrans production keys
3. Configure cron job for auto-expiration
4. Deploy to server

---

## 🆘 Getting Help

If any test FAILS:
1. Check error message carefully
2. Look at `IMPLEMENTATION_GUIDE.md` for setup instructions
3. Check `TROUBLESHOOTING.md` in project root
4. Review controller/model code for logic issues
5. Check browser console (F12) for JavaScript errors

---

**Test Duration**: ~10 minutes  
**Difficulty**: Easy (Just click & verify)  
**No Coding Required**: All features pre-built

Good luck! 🚀
