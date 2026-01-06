<?php
require_once 'includes/config.php';

echo "=== DATABASE ANALYSIS ===\n\n";

// Check tables and record counts
echo "1. TABLE SUMMARY:\n";
$tables = $conn->query("SHOW TABLES");
$tableList = [];
while($row = $tables->fetch_row()) {
    $tableName = $row[0];
    $tableList[] = $tableName;
    $count = $conn->query("SELECT COUNT(*) as cnt FROM $tableName")->fetch_assoc();
    echo "  - " . str_pad($tableName, 20) . ": " . $count['cnt'] . " records\n";
}

echo "\n2. DATA SAMPLE:\n";
echo "  Keluarga Table:\n";
$keluarga = $conn->query("SELECT id_keluarga, no_kartu_keluarga, kepala_keluarga, kecamatan FROM keluarga LIMIT 3");
while($row = $keluarga->fetch_assoc()) {
    echo "    - " . $row['no_kartu_keluarga'] . " (" . $row['kepala_keluarga'] . ") - " . $row['kecamatan'] . "\n";
}

echo "  Penduduk Table:\n";
$penduduk = $conn->query("SELECT id_penduduk, nama, umur, agama FROM penduduk LIMIT 3");
while($row = $penduduk->fetch_assoc()) {
    echo "    - " . $row['nama'] . " (" . $row['umur'] . " tahun, " . $row['agama'] . ")\n";
}

echo "\n3. CHECK API DATA:\n";

// Test each chart API
$apis = [
    'get_data_by_kecamatan',
    'get_grafik_agama',
    'get_grafik_trend_input',
    'get_grafik_umur_gender',
    'get_grafik_pendidikan',
    'get_grafik_pekerjaan',
    'get_grafik_verifikasi'
];

foreach($apis as $api) {
    // Simulate API call by including file and calling function
    $action = $api;
    switch($action) {
        case 'get_data_by_kecamatan':
            global $conn;
            $query = "SELECT kl.kecamatan,
                      COUNT(DISTINCT kl.id_keluarga) as total_kartu
                      FROM keluarga kl
                      WHERE kl.kecamatan IS NOT NULL AND kl.kecamatan != ''
                      GROUP BY kl.kecamatan
                      ORDER BY total_kartu DESC";
            $result = $conn->query($query);
            $data = [];
            while($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            echo "  ✅ " . $api . " - " . count($data) . " records\n";
            break;
        case 'get_grafik_agama':
            $query = "SELECT agama, COUNT(*) as jumlah FROM penduduk GROUP BY agama ORDER BY jumlah DESC";
            $result = $conn->query($query);
            $data = [];
            while($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            echo "  ✅ " . $api . " - " . count($data) . " records\n";
            break;
        default:
            echo "  ℹ️  " . $api . " - Need to check separately\n";
    }
}

?>
