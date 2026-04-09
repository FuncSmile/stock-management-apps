#!/bin/bash

# Issue 1
gh issue create --title "[Database] Schema Expansion: Pricing, Sales, and Audit Logs" --body "
## Deskripsi (Untuk Developer/AI)
Kita perlu memperbarui skema database agar sesuai dengan spesifikasi baru (**Pasar Edition**). Perubahan yang perlu dilakukan adalah:

1. **Update Tabel \`items\`**: Tambahkan kolom \`base_price\` (DECIMAL(15,2), Harga Modal) dan \`mark_price\` (DECIMAL(15,2), Harga Jual/Bandrol).
2. **Buat Tabel Baru \`sales_transactions\`**: 
   - \`id\` (INT, AI, PK)
   - \`batch_id\` (VARCHAR(50))
   - \`user_id\` (INT, terhubung ke tabel users)
   - \`item_id\` (CHAR(36))
   - \`qty\` (INT)
   - \`deal_price\` (DECIMAL(15,2))
   - \`total_profit\` (DECIMAL(15,2))
   - \`created_at\` (TIMESTAMP)
3. **Buat Tabel Baru \`audit_logs\`**:
   - \`id\` (INT, AI, PK)
   - \`user_id\` (INT)
   - \`action\` (VARCHAR(255))
   - \`payload\` (JSON)
   - \`created_at\` (TIMESTAMP)

## Kriteria Penerimaan (Acceptance Criteria)
- [ ] File migrasi berhasil dibuat untuk tabel dan kolom baru.
- [ ] Proses \`php spark migrate\` berjalan tanpa error.
- [ ] Skema database sudah sesuai dan terpasang dengan benar di lingkungan lokal.
"

# Issue 2
gh issue create --title "[Backend] Authenticated Sales Processing Module" --body "
## Deskripsi (Untuk Developer/AI)
Kita membutuhkan logika pemrosesan penjualan (*Sales Processing*) di backend yang mampu menangani transaksi pemindaian QR dan menyimpan record di \`sales_transactions\`. 

**Alur kerja yang dibutuhkan:**
1. Menerima payload dari frontend (\`item_id\`, \`qty\`, \`deal_price\`).
2. Menangkap ID user yang sedang login dari session.
3. Melakukan **Database Transaction** (\`transStart\` & \`transComplete\`).
4. Mengkalkulasi \`total_profit\` menggunakan rumus: \`(deal_price - base_price) * qty\`.
5. Mengurangi \`current_stock\` di tabel \`items\`.
6. Memasukkan rekaman ke \`sales_transactions\`.

## Kriteria Penerimaan (Acceptance Criteria)
- [ ] Dibuat sebuah controller/method baru untuk menerima request penjualan via API.
- [ ] Transaksi database dijamin aman (Atomicity).
- [ ] Sistem menghitung \`total_profit\` dengan otomatis dan akurat.
- [ ] Mengaitkan setiap log transaksi penjualan dengan \`user_id\` karyawan atau owner yang sedang login.
"

# Issue 3
gh issue create --title "[Frontend] Role-Based UI/UX: Owner vs Staff Interface" --body "
## Deskripsi (Untuk Developer/AI)
Menerapkan pembatasan antarmuka Frontend berdasarkan aturan **Role-Based Access Control (RBAC)** (\`OWNER\` dan \`STAFF\`).

**Spesifikasi UI Staff:**
- Tampilan minimalis, fokus pada modul Scanner/Penjualan.
- Konfigurasi untuk fitur penjualan (\`deal_price\`).
- **TIDAK BOLEH** melihat nilai \`base_price\` (Harga Modal) dan nilai \`total_profit\`.

**Spesifikasi UI Owner:**
- Punya akses penuh ke menu Dashboard analitik dan Data Master (Items).
- Bisa melihat list harga modal (\`base_price\`), histori margin profit menyeluruh, dan log sistem.

## Kriteria Penerimaan (Acceptance Criteria)
- [ ] Middleware/Filter CI4 membatasi akses URL owner oleh role staff.
- [ ] Pengecekan *conditional rendering* di UI (contoh: \`<?php if(in_groups('owner')) { ... }\`) untuk menyembunyikan tabel/menu rahasia.
- [ ] Dashboard analitik hanya bisa diakses oleh grup Owner.
"

# Issue 4
gh issue create --title "[Feature] Audit Log & Manual Stock Adjustment Tracker" --body "
## Deskripsi (Untuk Developer/AI)
Untuk menjaga integritas data margin dan laporan stok, setiap perubahan stok manual di luar alur pemindaian (Scanner/Sales) wajib tercatat dengan alasan yang valid.

**Instruksi:**
1. Integrasikan aksi log (insert ke tabel \`audit_logs\`) pada modul pengelolaan daftar barang (Edit/Update Items).
2. Setiap kali ada penyesuaian (*adjustment*) stok barang, sistem menangkap *State* sebelum (Old) dan sesudah (New) stok, lalu simpan ke kolom \`payload\`.
3. Sediakan format *action* (contoh: 'Barang Rusak' atau 'Salah Input Awal').
4. Implementasikan Notifikasi / View halaman kecil bagi Owner untuk bisa me-review \`audit_logs\`.

## Kriteria Penerimaan (Acceptance Criteria)
- [ ] \`audit_logs\` selalu bertambah setiap kali ada \`update\` manual stok barang berstatus sukses.
- [ ] \`payload\` terekam dalam format JSON string yang memuat array data yang diubah.
- [ ] Disediakan Controller/View bagi user tipe Owner untuk melihat log mutasi manual ini.
"
