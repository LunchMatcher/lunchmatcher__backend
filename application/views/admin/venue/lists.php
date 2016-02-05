<link href="<?php echo base_url('assets/css'); ?>/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url('assets/js'); ?>/bootstrap-datepicker.js"></script>
<script type="text/javascript">
			$(function () {
				$('.datetimepicker1').datepicker({
					format: 'yyyy-mm-dd',
					orientation: "auto top",
					endDate: '1d'
				});
				
            });

$(document).ready(function(){


	$(document).on('click', '#selectDate', function() {
			$("#searchvenue").attr("action","<?php echo site_url("admin/venue/lists");?>");
			$("#searchvenue").submit();return true;	
	});
	// END: Change Limit of pagination
	
});
	
</script>

<?php //echo http_build_query( $this->input->get() );?>
	<div class="background_loader" style="display:none;">
		<img src="<?php echo base_url() ?>assets/images/ploader.GIF" class="ajax_loader" width="75"/>
	</div>
  <div class="row" id="Title">
    <div class="col-lg-12"><legend>Venue Details</legend></div>
  </div>
  
<div class="row">
	<form name="searchvenue" id="searchvenue" action="" method="post">
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
		
		
		</form>
    </div>  
  
  
  
  
  <table class="table table-bordered table-hover"> 
    <thead><!--<a href="javascript:" id="deleteSel" class="delete_icon">Delete</a>-->
      <tr>
        <th> 
			No
		</th>
        <th>
			
			Venue Name
		</th>
		<th>
			Scheduled
		</th>
		<th>
			Details
		</th>
      </tr> 
    </thead>
    <tbody>
	 <?php if(sizeof($venuelist) > 0)  :  $i=0;?>
     <?php foreach($venuelist as $val) :  $i=$i+1;?>
	
      <tr>
        <td width="10"><?php echo $i;?></td>
        <td >
		<span style="float:left">	
		<?php
			if($val['photo_key']!=''){?>
			<img width="60" height="40" src="https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&amp;photoreference=<?php echo $val['photo_key']; ?>&amp;key=AIzaSyCSB01UwvVbd63eV_scq-rOD4AEirD8z9Q">
			<?php }else{ ?>
			<img src="<?php echo base_url(); ?>assets/images/no_image.png" width="60" height="40"/>
			<?php }
		?>&nbsp;&nbsp;
		</span>
		<span style="float:left">	
		<b><?php echo $val['name']; ?></b><br /><?php echo $val['address']; ?>
		</span></td>
		<td><?php echo $val['count']; ?></td>
		<td><a href="javascript:void(0)" onclick="view_details('<?php echo $val['venue_id'];?>')"><span class="mr_2" data-toggle="modal" data-target="#myModal"  data-backdrop="static">View Details</span></a></td>
	  </tr>
	  <?php endforeach; ?>
      <?php else: ?>
	  <tr>
	  	<td  colspan="2">No Scheduled Venues For this Date...</td>
	  </tr>
	  <?php endif; ?>
            </tbody>
    </table>

  <!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  
	 </div>
	  
<script>
function view_details(venue_id)
{
	$("#myModal").html("");
	var startdate=$("#startDate").val();
	var enddate=$("#endDate").val();
	$.ajax({
	type:"post",
	url:"<?php echo base_url(); ?>admin/venue/meetings",
	beforeSend: function(){
			$(".background_loader").show();
		},
	data:{'venue_id':venue_id,'schedule_timefrom':startdate,'schedule_timeto':enddate},
	success:function(data){
		$(".background_loader").hide();
		$("#myModal").html(data);
		//alert(data);
		}
	});
}

  </script>
  
  
