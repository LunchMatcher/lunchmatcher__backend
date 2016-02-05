<div class="modal-dialog cust_dilog">
 

    <div class="modal-content">
      <div class="modal-header" style="background-color:#00A06F;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Point Details</h4>
      </div>
      <div class="modal-body list-group panel">
	   <?php 
	   
	   	$i=0;
			if(count($details)!=0){ 
				foreach($details as $val){		?>
				<div class="list-group-item">
                <?php if( $val['point_id'] == 2 || $val['point_id'] == 3 || $val['point_id'] == 4 || $val['point_id'] == 5) :?>  
					<span class="prof_img">
                    <img src="<?php echo $val['picture_url']?$val['picture_url']:base_url('assets/images/no_image.png');?>" alt="profile">
                    </span>
                 <?php elseif($val['point_id'] == 1) :?>
                	<span class="prof_img">
                    	<i class="icon_register">R</i>
                    </span>
                <?php elseif($val['point_id'] == 6) :?>
                	<span class="prof_img">
                    	<i class="icon_below_rate glyphicon glyphicon-star-empty"></i>
                    </span>
                <?php elseif($val['point_id'] == 7) :?>
                	<span class="prof_img">
                    	<i class="icon_availability">A</i>
                    </span>   
                 <?php elseif($val['point_id'] == 8) :?>
                	<span class="prof_img">
                    	<i class="icon_above_rate glyphicon glyphicon-star"></i>
                    </span>
                 <?php elseif($val['point_id'] == 9) :?>
                	<span class="prof_img">
                    	<i class="icon_share_post glyphicon glyphicon-share"></i>
                    </span>
                 <?php elseif($val['point_id'] == 10) :?>
                	<span class="prof_img">
                    	<i class="icon_purchase">P</i>
                    </span>
                <?php endif; ?>
					<span class="point-title"><?php echo $val['title'];?><br/>
			   			<span> on <?php echo date($this->config->item('date_format'), strtotime($val['created_date']));	 ?></span>
                	</span>
					<span class="point-cnt"><?php echo $val['points']?number_format($val['points']):0;?> points</span>
			   </div>	
	 <?php }
	 	
		}else{ 
			 echo "No details";
	 }?> 								
      </div>
	  
      
    </div>
  
  </div>
 