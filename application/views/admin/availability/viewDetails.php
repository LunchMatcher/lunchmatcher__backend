<?php //echo "<pre>"; print_r($all_location_details); exit;?>
<div class="modal-dialog cust_dilog">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#00A06F;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">
		Available from 
		<?php 
			$timezone  = date_default_timezone_get();
			$date	  = new DateTime($details['match_time_from'], new DateTimeZone($timezone));
			$date->setTimezone(new DateTimeZone($details['timezone']?$details['timezone']:$timezone));
			echo $date->format($this->config->item('time_format'));
		?>
		to 
		<?php
			$timezone  = date_default_timezone_get();
			$date	  = new DateTime($details['match_time_to'], new DateTimeZone($timezone));
			$date->setTimezone(new DateTimeZone($details['timezone']?$details['timezone']:$timezone));
			echo $date->format($this->config->item('time_format')); 
		 ?>
         @ 
         <?php echo $details['location']; ?>
		</h4>
      </div>
      <div class="modal-body">
         
		<select id="available" class="select_val pull-right" name="select">
        <option>Location Match With</option> 
        <?php if(count($getmatchlocation)!=0){ 
              foreach($getmatchlocation as $match){ ?>
        <option value="<?php echo $match['match_logid'];?>" name="<?php echo $match['name'];?>"><?php echo $match['name'];?></option>
        <?php }}?>
        </select>
		<div id="MainMenu">
		  <div class="list-group panel">
		
				<div class="col-md-12 col-sm-12">
				<div class="col-md-10 col-sm-8 col-xs-12 pd0">
				<h4>Available Restaurants
				</h4></div>
				<div class="clearfix"></div>
					
					<div class="list-group panel">
						  <?php 
						  if(count($all_location_details)!=0){ 
							foreach($all_location_details as $val){
							
							 ?>
							<div class="col-md-12 list-group-item venuediv dev_<?php echo $val['venue_id'];?>" style="background-color:f5f5f5"  data-attr="<?php echo $val['venue_id'];?>">
								<div class="col-md-4">	
									<img src="https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference=<?php echo $val['photo_key'];?>&key=<?php echo $googleKey; ?>"  height="70" width="100" />
								</div>
								<div class="col-md-8">
									Restaurant Name : <?php echo $val['name'];?><br>
									Address : <?php echo $val['address'];?><br>
                                    <!--Phone : <?php echo $val['phone'];?><br>-->
									Rating : <?php $num= intval($val['rating']);?>
									<?php if($num==1){?>
										<img src="<?php echo base_url(); ?>assets/images/star1.png"  height="15" width="100" />
									<?php }elseif($num==2){ ?>	
										<img src="<?php echo base_url(); ?>assets/images/star2.png"  height="15" width="100" />
									<?php }elseif($num==3){ ?>
										<img src="<?php echo base_url(); ?>assets/images/star3.png"  height="15" width="100" />
									<?php }elseif($num==4){ ?>
										<img src="<?php echo base_url(); ?>assets/images/star4.png"  height="15" width="100" />
									<?php }elseif($num==5){ ?>
										<img src="<?php echo base_url(); ?>assets/images/star5.png"  height="15" width="100" />
									<?php }else{?>
										<img src="<?php echo base_url(); ?>assets/images/star0.png"  height="15" width="100" />
									<?php } ?>
									<br>
									<?php if($val['favourite']==1){?>
									<img src="<?php echo base_url(); ?>assets/images/fav.png"  width="18" /> Favourite
									<?php } ?>
								</div>
								
							</div>
							  <?php }
							}else{ 
								 echo "No restaurants here";
							 }?>
					  </div>
					
					
					
					
				</div>
				<div class="col-md-6 col-sm-12">

				
				</div>
				<div class="clearfix"></div>
	
			
		  </div>
		</div>
		
		
        <div id="MainMenu">
		  <div class="list-group panel">
		
				<div class="clearfix"></div>
				
				<div class="col-lg-12">
				 <div id="map" style="margin-top:0px;">
        		 </div>
				
				</div>
				<div class="clearfix"></div>
	
			
		  </div>
		</div>		
		
      </div>
		
        
        <div>
        <div class='row form-group'>
            <div class="col-lg-2"><label>Location :</label></div>
            <div class='col-lg-10'><?php echo $details['location'];?></div>
        </div>
  		<div class='row form-group'>
            <div class="col-lg-2"><label>Latitude :</label></div>
            <div class='col-lg-10'><?php echo $lattitude?></div>
        </div>
         <div class='row form-group'>
            <div class="col-lg-2"><label>Longitude :</label></div>
            <div class='col-lg-10'><?php echo $longitude?></div>
        </div>
         <div class='row form-group'>
            <div class="col-lg-2"><label>Radius :</label></div>
            <div class='col-lg-10'><?php echo $match_radius?></div>
        </div>
        
  </div>
  
  
    </div>
  </div>
  
  
 
