<?php
//koneksi ke database MySQL
$servername='localhost';
$username='root';
$password='';
$database='db_perpustakaan';

$conn=mysqli_connect($servername,$username,$password,$database);

//cek koneksi
if(!$conn) {
	die(mysqli_connect_error());
}
?>
