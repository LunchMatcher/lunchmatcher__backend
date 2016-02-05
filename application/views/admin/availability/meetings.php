<link href="<?php echo base_url('assets/css'); ?>/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url('assets/js'); ?>/bootstrap-datepicker.js"></script>
<script type="text/javascript">
			$(function () {
				$('.datetimepicker1').datepicker({
					format: 'yyyy-mm-dd',
					orientation: "auto top",
					endDate: '1d'
				});
			    $('.fmember').popover({placement: 'top', content: $(this).data('content'),  html: true, trigger: "hover"});
				$('.tmember').popover({placement: 'top', content: $(this).data('content'),  html: true, trigger: "hover"});
				
            });
	
</script>


<div class="background_loader" style="display:none;">
	<img src="<?php echo base_url() ?>assets/images/ploader.GIF" class="ajax_loader" width="75"/>
</div>
<form name="userMasterForm" id="userMasterForm" method="post" action="">
<input type="hidden" name="actions" id="actions" value="" />
  <div class="row" id="Title">
    <div class="col-lg-12"><legend>Meeting Scheduled</legend></div>
  </div>
  
 <div class="row" <?php if(!$message && !$error){?>style="display:none" <?php } ?> id="msg_head">
      <?php if($message){?>
      <div class="alert alert-success col-lg-12 col-offset-1"><?php echo $message;?><!--danger-info-->
        <button data-dismiss="alert" class="close" type="button">x</button>
        
      </div><?php } if($error){ ?>
      
      
      <div class="alert alert-danger col-lg-12 col-offset-1"><?php echo $error;?><!--danger-info-->
        <button data-dismiss="alert" class="close" type="button">x</button>
        
      </div><?php } ?>
  </div>

    <div class="row">
		<div class='col-lg-4'>
            <div class="form-group"><label>Start Date :</label>
                <div class='input-group date datetimepicker1' id='datetimepicker1'>
                    
					<input type='text' class="form-control" id="startDate" name="startDate" data-date-format="yyyy-mm-dd"
					value="<?php if($startDate!=""){echo $startDate;}?>" />
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
      

        <div class='col-lg-4'>
            <div class="form-group"><label>End Date :</label>
                <div class='input-group date datetimepicker1' id='datetimepicker2'>
                    
					<input type='text' class="form-control" id="endDate" name="endDate" data-date-format="yyyy-mm-dd"
					value="<?php if($endDate!=""){echo $endDate;}?>" />
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
	
		<div class='col-lg-4'>
            <div class="form-group">
               <button id="selectDate" class="btn btn-info " type="button" style="margin-top:23px;">Go!</button>
                    
             </span>
                
            </div>
        </div>
	
		
      
		
		
    </div>


  

  <table class="table table-bordered table-hover"> 
    <thead><!--<a href="javascript:" id="deleteSel" class="delete_icon">Delete</a>-->
      <tr>
	   	<th> 
		No 
		</th>
        <th> 
		First Member 
		</th>
        <th> 
		Second Member 
		</th>
        <th> 
		Venue
		</th>
        <th>
		Meeting Time
		</th>
      </tr> 
    </thead>
    <tbody>
	 <?php if(sizeof($meetingsBydate) > 0)  :
	 
	
	// echo "<pre>"; print_r($meetingsBydate); echo "</pre>"; exit;
	 $j=1; ?>
     <?php foreach($meetingsBydate as $val) : ?>
		
        <?php 
		if($val['f_rating']){ 
			switch($val['f_rating']){
				case 1 :
					$f_star = "<img src='".base_url()."assets/images/star1.png' height='12' />";
				break;
				case 2 :
					$f_star = "<img src='".base_url()."assets/images/star2.png' height='12' />";
				break;
				case 3 :
					$f_star = "<img src='".base_url()."assets/images/star3.png' height='12' />";
				break;
				case 4 :
					$f_star = "<img src='".base_url()."assets/images/star4.png' height='12' />";
				break;
				case 5 :
					$f_star = "<img src='".base_url()."assets/images/star5.png' height='12' />";
				break;
			}
		}
		?>
        <?php 
		if($val['t_rating']){ 
			switch($val['t_rating']){
				case 1 :
					$t_star = "<img src='".base_url()."assets/images/star1.png' height='12' />";
				break;
				case 2 :
					$t_star = "<img src='".base_url()."assets/images/star2.png' height='12' />";
				break;
				case 3 :
					$t_star = "<img src='".base_url()."assets/images/star3.png' height='12' />";
				break;
				case 4 :
					$t_star = "<img src='".base_url()."assets/images/star4.png' height='12' />";
				break;
				case 5 :
					$t_star = "<img src='".base_url()."assets/images/star5.png' height='12' />";
				break;
			}
		}
		?>
      <tr>
      	<td style="padding-right:0;"><?php echo $j; $j++;?></td>         
        <td>
          <a title="Feedback from  <?php echo stripslashes($val['t_member_first_name'])?>" class="fmember" rel="popover" data-content="
          <?php if($val['f_no_show']=='N'){ ?>
          			<span><?php echo $f_star;?></span><br/>
         			<span><?php echo $val['f_feed_back']; ?></span>
         			
         <?php }
		 		elseif($val['f_no_show']=='Y'){ ?>
					<span>Updated as NO SHOW</span>
                <?php
				}
				else{ ?>
                	<span>Feedback not given</span>
				<?php } ?>" >
         		<?php
				 if($val['f_member_picture']!=''){?>
					<img src="<?php echo $val['f_member_picture'];?>"  height="20" width="25" />
				<?php }else{ ?>
					<img src="<?php echo base_url(); ?>assets/images/no_image.png" height="20" width="25" />
				<?php } ?>
                <?php echo stripslashes($val['f_member_first_name'])?> <?php echo stripslashes($val['f_member_last_name'])?>
			</a>
		</td>
		<td>
          <a title="Feedback from  <?php echo stripslashes($val['f_member_first_name'])?>" class="tmember" rel="popover" data-content="
          <?php if($val['f_no_show']=='N'){ ?>
         			<span><?php echo $t_star;?></span><br/>
         			<span><?php echo $val['t_feed_back']; ?></span>
         			
         <?php }
		 		elseif($val['f_no_show']=='Y'){ ?>
					<span>Updated as NO SHOW</span>
                <?php
				}
				else{ ?>
                	<span>Feedback not given</span>
				<?php } ?>" >
         		<?php
				 if($val['t_member_picture']!=''){?>
					<img src="<?php echo $val['t_member_picture'];?>"  height="20" width="25" />
				<?php }else{ ?>
					<img src="<?php echo base_url(); ?>assets/images/no_image.png" height="20" width="25" />
				<?php } ?>
                <?php echo stripslashes($val['t_member_first_name'])?> <?php echo stripslashes($val['t_member_last_name'])?>
			</a>
		</td>
        <td>
		<?php echo $val['venue_name'];	 ?><br/>
       			<?php echo $val['venue_address'];	 ?>
		<a href="javascript:void(0)"  onclick="venue_feedback('<?php echo $val['venue_id'];?>','<?php echo $val['f_member_id']; ?>','<?php echo $val['t_member_id'];?>')"><span class="mr_2 pull-right" data-toggle="modal" data-target="#myModal2"  data-backdrop="static">Feedback</span></a>
		
		</td>
        
        <td>
		<?php 
		$timezone  = date_default_timezone_get();
		$date	  = new DateTime($val['schedule_timefrom'], new DateTimeZone($timezone));
		$date->setTimezone(new DateTimeZone($val['timezone']?$val['timezone']:$timezone));
		echo $date->format($this->config->item('time_format'));
		
		?>
		&nbsp; to &nbsp;
		<?php 
		$timezone  = date_default_timezone_get();
		$date	  = new DateTime($val['schedule_timeto'], new DateTimeZone($timezone));
		$date->setTimezone(new DateTimeZone($val['timezone']?$val['timezone']:$timezone));
		echo $date->format($this->config->item('time_format'));
	   
	    ?></td>
		</tr>
	  <?php endforeach; ?>
      <?php else: ?>
	  <tr><td  colspan="8">There are no meetings scheduled today...</td></tr>
	  <?php endif; ?>
            </tbody>
    </table>
  </form>
 <!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  
	 </div>
     <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  
	 </div> 
<script language="javascript">

$(document).ready(function(){

	

	$(document).on('click', '#selectDate', function() {
			$("#userMasterForm").attr("action", "<?php echo site_url("admin/availability/meetings");?>");
			$("#userMasterForm").submit();return true;	
	});
	// END: Change Limit of pagination
	
});
function venue_feedback(venue_id,f_member_id,t_member_id)
{
	$("#myModal2").html("");
	$.ajax({
	type:"post",
	url:"<?php echo base_url(); ?>admin/venue/meetingfeedback",
	data:{'id':venue_id,'f_member_id':f_member_id,'t_member_id':t_member_id},
	success:function(data){
		$("#myModal2").html(data);
		//alert(data);
		}
	});
}

</script>

