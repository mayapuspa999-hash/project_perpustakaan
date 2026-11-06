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
	header("location: tambah_buku.php");
	exit;
}

//ambil data dari form
$judul=mysqli_real_escape_string($conn,trim($_POST['judul']));
$kategori=mysqli_real_escape_string($conn,trim($_POST['kategori']));
$penulis=mysqli_real_escape_string($conn,trim($_POST['penulis']));
$tahun_terbit=trim($_POST['tahun_terbit']);
$stok=trim($_POST['stok']);

//validasi isian kosong
if($judul=='' || $kategori=='' || $penulis=='' || $tahun_terbit=='' || $stok=='') {
	header("location: tambah_buku.php?pesan=kosong");
	exit;
}

//validasi tahun terbit dan stok harus angka
if(!ctype_digit($tahun_terbit) || !ctype_digit($stok)) {
	header("location: tambah_buku.php?pesan=angka");
	exit;
}

//proses upload sampul (boleh tidak diisi)
$sampul='';
if($_FILES['sampul']['error']!=UPLOAD_ERR_NO_FILE) {
	$nama_file=$_FILES['sampul']['name'];
	$tmp=$_FILES['sampul']['tmp_name'];
	$ukuran=$_FILES['sampul']['size'];
	$error=$_FILES['sampul']['error'];

	//validasi ukuran maksimal 2 MB
	if($error==UPLOAD_ERR_INI_SIZE || $error==UPLOAD_ERR_FORM_SIZE || $ukuran>2*1024*1024) {
		header("location: tambah_buku.php?pesan=ukuran");
		exit;
	}
	if($error!=UPLOAD_ERR_OK) {
		header("location: tambah_buku.php?pesan=gagal_upload");
		exit;
	}

	//validasi ekstensi file
	$ekstensi=strtolower(pathinfo($nama_file,PATHINFO_EXTENSION));
	$boleh=array('jpg','jpeg','png');
	if(!in_array($ekstensi,$boleh)) {
		header("location: tambah_buku.php?pesan=ekstensi");
		exit;
	}

	//cek isi file benar-benar gambar
	if(!getimagesize($tmp)) {
		header("location: tambah_buku.php?pesan=bukan_gambar");
		exit;
	}

	//nama file dibuat unik supaya tidak bentrok
	$sampul=uniqid().'.'.$ekstensi;

	//pindahkan file ke folder uploads
	if(!move_uploaded_file($tmp,'uploads/'.$sampul)) {
		header("location: tambah_buku.php?pesan=gagal_upload");
		exit;
	}
}

//simpan data ke database
$query="INSERT INTO buku (judul, kategori, penulis, tahun_terbit, stok, sampul) VALUES ('$judul', '$kategori', '$penulis', '$tahun_terbit', '$stok', '$sampul')";
$simpan=mysqli_query($conn,$query);

if($simpan) {
	header("location: dashboard.php?pesan=tambah");
} else {
	//query gagal, hapus file yang sudah terlanjur diupload
	if($sampul!='' && file_exists('uploads/'.$sampul)) {
		unlink('uploads/'.$sampul);
	}
	header("location: tambah_buku.php?pesan=gagal");
}
exit;
?>
