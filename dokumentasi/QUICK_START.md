# ✅ QUICK START - Setup Dashboard Survey Kependudukan

## 🎯 Setup Database Diskominfo ke Dashboard (5 menit)

> **UPDATE**: Dashboard sekarang dilengkapi dengan **7+ Grafik Analisis** termasuk **Line Chart Trend Data** dan **Analisis Demografis**! 📈

### Step 1️⃣: Persiapan (1 menit)
- [ ] Download/copy folder `survey-kependudukan`
- [ ] Pastikan XAMPP/WAMP sudah running
- [ ] Pastikan MySQL service aktif

### Step 2️⃣: Setup Database (2 menit)

**Via PhpMyAdmin:**
- [ ] Buka: `http://localhost/phpmyadmin`
- [ ] Login dengan user `root`
- [ ] Klik "Buat" untuk database baru
  - Database name: `survey_kependudukan`
  - Collation: `utf8mb4_unicode_ci`
  - Klik "Buat"
- [ ] Select database yang baru dibuat
- [ ] Klik tab "Import"
- [ ] Pilih file: `database/survey_kependudukan_full.sql`
- [ ] Klik "Go"
- [ ] ✅ Tunggu sampai selesai

**Atau via Command Line:**
```bash
mysql -u root -p < survey-kependudukan/database/survey_kependudukan_full.sql
```

### Step 3️⃣: Copy Project Files (1 menit)
```
Copy folder ke:
C:\xampp\htdocs\survey-kependudukan

Atau:
C:\wamp64\www\survey-kependudukan
```

### Step 4️⃣: Konfigurasi Koneksi Database (1 menit)
- [ ] Edit file: `includes/config.php`
- [ ] Pastikan konfigurasi sesuai:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');      // Ubah jika ada password
define('DB_NAME', 'survey_kependudukan');
```

### Step 5️⃣: Test Dashboard (1 menit)
- [ ] Buka browser
- [ ] Akses: `http://localhost/survey-kependudukan/`
- [ ] Verifikasi:
  - [ ] Stat cards menampilkan angka (10 keluarga, 30 penduduk)
  - [ ] Chart "Distribusi Kecamatan" menampilkan data
  - [ ] Chart "Agama Penduduk" menampilkan pie chart
  - [ ] Tabel "Data Terbaru" menampilkan data keluarga
  - [ ] Menu "Penduduk" bisa dibuka
  - [ ] Menu "Grafik & Analisis" bisa dibuka
  - [ ] Search box berfungsi

---

## 📊 Database Info

### Total Data Sample
- **Keluarga**: 10 entries
- **Penduduk**: 30 entries
- **Kecamatan**: 18 kecamatan di Medan
- **Kelurahan**: 8 kelurahan sample
- **User**: 4 default users

### Default Users
```
Username: admin
Password: admin123
Role: Administrator

Username: petugas1
Password: petugas123
Role: Petugas

Username: petugas2
Password: petugas123
Role: Petugas

Username: viewer
Password: viewer123
Role: Viewer Dashboard
```

### Database Features
✅ 7 Tables dengan relationships
✅ 4 Views untuk analytics
✅ Indexes untuk performance
✅ Foreign keys untuk data integrity
✅ Timestamps otomatis
✅ Audit trail (input_oleh, verifikasi_oleh)

---

## 🧪 Verification Queries

Jalankan di PhpMyAdmin SQL tab untuk verify:

```sql
-- 1. Total Tables
SHOW TABLES;
-- Hasil: 9 tables (7 main + 2 views)

-- 2. Keluarga Data
SELECT COUNT(*) as total_keluarga FROM keluarga;
-- Hasil: 10

-- 3. Penduduk Data
SELECT COUNT(*) as total_penduduk FROM penduduk;
-- Hasil: 30

-- 4. Kecamatan Distribution
SELECT kecamatan, COUNT(*) FROM keluarga GROUP BY kecamatan;

-- 5. Status Verifikasi
SELECT status_verifikasi, COUNT(*) FROM keluarga GROUP BY status_verifikasi;

-- 6. View Test
SELECT * FROM view_ringkasan_kecamatan LIMIT 5;
```

---

## 🚀 Features Ready to Use

### Dashboard
✅ 6 Stat Cards dengan data real-time
✅ Bar Chart - Distribusi Kecamatan
✅ Pie Chart - Agama Penduduk
✅ Tabel Data Terbaru (10 keluarga terbaru)
✅ Auto-refresh setiap 5 menit

### Menu Penduduk
✅ List semua 30 penduduk
✅ Filter & search
✅ Status badges

### Menu Grafik & Analisis
✅ Pie Chart - Distribusi Agama
✅ Horizontal Bar - Top 10 Pekerjaan
✅ Line Chart - Trend per Kecamatan

### Menu Laporan
✅ Template ready (development)
✅ Filter by kecamatan
✅ Filter by date range

---

## 📱 Responsive Design
✅ Desktop (1400px+)
✅ Tablet (768px - 1024px)
✅ Mobile (<768px)

---

## 🔧 File Structure

