<?php 

date_default_timezone_set('Europe/Istanbul');
ob_start();
session_start();

try {

	$db= new PDO("mysql:host=localhost;dbname=nayswap;charset=utf8","root","123456789");

			//echo "veritabanı bağlantısı başarılı";

} catch (PDOException $e) {	

	echo $e->getMessage();
}



 ?>