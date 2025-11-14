<?php
// cloud-solutions.php

// Konfigurasi Database
 $db_host = "localhost";
 $db_user = "root";
 $db_pass = "";
 $db_name = "company_db";

// Koneksi ke Database
 $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Konfigurasi
 $companyName = "TechInnovate Solutions";
 $serviceTitle = "Cloud Solutions";
 $currentYear = date("Y");

// Handle form submission
 $message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $solution_name = $conn->real_escape_string($_POST['solution_name'] ?? '');
    $client_name = $conn->real_escape_string($_POST['client_name'] ?? '');
    $description = $conn->real_escape_string($_POST['description'] ?? '');
    $provider = $conn->real_escape_string($_POST['provider'] ?? '');
    $service_type = $conn->real_escape_string($_POST['service_type'] ?? '');
    $implementation_date = $conn->real_escape_string($_POST['implementation_date'] ?? '');
    $status = $conn->real_escape_string($_POST['status'] ?? 'Planning');
    
    if (!empty($solution_name) && !empty($client_name) && !empty($description) && !empty($provider) && !empty($service_type) && !empty($implementation_date)) {
        // Insert ke database
        $sql = "INSERT INTO cloud_solutions (solution_name, client_name, description, provider, service_type, implementation_date, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $solution_name, $client_name, $description, $provider, $service_type, $implementation_date, $status);
        
        if ($stmt->execute()) {
            $message = "<div class='alert success'>✓ Solusi cloud berhasil ditambahkan ke database.</div>";
        } else {
            $message = "<div class='alert error'>✗ Gagal menyimpan data: " . $conn->error . "</div>";
        }
        $stmt->close();
    } else {
        $message = "<div class='alert error'>✗ Mohon lengkapi semua field yang wajib diisi.</div>";
    }
}

