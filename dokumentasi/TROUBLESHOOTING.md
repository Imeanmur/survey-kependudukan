# TROUBLESHOOTING GUIDE

## 🆘 Common Issues & Solutions

---

## 1. Dashboard Issues

### ❌ Chart Not Displaying

**Symptoms**: 
- Chart containers show empty
- No error message visible

**Debug Steps**:
1. **Open Browser Console** (F12 → Console tab)
2. **Look for errors** - Usually one of these:
   - `Chart is not defined` 
   - `Cannot read property 'getContext'`
   - `Uncaught SyntaxError`

**Solutions**:

**If: "Chart is not defined"**
```javascript
// Problem: Chart.js library not loaded

// Verify in Network tab (F12 → Network)
// Check if Chart.js loaded:
// https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js

// Solution:
// 1. Hard refresh: Ctrl+Shift+Delete (clear cache)
// 2. Check internet connection
// 3. Verify script tag in index.html line 452:
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js" defer></script>
```

**If: "Cannot read property 'getContext'**
```javascript
// Problem: Canvas element doesn't exist or wrong ID

// Check in Browser Console:
document.getElementById('chartKecamatan')  // Should return <canvas> element
document.getElementById('chartAgama')      // Should return <canvas> element

// If returns null = Canvas element missing from HTML
// Solution: Verify index.html has canvas elements (search for id="chart")
```

**If: "Uncaught SyntaxError"**
```javascript
// Problem: API returning HTML (server error) instead of JSON

// Check Network tab → XHR/Fetch requests
// Look at Response tab for this request:
// /api/data.php?action=get_data_by_kecamatan

// If shows HTML with error: PHP syntax or database error
// Solution: Check browser console for exact error message
```

---

### ❌ Data Not Loading

**Symptoms**:
- Statistics show 0
- Data table empty
- Chart shows "no data"

**Debug Steps**:
```javascript
// Open console (F12) and test API:

// Test 1: Get statistics
fetch('./api/data.php?action=get_stats')
  .then(r => r.json())
  .then(d => console.log('Stats:', d));

// Test 2: Get data terbaru
fetch('./api/data.php?action=get_data_terbaru&limit=20')
  .then(r => r.json())
  .then(d => console.log('Data:', d.data.length + ' records'));
```

**Solutions**:

**If API returns `success: false`**:
```
Check message field - usually indicates:
- Database connection failed
- Table not found
- No data in database
```

**If: Database Connection Error**
```bash
# 1. Check MySQL is running
# Windows: Start MySQL service from Services
# Linux: sudo service mysql start
# Mac: sudo /usr/local/mysql/support-files/mysql.server start

# 2. Verify config.php credentials
# Edit: includes/config.php
$host = 'localhost';
$username = 'root';
$password = '';  # Check if correct

# 3. Test connection manually
mysql -u root -p survey_kependudukan
```

**If: Table Not Found**
```bash
# Problem: Database exists but tables missing

# Solution 1: Import setup.sql
mysql -u root survey_kependudukan < database/setup.sql

# Solution 2: Verify tables exist
mysql -u root survey_kependudukan -e "SHOW TABLES;"
# Should show: keluarga, penduduk, verifikasi

# If still empty, check file permissions
ls -la database/setup.sql  # Must be readable
```

**If: No Data (Empty Tables)**
```bash
# Problem: Tables exist but no records

# Solution: Generate test data
# Method 1: Via browser
http://localhost/survey-kependudukan/generate_data.php

# Method 2: Via MySQL
mysql -u root survey_kependudukan < database/survey_kependudukan.sql

# Verify:
mysql -u root survey_kependudukan -e "SELECT COUNT(*) FROM keluarga;"
# Should show: 55 (or more)
```

---

### ❌ Chart Animations Not Working

**Symptoms**:
- Charts appear instantly (no slide-in animation)
- Points don't animate on load

**Debug Steps**:
```javascript
// Check CSS loaded
document.querySelector('.chart-container').classList
// Should include animation classes

// Check chart animation config
// In browser console, inspect chart object:
// (Charts stored in window scope if visible)
```

**Solutions**:

