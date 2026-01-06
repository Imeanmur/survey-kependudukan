# 🏗️ SYSTEM ARCHITECTURE & DATABASE DIAGRAM

## System Architecture Overview

```
┌──────────────────────────────────────────────────────────────────────┐
│                        SURVEY KEPENDUDUKAN SYSTEM                    │
└──────────────────────────────────────────────────────────────────────┘

┌─ PRESENTATION LAYER ──────────────────────────────────────────────────┐
│                                                                        │
│  ┌────────────────────────────────────────────────────────────────┐  │
│  │              Browser / Web Interface                           │  │
│  │  index.html - Modern Responsive Dashboard                    │  │
│  │  - 6 Stat Cards (real-time)                                  │  │
│  │  - 4 Chart Visualizations                                    │  │
│  │  - Data Tables & Search                                      │  │
│  │  - 4 Main Menu: Dashboard, Penduduk, Grafik, Laporan         │  │
│  └────────────────────────────────────────────────────────────────┘  │
│                              ↓                                         │
│                         HTTP/AJAX                                     │
│                                                                        │
└────────────────────────────────────────────────────────────────────────┘

┌─ APPLICATION LAYER ───────────────────────────────────────────────────┐
│                                                                        │
│  ┌──────────────────┐    ┌──────────────────┐    ┌──────────────────┐ │
│  │   API Endpoints  │    │  JavaScript      │    │  CSS Styling     │ │
│  │                  │    │  Logic (Vanilla) │    │  (Responsive)    │ │
│  │ - data.php (9)   │    │                  │    │                  │ │
│  │ - penduduk.php   │    │ - Load data      │    │ - Modern design  │ │
│  │ - statistics     │    │ - Update UI      │    │ - Grid layout    │ │
│  │ - charts         │    │ - Event handlers │    │ - Dark mode ready│ │
│  │ - search/filter  │    │ - Navigation     │    │                  │ │
│  └──────────────────┘    └──────────────────┘    └──────────────────┘ │
│                                                                        │
└────────────────────────────────────────────────────────────────────────┘
                                   ↓
                          MySQL Query Processor

┌─ DATA ACCESS LAYER ───────────────────────────────────────────────────┐
│                                                                        │
│  ┌──────────────────────────────────────────────────────────────────┐ │
│  │         PHP MySQL Connection & Database Operations             │ │
│  │  - config.php (Connection)                                     │ │
│  │  - Query Execution                                             │ │
│  │  - Result Processing                                           │ │
│  │  - Error Handling & CORS                                       │ │
│  └──────────────────────────────────────────────────────────────────┘ │
│                                                                        │
└────────────────────────────────────────────────────────────────────────┘
                                   ↓
                          Database Connection

┌─ DATABASE LAYER ──────────────────────────────────────────────────────┐
│                                                                        │
│  ┌─────────────────────────────────────────────────────────────────┐  │
│  │         MySQL/MariaDB - survey_kependudukan                   │  │
│  │                                                                 │  │
│  │  Main Tables:                                                  │  │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐        │  │
│  │  │  keluarga    │  │   penduduk   │  │  verifikasi  │        │  │
│  │  │   (10 rows)  │→→│  (30 rows)   │  │  (8 rows)    │        │  │
│  │  └──────────────┘  └──────────────┘  └──────────────┘        │  │
│  │         ↓                 ↓                                     │  │
│  │  ┌──────────────┐  ┌──────────────┐                          │  │
│  │  │  kecamatan   │  │  kelurahan   │  Master Data              │  │
│  │  │  (18 rows)   │  │  (8+ rows)   │  (Reference)              │  │
│  │  └──────────────┘  └──────────────┘                          │  │
│  │                                                                 │  │
│  │  ┌──────────────┐  ┌──────────────┐                          │  │
│  │  │    user      │  │  aktivitas   │  System Logs              │  │
│  │  │  (4 users)   │  │   (logging)  │                           │  │
│  │  └──────────────┘  └──────────────┘                          │  │
│  │                                                                 │  │
│  │  Analytics Views:                                              │  │
│  │  • view_ringkasan_kecamatan                                    │  │
│  │  • view_distribusi_agama                                       │  │
│  │  • view_top_pekerjaan                                          │  │
│  │  • view_status_verifikasi                                      │  │
│  │                                                                 │  │
│  │  Indexes & Constraints:                                        │  │
│  │  • 15+ Performance Indexes                                     │  │
│  │  • Foreign Keys (Data Integrity)                               │  │
│  │  • UNIQUE Constraints                                          │  │
│  │  • FULLTEXT Search Indexes                                     │  │
│  └─────────────────────────────────────────────────────────────────┘  │
│                                                                        │
└────────────────────────────────────────────────────────────────────────┘
```

