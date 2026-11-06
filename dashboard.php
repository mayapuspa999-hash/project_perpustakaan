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

//pesan hasil proses (tambah, ubah, hapus)
$pesan='';
$jenis='success';
if(isset($_GET['pesan'])) {
	switch($_GET['pesan']) {
		case 'tambah':
			$pesan='Data buku berhasil ditambahkan.';
			break;
		case 'ubah':
			$pesan='Data buku berhasil diperbarui.';
			break;
		case 'hapus':
			$pesan='Data buku berhasil dihapus.';
			break;
		case 'tidak_ada':
			$pesan='Data buku tidak ditemukan.';
			$jenis='danger';
			break;
		case 'gagal':
			$pesan='Proses ke database gagal.';
			$jenis='danger';
			break;
	}
}

//ringkasan data untuk kartu statistik
$query="SELECT COUNT(*) AS total, COALESCE(SUM(stok),0) AS total_stok, COALESCE(SUM(stok>0),0) AS tersedia FROM buku";
$ringkas=mysqli_fetch_assoc(mysqli_query($conn,$query));
$dipinjam=$ringkas['total']-$ringkas['tersedia'];

//ambil semua data buku
$query="SELECT * FROM buku ORDER BY id DESC";
$data=mysqli_query($conn,$query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Dashboard | Mini Digital Library</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
	<style>
		body { background: #f4f6fb; }
		.navbar-brand i { color: #8fb3ff; }
		.stat-card { border: 0; border-radius: .9rem; }
		.stat-icon { width: 48px; height: 48px; border-radius: .75rem; font-size: 1.4rem; }
		.cover-thumb { width: 48px; height: 64px; object-fit: cover; border-radius: .4rem; }
		.cover-empty { width: 48px; height: 64px; border-radius: .4rem; background: #e9ecef; color: #adb5bd; }
	</style>
</head>
<body>
	<nav class="navbar navbar-dark bg-dark shadow-sm">
		<div class="container">
			<a class="navbar-brand fw-semibold" href="dashboard.php">
				<i class="bi bi-journal-bookmark-fill me-1"></i> Mini Digital Library
			</a>
			<div class="d-flex align-items-center gap-3">
				<span class="text-white-50 small d-none d-sm-inline">
					<i class="bi bi-person-circle me-1"></i><?php echo htmlspecialchars($_SESSION['username']); ?>
				</span>
				<a href="logout.php" class="btn btn-outline-light btn-sm">
					<i class="bi bi-box-arrow-right me-1"></i>Logout
				</a>
			</div>
		</div>
	</nav>

	<main class="container py-4">
		<?php if($pesan!='') { ?>
			<div class="alert alert-<?php echo $jenis; ?> alert-dismissible fade show" role="alert">
				<?php echo $pesan; ?>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
			</div>
		<?php } ?>

		<div class="row g-3 mb-4">
			<div class="col-6 col-lg-3">
				<div class="card stat-card shadow-sm"><div class="card-body d-flex align-items-center gap-3">
					<div class="stat-icon bg-primary-subtle text-primary d-flex align-items-center justify-content-center"><i class="bi bi-book"></i></div>
					<div><div class="text-muted small">Judul Buku</div><div class="fs-4 fw-bold"><?php echo $ringkas['total']; ?></div></div>
				</div></div>
			</div>
			<div class="col-6 col-lg-3">
				<div class="card stat-card shadow-sm"><div class="card-body d-flex align-items-center gap-3">
					<div class="stat-icon bg-info-subtle text-info d-flex align-items-center justify-content-center"><i class="bi bi-stack"></i></div>
					<div><div class="text-muted small">Total Stok</div><div class="fs-4 fw-bold"><?php echo $ringkas['total_stok']; ?></div></div>
				</div></div>
			</div>
			<div class="col-6 col-lg-3">
				<div class="card stat-card shadow-sm"><div class="card-body d-flex align-items-center gap-3">
					<div class="stat-icon bg-success-subtle text-success d-flex align-items-center justify-content-center"><i class="bi bi-check2-circle"></i></div>
					<div><div class="text-muted small">Tersedia</div><div class="fs-4 fw-bold"><?php echo $ringkas['tersedia']; ?></div></div>
				</div></div>
			</div>
			<div class="col-6 col-lg-3">
				<div class="card stat-card shadow-sm"><div class="card-body d-flex align-items-center gap-3">
					<div class="stat-icon bg-warning-subtle text-warning d-flex align-items-center justify-content-center"><i class="bi bi-hourglass-split"></i></div>
					<div><div class="text-muted small">Dipinjam</div><div class="fs-4 fw-bold"><?php echo $dipinjam; ?></div></div>
				</div></div>
			</div>
		</div>

		<div class="card border-0 shadow-sm" style="border-radius: .9rem;">
			<div class="card-body">
				<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
					<h5 class="fw-bold mb-0">Daftar Buku</h5>
					<div class="d-flex gap-2">
						<input type="search" id="cari" class="form-control form-control-sm" placeholder="Cari judul / penulis...">
						<a href="tambah_buku.php" class="btn btn-primary btn-sm text-nowrap">
							<i class="bi bi-plus-lg me-1"></i>Tambah Buku
						</a>
					</div>
				</div>

				<div class="table-responsive">
					<table class="table table-hover align-middle mb-0" id="tabelBuku">
						<thead class="table-light">
							<tr>
								<th>No</th>
								<th>Sampul</th>
								<th>Judul</th>
								<th>Kategori</th>
								<th>Penulis</th>
								<th>Tahun</th>
								<th>Stok</th>
								<th>Status</th>
								<th class="text-end">Aksi</th>
							</tr>
						</thead>
						<tbody>
						<?php
						$no=1;
						while($row=mysqli_fetch_assoc($data)) {
							//tentukan label status berdasarkan stok
							if($row['stok']>0) {
								$status='Tersedia';
								$warna='success';
							} else {
								$status='Dipinjam';
								$warna='warning';
							}
						?>
							<tr>
								<td><?php echo $no++; ?></td>
								<td>
									<?php if(!empty($row['sampul'])) { ?>
										<img src="uploads/<?php echo $row['sampul']; ?>" alt="Sampul" class="cover-thumb shadow-sm">
									<?php } else { ?>
										<div class="cover-empty d-flex align-items-center justify-content-center"><i class="bi bi-image"></i></div>
									<?php } ?>
								</td>
								<td class="fw-semibold"><?php echo htmlspecialchars($row['judul']); ?></td>
								<td><?php echo htmlspecialchars($row['kategori']); ?></td>
								<td><?php echo htmlspecialchars($row['penulis']); ?></td>
								<td><?php echo $row['tahun_terbit']; ?></td>
								<td><?php echo $row['stok']; ?></td>
								<td><span class="badge text-bg-<?php echo $warna; ?>"><?php echo $status; ?></span></td>
								<td class="text-end text-nowrap">
									<a href="edit_buku.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary">
										<i class="bi bi-pencil-square"></i> Edit
									</a>
									<button type="button" class="btn btn-sm btn-outline-danger btn-hapus"
										data-id="<?php echo $row['id']; ?>"
										data-judul="<?php echo htmlspecialchars($row['judul']); ?>">
										<i class="bi bi-trash"></i> Hapus
									</button>
								</td>
							</tr>
						<?php } ?>
						<?php if($no==1) { ?>
							<tr><td colspan="9" class="text-center text-muted py-4">Belum ada data buku.</td></tr>
						<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</main>

	<!-- Modal konfirmasi hapus -->
	<div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Hapus Buku</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
				</div>
				<div class="modal-body">
					Yakin ingin menghapus buku <strong id="judulHapus"></strong>? Data dan file sampulnya akan dihapus permanen.
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
					<a href="#" id="linkHapus" class="btn btn-danger">Ya, Hapus</a>
				</div>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script>
		//modal konfirmasi hapus
		var modalHapus = new bootstrap.Modal(document.getElementById('modalHapus'));
		document.querySelectorAll('.btn-hapus').forEach(function (tombol) {
			tombol.addEventListener('click', function () {
				document.getElementById('judulHapus').textContent = this.dataset.judul;
				document.getElementById('linkHapus').href = 'hapus_buku.php?id=' + this.dataset.id;
				modalHapus.show();
			});
		});

		//pencarian cepat di tabel
		document.getElementById('cari').addEventListener('input', function () {
			var kata = this.value.toLowerCase();
			document.querySelectorAll('#tabelBuku tbody tr').forEach(function (tr) {
				tr.style.display = tr.textContent.toLowerCase().indexOf(kata) > -1 ? '' : 'none';
			});
		});
	</script>
</body>
</html>
