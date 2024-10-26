<?php 
ob_start();
session_start();

 ?>
<!DOCTYPE html>
<html>
<head>
	<title>nayswap</title>

	<meta charset="UTF-8">
	<meta name="keywords" content="nayswap,takas,ikinciel,sahibinden,araba,ev,kitap,film,elektronik">
	<meta name="author" content="AVV">
	<link id="favicon" rel="icon" href="img/favicon-72x72.png" type="image/png" sizes="72x72">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" type="text/css" href="fontawesome/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="swiper/swiper-bundle.min.css">
	<link rel="stylesheet" type="text/css" href="dropzone/dropzone.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<style type="text/css">
		html,body{
			height: 100%;
			color: #000;
			background-color: #fff;
		}
		
	</style>
</head>
<body>
	<div class="swiper-container new-property">
		<div class="swiper-wrapper">

		</div>
	</div>	

	<template id="new-property-template">
		<div class="swiper-slide">
			<div class="form-container">
				<fieldset>
					<legend>{title}</legend>
					{field}
				</fieldset>
				<button class="new-property-form-button">Tamam</button>
			</div>
			<div id="new-property-alert"></div>
		</div>
	</template>

	<template id="new-property-end-template">
		<div class="swiper-slide">
			<h1>İlanınız hazır.</h1>
			
			
			
			<div id="new-property-end-loginbtn"><a href="index"><button>Devam</button></a></div>
		</div>
	</template>

	<template id="new-property-start-template">
		<div class="swiper-slide" id="new-property-first-slide">
			<a href="index" class="backtologin-link"><button>Vazgeç</button></a>
			<div class="maindiv-inslide">

				<h1>Yeni ilanınız için ürün fotoğraflarıyla başlayalım</h1>
				<div class="newprop-imagecontainer">
					<div id="newprop_dropzone" class="dropzone">
						
					</div>
				</div>

			</div>
			<button class="new-property-form-button">Oluştur</button>
		</div>
	</template>

	<?php if (isset($_SESSION['user_id'])){ echo "<input id='user_iddata' type='hidden' value='".$_SESSION['user_id']."'/> ";} ?>


	
	<script type="text/javascript" src="js/jquery-3.5.1.js"></script>
	<script type="text/javascript" src="js/currency.min.js"></script>
	<script type="text/javascript" src="swiper/swiper-bundle.min.js"></script>
	<script type="text/javascript" src="dropzone/dropzone.js"></script>
	<script type="text/javascript" src="js/new-property-form.js"></script>
	
	
</body>
</html>