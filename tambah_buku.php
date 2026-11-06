<?php
//mulai session
if(!isset($_SESSION)) {
	session_start();
}
require_once 'config/koneksi.php';

//cek login, jika belum login kembali ke halaman login
if(empty($_SESSION['username'])) {
	header("location: index.php");
	exit;
}

//pesan kesalahan dari proses_tambah.php
$pesan='';
if(isset($_GET['pesan'])) {
	switch($_GET['pesan']) {
		case 'kosong':
			$pesan='Semua kolom wajib diisi.';
			break;
		case 'angka':
			$pesan='Tahun terbit dan stok harus berupa angka.';
			break;
		case 'ekstensi':
			$pesan='Sampul harus berformat JPG atau PNG.';
			break;
		case 'ukuran':
			$pesan='Ukuran file sampul maksimal 2 MB.';
			break;
		case 'bukan_gambar':
			$pesan='File yang diunggah bukan gambar yang valid.';
			break;
		case 'gagal_upload':
			$pesan='Upload sampul gagal.';
			break;
		case 'gagal':
			$pesan='Data gagal disimpan ke database.';
			break;
	}
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Tambah Buku | Mini Digital Library</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
	<style>
		body { background: #f4f6fb; }
		.preview-box {
			width: 150px; height: 200px; border-radius: .6rem; background: #e9ecef;
			color: #adb5bd; font-size: 2.5rem; overflow: hidden;
		}
		.preview-box img { width: 100%; height: 100%; object-fit: cover; }
	</style>
</head>
<body>
	<nav class="navbar navbar-dark bg-dark shadow-sm">
		<div class="container">
			<a class="navbar-brand fw-semibold" href="dashboard.php">
				<i class="bi bi-journal-bookmark-fill me-1" style="color:#8fb3ff"></i> Mini Digital Library
			</a>
			<a href="logout.php" class="btn btn-outline-light btn-sm"><i class="bi bi-box-arrow-right me-1"></i>Logout</a>
		</div>
	</nav>

	<main class="container py-4" style="max-width: 820px;">
		<div class="d-flex justify-content-between align-items-center mb-3">
			<h4 class="fw-bold mb-0">Tambah Buku</h4>
			<a href="dashboard.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
		</div>

		<?php if($pesan!='') { ?>
			<div class="alert alert-danger" role="alert"><?php echo $pesan; ?></div>
		<?php } ?>

		<div class="card border-0 shadow-sm" style="border-radius:.9rem;">
			<div class="card-body p-4">
				<form action="proses_tambah.php" method="POST" enctype="multipart/form-data">
					<div class="row g-4">
						<div class="col-md-8">
							<div class="mb-3">
								<label for="judul" class="form-label">Judul Buku</label>
								<input type="text" class="form-control" id="judul" name="judul" maxlength="150" required>
							</div>
							<div class="row">
								<div class="col-sm-6 mb-3">
									<label for="kategori" class="form-label">Kategori</label>
									<input type="text" class="form-control" id="kategori" name="kategori" maxlength="50" list="daftarKategori" required>
									<datalist id="daftarKategori">
										<option value="Novel"><option value="Teknologi"><option value="Sains">
										<option value="Sejarah"><option value="Pendidikan"><option value="Lainnya">
									</datalist>
								</div>
								<div class="col-sm-6 mb-3">
									<label for="penulis" class="form-label">Penulis</label>
									<input type="text" class="form-control" id="penulis" name="penulis" maxlength="100" required>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6 mb-3">
									<label for="tahun_terbit" class="form-label">Tahun Terbit</label>
									<input type="number" class="form-control" id="tahun_terbit" name="tahun_terbit" min="1000" max="<?php echo date('Y'); ?>" required>
								</div>
								<div class="col-sm-6 mb-3">
									<label for="stok" class="form-label">Stok</label>
									<input type="number" class="form-control" id="stok" name="stok" min="0" value="1" required>
									<div class="form-text">Stok 0 otomatis berstatus "Dipinjam".</div>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<label class="form-label">Sampul Buku</label>
							<div class="preview-box d-flex align-items-center justify-content-center mb-2" id="previewBox">
								<i class="bi bi-image"></i>
							</div>
							<input type="file" class="form-control" id="sampul" name="sampul" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
							<div class="form-text">JPG/PNG, maksimal 2 MB.</div>
						</div>
					</div>

					<hr class="my-4">
					<div class="d-flex justify-content-end gap-2">
						<a href="dashboard.php" class="btn btn-light">Batal</a>
						<button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
					</div>
				</form>
			</div>
		</div>
	</main>

	<script>
		//pratinjau sampul dan cek ukuran di browser (validasi utama tetap di server)
		document.getElementById('sampul').addEventListener('change', function () {
			var box = document.getElementById('previewBox');
			var file = this.files[0];
			if (!file) { box.innerHTML = '<i class="bi bi-image"></i>'; return; }
			if (file.size > 2 * 1024 * 1024) {
				alert('Ukuran file maksimal 2 MB.');
				this.value = '';
				box.innerHTML = '<i class="bi bi-image"></i>';
				return;
			}
			var reader = new FileReader();
			reader.onload = function (e) { box.innerHTML = '<img src="' + e.target.result + '" alt="Pratinjau sampul">'; };
			reader.readAsDataURL(file);
		});
	</script>
</body>
</html>
