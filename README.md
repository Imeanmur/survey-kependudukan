# Dashboard Survey Kependudukan - Diskominfo Pemkot Medan

Dashboard modern dan interaktif untuk mengelola data survey kependudukan Kota Medan dengan koneksi database PhpMyAdmin.

## 📋 Fitur Utama

### Dashboard
- **Statistik Real-time**: Total Kartu Keluarga, Total Penduduk, Status Verifikasi
- **Visualisasi Data**: Chart distribusi kecamatan, agama penduduk
- **Data Terbaru**: Tabel data keluarga yang baru diinput
- **Refresh Otomatis**: Data diperbarui setiap 5 menit

### Menu Penduduk
- Daftar lengkap data penduduk
- Pencarian berdasarkan NIK, Nama, atau data lainnya
- Informasi detail setiap penduduk
- Pagination untuk performa optimal

### Menu Grafik & Analisis
- Distribusi Agama Penduduk (Pie Chart)
- Top 10 Pekerjaan Penduduk (Horizontal Bar Chart)
- Perbandingan Kartu Keluarga vs Penduduk per Kecamatan (Line Chart)
- Export data dalam berbagai format

### Menu Laporan
- Generate laporan custom berdasarkan:
  - Tipe laporan (Keluarga, Penduduk, Statistik)
  - Kecamatan spesifik
  - Range tanggal
  - Download PDF

## 🛠️ Teknologi yang Digunakan

- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Backend**: PHP 7.4+
- **Database**: MySQL/MariaDB
- **Chart Library**: Chart.js 3.9.1
- **Icons**: Font Awesome 6.4.0
- **Responsive**: Mobile-first Design

## 📁 Struktur File

```
survey-kependudukan/
├── index.html                 # Main dashboard page
├── assets/
│   ├── css/
│   │   └── style.css         # Main stylesheet dengan responsive design
│   └── js/
│       └── script.js         # JavaScript untuk interaktivitas
├── api/
│   ├── data.php              # API untuk data statistik & grafik
│   └── penduduk.php          # API untuk data penduduk
├── includes/
│   └── config.php            # Database connection configuration
└── database/
    └── setup.sql             # Database schema & sample data
```

## 🚀 Cara Instalasi & Setup

### 1. Persiapan Environment
- Pastikan XAMPP/WAMP/LAMP sudah terinstall
- PHP versi 7.4 atau lebih tinggi
- MySQL/MariaDB aktif

### 2. Clone/Copy Project
```bash
# Copy folder ke htdocs (XAMPP)
cp -r survey-kependudukan C:\xampp\htdocs\
# atau ke www (WAMP)
cp -r survey-kependudukan C:\wamp64\www\
```

### 3. Setup Database

**Cara 1: Menggunakan PhpMyAdmin**
1. Buka http://localhost/phpmyadmin
2. Login dengan user root
3. Buat database baru: `survey_kependudukan`
4. Pilih database yang baru dibuat
5. Klik tab SQL
6. Copy-paste isi file `database/setup.sql`
7. Klik "Go" untuk menjalankan query

**Cara 2: Command Line**
```bash
mysql -u root -p < database/setup.sql
```

### 4. Konfigurasi Database
Edit file `includes/config.php` sesuai konfigurasi database Anda:

```php
define('DB_HOST', 'localhost');     // Host database
define('DB_USER', 'root');          // Username database
define('DB_PASS', '');              // Password database (default kosong)
define('DB_NAME', 'survey_kependudukan'); // Nama database
```

### 5. Jalankan Dashboard
1. Buka browser dan akses: `http://localhost/survey-kependudukan/`
2. Dashboard siap digunakan!

## 📊 Struktur Database

### Tabel keluarga
Menyimpan data keluarga/kartu keluarga
- `id_keluarga` (Primary Key)
- `no_kartu_keluarga` (Unique)
- `kepala_keluarga`
- `alamat`
- `kelurahan`, `kecamatan`, `rt`, `rw`
- `status_verifikasi` (pending, terverifikasi, ditolak)
- `tanggal_input`

