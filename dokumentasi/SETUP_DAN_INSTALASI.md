# SETUP DAN INSTALASI

## 📌 Quick Setup (5 Menit)

### Prasyarat
- XAMPP/WAMP/LAMP server running
- MySQL service active
- PHP 7.2+

### Step-by-Step

**1. Database Setup**
```bash
# Via phpMyAdmin atau command line
mysql -u root < database/setup.sql
```

**2. Verify Installation**
```
http://localhost/survey-kependudukan/
```

**3. Generate Test Data (Optional)**
```
http://localhost/survey-kependudukan/generate_data.php
```

✅ Done! Dashboard ready to use.

---

## Instalasi Detail

### Windows (XAMPP)

#### 1. Download & Setup XAMPP
```
https://www.apachefriends.org/download.html
```
- Download XAMPP with PHP 7.4+
- Install di `C:\xampp\`
- Start Apache & MySQL services

#### 2. Copy Project
```bash
Copy folder survey-kependudukan ke:
C:\xampp\htdocs\
```

#### 3. Database Configuration

**Option A: Command Line**
```bash
cd C:\xampp\mysql\bin
mysql -u root < C:\xampp\htdocs\survey-kependudukan\database\setup.sql
```

**Option B: phpMyAdmin**
1. Buka `http://localhost/phpmyadmin/`
2. Login (default: user=root, password=empty)
3. Create database `survey_kependudukan`
4. Import `database/setup.sql`:
   - Click import tab
   - Choose file: `database/setup.sql`
   - Click "Go"

#### 4. Verify Database Connection
```bash
# Edit includes/config.php jika perlu
$host = 'localhost';
$username = 'root';
$password = '';  # Ubah jika ada password
$dbname = 'survey_kependudukan';
```

#### 5. Access Dashboard
```
http://localhost/survey-kependudukan/
```

---

### Linux (Ubuntu/Debian)

#### 1. Install LAMP Stack
```bash
sudo apt update
sudo apt install apache2 mysql-server php php-mysqli php-json
sudo systemctl start apache2
sudo systemctl start mysql
```

#### 2. Setup Database
```bash
sudo mysql -u root -p < /var/www/html/survey-kependudukan/database/setup.sql
```

#### 3. Configure PHP
```bash
sudo nano /etc/php/7.4/apache2/php.ini
# Set: max_upload_size = 100M
# Set: post_max_size = 100M

sudo systemctl restart apache2
```

#### 4. Copy Project
```bash
cd /var/www/html
sudo git clone <repo-url> survey-kependudukan
# or
sudo cp -r survey-kependudukan /var/www/html/
sudo chown -R www-data:www-data survey-kependudukan
```

#### 5. Access Dashboard
```
http://localhost/survey-kependudukan/
```

---

### Mac (MAMP/LAMP)

#### 1. Install MAMP
```
https://www.mamp.info/
```
- Download & install
- Start Services

#### 2. Database Setup
```bash
# Connect ke MySQL
/Applications/MAMP/Library/bin/mysql -u root -p

# Import database
source /Applications/MAMP/htdocs/survey-kependudukan/database/setup.sql;
```

#### 3. Copy Project
```bash
cp -r survey-kependudukan /Applications/MAMP/htdocs/
```

#### 4. Access Dashboard
```
http://localhost:8888/survey-kependudukan/
```

---

## Database Selection Guide

### 📊 Recommended: survey_kependudukan.sql
```bash
# Basic setup dengan schema
mysql -u root < database/survey_kependudukan.sql
```

**Contains:**
- ✅ All 3 tables (keluarga, penduduk, verifikasi)
- ✅ Table structure & indexes
- ✅ 5 sample keluarga records
- ❌ Minimal data (5 families only)

**Size:** ~50 KB

### 📊 Advanced: survey_kependudukan_full.sql
```bash
# Full setup dengan banyak data
mysql -u root < database/survey_kependudukan_full.sql
```

**Contains:**
- ✅ All 3 tables
- ✅ Comprehensive data
- ✅ Multiple kecamatan
- ✅ Realistic distribution
- ⚠️ Lebih kompleks

**Size:** ~5 MB

### ✅ Recommended Workflow
```bash
# 1. Gunakan setup.sql untuk development
mysql -u root < database/setup.sql

# 2. Generate data dengan generate_data.php
http://localhost/survey-kependudukan/generate_data.php

# 3. Hasilnya: 55 keluarga + 111 penduduk (siap untuk testing)
```

---

## Data Population

### Option 1: Via Browser (Recommended)
```
1. Buka http://localhost/survey-kependudukan/generate_data.php
2. Klik tombol Generate (jika ada)
3. Tunggu sampai selesai
4. Back to dashboard: http://localhost/survey-kependudukan/
```

**Output:**
```
✅ Inserted 50 keluarga records
✅ Database now populated with 55 keluarga
✅ Total penduduk: 111
```

### Option 2: Via MySQL
```sql
-- Download dan run generate_data.php output
-- Atau manual insert:

INSERT INTO keluarga (no_kartu_keluarga, kepala_keluarga, alamat, kelurahan, kecamatan, status_verifikasi)
VALUES 
  ('1175011201345001', 'SUMAIDI', 'Jl Gajah Mada No 123', 'Petisah', 'MEDAN BARU', 'terverifikasi'),
  ('1175011201345002', 'SITI NUR AZIZAH', 'Jl Pendidikan No 456', 'Sei Sikambing', 'MEDAN JOHOR', 'pending'),
  -- ... more records
```

