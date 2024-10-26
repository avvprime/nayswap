




//New property page swiper form



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

		

		$(document.body).on('click', '.new-property-form-button', function(){

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

	currency(step){
		return `
		<input
		type="number"
		${step.required ? 'required' : ''}
		${step.autofocus ? 'autofocus' : ''}
		placeholder="${step.placeholder}"
		minlength="${step.minlength}"
		maxlength="${step.maxlength}"
		pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" 
		id="${step.id}"
		/>
		`
	}
	textarea(step){
		return `
		<textarea 
		${step.required ? 'required' : ''}
		${step.autofocus ? 'autofocus' : ''}
		rows="10" cols="100"
		minlength=${step.minlength}
		maxlength=${step.maxlength}
		id="${step.id}"
		name="${step.name}"
		></textarea>
		`
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
		<input
		type="checkbox"
		id="${step.id}"
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

	selectcat(step){
		return `
		<select
		${step.required ? 'required' : ''}
		id="${step.id}"
		name="${step.name}"
		>
		<option value="">Lütfen seçiniz</option>
		<option value="1">Araç</option>
		<option value="2">Giyim</option>
		<option value="3">Elektronik</option>
		<option value="4">Spor-Hobi</option>
		<option value="5">Ev-Bahçe</option>
		<option value="6">Sağlık-Bakım</option>
		<option value="7">El Yapımı</option>
		<option value="8">Hizmetler</option>
		<option value="9">Kitap, Film ve Oyun</option>
		</select>
		`
	}
	selectsubcat(step){
		var subcategory_select_list = `
		<select
		${step.required ? 'required' : ''}
		id="${step.id}"
		name="${step.name}"
		>
		<option value="">Lütfen seçiniz</option>`+subcategories_data_for_newproperty+`</select>`;
		return subcategory_select_list
	}

	selectcity(step){
		var city_select_list = `
		<select
		${step.required ? 'required' : ''}
		id="${step.id}"
		name="${step.name}"
		>
		<option value="">Lütfen seçiniz</option>`+city_select_list_data+`
		</select>
		`
		return city_select_list
	}

	selectdistrict(step){
		var district_select_list = `
		<select
		${step.required ? 'required' : ''}
		id="${step.id}"
		name="${step.name}"
		>
		<option value="">Lütfen seçiniz</option>`+district_data_for_newproperty+`
		</select>
		`;
		return district_select_list
		
	}

	generate(step){

		if (step.hasOwnProperty('starttemplate')) {
			let start_template = document.getElementById('new-property-start-template').innerHTML
			this.swiper.appendSlide(start_template)
			if (this.current > 0 ) {
				this.swiper.slideNext()
			}
		}else if(step.hasOwnProperty('endtemplate')){
			let end_template = document.getElementById('new-property-end-template').innerHTML
			this.swiper.appendSlide(end_template)
			if (this.current > 0 ) {
				this.swiper.slideNext()
			}

		}else{
			if (!step.hasOwnProperty('type')) {
				step.type = 'input'
			}

			let field = this[step.type](step)

			let template = document.getElementById('new-property-template').innerHTML

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
	$('.swiper-slide-active #new-property-alert').css({"opacity":"1","visibility":"visible"});
	$('.swiper-slide-active #new-property-alert').text(message);
	setTimeout(function(){
		$('.swiper-slide-active #new-property-alert').css({"opacity":"0","visibility":"hidden"});
	},1000);
	
}

function newPropUpload(){

	$('.new-property-form-button').css("display","none");

	var newprop_title = $('#new-property-title').val();
	var newprop_desc = $('#new-property-desc').val();
	var newprop_price = $('#new-property-price').val();
	var newprop_swptitle = $('#new-property-swaptitle').val();
	var newprop_swpdesc = $('#new-property-swapdesc').val();

	var newprop_cat_index = $('#new-property-category').val();
	var newprop_cat = $('#new-property-category option:selected').text();

	var newprop_subcat_index = $('#new-property-subcategory').val();
	var newprop_subcat = $('#new-property-subcategory option:selected').text();

	var newprop_city_index = $('#new-property-city').val();
	var newprop_city = $('#new-property-city option:selected').text();

	var newprop_district_index = $('#new-property-district').val();
	var newprop_district = $('#new-property-district option:selected').text();
	var upload_new_property = "true";


	$.ajax({
		url:'db/prop-image-uploader.php',
		type:'POST',
		data:{newprop_cat:newprop_cat,newprop_subcat:newprop_subcat,newprop_city:newprop_city,newprop_district:newprop_district,upload_new_property:upload_new_property,newprop_title:newprop_title,newprop_desc:newprop_desc,newprop_price:newprop_price,newprop_swptitle:newprop_swptitle,newprop_swpdesc:newprop_swpdesc,newprop_city_index:newprop_city_index,newprop_district_index:newprop_district_index,newprop_cat_index:newprop_cat_index,newprop_subcat_index:newprop_subcat_index},
		success: function(response){
			if (response=="uploaded") {
				var check_newproperty="true";
				
				$.ajax({
					url:'db/prop-image-uploader.php',
					type:'POST',
					data:{check_newproperty:check_newproperty},
					success: function(response){
						var newprop_id = response;
						
						newpropImages.processQueue();
						newpropImages.on('sending', function(file, xhr, formData){
							formData.append('prop_id', newprop_id);
						});
						

						var update_propcover = true;
						
						setTimeout(function(){
							$.ajax({
								url:'db/prop-image-uploader.php',
								type:'POST',
								data:{update_propcover:update_propcover,newprop_id:newprop_id},
								success: function(response){
									if (response == "coverupdated") {
										 form.nextStep();
									}else if (response == "covercantupdated"){
										RegisterAlert("Kapak fotoğrafı yüklenemedi!");
									}else if (response == "imgscantpulled"){
										RegisterAlert("İlan resimleri çekilemedi");
									}
								}
							});
						}, 1500);
					}
				});
				
				
			}else if(response=="cantuploaded"){

				RegisterAlert("İlan yüklenemedi");
				
			}
		},
		error: function(error){
			console.log(error);
		}
	});
	RegisterAlert("Lütfen bekleyiniz.");
}

var priceExist = false;

const form = new Form();

form.step({
	starttemplate:'',
	beforeNextStep: function(){
		var pullcitylistfornewprop = true;
		$.ajax({
			url:'db/prop-image-uploader.php',
			type:'POST',
			data:{pullcitylistfornewprop:pullcitylistfornewprop},
			success: function(response){

				city_select_list_data = response;
				form.nextStep();
			}
		});
	}
}).step({
	title:"Öncelikle bir il seçiniz.",
	required: true,
	id: 'new-property-city',
	type: 'selectcity',
	name:'newprop_city_index'
}).step({
	title:"Bir ilçe seçiniz.",
	required: true,
	id:"new-property-district",
	name:"newprop-district",
	type:"selectdistrict"
}).step({
	title:"Hangi kategoride ilan vereceksiniz?",
	required: true,
	id: 'new-property-category',
	type: 'selectcat',
	name:'newprop_cat_index'
}).step({
	title:"Lütfen bir alt kategori seçiniz.",
	required: true,
	id: 'new-property-subcategory',
	type: 'selectsubcat',
	name:'newprop_subcat_index'
}).step({
	name:'property-title',
	title:'İlan başlığı',
	required: true,
	autofocus: true,
	placeholder:'',
	minlength:'10',
	maxlength:'50',
	id:'new-property-title'
}).step({
	title:'Kısaca üründen bahseder misiniz. Uzun bir açıklama isterseniz daha sonra düzenle bölümünden yapabilirsiniz.',
	minlength:'10',
	maxlength:'250',
	required:true,
	autofocus:true,
	type:'textarea',
	id:'new-property-desc'
}).step({
	name:'',
	title:'Ürün için biçtiğiniz fiyat nedir?',
	required:true,
	autofocus: true,
	type:'currency',
	placeholder:'',
	id:'new-property-price',
	beforeNextStep: function(){
		if ($('#new-property-price').val() > 99999999) {
			RegisterAlert("Fiyat 99.999.999,00'dan büyük olamaz.");
		}else{
			form.nextStep();
		}
	}
}).step({
	name:'',
	title:'Ürününüz için hangi takasa açıksınız?',
	required:true,
	autofocus: true,
	minlength:'5',
	maxlength:'35',
	placeholder:'Ne yada neleri kabul edersiniz?',
	id:'new-property-swaptitle'
}).step({
	name:'',
	title:'Takas şartlarınız ve istekleriniz ile ilgili küçük bir açıklama yapar mısınız?',
	required:true,
	autofocus: true,
	type:'textarea',
	placeholder:'',
	minlength:'20',
	maxlength:'200',
	id:'new-property-swapdesc',
	beforeNextStep: function(){
	 newPropUpload();
	}
}).step({
	endtemplate:''
})

form.start();

Dropzone.autoDiscover = false;
// writing dropzonejs programatically and setting features
var newpropImages = new Dropzone("div#newprop_dropzone", { 
	url: "db/newprop-image-uploads.php",
	method:"POST",
	maxFiles:10,
	parallelUploads: 10,
	autoProcessQueue: false
});

//enable upload before finish the form
newpropImages.on("addedfile",function(file){
	$('#new-property-first-slide button').css({"visibility":"visible","opacity":"1"});
});

//pull subcategories via ajax when user select a category


$(document).on('change',"#new-property-category", function(){
	var pullsubcats_fornewprop = true;
	var cat_data_fornewprop = $(this).val();

	$.ajax({
		url:'db/prop-image-uploader.php',
		type:'POST',
		data:{pullsubcats_fornewprop:pullsubcats_fornewprop,cat_data_fornewprop:cat_data_fornewprop},
		success: function(response){
			subcategories_data_for_newproperty = response;
			

		}
	});
});

$(document).on('change',"#new-property-city", function(){
	var pulldistricts_fornewprop = true;
	var city_data_fornewprop = $(this).val();

	$.ajax({
		url:'db/prop-image-uploader.php',
		type:'POST',
		data:{pulldistricts_fornewprop:pulldistricts_fornewprop,city_data_fornewprop:city_data_fornewprop},
		success: function(response){
			district_data_for_newproperty = response;
			

		}
	});
});