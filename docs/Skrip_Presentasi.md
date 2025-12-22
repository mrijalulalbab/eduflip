# 🎤 SKRIP BICARA PRESENTASI (15 SLIDE - ~15 MENIT)

## Proyek SJK/CSN: EduFlip Docker

---

## PEMBAGIAN TUGAS (5 Orang, 15 Slide)

| Nama       | Slide        | Durasi     |
| ---------- | ------------ | ---------- |
| **NILAM**  | 1-3          | ~2 menit   |
| **NAUFAL** | 4-7          | ~3 menit   |
| **NIKO**   | 8-9          | ~2.5 menit |
| **DIPTA**  | 10-11        | ~2.5 menit |
| **ALBAB**  | 12-15 + Demo | ~5 menit   |

**Total: ~15 menit** ✅

---

# 🟢 NILAM (Slide 1-3)

## Slide 1: Cover

> "Assalamualaikum warahmatullahi wabarakatuh. Selamat pagi/siang Bapak/Ibu dan teman-teman.
>
> Kami dari kelompok [NAMA KELOMPOK] akan presentasi proyek SJK/CSN.
>
> Proyek kami adalah implementasi web server menggunakan Docker Container, dengan aplikasi EduFlip yang sudah kami buat di mata kuliah PABW."

## Slide 2: Daftar Isi

> "Presentasi kami dibagi menjadi: Pendahuluan, Arsitektur Sistem, Implementasi, Pengujian & Demo, dan Kesimpulan."

## Slide 3: Latar Belakang & Tujuan

> "Kenapa kita pakai Docker?
>
> Bayangkan Docker seperti KOTAK MAKAN. Aplikasi kita dimasukkan ke dalam kotak supaya tidak tumpah, tidak tercampur dengan yang lain, dan mudah dibawa kemana-mana.
>
> Docker lebih ringan dari Virtual Machine karena tidak perlu OS tambahan.
>
> Tujuan kami: bikin Web Server, Database, dan DNS dalam container yang terhubung via virtual network, supaya bisa diakses dari browser pakai nama domain eduflip.local.
>
> Selanjutnya Naufal akan jelaskan teknologi dan arsitekturnya."

---

# 🔵 NAUFAL (Slide 4-7)

## Slide 4: Teknologi

> "Terima kasih Nilam. Saya jelaskan teknologi yang kami gunakan.
>
> Untuk container management: Docker dan Docker Compose.
> Web server: Apache dengan PHP versi 8.2.
> Database: MySQL versi 8.
> DNS server: BIND9.
> Dan aplikasi kami EduFlip yang ditulis dalam PHP."

## Slide 5: Arsitektur Sistem

> "Ini arsitektur sistem kami. Coba lihat gambarnya.
>
> Bayangkan laptop kita seperti RUMAH. Di dalam rumah ada TIGA KAMAR terpisah:
>
> Kamar pertama: Web Server - melayani halaman website.
> Kamar kedua: Database - menyimpan data user dan kursus.
> Kamar ketiga: DNS Server - menerjemahkan nama domain jadi IP.
>
> Ketiga kamar dihubungkan oleh KORIDOR bernama eduflip_net."

## Slide 6: Struktur Proyek

> "Ini struktur folder proyek kami.
>
> File docker-compose.yml untuk orchestration semua container.
> Dockerfile untuk build image web server.
> Folder database berisi init.sql untuk setup database awal.
> Folder docker/dns berisi konfigurasi BIND.
> Dan folder web berisi kode aplikasi PHP."

## Slide 7: Docker Compose

> "Docker Compose seperti RESEP MASAKAN. Tulis sekali di file docker-compose.yml, Docker yang jalankan semuanya.
>
> Di sini kita definisikan 3 service: web, db, dan dns.
> Plus 1 network: eduflip_net untuk menghubungkan semuanya.
>
> Tinggal ketik 'docker-compose up -d', semua container langsung jalan!
>
> Selanjutnya Niko jelaskan implementasi web dan database."

---

# 🟡 NIKO (Slide 8-9)

## Slide 8: Web Server

> "Terima kasih Naufal. Saya jelaskan web server.
>
> Di Dockerfile, kita pakai base image php:8.2-apache.
> Install extension PDO dan MySQLi untuk koneksi database.
> Set DocumentRoot ke folder web/public.
>
> Yang penting: untuk koneksi database, kita pakai nama container 'db', bukan 'localhost'.
> Docker otomatis tahu 'db' itu merujuk ke container database kita.
> Ini lebih aman dan benar."

## Slide 9: Database Server

> "Untuk database, pakai MySQL versi 8.
>
> Database 'eduflip' otomatis dibuat saat startup.
> File init.sql otomatis dijalankan untuk setup tabel-tabel.
> Pakai volume db_data supaya data tetap aman.
>
> Jadi meskipun container di-restart atau dihapus, datanya tidak hilang.
>
> Selanjutnya Dipta jelaskan DNS server."

