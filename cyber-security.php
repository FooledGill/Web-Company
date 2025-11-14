<?php
// cyber-security.php

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
 $serviceTitle = "Cyber Security";
 $currentYear = date("Y");

// Handle form submission
 $message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_name = $conn->real_escape_string($_POST['service_name'] ?? '');
    $client_name = $conn->real_escape_string($_POST['client_name'] ?? '');
    $description = $conn->real_escape_string($_POST['description'] ?? '');
    $service_type = $conn->real_escape_string($_POST['service_type'] ?? '');
    $security_level = $conn->real_escape_string($_POST['security_level'] ?? '');
    $assessment_date = $conn->real_escape_string($_POST['assessment_date'] ?? '');
    $next_assessment_date = $conn->real_escape_string($_POST['next_assessment_date'] ?? '');
    $status = $conn->real_escape_string($_POST['status'] ?? 'Scheduled');
    
    if (!empty($service_name) && !empty($client_name) && !empty($description) && !empty($service_type) && !empty($security_level) && !empty($assessment_date)) {
        // Insert ke database
        $sql = "INSERT INTO security_services (service_name, client_name, description, service_type, security_level, assessment_date, next_assessment_date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $service_name, $client_name, $description, $service_type, $security_level, $assessment_date, $next_assessment_date, $status);
        
        if ($stmt->execute()) {
            $message = "<div class='alert success'>✓ Layanan keamanan berhasil ditambahkan ke database.</div>";
        } else {
            $message = "<div class='alert error'>✗ Gagal menyimpan data: " . $conn->error . "</div>";
        }
        $stmt->close();
    } else {
        $message = "<div class='alert error'>✗ Mohon lengkapi semua field yang wajib diisi.</div>";
    }
}

