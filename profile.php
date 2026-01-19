<?php
require 'auth/check_session.php';
$admin = $_SESSION['admin'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna - Survey Kependudukan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .profile-header {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .profile-avatar-large {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
        }

        .profile-info h2 {
            margin: 0 0 0.5rem 0;
            color: var(--dark-text);
        }

        .profile-info p {
            margin: 0;
            color: #64748b;
        }

        /* Tabs */
        .tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .tab-btn {
            background: none;
            border: none;
            padding: 1rem 1.5rem;
            font-size: 1rem;
            color: #64748b;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.3s;
            font-weight: 500;
        }

        .tab-btn.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
        }

        .tab-btn:hover {
            color: var(--primary-color);
        }

        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .tab-content.active {
            display: block;
        }

        .bio-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .btn-danger {
            background-color: #ef4444;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            transition: background 0.3s;
        }

        .btn-danger:hover {
            background-color: #dc2626;
        }

        .btn-sm {
            padding: 4px 8px;
            font-size: 0.85rem;
        }
    </style>
</head>

<body>
    <div class="container-wrapper">
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-chart-bar"></i>
                    <span>Survey Kependudukan</span>
                </div>
                <p class="subtitle">Kota Medan</p>
            </div>

            <nav class="sidebar-nav">
                <a href="dashboard.php" class="nav-item">
                    <i class="fas fa-chart-pie"></i>
                    <span>Dashboard</span>
                </a>
                <!-- Link to profile is now via the user info footer -->
            </nav>

            <div class="sidebar-footer">
                <!-- User Info is now clickable -->
                <a href="profile.php" style="text-decoration: none; color: inherit; display: block;">
                    <div class="user-info"
                        style="cursor: pointer; background: rgba(255,255,255,0.1); border-radius: 8px; padding: 8px;">
                        <div class="user-avatar"><i class="fas fa-user-circle"></i></div>
                        <div class="user-details">
                            <p class="user-name">
                                <?php echo htmlspecialchars($admin['name']); ?>
                            </p>
                            <p class="user-role">
                                <?php echo htmlspecialchars($admin['role']); ?>
                            </p>
                        </div>
                    </div>
                </a>
                <div style="margin-top:12px;">
                    <a href="auth/logout.php" class="btn"
                        style="background:#fff;color:#333;border:1px solid #e9ecef;display:inline-flex;align-items:center;gap:8px;padding:8px 12px;border-radius:6px;text-decoration:none">
                        <i class="fas fa-sign-out-alt"></i> Keluar
                    </a>
                </div>
            </div>
        </aside>

        <main class="main-content">
            <header class="top-header">
                <div class="header-left">
                    <h1 id="pageTitle">Profil Pengguna</h1>
                </div>
            </header>

            <div class="content-area">

                <div class="profile-header">
                    <div class="profile-avatar-large">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="profile-info">
                        <h2>
                            <?php echo htmlspecialchars($admin['name']); ?>
                        </h2>
                        <p>
                            <?php echo htmlspecialchars($admin['role']); ?> |
                            <?php echo htmlspecialchars($admin['email']); ?>
                        </p>
                    </div>
                </div>

                <div class="tabs">
                    <button class="tab-btn active" onclick="switchTab('biodata')">Biodata</button>
                    <button class="tab-btn" onclick="switchTab('logs')">Log Aktivitas</button>
                </div>

                <!-- Tab 1: Biodata -->
                <div id="biodata" class="tab-content active">
                    <div class="card">
                        <div class="card-header">
                            <h3><i class="fas fa-id-card"></i> Informasi Akun</h3>
                        </div>
                        <div class="card-body">
                            <div class="bio-grid">
                                <div class="info-item">
                                    <i class="fas fa-user"></i>
                                    <div>
                                        <p class="label">Nama Lengkap</p>
                                        <p class="value">
                                            <?php echo htmlspecialchars($admin['name']); ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-envelope"></i>
                                    <div>
                                        <p class="label">Email</p>
                                        <p class="value">
                                            <?php echo htmlspecialchars($admin['email']); ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-briefcase"></i>
                                    <div>
                                        <p class="label">Role / Jabatan</p>
                                        <p class="value">
                                            <?php echo htmlspecialchars($admin['role']); ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-clock"></i>
                                    <div>
                                        <p class="label">Login Terakhir (Sesi Ini)</p>
                                        <p class="value">
                                            <?php echo date('d M Y, H:i', $admin['login_time']); ?> WIB
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Logs -->
                <div id="logs" class="tab-content">
                    <div class="card">
                        <div class="card-header">
                            <h3><i class="fas fa-history"></i> Riwayat Login</h3>
                            <button onclick="deleteAllLogs()" class="btn-danger">
                                <i class="fas fa-trash-alt"></i> Hapus Semua Log
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Waktu Login (WIB)</th>
                                            <th>IP Address</th>
                                            <th>Browser / UA</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="auditTableBody">
                                        <tr>
                                            <td colspan="5" class="text-center">Loading...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script>
        // Tab function
        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));

            document.getElementById(tabId).classList.add('active');
            // Find the button that calls this function with this tabId? Or just use event target if passed. 
            // Simpler: match by text content or index. 
            // Let's iterate buttons to check text or onclick attr.
            // A bit hacky but works for 2 tabs:
            const btns = document.querySelectorAll('.tab-btn');
            if (tabId === 'biodata') btns[0].classList.add('active');
            else btns[1].classList.add('active');

            if (tabId === 'logs') {
                loadLogs();
            }
        }

        // Logs Logic
        async function loadLogs() {
            try {
                const res = await fetch('api/audit_log.php');
                const json = await res.json();
                if (json.success) {
                    const tbody = document.getElementById('auditTableBody');
                    tbody.innerHTML = '';
                    if (json.data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="5" class="text-center">Tidak ada data log.</td></tr>';
                        return;
                    }

                    json.data.forEach(log => {
                        const row = `<tr>
                            <td>${log.id}</td>
                            <td>${log.login_time}</td>
                            <td>${log.ip_address}</td>
                            <td title="${log.user_agent}">${log.user_agent.substring(0, 30)}...</td>
                            <td>
                                <button onclick="deleteLog(${log.id})" class="btn-danger btn-sm" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>`;
                        tbody.innerHTML += row;
                    });
                }
            } catch (error) {
                console.error('Error loading logs:', error);
            }
        }

        async function deleteLog(id) {
            if (!confirm('Hapus log ini?')) return;
            try {
                const res = await fetch('api/audit_log.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'delete_single', id })
                });
                const result = await res.json();
                if (result.success) loadLogs();
                else alert('Gagal menghapus log');
            } catch (e) { alert('Error'); }
        }

        async function deleteAllLogs() {
            if (!confirm('YAKIN HAPUS SEMUA LOG? Tindakan ini tidak bisa dibatalkan.')) return;
            try {
                const res = await fetch('api/audit_log.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'delete_all' })
                });
                const result = await res.json();
                if (result.success) loadLogs();
                else alert('Gagal menghapus semua log');
            } catch (e) { alert('Error'); }
        }
    </script>
</body>

</html>