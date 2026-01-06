# PANDUAN LENGKAP - SURVEY KEPENDUDUKAN DASHBOARD

## 🎯 Daftar Isi

1. **Mulai Cepat** (5 menit)
2. **Apa Itu Dashboard?**
3. **Navigasi & Interface**
4. **Menggunakan Setiap Tab**
5. **Memahami Chart**
6. **Mencari & Filter Data**
7. **Troubleshooting Cepat**
8. **FAQ**

---

## ⚡ Mulai Cepat (5 Menit)

### Langkah 1: Buka Dashboard
```
http://localhost/survey-kependudukan/
```

### Langkah 2: Lihat Statistik
- Jika halaman muncul dengan angka: ✅ Sistem berjalan
- Jika halaman kosong/error: Lihat Troubleshooting bagian bawah

### Langkah 3: Jelajahi Tab
- Klik tab "Grafik & Analisis" untuk lihat 7 charts
- Klik tab "Penduduk" untuk lihat daftar lengkap

### Langkah 4: Coba Fitur
- Hover mouse ke chart untuk lihat tooltip
- Klik legend untuk sembunyikan/tampilkan data series
- Gunakan search box untuk cari keluarga

✅ **Done!** Dashboard siap digunakan.

---

## 📊 Apa Itu Dashboard?

### Definisi
Dashboard adalah sistem informasi untuk:
- 📈 Visualisasi data kependudukan Kota Medan
- 📋 Manajemen data keluarga dan penduduk
- 📊 Analisis statistik dan trend
- ✅ Tracking status verifikasi

### Manfaat
```
✓ Informasi real-time dalam satu tempat
✓ Chart interaktif untuk analisis cepat
✓ Search & filter untuk mencari data spesifik
✓ Export data untuk laporan
✓ Mobile-friendly untuk akses di mana saja
```

### Siapa yang Gunakan
- Aparatur/Petugas Pendataan
- Verifikator Data
- Supervisor/Kepala Bagian
- Analisis/Riset

---

## 🎨 Navigasi & Interface

### Layout Utama

```
┌─────────────────────────────────────────┐
│  SURVEY KEPENDUDUKAN - DASHBOARD        │  ← Judul
├──────────────┬──────────────────────────┤
│              │                          │
│  SIDEBAR     │   MAIN CONTENT AREA      │
│  - Dashboard │   - Statistik            │
│  - Grafik    │   - Chart               │
│  - Penduduk  │   - Data Table          │
│  - Laporan   │                          │
│              │                          │
└──────────────┴──────────────────────────┘
```

### Sidebar Menu
```
📊 Dashboard      ← Overview & 2 charts
📈 Grafik & Analisis ← 7 detailed charts  
👥 Penduduk      ← Complete resident list
📄 Laporan       ← Export & reports
```

### Top Bar
```
[🔍 Search Box]  [↻ Refresh]  [Settings]
```

---

## 🗂️ Menggunakan Setiap Tab

### Tab 1: Dashboard

**Konten**:
```
┌─ STATISTIK RINGKAS ─────────────────┐
│ Total Kartu: 55  |  Total Penduduk: 111 │
│ Pending: 15      |  Terverifikasi: 30   │
│ Ditolak: 10      |  Kecamatan: 5        │
└─────────────────────────────────────┘

┌─ CHART 1: DISTRIBUSI KECAMATAN ────┐
│  MEDAN BARU    ████████████ 15    │
│  MEDAN JOHOR   ██████████   12    │
│  (... more)                        │
└─────────────────────────────────────┘

┌─ CHART 2: AGAMA PENDUDUK ──────────┐
│  Islam ███████████ 82 (74%)        │
│  Kristen ████ 18 (16%)             │
│  (... more)                        │
└─────────────────────────────────────┘

┌─ DATA TERBARU TABLE ───────────────┐
│ KK No. | Kepala Keluarga | Kel. │
│ 111... | SUMAIDI        | 3    │
│ 222... | SITI NUR...    | 2    │
│ (... 20 records)                  │
└─────────────────────────────────────┘
```

