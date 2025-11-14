<?php
// admin.php - Admin Panel untuk Manage Messages
session_start();

// Konfigurasi Database
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "company_db";

// Koneksi Database
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Default admin credentials (dalam praktik nyata, simpan di database dengan password hash)
$admin_username = "admin";
$admin_password = "admin123"; // Ganti dengan password yang kuat!

// Handle Login
if (isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        header("Location: admin.php");
        exit;
    } else {
        $login_error = "Username atau password salah!";
    }
}

// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

// Cek jika sudah login
$is_logged_in = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

// Handle Delete Message
if ($is_logged_in && isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $sql = "DELETE FROM contacts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $success_msg = "Pesan berhasil dihapus!";
    }
    $stmt->close();
}

// Handle Update Status/Reply
if ($is_logged_in && isset($_POST['update_status'])) {
    $id = (int)$_POST['id'];
    $status = $_POST['status'] ?? 'pending';
    
    // Update status di database (tambahkan kolom status jika belum ada)
    $sql = "UPDATE contacts SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $id);
    if ($stmt->execute()) {
        $success_msg = "Status berhasil diupdate!";
    }
    $stmt->close();
}

// Get all messages with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Search functionality
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$where_clause = $search ? "WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR message LIKE '%$search%'" : '';

// Count total messages
$count_sql = "SELECT COUNT(*) as total FROM contacts $where_clause";
$count_result = $conn->query($count_sql);
$total_messages = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_messages / $per_page);

// Get messages
$sql = "SELECT * FROM contacts $where_clause ORDER BY created_at DESC LIMIT $offset, $per_page";
$result = $conn->query($sql);

// Get statistics
$stats_sql = "SELECT 
    COUNT(*) as total,
    COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN 1 END) as today,
    COUNT(CASE WHEN WEEK(created_at) = WEEK(CURDATE()) THEN 1 END) as this_week,
    COUNT(CASE WHEN MONTH(created_at) = MONTH(CURDATE()) THEN 1 END) as this_month
    FROM contacts";
