<?php
	$error = '';
    $radius='';
	$data='';
	$zipcode='';
	$search_address='';
	if(isset($_POST['radius']) or isset($_GET['search_address'])){
        if(isset($_GET['search_address'])) $address = urldecode($_GET['search_address']);
        if(isset($_POST['search_address'])) $address = trim($_POST['search_address']);
        if((int)isset($_GET['radius']) > 0) $radius = (int)$_GET['radius'];
        if((int)isset($_POST['radius']) > 0) $radius = (int)$_POST['radius'];
        if($radius == 0) $radius = 50;
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$remoteIP=explode(':',$_SERVER['HTTP_X_FORWARDED_FOR']);
			$_SERVER['REMOTE_ADDR']= $remoteIP[0];
		}
		$data = array();
		$geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address='. urlencode($location) .'&sensor=false');  
      $output		= 	json_decode($geocode);  
      $objlocation  = 	$output->results[0]->geometry->location; 
	  $latitude 	= 	$objlocation->lat;  
      $longitude 	= 	$objlocation->lng;  
      $range 		= 	$_POST['radius'];  
	  $lat_range 	= 	$range/69.172;  
           $lon_range 	= 	abs($range/(cos($latitude) * 69.172));  
           $min_lat 	= 	number_format($latitude - $lat_range, "4", ".", "");  
           $max_lat 	= 	number_format($latitude + $lat_range, "4", ".", "");  
           $min_lon 	= 	number_format($longitude - $lon_range, "4", ".", "");  
           $max_lon 	= 	number_format($longitude + $lon_range, "4", ".", "");  
           $sqlstr 		= 	mysql_query("SELECT * FROM my_listings latitude BETWEEN '".$min_lat."' AND '".$max_lat."' AND longitude BETWEEN '".$min_lon."' AND '".$max_lon."' ");  
		if (mysql_numrows($sqlstr) != 0) {  
            while ($row = mysql_fetch_array($sqlstr)) {  
			   $data['title'];
			   $data['address'] ;      
            }  
        }  	
}
		
		$u=0;
		
	foreach($data as $key => $value)
	   {
		$u	=	$key+1;
			$city 		    = $value['city'];
		  	$state		    = $value['state_code'];
  			$phone 		    = $value['phone'];
  			$center_name 	= $value['center_name']; 
  			$url 			= "http://".$value['website_url'];  
		    $street1 		= $value['street'];
  			$street2 		= $value['street2'];
  			$country  	= 'US';
  			$zipcode		= $value['zip'];
			$lat = $value['lat'];
			$long = $value['lon'];
		
		
		
		 
	$html_content  =  $center_name;
	
	if($street1)
	{
		$html_content.="<br>".$street1;
	}
	if($street2)
	{
		$html_content.="<br>".$street2;
	}
	if($city)
	{
		$html_content.="<br>".$city;
	}
	if(!empty($state) && !empty($city) && $city!=$state)
	{
		$html_content.=", ".$state;
	}
	if(!empty( $zipcode)){
		$html_content.=", ".$zipcode;
		}
	if($country)
	{
		$html_content.="<br>".$country;
	}
	if($url)
	{
		$html_content=$html_content.'********'.$url;
	}
    	$var  .= "['$html_content', $lat, $long, $u],";
		}
	$var = rtrim($var,',')	
	if((int)$radius == 0) $radius = 50;
    
	if(has_post_thumbnail()): 
	    global $post;
		$data = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
		$eplodedata = explode('/',$data);
		$count = count($eplodedata);
		$lastclrname = $eplodedata[$count - 1];
		$colorcode 	= explode('^',$lastclrname);
		$clrcdevlue = $colorcode[1];
	 ?>
    <div class="banner" style="background-color:#<?php echo $clrcdevlue ?> !important;">
        <?php $attachment = get_post(get_post_thumbnail_id());  ?>
        <div class="container">
            <div class="frame">
                <?php if($image_content = $attachment->post_content): ?>
                    <div class="text-box">
                        <?php echo apply_filters('the_content',$image_content) ?>
                    </div>
                <?php endif ?>
                <?php $large_src = wp_get_attachment_image_src(get_post_thumbnail_id(),'thumbnail_1496x500') ?>
                <?php $small_src = wp_get_attachment_image_src(get_post_thumbnail_id(),'thumbnail_748x250') ?>
                <div class="image-box">
                    <picture>
                        <!--[if IE 9]><video style="display: none;"><![endif]-->
                        <source srcset="<?php echo $small_src[0] ?>, <?php echo $large_src[0] ?> 2x">
                        <!--[if IE 9]></video><![endif]-->
                       <?php echo remove_thumbnail_dimensions(wp_get_attachment_image(get_post_thumbnail_id(),'thumbnail_748x250')) ?>
                    </picture>
                </div>
            </div>
        </div>
    </div>
