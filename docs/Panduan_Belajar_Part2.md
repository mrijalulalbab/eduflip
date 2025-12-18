# 📖 PANDUAN BELAJAR PART 2: DEEP DIVE

## Materi Lanjutan + Lagu Buat Hafal! 🎵

> **Part 2 ini lebih dalam.** Kalau Part 1 sudah paham, lanjut ke sini biar makin mantap!

---

# 🎵 LAGU DOCKER (Nada: Balonku Ada Lima)

```
🎶 DOCKER-KU ADA TIGA 🎶

Docker-ku ada tiga
Container warna-warni
Web server dan database
DNS juga ada di sini

Meletus container satu... DOR!
Hatiku sangat kaget
Docker-ku tinggal dua
Ku pegang erat-erat~

(Tapi tenang, tinggal "docker-compose up" lagi!) 😄
```

---

# 🎵 LAGU DNS (Nada: Naik-Naik ke Puncak Gunung)

```
🎶 NAIK-NAIK KE DNS SERVER 🎶

Naik-naik ke DNS server
Tinggi-tinggi sekali~
Kiri kanan ku lihat saja
Banyak domain di sini~

Eduflip local~
Resolve ke satu dua tujuh~
Titik nol titik nol titik satu!
(127.0.0.1 maksudnya 😆)
```

---

# 🎵 LAGU DOCKER COMPOSE (Nada: Potong Bebek Angsa)

```
🎶 JALANKAN DOCKER COMPOSE 🎶

Jalankan docker compose
Masak masak di dapur~
Satu command semua jalan
Web DB DNS langsung muncul!

Sorong ke kiri~ (docker-compose up!)
Sorong ke kanan~ (docker-compose down!)
La la la la la la la~
Container kita aman!
```

---

# BAGIAN 7: FILE-FILE PENTING EXPLAINED

## 📄 docker-compose.yml - Line by Line

```yaml
services: # Daftar "kamar" yang mau kita bikin
  web: # Nama service pertama: web
    build: . # Bikin image dari Dockerfile di folder ini
    container_name: eduflip_web # Nama container-nya
    ports:
      - "80:80" # Port 80 laptop = Port 80 container
    environment: # Environment variables (kayak setting)
      - DB_HOST=db # Nama host database = "db" (nama service)
      - DB_NAME=eduflip
      - DB_USER=root
      - DB_PASS=root
    depends_on:
      - db # Jangan start sebelum "db" jalan
    networks:
      - eduflip_net # Gabung ke network ini
    volumes: # Folder yang di-share
      - ./web/public/assets/uploads:/var/www/html/web/public/assets/uploads

  db: # Service kedua: database
    image: mysql:8.0 # Pakai image dari Docker Hub (tidak build sendiri)
    container_name: eduflip_db
    environment:
      MYSQL_ROOT_PASSWORD: root # Password root MySQL
      MYSQL_DATABASE: eduflip # Nama database yang auto-bikin
    ports:
      - "3306:3306"
    volumes:
      - db_data:/var/lib/mysql # Volume untuk data persistence
      - ./database/init.sql:/docker-entrypoint-initdb.d/init.sql # Auto-run SQL

  dns: # Service ketiga: DNS
    image: ubuntu/bind9
    container_name: eduflip_dns
    ports:
      - "53:53/tcp" # DNS pakai port 53 (TCP dan UDP)
      - "53:53/udp"
    volumes:
      - ./docker/dns/named.conf:/etc/bind/named.conf
      - ./docker/dns/zones:/etc/bind/zones

networks: # Definisi network
  eduflip_net:
    driver: bridge # Tipe network: bridge (untuk 1 host)

volumes: # Definisi volume
  db_data: # Volume bernama "db_data"
```

---

## 📄 Dockerfile - Line by Line

```dockerfile
FROM php:8.2-apache
# "FROM" = pakai image dasar ini
# php:8.2-apache = sudah ada PHP 8.2 dan Apache di dalamnya

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql mysqli zip
# "RUN" = jalankan command ini saat build
# apt-get install = install library yang dibutuhkan
# docker-php-ext-install = install extension PHP

RUN a2enmod rewrite
# Enable Apache mod_rewrite (untuk URL cantik)

WORKDIR /var/www/html
# "WORKDIR" = set folder kerja

COPY . /var/www/html/
# "COPY" = copy semua file dari folder lokal ke dalam container

ENV APACHE_DOCUMENT_ROOT /var/www/html/web/public
# "ENV" = set environment variable
# DocumentRoot = folder yang dilayani Apache

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
# Ganti DocumentRoot default Apache ke folder kita

RUN chown -R www-data:www-data /var/www/html
# Set permission supaya Apache bisa baca file
```

---

## 📄 named.conf - Konfigurasi DNS

```
options {
    directory "/var/cache/bind";   # Folder kerja BIND
    recursion yes;                  # Izinkan recursive query
    allow-query { any; };           # Terima query dari siapa saja
    listen-on { any; };             # Dengarkan di semua IP
    forwarders {
        8.8.8.8;                    # Kalau tidak tahu, tanya Google DNS
        8.8.4.4;
    };
};

zone "eduflip.local" {              # Definisi zone baru
    type master;                    # Kita adalah authority untuk zone ini
    file "/etc/bind/zones/db.eduflip.local";  # File zone-nya di sini
};
```

---

## 📄 Zone File (db.eduflip.local)

