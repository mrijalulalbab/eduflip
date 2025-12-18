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

┌─────────────────────────────────────┐
│           Docker Engine             │
│  ┌─────────┐ ┌─────────┐ ┌───────┐  │
│  │   WEB   │ │   DB    │ │  DNS  │  │
│  │ Apache  │ │ MySQL   │ │ BIND9 │  │
│  │ PHP 8.2 │ │  8.0    │ │       │  │
│  │  :80    │ │  :3306  │ │  :53  │  │
│  └────┬────┘ └────┬────┘ └───┬───┘  │
│       └───────────┴──────────┘      │
│            eduflip_net              │
└─────────────────────────────────────┘
```

---

## SLIDE 6: STRUKTUR PROYEK

```
STRUKTUR PROYEK

eduflipp/
├── docker-compose.yml   ← Orchestration
├── Dockerfile           ← Web image
├── database/
│   └── init.sql         ← DB setup
├── docker/dns/
│   ├── named.conf       ← DNS config
│   └── zones/
└── web/
    └── public/          ← Aplikasi PHP
```

---

## SLIDE 7: DOCKER COMPOSE

```
DOCKER COMPOSE (docker-compose.yml)

services:
  web:   PHP 8.2 + Apache    → port 80
  db:    MySQL 8.0           → port 3306
  dns:   BIND9               → port 53

networks:
  eduflip_net (bridge)

Satu perintah: docker-compose up -d
```

---

## SLIDE 8: WEB SERVER

```
WEB SERVER

Dockerfile:
• Base image: php:8.2-apache
• Extension: PDO, MySQLi
• DocumentRoot: web/public

Koneksi Database:
• Host = "db" (nama container)
• Bukan "localhost"
```

---

## SLIDE 9: DATABASE SERVER

```
DATABASE SERVER

Image: mysql:8.0

Konfigurasi:
• MYSQL_DATABASE = eduflip
• Auto-run init.sql saat startup
• Volume db_data untuk persistence

✅ Data tidak hilang saat restart
```

---

## SLIDE 10: DNS SERVER

```
DNS SERVER (BIND9)

named.conf:
• Zone: eduflip.local
• Forwarders: 8.8.8.8

Zone File (db.eduflip.local):
• eduflip.local     → 127.0.0.1
• www.eduflip.local → 127.0.0.1
• db.eduflip.local  → 127.0.0.1
```

---

## SLIDE 11: CARA MENJALANKAN

```
CARA MENJALANKAN

1. cd eduflipp
2. docker-compose up -d --build
3. docker-compose ps  (cek status)
4. Browser → http://eduflip.local

✅ Semua otomatis jalan!
```

---

## SLIDE 12: HASIL PENGUJIAN

```
HASIL PENGUJIAN

✅ Web Container    - Running
✅ DB Container     - Running
✅ DNS Container    - Running
✅ Network          - Connected
✅ DNS Resolution   - eduflip.local ✓
✅ Web Access       - Accessible
✅ DB Connection    - Connected
```

---

## SLIDE 13: DEMO

```
DEMO LIVE

1. Terminal: docker-compose ps
   → Semua container "Up"

2. Terminal: nslookup eduflip.local 127.0.0.1
   → Resolve ke 127.0.0.1

3. Browser: http://eduflip.local
   → Halaman EduFlip muncul

4. Login & tunjukkan fitur
```

---

## SLIDE 14: KESIMPULAN

```
KESIMPULAN

✅ Web, DB, DNS berhasil di-containerize
✅ Terhubung via virtual network
✅ DNS resolve eduflip.local
✅ Aplikasi berjalan normal

Pengembangan:
• Tambah HTTPS (SSL)
• Tambah Monitoring (Nagios)
• CI/CD Integration
```

---

## SLIDE 15: PENUTUP

```
TERIMA KASIH

🙋 Sesi Tanya Jawab

Kelompok:
Albab | Naufal | Niko | Dipta | Nilam
```
