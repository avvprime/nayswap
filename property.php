<?php require_once 'header.php'; 

$prop_id = $_GET['sef'];

$check_prop_infos=$db->prepare("SELECT properties.prop_title, properties.prop_desc, properties.prop_price, properties.prop_swaptitle, properties.prop_swapdesc, properties.prop_subcat_index, properties.prop_like, properties.prop_offer ,properties.prop_date, properties.belong_user_id, properties.prop_city, properties.prop_dist, properties.prop_cat, properties.prop_subcat, properties.prop_cat_index, properties.prop_subcat_index, users.user_name, users.user_surname, users.user_given, users.user_taken, users.user_given_rate, users.user_authentication,users.user_firebase_id
	FROM properties 
	INNER JOIN users ON properties.belong_user_id=users.user_id
	WHERE prop_id=:prop_id");
$check_prop_infos->execute(array('prop_id'=>$prop_id));
$pull_prop_infos=$check_prop_infos->fetch(PDO::FETCH_ASSOC);

$check_prop_images=$db->prepare("SELECT * FROM property_images WHERE imgbelong_prop_id=:imgbelong_prop_id");
$check_prop_images->execute(array('imgbelong_prop_id'=>$prop_id));
$image_list_forswiper = array();


$check_for_like = $db->prepare("SELECT like_id FROM users_likes 
	WHERE like_userid=:like_userid AND liked_propid=:like_propid");
$check_for_like->execute(array('like_userid'=>$_SESSION['user_id'],'like_propid'=>$prop_id));
$like_num = $check_for_like->rowCount();


$check_authenticated_user = $db->prepare("SELECT user_name, user_surname FROM users WHERE user_id={$_SESSION['user_id']} ");
$check_authenticated_user->execute(array());
$pull_user = $check_authenticated_user->fetch(PDO::FETCH_ASSOC);
?>

<!--  Advertisement -->
<div class="prop-page-line-advertisement"></div>



<!-- Navigation -->
<div class="breadcrumb-container">
	<ul>
		<li class="breadcrumb-item"><a href="index" class="breadcrumb-link">Anasayfa</a><i class="fas fa-chevron-right"></i></li>
		<li class="breadcrumb-item"><a href="<?php echo 'c-'.$pull_prop_infos['prop_cat_index'] ?>" class="breadcrumb-link"><?php echo $pull_prop_infos['prop_cat']; ?></a><i class="fas fa-chevron-right"></i></li>
		<li class="breadcrumb-item"><a href="<?php echo 'sc-'.$pull_prop_infos['prop_subcat_index'].'?cat='.$pull_prop_infos['prop_cat_index'] ?>" class="breadcrumb-link"><?php echo $pull_prop_infos['prop_subcat']; ?></a><i class="fas fa-chevron-right"></i></li>
	</ul>
</div>





<section class="propertypage-propshow-section">

	<div class="propshow-container">
		<div class="propshow-image-container">
			<div class="swiper-container gallery-top propshow">
				<div class="swiper-wrapper">
					<?php
					if($check_prop_images->rowCount() > 0){
						foreach ($check_prop_images as $value) {
							$image_list_forswiper[] = $value['propimg_way'];
							echo '<div class="swiper-slide" style="background-image:url('.$value['propimg_way'].')"></div>';
						}
					}

					 ?>
					
				</div>
				<!-- Add Arrows -->
				<div class="swiper-button-next swiper-button-white"></div>
				<div class="swiper-button-prev swiper-button-white"></div>
			</div>
			<div class="swiper-container gallery-thumbs propshow">
				<div class="swiper-wrapper">
					<?php
					foreach ($image_list_forswiper as  $value) {
						echo '<div class="swiper-slide" style="background-image:url('.$value.')"></div>';
					}
					 ?>
				</div>
			</div>
		</div>
	</div>

	<div class="propshow-info-container">
		<div class="propshow-info-title"><?php echo $pull_prop_infos['prop_title']; ?></div>
		<div class="propshow-info-price">
			<div class="propshow-moneyprice"><?php echo $pull_prop_infos['prop_price']." TL";?></div>
			<div class="propshow-swapprice"><?php echo $pull_prop_infos['prop_swaptitle']; ?></div>
			<div class="propshow-swapprice-description"><?php echo htmlspecialchars_decode($pull_prop_infos['prop_swapdesc']); ?></div>
		</div>
		<div class="propshow-info-propowner">
			<div class="propowner-name">
				<a href="<?php echo "user-".$pull_prop_infos['belong_user_id']; ?>"><?php echo $pull_prop_infos['user_name']." ".$pull_prop_infos['user_surname']; ?></a>
				<i class="fas fa-check-circle propshow-user-authentication"
				<?php $user_aut = $pull_prop_infos['user_authentication'];
				if($user_aut == 1){echo 'style="color: rgba(71, 183, 252,1); "';}
				else if($user_aut == 2){echo 'style="color: rgba(114, 237, 83,1); "';}
				else if($user_aut == 3){echo 'style="color: rgba(255, 215, 0,1); "';} 
				 ?>

				></i>
			</div>
			<div class="propowner-rates">
				<div class="propowner-seller-rate">
					Anlaşma Sayısı: <?php echo $pull_prop_infos['user_given']; ?>  
				</div>
				<div class="propowner-buyer-rate"> 
					Değerlendirme Notu: <button><?php echo $pull_prop_infos['user_given_rate']; ?>/10</button>
				</div>
			</div>
		</div>
		<div class="propshow-info-description">
			<span><?php echo $pull_prop_infos['prop_city']." / ".$pull_prop_infos['prop_dist']; ?></span>
			<?php 
			$propdate_year=substr($pull_prop_infos['prop_date'], 0,4);
          	$propdate_month=substr($pull_prop_infos['prop_date'], 4,4); 
          	$propdate_day=substr($pull_prop_infos['prop_date'], 8,2);
			 ?>
			<span><?php echo $propdate_day.$propdate_month.$propdate_year ?></span>
			<?php echo htmlspecialchars_decode($pull_prop_infos['prop_desc']); ?>
			<div class="propshow-interactive-info">
				<div><i class="fas fa-heart"></i> <?php echo $pull_prop_infos['prop_like']; ?></div>
			</div>
		</div>

	</div>

	
</section>
<div class="property-options">
	<ul>
		<?php if($pull_prop_infos['belong_user_id']!=$_SESSION['user_id']){?>
			<li data-title="Beğen">
				<button id="proppage-property-like-btn" data-propertyid="<?php echo $prop_id; ?>" 
					<?php 

					if ($like_num > 0) {
						echo 'style="background-color: #f23022; color: #fff;"';
					}else{
						echo 'style="background-color: #fff; color: #000;"';
					}

					?>>
					<i class="far fa-heart"></i>
				</button>
			</li>
			<li data-title="Teklif yap"><button><i class="fas fa-handshake"></i></button></li>
			<li><button id="chat-on-property-btn" data-fbaseId="<?php echo $pull_prop_infos['user_firebase_id']; ?>">Mesaj Gönder</button></li>
			<li data-title="Şikayet et"><button id="proppage-property-complaint-btn"><i class="fas fa-exclamation"></i></button></li>
		<?php }else{ ?>
			<li data-title="İlanı sil"><button><i class="fas fa-trash"></i></button></li>
			<li data-title="İlanı düzenle"><a href="property-edit?prop-no=<?php echo $prop_id; ?>"><button id="proppage-property-edit-btn"><i class="fas fa-pen"></i></button></a></li>
		<?php } ?>

	</ul>
</div>

<!-- Advertisement -->
<div class="prop-page-line-advertisement"></div>


<div class="proppage-similarproperties-title"><h4>Benzer ilanlar</h4></div>
<section class="property-section">
	<div class="property-container">
		<?php 
			$like_list = array();
			$pull_likelist = $db->prepare("SELECT liked_propid FROM users_likes WHERE like_userid={$_SESSION['user_id']}");
			$pull_likelist->execute(array());
			if ($pull_likelist->rowCount() > 0) {
				foreach ($pull_likelist as $value) {
					$like_list[] = $value['liked_propid'];
				}
			}
			$pull_propfor_index = $db->prepare("SELECT * FROM properties 
				WHERE prop_subcat=:prop_subcat
				ORDER BY prop_id DESC LIMIT 5");
			$pull_propfor_index->execute(array('prop_subcat'=>$pull_prop_infos['prop_subcat']));
			if ($pull_propfor_index->rowCount() > 0) {
				foreach ($pull_propfor_index as $value) {

					if ($value['prop_id'] != $prop_id) {

						if (false !== in_array(trim($value['prop_id']), $like_list)) {
							echo "
							<div class='property-box'>
								<button class='property-likebtn liked' data-propertyid='".$value['prop_id']."'><i class='far fa-heart'></i></button>
								<a href='prop-".$value['prop_id']."?urun=".seo($value['prop_title'])."'>
									<div class='property-image'><img src='".$value['prop_cover']."'></div>
									<div class='property-info'>
										<div class='property-title'>".$value['prop_title']."</div>
										<div class='property-seller'>".$value['owner_name']."</div>
										<div class='property-seller-rates'><span>".$value['owner_seller_rate']."/10</span><i class='far fa-dot-circle'></i><span>".$value['prop_city']."-".$value['prop_dist']."</span></div>
										<div class='property-price'>
											<div class='property-moneyprice'>".$value['prop_price']." TL</div>
										</div>
									</div>
								</a>
								<div class='property-swaprice'><button>".$value['prop_swaptitle']."</button></div>
								<div class='property-swapdesc-hover'>
									<p>
										".$value['prop_swapdesc']."
									</p>
								</div>
							</div>";
						}else{
							echo "
							<div class='property-box'>
								<button  class='property-likebtn' data-propertyid='".$value['prop_id']."'><i class='far fa-heart'></i></button>
								<a href='prop-".$value['prop_id']."?urun=".seo($value['prop_title'])."'>
									<div class='property-image'><img src='".$value['prop_cover']."'></div>
									<div class='property-info'>
										<div class='property-title'>".$value['prop_title']."</div>
										<div class='property-seller'>".$value['owner_name']."</div>
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
					
				}
			}else{
				echo "Benzer ilanlar bulunamadı.";
			}
				

			?>

	</div>
</section>	

<input type="hidden" id="complaint_from_user" value="<?php echo $_SESSION['user_id']; ?>">
<input type="hidden" id="complaint_to_user" value="<?php echo $pull_prop_infos['belong_user_id']; ?>">
<input type="hidden" id="complaint_property" value="<?php echo $_GET['sef']; ?>">
<input type="hidden" id="complaint_from_user_name" value="<?php echo $pull_user['user_name'].' '.$pull_user['user_surname']; ?>">
<input type="hidden" id="complaint_to_user_name" value="<?php echo $pull_prop_infos['user_name'].' '.$pull_prop_infos['user_surname']; ?>">

<?php require_once 'footer.php'; ?>


<script type="text/javascript" src="swiper/swiper-bundle.min.js"></script>

<script>
	
	var galleryThumbs = new Swiper('.gallery-thumbs', {
		spaceBetween: 10,
		slidesPerView: 4,
		freeMode: true,
		watchSlidesVisibility: true,
		watchSlidesProgress: true,
	});
	var galleryTop = new Swiper('.gallery-top', {
		spaceBetween: 10,
		navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
		thumbs: {
			swiper: galleryThumbs
		}
	});

	
</script>