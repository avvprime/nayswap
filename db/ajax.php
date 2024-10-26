<?php  

require_once 'connect.php';


//user block remove from profile
if (isset($_POST['removeBlock'])) {
	$uid = $_POST['uid'];
	$remove_block = $db->prepare("DELETE FROM users_blocks WHERE blocking_user=:blocking_user AND blocked_user=:blocked_user");
	$remove_block->execute(array(
		'blocking_user'=>$_SESSION['user_id'],
		'blocked_user'=>$uid
	));
	if ($remove_block) {
		echo "block_removed";
	}
	else
	{
		echo "block_not_removed";
	}
}

// user add block from profile 
if (isset($_POST['addBlock'])) {
	$uid = $_POST['uid'];
	if ($uid != $_SESSION['user_id']) {
		$addBlock = $db->prepare("INSERT INTO users_blocks SET 
			blocking_user=:blocking_user,
			blocked_user=:blocked_user
			");
		$addBlock->execute(array(
			'blocking_user'=>$_SESSION['user_id'],
			'blocked_user'=>$uid
		));
		if ($addBlock) {
			echo "block_added";
		}
		else
		{
			echo "block_not_added";
		}
	}
	else
	{
		echo "same_user";
	}
}



//user is block control before send first message
if (isset($_POST['chatStatus'])) {
	
	$messageFrom = $_POST['message_from'];
	$messageTo = $_POST['message_to'];

	$checkBlock = $db->prepare("SELECT block_id 
		FROM users_blocks 
		WHERE blocking_user={$messageFrom} AND blocked_user={$messageTo} 
		OR  blocking_user={$messageTo} AND blocked_user={$messageFrom}");

	$checkBlock->execute(array());

	if ($checkBlock->rowCount() > 0) {
		echo "userblock";
	}


}


//User complaint from property
if (isset($_POST['complaint_prop'])) {
	
	$add_complaint = $db->prepare("INSERT INTO complaints_via_properties SET
		complaint_from=:complaint_from,
		complaint_to=:complaint_to,
		complaint_property=:complaint_property,
		complaint_text=:complaint_text
		");

	$add_complaint->execute(array(
		'complaint_from' => $_POST['complaint_from'],
		'complaint_to' => $_POST['complaint_to'],
		'complaint_property' => $_POST['complaint_prop'],
		'complaint_text' => htmlspecialchars($_POST['complaint_text'])
	));

	if ($add_complaint)
	{
		echo "complaint_delivered";
	}
	else
	{
		echo "complaint_not_delivered";
	}

}

//User complaint from profile
if (isset($_POST['complaint_profile'])) {
	
	$add_complaint = $db->prepare("INSERT INTO complaints_via_profiles SET
		complaint_from=:complaint_from,
		complaint_to=:complaint_to,
		complaint_text=:complaint_text
		");

	$add_complaint->execute(array(
		'complaint_from' => $_SESSION['user_id'],
		'complaint_to' => $_POST['uid'],
		'complaint_text' => htmlspecialchars($_POST['complaint_text'])
	));

	if ($add_complaint)
	{
		echo "complaint_delivered";
	}
	else
	{
		echo "complaint_not_delivered";
	}

}





//header show subcategories
if (isset($_POST['showcat_onhover'])) {
	$check_subcats = $db->prepare("SELECT * FROM subcategories WHERE uppercat_id=:uppercat_id");
	$check_subcats->execute(array('uppercat_id'=>$_POST['category_data']));
	foreach ($check_subcats as $value) {
		echo "<li><a href='sc-".$value['subcat_id']."?cat=".$_POST['category_data']."'>".$value['subcat_name']."</a></li>";
	}
}



//property edit process
if (isset($_POST['property_title_data'])) {

	$updatetitle_data = htmlspecialchars($_POST['property_title_data']);

	$updatetitle_property =$db->prepare("UPDATE properties SET prop_title=:prop_title WHERE prop_id=:prop_id");
	$updatetitle_property->execute(array(
		'prop_title'=>$updatetitle_data,
		'prop_id'=>$_POST['property_id']
	));
	if ($updatetitle_property) {
		echo "proptitleupdated";
	}else{
		echo "proptitlenotupdated";
	}
}

if (isset($_POST['prop_desc_data'])) {
	
	$update_data = htmlspecialchars($_POST['prop_desc_data']);

	$update_property=$db->prepare("UPDATE properties SET prop_desc=:prop_desc WHERE prop_id=:prop_id");
	$update_property->execute(array('prop_desc'=>$update_data,'prop_id'=>$_POST['property_id']));
	if ($update_property) {
		echo "propdescupdated";
	}else{
		echo "propdescnotupdated";
	}
}

if (isset($_POST['prop_swaptitle_data'])) {

	$update_data = htmlspecialchars($_POST['prop_swaptitle_data']);	

	$update_property = $db->prepare("UPDATE properties SET prop_swaptitle=:prop_swaptitle WHERE prop_id=:prop_id");
	$update_property->execute(array('prop_swaptitle'=>$update_data,'prop_id'=>$_POST['property_id']));
	if ($update_property) {
		echo "propswaptitleupdated";
	}else{
		echo "propswaptitlenotupdated";
	}
}

if (isset($_POST['prop_swapdesc_data'])) {
	$update_data = htmlspecialchars($_POST['prop_swapdesc_data']);
	$update_property = $db->prepare("UPDATE properties SET prop_swapdesc=:prop_swapdesc WHERE prop_id=:prop_id");
	$update_property->execute(array('prop_swapdesc'=>$update_data,'prop_id'=>$_POST['property_id']));
	if ($update_property) {
		echo "propswapdescupdated";
	}else{
		echo "propswapdescnotupdated";
	}
}

if (isset($_POST['prop_price_data'])) {

	$update_data = htmlspecialchars($_POST['prop_price_data']);

	$update_property = $db->prepare("UPDATE properties SET prop_price=:prop_price WHERE prop_id=:prop_id");
	$update_property->execute(array('prop_price'=>$update_data,'prop_id'=>$_POST['property_id']));
	if ($update_property) {
		echo "proppriceupdated";
	}else{
		echo "proppricenotupdated";
	}

}

if (isset($_POST['delete_images_list'])) {

	$image_array = $_POST['delete_images_list'];
	
	$image_list_check = join(',', $image_array);

	$check_img = $db->prepare("SELECT * FROM property_images WHERE propimg_id IN({$image_list_check})");
	$check_img->execute(array());
	while($pull_img_for_delete=$check_img->fetch(PDO::FETCH_ASSOC)) {
		$image_way = "../".$pull_img_for_delete['propimg_way'];
		unlink($image_way);
	}
	

	$delete_imgs_foredit = $db->prepare("DELETE FROM property_images WHERE propimg_id IN({$image_list_check}) ");
	$delete_imgs_foredit->execute(array());
	if ($delete_imgs_foredit) {
		echo "images_deleted";
	}else{
		echo "images_not_deleted";
	}

	
	
}

if (isset($_POST['cover_image_data'])) {
	$new_cover=$db->prepare("UPDATE properties SET prop_cover=:prop_cover WHERE prop_id=:prop_id");
	$new_cover->execute(array('prop_cover'=>$_POST['cover_image_data'],'prop_id'=>$_POST['propid']));
	if ($new_cover) {
		echo "cover_updated";
	}else{
		echo "cover_cannot_updated";
	}
}

//like button event 
if (isset($_POST['like_event'])) {

	$check_like = $db->prepare("SELECT liked_propid FROM users_likes WHERE like_userid={$_SESSION['user_id']} AND liked_propid=:liked_propid");
	$check_like->execute(array('liked_propid'=>$_POST['liked_prop']));

	if ($check_like->rowCount() > 0) {
		$remove_like = $db->prepare("DELETE FROM users_likes 
			WHERE like_userid={$_SESSION['user_id']} AND liked_propid=:liked_propid");
		$remove_like->execute(array('liked_propid'=>$_POST['liked_prop']));
		if ($remove_like) {
			$update_like = $db->prepare("UPDATE properties SET prop_like=prop_like - 1 WHERE prop_id={$_POST['liked_prop']}");
			$update_like->execute(array());
			if ($update_like) {
				echo "likeremoved";
			}
		}
	}else{
		$add_like = $db->prepare("INSERT INTO users_likes SET 
			liked_propid=:liked_propid,
			like_userid=:like_userid
			");
		$add_like->execute(array('liked_propid'=>$_POST['liked_prop'],'like_userid'=>$_SESSION['user_id']));
		if ($add_like) {
			$update_like = $db->prepare("UPDATE properties SET prop_like=prop_like + 1 WHERE prop_id={$_POST['liked_prop']}");
			$update_like->execute(array());
			if ($update_like) {
				echo "likeadded";
			}
		}
	}
}



//listing subcategories on mobile devices
if (isset($_POST['subcategory_list'])) {
	
	$pull_scat=$db->prepare("SELECT * FROM subcategories WHERE uppercat_id=:uppercat_id");
	$pull_scat->execute(array('uppercat_id'=>$_POST['category_data']));
	foreach ($pull_scat as $value) {
		echo "<li><a href='sc-".$value['subcat_id']."?cat=".$_POST['category_data']."'>".$value['subcat_name']."</a><i class='fas fa-chevron-right'></i></li>";
	}
}




// check for recaptcha while user register
if (isset($_POST['checkfor_captcha'])) {
	
	require_once '../securimage/securimage.php';

	$securimage = new Securimage();

	if ($securimage->check($_POST['captcha_code'])==false) {
		echo "wrongcode";
		exit;
	}

}

// user register to mysql database 
if (isset($_POST['user_register'])) {


	$user_name = htmlspecialchars($_POST['user_name']);
	$user_surname = htmlspecialchars($_POST['user_surname']);
	$user_email = htmlspecialchars($_POST['user_email']);
	$user_password = md5(htmlspecialchars($_POST['user_pass']));
	$user_firebaseId = $_POST['firebaseUid'];

	$register=$db->prepare("INSERT INTO users SET 
		user_name=:username,
		user_surname=:usersurname,
		user_email=:useremail,
		user_password=:userpassword,
		user_firebase_id=:user_firebase_id
		");
	$user_register=$register->execute(array(
		'username'=>$user_name,
		'usersurname'=>$user_surname,
		'useremail'=>$user_email,
		'userpassword'=>$user_password,
		'user_firebase_id'=>$user_firebaseId
	));

	if ($user_register) {
		
		$pull_user_data = $db->prepare("SELECT user_id FROM users WHERE user_email=:user_email");
		$pull_user_data->execute(array('user_email' => $user_email));
		$write_id = $pull_user_data->fetch(PDO::FETCH_ASSOC);

		$_SESSION['user_id']=$write_id['user_id'];

		if ($write_id) {
			echo json_encode(array(
				'session' => $_SESSION['user_id'],
				'status' => "registered"
			));
		}

	}else{
		echo json_encode(array(
			'session' => 'no_session',
			'status' => 'notregistered'
		));
	}
	
}


//Given mail check before used.
if (isset($_POST['given_email'])) {
		
	$givenMail = htmlspecialchars($_POST['given_email']);
		
	$check_email = $db->prepare("SELECT user_email FROM users WHERE user_email=:user_email");
	$check_email->execute(array('user_email'=>$givenMail));
	if ($check_email->rowCount() > 0) {
		echo "usedmail";
	}
}

 ?>