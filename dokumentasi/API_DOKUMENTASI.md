# API DOKUMENTASI

## 📍 Base URL
```
http://localhost/survey-kependudukan/api/
```

## 🔌 Available Endpoints

| Endpoint | Method | Fungsi |
|----------|--------|--------|
| `data.php?action=get_stats` | GET | Statistik ringkas |
| `data.php?action=get_data_terbaru` | GET | 20 data terakhir |
| `data.php?action=get_data_by_kecamatan` | GET | Distribusi kecamatan |
| `data.php?action=get_grafik_agama` | GET | Distribusi agama |
| `data.php?action=get_grafik_pekerjaan` | GET | Top 10 pekerjaan |
| `data.php?action=get_grafik_verifikasi` | GET | Status verifikasi |
| `data.php?action=get_grafik_trend_input` | GET | Tren temporal |
| `data.php?action=get_grafik_umur_gender` | GET | Umur & gender |
| `data.php?action=get_grafik_pendidikan` | GET | Distribusi pendidikan |
| `data.php?action=search_keluarga` | GET | Search keluarga |

---

## 📊 Endpoint Details

### 1. Get Statistics

**URL**
```
GET /api/data.php?action=get_stats
```

**Parameters**
```
Tidak ada
```

**Response (Success)**
```json
{
  "success": true,
  "data": {
    "total_kartu": "55",
    "total_penduduk": "111",
    "verifikasi_pending": "15",
    "verifikasi_terverifikasi": "30",
    "verifikasi_ditolak": "10",
    "verifikasi_revisi": "0",
    "total_kecamatan": "5"
  }
}
```

**Response (Error)**
```json
{
  "success": false,
  "message": "Error message here"
}
```

**Example Usage**
```javascript
fetch('./api/data.php?action=get_stats')
  .then(response => response.json())
  .then(data => {
    console.log('Total Kartu:', data.data.total_kartu);
    console.log('Total Penduduk:', data.data.total_penduduk);
  })
  .catch(error => console.error('Error:', error));
```

**Chart Purpose**: Status display in header

---

### 2. Get Data Terbaru

**URL**
```
GET /api/data.php?action=get_data_terbaru&limit=20
```

**Parameters**
```
limit (optional): Number of records (default: 20)
```

**Response**
```json
{
  "success": true,
  "data": [
    {
      "id_keluarga": "1",
      "no_kartu_keluarga": "1175011201345001",
      "kepala_keluarga": "SUMAIDI",
      "kelurahan": "Petisah",
      "kecamatan": "MEDAN BARU",
      "alamat": "Jl Gajah Mada No 123",
      "status_verifikasi": "terverifikasi",
      "tanggal_input": "2025-01-06 10:30:45",
      "jumlah_anggota": "3"
    },
    // ... more records
  ]
}
```

**Example Usage**
```javascript
// Get 20 records (default)
fetch('./api/data.php?action=get_data_terbaru&limit=20')
  .then(r => r.json())
  .then(data => {
    data.data.forEach(keluarga => {
      console.log(keluarga.kepala_keluarga, '(' + keluarga.jumlah_anggota + ' anggota)');
    });
  });

// Get 50 records
fetch('./api/data.php?action=get_data_terbaru&limit=50')
```

**Chart Purpose**: Dashboard data table

---

### 3. Get Data by Kecamatan

**URL**
```
GET /api/data.php?action=get_data_by_kecamatan
```

**Parameters**
```
Tidak ada
```

**Response**
```json
{
  "success": true,
  "data": [
    {
      "kecamatan": "MEDAN BARU",
      "total_kartu": "15",
      "total_penduduk": "35"
    },
    {
      "kecamatan": "MEDAN JOHOR",
      "total_kartu": "12",
      "total_penduduk": "28"
    },
    {
      "kecamatan": "MEDAN SELAYANG",
      "total_kartu": "10",
      "total_penduduk": "22"
    },
    {
      "kecamatan": "MEDAN BELAWAN",
      "total_kartu": "9",
      "total_penduduk": "18"
    },
    {
      "kecamatan": "MEDAN MAIMUN",
      "total_kartu": "9",
      "total_penduduk": "8"
    }
  ]
}
```

**Example Usage**
```javascript
fetch('./api/data.php?action=get_data_by_kecamatan')
  .then(r => r.json())
  .then(data => {
    const labels = data.data.map(d => d.kecamatan);
    const values = data.data.map(d => d.total_kartu);
    createChart(labels, values);
  });
```

**Chart Purpose**: Bar chart - Distribusi Kecamatan (Dashboard)

---

### 4. Get Grafik Agama

**URL**
```
GET /api/data.php?action=get_grafik_agama
```

**Response**
```json
{
  "success": true,
  "labels": ["Islam", "Kristen", "Katolik", "Hindu", "Buddha"],
  "data": [85, 15, 8, 2, 1]
}
```

