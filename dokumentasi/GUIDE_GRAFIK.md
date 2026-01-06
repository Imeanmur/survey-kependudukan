# 📊 GUIDE GRAFIK & ANALISIS DASHBOARD

## 🎯 Pengenalan Tab Grafik & Analisis

Tab "Grafik & Analisis" menampilkan 7 visualisasi data interaktif menggunakan Chart.js library. Setiap grafik dirancang untuk memberikan insights yang berbeda dari data kependudukan.

## 📈 Daftar Grafik

### 1️⃣ TREND INPUT DATA PER BULAN 📉

**Tipe Chart**: Line Chart (Grafik Garis)

**Apa yang ditampilkan:**
- 2 garis dengan warna berbeda
- Garis Biru: Total data input per bulan
- Garis Hijau: Data yang sudah terverifikasi per bulan

**Data Source:**
```sql
GROUP BY: DATE_FORMAT(tanggal_input, '%Y-%m')
Data: COUNT(*), COUNT(status='terverifikasi')
```

**Cara Membaca:**
- **Puncak garis biru** = Bulan dengan input data terbanyak
- **Puncak garis hijau** = Bulan dengan verifikasi terbanyak
- **Jarak antar garis** = Backlog data (belum terverifikasi)
- **Garis menurun** = Pengurangan input data

**Kegunaan Analisis:**
✓ Monitor trend input data
✓ Identifikasi bulan sibuk
✓ Cek progress verifikasi
✓ Deteksi anomali/pattern

**Tips Interpretasi:**
- Jika garis hijau jauh di bawah biru → Banyak backlog verifikasi
- Jika garis menurun drastis → Perlu investigasi input data
- Pola musiman → Identifikasi peak seasons

---

### 2️⃣ PERBANDINGAN UMUR & GENDER 👥

**Tipe Chart**: Line Chart (Grafik Garis)

**Apa yang ditampilkan:**
- 2 garis perbandingan: Laki-laki (Biru) vs Perempuan (Pink)
- 7 kelompok umur: 0-5, 6-11, 12-17, 18-29, 30-44, 45-59, 60+ tahun
- Point markers pada setiap data point

**Data Source:**
```sql
GROUP BY: YEAR(CURDATE()) - YEAR(tanggal_lahir), jenis_kelamin
Data: COUNT(*) per kelompok umur & gender
```

**Cara Membaca:**
```
Garis Laki-laki (Biru) = Distribusi laki-laki per umur
Garis Perempuan (Pink) = Distribusi perempuan per umur

Contoh:
- Jika biru > pink di umur 18-29: Lebih banyak laki-laki muda
- Jika pink > biru di umur 45-59: Lebih banyak perempuan lansia
```

**Kegunaan Analisis:**
✓ Analisis struktur demografis
✓ Identifikasi gender imbalance
✓ Perencanaan program sosial
✓ Studi keluarga

**Tips Interpretasi:**
- Pola piramida ideal: Banyak muda, sedikit tua
- Pola botol: Populasi menua
- Pola ekspansi: Populasi muda dominan
- Jika ada gender imbalance → Investigasi penyebab

---

### 3️⃣ DISTRIBUSI AGAMA 🕌

**Tipe Chart**: Pie Chart (Grafik Lingkaran)

**Apa yang ditampilkan:**
- Proporsi penganut setiap agama
- Warna berbeda untuk setiap agama
- Persentase ditampilkan

**Data Source:**
```sql
GROUP BY: agama
Data: COUNT(*) per agama
```

**Agama yang biasanya ada:**
- Islam
- Kristen Protestan
- Katolik
- Hindu
- Buddha
- Konghucu

**Cara Membaca:**
```
Ukuran slice = Proporsi penganut
Warna = Identifikasi agama
Contoh: Slice besar = Agama dengan penganut paling banyak
```

**Kegunaan Analisis:**
✓ Analisis keragaman agama
✓ Perencanaan tempat ibadah
✓ Program keagamaan
✓ Data kependudukan

---

### 4️⃣ STATUS VERIFIKASI KELUARGA ✅

**Tipe Chart**: Bar Chart (Grafik Batang)

**Apa yang ditampilkan:**
- 4 batang untuk 4 status verifikasi:
  - Terverifikasi (Hijau)
  - Pending (Biru)
  - Ditolak (Merah)
  - Revisi (Orange)

