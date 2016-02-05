<script src="<?php echo base_url();?>assets/js/autocomplete/external/jquery/jquery.js"></script>
<script src="<?php echo base_url();?>assets/js/autocomplete/jquery-ui.min.js"></script>
<link href="<?php echo base_url();?>assets/js/autocomplete/jquery-ui.min.css" rel="stylesheet">
<style>
.ui-autocomplete {
display:block !important;
}
</style>
<script type="text/javascript">

$(document).on('keyup', '.search', function() {
		var name = this.value;  
		
		$.ajax({
		type: "POST",
		url: "<?php echo base_url(); ?>admin/points/search_name",
		data: {name:name},
		success: function(html)
		{ 
		//alert(html);return false;
		//$(".result").html(html).show();
		//html.split(",");
		if(html=='null'){
		return;
		}
		var availableTags = [html];
		//alert(availableTags);
		$( "#autocomplete" ).autocomplete({
		source:  $.parseJSON(availableTags)
		});
		
		
		}
		});
		
		});
$(document).on('click','ul .ui-corner-all', function() {
		var name = $(this).html();
		$("#autocomplete").val(name);
		//$(".ui-corner-all").hide();

		return false;
		
		});	


</script>

<?php //echo http_build_query( $this->input->get() );?>
<div class="background_loader" style="display:none;">
	<img src="<?php echo base_url() ?>assets/images/ploader.GIF" class="ajax_loader" width="75"/>
</div>
  <div class="row" id="Title">
    <div class="col-lg-12"><legend>Points Details</legend></div>
  </div>
  
<div class="row">

      <form name="searchuser" action="<?php echo base_url();?>admin/points/search" method="post" >
        <div class='col-lg-4'>
            <div class="form-group"><label>Search By Name :</label>
				<input id="autocomplete" class="form-control main_form1 search ui-autocomplete-input" type="text" placeholder="Search" name="search" autocomplete="off">
                    
            </div>
        </div>
		<div class='col-lg-4'>
            <div class="form-group">
               <button id="selectDate" class="btn btn-info " type="Submit" style="margin-top:23px;">Search</button>
                    
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
			
			Name
		</th>
		<!--<th>
			Email
		</th>-->
		<th>
			Score
		</th>
      </tr> 
    </thead>
    <tbody>
	 <?php if(sizeof($pointslist) > 0)  :  $i=0;?>
     <?php foreach($pointslist as $val) :  $i=$i+1;?>
	
      <tr>
        <td width="10"><?php echo $i;?></td>
        <td >
		<span style="float:left">	
		<?php
				 if($val['picture_url']!=''){?>
					<img src="<?php echo $val['picture_url'];?>"  height="25" width="25" />
				<?php }else{ ?>
					<img src="<?php echo base_url(); ?>assets/images/no_image.png" height="25" width="25" />
				<?php }
				?>&nbsp;&nbsp;
		</span>
		<span style="float:left">	
		<b><?php echo $val['first_name'].' '.$val['last_name']; ?></b>
		</span></td>
		<?php /*?><td><?php echo $val['email']; ?></td><?php */?>
		<td><a href="javascript:void(0)" onclick="view_details('<?php echo $val['member_id'];?>')"><span class="mr_2" data-toggle="modal" data-target="#myModal"  data-backdrop="static"><?php echo $val['points']; ?></span></a></td>
	  </tr>
	  <?php endforeach; ?>
      <?php else: ?>
	  <tr>
	  	<td  colspan="2">No records...</td>
	  </tr>
	  <?php endif; ?>
            </tbody>
    </table>

  <!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  
	 </div>
	  
<script>
function view_details(member_id)
{
	$("#myModal").html("");
	$.ajax({
	type:"post",
	url:"<?php echo base_url(); ?>admin/points/details",
	beforeSend: function(){
			$(".background_loader").show();
		},
	data:{'member_id':member_id},
	success:function(data){
		$(".background_loader").hide();
		$("#myModal").html(data);
		//alert(data);
		}
	});
}

  </script>
  
  