<?php endif;?>
<div class="container">
				<div class="filter-body">
					<!-- contain the main content of the page -->
					<div id="content">
						<section class="filter-section">
							<div class="title-box-add">
								<h2><a href="#">Find a Brain Training Center</a></h2>
								<p>Search below to find your LearningRx Brain Training Center and view local information.</p>
							</div>
						</section>
						<!-- filter form -->
						<form action="" class="filter-form validate-form" method="post">
							<strong class="caption">Start Your Search</strong>
							<div class="frame">
								<div class="box required-holder">
									<label for="ff1">Enter City or Zip Code:</label>
									<div class="input-box">
										<span class="error-placeholder">Please enter a valid address, city, state or zip code</span>
										<input class="required" type="text" id="ff1" placeholder="Start your search&#8230;" name="search_address" value="<?php echo $_POST['search_address']; ?>">
									</div>
								</div>
								<div class="box required-holder">
									<label for="ff2">&nbsp;</label>
									<div class="input-box">
										<input type="hidden" name="radius" value="300" />
                                        
										<button type="submit">find</button>
									</div>
								</div>
							</div>
						</form>
	
 <style>
    #map {
        position: relative;
        top: 0;
        left: 0;
        width: 100% !important;
        height: 500px !important;
    }
	.gm-style-iw + div img{
		max-width:59px !important;
	}
	.gm-style-iw > div > div {
    	line-height: 17px;
		overflow: hidden !important;
	}
</style>
<div id="map"> </div>
<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
<script type="text/javascript">
	   var locations = [ <?php echo $var;?> ];

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 2,
      center: new google.maps.LatLng(47.0000, 2.0000),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });
	
    var infowindow = new google.maps.InfoWindow();
    var marker, i;
	var blue=false;
	
    for (i = 0; i < locations.length; i++) 
	{  	
		//icone = '<?php echo bloginfo('stylesheet_directory'); ?>/images/legend-red.png';
		icone = 'http://lrxnew.learningrx.com/wp-content/themes/learningrx/images/marker-active.svg';
		blue=false;
     
	 	marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
		 icon: icone,
        map: map
      });
	  
	  if(blue){
		marker.setZIndex(500);	
	 }
	//var k='';
     //k = explode("********",locations[i][0],"");

	 	var mm='';
		google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {
        return function() {
			
			
			var mm='';
			var mm = locations[i][0].split("********");				
						
				if(locations[i][4]==0)
				{
					//infowindow.setContent('BrainRx Master Developer <br>'+mm[0]);
					infowindow.setContent(mm[0]);
				}
				else
				{
					infowindow.setContent(mm[0]);
				}			
			
			infowindow.open(map, marker);
        }
      })(marker, i));
	  
	  google.maps.event.addListener(marker, 'mouseout', (function(marker, i) {
        return function() {
          infowindow.close(map, marker);
        }
      })(marker, i));

	  google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
					var nn='';
					var nn = locations[i][0].split("********");	
					if(typeof nn[1] != "undefined")
					{
						window.open(nn[1],'_blank');
					}else{
						console.log('No web address found:');
					}
		 }
      })(marker, i));

	}
var map = new google.maps.Map(document.getElementById("map"), mapOptions);
google.maps.event.addDomListener(window, "resize", function() {
   var center = map.getCenter();
   google.maps.event.trigger(map, "resize");
   map.setCenter(center); 
});
   infowindow.setBackgroundColor("#000");
