

<div class="modal-dialog cust_dilog">
    <div class="modal-content ">
      <div class="modal-header" style="background-color:#00A06F;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Feedbacks</h4>
      </div>
      <div class="modal-body">
        <div id="MainMenu">
		  <div class="list-group panel">
		  
		  <?php 
		  
		  if(count($feedback)!=0){ 
		  	foreach($feedback as $feed){ ?>
            	<div class="row form-group">            
                    <a class="list-group-item">
                    	 <span style="float:left">	
						<?php
                            if($feed['picture_url']!=''){?>
                            <img width="23" height="23" src="<?php echo $feed['picture_url'];?>">
                            <?php }else{ ?>
                            <img src="<?php echo base_url(); ?>assets/images/no_image.png" width="23" height="23"/>
                            <?php }
                        ?>&nbsp;&nbsp;
                            </span>
                        <strong><?php echo $feed['name'];?></strong> On: <?php 
						$date_format = $this->config->item('date_format')?$this->config->item('date_format'):'M d, Y';
						echo date($date_format,strtotime($feed['created_time']))  ;?>
                    
                    </a>
                    <div class="list-group-item">
                        <div class="col-lg-12">  
                           
                           <?php if(($feed['rating_val'])==1) { ?> 
				           <img src="<?php echo base_url(); ?>assets/images/star1.png" height="12">
				           <?php } ?>
						   <?php if(($feed['rating_val'])==2) { ?> 
				           <img src="<?php echo base_url(); ?>assets/images/star2.png" height="12">
				           <?php } ?>
						   <?php if(($feed['rating_val'])==3) { ?> 
				           <img src="<?php echo base_url(); ?>assets/images/star3.png" height="12">
				           <?php } ?>
						   <?php if(($feed['rating_val'])==4) { ?> 
				           <img src="<?php echo base_url(); ?>assets/images/star4.png" height="12">
				           <?php } ?>
						   <?php if(($feed['rating_val'])==5) { ?> 
				           <img src="<?php echo base_url(); ?>assets/images/star5.png" height="12">
				           <?php } ?>
                           <br>
                           <?php echo nl2br($feed['feed_back']);?>
                            
                        </div>
                        <div class="clearfix"></div>
                    </div>
                 </div>
      			<div class="clearfix"></div>
			  <?php }
			}else{ 
				 echo "No feedback";
			 }?>
		  
			
			
			
		  </div>
		</div>
      </div>

    </div>
  </div>
<script>
function changearrow(id)
{
	if($("#div_"+id).data("val")==1){
		$("#div_"+id).removeClass("fa-angle-down").addClass("fa-angle-left");
		$("#div_"+id).data("val",0);
	}else{
		$("#div_"+id).removeClass("fa-angle-left").addClass("fa-angle-down");
		$("#div_"+id).data("val",1);
	}
}

</script>