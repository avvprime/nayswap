<?php 

require_once 'connect.php';

//uploading property... after this dropzone works and prop images uploads 
if (isset($_POST['upload_new_property'])) {
	
	$prop_title = htmlspecialchars($_POST['newprop_title']);
	$prop_desc = htmlspecialchars($_POST['newprop_desc']);
	$prop_price = htmlspecialchars($_POST['newprop_price']);
	$prop_swptitle = htmlspecialchars($_POST['newprop_swptitle']);
	$prop_swpdesc = htmlspecialchars($_POST['newprop_swpdesc']);
	$prop_cat = htmlspecialchars($_POST['newprop_cat']);
	$prop_subcat = htmlspecialchars($_POST['newprop_subcat']);
	$prop_city = htmlspecialchars($_POST['newprop_city']);
	$prop_district = htmlspecialchars($_POST['newprop_district']);


	$prop_cat_index = $_POST['newprop_cat_index'];
	$prop_subcat_index = $_POST['newprop_subcat_index'];
	$prop_city_index = $_POST['newprop_city_index'];
	$prop_district_index = $_POST['newprop_district_index'];

	$prop_belong_user = $_SESSION['user_id'];


	$check_prop_owner = $db->prepare("SELECT user_name, user_given_rate FROM users WHERE user_id={$prop_belong_user}");
	$check_prop_owner->execute(array());
	$write_prop_owner = $check_prop_owner->fetch(PDO::FETCH_ASSOC);

	$new_property = $db->prepare("INSERT INTO properties SET
		prop_title=:prop_title,
		prop_desc=:prop_desc,
		prop_price=:prop_price,
		prop_swaptitle=:prop_swaptitle,
		prop_swapdesc=:prop_swapdesc,
		prop_cat=:prop_cat,
		prop_subcat=:prop_subcat,
		prop_city=:prop_city,
		prop_dist=:prop_dist,
		belong_user_id=:belong_user_id,
		prop_cat_index=:prop_cat_index,
		prop_subcat_index=:prop_subcat_index,
		prop_city_index=:prop_city_index,
		prop_dist_index=:prop_dist_index,
		owner_seller_rate=:owner_seller_rate,
		owner_name=:owner_name
		");
	$newprop_uploaded = $new_property->execute(array(
		'prop_title'=>$prop_title,
		'prop_desc'=>$prop_desc,
		'prop_price'=>$prop_price,
		'prop_swaptitle'=>$prop_swptitle,
		'prop_swapdesc'=>$prop_swpdesc,
		'prop_cat'=>$prop_cat,
		'prop_subcat'=>$prop_subcat,
		'prop_city'=>$prop_city,
		'prop_dist'=>$prop_district,
		'belong_user_id'=>$prop_belong_user,
		'prop_cat_index'=>$prop_cat_index,
		'prop_subcat_index'=>$prop_subcat_index,
		'prop_city_index'=>$prop_city_index,
		'prop_dist_index'=>$prop_district_index,
		'owner_seller_rate'=>$write_prop_owner['user_given_rate'],
		'owner_name'=>$write_prop_owner['user_name']
	));

	if ($newprop_uploaded) {
		echo "uploaded";
	}else{
		echo "cantuploaded";
	}
}

//checking property is uploaded... if uploaded sending property id 
if (isset($_POST['check_newproperty'])) {
	$check_property = $db->prepare("SELECT prop_id FROM properties WHERE belong_user_id=:belong_user_id ORDER BY prop_id DESC LIMIT 1");
	$check_property->execute(array('belong_user_id'=>$_SESSION['user_id']));
	if ($pull_property=$check_property->fetch(PDO::FETCH_ASSOC)){
		echo $pull_property['prop_id'];
	}
}

/*when property and images uploaded we are inserting property cover. order to do that firstable pulling prop images after pick one and insert property cover*/
if (isset($_POST['update_propcover'])) {

	$pull_imgfor_cover = $db->prepare("SELECT propimg_way FROM property_images WHERE imgbelong_prop_id=:imgbelong_prop_id ORDER BY propimg_id ASC LIMIT 1");

	$pull_imgfor_cover->execute(array('imgbelong_prop_id'=>$_POST['newprop_id']));

	if ($pulled_img=$pull_imgfor_cover->fetch(PDO::FETCH_ASSOC)) {

		$insert_img=$db->prepare("UPDATE properties SET prop_cover=:prop_cover WHERE prop_id=:prop_id ");
		$img_inserted = $insert_img->execute(array(
			'prop_cover'=>$pulled_img['propimg_way'],
			'prop_id'=>$_POST['newprop_id']
		));

		if ($img_inserted) {
			echo "coverupdated";
		}else{
			echo "covercantupdated";
		}
	}else{
		echo $_POST['newprop_id'];
	}
}



//option subcategory list for new property form. it fires when user select a category
if (isset($_POST['pullsubcats_fornewprop'])) {
	
	$subcats = $db->prepare("SELECT * FROM subcategories WHERE uppercat_id=:uppercat_id");
	$subcats->execute(array('uppercat_id'=>$_POST['cat_data_fornewprop']));
	foreach ($subcats as $value) {
		echo "<option value='".$value['subcat_id']."'>".$value['subcat_name']."</option>";

	}
}


// City option list for new property 
if (isset($_POST['pullcitylistfornewprop'])) {
		
	$pullcity_fornewprop = $db->prepare("SELECT * FROM city");
	$pullcity_fornewprop->execute();
	foreach ($pullcity_fornewprop as $value) {
		echo "<option value='".$value['city_no']."'>".$value['city_name']."</option>";
	}
}

// district option list. it pulls when user select a city while create new property 

if (isset($_POST['pulldistricts_fornewprop'])) {
	
	$districts = $db->prepare("SELECT * FROM district WHERE belong_city_no=:belong_city_no");
	$districts->execute(array('belong_city_no'=>$_POST['city_data_fornewprop']));
	foreach ($districts as $value) {
		echo "<option value='".$value['district_no']."'>".$value['district_name']."</option>";

	}
}



 ?>