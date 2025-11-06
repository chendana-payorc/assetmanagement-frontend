<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login - AssetManager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      height: 100vh;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
    }

    .login-card {
      width: 100%;
      max-width: 400px;
      border: none;
      border-radius: 20px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
      overflow: hidden;
      background: #fff;
      transition: all 0.3s ease-in-out;
    }

    .login-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
    }

    .card-header {
      background-color: #23b7e5;
      color: white;
      text-align: center;
      font-weight: 600;
      font-size: 1.3rem;
      padding: 1rem;
    }

    .form-control {
      border-radius: 10px;
      padding: 0.75rem;
    }

    .btn-primary {
      background-color: #23b7e5;
      border: none;
      border-radius: 10px;
      padding: 0.75rem;
      font-weight: 600;
      transition: all 0.3s;
    }

    .btn-primary:hover {
      opacity: 0.9;
      transform: translateY(-2px);
    }

    .form-label {
      font-weight: 500;
      color: #333;
    }

    .small-text {
      text-align: center;
      margin-top: 10px;
      color: #666;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>
  <div class="login-card">
    <div class="card-header">Admin Login</div>
    <div class="card-body p-4">
      <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger text-center"><?= session()->getFlashdata('error') ?></div>
      <?php endif; ?>

      <form method="POST" action="<?= base_url('login') ?>">
        <div class="mb-3">
          <label class="form-label">Email Address</label>
          <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
      </form>
    </div>
  </div>
</body>
</html>
