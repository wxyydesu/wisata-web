# 🔧 TECHNICAL REFERENCE - Developer Guide

**Purpose**: Deep-dive technical documentation for developers maintaining/extending these features

---

## 📚 Table of Contents
1. Architecture Overview
2. Database Schema Details
3. Code Structure & Patterns
4. API Reference
5. JavaScript Implementation
6. Extension Points

---

## 1. Architecture Overview

### System Design Principles

#### Separation of Concerns
```
Request → Route → Controller → Service → Model → Database
                                ↓
                            Response
```

- **Routes**: HTTP endpoint definitions
- **Controllers**: Business logic orchestration
- **Services**: Reusable payment/notification logic
- **Models**: Data representation & relationships
- **Migrations**: Schema version control

#### Design Patterns Used

**1. Service Locator (Midtrans)**
```php
// MidtransService handles all payment logic
$snap = new MidtransService();
$token = $snap->createToken($reservasi, $pelanggan, $banks, 'penginapan');
```

**2. Repository Pattern (Implicit)**
```php
// Models act as repositories
$reservasi = Reservasi::with('ulasan')->where('status', 'booking')->get();
```

**3. Observer Pattern (Laravel Events)**
```php
// Could extend with PaymentProcessed event
event(new PaymentProcessed($reservasi));
```

**4. Strategy Pattern (Payment Methods)**
```php
// Payment processing strategy determined by payment_type
// Credit card, bank transfer, e-wallet, etc handled by Midtrans
```

---

## 2. Database Schema Details

### penginapans Table (Extended)

**Original Columns** (pre-implementation):
```sql
id, nama_penginapan, deskripsi, fasilitas, 
foto1, foto2, foto3, foto4, foto5, created_at, updated_at
```

**New Columns Added**:
```sql
harga_per_malam DECIMAL(12,2) DEFAULT 0        -- Unit price per night
kapasitas INT DEFAULT 1                          -- Total capacity
lokasi VARCHAR(255)                              -- Location for map/GPS
status ENUM('tersedia', 'tidak tersedia')       -- Availability status
```

### penginapan_reservasis Table (New)

```sql
CREATE TABLE penginapan_reservasis (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    
    -- Foreign Keys
    id_pelanggan BIGINT UNSIGNED NOT NULL,
    id_penginapan BIGINT UNSIGNED NOT NULL,
    
    -- Booking Dates
    tgl_reservasi DATE,                          -- When reservation was made
    tgl_check_in DATE NOT NULL,                  -- Check-in date
    tgl_check_out DATE NOT NULL,                 -- Check-out date (exclusive)
    lama_malam INT NOT NULL,                     -- Calculated dias_check_out - tgl_check_in
    
    -- Pricing
    harga_per_malam DECIMAL(12,2) NOT NULL,     -- Snapshot of price at booking time
    jumlah_kamar INT NOT NULL DEFAULT 1,        -- Number of rooms
    diskon INT UNSIGNED DEFAULT 0,               -- Discount percentage (0-100)
    nilai_diskon DECIMAL(12,2) UNSIGNED DEFAULT 0,  -- Calculated discount amount
    total_bayar DECIMAL(12,2) NOT NULL,         -- Final amount (salary - discount)
    
    -- Payment Proof
    file_bukti_tf VARCHAR(255) NULLABLE,        -- Transfer proof image path
    
    -- Status Tracking
    status_reservasi ENUM(
        'menunggu konfirmasi',
        'booking',
        'batal',
        'selesai'
    ) DEFAULT 'menunggu konfirmasi',
    
    -- Midtrans Payment Fields
    midtrans_order_id VARCHAR(255) UNIQUE,      -- Unique order ID for Midtrans
    midtrans_transaction_id VARCHAR(255),        -- Transaction ID from Midtrans
    midtrans_status VARCHAR(50),                 -- settle, pending, cancel, etc
    midtrans_payment_type VARCHAR(50),           -- credit_card, bank_transfer, etc
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Constraints
    FOREIGN KEY (id_pelanggan) REFERENCES pelanggans(id) ON DELETE CASCADE,
    FOREIGN KEY (id_penginapan) REFERENCES penginapans(id) ON DELETE CASCADE,
    INDEX idx_pelanggan (id_pelanggan),
    INDEX idx_penginapan (id_penginapan),
    INDEX idx_status (status_reservasi),
    INDEX idx_checkout_date (tgl_check_out)
);
```

