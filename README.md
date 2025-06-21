<p align="center">
    <a href="https://laravel.com" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
    </a>
    <h1 align="center" style="font-family: 'Poppins', sans-serif;">Paayu Attire</h1>
    <p align="center"><b>Modern Rental & Management System for Kebaya and Attire</b></p>
</p>

<p align="center">
    <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat&logo=laravel" alt="Laravel">
    <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php" alt="PHP">
    <img src="https://img.shields.io/badge/Bootstrap-5.x-7952B3?style=flat&logo=bootstrap" alt="Bootstrap">
    <img src="https://img.shields.io/badge/Filament-3.x-3B82F6?style=flat" alt="Filament">
    <img src="https://img.shields.io/badge/MySQL-8.x-4479A1?style=flat&logo=mysql" alt="MySQL">
</p>

---

## ✨ Fitur Utama
- 🎟️ **Sistem Sewa Modern**: Booking, checkout, denda otomatis, validasi, dan histori transaksi.
- 🔒 **Google Login & Unlink**: Integrasi login Google, unlink, dan pengelolaan password.
- 👤 **Manajemen Profil**: Edit profil & password langsung di satu halaman.
- 🛡️ **Admin Panel**: Filament-powered, status transaksi sinkron, denda bisa diatur admin.
- 📱 **Responsive UI**: Bootstrap 5, custom main.css, dan font Poppins.
- 🖼️ **Upload Bukti & Komentar**: Upload bukti pembayaran dan gambar di komentar.
- 📊 **Laporan**: Export Excel (opsional, jika diaktifkan).

## 🛠️ Tech Stack
| Komponen        | Teknologi                           |
|-----------------|-------------------------------------|
| **Framework**   | Laravel 12                          |
| **Frontend**    | Bootstrap 5, main.css, Poppins      |
| **Admin Panel** | Filament 3                          |
| **Auth**        | Google OAuth, Laravel Auth          |
| **Database**    | MySQL 8                             |

## 📞 Kontak
<div align="center">
    <a href="mailto:paayuattire@gmail.com">
        <img src="https://img.shields.io/badge/Email-paayuattire%40gmail.com-blue?style=flat&logo=gmail" alt="Email">
    </a>
    <a href="https://instagram.com/paayuattire">
        <img src="https://img.shields.io/badge/Instagram-%40paayuattire-E4405F?style=flat&logo=instagram" alt="Instagram">
    </a>
</div>

## 🖼️ Screenshot Aplikasi
<p align="center">
    <img src="public/assets/images/screenshots/katalog.png" alt="Katalog Screenshot" width="600"/>
</p>

## 🚀 Instalasi
```bash
# Clone repository
git clone https://github.com/yourusername/paayu-attire.git
cd paayu-attire

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Konfigurasi database di .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=paayu_attire
DB_USERNAME=root
DB_PASSWORD=

# Jalankan migrasi & seeder
php artisan migrate --seed

# Compile asset
npm run build

# Jalankan server
php artisan serve
```

## License
MIT
