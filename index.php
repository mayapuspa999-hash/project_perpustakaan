<?php
//mulai session
if(!isset($_SESSION)) {
	session_start();
}

//jika sudah login, langsung ke dashboard
if(isset($_SESSION['username'])) {
	header("location: dashboard.php");
	exit;
}

//jika ada cookie remember me, isi session lalu ke dashboard
if(isset($_COOKIE['username'])) {
	$_SESSION['username']=$_COOKIE['username'];
	header("location: dashboard.php");
	exit;
}

//pesan kesalahan login
$pesan='';
if(isset($_GET['pesan'])) {
	switch($_GET['pesan']) {
		case 'kosong':
			$pesan='Username dan password wajib diisi.';
			break;
		case 'gagal':
			$pesan='Username atau password salah.';
			break;
	}
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login | Mini Digital Library</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
	<style>
		body {
			min-height: 100vh;
			background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #6a85b6 100%);
		}
		.login-card { max-width: 420px; border: 0; border-radius: 1rem; }
		.brand-icon {
			width: 64px; height: 64px; border-radius: 50%;
			background: #e8efff; color: #2a5298; font-size: 2rem;
		}
	</style>
</head>
<body class="d-flex align-items-center justify-content-center p-3">
	<div class="card login-card shadow-lg w-100">
		<div class="card-body p-4 p-md-5">
			<div class="text-center mb-4">
				<div class="brand-icon d-inline-flex align-items-center justify-content-center mb-3">
					<i class="bi bi-journal-bookmark-fill"></i>
				</div>
				<h4 class="fw-bold mb-1">Mini Digital Library</h4>
				<p class="text-muted small mb-0">Silakan masuk untuk mengelola data buku</p>
			</div>

			<?php if($pesan!='') { ?>
				<div class="alert alert-danger py-2 small" role="alert"><?php echo $pesan; ?></div>
			<?php } ?>

			<form action="proses_login.php" method="POST">
				<div class="mb-3">
					<label for="username" class="form-label">Username</label>
					<div class="input-group">
						<span class="input-group-text"><i class="bi bi-person"></i></span>
						<input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required autofocus>
					</div>
				</div>
				<div class="mb-3">
					<label for="password" class="form-label">Password</label>
					<div class="input-group">
						<span class="input-group-text"><i class="bi bi-lock"></i></span>
						<input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
						<button class="btn btn-outline-secondary" type="button" id="togglePassword" aria-label="Tampilkan password">
							<i class="bi bi-eye"></i>
						</button>
					</div>
				</div>
				<div class="form-check mb-4">
					<input class="form-check-input" type="checkbox" id="remember" name="remember" value="1">
					<label class="form-check-label" for="remember">Remember Me</label>
				</div>
				<button type="submit" class="btn btn-primary w-100 py-2">
					<i class="bi bi-box-arrow-in-right me-1"></i> Masuk
				</button>
			</form>
		</div>
	</div>

	<script>
		//tampilkan / sembunyikan password
		document.getElementById('togglePassword').addEventListener('click', function () {
			var input = document.getElementById('password');
			var icon = this.querySelector('i');
			var tampil = input.type === 'password';
			input.type = tampil ? 'text' : 'password';
			icon.className = tampil ? 'bi bi-eye-slash' : 'bi bi-eye';
		});
	</script>
</body>
</html>
