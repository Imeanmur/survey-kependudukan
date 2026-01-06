# Dokumentasi Integrasi Dashboard Survey Kependudukan

## 📋 Ringkasan Perubahan

Dashboard Survey Kependudukan telah berhasil diintegrasikan dengan database `survey_kependudukan.sql` dan dilengkapi dengan berbagai visualisasi grafik garis (line chart) untuk analisis data yang lebih mendalam.

## 🔧 Perubahan yang Dilakukan

### 1. **Konfigurasi Database** (includes/config.php)
- Update kredensial database ke default XAMPP:
  - Host: localhost
  - User: root
  - Password: (kosong)
  - Database: survey_kependudukan

### 2. **API Endpoints Baru** (api/data.php)

#### Endpoints yang Ditambahkan:

| Endpoint | Deskripsi | Return Data |
|----------|-----------|------------|
| `get_grafik_verifikasi` | Status verifikasi keluarga | labels, data (count per status) |
| `get_grafik_trend_input` | Trend input data per bulan | labels (bulan), datasets (input, verifikasi) |
| `get_grafik_umur_gender` | Perbandingan umur dan gender | labels (kelompok umur), datasets (laki, perempuan) |
| `get_grafik_pendidikan` | Distribusi pendidikan | labels (tipe pendidikan), data (count) |

### 3. **Frontend Updates** (index.html)

#### Grafik Baru di Tab "Grafik & Analisis":

1. **Trend Input Data Per Bulan** 📈
   - Menampilkan grafik garis yang menunjukkan jumlah data yang diinput per bulan
   - Menampilkan perbandingan dengan data yang terverifikasi
   - Membantu melihat tren input data dan verifikasi

2. **Perbandingan Umur dan Gender** 👥
   - Grafik garis yang membandingkan distribusi laki-laki dan perempuan per kelompok umur
   - Kelompok umur: 0-5, 6-11, 12-17, 18-29, 30-44, 45-59, 60+ tahun
   - Berguna untuk analisis demografis

3. **Status Verifikasi Keluarga** ✅
   - Grafik bar yang menampilkan jumlah keluarga per status verifikasi
   - Status: Terverifikasi, Pending, Ditolak, Revisi
   - Membantu monitoring proses verifikasi

4. **Pendidikan Terakhir Penduduk** 🎓
   - Grafik horizontal bar dengan distribusi pendidikan
   - Semua tingkat pendidikan dari database
   - Membantu analisis level pendidikan populasi

5. **Grafik Lainnya** (sudah ada):
   - Distribusi Agama Penduduk (Pie Chart)
   - Top 10 Pekerjaan Penduduk (Bar Chart)
   - Perbandingan Data Keluarga per Kecamatan (Line Chart)

### 4. **JavaScript Functions** (assets/js/script.js)

#### Function Baru Ditambahkan:

```javascript
// Deklarasi variabel chart baru
let chartTrendInput, chartUmurGender, chartVerifikasi, chartPendidikan;

// Function untuk membuat grafik
createChartTrendInput(labels, datasets)    // Trend input per bulan
createChartUmurGender(labels, datasets)    // Perbandingan umur-gender
createChartVerifikasi(labels, data)        // Status verifikasi
createChartPendidikan(labels, data)        // Distribusi pendidikan
```

#### Update Function Existing:
- `loadGrafik()` - Diperluas untuk memanggil semua API endpoints baru

### 5. **CSS Improvements** (assets/css/style.css)
- Perbaikan styling untuk chart container responsif
- Optimisasi tampilan canvas pada berbagai ukuran layar

## 📊 Struktur Data Database

### Tabel Utama yang Digunakan:

1. **keluarga**
   - `id_keluarga` - ID keluarga
   - `no_kartu_keluarga` - No kartu keluarga
   - `kepala_keluarga` - Nama kepala keluarga
   - `kecamatan` - Kecamatan tempat tinggal
   - `status_verifikasi` - Status: terverifikasi, pending, ditolak, revisi
   - `tanggal_input` - Tanggal data diinput

2. **penduduk**
   - `id_penduduk` - ID penduduk
   - `id_keluarga` - ID keluarga referensi
   - `nik` - Nomor Identitas Keluarga
   - `nama_lengkap` - Nama lengkap
   - `tanggal_lahir` - Tanggal lahir (untuk perhitungan umur)
   - `jenis_kelamin` - Laki-laki/Perempuan
   - `agama` - Agama
   - `pekerjaan` - Pekerjaan
   - `pendidikan_terakhir` - Pendidikan terakhir

