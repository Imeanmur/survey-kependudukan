# DATA MAPPING GUIDE - Integrasi Database Diskominfo

## 📋 Ringkasan Integrasi

Database dari Diskominfo telah diintegrasikan dan ditingkatkan dengan struktur yang lebih comprehensive untuk mendukung semua fitur dashboard yang lebih powerful.

---

## 🔄 Field Mapping Table

### Tabel KELUARGA

| Database Diskominfo | Database Baru | Tipe Data | Keterangan |
|-------------------|---------------|-----------|-----------|
| `no_kartu_keluarga` | `no_kartu_keluarga` | VARCHAR(16) | ✅ Same |
| `kepala_keluarga` | `kepala_keluarga` | VARCHAR(100) | ✅ Same |
| `alamat` | `alamat` | TEXT | ✅ Same |
| `rt` | `rt` | VARCHAR(3) | ✅ Same |
| `rw` | `rw` | VARCHAR(3) | ✅ Same |
| `kelurahan` | `kelurahan` | VARCHAR(100) | ✅ Same |
| `kecamatan` | `kecamatan` | VARCHAR(100) | ✅ Same |
| - | `nik_kepala_keluarga` | VARCHAR(16) | ✨ NEW |
| - | `ibu_rumah_tangga` | VARCHAR(100) | ✨ NEW |
| - | `latitude` | DECIMAL(10,8) | ✨ NEW (GPS) |
| - | `longitude` | DECIMAL(11,8) | ✨ NEW (GPS) |
| - | `provinsi` | VARCHAR(100) | ✨ NEW |
| - | `kota` | VARCHAR(100) | ✨ NEW |
| - | `input_oleh` | VARCHAR(100) | ✨ NEW (Audit) |
| - | `verifikasi_oleh` | VARCHAR(100) | ✨ NEW (Audit) |
| - | `tanggal_verifikasi` | DATETIME | ✨ NEW |
| - | `tanggal_update` | DATETIME | ✨ NEW (Auto) |
| - | `keterangan` | TEXT | ✨ NEW |

### Tabel PENDUDUK

| Database Diskominfo | Database Baru | Tipe Data | Keterangan |
|-------------------|---------------|-----------|-----------|
| `nik` | `nik` | VARCHAR(16) | ✅ Same |
| `nama_lengkap` | `nama_lengkap` | VARCHAR(100) | ✅ Same |
| `jenis_kelamin` | `jenis_kelamin` | ENUM | ✅ Same |
| `tempat_lahir` | `tempat_lahir` | VARCHAR(100) | ✅ Same |
| `tanggal_lahir` | `tanggal_lahir` | DATE | ✅ Same |
| `agama` | `agama` | ENUM | ✅ Same |
| `status_perkawinan` | `status_perkawinan` | ENUM | ✅ Same |
| `pendidikan_terakhir` | `pendidikan_terakhir` | VARCHAR(100) | ✅ Same |
| `pekerjaan` | `pekerjaan` | VARCHAR(100) | ✅ Same |
| `status_penduduk` | `status_penduduk` | ENUM | ✅ Same |
| `hubungan_keluarga` | `hubungan_keluarga` | VARCHAR(50) | ✅ Same |
| - | `golongan_darah` | VARCHAR(2) | ✨ NEW |
| - | `penyakit_kronis` | TEXT | ✨ NEW (Health) |
| - | `keterangan` | TEXT | ✨ NEW |
| - | `tanggal_update` | DATETIME | ✨ NEW (Auto) |

### Tabel VERIFIKASI

| Database Diskominfo | Database Baru | Tipe Data | Keterangan |
|-------------------|---------------|-----------|-----------|
| `id_verifikasi` | `id_verifikasi` | INT | ✅ Same |
| `id_keluarga` | `id_keluarga` | INT | ✅ Same (FK) |
| `tanggal_verifikasi` | `tanggal_verifikasi` | DATETIME | ✅ Same |
| `status` | `status` | VARCHAR(50) | ✅ Same |
| `petugas_verifikasi` | `petugas_verifikasi` | VARCHAR(100) | ✅ Same |
| `catatan` | `catatan` | TEXT | ✅ Same |
| - | `dokumen_path` | VARCHAR(255) | ✨ NEW (Upload) |
| - | `latitude_verifikasi` | DECIMAL(10,8) | ✨ NEW (GPS) |
| - | `longitude_verifikasi` | DECIMAL(11,8) | ✨ NEW (GPS) |

