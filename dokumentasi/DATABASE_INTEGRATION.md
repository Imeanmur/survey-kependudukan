# PANDUAN IMPORT DATABASE DISKOMINFO KE DASHBOARD

## 📋 Status Database Integration

Database Diskominfo telah diintegrasikan dengan struktur dashboard yang lebih lengkap dan powerful.

---

## 🚀 PILIHAN SETUP DATABASE

### OPSI 1: Import Database Baru (RECOMMENDED)

Gunakan database yang telah dioptimasi dengan semua fitur dashboard:

**File:** `database/survey_kependudukan_full.sql`

**Keuntungan:**
- ✅ Struktur optimized untuk dashboard
- ✅ Views untuk analytics built-in
- ✅ Indexes untuk performance
- ✅ Sample data 10 keluarga + 30 penduduk
- ✅ User management system
- ✅ Activity logging

**Langkah-langkah:**

1. **Buka PhpMyAdmin**
   ```
   http://localhost/phpmyadmin
   ```

2. **Buat Database Baru**
   - Klik "Buat" atau "New"
   - Nama: `survey_kependudukan`
   - Collation: `utf8mb4_unicode_ci`
   - Klik "Buat"

3. **Import SQL File**
   - Pilih database yang baru dibuat
   - Klik tab "Import"
   - Klik "Pilih File"
   - Pilih: `database/survey_kependudukan_full.sql`
   - Klik tombol "Go"
   - ✅ Database sudah terbuat dengan semua tabel dan data

4. **Verifikasi Import**
   - Refresh PhpMyAdmin
   - Lihat struktur tabel:
     - keluarga (10 rows)
     - penduduk (30 rows)
     - verifikasi
     - kecamatan
     - kelurahan
     - user
     - aktivitas

---

### OPSI 2: Merge Data Lama dengan Data Baru

Jika sudah ada database lama yang ingin dipertahankan datanya:

**Langkah-langkah:**

1. **Backup Database Lama**
   ```sql
   -- Export dulu database lama Anda
   -- PhpMyAdmin → Database Lama → Export → Save File
   ```

2. **Persiapkan Data Migration Script**
   - Lihat mapping kolom di `database/migration_guide.md`
   - Sesuaikan nama kolom yang berbeda

3. **Jalankan Migration**
   ```sql
   -- Sesuaikan query sesuai struktur database Anda
   INSERT INTO keluarga (no_kartu_keluarga, kepala_keluarga, alamat, ...)
   SELECT * FROM database_lama.keluarga;
   ```

---

## 📊 Struktur Database yang Terintegrasi

### Tabel Utama

#### 1. `keluarga` - Data Kartu Keluarga
```
Kolom penting:
- id_keluarga (Primary Key)
- no_kartu_keluarga (Unique)
- kepala_keluarga
- ibu_rumah_tangga ← NEW
- alamat, rt, rw
- kelurahan, kecamatan
- latitude, longitude ← NEW (untuk geo-tagging)
- status_verifikasi (pending, terverifikasi, ditolak, revisi)
- tanggal_input, tanggal_update ← Timestamp otomatis
- input_oleh, verifikasi_oleh ← Audit trail
```

#### 2. `penduduk` - Data Anggota Keluarga
```
Kolom penting:
- id_penduduk (Primary Key)
- id_keluarga (Foreign Key)
- nik, nama_lengkap
- jenis_kelamin, tanggal_lahir
- agama, status_perkawinan
- pendidikan_terakhir
- pekerjaan
- status_penduduk
- hubungan_keluarga
- golongan_darah ← NEW
- penyakit_kronis ← NEW (health data)
```

#### 3. `verifikasi` - Log Verifikasi
```
Kolom:
- id_verifikasi
- id_keluarga
- tanggal_verifikasi
- status, petugas_verifikasi
- catatan
- dokumen_path ← NEW (untuk attach file)
- latitude_verifikasi, longitude_verifikasi ← NEW
```

#### 4. `kecamatan` & `kelurahan` - Master Data
```
Untuk referensi dan reporting
```

#### 5. `user` - User Management
```
Kolom:
- id_user
- username, password (SHA2)
- email, nama_lengkap
- role (admin, petugas, viewer)
- status (active, inactive)
```

#### 6. `aktivitas` - Activity Logging
```
Tracking semua perubahan data untuk audit
```

---

## 📈 Views untuk Analytics (Built-in)

Database sudah include 4 views untuk reporting:

### 1. `view_ringkasan_kecamatan`
```sql
-- Total keluarga dan penduduk per kecamatan
SELECT * FROM view_ringkasan_kecamatan;
```

### 2. `view_distribusi_agama`
```sql
-- Distribusi agama dengan persentase
SELECT * FROM view_distribusi_agama;
```

