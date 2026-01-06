# PANDUAN INSTALASI DASHBOARD SURVEY KEPENDUDUKAN

## Langkah-Langkah Instalasi

### ✅ STEP 1: Setup Database di PhpMyAdmin

1. **Buka PhpMyAdmin**
   - Akses: http://localhost/phpmyadmin
   - Login dengan user: root (password kosong jika default XAMPP)

2. **Buat Database Baru**
   - Klik "New" atau "Buat" di sidebar
   - Nama database: `survey_kependudukan`
   - Collation: `utf8mb4_unicode_ci`
   - Klik "Create"

3. **Import SQL File**
   - Pilih database yang baru dibuat
   - Klik tab "SQL"
   - Copy-paste seluruh isi file: `database/setup.sql`
   - Klik tombol "Go" untuk menjalankan

   **Atau** gunakan cara alternatif:
   - Tab "Import"
   - Pilih file `database/setup.sql`
   - Klik "Go"

4. **Verifikasi Database**
   - Refresh PhpMyAdmin
   - Lihat struktur tabel yang sudah dibuat:
     - keluarga
     - penduduk
     - verifikasi

### ✅ STEP 2: Konfigurasi Database Connection

1. **Edit file `includes/config.php`**
   ```
   Location: survey-kependudukan/includes/config.php
   ```

2. **Sesuaikan dengan konfigurasi Anda:**
   ```php
   define('DB_HOST', 'localhost');     // Ubah jika berbeda
   define('DB_USER', 'root');          // Ubah jika berbeda
   define('DB_PASS', '');              // Ubah password jika ada
   define('DB_NAME', 'survey_kependudukan'); // Sesuaikan nama DB
   ```

3. **Simpan file**

### ✅ STEP 3: Setup File di Web Server

1. **Untuk XAMPP:**
   ```
   Copy folder: C:\xampp\htdocs\survey-kependudukan
   Akses: http://localhost/survey-kependudukan/
   ```

2. **Untuk WAMP:**
   ```
   Copy folder: C:\wamp64\www\survey-kependudukan
   Akses: http://localhost/survey-kependudukan/
   ```

3. **Untuk LAMP (Linux):**
   ```
   Copy folder: /var/www/html/survey-kependudukan
   Akses: http://localhost/survey-kependudukan/
   ```

### ✅ STEP 4: Test Dashboard

1. **Buka Browser**
   - Ketik: http://localhost/survey-kependudukan/

2. **Verifikasi Data Muncul**
   - Total Kartu Keluarga: 5
   - Total Penduduk: 5
   - Tabel data terbaru menampilkan 5 baris

3. **Test Menu Navigation**
   - Klik menu "Penduduk"
   - Klik menu "Grafik & Analisis"
   - Klik menu "Laporan"

## Troubleshooting

### ❌ Koneksi Database Error

**Error Message:**
```
Connection failed: Unknown database 'survey_kependudukan'
```

**Solusi:**
1. Pastikan database sudah dibuat di PhpMyAdmin
2. Cek nama database di config.php
3. Cek username & password database

---

### ❌ Blank Dashboard

**Error Message:**
- Dashboard kosong, tidak ada data

**Solusi:**
1. Buka browser console (F12)
2. Lihat tab "Console" untuk error messages
3. Cek di PhpMyAdmin apakah data sudah ada
4. Refresh halaman (Ctrl+F5)

---

### ❌ Chart/Grafik Tidak Tampil

**Solusi:**
1. Cek internet connection (Chart.js dari CDN)
2. Buka console (F12) untuk error
3. Cek file `assets/js/script.js` sudah benar

---

### ❌ API 404 Error

**Error Message:**
```
GET http://localhost/survey-kependudukan/api/data.php 404 (Not Found)
```

**Solusi:**
1. Pastikan folder `api` dan file `data.php` ada
2. Cek path di `assets/js/script.js` sudah benar
3. Restart server

---

## Fitur Data Sample

Database sudah disediakan dengan data sample:
- **5 Keluarga** dengan berbagai kecamatan
- **5 Anggota Penduduk** dengan berbagai data

Anda dapat:
1. Menambah data baru melalui interface (jika sudah dibuat)
2. Edit data melalui PhpMyAdmin
3. Delete/Hapus data di PhpMyAdmin

## Struktur Folder

```
survey-kependudukan/
├── index.html                 ← Buka file ini di browser
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── script.js
├── api/
│   ├── data.php              ← API untuk data
│   └── penduduk.php          ← API untuk penduduk
├── includes/
│   └── config.php            ← ⚠️ EDIT INI untuk database config
├── database/
│   └── setup.sql             ← ⚠️ Jalankan ini di PhpMyAdmin
└── README.md
```

## Kebutuhan Sistem

- **Web Server**: Apache/Nginx
- **PHP**: 7.4 atau lebih tinggi
- **Database**: MySQL 5.7+ atau MariaDB 10.0+
- **Browser**: Chrome, Firefox, Safari, Edge (terbaru)
- **Internet**: Untuk load CDN resources (Chart.js, Font Awesome)

## Tips Penggunaan

1. **Refresh Data Manual**: Klik tombol refresh (icon ↻) di header
2. **Search**: Gunakan search box untuk mencari kartu keluarga
3. **Download Laporan**: Fitur laporan akan dikembangkan lebih lanjut
4. **Mobile Friendly**: Dashboard responsif di semua ukuran layar

## Support

Jika ada masalah:
1. Cek console browser (F12 → Console tab)
2. Cek error log di PhpMyAdmin
3. Baca kembali dokumentasi di README.md
4. Hubungi tim development

---

**Status**: ✅ Siap Digunakan
**Version**: 1.0.0
**Last Update**: Januari 2026
