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
- **Monitoring**: Nagios

### Arsitektur Container

Sistem berjalan di atas **4 container** terpisah yang terhubung via virtual network `eduflip_net`:

1. **eduflip_web**: Web Server (Apache + PHP) - Port 80
2. **eduflip_db**: Database Server (MySQL) - Port 3306
3. **eduflip_dns**: DNS Server (BIND9) - Port 53
4. **eduflip_nagios**: Monitoring Server (Nagios) - Port 8080

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

Pastikan keempat container (web, db, dns, nagios) statusnya **Up**:

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

- 👉 **Web App**: [http://eduflip.local](http://eduflip.local)
- 👉 **Monitoring**: [http://localhost:8080](http://localhost:8080) (User: `nagiosadmin`)

## 🔑 Akun Default (Untuk Pengujian)

| Role          | Email                     | Password   |
| :------------ | :------------------------ | :--------- |
| **Admin**     | `admin@eduflip.local`     | `password` |
| **Dosen**     | `dosen@eduflip.local`     | `password` |
| **Mahasiswa** | `mahasiswa@eduflip.local` | `password` |

---