<script type="text/javascript">
	 
	var geocoder = new google.maps.Geocoder();
	var lat = <?php echo $details['match_latitude']; ?>;
	var long = <?php echo $details['match_longitude']; ?>;
	var address;
	
	var latlng = new google.maps.LatLng(lat, long);
	geocoder.geocode({'latLng':latlng},function(data,status){
		if(status == google.maps.GeocoderStatus.OK){
		address = data[1].formatted_address; //this is the full address
		$('#addr').html(address);
		//alert(address);
		/*	for(var i=0; i<data[1].address_components.length; i++){
			 
			if(data[1].address_components[i].types[0] == "administrative_area_level_1"){
			 
			//alert(data[1].address_components[i].short_name); // we can only get the "State" part from the full formatted address
			 
			}
			 
			}*/
		 
		}
	 
	})
	 
</script>
<script type="text/javascript">


	  	var markerArray = []; 
    	var locations = [<?php echo $locations; ?> ]; 
		
	  	var map = new google.maps.Map(document.getElementById('map'), {
      	zoom: 16,
      	center: new google.maps.LatLng(<?php echo $details['match_latitude']; ?> ,<?php echo $details['match_longitude']; ?> ),
      	mapTypeId: google.maps.MapTypeId.ROADMAP
    	});

    	var infowindow = new google.maps.InfoWindow();

    	var marker, i;
    for (i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
		//position: new google.maps.String(locations[i][0]),
        map: map
      });

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(address);
          infowindow.open(map, marker);
        }
      })(marker, i));
	   markerArray.push(marker);
	   createRadius(<?php echo $match_radius;?>) ;
    }
	
	function createRadius(dist) 
	{
    var myCircle = new google.maps.Circle({
        center: markerArray[markerArray.length - 1].getPosition(),
        map: map,
        radius: dist,
        strokeColor: "#FF0000",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#FF0000",
        fillOpacity: 0.35
    });
    var myBounds = myCircle.getBounds();
    
    //filters markers
    for(var i=markerArray.length;i--;){
         if(!myBounds.contains(markerArray[i].getPosition()))
             markerArray[i].setMap(null);
    }
    map.setCenter(markerArray[markerArray.length - 1].getPosition());
    map.setZoom(<?php if($map_zoom != '') { echo $map_zoom+1; } else { echo "12";} ?> );
}

$('select').on('change', function () {
 
   $('.venuediv').removeClass('sucess');
    var logid=$( "#available" ).val();
   //your code here...
   var venuearray = [];
   $( ".venuediv" ).each(function() {
   venuearray.push($(this).attr('data-attr'));
   });
 
   $.ajax({                   
							   type:"POST",
							   url:"<?php echo base_url();?>admin/availability/getAvailability",
							   data:{match_logid:logid},
							   success:function(data)
							   {
							  
                              var data= data.split(",")
                              for (var i = 0; i < data.length; i++) {
							  var data_new=data[i];
							  var exist=jQuery.inArray(data_new,venuearray);
							  
							  if((exist)>-1){
								 	$('.dev_'+data_new).addClass('sucess');
								 
							  }
							    
							  }
			     			
									
							  }
							   
					  });	
});
</script>

 <style>
        #map {
            height: 350px;
            width: 100%;
        }
		.sucess
		{
		background-color:#dff0d8;
		}
    </style>