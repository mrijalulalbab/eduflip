# LAPORAN PROYEK SISTEM JARINGAN KOMPUTER (SJK/CSN)

## IMPLEMENTASI WEB SERVER BERBASIS DOCKER CONTAINER DENGAN INTEGRASI DATABASE DAN DNS SERVER

---

**Nama Aplikasi:** EduFlip - Learning Management System

**Mata Kuliah:** Sistem Jaringan Komputer / Computer Systems and Networks (SJK/CSN)

---

## DAFTAR ISI

1. [BAB I - Pendahuluan](#bab-i---pendahuluan)
2. [BAB II - Landasan Teori](#bab-ii---landasan-teori)
3. [BAB III - Perancangan Sistem](#bab-iii---perancangan-sistem)
4. [BAB IV - Implementasi](#bab-iv---implementasi)
5. [BAB V - Pengujian](#bab-v---pengujian)
6. [BAB VI - Kesimpulan dan Saran](#bab-vi---kesimpulan-dan-saran)
7. [Daftar Pustaka](#daftar-pustaka)
8. [Lampiran](#lampiran)

---

## BAB I - Pendahuluan

### 1.1 Latar Belakang

Perkembangan teknologi informasi yang pesat menuntut pengelolaan infrastruktur server yang efisien, scalable, dan mudah di-deploy. Teknologi containerization dengan Docker menjadi solusi modern yang memungkinkan isolasi aplikasi dalam lingkungan yang ringan dan portabel.

Proyek ini mengimplementasikan sebuah sistem web server lengkap menggunakan Docker Container untuk menjalankan aplikasi **EduFlip**, sebuah Learning Management System (LMS) berbasis web yang dikembangkan pada mata kuliah Pengembangan Aplikasi Berbasis Web (PABW).

### 1.2 Rumusan Masalah

1. Bagaimana mengimplementasikan web server dalam lingkungan container Docker?
2. Bagaimana memisahkan web server dan database server ke dalam container yang berbeda?
3. Bagaimana menghubungkan kedua container melalui virtual network?
4. Bagaimana mengimplementasikan DNS server berbasis BIND untuk resolusi nama domain lokal?

### 1.3 Tujuan Proyek

1. Mengimplementasikan web server Apache dengan PHP dalam Docker Container
2. Mengimplementasikan database server MySQL dalam Docker Container terpisah
3. Menghubungkan kedua container melalui Docker virtual network
4. Mengimplementasikan DNS server berbasis BIND9 untuk domain lokal `eduflip.local`
5. Memastikan aplikasi web dapat diakses dari browser pada host OS

### 1.4 Manfaat Proyek

1. Memahami konsep containerization dan orchestration dengan Docker
2. Memahami konfigurasi web server dan database server
3. Memahami konsep DNS dan implementasinya dengan BIND9
4. Menerapkan best practice dalam deployment aplikasi modern

---

## BAB II - Landasan Teori

### 2.1 Docker Container

Docker adalah platform open-source yang memungkinkan developer untuk mengemas, mendistribusikan, dan menjalankan aplikasi dalam container. Container adalah unit standar software yang mengemas kode dan semua dependensinya sehingga aplikasi dapat berjalan konsisten di berbagai environment.

**Keunggulan Docker:**

- **Isolasi:** Setiap container berjalan terpisah
- **Portabilitas:** Container dapat berjalan di mana saja Docker tersedia
- **Efisiensi:** Lebih ringan dibanding Virtual Machine
- **Konsistensi:** Environment yang sama di development dan production

### 2.2 Docker Compose

Docker Compose adalah tool untuk mendefinisikan dan menjalankan aplikasi Docker multi-container. Dengan file `docker-compose.yml`, kita dapat mengkonfigurasi layanan aplikasi dan menjalankan semuanya dengan satu perintah.

### 2.3 Web Server Apache

Apache HTTP Server adalah web server open-source yang paling banyak digunakan di dunia. Apache mendukung berbagai fitur seperti virtual hosting, URL rewriting, dan integrasi dengan berbagai bahasa pemrograman termasuk PHP.

### 2.4 Database MySQL

MySQL adalah sistem manajemen database relasional (RDBMS) yang populer. MySQL menggunakan Structured Query Language (SQL) untuk mengelola data dan mendukung fitur-fitur enterprise seperti replikasi dan clustering.

### 2.5 DNS Server BIND

BIND (Berkeley Internet Name Domain) adalah implementasi DNS server yang paling banyak digunakan di internet. BIND menyediakan layanan resolusi nama domain, mengubah nama domain menjadi alamat IP dan sebaliknya.

### 2.6 Docker Networking

Docker menyediakan networking driver untuk menghubungkan container:

- **bridge:** Network default, isolasi container dalam satu host
- **host:** Container menggunakan network host langsung
- **overlay:** Menghubungkan container antar host
- **none:** Container tanpa networking

---

## BAB III - Perancangan Sistem

### 3.1 Arsitektur Sistem

Sistem terdiri dari tiga container utama yang terhubung melalui virtual network:

```
┌────────────────────────────────────────────────────────────────────────────────┐
│                        HOST OS (Windows/Linux)                                 │
│                                                                                │
│  ┌──────────────────────────────────────────────────────────────────────────┐  │
│  │                    Docker Virtual Network (eduflip_net)                  │  │
│  │                                                                          │  │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  │  │
│  │  │  Web Server  │  │   Database   │  │  DNS Server  │  │  Monitoring  │  │  │
│  │  │   (Apache)   │  │    (MySQL)   │  │    (BIND9)   │  │   (Nagios)   │  │  │
│  │  │              │  │              │  │              │  │              │  │  │
│  │  │   Port: 80   │  │  Port: 3306  │  │   Port: 53   │  │  Port: 8080  │  │  │
│  │  │              │  │              │  │              │  │              │  │  │
│  │  │ eduflip_web  │  │  eduflip_db  │  │ eduflip_dns  │  │eduflip_nagios│  │  │
│  │  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘  │  │
│  │         │                 │                 │                 │          │  │
│  │         └─────────────────┼─────────────────┼─────────────────┘          │  │
│  │                           │                                              │  │
│  └───────────────────────────┼──────────────────────────────────────────────┘  │
│                              │                                                 │
│  ┌───────────────────────────┼──────────────────────────────────────────────┐  │
│  │               Browser (Client: http://eduflip.local)                     │  │
│  └──────────────────────────────────────────────────────────────────────────┘  │
└────────────────────────────────────────────────────────────────────────────────┘
```

### 3.2 Spesifikasi Container

| Container      | Image                   | Port           | Fungsi                   |
| -------------- | ----------------------- | -------------- | ------------------------ |
| eduflip_web    | php:8.2-apache (custom) | 80             | Web Server + PHP Runtime |
| eduflip_db     | mysql:8.0               | 3306           | Database Server          |
| eduflip_dns    | ubuntu/bind9            | 53/tcp, 53/udp | DNS Server               |
| eduflip_nagios | jasonrivers/nagios      | 8080           | Monitoring Server        |

### 3.3 Struktur Direktori Proyek

```
eduflipp/
├── docker-compose.yml      # Orchestration configuration
├── Dockerfile              # Web server image definition
├── database/
│   └── init.sql            # Database initialization script
├── docker/
│   └── dns/
│       ├── named.conf      # BIND configuration
│       └── zones/
│           └── db.eduflip.local  # Zone file
└── web/
    ├── includes/           # PHP includes (config, auth, etc.)
    └── public/             # Web root (DocumentRoot)
        ├── index.php
        ├── assets/
        ├── mahasiswa/
        └── dosen/
```

### 3.4 Topologi Jaringan

```
                    Internet
                        │
                        ▼
              ┌─────────────────┐
              │   Host Machine  │
              │   (Windows OS)  │
              └────────┬────────┘
                       │
            ┌──────────┴──────────┐
            │   Docker Engine     │
            └──────────┬──────────┘
                       │
         ┌─────────────┴─────────────┐
         │      bridge: eduflip_net  │
         │      Subnet: 172.x.x.0/16 │
         └─────────────┬─────────────┘
                       │
    ┌──────────────────┼──────────────────┐
    │                  │                  │
    ▼                  ▼                  ▼
┌────────┐        ┌────────┐        ┌────────┐
│  WEB   │        │   DB   │        │  DNS   │
│ :80    │◄──────►│ :3306  │        │ :53    │
└────────┘        └────────┘        └────────┘
```

---

## BAB IV - Implementasi

### 4.1 Konfigurasi Docker Compose

File `docker-compose.yml` mendefinisikan ketiga service:

```yaml
services:
  web:
    build: .
    container_name: eduflip_web
    ports:
      - "80:80"
    environment:
      - DB_HOST=db
      - DB_NAME=eduflip
      - DB_USER=root
      - DB_PASS=root
    depends_on:
      - db
    networks:
      - eduflip_net
    volumes:
      - ./web/public/assets/uploads:/var/www/html/web/public/assets/uploads

  db:
    image: mysql:8.0
    container_name: eduflip_db
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: eduflip
    ports:
      - "3306:3306"
    volumes:
      - db_data:/var/lib/mysql
      - ./database/init.sql:/docker-entrypoint-initdb.d/init.sql
    networks:
      - eduflip_net

  dns:
    image: ubuntu/bind9
    container_name: eduflip_dns
    ports:
      - "53:53/tcp"
      - "53:53/udp"
    volumes:
      - ./docker/dns/named.conf:/etc/bind/named.conf
      - ./docker/dns/zones:/etc/bind/zones
    networks:
      - eduflip_net

networks:
  eduflip_net:
    driver: bridge

volumes:
  db_data:
```

**Penjelasan Konfigurasi:**

1. **Service `web`:**

   - Build dari Dockerfile lokal
   - Map port 80 host ke port 80 container
   - Environment variables untuk koneksi database
   - Tergantung pada service `db` (depends_on)
   - Mount volume untuk persistent uploads

2. **Service `db`:**

   - Menggunakan image MySQL 8.0 resmi
   - Map port 3306 untuk akses database
   - Volume untuk data persistence
   - Auto-initialize dengan `init.sql`

3. **Service `dns`:**
   - Menggunakan image Ubuntu BIND9
   - Map port 53 TCP dan UDP
   - Mount konfigurasi BIND

### 4.2 Konfigurasi Web Server (Dockerfile)

```dockerfile
FROM php:8.2-apache

# Install System Dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql mysqli zip

# Enable Apache Mod Rewrite
RUN a2enmod rewrite

# Set Working Directory
WORKDIR /var/www/html

# Copy Source Code
COPY . /var/www/html/

# Adjust Apache DocumentRoot to point to web/public
ENV APACHE_DOCUMENT_ROOT /var/www/html/web/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set Permissions
RUN chown -R www-data:www-data /var/www/html
```

**Penjelasan:**

- Base image: PHP 8.2 dengan Apache
- Install extension: PDO, MySQLi, Zip
- Enable mod_rewrite untuk URL routing
- Set DocumentRoot ke `web/public`

### 4.3 Konfigurasi Database Connection

File `web/includes/config.php`:

```php
<?php
// Database Configuration
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'eduflip');

// Application Configuration
define('APP_NAME', 'EduFlip');

// Dynamic APP_URL for Docker/Tunneling
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
define('APP_URL', $protocol . "://" . $_SERVER['HTTP_HOST']);

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("set names utf8mb4");
} catch(PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}

// Start Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
```

**Penjelasan:**

- Menggunakan environment variables untuk konfigurasi database
- Fallback ke nilai default untuk development lokal
- PDO dengan error mode exception
- Support UTF-8 multibyte

### 4.4 Konfigurasi DNS Server (BIND9)

#### 4.4.1 File Konfigurasi Utama (`named.conf`)

```
options {
    directory "/var/cache/bind";
    recursion yes;
    allow-query { any; };
    listen-on { any; };
    forwarders {
        8.8.8.8;
        8.8.4.4;
    };
};

zone "eduflip.local" {
    type master;
    file "/etc/bind/zones/db.eduflip.local";
};
```

**Penjelasan:**

- `recursion yes`: Mengizinkan recursive query
- `allow-query { any; }`: Menerima query dari semua IP
- `forwarders`: Forward ke Google DNS untuk domain eksternal
- Zone `eduflip.local` sebagai master zone

#### 4.4.2 File Zone (`db.eduflip.local`)

```
$TTL    604800
@       IN      SOA     ns1.eduflip.local. admin.eduflip.local. (
                              3         ; Serial
                         604800         ; Refresh
                          86400         ; Retry
                        2419200         ; Expire
                         604800 )       ; Negative Cache TTL
;
@       IN      NS      ns1.eduflip.local.
@       IN      A       127.0.0.1
ns1     IN      A       127.0.0.1
www     IN      A       127.0.0.1
db      IN      A       127.0.0.1
```

**Penjelasan Record:**

- **SOA (Start of Authority):** Informasi authoritative DNS
- **NS:** Nameserver untuk zone ini
- **A Record @:** Root domain resolve ke 127.0.0.1
- **A Record www:** Subdomain www resolve ke 127.0.0.1
- **A Record db:** Subdomain database resolve ke 127.0.0.1

407: ---
408:
409: ### 4.5 Konfigurasi Monitoring Server (Nagios)
410:
411: Sistem monitoring diimplementasikan menggunakan Nagios Core untuk memantau kesehatan (health check) dari ketiga container utama (web, db, dan dns).
412:
413: **File Konfigurasi Host & Service (`eduflip.cfg`):**
414:
415: `bash
416: # Definisi Host
417: define host {
418:     use                     linux-server
419:     host_name               eduflip_web
420:     address                 web
421: }
422: # (Definisi serupa untuk eduflip_db dan eduflip_dns)
423:
424: # Service Checks
425: define service {
426:     use                     generic-service
427:     host_name               eduflip_web
428:     service_description     HTTP Status
429:     check_command           check_http
430: }
431: `
432:
433: **Integrasi Docker Compose:**
434:
435: `yaml
436: nagios:
437: image: jasonrivers/nagios:latest
438: ports:
439: - "8080:80"
440: volumes:

### 4.5 Konfigurasi Monitoring Server (Nagios)

Sistem monitoring diimplementasikan menggunakan Nagios Core untuk memantau kesehatan (health check) dari ketiga container utama (web, db, dan dns).

**File Konfigurasi Host & Service (`eduflip.cfg`):**

`bash

# Definisi Host

define host {
use linux-server
host_name eduflip_web
address web
}

# (Definisi serupa untuk eduflip_db dan eduflip_dns)

# Service Checks

define service {
use generic-service
host_name eduflip_web
service_description HTTP Status
check_command check_http
}
`

**Integrasi Docker Compose:**

`yaml
  nagios:
    image: jasonrivers/nagios:latest
    ports:
      - "8080:80"
    volumes:
      - ./docker/nagios/eduflip.cfg:/opt/nagios/etc/conf.d/eduflip.cfg
`

Dengan konfigurasi ini, Nagios akan melakukan ping dan pengecekan service TCP (port 80, 3306, 53) secara berkala.

### 4.6 Troubleshooting & Perbaikan Bug

Selama proses pengembangan dan deployment, masalah teknis utama yang berkaitan dengan infrastruktur Docker ditemukan dan diselesaikan:

1. **Database Initialization Error:**
   - **Masalah:** Script `init.sql` gagal dieksekusi saat container pertama kali dijalankan karena masalah encoding karakter, menyebabkan tabel database tidak terbentuk meskipun container berjalan.
   - **Solusi:** Menyimpan ulang file `init.sql` dengan encoding UTF-8 tanpa BOM untuk memastikan kompatibilitas penuh dengan image MySQL 8.0, serta menggunakan mekanisme volume Docker untuk persistensi data.

---

## BAB V - Pengujian

### 5.1 Langkah Pengujian

#### 5.1.1 Menjalankan Container

```bash
# Masuk ke direktori proyek
cd eduflipp

# Build dan jalankan semua container
docker-compose up -d --build

# Cek status container
docker-compose ps
```

**Output yang diharapkan:**

```
NAME           COMMAND                  SERVICE   STATUS          PORTS
eduflip_db     "docker-entrypoint..."   db        Up              0.0.0.0:3306->3306/tcp
eduflip_dns    "named -g"               dns       Up              0.0.0.0:53->53/tcp, 0.0.0.0:53->53/udp
eduflip_web    "docker-php-entryp..."   web       Up              0.0.0.0:80->80/tcp
```

#### 5.1.2 Pengujian DNS Server

```bash
# Test resolusi domain (Windows)
nslookup eduflip.local 127.0.0.1

# Test resolusi subdomain www
nslookup www.eduflip.local 127.0.0.1
```

**Output yang diharapkan:**

```
Server:  127.0.0.1
Address:  127.0.0.1#53

Name:    eduflip.local
Address: 127.0.0.1
```

#### 5.1.3 Konfigurasi DNS pada Host

**Untuk Windows:**

1. Buka Control Panel > Network and Sharing Center
2. Klik adapter yang aktif > Properties
3. Pilih Internet Protocol Version 4 (TCP/IPv4) > Properties
4. Set "Preferred DNS Server" ke `127.0.0.1`

**Atau edit file hosts:**

_(Metode ini tidak digunakan agar sesuai dengan kaidah DNS Server yang sebenarnya)_

> **Catatan:** Pastikan tidak ada entri `eduflip.local` di file `hosts` system Anda.

#### 5.1.4 Pengujian Web Server

1. Buka browser
2. Akses: `http://eduflip.local` atau `http://www.eduflip.local`
3. Halaman login EduFlip seharusnya muncul

482:
483: #### 5.1.5 Pengujian Monitoring (Nagios)
484:
485: 1. Buka browser
486: 2. Akses: `http://localhost:8080`
487: 3. Login dengan user `nagiosadmin` dan password `nagios`
488: 4. Menu **Hosts** harus menampilkan status UP untuk `eduflip_web`, `eduflip_db`, dan `eduflip_dns`
489: 5. Menu **Services** harus menampilkan status OK untuk HTTP, MySQL, dan DNS Checks
490:
491: #### 5.1.6 Pengujian Koneksi Database

1. Coba login atau register akun baru
2. Data seharusnya tersimpan di database
3. Verifikasi dengan:

```bash
docker exec -it eduflip_db mysql -u root -proot eduflip -e "SELECT * FROM users LIMIT 5;"
```

### 5.2 Hasil Pengujian

| No  | Komponen         | Test Case                       | Status  |
| --- | ---------------- | ------------------------------- | ------- |
| 1   | Web Container    | Container running               | ✅ PASS |
| 2   | DB Container     | Container running               | ✅ PASS |
| 3   | DNS Container    | Container running               | ✅ PASS |
| 4   | Virtual Network  | Containers connected            | ✅ PASS |
| 5   | DNS Resolution   | eduflip.local resolves          | ✅ PASS |
| 6   | Web Access       | http://eduflip.local accessible | ✅ PASS |
| 7   | DB Connection    | PHP connects to MySQL           | ✅ PASS |
| 8   | Data Persistence | DB data persists                | ✅ PASS |
| 9   | Nagios Monitor   | Dashboard Accessible (8080)     | ✅ PASS |
| 10  | Service Checks   | All Services Status OK          | ✅ PASS |

---

## BAB VI - Kesimpulan dan Saran

### 6.1 Kesimpulan

Berdasarkan implementasi dan pengujian yang telah dilakukan, dapat disimpulkan bahwa:

1. **Web Server** berhasil diimplementasikan menggunakan Docker Container dengan base image PHP 8.2 dan Apache. Konfigurasi custom Dockerfile memungkinkan instalasi extension yang diperlukan dan pengaturan DocumentRoot yang sesuai.

2. **Database Server** berhasil diimplementasikan dalam container terpisah menggunakan MySQL 8.0. Koneksi antara web server dan database server berjalan melalui Docker virtual network dengan hostname internal `db`.

3. **Virtual Network** berhasil dikonfigurasi menggunakan Docker bridge network `eduflip_net` yang menghubungkan ketiga container (web, db, dns) dalam satu jaringan terisolasi.

4. **DNS Server** berbasis BIND9 berhasil diimplementasikan untuk me-resolve domain lokal `eduflip.local` beserta subdomain-nya (www, db) ke IP 127.0.0.1.

5. **Aplikasi Web EduFlip** dapat diakses dari browser pada host OS melalui URL `http://eduflip.local` dengan semua fitur berfungsi normal termasuk koneksi database.

### 6.2 Saran

1. **Keamanan:** Implementasikan HTTPS menggunakan sertifikat SSL/TLS untuk produksi.
2. **Monitoring:** Tambahkan monitoring seperti Prometheus/Grafana untuk observability.
3. **Backup:** Implementasikan strategi backup otomatis untuk database.
4. **Scaling:** Pertimbangkan penggunaan Docker Swarm atau Kubernetes untuk high availability.
5. **CI/CD:** Integrasikan dengan pipeline CI/CD untuk automated deployment.

---

## Daftar Pustaka

1. Docker Documentation. (2024). _Docker Overview_. https://docs.docker.com/get-started/overview/
2. Docker Compose Documentation. (2024). _Compose File Reference_. https://docs.docker.com/compose/compose-file/
3. Apache HTTP Server Project. (2024). _Apache Documentation_. https://httpd.apache.org/docs/
4. MySQL Documentation. (2024). _MySQL 8.0 Reference Manual_. https://dev.mysql.com/doc/refman/8.0/en/
5. ISC BIND 9. (2024). _BIND 9 Administrator Reference Manual_. https://bind9.readthedocs.io/
6. PHP Documentation. (2024). _PHP Manual_. https://www.php.net/manual/en/

---

## Lampiran

### Lampiran A: Screenshot Hasil Pengujian

_(Sisipkan screenshot berikut saat membuat PDF final)_

1. Screenshot `docker-compose ps` menunjukkan semua container running
2. Screenshot nslookup berhasil resolve eduflip.local
3. Screenshot halaman utama EduFlip di browser
4. Screenshot halaman login
5. Screenshot database berisi data

### Lampiran B: Source Code Lengkap

Source code lengkap dapat diakses di repository proyek.

### Lampiran C: Struktur Database

_(Sisipkan ERD atau daftar tabel database)_

---

**Disusun oleh:**

Nama Kelompok: **\*\*\*\***\_**\*\*\*\***

Anggota:

1. ***
2. ***
3. ***

Tanggal: Desember 2024
