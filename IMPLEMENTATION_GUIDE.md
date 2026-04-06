# Implementasi Fitur Wisata - Implementation Guide

## 📋 Daftar Fitur yang Telah Diimplementasikan

### 1. ✅ Sistem Booking Penginapan dengan Midtrans

#### Database Migrations:
- `2025_04_06_000001_add_booking_fields_to_penginapans_table.php` - Tambah field harga, kapasitas, lokasi, status
- `2025_04_06_000002_create_penginapan_reservasis_table.php` - Table untuk reservasi penginapan
- `2025_04_06_000003_update_ulasan_for_paket_wisata.php` - Tambah support ulasan untuk paket wisata

#### Models:
- `Penginapan.php` - Updated dengan relation booking
- `PenginapanReservasi.php` - Model baru untuk reservasi penginapan dengan Midtrans support
- `Ulasan.php` - Updated untuk support ulasan di paket dan penginapan

#### Controllers:
- `PenginapanReservasiController.php` - CRUD lengkap dengan payment handling
  - `index()` - Daftar reservasi
  - `create()` - Form pembuatan reservasi
  - `store()` - Save reservasi baru
  - `edit()` - Edit reservasi
  - `update()` - Update reservasi
  - `delete()` - Hapus reservasi
  - `payment()` - Halaman pembayaran
  - `processPayment()` - Generate Midtrans snap token
  - `callback()` - Handle Midtrans payment callback
- `PenginapanController.php` - Updated untuk support booking fields

#### Views (Backend):
- `resources/views/be/penginapan_reservasi/index.blade.php` - Daftar reservasi
- `resources/views/be/penginapan_reservasi/create.blade.php` - Form tambah dengan hitung otomatis
- `resources/views/be/penginapan_reservasi/edit.blade.php` - Form edit
- `resources/views/be/penginapan_reservasi/show.blade.php` - Detail reservasi
- `resources/views/be/penginapan_reservasi/payment.blade.php` - Payment gateway Midtrans

#### Features:
- ✅ Hitung otomatis harga berdasarkan tanggal check-in/out dan jumlah kamar
- ✅ Support diskon otomatis
- ✅ Integrasi Midtrans Snap untuk pembayaran
- ✅ Status tracking: menunggu konfirmasi → booking → selesai/batal
- ✅ Upload bukti transfer (optional)
- ✅ Validasi tanggal dan jumlah kamar

---

### 2. ✅ Fitur Ulasan (Review) untuk Paket Wisata & Penginapan

#### Controllers:
- `UlasanController.php` - Baru untuk mengelola ulasan
  - `store()` - Tambah ulasan baru
  - `update()` - Edit ulasan
  - `destroy()` - Hapus ulasan

#### Frontend Views Updated:
- `resources/views/fe/detail_paket/index.blade.php` - Tambah review form & list ulasan dinamis
- `resources/views/fe/penginapan/detail.blade.php` - Tambah review form & list ulasan dinamis

#### Features:
- ✅ Rating bintang 1-5 dengan visual feedback
- ✅ Form input komentar dengan AJAX submission
- ✅ Tampilkan rating rata-rata dan jumlah ulasan
- ✅ Hanya user yang sudah login bisa ulasan
- ✅ Hapus & edit ulasan (authorization check)
- ✅ Link ulasan ke reservasi paket dan penginapan
- ✅ Format waktu "time ago" (diffForHumans)

#### Database Relations:
```
Ulasan -> User (belongsTo)
Ulasan -> Penginapan (belongsTo)
Ulasan -> PaketWisata (belongsTo)
Ulasan -> Reservasi (belongsTo) [paket wisata]
Ulasan -> PenginapanReservasi (belongsTo) [penginapan]
```

---

### 3. ✅ Sistem Expiration untuk Booking

#### Console Command:
- `app/Console/Commands/ExpireBookings.php` - Command untuk auto-expire bookings
  ```bash
  php artisan bookings:expire              # Expire semua booking
  php artisan bookings:expire --type=reservasi    # Hanya paket wisata
  php artisan bookings:expire --type=penginapan   # Hanya penginapan
  ```

#### Model Methods:
- `Reservasi::isExpired()` - Check apakah booking sudah expired
- `Reservasi::getRemainingDays()` - Hitung hari sisa booking
- `PenginapanReservasi::isExpired()` - Check apakah penginapan booking sudah expired
- `PenginapanReservasi::getRemainingDays()` - Hitung hari sisa booking

#### Features:
- ✅ Auto-update status dari 'booking' → 'selesai' saat tanggal akhir terlampaui
- ✅ Hanya untuk booking dengan status 'booking' (paid)
- ✅ Paket wisata: berdasarkan `tgl_akhir`
- ✅ Penginapan: berdasarkan `tgl_check_out`
- ✅ Helper methods untuk cek status booking

#### Setup Scheduler (Task Scheduling):

Untuk production, setup cron job:
```bash
* * * * * cd /path/to/wisata-web && php artisan schedule:run >> /dev/null 2>&1
```

Atau manual jalankan command setiap hari dengan task scheduler sistem operasi.

