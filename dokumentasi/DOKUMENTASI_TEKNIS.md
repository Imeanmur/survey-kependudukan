# 🔧 DOKUMENTASI TEKNIS - Dashboard Survey Kependudukan

## 📋 Ringkasan Teknis

Dashboard ini adalah aplikasi web responsif yang menampilkan data survey kependudukan dari database MySQL dengan visualisasi menggunakan Chart.js.

**Tech Stack:**
- Frontend: HTML5, CSS3, JavaScript (Vanilla)
- Backend: PHP 7.x
- Database: MySQL 8.0
- Visualization: Chart.js 3.9.1
- Icons: FontAwesome 6.4.0

## 🏗️ Arsitektur Sistem

```
┌─────────────────────────────────────────────────┐
│             Web Browser (Frontend)              │
│  ┌──────────────────────────────────────────┐   │
│  │ - HTML5 UI                               │   │
│  │ - Chart.js Visualisasi                   │   │
│  │ - JavaScript Logic                       │   │
│  └──────────────────────────────────────────┘   │
└────────────────┬────────────────────────────────┘
                 │ AJAX/Fetch
                 │ JSON Data
                 │
┌────────────────▼────────────────────────────────┐
│         API Layer (Backend - PHP)               │
│  ┌──────────────────────────────────────────┐   │
│  │ /api/data.php                            │   │
│  │ - get_stats                              │   │
│  │ - get_data_terbaru                       │   │
│  │ - get_grafik_trend_input (NEW)          │   │
│  │ - get_grafik_umur_gender (NEW)          │   │
│  │ - get_grafik_verifikasi (NEW)           │   │
│  │ - get_grafik_pendidikan (NEW)           │   │
│  └──────────────────────────────────────────┘   │
└────────────────┬────────────────────────────────┘
                 │ SQL Queries
                 │
┌────────────────▼────────────────────────────────┐
│      Database Layer (MySQL)                     │
│  ┌──────────────────────────────────────────┐   │
│  │ survey_kependudukan                      │   │
│  │ ├── keluarga (table)                     │   │
│  │ ├── penduduk (table)                     │   │
│  │ ├── kecamatan (table)                    │   │
│  │ └── [other tables]                       │   │
│  └──────────────────────────────────────────┘   │
└─────────────────────────────────────────────────┘
```

## 📂 File Structure

```
survey-kependudukan/
├── index.html                  # Main dashboard UI
├── QUICK_START.md             # Setup cepat
├── INTEGRASI_DASHBOARD.md     # Dokumentasi integrasi
├── GUIDE_GRAFIK.md            # Panduan grafik
├── SUMMARY_INTEGRASI.md       # Ringkasan teknis
├── TESTING_DEPLOYMENT.md      # Testing checklist
│
├── includes/
│   └── config.php             # Database config
│                                (MySQL connection)
│
├── api/
│   ├── data.php               # Main API endpoints
│   │   - get_stats()
│   │   - get_data_terbaru()
│   │   - get_data_by_kecamatan()
│   │   - getGrafikAgama()
│   │   - getGrafikPekerjaan()
│   │   - getGrafikTrendInput()      [NEW]
│   │   - getGrafikUmurGender()      [NEW]
│   │   - getGrafikVerifikasi()      [NEW]
│   │   - getGrafikPendidikan()      [NEW]
│   │   - searchKeluarga()
│   │
│   └── penduduk.php           # Penduduk endpoints
│       - getPenduduk()
│       - getPendudukByKeluarga()
│
├── assets/
│   ├── css/
│   │   └── style.css          # Main styling
│   │       - Root variables
│   │       - Layout (sidebar, main)
│   │       - Cards & charts
│   │       - Responsive design
│   │       - Animations
│   │
│   └── js/
│       └── script.js          # Main JavaScript
│           - Chart instances
│           - API calls
│           - Event listeners
│           - Chart creation functions
│           - Utility functions
│
├── database/
│   ├── survey_kependudukan.sql      # Database schema + data
│   └── survey_kependudukan_full.sql # Full export
│
└── docs/ (optional)
    └── [documentation files]
```

## 🗄️ Database Schema

### Table: keluarga

