<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Account Settings</title>
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
  </style>
</head>
<body>
  <h1>Account Settings</h1>
  
  <!-- Form ini mengirim data ke file update_account.php -->
  <form action="update_account.php" method="POST">
    
    <label for="nama_toko">Nama Toko:</label>
    <input type="text" id="nama_toko" name="nama_toko" 
           value="TOKO ALFAMART" required>

    <label for="telepon">Telepon:</label>
    <input type="text" id="telepon" name="telepon" 
           value="0852354564" required>

    <label for="alamat">Alamat:</label>
    <textarea id="alamat" name="alamat" rows="3" required>Desa Sukosari Lor Kecamatan Sukosari Kabupaten Bondowoso</textarea>
    
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" 
           value="admin" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" 
           value="password" required>
    
    <button type="submit">Update</button>
  </form>
</body>
</html>
