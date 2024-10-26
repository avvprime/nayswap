<?php 


require_once 'db/connect.php';
require_once 'seo-function.php';

$check_cats_for_menu=$db->prepare("SELECT * FROM categories");
$check_cats_for_menu->execute();

$category_list = array();


?>

<!DOCTYPE html>
<html lang="tr">
<head>
	<title>nayswap</title>

	<meta charset="UTF-8">
	<meta name="keywords" content="nayswap,takas,ikinciel,sahibinden,araba,ev,kitap,film,elektronik">
	<meta name="author" content="AVV">
	<link id="favicon" rel="icon" href="img/favicon-72x72.png" type="image/png" sizes="72x72">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" type="text/css" href="fontawesome/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="swiper/swiper-bundle.min.css">
	<link rel="stylesheet" type="text/css" href="sweetalert2/sweetalert2.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<div class="navbar">
		<div class="brand-name"><a href="index">nayswap</a></div>
		<div class="desktopsearchbar">
			<input type="text" placeholder="Burada ara">
			<button><i class="fas fa-search"></i></button>
		</div>
		<div class="logindiv">
			
				<a href="login"><button class="mobile-userlogo"><i class="far fa-user-circle"></i></button></a>
				<a href="login"><button class="desktop-userlogin-btn">Giriş / Kayıt</button></a>
			
			
		</div>
	</div>
	<div class="user-tools">
		<ul>
			
				<li data-title="Yeni ilan"><a href="new-property"><i class="fas fa-plus"></i></a></li>
				<li data-title="Anlaşmalarım"><a href="contacts"><i class="fas fa-handshake"></i></a></li>
				<li data-title="Beğendiklerim"><a href="myfavs"><i class="fas fa-heart"></i></a></li>
				<li data-title="Mesajlar">
					<a href="chat"><i class="fas fa-inbox"></i></a>
				</li>
				<li data-title="Hesabım"><a href="myprofile"><i class="fas fa-user-circle"></i></a></li>
			
		</ul>
		<div class="user-tools-underline"></div>
	</div>
	<div class="mobilecategories">
		<a href="mc-"><button id="mobile-categories-button">Kategoriler</button></a>
	</div>
	<div class="categories">
		<ul>
			<?php while($pull_cats_for_menu=$check_cats_for_menu->fetch(PDO::FETCH_ASSOC)){ 
				array_push($category_list, $pull_cats_for_menu['cat_name']);
				?>
			<li data-categoryindex="<?php echo $pull_cats_for_menu['cat_id']; ?>">
				<a href="<?php echo 'c-'.$pull_cats_for_menu['cat_id']; ?>"><?php echo $pull_cats_for_menu['cat_name'];?></a>
				<div class="subcat-show-container"></div>
			</li>
		<?php } ?>
		
		</ul>

	</div>
	<div class="category-showpanel-fon"></div>