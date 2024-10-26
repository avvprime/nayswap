<?php 
require_once 'connect.php';

if (!empty($_FILES['file'])) {
 	
	$uploadpath="../img/property-photos/";
	$tmpname = $_FILES['file']['tmp_name'];
	$name = $_FILES['file']['name'];
	$ext=strtolower(substr($_FILES['file']["name"], strpos($_FILES['file']["name"], '.')+1));
	
	$uniqid=rand(10000,20000).uniqid();
	if (move_uploaded_file($tmpname, $uploadpath.$uniqid.".".$ext)) {
		
		$userid=$_SESSION['user_id'];
		$prop_id=$_POST['prop_id'];
		$refimgyol = substr($uploadpath, 3).$uniqid.".".$ext;

		$insert_images=$db->prepare("INSERT INTO property_images SET 
			imgbelong_prop_id=:imgbelong_prop_id,
			propimg_way=:propimg_way,
			imgbelong_user_id=:imgbelong_user_id
			");
		$insert_images->execute(array(
			'imgbelong_prop_id'=>$prop_id,
			'propimg_way'=>$refimgyol,
			'imgbelong_user_id'=>$userid
		));

	}
	
	
}





 ?>