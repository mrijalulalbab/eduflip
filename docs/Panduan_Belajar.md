# 📖 PANDUAN BELAJAR PROYEK DOCKER

## Understanding Everything - Biar Bisa Jawab Pertanyaan Dosen!

> **Tips:** Baca pelan-pelan, pahami konsepnya, jangan hafal. Kalau paham, jawab pertanyaan apapun pasti bisa!

---

# BAGIAN 1: KONSEP DASAR

## 🐳 Apa itu Docker?

**Analogi Sederhana:**
Bayangkan kamu mau bawa makanan ke kampus. Kamu taruh di **kotak makan** supaya:

- Tidak tumpah
- Tidak tercampur makanan lain
- Mudah dibawa kemana-mana

**Docker = Kotak makan untuk aplikasi.**

Aplikasi kita "dimasukkan" ke dalam **container** supaya:

- Tidak bentrok dengan aplikasi lain di komputer
- Bisa dipindah ke komputer lain tanpa masalah
- Semua orang dapat versi yang sama persis

**Kenapa tidak pakai XAMPP saja?**

- XAMPP: Install di komputer, versi bisa beda-beda, bentrok dengan aplikasi lain
- Docker: Terisolasi, versi pasti sama, tidak bentrok

---

## 🆚 Docker vs Virtual Machine (VM)

| Aspek    | Virtual Machine                      | Docker                              |
| -------- | ------------------------------------ | ----------------------------------- |
| Analogi  | Beli komputer baru di dalam komputer | Bikin folder khusus yang terisolasi |
| Berat    | Berat (GB), perlu OS lengkap         | Ringan (MB), share OS dengan host   |
| Start    | Lama (menit)                         | Cepat (detik)                       |
| Resource | Boros RAM & CPU                      | Hemat resource                      |

**Intinya:** Docker lebih RINGAN karena tidak perlu install OS terpisah.

---

## 📦 Istilah-Istilah Penting Docker

### 1. Image

**Analogi:** Resep masakan

Image adalah **blueprint/cetakan** untuk membuat container. Berisi instruksi apa saja yang harus di-install.

Contoh: `php:8.2-apache` = image yang sudah ada PHP dan Apache

### 2. Container

**Analogi:** Makanan jadi dari resep

Container adalah **instance yang berjalan** dari sebuah image. Satu image bisa jadi banyak container.

Contoh: Dari image `mysql:8.0`, kita buat container `eduflip_db`

### 3. Dockerfile

**Analogi:** Resep masakan kustom

File berisi instruksi untuk **membuat image custom** kita sendiri.

```dockerfile
FROM php:8.2-apache      # Pakai resep dasar ini
RUN apt-get install ...  # Tambahkan bahan ini
COPY . /var/www/html     # Masukkan kode kita
```

### 4. Docker Compose

**Analogi:** Menu kombo restoran

Tool untuk **menjalankan banyak container sekaligus** dengan satu perintah.

File: `docker-compose.yml`

### 5. Volume

**Analogi:** Lemari penyimpanan

Tempat **menyimpan data permanen** di luar container.
Kalau container dihapus, data di volume tetap aman.

### 6. Network

**Analogi:** Grup WhatsApp

**Jaringan virtual** yang menghubungkan container.
Container dalam network yang sama bisa saling komunikasi.

---

# BAGIAN 2: ARSITEKTUR PROYEK KITA

## 🏠 Gambaran Besar

```
Laptop Kamu (Host)
    │
    └── Docker Engine
            │
            └── Network: eduflip_net
                    │
        ┌───────────┼───────────┐
        │           │           │
    Container   Container   Container
       WEB         DB         DNS
    (Apache)    (MySQL)    (BIND9)
     :80        :3306       :53
```

**Penjelasan:**

1. Di laptop kamu ada **Docker Engine** yang menjalankan semuanya
2. Ada **3 container** (WEB, DB, DNS) seperti 3 kamar terpisah
3. Semua dihubungkan oleh **network** bernama `eduflip_net` (seperti koridor)
4. Dari laptop, kamu akses lewat **browser** ke `http://eduflip.local`

---

## 📁 Struktur Folder Proyek

```
eduflipp/
├── docker-compose.yml   ← "Menu kombo" untuk jalankan semua
├── Dockerfile           ← "Resep" untuk bikin image web
├── database/
│   └── init.sql         ← Script SQL untuk setup database
├── docker/
│   └── dns/
│       ├── named.conf   ← Konfigurasi DNS
│       └── zones/
│           └── db.eduflip.local  ← Daftar domain
└── web/
    ├── includes/
    │   └── config.php   ← Konfigurasi koneksi DB
    └── public/          ← Folder website (DocumentRoot)
```

