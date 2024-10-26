<?php  
ob_start();
session_start();
require_once 'connect.php';

//user login process
if (isset($_POST['login_submit'])) {
	
	$usermail = htmlspecialchars($_POST['email']);
	$userpassword = md5(htmlspecialchars($_POST['password']));

	$verify = $db->prepare("SELECT user_id FROM users WHERE user_email=:user_email AND user_password=:user_password AND user_ban=:user_ban");
	$verify->execute(array('user_email'=>$usermail,'user_password'=>$userpassword,'user_ban'=>0));
	if ($exist=$verify->fetch(PDO::FETCH_ASSOC)) {
		if (!$_SESSION['user_id']) {

			$_SESSION['user_id']=$exist['user_id'];

			echo json_encode(array(
				'session' => $_SESSION['user_id'],
				'status' => 'success'
			));
			
		}else{
			echo json_encode(array(
				'session' => 'no_session',
				'status' => 'already_open'
			));
		}
	}else{
		$verifyban = $db->prepare("SELECT user_id FROM users WHERE user_email=:user_email AND user_password=:user_password AND user_ban=:user_ban");
		$verifyban->execute(array('user_email'=>$usermail,'user_password'=>$userpassword,'user_ban'=>1));
		if ($banexist=$verifyban->fetch(PDO::FETCH_ASSOC)) {
			echo json_encode(array(
				'session' => 'no_session',
				'status' => 'banned_user'
			));
		}else{
			echo json_encode(array(
				'session' => 'no_session',
				'status' => 'nonexist_user'
			));
		}
	}
}


 ?>