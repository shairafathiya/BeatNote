🎵 Beat Notes – Laravel Web App for Music, Notes, and Events
Beat Notes adalah aplikasi web berbasis Laravel yang dirancang untuk musisi, pelajar, dan penggemar musik. Aplikasi ini menggabungkan tiga fitur utama: catatan pribadi, pemutar musik, dan manajemen event dalam satu platform terpadu.

📌 Fitur Utama
📝 Catatan Pribadi (Notes)

Tambah, edit, dan hapus catatan.

Pencarian catatan berdasarkan kata kunci.

🎶 Musik

Katalog musik berdasarkan genre (R&B, Reggae, Classical, dll).

Putar musik langsung dari browser.

📅 Event

Buat dan daftar ke event musik.

Lihat detail event dan daftar peserta.

🔐 Autentikasi

Sistem login dan register dengan proteksi halaman menggunakan middleware.

🧱 Teknologi yang Digunakan
Laravel 10+ (Backend framework)

Blade (Template engine Laravel)

Bootstrap & Custom CSS (UI Styling)

MySQL / MariaDB (Database)

PHP 8.x

Git & GitHub (Version control)

🛠️ Instalasi & Setup
1. Clone Repository
bash
Salin
Edit
git clone https://github.com/username/beat-notes.git
cd beat-notes
2. Install Dependency Laravel
bash
Salin
Edit
composer install
3. Copy .env dan Set Konfigurasi
bash
Salin
Edit
cp .env.example .env
Edit file .env:

env
Salin
Edit
DB_DATABASE=beat_notes
DB_USERNAME=root
DB_PASSWORD=your_mysql_password
4. Generate Key dan Migrate
bash
Salin
Edit
php artisan key:generate
php artisan migrate --seed
5. Jalankan Server Lokal
bash
Salin
Edit
php artisan serve
Akses di browser: http://127.0.0.1:8000

📸 Tampilan Aplikasi
Tambahkan screenshot di folder /public/img dan tampilkan seperti ini:


👥 Tim Pengembang
Nur Shadiqah – Fitur Notes, Event, UI Dashboard, Autentikasi

Tasya – Fitur Musik, Seeder Data, Validasi Input, Layout UI

🧪 Status Proyek
✅ Dalam tahap akhir pengembangan (v1.0)

📄 Lisensi
MIT License
