<?php 

require_once 'connect.php';

require_once '../seo-function.php';

$city = $_POST['city'];
$search = htmlspecialchars($_POST['query']);
$subcategory = $_POST['subcategory'];


$limit = 12;

$page = 1;

if ($_POST['page'] > 1) {
	$start = (($_POST['page'] - 1) * $limit);
	$page = $_POST['page'];
}else{
	$start = 0;
}


$query = "SELECT * FROM properties WHERE prop_subcat_index=$subcategory ";


if ($city != null) {
	$query .= "AND prop_city_index={$city} ";
}


if ($search != null) {
	$query .= "AND prop_swaptitle LIKE '%".$search."%'";
}


$properties = $db->prepare($query);

$properties->execute(array());

$property_num = $properties->rowCount();


$query .= "LIMIT ".$start." , ".$limit."";


$properties = $db->prepare($query);
$properties->execute(array());


$pagination = '
<div class="subcat-page-filt-pagination"><ul>
';

$total_links = ceil($property_num/$limit);


$previous_link = '';

$next_link = '';

$page_link = '';

$page_array = array();

if ($total_links > 4)
{
	if ($page < 5) 
	{
		for ($count=1; $count <=5 ; $count++)
		{ 
			$page_array[] = $count;
		}	
		$page_array[] = '...';
		$page_array[] = $total_links;
	}
	else
	{
		$end_limit = $total_links - 5;
		if ($page > $end_limit) 
		{
			$page_array[] = 1;
			$page_array[] = '...';

			for($count = $end_limit; $count <= $total_links; $count++ )
			{
				$page_array[] = $count;
			}
		}
		else
		{
			$page_array[] = 1;
			$page_array[] = '...';
			for($count = $page - 1; $count <= $page + 1; $count++)
			{
				$page_array[] = $count;
			}
			$page_array[] = '...';
			$page_array = $total_links;
		}
	}
}
else
{
	for ($count=1; $count <= $total_links; $count++) 
	{ 
		$page_array[] = $count;
	}
}

for ($count=0; $count < count($page_array); $count++) 
{ 
	if ($page == $page_array[$count]) 
	{
		$page_link .= '
		<li class="page-item active"><a href="#" class="page-link">'.$page_array[$count].'</a></li>
		';

		$previous_id = $page_array[$count] - 1;
		if ($previous_id > 0) 
		{
			$previous_link = '<li class="page-item"><a href="javascript:void(0)" class="page-link" data-pagenumber="'.$previous_id.'">Önceki</a></li>';
		}
		else
		{
			$previous_link  = '
			<li class="page-item disabled"><a href="#" class="page-link">Önceki</a></li>
			';
		}
		$next_id = $page_array[$count] + 1;

		if ($next_id > $total_links)
		{
			$next_link = '
			<li class="page-item disabled"><a href="#" class="page-link">Sonraki</a>
			';
		}
		else
		{
			$next_link = '
			<li class="page-item"><a href="javascript:void(0)" class="page-link" data-pagenumber="'.$next_id.'">Sonraki</a></li>
			';
		}
	}
	else
	{
		if ($page_array[$count]=='...')
		{
			$page_link .= '
			<li class="page-item disabled"><a href="#" class="page-link">...</a></li>
			';
		}
		else
		{
			$page_link .= '
			<li class="page-item"><a class="page-link" href="javascript:void(0)" data-pagenumber="'.$page_array[$count].'">'.$page_array[$count].'</a></li>
			';
		}
	}
}

$pagination .= $previous_link . $page_link . $next_link ."</ul></div>";



if ($property_num > 0) {
	while($prop_data = $properties->fetch(PDO::FETCH_ASSOC)){
	$prop_list[] =  "<div class='property-box'>
							<button  class='property-likebtn' data-propertyid='".$prop_data['prop_id']."'><i class='far fa-heart'></i></button>
							<a href='prop-".$prop_data['prop_id']."?urun=".seo($prop_data['prop_title'])."'>
								<div class='property-image'><img src='".$prop_data['prop_cover']."'></div>
								<div class='property-info'>
									<div class='property-title'>".$prop_data['prop_title']."</div>
									<div class='property-seller'>".$prop_data['owner_name']."</div>
									<div class='property-seller-rates'><span>".$prop_data['owner_seller_rate']."/10</span> <i class='far fa-dot-circle'></i><span>".$prop_data['prop_city']."-".$prop_data['prop_dist']."</span></div>
									<div class='property-price'>
										<div class='property-moneyprice'>".$prop_data['prop_price']." TL</div>
									</div>
								</div>
							</a>
							<div class='property-swaprice'><button>".$prop_data['prop_swaptitle']."</button></div>
							<div class='property-swapdesc-hover'>
								<p>
									".htmlspecialchars_decode($prop_data['prop_swapdesc'])."
								</p>
							</div>
						</div>";
					}
				echo json_encode(array(
					'properties' => $prop_list,
					'prop_num' => $property_num,
					'pagination' => $pagination

				));
				}else
				{
					$prop_list[0] = ".";
					$prop_list[1] = "<label>İlan bulunamadı</label>";
					echo json_encode(array(
						'properties' => $prop_list,
						'prop_num' => 0,
						'pagination' => $pagination
					));
				}



?>