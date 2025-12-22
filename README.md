# 🎓 EduFlip - Flipped Classroom LMS

[![Docker](https://img.shields.io/badge/Docker-Ready-blue?logo=docker)](https://www.docker.com/)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=white)](https://www.mysql.com/)
[![BIND9](https://img.shields.io/badge/DNS-BIND9-green)](https://www.isc.org/bind/)
[![Nagios](https://img.shields.io/badge/Monitoring-Nagios-orange)](https://www.nagios.org/)

> **Learning Management System** berbasis konsep _Flipped Classroom_ untuk mendukung pembelajaran interaktif antara dosen dan mahasiswa.

---

## ✨ Fitur Utama

| Fitur                   | Deskripsi                                                         |
| ----------------------- | ----------------------------------------------------------------- |
| 👥 **Multi-Role**       | Admin, Dosen, dan Mahasiswa dengan dashboard terpisah             |
| 📚 **Manajemen Kursus** | Dosen dapat membuat kursus dan mengunggah materi (PDF/HTML/Video) |
| 📝 **Kuis Online**      | Sistem penilaian otomatis dengan timer dan hasil langsung         |
| 💬 **Forum Diskusi**    | Ruang diskusi untuk setiap kursus                                 |
| � **Analytics**         | Dashboard statistik untuk dosen                                   |
| 🏆 **Sertifikat**       | Sertifikat digital untuk siswa yang lulus                         |

---

## 🏗️ Arsitektur Sistem

```
┌─────────────────────────────────────────────────────────┐
│                     Docker Engine                        │
│  ┌───────────┐ ┌───────────┐ ┌─────────┐ ┌───────────┐  │
│  │   WEB     │ │    DB     │ │   DNS   │ │  MONITOR  │  │
│  │  Apache   │ │  MySQL    │ │  BIND9  │ │  Nagios   │  │
│  │  PHP 8.2  │ │   8.0     │ │         │ │           │  │
│  │   :80     │ │  :3306    │ │   :53   │ │  :8080    │  │
│  └─────┬─────┘ └─────┬─────┘ └────┬────┘ └─────┬─────┘  │
│        └─────────────┴────────────┴────────────┘        │
│                    eduflip_net (Bridge)                  │
└─────────────────────────────────────────────────────────┘
```

### 📦 Container Services

| Container        | Image              | Port | Fungsi                     |
| ---------------- | ------------------ | ---- | -------------------------- |
| `eduflip_web`    | php:8.2-apache     | 80   | Web Application Server     |
| `eduflip_db`     | mysql:8.0          | 3306 | Database Server            |
| `eduflip_dns`    | ubuntu/bind9       | 53   | DNS Server (eduflip.local) |
| `eduflip_nagios` | jasonrivers/nagios | 8080 | Network Monitoring         |

---

## 🚀 Quick Start

### Prasyarat

- [Docker Desktop](https://www.docker.com/products/docker-desktop) terinstall dan running

### 1️⃣ Clone & Build

```bash
git clone https://github.com/mrijalulalbab/eduflip.git
cd eduflip
docker-compose up -d --build
```

### 2️⃣ Verifikasi Container

```bash
docker-compose ps
```

Pastikan **4 container** statusnya `Up`.

### 3️⃣ Konfigurasi DNS Lokal

Edit file `hosts` (Run as Administrator):

- **Windows**: `C:\Windows\System32\drivers\etc\hosts`
- **Linux/Mac**: `/etc/hosts`

Tambahkan baris berikut:

```
127.0.0.1    eduflip.local
127.0.0.1    www.eduflip.local
127.0.0.1    db.eduflip.local
```

### 4️⃣ Akses Aplikasi

| Service        | URL                   | Credentials               |
| -------------- | --------------------- | ------------------------- |
| 🌐 **Web App** | http://eduflip.local  | Lihat tabel akun di bawah |
| � **Nagios**   | http://localhost:8080 | `nagiosadmin` / `nagios`  |

---

## � Akun Default

| Role             | Email                     | Password   |
| ---------------- | ------------------------- | ---------- |
| 🔴 **Admin**     | `admin@eduflip.local`     | `password` |
| 🟡 **Dosen**     | `dosen@eduflip.local`     | `password` |
| 🟢 **Mahasiswa** | `mahasiswa@eduflip.local` | `password` |

---

## 📁 Struktur Proyek

```
eduflipp/
├── 📄 docker-compose.yml    # Container Orchestration
├── 📄 Dockerfile            # Web Image Build
├── 📂 database/
│   └── init.sql             # Schema + Seed Data
├── 📂 docker/
│   ├── dns/                 # BIND9 Configuration
│   └── nagios/              # Monitoring Configuration
└── 📂 web/
    ├── includes/            # PHP Libraries
    └── public/              # Application Code
        ├── admin/           # Admin Dashboard
        ├── dosen/           # Lecturer Dashboard
        ├── student/         # Student Dashboard
        └── assets/          # CSS, JS, Uploads
```

---

## 🛠️ Tech Stack

| Layer          | Technology              |
| -------------- | ----------------------- |
| **Backend**    | PHP 8.2 (Native)        |
| **Database**   | MySQL 8.0               |
| **Web Server** | Apache 2.4              |
| **Frontend**   | HTML5, CSS3, JavaScript |
| **Container**  | Docker & Docker Compose |
| **DNS**        | BIND9                   |
| **Monitoring** | Nagios Core             |

---

## 👨‍💻 Tim Pengembang

Proyek ini dikembangkan sebagai tugas **Sistem Jaringan Komputer (SJK/CSN)**.

| Nama   | Role         |
| ------ | ------------ |
| Albab  | Project Lead |
| Naufal | Developer    |
| Niko   | Developer    |
| Dipta  | Developer    |
| Nilam  | Developer    |

---

## 📄 Lisensi

Proyek ini dibuat untuk keperluan akademik.

---

<p align="center">
  <b>🎓 EduFlip</b> - Learn, Engage, Succeed
</p>
