<?php
include "config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nama_lengkap = $_POST['nama_lengkap'];
    $no_telepon = $_POST['no_telepon'];
    $alamat = $_POST['alamat'];

    $cek = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Username sudah digunakan!');</script>";
    } else {
        $query = "INSERT INTO user (username, password, nama_lengkap, no_telepon, alamat) 
                  VALUES ('$username', '$password', '$nama_lengkap', '$no_telepon', '$alamat')";
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href='login_user.php';</script>";
        } else {
            echo "<script>alert('Registrasi gagal!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register User</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        /* General Reset */
        body, h2, form, input, textarea, button {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fc;
            padding: 0;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-container {
            background: #fff;
            width: 100%;
            max-width: 450px;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-size: 26px;
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        label {
            font-weight: 500;
            margin-bottom: 8px;
            color: #555;
            display: block;
        }

        input,
        textarea {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px 0;
            border-radius: 6px;
            border: 1px solid #ddd;
            font-size: 16px;
            color: #333;
        }

        textarea {
            resize: vertical;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
            color: #555;
        }

        .footer a {
            color: #007BFF;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

    </style>
</head>

<body>
    <div class="form-container">
        <h2>Register User</h2>

        <form action="" method="POST">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <label for="nama_lengkap">Full Name</label>
            <input type="text" name="nama_lengkap" id="nama_lengkap" required>

            <label for="no_telepon">Phone Number</label>
            <input type="number" name="no_telepon" id="no_telepon" required>

            <label for="alamat">Address</label>
            <textarea name="alamat" id="alamat" required></textarea>

            <button type="submit">Register</button>
        </form>

        <div class="footer">
            <p>Already have an account? <a href="login_user.php">Login here</a>.</p>
        </div>
    </div>
</body>

</html>
