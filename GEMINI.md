Ini adalah versi final **Technical Specification** yang telah diintegrasikan dengan sistem **RBAC (Role-Based Access Control)** dan **Audit Logging**. Spesifikasi ini dirancang agar kamu memiliki kontrol penuh sebagai Owner, sementara operasional di pasar tetap cepat dan akuntabel.

---

# Technical Specification: NexStock QR (Pasar Edition + RBAC)

## 1. System Overview
Sistem manajemen stok dan POS Hybrid berbasis **CodeIgniter 4** untuk UMKM sepatu. Mengintegrasikan **Sequential SKU Scanning** dengan **Negotiated Pricing** dan **Multi-user Accountability**. Sistem membedakan hak akses secara ketat antara Owner dan Staff untuk melindungi data margin keuntungan.

## 2. Technology Stack
* **Backend:** CodeIgniter 4.x (PHP 8.1+).
* **Database:** MySQL 8.0.
* **Auth:** CodeIgniter Shield (Recommended) atau Custom Session-based RBAC.
* **PWA:** Service Workers untuk reliabilitas di lokasi minim sinyal.
* **Frontend:** Tailwind CSS, Vanilla JS, Lucide Icons.

## 3. Database Schema (Final Optimized)

### Table: `users`
| Column | Type | Constraints |
| :--- | :--- | :--- |
| `id` | INT | Primary Key, AI |
| `username` | VARCHAR(50) | Unique |
| `password` | VARCHAR(255) | Hash |
| `role` | ENUM('OWNER', 'STAFF') | Default 'STAFF' |

### Table: `items`
| Column | Type | Constraints |
| :--- | :--- | :--- |
| `id` | CHAR(36) | Primary Key (UUID) |
| `sku` | VARCHAR(50) | Unique, Index |
| `name` | VARCHAR(255) | Not Null |
| `base_price` | DECIMAL(15,2) | Harga modal (Owner Only) |
| `mark_price` | DECIMAL(15,2) | Harga bandrol |
| `current_stock` | INT | Default 0 |

### Table: `sales_transactions`
| Column | Type | Constraints |
| :--- | :--- | :--- |
| `id` | INT | AI, Primary Key |
| `batch_id` | VARCHAR(50) | UUID Batch |
| `user_id` | INT | **FK -> users(id) (Aktor transaksi)** |
| `item_id` | CHAR(36) | FK -> items(id) |
| `qty` | INT | Jumlah item |
| `deal_price` | DECIMAL(15,2) | Harga kesepakatan |
| `total_profit` | DECIMAL(15,2) | `(deal_price - base_price) * qty` |
| `created_at` | TIMESTAMP | Default Current |

### Table: `audit_logs`
| Column | Type | Constraints |
| :--- | :--- | :--- |
| `id` | INT | AI, PK |
| `user_id` | INT | FK -> users(id) |
| `action` | VARCHAR(255) | Deskripsi (e.g., "Update SKU-01 Stock") |
| `payload` | JSON | Detail perubahan (Old vs New) |
| `created_at` | TIMESTAMP | Default Current |

---

## 4. Key Modules & RBAC Logic

### 4.1. Access Control Matrix
| Module | Staff | Owner |
| :--- | :---: | :---: |
| Scanning & Sales (Pasar) | ✅ | ✅ |
| History Transaksi (Global) | ❌ | ✅ |
| Management User & Audit Logs | ❌ | ✅ |
| View Base Price & Total Profit | ❌ | ✅ |
| Manual Stock Adjustment | ❌ | ✅ |

### 4.2. Authenticated Batch Processing (Backend)
Sistem mencatat siapa yang melakukan scan untuk setiap transaksi.

```php
public function processSale() {
    $data = $this->request->getJSON();
    $userId = session()->get('user_id'); // Capture current user session
    
    $this->db->transStart();
    foreach ($data->items as $row) {
        $item = $this->itemModel->where('sku', $row->sku)->first();
        $profit = ($row->deal_price - $item['base_price']) * $row->qty;

        // Update Stock
        $this->itemModel->update($item['id'], [
            'current_stock' => $item['current_stock'] - $row->qty
        ]);

        // Insert Transaction with User Accountability
        $this->salesModel->insert([
            'batch_id'   => $data->batch_id,
            'user_id'    => $userId, // Pencatatan user
            'item_id'    => $item['id'],
            'qty'        => $row->qty,
            'deal_price' => $row->deal_price,
            'total_profit' => $profit
        ]);
    }
    $this->db->transComplete();
}
```

---

## 5. UI/UX: Role-Based Interface
* **Staff Interface:** Menu minimalis. Fokus utama adalah tombol besar **"Start Scanning"**. List transaksi hanya menampilkan history hari ini tanpa nominal profit.
* **Owner Interface:** Dashboard analitik dengan grafik profit, filter performa per karyawan, dan log aktivitas sistem.
* **Security UI:** Field `base_price` dikosongkan/disensor secara otomatis oleh backend jika `session(role) !== 'OWNER'`.

## 6. Audit & Recovery
* **Transaction Trail:** Setiap perubahan stok manual di luar modul scan wajib mencatat alasan (misal: "Barang Rusak") ke tabel `audit_logs`.
* **Anti-Fraud Alert:** Notifikasi otomatis ke Owner jika ada transaksi yang dibatalkan setelah proses scan selesai (mencegah karyawan scan tapi uang tidak masuk laci).

---

**Summary Akhir:**
Dengan TechSpec ini, kamu sudah membangun sistem kelas profesional. Kamu punya **kecepatan scan**, **fleksibilitas nego harga**, dan **keamanan data (RBAC)**. 

Karena TechSpec sudah matang, apakah kita mau mulai eksekusi di **Database Migration** atau membuat **Base Controller** yang sudah include Auth Filter?