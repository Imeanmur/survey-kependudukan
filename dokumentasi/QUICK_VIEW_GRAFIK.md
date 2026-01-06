# 🚀 QUICK START - Grafik & Animasi Dinamis

## ⚡ 3 Langkah Akses Dashboard

### 1. Buka Dashboard
```
URL: http://localhost/survey-kependudukan/
```

### 2. Klik Tab "Grafik & Analisis"
```
Sidebar kiri → Grafik & Analisis icon (chart line)
```

### 3. Lihat 7 Grafik dengan Animasi Menarik
```
✅ Trend Input Data Per Bulan (Line Chart)
✅ Perbandingan Umur dan Gender (Line Chart)
✅ Distribusi Agama Penduduk (Pie Chart)
✅ Status Verifikasi Keluarga (Bar Chart)
✅ Pendidikan Terakhir Penduduk (Horizontal Bar)
✅ Pekerjaan Top 10 (Horizontal Bar)
✅ Perbandingan Kecamatan (Line Chart)
```

---

## 🎨 Animasi yang Bisa Dilihat

### Saat Chart Pertama Kali Load
```
1. Chart slide dari bawah dengan fade-in effect
2. Line dan area fill dengan smooth progression
3. Points muncul bertahap
4. Durasi: 0.6-1.2 detik per chart
```

### Saat Hover ke Data Points
```
1. Point radius membesar (6px → 8px)
2. Tooltip muncul dengan info detail
3. Line menjadi lebih prominent
4. Smooth transition tanpa jank
```

### Efek Card Hover
```
1. Shadow meningkat untuk depth
2. Card naik sedikit (translateY -4px)
3. Icon pada header rotate sedikit
4. Background header berubah warna
```

---

## 📊 Data yang Ditampilkan

### Trend Input Data Per Bulan
```
Mengambil dari tabel: keluarga
Query: GROUP BY DATE_FORMAT(tanggal_input, '%Y-%m')
Menampilkan:
  - Total data input per bulan
  - Total data terverifikasi per bulan
  - Trend line menunjukkan pola input
```

### Perbandingan Umur dan Gender
```
Mengambil dari tabel: penduduk
Kelompok umur (7 kategori):
  - 0-5 Tahun
  - 6-11 Tahun
  - 12-17 Tahun
  - 18-29 Tahun
  - 30-44 Tahun
  - 45-59 Tahun
  - 60+ Tahun
Menampilkan: Laki-laki (biru) vs Perempuan (merah muda)
```

---

## 💻 Technical Details

### Database Connection
```
File: includes/config.php
- Server: localhost
- User: root
- Password: (empty)
- Database: survey_kependudukan
```

### API Endpoints
```
Base URL: api/data.php

?action=get_grafik_trend_input      → Trend data per bulan
?action=get_grafik_umur_gender      → Age-gender distribution
?action=get_grafik_verifikasi       → Verification status
?action=get_grafik_pendidikan       → Education distribution
?action=get_grafik_agama            → Religion distribution
?action=get_grafik_pekerjaan        → Job distribution
?action=get_data_by_kecamatan       → District statistics
```

### JavaScript Chart Library
```
Chart.js 3.9.1
Location: CDN (https://cdnjs.cloudflare.com/ajax/libs/chart.js/3.9.1/chart.min.js)
Types: Line, Bar, Pie, Doughnut
```

---

## 🎯 Key Features

### Interaktif
- ✅ Hover untuk tooltip
- ✅ Click legend untuk toggle series
- ✅ Zoom support pada mobile
- ✅ Touch gestures

### Responsif
- ✅ Desktop optimized (full-width, 400px height)
- ✅ Tablet optimized (350px height)
- ✅ Mobile optimized (300px height, single column)

### Performance
- ✅ Smooth 60fps animation
- ✅ Parallel data loading
- ✅ Auto-refresh 5 menit
- ✅ Error handling graceful

### Visual
- ✅ Vibrant color scheme
- ✅ Professional styling
- ✅ Clear typography
- ✅ Subtle shadows & depth

---

## 🔄 Auto-Refresh

Dashboard otomatis refresh data setiap:
```
- 5 menit untuk semua chart
- Manual refresh dengan button di top-right (sync icon)
```

---

## 📱 Mobile View

Pada mobile phone, chart akan:
```
- Full width, 1 column
- Reduced height (250-300px)
- Touch-friendly interactions
- Pinch-zoom support
- Optimized for battery/performance
```

---

## ✨ Animasi Timing

| Chart | Load Time | Animation Type |
|-------|-----------|-----------------|
| Trend Input | 0.6s | Slide-in + Data Fill |
| Umur Gender | 0.8s | Slide-in + Data Fill |
| Verifikasi | 0.8s | Slide-in + Bar Animate |
| Pendidikan | 0.8s | Slide-in + Bar Animate |
| Agama | 0.6s | Slide-in + Pie Rotate |
| Pekerjaan | 0.8s | Slide-in + Bar Animate |
| Kecamatan | 0.8s | Slide-in + Data Fill |

---

## 🎨 Warna Default

```
Line Chart 1: #667eea (Ungu)
Line Chart 2: #43e97b (Hijau)
Line Chart 3: #4facfe (Biru)
Line Chart 4: #fa709a (Pink)

Bar Colors: Multiple gradient colors
Status Colors: 
  - Terverifikasi: Hijau
  - Pending: Orange
  - Ditolak: Merah
  - Revisi: Ungu
```

---

## 🐛 Troubleshooting

### Chart tidak muncul?
```
1. Check Console (F12 → Console tab)
2. Pastikan database sudah import survey_kependudukan.sql
3. Test API: http://localhost/survey-kependudukan/api/data.php?action=get_stats
```

### Animasi terasa lambat?
```
1. Close background apps
2. Try different browser
3. Check if GPU acceleration enabled
```

### Data tidak update?
```
1. Click refresh button
2. Or wait 5 minutes untuk auto-refresh
3. Check network tab untuk API calls
```

---

## 📞 Support

Untuk issue lebih lanjut, silakan baca file dokumentasi:
- GRAFIK_ANIMASI_INFO.md (Detailed documentation)
- GRAFIK_UPDATE_SUMMARY.md (Summary of changes)

---

**Status**: ✅ Production Ready
**Last Updated**: Januari 2026
**Dashboard**: Survey Kependudukan Medan

Enjoy the visualizations! 🎉
