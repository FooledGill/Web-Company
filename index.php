<?php
// index.php - Website Company Profile dengan MySQL (Updated Version)

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
 $companyName = "EgiTech Solutions";
 $companyTagline = "Inovasi Teknologi untuk Masa Depan";
 $currentYear = date("Y");

// Data perusahaan (sekarang juga mengandung link)
 $services = [
    [
        "icon" => "💻",
        "title" => "Web Development",
        "description" => "Pembuatan website profesional dengan teknologi terkini",
        "link" => "web-development.php"
    ],
    [
        "icon" => "📱",
        "title" => "Mobile Apps",
        "description" => "Aplikasi mobile untuk iOS dan Android yang user-friendly",
        "link" => "mobile-apps.php"
    ],
    [
        "icon" => "☁️",
        "title" => "Cloud Solutions",
        "description" => "Solusi cloud computing untuk bisnis yang scalable",
        "link" => "cloud-solutions.php"
    ],
    [
        "icon" => "🔒",
        "title" => "Cyber Security",
        "description" => "Perlindungan data dan sistem dengan keamanan terbaik",
        "link" => "cyber-security.php"
    ]
];

 $team = [
    ["name" => "Ragil Agustino", "position" => "CEO & Founder"],
    ["name" => "Carlotta Montelli", "position" => "CTO"],
    ["name" => "Mikage Reo", "position" => "Lead Developer"],
    ["name" => "Anisphia Wyyn Palettia", "position" => "UI/UX Designer"]
];

// Handle form contact
 $message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name'] ?? '');
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    $msg = $conn->real_escape_string($_POST['message'] ?? '');
    
    if (!empty($name) && !empty($email) && !empty($msg)) {
        // Validasi email
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Insert ke database
            $sql = "INSERT INTO contacts (name, email, message, created_at) VALUES (?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $name, $email, $msg);
            
            if ($stmt->execute()) {
                $message = "<div class='alert success'>✓ Terima kasih $name! Pesan Anda telah tersimpan di database.</div>";
            } else {
                $message = "<div class='alert error'>✗ Gagal menyimpan data: " . $conn->error . "</div>";
            }
            $stmt->close();
        } else {
            $message = "<div class='alert error'>✗ Format email tidak valid.</div>";
        }
    } else {
        $message = "<div class='alert error'>✗ Mohon lengkapi semua field.</div>";
    }
}

// --- FITUR BARU: Ambil Aktivitas Terbaru dari Semua Layanan ---
 $latest_activities = [];

// 1. Web Development
 $sql_web = "SELECT project_name, client_name, status, created_at FROM web_projects ORDER BY created_at DESC LIMIT 1";
 $result_web = $conn->query($sql_web);
if ($result_web && $result_web->num_rows > 0) {
    $row = $result_web->fetch_assoc();
    $latest_activities[] = [
        "type" => "Web Development",
        "icon" => "💻",
        "title" => $row['project_name'],
        "client" => $row['client_name'],
        "status" => $row['status'],
        "date" => $row['created_at'],
        "link" => "web-development.php#projects"
    ];
}

// 2. Mobile Apps
 $sql_mobile = "SELECT app_name, client_name, status, created_at FROM mobile_apps ORDER BY created_at DESC LIMIT 1";
 $result_mobile = $conn->query($sql_mobile);
if ($result_mobile && $result_mobile->num_rows > 0) {
    $row = $result_mobile->fetch_assoc();
    $latest_activities[] = [
        "type" => "Mobile Apps",
        "icon" => "📱",
        "title" => $row['app_name'],
        "client" => $row['client_name'],
        "status" => $row['status'],
        "date" => $row['created_at'],
        "link" => "mobile-apps.php#apps"
    ];
}

// 3. Cloud Solutions
 $sql_cloud = "SELECT solution_name, client_name, status, created_at FROM cloud_solutions ORDER BY created_at DESC LIMIT 1";
 $result_cloud = $conn->query($sql_cloud);
if ($result_cloud && $result_cloud->num_rows > 0) {
    $row = $result_cloud->fetch_assoc();
    $latest_activities[] = [
        "type" => "Cloud Solutions",
        "icon" => "☁️",
        "title" => $row['solution_name'],
        "client" => $row['client_name'],
        "status" => $row['status'],
        "date" => $row['created_at'],
        "link" => "cloud-solutions.php#solutions"
    ];
}

