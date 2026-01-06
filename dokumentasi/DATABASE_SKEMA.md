# DATABASE SCHEMA DOKUMENTASI

## 📦 Overview

Database `survey_kependudukan` terdiri dari **3 tabel utama** dengan relasi one-to-many untuk mengelola data keluarga dan penduduk.

---

## 🗄️ Entity Relationship Diagram

```
┌─────────────────────┐
│     keluarga        │
├─────────────────────┤
│ id_keluarga (PK)    │
│ no_kartu_keluarga   │
│ kepala_keluarga     │
│ alamat              │
│ kecamatan           │
│ status_verifikasi   │
└──────────┬──────────┘
           │ 1:N
           │
     ┌─────▼──────────┐
     │   penduduk     │
     ├────────────────┤
     │ id_penduduk(PK)│
     │ id_keluarga(FK)│
     │ nik            │
     │ nama_lengkap   │
     │ agama          │
     │ pekerjaan      │
     └────────────────┘
           
┌─────────────────────┐
│  verifikasi         │
├─────────────────────┤
│ id_verifikasi (PK)  │
│ id_keluarga (FK)    │
│ tanggal_verifikasi  │
│ status              │
└─────────────────────┘
```

---

## 📋 Tabel: keluarga

**Fungsi**: Menyimpan informasi keluarga/rumah tangga

**Create Statement**:
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
    status_verifikasi ENUM('pending','terverifikasi','ditolak') DEFAULT 'pending',
    keterangan TEXT,
    INDEX idx_kecamatan (kecamatan),
    INDEX idx_status (status_verifikasi)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Field Details

| Field | Type | Constraints | Deskripsi |
|-------|------|-------------|-----------|
| **id_keluarga** | INT | PRIMARY KEY, AUTO_INCREMENT | Unique ID untuk setiap keluarga |
| **no_kartu_keluarga** | VARCHAR(16) | UNIQUE, NOT NULL | Nomor KK (format: 16 digit) |
| **kepala_keluarga** | VARCHAR(100) | NOT NULL | Nama kepala rumah tangga |
| **alamat** | TEXT | NOT NULL | Alamat lengkap (jalan, nomor, dst) |
| **kelurahan** | VARCHAR(100) | NULL | Kelurahan/desa |
| **kecamatan** | VARCHAR(100) | NULL | Kecamatan (Medan Baru, dll) |
| **rt** | VARCHAR(3) | NULL | Rukun Tetangga (1-3 digit) |
| **rw** | VARCHAR(3) | NULL | Rukun Warga (1-3 digit) |
| **tanggal_input** | DATETIME | DEFAULT CURRENT_TIMESTAMP | Timestamp pencatatan |
| **status_verifikasi** | ENUM | DEFAULT 'pending' | pending/terverifikasi/ditolak |
| **keterangan** | TEXT | NULL | Catatan tambahan |

### Kecamatan Values
```
- MEDAN BARU
- MEDAN JOHOR
- MEDAN SELAYANG
- MEDAN BELAWAN
- MEDAN MAIMUN
```

### Status Verifikasi Values
```
- pending: Menunggu verifikasi
- terverifikasi: Sudah diverifikasi
- ditolak: Data ditolak/revisi diperlukan
```

### Sample Data
```sql
INSERT INTO keluarga VALUES (
    1,
    '1175011201345001',
    'SUMAIDI',
    'Jl Gajah Mada No 123',
    'Petisah',
    'MEDAN BARU',
    '02',
    '08',
    '2025-01-06 10:30:45',
    'terverifikasi',
    'Keluarga normal'
);
```

---

## 👥 Tabel: penduduk

**Fungsi**: Menyimpan informasi detail setiap anggota keluarga

