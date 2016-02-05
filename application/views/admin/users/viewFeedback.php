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
				<a href="#demo<?php echo $feed['feed_id'];?>" class="list-group-item list-group-item-success" data-toggle="collapse" data-parent="#MainMenu" onClick="changearrow('<?php echo $feed['feed_id'];?>')" >
				Feedback : <?php echo $feed['feed_back'];?><br>
				Given BY : <?php echo $feed['first_name'].' '.$feed['last_name'];?><br>
				Rating :   <?php if(($feed['rating_val'])==1) { ?> 
				           <img src="<?php echo base_url(); ?>assets/images/star1.png">
				           <?php } ?>
						   <?php if(($feed['rating_val'])==2) { ?> 
				           <img src="<?php echo base_url(); ?>assets/images/star2.png">
				           <?php } ?>
						   <?php if(($feed['rating_val'])==3) { ?> 
				           <img src="<?php echo base_url(); ?>assets/images/star3.png">
				           <?php } ?>
						   <?php if(($feed['rating_val'])==4) { ?> 
				           <img src="<?php echo base_url(); ?>assets/images/star4.png">
				           <?php } ?>
						   <?php if(($feed['rating_val'])==5) { ?> 
				           <img src="<?php echo base_url(); ?>assets/images/star5.png">
				           <?php } ?>
                           <br>
				
				
				Created On: <?php echo date("M d, Y ",strtotime($feed['created_time']))  ;?>

				</a>
				
			  
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