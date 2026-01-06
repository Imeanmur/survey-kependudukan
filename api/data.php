<?php
require_once '../includes/config.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch($action) {
    case 'get_stats':
        getStats();
        break;
    case 'get_data_terbaru':
        getDataTerbaru();
        break;
    case 'get_data_by_kecamatan':
        getDataByKecamatan();
        break;
    case 'get_grafik_agama':
        getGrafikAgama();
        break;
    case 'get_grafik_pekerjaan':
        getGrafikPekerjaan();
        break;
    case 'get_grafik_verifikasi':
        getGrafikVerifikasi();
        break;
    case 'get_grafik_trend_input':
        getGrafikTrendInput();
        break;
    case 'get_grafik_umur_gender':
        getGrafikUmurGender();
        break;
    case 'get_grafik_pendidikan':
        getGrafikPendidikan();
        break;
    case 'search_keluarga':
        searchKeluarga();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function getStats() {
    global $conn;
    
    $stats = array();
    
    // Total Kartu Keluarga
    $result = $conn->query("SELECT COUNT(*) as total FROM keluarga");
    $stats['total_kartu'] = $result->fetch_assoc()['total'];
    
    // Total Penduduk
    $result = $conn->query("SELECT COUNT(*) as total FROM penduduk");
    $stats['total_penduduk'] = $result->fetch_assoc()['total'];
    
    // Verifikasi Pending
    $result = $conn->query("SELECT COUNT(*) as total FROM keluarga WHERE status_verifikasi = 'pending'");
    $stats['verifikasi_pending'] = $result->fetch_assoc()['total'];
    
    // Verifikasi Terverifikasi
    $result = $conn->query("SELECT COUNT(*) as total FROM keluarga WHERE status_verifikasi = 'terverifikasi'");
    $stats['verifikasi_terverifikasi'] = $result->fetch_assoc()['total'];
    
    // Verifikasi Ditolak
    $result = $conn->query("SELECT COUNT(*) as total FROM keluarga WHERE status_verifikasi = 'ditolak'");
    $stats['verifikasi_ditolak'] = $result->fetch_assoc()['total'];
    
    // Verifikasi Revisi
    $result = $conn->query("SELECT COUNT(*) as total FROM keluarga WHERE status_verifikasi = 'revisi'");
    $stats['verifikasi_revisi'] = $result->fetch_assoc()['total'];
    
    // Total Kecamatan
    $result = $conn->query("SELECT COUNT(DISTINCT kecamatan) as total FROM keluarga WHERE kecamatan IS NOT NULL");
    $stats['total_kecamatan'] = $result->fetch_assoc()['total'];
    
    echo json_encode(['success' => true, 'data' => $stats]);
}

function getDataTerbaru() {
    global $conn;
    
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
    
    $query = "SELECT k.*, 
              (SELECT COUNT(*) FROM penduduk WHERE id_keluarga = k.id_keluarga) as jumlah_anggota,
              (SELECT GROUP_CONCAT(pekerjaan SEPARATOR ', ') FROM penduduk WHERE id_keluarga = k.id_keluarga AND pekerjaan IS NOT NULL LIMIT 2) as pekerjaan_list
              FROM keluarga k 
              WHERE k.status_verifikasi IS NOT NULL
              ORDER BY k.tanggal_input DESC 
              LIMIT $limit";
    
    $result = $conn->query($query);
    $data = array();
    
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    
    echo json_encode(['success' => true, 'data' => $data, 'total' => count($data)]);
}

