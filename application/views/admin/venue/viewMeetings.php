<div class="modal-dialog cust_dilog">
 

    <div class="modal-content">
      <div class="modal-header" style="background-color:#00A06F;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Meeting Details</h4>
      </div>
      <div class="modal-body list-group panel">
   
	 <?php if(sizeof($details) > 0)  :  $i=0;?>
     <?php foreach($details as $val) :  $i=$i+1;?>
		<div class="row form-group">            
            <a class="list-group-item">
            	Meeting between 
				<?php echo date(getConfigValue('time_format'), strtotime($val['schedule_timefrom']));	 ?>
                and  
                <?php echo date(getConfigValue('time_format'), strtotime($val['schedule_timeto']));	 ?>
                on
                <?php echo date(getConfigValue('date_format'), strtotime($val['schedule_timeto']));	 ?>
            </a>
            <div class="list-group-item">
                <div class="col-lg-6">       
                    <span style="float:left">	
                <?php
                    if($val['from_user_picture_url']!=''){?>
                    <img width="50" height="50" src="<?php echo $val['from_user_picture_url'];?>">
                    <?php }else{ ?>
                    <img src="<?php echo base_url(); ?>assets/images/no_image.png" width="50" height="50"/>
                    <?php }
                ?>&nbsp;&nbsp;
                    </span>
                    <span style="float:left">	
                <b><?php echo $val['from_user_name']; ?></b><br /><?php echo $val['from_user_company']; ?><br/><?php echo $val['from_user_position']; ?>
                    </span>
                </div>
                <div class="col-lg-6">
                    <span style="float:left">	
                <?php
                    if($val['to_user_picture_url']!=''){?>
                    <img width="50" height="50" src="<?php echo $val['to_user_picture_url'];?>">
                    <?php }else{ ?>
                    <img src="<?php echo base_url(); ?>assets/images/no_image.png" width="70" height="50"/>
                    <?php }
                ?>&nbsp;&nbsp;
                    </span>
                    <span style="float:left">	
                <b><?php echo $val['to_user_name']; ?></b><br /><?php echo $val['to_user_company']; ?><br /><?php echo $val['to_user_position']; ?>
                    </span>
                </div>
                <div class="clearfix"></div>
            </div>
         </div>
      <div class="clearfix"></div>
	  <?php endforeach; ?>
      <?php else: ?>
	 <div class="list-group-item col-lg-12">No Scheduled Venues For this Date...</div>
	  <?php endif; ?>
           
      
      </div>
	  
      
    </div>
  
  </div>
 