**If: CSS animations not working**
```bash
# 1. Check CSS file is loaded (Network tab in F12)
# Should see: assets/css/style.css loaded with 200 status

# 2. Clear browser cache
Ctrl+Shift+Delete → All time → Clear

# 3. Verify keyframes in style.css
# Search for: @keyframes chartSlideIn
# Should exist starting at line ~250

# 4. Check CSS syntax
# Use browser DevTools → Elements tab
# Right-click chart-container → Inspect
# Check "Computed" tab for animation properties
```

**If: Chart.js animation not working**
```javascript
// Check chart animation config in script.js
// Line ~440 in createChartKecamatan():

options: {
  animation: {
    duration: 1000,  // ← Should be > 0
    easing: 'easeInOutQuart'
  }
}

// If duration: 0 = no animation
// Fix: Change to duration: 1000
```

---

## 2. Database Issues

### ❌ Cannot Connect to Database

**Error Message**:
```
"Connection failed: (HY000/2003): Can't connect to MySQL server on 'localhost'"
```

**Solutions**:

**Step 1: Check MySQL Service**
```bash
# Windows
# Open Task Manager → Services tab
# Look for: MySQL (or MySQLd)
# Status should be: Running

# If not running:
# Services → MySQL → Right-click → Start

# Or via command line:
net start MySQL

# Linux
sudo systemctl status mysql
# If inactive, start it:
sudo systemctl start mysql

# Mac
sudo /usr/local/mysql/support-files/mysql.server status
# Start if needed:
sudo /usr/local/mysql/support-files/mysql.server start
```

**Step 2: Verify Connection Details**
```bash
# Try connecting directly
mysql -u root
# If fails, try with password
mysql -u root -p

# If still fails:
# Check if MySQL port is accessible
netstat -an | grep 3306  # Windows/Mac
sudo netstat -an | grep 3306  # Linux
```

**Step 3: Update config.php**
```php
// includes/config.php
$host = 'localhost';      // Try 127.0.0.1 if localhost fails
$username = 'root';
$password = '';           // Add password if set
$dbname = 'survey_kependudukan';

// Alternative configurations:
// If using socket:
$host = 'localhost:/var/run/mysqld/mysqld.sock';

// If using different port:
$host = 'localhost:3307';
```

---

### ❌ Table Doesn't Exist

**Error Message**:
```
"Table 'survey_kependudukan.keluarga' doesn't exist"
```

**Solutions**:

**Verify Database & Tables**
```bash
# 1. Check database exists
mysql -u root -e "SHOW DATABASES;" | grep survey

# 2. Check tables exist
mysql -u root survey_kependudukan -e "SHOW TABLES;"

# Expected output:
# keluarga
# penduduk  
# verifikasi
```

**If Tables Missing**:
```bash
# Import schema
mysql -u root survey_kependudukan < database/setup.sql

# Or use phpMyAdmin:
# 1. Open http://localhost/phpmyadmin/
# 2. Select database "survey_kependudukan"
# 3. Click "Import" tab
# 4. Choose database/setup.sql
# 5. Click "Go"
```

**If Database Missing**:
```bash
# Create database first
mysql -u root -e "CREATE DATABASE survey_kependudukan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Then import schema
mysql -u root survey_kependudukan < database/setup.sql
```

---

### ❌ No Data in Tables

**Symptoms**:
- Tables exist but COUNT(*) shows 0
- Statistics page shows all zeros

**Solutions**:

**Method 1: Import SQL file with data**
```bash
# Use survey_kependudukan.sql (has sample data)
mysql -u root survey_kependudukan < database/survey_kependudukan.sql
```

**Method 2: Generate data via PHP**
```
# Open in browser:
http://localhost/survey-kependudukan/generate_data.php

# Should output:
# ✅ Inserted 50 keluarga records
# ✅ Database now populated with 55 keluarga
# ✅ Total penduduk: 111
```

**Method 3: Manual insert**
```bash
# Connect to MySQL
mysql -u root survey_kependudukan

# Insert sample family
mysql> INSERT INTO keluarga (no_kartu_keluarga, kepala_keluarga, alamat, kelurahan, kecamatan, status_verifikasi) 
VALUES ('1175011201345001', 'SUMAIDI', 'Jl Test', 'Kelurahan', 'MEDAN BARU', 'terverifikasi');

# Insert sample family member
mysql> INSERT INTO penduduk (id_keluarga, nik, nama_lengkap, jenis_kelamin, agama, status_perkawinan, pekerjaan, hubungan_keluarga)
VALUES (1, '1175011201340001', 'SUMAIDI', 'Laki-laki', 'Islam', 'Kawin', 'Pedagang', 'Kepala Keluarga');
```

