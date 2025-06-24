<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Kasir - Toko Gurwan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
    <style>
        /* ===== Redesain total menu login ===== */
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: linear-gradient(120deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(102, 126, 234, 0.18);
            padding: 2.5rem 2rem 2rem 2rem;
            width: 100%;
            max-width: 350px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .login-logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.2rem;
            color: #fff;
            font-size: 2rem;
            box-shadow: 0 2px 8px rgba(102,126,234,0.10);
        }

        .login-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.2rem;
            letter-spacing: 0.5px;
        }

        .login-desc {
            color: #888;
            font-size: 1rem;
            margin-bottom: 1.5rem;
        }

        .login-form {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 1.1rem;
        }

        .login-input {
            border: 1.5px solid transparent;
            border-radius: 7px;
            padding: 0.7rem 1rem;
            font-size: 1.05rem;
            background: #f5f6fa;
            color: #222;
            outline: none;
            box-shadow: none;
            transition: border-color 0.2s, background 0.2s;
        }
        .login-input:focus {
            border-color: #667eea;
            background: #f0f2f8;
            box-shadow: none;
        }

        .login-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 7px;
            padding: 0.8rem 0;
            font-size: 1.08rem;
            font-weight: 600;
            margin-top: 0.2rem;
            cursor: pointer;
            transition: background 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 8px rgba(102,126,234,0.10);
        }
        .login-btn:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }

        .login-footer {
            margin-top: 1.5rem;
            text-align: center;
            color: #888;
            font-size: 0.95rem;
        }

        .login-footer strong {
            color: #667eea;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 1.5rem 0.7rem 1.2rem 0.7rem;
                max-width: 98vw;
            }
        }

        /* Remove autofill yellow background in Chrome */
        input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 1000px #fff inset !important;
            box-shadow: 0 0 0 1000px #fff inset !important;
            -webkit-text-fill-color: #222 !important;
        }
    </style>
</head>
<body>
  <div class="login-container">
    <div class="login-logo">
      <i class="bi bi-shop"></i>
    </div>
    <div class="login-title">Toko Gurwan</div>
    <div class="login-desc">Sistem Kasir Digital</div>
    <?php if (isset($_GET['error'])): ?>
      <div class="alert alert-danger" style="width:100%;text-align:center;padding:0.7rem 1rem;margin-bottom:1rem;">
        <?php echo htmlspecialchars($_GET['error']); ?>
      </div>
    <?php endif; ?>
    <?php if (isset($_GET['message'])): ?>
      <div class="alert alert-success" style="width:100%;text-align:center;padding:0.7rem 1rem;margin-bottom:1rem;">
        <?php echo htmlspecialchars($_GET['message']); ?>
      </div>
    <?php endif; ?>
    <form action="login.php" method="POST" class="login-form" id="loginForm">
      <input type="text" class="login-input" name="username" id="username" placeholder="Username" required autocomplete="username" autofocus>
      <div class="login-input" style="position:relative;padding:0;display:flex;align-items:center;background:#f5f6fa;border-radius:7px;border:1.5px solid transparent;">
        <input type="password" name="password" id="password" placeholder="Password" required autocomplete="current-password" style="border:none;outline:none;background:transparent;flex:1;padding:0.7rem 1rem;font-size:1.05rem;color:#222;box-shadow:none;" />
        <i class="bi bi-eye toggle-password" id="togglePassword" style="position:relative;right:1.1rem;cursor:pointer;color:#bfc9e0;font-size:1.1rem;"></i>
      </div>
      <button type="submit" class="login-btn" id="loginBtn">
        Masuk ke Sistem
      </button>
    </form>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const togglePassword = document.querySelector("#togglePassword");
    const passwordInput = document.querySelector("#password");
    togglePassword.addEventListener("click", function () {
      const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
      passwordInput.setAttribute("type", type);
      this.classList.toggle("bi-eye");
      this.classList.toggle("bi-eye-slash");
    });
    document.addEventListener('DOMContentLoaded', function() {
      document.getElementById('username').focus();
    });
    document.addEventListener('keydown', function(e) {
      if (e.altKey && e.key === 'l') {
        e.preventDefault();
        document.getElementById('username').focus();
      }
    });
    // Validasi border warna
    const inputs = document.querySelectorAll('.login-input');
    inputs.forEach(input => {
      input.addEventListener('blur', function() {
        if (this.value.trim() === '') {
          this.style.borderColor = '#dc3545';
        } else {
          this.style.borderColor = '#28a745';
        }
      });
      input.addEventListener('input', function() {
        if (this.style.borderColor === '#dc3545') {
          this.style.borderColor = '#bfc9e0';
        }
      });
    });
  </script>
</body>
</html>