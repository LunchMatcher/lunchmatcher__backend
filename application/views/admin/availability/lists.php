<link href="<?php echo base_url('assets/css'); ?>/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url('assets/js'); ?>/bootstrap-datepicker.js"></script>
<script type="text/javascript">
			$(function () {
				$('.datetimepicker1').datepicker({
					format: 'yyyy-mm-dd',
					orientation: "auto top"
				});
			
				
				$('.notifications').popover({placement: 'top', content: $(this).data('content'),  html: true, trigger: "hover"});
				$('.scedule').popover({placement: 'top', content: $(this).data('content'),  html: true, trigger: "hover"});
				$('.pref').popover({placement: 'top', content: $(this).data('content'),  html: true, trigger: "hover"});
            });
	
</script>


<div class="background_loader" style="display:none;">
	<img src="<?php echo base_url() ?>assets/images/ploader.GIF" class="ajax_loader" width="75"/>
</div>
<form name="userMasterForm" id="userMasterForm" method="post" action="">
<input type="hidden" name="actions" id="actions" value="" />
  <div class="row" id="Title">
    <div class="col-lg-12"><legend>Availability Management</legend></div>
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
		Name 
		</th>
        <th> 
		Score 
		</th>
         <th> 
		Location 
		</th>
        <th> 
		Radius 
		</th>
        <th>
		Match Time From
		</th>
        <th>
		Match Time To
		</th>

         
		
      </tr> 
    </thead>
    <tbody>
	 <?php 
	// echo "<pre>"; print_r($availablelistBydate); echo "</pre>"; exit;
	 
	 if(sizeof($availablelistBydate) > 0)  :
	 $j=1; ?>
     <?php foreach($availablelistBydate as $val) : ?>
	
      <tr>
     

        <td style="padding-right:0;" title="<?php echo $val['match_logid'];?>"><?php echo $j; $j++;?>
        <?php if($val['matches'] > 0 ) : ?>
        <span title="Meeting scheduled with" class="scedule" rel="popover" data-content="
         <?php if(sizeof($todayMatches[$val['match_logid']]) > 0){
			foreach($todayMatches[$val['match_logid']] as $matches){
				if($matches['member_id']==$val['matches']) {
					if($matches['picture_url']!=''){?>
						<img src='<?php echo $matches['picture_url'];?>'  height='20' width='25' />
					<?php }else{ ?>
						<img src='<?php echo base_url(); ?>assets/images/no_image.png' height='20' width='25' />
					<?php }
					echo '<span>'.$matches['first_name'].' '.$matches['last_name'].'</span><br/>';
				}
			}
		 }
		 else{
			echo '<span>No matches found</span><br/>'; 
		 }?>
         
         " >
        </span><?php endif;?></td>         
        <td>
		<span style="float:left" title="<?php echo $val['member_id'];?>">	
        <a title="Preferences" class="pref" rel="popover" data-content="
        <?php echo '<span>Exclude gender : '.$val['gender_exclude'].'</span>'; ?><br/>
        <?php $prematch = ($val['exclude_pre_match']=='Y')?'Yes':'No';
		echo '<span>Exclude pre matches : '.$prematch.'</span>'; ?><br/>
        <?php $comp = ($val['companies_exclude'] != '')?'Yes':'No';
		echo '<span>Exclude companies : '.$comp.'</span>'; ?><br/>
        " >
		<?php
				 if($val['picture_url']!=''){?>
					<img src="<?php echo $val['picture_url'];?>"  height="20" width="25" />
				<?php }else{ ?>
					<img src="<?php echo base_url(); ?>assets/images/no_image.png" height="20" width="25" />
				<?php } ?>
         </a>    &nbsp;&nbsp;
		</span>
         <a class="link-under" href="javascript:void(0)" title="<?php echo $val['member_id'];?>" 
           onclick="view_profile('<?php echo $val['member_id'];?>')">
           <span data-toggle="modal" data-target="#myModal"  data-backdrop="static">
		   <?php echo stripslashes($val['first_name'])?> <?php echo stripslashes($val['last_name'])?></span></a>
		&nbsp;
          <a style="float:right; margin-left:5px;"  href="javascript:void(0)" title="View Previous Matches" 
           onclick="view_history('<?php echo $val['member_id'];?>')">
           <span data-toggle="modal" data-target="#myModal"  data-backdrop="static">
		   <img src="<?php echo base_url(); ?>assets/images/history.png" alt="history" width="18" /></span></a>
           
         <a title="Todays Notifications" class="btn notifications" rel="popover" data-content="
         <?php if(sizeof($todayMatches[$val['match_logid']]) > 0){ ?>
         <ul style='width:270px; margin:5px; padding:0px;'>
         <?php
			foreach($todayMatches[$val['match_logid']] as $matches){ ?>
            <li style='list-style:none;padding-bottom:5px;'>
            <?php
				if($matches['picture_url']!=''){?>
					<img src='<?php echo $matches['picture_url'];?>'  height='20' width='25' style='float:left;' />
				<?php }else{ ?>
					<img src='<?php echo base_url(); ?>assets/images/no_image.png' height='20' width='25' style='float:left;' />
				<?php }
				echo '<div>&nbsp;<span>'.$matches['first_name'].' '.$matches['last_name'].'</span>&nbsp;|&nbsp;&nbsp;<small><i>'.date("M d @ h i A", strtotime($matches['matchgot_time'])).'</i></small></div>'; ?>
              </li>  
             <?php
			} ?>
            </ul>
         <?php
		 }
		 else{
			echo '<span>No matches found</span><br/>'; 
		 }?>
         
         " ><span class="matches-notific" title="<?php echo sizeof($todayMatches[$val['match_logid']])?'Got '.sizeof($todayMatches[$val['match_logid']]). ' matches notification(s)':'No matches found';?>">
		 <?php echo sizeof($todayMatches[$val['match_logid']]);?></span></a>
       
         </td>
         <td><?php echo $val['score'];	 ?></td>
        <td title="<?php echo $val['timezone'];?>">
		<?php echo $val['location'];	 ?>
       
        <a style="float:right;" href="javascript:void(0)" 
           title="cron start @ <?php echo date($this->config->item('time_format'), strtotime($val['match_time_from'] .' -'.$this->config->item('cron_start').' hours'))?>" onclick="view_feedback('<?php echo $val['member_id'];?>','<?php echo $val['match_logid'];?>')"><span class="mr_2" data-toggle="modal" data-target="#myModal"  data-backdrop="static">Venues(<?php echo $val['total_venues'];?>)</span></a>
		</td>
        <td>
		<?php echo $val['match_radius']. ' KM';	 ?>
		</td>
        <td title="<?php echo $val['match_time_from'];?>">
		<?php 
		$timezone  = date_default_timezone_get();
		$date	  = new DateTime($val['match_time_from'], new DateTimeZone($timezone));
		$date->setTimezone(new DateTimeZone($val['timezone']?$val['timezone']:$timezone));
		echo $date->format($this->config->item('time_format'));
		
		?>
		</td>
        <td title="<?php echo $val['match_time_to'];?>">
		<?php 
		$timezone  = date_default_timezone_get();
		$date	  = new DateTime($val['match_time_to'], new DateTimeZone($timezone));
		$date->setTimezone(new DateTimeZone($val['timezone']?$val['timezone']:$timezone));
		echo $date->format($this->config->item('time_format'));
	   
	    ?></td>

		
		 
		</tr>
	  <?php endforeach; ?>
      <?php else: ?>
	  <tr><td  colspan="8">No records...</td></tr>
	  <?php endif; ?>
            </tbody>
    </table>
  <div class="row" id="Table footer">
    <div class="col-lg-12 ">
	
	
	
	<div class="col-offset-1 col-lg-2 pull-right">
			<div class="input-group">
			 <!-- <span class="input-group-addon">
				<i class="glyphicon glyphicon-map-marker"></i> 
			  </span>
			  <select name="limit" id="limit" class="form-control" >
				
					<option value="5" <?php if($limit == 5){?> selected="selected"<?php } ?> >5</option>
					<option value="10" <?php if($limit == 10){?> selected="selected"<?php } ?> >10</option>
					<option value="20" <?php if($limit == 20){?> selected="selected"<?php } ?> >20</option>
					<option value="50" <?php if($limit == 50){?> selected="selected"<?php } ?>>50</option>
					<option value="100" <?php if($limit == 100){?> selected="selected"<?php } ?>>100</option>
					<option value="all" <?php if($limit == 'all'){?> selected="selected"<?php } ?>>ALL</option>
				</select>-->
			</div><!-- /input-group -->
		</div>
		
		<div class="col-offset-1 col-lg-10">
				<div class="input-group">
					<ul class="pagination pull-right" style="margin:0px;">
					<?php 
					//$page_count = ceil($tot_prop / $limit);
					//echo $this->pagination->create_links(); ?>
					</ul>
				</div>
		</div>
		
	
    </div>
  </div>
 
 
 
	
	  </form>
  

  
  
  <!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  
	 </div>
     
