# DOKUMENTASI SURVEY KEPENDUDUKAN - DASHBOARD

## 📋 Daftar Isi
1. [Overview Sistem](#overview-sistem)
2. [Instalasi dan Setup](#instalasi-dan-setup)
3. [Struktur Database](#struktur-database)
4. [Fitur Dashboard](#fitur-dashboard)
5. [Chart dan Grafik](#chart-dan-grafik)
6. [API Endpoints](#api-endpoints)
7. [Panduan Penggunaan](#panduan-penggunaan)
8. [Troubleshooting](#troubleshooting)

---

## Overview Sistem

### Apa itu Survey Kependudukan Dashboard?
Sistem dashboard interaktif untuk mengelola data survey kependudukan Kota Medan dengan fitur:
- **Real-time Analytics** - Visualisasi data dengan Chart.js
- **Data Management** - CRUD operations untuk keluarga dan penduduk
- **Multiple Charts** - 9 chart berbeda untuk analisis komprehensif
- **Responsive Design** - Bekerja di desktop, tablet, dan mobile

### Tech Stack
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Backend**: PHP 7.x + MySQLi
- **Charting**: Chart.js 3.9.1
- **Icons**: FontAwesome 6.4.0
- **Server**: XAMPP (Apache + MySQL)

### System Requirements
- PHP 7.2+
- MySQL 5.7+
- 512 MB RAM minimum
- Modern browser (Chrome, Firefox, Safari, Edge)

---

## Instalasi dan Setup

### 1. Setup Database

**Opsi A: Menggunakan setup.sql (Rekomendasi)**
```bash
mysql -u root survey_kependudukan < database/setup.sql
```

**Opsi B: Menggunakan survey_kependudukan.sql (Full data)**
```bash
mysql -u root survey_kependudukan < database/survey_kependudukan.sql
```

### 2. Generate Data Dummy
Untuk development dan testing:
```bash
# Via Browser
http://localhost/survey-kependudukan/generate_data.php
```

Hasilnya:
- 55 keluarga records
- 111 penduduk records
- Tersebar di 5 kecamatan berbeda

### 3. Konfigurasi Database
Edit `includes/config.php` jika perlu mengubah:
```php
// Database credentials
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'survey_kependudukan';
```

### 4. Akses Dashboard
```
http://localhost/survey-kependudukan/
```

---

## Struktur Database

### Tabel: keluarga
```sql
CREATE TABLE keluarga (
    id_keluarga INT PRIMARY KEY AUTO_INCREMENT,
    no_kartu_keluarga VARCHAR(16) NOT NULL UNIQUE,
    kepala_keluarga VARCHAR(100) NOT NULL,
    alamat TEXT NOT NULL,
    kelurahan VARCHAR(100),
    kecamatan VARCHAR(100),
    rt VARCHAR(3),
    rw VARCHAR(3),
    tanggal_input DATETIME DEFAULT CURRENT_TIMESTAMP,
    status_verifikasi ENUM('pending','terverifikasi','ditolak'),
    keterangan TEXT
);
```

| Field | Type | Keterangan |
|-------|------|-----------|
| id_keluarga | INT | Primary key |
| no_kartu_keluarga | VARCHAR(16) | Nomor KK unik |
| kepala_keluarga | VARCHAR(100) | Nama kepala keluarga |
| kecamatan | VARCHAR(100) | Wilayah kecamatan |
| status_verifikasi | ENUM | pending/terverifikasi/ditolak |
| tanggal_input | DATETIME | Timestamp pencatatan |

### Tabel: penduduk
```sql
CREATE TABLE penduduk (
    id_penduduk INT PRIMARY KEY AUTO_INCREMENT,
    id_keluarga INT NOT NULL,
    nik VARCHAR(16) NOT NULL UNIQUE,
    nama_lengkap VARCHAR(100) NOT NULL,
    jenis_kelamin ENUM('Laki-laki','Perempuan'),
    tempat_lahir VARCHAR(100),
    tanggal_lahir DATE,
    agama ENUM('Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'),
    status_perkawinan ENUM('Belum Kawin','Kawin','Cerai Hidup','Cerai Mati'),
    pendidikan_terakhir VARCHAR(50),
    pekerjaan VARCHAR(100),
    status_penduduk ENUM('Tetap','Sementara'),
    hubungan_keluarga VARCHAR(50),
    tanggal_input DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_keluarga) REFERENCES keluarga(id_keluarga)
);
```

### Tabel: verifikasi
```sql
CREATE TABLE verifikasi (
    id_verifikasi INT PRIMARY KEY AUTO_INCREMENT,
    id_keluarga INT NOT NULL,
    tanggal_verifikasi DATETIME DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50),
    petugas_verifikasi VARCHAR(100),
    catatan TEXT,
    FOREIGN KEY (id_keluarga) REFERENCES keluarga(id_keluarga)
);
```

---

## Fitur Dashboard

### 1. Dashboard Tab
**Statistik Ringkas**
- Total Kartu Keluarga
- Total Penduduk
- Verifikasi Pending, Terverifikasi, Ditolak
- Persentase Verifikasi

**Charts**
- **Distribusi Kecamatan** (Bar Chart) - Total keluarga per kecamatan
- **Agama Penduduk** (Doughnut Chart) - Distribusi agama

**Data Terbaru**
- Tabel 20 record terakhir dengan informasi:
  - No. KK
  - Kepala Keluarga
  - Kelurahan
  - Kecamatan
  - Jumlah Anggota
  - Status Verifikasi

### 2. Grafik & Analisis Tab (7 Charts)
1. **Trend Input Data Per Bulan** (Line Chart)
   - Menampilkan perbandingan input dan verifikasi per bulan
   
2. **Perbandingan Umur & Gender** (Line Chart)
   - Grafik perbandingan dua series

3. **Distribusi Agama Penduduk** (Pie Chart)
   - Breakdown agama semua penduduk

4. **Status Verifikasi Keluarga** (Bar Chart)
   - Pending, Terverifikasi, Ditolak

5. **Pendidikan Terakhir Penduduk** (Horizontal Bar)
   - Distribusi jenjang pendidikan

6. **Top 10 Pekerjaan Penduduk** (Horizontal Bar)
   - Pekerjaan terbanyak

7. **Kecamatan Detail** (Line Chart)
   - Perbandingan keluarga per kecamatan

### 3. Penduduk Tab
- Daftar lengkap semua penduduk
- Search/filter penduduk
- Informasi detail (NIK, umur, agama, pendidikan, pekerjaan)

### 4. Laporan Tab
- Generate laporan PDF
- Filter berdasarkan kecamatan
- Export data

---

## Chart dan Grafik

### Teknologi
- **Library**: Chart.js 3.9.1
- **Animasi**: Custom CSS animations
- **Responsive**: Mobile-friendly

### Jenis Chart yang Tersedia

| Chart | Tipe | Lokasi | Fungsi |
|-------|------|--------|--------|
| Kecamatan | Bar | Dashboard | Distribusi keluarga |
| Agama | Doughnut | Dashboard | Breakdown agama |
| Trend Input | Line | Grafik Tab | Tren temporal |
| Umur-Gender | Line | Grafik Tab | Perbandingan series |
| Agama Full | Pie | Grafik Tab | Detail agama |
| Verifikasi | Bar | Grafik Tab | Status verifikasi |
| Pendidikan | H-Bar | Grafik Tab | Distribusi pendidikan |
| Pekerjaan | H-Bar | Grafik Tab | Top 10 pekerjaan |
| Kecamatan Detail | Line | Grafik Tab | Detail per kecamatan |

### Animasi
Setiap chart memiliki animasi smooth:
- **Chart Slide-In**: Slide dari bawah (0.6s)
- **Chart Fade-In**: Fade masuk (0.8s)
- **Data Animation**: Data fill animation (1000ms)
- **Point Hover**: Interactive point effects

---

## API Endpoints

### Base URL
```
http://localhost/survey-kependudukan/api/
```

### Endpoints

#### 1. Get Statistics
```
GET /data.php?action=get_stats
```
**Response:**
```json
{
  "success": true,
  "data": {
    "total_kartu": "55",
    "total_penduduk": "111",
    "verifikasi_pending": "15",
    "verifikasi_terverifikasi": "30",
    "verifikasi_ditolak": "10",
    "verifikasi_revisi": "0",
    "total_kecamatan": "5"
  }
}
```

#### 2. Get Data Terbaru
```
GET /data.php?action=get_data_terbaru&limit=20
```
Returns: 20 latest keluarga records dengan jumlah anggota

#### 3. Get Data by Kecamatan
```
GET /data.php?action=get_data_by_kecamatan
```
**Response:**
```json
{
  "success": true,
  "data": [
    {
      "kecamatan": "MEDAN BARU",
      "total_kartu": "15",
      "total_penduduk": "35"
    }
  ]
}
```

#### 4. Get Chart: Agama
```
GET /data.php?action=get_grafik_agama
```
**Response:**
```json
{
  "success": true,
  "labels": ["Islam", "Kristen", "Katolik"],
  "data": [80, 20, 10]
}
```

#### 5. Get Chart: Trend Input
```
GET /data.php?action=get_grafik_trend_input
```
**Response:**
```json
{
  "success": true,
  "labels": ["January", "February", "March"],
  "datasets": {
    "input": [50, 60, 55],
    "verifikasi": [40, 55, 50]
  }
}
```

#### 6. Get Chart: Umur & Gender
```
GET /data.php?action=get_grafik_umur_gender
```
Returns dataset dengan dua series (Laki-laki, Perempuan)

#### 7. Get Chart: Pendidikan
```
GET /data.php?action=get_grafik_pendidikan
```
Returns distribusi pendidikan terakhir

#### 8. Get Chart: Pekerjaan
```
GET /data.php?action=get_grafik_pekerjaan
```
Returns top 10 pekerjaan

#### 9. Get Chart: Verifikasi
```
GET /data.php?action=get_grafik_verifikasi
```
Returns status verifikasi counts

#### 10. Search Keluarga
```
GET /data.php?action=search_keluarga&q=SUMAIDI
```
Returns matching keluarga records

---

## Panduan Penggunaan

### Mengakses Dashboard

1. **Buka Browser**
   ```
   http://localhost/survey-kependudukan/
   ```

2. **Interface Utama**
   - **Sidebar Kiri**: Menu navigasi
   - **Content Area**: Konten utama
   - **Top Bar**: Search & refresh button

### Navigasi Menu

**Dashboard Tab**
- Statistik ringkas
- 2 charts utama
- Tabel data terbaru

**Grafik & Analisis Tab**
- 7 charts detail
- Animasi smooth
- Interactive legend

**Penduduk Tab**
- Daftar lengkap penduduk
- Search functionality
- Sorting & filtering

**Laporan Tab**
- Generate PDF
- Custom filters
- Export options

### Search Function
1. Gunakan search box di top
2. Ketik nomor KK atau nama kepala keluarga
3. Hasil otomatis filter

### Interpretasi Chart

**Line Chart**
- X-axis: Time/Category
- Y-axis: Value
- Hover untuk lihat detail

**Bar Chart**
- Tinggi bar: Value
- Legend: Series
- Click legend untuk show/hide series

**Pie/Doughnut**
- Segment size: Proporsi
- Click legend untuk highlight

---

## Troubleshooting

### Chart Tidak Muncul

**Kemungkinan Penyebab & Solusi:**

1. **Chart.js belum load**
   ```
   Solusi: Cek browser console (F12), tunggu 2-3 detik, refresh
   ```

2. **API error**
   ```
   Solusi: Cek API response di Network tab
   GET /api/data.php?action=... harus return 200 OK
   ```

3. **Database kosong**
   ```
   Solusi: Jalankan generate_data.php atau import SQL
   ```

### Data Tidak Tampil

**Solusi:**
1. Refresh browser (Ctrl+F5)
2. Cek database connection di `includes/config.php`
3. Buka console (F12) cek error message

### API Return Error

**Debugging:**
```javascript
// Cek di browser console
fetch('./api/data.php?action=get_data_by_kecamatan')
  .then(r => r.json())
  .then(d => console.log(d))
  .catch(e => console.error(e))
```

### Chart Tidak Responsive

**Solusi:**
- Pastikan CSS sudah loaded (check Network tab)
- Chart container harus punya explicit height
- Browser cache: Ctrl+F5 (hard refresh)

---

## File Structure

```
survey-kependudukan/
├── index.html                 # Main HTML file
├── includes/
│   └── config.php            # Database config
├── api/
│   ├── data.php              # Chart & data API
│   └── penduduk.php          # Penduduk API (optional)
├── assets/
│   ├── css/
│   │   └── style.css         # Main stylesheet
│   └── js/
│       └── script.js         # JavaScript logic
├── database/
│   ├── setup.sql             # Simple setup
│   ├── survey_kependudukan.sql
│   └── survey_kependudukan_full.sql
├── dokumentasi/              # Documentation folder
│   └── README.md
└── [other files]
```

---

## Performance Tips

1. **Optimize Database**
   - Create indexes on frequently queried columns
   - Use LIMIT untuk pagination

2. **Cache Charts**
   - Chart data bisa di-cache di localStorage
   - Refresh setiap 5 menit

3. **Optimize Images**
   - Use SVG untuk icons
   - Compress images

4. **Network Optimization**
   - Minimize CSS/JS files
   - Enable gzip compression
   - Use CDN untuk library

---

## Support & Contact

Untuk pertanyaan atau laporan bug:
- Email: diskominfo@medan.go.id
- Dokumentasi lengkap: Folder `dokumentasi/`

---

**Version**: 1.0  
**Last Updated**: January 6, 2026  
**Status**: Production Ready ✅