### 3. `view_top_pekerjaan`
```sql
-- Top 10 pekerjaan dengan persentase
SELECT * FROM view_top_pekerjaan;
```

### 4. `view_status_verifikasi`
```sql
-- Status verifikasi dengan breakdown
SELECT * FROM view_status_verifikasi;
```

---

## 🔑 Default Login Credentials

Database sudah include 4 user siap pakai:

| Username | Password | Role | Email |
|----------|----------|------|-------|
| admin | admin123 | Admin | admin@diskominfo.medan.go.id |
| petugas1 | petugas123 | Petugas | petugas1@diskominfo.medan.go.id |
| petugas2 | petugas123 | Petugas | petugas2@diskominfo.medan.go.id |
| viewer | viewer123 | Viewer | viewer@diskominfo.medan.go.id |

**Catatan:** Password di-hash dengan SHA2, ubah setelah login pertama kali!

---

## ✅ Verifikasi Import Berhasil

Setelah import, run queries berikut untuk verifikasi:

```sql
-- 1. Jumlah tabel
SHOW TABLES;
-- Hasil: 9 tables

-- 2. Jumlah keluarga
SELECT COUNT(*) FROM keluarga;
-- Hasil: 10 rows

-- 3. Jumlah penduduk
SELECT COUNT(*) FROM penduduk;
-- Hasil: 30 rows

-- 4. Status verifikasi breakdown
SELECT status_verifikasi, COUNT(*) FROM keluarga GROUP BY status_verifikasi;

-- 5. Distribusi agama
SELECT agama, COUNT(*) FROM penduduk GROUP BY agama;

-- 6. Data per kecamatan
SELECT * FROM view_ringkasan_kecamatan;
```

---

## 🔄 API Integration

Setelah database setup, semua API endpoints otomatis tersedia:

### Dashboard Stats
```
GET /api/data.php?action=get_stats
```
Response akan include:
- total_kartu
- total_penduduk
- verifikasi_pending, terverifikasi, ditolak, revisi
- total_kecamatan

### Charts & Grafik
```
GET /api/data.php?action=get_data_by_kecamatan
GET /api/data.php?action=get_grafik_agama
GET /api/data.php?action=get_grafik_pekerjaan
GET /api/data.php?action=get_grafik_verifikasi ← NEW
```

### Search & Filter
```
GET /api/data.php?action=search_keluarga&search=query
GET /api/data.php?action=get_kecamatan_list
```

---

## ⚙️ Konfigurasi Database Connection

File: `includes/config.php`

Pastikan sudah sesuai:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // Ubah jika ada password
define('DB_NAME', 'survey_kependudukan');
```

---

## 🎯 Next Steps

Setelah database setup:

1. ✅ Test dashboard di browser: `http://localhost/survey-kependudukan/`
2. ✅ Verifikasi semua data muncul di dashboard
3. ✅ Test menu Penduduk melihat 30 data
4. ✅ Test grafik sudah terisi data
5. ✅ Test search functionality
6. ✅ Test filter by kecamatan

---

## 📝 Data Migration Best Practices

Jika migrate dari database lama:

1. **Backup terlebih dahulu**
   ```sql
   mysqldump -u root survey_kependudukan > backup.sql
   ```

2. **Validate data integrity**
   - Check foreign key relationships
   - Validate NIK format (16 digit)
   - Verify no duplicates

3. **Run validation queries**
   ```sql
   -- Check orphaned penduduk
   SELECT * FROM penduduk p 
   WHERE p.id_keluarga NOT IN (SELECT id_keluarga FROM keluarga);
   
   -- Check duplicate NIK
   SELECT nik, COUNT(*) FROM penduduk GROUP BY nik HAVING COUNT(*) > 1;
   ```

---

## 🆘 Troubleshooting

### Import Error: "Unknown Character Set"
- Change collation di PhpMyAdmin saat create database
- Pilih: `utf8mb4_unicode_ci`

### Foreign Key Constraint Error
- MySQL version harus 5.7+
- Pastikan tabel parent created terlebih dahulu (sudah otomatis di SQL script)

### Views Error
- Pastikan semua tabel sudah terbuat
- Run views creation script terpisah jika perlu

### Data tidak muncul di dashboard
- Clear browser cache (Ctrl+Shift+Delete)
- Check API response di browser F12 → Network tab
- Verify database connection di `config.php`

---

## 📞 Support

Untuk masalah setup database, hubungi:
- **Tim Development**: [Kontak Anda]
- **Documentation**: Baca README.md dan INSTALASI.md

---

**Status**: ✅ Ready for Integration
**Database Version**: 1.0.0
**Last Updated**: Januari 2026