**Example Usage**
```javascript
fetch('./api/data.php?action=get_grafik_agama')
  .then(r => r.json())
  .then(data => {
    // Create doughnut chart
    const ctx = document.getElementById('chartAgama').getContext('2d');
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: data.labels,
        datasets: [{
          data: data.data,
          backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']
        }]
      }
    });
  });
```

**Chart Purpose**: Doughnut Chart - Agama Penduduk (Dashboard)

---

### 5. Get Grafik Trend Input

**URL**
```
GET /api/data.php?action=get_grafik_trend_input
```

**Response**
```json
{
  "success": true,
  "labels": ["January", "February", "March", "April", "May"],
  "datasets": {
    "input": [45, 52, 48, 55, 60],
    "verifikasi": [30, 40, 38, 45, 50]
  }
}
```

**Example Usage**
```javascript
fetch('./api/data.php?action=get_grafik_trend_input')
  .then(r => r.json())
  .then(data => {
    // Create line chart
    const ctx = document.getElementById('chartTrend').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: data.labels,
        datasets: [
          {
            label: 'Input Data',
            data: data.datasets.input,
            borderColor: '#36A2EB',
            tension: 0.4
          },
          {
            label: 'Verifikasi',
            data: data.datasets.verifikasi,
            borderColor: '#FF6384',
            tension: 0.4
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: { duration: 1000 }
      }
    });
  });
```

**Chart Purpose**: Line Chart - Trend Input Per Bulan (Grafik Tab)

---

### 6. Get Grafik Umur & Gender

**URL**
```
GET /api/data.php?action=get_grafik_umur_gender
```

**Response**
```json
{
  "success": true,
  "labels": ["0-10", "11-20", "21-30", "31-40", "41-50", "51-60", "61+"],
  "datasets": {
    "Laki-laki": [12, 18, 25, 22, 15, 10, 5],
    "Perempuan": [10, 16, 28, 20, 18, 12, 7]
  }
}
```

**Example Usage**
```javascript
fetch('./api/data.php?action=get_grafik_umur_gender')
  .then(r => r.json())
  .then(data => {
    const ctx = document.getElementById('chartUmurGender').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: data.labels,
        datasets: [
          {
            label: 'Laki-laki',
            data: data.datasets['Laki-laki'],
            borderColor: '#36A2EB',
            backgroundColor: 'rgba(54, 162, 235, 0.1)',
            tension: 0.4
          },
          {
            label: 'Perempuan',
            data: data.datasets['Perempuan'],
            borderColor: '#FF6384',
            backgroundColor: 'rgba(255, 99, 132, 0.1)',
            tension: 0.4
          }
        ]
      }
    });
  });
```

**Chart Purpose**: Line Chart - Umur & Gender (Grafik Tab)

---

### 7. Get Grafik Pendidikan

**URL**
```
GET /api/data.php?action=get_grafik_pendidikan
```

**Response**
```json
{
  "success": true,
  "labels": ["SD", "SMP", "SMA", "Diploma", "Sarjana", "Tidak Sekolah"],
  "data": [25, 30, 35, 10, 8, 3]
}
```

**Chart Purpose**: Horizontal Bar Chart - Pendidikan Terakhir (Grafik Tab)

---

### 8. Get Grafik Pekerjaan

**URL**
```
GET /api/data.php?action=get_grafik_pekerjaan
```

**Response**
```json
{
  "success": true,
  "labels": ["Petani", "Pedagang", "Buruh", "Pegawai Negeri", "Pengusaha", "Sopir", "Tukang", "Ibu Rumah Tangga", "Pelajar", "Pensiunan"],
  "data": [15, 12, 10, 8, 7, 6, 5, 4, 3, 2]
}
```

**Chart Purpose**: Horizontal Bar Chart - Top 10 Pekerjaan (Grafik Tab)

---

### 9. Get Grafik Verifikasi

**URL**
```
GET /api/data.php?action=get_grafik_verifikasi
```

**Response**
```json
{
  "success": true,
  "labels": ["Pending", "Terverifikasi", "Ditolak"],
  "data": [10, 35, 10],
  "colors": ["#FFC107", "#28A745", "#DC3545"]
}
```

**Chart Purpose**: Bar Chart - Status Verifikasi (Grafik Tab)

---

### 10. Get Grafik Agama Full

**URL**
```
GET /api/data.php?action=get_grafik_agama
```
(Alternative endpoint with more detail)

**Response**
```json
{
  "success": true,
  "labels": ["Islam", "Kristen", "Katolik", "Hindu", "Buddha"],
  "data": [85, 15, 8, 2, 1]
}
```

**Chart Purpose**: Pie Chart - Agama Penduduk Detail (Grafik Tab)

---

### 11. Search Keluarga

**URL**
```
GET /api/data.php?action=search_keluarga&q=SUMAIDI
```

**Parameters**
```
q (required): Search query (nomor KK atau nama kepala keluarga)
```

**Response**
```json
{
  "success": true,
  "data": [
    {
      "id_keluarga": "1",
      "no_kartu_keluarga": "1175011201345001",
      "kepala_keluarga": "SUMAIDI",
      "kelurahan": "Petisah",
      "kecamatan": "MEDAN BARU",
      "alamat": "Jl Gajah Mada No 123",
      "status_verifikasi": "terverifikasi",
      "jumlah_anggota": "3"
    }
  ]
}
```

