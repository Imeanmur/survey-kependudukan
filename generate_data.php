<?php
require_once 'includes/config.php';

echo "=== GENERATING MORE DATA ===\n\n";

// Data keluarga
$kecamatans = ['MEDAN BARU', 'MEDAN JOHOR', 'MEDAN SELAYANG', 'MEDAN BELAWAN', 'MEDAN MAIMUN'];
$kelurahans = ['MEDAN POLONIA', 'TITI KUNING', 'ASAM KUMBANG', 'BELAWAN II', 'DARAT', 'PETISAH', 'BINTANG', 'AMPLAS'];
$kepala_keluargas = ['AHMAD', 'BUDI', 'CITRA', 'DIDI', 'EKA', 'FAJAR', 'GINA', 'HENDRA', 'IRENE', 'JOKO', 'KAREN', 'LINA', 'MAMAN', 'NINA', 'OKTA'];
$status_keluarga = ['pending', 'terverifikasi', 'ditolak'];

// Clear existing data (except first 5)
$conn->query("DELETE FROM penduduk WHERE id_keluarga > 5");
$conn->query("DELETE FROM keluarga WHERE id_keluarga > 5");

// Insert additional keluarga (50 more)
$insertCount = 0;
for ($i = 0; $i < 50; $i++) {
    $no_kk = '12' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT) . str_pad(rand(1, 9999), 6, '0', STR_PAD_LEFT);
    $kepala = $kepala_keluargas[rand(0, count($kepala_keluargas) - 1)];
    $alamat = 'Jl. ' . $kepala . ' ' . rand(1, 150);
    $kelurahan = $kelurahans[rand(0, count($kelurahans) - 1)];
    $kecamatan = $kecamatans[rand(0, count($kecamatans) - 1)];
    $status = $status_keluarga[rand(0, 2)];
    
    $query = "INSERT INTO keluarga (no_kartu_keluarga, kepala_keluarga, alamat, kelurahan, kecamatan, rt, rw, status_verifikasi, tanggal_input) 
              VALUES ('$no_kk', '$kepala - $i', '$alamat', '$kelurahan', '$kecamatan', '".rand(1,10)."', '".rand(1,20)."', '$status', NOW())";
    
    if ($conn->query($query)) {
        $insertCount++;
        $kk_id = $conn->insert_id;
        
        // Insert 1-3 penduduk per keluarga
        $jumlah_penduduk = rand(1, 3);
        for ($j = 0; $j < $jumlah_penduduk; $j++) {
            $nik = '12' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT) . str_pad(rand(1, 9999999), 7, '0', STR_PAD_LEFT);
            $nama = $kepala_keluargas[rand(0, count($kepala_keluargas) - 1)] . ' ' . chr(rand(65, 90));
            $jenkel = rand(0, 1) ? 'Laki-laki' : 'Perempuan';
            $agama_list = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];
            $agama = $agama_list[rand(0, 5)];
            $kawin_list = ['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'];
            $kawin = $kawin_list[rand(0, 3)];
            $pendidikan_list = ['SD', 'SMP', 'SMA', 'SMK', 'D3', 'S1', 'S2'];
            $pendidikan = $pendidikan_list[rand(0, 6)];
            $pekerjaan_list = ['Pegawai Negeri Sipil', 'Karyawan Swasta', 'Wiraswasta', 'Petani', 'Nelayan', 'Pedagang', 'Ibu Rumah Tangga', 'Pelajar', 'Pensiunan', 'Buruh', 'Sopir', 'Tukang'];
            $pekerjaan = $pekerjaan_list[rand(0, 11)];
            $hubungan_list = ['Kepala Keluarga', 'Istri', 'Anak', 'Orang Tua', 'Menantu', 'Cucu', 'Pembantu'];
            $hubungan = $hubungan_list[rand(0, 6)];
            
            $q = "INSERT INTO penduduk (id_keluarga, nik, nama_lengkap, jenis_kelamin, tempat_lahir, tanggal_lahir, agama, status_perkawinan, pendidikan_terakhir, pekerjaan, status_penduduk, hubungan_keluarga, tanggal_input)
                  VALUES ($kk_id, '$nik', '$nama', '$jenkel', 'MEDAN', '".rand(1970, 2010)."-".str_pad(rand(1,12),2,'0',STR_PAD_LEFT)."-".str_pad(rand(1,28),2,'0',STR_PAD_LEFT)."', '$agama', '$kawin', '$pendidikan', '$pekerjaan', 'Tetap', '$hubungan', NOW())";
            
            $conn->query($q);
        }
    }
}

echo "✅ Inserted $insertCount keluarga records\n";
echo "✅ Database now populated with " . ($insertCount + 5) . " keluarga\n";

// Show summary
$kk_count = $conn->query("SELECT COUNT(*) as cnt FROM keluarga")->fetch_assoc()['cnt'];
$p_count = $conn->query("SELECT COUNT(*) as cnt FROM penduduk")->fetch_assoc()['cnt'];
echo "\n=== SUMMARY ===\n";
echo "Total Keluarga: $kk_count\n";
echo "Total Penduduk: $p_count\n";
echo "\nData ready for dashboard!\n";

?>
