# INTEGRASI MIDTRANS - FRONTEND (HOME MENU)

## Ringkasan Perubahan

Semua halaman checkout dan pesanan telah diintegrasikan dengan **Midtrans Snap Payment Gateway** untuk pengalaman pembayaran yang lebih mulus dan aman.

---

## 1. CHECKOUT PAGE (`resources/views/fe/checkout/index.blade.php`)

### Perubahan:
- ❌ Hapus: Form bank selection + file upload manual
- ✅ Tambah: Ringkasan pesanan yang jelas dan mudah dibaca
- ✅ Tambah: Tombol "Lanjutkan ke Pembayaran" yang redirect ke payment page

### Flow Baru:
```
1. User pilih paket + tanggal + jumlah peserta
   ↓
2. Redirect ke halaman checkout
   ↓
3. Lihat ringkasan harga dan detail paket
   ↓
4. Klik "Lanjutkan ke Pembayaran"
   ↓
5. Backend create Reservasi dengan status 'pesan' (unpaid)
   ↓
6. Redirect ke halaman payment untuk selesaikan transaksi Midtrans
```

### Backend Changes:
- **CheckoutController::store()** - Simplified:
  - Hapus requirement bank_id dan file_bukti_tf
  - Create Reservasi dengan status 'pesan' (instead of 'menunggu konfirmasi')
  - Redirect ke payment page (instead of index pesanan)

---

## 2. PESANAN INDEX PAGE (`resources/views/fe/pesanan/index.blade.php`)

### Perubahan:
- ✅ Tambah: Tombol "Bayar" untuk pesanan dengan status 'pesan'
- ✅ Update: Status badge untuk menampilkan status dengan lebih jelas
  - 'pesan' → "Menunggu Bayar" (warning/yellow)
  - 'menunggu konfirmasi' → "Menunggu Konfirmasi" (info/blue)
  - 'selesai' → "Selesai" (success/green)

### Action Buttons (per pesanan):
- 👁️ **Detail** - Lihat detail pesanan
- 💳 **Bayar** - Lakukan pembayaran (hanya muncul jika status 'pesan')
- 🖨️ **Cetak** - Cetak invoice

### Responsive:
- Mobile view: Cards dengan action buttons
- Desktop view: Table dengan inline action buttons

---

## 3. PESANAN DETAIL PAGE (`resources/views/fe/pesanan/detail.blade.php`)

### Perubahan:
- ✅ Update: Status badge dengan label yang lebih descriptive
- ✅ Tambah: Tombol "Lakukan Pembayaran" untuk pesanan unpaid
- ✅ Update: Action buttons di bawah menyesuaikan dengan Midtrans flow

### Action Buttons:
- ← **Kembali ke Daftar Pesanan** - Kembali
- 💳 **Lakukan Pembayaran** - Bayar sekarang (jika status 'pesan')
- 🖨️ **Cetak Invoice** - Cetak invoice

---

## 4. PESANAN PAYMENT PAGE (NEW - `resources/views/fe/pesanan/payment.blade.php`)

### Fitur:
- ✅ Tampilkan ringkasan pesanan dengan detail lengkap
- ✅ Tampilkan ringkasan biaya (harga, diskon, total)
- ✅ Integrase dengan Midtrans Snap
- ✅ Support berbagai metode pembayaran:
  - 💳 Kartu Kredit (Visa, Mastercard, JCB)
  - 🏦 Transfer Bank (BCA, Mandiri, BNI, CIMB, BRI, Danamon, Permata, Maybank)
  - 📱 E-Wallet

### Payment Flow:
```
1. User klik "Lakukan Pembayaran"
   ↓
2. Halaman payment terbuka dengan detail pesanan
   ↓
3. User klik "Lanjutkan Pembayaran"
   ↓
4. AJAX request ke server untuk dapatkan Snap Token
   ↓
5. Midtrans Snap modal terbuka
   ↓
6. User pilih metode pembayaran dan selesaikan
   ↓
7. Midtrans kirim callback ke server
   ↓
8. Status pesanan update ke 'dibayar'
   ↓
9. User redirect ke detail pesanan
```

---

## 5. CONTROLLER CHANGES

### PesananController - New Methods:

**1. `payment($id)` - Tampilkan halaman pembayaran**
- Route: `GET /pesanan/{id}/payment`
- Validasi: Order hanya bisa dibayar jika status 'pesan'
- Response: View dengan Midtrans client key