**Key Indexing Decision**: 
- `tgl_check_out` indexed untuk fast expiration queries
- `status_reservasi` indexed untuk quick filtering
- Foreign keys indexed untuk JOIN performance

### ulasan Table (Enhanced)

**Original Structure**:
```sql
id, penginapan_id FK, user_id FK, rating, komentar, timestamps
```

**New Columns** (Migration 3):
```sql
-- Makes table polymorphic: can link to multiple entities
paket_wisata_id BIGINT UNSIGNED NULLABLE        -- Link to packages
reservasi_id BIGINT UNSIGNED NULLABLE            -- Link to package bookings
penginapan_reservasi_id BIGINT UNSIGNED NULLABLE -- Link to accommodation bookings
```

**Result**: Single ulasan table supports reviews for:
- Penginapan (accommodation)
- Paket Wisata (tour packages)
- Both with optional booking reference

---

## 3. Code Structure & Patterns

### Model Relationships

```relationship flow
User (1) ──→ (many) Ulasan
         ──→ (many) Pelanggan (implied)

Pelanggan (1) ──→ (many) Reservasi (packages)
          ──→ (many) PenginapanReservasi (accommodations)
          ──→ (many) Ulasan

Penginapan (1) ──→ (many) PenginapanReservasi
          ──→ (many) Ulasan

PaketWisata (1) ──→ (many) Reservasi
           ──→ (many) Ulasan

Reservasi (1) ──→ (many) Ulasan
        ──→ (1) Pelanggan
        ──→ (1) PaketWisata

PenginapanReservasi (1) ──→ (many) Ulasan
                   ──→ (1) Pelanggan
                   ──→ (1) Penginapan
```

### Model Implementation Examples

#### PenginapanReservasi Model
```php
class PenginapanReservasi extends Model
{
    protected $table = 'penginapan_reservasis';
    
    protected $fillable = [
        'id_pelanggan', 'id_penginapan', 'tgl_reservasi',
        'tgl_check_in', 'tgl_check_out', 'lama_malam',
        'harga_per_malam', 'jumlah_kamar', 'diskon', 
        'nilai_diskon', 'total_bayar', 'file_bukti_tf',
        'status_reservasi', 'midtrans_order_id',
        'midtrans_transaction_id', 'midtrans_status',
        'midtrans_payment_type'
    ];
    
    protected $casts = [
        'tgl_check_in' => 'date',
        'tgl_check_out' => 'date',
        'harga_per_malam' => 'decimal:2',
        'total_bayar' => 'decimal:2'
    ];
    
    // Key Methods
    public function pelanggan() { return $this->belongsTo(Pelanggan::class, 'id_pelanggan'); }
    public function penginapan() { return $this->belongsTo(Penginapan::class, 'id_penginapan'); }
    public function ulasan() { return $this->hasMany(Ulasan::class, 'penginapan_reservasi_id'); }
    
    public function isExpired()
    {
        return $this->status_reservasi === 'booking'
            && Carbon::now()->toDateString() > $this->tgl_check_out->toDateString();
    }
    
    public function getRemainingDays()
    {
        return $this->tgl_check_out->diffInDays(Carbon::now());
    }
}
```

**Why These Casts?**
- Dates casted to Carbon instances for comparison operators
- Decimals casted for money operations precision
- Prevents type juggling errors

#### Ulasan Model
```php
class Ulasan extends Model
{
    protected $table = 'ulasan';
    
    protected $fillable = [
        'penginapan_id', 'paket_wisata_id', 'user_id',
        'reservasi_id', 'penginapan_reservasi_id',
        'rating', 'komentar'
    ];
    
    // All relationships are optional (nullable FKs)
    public function penginapan() { return $this->belongsTo(Penginapan::class); }
    public function paketWisata() { return $this->belongsTo(PaketWisata::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function reservasi() { return $this->belongsTo(Reservasi::class); }
    public function penginapanReservasi() { return $this->belongsTo(PenginapanReservasi::class); }
}
```