```
$TTL    604800                      # Time To Live: 7 hari dalam detik
@       IN      SOA     ns1.eduflip.local. admin.eduflip.local. (
                              3     # Serial number (naikkan tiap update)
                         604800     # Refresh
                          86400     # Retry
                        2419200     # Expire
                         604800 )   # Negative Cache TTL

@       IN      NS      ns1.eduflip.local.    # Nameserver untuk zone ini
@       IN      A       127.0.0.1             # eduflip.local → 127.0.0.1
ns1     IN      A       127.0.0.1             # ns1.eduflip.local → 127.0.0.1
www     IN      A       127.0.0.1             # www.eduflip.local → 127.0.0.1
db      IN      A       127.0.0.1             # db.eduflip.local → 127.0.0.1
```

**Jenis Record DNS:**
| Record | Fungsi | Contoh |
|--------|--------|--------|
| A | Domain → IPv4 | eduflip.local → 127.0.0.1 |
| NS | Nameserver | ns1.eduflip.local |
| SOA | Info authority | Serial, refresh timing |
| CNAME | Alias | www → eduflip.local |
| MX | Mail server | (tidak dipakai di sini) |

---

# BAGIAN 8: TROUBLESHOOTING

## ❌ "Port 53 is already in use"

**Penyebab:** Ada service lain yang pakai port 53 (biasanya Windows DNS Client)

**Solusi Windows:**

```powershell
# Cek siapa yang pakai port 53
netstat -ano | findstr :53

# Matikan service (jika perlu)
net stop "DNS Client"
```

Atau, ganti port DNS di docker-compose.yml:

```yaml
ports:
  - "5353:53/tcp"
  - "5353:53/udp"
```

---

## ❌ "Connection refused" ke database

**Penyebab:** Container database belum siap

**Solusi:**

1. Cek apakah container db sudah running: `docker-compose ps`
2. Cek log: `docker-compose logs db`
3. Pastikan pakai `DB_HOST=db` bukan `localhost`

---

## ❌ Website tidak muncul / 404

**Penyebab:** DocumentRoot salah

**Solusi:**

1. Cek Dockerfile → `APACHE_DOCUMENT_ROOT` harus `/var/www/html/web/public`
2. Rebuild: `docker-compose up -d --build`

---

## ❌ DNS tidak resolve

**Penyebab:** DNS belum dikonfigurasi di host

**Solusi:**
Edit file hosts (`C:\Windows\System32\drivers\etc\hosts`):

```
127.0.0.1    eduflip.local
127.0.0.1    www.eduflip.local
```

Test:

```bash
nslookup eduflip.local 127.0.0.1
```

---

# BAGIAN 9: COMMANDS CHEAT SHEET

## Docker Compose Commands

| Command                        | Fungsi                                |
| ------------------------------ | ------------------------------------- |
| `docker-compose up -d`         | Jalankan semua container (background) |
| `docker-compose up -d --build` | Build ulang + jalankan                |
| `docker-compose down`          | Stop + hapus container                |
| `docker-compose ps`            | Lihat status container                |
| `docker-compose logs`          | Lihat semua log                       |
| `docker-compose logs web`      | Lihat log container web               |
| `docker-compose restart web`   | Restart container web                 |
| `docker-compose exec web bash` | Masuk ke dalam container              |

## Docker Commands

| Command                       | Fungsi                                |
| ----------------------------- | ------------------------------------- |
| `docker ps`                   | Lihat container yang running          |
| `docker ps -a`                | Lihat semua container (termasuk stop) |
| `docker images`               | Lihat daftar images                   |
| `docker stop <name>`          | Stop container                        |
| `docker rm <name>`            | Hapus container                       |
| `docker rmi <image>`          | Hapus image                           |
| `docker exec -it <name> bash` | Masuk ke container                    |

## MySQL di Container

```bash
# Masuk ke MySQL
docker exec -it eduflip_db mysql -u root -proot

# Jalankan query langsung
docker exec -it eduflip_db mysql -u root -proot eduflip -e "SELECT * FROM users"

# Backup database
docker exec eduflip_db mysqldump -u root -proot eduflip > backup.sql

# Restore database
docker exec -i eduflip_db mysql -u root -proot eduflip < backup.sql
```

---

# BAGIAN 10: QUIZ DIRI SENDIRI

Jawab pertanyaan ini TANPA lihat catatan:

1. Apa bedanya `image` dan `container`?
2. Kenapa kita pakai `db` bukan `localhost` untuk koneksi database?
3. Apa fungsi `volumes` di docker-compose?
4. Sebutkan 3 container yang ada di proyek ini!
5. Apa fungsi DNS server?
6. Bagaimana cara melihat log container?
7. Apa yang terjadi kalau `docker-compose down`?
8. Kenapa data database tidak hilang saat restart?
9. Apa bedanya `docker-compose up` dan `docker-compose up --build`?
10. File apa yang berisi instruksi untuk membuat image custom?

**Jawaban ada di Part 1! Cek kalau ragu.**

---

# 🎵 BONUS: LAGU PENUTUP (Nada: Sayonara)

```
🎶 DOCKER SELAMANYA 🎶

Docker... selamanya...
Container... berjaya...
Build sekali, jalan di mana saja
Itulah Docker kita~

Web server... database...
DNS juga ada...
Satu command semua jalan
docker-compose up!

🎤 *mic drop*
```

---

**Sekarang harusnya kamu sudah SIAP BANGET! 💪**

_Tips terakhir: Nyanyikan lagunya sambil ngoding, dijamin ingat!_ 🎶
