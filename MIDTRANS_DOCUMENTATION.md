# Dokumentasi Integrasi Midtrans - Sistem Pembayaran

## Overview
Sistem pembayaran wisata telah diintegrasikan dengan **Midtrans** sebagai payment gateway utama. User dapat melakukan pembayaran melalui berbagai metode termasuk transfer bank, kartu kredit, dan e-wallet.

## Konfigurasi

### Credentials Midtrans
- **Merchant ID**: G676457763
- **Client Key**: SB-Mid-client-myxIlZhChDbz1rsU
- **Server Key**: SB-Mid-server-r4nEs4gtaLPMrp-JTE9iCeW5
- **Environment**: Sandbox (Development)

Credentials disimpan di file `.env`:
```env
MIDTRANS_MERCHANT_ID=G676457763
MIDTRANS_CLIENT_KEY=SB-Mid-client-myxIlZhChDbz1rsU
MIDTRANS_SERVER_KEY=SB-Mid-server-r4nEs4gtaLPMrp-JTE9iCeW5
MIDTRANS_PRODUCTION=false
```

## Arsitektur Sistem

### 1. Database Schema
#### Table: reservasis (New Columns)
- `midtrans_order_id` (string): Order ID untuk tracking Midtrans
- `midtrans_transaction_id` (string): Transaction ID dari Midtrans
- `midtrans_status` (string): Status dari Midtrans (settlement, capture, pending, deny, expire, cancel)
- `midtrans_payment_type` (string): Tipe pembayaran (bank_transfer, credit_card, e_wallet, dll)

#### Table: banks (Updated)
- `kode_bank` (string): Kode bank untuk Midtrans (bca, mandiri, bni, cimb, bri, dll)
- `aktif` (boolean): Status bank apakah aktif atau tidak untuk pembayaran

### 2. Service Layer

#### MidtransService (`app/Services/MidtransService.php`)
Service ini menangani semua interaksi dengan Midtrans API:

**Methods:**
- `createToken($reservasi, $pelanggan, $banks)`: Membuat Snap token untuk payment
- `getStatus($transactionId)`: Mendapatkan status transaksi
- `parseNotification($notificationBody)`: Parse notifikasi dari Midtrans
- `handleCallback($notification)`: Proses callback dari Midtrans
- `mapStatus($midtransStatus)`: Map status Midtrans ke status aplikasi

### 3. Controller

#### ReservasiController (`app/Http/Controllers/ReservasiController.php`)

**New Methods:**

1. **payment(Reservasi $reservasi)**
   - Route: `GET /dashboard/reservasi/{reservasi}/payment`
   - Menampilkan halaman pembayaran dengan detail reservasi
   - Hanya untuk reservasi yang belum dibayar
   - User hanya bisa akses pembayaran milik mereka

2. **getSnapToken(Request $request, Reservasi $reservasi)**
   - Route: `POST /dashboard/api/{reservasi}/snap-token`
   - Return Snap token via JSON untuk frontend
   - Used untuk membuka Midtrans Snap modal
   - Menyimpan order ID untuk tracking

3. **handleCallback(Request $request)**
   - Route: `POST /payment/callback` (public, tanpa auth)
   - Handle callback dari Midtrans setelah payment
   - Update status reservasi berdasarkan Midtrans status
   - Log semua transaksi payment

### 4. Frontend Flow

#### Payment Page (`resources/views/be/reservasi/payment.blade.php`)

Flow pembayaran:
1. User melihat detail reservasi dan total pembayaran
2. User memilih metode pembayaran
3. User klik tombol "Lakukan Pembayaran"
4. JavaScript mengambil Snap Token dari server
5. Midtrans Snap modal terbuka
6. User menyelesaikan pembayaran di Snap
7. Midtrans mengirim callback ke server
8. Server update status reservasi
9. User dialihkan ke halaman pesanan

### 5. Routes