---

## 🔧 Setup Instruksi

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Setup MidtransService
Pastikan config/midtrans.php sudah benar dengan server key & client key dari Midtrans.

### 3. Update PenginapanController Views
Backend form penginapan sudah di-update untuk include:
- Harga per malam
- Kapasitas
- Lokasi
- Status (tersedia/tidak tersedia)

### 4. Frontend Integration
User dapat:
- Lihat booking form di fe/penginapan/detail.blade.php
- Submit ulasan dengan rating
- Lihat ulasan dari user lain
- Proses pembayaran via Midtrans

---

## 📁 File Structure

### Backend Routes (dashboard):
```
GET  /dashboard/penginapan-reservasi
GET  /dashboard/penginapan-reservasi/create
POST /dashboard/penginapan-reservasi
GET  /dashboard/penginapan-reservasi/{id}/show
GET  /dashboard/penginapan-reservasi/{id}/edit
PUT  /dashboard/penginapan-reservasi/{id}
DELETE /dashboard/penginapan-reservasi/{id}
GET  /dashboard/penginapan-reservasi/{id}/payment
POST /dashboard/penginapan-reservasi/{id}/snap-token
POST /penginapan-reservation/callback
```

### Frontend Routes:
```
POST /ulasan (store review)
PUT  /ulasan/{id} (update review)
DELETE /ulasan/{id} (delete review)
```

---

## 🎯 Status Reservasi

### Paket Wisata (Reservasi):
- `menunggu konfirmasi` - Baru dibuat, menunggu admin konfirmasi
- `booking` - Sudah dibayar via Midtrans
- `ditolak` - Ditolak oleh admin
- `selesai` - Tanggal akhir sudah terlewat / selesai

### Penginapan (PenginapanReservasi):
- `menunggu konfirmasi` - Baru dibuat
- `booking` - Sudah dibayar
- `batal` - Dibatalkan
- `selesai` - Check-out date sudah terlewat

---

## 💳 Midtrans Integration

### Payment Flow:
1. User buat reservasi → save dengan status `menunggu konfirmasi`
2. User klik "Proses Pembayaran" → buka payment page
3. User submit payment → generate Snap Token
4. Midtrans modal muncul → user pilih metode pembayaran
5. Pembayaran selesai → callback update status ke `booking`

### Midtrans Callback Handler:
- POST `/penginapan-reservation/callback` 
- Auto-update status reservasi berdasarkan payment result
- Simpan transaction ID & payment details

---

## 🗄️ Database Schema

### penginapan_reservasis Table:
```
id
id_pelanggan (FK)
id_penginapan (FK)
tgl_reservasi
tgl_check_in
tgl_check_out
lama_malam
harga_per_malam
jumlah_kamar
diskon (%)
nilai_diskon
total_bayar
file_bukti_tf
status_reservasi
midtrans_order_id
midtrans_transaction_id
midtrans_status
midtrans_payment_type
timestamps
```

### ulasan Table (Updated):
```
id
penginapan_id (FK) - nullable
paket_wisata_id (FK) - NEW, nullable
user_id (FK)
reservasi_id (FK) - NEW, nullable
penginapan_reservasi_id (FK) - NEW, nullable
rating
komentar
timestamps
```

---

## 🔐 Authorization

### Reservasi Penginapan:
- `create`: Only auth users
- `edit/update/delete`: Owner or admin
- `payment`: Owner of reservation
- `show`: Owner or admin

### Ulasan:
- `store`: Auth users only
- `update`: Owner of review or admin
- `delete`: Owner of review or admin

---

## ⚠️ Important Notes

1. **Midtrans Configuration**: Pastikan server key dan client key sudah benar
2. **Email Configuration**: Untuk notifikasi pembayaran, setup email config
3. **Storage**: Pastikan storage/public bisa ditulis untuk upload bukti transfer
4. **Scheduler**: Untuk auto-expire bookings, setup cron job atau manual jalankan command
5. **Timezone**: Set timezone di config/app.php untuk timestamp accuracy

---

## 🚀 Testing

### Test Midtrans Payment:
1. Gunakan sandbox keys dari Midtrans
2. Test card numbers tersedia di Midtrans docs
3. Check payment status di Midtrans dashboard

### Test Expiration:
```bash
# Manually test expire command
php artisan bookings:expire --type=all

# Check database untuk verify status update
SELECT * FROM reservasis WHERE status_reservasi = 'selesai';
SELECT * FROM penginapan_reservasis WHERE status_reservasi = 'selesai';
```

### Test Review Feature:
1. Login sebagai user
2. Buat booking (tanggal akhir sudah terlewat untuk eligible)
3. Pergi ke detail page
4. Tambah review dengan rating
5. Verify review muncul di list

---

## 📞 Dukungan Fitur

Untuk integration lebih lanjut atau customization:
- Midtrans Docs: https://docs.midtrans.com
- Laravel Docs: https://laravel.com/docs
- Email: support jika diperlukan

---

**Last Updated**: April 6, 2026
**Status**: ✅ Complete & Ready for Testing