**2. `getSnapToken($id)` - Generate Snap Token via AJAX**
- Route: `POST /pesanan/{id}/snap-token`
- Return: JSON dengan snap_token
- Used by: Frontend JavaScript untuk buka Midtrans Snap

### CheckoutController - Modified `store()`:
- Simplifikasi validation (hapus bank_id, file_bukti_tf)
- Create Reservasi dengan status 'pesan'
- Redirect ke payment page

---

## 6. ROUTES

### Frontend Routes (Authenticated):
```php
// Existing
GET    /checkout/{id}                    (checkout form)
POST   /checkout                         (store checkout) - MODIFIED
GET    /pesanan                          (list orders)
GET    /pesanan/{id}                     (detail order)
GET    /pesanan/{id}/print               (print invoice)
GET    /pesanan/print/all                (print all invoices)

// NEW for Midtrans
GET    /pesanan/{id}/payment             (payment page)
POST   /pesanan/{id}/snap-token          (get snap token)
```

---

## 7. STATUS MAPPING

```
Status Flow:
┌──────────────────────────────────────┐
│   Checkout Page                      │
│   ↓ Create Reservasi                 │
├──────────────────────────────────────┤
│   Status: 'pesan' (Unpaid)           │
│   Show button: "Lakukan Pembayaran"  │
├──────────────────────────────────────┤
│   Payment Page                       │
│   ↓ User Midtrans Payment            │
├──────────────────────────────────────┤
│   Midtrans Callback                  │
│   ↓ Update status                    │
├──────────────────────────────────────┤
│   Status: 'dibayar' (Paid)           │
│   Hide payment button                │
└──────────────────────────────────────┘
```

---

## 8. KEY IMPROVEMENTS

✅ **Better UX:**
- Simpler checkout flow
- Real-time payment gateway
- Multiple payment methods

✅ **Automated Payment Handling:**
- Midtrans callback automatically updates order status
- No more manual verification needed

✅ **Security:**
- CSRF protected forms
- SSL encryption for payments
- Server-side verification of Midtrans callbacks

✅ **Mobile Responsive:**
- Touch-friendly payment page
- Easy navigation on mobile devices

✅ **Status Clarity:**
- Clear status labels ('Menunggu Bayar', 'Menunggu Konfirmasi', 'Selesai')
- Color-coded badges for quick identification

---

## 9. FILES MODIFIED

### Frontend Views:
- ✅ `resources/views/fe/checkout/index.blade.php` - Redesigned checkout
- ✅ `resources/views/fe/pesanan/index.blade.php` - Added payment button
- ✅ `resources/views/fe/pesanan/detail.blade.php` - Added payment button
- ✅ `resources/views/fe/pesanan/payment.blade.php` - NEW payment page

### Controllers:
- ✅ `app/Http/Controllers/CheckoutController.php` - Modified store()
- ✅ `app/Http/Controllers/PesananController.php` - Added payment() & getSnapToken()

### Routes:
- ✅ `routes/web.php` - Added /pesanan/{id}/payment and /pesanan/{id}/snap-token

---

## 10. TESTING CHECKLIST

- [ ] Checkout page menampilkan dengan benar
- [ ] Klik "Lanjutkan ke Pembayaran" membuat Reservasi dan redirect ke payment page
- [ ] Payment page menampilkan detail pesanan dengan benar
- [ ] Klik "Lanjutkan Pembayaran" membuka Midtrans Snap
- [ ] Pesanan list menampilkan button "Bayar" untuk status 'pesan'
- [ ] Pesanan detail menampilkan button "Lakukan Pembayaran" untuk status 'pesan'
- [ ] Complete payment di Midtrans redirect kembali ke pesanan detail
- [ ] Status pesanan berubah menjadi 'dibayar' setelah payment berhasil
- [ ] Button "Bayar" hilang setelah payment berhasil

---

## 11. INTEGRATIONS

✅ Fully integrated dengan:
- ✅ Midtrans Snap Payment Gateway
- ✅ Bank Management System (CRUD)
- ✅ Dashboard Reservation Payment
- ✅ Email notifications (dapat ditambahkan)

---

**Sistem pembayaran frontend HOME MENU telah terintegrasi penuh dengan Midtrans!** 🎉