---

# 🟠 DIPTA (Slide 10-11)

## Slide 10: DNS Server

> "Terima kasih Niko. Saya jelaskan DNS server.
>
> DNS seperti BUKU TELEPON. Kalau kita ketik 'google.com', komputer tidak tahu itu apa. DNS yang terjemahkan jadi alamat IP.
>
> Kita bikin DNS server sendiri pakai BIND9.
> Di file named.conf, kita definisikan zone 'eduflip.local'.
> Di zone file, kita mapping: eduflip.local, www.eduflip.local, dan db.eduflip.local semuanya mengarah ke 127.0.0.1.
>
> Jadi browser bisa akses pakai nama domain yang readable."

## Slide 11: Cara Menjalankan

> "Cara menjalankan sangat mudah.
>
> Buka terminal, masuk ke folder proyek.
> Ketik: docker-compose up -d --build.
> Tunggu sebentar sampai selesai.
> Cek status dengan docker-compose ps, pastikan semua 'Up'.
> Buka browser, ketik http://eduflip.local.
>
> Selesai! Tidak perlu install Apache, MySQL, atau PHP manual.
>
> Selanjutnya Albab akan tunjukkan hasil pengujian dan demo."

---

# 🟣 ALBAB (Slide 12-15 + Demo)

## Slide 12: Hasil Pengujian

> "Terima kasih Dipta. Hasil pengujian kami:
>
> Semua container berhasil running.
> Network terhubung dengan baik.
> DNS berhasil resolve eduflip.local.
> Website bisa diakses dari browser.
> Database terkoneksi dengan lancar.
>
> Semua sesuai spesifikasi yang diminta."

## Slide 13: DEMO LIVE

**Langkah-langkah:**

1. Buka terminal, ketik: `docker-compose ps`

   > "Ini bukti ketiga container kita running. Lihat statusnya 'Up' semua."

2. Ketik: `nslookup eduflip.local 127.0.0.1`

   > "Ini bukti DNS bekerja. eduflip.local ter-resolve ke 127.0.0.1."

3. Buka browser, ketik: `http://eduflip.local`

   > "Sekarang kita buka aplikasinya. Ini halaman EduFlip."

4. Tunjukkan halaman/fitur
   > "Aplikasi berjalan normal. Kita bisa login, lihat dashboard, dan menggunakan fitur-fiturnya."

## Slide 14: Kesimpulan

> "Kesimpulan dari proyek kami:
>
> Docker berhasil memudahkan deployment aplikasi web.
> Web Server, Database, dan DNS berhasil di-containerize dan terhubung via virtual network.
> DNS berhasil resolve domain eduflip.local.
> Aplikasi berjalan dengan baik.
>
> Untuk pengembangan ke depan, bisa ditambahkan HTTPS untuk keamanan, dan monitoring seperti Nagios untuk observability."

## Slide 15: Penutup

> "Demikian presentasi dari kelompok kami. Terima kasih atas perhatiannya.
>
> Apakah ada pertanyaan?"

---

# 🆘 JAWABAN PERTANYAAN YANG MUNGKIN DITANYA

**"Apa bedanya Docker dengan Virtual Machine?"**

> "Virtual Machine itu seperti beli komputer baru di dalam komputer. Berat dan lambat karena perlu OS tambahan. Docker seperti folder khusus yang terisolasi. Lebih ringan dan cepat karena share kernel dengan host."

**"Kenapa pakai BIND untuk DNS? Kenapa tidak edit file hosts saja?"**

> "Edit file hosts memang lebih mudah, tapi di spesifikasi tugas diminta implementasi DNS server berbasis BIND. Lagipula BIND lebih 'proper' untuk production environment dan bisa diakses oleh banyak komputer."

**"Kalau docker-compose down, data hilang tidak?"**

> "Tidak hilang. Kita pakai volume bernama db_data yang menyimpan data database di luar container. Jadi meskipun container dihapus, datanya tetap ada."

**"Ini bisa diakses dari komputer lain?"**

> "Bisa, tapi perlu konfigurasi tambahan. Sekarang DNS kita setting ke 127.0.0.1 yang artinya localhost. Untuk akses dari komputer lain, kita perlu ganti ke IP address komputer host dan atur firewall-nya."

**"Kenapa port 80 bukan 8080?"**

> "Port 80 adalah port standar untuk HTTP. Kalau pakai 8080, kita harus ketik http://eduflip.local:8080 yang kurang praktis. Pakai port 80, cukup ketik http://eduflip.local saja."

---

# ✨ TIPS SEBELUM PRESENTASI

1. ✅ Latihan bareng 1-2x sebelum hari H
2. ✅ Pastikan Docker sudah running sebelum masuk kelas
3. ✅ Siapkan screenshot backup kalau demo gagal
4. ✅ Bawa air minum biar tidak kering
5. ✅ Santai! Dosen tahu kalian masih belajar

**Semoga lancar presentasinya! 💪**
