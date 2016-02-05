<div class="modal-dialog cust_dilog">
    <div class="modal-content ">
      <div class="modal-header" style="background-color:#00A06F;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Feedbacks</h4>
      </div>
      <div class="modal-body">
        <div id="MainMenu">
		  <div class="list-group panel">
		  
		  
		  Comming soon
		 <?php /*?> <?php 
		  
		  if(count($feedback)!=0){ 
		  	foreach($feedback as $feed){ ?>
				<a href="#demo<?php echo $feed['feed_id'];?>" class="list-group-item list-group-item-success" data-toggle="collapse" data-parent="#MainMenu" onClick="changearrow('<?php echo $feed['feed_id'];?>')" >
				Given BY : <?php echo $feed['first_name'].' '.$feed['last_name'];?><br>
				Rating : <?php echo $feed['rating'];?>
				
				<i class="fa pull-right fa-angle-down" data-val="1" id="div_<?php echo $feed['feed_id'];?>" ></i>
				</a>
				<div class="collapse" id="demo<?php echo $feed['feed_id'];?>">
				  <a href="javascript:;" class="list-group-item-sub">Feedback :  <?php echo $feed['feed_back'];?></a>
				</div>
			  
			  <?php }
			}else{ 
				 echo "No feedback";
			 }?><?php */?>
		  
			
			
			
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