---

# BAGIAN 3: PENJELASAN TIAP KOMPONEN

## 🌐 1. Web Server (Container: eduflip_web)

**Apa fungsinya?**
Melayani halaman website ke browser. Ketika kamu buka `http://eduflip.local`, web server yang memproses.

**Teknologi:**

- Apache (web server)
- PHP 8.2 (bahasa pemrograman)

**File penting: `Dockerfile`**

```dockerfile
FROM php:8.2-apache
# Artinya: Pakai image yang sudah ada PHP dan Apache

RUN docker-php-ext-install pdo pdo_mysql mysqli
# Artinya: Install extension untuk koneksi database

ENV APACHE_DOCUMENT_ROOT /var/www/html/web/public
# Artinya: Set folder utama website ke web/public
```

**Kenapa pakai nama `db` bukan `localhost`?**
Dalam Docker, setiap container punya "nama". Untuk konek ke container database, kita pakai namanya: `db`.
Docker otomatis tahu `db` = container database kita.

---

## 🗄️ 2. Database Server (Container: eduflip_db)

**Apa fungsinya?**
Menyimpan semua data: user, kursus, nilai, dll.

**Teknologi:**

- MySQL 8.0

**Konfigurasi di docker-compose.yml:**

```yaml
db:
  image: mysql:8.0
  environment:
    MYSQL_ROOT_PASSWORD: root
    MYSQL_DATABASE: eduflip
  volumes:
    - db_data:/var/lib/mysql # Data permanen
    - ./database/init.sql:/docker-entrypoint-initdb.d/init.sql # Setup awal
```

**Penjelasan:**

- `MYSQL_DATABASE: eduflip` → Database otomatis dibuat
- `init.sql` → File SQL dijalankan saat pertama kali start
- `db_data` → Volume untuk simpan data permanen

**Kenapa data tidak hilang saat restart?**
Karena kita pakai **volume** `db_data`. Data disimpan di luar container.

---

## 🔤 3. DNS Server (Container: eduflip_dns)

**Apa fungsinya?**
Menerjemahkan nama domain jadi alamat IP.

**Analogi:**
DNS = **Buku telepon**

- Kamu tahu nama orang, tapi perlu cari nomornya
- Browser tahu `eduflip.local`, tapi perlu cari IP-nya

**Teknologi:**

- BIND9 (software DNS server paling populer)

**File: `named.conf`**

```
zone "eduflip.local" {
    type master;
    file "/etc/bind/zones/db.eduflip.local";
};
```

Artinya: DNS ini bertanggung jawab atas domain `eduflip.local`

**File: `db.eduflip.local` (Zone File)**

```
@       IN      A       127.0.0.1
www     IN      A       127.0.0.1
db      IN      A       127.0.0.1
```

**Penjelasan:**

- `eduflip.local` → 127.0.0.1
- `www.eduflip.local` → 127.0.0.1
- `db.eduflip.local` → 127.0.0.1

Semua mengarah ke 127.0.0.1 (localhost = komputer kita sendiri).

**Kenapa tidak edit file hosts saja?**
Bisa, tapi di spesifikasi tugas diminta DNS server berbasis BIND.
Plus, BIND lebih "proper" untuk production dan bisa diakses banyak komputer.

---

## 🔗 4. Virtual Network (eduflip_net)

**Apa fungsinya?**
Menghubungkan ketiga container supaya bisa komunikasi.

**Analogi:**
Network = **Grup WhatsApp khusus**

- Anggota grup bisa chat satu sama lain
- Orang luar tidak bisa masuk

**Konfigurasi:**

```yaml
networks:
  eduflip_net:
    driver: bridge
```

**Kenapa pakai bridge?**

- `bridge` = network default Docker untuk satu komputer
- Container bisa komunikasi pakai nama container (web, db, dns)

---

# BAGIAN 4: CARA KERJA SISTEM

## 🔄 Alur Ketika Akses Website

```
1. Kamu ketik: http://eduflip.local di browser

2. Browser tanya DNS: "eduflip.local itu IP berapa?"

3. DNS Server jawab: "127.0.0.1"

4. Browser kirim request ke 127.0.0.1:80

5. Docker forward ke container WEB (port 80)

6. Apache terima request, PHP proses

7. PHP perlu data? Koneksi ke container DB (nama: db)

8. Database kirim data balik ke PHP

9. PHP render HTML, kirim ke browser

10. Kamu lihat halaman EduFlip! 🎉
```

