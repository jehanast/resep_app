<?php 
session_start();
include __DIR__ . '/../config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $user = mysqli_fetch_assoc($query);

    if ($user && $password == $user['password']) {

        $_SESSION['user'] = $user;

        if ($user['role'] == 'admin') {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../karyawan/dashboard.php");
        }
        exit;

    } else {
        $error = "Email atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Seperdua</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
    body {
        background: linear-gradient(135deg, #0A1F44, #1E3A8A);
        font-family: 'Poppins', sans-serif;
    }

    .login-container {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .login-card {
        background: white;
        padding: 30px;
        border-radius: 20px;
        width: 100%;
        max-width: 380px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.25);
        animation: fadeIn 0.6s ease;
    }

    .logo-box {
        background: #0A1F44;
        padding: 12px;
        border-radius: 15px;
        display: inline-block;
    }

    .logo-login {
        height: 60px;
    }

    .brand {
        color: #0A1F44;
        font-weight: 600;
        font-size: 20px;
    }

    .subtitle {
        font-size: 13px;
        color: #6b7280;
    }

    .form-control {
        border-radius: 25px;
        padding: 12px 15px;
        font-size: 14px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #1E3A8A, #3B82F6);
        border: none;
        border-radius: 25px;
        padding: 12px;
        font-weight: 500;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #3B82F6, #60A5FA);
    }

    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(20px);}
        to {opacity: 1; transform: translateY(0);}
    }

    /* 🔥 MOBILE OPTIMIZATION */
    @media (max-width: 576px) {

        .login-card {
            padding: 25px;
            border-radius: 15px;
        }

        .logo-login {
            height: 50px;
        }

        .brand {
            font-size: 18px;
        }

        .form-control {
            font-size: 13px;
        }

        .btn-primary {
            padding: 10px;
        }
    }
    </style>
</head>

<body>

<div class="login-container">

    <div class="login-card">

        <div class="text-center mb-3">
            <div class="logo-box">
                <img src="../assets/logo.png" class="logo-login">
            </div>
            <h4 class="mt-2 brand">Seperdua Recipe</h4>
            <p class="subtitle">Login ke sistem</p>
        </div>

        <?php if(isset($error)) { ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php } ?>

        <form method="POST">

            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>

            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <button class="btn btn-primary w-100">Login</button>

        </form>

    </div>

</div>

</body>
</html>