---

### Controller Flow Diagrams

#### PenginapanReservasiController::store()
```
Request (/penginapan-reservasi POST)
  ↓
Validate Input
  ├─ id_pelanggan exists
  ├─ id_penginapan exists
  ├─ date validation (checkout > checkin)
  └─ jumlah_kamar > 0
  ↓
Calculate Pricing
  ├─ lama_malam = checkout - checkin
  ├─ subtotal = harga × lama × jumlah_kamar
  ├─ nilai_diskon = subtotal × (diskon / 100)
  └─ total_bayar = subtotal - nilai_diskon
  ↓
Create Reservasi Record
  ├─ status = 'menunggu konfirmasi'
  ├─ Save all fields
  └─ If file: Store bukti_tf → storage/public/bukti_transfer/
  ↓
Return View (show.blade.php)
```

#### UlasanController::store()
```
Request (POST /ulasan)
  ↓
Check Auth
  └─ auth()->check() OR redirect login
  ↓
Validate Input
  ├─ Rating: 1-5 required
  ├─ Komentar: required, max 1000
  └─ Either penginapan_id OR paket_wisata_id
  ↓
Create Ulasan Record
  ├─ user_id = Auth::id()
  ├─ Save rating & komentar
  ├─ Link to entity (penginapan/paket/reservasi)
  └─ timestamps auto-set
  ↓
Return Response
  ├─ If AJAX: JSON {"success": true}
  └─ If Form: redirect with success toast
```

#### Midtrans Callback Handler
```
POST /penginapan-reservation/callback (from Midtrans server)
  ↓
Verify Signature (security check)
  └─ Hash matches Midtrans server key
  ↓
Extract Transaction Data
  ├─ order_id
  ├─ transaction_status
  ├─ payment_type
  ├─ transaction_id
  └─ gross_amount
  ↓
Update Reservasi Record
  ├─ if settlement: status = 'booking' ✓
  ├─ if pending: status = 'menunggu konfirmasi'
  ├─ if deny/cancel: status = 'batal'
  └─ Store midtrans_* fields
  ↓
Log Transaction
  └─ Update timestamps
  ↓
Send Email (if configured)
  └─ Payment confirmation to customer
  ↓
Return 200 OK (acknowledge receipt)
```

---

## 4. API Reference

### Routes Summary

#### Backend (Admin)
```
GET    /dashboard/penginapan-reservasi              index
GET    /dashboard/penginapan-reservasi/create       create
POST   /dashboard/penginapan-reservasi              store
GET    /dashboard/penginapan-reservasi/{id}/show    show
GET    /dashboard/penginapan-reservasi/{id}/edit    edit
PUT    /dashboard/penginapan-reservasi/{id}         update
DELETE /dashboard/penginapan-reservasi/{id}         destroy
GET    /dashboard/penginapan-reservasi/{id}/payment payment
POST   /dashboard/penginapan-reservasi/{id}/snap-token processPayment
```

#### Frontend (Public)
```
POST   /ulasan                    store review (auth required)
PUT    /ulasan/{id}              update review (owner|admin)
DELETE /ulasan/{id}              delete review (owner|admin)
```

#### Webhooks
```
POST   /penginapan-reservation/callback  Midtrans payment callback
```

### Request/Response Examples

#### Create Penginapan Reservasi
**Request**:
```http
POST /dashboard/penginapan-reservasi
Content-Type: application/x-www-form-urlencoded

id_pelanggan=3&id_penginapan=5
&tgl_check_in=2025-04-20
&tgl_check_out=2025-04-23
&jumlah_kamar=2
&diskon=10
&status_reservasi=menunggu konfirmasi
&file_bukti_tf=[FILE]
```

**Response** (Redirect):
```
302 Found
Location: /dashboard/penginapan-reservasi/42/show
Session: { success: 'Reservasi berhasil dibuat' }
```

