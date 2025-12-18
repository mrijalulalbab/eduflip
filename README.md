# EduFlip - Flipped Classroom Management System

EduFlip adalah aplikasi web berbasis **Flipped Classroom** yang dirancang untuk membantu dosen dan mahasiswa dalam kegiatan belajar mengajar yang lebih interaktif. Sistem ini mencakup manajemen kursus, materi, kuis, dan forum diskusi.

## 📋 Fitur Utama

- **Multi-role User**: Admin, Dosen, dan Mahasiswa.
- **Manajemen Kursus**: Dosen dapat membuat kursus dan mengunggah materi.
- **Kuis Online**: Mahasiswa dapat mengerjakan kuis dengan sistem penilaian otomatis.
- **Forum Diskusi**: Ruang diskusi untuk setiap kursus.

## 🛠️ Teknologi & Arsitektur

### Tech Stack

- **Backend**: Native PHP 8.2
- **Database**: MySQL 8.0
- **Frontend**: HTML5, CSS3
- **Infrastructure**: Docker & Docker Compose
- **DNS Server**: BIND9

### Arsitektur Container

Sistem berjalan di atas 3 container terpisah yang terhubung via virtual network `eduflip_net`:

1. **eduflip_web**: Web Server (Apache + PHP)
2. **eduflip_db**: Database Server (MySQL)
3. **eduflip_dns**: DNS Server (BIND9)

## 🚀 Cara Instalasi & Menjalankan (Docker)

**Prasyarat:**

- Docker Desktop sudah terinstall dan running.

### 1. Jalankan Container

Buka terminal di folder proyek dan jalankan perintah:

```bash
docker-compose up -d --build
```

Tunggu hingga proses build selesai.

### 2. Cek Status Container

Pastikan ketiga container (web, db, dns) statusnya **Up**:

```bash
docker-compose ps
```

### 3. Konfigurasi DNS (PENTING!)

Agar bisa mengakses via domain `eduflip.local`, arahkan DNS komputer Anda ke `127.0.0.1`.

**Cara Cepat (Windows):**
Edit file `C:\Windows\System32\drivers\etc\hosts` (Run as Administrator), tambahkan:

```
127.0.0.1    eduflip.local
127.0.0.1    www.eduflip.local
127.0.0.1    db.eduflip.local
```

### 4. Akses Aplikasi

Buka browser dan kunjungi:
👉 **[http://eduflip.local](http://eduflip.local)**

## 🔑 Akun Default (Untuk Pengujian)

| Role          | Email                 | Password   |
| :------------ | :-------------------- | :--------- |
| **Admin**     | `admin@eduflip.com`   | `password` |
| **Dosen**     | `dosen@eduflip.com`   | `password` |
| **Mahasiswa** | `student@eduflip.com` | `password` |

---

## 📂 Dokumentasi Proyek (Tugas CSN)

Dokumentasi lengkap untuk tugas mata kuliah SJK/CSN dapat ditemukan di folder `docs/`:

- **[Laporan Proyek](docs/Draft_Laporan_Proyek_CSN.md)**: Detail implementasi teknis.
- **[Slide Presentasi](docs/Slide_PPT_Content.md)**: Konten slide PPT.
- **[Panduan Belajar](docs/Panduan_Belajar.md)**: Q&A dan pemahaman konsep Docker.
