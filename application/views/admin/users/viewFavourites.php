<div class="modal-dialog cust_dilog">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#00A06F;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">
			Favourite Restaurants
		</h4>
      </div>
      <div class="modal-body">

		
		<div id="MainMenu">
		  <div class="list-group panel">
		
				<div class="col-md-12 col-sm-12">
				
				<div class="clearfix"></div>
					
					<div class="list-group panel">
						  <?php 
						  if(count($details)!=0){ 
							foreach($details as $val){
							
							 ?>
							<div class="col-md-12 list-group-item " style="background-color:f5f5f5" >
								<div class="col-md-4">	
									<img src="https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference=<?php echo $val['photo_key'];?>&key=<?php echo $googleKey; ?>"  height="100" width="150" />
								</div>
								<div class="col-md-8">
									Restaurant Name : <?php echo $val['name'];?><br>
									Address : <?php echo $val['address'];?><br>
									Phone : <?php echo $val['phone'];?>
								</div>
								
							</div>
							  <?php }
							}else{ 
								 echo "No Favourite restaurants";
							 }?>
					  </div>
					
					
					
					
				</div>
				<div class="col-md-6 col-sm-12">

				
				</div>
				<div class="clearfix"></div>
	
			
		  </div>
		</div>
		
		
        <div id="MainMenu">
		  
		</div>		
		
      </div>

    </div>
  </div>
 