// 4. Cyber Security
 $sql_security = "SELECT service_name, client_name, status, created_at FROM security_services ORDER BY created_at DESC LIMIT 1";
 $result_security = $conn->query($sql_security);
if ($result_security && $result_security->num_rows > 0) {
    $row = $result_security->fetch_assoc();
    $latest_activities[] = [
        "type" => "Cyber Security",
        "icon" => "🔒",
        "title" => $row['service_name'],
        "client" => $row['client_name'],
        "status" => $row['status'],
        "date" => $row['created_at'],
        "link" => "cyber-security.php#services"
    ];
}

// Urutkan aktivitas terbaru berdasarkan tanggal
usort($latest_activities, function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});


// Ambil 5 pesan terakhir dari database (fitur lama, dipertahankan)
 $sql_contacts = "SELECT name, email, message, created_at FROM contacts ORDER BY created_at DESC LIMIT 5";
 $result_contacts = $conn->query($sql_contacts);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $companyName; ?> - <?php echo $companyTagline; ?></title>
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
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .btn-small {
            padding: 8px 20px;
            font-size: 0.9rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            margin-top: 1rem;
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

        /* Services Grid */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 4rem;
        }

        .service-card {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .service-card .icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .service-card h3 {
            color: #667eea;
            margin-bottom: 1rem;
        }
        
        .service-card .card-content {
            flex-grow: 1;
        }

        /* About Section */
        .about {
            background: #f8f9fa;
        }

        /* Team Grid */
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
        }

        .team-member {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .team-member:hover {
            transform: translateY(-5px);
        }

        .team-member h3 {
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .team-member p {
            color: #666;
            font-size: 0.9rem;
        }

        /* NEW: Latest Activities Section */
        .activities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .activity-card {
            background: white;
            border: 1px solid #e9ecef;
            border-left: 5px solid #667eea;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .activity-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .activity-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .activity-icon {
            font-size: 2rem;
        }

        .activity-title h4 {
            color: #333;
            font-size: 1.1rem;
        }

        .activity-title p {
            color: #667eea;
            font-size: 0.9rem;
            font-weight: bold;
        }

        .activity-details {
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 1rem;
        }

        .activity-details span {
            display: block;
        }

        .activity-status {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: bold;
            color: white;
            margin-bottom: 1rem;
        }
        .status-completed { background: #28a745; }
        .status-in-progress { background: #17a2b8; }
        .status-planning { background: #ffc107; color: #333; }
        .status-released { background: #6f42c1; }
        .status-testing { background: #fd7e14; }
        .status-scheduled { background: #6c757d; }

        /* Contact Form */
        .contact-form {
            max-width: 600px;
            margin: 0 auto 3rem;
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
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 150px;
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

        /* Messages List */
        .messages-list {
            max-width: 800px;
            margin: 3rem auto 0;
        }

        .messages-list h3 {
            color: #667eea;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .message-item {
            background: white;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .message-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #eee;
        }

        .message-name {
            font-weight: bold;
            color: #667eea;
        }

        .message-email {
            color: #666;
            font-size: 0.9rem;
        }

        .message-date {
            color: #999;
            font-size: 0.85rem;
        }

        .message-text {
            color: #333;
            margin-top: 0.5rem;
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

            .services-grid,
            .team-grid,
            .activities-grid {
                grid-template-columns: 1fr;
            }

            .message-header {
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
                <li><a href="#home">Home</a></li>
                <li><a href="#services">Layanan</a></li>
                <li><a href="#about">Tentang</a></li>
                <li><a href="#team">Tim</a></li>
                <li><a href="#contact">Kontak</a></li>
            </ul>
        </nav>
    </header>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <h1><?php echo $companyName; ?></h1>
        <p><?php echo $companyTagline; ?></p>
        <a href="#contact" class="btn">Hubungi Kami</a>
    </section>

    <!-- Services Section -->
    <section id="services" class="container">
        <h2 class="section-title">Layanan Kami</h2>
        <div class="services-grid">
            <?php foreach ($services as $service): ?>
            <div class="service-card">
                <div class="card-content">
                    <div class="icon"><?php echo $service['icon']; ?></div>
                    <h3><?php echo $service['title']; ?></h3>
                    <p><?php echo $service['description']; ?></p>
                </div>
                <a href="<?php echo $service['link']; ?>" class="btn btn-small">Lihat Detail</a>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about">
        <div class="container">
            <h2 class="section-title">Tentang Kami</h2>
            <p style="text-align: center; max-width: 800px; margin: 0 auto; font-size: 1.1rem;">
                <?php echo $companyName; ?> adalah perusahaan teknologi yang berdedikasi untuk memberikan 
                solusi inovatif bagi bisnis Anda. Dengan tim profesional dan berpengalaman, kami siap 
                membantu mengembangkan bisnis Anda ke era digital.
            </p>
        </div>
    </section>

    <!-- Team Section -->
    <section id="team" class="container">
        <h2 class="section-title">Tim Kami</h2>
        <div class="team-grid">
            <?php foreach ($team as $member): ?>
            <div class="team-member">
                <h3><?php echo $member['name']; ?></h3>
                <p><?php echo $member['position']; ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- NEW: Latest Activities Section -->
    <?php if (!empty($latest_activities)): ?>
    <section class="container">
        <h2 class="section-title">Aktivitas Terbaru Kami</h2>
        <div class="activities-grid">
            <?php foreach ($latest_activities as $activity): ?>
            <div class="activity-card">
                <a href="<?php echo $activity['link']; ?>" style="text-decoration: none; color: inherit;">
                    <div class="activity-header">
                        <div class="activity-icon"><?php echo $activity['icon']; ?></div>
                        <div class="activity-title">
                            <h4><?php echo htmlspecialchars($activity['title']); ?></h4>
                            <p><?php echo htmlspecialchars($activity['type']); ?></p>
                        </div>
                    </div>
                    <div class="activity-details">
                        <span>Klien: <?php echo htmlspecialchars($activity['client']); ?></span>
                        <span>Diperbarui: <?php echo date('d M Y', strtotime($activity['date'])); ?></span>
                    </div>
                    <div class="activity-status status-<?php echo strtolower(str_replace(' ', '-', $activity['status'])); ?>">
                        <?php echo htmlspecialchars($activity['status']); ?>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Contact Section -->
    <section id="contact" class="container">
        <h2 class="section-title">Hubungi Kami</h2>
        <div class="contact-form">
            <?php echo $message; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Nama</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="message">Pesan</label>
                    <textarea id="message" name="message" required></textarea>
                </div>
                <button type="submit" class="btn-submit">Kirim Pesan</button>
            </form>
        </div>

        <!-- Display Latest Messages -->
        <?php if ($result_contacts && $result_contacts->num_rows > 0): ?>
        <div class="messages-list">
            <h3>📨 Pesan Terbaru dari Database</h3>
            <?php while($row = $result_contacts->fetch_assoc()): ?>
            <div class="message-item">
                <div class="message-header">
                    <div>
                        <div class="message-name"><?php echo htmlspecialchars($row['name']); ?></div>
                        <div class="message-email"><?php echo htmlspecialchars($row['email']); ?></div>
                    </div>
                    <div class="message-date">
                        <?php echo date('d M Y, H:i', strtotime($row['created_at'])); ?>
                    </div>
                </div>
                <div class="message-text">
                    <?php echo nl2br(htmlspecialchars($row['message'])); ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php endif; ?>
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
<!-- Di bagian navigasi header -->
<nav>
    <div class="logo"><?php echo $companyName; ?></div>
    <ul>
        <li><a href="#home">Home</a></li>
        <li><a href="#services">Layanan</a></li>
        <li><a href="projects.php">Proyek</a></li> <!-- Tambahkan ini -->
        <li><a href="#about">Tentang</a></li>
        <li><a href="#team">Tim</a></li>
        <li><a href="#contact">Kontak</a></li>
    </ul>
</nav>

<!-- Di bagian hero section -->
<section id="home" class="hero">
    <h1><?php echo $companyName; ?></h1>
    <p><?php echo $companyTagline; ?></p>
    <div style="display: flex; gap: 1rem; justify-content: center;">
        <a href="#contact" class="btn">Hubungi Kami</a>
        <a href="projects.php" class="btn" style="background: rgba(255,255,255,0.2);">Lihat Proyek Kami</a> <!-- Tambahkan ini -->
    </div>
</section>