<script language="javascript">

$(document).ready(function(){

	//Change Limit of pagination
	$(document).on('change', '#limit', function() {
			$("#userMasterForm").attr("action", "<?php echo site_url("admin/users/lists");?>");
				$("#userMasterForm").submit();return true;
	});	

	$(document).on('click', '#selectDate', function() {
			$("#userMasterForm").attr("action", "<?php echo site_url("admin/availability/lists");?>");
			$("#userMasterForm").submit();return true;	
	});
	// END: Change Limit of pagination
	
});

function view_feedback(member_id,match_logid)
{
	$("#myModal").html("");
	$.ajax({
	type:"post",
	url:"<?php echo base_url(); ?>admin/availability/details",
	beforeSend: function(){
			$(".background_loader").show();
		},
	data:{'member_id':member_id,'match_logid':match_logid},
	success:function(data){
		$(".background_loader").hide();
		$("#myModal").html(data);
		//alert(data);
		}
	});
}
function view_profile(member_id)
{
	$("#myModal").html("");
	$.ajax({
	type:"post",
	url:"<?php echo base_url(); ?>admin/users/profile",
	beforeSend: function(){
			$(".background_loader").show();
		},
	data:{'member_id':member_id},
	success:function(data){
		$(".background_loader").hide();
		$("#myModal").html(data);
		}
	});
}
function view_history(member_id)
{
	$("#myModal").html("");
	$.ajax({
	type:"post",
	url:"<?php echo base_url(); ?>admin/users/history",
	beforeSend: function(){
			$(".background_loader").show();
		},
	data:{'member_id':member_id},
	success:function(data){
		$(".background_loader").hide();
		$("#myModal").html(data);
		}
	});
}


</script>
<link href="http://code.google.com/apis/maps/documentation/javascript/examples/default.css" rel="stylesheet" type="text/css" /> 
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA8vJNtqY_6dIPhz4iCT7d1N8cdCTqjPkg&sensor=false"></script> 