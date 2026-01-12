<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: /survey-kependudukan/login.html');
    exit;
}
$admin = $_SESSION['admin'];
?>
<?php /* The dashboard content mirrors the previous index.html */ ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Survey Kependudukan - Diskominfo Pemkot Medan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chart.js/3.9.1/chart.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-chart-bar"></i>
                    <span>Survey Kependudukan</span>
                </div>
                <p class="subtitle">Kota Medan</p>
            </div>

            <nav class="sidebar-nav">
                <a href="javascript:void(0);" class="nav-item active" data-menu="dashboard">
                    <i class="fas fa-chart-pie"></i>
                    <span>Dashboard</span>
                </a>
                <a href="javascript:void(0);" class="nav-item" data-menu="penduduk">
                    <i class="fas fa-users"></i>
                    <span>Penduduk</span>
                </a>
                <a href="javascript:void(0);" class="nav-item" data-menu="grafik">
                    <i class="fas fa-chart-line"></i>
                    <span>Grafik & Analisis</span>
                </a>
                <a href="javascript:void(0);" class="nav-item" data-menu="laporan">
                    <i class="fas fa-file-pdf"></i>
                    <span>Laporan</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="user-details">
                        <p class="user-name"><?php echo htmlspecialchars($admin['name']); ?></p>
                        <p class="user-role"><?php echo htmlspecialchars($admin['role']); ?></p>
                    </div>
                </div>
                <div style="margin-top:12px;">
                    <a href="/survey-kependudukan/auth/logout.php" class="btn" style="background:#fff;color:#333;border:1px solid #e9ecef;display:inline-flex;align-items:center;gap:8px;padding:8px 12px;border-radius:6px;text-decoration:none">
                        <i class="fas fa-sign-out-alt"></i> Keluar
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="top-header">
                <div class="header-left">
                    <button class="toggle-sidebar" id="toggleSidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 id="pageTitle">Dashboard</h1>
                </div>
                <div class="header-right">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchInput" placeholder="Cari nomor kartu atau nama...">
                    </div>
                    <button class="header-btn refresh-btn" id="refreshBtn" title="Refresh Data">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    <button class="header-btn notification-btn" title="Notifikasi">
                        <i class="fas fa-bell"></i>
                        <span class="badge">3</span>
                    </button>
                </div>
            </header>

            <!-- Content Area -->
            <div class="content-area">
                
                <!-- Dashboard Menu -->
                <div id="dashboardMenu" class="menu-content active">
                    <!-- Stats Cards -->
                    <div class="stats-grid">
                        <div class="stat-card gradient-blue">
                            <div class="stat-content">
                                <div class="stat-info">
                                    <p class="stat-label">Total Kartu Keluarga</p>
                                    <h2 class="stat-value" id="totalKartu">-</h2>
                                </div>
                                <div class="stat-icon">
                                    <i class="fas fa-id-card"></i>
                                </div>
                            </div>
                            <div class="stat-footer">
                                <span class="trend positive">
                                    <i class="fas fa-arrow-up"></i> Data Updated
                                </span>
                            </div>
                        </div>

                        <div class="stat-card gradient-green">
                            <div class="stat-content">
                                <div class="stat-info">
                                    <p class="stat-label">Total Penduduk</p>
                                    <h2 class="stat-value" id="totalPenduduk">-</h2>
                                </div>
                                <div class="stat-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                            <div class="stat-footer">
                                <span class="trend positive">
                                    <i class="fas fa-arrow-up"></i> Data Updated
                                </span>
                            </div>
                        </div>

                        <div class="stat-card gradient-orange">
                            <div class="stat-content">
                                <div class="stat-info">
                                    <p class="stat-label">Verifikasi Pending</p>
                                    <h2 class="stat-value" id="verifikasiPending">-</h2>
                                </div>
                                <div class="stat-icon">
                                    <i class="fas fa-hourglass-half"></i>
                                </div>
                            </div>
                            <div class="stat-footer">
                                <span class="trend neutral">
                                    <i class="fas fa-minus"></i> Menunggu
                                </span>
                            </div>
                        </div>

                        <div class="stat-card gradient-red">
                            <div class="stat-content">
                                <div class="stat-info">
                                    <p class="stat-label">Verifikasi Ditolak</p>
                                    <h2 class="stat-value" id="verifikasiDitolak">-</h2>
                                </div>
                                <div class="stat-icon">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                            </div>
                            <div class="stat-footer">
                                <span class="trend negative">
                                    <i class="fas fa-arrow-down"></i> Review
                                </span>
                            </div>
                        </div>

                        <div class="stat-card gradient-purple">
                            <div class="stat-content">
                                <div class="stat-info">
                                    <p class="stat-label">Terverifikasi</p>
                                    <h2 class="stat-value" id="verifikasiTerverifikasi">-</h2>
                                </div>
                                <div class="stat-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                            <div class="stat-footer">
                                <span class="trend positive">
                                    <i class="fas fa-arrow-up"></i> Approval
                                </span>
                            </div>
                        </div>

                        <div class="stat-card gradient-cyan">
                            <div class="stat-content">
                                <div class="stat-info">
                                    <p class="stat-label">Persentase Verifikasi</p>
                                    <h2 class="stat-value" id="persentaseVerifikasi">-</h2>
                                </div>
                                <div class="stat-icon">
                                    <i class="fas fa-percentage"></i>
                                </div>
                            </div>
                            <div class="stat-footer">
                                <span class="trend positive">
                                    <i class="fas fa-arrow-up"></i> Target
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Section -->
                    <div class="charts-section">
                        <div class="chart-container">
                            <div class="card">
                                <div class="card-header">
                                    <h3>Distribusi Kecamatan</h3>
                                    <button class="card-menu-btn">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <canvas id="chartKecamatan"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="chart-container">
                            <div class="card">
                                <div class="card-header">
                                    <h3>Agama Penduduk</h3>
                                    <button class="card-menu-btn">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <canvas id="chartAgama"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Terbaru Table -->
                    <div class="card">
                        <div class="card-header">
                            <h3><i class="fas fa-table"></i> Data Terbaru Masuk</h3>
                            <a href="#" class="link-more">Lihat Semua</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tabelDataTerbaru" class="table">
                                    <thead>
                                        <tr>
                                            <th>No. Kartu Keluarga</th>
                                            <th>Kepala Keluarga</th>
                                            <th>Kelurahan</th>
                                            <th>Kecamatan</th>
                                            <th>Anggota</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabelDataTerbaruBody">
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">
                                                <i class="fas fa-spinner fa-spin"></i> Loading...
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Penduduk Menu -->
                <div id="pendudukMenu" class="menu-content">
                    <div class="card">
                        <div class="card-header">
                            <h3><i class="fas fa-users"></i> Data Penduduk</h3>
                            <div class="card-tools">
                                <input type="text" id="searchPenduduk" placeholder="Cari penduduk..." class="search-input">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tabelPenduduk" class="table">
                                    <thead>
                                        <tr>
                                            <th>NIK</th>
                                            <th>Nama Lengkap</th>
                                            <th>No. KK</th>
                                            <th>Kelamin</th>
                                            <th>Agama</th>
                                            <th>Pekerjaan</th>
                                            <th>Status Kawin</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabelPendudukBody">
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">
                                                <i class="fas fa-spinner fa-spin"></i> Loading...
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Keluarga Menu -->
                <div id="keluargaDetailMenu" class="menu-content">
                    <div class="card">
                        <div class="card-header">
                            <h3><i class="fas fa-id-card"></i> Detail Kartu Keluarga</h3>
                            <div class="card-tools">
                                <button class="btn" id="btnBackDetail"><i class="fas fa-arrow-left"></i> Kembali</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="grid-2">
                                <div>
                                    <div class="info-item">
                                        <i class="fas fa-hashtag"></i>
                                        <div>
                                            <p class="label">No. KK</p>
                                            <p class="value" id="detailKK">-</p>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-user"></i>
                                        <div>
                                            <p class="label">Kepala Keluarga</p>
                                            <p class="value" id="detailKepala">-</p>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <div>
                                            <p class="label">Alamat</p>
                                            <p class="value" id="detailAlamat">-</p>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-city"></i>
                                        <div>
                                            <p class="label">Kelurahan / Kecamatan</p>
                                            <p class="value"><span id="detailKelurahan">-</span> / <span id="detailKecamatan">-</span></p>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-clipboard-check"></i>
                                        <div>
                                            <p class="label">Status Verifikasi</p>
                                            <p class="value" id="detailStatus">-</p>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>NIK</th>
                                                    <th>Nama</th>
                                                    <th>Hubungan</th>
                                                    <th>JK</th>
                                                    <th>Pekerjaan</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tabelAnggotaBody">
                                                <tr><td colspan="5" class="text-center text-muted"><i class="fas fa-spinner fa-spin"></i> Memuat...</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grafik Menu -->
                <div id="grafikMenu" class="menu-content">
                    <div class="charts-section">
                        <div class="chart-container full-width">
                            <div class="card">
                                <div class="card-header">
                                    <h3><i class="fas fa-chart-line"></i> Trend Input Data Per Bulan</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="chartTrendInput"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="chart-container full-width">
                            <div class="card">
                                <div class="card-header">
                                    <h3><i class="fas fa-chart-line"></i> Perbandingan Umur dan Gender</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="chartUmurGender"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="chart-container full-width">
                            <div class="card">
                                <div class="card-header">
                                    <h3>Distribusi Agama Penduduk</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="chartAgamaFull"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="chart-container full-width">
                            <div class="card">
                                <div class="card-header">
                                    <h3>Status Verifikasi Keluarga</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="chartVerifikasi"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="chart-container full-width">
                            <div class="card">
                                <div class="card-header">
                                    <h3>Pendidikan Terakhir Penduduk</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="chartPendidikan"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="chart-container full-width">
                            <div class="card">
                                <div class="card-header">
                                    <h3>Top 7 Pekerjaan Penduduk</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="chartPekerjaan"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="chart-container full-width">
                            <div class="card">
                                <div class="card-header">
                                    <h3>Perbandingan Data Keluarga per Kecamatan</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="chartKecamatanDetail"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Laporan Menu -->
                <div id="laporanMenu" class="menu-content">
                    <div class="grid-2">
                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-file-pdf"></i> Generate Laporan</h3>
                            </div>
                            <div class="card-body">
                                <form id="formLaporan">
                                    <div class="form-group">
                                        <label for="laporanTipe">Tipe Laporan</label>
                                        <select id="laporanTipe" class="form-control">
                                            <option value="">-- Pilih Tipe --</option>
                                            <option value="keluarga">Data Keluarga</option>
                                            <option value="penduduk">Data Penduduk</option>
                                            <option value="statistik">Statistik</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="laporanKecamatan">Kecamatan</label>
                                        <select id="laporanKecamatan" class="form-control">
                                            <option value="">-- Semua Kecamatan --</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="laporanTanggalAwal">Tanggal Awal</label>
                                        <input type="date" id="laporanTanggalAwal" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="laporanTanggalAkhir">Tanggal Akhir</label>
                                        <input type="date" id="laporanTanggalAkhir" class="form-control">
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-download"></i> Download Laporan
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-info-circle"></i> Informasi</h3>
                            </div>
                            <div class="card-body">
                                <div class="info-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <div>
                                        <p class="label">Tanggal Diperbarui</p>
                                        <p class="value" id="infoTanggalUpdate">-</p>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-database"></i>
                                    <div>
                                        <p class="label">Total Record</p>
                                        <p class="value" id="infoTotalRecord">-</p>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-check-square"></i>
                                    <div>
                                        <p class="label">Status Database</p>
                                        <p class="value" id="infoStatusDb">Connected</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script>
        window.chartLoadedFromCDN = false;
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js" defer onload="window.chartLoadedFromCDN = true;" onerror="console.log('CDN 1 failed, trying alternative...')"></script>
    <script>
        if (!window.chartLoadedFromCDN) {
            var fallbackScript = document.createElement('script');
            fallbackScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/chart.js/3.9.1/chart.min.js';
            fallbackScript.defer = true;
            fallbackScript.onload = function() { window.chartLoadedFromCDN = true; };
            document.body.appendChild(fallbackScript);
        }
    </script>
    <script src="assets/js/script.js"></script>
</body>
</html>
