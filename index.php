<?php require_once 'header.php';

require_once 'seo-function.php';

?>
<div class="indexpage-top-title">
	<h1>hoşgeldin,</h1>
	<h2><strong>Takas</strong> yapmak için ihtiyacın olan herkes ve her şey için doğru adres.</h2>
</div>

	<section class="property-section">
		<div class="property-container">
			<?php 
			$like_list = array();
			$pull_likelist = $db->prepare("SELECT liked_propid FROM users_likes WHERE like_userid=:like_userid");
			$pull_likelist->execute(array('like_userid'=>$_SESSION['user_id']));
			if ($pull_likelist->rowCount() > 0) {
				foreach ($pull_likelist as $value) {
					array_push($like_list, $value['liked_propid']);	
				}

			}
			$pull_propfor_index = $db->prepare("SELECT * FROM properties ORDER BY prop_id DESC LIMIT 12");
			$pull_propfor_index->execute();
			if ($pull_propfor_index->rowCount() > 0) {
				foreach ($pull_propfor_index as $value) {
					if (false !== in_array(trim($value['prop_id']), $like_list)) {
						echo "
						<div class='property-box'>
							<button class='property-likebtn liked' data-propertyid='".$value['prop_id']."'><i class='far fa-heart'></i></button>
							<a href='prop-".$value['prop_id']."?urun=".seo($value['prop_title'])."'>
								<div class='property-image'><img src='".$value['prop_cover']."'></div>
								<div class='property-info'>
									<div class='property-title'>".$value['prop_title']."</div>
									<div class='property-seller'>".$value['owner_name']."</div>
									<div class='property-seller-rates'><span>".$value['owner_seller_rate']."/10</span> <i class='far fa-dot-circle'></i><span>".$value['prop_city']."-".$value['prop_dist']."</span></div>
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
					}else{
						echo "
						<div class='property-box'>
							<button  class='property-likebtn' data-propertyid='".$value['prop_id']."'><i class='far fa-heart'></i></button>
							<a href='prop-".$value['prop_id']."?urun=".seo($value['prop_title'])."'>
								<div class='property-image'><img src='".$value['prop_cover']."'></div>
								<div class='property-info'>
									<div class='property-title'>".$value['prop_title']."</div>
									<div class='property-seller'>".$value['owner_name']."</div>
									<div class='property-seller-rates'><span>".$value['owner_seller_rate']."/10</span> <i class='far fa-dot-circle'></i><span>".$value['prop_city']."-".$value['prop_dist']."</span></div>
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


			?>
			

			

		</div>
	</section>
	

	
<?php require_once 'footer.php'; ?>