#### Submit Review (AJAX)
**Request**:
```http
POST /ulasan
Content-Type: application/json
X-Requested-With: XMLHttpRequest

{
    "penginapan_id": 5,
    "rating": 5,
    "komentar": "Sangat puas dengan pelayanannya!"
}
```

**Response**:
```json
{
    "success": true,
    "message": "Ulasan berhasil ditambahkan",
    "review": {
        "id": 127,
        "rating": 5,
        "komentar": "Sangat puas dengan pelayanannya!",
        "user": { "name": "John Doe" },
        "created_at": "2025-04-06 14:30:00"
    }
}
```

#### Midtrans Callback
**Request** (from Midtrans):
```json
{
    "transaction_time": "2025-04-06 14:20:00",
    "transaction_status": "settlement",
    "transaction_id": "1234567890123456",
    "status_message": "Success",
    "status_code": "200",
    "signature_key": "abc123...",
    "order_id": "PEN-42-1712431000",
    "gross_amount": "1800000.00",
    "fraud_status": "accept",
    "payment_type": "credit_card"
}
```

**Response**:
```
200 OK
```

---

## 5. JavaScript Implementation

### Price Calculation Logic

**Location**: `resources/views/be/penginapan_reservasi/create.blade.php`

```javascript
function calculateLama() {
    const checkIn = new Date(document.querySelector('input[name="tgl_check_in"]').value);
    const checkOut = new Date(document.querySelector('input[name="tgl_check_out"]').value);
    
    if (checkIn && checkOut && checkOut > checkIn) {
        const diffTime = Math.abs(checkOut - checkIn);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        document.querySelector('input[name="lama_malam"]').value = diffDays;
        updatePrice();
    }
}

function updatePrice() {
    const hargaMalam = parseFloat(document.querySelector('select[name="id_penginapan"]').selectedOptions[0].dataset.harga) || 0;
    const lamaMalam = parseInt(document.querySelector('input[name="lama_malam"]').value) || 0;
    const jumlahKamar = parseInt(document.querySelector('input[name="jumlah_kamar"]').value) || 1;
    const diskon = parseFloat(document.querySelector('input[name="diskon"]').value) || 0;
    
    const subtotal = hargaMalam * lamaMalam * jumlahKamar;
    const nilaiDiskon = subtotal * (diskon / 100);
    const totalBayar = subtotal - nilaiDiskon;
    
    document.querySelector('#harga-display').textContent = 
        'Rp ' + hargaMalam.toLocaleString('id-ID');
    document.querySelector('#lama-display').textContent = lamaMalam + ' malam';
    document.querySelector('#subtotal-display').textContent = 
        'Rp ' + subtotal.toLocaleString('id-ID');
    document.querySelector('#total-display').textContent = 
        'Rp ' + totalBayar.toLocaleString('id-ID');
}
```

**Key Points**:
- Calculation on client side for instant feedback
- Uses `toLocaleString()` for Indonesian currency format
- Handles null/undefined values with `||` defaults

### Review Star Rating UI

**Location**: `resources/views/fe/*/detail.blade.php`

```javascript
document.querySelectorAll('.star').forEach(star => {
    star.addEventListener('click', function() {
        const rating = this.dataset.rating;
        document.querySelector('input[name="rating"]').value = rating;
        
        // Update visual feedback
        document.querySelectorAll('.star').forEach(s => {
            if (s.dataset.rating <= rating) {
                s.style.color = '#ffc107';  // Yellow
            } else {
                s.style.color = '#ddd';     // Gray
            }
        });
    });
    
    // Hover preview
    star.addEventListener('mouseenter', function() {
        let temp = this.dataset.rating;
        document.querySelectorAll('.star').forEach(s => {
            s.style.color = s.dataset.rating <= temp ? '#ffc107' : '#ddd';
        });
    });
});

// Reset on mouse leave
document.querySelector('.star-container').addEventListener('mouseleave', function() {
    const currentRating = document.querySelector('input[name="rating"]').value || 0;
    updateStarDisplay(currentRating);
});
```

### AJAX Review Submission

```javascript
document.querySelector('#review-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    
    try {
        const response = await fetch('/ulasan', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Ulasan berhasil dikirim!',
                timer: 1500
            }).then(() => {
                location.reload();  // Reload to show new review
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal mengirim ulasan',
                text: data.message
            });
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Terjadi kesalahan'
        });
    }
});
```