**Example Usage**
```javascript
const searchQuery = 'SUMAIDI';
fetch(`./api/data.php?action=search_keluarga&q=${encodeURIComponent(searchQuery)}`)
  .then(r => r.json())
  .then(data => {
    if (data.data.length > 0) {
      console.log('Found:', data.data);
    } else {
      console.log('No results');
    }
  });
```

---

## 🔄 Complete JavaScript Example

```javascript
// Fetch all data for dashboard initialization
async function initializeDashboard() {
  try {
    // Get statistics
    const statsRes = await fetch('./api/data.php?action=get_stats');
    const stats = await statsRes.json();
    
    // Get data terbaru
    const terbaruRes = await fetch('./api/data.php?action=get_data_terbaru&limit=20');
    const terbaru = await terbaruRes.json();
    
    // Get charts data
    const kecamatanRes = await fetch('./api/data.php?action=get_data_by_kecamatan');
    const kecamatan = await kecamatanRes.json();
    
    const agamaRes = await fetch('./api/data.php?action=get_grafik_agama');
    const agama = await agamaRes.json();
    
    // Update UI
    updateStatistics(stats.data);
    updateDataTable(terbaru.data);
    createChartKecamatan(kecamatan.data);
    createChartAgama(agama.data, agama.labels);
    
  } catch (error) {
    console.error('Error initializing dashboard:', error);
  }
}

// Initialize when DOM ready
document.addEventListener('DOMContentLoaded', initializeDashboard);
```

---

## 🔒 Error Handling

All endpoints return consistent error response:
```json
{
  "success": false,
  "message": "Error description here"
}
```

**Common Errors:**

| Error | Cause | Solution |
|-------|-------|----------|
| "Database connection failed" | MySQL not running | Start MySQL service |
| "Table not found" | Schema not imported | Import setup.sql |
| "No data found" | Empty tables | Run generate_data.php |
| "Invalid action" | Wrong parameter | Check endpoint spelling |

---

## 📈 Rate Limiting & Caching

**No rate limiting implemented** - Feel free to call endpoints frequently.

**Suggested client-side caching:**
```javascript
const CACHE_TTL = 5 * 60 * 1000; // 5 minutes
const apiCache = {};

async function fetchWithCache(url) {
  if (apiCache[url] && Date.now() - apiCache[url].time < CACHE_TTL) {
    return apiCache[url].data;
  }
  
  const response = await fetch(url);
  const data = await response.json();
  apiCache[url] = { data, time: Date.now() };
  return data;
}
```

---

## 📱 CORS & Cross-Domain

**Current Setup**: No CORS restrictions (same-origin only).

**To enable cross-domain access**, add to `api/data.php`:
```php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
```

---

## 🧪 Testing Endpoints

### Using cURL
```bash
# Get statistics
curl "http://localhost/survey-kependudukan/api/data.php?action=get_stats"

# Get data by kecamatan
curl "http://localhost/survey-kependudukan/api/data.php?action=get_data_by_kecamatan"

# Search keluarga
curl "http://localhost/survey-kependudukan/api/data.php?action=search_keluarga&q=SUMAIDI"
```

### Using PowerShell
```powershell
# Get statistics
Invoke-WebRequest "http://localhost/survey-kependudukan/api/data.php?action=get_stats" | ConvertFrom-Json

# Get data by kecamatan  
Invoke-WebRequest "http://localhost/survey-kependudukan/api/data.php?action=get_data_by_kecamatan" | ConvertFrom-Json
```

### Using Browser Console
```javascript
// Copy-paste into browser F12 console
fetch('./api/data.php?action=get_stats')
  .then(r => r.json())
  .then(d => console.table(d.data));
```

---

## 📊 API Response Sizes (Approximate)

| Endpoint | Size | Response Time |
|----------|------|---------------|
| get_stats | 0.5 KB | <10ms |
| get_data_terbaru | 15 KB | <50ms |
| get_data_by_kecamatan | 0.5 KB | <10ms |
| get_grafik_agama | 1 KB | <15ms |
| get_grafik_trend_input | 2 KB | <20ms |
| get_grafik_umur_gender | 2 KB | <20ms |
| get_grafik_pendidikan | 1 KB | <15ms |
| get_grafik_pekerjaan | 1 KB | <15ms |
| get_grafik_verifikasi | 1 KB | <15ms |
| search_keluarga | Variable | <30ms |

---

## 🔐 Security Notes

**Current Implementation:**
- ✅ MySQLi prepared statements (SQL injection protection)
- ✅ Input validation for search queries
- ⚠️ No authentication/authorization (public API)
- ⚠️ No rate limiting

**For Production:**
- Add API key authentication
- Implement rate limiting
- Add request logging
- Use HTTPS only
- Validate all inputs

---

**Version**: 1.0  
**Last Updated**: January 2026  
**Status**: Complete ✅
