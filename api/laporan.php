<?php
session_start();
require_once '../includes/config.php';

// Cek login
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.html");
    exit();
}

$tipe = isset($_GET['tipe']) ? $_GET['tipe'] : '';
$kecamatan = isset($_GET['kecamatan']) ? $_GET['kecamatan'] : '';
$tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : '';
$tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : '';
$format = isset($_GET['format']) ? $_GET['format'] : 'html'; // html or csv

if (empty($tipe)) {
    die("Tipe laporan harus dipilih.");
}

// Build Query
$where = "WHERE 1=1";
$params = [];
$types = "";

if (!empty($kecamatan)) {
    $where .= " AND k.kecamatan = ?";
    $params[] = $kecamatan;
    $types .= "s";
}

if (!empty($tanggal_awal)) {
    $where .= " AND DATE(k.tanggal_input) >= ?";
    $params[] = $tanggal_awal;
    $types .= "s";
}

if (!empty($tanggal_akhir)) {
    $where .= " AND DATE(k.tanggal_input) <= ?";
    $params[] = $tanggal_akhir;
    $types .= "s";
}

$data = [];
$headers = [];
$title = "";

if ($tipe === 'keluarga') {
    $title = "Laporan Data Keluarga";
    $query = "SELECT k.no_kartu_keluarga, k.kepala_keluarga, k.alamat, k.kecamatan, 
              (SELECT COUNT(*) FROM penduduk WHERE id_keluarga = k.id_keluarga) as jumlah_anggota,
              k.status_verifikasi, k.tanggal_input
              FROM keluarga k 
              $where 
              ORDER BY k.tanggal_input DESC";
              
    $headers = ['No. KK', 'Kepala Keluarga', 'Alamat', 'Kecamatan', 'Jml Anggota', 'Status', 'Tanggal Input'];
    
} elseif ($tipe === 'penduduk') {
    $title = "Laporan Data Penduduk";
    // For penduduk, we join with keluarga to filter by kecamatan/date if needed
    // Assuming filters apply to the Family input date or we could check residents input date
    // Let's assume filters apply to Family's location (Kecamatan) and Resident's input date if available, or family's.
    // 'penduduk' table has 'tanggal_input' as well (from api/penduduk.php line 29).
    // But 'kecamatan' is in 'keluarga' table.
    
    $wherePenduduk = "WHERE 1=1";
    $paramsPenduduk = [];
    $typesPenduduk = "";

    if (!empty($kecamatan)) {
        $wherePenduduk .= " AND k.kecamatan = ?";
        $paramsPenduduk[] = $kecamatan;
        $typesPenduduk .= "s";
    }

    if (!empty($tanggal_awal)) {
        $wherePenduduk .= " AND DATE(p.tanggal_input) >= ?";
        $paramsPenduduk[] = $tanggal_awal;
        $typesPenduduk .= "s";
    }

    if (!empty($tanggal_akhir)) {
        $wherePenduduk .= " AND DATE(p.tanggal_input) <= ?";
        $paramsPenduduk[] = $tanggal_akhir;
        $typesPenduduk .= "s";
    }

    $query = "SELECT p.nik, p.nama_lengkap, p.jenis_kelamin, p.tempat_lahir, p.tanggal_lahir, 
              p.agama, p.status_perkawinan, p.pekerjaan, k.kecamatan
              FROM penduduk p
              JOIN keluarga k ON p.id_keluarga = k.id_keluarga
              $wherePenduduk
              ORDER BY p.tanggal_input DESC";

    $headers = ['NIK', 'Nama Lengkap', 'L/P', 'TTL', 'Agama', 'Status Kawin', 'Pekerjaan', 'Kecamatan'];
    
    // Override params for penduduk query
    $params = $paramsPenduduk;
    $types = $typesPenduduk;

} elseif ($tipe === 'statistik') {
    $title = "Laporan Statistik";
    // Simple summary
    // This requires a different approach, maybe just HTML output directly or a simple array
    // Let's just do a summary of Key Metrics
    $headers = ['Metrik', 'Nilai'];
    
    // We can't easily use the generic query builder below for this one.
    // Let's handle it separately.
}

// Execute Query
if ($tipe !== 'statistik') {
    $stmt = $conn->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        if ($tipe === 'keluarga') {
            $data[] = [
                $row['no_kartu_keluarga'],
                $row['kepala_keluarga'],
                $row['alamat'],
                $row['kecamatan'],
                $row['jumlah_anggota'],
                ucfirst($row['status_verifikasi']),
                $row['tanggal_input']
            ];
        } elseif ($tipe === 'penduduk') {
            $ttl = $row['tempat_lahir'] . ', ' . date('d-m-Y', strtotime($row['tanggal_lahir']));
            $data[] = [
                $row['nik'],
                $row['nama_lengkap'],
                $row['jenis_kelamin'],
                $ttl,
                $row['agama'],
                $row['status_perkawinan'],
                $row['pekerjaan'],
                $row['kecamatan']
            ];
        }
    }
} else {
    // Statistik logic
    // 1. Total Keluarga
    $q = "SELECT COUNT(*) as total FROM keluarga k $where";
    $stmt = $conn->prepare($q);
    if (!empty($params)) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $data[] = ['Total Keluarga', $stmt->get_result()->fetch_assoc()['total']];

    // 2. Total Penduduk (Need different where if date filter applies to penduduk input, but let's assume filtering families implies filtering their residents for now or just generic count in that area)
    // If filtering by Kecamatan, we can count residents in that kecamatan.
    $q2 = "SELECT COUNT(p.id_penduduk) as total FROM penduduk p JOIN keluarga k ON p.id_keluarga = k.id_keluarga $where";
    $stmt = $conn->prepare($q2);
    if (!empty($params)) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $data[] = ['Total Penduduk', $stmt->get_result()->fetch_assoc()['total']];
}

// OUTPUT
if ($format === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="laporan_' . $tipe . '_' . date('Y-m-d') . '.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, $headers);
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit();
}

// HTML Print Format
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1, h2 { text-align: center; margin-bottom: 5px; }
        .meta { text-align: center; margin-bottom: 20px; font-size: 11px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
        .no-print { margin-bottom: 20px; text-align: right; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print">
        <button onclick="window.print()">Cetak</button>
        <button onclick="window.close()">Tutup</button>
    </div>

    <h1>SURVEY KEPENDUDUKAN</h1>
    <h2><?= $title ?></h2>
    
    <div class="meta">
        <?php if($kecamatan): ?> Kecamatan: <strong><?= htmlspecialchars($kecamatan) ?></strong> | <?php endif; ?>
        <?php if($tanggal_awal || $tanggal_akhir): ?> 
            Periode: <strong><?= $tanggal_awal ?> s/d <?= $tanggal_akhir ?></strong>
        <?php else: ?>
            Dicetak pada: <?= date('d-m-Y H:i:s') ?>
        <?php endif; ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <?php foreach($headers as $h): ?>
                    <th><?= $h ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php if(count($data) > 0): ?>
                <?php foreach($data as $key => $row): ?>
                    <tr>
                        <td style="text-align: center; width: 30px;"><?= $key + 1 ?></td>
                        <?php foreach($row as $cell): ?>
                            <td><?= htmlspecialchars($cell) ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= count($headers) + 1 ?>" style="text-align: center;">Tidak ada data</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
