<?php 
require_once 'header.php';
?>

<!-- Advertisement -->
<div class="prop-page-line-advertisement"></div>


<div class="breadcrumb-container">
	<ul>
		<li class="breadcrumb-item"><a href="index" class="breadcrumb-link">Anasayfa</a><i class="fas fa-chevron-right"></i></li>
		<li class="breadcrumb-item"><a href="myfavs" class="breadcrumb-link">Beğendiklerim</a><i class="fas fa-chevron-right"></i></li>
	</ul>
</div>





<div class="myfavs-toptitle"><h4>Beğendiklerim</h4></div>
<section class="property-section">
		<div class="property-container">
			<?php 
			$pull_propfor_index = $db->prepare("SELECT * FROM properties 
				WHERE EXISTS( SELECT 1 FROM users_likes WHERE liked_propid=prop_id AND like_userid={$_SESSION['user_id']}) 
				ORDER BY prop_id DESC LIMIT 15");
			$pull_propfor_index->execute();
			if ($pull_propfor_index->rowCount() > 0) {
				foreach ($pull_propfor_index as $value) { 
					echo "
					<div class='property-box'>
							<button  class='property-likebtn liked' data-propertyid='".$value['prop_id']."'><i class='far fa-heart'></i></button>
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
			}else{
				echo "Henüz bir ilan beğenmediniz";
			}
			?>
	</div>
</section>
<?php
require_once 'footer.php';
 ?>