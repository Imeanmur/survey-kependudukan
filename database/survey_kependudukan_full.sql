-- ===================================================
-- DATABASE SURVEY KEPENDUDUKAN DISKOMINFO MEDAN
-- Integrated Database with Full Data Integration
-- ===================================================

-- Drop existing database if exists
DROP DATABASE IF EXISTS survey_kependudukan;

-- Create Database
CREATE DATABASE IF NOT EXISTS survey_kependudukan;
USE survey_kependudukan;

-- ===================================================
-- TABLE: keluarga (Kartu Keluarga)
-- ===================================================
CREATE TABLE IF NOT EXISTS keluarga (
    id_keluarga INT PRIMARY KEY AUTO_INCREMENT,
    no_kartu_keluarga VARCHAR(16) NOT NULL UNIQUE,
    nik_kepala_keluarga VARCHAR(16),
    kepala_keluarga VARCHAR(100) NOT NULL,
    ibu_rumah_tangga VARCHAR(100),
    alamat TEXT NOT NULL,
    rt VARCHAR(3),
    rw VARCHAR(3),
    kelurahan VARCHAR(100),
    kecamatan VARCHAR(100),
    kelurahan_id INT,
    kecamatan_id INT,
    provinsi VARCHAR(100),
    kota VARCHAR(100),
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    tanggal_input DATETIME DEFAULT CURRENT_TIMESTAMP,
    tanggal_update DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    status_verifikasi ENUM('pending', 'terverifikasi', 'ditolak', 'revisi') DEFAULT 'pending',
    keterangan TEXT,
    input_oleh VARCHAR(100),
    verifikasi_oleh VARCHAR(100),
    tanggal_verifikasi DATETIME,
    INDEX idx_keluarga_status (status_verifikasi),
    INDEX idx_keluarga_kecamatan (kecamatan),
    INDEX idx_keluarga_no_kk (no_kartu_keluarga),
    FULLTEXT INDEX ft_keluarga (kepala_keluarga, alamat)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- TABLE: penduduk (Data Anggota Keluarga)
-- ===================================================
CREATE TABLE IF NOT EXISTS penduduk (
    id_penduduk INT PRIMARY KEY AUTO_INCREMENT,
    id_keluarga INT NOT NULL,
    nik VARCHAR(16) NOT NULL UNIQUE,
    nama_lengkap VARCHAR(100) NOT NULL,
    jenis_kelamin ENUM('Laki-laki', 'Perempuan') NOT NULL,
    tempat_lahir VARCHAR(100),
    tanggal_lahir DATE,
    agama ENUM('Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Lainnya') NOT NULL,
    status_perkawinan ENUM('Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati') NOT NULL,
    pendidikan_terakhir VARCHAR(100),
    pekerjaan VARCHAR(100),
    status_penduduk ENUM('Tetap', 'Sementara', 'Hilang', 'Mati') NOT NULL,
    hubungan_keluarga VARCHAR(50),
    golongan_darah VARCHAR(2),
    penyakit_kronis TEXT,
    keterangan TEXT,
    tanggal_input DATETIME DEFAULT CURRENT_TIMESTAMP,
    tanggal_update DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_keluarga) REFERENCES keluarga(id_keluarga) ON DELETE CASCADE,
    INDEX idx_penduduk_keluarga (id_keluarga),
    INDEX idx_penduduk_nik (nik),
    INDEX idx_penduduk_agama (agama),
    INDEX idx_penduduk_pekerjaan (pekerjaan),
    FULLTEXT INDEX ft_penduduk (nama_lengkap, pekerjaan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- TABLE: verifikasi (Log Verifikasi Data)
-- ===================================================
CREATE TABLE IF NOT EXISTS verifikasi (
    id_verifikasi INT PRIMARY KEY AUTO_INCREMENT,
    id_keluarga INT NOT NULL,
    tanggal_verifikasi DATETIME DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50),
    petugas_verifikasi VARCHAR(100),
    catatan TEXT,
    dokumen_path VARCHAR(255),
    latitude_verifikasi DECIMAL(10, 8),
    longitude_verifikasi DECIMAL(11, 8),
    FOREIGN KEY (id_keluarga) REFERENCES keluarga(id_keluarga) ON DELETE CASCADE,
    INDEX idx_verifikasi_status (status),
    INDEX idx_verifikasi_tanggal (tanggal_verifikasi)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- TABLE: kelurahan (Master Data Kelurahan)
-- ===================================================
CREATE TABLE IF NOT EXISTS kelurahan (
    id_kelurahan INT PRIMARY KEY AUTO_INCREMENT,
    nama_kelurahan VARCHAR(100) NOT NULL,
    kecamatan_id INT NOT NULL,
    kode_kelurahan VARCHAR(10),
    INDEX idx_kelurahan_kecamatan (kecamatan_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- TABLE: kecamatan (Master Data Kecamatan)
-- ===================================================
CREATE TABLE IF NOT EXISTS kecamatan (
    id_kecamatan INT PRIMARY KEY AUTO_INCREMENT,
    nama_kecamatan VARCHAR(100) NOT NULL UNIQUE,
    kota_id INT,
    kode_kecamatan VARCHAR(10),
    INDEX idx_kecamatan_kota (kota_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- TABLE: user (User/Admin)
-- ===================================================
CREATE TABLE IF NOT EXISTS user (
    id_user INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    nama_lengkap VARCHAR(100),
    role ENUM('admin', 'petugas', 'viewer') DEFAULT 'viewer',
    status ENUM('active', 'inactive') DEFAULT 'active',
    tanggal_input DATETIME DEFAULT CURRENT_TIMESTAMP,
    tanggal_update DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user_role (role),
    INDEX idx_user_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- TABLE: aktivitas (Log Aktivitas)
-- ===================================================
CREATE TABLE IF NOT EXISTS aktivitas (
    id_aktivitas INT PRIMARY KEY AUTO_INCREMENT,
    id_user INT,
    tipe_aktivitas VARCHAR(50),
    deskripsi TEXT,
    tabel_terkait VARCHAR(100),
    id_record INT,
    tanggal_aktivitas DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES user(id_user),
    INDEX idx_aktivitas_user (id_user),
    INDEX idx_aktivitas_tanggal (tanggal_aktivitas)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- INSERT: Master Data Kecamatan
-- ===================================================
INSERT INTO kecamatan (nama_kecamatan, kode_kecamatan) VALUES
('MEDAN BARU', '1201'),
('MEDAN JOHOR', '1202'),
('MEDAN SELAYANG', '1203'),
('MEDAN BELAWAN', '1204'),
('MEDAN MAIMUN', '1205'),
('MEDAN SUNGGAL', '1206'),
('MEDAN TUNTUNGAN', '1207'),
('MEDAN KOTA', '1208'),
('MEDAN AREA', '1209'),
('MEDAN LABUHAN', '1210'),
('MEDAN MARELAN', '1211'),
('MEDAN PETISAH', '1212'),
('MEDAN RAYA', '1213'),
('MEDAN SATRIA', '1214'),
('MEDAN DELI', '1215'),
('MEDAN DENAI', '1216'),
('MEDAN TIMUR', '1217'),
('MEDAN PERJUANGAN', '1218');

-- ===================================================
-- INSERT: Master Data Kelurahan (Sample dari Medan Baru)
-- ===================================================
INSERT INTO kelurahan (nama_kelurahan, kecamatan_id, kode_kelurahan) VALUES
('ANGGRUNG', 1, '010101'),
('TITI KUNING', 2, '010201'),
('ASAM KUMBANG', 3, '010301'),
('BELAWAN II', 4, '010401'),
('DARAT', 5, '010501'),
('GATOT SUBROTO', 1, '010102'),
('RADJASA', 2, '010202'),
('KOLAM', 3, '010302');

-- ===================================================
-- INSERT: Data Keluarga (Sample Data)
-- ===================================================
INSERT INTO keluarga (no_kartu_keluarga, nik_kepala_keluarga, kepala_keluarga, ibu_rumah_tangga, alamat, rt, rw, kelurahan, kecamatan, provinsi, kota, status_verifikasi, input_oleh, tanggal_input, tanggal_verifikasi, verifikasi_oleh) VALUES
('1207231609094836', '1207231609094001', 'SUMAIDI', 'SITI MURNI', 'Jl. Anggrung No. 45', '01', '04', 'ANGGRUNG', 'MEDAN BARU', 'Sumatera Utara', 'Medan', 'terverifikasi', 'Petugas A', '2026-01-02 09:30:00', '2026-01-02 14:00:00', 'Verifikator A'),
('1271211603230011', '1271211603230001', 'SUJOKO', 'ENDAH WIJAYA', 'Jl. Titi Kuning No. 12', '05', '02', 'TITI KUNING', 'MEDAN JOHOR', 'Sumatera Utara', 'Medan', 'terverifikasi', 'Petugas B', '2026-01-02 10:15:00', '2026-01-02 15:30:00', 'Verifikator B'),
('1271062803190003', '1271062803190001', 'SUTRISNO', 'YANTI HARTONO', 'Jl. Asam Kumbang No. 78', '03', '01', 'ASAM KUMBANG', 'MEDAN SELAYANG', 'Sumatera Utara', 'Medan', 'terverifikasi', 'Petugas A', '2026-01-02 11:00:00', '2026-01-02 16:00:00', 'Verifikator A'),
('1271062701140003', '1271062701140001', 'SUPARDI', 'LINDA KUSUMA', 'Jl. Belawan II No. 33', '02', '03', 'BELAWAN II', 'MEDAN BELAWAN', 'Sumatera Utara', 'Medan', 'terverifikasi', 'Petugas C', '2026-01-02 12:30:00', '2026-01-03 09:00:00', 'Verifikator C'),
('1270812702030002', '1270812702030001', 'HERMAWAN', 'RINI SETIAWAN', 'Jl. Gatot Subroto No. 56', '04', '05', 'DARAT', 'MEDAN MAIMUN', 'Sumatera Utara', 'Medan', 'pending', 'Petugas D', '2026-01-03 08:00:00', NULL, NULL),
('1271831605150004', '1271831605150001', 'BAMBANG SUSANTO', 'ANI RAHAYU', 'Jl. Radjasa No. 22', '06', '02', 'RADJASA', 'MEDAN JOHOR', 'Sumatera Utara', 'Medan', 'terverifikasi', 'Petugas B', '2026-01-03 09:30:00', '2026-01-03 14:00:00', 'Verifikator B'),
('1272031708180005', '1272031708180001', 'HENDRA WIJAYA', 'DEWI LESTARI', 'Jl. Kolam No. 88', '08', '04', 'KOLAM', 'MEDAN SELAYANG', 'Sumatera Utara', 'Medan', 'terverifikasi', 'Petugas A', '2026-01-03 11:00:00', '2026-01-03 15:30:00', 'Verifikator A'),
('1270512603090006', '1270512603090001', 'AHMAD SANTOSO', 'SITI NURHALIZA', 'Jl. Anggrung No. 67', '02', '03', 'ANGGRUNG', 'MEDAN BARU', 'Sumatera Utara', 'Medan', 'ditolak', 'Petugas C', '2026-01-04 08:30:00', '2026-01-04 13:00:00', 'Verifikator C'),
('1270712604120007', '1270712604120001', 'TAUFIK HIDAYAT', 'NURHAYATI', 'Jl. Gatot Subroto No. 91', '03', '06', 'DARAT', 'MEDAN MAIMUN', 'Sumatera Utara', 'Medan', 'pending', 'Petugas D', '2026-01-04 10:00:00', NULL, NULL),
('1271311609140008', '1271311609140001', 'DEDI GUNAWAN', 'SISKA PURNAMA', 'Jl. Titi Kuning No. 45', '07', '03', 'TITI KUNING', 'MEDAN JOHOR', 'Sumatera Utara', 'Medan', 'terverifikasi', 'Petugas B', '2026-01-04 11:30:00', '2026-01-04 16:00:00', 'Verifikator B');

-- ===================================================
-- INSERT: Data Penduduk (Anggota Keluarga)
-- ===================================================
INSERT INTO penduduk (id_keluarga, nik, nama_lengkap, jenis_kelamin, tempat_lahir, tanggal_lahir, agama, status_perkawinan, pendidikan_terakhir, pekerjaan, status_penduduk, hubungan_keluarga, golongan_darah) VALUES
-- Keluarga 1: SUMAIDI
(1, '1207231609094001', 'SUMAIDI', 'Laki-laki', 'MEDAN', '1969-09-16', 'Islam', 'Kawin', 'S1', 'Pegawai Negeri Sipil', 'Tetap', 'Kepala Keluarga', 'O'),
(1, '1207233003090002', 'SITI MURNI', 'Perempuan', 'MEDAN', '1990-03-30', 'Islam', 'Kawin', 'SMA', 'Ibu Rumah Tangga', 'Tetap', 'Istri', 'A'),
(1, '1207238505100003', 'RINA SUMAIDI', 'Perempuan', 'MEDAN', '2005-05-15', 'Islam', 'Belum Kawin', 'SMA', 'Pelajar', 'Tetap', 'Anak', 'O'),
(1, '1207239605110004', 'RINO SUMAIDI', 'Laki-laki', 'MEDAN', '2006-05-20', 'Islam', 'Belum Kawin', 'SMP', 'Pelajar', 'Tetap', 'Anak', 'B'),

-- Keluarga 2: SUJOKO
(2, '1271211603230001', 'SUJOKO', 'Laki-laki', 'MEDAN', '1963-03-16', 'Islam', 'Kawin', 'SMP', 'Wiraswasta', 'Tetap', 'Kepala Keluarga', 'A'),
(2, '1271213003220002', 'ENDAH WIJAYA', 'Perempuan', 'MEDAN', '1988-03-30', 'Islam', 'Kawin', 'SMA', 'Karyawan Swasta', 'Tetap', 'Istri', 'B'),
(2, '1271218505080003', 'DEDI SUJOKO', 'Laki-laki', 'MEDAN', '2008-05-18', 'Islam', 'Belum Kawin', 'SMP', 'Pelajar', 'Tetap', 'Anak', 'A'),

-- Keluarga 3: SUTRISNO
(3, '1271062803190001', 'SUTRISNO', 'Laki-laki', 'MEDAN', '1980-03-28', 'Islam', 'Kawin', 'SMA', 'Karyawan Swasta', 'Tetap', 'Kepala Keluarga', 'O'),
(3, '1271063008200002', 'YANTI HARTONO', 'Perempuan', 'MEDAN', '2000-08-30', 'Islam', 'Kawin', 'D3', 'Perawat', 'Tetap', 'Istri', 'AB'),
(3, '1271068505150003', 'ANITA SUTRISNO', 'Perempuan', 'MEDAN', '2005-05-18', 'Islam', 'Belum Kawin', 'SMA', 'Pelajar', 'Tetap', 'Anak', 'O'),

-- Keluarga 4: SUPARDI
(4, '1271062701140001', 'SUPARDI', 'Laki-laki', 'MEDAN', '1977-01-27', 'Islam', 'Kawin', 'SMK', 'Teknisi', 'Tetap', 'Kepala Keluarga', 'B'),
(4, '1271063105190002', 'LINDA KUSUMA', 'Perempuan', 'MEDAN', '1991-05-31', 'Islam', 'Kawin', 'SMA', 'Ibu Rumah Tangga', 'Tetap', 'Istri', 'O'),
(4, '1271068706100003', 'REZA SUPARDI', 'Laki-laki', 'MEDAN', '2010-06-20', 'Islam', 'Belum Kawin', 'SD', 'Pelajar', 'Tetap', 'Anak', 'B'),

-- Keluarga 5: HERMAWAN
(5, '1270812702030001', 'HERMAWAN', 'Laki-laki', 'MEDAN', '1985-02-27', 'Islam', 'Kawin', 'SMA', 'Pengusaha', 'Tetap', 'Kepala Keluarga', 'A'),
(5, '1270813105200002', 'RINI SETIAWAN', 'Perempuan', 'MEDAN', '1991-05-31', 'Islam', 'Kawin', 'SMA', 'Ibu Rumah Tangga', 'Tetap', 'Istri', 'O'),
(5, '1270818605220003', 'AFNAN HERMAWAN', 'Laki-laki', 'MEDAN', '2006-05-18', 'Islam', 'Belum Kawin', 'SMP', 'Pelajar', 'Tetap', 'Anak', 'A'),

-- Keluarga 6: BAMBANG
(6, '1271831605150001', 'BAMBANG SUSANTO', 'Laki-laki', 'MEDAN', '1984-05-16', 'Islam', 'Kawin', 'S1', 'Guru', 'Tetap', 'Kepala Keluarga', 'O'),
(6, '1271833008190002', 'ANI RAHAYU', 'Perempuan', 'MEDAN', '1990-08-30', 'Islam', 'Kawin', 'S1', 'Guru', 'Tetap', 'Istri', 'A'),

-- Keluarga 7: HENDRA
(7, '1272031708180001', 'HENDRA WIJAYA', 'Laki-laki', 'MEDAN', '1982-08-17', 'Islam', 'Kawin', 'SMA', 'Karyawan Swasta', 'Tetap', 'Kepala Keluarga', 'B'),
(7, '1272033105200002', 'DEWI LESTARI', 'Perempuan', 'MEDAN', '1992-05-31', 'Islam', 'Kawin', 'SMA', 'Ibu Rumah Tangga', 'Tetap', 'Istri', 'O'),

-- Keluarga 8: AHMAD
(8, '1270512603090001', 'AHMAD SANTOSO', 'Laki-laki', 'MEDAN', '1980-03-26', 'Islam', 'Kawin', 'SMP', 'Petani', 'Tetap', 'Kepala Keluarga', 'A'),
(8, '1270513209130002', 'NURUL HIDAYAH', 'Perempuan', 'MEDAN', '1992-09-32', 'Islam', 'Kawin', 'SMP', 'Ibu Rumah Tangga', 'Tetap', 'Istri', 'B'),

-- Keluarga 9: TAUFIK
(9, '1270712604120001', 'TAUFIK HIDAYAT', 'Laki-laki', 'MEDAN', '1978-04-12', 'Islam', 'Kawin', 'SMA', 'Karyawan Swasta', 'Tetap', 'Kepala Keluarga', 'O'),
(9, '1270713110180002', 'NURHAYATI', 'Perempuan', 'MEDAN', '1988-10-31', 'Islam', 'Kawin', 'SMA', 'Ibu Rumah Tangga', 'Tetap', 'Istri', 'A'),

-- Keluarga 10: DEDI
(10, '1271311609140001', 'DEDI GUNAWAN', 'Laki-laki', 'MEDAN', '1981-09-16', 'Islam', 'Kawin', 'SMA', 'Karyawan Swasta', 'Tetap', 'Kepala Keluarga', 'B'),
(10, '1271313104200002', 'SISKA PURNAMA', 'Perempuan', 'MEDAN', '1992-04-31', 'Islam', 'Kawin', 'SMA', 'Ibu Rumah Tangga', 'Tetap', 'Istri', 'O');

-- ===================================================
-- INSERT: Verifikasi Log
-- ===================================================
INSERT INTO verifikasi (id_keluarga, tanggal_verifikasi, status, petugas_verifikasi, catatan) VALUES
(1, '2026-01-02 14:00:00', 'terverifikasi', 'Verifikator A', 'Data valid, semua dokumen lengkap'),
(2, '2026-01-02 15:30:00', 'terverifikasi', 'Verifikator B', 'Data valid, sudah diverifikasi'),
(3, '2026-01-02 16:00:00', 'terverifikasi', 'Verifikator A', 'Data diterima, semua lengkap'),
(4, '2026-01-03 09:00:00', 'terverifikasi', 'Verifikator C', 'Data valid'),
(6, '2026-01-03 14:00:00', 'terverifikasi', 'Verifikator B', 'Semua dokumen lengkap'),
(7, '2026-01-03 15:30:00', 'terverifikasi', 'Verifikator A', 'Data sudah verified'),
(8, '2026-01-04 13:00:00', 'ditolak', 'Verifikator C', 'Alamat tidak jelas, data tidak sesuai'),
(10, '2026-01-04 16:00:00', 'terverifikasi', 'Verifikator B', 'Data berhasil diverifikasi');

-- ===================================================
-- INSERT: Default Admin User
-- ===================================================
INSERT INTO user (username, password, email, nama_lengkap, role, status) VALUES
('admin', SHA2('admin123', 256), 'admin@diskominfo.medan.go.id', 'Administrator', 'admin', 'active'),
('petugas1', SHA2('petugas123', 256), 'petugas1@diskominfo.medan.go.id', 'Petugas Survei 1', 'petugas', 'active'),
('petugas2', SHA2('petugas123', 256), 'petugas2@diskominfo.medan.go.id', 'Petugas Survei 2', 'petugas', 'active'),
('viewer', SHA2('viewer123', 256), 'viewer@diskominfo.medan.go.id', 'Viewer Dashboard', 'viewer', 'active');

-- ===================================================
-- CREATE VIEWS untuk Analytics
-- ===================================================

-- View: Ringkasan Data per Kecamatan
CREATE OR REPLACE VIEW view_ringkasan_kecamatan AS
SELECT 
    k.nama_kecamatan,
    COUNT(DISTINCT kl.id_keluarga) as total_keluarga,
    COUNT(DISTINCT p.id_penduduk) as total_penduduk,
    SUM(CASE WHEN kl.status_verifikasi = 'terverifikasi' THEN 1 ELSE 0 END) as terverifikasi,
    SUM(CASE WHEN kl.status_verifikasi = 'pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN kl.status_verifikasi = 'ditolak' THEN 1 ELSE 0 END) as ditolak
FROM kecamatan k
LEFT JOIN keluarga kl ON k.id_kecamatan = kl.kecamatan_id
LEFT JOIN penduduk p ON kl.id_keluarga = p.id_keluarga
GROUP BY k.id_kecamatan, k.nama_kecamatan;

-- View: Distribusi Agama
CREATE OR REPLACE VIEW view_distribusi_agama AS
SELECT 
    agama,
    COUNT(*) as jumlah,
    ROUND((COUNT(*) / (SELECT COUNT(*) FROM penduduk)) * 100, 2) as persentase
FROM penduduk
GROUP BY agama
ORDER BY jumlah DESC;

-- View: Top Pekerjaan
CREATE OR REPLACE VIEW view_top_pekerjaan AS
SELECT 
    CASE WHEN pekerjaan IS NULL OR pekerjaan = '' THEN 'Belum Bekerja' ELSE pekerjaan END as pekerjaan,
    COUNT(*) as jumlah,
    ROUND((COUNT(*) / (SELECT COUNT(*) FROM penduduk WHERE status_penduduk = 'Tetap')) * 100, 2) as persentase
FROM penduduk
WHERE status_penduduk = 'Tetap'
GROUP BY pekerjaan
ORDER BY jumlah DESC
LIMIT 10;

-- View: Status Verifikasi
CREATE OR REPLACE VIEW view_status_verifikasi AS
SELECT 
    status_verifikasi,
    COUNT(*) as jumlah,
    ROUND((COUNT(*) / (SELECT COUNT(*) FROM keluarga)) * 100, 2) as persentase
FROM keluarga
GROUP BY status_verifikasi;

-- ===================================================
-- Indexes untuk Performance Optimization
-- ===================================================
CREATE INDEX idx_keluarga_tanggal ON keluarga(tanggal_input);
CREATE INDEX idx_keluarga_verifikasi_oleh ON keluarga(verifikasi_oleh);
CREATE INDEX idx_penduduk_tanggal ON penduduk(tanggal_input);
CREATE INDEX idx_penduduk_status_penduduk ON penduduk(status_penduduk);
CREATE INDEX idx_verifikasi_tanggal ON verifikasi(tanggal_verifikasi);
CREATE INDEX idx_aktivitas_tanggal ON aktivitas(tanggal_aktivitas);

-- ===================================================
-- END OF DATABASE SETUP
-- ===================================================
-- Total Tables: 9 (keluarga, penduduk, verifikasi, kelurahan, kecamatan, user, aktivitas)
-- Total Views: 4
-- Sample Data: 10 keluarga, 30 penduduk
-- Ready for integration with dashboard
