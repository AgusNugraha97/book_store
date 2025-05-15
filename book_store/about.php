<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> <!-- Material Icons -->
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #e3f2fd;
            color: #333;
        }

        /* Navbar styles */
        nav {
            background-color: #1976d2;
            padding: 15px 20px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        nav .logo {
            font-size: 24px;
            font-weight: bold;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        nav .logo img {
            width: 35px;
            margin-right: 10px;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            padding: 8px 15px;
            transition: all 0.3s ease;
        }

        nav a:hover {
            background-color: #1565c0;
            border-radius: 5px;
            transform: scale(1.1);
        }

        nav .nav-links {
            display: flex;
            align-items: center;
        }

        nav .nav-links a {
            margin-left: 20px;
        }

        nav .nav-links a i {
            margin-right: 8px;
        }

        /* Main container styles */
        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        h1 {
            color: #1976d2;
            text-align: center;
        }

        p {
            line-height: 1.6;
            font-size: 16px;
        }

        .section {
            margin-top: 30px;
        }

        .section h2 {
            color: #1565c0;
            border-bottom: 2px solid #bbdefb;
            padding-bottom: 5px;
        }

        .team {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 15px;
        }

        .team-member {
            background-color: #f1f8ff;
            padding: 15px;
            border-radius: 8px;
            flex: 1 1 200px;
            text-align: center;
        }

        @media (max-width: 600px) {
            .team {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

    <!-- Navigation Bar -->
    <nav>
        <a href="index.php" class="logo">
            <img src="logobuku.png" alt="Logo"> Toko Buku Agus
        </a>
        <div class="nav-links">
            <a href="index.php"><i class="material-icons">home</i> Home</a>
            <a href="about.php"><i class="material-icons">info</i> Tentang Kami</a>
            <a href="contact.php"><i class="material-icons">phone</i> Kontak</a>
            <?php if (isset($_SESSION['user'])): ?>
                <a href="logout.php"><i class="material-icons">exit_to_app</i> Logout</a>
            <?php else: ?>
                <a href="login_user.php"><i class="material-icons">login</i> Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- About Us Section -->
    <div class="container">
        <h1>Tentang Kami</h1>
        <img src="gedung.png" alt="Deskripsi Gambar" width="900">
        <p>
            Selamat datang di Toko Buku Agus Nugraha Jaya kami! Kami adalah platform e-commerce yang berkomitmen untuk menyediakan produk buku berkualitas tinggi dengan harga terbaik seluruh Indonesia. Dengan dukungan teknologi dan tim yang profesional, kami hadir untuk memberikan pengalaman belanja online yang aman, cepat, dan nyaman.
        </p>

        <div class="section">
            <div style="text-align: center;">
                <h2>Misi Kami</h2>
                <p>
                    Memberikan layanan terbaik kepada pelanggan dengan menyediakan produk pilihan dan pengiriman yang handal. Kami percaya bahwa kepuasan pelanggan adalah prioritas utama.
                </p>
            </div>
        </div>

        <div class="section">
            <div style="text-align: center;">
                <h2>Tim Kami</h2>
            </div>
            <div class="team">
                <div class="team-member">
                    <h3>Agus Nugraha, S.Kom</h3>
                    <p>CEO & Founder</p>
                </div>
                <div class="team-member">
                    <h3>Ahmad Juhari, S.Kom</h3>
                    <p>Manajer Operasional</p>
                </div>
                <div class="team-member">
                    <h3>Lia Rahmawati S.Kom</h3>
                    <p>Desain & UI/UX</p>
                </div>
                <div class="team-member">
                    <h3>Bayu Askak S.Kom</h3>
                    <p>Developer</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer style="background-color: #1976d2; color: white; text-align: center; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">
        <div style="margin-bottom: 20px;">
            <h3 style="color: #fff; font-family: 'Arial', sans-serif; font-size: 24px; margin-bottom: 10px;">Lokasi Toko Kami</h3>
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.234851963479!2d106.90686401070559!3d-6.232740761011244!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f34afbd53857%3A0x84d129245f0e703b!2sPusat%20Pelatihan%20dan%20Pengembangan%20Pendidikan%20(P4)%20Kota%20Administrasi%20Jakarta%20Timur!5e0!3m2!1sid!2sid!4v1746753209944!5m2!1sid!2sid" 
                width="100%" 
                height="300" 
                style="border: 0; border-radius: 8px; margin-top: 10px;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>

        <p style="font-family: 'Arial', sans-serif; font-size: 16px;">&copy; <?php echo date("Y"); ?> Toko Buku Agus. All rights reserved.</p>

        <!-- Tambahkan ikon sosial media (opsional) -->
        <div style="margin-top: 20px;">
            <a href="#" style="color: white; margin: 0 10px; font-size: 24px; text-decoration: none;">
                <i class="fab fa-facebook-square"></i>
            </a>
            <a href="#" style="color: white; margin: 0 10px; font-size: 24px; text-decoration: none;">
                <i class="fab fa-twitter-square"></i>
            </a>
            <a href="#" style="color: white; margin: 0 10px; font-size: 24px; text-decoration: none;">
                <i class="fab fa-instagram-square"></i>
            </a>
        </div>
    </footer>

    <!-- Link untuk Font Awesome (untuk ikon sosial media) -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>

</html>