3. **kecamatan**
   - `id_kecamatan` - ID kecamatan
   - `nama_kecamatan` - Nama kecamatan

## 🎯 Fitur Utama Dashboard

### Dashboard Tab:
- Total Kartu Keluarga
- Total Penduduk
- Verifikasi Status (Pending, Ditolak, Terverifikasi)
- Persentase Verifikasi
- Distribusi Kecamatan
- Distribusi Agama Penduduk
- Tabel Data Terbaru

### Penduduk Tab:
- Daftar lengkap penduduk dengan filter
- Informasi NIK, Nama, No KK, Gender, Agama, Pekerjaan, Status Kawin

### Grafik & Analisis Tab:
- **Trend Input Data Per Bulan** - Melihat trend input dan verifikasi data
- **Perbandingan Umur dan Gender** - Analisis demografis penduduk
- **Distribusi Agama** - Pie chart agama penduduk
- **Status Verifikasi** - Bar chart status keluarga
- **Pendidikan Terakhir** - Horizontal bar chart
- **Top 10 Pekerjaan** - Bar chart pekerjaan terpopuler
- **Perbandingan Kecamatan** - Line chart data per kecamatan

### Laporan Tab:
- Generate laporan berdasarkan tipe, kecamatan, dan rentang tanggal
- (Fitur akan dikembangkan lebih lanjut)

## 🚀 Cara Menggunakan

### 1. Setup Database
```bash
# Pastikan XAMPP sudah running
# Import file database
1. Buka phpmyadmin (http://localhost/phpmyadmin)
2. Buat database baru atau gunakan yang sudah ada
3. Import file: database/survey_kependudukan.sql
```

### 2. Akses Dashboard
```
http://localhost/survey-kependudukan/
```

### 3. Navigasi
- Klik menu di sidebar untuk berpindah antar tab
- Klik tombol Refresh untuk memperbarui data
- Gunakan search untuk mencari data keluarga

## 📈 Visualisasi Grafik

### Jenis Grafik yang Digunakan:

1. **Line Chart** (Grafik Garis)
   - Trend Input Data Per Bulan
   - Perbandingan Umur dan Gender
   - Perbandingan Data Keluarga per Kecamatan

2. **Bar Chart** (Grafik Batang)
   - Distribusi Kecamatan
   - Status Verifikasi Keluarga
   - Top 10 Pekerjaan
   - Pendidikan Terakhir

3. **Pie/Doughnut Chart**
   - Distribusi Agama Penduduk

## 🎨 Palet Warna

```
Primary: #667eea (Biru)
Secondary: #f093fb (Pink)
Success: #43e97b (Hijau)
Warning: #ffa502 (Orange)
Danger: #f5576c (Merah)
Info: #4facfe (Biru Muda)
```

## 🔐 Keamanan

- CORS headers sudah dikonfigurasi
- SQL queries menggunakan parameterized queries untuk pencegahan SQL injection
- Password database menggunakan default XAMPP (silakan ubah di production)

## 📝 File-file yang Dimodifikasi

```
✅ includes/config.php              - Update database credentials
✅ api/data.php                    - Tambah 3 API endpoints baru
✅ index.html                      - Tambah 3 grafik baru di Grafik & Analisis tab
✅ assets/js/script.js             - Tambah 4 function chart baru + update loadGrafik()
✅ assets/css/style.css            - Perbaikan chart container styling
```

## 🐛 Troubleshooting

### Error: Connection failed
- Pastikan XAMPP sudah running
- Cek username/password di `config.php` sesuai dengan setup XAMPP
- Pastikan database `survey_kependudukan` sudah ada

### Grafik tidak tampil
- Buka browser console (F12) untuk melihat error
- Pastikan Chart.js library sudah loaded
- Pastikan API endpoint bisa diakses (cek network tab)

### Data tidak terupdate
- Klik tombol Refresh (ikon sync di header)
- Data otomatis refresh setiap 5 menit
- Cek apakah ada error di console browser

## 📚 Referensi

- Chart.js: https://www.chartjs.org/
- Bootstrap Icons: https://fontawesome.com/
- MySQL Documentation: https://dev.mysql.com/doc/

## ✨ Next Steps / Improvement Ideas

1. Tambah fitur export PDF untuk laporan
2. Tambah filter date range untuk grafik
3. Tambah map visualisasi untuk geografis kecamatan
4. Implementasi real-time data update dengan WebSocket
5. Tambah user authentication & role management
6. Optimize database queries dengan indexing
7. Tambah more advanced analytics & predictive features

---

**Last Updated:** 6 Januari 2026
**Version:** 1.0
