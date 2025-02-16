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
    <title>Login Kasir</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
</head>
<body class="bg-light">
    <div class="container mt-5" style="max-width: 400px;">
        <div class="card shadow-sm" style="border-radius: 12px;">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <h1 class="display-5 fw-bold text-primary mb-3">Toko Gurwan</h1>
                    <p class="text-muted">Silahkan login untuk melanjutkan</p>
                </div>
                
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger mb-3" style="border-radius: 8px;">
                        <?php echo htmlspecialchars($_GET['error']); ?>
                    </div>
                <?php endif; ?>

                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <input type="text" class="form-control form-control-lg" name="username" placeholder="Username" required style="border-radius: 8px;">
                    </div>
                    
                    <div class="mb-3">
                        <input type="password" class="form-control form-control-lg" name="password" placeholder="Password" required style="border-radius: 8px;">
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg w-100 mb-2 fw-bold" style="border-radius: 8px;">Masuk</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
