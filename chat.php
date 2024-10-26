<?php  require_once 'header.php';


?>

<div class="breadcrumb-container">
	<ul>
		<li class="breadcrumb-item"><a href="index" class="breadcrumb-link">Anasayfa</a><i class="fas fa-chevron-right"></i></li>
		<li class="breadcrumb-item"><a href="chat" class="breadcrumb-link">Mesajlar</a><i class="fas fa-chevron-right"></i></li>
	</ul>
</div>


<section class="chat-platform">
	<h1><i class="fas fa-inbox"></i> Gelen Kutusu</h1>
	<div class="message-options-container">
		<span></span>
	</div>

	<div class="message-list-container">

		

	</div>

</section>






















<?php require_once 'footer.php'; 

$ask_userName_for_message = $db->prepare("SELECT user_name,user_surname,user_firebase_id FROM users WHERE user_id={$_SESSION['user_id']} ");
$ask_userName_for_message->execute(array());
$pull_userName_for_message = $ask_userName_for_message->fetch(PDO::FETCH_ASSOC);

?>

<input type="hidden" class="user_id" value="<?php echo $_SESSION['user_id']; ?>" >
<input type="hidden" class="user_name" value="<?php echo $pull_userName_for_message['user_name'].' '.$pull_userName_for_message['user_surname']; ?>">
<input type="hidden" class="userFuid" value="<?php echo $pull_userName_for_message['user_firebase_id']; ?>">
<script>
	$(document).ready(function(){

		chatDownload();

		$(document).on('click', '#chat-page-message-send-btn', function(){
			sendMessageOnChat();
		});

		$(document).keyup(function(e) {
			if (e.key === "Enter") { 
				if (document.getElementById('chat-page-message-send-btn')) {
					sendMessageOnChat();
				}
			}
		});

	});
</script>