function getDataByKecamatan() {
    global $conn;
    
    // Get data directly from keluarga and penduduk, grouped by kecamatan field
    $query = "SELECT kl.kecamatan,
              COUNT(DISTINCT kl.id_keluarga) as total_kartu,
              COUNT(DISTINCT p.id_penduduk) as total_penduduk,
              SUM(CASE WHEN kl.status_verifikasi = 'terverifikasi' THEN 1 ELSE 0 END) as terverifikasi,
              SUM(CASE WHEN kl.status_verifikasi = 'pending' THEN 1 ELSE 0 END) as pending
              FROM keluarga kl
              LEFT JOIN penduduk p ON kl.id_keluarga = p.id_keluarga
              WHERE kl.kecamatan IS NOT NULL AND kl.kecamatan != ''
              GROUP BY kl.kecamatan
              ORDER BY total_kartu DESC";
    
    $result = $conn->query($query);
    if(!$result) {
        echo json_encode(['success' => false, 'message' => $conn->error]);
        return;
    }
    
    $data = array();
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    
    echo json_encode(['success' => true, 'data' => $data]);
}

function getGrafikAgama() {
    global $conn;
    
    $query = "SELECT agama, COUNT(*) as jumlah 
              FROM penduduk 
              GROUP BY agama 
              ORDER BY jumlah DESC";
    
    $result = $conn->query($query);
    $data = array();
    $labels = array();
    $values = array();
    
    while($row = $result->fetch_assoc()) {
        $labels[] = $row['agama'];
        $values[] = (int)$row['jumlah'];
    }
    
    echo json_encode(['success' => true, 'labels' => $labels, 'data' => $values]);
}

function getGrafikPekerjaan() {
    global $conn;
    
    $query = "SELECT pekerjaan, COUNT(*) as jumlah 
              FROM penduduk 
              WHERE pekerjaan IS NOT NULL AND pekerjaan != ''
              GROUP BY pekerjaan 
              ORDER BY jumlah DESC 
              LIMIT 10";
    
    $result = $conn->query($query);
    $data = array();
    $labels = array();
    $values = array();
    
    while($row = $result->fetch_assoc()) {
        $labels[] = $row['pekerjaan'];
        $values[] = (int)$row['jumlah'];
    }
    
    echo json_encode(['success' => true, 'labels' => $labels, 'data' => $values]);
}

function searchKeluarga() {
    global $conn;
    
    $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
    
    if(strlen($search) < 2) {
        echo json_encode(['success' => false, 'message' => 'Minimal 2 karakter']);
        return;
    }
    
    $query = "SELECT k.*, 
              (SELECT COUNT(*) FROM penduduk WHERE id_keluarga = k.id_keluarga) as jumlah_anggota
              FROM keluarga k 
              WHERE k.no_kartu_keluarga LIKE '%$search%' 
              OR k.kepala_keluarga LIKE '%$search%'
              OR k.alamat LIKE '%$search%'
              OR k.kecamatan LIKE '%$search%'
              LIMIT 30";
    
    $result = $conn->query($query);
    $data = array();
    
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    
    echo json_encode(['success' => true, 'data' => $data, 'total' => count($data)]);
}

function getGrafikVerifikasi() {
    global $conn;
    
    $query = "SELECT 
              status_verifikasi,
              COUNT(*) as jumlah
              FROM keluarga
              GROUP BY status_verifikasi
              ORDER BY jumlah DESC";
    
    $result = $conn->query($query);
    $labels = array();
    $values = array();
    
    while($row = $result->fetch_assoc()) {
        $status = $row['status_verifikasi'];
        $label = ucfirst(str_replace('_', ' ', $status));
        $labels[] = $label;
        $values[] = (int)$row['jumlah'];
    }
    
    echo json_encode(['success' => true, 'labels' => $labels, 'data' => $values]);
}

function getKecamatanList() {
    global $conn;
    
    $query = "SELECT DISTINCT nama_kecamatan 
              FROM kecamatan 
              ORDER BY nama_kecamatan ASC";
    
    $result = $conn->query($query);
    $data = array();
    
    while($row = $result->fetch_assoc()) {
        $data[] = $row['nama_kecamatan'];
    }
    
    echo json_encode(['success' => true, 'data' => $data]);
}