**Cara Menggunakan**:
1. **Baca Statistik**: Lihat angka di atas
2. **Hover Chart**: Mouse ke atas bar untuk lihat nilai pasti
3. **Klik Legend**: Klik nama agama untuk sembunyikan/tampilkan
4. **Scroll Table**: Lihat data terbaru
5. **Klik Row**: (Jika diimplementasikan) untuk lihat detail

**Tips**:
- Statistik update otomatis setiap 30 detik
- Chart animasi smooth (tidak jangan dikhawatirkan lambat)
- Data table menampilkan 20 terakhir (baru)

---

### Tab 2: Grafik & Analisis

**Berisi 7 Chart**:

#### Chart 1: Tren Input Data Per Bulan
```
Fungsi: Melihat trend input vs verifikasi per bulan
Contoh Bacaan:
  Jan: 45 input, 30 verifikasi → 15 backlog
  Feb: 52 input, 40 verifikasi → 12 backlog
  Mar: 48 input, 38 verifikasi → 10 backlog (✓ bagus, backlog turun)
```

#### Chart 2: Perbandingan Umur & Gender
```
Fungsi: Melihat distribusi usia berdasarkan gender
Contoh Bacaan:
  Usia 21-30: Laki-laki 25, Perempuan 28 → Perempuan lebih banyak
  Usia 41-50: Laki-laki 15, Perempuan 18 → Keduanya serupa
```

#### Chart 3-9: (Lihat dokumentasi GRAFIK_PANDUAN.md)

**Cara Menggunakan**:
1. **Baca Judul**: Pahami apa yang diukur
2. **Lihat Legend**: Warna mana = apa
3. **Hover Point**: Lihat exact value
4. **Klik Legend Item**: Untuk toggle series
5. **Scroll Down**: Untuk lihat chart lainnya

**Tips**:
- Setiap chart punya story yang berbeda
- Combine info dari multiple charts untuk insight
- Line chart trend adalah yang paling penting (tren)

---

### Tab 3: Penduduk

**Konten**: Daftar lengkap semua penduduk (111 orang)

**Columns**:
- NIK (Nomor Induk Kependudukan)
- Nama Lengkap
- Jenis Kelamin (L/P)
- Tanggal Lahir
- Agama
- Pendidikan Terakhir
- Pekerjaan
- Status Penduduk

**Cara Menggunakan**:
```
1. Search:
   - Ketik nama di search box
   - Data otomatis filter realtime

2. Sorting:
   - Klik header column untuk sort
   - Click lagi untuk sort descending

3. Filter (jika ada):
   - Pilih agama → lihat semua orang Islam saja
   - Pilih pekerjaan → lihat semua petani saja

4. Lihat Detail:
   - Klik row untuk lihat detail lengkap
```

---

### Tab 4: Laporan

**Konten**: Export dan generate laporan

**Fitur**:
- Filter by kecamatan
- Filter by status verifikasi
- Filter by date range
- Generate PDF / Excel
- Print langsung

**Cara Menggunakan**:
```
1. Pilih filter (kecamatan, tanggal, status)
2. Klik "Generate Laporan"
3. Pilih format (PDF/Excel)
4. Download atau Print
```

---

## 📊 Memahami Chart

### Jenis Chart & Cara Baca

#### 1️⃣ Bar Chart (Batang)
```
Guna: Membandingkan nilai antar kategori
Cara Baca: Bar lebih tinggi = nilai lebih besar

Contoh:
MEDAN BARU    ████████████ 15
MEDAN JOHOR   ██████████   12
MEDAN SELAYANG█████████    10

Artinya: MEDAN BARU punya keluarga paling banyak
```

#### 2️⃣ Line Chart (Garis) ⭐ PENTING
```
Guna: Melihat trend perubahan seiring waktu
Cara Baca: 
  - Garis naik = nilai meningkat (bagus?)
  - Garis turun = nilai menurun
  - Garis datar = stabil

Contoh (Tren Input Per Bulan):
  Input  ↗ (naik) = Banyak data baru (✓ bagus)
  Verif  ↗ (naik tapi lambat) = Backlog bertambah (⚠ perlu alert)

Gap antara 2 lines = backlog data yang belum diverifikasi
```