```sql
CREATE TABLE keluarga (
    id_keluarga INT PRIMARY KEY AUTO_INCREMENT,
    no_kartu_keluarga VARCHAR(16) UNIQUE,
    kepala_keluarga VARCHAR(100),
    alamat TEXT,
    kelurahan VARCHAR(50),
    kecamatan VARCHAR(50),
    status_verifikasi ENUM('terverifikasi','pending','ditolak','revisi'),
    tanggal_input DATETIME,
    tanggal_update DATETIME,
    INDEX idx_kecamatan (kecamatan),
    INDEX idx_status (status_verifikasi),
    INDEX idx_tanggal_input (tanggal_input)
);
```

### Table: penduduk

```sql
CREATE TABLE penduduk (
    id_penduduk INT PRIMARY KEY AUTO_INCREMENT,
    id_keluarga INT,
    nik VARCHAR(16),
    nama_lengkap VARCHAR(100),
    jenis_kelamin CHAR(1),          -- L/P
    tanggal_lahir DATE,
    agama VARCHAR(30),
    pekerjaan VARCHAR(50),
    pendidikan_terakhir VARCHAR(30),
    status_perkawinan VARCHAR(20),
    tanggal_input DATETIME,
    FOREIGN KEY (id_keluarga) REFERENCES keluarga(id_keluarga),
    INDEX idx_id_keluarga (id_keluarga),
    INDEX idx_tanggal_lahir (tanggal_lahir)
);
```

### Table: kecamatan

```sql
CREATE TABLE kecamatan (
    id_kecamatan INT PRIMARY KEY AUTO_INCREMENT,
    nama_kecamatan VARCHAR(50) UNIQUE
);
```

## 🔌 API Endpoints Dokumentasi

### 1. GET /api/data.php?action=get_stats

**Tujuan**: Mengambil statistik ringkasan dashboard

**Response**:
```json
{
  "success": true,
  "data": {
    "total_kartu": 1234,
    "total_penduduk": 5678,
    "verifikasi_pending": 100,
    "verifikasi_terverifikasi": 1000,
    "verifikasi_ditolak": 50,
    "verifikasi_revisi": 84,
    "total_kecamatan": 21
  }
}
```

**Query**:
```sql
SELECT COUNT(*) as total FROM keluarga;
SELECT COUNT(*) as total FROM penduduk;
-- Per status (4 queries)
```

---

### 2. GET /api/data.php?action=get_grafik_trend_input

**Tujuan**: Trend input data per bulan (Line Chart)

**Response**:
```json
{
  "success": true,
  "labels": [
    "Januari 2025",
    "Februari 2025",
    "Maret 2025"
  ],
  "datasets": {
    "input": [100, 150, 200],
    "verifikasi": [80, 120, 180]
  }
}
```

**Query**:
```sql
SELECT 
  DATE_FORMAT(tanggal_input, '%Y-%m') as bulan,
  DATE_FORMAT(tanggal_input, '%M %Y') as bulan_label,
  COUNT(*) as total_input,
  SUM(CASE WHEN status_verifikasi = 'terverifikasi' THEN 1 ELSE 0 END) as terverifikasi
FROM keluarga
WHERE tanggal_input IS NOT NULL
GROUP BY DATE_FORMAT(tanggal_input, '%Y-%m')
ORDER BY bulan ASC;
```

**Data Processing**:
- Format bulan ke "Month Year" untuk label
- Pisahkan input count dan verifikasi count
- Sort by bulan ascending (chronological)

---

### 3. GET /api/data.php?action=get_grafik_umur_gender

**Tujuan**: Perbandingan umur dan gender (Line Chart)

**Response**:
```json
{
  "success": true,
  "labels": [
    "0-5 Tahun",
    "6-11 Tahun",
    "12-17 Tahun",
    "18-29 Tahun",
    "30-44 Tahun",
    "45-59 Tahun",
    "60+ Tahun"
  ],
  "datasets": {
    "laki": [100, 120, 150, 200, 180, 120, 80],
    "perempuan": [95, 115, 145, 210, 175, 130, 90]
  }
}
```

**Query**:
```sql
SELECT 
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
ORDER BY FIELD(kelompok_umur, '0-5 Tahun', '6-11 Tahun', '12-17 Tahun', 
               '18-29 Tahun', '30-44 Tahun', '45-59 Tahun', '60+ Tahun'), 
         jenis_kelamin;
```