---

## 📊 Tabel Baru Tambahan

### Tabel KECAMATAN (Master Data)
```sql
id_kecamatan (PK)
nama_kecamatan (UNIQUE)
kode_kecamatan
```
**Fungsi:** Reference data untuk konsistensi input

### Tabel KELURAHAN (Master Data)
```sql
id_kelurahan (PK)
nama_kelurahan
kecamatan_id (FK)
kode_kelurahan
```
**Fungsi:** Reference data untuk dropdown

### Tabel USER (User Management)
```sql
id_user (PK)
username, password (SHA2)
email, nama_lengkap
role (admin, petugas, viewer)
status (active, inactive)
```
**Fungsi:** Manajemen user untuk login sistem

### Tabel AKTIVITAS (Audit Log)
```sql
id_aktivitas (PK)
id_user (FK)
tipe_aktivitas
deskripsi, tabel_terkait, id_record
tanggal_aktivitas
```
**Fungsi:** Log semua perubahan data

---

## 🔗 Relationship Diagram

```
┌─────────────────┐
│   kecamatan     │
│  (Master Data)  │
└────────┬────────┘
         │
         │ 1:N
         ↓
┌─────────────────────────┐
│      keluarga           │         ┌──────────────┐
│ (Kartu Keluarga)        ├────────→│  user        │
│                         │ input   │ (Input By)   │
└────────┬────────────────┘         └──────────────┘
         │
         │ 1:N
         ↓
┌─────────────────┐
│    penduduk     │
│ (Anggota KK)    │
└─────────────────┘

┌─────────────────────────┐
│      keluarga           │
└────────┬────────────────┘
         │
         │ 1:N
         ↓
┌─────────────────┐
│   verifikasi    │         ┌──────────────┐
│ (Audit Log)     ├────────→│  user        │
│                 │ verify  │ (Verified By)│
└─────────────────┘         └──────────────┘

┌─────────────────────────┐
│   user                  │
└────────┬────────────────┘
         │
         │ 1:N
         ↓
┌─────────────────┐
│   aktivitas     │
│ (Activity Log)  │
└─────────────────┘
```

---

## 🔄 STATUS FIELDS MAPPING

### Status Verifikasi
```
LAMA (dari Diskominfo):
- pending ✅
- terverifikasi ✅
- ditolak ✅

BARU (added):
- revisi (untuk perubahan status)
```

### Agama (standardized)
```
ENUM values:
'Islam'
'Kristen'
'Katolik'
'Hindu'
'Buddha'
'Konghucu'
'Lainnya'
```

### Jenis Kelamin
```
ENUM values:
'Laki-laki'
'Perempuan'
```

### Status Perkawinan
```
ENUM values:
'Belum Kawin'
'Kawin'
'Cerai Hidup'
'Cerai Mati'
```

### Status Penduduk
```
ENUM values:
'Tetap'
'Sementara'
'Hilang' ← NEW
'Mati' ← NEW
```

### User Role
```
ENUM values:
'admin'      - Full access
'petugas'    - Input & verify data
'viewer'     - Read only
```

---

## 📈 View Analytics (untuk reporting)

Sudah ada 4 views siap pakai:

### 1. view_ringkasan_kecamatan
```sql
SELECT 
  nama_kecamatan,
  total_keluarga,
  total_penduduk,
  terverifikasi,
  pending,
  ditolak
FROM view_ringkasan_kecamatan;
```

### 2. view_distribusi_agama
```sql
SELECT 
  agama,
  jumlah,
  persentase
FROM view_distribusi_agama;
```

### 3. view_top_pekerjaan
```sql
SELECT 
  pekerjaan,
  jumlah,
  persentase
FROM view_top_pekerjaan
LIMIT 10;
```

### 4. view_status_verifikasi
```sql
SELECT 
  status_verifikasi,
  jumlah,
  persentase
FROM view_status_verifikasi;
```