---

## Entity Relationship Diagram (ERD)

```
┌─────────────────────────────────────────────────────────────────────────┐
│                         DATABASE RELATIONSHIPS                          │
└─────────────────────────────────────────────────────────────────────────┘

                           ┌──────────────────┐
                           │   kecamatan      │
                           │ (Master Data)    │
                           ├──────────────────┤
                           │ id_kecamatan (PK)│
                           │ nama_kecamatan   │
                           │ kode_kecamatan   │
                           └────────┬─────────┘
                                    │ 1:N
                                    │
                    ┌───────────────┴────────────────┐
                    │                                │
        ┌───────────▼──────────────┐      ┌─────────▼─────────────┐
        │   keluarga               │      │   kelurahan           │
        │ (Kartu Keluarga)         │      │ (Master Data)         │
        ├──────────────────────────┤      ├──────────────────────┤
        │ id_keluarga (PK)         │      │ id_kelurahan (PK)     │
        │ no_kartu_keluarga (UNIQUE)      │ nama_kelurahan        │
        │ nik_kepala_keluarga      │      │ kecamatan_id (FK)     │
        │ kepala_keluarga          │      │ kode_kelurahan        │
        │ ibu_rumah_tangga         │      └──────────────────────┘
        │ alamat                   │
        │ rt, rw                   │
        │ kelurahan, kecamatan     │
        │ latitude, longitude      │
        │ status_verifikasi        │
        │ input_oleh (FK→user)     │
        │ verifikasi_oleh (FK→user)│
        │ tanggal_input            │
        │ tanggal_update           │
        │ tanggal_verifikasi       │
        │ keterangan               │
        └────────┬──────────────────┘
                 │ 1:N
                 │
    ┌────────────▼──────────────────┐
    │                               │ 1:N
    │                               │
┌───▼──────────────────┐     ┌──────▼────────────────┐
│   penduduk           │     │  verifikasi          │
│ (Anggota Keluarga)   │     │ (Verification Log)   │
├──────────────────────┤     ├──────────────────────┤
│ id_penduduk (PK)     │     │ id_verifikasi (PK)   │
│ id_keluarga (FK)     │     │ id_keluarga (FK)     │
│ nik (UNIQUE)         │     │ tanggal_verifikasi   │
│ nama_lengkap         │     │ status               │
│ jenis_kelamin        │     │ petugas_verifikasi   │
│ tempat_lahir         │     │ catatan              │
│ tanggal_lahir        │     │ dokumen_path         │
│ agama                │     │ latitude_verifikasi  │
│ status_perkawinan    │     │ longitude_verifikasi │
│ pendidikan_terakhir  │     └──────────────────────┘
│ pekerjaan            │
│ status_penduduk      │
│ hubungan_keluarga    │
│ golongan_darah       │
│ penyakit_kronis      │
│ keterangan           │
│ tanggal_input        │
│ tanggal_update       │
└──────────────────────┘

┌──────────────────────┐      ┌──────────────────────┐
│     user             │      │    aktivitas         │
│ (User Management)    │      │ (Activity Logging)   │
├──────────────────────┤      ├──────────────────────┤
│ id_user (PK)         │◄──────id_user (FK)         │
│ username (UNIQUE)    │      │ id_aktivitas (PK)    │
│ password (SHA2)      │      │ tipe_aktivitas       │
│ email (UNIQUE)       │      │ deskripsi            │
│ nama_lengkap         │      │ tabel_terkait        │
│ role (admin, petugas)│      │ id_record            │
│ status               │      │ tanggal_aktivitas    │
│ tanggal_input        │      └──────────────────────┘
│ tanggal_update       │
└──────────────────────┘

Connections:
───────── = Foreign Key Relationship
(FK)      = Foreign Key Reference
(PK)      = Primary Key
1:N       = One to Many
UNIQUE    = Unique Constraint
```