#### 3️⃣ Pie/Doughnut Chart
```
Guna: Melihat proporsi dari total
Cara Baca: Slice lebih besar = bagian lebih besar dari total

Contoh (Agama):
Islam     ███████████████ 74%
Kristen   ████ 16%
Katolik   ██ 7%
Lainnya   █ 3%

Artinya: Mayoritas Islam 74%, sisanya 26%
```

### Interaksi Chart

**Hover (Arahkan mouse)**:
```
→ Tooltip muncul
→ Lihat nilai pasti
→ Point/Bar menjadi highlight
```

**Click Legend**:
```
→ Klik "Islam" di legend
→ Bagian Islam hilang dari chart
→ Klik lagi untuk tampilkan
Guna: Fokus ke series spesifik
```

**Resize**:
```
→ Drag browser window lebih kecil
→ Chart otomatis resize
→ Berfungsi di mobile
```

---

## 🔍 Mencari & Filter Data

### Global Search

**Lokasi**: Top bar (search box)

**Cara Gunakan**:
```
1. Klik search box
2. Ketik nomor KK atau nama
3. Enter atau tunggu 1 detik
4. Hasil otomatis tampil
```

**Contoh Query**:
```
"SUMAIDI"              → Cari nama
"1175011201345001"     → Cari nomor KK
"MEDAN BARU"           → Cari kecamatan
```

### Advanced Filter (Laporan Tab)

**Available Filters**:
- Kecamatan (dropdown)
- Status Verifikasi (pending/terverifikasi/ditolak)
- Tanggal (from-to date picker)
- Religion (dropdown)
- Education Level (dropdown)

**Cara Gunakan**:
```
1. Buka tab "Laporan"
2. Pilih filter dari dropdown
3. Klik "Apply Filter"
4. Hasil tampil di bawah
5. Klik "Export" untuk download
```

### Kombinasi Filter

```
Contoh 1: Cari semua data di MEDAN BARU yang belum verifikasi
→ Kecamatan: MEDAN BARU
→ Status: pending
→ Apply

Contoh 2: Lihat trend agama untuk tahun lalu
→ Date: 2024-01-01 to 2024-12-31
→ Group by: Agama
→ Generate Laporan
```

---

## 🆘 Troubleshooting Cepat

### ❌ Chart Tidak Muncul

**Solusi Cepat**:
```
1. Refresh browser: F5
2. Hard refresh: Ctrl+Shift+R (Windows) atau Cmd+Shift+R (Mac)
3. Tunggu 3 detik, chart biasanya muncul
4. Kalo masih tidak: Buka DevTools (F12) → Console
   - Cek error message
   - Screenshot error
   - Hubungi admin
```

### ❌ Statistik Menunjukkan 0

**Solusi**:
```
1. Database mungkin kosong
2. Buka browser console (F12)
3. Paste ini:
   fetch('./api/data.php?action=get_stats')
   .then(r => r.json())
   .then(d => console.log(d))
4. Lihat response
5. Jika success: false → hubungi admin
6. Jika success: true tapi data 0 → need data generation
```

### ❌ Search Tidak Berfungsi

**Solusi**:
```
1. Cek internet connection
2. Jika online tapi search slow → database besar
3. Gunakan exact match (nomor KK full) lebih cepat
```

### ❌ Halaman Lambat

**Solusi**:
```
1. Tutup tab lain (menghemat RAM)
2. Refresh halaman (F5)
3. Nonaktifkan extension (ad blocker, etc)
4. Tunggu 5 detik untuk semua chart render
```

---

## ❓ FAQ - Frequently Asked Questions

### Q: Apakah data realtime?
**A**: Tidak sepenuhnya. Data update ketika:
- Halaman refresh (F5)
- Setiap 30 detik (auto)
- Klik tombol refresh (jika ada)

Untuk realtime, gunakan database query langsung.

---

### Q: Bisakah saya export data?
**A**: Ya, di tab "Laporan" → Download PDF/Excel (jika diimplementasikan)
Atau copy-paste dari tabel ke Excel.

---

### Q: Bagaimana jika data salah?
**A**: 
1. Di tab "Penduduk" cari person tersebut
2. Klik untuk buka detail
3. Klik tombol "Edit" (jika ada)
4. Ubah data → Simpan

