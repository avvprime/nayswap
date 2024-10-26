<?php 

require_once 'db/connect.php';

if ($_GET['user'] != $_SESSION['user_id']) {
	header("Location:index?s=watch-your-steps");
	exit;
}


?>
<!DOCTYPE html>
<html lang="tr">
<head>
	<title>Hesabımı Düzenle - nayswap</title>
	<meta name="description" content="İkinci el araba,emlak,elektronik ve çok daha fazlasını ister parayla ister takasla elde edin.">
	
	<meta charset="UTF-8">
	<meta name="keywords" content="nayswap,takas,ikinciel,sahibinden,araba,ev,kitap,film,elektronik">
	<meta name="author" content="AVV">
	<link id="favicon" rel="icon" href="img/favicon-72x72.png" type="image/png" sizes="72x72">

	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" type="text/css" href="fontawesome/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="swiper/swiper-bundle.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	
	







	<script src="https://www.gstatic.com/firebasejs/8.2.7/firebase-app.js"></script>
	<script src="https://www.gstatic.com/firebasejs/8.2.7/firebase-database.js"></script>

	<script type="text/javascript" src="js/jquery-3.5.1.js"></script>
	<script type="text/javascript" src="js/main.js"></script>

</body>