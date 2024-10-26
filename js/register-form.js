




class Form{
	steps=[]
	current=0
	constructor(){
		this.swiper = new Swiper('.swiper-container', {
			direction: 'vertical',
			touchRatio: 0,
			on: {
				slideChangeTransitionEnd: () => {
					$('.swiper-slide-active input').focus()
				}
			}
		});	

		$(document.body).on('keyup', function(event){
			if (event.key === "Enter") {
				$('.swiper-slide-active fieldset:valid + .register-form-button').click()
			}
		})

		$(document.body).on('click', '.register-form-button', function(){

			if (this.steps[this.current].hasOwnProperty('beforeNextStep')) {

				this.steps[this.current].beforeNextStep()

			}else{
				this.nextStep()
			}
			

		}.bind(this))
	}

	nextStep(){
		this.current += 1
		if (this.steps[this.current]) {
			this.generate(this.steps[this.current])
		}else{
			console.log("Finish")
		}
		
	}

	step(step){
		this.steps.push(step)
		return this
	}

	input(step){
		return `
		<input
		type="text"
		${step.required ? 'required' : ''}
		${step.autofocus ? 'autofocus' : ''}
		placeholder="${step.placeholder}"
		minlength="${step.minlength}"
		maxlength="${step.maxlength}"
		id="${step.id}"
		/>`
	}

	password(step){
		return `
		<input
		type="password"
		${step.required ? 'required' : ''}
		${step.autofocus ? 'autofocus' : ''}
		placeholder="${step.placeholder}"
		minlength="${step.minlength}"
		maxlength="${step.maxlength}"
		id="${step.id}"
		/>
		`
	}

	checkbox(step){
		return `
		<a href="policy" target="_blank">Kullanıcı Sözleşmesi ve Gizlilik Politikası</a>
		
		<input
		type="checkbox"
		id="${step.id}"
		${step.required ? 'required' : ''}
		/>
		`
	}

	email(step){
		return `
		<input 
		type="email"
		${step.required ? 'required' : ''}
		${step.autofocus ? 'autofocus' : ''}
		placeholder="${step.placeholder}"
		minlength="${step.minlength}"
		maxlength="${step.maxlength}"
		id="${step.id}"
		/>
		`
	}

	recaptcha(step){
		return `
		<img  id="sec_code" src="securimage/securimage_show.php" alt="CAPTCHA Image">
		<a href="#" onclick="document.getElementById('sec_code').src='securimage/securimage_show.php?'+ Math.random(); return false">Yeni Resim</a>
		<input type="text" id="captcha_code_id" name="captcha_code" placeholder="Güvenlik kodu" required autocomplete="off">
		`
	}

	generate(step){

		if (step.hasOwnProperty('starttemplate')) {
			let start_template = document.getElementById('register-start-template').innerHTML
			this.swiper.appendSlide(start_template)
			if (this.current > 0 ) {
				this.swiper.slideNext()
			}
		}else if(step.hasOwnProperty('endtemplate')){
			let end_template = document.getElementById('register-end-template').innerHTML
			this.swiper.appendSlide(end_template)
			if (this.current > 0 ) {
				this.swiper.slideNext()
			}

		}else{
			if (!step.hasOwnProperty('type')) {
				step.type = 'input'
			}

			let field = this[step.type](step)

			let template = document.getElementById('slide-template').innerHTML

			template = template
			.replace('{field}', field)
			.replace('{title}',step.title)

			this.swiper.appendSlide(template)
			if (this.current > 0 ) {
				this.swiper.slideNext()
			}
		}

		
	}

	start(){
		this.generate(this.steps[this.current])
	}

	
}

function RegisterAlert(message){
	$('.swiper-slide-active #register-alert').css({"opacity":"1","visibility":"visible"});
	$('.swiper-slide-active #register-alert').text(message);
	setTimeout(function(){
		$('.swiper-slide-active #register-alert').css({"opacity":"0","visibility":"hidden"});
	},3000);
	
}