---

### Q: Bisakah saya print laporan?
**A**: 
Cara 1: Browser Print (Ctrl+P)
Cara 2: Export ke PDF (recommended)
Cara 3: Tab Laporan → Pilih format → Print dari file

---

### Q: Apa perbedaan "Penduduk" dan "Keluarga"?
**A**:
- **Keluarga**: Satu kartu keluarga (1 kepala + anggota)
- **Penduduk**: Setiap individu di keluarga

Contoh: Keluarga Sumaidi = 1 family record, tapi 3 penduduk (Sumaidi, Istri, Anak)

---

### Q: Bagaimana chart diupdate?
**A**: Chart auto-update dari API setiap kali:
1. Page load/refresh
2. Filter changed
3. 30-second interval (jika active)

---

### Q: Dapatkah saya customize dashboard?
**A**: Saat ini terbatas. Untuk customize:
1. Hubungi admin/developer
2. Mereka bisa ubah:
   - Warna chart
   - Layout
   - Fields yang ditampilkan
   - Update frequency

---

### Q: Apa itu "Backlog Verifikasi"?
**A**: Gap antara data input vs verifikasi.
Contoh:
- Input bulan ini: 60 record
- Terverifikasi: 50 record
- Backlog: 10 record (belum diverifikasi)

Backlog naik = perlu lebih banyak verifikator

---

### Q: Berapa lama data muncul setelah input?
**A**: Langsung muncul di dashboard setelah:
1. Data diinput di database
2. Halaman di-refresh
3. API dipanggil ulang

Untuk update otomatis = tunggu 30 detik.

---

### Q: Dapatkah saya akses dari mobile?
**A**: Ya! Dashboard responsive untuk:
- Smartphone (320px+)
- Tablet (768px+)
- Desktop (1920px+)

Akses: http://localhost/survey-kependudukan/ (jika di network yang sama)
Atau: Buka IP server dari mobile (e.g., http://192.168.1.100/survey-kependudukan/)

---

### Q: Bagaimana jika ada bug/error?
**A**: 
1. Screenshot error
2. Catat langkah yang menyebabkan error
3. Buka F12 → Console → Copy error message
4. Hubungi developer dengan informasi di atas

---

## 📞 Kontak & Dukungan

**Untuk Bantuan**:
- Hubungi: Admin/Developer
- Email: diskominfo@medan.go.id
- WhatsApp: [No admin]

**Informasi Berguna saat Laporan Bug**:
- Screenshot error
- Browser: Chrome/Firefox/Safari/Edge
- OS: Windows/Mac/Linux
- Langkah reproduce
- Console error message (F12)

---

## 📚 Dokumentasi Lengkap

Untuk info lebih detail, lihat file dokumentasi:
- `README.md` - Overview lengkap
- `SETUP_DAN_INSTALASI.md` - Setup guide
- `API_DOKUMENTASI.md` - API reference
- `GRAFIK_PANDUAN.md` - Chart explanation
- `DATABASE_SKEMA.md` - Database structure
- `TROUBLESHOOTING.md` - Detailed troubleshooting
- `FEATURES.md` - Feature list

---

## ✅ Checklist Awal Pemakaian

Pastikan sebelum mulai:
- [ ] Dashboard terbuka (http://localhost/survey-kependudukan/)
- [ ] Statistik menunjukkan angka > 0
- [ ] Minimal 1 chart sudah muncul
- [ ] Search box responsif
- [ ] Bisa klik tab (Dashboard, Grafik, Penduduk, Laporan)

Jika semua ✓ = Dashboard siap digunakan! 🎉

---

**Version**: 1.0  
**Last Updated**: January 2026  
**Status**: User-Ready ✅

---

## 🎯 Next Steps

1. **Familiarize**: Jelajahi setiap tab
2. **Understand**: Baca penjelasan di halaman ini
3. **Practice**: Coba search, filter, klik chart
4. **Learn**: Baca dokumentasi lebih detail jika perlu
5. **Use**: Mulai gunakan untuk analisis data

Selamat menggunakan Dashboard Survey Kependudukan! 🎉
