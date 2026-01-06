// API Base URL
const API_BASE = './api/';

// Chart instances
let chartKecamatan, chartAgama, chartAgamaFull, chartPekerjaan, chartKecamatanDetail;
let chartTrendInput, chartUmurGender, chartVerifikasi, chartPendidikan;

// Wait for Chart.js to be available
function waitForChart(maxWait = 20000) {  // Increased timeout from 10s to 20s
    return new Promise((resolve) => {
        const startTime = Date.now();
        const checkChart = () => {
            if (typeof Chart !== 'undefined') {
                console.log('✅ Chart.js loaded successfully');
                resolve(true);
            } else if (Date.now() - startTime < maxWait) {
                setTimeout(checkChart, 200);  // Check every 200ms instead of 100ms
            } else {
                console.error('❌ Chart.js failed to load within timeout');
                console.warn('⚠️  Trying to continue without Chart.js...');
                resolve(false);
            }
        };
        checkChart();
    });
}

// Document Ready
document.addEventListener('DOMContentLoaded', async function() {
    console.log('✅ DOM Content Loaded');
    
    // Wait for Chart.js before initializing
    const chartReady = await waitForChart();
    if (chartReady) {
        console.log('✅ Chart.js ready, initializing app...');
        initializeApp();
    } else {
        console.warn('⚠️  Chart.js not loaded, but continuing without charts...');
        // Continue anyway - show dashboard without charts
        initializeApp();
    }
});

function initializeApp() {
    console.log('✅ Initializing app...');
    
    // Load initial data
    loadDashboard();
    
    // Event Listeners
    setupEventListeners();
    
    // Hash routing
    handleHashChange();
    window.addEventListener('hashchange', handleHashChange);
    
    // Refresh data every 5 minutes
    setInterval(loadDashboard, 300000);
}

function setupEventListeners() {
    console.log('✅ Setting up event listeners...');
    
    // Menu Navigation - Click event
    document.querySelectorAll('.nav-item').forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            const menu = item.dataset.menu;
            console.log('Menu clicked:', menu);
            window.location.hash = '#' + menu;
        });
    });

    // Sidebar Toggle
    const toggleSidebar = document.getElementById('toggleSidebar');
    if (toggleSidebar) {
        toggleSidebar.addEventListener('click', () => {
            document.querySelector('.sidebar').classList.toggle('active');
        });
    }

    // Refresh Button
    const refreshBtn = document.getElementById('refreshBtn');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', () => {
            refreshBtn.classList.add('fa-spin');
            loadDashboard();
            setTimeout(() => refreshBtn.classList.remove('fa-spin'), 1000);
        });
    }

    // Search
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('keyup', (e) => {
            clearTimeout(searchTimeout);
            const query = e.target.value.trim();
            if (query.length >= 3) {
                searchTimeout = setTimeout(() => searchKeluarga(query), 500);
            }
        });
    }

    // Laporan Form
    const formLaporan = document.getElementById('formLaporan');
    if (formLaporan) {
        formLaporan.addEventListener('submit', handleLaporanSubmit);
    }
}

// Hash routing handler
function handleHashChange() {
    const hash = window.location.hash.slice(1) || 'dashboard';
    console.log('Hash changed to:', hash);
    
    // Find matching nav item
    const navItem = document.querySelector(`[data-menu="${hash}"]`);
    if (navItem) {
        handleMenuClick(navItem);
    }
}