**Data Source:**
```sql
GROUP BY: status_verifikasi
Data: COUNT(*) per status
```

**Cara Membaca:**
```
Tinggi batang = Jumlah keluarga per status
Warna batang = Membedakan status

Contoh:
- Batang hijau tinggi = Banyak yang terverifikasi ✓
- Batang merah tinggi = Banyak yang ditolak (perlu review)
- Batang oranye tinggi = Banyak yang revisi (belum selesai)
- Batang biru tinggi = Backlog pending (menunggu verifikasi)
```

**Kegunaan Analisis:**
✓ Monitor progress verifikasi
✓ Identifikasi bottleneck
✓ Perencanaan resource verifikasi
✓ Quality control

**Tips Interpretasi:**
- **Ideal**: Terverifikasi >> Pending > Revisi ≥ Ditolak
- **Concern**: Pending terlalu banyak → Perlu tambah verifikator
- **Issue**: Ditolak banyak → Perlu training enumerator

---

### 5️⃣ PENDIDIKAN TERAKHIR PENDUDUK 🎓

**Tipe Chart**: Horizontal Bar Chart (Grafik Batang Horizontal)

**Apa yang ditampilkan:**
- Distribusi tingkat pendidikan penduduk
- Batang horizontal dengan warna berbeda
- Jumlah penduduk per tingkat pendidikan

**Tingkat Pendidikan Tipikal:**
```
- Tidak Sekolah
- SD/Sederajat
- SMP/Sederajat
- SMA/Sederajat
- Diploma
- S1/Sederajat
- S2/S3
```

**Data Source:**
```sql
GROUP BY: pendidikan_terakhir
Data: COUNT(*) per tingkat pendidikan
```

**Cara Membaca:**
```
Panjang batang = Jumlah penduduk per tingkat pendidikan
Batang paling panjang = Pendidikan paling umum

Contoh interpretasi:
- Batang panjang di SMA → Mayoritas tamat SMA
- Batang pendek di S1 → Sedikit yang lanjut S1
- Batang panjang di "Tidak Sekolah" → Ada masalah pendidikan
```

**Kegunaan Analisis:**
✓ Analisis level pendidikan populasi
✓ Perencanaan program pendidikan
✓ Identifikasi needs capacity building
✓ Studi sosial ekonomi

**Tips Interpretasi:**
- **Indikator positif**: Banyak SMA-S1
- **Indikator negatif**: Banyak "Tidak Sekolah"
- **Perbandingan**: Bandingkan dengan target nasional

---

### 6️⃣ TOP 10 PEKERJAAN PENDUDUK 💼

**Tipe Chart**: Horizontal Bar Chart

**Apa yang ditampilkan:**
- Top 10 jenis pekerjaan paling banyak
- Jumlah penduduk per pekerjaan
- Diurutkan dari terbanyak ke terakhir

**Data Source:**
```sql
GROUP BY: pekerjaan
Data: COUNT(*)
LIMIT: 10
ORDER BY: jumlah DESC
```

**Contoh Pekerjaan yang Umum:**
- Petani
- Buruh
- Pedagang
- Pegawai Negeri
- Pegawai Swasta
- Pengusaha
- Tukang
- Pensiunan
- dll

**Cara Membaca:**
```
Batang paling panjang = Pekerjaan paling dominan
Urutan = Ranking pekerjaan

Contoh:
Batang 1 (Petani) paling panjang = Mayoritas petani
Batang 10 (Tukang) paling pendek = Top 10 tapi paling sedikit
```

**Kegunaan Analisis:**
✓ Analisis struktur ekonomi
✓ Perencanaan program UMKM
✓ Identifikasi sektor dominan
✓ Kajian ketenagakerjaan

---

### 7️⃣ DATA PER KECAMATAN 🗺️

**Tipe Chart**: Line Chart

**Apa yang ditampilkan:**
- Perbandingan 2 metrik per kecamatan
- Garis Biru: Total Kartu Keluarga
- Garis Pink: Total Penduduk
- X-Axis: Semua kecamatan

**Data Source:**
```sql
GROUP BY: kecamatan
Data: 
  - COUNT(keluarga)
  - COUNT(penduduk)
```

