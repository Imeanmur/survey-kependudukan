-- Create Database
CREATE DATABASE IF NOT EXISTS survey_kependudukan;
USE survey_kependudukan;

-- Tabel Keluarga
CREATE TABLE IF NOT EXISTS keluarga (
    id_keluarga INT PRIMARY KEY AUTO_INCREMENT,
    no_kartu_keluarga VARCHAR(16) NOT NULL UNIQUE,
    kepala_keluarga VARCHAR(100) NOT NULL,
    alamat TEXT NOT NULL,
    kelurahan VARCHAR(100),
    kecamatan VARCHAR(100),
    rt VARCHAR(3),
    rw VARCHAR(3),
    tanggal_input DATETIME DEFAULT CURRENT_TIMESTAMP,
    status_verifikasi ENUM('pending', 'terverifikasi', 'ditolak') DEFAULT 'pending',
    keterangan TEXT
);

-- Tabel Penduduk
CREATE TABLE IF NOT EXISTS penduduk (
    id_penduduk INT PRIMARY KEY AUTO_INCREMENT,
    id_keluarga INT NOT NULL,
    nik VARCHAR(16) NOT NULL UNIQUE,
    nama_lengkap VARCHAR(100) NOT NULL,
    jenis_kelamin ENUM('Laki-laki', 'Perempuan') NOT NULL,
    tempat_lahir VARCHAR(100),
    tanggal_lahir DATE,
    agama ENUM('Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu') NOT NULL,
    status_perkawinan ENUM('Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati') NOT NULL,
    pendidikan_terakhir VARCHAR(50),
    pekerjaan VARCHAR(100),
    status_penduduk ENUM('Tetap', 'Sementara') NOT NULL,
    hubungan_keluarga VARCHAR(50),
    tanggal_input DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_keluarga) REFERENCES keluarga(id_keluarga)
);

-- Tabel Verifikasi
CREATE TABLE IF NOT EXISTS verifikasi (
    id_verifikasi INT PRIMARY KEY AUTO_INCREMENT,
    id_keluarga INT NOT NULL,
    tanggal_verifikasi DATETIME DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50),
    petugas_verifikasi VARCHAR(100),
    catatan TEXT,
    FOREIGN KEY (id_keluarga) REFERENCES keluarga(id_keluarga)
);

-- Index untuk performance
CREATE INDEX idx_keluarga_status ON keluarga(status_verifikasi);
CREATE INDEX idx_keluarga_kecamatan ON keluarga(kecamatan);
CREATE INDEX idx_penduduk_keluarga ON penduduk(id_keluarga);

-- Insert Sample Data
INSERT INTO keluarga (no_kartu_keluarga, kepala_keluarga, alamat, kelurahan, kecamatan, rt, rw, status_verifikasi) VALUES
('1207231609094836', 'SUMAIDI', 'Jl. Anggrung', 'MEDAN POLONIA', 'MEDAN BARU', '01', '04', 'terverifikasi'),
('1271211603230011', 'SUJOKO', 'Jl. Titi Kuning', 'TITI KUNING', 'MEDAN JOHOR', '05', '02', 'terverifikasi'),
('1271062803190003', 'SUTRISNO', 'Jl. Asam Kumbang', 'ASAM KUMBANG', 'MEDAN SELAYANG', '03', '01', 'terverifikasi'),
('1271062701140003', 'SUPARDI', 'Jl. Belawan II', 'BELAWAN II', 'MEDAN BELAWAN', '02', '03', 'terverifikasi'),
('1270812702030002', 'HERMAWAN', 'Jl. Gatot Subroto', 'DARAT', 'MEDAN MAIMUN', '04', '05', 'pending');

INSERT INTO penduduk (id_keluarga, nik, nama_lengkap, jenis_kelamin, tempat_lahir, tanggal_lahir, agama, status_perkawinan, pendidikan_terakhir, pekerjaan, status_penduduk, hubungan_keluarga) VALUES
(1, '1207231609094001', 'SUMAIDI', 'Laki-laki', 'MEDAN', '1969-09-16', 'Islam', 'Kawin', 'SMA', 'Pegawai Negeri Sipil', 'Tetap', 'Kepala Keluarga'),
(1, '1207233003090002', 'SITI MURNI', 'Perempuan', 'MEDAN', '1990-03-30', 'Islam', 'Kawin', 'SMA', 'Ibu Rumah Tangga', 'Tetap', 'Istri'),
(2, '1271211603230001', 'SUJOKO', 'Laki-laki', 'MEDAN', '1963-03-16', 'Islam', 'Kawin', 'SMP', 'Wiraswasta', 'Tetap', 'Kepala Keluarga'),
(3, '1271062803190001', 'SUTRISNO', 'Laki-laki', 'MEDAN', '1980-03-28', 'Islam', 'Kawin', 'SMA', 'Karyawan Swasta', 'Tetap', 'Kepala Keluarga'),
(4, '1271062701140001', 'SUPARDI', 'Laki-laki', 'MEDAN', '1977-01-27', 'Islam', 'Kawin', 'SMK', 'Teknisi', 'Tetap', 'Kepala Keluarga');
