# CHART DAN GRAFIK PANDUAN

## 📊 Overview Semua Chart

Dashboard menyediakan **9 chart interaktif** dengan animasi smooth untuk visualisasi data kependudukan.

### Chart Summary Table

| No | Nama Chart | Tipe | Lokasi | Fitur |
|----|-----------|----|--------|------|
| 1 | Distribusi Kecamatan | Bar | Dashboard | Stacked bars |
| 2 | Agama Penduduk | Doughnut | Dashboard | Interactive legend |
| 3 | Tren Input Data | Line | Grafik Tab | Dual series (Input vs Verifikasi) |
| 4 | Umur & Gender | Line | Grafik Tab | Dual series (Laki-laki vs Perempuan) |
| 5 | Agama Detail | Pie | Grafik Tab | Full breakdown |
| 6 | Verifikasi Status | Bar | Grafik Tab | Pending/Terverifikasi/Ditolak |
| 7 | Pendidikan | Horizontal Bar | Grafik Tab | Sortable by education level |
| 8 | Pekerjaan | Horizontal Bar | Grafik Tab | Top 10 occupations |
| 9 | Kecamatan Detail | Line | Grafik Tab | Keluarga per kecamatan |

---

## 🎨 Styling & Animasi

### Global Chart Configuration
```javascript
// Default chart options dalam script.js
const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,  // Important untuk responsive
  animation: {
    duration: 1000,  // 1 second animation
    easing: 'easeInOutQuart'
  },
  plugins: {
    legend: {
      display: true,
      position: 'top',
      labels: {
        usePointStyle: true,
        padding: 15,
        font: { size: 12 }
      }
    }
  }
};
```

### CSS Animations
Setiap chart memiliki CSS animation:

```css
/* File: assets/css/style.css */

/* Chart slide-in animation */
.chart-container {
  animation: chartSlideIn 0.6s ease-out;
  min-height: 350px;
}

@keyframes chartSlideIn {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Canvas fade-in animation */
.chart-container canvas {
  animation: chartFadeIn 0.8s ease-in-out 0.2s backwards;
}

@keyframes chartFadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}
```

**Animation Timing:**
- Container slide-in: 0.6s from bottom with opacity
- Canvas fade-in: 0.8s with 0.2s delay
- Data animation: 1000ms easeInOutQuart
- Hover effects: Point highlight + tooltip

---

## 📍 Dashboard Charts

### Chart 1: Distribusi Kecamatan (Bar Chart)

**Fungsi**: Menampilkan jumlah kartu keluarga per kecamatan

**Data Source**: `get_data_by_kecamatan` API

**Visual**:
```
MEDAN BARU     |████████████████████| 15 kartu
MEDAN JOHOR    |█████████████████   | 12 kartu
MEDAN SELAYANG |████████████████    | 10 kartu
MEDAN BELAWAN  |██████████████      | 9 kartu
MEDAN MAIMUN   |██████████████      | 9 kartu
```

**Configuration**:
```javascript
function createChartKecamatan(data) {
  const ctx = document.getElementById('chartKecamatan').getContext('2d');
  
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: data.map(d => d.kecamatan),
      datasets: [{
        label: 'Total Kartu Keluarga',
        data: data.map(d => d.total_kartu),
        backgroundColor: [
          '#FF6384',
          '#36A2EB',
          '#FFCE56',
          '#4BC0C0',
          '#9966FF'
        ],
        borderRadius: 5
      }]
    },
    options: {
      ...chartOptions,
      maintainAspectRatio: false,  // Fill container
      scales: {
        y: { beginAtZero: true }
      }
    }
  });
}
```

**Interaksi**:
- Hover: Show value tooltip
- Click legend: Toggle series visibility
- Responsive: Adapts to screen size

---

### Chart 2: Agama Penduduk (Doughnut Chart)

**Fungsi**: Distribusi agama penduduk dengan visual yang menarik

**Data Source**: `get_grafik_agama` API

**Sample Data**:
- Islam: 85 (76%)
- Kristen: 15 (14%)
- Katolik: 8 (7%)
- Hindu: 2 (2%)
- Buddha: 1 (1%)

