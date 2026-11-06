<?php
//mulai session
if(!isset($_SESSION)) {
	session_start();
}

//hapus session
unset($_SESSION['username']);
session_destroy();

//hapus cookie remember me
setcookie('username','',time()-60,'/');

header("location: index.php");
exit;
?>