// Grafik Garis - Trend Input Data Per Bulan
function getGrafikTrendInput() {
    global $conn;
    
    $query = "SELECT 
              DATE_FORMAT(tanggal_input, '%Y-%m') as bulan,
              DATE_FORMAT(tanggal_input, '%M %Y') as bulan_label,
              COUNT(*) as total_input,
              SUM(CASE WHEN status_verifikasi = 'terverifikasi' THEN 1 ELSE 0 END) as terverifikasi
              FROM keluarga
              WHERE tanggal_input IS NOT NULL
              GROUP BY DATE_FORMAT(tanggal_input, '%Y-%m')
              ORDER BY DATE_FORMAT(tanggal_input, '%Y-%m') ASC";
    
    $result = $conn->query($query);
    $labels = array();
    $dataInput = array();
    $dataVerifikasi = array();
    
    while($row = $result->fetch_assoc()) {
        $labels[] = $row['bulan_label'];
        $dataInput[] = (int)$row['total_input'];
        $dataVerifikasi[] = (int)$row['terverifikasi'];
    }
    
    echo json_encode([
        'success' => true, 
        'labels' => $labels, 
        'datasets' => [
            'input' => $dataInput,
            'verifikasi' => $dataVerifikasi
        ]
    ]);
}

// Grafik Garis - Perbandingan Umur dan Gender
function getGrafikUmurGender() {
    global $conn;
    
    // Hitung umur dari tanggal lahir
    $query = "SELECT 
              CASE 
                  WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) < 5 THEN '0-5 Tahun'
                  WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) < 12 THEN '6-11 Tahun'
                  WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) < 18 THEN '12-17 Tahun'
                  WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) < 30 THEN '18-29 Tahun'
                  WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) < 45 THEN '30-44 Tahun'
                  WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) < 60 THEN '45-59 Tahun'
                  ELSE '60+ Tahun'
              END as kelompok_umur,
              jenis_kelamin,
              COUNT(*) as jumlah
              FROM penduduk
              WHERE tanggal_lahir IS NOT NULL
              GROUP BY kelompok_umur, jenis_kelamin
              ORDER BY FIELD(kelompok_umur, '0-5 Tahun', '6-11 Tahun', '12-17 Tahun', '18-29 Tahun', '30-44 Tahun', '45-59 Tahun', '60+ Tahun'), jenis_kelamin";
    
    $result = $conn->query($query);
    $labels = array();
    $dataLaki = array();
    $dataPerempuan = array();
    $currentLabel = '';
    
    while($row = $result->fetch_assoc()) {
        if ($currentLabel !== $row['kelompok_umur']) {
            $currentLabel = $row['kelompok_umur'];
            $labels[] = $currentLabel;
            $dataLaki[] = 0;
            $dataPerempuan[] = 0;
        }
        
        $lastIdx = count($labels) - 1;
        if (strtolower($row['jenis_kelamin']) === 'laki-laki' || $row['jenis_kelamin'] === 'L') {
            $dataLaki[$lastIdx] = (int)$row['jumlah'];
        } else {
            $dataPerempuan[$lastIdx] = (int)$row['jumlah'];
        }
    }
    
    echo json_encode([
        'success' => true,
        'labels' => $labels,
        'datasets' => [
            'laki' => $dataLaki,
            'perempuan' => $dataPerempuan
        ]
    ]);
}

// Grafik Garis - Pendidikan Terakhir
function getGrafikPendidikan() {
    global $conn;
    
    $query = "SELECT 
              pendidikan_terakhir,
              COUNT(*) as jumlah
              FROM penduduk
              WHERE pendidikan_terakhir IS NOT NULL AND pendidikan_terakhir != ''
              GROUP BY pendidikan_terakhir
              ORDER BY jumlah DESC";
    
    $result = $conn->query($query);
    $labels = array();
    $values = array();
    
    while($row = $result->fetch_assoc()) {
        $labels[] = $row['pendidikan_terakhir'];
        $values[] = (int)$row['jumlah'];
    }
    
    echo json_encode([
        'success' => true,
        'labels' => $labels,
        'data' => $values
    ]);
}
?>