**Authenticated Routes (dengan middleware auth):**
```php
GET    /dashboard/reservasi/{reservasi}/payment      -> ReservasiController@payment
POST   /dashboard/api/{reservasi}/snap-token          -> ReservasiController@getSnapToken
```

**Public Routes (tanpa auth, untuk Midtrans callback):**
```php
POST   /payment/callback                               -> ReservasiController@handleCallback
```

## Bank Management

### CRUD Bank

Sistem bank di dashboard memungkinkan admin/bendahara untuk:

1. **Menambah Bank Baru**
   - Route: `POST /dashboard/bank` (BankController@store)
   - Fields:
     - Nama Bank (required)
     - Kode Bank / Midtrans Code (required, unique)
     - No. Rekening (required)
     - Atas Nama (optional)
     - Status Aktif (checkbox)

2. **Edit Bank**
   - Route: `PUT /dashboard/bank/{id}` (BankController@update)

3. **Hapus Bank**
   - Route: `DELETE /dashboard/bank/{id}` (BankController@destroy)

4. **Daftar Bank**
   - Route: `GET /dashboard/bank` (BankController@index)
   - Menampilkan semua bank dengan status aktif/inactive

### Daftar Bank Code yang Didukung (Midtrans):
- `bca`: Bank Central Asia
- `mandiri`: Bank Mandiri
- `bni`: Bank BNI
- `cimb`: CIMB Niaga
- `bri`: Bank BRI
- `danamon`: Bank Danamon
- `permata`: Bank Permata
- `maybank`: Maybank

## Alur Pembayaran (Payment Flow)

### Scenario 1: Create Reservasi dan Lakukan Pembayaran

```
1. Admin/User membuat Reservasi
   ↓
2. Sistem membuat Reservasi dengan status 'pesan'
   ↓
3. User/Admin redirect ke halaman detail Reservasi
   ↓
4. Jika status masih 'pesan':
      - Tampilkan tombol "Lakukan Pembayaran"
   ↓
5. User klik tombol "Lakukan Pembayaran"
   ↓
6. Redirect ke halaman pembayaran (payment page)
   ↓
7. Di halaman pembayaran:
   - Tampilkan detail reservasi dan total pembayaran
   - User klik "Lanjutkan Pembayaran"
   ↓
8. Frontend kirim AJAX request ke /api/{resasiId}/snap-token
   ↓
9. Server (MidtransService) create Snap token
   ↓
10. Frontend terima Snap token
   ↓
11. Snap.pay({snap_token}) dipanggil
   ↓
12. Midtrans Snap modal terbuka
   ↓
13. User memilih metode pembayaran dan selesaikan transaksi
   ↓
14. Midtrans mengirim callback HTTP POST ke /payment/callback
   ↓
15. Server update reservasi dengan:
    - midtrans_transaction_id
    - midtrans_status
    - midtrans_payment_type
    - status_reservasi = 'dibayar' (jika payment success)
   ↓
16. Frontend menampilkan pesan sukses
   ↓
17. User dialihkan ke halaman pesanan
```

### Scenario 2: Payment Callback Flow

```
Midtrans → Server
  ↓
POST /payment/callback
  ↓
MidtransService::handleCallback()
  ↓
Parse order_id untuk mendapatkan reservasi_id
  ↓
Update Reservasi:
  - midtrans_transaction_id
  - midtrans_status
  - midtrans_payment_type
  - status_reservasi = mapped status
  ↓
Log transaksi
  ↓
Return success response ke Midtrans
```

## Status Mapping

### Midtrans Status → Application Status

| Midtrans Status | App Status | Keterangan |
|---|---|---|
| capture | dibayar | Pembayaran Berhasil (Credit Card) |
| settlement | dibayar | Pembayaran Berhasil (Transfer Bank) |
| pending | pesan | Pembayaran Pending/Menunggu |
| deny | ditolak | Pembayaran Ditolak |
| expire | ditolak | Pembayaran Kadaluarsa |
| cancel | ditolak | Pembayaran Dibatalkan |