**Create Statement**:
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
    FOREIGN KEY (id_keluarga) REFERENCES keluarga(id_keluarga),
    INDEX idx_keluarga (id_keluarga),
    INDEX idx_agama (agama),
    INDEX idx_pekerjaan (pekerjaan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Field Details

| Field | Type | Constraints | Deskripsi |
|-------|------|-------------|-----------|
| **id_penduduk** | INT | PRIMARY KEY, AUTO_INCREMENT | Unique ID untuk penduduk |
| **id_keluarga** | INT | FOREIGN KEY, NOT NULL | Reference ke tabel keluarga |
| **nik** | VARCHAR(16) | UNIQUE, NOT NULL | Nomor Induk Kependudukan |
| **nama_lengkap** | VARCHAR(100) | NOT NULL | Nama lengkap penduduk |
| **jenis_kelamin** | ENUM | NULL | Laki-laki / Perempuan |
| **tempat_lahir** | VARCHAR(100) | NULL | Tempat lahir |
| **tanggal_lahir** | DATE | NULL | Tanggal lahir (YYYY-MM-DD) |
| **agama** | ENUM | NULL | Agama (Islam, Kristen, dst) |
| **status_perkawinan** | ENUM | NULL | Belum Kawin, Kawin, Cerai |
| **pendidikan_terakhir** | VARCHAR(50) | NULL | SD, SMP, SMA, Diploma, Sarjana |
| **pekerjaan** | VARCHAR(100) | NULL | Nama pekerjaan/profesi |
| **status_penduduk** | ENUM | NULL | Tetap / Sementara |
| **hubungan_keluarga** | VARCHAR(50) | NULL | Kepala, Istri, Anak, dst |
| **tanggal_input** | DATETIME | DEFAULT CURRENT_TIMESTAMP | Timestamp pencatatan |

### Enum Values

**jenis_kelamin**:
```
- Laki-laki
- Perempuan
```

**agama**:
```
- Islam
- Kristen
- Katolik
- Hindu
- Buddha
- Konghucu
```

**status_perkawinan**:
```
- Belum Kawin
- Kawin
- Cerai Hidup
- Cerai Mati
```

**pendidikan_terakhir**:
```
- Tidak Sekolah
- SD
- SMP
- SMA
- Diploma
- Sarjana
```

**pekerjaan** (Examples):
```
- Petani
- Pedagang
- Buruh
- Pegawai Negeri
- Pengusaha
- Sopir
- Tukang (Batu, Kayu, dll)
- Ibu Rumah Tangga
- Pelajar
- Pensiunan
- Tidak Bekerja
- (Custom values allowed)
```

**status_penduduk**:
```
- Tetap
- Sementara
```

**hubungan_keluarga**:
```
- Kepala Keluarga
- Istri
- Anak
- Menantu
- Cucu
- Orang Tua
- Saudara
- (Custom values)
```

### Sample Data
```sql
INSERT INTO penduduk VALUES (
    1,
    1,
    '1175011201340001',
    'SUMAIDI',
    'Laki-laki',
    'Medan',
    '1965-05-10',
    'Islam',
    'Kawin',
    'SMA',
    'Pedagang',
    'Tetap',
    'Kepala Keluarga',
    '2025-01-06 10:30:45'
);
```

---

## ✅ Tabel: verifikasi

**Fungsi**: Menyimpan riwayat verifikasi keluarga

**Create Statement**:
```sql
CREATE TABLE verifikasi (
    id_verifikasi INT PRIMARY KEY AUTO_INCREMENT,
    id_keluarga INT NOT NULL,
    tanggal_verifikasi DATETIME DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50),
    petugas_verifikasi VARCHAR(100),
    catatan TEXT,
    FOREIGN KEY (id_keluarga) REFERENCES keluarga(id_keluarga),
    INDEX idx_keluarga (id_keluarga),
    INDEX idx_tanggal (tanggal_verifikasi)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Field Details

| Field | Type | Constraints | Deskripsi |
|-------|------|-------------|-----------|
| **id_verifikasi** | INT | PRIMARY KEY, AUTO_INCREMENT | Unique ID untuk verifikasi |
| **id_keluarga** | INT | FOREIGN KEY, NOT NULL | Reference ke tabel keluarga |
| **tanggal_verifikasi** | DATETIME | DEFAULT CURRENT_TIMESTAMP | Waktu verifikasi dilakukan |
| **status** | VARCHAR(50) | NULL | Verified / Rejected / Pending |
| **petugas_verifikasi** | VARCHAR(100) | NULL | Nama petugas yang verifikasi |
| **catatan** | TEXT | NULL | Catatan dari petugas |

### Sample Data
```sql
INSERT INTO verifikasi VALUES (
    1,
    1,
    '2025-01-06 14:20:30',
    'Verified',
    'Ahmad Wijaya',
    'Data lengkap dan valid'
);
```

---

## 🔑 Relationships & Foreign Keys

### One-to-Many: keluarga → penduduk
```
1 keluarga dapat memiliki banyak penduduk (1-N)
Contoh:
- Keluarga ID 1 (Sumaidi) → 3 penduduk (Sumaidi, Istri, Anak)
- Keluarga ID 2 → 2 penduduk
```

**Implementasi**:
```sql
FOREIGN KEY (id_keluarga) REFERENCES keluarga(id_keluarga)
```

### One-to-One: keluarga → verifikasi
```
1 keluarga dapat memiliki multiple verifikasi records
Digunakan untuk audit trail
```

---

## 📊 Data Statistics

### Current Data (After generate_data.php)
```
Tabel keluarga:     55 records
Tabel penduduk:     111 records
Tabel verifikasi:   0 records (for now)
```

### Data Distribution

**By Kecamatan**:
```
MEDAN BARU:     15 kartu (27%)
MEDAN JOHOR:    12 kartu (22%)
MEDAN SELAYANG: 10 kartu (18%)
MEDAN BELAWAN:   9 kartu (16%)
MEDAN MAIMUN:    9 kartu (17%)
─────────────────────────────
TOTAL:          55 kartu
```

**By Religion**:
```
Islam:   ~82 penduduk (74%)
Kristen: ~18 penduduk (16%)
Katolik: ~8 penduduk  (7%)
Hindu:   ~2 penduduk  (2%)
Buddha:  ~1 penduduk  (1%)
```

**By Gender**:
```
Laki-laki:  ~58 penduduk (52%)
Perempuan:  ~53 penduduk (48%)
```

---

## 🔄 Query Examples

### Get Family with Members
```sql
SELECT k.*, COUNT(p.id_penduduk) as jumlah_anggota
FROM keluarga k
LEFT JOIN penduduk p ON k.id_keluarga = p.id_keluarga
GROUP BY k.id_keluarga
ORDER BY k.tanggal_input DESC;
```

### Get Religion Distribution
```sql
SELECT agama, COUNT(*) as jumlah
FROM penduduk
WHERE agama IS NOT NULL
GROUP BY agama
ORDER BY jumlah DESC;
```

### Get Occupation Distribution (Top 10)
```sql
SELECT pekerjaan, COUNT(*) as jumlah
FROM penduduk
WHERE pekerjaan IS NOT NULL
GROUP BY pekerjaan
ORDER BY jumlah DESC
LIMIT 10;
```

### Get Verification Status
```sql
SELECT status_verifikasi, COUNT(*) as jumlah
FROM keluarga
GROUP BY status_verifikasi;
```

### Get Data by Kecamatan
```sql
SELECT 
    kecamatan,
    COUNT(DISTINCT id_keluarga) as total_kartu,
    COUNT(p.id_penduduk) as total_penduduk
FROM keluarga k
LEFT JOIN penduduk p ON k.id_keluarga = p.id_keluarga
GROUP BY kecamatan
ORDER BY total_kartu DESC;
```

### Get Monthly Trend
```sql
SELECT 
    DATE_FORMAT(tanggal_input, '%Y-%m') as bulan,
    COUNT(*) as jumlah_input
FROM keluarga
GROUP BY DATE_FORMAT(tanggal_input, '%Y-%m')
ORDER BY bulan;
```

---

## 🛠️ Database Maintenance

### Create Backups
```bash
# Full backup
mysqldump -u root survey_kependudukan > backup_$(date +%Y%m%d).sql

# With password
mysqldump -u root -p survey_kependudukan > backup.sql
```

### Optimize Tables
```sql
-- Optimize all tables
OPTIMIZE TABLE keluarga, penduduk, verifikasi;

-- Check integrity
CHECK TABLE keluarga, penduduk, verifikasi;

-- Repair if needed
REPAIR TABLE keluarga, penduduk, verifikasi;
```

### Create Indexes for Performance
```sql
-- Already included in CREATE TABLE statements
-- But can add manually:

ALTER TABLE keluarga ADD INDEX idx_kecamatan (kecamatan);
ALTER TABLE keluarga ADD INDEX idx_status (status_verifikasi);
ALTER TABLE penduduk ADD INDEX idx_keluarga (id_keluarga);
ALTER TABLE penduduk ADD INDEX idx_agama (agama);
ALTER TABLE penduduk ADD INDEX idx_pekerjaan (pekerjaan);
ALTER TABLE verifikasi ADD INDEX idx_keluarga (id_keluarga);
ALTER TABLE verifikasi ADD INDEX idx_tanggal (tanggal_verifikasi);
```

### Analyze Table Statistics
```sql
-- Update table statistics for query optimizer
ANALYZE TABLE keluarga, penduduk, verifikasi;
```

---

## 📈 Growth Planning

### Estimated Sizes (per 10,000 keluarga)
```
keluarga table:   ~5 MB
penduduk table:   ~8 MB (assuming 4 penduduk/family average)
verifikasi table: ~3 MB (assuming 1 verification record/family)
─────────────────────────────
Total with indexes: ~20 MB
```

### Recommended Maintenance Frequency
```
- Daily:   Backup database
- Weekly:  OPTIMIZE TABLE
- Monthly: CHECK TABLE + ANALYZE TABLE
- Yearly:  Review & archive old data
```

---

## 🔐 Data Integrity

### Constraints Applied
```
✅ PRIMARY KEY - Ensures unique ID
✅ UNIQUE - no_kartu_keluarga, nik
✅ NOT NULL - Required fields
✅ FOREIGN KEY - Maintains referential integrity
✅ ENUM - Restricts to predefined values
✅ DEFAULT - Auto-fills timestamp
```

### Data Validation (PHP Level)
Should be implemented in `api/data.php`:
```php
// Check required fields
if (!isset($_POST['no_kartu_keluarga']) || empty($_POST['no_kartu_keluarga'])) {
    die(json_encode(['success' => false, 'message' => 'NIK required']));
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die(json_encode(['success' => false, 'message' => 'Invalid email']));
}

// Validate date format
if (!strtotime($tanggal_lahir)) {
    die(json_encode(['success' => false, 'message' => 'Invalid date']));
}
```

---

**Version**: 1.0  
**Last Updated**: January 2026  
**Status**: Complete ✅