---

## 🛠️ Migration Script Template

Jika ingin migrate dari database lama:

```sql
-- 1. Disable foreign key untuk sementara
SET FOREIGN_KEY_CHECKS = 0;

-- 2. Insert data keluarga
INSERT INTO survey_kependudukan.keluarga (
  no_kartu_keluarga,
  kepala_keluarga,
  alamat,
  rt, rw,
  kelurahan,
  kecamatan,
  status_verifikasi
)
SELECT 
  no_kartu_keluarga,
  kepala_keluarga,
  alamat,
  rt, rw,
  kelurahan,
  kecamatan,
  'pending' as status_verifikasi
FROM database_lama.keluarga;

-- 3. Insert data penduduk
INSERT INTO survey_kependudukan.penduduk (
  id_keluarga,
  nik,
  nama_lengkap,
  jenis_kelamin,
  tempat_lahir,
  tanggal_lahir,
  agama,
  status_perkawinan,
  pendidikan_terakhir,
  pekerjaan,
  status_penduduk,
  hubungan_keluarga
)
SELECT 
  k.id_keluarga,
  p.nik,
  p.nama_lengkap,
  p.jenis_kelamin,
  p.tempat_lahir,
  p.tanggal_lahir,
  p.agama,
  p.status_perkawinan,
  p.pendidikan_terakhir,
  p.pekerjaan,
  p.status_penduduk,
  p.hubungan_keluarga
FROM database_lama.penduduk p
JOIN survey_kependudukan.keluarga k ON p.no_kartu_keluarga = k.no_kartu_keluarga;

-- 4. Enable foreign key kembali
SET FOREIGN_KEY_CHECKS = 1;

-- 5. Verify hasil
SELECT COUNT(*) as total_keluarga FROM survey_kependudukan.keluarga;
SELECT COUNT(*) as total_penduduk FROM survey_kependudukan.penduduk;
```

---

## 🔐 Database Integrity Checks

Jalankan queries berikut untuk verifikasi data integrity:

```sql
-- 1. Check no_kartu_keluarga uniqueness
SELECT no_kartu_keluarga, COUNT(*) 
FROM keluarga 
GROUP BY no_kartu_keluarga 
HAVING COUNT(*) > 1;

-- 2. Check NIK uniqueness
SELECT nik, COUNT(*) 
FROM penduduk 
GROUP BY nik 
HAVING COUNT(*) > 1;

-- 3. Check orphaned penduduk (no keluarga)
SELECT * FROM penduduk 
WHERE id_keluarga NOT IN (SELECT id_keluarga FROM keluarga);

-- 4. Check empty required fields
SELECT * FROM keluarga 
WHERE kepala_keluarga IS NULL OR alamat IS NULL;

-- 5. Check valid date format
SELECT * FROM penduduk 
WHERE tanggal_lahir > CURDATE();

-- 6. Check status enum values
SELECT DISTINCT status_verifikasi FROM keluarga;
SELECT DISTINCT agama FROM penduduk;
SELECT DISTINCT status_perkawinan FROM penduduk;
```

---

## 📝 Best Practices

### Input Data
1. ✅ Selalu validate NIK format (16 digit)
2. ✅ Validate tanggal_lahir (tidak boleh masa depan)
3. ✅ Selalu input minimal data wajib (marked with *)
4. ✅ Use dropdown untuk agama, status perkawinan, dll

### Database Maintenance
1. ✅ Backup regular (minimal daily)
2. ✅ Optimize tables regularly
3. ✅ Monitor indexes performance
4. ✅ Archive old data (>2 tahun) ke separate DB

### Security
1. ✅ Change default passwords
2. ✅ Use HTTPS untuk production
3. ✅ Implement proper access control
4. ✅ Encrypt sensitive data (NIK, tanggal lahir)

---

## 📞 Support & Documentation

- **Setup Guide**: `INSTALASI.md`
- **Database Integration**: `DATABASE_INTEGRATION.md`
- **Full Documentation**: `README.md`

---

**Version**: 1.0.0
**Last Updated**: Januari 2026
**Status**: ✅ Ready for Production