**Data Processing**:
- Hitung umur dari tanggal lahir
- Group by kelompok umur dan gender
- Build 2 array: laki-laki, perempuan
- Maintain order: 0-5, 6-11, 12-17, dst

---

### 4. GET /api/data.php?action=get_grafik_verifikasi

**Tujuan**: Status verifikasi keluarga (Bar Chart)

**Response**:
```json
{
  "success": true,
  "labels": ["Terverifikasi", "Pending", "Ditolak", "Revisi"],
  "data": [1000, 200, 50, 100]
}
```

**Query**:
```sql
SELECT 
  status_verifikasi,
  COUNT(*) as jumlah
FROM keluarga
GROUP BY status_verifikasi
ORDER BY jumlah DESC;
```

**Data Processing**:
- Convert status ke label readable (ucfirst, underscores to spaces)
- Group count per status
- Sort by jumlah descending

---

### 5. GET /api/data.php?action=get_grafik_pendidikan

**Tujuan**: Distribusi pendidikan terakhir (Bar Chart)

**Response**:
```json
{
  "success": true,
  "labels": ["SD", "SMP", "SMA", "S1", "Diploma", "S2"],
  "data": [500, 800, 1200, 400, 150, 50]
}
```

**Query**:
```sql
SELECT 
  pendidikan_terakhir,
  COUNT(*) as jumlah
FROM penduduk
WHERE pendidikan_terakhir IS NOT NULL AND pendidikan_terakhir != ''
GROUP BY pendidikan_terakhir
ORDER BY jumlah DESC;
```

---

## 💻 JavaScript Architecture

### Chart Instances

```javascript
// Global chart objects
let chartKecamatan;          // Bar chart
let chartAgama;              // Doughnut chart (dashboard)
let chartAgamaFull;          // Pie chart (grafik tab)
let chartPekerjaan;          // Horizontal bar
let chartKecamatanDetail;    // Line chart

// NEW Chart instances
let chartTrendInput;         // Line chart - trend
let chartUmurGender;         // Line chart - demographics
let chartVerifikasi;         // Bar chart - verification
let chartPendidikan;         // Horizontal bar - education
```

### Function Flow

```javascript
// 1. Page Load
DOMContentLoaded
  → initializeApp()
    → loadDashboard()
    → setupEventListeners()
    → setInterval(loadDashboard, 300000)  // Auto-refresh 5 min

// 2. Load Dashboard
loadDashboard()
  ├── fetch(api/data.php?action=get_stats)
  │   └── updateStats()
  ├── fetch(api/data.php?action=get_data_terbaru)
  │   └── updateDataTerbaru()
  └── loadChartsData()
      ├── fetch(api/data.php?action=get_data_by_kecamatan)
      │   └── createChartKecamatan()
      └── fetch(api/data.php?action=get_grafik_agama)
          └── createChartAgama()

// 3. Load Grafik Tab
loadGrafik()
  ├── fetch(...?action=get_grafik_trend_input)
  │   └── createChartTrendInput()
  ├── fetch(...?action=get_grafik_umur_gender)
  │   └── createChartUmurGender()
  ├── fetch(...?action=get_grafik_verifikasi)
  │   └── createChartVerifikasi()
  ├── fetch(...?action=get_grafik_pendidikan)
  │   └── createChartPendidikan()
  ├── fetch(...?action=get_grafik_agama)
  │   └── createChartAgamaFull()
  ├── fetch(...?action=get_grafik_pekerjaan)
  │   └── createChartPekerjaan()
  └── fetch(...?action=get_data_by_kecamatan)
      └── createChartKecamatanDetail()
```

### Chart Creation Pattern

```javascript
function createChartXYZ(labels, data) {
    // 1. Get canvas element
    const ctx = document.getElementById('chartXYZ');
    if (!ctx) return;  // Safety check

    // 2. Destroy existing chart
    if (chartXYZ) chartXYZ.destroy();

    // 3. Create new chart
    chartXYZ = new Chart(ctx, {
        type: 'line',  // or 'bar', 'pie', etc
        data: {
            labels: labels,
            datasets: [{
                label: 'Dataset 1',
                data: data,
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                // ... more options
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            // ... more options
        }
    });
}
```

