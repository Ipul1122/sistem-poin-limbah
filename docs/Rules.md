## 1. Larangan Arsitektur & Package
- **DILARANG** menginstal `laravel/breeze`, `laravel/jetstream`, `laravel/ui`, atau package RBAC eksternal seperti `spatie/laravel-permission`.
- Seluruh proses login, register, logout, dan pengecekan role (`admin` vs `peserta`) **WAJIB** menggunakan `Illuminate\Support\Facades\Auth` dan Custom Middleware bawaan Laravel 12 (`bootstrap/app.php`).
- **DILARANG** membuat REST API terpisah (Sanctum/Passport) atau SPA (React/Vue). Aplikasi harus 100% **Laravel Blade Monolith**.

## 2. Batasan Domain Limbah (Strict Input Validation)
- Sistem **HANYA** menerima dua jenis limbah: **Botol** dan **Kaleng**.
- Dilarang menambahkan kategori limbah lain (seperti kertas, kardus, elektronik, atau limbah cair) di luar dua kategori yang ditetapkan.
- Input untuk kategori **Kaleng** wajib berupa satuan berat (`gram`), sedangkan **Botol** wajib berupa satuan jumlah (`pcs`).

## 3. Batasan Kalkulasi & Transaksi
- Perhitungan poin tidak boleh di-hardcode di dalam file Blade (`.blade.php`). Seluruh logika kalkulasi poin dan konversi Rupiah wajib berada di dalam **Service Class** (misal: `App\Services\PointCalculationService`).
- Saldo poin peserta (`users.total_points`) **TIDAK BOLEH** bertambah sebelum status transaksi diubah menjadi `approved` oleh Admin.
- Peserta tidak dapat menukarkan poin ke Rupiah melebihi `total_points` yang dimilikinya saat ini.

## 4. Batasan UI/UX
- Wajib mematuhi token warna dan tipografi pada `docs/Design.md`.
- Setiap halaman Admin dan Peserta wajib diproteksi oleh middleware role masing-masing sehingga Peserta tidak bisa mengakses URL `/admin/*` meskipun mengetik URL secara langsung.