function handleMenuClick(item) {
    console.log('Handling menu click:', item.dataset.menu);
    
    // Remove active class from all items
    document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
    item.classList.add('active');

    // Hide all menu contents
    document.querySelectorAll('.menu-content').forEach(menu => menu.classList.remove('active'));

    // Show selected menu
    const menuId = item.dataset.menu + 'Menu';
    const menuElement = document.getElementById(menuId);
    console.log('Menu element ID:', menuId, 'Found:', !!menuElement);
    
    if (menuElement) {
        menuElement.classList.add('active');
        document.getElementById('pageTitle').textContent = item.querySelector('span').textContent;

        // Load data based on menu
        const menuType = item.dataset.menu;
        if (menuType === 'dashboard') {
            loadDashboard();
        } else if (menuType === 'penduduk') {
            loadPenduduk();
        } else if (menuType === 'grafik') {
            loadGrafik();
        } else if (menuType === 'laporan') {
            loadLaporanData();
        }
    } else {
        console.error('Menu element not found:', menuId);
    }

    // Close sidebar on mobile
    if (window.innerWidth <= 768) {
        document.querySelector('.sidebar').classList.remove('active');
    }
}

function loadDashboard() {
    // Load Stats
    fetch(`${API_BASE}data.php?action=get_stats`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateStats(data.data);
            }
        })
        .catch(error => console.error('Error loading stats:', error));

    // Load Data Terbaru
    fetch(`${API_BASE}data.php?action=get_data_terbaru&limit=20`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateDataTerbaru(data.data);
            }
        })
        .catch(error => console.error('Error loading data terbaru:', error));

    // Load Charts
    loadChartsData();
}

function updateStats(stats) {
    document.getElementById('totalKartu').textContent = formatNumber(stats.total_kartu);
    document.getElementById('totalPenduduk').textContent = formatNumber(stats.total_penduduk);
    document.getElementById('verifikasiPending').textContent = formatNumber(stats.verifikasi_pending);
    document.getElementById('verifikasiDitolak').textContent = formatNumber(stats.verifikasi_ditolak);
    document.getElementById('verifikasiTerverifikasi').textContent = formatNumber(stats.verifikasi_terverifikasi);

    // Calculate percentage
    const total = stats.total_kartu || 1;
    const persentase = Math.round((stats.verifikasi_terverifikasi / total) * 100);
    document.getElementById('persentaseVerifikasi').textContent = persentase + '%';

    // Update info
    document.getElementById('infoTotalRecord').textContent = formatNumber(stats.total_kartu + stats.total_penduduk);
}

