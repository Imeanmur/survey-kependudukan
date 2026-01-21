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

// Document Ready - SIMPLIFIED
document.addEventListener('DOMContentLoaded', function () {
    console.log('✅ DOM Content Loaded');
    console.log('📊 Starting initialization...');

    // Initialize app immediately - don't wait for Chart.js
    initializeApp();
});

function initializeApp() {
    console.log('✅ Initializing app...');

    // Event Listeners FIRST (before anything else)
    setupEventListeners();

    // Load initial dashboard
    console.log('✅ Loading initial dashboard...');
    loadDashboard();

    // Hash routing
    window.addEventListener('hashchange', handleHashChange);
    console.log('✅ Hash change listener attached');

    // Refresh data every 5 minutes
    setInterval(loadDashboard, 300000);
}

function setupEventListeners() {
    console.log('✅ Setting up event listeners...');

    // Menu Navigation - Click event using addEventListener
    const navItems = document.querySelectorAll('.nav-item');
    console.log('📊 Total nav items found:', navItems.length);

    navItems.forEach((item, index) => {
        console.log(`✅ Attaching listener to nav item ${index}:`, item.dataset.menu);

        // Remove any existing onclick handlers first
        item.onclick = null;

        // Add listener using addEventListener for more reliable binding
        item.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const menuType = this.dataset.menu;
            console.log('🔴 CLICKED NAV ITEM:', menuType);

            // Hide all menus
            const allMenus = document.querySelectorAll('.menu-content');
            console.log('🔴 Found menus to hide:', allMenus.length);
            allMenus.forEach(el => {
                el.classList.remove('active');
                console.log('  ✅ Removed active from:', el.id);
            });

            // Remove active from all nav items
            const allNavItems = document.querySelectorAll('.nav-item');
            allNavItems.forEach(el => {
                el.classList.remove('active');
            });

            // Add active to current item
            this.classList.add('active');
            console.log('🔴 Added active to nav item:', menuType);

            // Show target menu
            const targetMenuId = menuType + 'Menu';
            const targetMenu = document.getElementById(targetMenuId);

            console.log('🔴 Looking for menu ID:', targetMenuId);
            console.log('🔴 Menu element found:', targetMenu !== null);

            if (targetMenu) {
                targetMenu.classList.add('active');
                console.log('✅ Menu activated:', targetMenuId);
                // Scroll content to top for better UX
                const content = document.querySelector('.content-area');
                if (content) {
                    content.scrollTo({ top: 0, behavior: 'smooth' });
                }
            } else {
                console.error('❌ Menu NOT found:', targetMenuId);
                console.error('   Available IDs:', Array.from(document.querySelectorAll('[id*="Menu"]')).map(e => e.id));
            }

            // Update title
            const spanEl = this.querySelector('span');
            const titleText = spanEl ? spanEl.textContent : menuType;
            const titleEl = document.getElementById('pageTitle');
            if (titleEl) {
                titleEl.textContent = titleText;
                console.log('🔴 Updated title to:', titleText);
            }

            // Load data
            console.log('🔴 Loading data for:', menuType);
            if (menuType === 'dashboard') {
                loadDashboard();
            } else if (menuType === 'penduduk') {
                loadPenduduk();
            } else if (menuType === 'grafik') {
                loadGrafik();
            } else if (menuType === 'laporan') {
                loadLaporanData();
            }

            return false;
        }, false); // Use non-capturing phase
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
            const icon = refreshBtn.querySelector('i');
            if (icon) icon.classList.add('fa-spin');
            loadDashboard();
            setTimeout(() => { if (icon) icon.classList.remove('fa-spin'); }, 1000);
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

    // Search Penduduk (Nama atau NIK)
    const searchPendudukInput = document.getElementById('searchPenduduk');
    if (searchPendudukInput) {
        let pendudukSearchTimeout;
        searchPendudukInput.addEventListener('keyup', (e) => {
            clearTimeout(pendudukSearchTimeout);
            const query = e.target.value.trim();
            if (query.length === 0) {
                // kembali ke data awal jika input dikosongkan
                loadPenduduk();
                return;
            }
            if (query.length >= 2) {
                pendudukSearchTimeout = setTimeout(() => searchPenduduk(query), 400);
            }
        });
    }

    // Laporan Form
    const formLaporan = document.getElementById('formLaporan');
    if (formLaporan) {
        formLaporan.addEventListener('submit', handleLaporanSubmit);
    }

    // Tombol kembali dari detail
    const btnBackDetail = document.getElementById('btnBackDetail');
    if (btnBackDetail) {
        btnBackDetail.addEventListener('click', () => {
            // kembali ke dashboard
            const allMenus = document.querySelectorAll('.menu-content');
            allMenus.forEach(el => el.classList.remove('active'));
            const dash = document.getElementById('dashboardMenu');
            if (dash) dash.classList.add('active');
            const titleEl = document.getElementById('pageTitle');
            if (titleEl) titleEl.textContent = 'Dashboard';
        });
    }
}

