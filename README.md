# EduFlip - Flipped Classroom Management System

EduFlip adalah aplikasi web berbasis **Flipped Classroom** yang dirancang untuk membantu dosen dan mahasiswa dalam kegiatan belajar mengajar yang lebih interaktif. Sistem ini mencakup manajemen kursus, materi, kuis, dan forum diskusi.

## 📋 Fitur Utama

- **Multi-role User**: Admin, Dosen, dan Mahasiswa.
- **Manajemen Kursus**: Dosen dapat membuat kursus dan mengunggah materi.
- **Kuis Online**: Mahasiswa dapat mengerjakan kuis dengan sistem penilaian otomatis.
- **Forum Diskusi**: Ruang diskusi untuk setiap kursus.

## 🛠️ Teknologi

- **Backend**: Native PHP
- **Database**: MySQL
- **Frontend**: HTML5, CSS3 (Vanilla + FontAwesome/RemixIcon)

## 🚀 Cara Instalasi

1.  **Persiapan Folder**

    - Pastikan folder `eduflipp` berada di dalam direktori server lokal Anda (contoh: `c:\xampp\htdocs\eduflipp`).

2.  **Setup Database**

    - Buka phpMyAdmin (biasanya di `http://localhost/phpmyadmin`).
    - Buat database baru jika diperlukan, atau biarkan skrip yang menanganinya.
    - Import file `database/init.sql`. Skrip ini akan otomatis membuat database `eduflip` dan tabel-tabel yang diperlukan beserta data dummy awal.

3.  **Konfigurasi (Opsional)**

    - Jika Anda menggunakan password resource database selain kosong (default XAMPP), buka file `web/includes/config.php` dan sesuaikan bagian `DB_PASS`.

4.  **Akses Aplikasi**
    - Buka browser dan kunjungi: `http://localhost/eduflipp/web/public/`

## 🔑 Akun Default (Untuk Pengujian)

Berikut adalah akun yang sudah disiapkan dalam database untuk pengujian:

| Role          | Email                 | Password   |
| :------------ | :-------------------- | :--------- |
| **Admin**     | `admin@eduflip.com`   | `password` |
| **Dosen**     | `dosen@eduflip.com`   | `password` |
| **Mahasiswa** | `student@eduflip.com` | `password` |

---

**Catatan untuk Penilai:**
Folder `web/` berisi seluruh _source code_ aplikasi. Folder `database/` berisi skrip SQL.