---

## Configuration

### Database Credentials (includes/config.php)
```php
<?php
// Database Configuration
$host = 'localhost';           // Server
$username = 'root';            // Username
$password = '';                // Password (empty by default)
$dbname = 'survey_kependudukan'; // Database name

// Connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Set charset
$conn->set_charset('utf8mb4');
?>
```

### Modify if Needed:
```php
// If MySQL has password:
$password = 'your_password_here';

// If using different host:
$host = '192.168.1.100';

// If using different database name:
$dbname = 'survey_kependudukan_v2';
```

---

## Verification Checklist

### ✅ Database
- [ ] Database `survey_kependudukan` exists
- [ ] 3 tables exist: keluarga, penduduk, verifikasi
- [ ] Tables have data (keluarga: 55 records minimum)

### ✅ PHP Connection
- [ ] `includes/config.php` configured correctly
- [ ] No connection errors in logs

### ✅ Dashboard
- [ ] Homepage loads without errors
- [ ] Statistics display correctly
- [ ] Charts render with data
- [ ] No console errors (F12)

### ✅ API Endpoints
Test di browser:
```
http://localhost/survey-kependudukan/api/data.php?action=get_stats
```

Should return:
```json
{
  "success": true,
  "data": { ... }
}
```

---

## Troubleshooting Setup

### Error: "Connection failed"
```
Solusi:
1. Cek MySQL running (Services atau terminal)
2. Cek username/password di config.php
3. Cek database exists: mysql -u root -e "SHOW DATABASES;"
```

### Error: "Table doesn't exist"
```
Solusi:
1. Import setup.sql lagi: mysql -u root < database/setup.sql
2. Verify: mysql -u root survey_kependudukan -e "SHOW TABLES;"
```

### No Data Showing
```
Solusi:
1. Run: http://localhost/survey-kependudukan/generate_data.php
2. Or manual insert test data
3. Verify: mysql -u root survey_kependudukan -e "SELECT COUNT(*) FROM keluarga;"
```

### Chart API Returns Error
```
Debug steps:
1. Check browser Network tab (F12)
2. See actual API response
3. Check PHP error logs
4. Verify database has data
```

---

## First Time Verification

### 1. Dashboard Statistics
- Total KK: >= 5
- Total Penduduk: >= 10
- Verifikasi counts: >= 0

### 2. Charts Display
- Kecamatan bar chart: Shows bars
- Agama doughnut chart: Shows segments
- All charts have smooth animations

### 3. Data Table
- Shows 20 latest families
- Search box works
- Pagination working

### 4. Grafik Tab
- 7 charts render
- All with data
- Line charts smooth
- No console errors

---

## Database Backup

### Create Backup
```bash
# Full backup
mysqldump -u root survey_kependudukan > backup.sql

# With password
mysqldump -u root -p survey_kependudukan > backup.sql
```

### Restore Backup
```bash
# From backup file
mysql -u root survey_kependudukan < backup.sql
```

---

## Update & Maintenance

### Check for Updates
```bash
# Git (if using git)
git pull origin main

# Manual: Download latest files
```

### Database Optimization
```sql
-- Optimize all tables
OPTIMIZE TABLE keluarga, penduduk, verifikasi;

-- Check table status
CHECK TABLE keluarga, penduduk, verifikasi;

-- Repair if needed
REPAIR TABLE keluarga, penduduk, verifikasi;
```

### Clear Cache (If Implemented)
```bash
# Remove cached API responses
rm -rf cache/*

# Clear browser cache: Ctrl+Shift+Delete
```

---

## Performance Tuning

### MySQL Optimization
```sql
-- Create indexes for faster queries
ALTER TABLE keluarga ADD INDEX idx_kecamatan (kecamatan);
ALTER TABLE keluarga ADD INDEX idx_status (status_verifikasi);
ALTER TABLE penduduk ADD INDEX idx_keluarga (id_keluarga);
ALTER TABLE penduduk ADD INDEX idx_agama (agama);
```

### PHP Optimization
```php
// In config.php or top of files:
// Enable output buffering
ob_start();

// Set cache headers
header('Cache-Control: max-age=3600');
header('Expires: ' . gmdate('r', strtotime('+1 hour')));
```

### Browser Cache
```javascript
// Enable caching in script.js
const API_CACHE = {};
const CACHE_TTL = 5 * 60 * 1000; // 5 minutes

function getCachedAPI(url) {
    if (API_CACHE[url] && Date.now() - API_CACHE[url].time < CACHE_TTL) {
        return API_CACHE[url].data;
    }
    return null;
}
```

---

## Deployment Checklist

- [ ] Database backup created
- [ ] config.php credentials verified
- [ ] Error reporting configured (production: E_ALL to file only)
- [ ] SSL certificate installed (if HTTPS)
- [ ] Database indexes created
- [ ] API response times acceptable
- [ ] All charts rendering correctly
- [ ] Mobile responsiveness tested
- [ ] Browser compatibility tested (Chrome, Firefox, Safari, Edge)
- [ ] Backup scheduled (daily/weekly)

---

**Version**: 1.0  
**Last Updated**: January 2026