**Configuration**:
```javascript
function createChartAgama(labels, data) {
  const ctx = document.getElementById('chartAgama').getContext('2d');
  
  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: labels,
      datasets: [{
        data: data,
        backgroundColor: [
          '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'
        ],
        borderColor: '#fff',
        borderWidth: 2
      }]
    },
    options: {
      ...chartOptions,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom'
        }
      }
    }
  });
}
```

**Keunikan**:
- Doughnut hole (center gap) untuk estetika
- Warna berbeda untuk setiap agama
- White border untuk clarity

---

## 📈 Grafik Tab - 7 Advanced Charts

### Chart 3: Tren Input Data Per Bulan (LINE CHART) ⭐

**Fungsi**: Menampilkan tren input dan verifikasi data per bulan

**Type**: Multi-series Line Chart

**Data Source**: `get_grafik_trend_input` API

**Series**:
1. **Input Data** (Blue line) - Total keluarga yang di-input
2. **Verifikasi** (Red line) - Total keluarga yang terverifikasi

**Sample Data**:
```
Bulan       | Input | Verifikasi
January     | 45    | 30
February    | 52    | 40
March       | 48    | 38
April       | 55    | 45
May         | 60    | 50
```

**Configuration**:
```javascript
function createChartTrendInput(labels, datasets) {
  const ctx = document.getElementById('chartTrendInput').getContext('2d');
  
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [
        {
          label: 'Data Input',
          data: datasets.input,
          borderColor: '#36A2EB',
          backgroundColor: 'rgba(54, 162, 235, 0.1)',
          borderWidth: 2,
          tension: 0.4,  // Smooth curve
          fill: true,
          pointRadius: 4,
          pointHoverRadius: 6
        },
        {
          label: 'Verifikasi',
          data: datasets.verifikasi,
          borderColor: '#FF6384',
          backgroundColor: 'rgba(255, 99, 132, 0.1)',
          borderWidth: 2,
          tension: 0.4,
          fill: true,
          pointRadius: 4,
          pointHoverRadius: 6
        }
      ]
    },
    options: {
      ...chartOptions,
      interaction: {
        mode: 'index',
        intersect: false
      },
      scales: {
        y: {
          beginAtZero: true,
          title: { display: true, text: 'Jumlah Data' }
        },
        x: {
          title: { display: true, text: 'Bulan' }
        }
      }
    }
  });
}
```

**Visual Features**:
- ✅ Two smooth lines (tension: 0.4)
- ✅ Filled area under lines
- ✅ Interactive points (hover to see value)
- ✅ Legend to toggle series
- ✅ Responsive grid

**Interpretasi**:
- **Naik garis input**: Banyak data baru dicatat
- **Naik garis verifikasi**: Banyak data yang dikonfirmasi
- **Gap antara lines**: Backlog verifikasi
- **Trend positif**: Sistem berjalan baik

---

### Chart 4: Perbandingan Umur & Gender (LINE CHART) ⭐

**Fungsi**: Membandingkan distribusi usia antara laki-laki dan perempuan

**Type**: Multi-series Line Chart

**Data Source**: `get_grafik_umur_gender` API

**Series**:
1. **Laki-laki** (Blue) - Penduduk laki-laki per kelompok usia
2. **Perempuan** (Red) - Penduduk perempuan per kelompok usia

**Sample Data**:
```
Usia    | Laki-laki | Perempuan
0-10    | 12        | 10
11-20   | 18        | 16
21-30   | 25        | 28
31-40   | 22        | 20
41-50   | 15        | 18
51-60   | 10        | 12
61+     | 5         | 7
```