---

## ⚡ Perintah-Perintah Penting

| Perintah                          | Fungsi                                 |
| --------------------------------- | -------------------------------------- |
| `docker-compose up -d`            | Jalankan semua container di background |
| `docker-compose up -d --build`    | Jalankan + rebuild image               |
| `docker-compose down`             | Matikan semua container                |
| `docker-compose ps`               | Lihat status container                 |
| `docker-compose logs web`         | Lihat log container web                |
| `docker exec -it eduflip_db bash` | Masuk ke dalam container               |

---

# BAGIAN 5: PERTANYAAN YANG SERING DITANYA

## Q1: "Jelaskan apa itu Docker!"

> "Docker adalah platform untuk menjalankan aplikasi dalam container. Container itu seperti kotak terisolasi yang berisi aplikasi dan semua dependensinya. Lebih ringan dari VM karena tidak perlu OS terpisah."

## Q2: "Bedanya Docker dan Virtual Machine?"

> "VM seperti beli komputer baru di dalam komputer - berat dan lambat. Docker seperti bikin folder terisolasi - ringan dan cepat. Docker share kernel dengan host, VM perlu OS sendiri."

## Q3: "Apa itu Dockerfile?"

> "Dockerfile adalah file berisi instruksi untuk membuat Docker image. Seperti resep masakan. Di dalamnya ada perintah: pakai base image apa, install apa, copy file apa."

## Q4: "Apa itu Docker Compose?"

> "Docker Compose adalah tool untuk menjalankan banyak container sekaligus. Semua konfigurasi ditulis di file docker-compose.yml. Satu perintah 'docker-compose up', semua jalan."

## Q5: "Kenapa ada 3 container terpisah?"

> "Supaya modular dan scalable. Kalau butuh database lebih kuat, tinggal upgrade container DB. Kalau web server bermasalah, DB tidak terpengaruh."

## Q6: "Bagaimana container bisa komunikasi?"

> "Lewat virtual network. Di docker-compose.yml kita definisikan network bernama eduflip_net. Semua container yang join network ini bisa saling akses pakai nama container."

## Q7: "Kenapa koneksi DB pakai nama 'db' bukan 'localhost'?"

> "Dalam Docker, 'localhost' berarti container itu sendiri. Untuk akses container lain, kita pakai nama service-nya. 'db' adalah nama service database di docker-compose.yml."

## Q8: "Apa fungsi DNS server di proyek ini?"

> "DNS menerjemahkan nama domain 'eduflip.local' jadi IP address 127.0.0.1. Supaya browser bisa akses website pakai nama yang mudah diingat, bukan angka IP."

## Q9: "Kalau docker-compose down, data database hilang?"

> "Tidak. Kita pakai volume 'db_data' yang menyimpan data di luar container. Volume ini persist meskipun container dihapus."

## Q10: "Apa itu port mapping (80:80)?"

> "'80:80' artinya port 80 di host (laptop) dipetakan ke port 80 di container. Jadi akses localhost:80 di browser = akses port 80 container web."

---

# BAGIAN 6: TIPS JAWAB PERTANYAAN

1. **Jangan panik!** Tarik napas, pikir dulu baru jawab

2. **Pakai analogi** kalau bisa:

   - Docker = kotak makan
   - DNS = buku telepon
   - Network = grup WhatsApp
   - Volume = lemari penyimpanan

3. **Kalau tidak tahu**, bilang jujur:

   > "Untuk bagian itu saya belum explore lebih dalam, tapi yang saya tahu adalah..."

4. **Jawab intinya dulu**, baru detail kalau diminta

5. **Tunjukkan paham konsep**, bukan hafalan

---

# ✅ CHECKLIST SEBELUM PRESENTASI

- [ ] Saya paham apa itu Docker dan bedanya dengan VM
- [ ] Saya paham fungsi Dockerfile dan Docker Compose
- [ ] Saya paham kenapa ada 3 container terpisah
- [ ] Saya paham bagaimana container saling komunikasi
- [ ] Saya paham fungsi DNS server
- [ ] Saya paham kenapa data tidak hilang (volume)
- [ ] Saya sudah coba jalankan docker-compose di laptop

---

**Semoga sukses! 💪**

_Kalau masih bingung, baca ulang pelan-pelan. Pahami konsepnya, jangan hafal._
