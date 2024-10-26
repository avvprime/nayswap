<?php require_once 'header.php';

$cat_name = $category_list[$_GET['cat']-1];

$check_subcat_name = $db->prepare("SELECT subcat_name FROM subcategories WHERE subcat_id=:subcat_id");
$check_subcat_name->execute(array('subcat_id'=>$_GET['sef']));
$pull_subcat_name = $check_subcat_name->fetch(PDO::FETCH_ASSOC);

$check_for_props = $db->prepare("SELECT * FROM properties WHERE prop_subcat_index=:prop_subcat_index ORDER BY prop_id ASC");
$check_for_props->execute(array('prop_subcat_index'=>$_GET['sef']));

$numberof_rows = $check_for_props->rowCount();
?>

<!-- Advertisement -->
<div class="prop-page-line-advertisement"></div>


<div class="breadcrumb-container">
	<ul>
		<li class="breadcrumb-item">
			<a href="index" class="breadcrumb-link">Anasayfa</a><i class="fas fa-chevron-right"></i>
		</li>
		<li class="breadcrumb-item">
			<a href="<?php echo 'c-'.$_GET['cat']; ?>" class="breadcrumb-link"><?php echo $cat_name ?></a><i class="fas fa-chevron-right"></i>
		</li>
		<li class="breadcrumb-item">
			<a href="<?php echo 'sc-'.$_GET['sef'].'?cat='.$_GET['cat']; ?>" class="breadcrumb-link"><?php echo $pull_subcat_name['subcat_name']; ?></a><i class="fas fa-chevron-right"></i>
		</li>
	</ul>
</div>

<input type="hidden" id="subcat-page-subcategory-data" value="<?php echo $_GET['sef']; ?>">


<div class="cat-page-bigtitle">
	<h1><?php echo $pull_subcat_name['subcat_name']; ?></h1><span><?php echo $numberof_rows; ?> sonuç bulundu.</span>
</div>

<?php 

$pull_city_for_subfilter = $db->prepare("SELECT * FROM city");
$pull_city_for_subfilter->execute();


?>

<div class="subcat-page-filter-container">
	<div class="filter-item">
		<select id="subfilter-city-select">
			<option value="">Tüm iller</option>
			<?php if ($pull_city_for_subfilter->rowCount() > 0){
				foreach ($pull_city_for_subfilter as $key) { ?>
				<option value="<?php echo $key['city_no']; ?>"><?php echo $key['city_name']; ?></option>	
			<?php
				}
			} ?>
		</select>
	</div>
	<div class="filter-item">
		<span>'de/da</span>
	</div>
	<div class="filter-item">
		<input type="text" id="subfilter-text-input" placeholder="elimdeki ürün" spellcheck = "false"
>
	</div>
	<div class="filter-item">
		<span>ile</span><button id="subcat-page-search-btn">takas</button>
	</div>
		
</div>

<section class="property-section">
	<div class="property-container" id="subcategory-property-container">
		<?php while($pull_props=$check_for_props->fetch(PDO::FETCH_ASSOC)){
		echo "
						<div class='property-box'>
							<button  class='property-likebtn' data-propertyid='".$pull_props['prop_id']."'><i class='far fa-heart'></i></button>
							<a href='prop-".$pull_props['prop_id']."?urun=".seo($pull_props['prop_title'])."'>
								<div class='property-image'><img src='".$pull_props['prop_cover']."'></div>
								<div class='property-info'>
									<div class='property-title'>".$pull_props['prop_title']."</div>
									<div class='property-seller'>".$pull_props['owner_name']."</div>
									<div class='property-seller-rates'><span>".$pull_props['owner_seller_rate']."/10</span> <i class='far fa-dot-circle'></i><span>".$pull_props['prop_city']."-".$pull_props['prop_dist']."</span></div>
									<div class='property-price'>
										<div class='property-moneyprice'>".$pull_props['prop_price']." TL</div>
									</div>
								</div>
							</a>
							<div class='property-swaprice'><button>".$pull_props['prop_swaptitle']."</button></div>
							<div class='property-swapdesc-hover'>
								<p>
									".htmlspecialchars_decode($pull_props['prop_swapdesc'])."
								</p>
							</div>
						</div>";
					
						
		}
		?>
	</div>
</section>
<div class="property-pagination-container"></div>














<?php require_once 'footer.php'; ?>

<script>
	$(document).ready(function(){

		
		var subcategory = $('#subcat-page-subcategory-data').val();

		function load_data(page,subcategory,city,query = ''){
			$.ajax({
				url:'db/subcategory-filter-process.php',
				type:'POST',
				dataType:'json',
				data:{city:city,query:query,subcategory:subcategory,page:page},
				success: function(response){
					
					var properties = response.properties;
					var prop_num = response.prop_num;
					var page_data = response.pagination;
					
					$('#subcategory-property-container').html(' ');
					$('.cat-page-bigtitle span').html(prop_num+" sonuç bulundu");
					
					for (var i = 0; i < properties.length; i++){
						$('#subcategory-property-container').append(properties[i]);
					}

					$('.property-pagination-container').html(page_data);
				}
			});
		}

		load_data(1,subcategory);


		$('#subcat-page-search-btn').on('click', function(){

			var query = $('#subfilter-text-input').val();
			var city = $('#subfilter-city-select').val();

			load_data(1,subcategory,city,query);
		});


		$(document).on('click', '.page-link', function(){

			var page = $(this).attr('data-pagenumber');
			var query = $('#subfilter-text-input').val();
			var city = $('#subfilter-city-select').val();

			load_data(page,subcategory,city,query);

		});
	});
</script>