## 🎨 CSS Architecture

### CSS Variables (Root)

```css
:root {
    --primary: #0066ff;
    --secondary: #00d4ff;
    --success: #00d084;
    --warning: #ffa500;
    --danger: #ff6b6b;
    --dark: #1a1a2e;
    --light: #f8f9fa;
    --gray: #6c757d;
    --shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
```

### Layout System

```css
/* Container */
.container-wrapper {
    display: flex;           /* Sidebar + Main */
    height: 100vh;
    overflow: hidden;
}

/* Sidebar (260px fixed) */
.sidebar {
    width: 260px;
    position: fixed;
    overflow-y: auto;
}

/* Main Content */
.main-content {
    margin-left: 260px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

/* Top Header */
.top-header {
    flex: 0 0 60px;
}

/* Content Area */
.content-area {
    flex: 1;
    overflow-y: auto;
    padding: 30px;
}

/* Charts Grid */
.charts-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.chart-container.full-width {
    grid-column: 1 / -1;
}
```

### Responsive Breakpoints

```css
/* Desktop: 1920px */
/* Tablet: 768px - 1024px */
/* Mobile: < 768px */

@media (max-width: 1024px) {
    .sidebar { width: 220px; }
    .main-content { margin-left: 220px; }
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 768px) {
    .sidebar { transform: translateX(-100%); /* hidden */}
    .main-content { margin-left: 0; }
    .sidebar.active { transform: translateX(0); /* shown */ }
    .top-header { flex-direction: column; }
    .stats-grid { grid-template-columns: 1fr; }
}
```

## 🔐 Security Considerations

### Current Implementation

✓ **CORS Headers**: Configured untuk API access
✓ **Database Connection**: Parameterized queries
✓ **Input Validation**: Search input sanitized
✓ **Error Handling**: Try-catch, error messages

### For Production

- [ ] Add authentication layer
- [ ] Implement role-based access
- [ ] Use prepared statements (already done)
- [ ] Add rate limiting on API
- [ ] Use HTTPS only
- [ ] Hide error details
- [ ] Add logging/monitoring
- [ ] Regular security audits
- [ ] Update dependencies

## 📈 Performance Optimization

### Current

- CDN: FontAwesome 6.4.0, Chart.js 3.9.1
- Compression: Gzip for CSS/JS
- Caching: Browser caching enabled
- Lazy loading: Charts load on demand

### Recommended

- [ ] Minify CSS/JS
- [ ] Use service workers
- [ ] Implement pagination
- [ ] Database query optimization
- [ ] Add indexes on frequently queried columns
- [ ] Use LIMIT on queries
- [ ] Implement caching layer (Redis)
- [ ] CDN for static assets

## 🐛 Debugging Tips

### Browser Console

```javascript
// Check API endpoints
fetch('/api/data.php?action=get_stats')
  .then(r => r.json())
  .then(d => console.log(d));

// Check chart instance
console.log(chartTrendInput);

// Check element
console.log(document.getElementById('chartTrendInput'));
```

### Network Tab (F12)

- Check API response status (200 = OK)
- Check response size (< 100KB ideal)
- Check response time (< 1000ms ideal)

### Common Issues

1. **404 API Error**
   - Check file path `/api/data.php`
   - Check action parameter spelling
   - Check file exists

2. **Chart Not Displaying**
   - Check canvas element exists
   - Check data not empty
   - Check Chart.js loaded
   - Check console for errors

3. **Data Not Updating**
   - Check database connection
   - Check query syntax
   - Check auto-refresh timer
   - Check browser cache

## 📚 Dependencies

### Frontend Libraries

```html
<!-- Chart.js for visualization -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/chart.js/3.9.1/chart.min.js"></script>

<!-- FontAwesome for icons -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
```

### Backend Requirements

- PHP 7.4+
- MySQL 5.7+ atau 8.0
- PDO extension (untuk prepared statements)
- JSON extension (built-in PHP)

### Browser Support

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

---

**Last Updated**: 6 Januari 2026  
**Technical Documentation Version**: 1.0
