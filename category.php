<?php require_once 'header.php'; 

$category_no = $_GET['sef'];
$cat_name = $category_list[$category_no-1];



$pull_subcat_data = $db->prepare("SELECT * FROM subcategories WHERE uppercat_id={$category_no} ");
$pull_subcat_data->execute(array());
foreach ($pull_subcat_data as $value) {
	$subcategory_list[$value['subcat_name']] = $value['subcat_id'];
}
?>

<section class="cat-page-main-section">
	
	<div class="breadcrumb-container">
		<ul>
			<li class="breadcrumb-item"><a href="index" class="breadcrumb-link">Anasayfa</a><i class="fas fa-chevron-right"></i></li>
			<li class="breadcrumb-item"><a href="<?php echo 'c-'.$category_no; ?>" class="breadcrumb-link"><?php echo $cat_name; ?></a><i class="fas fa-chevron-right"></i></li>
		</ul>
	</div>


	<div class="cat-page-bigtitle">
		<h1><?php echo $cat_name; ?></h1>
	</div>

	<div class="subcategory-container">
		<?php foreach($subcategory_list as $subcat => $subcat_value){ ?>
			<div class="subcategory-item">
				<a href="<?php echo 'sc-'.$subcat_value.'?cat='.$category_no; ?>"><?php echo $subcat; ?></a>
			</div>
		<?php } ?>
	</div>
</section>
















<?php require_once 'footer.php'; ?>