## Implementasi Fitur

### 1. Installation & Setup
```bash
# 1. Install Midtrans SDK
composer require midtrans/midtrans-php

# 2. Add credentials to .env
MIDTRANS_MERCHANT_ID=G676457763
MIDTRANS_CLIENT_KEY=SB-Mid-client-myxIlZhChDbz1rsU
MIDTRANS_SERVER_KEY=SB-Mid-server-r4nEs4gtaLPMrp-JTE9iCeW5
MIDTRANS_PRODUCTION=false

# 3. Create config file
config/midtrans.php

# 4. Run migrations
php artisan migrate

# 5. Seed bank data
php artisan db:seed --class=BankSeeder
```

### 2. Files yang Ditambahkan/Diubah

**Files Baru:**
- `config/midtrans.php` - Konfigurasi Midtrans
- `app/Services/MidtransService.php` - Service untuk Midtrans API
- `resources/views/be/reservasi/payment.blade.php` - Payment page
- `database/seeders/BankSeeder.php` - Seeder untuk bank data
- `database/migrations/2025_04_05_000000_add_midtrans_to_reservasis_table.php`
- `database/migrations/2025_04_05_000001_update_banks_table_for_midtrans.php`

**Files yang Dimodifikasi:**
- `.env` - Added Midtrans credentials
- `app/Models/Reservasi.php` - Added Midtrans fillable fields
- `app/Models/Bank.php` - Added kode_bank dan aktif fields
- `app/Http/Controllers/ReservasiController.php` - Added payment methods
- `app/Http/Controllers/BankController.php` - Updated untuk handle new fields
- `routes/web.php` - Added payment routes
- `resources/views/be/reservasi/show.blade.php` - Added payment button
- `resources/views/be/bank/create.blade.php` - Added bank code field
- `resources/views/be/bank/edit.blade.php` - Added bank code field
- `resources/views/be/bank/index.blade.php` - Show bank code & status

## Security

### Security Measures:

1. **Authorization Check**: Setiap user hanya bisa akses/bayar reservasi milik mereka
2. **CSRF Protection**: Semua POST request dilindungi dengan CSRF token
3. **Signature Verification**: Midtrans callback di-verify dengan Server Key
4. **Logging**: Semua transaksi di-log untuk audit trail

## Error Handling

Jika terjadi error:
1. Try-catch block menangkap exception
2. Log error untuk debugging
3. Return error response ke client
4. User dapat mencoba ulang

## Testing

### Manual Testing Steps:

1. **Create Reservasi**
   - Login sebagai admin/bendahara
   - Create reservasi baru
   - Verify: Reservasi status harus 'pesan'

2. **Test Payment Page**
   - Click "Lakukan Pembayaran" di detail reservasi
   - Verify: Payment page terbuka dengan correct details

3. **Test Snap Payment**
   - Click "Lanjutkan Pembayaran"
   - Verify: Snap modal terbuka
   - Use Midtrans sandbox test credentials

4. **Test Callback**
   - Complete payment di Snap
   - Verify: Fungsi callback dipanggil
   - Check database: midtrans fields sudah ter-update

## Future Enhancements

1. **Email Notification**: Send payment confirmation email
2. **Payment History**: Track semua transaksi payment
3. **Refund Flow**: Implement refund functionality
4. **Multiple Currency**: Support multi-currency payment
5. **Subscription Payment**: Recurring payment untuk paket langganan

## References

- [Midtrans Documentation](https://docs.midtrans.com/)
- [Midtrans Snap Integration](https://docs.midtrans.com/en/snap/overview)
- [Midtrans API Reference](https://api-docs.midtrans.com/)
- [Midtrans SDK PHP](https://github.com/Midtrans/midtrans-php)

## Support

Untuk bantuan:
- Check logs di `storage/logs/laravel.log`
- Verify Midtrans credentials di `.env`
- Test dengan Midtrans sandbox credentials
- Hubungi Midtrans support team jika ada issue dengan API