</script>
						<div class="result-holder" style="margin-top:2%;">
							<section class="result-bar">
                                <?php
								if(is_array($data))
								{
								 $totlcenter = count($data);
								 ?>
                                 <h2>Your Search Results</h2>
								<p><span class="count-holder"><?php echo $totlcenter; ?></span> LearningRx centers match your search criteria</p>
							<?php } ?>
                            </section>
							<section class="result-row no-results-row">
								<strong class="little-caption">No center in your area? </strong>
								<p><a href="#">Click here to learn about opening your own LearningRx Brain Training Center, or call us at 719-264-8808 to find out about other opportunities that might be available in your area</a></p>
							</section>
                            
							<?php
							if(is_array($data))
							{
							foreach($data as $key => $value)
							{
								if(empty($value['website_url'])){						
                                        	$url = strToLower(strtr(trim(substr($value['center_name'], strpos($value['center_name'], '-')+1, strLen($value['center_name']))), ' ', '-'));
                                        }
								else{
						if(strstr($value['website_url'],'http://'))
							$url = $value['website_url'];
						else
							$url = "http://".$value['website_url'];
					}
					$direction_from = $value['street']." ".$value['city']." ".$value['zip']." ".$usa_states[$value['state']];
                                        $direction_from = str_replace(array(' '), array('+'), $direction_from);
                                        
                                        $direction_to = str_replace(array(' '), array('+'), $zipcode." ".$address);		
										
							 ?>
                            
                            <section class="result-row">
								<div class="box wide">
									<strong class="location-title"><a href="#"><?php echo $value['center_name']; ?></a></strong>
									<address>
										<?php echo $value['street']; ?>, <?php echo $value['street2']; ?> <br>
										<?php echo $value['city'] ?>, <?php echo $value['state']; ?> <?php echo $value['zip']; ?>
									</address>
									<span class="distance"><?php echo Round($value['distance'],1) ?> miles from location</span>
								</div>
								<div class="box short">
									<strong class="big-caption">Contact Center</strong>
									<a class="phone" href="tel:<?php echo $value['area_code'].$value['phone']; ?>"><?php echo "(".$value['area_code'].") ".$value['phone']; ?></a> <br>
									<a href="<?php echo $url ?>" target="_blank" class="btn">View Center Website</a>
								</div>
							</section>
							
							<?php }
							}
							else if($_POST['search_address'] && !is_array($data)) {
								?>
                                <section class="result-row no-results-row" style=" display:block !important;">
								<strong class="little-caption">No center in your area? </strong>
								<p><a href="#">Click here to learn about opening your own LearningRx Brain Training Center, or call us at 719-264-8808 to find out about other opportunities that might be available in your area</a></p>
							</section>
                                <?php
								}
							 ?>
							
							<?php
                            $centers = mysql_query("select email_addr, center_name, state_code, city, area_code, phone, addr_street, addr_unit, addr_street2, zip_code from lrx_center as t1, lrx_address as t2 where t1.id_addr = t2.id_addr and t1.status = 2 and is_branch_center = 0 order by center_name asc") or die(mysql_error());
	                   	if($centers){
							while($data_open_soon = mysql_fetch_array($centers)){  
							 ?>
                            <section class="result-row disabled">
								<div class="box wide">
									<span class="open-soon">Opening Soon</span>
									<strong class="location-title"><a href="#"><?php echo $data_open_soon['center_name']; ?></a></strong>
									<address>
										<?php if(!empty($data_open_soon['addr_street']) or !empty($data_open_soon['addr_unit'])) echo $data_open_soon['addr_street']." ".$data_open_soon['addr_unit']."<br />"; ?>, <?php if(!empty($data_open_soon['addr_street2'])) echo $data_open_soon['addr_street2'] ?> <br>
										<?php $data_open_soon['city'] ?>, <?php echo $usa_states[$data_open_soon['state_code']] ?> <?php echo $data_open_soon['zip_code']; ?>
									</address>
									<span class="distance">47.5 miles from location</span>
								</div>
							</section>
                           <?php }
							}
							mysql_select_db(DB_NAME);
						    ?> 
						</div>
					</div>
				</div>
			</div>