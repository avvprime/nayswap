<?php 

require_once 'db/connect.php';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
	<title>Categories</title>
	<meta charset="UTF-8">
	<meta name="keywords" content="nayswap,takas,ikinciel,sahibinden,araba,ev,kitap,film,elektronik">
	<meta name="author" content="AVV">
	<link id="favicon" rel="icon" href="img/favicon-72x72.png" type="image/png" sizes="72x72">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" type="text/css" href="fontawesome/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<div class="mcategory-container">
		<div class="mcategories-toptools">
			<button id="mobile-cats-backbtn"><i class="fas fa-chevron-left"></i></button>
			<a href="index"><button><i class="fas fa-times"></i></button></a>
		</div>
		<div class="mcategories">
			<h4>Kategoriler</h4>
			<?php $catpull = $db->prepare("SELECT * FROM categories");
			$catpull->execute();
			 ?>
			<ul>
				<?php while($catlist=$catpull->fetch(PDO::FETCH_ASSOC)){ ?>
				<li data-catid="<?php echo $catlist['cat_id']; ?>"><?php echo $catlist['cat_name']; ?><i class="fas fa-chevron-right"></i></li>
			<?php } ?>
			</ul>
		</div>
		<div class="msubcategories">
			<h4>Kategoriler</h4>
			<ul>
			
			</ul>
		</div>
	</div>



	<script src="https://www.gstatic.com/firebasejs/8.4.1/firebase-app.js"></script>
	<script src="https://www.gstatic.com/firebasejs/8.4.1/firebase-auth.js"></script>
	<script src="https://www.gstatic.com/firebasejs/8.4.1/firebase-database.js"></script>

	<script type="text/javascript" src="js/jquery-3.5.1.js"></script>
	<script type="text/javascript" src="js/fontawesome.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
	
</body>
</html>