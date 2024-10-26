<?php 

require_once 'db/connect.php';

require_once 'seo-function.php';

$check_user_data = $db->prepare("SELECT * FROM users WHERE user_id={$_SESSION['user_id']}");
$check_user_data->execute(array());
$pull_user_data = $check_user_data->fetch(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="tr">
<head>
	<title>Hesabım - nayswap</title>
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
		body{
			position: relative;
		}
		.property-container{
			background-color: #fff;
			padding: 10px;
			border-radius: 20px;
		}
		
		
	</style>
</head>
<body>
	<div class="profile-backtohome">
		<a href="index"><i class="fas fa-chevron-left"></i> Ana sayfa</a>
		<div class="profile-topcontent">
			<button class="profile-open-options"><i class="fas fa-ellipsis-v"></i></button>
		</div>
	</div>
	<div class="profile-sideoptions-fon">
	</div>
	<div class="profile-sideoptions-list">
		<div class="profile-edit-list">
			<h5>Hesap Görünümü</h5>
			<ul>
				<li><a href="editprofile-photo?user=<?php echo $pull_user_data['user_id']; ?>">Hesap fotoğrafı</a></li>
				<li><a href="editprofile-about?user=<?php echo $pull_user_data['user_id']; ?>">Hakkımda</a></li>
			</ul>
			<h5>Hesap Ayarları</h5>
			<ul>
				<li>
					<a href="editprofile-email?user=<?php echo $pull_user_data['user_id']; ?>">E-posta doğrulaması</a>
				</li>
				<li>
					<a href="editprofile-password?user=<?php echo $pull_user_data['user_id']; ?>">Şifre değiştir</a>
				</li>
				<li>
					<a href="editprofile-account?user=<?php echo $pull_user_data['user_id']; ?>">Hesabımı sil</a>
				</li>
			</ul>
		</div>
	</div>

	<div class="profile-topdiv">
		<div class="identity-container" data-tilt>
			<div class="id-topdiv">
				<div class="id-img"><img src="<?php echo $pull_user_data['user_photo']; ?>"></div>
				<div class="id-brandname">nayswap</div>
			</div>
			<div class="id-bottomdiv">
				<h4><?php echo $pull_user_data['user_name']." ".$pull_user_data['user_surname']; ?></h4>
				<div class="id-infos">
					<div class="id-info-date">
						<h6>Alınan</h6>
						<h5><?php echo $pull_user_data['user_taken']; ?></h5>
					</div>
					<div class="id-info-dealnum">
						<h6>Verilen</h6>
						<h5><?php echo $pull_user_data['user_given']; ?></h5>
					</div>
					<div class="id-info-rate">
						<h6>Değerlendirme Notu</h6>
						<h5><?php echo $pull_user_data['user_given_rate']."/10"; ?></h5>
					</div>
				</div>
				<div class="id-authentication">
					<?php 
					if ($pull_user_data['user_authentication'] == 0)
					{
						echo '<div class="authentication-bar"></div>';
					}
					else if($pull_user_data['user_authentication'] == 1)
					{
						echo '<div class="authentication-bar one-level-authenticated"></div>';
					}
					else if($pull_user_data['user_authentication'] == 2)
					{
						echo '<div class="authentication-bar two-level-authenticated"></div>';
					}
					else if($pull_user_data['user_authentication'] == 3)
					{
						echo '<div class="authentication-bar three-level-authenticated"></div>';
					}


					 ?>
				</div>
			</div>
		</div>
	</div>
	<div class="profile-info"><span class="profile-info">Kullanıcı bilgilerini görmek için kaydırın.</span></div>
	<!-- Swiper -->
	<div class="swiper-container profile-about">
		<div class="swiper-wrapper">
			<div class="swiper-slide">
				<h5>Hakkımda</h5>
				<i class="fas fa-chevron-right right-arrow"></i>
				<p><?php echo $pull_user_data['user_about']; ?></p>
			</div>
	
			<div class="swiper-slide">
				<h5>Verdiklerim</h5>
				<i class="fas fa-chevron-left left-arrow"></i>
				<div class="myprofile-myshops-container">
					<div class="myshops-item">
						<img src="img/property-photos/example.jpg"><button>Tafsilatı</button>
					</div>
					<div class="myshops-item">
						<img src="img/property-photos/example.jpg"><button>Tafsilatı</button>
					</div>
					<div class="myshops-item">
						<img src="img/property-photos/example.jpg"><button>Tafsilatı</button>
					</div>
					<div class="myshops-item">
						<img src="img/property-photos/example.jpg"><button>Tafsilatı</button>
					</div>
					<div class="myshops-item">
						<img src="img/property-photos/example.jpg"><button>Tafsilatı</button>
					</div>
					<div class="myshops-item">
						<img src="img/property-photos/example.jpg"><button>Tafsilatı</button>
					</div>
					<div class="myshops-item">
						<img src="img/property-photos/example.jpg"><button>Tafsilatı</button>
					</div>
					<div class="myshops-item">
						<img src="img/property-photos/example.jpg"><button>Tafsilatı</button>
					</div>
					<div class="myshops-item">
						<img src="img/property-photos/example.jpg"><button>Tafsilatı</button>
					</div>
					<div class="myshops-item">
						<img src="img/property-photos/example.jpg"><button>Tafsilatı</button>
					</div>
					<div class="myshops-item">
						<img src="img/property-photos/example.jpg"><button>Tafsilatı</button>
					</div>
					<div class="myshops-item">
						<img src="img/property-photos/example.jpg"><button>Tafsilatı</button>
					</div>
					<div class="myshops-item">
						<img src="img/property-photos/example.jpg"><button>Tafsilatı</button>
					</div>
				</div>
			</div>
		</div>
		<!-- Add Pagination -->
		<div class="swiper-pagination"></div>
	</div>

	<!-- User's shops details  -->
	<div class="myshops-about">
		<div class="giver-about">
			<div class="giver-about-top">
				<h4>Eşref Bey Tasarım</h4><span>5/10</span>
			</div>
			<div class="giver-about-content">
				<p><i class="fas fa-quote-left"></i> 
				Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
				tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
				quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
				consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
				<i class="fas fa-quote-right"></i> </p>
			</div>
		</div>
		<div class="shop-details">
			<div class="shop-handshake-div"><i class="far fa-handshake"></i></div>
			<div class="shop-date-div"><span>20.05.2021</span></div>
		</div>
		<div class="taker-about">
			<div class="taker-about-top">
				<h4>Yavuz Ali</h4><span>6/10</span>
			</div>
			<div class="giver-about-content">
				<p><i class="fas fa-quote-left"></i> 
				Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
				tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
				quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
				consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
				<i class="fas fa-quote-right"></i> </p>
			</div>
		</div>
	</div>
	<div class="profile-users-properties"><h3>Kullanıcının ilanları</h3></div>
	<section class="property-section">
		<div class="property-container">
			<?php 
			$pull_propfor_index = $db->prepare("SELECT * FROM properties 
				WHERE belong_user_id={$_SESSION['user_id']}
				ORDER BY prop_id DESC ");
			$pull_propfor_index->execute();
			if ($pull_propfor_index->rowCount() > 0) {
				foreach ($pull_propfor_index as $value) { 
					echo "
					<div class='property-box'>
							<button  class='property-likebtn' data-propertyid='".$value['prop_id']."'><i class='far fa-heart'></i></button>
							<a href='prop-".$value['prop_id']."?urun=".seo($value['prop_title'])."'>
								<div class='property-image'><img src='".$value['prop_cover']."'></div>
								<div class='property-info'>
									<div class='property-title'>".$value['prop_title']."</div>
									<div class='property-seller'>".$value['owner_name']." ".$value['user_surname']."</div>
									<div class='property-seller-rates'><span>".$value['owner_seller_rate']."/10</span><i class='far fa-dot-circle'></i><span>".$value['prop_city']."-".$value['prop_dist']."</span></div>
									<div class='property-price'>
										<div class='property-moneyprice'>".$value['prop_price']." TL</div>
									</div>
								</div>
							</a>
							<div class='property-swaprice'><button>".$value['prop_swaptitle']."</button></div>
							<div class='property-swapdesc-hover'>
								<p>
									".htmlspecialchars_decode($value['prop_swapdesc'])."
								</p>
							</div>
						</div>";
				}
			}


			?>
		</div>
	</section>

	<script src="https://www.gstatic.com/firebasejs/8.4.1/firebase-app.js"></script>
	<script src="https://www.gstatic.com/firebasejs/8.4.1/firebase-auth.js"></script>
	<script src="https://www.gstatic.com/firebasejs/8.4.1/firebase-database.js"></script>

	<script type="text/javascript" src="js/jquery-3.5.1.js"></script>
	<script type="text/javascript" src="js/tilt.jquery.js"></script>
	<script type="text/javascript" src="swiper/swiper-bundle.min.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
	<script>
		var swiper = new Swiper('.swiper-container', {
			effect: 'coverflow',
			grabCursor: true,
			centeredSlides: true,
			slidesPerView: 'auto',
			coverflowEffect: {
				rotate: 50,
				stretch: 0,
				depth: 100,
				modifier: 1,
				slideShadows: true,
			},
			pagination: {
				el: '.swiper-pagination',
			},
		});
		console.log("background by SVGBackgrounds.com");
	</script>
</body>
</html>