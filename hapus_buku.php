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

//ambil id buku dari URL
if(!isset($_GET['id'])) {
	header("location: dashboard.php?pesan=tidak_ada");
	exit;
}
$id=intval($_GET['id']);

//ambil nama file sampul sebelum datanya dihapus
$query="SELECT sampul FROM buku WHERE id=$id";
$data=mysqli_query($conn,$query);
if(mysqli_num_rows($data)==0) {
	header("location: dashboard.php?pesan=tidak_ada");
	exit;
}
$row=mysqli_fetch_assoc($data);

//hapus data dari database
$query="DELETE FROM buku WHERE id=$id";
$hapus=mysqli_query($conn,$query);

if($hapus) {
	//hapus file gambar dari folder uploads
	if($row['sampul']!='' && file_exists('uploads/'.$row['sampul'])) {
		unlink('uploads/'.$row['sampul']);
	}
	header("location: dashboard.php?pesan=hapus");
} else {
	header("location: dashboard.php?pesan=gagal");
}
exit;
?>