**Cara Membaca:**
```
Point pada garis biru = Jumlah KK di kecamatan tersebut
Point pada garis pink = Jumlah penduduk di kecamatan tersebut

Contoh:
- Kecamatan A (biru=500, pink=2500) = 500 KK dengan 5 orang rata-rata
- Kecamatan B (biru=300, pink=2000) = 300 KK dengan 6-7 orang rata-rata
```

**Kegunaan Analisis:**
✓ Perbandingan antar kecamatan
✓ Identifikasi density populasi
✓ Perencanaan program per kecamatan
✓ Alokasi resource

---

## 🎮 Cara Interaksi dengan Grafik

### 1. Hover pada Grafik
```
Arahkan mouse ke data point:
- Akan muncul tooltip dengan nilai detail
- Latar belakang point akan highlight
```

### 2. Legend (Keterangan Grafik)
```
Di atas grafik ada legend dengan warna:
- Klik legend untuk hide/show dataset
- Berguna untuk fokus pada data tertentu
```

### 3. Responsive
```
Ukuran grafik otomatis menyesuaikan:
- Desktop: Grafik penuh + legend samping
- Tablet: Grafik panjang + legend bawah
- Mobile: Grafik penuh lebar + legend vertikal
```

### 4. Auto-Scale
```
Y-axis otomatis scale sesuai data:
- Jika data kecil → Scale 0-100
- Jika data besar → Scale 0-10000+
- Gridline membantu membaca nilai
```

## 📊 Perbandingan Grafik

| Grafik | Tipe | Fungsi | Best For |
|--------|------|--------|----------|
| Trend Input | Line | Melihat perubahan waktu | Analisis trend |
| Umur Gender | Line | Perbandingan kategori | Demografis |
| Agama | Pie | Proporsi total | Persentase |
| Verifikasi | Bar | Perbandingan kategori | Status |
| Pendidikan | Bar H | Ranking kategori | Distribusi |
| Pekerjaan | Bar H | Top items | Analisis sektor |
| Kecamatan | Line | Trend per group | Perbandingan regional |

## 💡 Tips Penggunaan

### Untuk Admin/Verifikator:
✓ Monitor Status Verifikasi secara berkala
✓ Identifikasi bottleneck di Trend Input
✓ Track progress verifikasi per bulan
✓ Plan resource berdasarkan backlog

### Untuk Policy Maker:
✓ Analisis Demografis (Umur-Gender)
✓ Analisis Sektor Pekerjaan
✓ Perbandingan antar Kecamatan
✓ Identifikasi populasi rawan (Pendidikan rendah)

### Untuk Statistician:
✓ Export data untuk analisis lebih lanjut
✓ Identifikasi outlier/anomali
✓ Correlate multiple variables
✓ Time series forecasting

## 🔍 Interpretasi Cepat

### Flag Merah (Action Needed):
🚩 Verifikasi: Banyak pending → Perlu verifikator tambahan
🚩 Trend: Garis tidak konsisten → Perlu investigasi
🚩 Pendidikan: Banyak tidak sekolah → Program intervensi
🚩 Kecamatan: Disparitas besar → Alokasi resource tidak rata

### Flag Hijau (Good):
✅ Verifikasi: Terverifikasi mendominasi → On track
✅ Trend: Garis meningkat konsisten → Baik
✅ Pendidikan: SMA-S1 mendominasi → Positif
✅ Kecamatan: Rata → Pendistribusian merata

## 📋 Checklist Analisis

Setiap membuka tab Grafik & Analisis:
- [ ] Cek Trend Input - ada anomali?
- [ ] Cek Verifikasi Status - backlog normal?
- [ ] Cek Demografis - ada gender imbalance?
- [ ] Cek Pendidikan - butuh program?
- [ ] Cek Kecamatan - ada disparitas?

## 🎓 Referensi Lebih Lanjut

- **Chart.js Documentation**: https://www.chartjs.org/docs/latest/
- **Data Visualization Best Practices**: https://www.tableau.com/about/blog/
- **Statistical Analysis**: https://www.coursera.org/

---

**Version**: 1.0  
**Last Updated**: 6 Januari 2026

Gunakan grafik ini untuk membuat keputusan yang lebih baik! 📊
