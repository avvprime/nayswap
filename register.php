<!DOCTYPE html>
<html lang="tr">
<head>
	<title>Kayıt Ol - nayswap</title>
	<meta name="description" content="İkinci el araba,emlak,elektronik ve çok daha fazlasını ister parayla ister takasla elde edin.">
	
	<meta charset="UTF-8">
	<meta name="keywords" content="nayswap,takas,ikinciel,sahibinden,araba,ev,kitap,film,elektronik">
	<meta name="author" content="AVV">
	<link id="favicon" rel="icon" href="img/favicon-72x72.png" type="image/png" sizes="72x72">

	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" type="text/css" href="fontawesome/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="swiper/swiper-bundle.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<style type="text/css">
		html,body{
			height: 100%;
			color: #fff;
			background-color: #0cbaba;
		}
		body{
			/*<a href="https://www.vecteezy.com/free-vector/space">Space Vectors by Vecteezy</a>*/
			background-image: url("img/register-background-images/eezy_69.svg");
			background-repeat: no-repeat;
			background-position: center;
			background-size: cover;
		}
	</style>
	
</head>
<body>
	<div class="swiper-container">
		<div class="swiper-wrapper">
		
		</div>
	</div>	

	<template id="slide-template">
		<div class="swiper-slide">
			<div class="form-container">
				<fieldset>
					<legend>{title}</legend>
					{field}
				</fieldset>
				<button class="register-form-button">Devam</button>
			</div>
			<div id="register-alert"></div>
		</div>
	</template>

	<template id="register-end-template">
		<div class="swiper-slide">
			<h1>Hoşgeldin,</h1>
			<p>artık nayswap.com'un bir üyesisin.</p>
			<div id="register-end-loginbtn"><a href="index"><button>Devam et</button></a></div>
		</div>
	</template>

	<template id="register-start-template">
		<div class="swiper-slide" id="register-first-slide">
			<a href="https://www.vecteezy.com/free-vector/space">Space Vectors by Vecteezy</a>
			<a href="login" class="backtologin-link"><button>Hesabım var</button></a>
			<a href="index" class="register-first-slide-brand">nayswap</a>
			<div class="maindiv-inslide">

				<h1>nayswap kayıt</h1>
				<p>nayswap de güvenilir bir ortam oluşturmayı amaçlıyoruz. Bunun için bazı bilgilere ihtiyacımız var.Lütfen sizden istenilen bilgilere doğru cevaplar veriniz. Kayıt ol diyerek bu formda verdiğiniz bilgilerin doğruluğunu beyan etmiş olursunuz. Hazırsanız sizin için bir kimlik oluşturmaya başlayalım.</p>
			</div>
			<button class="register-form-button">Hazırım</button>
		</div>
	</template>


	
	<script type="text/javascript" src="swiper/swiper-bundle.min.js"></script>
	<script type="text/javascript" src="js/jquery-3.5.1.js"></script>

	

	<!-- The core Firebase JS SDK is always required and must be listed first -->
	<script src="https://www.gstatic.com/firebasejs/8.4.1/firebase-app.js"></script>
	<script src="https://www.gstatic.com/firebasejs/8.4.1/firebase-auth.js"></script>
	<script src="https://www.gstatic.com/firebasejs/8.4.1/firebase-database.js"></script>
	<!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#available-libraries -->

    <script type="text/javascript" src="js/main.js"></script>
	<script type="text/javascript" src="js/register-form.js"></script>

</body>
</html>