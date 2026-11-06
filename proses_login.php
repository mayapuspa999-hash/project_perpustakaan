<?php
//mulai session
if(!isset($_SESSION)) {
	session_start();
}
require_once 'config/koneksi.php';

//hanya menerima metode POST
if($_SERVER['REQUEST_METHOD']!='POST') {
	header("location: index.php");
	exit;
}

//ambil data dari form
$username=mysqli_real_escape_string($conn,trim($_POST['username']));
$password=$_POST['password'];

//cek isian kosong
if($username=='' || $password=='') {
	header("location: index.php?pesan=kosong");
	exit;
}

//cari user berdasarkan username
$query="SELECT * FROM users WHERE username='$username'";
$login=mysqli_query($conn,$query);

//cek jumlah data yang ditemukan
if(mysqli_num_rows($login)==1) {
	$data=mysqli_fetch_assoc($login);

	//cocokkan password dengan hash di database
	if(password_verify($password,$data['password'])) {
		//buat session
		session_regenerate_id(true);
		$_SESSION['username']=$data['username'];

		//jika remember me dicentang, buat cookie 1 hari
		if(isset($_POST['remember'])) {
			setcookie('username',$data['username'],time()+86400,'/');
		} else {
			setcookie('username','',time()-60,'/');
		}

		header("location: dashboard.php");
		exit;
	}
}

//username atau password salah
header("location: index.php?pesan=gagal");
exit;
?>