// Ambil data solusi dari database
 $sql = "SELECT * FROM cloud_solutions ORDER BY created_at DESC";
 $result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $serviceTitle; ?> - <?php echo $companyName; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        /* Header & Navigation */
        header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        nav {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 2rem;
        }

        nav a {
            color: white;
            text-decoration: none;
            transition: opacity 0.3s;
        }

        nav a:hover {
            opacity: 0.8;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 150px 2rem 100px;
            text-align: center;
            margin-top: 60px;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: transform 0.3s;
        }

        .btn:hover {
            transform: translateY(-3px);
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 4rem 2rem;
        }

        /* Section Titles */
        .section-title {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 3rem;
            color: #667eea;
        }

        /* Service Description */
        .service-description {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 3rem;
            text-align: center;
        }

        .service-description p {
            max-width: 800px;
            margin: 0 auto;
            font-size: 1.1rem;
        }

        /* Form Styles */
        .solution-form {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 3rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #667eea;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 40px;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
        }

        .alert {
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
            text-align: center;
        }

        .alert.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Solutions List */
        .solutions-list {
            margin-top: 3rem;
        }

        .solution-card {
            background: white;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .solution-card:hover {
            transform: translateY(-5px);
        }

        .solution-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #eee;
        }

        .solution-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: #667eea;
        }

        .solution-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
            color: white;
        }

        .status-planning {
            background: #ffc107;
        }

        .status-in-progress {
            background: #17a2b8;
        }

        .status-completed {
            background: #28a745;
        }

        .status-maintenance {
            background: #fd7e14;
        }

        .solution-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .solution-detail {
            display: flex;
            flex-direction: column;
        }

        .solution-detail-label {
            font-weight: bold;
            color: #666;
            font-size: 0.9rem;
        }

        .solution-detail-value {
            color: #333;
        }

        .provider-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: bold;
            color: white;
            margin-right: 5px;
        }

        .provider-aws {
            background: #ff9900;
        }

        .provider-google {
            background: #4285f4;
        }

        .provider-azure {
            background: #0078d4;
        }

        .provider-other {
            background: #6c757d;
        }

        .service-type-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: bold;
            color: white;
            margin-right: 5px;
        }

        .type-storage {
            background: #17a2b8;
        }

        .type-computing {
            background: #6610f2;
        }

        .type-database {
            background: #e83e8c;
        }

        .type-networking {
            background: #20c997;
        }

        .type-hybrid {
            background: #fd7e14;
        }

        .solution-description {
            margin-top: 1rem;
            color: #555;
        }

        /* Footer */
        footer {
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 2rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }

            nav ul {
                gap: 1rem;
                font-size: 0.9rem;
            }

            .solution-header {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <nav>
            <div class="logo"><?php echo $companyName; ?></div>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="index.php#services">Layanan</a></li>
                <li><a href="index.php#about">Tentang</a></li>
                <li><a href="index.php#team">Tim</a></li>
                <li><a href="index.php#contact">Kontak</a></li>
            </ul>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <h1><?php echo $serviceTitle; ?></h1>
        <p>Solusi cloud computing untuk bisnis yang scalable</p>
        <a href="#solutions" class="btn">Lihat Solusi Kami</a>
    </section>

    <!-- Service Description -->
    <section class="container">
        <div class="service-description">
            <p>
                Kami menyediakan solusi cloud computing yang scalable dan aman untuk mendukung pertumbuhan bisnis Anda. 
                Dengan pengalaman dalam berbagai platform cloud seperti AWS, Google Cloud, dan Azure, kami siap membantu 
                Anda memigrasikan infrastruktur IT ke cloud atau mengoptimalkan solusi cloud yang sudah ada.
            </p>
        </div>

        <!-- Solution Form -->
        <div class="solution-form">
            <h2 class="section-title">Tambah Solusi Cloud Baru</h2>
            <?php echo $message; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="solution_name">Nama Solusi *</label>
                    <input type="text" id="solution_name" name="solution_name" required>
                </div>
                <div class="form-group">
                    <label for="client_name">Nama Klien *</label>
                    <input type="text" id="client_name" name="client_name" required>
                </div>
                <div class="form-group">
                    <label for="description">Deskripsi Solusi *</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="provider">Provider Cloud *</label>
                    <select id="provider" name="provider" required>
                        <option value="AWS">AWS</option>
                        <option value="Google Cloud">Google Cloud</option>
                        <option value="Azure">Azure</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="service_type">Jenis Layanan *</label>
                    <select id="service_type" name="service_type" required>
                        <option value="Storage">Storage</option>
                        <option value="Computing">Computing</option>
                        <option value="Database">Database</option>
                        <option value="Networking">Networking</option>
                        <option value="Hybrid">Hybrid</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="implementation_date">Tanggal Implementasi *</label>
                    <input type="date" id="implementation_date" name="implementation_date" required>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="Planning">Planning</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                        <option value="Maintenance">Maintenance</option>
                    </select>
                </div>
                <button type="submit" class="btn-submit">Simpan Solusi</button>
            </form>
        </div>

        <!-- Solutions List -->
        <div id="solutions" class="solutions-list">
            <h2 class="section-title">Solusi Cloud Kami</h2>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <div class="solution-card">
                    <div class="solution-header">
                        <div class="solution-title"><?php echo htmlspecialchars($row['solution_name']); ?></div>
                        <div class="solution-status status-<?php echo strtolower(str_replace(' ', '-', $row['status'])); ?>">
                            <?php echo htmlspecialchars($row['status']); ?>
                        </div>
                    </div>
                    <div class="solution-details">
                        <div class="solution-detail">
                            <span class="solution-detail-label">Klien</span>
                            <span class="solution-detail-value"><?php echo htmlspecialchars($row['client_name']); ?></span>
                        </div>
                        <div class="solution-detail">
                            <span class="solution-detail-label">Provider</span>
                            <span class="solution-detail-value">
                                <span class="provider-badge provider-<?php echo strtolower(str_replace(' ', '', $row['provider'])); ?>">
                                    <?php echo htmlspecialchars($row['provider']); ?>
                                </span>
                            </span>
                        </div>
                        <div class="solution-detail">
                            <span class="solution-detail-label">Jenis Layanan</span>
                            <span class="solution-detail-value">
                                <span class="service-type-badge type-<?php echo strtolower($row['service_type']); ?>">
                                    <?php echo htmlspecialchars($row['service_type']); ?>
                                </span>
                            </span>
                        </div>
                        <div class="solution-detail">
                            <span class="solution-detail-label">Tanggal Implementasi</span>
                            <span class="solution-detail-value"><?php echo date('d M Y', strtotime($row['implementation_date'])); ?></span>
                        </div>
                    </div>
                    <div class="solution-description">
                        <?php echo nl2br(htmlspecialchars($row['description'])); ?>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align: center; color: #666;">Belum ada solusi cloud yang terdaftar.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; <?php echo $currentYear; ?> <?php echo $companyName; ?>. All Rights Reserved.</p>
    </footer>
</body>
</html>
<?php
 $conn->close();
?>