---

## Database Tables Detail

### 1. Keluarga (Kartu Keluarga)

```
+─────────────────────────────────────────────────────────+
│              KELUARGA TABLE (10 rows)                   │
+─────────────────────────────────────────────────────────+

Primary Key: id_keluarga (AUTO_INCREMENT)
Unique Keys: no_kartu_keluarga

Columns:
├─ id_keluarga                    INT (Primary Key)
├─ no_kartu_keluarga              VARCHAR(16) [UNIQUE]
├─ nik_kepala_keluarga            VARCHAR(16)
├─ kepala_keluarga                VARCHAR(100) [Required]
├─ ibu_rumah_tangga               VARCHAR(100)
├─ alamat                         TEXT [Required]
├─ rt, rw                         VARCHAR(3)
├─ kelurahan                      VARCHAR(100)
├─ kecamatan                      VARCHAR(100) [Indexed]
├─ kelurahan_id, kecamatan_id     INT (for relations)
├─ provinsi, kota                 VARCHAR(100)
├─ latitude                       DECIMAL(10,8)
├─ longitude                      DECIMAL(11,8)
├─ tanggal_input                  DATETIME (default now)
├─ tanggal_update                 DATETIME (auto update)
├─ status_verifikasi              ENUM('pending', 'terverifikasi', 'ditolak', 'revisi')
├─ keterangan                     TEXT
├─ input_oleh                     VARCHAR(100)
├─ verifikasi_oleh                VARCHAR(100)
└─ tanggal_verifikasi             DATETIME

Indexes:
├─ PRIMARY KEY (id_keluarga)
├─ UNIQUE KEY (no_kartu_keluarga)
├─ INDEX (status_verifikasi)
├─ INDEX (kecamatan)
└─ FULLTEXT INDEX (kepala_keluarga, alamat)
```

### 2. Penduduk (Anggota Keluarga)

```
+─────────────────────────────────────────────────────────+
│              PENDUDUK TABLE (30 rows)                   │
+─────────────────────────────────────────────────────────+

Primary Key: id_penduduk (AUTO_INCREMENT)
Foreign Key: id_keluarga → keluarga.id_keluarga
Unique Keys: nik

Columns:
├─ id_penduduk                    INT (Primary Key)
├─ id_keluarga                    INT (Foreign Key)
├─ nik                            VARCHAR(16) [UNIQUE]
├─ nama_lengkap                   VARCHAR(100) [Required]
├─ jenis_kelamin                  ENUM('Laki-laki', 'Perempuan')
├─ tempat_lahir                   VARCHAR(100)
├─ tanggal_lahir                  DATE
├─ agama                          ENUM('Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Lainnya')
├─ status_perkawinan              ENUM('Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati')
├─ pendidikan_terakhir            VARCHAR(100)
├─ pekerjaan                      VARCHAR(100) [Indexed]
├─ status_penduduk                ENUM('Tetap', 'Sementara', 'Hilang', 'Mati')
├─ hubungan_keluarga              VARCHAR(50)
├─ golongan_darah                 VARCHAR(2)
├─ penyakit_kronis                TEXT
├─ keterangan                     TEXT
├─ tanggal_input                  DATETIME (default now)
└─ tanggal_update                 DATETIME (auto update)

Indexes:
├─ PRIMARY KEY (id_penduduk)
├─ UNIQUE KEY (nik)
├─ FOREIGN KEY (id_keluarga) → keluarga(id_keluarga)
├─ INDEX (id_keluarga)
├─ INDEX (agama)
├─ INDEX (pekerjaan)
└─ FULLTEXT INDEX (nama_lengkap, pekerjaan)
```

### 3. Verifikasi (Verification Log)