**Configuration**:
```javascript
function createChartUmurGender(labels, datasets) {
  const ctx = document.getElementById('chartUmurGender').getContext('2d');
  
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [
        {
          label: 'Laki-laki',
          data: datasets['Laki-laki'],
          borderColor: '#36A2EB',
          backgroundColor: 'rgba(54, 162, 235, 0.1)',
          borderWidth: 2,
          tension: 0.4,
          fill: true,
          pointRadius: 4
        },
        {
          label: 'Perempuan',
          data: datasets['Perempuan'],
          borderColor: '#FF6384',
          backgroundColor: 'rgba(255, 99, 132, 0.1)',
          borderWidth: 2,
          tension: 0.4,
          fill: true,
          pointRadius: 4
        }
      ]
    },
    options: {
      ...chartOptions,
      scales: {
        y: { beginAtZero: true, title: { display: true, text: 'Jumlah Penduduk' } },
        x: { title: { display: true, text: 'Kelompok Usia' } }
      }
    }
  });
}
```

**Visual Features**:
- ✅ Dual-color comparison
- ✅ Smooth bezier curves
- ✅ Filled areas
- ✅ Point markers
- ✅ Legend toggle

**Interpretasi**:
- **Lines yang naik**: Banyak penduduk di usia tersebut
- **Perempuan > Laki-laki**: Gender imbalance di usia tersebut
- **Trend keseluruhan**: Demographic pyramid shape

---

### Chart 5: Distribusi Agama Detail (PIE CHART)

**Fungsi**: Breakdown lengkap agama penduduk (pie version)

**Type**: Pie Chart

**Data Source**: `get_grafik_agama` API

**Configuration**:
```javascript
function createChartAgamaFull(labels, data) {
  const ctx = document.getElementById('chartAgamaFull').getContext('2d');
  
  new Chart(ctx, {
    type: 'pie',
    data: {
      labels: labels,
      datasets: [{
        data: data,
        backgroundColor: [
          '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'
        ],
        borderColor: '#fff',
        borderWidth: 2
      }]
    },
    options: {
      ...chartOptions,
      plugins: {
        legend: {
          position: 'right'
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              let label = context.label || '';
              if (label) label += ': ';
              label += context.parsed + ' orang';
              return label;
            }
          }
        }
      }
    }
  });
}
```

---

### Chart 6: Status Verifikasi (BAR CHART)

**Fungsi**: Menampilkan status verifikasi keluarga

**Type**: Bar Chart dengan 3 kategori

**Categories**:
- **Pending** (Yellow) - Menunggu verifikasi
- **Terverifikasi** (Green) - Sudah terverifikasi
- **Ditolak** (Red) - Ditolak/Revisi diperlukan

**Configuration**:
```javascript
function createChartVerifikasi(labels, data, colors) {
  const ctx = document.getElementById('chartVerifikasi').getContext('2d');
  
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Jumlah Keluarga',
        data: data,
        backgroundColor: colors,
        borderRadius: 5
      }]
    },
    options: {
      ...chartOptions,
      scales: {
        y: { beginAtZero: true }
      }
    }
  });
}
```

---

### Chart 7: Pendidikan Terakhir (HORIZONTAL BAR)

**Fungsi**: Distribusi jenjang pendidikan penduduk

**Type**: Horizontal Bar Chart

**Categories**:
- SD
- SMP
- SMA
- Diploma
- Sarjana
- Tidak Sekolah

**Configuration**:
```javascript
function createChartPendidikan(labels, data) {
  const ctx = document.getElementById('chartPendidikan').getContext('2d');
  
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Jumlah Penduduk',
        data: data,
        backgroundColor: '#36A2EB',
        borderRadius: 5
      }]
    },
    options: {
      ...chartOptions,
      indexAxis: 'y',  // Horizontal
      scales: {
        x: { beginAtZero: true }
      }
    }
  });
}
```

---

### Chart 8: Top 10 Pekerjaan (HORIZONTAL BAR)

**Fungsi**: Pekerjaan terbanyak di komunitas

**Type**: Horizontal Bar Chart

**Top Occupations**:
1. Petani
2. Pedagang
3. Buruh
4. Pegawai Negeri
5. Pengusaha
6. Sopir
7. Tukang
8. Ibu Rumah Tangga
9. Pelajar
10. Pensiunan

