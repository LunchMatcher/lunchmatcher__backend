<div class="modal-dialog cust_dilog">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#00A06F;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Match History</h4>
      </div>
      <div class="modal-body list-group panel">
	   <?php 
	   
	   	$i=0;
			if(count($users)!=0){ 
				foreach($users as $val){		?>
				<div class="list-group-item">
                	<div class="col-lg-5">
					<span class="prof_img">
                    <img title="<?php echo $val['log_id'];?>" src="<?php echo $val['picture_url']?$val['picture_url']:base_url('assets/images/no_image.png');?>" alt="profile">
                    </span>
					<span class="point-title"><?php echo $val['formatted_name'];?><br/>
			   		<span> <?php echo $val['headline'];	 ?></span><br/>
                        <?php if($val['rating']==1){?>
						<img src="<?php echo base_url(); ?>assets/images/star1.png"  height="15" width="100" />
						<?php }elseif($val['rating']==2){ ?>	
						<img src="<?php echo base_url(); ?>assets/images/star2.png"  height="15" width="100" />
						<?php }elseif($val['rating']==3){ ?>
						<img src="<?php echo base_url(); ?>assets/images/star3.png"  height="15" width="100" />
						<?php }elseif($val['rating']==4){ ?>
						<img src="<?php echo base_url(); ?>assets/images/star4.png"  height="15" width="100" />
						<?php }elseif($val['rating']==5){ ?>
						<img src="<?php echo base_url(); ?>assets/images/star5.png"  height="15" width="100" />
						<?php }else{?>
						<img src="<?php echo base_url(); ?>assets/images/star0.png"  height="15" width="100" />
						<?php } ?>
                	</span>
                    <span class="gray"> on <?php echo date($this->config->item('date_format'), strtotime($val['schedule_timefrom']));	 ?></span> 
                   </div>
                   <div class="col-lg-1">at</div>
                   <div class="col-lg-6">
                	<span class="prof_img2">
                    <?php
						if($val['photo_key']!=''){?>
						<img width="80" height="70" src="https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&amp;photoreference=<?php echo $val['photo_key']; ?>&amp;key=AIzaSyCSB01UwvVbd63eV_scq-rOD4AEirD8z9Q">
						<?php }else{ ?>
						<img src="<?php echo base_url(); ?>assets/images/no_image.png" width="80" height="70"/>
						<?php }
					?>
                    </span>
					<span class="point-title"><?php echo $val['name'];?><br/><span class="gray">  <?php echo $val['address'];	 ?></span></span>
                    
                   </div>
                    <div class="clearfix"></div>
                </div>	
	 <?php }
	 	
		}else{ 
			 echo "There are no previous matches found...";
	 }?> 								
      </div>
	  
      
    </div>
  
  </div>
 