// Hash routing handler - SIMPLE VERSION
function handleHashChange() {
    const hash = window.location.hash.slice(1) || 'dashboard';
    console.log('✅ Hash changed to:', hash);

    // Find and click the nav item
    const navItem = document.querySelector(`[data-menu="${hash}"]`);
    if (navItem) {
        navItem.click();
    }
}

function loadDashboard() {
    console.log('✅ Loading Dashboard...');

    // Load Stats
    fetch(`${API_BASE}data.php?action=get_stats`)
        .then(response => {
            console.log('Stats API response:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Stats data received:', data);
            if (data.success) {
                updateStats(data.data);
            }
        })
        .catch(error => console.error('❌ Error loading stats:', error));

    // Load Data Terbaru
    fetch(`${API_BASE}data.php?action=get_data_terbaru&limit=20`)
        .then(response => {
            console.log('Data Terbaru API response:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Data Terbaru received:', data);
            if (data.success) {
                updateDataTerbaru(data.data);
            }
        })
        .catch(error => console.error('❌ Error loading data terbaru:', error));

    // Load Charts
    console.log('✅ Loading Charts...');
    loadChartsData();
}

function updateStats(stats) {
    animateCounter(document.getElementById('totalKartu'), stats.total_kartu);
    animateCounter(document.getElementById('totalPenduduk'), stats.total_penduduk);
    animateCounter(document.getElementById('verifikasiPending'), stats.verifikasi_pending);
    animateCounter(document.getElementById('verifikasiDitolak'), stats.verifikasi_ditolak);
    animateCounter(document.getElementById('verifikasiTerverifikasi'), stats.verifikasi_terverifikasi);

    // Calculate percentage
    const total = stats.total_kartu || 1;
    const persentase = Math.round((stats.verifikasi_terverifikasi / total) * 100);
    animateCounter(document.getElementById('persentaseVerifikasi'), persentase, '%');

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

// Cari Penduduk berdasarkan nama lengkap atau NIK
function searchPenduduk(query) {
    fetch(`${API_BASE}penduduk.php?action=search_penduduk&search=${encodeURIComponent(query)}`)
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

    // Construct URL with query parameters
    const params = new URLSearchParams({
        tipe: tipe,
        kecamatan: kecamatan,
        tanggal_awal: tanggalAwal,
        tanggal_akhir: tanggalAkhir,
        format: 'html' // Default to HTML/Print View
    });

    // Open report in new tab
    const url = `${API_BASE}laporan.php?${params.toString()}`;
    window.open(url, '_blank');
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
                    position: 'bottom',
                    labels: {
                        padding: 8,
                        font: {
                            size: 10
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 8,
                    cornerRadius: 6,
                    titleFont: { size: 11 },
                    bodyFont: { size: 10 },
                    callbacks: {
                        label: function (context) {
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
                borderRadius: 6,
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
                    padding: 8,
                    cornerRadius: 6,
                    titleFont: { size: 11, weight: 'bold' },
                    bodyFont: { size: 10 },
                    callbacks: {
                        label: function (context) {
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
                        font: { size: 9 },
                        callback: function (value) {
                            return value.toLocaleString('id-ID');
                        }
                    }
                },
                y: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: { size: 9 }
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

    // Prepare nice gradient backgrounds
    const grad1 = makeAreaGradient(ctx, '#667eea');
    const grad2 = makeAreaGradient(ctx, '#f093fb');

    chartKecamatanDetail = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Total Kartu Keluarga',
                    data: totalKartu,
                    borderColor: '#667eea',
                    backgroundColor: grad1,
                    tension: 0.4,
                    fill: true,
                    borderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                },
                {
                    label: 'Total Penduduk',
                    data: totalPenduduk,
                    borderColor: '#f093fb',
                    backgroundColor: grad2,
                    tension: 0.4,
                    fill: true,
                    borderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
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
                        padding: 10,
                        font: {
                            size: 10,
                            weight: '600'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 8,
                    cornerRadius: 6,
                    titleFont: { size: 11, weight: 'bold' },
                    bodyFont: { size: 10 },
                    displayColors: true,
                    callbacks: {
                        label: function (context) {
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
                        font: { size: 9 },
                        callback: function (value) {
                            return value.toLocaleString('id-ID');
                        }
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        font: { size: 9 }
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

    const gradInput = makeAreaGradient(ctx, '#667eea');
    const gradVerif = makeAreaGradient(ctx, '#43e97b');

    chartTrendInput = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Total Input Data',
                    data: datasets.input,
                    borderColor: '#667eea',
                    backgroundColor: gradInput,
                    tension: 0.4,
                    fill: true,
                    borderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    segment: {
                        borderDash: ctx => ctx.p1DataIndex === undefined ? [5, 5] : undefined
                    }
                },
                {
                    label: 'Terverifikasi',
                    data: datasets.verifikasi,
                    borderColor: '#43e97b',
                    backgroundColor: gradVerif,
                    tension: 0.4,
                    fill: true,
                    borderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
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
                        padding: 10,
                        font: {
                            size: 10,
                            weight: '600'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 8,
                    cornerRadius: 6,
                    titleFont: { size: 11, weight: 'bold' },
                    bodyFont: { size: 10 },
                    displayColors: true,
                    callbacks: {
                        label: function (context) {
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
                        font: { size: 9 },
                        callback: function (value) {
                            return value.toLocaleString('id-ID');
                        }
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        font: { size: 9 }
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

    const gradL = makeAreaGradient(ctx, '#4facfe');
    const gradP = makeAreaGradient(ctx, '#fa709a');

    chartUmurGender = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Laki-laki',
                    data: datasets.laki,
                    borderColor: '#4facfe',
                    backgroundColor: gradL,
                    tension: 0.4,
                    fill: true,
                    borderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#4facfe',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                },
                {
                    label: 'Perempuan',
                    data: datasets.perempuan,
                    borderColor: '#fa709a',
                    backgroundColor: gradP,
                    tension: 0.4,
                    fill: true,
                    borderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
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
                        padding: 10,
                        font: {
                            size: 10,
                            weight: '600'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 8,
                    cornerRadius: 6,
                    titleFont: { size: 11, weight: 'bold' },
                    bodyFont: { size: 10 },
                    displayColors: true,
                    callbacks: {
                        label: function (context) {
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
                        font: { size: 9 },
                        callback: function (value) {
                            return value.toLocaleString('id-ID');
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: { size: 9 }
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
                borderRadius: 6,
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
                    padding: 8,
                    cornerRadius: 6,
                    titleFont: { size: 11, weight: 'bold' },
                    bodyFont: { size: 10 },
                    callbacks: {
                        label: function (context) {
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
                        font: { size: 9 },
                        callback: function (value) {
                            return value.toLocaleString('id-ID');
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: { size: 9 }
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
                borderRadius: 6,
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
                    padding: 8,
                    cornerRadius: 6,
                    titleFont: { size: 11, weight: 'bold' },
                    bodyFont: { size: 10 },
                    callbacks: {
                        label: function (context) {
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
                        font: { size: 9 },
                        callback: function (value) {
                            return value.toLocaleString('id-ID');
                        }
                    }
                },
                y: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: { size: 9 }
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

// Animated counter for stat cards
function animateCounter(el, target, suffix = '') {
    if (!el) return;
    const clean = (txt) => parseInt(String(txt).replace(/[^0-9]/g, '') || '0', 10);
    const start = clean(el.textContent);
    const end = typeof target === 'number' ? target : clean(target);
    const duration = 900;
    const startTime = performance.now();

    function step(now) {
        const p = Math.min((now - startTime) / duration, 1);
        const eased = 1 - Math.pow(1 - p, 3); // easeOutCubic
        const value = Math.round(start + (end - start) * eased);
        el.textContent = suffix ? value.toLocaleString('id-ID') + suffix : value.toLocaleString('id-ID');
        if (p < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
}

// Create a vertical fading area gradient for line charts
function makeAreaGradient(canvasEl, hexColor) {
    const ctx2d = canvasEl.getContext ? canvasEl.getContext('2d') : canvasEl;
    const gradient = ctx2d.createLinearGradient(0, 0, 0, canvasEl.height || 300);
    gradient.addColorStop(0, hexToRgba(hexColor, 0.22));
    gradient.addColorStop(1, hexToRgba(hexColor, 0));
    return gradient;
}

function hexToRgba(hex, alpha) {
    const h = hex.replace('#', '');
    const bigint = parseInt(h.length === 3 ? h.split('').map(c => c + c).join('') : h, 16);
    const r = (bigint >> 16) & 255;
    const g = (bigint >> 8) & 255;
    const b = bigint & 255;
    return `rgba(${r}, ${g}, ${b}, ${alpha})`;
}

function viewDetail(idKeluarga) {
    if (!idKeluarga) return;

    // Tampilkan indikator loading pada tabel anggota
    const anggotaBody = document.getElementById('tabelAnggotaBody');
    if (anggotaBody) {
        anggotaBody.innerHTML = '<tr><td colspan="5" class="text-center text-muted"><i class="fas fa-spinner fa-spin"></i> Memuat...</td></tr>';
    }

    fetch(`${API_BASE}data.php?action=get_keluarga_detail&id=${encodeURIComponent(idKeluarga)}`)
        .then(r => r.json())
        .then(res => {
            if (!res.success) {
                alert(res.message || 'Gagal memuat detail');
                return;
            }

            const k = res.keluarga;
            document.getElementById('detailKK').textContent = k.no_kartu_keluarga || '-';
            document.getElementById('detailKepala').textContent = k.kepala_keluarga || '-';
            document.getElementById('detailAlamat').textContent = k.alamat || '-';
            document.getElementById('detailKelurahan').textContent = k.kelurahan || '-';
            document.getElementById('detailKecamatan').textContent = k.kecamatan || '-';
            document.getElementById('detailStatus').textContent = (k.status_verifikasi || '-').toUpperCase();

            // Render anggota
            const body = document.getElementById('tabelAnggotaBody');
            body.innerHTML = '';
            if (!res.anggota || res.anggota.length === 0) {
                body.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Tidak ada anggota</td></tr>';
            } else {
                res.anggota.forEach(a => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${a.nik || '-'}</td>
                        <td>${a.nama_lengkap || '-'}</td>
                        <td>${a.hubungan_keluarga || '-'}</td>
                        <td>${a.jenis_kelamin || '-'}</td>
                        <td>${a.pekerjaan || '-'}</td>
                    `;
                    body.appendChild(tr);
                });
            }

            // Beralih ke halaman detail
            const allMenus = document.querySelectorAll('.menu-content');
            allMenus.forEach(el => el.classList.remove('active'));
            document.getElementById('keluargaDetailMenu').classList.add('active');

            const titleEl = document.getElementById('pageTitle');
            if (titleEl) titleEl.textContent = 'Detail Kartu Keluarga';
        })
        .catch(err => {
            console.error('Error memuat detail:', err);
            alert('Terjadi kesalahan saat memuat detail');
        });
}

function viewPendudukDetail(idPenduduk) {
    alert('Fitur detail penduduk akan segera tersedia');
}

// Update time
setInterval(() => {
    document.getElementById('infoTanggalUpdate').textContent = new Date().toLocaleString('id-ID');
}, 1000);