function updateDataTerbaru(data) {
    const tbody = document.getElementById('tabelDataTerbaruBody');
    tbody.innerHTML = '';

    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">Tidak ada data</td></tr>';
        return;
    }

    data.forEach(item => {
        const statusClass = item.status_verifikasi === 'terverifikasi' ? 'badge-success' :
                          item.status_verifikasi === 'ditolak' ? 'badge-danger' :
                          'badge-warning';
        
        const statusText = item.status_verifikasi === 'terverifikasi' ? 'Terverifikasi' :
                         item.status_verifikasi === 'ditolak' ? 'Ditolak' :
                         'Pending';

        const row = document.createElement('tr');
        row.innerHTML = `
            <td><strong>${item.no_kartu_keluarga}</strong></td>
            <td>${item.kepala_keluarga}</td>
            <td>${item.kelurahan}</td>
            <td>${item.kecamatan}</td>
            <td><span class="badge badge-info">${item.jumlah_anggota}</span></td>
            <td><span class="badge-status ${statusClass}">${statusText}</span></td>
            <td>
                <button class="btn btn-sm" onclick="viewDetail(${item.id_keluarga})">
                    <i class="fas fa-eye"></i> Lihat
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function loadChartsData() {
    // Check if Chart.js is loaded
    if (typeof Chart === 'undefined') {
        console.warn('⚠️  Chart.js not available yet, skipping dashboard charts');
        return;
    }
    
    // Load Kecamatan Chart
    fetch(`${API_BASE}data.php?action=get_data_by_kecamatan`)
        .then(response => response.json())
        .then(data => {
            if (data.success && typeof Chart !== 'undefined') {
                console.log('✅ Kecamatan data loaded');
                createChartKecamatan(data.data);
            }
        })
        .catch(error => console.error('Error loading kecamatan data:', error));

    // Load Agama Chart
    fetch(`${API_BASE}data.php?action=get_grafik_agama`)
        .then(response => response.json())
        .then(data => {
            if (data.success && typeof Chart !== 'undefined') {
                console.log('✅ Agama data loaded');
                createChartAgama(data.labels, data.data);
            }
        })
        .catch(error => console.error('Error loading agama data:', error));
}

function loadGrafik() {
    // Ensure Chart.js is loaded
    if (typeof Chart === 'undefined') {
        console.warn('Chart.js belum loaded, menunggu...');
        setTimeout(() => loadGrafik(), 500);
        return;
    }

    // Show loading state
    showChartLoading();

    // Load trend input chart
    fetch(`${API_BASE}data.php?action=get_grafik_trend_input`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('✅ Trend input data loaded');
                createChartTrendInput(data.labels, data.datasets);
            }
        })
        .catch(error => console.error('Error:', error));

    // Load umur gender chart
    fetch(`${API_BASE}data.php?action=get_grafik_umur_gender`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('✅ Umur gender data loaded');
                createChartUmurGender(data.labels, data.datasets);
            }
        })
        .catch(error => console.error('Error:', error));

    // Load verifikasi chart
    fetch(`${API_BASE}data.php?action=get_grafik_verifikasi`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('✅ Verifikasi data loaded');
                createChartVerifikasi(data.labels, data.data);
            }
        })
        .catch(error => console.error('Error:', error));

    // Load pendidikan chart
    fetch(`${API_BASE}data.php?action=get_grafik_pendidikan`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('✅ Pendidikan data loaded');
                createChartPendidikan(data.labels, data.data);
            }
        })
        .catch(error => console.error('Error:', error));

    // Load full agama chart
    fetch(`${API_BASE}data.php?action=get_grafik_agama`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('✅ Agama data loaded');
                createChartAgamaFull(data.labels, data.data);
            }
        })
        .catch(error => console.error('Error:', error));

    // Load pekerjaan chart
    fetch(`${API_BASE}data.php?action=get_grafik_pekerjaan`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('✅ Pekerjaan data loaded');
                createChartPekerjaan(data.labels, data.data);
            }
        })
        .catch(error => console.error('Error:', error));

    // Load kecamatan detail chart
    fetch(`${API_BASE}data.php?action=get_data_by_kecamatan`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('✅ Kecamatan detail data loaded');
                createChartKecamatanDetail(data.data);
            }
        })
        .catch(error => console.error('Error:', error));
}

// Show loading animation for charts
function showChartLoading() {
    const chartContainers = document.querySelectorAll('.chart-container canvas');
    chartContainers.forEach(canvas => {
        const parent = canvas.parentElement;
        const loading = document.createElement('div');
        loading.className = 'chart-loading';
        parent.style.position = 'relative';
    });
}

function loadPenduduk() {
    fetch(`${API_BASE}penduduk.php?action=get_penduduk&limit=100`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updatePendudukTable(data.data);
            }
        })
        .catch(error => console.error('Error:', error));
}

function updatePendudukTable(data) {
    const tbody = document.getElementById('tabelPendudukBody');
    tbody.innerHTML = '';

    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">Tidak ada data</td></tr>';
        return;
    }

    data.forEach(item => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${item.nik}</td>
            <td>${item.nama_lengkap}</td>
            <td>${item.no_kartu_keluarga}</td>
            <td>${item.jenis_kelamin}</td>
            <td>${item.agama}</td>
            <td>${item.pekerjaan || '-'}</td>
            <td>${item.status_perkawinan}</td>
            <td>
                <button class="btn btn-sm" onclick="viewPendudukDetail(${item.id_penduduk})">
                    <i class="fas fa-eye"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function loadLaporanData() {
    // Load kecamatan options
    fetch(`${API_BASE}data.php?action=get_data_by_kecamatan`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const select = document.getElementById('laporanKecamatan');
                data.data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.kecamatan;
                    option.textContent = item.kecamatan;
                    select.appendChild(option);
                });
            }
        })
        .catch(error => console.error('Error:', error));

    // Set today's date for laporan
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('laporanTanggalAkhir').value = today;
    
    // Set first day of month for start date
    const firstDay = new Date();
    firstDay.setDate(1);
    document.getElementById('laporanTanggalAwal').value = firstDay.toISOString().split('T')[0];
}

function searchKeluarga(query) {
    fetch(`${API_BASE}data.php?action=search_keluarga&search=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateDataTerbaru(data.data);
            }
        })
        .catch(error => console.error('Error:', error));
}