**Configuration**:
```javascript
function createChartPekerjaan(labels, data) {
  const ctx = document.getElementById('chartPekerjaan').getContext('2d');
  
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Jumlah Penduduk',
        data: data,
        backgroundColor: '#FF6384',
        borderRadius: 5
      }]
    },
    options: {
      ...chartOptions,
      indexAxis: 'y',
      scales: {
        x: { beginAtZero: true }
      }
    }
  });
}
```

---

### Chart 9: Kecamatan Detail (LINE CHART) ⭐

**Fungsi**: Perbandingan jumlah keluarga per kecamatan dengan trend line

**Type**: Line Chart (kecamatan comparison)

**Data Source**: `get_data_by_kecamatan` API

**Series**: Satu line untuk setiap kecamatan

**Configuration**:
```javascript
function createChartKecamatanDetail(data) {
  const ctx = document.getElementById('chartKecamatanDetail').getContext('2d');
  
  const colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'];
  const datasets = data.map((d, index) => ({
    label: d.kecamatan,
    data: [d.total_kartu, d.total_penduduk],
    borderColor: colors[index],
    backgroundColor: colors[index] + '20',
    borderWidth: 2,
    tension: 0.4,
    fill: true
  }));
  
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Total Kartu', 'Total Penduduk'],
      datasets: datasets
    },
    options: {
      ...chartOptions,
      scales: {
        y: { beginAtZero: true }
      }
    }
  });
}
```

---

## 🎯 Interaction Features

### 1. Hover Tooltips
Setiap chart menampilkan tooltip saat di-hover:
```javascript
plugins: {
  tooltip: {
    enabled: true,
    backgroundColor: 'rgba(0,0,0,0.8)',
    titleColor: '#fff',
    bodyColor: '#fff',
    padding: 10,
    displayColors: true
  }
}
```

### 2. Legend Toggle
Klik legend items untuk show/hide series:
```javascript
plugins: {
  legend: {
    onClick: function(e, legendItem, legend) {
      const index = legendItem.datasetIndex;
      const chart = legend.chart;
      chart.getDatasetMeta(index).hidden = !chart.getDatasetMeta(index).hidden;
      chart.update();
    }
  }
}
```

### 3. Responsive Sizing
```javascript
options: {
  responsive: true,
  maintainAspectRatio: false,  // Allow custom height
  onResize: function(chart) {
    // Refresh on resize
  }
}
```

---

## 📱 Mobile Optimization

Semua chart responsive untuk mobile:
```css
@media (max-width: 768px) {
  .chart-container {
    min-height: 250px;  /* Smaller on mobile */
  }
  
  .chart-container canvas {
    max-height: 300px;
  }
}
```

---

## 🔧 Customization Guide

### Mengubah Warna Chart
```javascript
// Di script.js, update backgroundColor array
backgroundColor: [
  '#FF6384',  // Pink
  '#36A2EB',  // Blue
  '#FFCE56',  // Yellow
  '#4BC0C0',  // Teal
  '#9966FF'   // Purple
]
```

### Mengubah Animation Speed
```javascript
options: {
  animation: {
    duration: 1000,  // Change this (in milliseconds)
    easing: 'easeInOutQuart'
  }
}
```

### Mengubah Chart Type
```javascript
// Change type property
type: 'bar'      // Bar
type: 'line'     // Line
type: 'pie'      // Pie
type: 'doughnut' // Doughnut
type: 'radar'    // Radar (bonus)
```

---

## 📊 Data Interpretation Guide

### Line Charts (Trend)
- **X-axis (Horizontal)**: Time period atau category
- **Y-axis (Vertical)**: Value/count
- **Slope up**: Nilai meningkat
- **Slope down**: Nilai menurun
- **Flat line**: Nilai stabil

### Bar Charts
- **Bar height**: Nilai/jumlah
- **Longer bar**: Nilai lebih besar
- **Color**: Category atau series

### Pie/Doughnut Charts
- **Slice size**: Proporsi dari total
- **Larger slice**: Persentase lebih tinggi
- **Small slices**: Minority categories

---

**Version**: 1.0  
**Last Updated**: January 2026  
**Status**: Complete with 3 Line Charts ✅
