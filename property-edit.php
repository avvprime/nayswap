<?php 

require_once 'header.php';



$check_prop_for_edit=$db->prepare("SELECT * FROM properties WHERE prop_id=:prop_id AND belong_user_id=:belong_user_id");
$check_prop_for_edit->execute(array('prop_id'=>$_GET['prop-no'],'belong_user_id'=>$_SESSION['user_id']));
if ($pull_prop_for_edit=$check_prop_for_edit->fetch(PDO::FETCH_ASSOC)) {?>
	<input type="hidden" id="propidfor_propedit" value="<?php echo $pull_prop_for_edit['prop_id']; ?>">
	<input type="hidden" id="propcover_for_propedit" value="<?php echo $pull_prop_for_edit['prop_cover']; ?>">
<?php 
}else{
	header("Location:index");
	exit;
}
?>

<!-- Advertisement -->
<div class="prop-page-line-advertisement"></div>
<!-- Advertisement -->


<div class="breadcrumb-container">
	<ul>
		<li class="breadcrumb-item"><a href="index" class="breadcrumb-link">Anasayfa</a><i class="fas fa-chevron-right"></i></li>
		<li class="breadcrumb-item"><a href="myfavs" class="breadcrumb-link">İlan düzenleme</a><i class="fas fa-chevron-right"></i></li>
	</ul>
</div>



<script src="https://cdn.ckeditor.com/4.16.0/basic/ckeditor.js"></script>

<div class="propedit-top-title"><h1>İlan düzenleme</h1></div>


<div class="property-edit-list-container">
	<div class="property-edit-list-item">
		<label for="property-title-edit">İlan başlığı(İlanın içeriği ile ilgili bilgi)</label>
		<p id="prop-title-show"><?php echo $pull_prop_for_edit['prop_title']; ?></p>
		<input type="text" id="property-title-edit" maxlength="50" placeholder="Buraya yaz">
		<button id="property-title-edit-btn">Değiştir</button>
	</div>
	<div class="property-edit-list-item">
		<label for="property-description-edit">İlan açıklaması(Ürün bilgileri)</label>
		<textarea id="property-description-edit" name="property-description-edit" maxlength="450" placeholder="Buraya yaz">
			<?php echo $pull_prop_for_edit['prop_desc']; ?>
		</textarea>
		<button id="property-description-edit-btn">Değiştir</button>
		<script>
			CKEDITOR.replace( 'property-description-edit', {
				removePlugins: 'link'
			});
		</script>
	</div>
	<div class="property-edit-list-item">
		<label for="property-swaptitle-edit">Takas başlığı(Takas etmek istediğiniz ürün,hizmet...)</label>
		<p id="prop-swaptitle-show"><?php echo $pull_prop_for_edit['prop_swaptitle']; ?></p>
		<input type="text" id="property-swaptitle-edit" maxlength="35" placeholder="Buraya yaz">
		<button id="property-swaptitle-edit-btn">Değiştir</button>
	</div>
	<div class="property-edit-list-item">
		<label for="property-swapdesc-edit">Takas açıklaması(Takas için beklentileriniz vb.)</label>

		<textarea id="property-swapdesc-edit" maxlength="200" placeholder="Buraya yaz">
			<?php echo $pull_prop_for_edit['prop_swapdesc']; ?>
		</textarea>
		<script>
			CKEDITOR.replace( 'property-swapdesc-edit', {
				removePlugins: 'link'
			});
		</script>
		<button id="property-swapdesc-edit-btn">Değiştir</button>
	</div>
	<div class="property-edit-list-item">
		<label for="property-price-edit">Ürün fiyatı</label>
		<p id="prop-price-show"><?php echo $pull_prop_for_edit['prop_price']; ?> TL</p>
		<input type="number" id="property-price-edit"  placeholder="Buraya yaz">
		<button id="property-price-edit-btn">Değiştir</button>
	</div>
	<div class="property-edit-list-item cover-edit-item">
		<label>Kapak fotoğrafı</label>
		<div><img id="prop-cover-edit-image" src="<?php echo $pull_prop_for_edit['prop_cover']; ?>"></div>
	</div>
	<div class="property-edit-list-item image-delete-item">
		<label>Ürün resimleri </label>
		<ul id="propedit-image-list">
			<?php 
			
			$check_images_for_edit=$db->prepare("SELECT * FROM property_images WHERE imgbelong_prop_id=:imgbelong_prop_id");
			$check_images_for_edit->execute(array('imgbelong_prop_id'=>$pull_prop_for_edit['prop_id']));
			while($pull_images_for_edit=$check_images_for_edit->fetch(PDO::FETCH_ASSOC)){
				
				?>
		  <li>
		  	<input type="checkbox" name="prop-edit-imageedit" data-imageway="<?php echo $pull_images_for_edit['propimg_way']; ?>" id="pi<?php echo $pull_images_for_edit['propimg_id'];?>" />
		    <label for="pi<?php echo $pull_images_for_edit['propimg_id'];?>">
		    	<img src="<?php echo $pull_images_for_edit['propimg_way']; ?>" />
		    </label>
		  </li>
			<?php } ?>
		</ul>
		<button id="property-photo-edit-btn">Seçilenleri sil</button>
		<button id="property-cover-edit-btn">Kapak fotoğrafı olarak ayarla</button>
	</div>
	
</div>
<div class="property-edit-bottom-div"><a href="index">Bitir ve anasayfaya dön</a></div>



<?php

require_once 'footer.php';

?>