### Tabel penduduk
Menyimpan data anggota keluarga
- `id_penduduk` (Primary Key)
- `id_keluarga` (Foreign Key)
- `nik` (Unique)
- `nama_lengkap`
- `jenis_kelamin`
- `tempat_lahir`, `tanggal_lahir`
- `agama`, `status_perkawinan`
- `pendidikan_terakhir`, `pekerjaan`
- `hubungan_keluarga`

### Tabel verifikasi
Menyimpan log verifikasi data
- `id_verifikasi` (Primary Key)
- `id_keluarga` (Foreign Key)
- `tanggal_verifikasi`
- `status`
- `petugas_verifikasi`
- `catatan`

## 🎨 Fitur Desain

### Color Scheme
- **Primary**: Blue (#0066ff)
- **Secondary**: Cyan (#00d4ff)
- **Success**: Green (#00d084)
- **Warning**: Orange (#ffa500)
- **Danger**: Red (#ff6b6b)

### UI Components
- Gradient backgrounds untuk stat cards
- Hover effects untuk interaktivitas
- Responsive layout (Desktop, Tablet, Mobile)
- Smooth animations & transitions
- Custom scrollbar styling

### Layout Responsif
- **Desktop**: Full sidebar + content
- **Tablet**: Collapsible sidebar
- **Mobile**: Hidden sidebar (toggle button), optimized content

## 📱 API Endpoints

### Data API (`api/data.php`)

**Get Statistics**
```
GET /api/data.php?action=get_stats
```
Response:
```json
{
  "success": true,
  "data": {
    "total_kartu": 66,
    "total_penduduk": 86,
    "verifikasi_pending": 5,
    "verifikasi_ditolak": 0,
    "verifikasi_terverifikasi": 61
  }
}
```

**Get Latest Data**
```
GET /api/data.php?action=get_data_terbaru&limit=10
```

**Search Keluarga**
```
GET /api/data.php?action=search_keluarga&search=query
```

**Get Data by Kecamatan**
```
GET /api/data.php?action=get_data_by_kecamatan
```

**Get Chart Agama**
```
GET /api/data.php?action=get_grafik_agama
```

**Get Chart Pekerjaan**
```
GET /api/data.php?action=get_grafik_pekerjaan
```

### Penduduk API (`api/penduduk.php`)

**Get All Penduduk**
```
GET /api/penduduk.php?action=get_penduduk&limit=50&offset=0
```

**Get Penduduk by Keluarga**
```
GET /api/penduduk.php?action=get_penduduk_by_keluarga&id_keluarga=1
```

## 🔧 Customization

### Mengubah Warna
Edit file `assets/css/style.css`, bagian `:root`:
```css
:root {
    --primary: #0066ff;      /* Ubah warna primary */
    --secondary: #00d4ff;    /* Ubah warna secondary */
    --success: #00d084;      /* Ubah warna success */
    /* ... dll */
}
```

### Menambah Menu Baru
1. Edit `index.html` - tambah item di sidebar
2. Edit `assets/js/script.js` - tambah handler untuk menu baru
3. Buat file API baru di folder `api/` jika diperlukan

### Mengubah Periode Refresh
Edit `assets/js/script.js`:
```javascript
// Refresh data every 5 minutes (ubah nilai dalam ms)
setInterval(loadDashboard, 300000); // 300000 ms = 5 menit
```

## 🐛 Troubleshooting

### Database Connection Error
- Cek konfigurasi `includes/config.php`
- Pastikan MySQL service sudah berjalan
- Pastikan database `survey_kependudukan` sudah dibuat

### Data tidak muncul di dashboard
- Refresh browser (Ctrl+F5)
- Cek browser console (F12) untuk error message
- Pastikan data sudah diinput di PhpMyAdmin

### Chart tidak tampil
- Pastikan internet connection aktif (untuk load Chart.js dari CDN)
- Cek browser console untuk error
- Pastikan canvas element ada di HTML

## 📝 Lisensi

Proyek ini dibuat untuk Diskominfo Pemkot Medan.

## 👨‍💼 Support & Maintenance

Untuk pertanyaan atau issues, hubungi:
- **Tim Development**: [kontak anda]
- **Dokumentasi**: Lihat file ini untuk referensi

---

**Last Updated**: Januari 2026
**Version**: 1.0.0
