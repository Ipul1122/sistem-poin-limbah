# Design System & UI Guidelines

## 1. Tipografi (Fonts)
Menggunakan Google Fonts yang di-load pada layout utama `resources/views/layouts/app.blade.php`:
- **Heading / Judul:** `Montserrat` (Bold 700, SemiBold 600) — Memberikan kesan modern, tegas, dan terstruktur.
- **Body / Teks Utama:** `Poppins` (Regular 400, Medium 500) — Keterbacaan tinggi untuk tabel data dan form input.

## 2. Palet Warna (Color Palette)

### Background Colors
- **Primary Background:** `#FFF5F7` (Light Pastel Pink / Pink Muda — lembut, bersih, nyaman di mata, tidak menyilaukan)
- **Secondary Background:** `#FFFFFF` (Pure White — untuk *card*, modal, dan tabel kontainer dengan border `#FCE7F3`)

### Brand & Accent Colors
- **Primary Gradient / Accent:** Linear Gradient dari `#F472B6` (Pink 400) ke `#FB7185` (Rose 400) — digunakan untuk tombol aksi utama (CTA), *active state* sidebar, dan *badge* poin.
- **Secondary Accent:** `#1F2937` (Slate 800) — untuk teks utama dan header navigasi.
- **Text Highlight / Accent:** `#EC4899` (Pink 500) — untuk highlight teks, link interaktif, dan border aktif.

### Semantic Colors (Status)
- **Success (Grade A / Disetujui):** `#10B981` (Emerald 500)
- **Info (Grade B):** `#3B82F6` (Blue 500)
- **Warning (Grade C / Pending):** `#F59E0B` (Amber 500)
- **Danger (Grade D / Ditolak):** `#EF4444` (Red 500)
