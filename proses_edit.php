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

//hanya menerima metode POST
if($_SERVER['REQUEST_METHOD']!='POST') {
	header("location: dashboard.php");
	exit;
}

//ambil data dari form
$id=intval($_POST['id']);
$judul=mysqli_real_escape_string($conn,trim($_POST['judul']));
$kategori=mysqli_real_escape_string($conn,trim($_POST['kategori']));
$penulis=mysqli_real_escape_string($conn,trim($_POST['penulis']));
$tahun_terbit=trim($_POST['tahun_terbit']);
$stok=trim($_POST['stok']);

$halaman="edit_buku.php?id=$id";

//ambil nama sampul lama dari database
$query="SELECT sampul FROM buku WHERE id=$id";
$data=mysqli_query($conn,$query);
if(mysqli_num_rows($data)==0) {
	header("location: dashboard.php?pesan=tidak_ada");
	exit;
}
$row=mysqli_fetch_assoc($data);
$sampul_lama=$row['sampul'];

//validasi isian kosong
if($judul=='' || $kategori=='' || $penulis=='' || $tahun_terbit=='' || $stok=='') {
	header("location: $halaman&pesan=kosong");
	exit;
}

//validasi tahun terbit dan stok harus angka
if(!ctype_digit($tahun_terbit) || !ctype_digit($stok)) {
	header("location: $halaman&pesan=angka");
	exit;
}

//jika tidak ada file baru, pakai nama sampul lama
$sampul=$sampul_lama;
$ganti_sampul=false;

//jika ada file baru, validasi lalu upload
if($_FILES['sampul']['error']!=UPLOAD_ERR_NO_FILE) {
	$nama_file=$_FILES['sampul']['name'];
	$tmp=$_FILES['sampul']['tmp_name'];
	$ukuran=$_FILES['sampul']['size'];
	$error=$_FILES['sampul']['error'];

	//validasi ukuran maksimal 2 MB
	if($error==UPLOAD_ERR_INI_SIZE || $error==UPLOAD_ERR_FORM_SIZE || $ukuran>2*1024*1024) {
		header("location: $halaman&pesan=ukuran");
		exit;
	}
	if($error!=UPLOAD_ERR_OK) {
		header("location: $halaman&pesan=gagal_upload");
		exit;
	}

	//validasi ekstensi file
	$ekstensi=strtolower(pathinfo($nama_file,PATHINFO_EXTENSION));
	$boleh=array('jpg','jpeg','png');
	if(!in_array($ekstensi,$boleh)) {
		header("location: $halaman&pesan=ekstensi");
		exit;
	}

	//cek isi file benar-benar gambar
	if(!getimagesize($tmp)) {
		header("location: $halaman&pesan=bukan_gambar");
		exit;
	}

	//nama file dibuat unik, lalu pindahkan ke folder uploads
	$sampul=uniqid().'.'.$ekstensi;
	if(!move_uploaded_file($tmp,'uploads/'.$sampul)) {
		header("location: $halaman&pesan=gagal_upload");
		exit;
	}
	$ganti_sampul=true;
}

//ubah data di database
$query="UPDATE buku SET judul='$judul', kategori='$kategori', penulis='$penulis', tahun_terbit='$tahun_terbit', stok='$stok', sampul='$sampul' WHERE id=$id";
$ubah=mysqli_query($conn,$query);

if($ubah) {
	//data berhasil diubah, baru hapus file sampul lama
	if($ganti_sampul && $sampul_lama!='' && file_exists('uploads/'.$sampul_lama)) {
		unlink('uploads/'.$sampul_lama);
	}
	header("location: dashboard.php?pesan=ubah");
} else {
	//query gagal, hapus file baru yang sudah terlanjur diupload
	if($ganti_sampul && file_exists('uploads/'.$sampul)) {
		unlink('uploads/'.$sampul);
	}
	header("location: $halaman&pesan=gagal");
}
exit;
?>
