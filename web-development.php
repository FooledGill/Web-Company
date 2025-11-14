<?php
// web-development.php

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
 $serviceTitle = "Web Development";
 $currentYear = date("Y");

// Handle form submission
 $message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $project_name = $conn->real_escape_string($_POST['project_name'] ?? '');
    $client_name = $conn->real_escape_string($_POST['client_name'] ?? '');
    $description = $conn->real_escape_string($_POST['description'] ?? '');
    $technology = $conn->real_escape_string($_POST['technology'] ?? '');
    $start_date = $conn->real_escape_string($_POST['start_date'] ?? '');
    $end_date = $conn->real_escape_string($_POST['end_date'] ?? '');
    $status = $conn->real_escape_string($_POST['status'] ?? 'Planning');
    
    if (!empty($project_name) && !empty($client_name) && !empty($description) && !empty($technology) && !empty($start_date)) {
        // Insert ke database
        $sql = "INSERT INTO web_projects (project_name, client_name, description, technology, start_date, end_date, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $project_name, $client_name, $description, $technology, $start_date, $end_date, $status);
        
        if ($stmt->execute()) {
            $message = "<div class='alert success'>✓ Proyek web berhasil ditambahkan ke database.</div>";
        } else {
            $message = "<div class='alert error'>✗ Gagal menyimpan data: " . $conn->error . "</div>";
        }
        $stmt->close();
    } else {
        $message = "<div class='alert error'>✗ Mohon lengkapi semua field yang wajib diisi.</div>";
    }
}

// Ambil data proyek dari database
 $sql = "SELECT * FROM web_projects ORDER BY created_at DESC";
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
        .project-form {
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

        /* Projects List */
        .projects-list {
            margin-top: 3rem;
        }

        .project-card {
            background: white;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .project-card:hover {
            transform: translateY(-5px);
        }

        .project-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #eee;
        }

        .project-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: #667eea;
        }

        .project-status {
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

        .status-on-hold {
            background: #6c757d;
        }

        .project-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .project-detail {
            display: flex;
            flex-direction: column;
        }

        .project-detail-label {
            font-weight: bold;
            color: #666;
            font-size: 0.9rem;
        }

        .project-detail-value {
            color: #333;
        }

        .project-description {
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

            .project-header {
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
        <p>Pembuatan website profesional dengan teknologi terkini</p>
        <a href="#projects" class="btn">Lihat Proyek Kami</a>
    </section>

    <!-- Service Description -->
    <section class="container">
        <div class="service-description">
            <p>
                Kami menyediakan layanan pengembangan web yang komprehensif, mulai dari perencanaan hingga implementasi dan pemeliharaan. 
                Tim kami terdiri dari pengembang berpengalaman yang menguasai berbagai teknologi web modern untuk menciptakan 
                solusi yang sesuai dengan kebutuhan bisnis Anda.
            </p>
        </div>

        <!-- Project Form -->
        <div class="project-form">
            <h2 class="section-title">Tambah Proyek Web Baru</h2>
            <?php echo $message; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="project_name">Nama Proyek *</label>
                    <input type="text" id="project_name" name="project_name" required>
                </div>
                <div class="form-group">
                    <label for="client_name">Nama Klien *</label>
                    <input type="text" id="client_name" name="client_name" required>
                </div>
                <div class="form-group">
                    <label for="description">Deskripsi Proyek *</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="technology">Teknologi *</label>
                    <input type="text" id="technology" name="technology" required>
                </div>
                <div class="form-group">
                    <label for="start_date">Tanggal Mulai *</label>
                    <input type="date" id="start_date" name="start_date" required>
                </div>
                <div class="form-group">
                    <label for="end_date">Tanggal Selesai</label>
                    <input type="date" id="end_date" name="end_date">
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="Planning">Planning</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                        <option value="On Hold">On Hold</option>
                    </select>
                </div>
                <button type="submit" class="btn-submit">Simpan Proyek</button>
            </form>
        </div>

        <!-- Projects List -->
        <div id="projects" class="projects-list">
            <h2 class="section-title">Proyek Web Kami</h2>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <div class="project-card">
                    <div class="project-header">
                        <div class="project-title"><?php echo htmlspecialchars($row['project_name']); ?></div>
                        <div class="project-status status-<?php echo strtolower(str_replace(' ', '-', $row['status'])); ?>">
                            <?php echo htmlspecialchars($row['status']); ?>
                        </div>
                    </div>
                    <div class="project-details">
                        <div class="project-detail">
                            <span class="project-detail-label">Klien</span>
                            <span class="project-detail-value"><?php echo htmlspecialchars($row['client_name']); ?></span>
                        </div>
                        <div class="project-detail">
                            <span class="project-detail-label">Teknologi</span>
                            <span class="project-detail-value"><?php echo htmlspecialchars($row['technology']); ?></span>
                        </div>
                        <div class="project-detail">
                            <span class="project-detail-label">Tanggal Mulai</span>
                            <span class="project-detail-value"><?php echo date('d M Y', strtotime($row['start_date'])); ?></span>
                        </div>
                        <?php if ($row['end_date']): ?>
                        <div class="project-detail">
                            <span class="project-detail-label">Tanggal Selesai</span>
                            <span class="project-detail-value"><?php echo date('d M Y', strtotime($row['end_date'])); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="project-description">
                        <?php echo nl2br(htmlspecialchars($row['description'])); ?>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align: center; color: #666;">Belum ada proyek web yang terdaftar.</p>
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