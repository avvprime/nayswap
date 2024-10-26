<!DOCTYPE html>
<html lang="tr">
<head>
	<title>Giriş yap - nayswap</title>
	<meta name="description" content="İkinci el araba,emlak,elektronik ve çok daha fazlasını ister parayla ister takasla elde edin.">
	
	<meta charset="UTF-8">
	<meta name="keywords" content="nayswap,takas,ikinciel,sahibinden,araba,ev,kitap,film,elektronik">
	<meta name="author" content="AVV">
	<link id="favicon" rel="icon" href="img/favicon-72x72.png" type="image/png" sizes="72x72">

	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" type="text/css" href="fontawesome/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="sweetalert2/sweetalert2.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<div class="login-background">
		<div class="login-header"><h1><a href="index">nayswap</a></h1></div>	
		<form class="login-main-column"  id="login-form">
			<div class="login-page-form">
				<div class="login-maildiv">
					<input type="text" id="login-page-mail" name="login-mail" required autocomplete="off">
					<label for="login-page-mail"><span>E-posta</span></label>
					<div class="loginp-mailunderline"></div>
				</div>
				<div></div>
				<div class="login-maildiv">
					<input type="password" id="login-page-password" name="login-pass" required>
					<label for="login-page-password"><span>Şifre</span></label>
					<div class="loginp-mailunderline"></div>
				</div>
			</div>
			<div class="login-submitdiv">
				<button id="login-submit-btn">Giriş</button>
			</div>
		</form>
	</div>
	<div class="login-page-footer">
		<h4>Yoksa bir hesabın yok mu? Şimdi <a href="register"><button>Kaydol</button></a></h4>
		<h4 class="forgotmy-password-link"><a href="">Şifremi unuttum.</a></h4>
	</div>
	

	<!-- The core Firebase JS SDK is always required and must be listed first -->
	<script src="https://www.gstatic.com/firebasejs/8.4.1/firebase-app.js"></script>
	<script src="https://www.gstatic.com/firebasejs/8.4.1/firebase-auth.js"></script>
	<script src="https://www.gstatic.com/firebasejs/8.4.1/firebase-database.js"></script>
	<!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#available-libraries -->


	<script type="text/javascript" src="js/jquery-3.5.1.js"></script>
	<script type="text/javascript" src="js/fontawesome.js"></script>
	<script src="sweetalert2/sweetalert2.js"></script>
	<script type="text/javascript" src="js/main.js"></script>

</body>
</html>