```
survey-kependudukan/
├── index.html                          ← Dashboard HTML
├── includes/config.php                 ← DB Config ⚠️ CONFIGURE
├── api/
│   ├── data.php                        ← Stats API
│   └── penduduk.php                    ← Penduduk API
├── assets/
│   ├── css/style.css                   ← Dashboard styling
│   └── js/script.js                    ← JavaScript logic
├── database/
│   ├── survey_kependudukan_full.sql   ← Import ini ⭐ MAIN
│   └── setup.sql                       ← Original setup
├── INSTALASI.md                        ← Installation guide
├── DATABASE_INTEGRATION.md             ← DB Integration guide
├── DATA_MAPPING.md                     ← Field mapping
├── QUICK_START.md                      ← This file
└── README.md                           ← Full documentation
```

---

## 🐛 Troubleshooting

### Data tidak muncul di dashboard
```
Solusi:
1. Refresh browser (Ctrl+F5)
2. Check database connection: config.php
3. Open F12 → Console → Lihat error
4. Run: SELECT COUNT(*) FROM keluarga;
```

### Chart tidak tampil
```
Solusi:
1. Check internet connection (CDN resources)
2. Check F12 → Console untuk error
3. Verify data ada di database
```

### Login tidak bisa (jika sudah implementasi)
```
Default credentials:
- Username: admin
- Password: admin123
```

### Foreign key error saat import
```
Solusi:
1. Pastikan MySQL version 5.7+
2. Gunakan database baru (jangan existing)
3. Check file SQL syntax
```

---

## 📈 Next Steps

### Immediate (setelah verify)
1. ✅ Backup database
2. ✅ Change admin password
3. ✅ Test semua menu & fitur

### Short Term (1 minggu)
1. Add form input/edit data
2. Implement login system
3. Add export to PDF/Excel

### Medium Term (1 bulan)
1. Add more detailed analytics
2. Implement role-based access
3. Add notification system
4. Add advanced filtering

### Long Term (ongoing)
1. Mobile app development
2. API for 3rd party integration
3. Advanced analytics & BI
4. Real-time data sync

---

## 📚 Documentation Files

| File | Purpose |
|------|---------|
| README.md | Dokumentasi lengkap |
| INSTALASI.md | Step-by-step installation |
| DATABASE_INTEGRATION.md | Database setup guide |
| DATA_MAPPING.md | Field mapping & migration |
| QUICK_START.md | **This file - Quick checklist** |

---

## ✨ Sample Data Overview

### Keluarga Sample (10 rows)
```
1. SUMAIDI (Medan Baru) - Terverifikasi
2. SUJOKO (Medan Johor) - Terverifikasi
3. SUTRISNO (Medan Selayang) - Terverifikasi
4. SUPARDI (Medan Belawan) - Terverifikasi
5. HERMAWAN (Medan Maimun) - Pending
6. BAMBANG (Medan Johor) - Terverifikasi
7. HENDRA (Medan Selayang) - Terverifikasi
8. AHMAD (Medan Baru) - Ditolak
9. TAUFIK (Medan Maimun) - Pending
10. DEDI (Medan Johor) - Terverifikasi
```

### Distribusi Agama (dari 30 penduduk)
```
Islam: 30 (100%)
Kristen: 0
Katolik: 0
Hindu: 0
Buddha: 0
Konghucu: 0
```

### Top Pekerjaan
```
1. Pelajar: 8
2. Ibu Rumah Tangga: 7
3. Karyawan Swasta: 6
4. Guru: 2
5. Perawat: 1
6. Teknisi: 1
7. Wiraswasta: 1
8. Pengusaha: 1
9. Pegawai Negeri Sipil: 1
```

---

## 🎯 Success Indicators

✅ Semua persyaratan tercapai ketika:
- [ ] Database berhasil import dengan 9 tabel
- [ ] Dashboard menampilkan stat cards dengan data
- [ ] Semua 4 menu (Dashboard, Penduduk, Grafik, Laporan) bisa diakses
- [ ] Charts menampilkan visualisasi data
- [ ] Search & filter berfungsi
- [ ] Semua 30 penduduk tampil di menu Penduduk
- [ ] Responsive design berfungsi di mobile

---

## 💡 Tips & Tricks

### Untuk Development
1. Gunakan Chrome DevTools (F12) untuk debugging
2. Check Network tab untuk API response
3. Use SQL queries di PhpMyAdmin untuk verify data

### Untuk Data Entry
1. Validate data sebelum insert
2. Gunakan dropdown untuk consistency
3. Always input required fields
4. Check duplicate NIK sebelum save

### Untuk Performance
1. Clear browser cache regularly
2. Optimize database indexes
3. Archive old data regularly
4. Monitor database size

---

## 🆘 Contact & Support

**Untuk bantuan:**
- Baca dokumentasi di file .md
- Check database integration guide
- Verify SQL queries di PhpMyAdmin
- Check API endpoints di browser F12

**Tim Development**: [Masukkan kontak Anda]

---

**Setup Status**: ✅ Ready to Execute
**Estimated Time**: 5 minutes
**Difficulty**: ⭐ Easy
**Version**: 1.0.0
**Last Updated**: Januari 2026

🚀 **Good luck! Dashboard siap digunakan!**
