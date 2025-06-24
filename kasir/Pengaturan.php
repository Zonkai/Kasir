<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Account Settings</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="assets/style.css">
  <style>
    /* Sekadar contoh styling sederhana */
    label {
      display: block;
      margin-top: 10px;
    }
    input[type="text"],
    input[type="password"],
    textarea {
      width: 300px;
      padding: 5px;
    }
    button {
      margin-top: 15px;
      padding: 10px 20px;
    }
    body {
      padding-left: 250px; /* Sesuaikan dengan lebar sidebar */
    }
  </style>
</head>
<body>
<?php include 'sidebar.php'; ?>
  <div class="container mt-5">
    <h1>Ubah Akun</h1>
    
    <!-- Form ini mengirim data ke file update_account.php -->
    <form action="update_account.php" method="POST" class="mt-4">
      <div class="mb-3">
        <label for="nama_toko" class="form-label">Nama Toko:</label>
        <input type="text" id="nama_toko" name="nama_toko" 
               value="" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="username" class="form-label">Username:</label>
        <input type="text" id="username" name="username" 
               value="" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password Baru:</label>
        <input type="password" id="password" name="password" 
               value="" class="form-control" required>
      </div>
      
      <button type="submit" class="btn btn-primary">Update</button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