---

## 6. Extension Points

### How to Add New Features

#### Add Payment Method Notification
```php
// In PenginapanReservasiController callback()
if ($transaction_status == 'settlement') {
    // Send SMS notification
    Notification::send($pelanggan, new PaymentConfirmed($reservasi));
    // Or: Queue::push(new SendPaymentSMS($reservasi));
}
```

#### Add Review Auto-Posting to Social Media
```php
// In UlasanController store()
if ($request->share_to_facebook) {
    $facebook = new FacebookClient();
    $facebook->post('Check out this amazing accommodation!', [
        'link' => url("/penginapan/{$ulasan->penginapan_id}"),
        'picture' => $ulasan->penginapan->foto1
    ]);
}
```

#### Add Loyalty Points on Booking
```php
// In PenginapanReservasiController store()
$loyaltyPoints = $total_bayar / 1000;  // 1 point per Rp 1000
$pelanggan->addPoints($loyaltyPoints);
$pelanggan->logTransaction('booking_points', $loyaltyPoints);
```

#### Extend Expiration with Email Notification
```php
// In ExpireBookings command handle()
$expired = PenginapanReservasi::where('status', 'booking')
    ->where('tgl_check_out', '<', Carbon::today())
    ->update(['status_reservasi' => 'selesai']);

foreach ($expired as $reservation) {
    Mail::send(new BookingExpiredNotification($reservation));
}
```

#### Add Admin Review Moderation
```php
// In Ulasan model
public function scopePending($query) {
    return $query->where('status', 'pending');
}

public function approve() {
    $this->status = 'approved';
    $this->save();
}

// In AdminController
Route::post('/ulasan/{id}/approve', function(Ulasan $ulasan) {
    $ulasan->approve();
    return back();
});
```

---

## 📌 Performance Considerations

### Query Optimization

**Eager Load Reviews**:
```php
// ❌ Bad: N+1 query problem
$penginapans = Penginapan::all();
foreach ($penginapans as $p) {
    echo $p->ulasan()->count();  // Query per penginapan
}

// ✅ Good: Eager load
$penginapans = Penginapan::with('ulasan')->get();
foreach ($penginapans as $p) {
    echo $p->ulasan->count();  // No additional queries
}
```

**Index Strategy**:
```php
// In migration:
$table->index('status_reservasi');          // Fast status filtering
$table->index('tgl_check_out');             // Fast expiration queries
$table->index('id_pelanggan');              // Fast customer lookup
$table->index(['status_reservasi', 'tgl_check_out']);  // Composite index for expiration
```

### Caching Strategy

```php
// Cache average rating
$avg = Cache::remember(
    "penginapan_{$id}_avg_rating",
    now()->addHours(1),
    fn() => Ulasan::where('penginapan_id', $id)->avg('rating')
);
```

---

## 🧪 Testing Examples

### Unit Test for Expiration
```php
class ExpirationTest extends TestCase
{
    public function test_booking_expires_after_checkout_date()
    {
        $reservation = PenginapanReservasi::create([
            'tgl_check_out' => Carbon::yesterday(),
            'status_reservasi' => 'booking'
        ]);
        
        $this->assertTrue($reservation->isExpired());
    }
}
```

### Feature Test for Review
```php
class ReviewTest extends TestCase
{
    public function test_authenticated_user_can_submit_review()
    {
        $user = User::factory()->create();
        $penginapan = Penginapan::factory()->create();
        
        $response = $this->actingAs($user)->post('/ulasan', [
            'penginapan_id' => $penginapan->id,
            'rating' => 5,
            'komentar' => 'Great place!'
        ]);
        
        $this->assertDatabaseHas('ulasan', [
            'user_id' => $user->id,
            'penginapan_id' => $penginapan->id,
            'rating' => 5
        ]);
    }
}
```

---

**Document Version**: 1.0  
**Last Updated**: April 6, 2025  
**Target Audience**: Backend developers, DevOps engineers, QA testers

For questions contact: [Development Team]
