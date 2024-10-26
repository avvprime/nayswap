
// Your web app's Firebase configuration
var firebaseConfig = {
	apiKey: "***************",
	authDomain: "******************",
	databaseURL: "***************",
	projectId: "********",
	storageBucket: "************",
	messagingSenderId: "************",
	appId: "*****************"
};
// Initialize Firebase

firebase.initializeApp(firebaseConfig);

const auth = firebase.auth();
const db = firebase.database();


var fUser = "empty";

auth.onAuthStateChanged(function(user){

	if (user)
	{
		fUser = firebase.auth().currentUser.uid;
	}

});




var timeNow = Date.now();



function escapeHtml(str)
{
	var map = {
		'&': '&amp;',
		'<': '&lt;',
		'>': '&gt;',
		'"': "&quot;",
		"'": "&#039;"
	};
	return str.replace(/[&<>"']/g, function(m) {return map[m];});
}


function decodeHtml(str)
{
	var map = {
		'&amp;': '&',
		'&lt;': '<',
		'&gt;': '>',
		"&quot;": '"',
		"&#039;": "'"
	};
	return str.replace(/&amp;|&lt;|&gt;|&quot;|&#039;/g,function(m){return map[m];});
}

function userIdSeperate(room,firstuser){

	var length = room.length;
	var tabPos = room.indexOf("_");
	var firstUserPos = room.indexOf(firstuser);
	var secondUserPos;

	if (firstUserPos > tabPos) {
		secondUser = room.slice(0,tabPos);
	}
	else{
		secondUser = room.slice(tabPos + 1,length);
	}


	return secondUser;
}


function dateConverter(timestampData){

	const date = new Date(timestampData);

	const today = date.toLocaleString("tr-TR");
	return today;
}



//chat page download process 
function chatDownload(){
		
		let fUid = $('.userFuid').val();
		var user_rooms = db.ref('chat/rooms/'+fUid);

		user_rooms.once('value', (snapshot) => {

			var total_contact = snapshot.numChildren();
			$('.message-options-container span').text('Toplam '+total_contact+' sohbet');
			$('.message-options-container span').attr('data-totalChat',total_contact)
			snapshot.forEach((childSnapshot) => {

				var data = childSnapshot.val();
				var date = dateConverter(data.lastMessageTimestamp).slice(0,10);
				var contact_person = `
				<div class="message-list-item" data-contact_room="`+childSnapshot.key+`">
			<div class="message-list-item-options">
				<button class="chat-room-check" data-title="Sil"  data-room="`+childSnapshot.key+`"><i class="fas fa-trash"></i></button>
			</div>
			<div class="message-list-link">
				<a href="#" data-contact_room="`+childSnapshot.key+`" data-contactUid="`+data.contactUid+`">
					<h3>`+data.contactName+`</h3>
					<p>`+data.lastMessage+`</p>
					<span>`+date+`</span>
				</a>
			</div>
		</div>`;

				$('.message-list-container').append(contact_person);

			});
		}); 

		$(document).keyup(function(e) {
			if (e.key === "Escape") { 
				$('.chat-panel-fon').remove();
			}
		});
}







// chat delete process start
$(document).on('click', '.chat-room-check', function(){

	var confirmLabel = `<div class="delete-check-label-fon">
							<div class="delete-check-label">
								<h5>Konuşma silinecek</h5>
								<div>
									<button class="confirm-delete-btn" id="`+$(this).attr('data-room')+`">Sil</button>
									<button class="cancel-delete-btn">Vazgeç</button>
								</div>
							</div>
						</div>`;

	$('body').append(confirmLabel);

});

$(document).on('click', '.cancel-delete-btn', function(){
	$('.delete-check-label-fon').remove();
});
$(document).on('click', '.delete-check-label-fon', function(){
	$('.delete-check-label-fon').remove();
});

$(document).on('click', '.confirm-delete-btn', function(){

	var totalChat = $('.message-options-container span').attr('data-totalChat');
	

	var room = $(this).attr('id');
	let uid = $('.userFuid').val();

	db.ref('chat/messages/'+room+"/user_"+uid+"_deleted").set({
		deleteTimestamp:timeNow,
		whoDeleted:uid,
		messageRoom:room
	}, function(error){
		if (error) {
			console.log("An error has occurated during adding delete data to message room!");
		}
		else
		{
			db.ref('chat/rooms/'+uid+"/"+room).remove()
			.then(() => {
				$('*[data-contact_room="'+room+'"]').remove();
				totalChat = totalChat - 1;
				$('.message-options-container span').text('Toplam '+totalChat+' sohbet');
			});
		}
	});

	


});


//chat delete process end

//when user click chat contact
$(document).on('click', '.message-list-link a', function(event){

	event.preventDefault();

	var user_name = $(this).children('h3').text();
	let uid = $('.userFuid').val();
	var room = $(this).attr('data-contact_room');
	let mysql_uid = $('.user_id').val();
	let contact_uid = userIdSeperate(room,mysql_uid);
	let contact_firebase_id = $(this).attr('data-contactUid');
	

	var chatPanel = `
	<div class="chat-panel-fon">
	<div class="chat-panel">
		<div class="chat-panel-top">
			<h3><a href="user-`+contact_uid+`" id="chat-contact-name" data-fbaseuid="`+contact_firebase_id+`" >`+user_name+`</a></h3>
			<button id="chat-panel-close-btn"><i class="fas fa-window-close"></i></button>
		</div>
		<div class="chat-panel-content">
			<div class="empty-for-chat"></div>
			
		</div>
		<div class="chat-panel-bottom">
			<div>
				<input id="message-input-on-chatpage" type="text" placeholder="Buraya yazın">
				<button id="chat-page-message-send-btn" data-roomNo="`+room+`"><i class="fas fa-paper-plane"></i></button>
			</div>
		</div>
	</div>
</div>`;

	$('body').append(chatPanel);

	//kullanıcı daha mesajları silmiş mi diye kontrol ediyoruz eğer silmişse o tarihten sonraki mesajları getireceğiz yalnızca
	db.ref('chat/messages/'+room).orderByChild("whoDeleted").equalTo(uid).once("value" , snapshot => {
		
		var empty_for_chat_div = `<div class="empty-for-chat"></div>`;

		if (snapshot.exists()) {
			
			var deleteControl = db.ref('chat/messages/'+room+"/user_"+uid+"_deleted");
			
			deleteControl.once('value', snap =>{
				delete_data = snap.val();

				var delDate = delete_data.deleteTimestamp;

				var messages = db.ref('chat/messages/'+room+'/message_list').orderByChild("messageTimestamp").startAfter(delDate);
				messages.on('value', message_snap => {
					$('.chat-panel-content').html(empty_for_chat_div);
					message_snap.forEach((message) => {
						var data = message.val();
						if (data.messageSender == uid)
						{
							var message = `
							<div class="message me">
								<div class="message-bubble">`+data.messageContent+`</div>
								<div class="message-date">`+dateConverter(data.messageTimestamp).slice(0,-3)+`</div>
							</div>
							`;
							$('.chat-panel-content').append(message);
						}
						else
						{
							var message = `
							<div class="message">
								<div class="message-bubble">`+data.messageContent+`</div>
								<div class="message-date">`+dateConverter(data.messageTimestamp).slice(0,-3)+`</div>
							</div>
							`;
							$('.chat-panel-content').append(message);
						}
						
					});
					$(".chat-panel-content").scrollTop($('.chat-panel-content')[0].scrollHeight - $('.chat-panel-content')[0].clientHeight);
				});
			});
		}
		else
		{
			var messages = db.ref('chat/messages/'+room+'/message_list');
			messages.on('value', message_snap => {
				$('.chat-panel-content').html(empty_for_chat_div);
				message_snap.forEach((message) => {
					var data = message.val();
					if (data.messageSender == uid)
					{
						var message = `
						<div class="message me">
						<div class="message-bubble">`+data.messageContent+`</div>
						<div class="message-date">`+dateConverter(data.messageTimestamp).slice(0,-3)+`</div>
						</div>
						`;
						$('.chat-panel-content').append(message);
					}
					else
					{
						var message = `
						<div class="message">
						<div class="message-bubble">`+data.messageContent+`</div>
						<div class="message-date">`+dateConverter(data.messageTimestamp).slice(0,-3)+`</div>
						</div>
						`;
						$('.chat-panel-content').append(message);
					}
					
				});
				$(".chat-panel-content").scrollTop($('.chat-panel-content')[0].scrollHeight - $('.chat-panel-content')[0].clientHeight);
			});
		}
		
		
	});

});


//chatting process on chat page

function sendMessageOnChat(){
	var message = escapeHtml($('#message-input-on-chatpage').val());

	if (!message.length > 0 || message.length > 200) {
		return;
	}
	
	$('#message-input-on-chatpage').val('');
	
	var room = $('#chat-page-message-send-btn').attr('data-roomNo');
	var uid = $('.userFuid').val();
	var contactUid =  $('#chat-contact-name').attr('data-fbaseuid');
	var contact_name = $('#chat-contact-name').text();
	var user_name = $('.user_name').val();
	

	db.ref('chat/messages/'+room+'/message_list').push({
		messageTimestamp:timeNow,
		messageSender:uid,
		messageContent:message
	}, function(error){
		if (error) {
			console.log("An error has occurated during sending message");
		}else{
			db.ref('chat/rooms/'+uid+"/"+room).update({
				contactName:contact_name,
				contactUid:contactUid,
				lastMessage:message,
				lastMessageTimestamp:timeNow
			},
			function(roomUpdateError){
				if (roomUpdateError) {
					console.log("An error has occurated during updating first room");
				}
				else
				{
					db.ref('chat/rooms/'+contactUid+"/"+room).update({
						contactName:user_name,
						contactUid:uid,
						lastMessage:message,
						lastMessageTimestamp:timeNow
					},
					function(subProcessError){
						if (subProcessError) {
							console.log("An error has been occurated during updating second room");
						}
						
					});
				}
			});

			

		}
	})
}



//chat close functions
$(document).on('click', '#chat-panel-close-btn', function(){
	$('.chat-panel-fon').remove();
});



//property show page accordion content
$('.propshow-swapprice').on('click', function(){
	$('.propshow-info-price').toggleClass("swap-desc-active");
});


// log out process with firebase and mysql
$('#header-logout').on('click', function(e){

	e.preventDefault();
	auth.signOut().then(() => {
		location.href = this.href;
		console.log("loged out");
	})

});

// log in process with firebase and mysql
$('#login-submit-btn').on('click', function(e){
	e.preventDefault();
	const email = $('#login-page-mail').val();
	const password = $('#login-page-password').val();
	const login_submit = true;

	$.ajax({
		url:'db/dboperation.php',
		type:'POST',
		dataType:'json',
		data:{login_submit:login_submit,email:email,password:password},
		success: function(response){
			if (response.status == "success")
			{
				auth.signInWithEmailAndPassword(email,password).then(cred => {
					location.href = 'index';
				});
			}
			else if(response.status == "nonexist_user")
			{
				Swal.fire({
					title:'Kullanıcı adı veya şifre yanlış',
					icon:'warning',
					confirmButtonText:'Tamam'
				});
			}
			else if(response.status == "banned_user")
			{
				Swal.fire({
					title:'Hesabınıza erişiminiz engellenmiştir',
					icon:'warning',
					confirmButtonText:'Tamam'
				});
			}
			else if(response.status == "already_open")
			{
				Swal.fire({
					title:'Bir oturum açık durumda',
					text:'Farklı bir oturumla devam etmek istiyorsanız açık olanı kapatmalısınız',
					icon:'warning',
					confirmButtonText:'Tamam'
				});
			}
		}
	})

});







//property show page top buttons


//property complaint process
$('#proppage-property-complaint-btn').on('click', function(){
	if ($('#user_activity_data').val()=="true") {

		var complaint_from = $('#complaint_from_user').val();
		var complaint_to = $('#complaint_to_user').val();
		var complaint_prop = $('#complaint_property').val();

		(async () => {

		const { value: text } = await Swal.fire({
		  input: 'textarea',
		  inputLabel: 'Şikayet Formu',
		  inputPlaceholder: 'Buraya yazın',
		  inputAttributes: {
		    'aria-label': 'Buraya yazin',
		    'max-length': 500
		  },
		  showCancelButton: true,
		  confirmButtonText:'Gönder',
		  cancelButtonText:'Vazgeç'
		}
		)
			
	
		if (text) {
			$.ajax({
				url:'db/ajax.php',
				type:'POST',
				data:{complaint_from:complaint_from,complaint_to:complaint_to,complaint_prop:complaint_prop,complaint_text:text},
				success: function(response){
					if (response == "complaint_delivered") {
						Swal.fire({
							title:'Şikayet İletildi',
							icon:'success',
							confirmButtonText:'Tamam'
						})
					}
					else{
						Swal.fire({
							title:'Şikayet İletilemedi',
							text:'Geçici bir sorun var lütfen daha sonra tekrar deneyin',
							icon:'error',
							confirmButtonText:'Tamam'
						});
					}
				}
			});
		}

		})()
		

	}
});


// ////////////////////////////////////////////////////////////////////////////
// /////////////////////////////////////////////////////////////////////////////
// start chat process on property
$('#chat-on-property-btn').on('click', function(){

	if ($('#user_activity_data').val()=="true") {


		
		var chatStatus = "true";
		var message_from = $('#complaint_from_user').val();
		var message_to = $('#complaint_to_user').val();

		var message_from_name = $('#complaint_from_user_name').val();
		var message_to_name = $('#complaint_to_user_name').val();
		var messageToFirebaseUid = $('#chat-on-property-btn').attr('data-fbaseId');

		$.ajax({
			url:'db/ajax.php',
			type:'POST',
			data:{chatStatus:chatStatus,message_from:message_from,message_to:message_to},
			success: function(response){
				if (response == "userblock") {
					Swal.fire({
						title:'Kullanıcı engeli!',
						text:'Eğer siz bu kullanıcıyı engellemediyseniz kullanıcı sizi engellemiş olabilir.',
						icon:'warning',
						confirmButtonText:'Tamam'
					});
					return;
				}
			}
		});


		if (message_from > message_to){
			var first_person = message_from;
			var second_person = message_to;
		}
		else{
			var first_person = message_to;
			var second_person = message_from;
		}

		
		
			(async () => {

				const { value: text } = await Swal.fire({
					input: 'textarea',
					inputLabel: 'Mesajlaşmaya Başla',
					inputPlaceholder: 'Buraya yazın',
					inputAttributes: {
						'aria-label': 'Buraya yazin',
						'max-length': 200
					},
					showCancelButton: true,
					confirmButtonText:'Gönder',
					cancelButtonText:'Vazgeç'
				}
				)
				if (text) {
					var room = first_person+'_'+second_person;
					var message = escapeHtml(text);
					db.ref('chat/rooms/'+fUser+'/'+room).set({
						contactName:message_to_name,
						contactUid:messageToFirebaseUid,
						lastMessage:message,
						lastMessageTimestamp:timeNow
					})
					.then(() => {
						db.ref('chat/rooms/'+messageToFirebaseUid+'/'+room).set({
							contactName:message_from_name,
							contactUid:fUser,
							lastMessage:message,
							lastMessageTimestamp:timeNow
						})
						.then(() => {
							db.ref('chat/messages/'+room+'/message_list').push({
								messageContent:message,
								messageSender:fUser,
								messageTimestamp:timeNow

							})
							.then(() => {
								Swal.fire({
									title:'Mesajınız iletildi',
									icon:'success',
									confirmButtonText:'Tamam'
								})
							})
							.catch(function(send_message_error){
								console.log(send_message_error);
							});
						})
						.catch(function(secondroom_update_error){
							console.log(secondroom_update_error);
						});
					})
					.catch(function(firstroom_update_error){
						console.log(room_update_error);
					});
				}

			})()
		
	}
});


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////	






//property edit page 
$('#property-title-edit-btn').on('click', function(){

	var property_title_data = $('#property-title-edit').val();
	var property_id = $('#propidfor_propedit').val();
	
	if (property_title_data.length > 0) {
		
		$.ajax({
			url:'db/ajax.php',
			type:'POST',
			data:{property_title_data:property_title_data,property_id:property_id},
			success: function(update){
				if (update == "proptitleupdated") {
					Swal.fire({
						title:'Başlık güncellendi',
						icon:'success',
						confirmButtonText:'Tamam'
					});
					$('#property-title-edit').val('');
					$('#prop-title-show').text(property_title_data);
				}else if(update == "proptitlenotupdated"){
					Swal.fire({
						title:'Hata',
						text:'Lütfen daha sonra tekrar deneyin',
						icon:'error',
						confirmButtonText:'Tamam'
					});
					$('#property-title-edit').val('');
				}
			}
		});
	}

});

$('#property-description-edit-btn').on('click', function(){

	var prop_desc_data = CKEDITOR.instances["property-description-edit"].getData();
	var property_id = $('#propidfor_propedit').val();
	
	if (prop_desc_data.length > 0 && prop_desc_data.length < 1000) {
		$.ajax({
			url:'db/ajax.php',
			type:'POST',
			data:{prop_desc_data:prop_desc_data,property_id:property_id},
			success: function(update){
				if (update == "propdescupdated") {
					Swal.fire({
						title:'Açıklama güncellendi',
						icon:'success',
						confirmButtonText:'Tamam'
					});
				}else if(update == "propdescnotupdated"){
					Swal.fire({
						title:'Hata',
						text:'Lütfen daha sonra tekrar deneyin',
						icon:'error',
						confirmButtonText:'Tamam'
					});
				}
			}
		});
	}else{
		Swal.fire({
			title:'En fazla 450 karakter',
			text:'Açıklama yazısı 450 karakterden fazla olamaz',
			icon:'info',
			confirmButtonText:'Tamam'
		});
	}

});

$('#property-swaptitle-edit-btn').on('click', function(){
	prop_swaptitle_data = $('#property-swaptitle-edit').val();
	var property_id = $('#propidfor_propedit').val();

	if (prop_swaptitle_data.length > 0) {
		$.ajax({
			url:'db/ajax.php',
			type:'POST',
			data:{prop_swaptitle_data:prop_swaptitle_data,property_id:property_id},
			success: function(update){
				if (update == "propswaptitleupdated") {
					Swal.fire({
						title:'İlan başlığı güncellendi',
						icon:'success',
						confirmButtonText:'Tamam'
					});
					$('#property-swaptitle-edit').val('');
					$('#prop-swaptitle-show').text(prop_swaptitle_data);
				}else if(update == "propswaptitlenotupdated"){
					Swal.fire({
						title:'Hata',
						text:'Lütfen daha sonra tekrar deneyin',
						icon:'error',
						confirmButtonText:'Tamam'
					});
				}
			}
		});
	}
});	

$('#property-swapdesc-edit-btn').on('click', function(){
	prop_swapdesc_data = CKEDITOR.instances["property-swapdesc-edit"].getData();
	var property_id = $('#propidfor_propedit').val();
	if (prop_swapdesc_data.length > 0 && prop_swapdesc_data.length < 201) {
		$.ajax({
			url:'db/ajax.php',
			type:'POST',
			data:{prop_swapdesc_data:prop_swapdesc_data,property_id:property_id},
			success: function(update){
				if (update == "propswapdescupdated"){
					Swal.fire({
						title:'Takas başlığı güncellendi',
						icon:'success',
						confirmButtonText:'Tamam'
					});
				}else if(update == "propswapdescnotupdated"){
					Swal.fire({
						title:'Hata',
						text:'Lütfen daha sonra tekrar deneyin',
						icon:'error',
						confirmButtonText:'Tamam'
					});
				}
			}
		});
	}else{
		Swal.fire({
			title:'En fazla 200 karakter',
			text:'Takas açıklama yazısı en fazla 200 karakter olabilir.',
			icon:'info',
			confirmButtonText:'Tamam'
		});
	}
});



$('#property-price-edit-btn').on('click', function(){
	var prop_price_data = $('#property-price-edit').val();
	var property_id = $('#propidfor_propedit').val();

	if (prop_price_data > 0 && prop_price_data < 99999999) {

		$.ajax({
			url:'db/ajax.php',
			type:'POST',
			data:{prop_price_data:prop_price_data,property_id:property_id},
			success: function(update){
				if (update == "proppriceupdated") {
					Swal.fire({
						title:'Ürün fiyatı güncellendi',
						icon:'success',
						confirmButtonText:'Tamam'
					});
					$('#property-price-edit').val('');
					$('#prop-price-show').text(prop_price_data+" TL");
				}else if(update == "proppricenotupdated"){
					Swal.fire({
						title:'Hata',
						text:'Lütfen daha sonra tekrar deneyin',
						icon:'error',
						confirmButtonText:'Tamam'
					});
				}
			}
		});
	}else{
		Swal.fire({
			title:'En fazla 99.999.999,00 TL',
			text:'Azami fiyatın üstüne çıkılamaz.',
			icon:'info',
			confirmButtonText:'Tamam'
		});
	}
});

$('#property-photo-edit-btn').on('click', function(){
	var image_list = [];

	$('input[name="prop-edit-imageedit"]:checked').each(function() {
  	 image_list.push(this.id.slice(2));
	});
	if ($('input[type=checkbox]').is(':checked')) {
	 	if ($('input[type=checkbox]:checked + label img').attr('src') != $('#propcover_for_propedit').val()) {
	 		$.ajax({
			 	url:'db/ajax.php',
			 	type:'POST',
			 	data:{delete_images_list:image_list},
			 	success: function(response){
				 		if (response == "images_deleted") {
				 			$('input[name="prop-edit-imageedit"]:checked + label').css("display","none");
				 			Swal.fire({
				 				title:'Fotoğraflar silindi',
				 				icon:'success',
				 				confirmButtonText:'Tamam'
				 			});
				 		}else if (response == "images_not_deleted"){
				 			Swal.fire({
				 				title:'Hata',
				 				text:'Lütfen daha sonra tekrar deneyin',
				 				icon:'error',
				 				confirmButtonText:'Tamam'
				 			});
				 		}else if(response == "filedoesnotexist"){
				 			console.loh("Dosya bulunamadı hatası");
				 		}
			 		}
		 	});
	 	}else{
	 		Swal.fire({
	 			title:'Kapak fotoğrafını silemezsiniz.',
	 			text:'Silmek için kapak fotoğrafınızı değiştirmelisiniz.',
	 			icon:'info',
	 			confirmButtonText:'Tamam'
	 		});
	 	}
	 }else{
	 	Swal.fire({
	 		title:'Fotoğraf seçmediniz',
	 		icon:'info',
	 		confirmButtonText:'Tamam'
	 	});
	 	
	 }
});

$('#property-cover-edit-btn').on('click', function(){
	if ($('input[name=prop-edit-imageedit]:checked').length > 0) {

		if ($('input[name=prop-edit-imageedit]:checked').length < 2) {

			var image_data = $('input[name=prop-edit-imageedit]:checked').attr('data-imageway');
			var prop_id =  $('#propidfor_propedit').val();
			
			$.ajax({
				url:'db/ajax.php',
				type:'POST',
				data:{cover_image_data:image_data,propid:prop_id},
				success: function(response){
					if (response == "cover_updated") {
						Swal.fire({
							title:'Kapak fotoğrafı güncellendi',
							icon:'success',
							confirmButtonText:'Tamam'
						});
						$('#prop-cover-edit-image').attr('src',image_data);
					}else if(response == "cover_cannot_updated"){
						Swal.fire({
							title:'Kapak fotoğrafı güncellenemedi!',
							icon:'error',
							confirmButtonText:'Tamam'
						});
					}

				}
			});

		}else{
			Swal.fire({
				title:'En fazla 1 fotoğraf',
				text:'Yalnızca bir adet fotoğraf seçilebilir.',
				icon:'info',
				confirmButtonText:'Tamam'
			});
		}
	}else{
		Swal.fire({
			title:'Fotoğraf seçilmedi',
			text:'Devam etmek için bir fotoğraf seçmelisiniz.',
			icon:'info',
			confirmButtonText:'Tamam'
		});
	}
});

//properties like button 

$('button.property-likebtn , #proppage-property-like-btn').on('click', function(){
	if ($('#user_activity_data').val()=="true") {

		var like_event = true;
		var clicked_button = $(this);
		var liked_prop = clicked_button.attr('data-propertyid');
		
		$.ajax({
			url:'db/ajax.php',
			type:'POST',
			data:{like_event:like_event,liked_prop:liked_prop},
			success: function(response){
				if (response == "likeadded") {
					clicked_button.css({"background":"#f23022","color":"#fff"});
				}else if (response == "likeremoved"){
					clicked_button.css({"background":"#fff","color":"#000"});
				}
			}
		});
	}/*window.load to login page on hosting*/

});



//profile page 
$('.profile-open-options').on('click', function(){

	
	$('.profile-sideoptions-fon').css({"visibility":"visible","opacity":"1","z-index":"3"});
	$('.profile-sideoptions-list').css({"visibility":"visible","opacity":"1","z-index":"4"});
});

$('.profile-sideoptions-fon').on('click',function(){
	$('.profile-sideoptions-fon').css({"visibility":"hidden","opacity":"0","z-index":"-1"});
	$('.profile-sideoptions-list').css({"visibility":"hidden","opacity":"0","z-index":"-1"});
	$('.myshops-about').css({"visibility":"hidden","opacity":"0","z-index":"-1"});
});

$('.myshops-item').on('click', function(){

	$('.profile-sideoptions-fon').css({"visibility":"visible","opacity":"1","z-index":"3"});
	$('.myshops-about').css({"visibility":"visible","opacity":"1","z-index":"4"});
});


// index page category and subcategory desktop view
$('.categories > ul > li').on({
	mouseenter: function(){
		var subcat_place = $(this).children('.subcat-show-container');
		var category_data = $(this).attr('data-categoryindex');
		var showcat_onhover = true;
		$.ajax({
			url:'db/ajax.php',
			type:'POST',
			data:{showcat_onhover:showcat_onhover,category_data:category_data},
			success: function(subcats){
				subcat_place.html('<div class="subcat-show-container-frame"><ul>'+subcats+'</ul></div>');
			}
		});
		$(this).children('.subcat-show-container').addClass('show-categories');
		$('.category-showpanel-fon').css({"visibility":"visible","opacity":"1"});
	},
	mouseleave: function(){
		$(this).children('.subcat-show-container').removeClass("show-categories");
		$('.category-showpanel-fon').css({"visibility":"hidden","opacity":"0"});
	}
});







//mobile view categories
$('.mcategories ul').on('click', 'li', function(){

	var category_data = $(this).attr("data-catid");
	var subcategory_list = "true";
	$.ajax({
		url:'db/ajax.php',
		type:'POST',
		data:{subcategory_list:subcategory_list,category_data:category_data},
		success: function(e){
			$('.mcategory-container .msubcategories ul').html(`<li><a href="c-`+category_data+`">Tümünü göster</a><i class="fas fa-chevron-right"></i></li>`);
			$('.mcategory-container .msubcategories ul').append(e);
			$('.mcategory-container .msubcategories').css("right","0px");
		}
	});	
});
//close subcategories in mobile view
$('#mobile-cats-backbtn').on('click' , function(){
	$('.msubcategories').css("right","-100%");
});


$('.property-swaprice').on('click' ,function(){
	$(this).next().css("bottom","0px");
});

$('.property-swapdesc-hover').on('click' , function(){
	$(this).css("bottom","-150px");
});



// index page category and subcategory end


//profile page block process
$('#user-page-block-btn').on('click', function(event){

	event.preventDefault();
	

	let uid = $(this).attr('data-user');
	let isBlocked = $(this).attr('data-block');
	
	if (isBlocked == "false") {
		
		let addBlock = true;

		$.ajax({
			url:'db/ajax.php',
			type:'POST',
			data:{uid:uid,addBlock:addBlock},
			success: function(response){
				if (response == "block_added") {
					$('#user-page-block-btn').text("Engeli kaldır");
					$('#user-page-block-btn').attr('data-block','true');
				}
				else if (response == "same_user") {
					Swal.fire({
						title:'İzinsiz eylem!',
						text:'Kendinizi engelleyemezsiniz.',
						icon:'warning',
						confirmButtonText:'Tamam'
					});
				}
				else
				{
					Swal.fire({
						title:'İşlem başarısız',
						text:'Bir sorunla karşılaştık lütfen daha sonra tekrar deneyin.',
						icon:'error',
						confirmButtonText:'Tamam'
					});
				}
			}
		});
	}else{

		let removeBlock = true
		$.ajax({
			url:'db/ajax.php',
			type:'POST',
			data:{uid:uid,removeBlock:removeBlock},
			success: function(response){
				if (response == "block_removed") {
					$('#user-page-block-btn').text("Engelle");
					$('#user-page-block-btn').attr('data-block','false');
				}
				else
				{
					Swal.fire({
						title:'İşlem başarısız',
						text:'Bir sorunla karşılaştık lütfen daha sonra tekrar deneyin.',
						icon:'error',
						confirmButtonText:'Tamam'
					});
				}
			}
		});
	}

});

//profile page complaint process
$('#user-page-complaint-btn').on('click', function(event){
	event.preventDefault();

	let uid = $('#user-page-block-btn').attr('data-user');
	let complaint_profile = true;


	(async () => {

		const { value: text } = await Swal.fire({
		  input: 'textarea',
		  inputLabel: 'Şikayet Formu',
		  inputPlaceholder: 'Buraya yazın',
		  inputAttributes: {
		    'aria-label': 'Buraya yazin',
		    'max-length': 500
		  },
		  showCancelButton: true,
		  confirmButtonText:'Gönder',
		  cancelButtonText:'Vazgeç'
		}
		)
			
	
		if (text) {
			$.ajax({
				url:'db/ajax.php',
				type:'POST',
				data:{complaint_text:text,uid:uid,complaint_profile:complaint_profile},
				success: function(response){
					if (response == "complaint_delivered") {
						Swal.fire({
							title:'Şikayet İletildi',
							icon:'success',
							confirmButtonText:'Tamam'
						});
					}
					else{
						Swal.fire({
							title:'Şikayet İletilemedi',
							text:'Geçici bir sorun var lütfen daha sonra tekrar deneyin',
							icon:'error',
							confirmButtonText:'Tamam'
						});
					}
				}
			});
		}

		})()

	

});