$stats = $conn->query($stats_sql)->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Message Management</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f6fa;
            color: #333;
        }

        /* Login Page */
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .login-box {
            background: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }

        .login-box h2 {
            color: #667eea;
            margin-bottom: 2rem;
            text-align: center;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
        }

        .error-msg {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 1rem;
            text-align: center;
        }

        /* Admin Dashboard */
        .admin-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .admin-header h1 {
            font-size: 1.8rem;
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn-logout {
            padding: 8px 20px;
            background: rgba(255,255,255,0.2);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .btn-logout:hover {
            background: rgba(255,255,255,0.3);
        }

        /* Statistics Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-card .number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .stat-card .label {
            color: #666;
            font-size: 0.9rem;
        }

        /* Main Content */
        .main-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Search & Filter */
        .controls {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .search-box {
            flex: 1;
            display: flex;
            gap: 0.5rem;
        }

        .search-box input {
            flex: 1;
            padding: 10px 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
        }

        .btn-search {
            padding: 10px 25px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-search:hover {
            background: #5568d3;
        }

        /* Messages Table */
        .messages-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #667eea;
            color: white;
        }

        th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .message-preview {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .btn-action {
            padding: 6px 12px;
            margin: 0 3px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: transform 0.2s;
        }

        .btn-action:hover {
            transform: translateY(-2px);
        }

        .btn-view {
            background: #3498db;
            color: white;
        }

        .btn-delete {
            background: #e74c3c;
            color: white;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            padding: 2rem;
        }

        .pagination a {
            padding: 8px 15px;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 5px;
            border: 2px solid #667eea;
            transition: all 0.3s;
        }

        .pagination a:hover,
        .pagination a.active {
            background: #667eea;
            color: white;
        }

        /* Alert Messages */
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            text-align: center;
        }

        .alert.success {
            background: #d4edda;
            color: #155724;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .modal-header h3 {
            color: #667eea;
        }

        .btn-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #999;
        }

        .message-detail {
            padding: 1rem 0;
        }

        .message-detail strong {
            color: #667eea;
            display: block;
            margin-bottom: 0.5rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-header {
                flex-direction: column;
                gap: 1rem;
            }

            .controls {
                flex-direction: column;
            }

            table {
                font-size: 0.9rem;
            }

            th, td {
                padding: 0.5rem;
            }

            .message-preview {
                max-width: 150px;
            }
        }
    </style>
</head>
<body>

<?php if (!$is_logged_in): ?>
    <!-- Login Page -->
    <div class="login-container">
        <div class="login-box">
            <h2>🔐 Admin Login</h2>
            <?php if (isset($login_error)): ?>
                <div class="error-msg"><?php echo $login_error; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" name="login" class="btn-login">Login</button>
            </form>
            <p style="text-align: center; margin-top: 1rem; color: #999; font-size: 0.9rem;">
                Default: admin / admin123
            </p>
        </div>
    </div>

<?php else: ?>
    <!-- Admin Dashboard -->
    <div class="admin-header">
        <h1>📊 Admin Panel - Message Management</h1>
        <div class="admin-info">
            <span>👤 <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
            <a href="?logout=1" class="btn-logout">Logout</a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="number"><?php echo $stats['total']; ?></div>
            <div class="label">Total Pesan</div>
        </div>
        <div class="stat-card">
            <div class="number"><?php echo $stats['today']; ?></div>
            <div class="label">Hari Ini</div>
        </div>
        <div class="stat-card">
            <div class="number"><?php echo $stats['this_week']; ?></div>
            <div class="label">Minggu Ini</div>
        </div>
        <div class="stat-card">
            <div class="number"><?php echo $stats['this_month']; ?></div>
            <div class="label">Bulan Ini</div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <?php if (isset($success_msg)): ?>
            <div class="alert success"><?php echo $success_msg; ?></div>
        <?php endif; ?>

        <!-- Search & Controls -->
        <div class="controls">
            <form method="GET" class="search-box">
                <input type="text" name="search" placeholder="Cari berdasarkan nama, email, atau pesan..." 
                       value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn-search">🔍 Cari</button>
            </form>
        </div>

        <!-- Messages Table -->
        <div class="messages-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Pesan</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td>
                                    <div class="message-preview">
                                        <?php echo htmlspecialchars($row['message']); ?>
                                    </div>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                                <td>
                                    <button class="btn-action btn-view" 
                                            onclick="viewMessage(<?php echo $row['id']; ?>, 
                                                    '<?php echo htmlspecialchars(addslashes($row['name'])); ?>', 
                                                    '<?php echo htmlspecialchars(addslashes($row['email'])); ?>', 
                                                    '<?php echo htmlspecialchars(addslashes($row['message'])); ?>', 
                                                    '<?php echo $row['created_at']; ?>')">
                                        👁️ Lihat
                                    </button>
                                    <a href="?delete=<?php echo $row['id']; ?>" 
                                       class="btn-action btn-delete"
                                       onclick="return confirm('Yakin ingin menghapus pesan ini?')">
                                        🗑️ Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem;">
                                Tidak ada pesan ditemukan
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?><?php echo $search ? '&search='.$search : ''; ?>" 
                       class="<?php echo $page == $i ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal for View Message -->
    <div id="messageModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>📧 Detail Pesan</h3>
                <button class="btn-close" onclick="closeModal()">×</button>
            </div>
            <div class="message-detail">
                <strong>ID:</strong>
                <p id="modal-id"></p>
                
                <strong>Nama:</strong>
                <p id="modal-name"></p>
                
                <strong>Email:</strong>
                <p id="modal-email"></p>
                
                <strong>Pesan:</strong>
                <p id="modal-message" style="white-space: pre-wrap;"></p>
                
                <strong>Tanggal:</strong>
                <p id="modal-date"></p>
            </div>
        </div>
    </div>

    <script>
        function viewMessage(id, name, email, message, date) {
            document.getElementById('modal-id').textContent = id;
            document.getElementById('modal-name').textContent = name;
            document.getElementById('modal-email').textContent = email;
            document.getElementById('modal-message').textContent = message;
            document.getElementById('modal-date').textContent = new Date(date).toLocaleString('id-ID');
            document.getElementById('messageModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('messageModal').classList.remove('active');
        }

        // Close modal when clicking outside
        document.getElementById('messageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
<?php endif; ?>

</body>
</html>
<?php
$conn->close();
?>