**Verify Data**:
```bash
# Check keluarga table
mysql -u root survey_kependudukan -e "SELECT COUNT(*) FROM keluarga;"

# Check specific data
mysql -u root survey_kependudukan -e "SELECT * FROM keluarga LIMIT 5;"
```

---

## 3. API Issues

### ❌ API Returns Error

**Debug**:
```javascript
// Open browser console
fetch('./api/data.php?action=get_data_by_kecamatan')
  .then(r => r.json())
  .then(d => {
    console.log('Success:', d.success);
    console.log('Message:', d.message);
    console.log('Full response:', d);
  });
```

**Common Errors**:

| Error | Cause | Fix |
|-------|-------|-----|
| "Database connection failed" | MySQL not running or wrong credentials | Check config.php, start MySQL |
| "Table not found" | Schema not imported | Import database/setup.sql |
| "No data found" | Tables are empty | Run generate_data.php |
| "Invalid action" | Wrong ?action parameter | Check parameter spelling |
| "null" returned | Malformed JSON | Check PHP errors in error log |

**Check PHP Error Log**:
```bash
# XAMPP
tail -f C:\xampp\php\logs\error.log
# or on Windows
type C:\xampp\php\logs\error.log

# Linux
tail -f /var/log/php-fpm.log

# Mac
tail -f /var/log/apache2/error_log
```

---

### ❌ API Slow or Timeout

**Symptoms**:
- Charts take >3 seconds to load
- Sometimes "Network error" appears

**Solutions**:

**Check Query Performance**:
```bash
# Enable MySQL slow query log
mysql -u root -e "SET GLOBAL slow_query_log = 'ON';"
mysql -u root -e "SET GLOBAL long_query_time = 2;"

# Check log
mysql -u root -e "SELECT * FROM mysql.slow_log;"
```

**Add Indexes**:
```sql
-- Already should exist, but verify
ALTER TABLE keluarga ADD INDEX idx_kecamatan (kecamatan);
ALTER TABLE keluarga ADD INDEX idx_status (status_verifikasi);
ALTER TABLE penduduk ADD INDEX idx_keluarga (id_keluarga);
ALTER TABLE penduduk ADD INDEX idx_agama (agama);
```

**Cache API Responses**:
```javascript
// In script.js, add caching:
const apiCache = {};
const CACHE_TTL = 5 * 60 * 1000; // 5 minutes

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

## 4. File & Directory Issues

### ❌ "Cannot find file" Error

**Symptoms**:
- 404 errors in Network tab
- "File not found" messages

**Debug**:
```bash
# Check if files exist
ls -la C:\xampp\htdocs\survey-kependudukan\

# Key files should be:
# index.html
# api/data.php
# includes/config.php
# assets/css/style.css
# assets/js/script.js
```

**Solutions**:
```bash
# If missing, copy from repository
git clone <repo> survey-kependudukan

# Or manually copy required files:
# 1. index.html → root
# 2. api/data.php → api/ folder
# 3. includes/config.php → includes/ folder
# 4. assets/* → assets/ folder
# 5. database/*.sql → database/ folder
```

---

### ❌ Permission Denied Errors

**Linux/Mac Only**:
```bash
# Check permissions
ls -la /var/www/html/survey-kependudukan/

# Fix permissions
sudo chown -R www-data:www-data /var/www/html/survey-kependudukan/
sudo chmod -R 755 /var/www/html/survey-kependudukan/

# For writable directories:
sudo chmod -R 775 /var/www/html/survey-kependudukan/cache
sudo chmod -R 775 /var/www/html/survey-kependudukan/uploads
```

---

## 5. Browser Issues

### ❌ Responsive Design Broken

**Symptoms**:
- Layout doesn't adapt to screen size
- Overlapping elements on mobile

**Solutions**:
```bash
# 1. Hard refresh browser
Ctrl+Shift+Delete (Clear cache)
Ctrl+Shift+R (Hard refresh)