function handleLaporanSubmit(e) {
    e.preventDefault();
    const tipe = document.getElementById('laporanTipe').value;
    const kecamatan = document.getElementById('laporanKecamatan').value;
    const tanggalAwal = document.getElementById('laporanTanggalAwal').value;
    const tanggalAkhir = document.getElementById('laporanTanggalAkhir').value;

    if (!tipe) {
        alert('Pilih tipe laporan terlebih dahulu');
        return;
    }

    alert('Laporan sedang dipersiapkan. Fitur ini akan dikembangkan lebih lanjut.');
}

// Chart Creation Functions
function createChartKecamatan(data) {
    const ctx = document.getElementById('chartKecamatan');
    if (!ctx) return;

    const labels = data.map(item => item.kecamatan);
    const chartData = data.map(item => item.total_kartu);
    const backgroundColors = generateColors(data.length);

    if (chartKecamatan) chartKecamatan.destroy();

    chartKecamatan = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Keluarga',
                data: chartData,
                backgroundColor: backgroundColors,
                borderRadius: 6,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

function createChartAgama(labels, data) {
    const ctx = document.getElementById('chartAgama');
    if (!ctx) return;

    const backgroundColors = generateColors(labels.length);

    if (chartAgama) chartAgama.destroy();

    chartAgama = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: backgroundColors,
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

function createChartAgamaFull(labels, data) {
    const ctx = document.getElementById('chartAgamaFull');
    if (!ctx) return;

    const backgroundColors = generateColors(labels.length);

    if (chartAgamaFull) chartAgamaFull.destroy();

    chartAgamaFull = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: backgroundColors,
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                animateRotate: true,
                animateScale: false,
                duration: 1000,
                easing: 'easeInOutQuart'
            },
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        padding: 15,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return label + ': ' + value.toLocaleString('id-ID') + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
}

function createChartPekerjaan(labels, data) {
    const ctx = document.getElementById('chartPekerjaan');
    if (!ctx) return;

    const backgroundColors = generateColors(labels.length);

    if (chartPekerjaan) chartPekerjaan.destroy();

    chartPekerjaan = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Penduduk',
                data: data,
                backgroundColor: backgroundColors,
                borderRadius: 8,
                borderWidth: 0
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 1000,
                easing: 'easeInOutQuart'
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    callbacks: {
                        label: function(context) {
                            return 'Jumlah: ' + context.parsed.x.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('id-ID');
                        }
                    }
                },
                y: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

function createChartKecamatanDetail(data) {
    const ctx = document.getElementById('chartKecamatanDetail');
    if (!ctx) return;

    const labels = data.map(item => item.kecamatan);
    const totalKartu = data.map(item => item.total_kartu);
    const totalPenduduk = data.map(item => item.total_penduduk);

    if (chartKecamatanDetail) chartKecamatanDetail.destroy();

    chartKecamatanDetail = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Total Kartu Keluarga',
                    data: totalKartu,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.15)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                },
                {
                    label: 'Total Penduduk',
                    data: totalPenduduk,
                    borderColor: '#f093fb',
                    backgroundColor: 'rgba(240, 147, 251, 0.15)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointBackgroundColor: '#f093fb',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 1200,
                easing: 'easeInOutQuart'
            },
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 13,
                            weight: '600'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) label += ': ';
                            label += context.parsed.y.toLocaleString('id-ID');
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('id-ID');
                        }
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                }
            }
        }
    });
}