```
+─────────────────────────────────────────────────────────+
│              VERIFIKASI TABLE (8 rows)                  │
+─────────────────────────────────────────────────────────+

Primary Key: id_verifikasi (AUTO_INCREMENT)
Foreign Key: id_keluarga → keluarga.id_keluarga

Columns:
├─ id_verifikasi                  INT (Primary Key)
├─ id_keluarga                    INT (Foreign Key)
├─ tanggal_verifikasi             DATETIME (default now)
├─ status                         VARCHAR(50)
├─ petugas_verifikasi             VARCHAR(100)
├─ catatan                        TEXT
├─ dokumen_path                   VARCHAR(255)
├─ latitude_verifikasi            DECIMAL(10,8)
└─ longitude_verifikasi           DECIMAL(11,8)

Indexes:
├─ PRIMARY KEY (id_verifikasi)
├─ FOREIGN KEY (id_keluarga) → keluarga(id_keluarga)
├─ INDEX (status)
└─ INDEX (tanggal_verifikasi)
```

### 4. Kecamatan & Kelurahan (Master Data)

```
KECAMATAN TABLE (18 rows - all districts in Medan)
├─ id_kecamatan (PK)
├─ nama_kecamatan (UNIQUE)
├─ kode_kecamatan
└─ INDEX (nama_kecamatan)

KELURAHAN TABLE (8+ rows - sample)
├─ id_kelurahan (PK)
├─ nama_kelurahan
├─ kecamatan_id (FK)
├─ kode_kelurahan
└─ INDEX (kecamatan_id)
```

### 5. User & Aktivitas (System)

```
USER TABLE (4 default users)
├─ id_user (PK)
├─ username (UNIQUE)
├─ password (SHA2 hashed)
├─ email (UNIQUE)
├─ nama_lengkap
├─ role (admin, petugas, viewer)
├─ status (active, inactive)
├─ tanggal_input, tanggal_update
└─ INDEX (role, status)

AKTIVITAS TABLE (audit log)
├─ id_aktivitas (PK)
├─ id_user (FK)
├─ tipe_aktivitas
├─ deskripsi, tabel_terkait, id_record
├─ tanggal_aktivitas
└─ INDEX (id_user, tanggal_aktivitas)
```

---

## API Data Flow Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                    CLIENT REQUESTS                          │
└─────────────────────────────────────────────────────────────┘
              ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
              
┌─────────────────────────────────────────────────────────────┐
│                    API ENDPOINTS                            │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ api/data.php?action=                                       │
│ ├─ get_stats                  → stats.json                │
│ ├─ get_data_terbaru           → keluarga array            │
│ ├─ get_data_by_kecamatan      → kecamatan stats           │
│ ├─ get_grafik_agama           → agama distribution        │
│ ├─ get_grafik_pekerjaan       → employment data           │
│ ├─ get_grafik_verifikasi      → verification status       │
│ ├─ search_keluarga            → search results            │
│ ├─ get_kecamatan_list         → dropdown list             │
│ └─ get_summary_dashboard      → comprehensive stats       │
│                                                             │
│ api/penduduk.php?action=                                   │
│ ├─ get_penduduk               → all residents             │
│ └─ get_penduduk_by_keluarga   → by family ID              │
│                                                             │
└─────────────────────────────────────────────────────────────┘
              ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓

┌─────────────────────────────────────────────────────────────┐
│                    PHP PROCESSING                          │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ ├─ config.php: MySQL Connection                           │
│ ├─ Sanitize Input: real_escape_string()                   │
│ ├─ Execute Query: $conn->query()                          │
│ ├─ Process Result: fetch_assoc()                          │
│ ├─ Format Response: json_encode()                         │
│ └─ Error Handling: catch & return error JSON              │
│                                                             │
└─────────────────────────────────────────────────────────────┘
              ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓

┌─────────────────────────────────────────────────────────────┐
│                    MYSQL QUERIES                           │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ ├─ SELECT with JOINs                                       │
│ ├─ GROUP BY aggregations                                   │
│ ├─ WHERE filtering & conditions                            │
│ ├─ ORDER BY sorting                                        │
│ ├─ LIMIT pagination                                        │
│ ├─ VIEW queries (pre-calculated)                           │
│ └─ FULLTEXT search                                         │
│                                                             │
└─────────────────────────────────────────────────────────────┘
              ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓

┌─────────────────────────────────────────────────────────────┐
│                    DATABASE RESPONSE                       │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ Result Set from:                                           │
│ ├─ Tables: keluarga, penduduk, verifikasi, etc            │
│ ├─ Views: pre-calculated aggregations                      │
│ ├─ Indexes: optimized lookups                              │
│ └─ Constraints: validated data integrity                   │
│                                                             │
└─────────────────────────────────────────────────────────────┘
              ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓

┌─────────────────────────────────────────────────────────────┐
│                    JSON RESPONSE                           │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ {                                                           │
│   "success": true/false,                                   │
│   "data": { ... },                                         │
│   "labels": [ ... ],                                       │
│   "message": "error msg if any"                           │
│ }                                                           │
│                                                             │
└─────────────────────────────────────────────────────────────┘
              ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓

┌─────────────────────────────────────────────────────────────┐
│                    JAVASCRIPT HANDLING                     │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ ├─ Parse JSON response                                     │
│ ├─ Check success flag                                      │
│ ├─ Extract data from response                              │
│ ├─ Format for display                                      │
│ ├─ Update HTML/DOM                                         │
│ └─ Trigger re-render of components                         │
│                                                             │
└─────────────────────────────────────────────────────────────┘
              ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓

┌─────────────────────────────────────────────────────────────┐
│                    UI RENDERING                            │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ ├─ Update Stat Cards (numbers)                             │
│ ├─ Render Charts (Chart.js)                                │
│ ├─ Populate Tables (table rows)                            │
│ ├─ Display Status Badges                                   │
│ └─ Apply CSS Styling & Animations                          │
│                                                             │
└─────────────────────────────────────────────────────────────┘
              ↓
        ┌──────────────┐
        │  User Sees   │
        │  Dashboard   │
        └──────────────┘
```

---

## Analytics Views Structure

```
┌─────────────────────────────────────────────────────────────┐
│           4 PRE-CALCULATED ANALYTICS VIEWS                 │
└─────────────────────────────────────────────────────────────┘

VIEW 1: view_ringkasan_kecamatan
┌─────────────────────────────────────┐
│ SELECT:                             │
│ - nama_kecamatan                    │
│ - total_keluarga (COUNT)            │
│ - total_penduduk (COUNT)            │
│ - terverifikasi (SUM CASE)          │
│ - pending (SUM CASE)                │
│ - ditolak (SUM CASE)                │
│ GROUP BY kecamatan                  │
│ ORDER BY total_keluarga DESC        │
│                                     │
│ Use: District summary dashboard     │
└─────────────────────────────────────┘

VIEW 2: view_distribusi_agama
┌─────────────────────────────────────┐
│ SELECT:                             │
│ - agama                             │
│ - jumlah (COUNT)                    │
│ - persentase (COUNT/TOTAL * 100)    │
│ GROUP BY agama                      │
│ ORDER BY jumlah DESC                │
│                                     │
│ Use: Religion distribution pie chart│
└─────────────────────────────────────┘

VIEW 3: view_top_pekerjaan
┌─────────────────────────────────────┐
│ SELECT:                             │
│ - pekerjaan (CASE for null)         │
│ - jumlah (COUNT)                    │
│ - persentase (COUNT/TOTAL * 100)    │
│ GROUP BY pekerjaan                  │
│ ORDER BY jumlah DESC                │
│ LIMIT 10                            │
│                                     │
│ WHERE status_penduduk = 'Tetap'     │
│                                     │
│ Use: Top 10 employment chart        │
└─────────────────────────────────────┘

VIEW 4: view_status_verifikasi
┌─────────────────────────────────────┐
│ SELECT:                             │
│ - status_verifikasi                 │
│ - jumlah (COUNT)                    │
│ - persentase (COUNT/TOTAL * 100)    │
│ GROUP BY status_verifikasi          │
│                                     │
│ Use: Verification status breakdown  │
└─────────────────────────────────────┘
```

---

## Performance Optimization

```
┌─────────────────────────────────────────────────────────────┐
│              DATABASE OPTIMIZATION STRATEGY                 │
└─────────────────────────────────────────────────────────────┘

Indexes (15+):
├─ PRIMARY KEY on all tables
├─ UNIQUE on: no_kartu_keluarga, nik, username, email
├─ INDEX on: status_verifikasi, kecamatan, agama, pekerjaan
├─ INDEX on: dates (tanggal_input, tanggal_verifikasi)
├─ INDEX on: foreign keys (id_keluarga, id_user)
├─ INDEX on: role, status
└─ FULLTEXT on: kepala_keluarga, nama_lengkap, alamat