# 2. Check viewport meta tag in index.html
# Should be in <head>:
<meta name="viewport" content="width=device-width, initial-scale=1.0">

# 3. Test in DevTools
F12 → Toggle device toolbar (Ctrl+Shift+M)
# Try different screen sizes

# 4. Check CSS media queries
# Search in style.css for: @media (max-width:
```

---

### ❌ Fonts/Icons Not Showing

**Symptoms**:
- FontAwesome icons show as squares
- Custom fonts not loading

**Solutions**:
```bash
# Check Network tab (F12)
# Look for FontAwesome CDN request
# https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css

# If fails:
# 1. Check internet connection
# 2. Try different CDN:
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">

# 3. Or download and host locally
# Download from: https://fontawesome.com/download
# Extract to: assets/fonts/fontawesome/
```

---

## 6. Performance Issues

### 🐢 Dashboard Loading Slow

**Diagnosis**:
```javascript
// Measure load time
const start = performance.now();
// ... your code ...
const end = performance.now();
console.log('Time:', end - start, 'ms');
```

**Optimization**:
```javascript
// 1. Load charts asynchronously
async function loadCharts() {
  await loadChart1();
  await loadChart2();
  // Instead of:
  // loadChart1();
  // loadChart2();
}

// 2. Cache API responses
// 3. Lazy load off-screen content
// 4. Use requestAnimationFrame for smooth rendering
```

---

## 7. Data Validation Issues

### ❌ Invalid Data Entered

**Example**: Non-numeric values in numeric fields

**Solutions**:

**Add Client-Side Validation**:
```javascript
// Before sending to API
function validateData(data) {
  if (!data.no_kartu_keluarga || !/^\d{16}$/.test(data.no_kartu_keluarga)) {
    alert('KK number must be 16 digits');
    return false;
  }
  if (!data.nik || !/^\d{16}$/.test(data.nik)) {
    alert('NIK must be 16 digits');
    return false;
  }
  return true;
}
```

**Add Server-Side Validation** (in api/data.php):
```php
if (!preg_match('/^\d{16}$/', $_POST['no_kartu_keluarga'])) {
    die(json_encode(['success' => false, 'message' => 'Invalid KK format']));
}
```

---

## Quick Reference: Common Commands

### MySQL Commands
```bash
# Connect
mysql -u root -p survey_kependudukan

# Check database size
SELECT table_name, ROUND(((data_length + index_length) / 1024 / 1024), 2) as size_mb 
FROM information_schema.tables 
WHERE table_schema = 'survey_kependudukan';

# Backup
mysqldump -u root survey_kependudukan > backup.sql

# Restore
mysql -u root survey_kependudukan < backup.sql

# Count records
SELECT COUNT(*) FROM keluarga;
SELECT COUNT(*) FROM penduduk;
```

### Useful Keyboard Shortcuts
```
F12                    - Open DevTools
Ctrl+Shift+Delete      - Clear browser cache
Ctrl+Shift+R           - Hard refresh page
Ctrl+Shift+M           - Toggle responsive mode
Ctrl+Shift+I           - Inspect element
```

---

## When Nothing Works

**Nuclear Option** (Reset Everything):
```bash
# 1. Delete database
mysql -u root -e "DROP DATABASE survey_kependudukan;"

# 2. Create fresh database
mysql -u root -e "CREATE DATABASE survey_kependudukan CHARACTER SET utf8mb4;"

# 3. Import schema
mysql -u root survey_kependudukan < database/setup.sql

# 4. Generate data
# Via browser: http://localhost/survey-kependudukan/generate_data.php

# 5. Clear browser cache
# Ctrl+Shift+Delete → All time → Clear

# 6. Restart services
# MySQL: Stop → Start
# Apache: Restart

# 7. Reload dashboard
# http://localhost/survey-kependudukan/
```

---

## Getting Help

**Before asking for help, provide:**
1. Screenshot of error message
2. Browser console errors (F12 → Console)
3. Network tab errors (F12 → Network)
4. Steps to reproduce the issue
5. Output of:
   ```bash
   mysql -u root -e "SHOW DATABASES;" | grep survey
   mysql -u root survey_kependudukan -e "SELECT COUNT(*) FROM keluarga;"
   ```

---

**Version**: 1.0  
**Last Updated**: January 2026