function userRegister(){
	var user_name = $('#register-name').val();
	var user_surname = $('#register-surname').val();
	var user_email = $('#register-email').val();
	var user_pass = $('#register-passtwo').val();
	var user_register = "true";
	$('.register-form-button').css("display","none");
	RegisterAlert("Lütfen bekleyin")

	auth.createUserWithEmailAndPassword(user_email,user_pass)
	.then(cred => {

		var firebaseUid = cred.user.uid;
		
		$.ajax({
		url:'db/ajax.php',
		type:'POST',
		dataType: 'json',
		data:{user_register:user_register,user_name:user_name,user_surname:user_surname,user_email:user_email,user_pass:user_pass,firebaseUid:firebaseUid},
		success: function(response){
			if (response.status=="registered") {
					var fullName = user_name+" "+user_surname;
				db.ref('chat/users/'+firebaseUid).set({
					userMysqlID:response.session,
					userFullName:fullName
				})
				.then(register_response => {
					form.nextStep();
				})



			}else if(response.status=="notregistered"){

				RegisterAlert("Üzgünüz geçici bir hata var");
				console.log("notregistered");
			}
		},
		error: function(error){
			console.log(error);
		}
	});

	})
	.catch(function(error) {
		var errorCode = error.code;
		var errorMessage = error.message;
		if (errorCode == 'auth/invalid-email') {
			RegisterAlert('Geçersiz e-posta adresi. Lütfen formu tekrar doldurun');
		}
		else{
			console.log(error);
			alert(errorMessage);
		}
	});

	
}

const form = new Form();

form.step({
	starttemplate:''
}).step({
	name:'name',
	title:'Adınız',
	required: true,
	autofocus: true,
	placeholder:'Adınızı yazınız',
	minlength:'3',
	maxlength:'11',
	id:'register-name'
}).step({
	name:'surname',
	title:'Soyadınız',
	required: true,
	autofocus: true,
	placeholder:'Soyadınızı yazınız',
	minlength:'2',
	maxlength:'15',
	id:'register-surname'
}).step({
	name:'email',
	title:'E-posta adresiniz',
	required:true,
	autofocus: true,
	type:'email',
	placeholder:'E-posta adresinizi yazınız',
	minlength:'15',
	id:'register-email',
	beforeNextStep: function(){
		var given_email = $('#register-email').val()
		$.ajax({
			url:'db/ajax.php',
			type:'POST',
			data:{given_email:given_email},
			success: function(r){
				if (r=="usedmail") {
					
					RegisterAlert("Bu e-posta adresi daha önce kullanılmış");
					
				}else{
					form.nextStep();
				}
			}
		})
	}
}).step({
	name:'pass',
	title:'Bir şifre belirleyiniz',
	required:true,
	autofocus: true,
	type:'password',
	minlength:'6',
	maxlength:'20',
	placeholder:'En az 6 karakterli olmalı',
	id:'register-passone'
}).step({
	name:'passcont',
	title:'Şifreyi tekrar yazınız',
	required:true,
	autofocus: true,
	type:'password',
	placeholder:'Şifrenizi tekrar yazınız',
	minlength:'6',
	id:'register-passtwo',
	beforeNextStep: function(){
		if ($('#register-passone').val() != $('#register-passtwo').val()) {
			RegisterAlert("Şifreler aynı değil!");
		}else{
			form.nextStep();
		}
	}
}).step({
	type:'recaptcha',
	title:'Güvenlik kodu',
	required:true,
	autofocus:true,
	beforeNextStep: function(){
		var checkfor_captcha = true;
		var captcha_code = $('#captcha_code_id').val();
		$.ajax({
			url:'db/ajax.php',
			type:'POST',
			data:{checkfor_captcha:checkfor_captcha,captcha_code:captcha_code},
			success: function(response){
				if (response=="wrongcode") {
					RegisterAlert("Yanlış kod. Lütfen tekrar deneyin.");
				}else{
					form.nextStep();
				}
			}
		});
	}
}).step({
	name:'policy-check',
	title:'Devam ederek, Kullanıcı Sözleşmesi ve Gizlilik Politikasını okuyup kabul ettiğinizi beyan edersiniz.',
	required:true,
	type: 'checkbox',
	value:'true',
	id:'register-policy-check',
	beforeNextStep: function(){
		userRegister();
	}
}).step({
	endtemplate:''
})

form.start();