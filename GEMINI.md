Sebagai developer yang terbiasa dengan sistem yang terukur, berikut adalah **Technical Specification (TechSpec)** untuk sistem manajemen stok berbasis QR Code menggunakan CodeIgniter 4.

---

## 1. System Overview
Sistem ini dirancang untuk mengelola inventaris secara *real-time* dengan QR Code sebagai pengidentifikasi unik tiap item. Sistem harus dapat diakses melalui perangkat mobile (untuk scanning) dan desktop (untuk administrasi).

## 2. Technology Stack
* **Backend:** CodeIgniter 4.x (PHP 8.1+)
* **Database:** MySQL 8.0 (mendukung JSON data jika diperlukan)
* **Frontend:** Tailwind CSS.
* **Libraries:**
    * `endroid/qr-code`: QR Code Generation.
    * `html5-qrcode`: Browser-based QR Scanner.
    * `dompdf/dompdf`: Untuk cetak label/laporan ke PDF.

## 3. Database Schema
Kita akan menggunakan normalisasi standar untuk memastikan integritas data stok.

### Table: `items`
Menyimpan informasi master barang.
| Column | Type | Constraints |
| :--- | :--- | :--- |
| `id` | UUID / CHAR(36) | Primary Key |
| `sku` | VARCHAR(50) | Unique, Index |
| `name` | VARCHAR(255) | Not Null |
| `current_stock` | INT | Default 0 |
| `min_stock` | INT | Default 5 (untuk alert) |
| `qr_code_path` | VARCHAR(255) | Path file gambar QR |

### Table: `stock_transactions`
Mencatat setiap pergerakan barang.
| Column | Type | Constraints |
| :--- | :--- | :--- |
| `id` | INT | AI, Primary Key |
| `item_id` | CHAR(36) | Foreign Key -> items(id) |
| `type` | ENUM('IN', 'OUT') | Not Null |
| `quantity` | INT | Not Null |
| `remarks` | TEXT | Keterangan (misal: "Barang Rusak") |
| `created_at` | TIMESTAMP | Current Timestamp |

---

## 4. Key Modules & Logic

### 4.1. QR Generation Engine
Sistem akan menggenerate QR Code berdasarkan `id` atau `sku` barang.
* **Endpoint:** `GET /items/generate-qr/(:segment)`
* **Logic:**
    1.  Cek keberadaan ID barang di database.
    2.  Inisialisasi `endroid/qr-code`.
    3.  Output direktori: `public/uploads/qr/`.
    4.  Format penamaan: `{sku}.png`.

### 4.2. Scanning Interface (Mobile Optimized)
Halaman scanner harus ringan dan mendukung autofocus.
* **View:** `ScannerView` menggunakan library `html5-qrcode`.
* **Flow:**
    1.  User memilih mode (Masuk atau Keluar).
    2.  Kamera aktif -> Scan QR.
    3.  Setelah terdeteksi, library melakukan `POST` request ke `/api/stock/update`.
    4.  Frontend memberikan feedback (Vibrate/Sound) dan menampilkan info barang yang di-scan.

### 4.3. Stock Adjustment Logic (The "Core")
Proses ini harus menggunakan **Database Transactions** untuk mencegah *race condition*.

```php
// Pseudo-logic di Controller
public function updateStock() {
    $this->db->transStart();
    
    // 1. Ambil data dari POST (id_barang, qty, type)
    // 2. Update table 'items' (decrement/increment current_stock)
    // 3. Insert ke table 'stock_transactions'
    
    $this->db->transComplete();
    
    if ($this->db->transStatus() === false) {
        return $this->response->setJSON(['status' => 'error']);
    }
}
```

---

## 5. Security & Validation
* **Authentication:** Menggunakan Shield (CI4 official) atau session-based auth biasa.
* **Input Validation:** Validasi `quantity` tidak boleh negatif.
* **Stock Validation:** Untuk transaksi `OUT`, sistem harus mengecek apakah `current_stock >= quantity`. Jika tidak, return error 400.
* **API Security:** Gunakan CSRF protection untuk setiap request AJAX dari scanner.

---

## 6. Deployment Requirements
* **SSL/HTTPS:** Wajib aktif agar API `getUserMedia` (kamera) pada browser mobile bisa berjalan.
* **Server:** Shared Hosting/VPS dengan ekstensi PHP `gd`, `intl`, dan `mbstring` aktif.
* **Storage:** Permission `775` pada folder `writable/` dan `public/uploads/`.

---



Apakah struktur ini sudah cukup untuk kamu jadikan acuan *coding*, atau perlu detail tambahan untuk salah satu modulnya?