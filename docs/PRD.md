# Product Requirements Document (PRD)
**Nama Produk:** Sistem Penukaran Poin Limbah (EcoPoint Monolith)  
**Tech Stack:** Laravel 12, PHP 8.2+, MySQL (XAMPP), Blade, Tailwind CSS  

## 1. Tujuan Proyek
Membangun aplikasi web monolitik berbasis RBAC untuk memfasilitasi pencatatan penukaran limbah (khusus kategori **Botol** dan **Kaleng**) menjadi poin yang dapat dikonversi ke nilai tukar Rupiah.

## 2. Role & Hak Akses (RBAC)
1. **Admin**
   - Login/Logout manual menggunakan sesi Laravel.
   - Mengelola master data kategori sampah & parameter poin (CRUD).
   - Memverifikasi transaksi penukaran sampah dan menentukan grade kelayakan (A/B/C/D).
   - Mengelola saldo poin dan penukaran poin peserta ke Rupiah.
2. **Peserta**
   - Registrasi & Login/Logout manual.
   - Mengajukan form penukaran limbah (memilih jenis: Botol atau Kaleng, input jumlah/berat).
   - Melihat riwayat transaksi, total poin terkumpul, dan melakukan klaim penukaran poin ke Rupiah.

## 3. Aturan Bisnis & Formula Kalkulasi

### A. Poin Dasar (Base Point)
- **Botol:** 1 Botol = `1 Poin`
- **Kaleng:** 1 Kaleng dengan berat per 100 gram = `1 Poin` *(Formula: `(Berat Gram / 100) * 1 Poin`)*

### B. Poin Kondisi / Grade Kelayakan
Setiap item yang disetor dinilai kelayakannya dengan tambahan/pengali nilai kondisi:
- **Grade A (Sangat Bagus):** `4 Poin`
- **Grade B (Bagus):** `3 Poin`
- **Grade C (Cukup):** `2 Poin`
- **Grade D (Kurang Layak):** `1 Poin`

*(Total Poin Transaksi = Poin Dasar + Poin Grade Kelayakan)*

### C. Konversi Penukaran Poin ke Rupiah
- `20 Poin` = **Rp 1.000**
- `10 Poin` = **Rp 500**
- `1 Poin`  = **Rp 100**
*(Sistem menggunakan perhitungan pecahan greedy dari denominasi terbesar: 20 poin → 10 poin → 1 poin).*