// Ambil data layanan keamanan dari database
 $sql = "SELECT * FROM security_services ORDER BY created_at DESC";
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
        .security-form {
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

        /* Security Services List */
        .services-list {
            margin-top: 3rem;
        }

        .service-card {
            background: white;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .service-card:hover {
            transform: translateY(-5px);
        }

        .service-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #eee;
        }

        .service-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: #667eea;
        }

        .service-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
            color: white;
        }

        .status-scheduled {
            background: #6c757d;
        }

        .status-in-progress {
            background: #17a2b8;
        }

        .status-completed {
            background: #28a745;
        }

        .status-follow-up {
            background: #fd7e14;
        }

        .service-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .service-detail {
            display: flex;
            flex-direction: column;
        }

        .service-detail-label {
            font-weight: bold;
            color: #666;
            font-size: 0.9rem;
        }

        .service-detail-value {
            color: #333;
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

        .type-audit {
            background: #007bff;
        }

        .type-penetration {
            background: #dc3545;
        }

        .type-assessment {
            background: #28a745;
        }

        .type-implementation {
            background: #6f42c1;
        }

        .type-training {
            background: #fd7e14;
        }

        .security-level-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: bold;
            color: white;
            margin-right: 5px;
        }

        .level-basic {
            background: #6c757d;
        }

        .level-intermediate {
            background: #17a2b8;
        }

        .level-advanced {
            background: #fd7e14;
        }

        .level-enterprise {
            background: #dc3545;
        }

        .service-description {
            margin-top: 1rem;
            color: #555;
        }

        .next-assessment {
            margin-top: 0.5rem;
            font-size: 0.9rem;
            color: #666;
        }

        .next-assessment strong {
            color: #dc3545;
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

            .service-header {
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
        <p>Perlindungan data dan sistem dengan keamanan terbaik</p>
        <a href="#services" class="btn">Lihat Layanan Kami</a>
    </section>

    <!-- Service Description -->
    <section class="container">
        <div class="service-description">
            <p>
                Kami menyediakan layanan keamanan siber komprehensif untuk melindungi aset digital Anda dari ancaman yang terus berkembang. 
                Tim ahli keamanan kami berpengalaman dalam audit keamanan, pengujian penetrasi, implementasi sistem keamanan, 
                dan pelatihan kesadaran keamanan untuk memastikan infrastruktur IT Anda tetap aman.
            </p>
        </div>

        <!-- Security Service Form -->
        <div class="security-form">
            <h2 class="section-title">Tambah Layanan Keamanan Baru</h2>
            <?php echo $message; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="service_name">Nama Layanan *</label>
                    <input type="text" id="service_name" name="service_name" required>
                </div>
                <div class="form-group">
                    <label for="client_name">Nama Klien *</label>
                    <input type="text" id="client_name" name="client_name" required>
                </div>
                <div class="form-group">
                    <label for="description">Deskripsi Layanan *</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="service_type">Jenis Layanan *</label>
                    <select id="service_type" name="service_type" required>
                        <option value="Audit">Audit</option>
                        <option value="Penetration Testing">Penetration Testing</option>
                        <option value="Security Assessment">Security Assessment</option>
                        <option value="Implementation">Implementation</option>
                        <option value="Training">Training</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="security_level">Tingkat Keamanan *</label>
                    <select id="security_level" name="security_level" required>
                        <option value="Basic">Basic</option>
                        <option value="Intermediate">Intermediate</option>
                        <option value="Advanced">Advanced</option>
                        <option value="Enterprise">Enterprise</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="assessment_date">Tanggal Assessment *</label>
                    <input type="date" id="assessment_date" name="assessment_date" required>
                </div>
                <div class="form-group">
                    <label for="next_assessment_date">Tanggal Assessment Berikutnya</label>
                    <input type="date" id="next_assessment_date" name="next_assessment_date">
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="Scheduled">Scheduled</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                        <option value="Follow-up Required">Follow-up Required</option>
                    </select>
                </div>
                <button type="submit" class="btn-submit">Simpan Layanan</button>
            </form>
        </div>

        <!-- Security Services List -->
        <div id="services" class="services-list">
            <h2 class="section-title">Layanan Keamanan Kami</h2>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <div class="service-card">
                    <div class="service-header">
                        <div class="service-title"><?php echo htmlspecialchars($row['service_name']); ?></div>
                        <div class="service-status status-<?php echo strtolower(str_replace(' ', '-', $row['status'])); ?>">
                            <?php echo htmlspecialchars($row['status']); ?>
                        </div>
                    </div>
                    <div class="service-details">
                        <div class="service-detail">
                            <span class="service-detail-label">Klien</span>
                            <span class="service-detail-value"><?php echo htmlspecialchars($row['client_name']); ?></span>
                        </div>
                        <div class="service-detail">
                            <span class="service-detail-label">Jenis Layanan</span>
                            <span class="service-detail-value">
                                <span class="service-type-badge type-<?php echo strtolower(str_replace(' ', '', $row['service_type'])); ?>">
                                    <?php echo htmlspecialchars($row['service_type']); ?>
                                </span>
                            </span>
                        </div>
                        <div class="service-detail">
                            <span class="service-detail-label">Tingkat Keamanan</span>
                            <span class="service-detail-value">
                                <span class="security-level-badge level-<?php echo strtolower($row['security_level']); ?>">
                                    <?php echo htmlspecialchars($row['security_level']); ?>
                                </span>
                            </span>
                        </div>
                        <div class="service-detail">
                            <span class="service-detail-label">Tanggal Assessment</span>
                            <span class="service-detail-value"><?php echo date('d M Y', strtotime($row['assessment_date'])); ?></span>
                        </div>
                    </div>
                    <div class="service-description">
                        <?php echo nl2br(htmlspecialchars($row['description'])); ?>
                    </div>
                    <?php if ($row['next_assessment_date']): ?>
                    <div class="next-assessment">
                        Assessment berikutnya: <strong><?php echo date('d M Y', strtotime($row['next_assessment_date'])); ?></strong>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align: center; color: #666;">Belum ada layanan keamanan yang terdaftar.</p>
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