// Chart Trend Input Data Per Bulan (Line Chart)
function createChartTrendInput(labels, datasets) {
    const ctx = document.getElementById('chartTrendInput');
    if (!ctx) return;

    if (chartTrendInput) chartTrendInput.destroy();

    chartTrendInput = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Total Input Data',
                    data: datasets.input,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.15)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    segment: {
                        borderDash: ctx => ctx.p1DataIndex === undefined ? [5,5] : undefined
                    }
                },
                {
                    label: 'Terverifikasi',
                    data: datasets.verifikasi,
                    borderColor: '#43e97b',
                    backgroundColor: 'rgba(67, 233, 123, 0.15)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointBackgroundColor: '#43e97b',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 1000,
                easing: 'easeInOutQuart'
            },
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 13,
                            weight: '600'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) label += ': ';
                            label += context.parsed.y.toLocaleString('id-ID');
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('id-ID');
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// Chart Umur dan Gender (Line Chart)
function createChartUmurGender(labels, datasets) {
    const ctx = document.getElementById('chartUmurGender');
    if (!ctx) return;

    if (chartUmurGender) chartUmurGender.destroy();

    chartUmurGender = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Laki-laki',
                    data: datasets.laki,
                    borderColor: '#4facfe',
                    backgroundColor: 'rgba(79, 172, 254, 0.15)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointBackgroundColor: '#4facfe',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                },
                {
                    label: 'Perempuan',
                    data: datasets.perempuan,
                    borderColor: '#fa709a',
                    backgroundColor: 'rgba(250, 112, 154, 0.15)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointBackgroundColor: '#fa709a',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 1200,
                easing: 'easeInOutQuart'
            },
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 13,
                            weight: '600'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) label += ': ';
                            label += context.parsed.y.toLocaleString('id-ID');
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('id-ID');
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// Chart Status Verifikasi (Bar Chart)
function createChartVerifikasi(labels, data) {
    const ctx = document.getElementById('chartVerifikasi');
    if (!ctx) return;

    const backgroundColors = ['#667eea', '#43e97b', '#f5576c', '#ffa502'];

    if (chartVerifikasi) chartVerifikasi.destroy();

    chartVerifikasi = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Keluarga',
                data: data,
                backgroundColor: backgroundColors.slice(0, labels.length),
                borderRadius: 8,
                borderSkipped: false,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 1000,
                easing: 'easeInOutQuart'
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    callbacks: {
                        label: function(context) {
                            return 'Jumlah: ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('id-ID');
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// Chart Pendidikan (Horizontal Bar Chart)
function createChartPendidikan(labels, data) {
    const ctx = document.getElementById('chartPendidikan');
    if (!ctx) return;

    const backgroundColors = generateColors(labels.length);

    if (chartPendidikan) chartPendidikan.destroy();

    chartPendidikan = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Penduduk',
                data: data,
                backgroundColor: backgroundColors,
                borderRadius: 8,
                borderWidth: 0
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 1000,
                easing: 'easeInOutQuart'
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    callbacks: {
                        label: function(context) {
                            return 'Jumlah: ' + context.parsed.x.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('id-ID');
                        }
                    }
                },
                y: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// Helper Functions
function formatNumber(num) {
    return new Intl.NumberFormat('id-ID').format(num);
}

function generateColors(count) {
    const colors = [
        '#667eea', '#764ba2', '#f093fb', '#f5576c', 
        '#fa709a', '#fee140', '#30b0fe', '#4facfe',
        '#00f2fe', '#43e97b', '#38f9d7', '#ff6b6b'
    ];
    
    const result = [];
    for (let i = 0; i < count; i++) {
        result.push(colors[i % colors.length]);
    }
    return result;
}

function viewDetail(idKeluarga) {
    alert('Fitur detail keluarga akan segera tersedia');
}

function viewPendudukDetail(idPenduduk) {
    alert('Fitur detail penduduk akan segera tersedia');
}

// Update time
setInterval(() => {
    document.getElementById('infoTanggalUpdate').textContent = new Date().toLocaleString('id-ID');
}, 1000);
