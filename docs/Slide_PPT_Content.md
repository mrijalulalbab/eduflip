# 📊 KONTEN SLIDE PPT (15 SLIDE - ~15 MENIT)

## Proyek SJK/CSN: EduFlip Docker

> **Cara Pakai:** Copy-paste setiap kotak ke slide PPT Anda

---

## SLIDE 1: COVER

```
IMPLEMENTASI WEB SERVER BERBASIS DOCKER CONTAINER
Dengan Integrasi Database dan DNS Server

📚 EduFlip - Learning Management System
📖 Mata Kuliah: Sistem Jaringan Komputer (SJK/CSN)

Kelompok:
• Albab    • Naufal    • Niko
• Dipta    • Nilam
```

---

## SLIDE 2: DAFTAR ISI

```
DAFTAR ISI

1. Pendahuluan
2. Arsitektur Sistem
3. Implementasi
4. Pengujian & Demo
5. Kesimpulan
```

---

## SLIDE 3: LATAR BELAKANG & TUJUAN

```
PENDAHULUAN

Latar Belakang:
• Docker = Container untuk isolasi aplikasi
• Lebih ringan dari Virtual Machine
• Mudah deploy & portabel

Tujuan:
✅ Web Server + Database + DNS dalam Container
✅ Terhubung via Virtual Network
✅ Akses dari Browser (eduflip.local)
```

---

## SLIDE 4: TEKNOLOGI

```
TEKNOLOGI YANG DIGUNAKAN

┌─────────────────┬─────────────────┐
│ Komponen        │ Teknologi       │
├─────────────────┼─────────────────┤
│ Container       │ Docker          │
│ Orchestration   │ Docker Compose  │
│ Web Server      │ Apache + PHP 8.2│
│ Database        │ MySQL 8.0       │
│ DNS Server      │ BIND9           │
│ Aplikasi        │ EduFlip (PHP)   │
└─────────────────┴─────────────────┘
```

---

## SLIDE 5: ARSITEKTUR SISTEM

```
ARSITEKTUR SISTEM

┌───────────────────────────────────────────────────┐
│                   Docker Engine                   │
│  ┌─────────┐ ┌─────────┐ ┌───────┐ ┌───────────┐  │
│  │   WEB   │ │   DB    │ │  DNS  │ │  MONITOR  │  │
│  │ Apache  │ │ MySQL   │ │ BIND9 │ │  Nagios   │  │
│  │ PHP 8.2 │ │  8.0    │ │       │ │           │  │
│  │  :80    │ │  :3306  │ │  :53  │ │  :8080    │  │
│  └────┬────┘ └────┬────┘ └───┬───┘ └─────┬─────┘  │
│       └───────────┴──────────┴───────────┘        │
│                  eduflip_net (Bridge)             │
└───────────────────────────────────────────────────┘
```

---

## SLIDE 6: STRUKTUR PROYEK

```
STRUKTUR PROYEK

eduflipp/
├── docker-compose.yml   ← Orchestration
├── Dockerfile           ← Web image
├── database/
│   └── init.sql         ← DB setup & UII Content
├── docker/
│   ├── dns/             ← BIND9 Config & Zones
│   └── nagios/          ← Monitoring Config
└── web/
    └── public/          ← Aplikasi PHP
```

---

## SLIDE 7: DOCKER COMPOSE

```
DOCKER COMPOSE (docker-compose.yml)

services:
  web:    PHP 8.2 + Apache    → port 80
  db:     MySQL 8.0           → port 3306
  dns:    BIND9               → port 53/udp
  nagios: Network Monitor     → port 8080

networks:
  eduflip_net (bridge)

Satu perintah: docker-compose up -d --build
```

---

## SLIDE 8: WEB SERVER

```
WEB SERVER

Dockerfile:
• Base image: php:8.2-apache
• Extension: PDO, MySQLi
• DocumentRoot: web/public

Features:
• Plug-n-Play Content (UII Materials)
• Auto Database Initialization
• Git-tracked uploads
```

---

## SLIDE 9: DATABASE SERVER

```
DATABASE SERVER

Image: mysql:8.0

Konfigurasi:
• MYSQL_DATABASE = eduflip
• Init Script: init.sql
  - Auto-create 13 Tables
  - Auto-seed User & Courses
  - Plug-n-Play UII Data

✅ Data Persistence via 'db_data' volume
```

---

## SLIDE 10: DNS & MONITORING

```
DNS SERVER (BIND9)
• Zone: eduflip.local
• Records:
  - web.eduflip.local  → 127.0.0.1
  - db.eduflip.local   → 127.0.0.1 (Vital for Nagios)

MONITORING (NAGIOS)
• Memantau status server (UP/DOWN)
• Cek HTTP, Ping, dan SSH
• Dashboard: http://localhost:8080
```

---

## SLIDE 11: CARA MENJALANKAN

```
CARA MENJALANKAN

1. cd eduflipp
2. docker-compose up -d --build
3. docker-compose ps  (cek status)
4. Akses Aplikasi:
   → Web: http://eduflip.local
   → Monitor: http://localhost:8080

✅ 100% Plug-n-Play (Database & File otomatis)
```

---

## SLIDE 12: HASIL PENGUJIAN

```
HASIL PENGUJIAN

✅ Web Container    - Running
✅ DB Container     - Running
✅ DNS Container    - Running (Port 53)
✅ Nagios Monitor   - Running (Port 8080)
✅ Network          - Connected (eduflip_net)
✅ DNS Resolution   - db.eduflip.local OK
✅ UII Content      - Auto-Loaded
```

---

## SLIDE 13: DEMO

```
DEMO LIVE

1. Terminal: docker-compose ps
   → Tunjukkan 4 service aktif

2. Terminal: nslookup db.eduflip.local 127.0.0.1
   → Buktikan DNS internal jalan

3. Browser: http://eduflip.local
   → Login & Buka materi UII (Plug-n-Play)

4. Browser: http://localhost:8080
   → Tunjukkan Dashboard Nagios hijau (OK)
```

---

## SLIDE 14: KESIMPULAN

```
KESIMPULAN

✅ Web, DB, DNS, Nagios berhasil di-containerize
✅ Custom DNS (BIND9) untuk resolusi internal
✅ Monitoring Server aktif dengan Nagios
✅ Sistem Plug-n-Play (Code + DB + Content)

Pengembangan Selanjutnya:
• HTTPS (SSL/TLS)
• CI/CD Integration
• Scaling (Docker Swarm)
```

---

## SLIDE 15: PENUTUP

```
TERIMA KASIH

🙋 Sesi Tanya Jawab

Kelompok:
Albab | Naufal | Niko | Dipta | Nilam
```