Constraints:
├─ FOREIGN KEY relationships (data integrity)
├─ UNIQUE constraints (no duplicates)
├─ NOT NULL on required fields
├─ ENUM types (validation at DB level)
└─ DECIMAL for precise location data

Query Optimization:
├─ Use JOINs instead of subqueries (when possible)
├─ Pre-calculated VIEWS for aggregations
├─ LIMIT pagination for large results
├─ SELECT specific columns (not *)
└─ Use EXPLAIN to analyze queries

Connection Optimization:
├─ Connection pooling ready
├─ UTF8MB4 charset (full unicode)
├─ InnoDB engine (transactions, foreign keys)
└─ Prepared statements ready (security)

Caching Strategy:
├─ API responses cacheable
├─ 5-minute auto-refresh recommended
├─ Browser caching for static assets
└─ Database query caching available
```

---

## Security Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                  SECURITY LAYERS                           │
└─────────────────────────────────────────────────────────────┘

Layer 1: Input Validation
├─ PHP real_escape_string() for SQL injection prevention
├─ Type casting for parameters
├─ Length validation for strings
└─ ENUM types for restricted values

Layer 2: Database Security
├─ User roles (admin, petugas, viewer)
├─ Foreign key constraints
├─ UNIQUE constraints for critical fields
├─ FULLTEXT indexes for safe searching
└─ Activity logging (audit trail)

Layer 3: Authentication (Future)
├─ Password hashing with SHA2
├─ Session management
├─ Role-based access control (RBAC)
└─ API token validation

Layer 4: Data Protection
├─ HTTPS ready (for production)
├─ CORS headers configured
├─ Sensitive data logging (audit trail)
└─ Backup & recovery procedures

Layer 5: Audit & Compliance
├─ aktivitas table for logging
├─ Who-When-What tracking
├─ Verifikasi log for approval trail
└─ Timestamps on all operations
```

---

## Deployment Architecture

```
┌─────────────────────────────────────────────────────────────┐
│            RECOMMENDED DEPLOYMENT SETUP                    │
└─────────────────────────────────────────────────────────────┘

Development:
├─ localhost:8080 (XAMPP/WAMP)
├─ MySQL 5.7+
└─ PHP 7.4+

Staging:
├─ Internal server (192.168.x.x)
├─ MySQL 8.0+
├─ PHP 8.0+
└─ SSL certificate (self-signed)

Production:
├─ Domain: survey.medan.go.id
├─ Dedicated server or cloud
├─ MySQL 8.0+ (replicated)
├─ PHP 8.0+ (multiple instances)
├─ SSL/TLS (valid certificate)
├─ Load balancer (if needed)
├─ CDN for static assets
└─ Daily backups

Monitoring:
├─ Database performance monitoring
├─ Error logging & alerting
├─ API uptime monitoring
├─ User activity tracking
└─ Backup integrity checks
```

---

## File Relationship Map

```
┌─ survey-kependudukan/
│
├─ index.html ................... Main dashboard (HTML)
│  ├─ /assets/css/style.css ...... Dashboard styling (CSS)
│  └─ /assets/js/script.js ....... Dashboard logic (JavaScript)
│      ├─ Calls: /api/data.php
│      └─ Calls: /api/penduduk.php
│
├─ /api/
│  ├─ data.php .................. Statistics & Charts API
│  │  └─ requires: /includes/config.php
│  └─ penduduk.php .............. Residents data API
│      └─ requires: /includes/config.php
│
├─ /includes/
│  └─ config.php ................Database connection (PHP)
│      └─ connects to: survey_kependudukan (MySQL)
│
├─ /database/
│  ├─ survey_kependudukan_full.sql ... Database dump
│  └─ setup.sql ..................... Original setup
│
└─ /docs/
   ├─ QUICK_START.md ............... 5-min setup checklist
   ├─ INSTALASI.md ................. Installation guide
   ├─ DATABASE_INTEGRATION.md ....... DB integration guide
   ├─ DATA_MAPPING.md .............. Field mapping reference
   ├─ INTEGRATION_SUMMARY.md ........ Integration summary
   └─ README.md .................... Full documentation
```

---

**Version**: 1.0.0
**Last Updated**: Januari 5, 2026
**Status**: ✅ Complete Architecture

🏗️ **System ready for deployment!**
