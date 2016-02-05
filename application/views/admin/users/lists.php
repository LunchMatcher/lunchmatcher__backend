<script language="javascript">


function toggleStatus(id,status,statusdiv){
		$.ajax({
					url: "<?php echo base_url()?>admin/users/ajaxstatus",
					data : {id:id,status:status,statusdiv:statusdiv},
					success: function(result){	
					window.location.reload();
						
				}
			
				});		
	
	}	
function toggleblock(id,block,blockdiv){
		$.ajax({
					url: "<?php echo base_url()?>admin/users/ajaxblock",
					data : {id:id,block:block,blockdiv:blockdiv},
					success: function(result){	
					//alert(result);return false;
					window.location.reload();
						
				}
			
				});		
	
	}	
		
$(document).on('click', '.edit_point', function() {
		var member_id=jQuery(this).attr('data-attr');
		jQuery('#add_'+member_id).hide();
		jQuery('#span_'+member_id).show();
});


$(document).on('click', '.save_point', function() {
	   
		var member_id=$(this).attr('data-attr');
		var points = parseFloat($('#point_'+member_id).val());
		var point_sum = ($('#point_sum_'+member_id).html()!='')?parseFloat($('#point_sum_'+member_id).html()):0;
		var total=points+point_sum;
		
		if(points>0){
				$.ajax({
					type:"post",
					url:"<?php echo base_url(); ?>admin/users/addpoint",
					data:{'member_id':member_id,'points':points},
					success:function(data){
						$('#point_sum_'+member_id).html(total);
						jQuery('#span_'+member_id).hide();
						jQuery('#add_'+member_id).show();
						return true;
							
					}
					});
		}
		else{
			alert("Point value must be greater than zero!!")
		}
			
});
	

$(document).ready(function(){
// function to delete masteraction 
	$("#deleteSel").click(function(){
		var chkCnt	=	$(".chk:checked").length;
		if(chkCnt==0){
			//alert("Please select at least one user.!");
			showMessageBox('Select atleast one item','danger');
			return false;
		}
		
			if(confirm('Are you sure to delete the selected user(s)?')){
				$("#userMasterForm").attr("action", "<?php echo site_url("admin/users/index");?>");
				$("#bulkaction_list").val('delete_list');
				$("#actions").val("delete");
				$("#userMasterForm").submit();return true;
			}
			
	});
	
	
// function to delete product 
	$("#bulkaction").change(function(){
		var chkCnt	=	$(".chk:checked").length;
		var action	=	$("#bulkaction").val();		

		if(chkCnt==0){
			alert("Please select at least one user.!");
			return false;
		}
		else if(action == 'delete'){
			if(confirm('Are you sure to delete the selected user(s)?')){
				$("#userMasterForm").attr("action", "<?php echo site_url("admin/users/bulkAction");?>");
				$("#userMasterForm").submit();return true;
			}
			else{
				return false;
			}	
		
		}
		else if(action == 'active' || action == 'inactive'){
			if(confirm('Are you sure to change status of the selected user(s)?')){			
				$("#userMasterForm").attr("action", "<?php echo site_url("admin/users/bulkAction");?>");
				$("#userMasterForm").submit();return true;
			}
			else{
				return false;
			}	
		
		}
		else{
			alert("Please specify any action.!");
			return false;
		}
	});
	
	// filter function
		$("#filter_button").click(function(){
			$("#userMasterForm").submit();return true;	
		});
	// End : filter function
		
	//check all
	$('#select_all').click(	function(){
	//alert("s");
		if($('#select_all').is(':checked'))
		$('.chk').prop('checked',true);
		else
			$('.chk').prop('checked',false);
		});
	// end check all
	

	
	//check all
$('.sortlink').click(
		function(){
		var feild = $(this).attr('rel');
		var title = $(this).attr('title');
		
		$(this).removeAttr('title');
		if(title =='ASC'){
			$(this).attr('title', 'DESC');
		}
		else{	
			$(this).attr('title', 'ASC');
		}
		$('#order_by_field').val(feild);
		$('#order_by_value').val($(this).attr('title'));
		$("#userMasterForm").attr("action", "<?php echo site_url("admin/users/lists");?>");
		$("#userMasterForm").submit();return true;	
	});


	
	// END: check all
	
	//Change Limit of pagination
	$(document).on('change', '#limit', function() {
			$("#userMasterForm").attr("action", "<?php echo site_url("admin/users/lists");?>");
				$("#userMasterForm").submit();return true;
	});	
	
	
	$('#btn_search').click(
		function(){		
			$("#userMasterForm").attr("action", "<?php echo site_url("admin/users/lists");?>");
				$("#userMasterForm").submit();return true;
	});
	
	$(document).on('change', '#status', function() {
			$("#userMasterForm").attr("action", "<?php echo site_url("admin/users/lists");?>");
			$("#userMasterForm").submit();return true;	
	});
	// END: Change Limit of pagination
	
});




</script>


<?php //echo http_build_query( $this->input->get() );?>

<form name="userMasterForm" id="userMasterForm" method="post" action="<?php echo base_url();?>admin/users/lists">
<input type="hidden" name="order_by_field" id="order_by_field" value="<?php echo $_REQUEST['order_by_field'];?>" />
<input type="hidden" name="order_by_value" id="order_by_value" value="<?php echo $_REQUEST['order_by_value'];?>" />
<input type="hidden" name="actions" id="actions" value="" />
  <div class="row" id="Title">
    <div class="col-lg-12"><legend>Registered Users</legend></div>
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
  
  <!--<div class="row">
    <div class="col-lg-1 pull-right">
      <p>
	  <a class="add_new_icon" alt="Add New" itle="Add New" href="<?php //echo base_url()?>admin/users/details"><button class="btn btn-info btn-group-justified" type="button">Add</button></a>
     
      </p>
    </div>
  </div>-->

  <div class="row form-group">
    
    <?php if($status !='T'){?> 
    <div class="col-offset-1 col-lg-3">
    <div class="input-group">
 <span class="input-group-addon">
        <i class="glyphicon glyphicon-map-marker"></i> 
      </span>	
	  <select name="bulkaction" id="bulkaction" class="form-control" >
	  		<option value="" <?php if($bulkaction ==''){?> selected="selected"<?php } ?> >Bulk actions</option>
	  		<option value="delete" <?php if($bulkaction == 'delete'){?> selected="selected"<?php } ?> >Delete</option>
	  		<option value="active" <?php if($bulkaction == 'active'){?> selected="selected"<?php } ?> >Active</option>
			<option value="inactive" <?php if($bulkaction == 'inactive'){?> selected="selected"<?php } ?> >Inactive</option>
	</select>
	 
    </div><!-- /input-group -->
  </div>
  
    <?php } ?>
  
  
   <div class="col-offset-1 col-lg-3">
   <div class="input-group">
   <span class="input-group-addon">
   <i class="glyphicon glyphicon-map-marker"></i> 
   </span>	
   <select name="status" id="status" class="form-control" >
   <option value="" <?php if($status ==''){?> selected="selected"<?php } ?> >Filter by Status</option>
   <option value="Y" <?php if($status == 'Y'){?> selected="selected"<?php } ?> >Active</option>
   <option value="N" <?php if($status == 'N'){?> selected="selected"<?php } ?> >Inactive</option>
   <option value="T" <?php if($status == 'T'){?> selected="selected"<?php } ?> >Trashed</option>
   </select>
	 
    </div><!-- /input-group -->
  </div>
  
  <div class="col-lg-6">
    
     <div class="input-group">
     
      <input type="text" class="form-control" placeholder="Keyword Search" onFocus="if(this.value=='Keywords')this.value=''" onBlur="if(this.value=='')this.value=''" name="key" id="key" value="<?php if($key != ''){ echo $key;}else{ echo '';}?>">
      <span class="input-group-btn btn_search">
      <button class="btn btn-info " id="btn_search" type="button">Go!</button>
      </span>
	  <span class="input-group-btn btn_search">
	  <a href="javascript:" onclick="window.location.href='<?php echo base_url()?>admin/users/index'" class="blu_btn">
	  <button class="btn btn-default" type="button">Reset</button></a>
	  </span>
     </div><!-- /input-group -->
	 
	 
    </div>
  
  
  </div>

  <table class="table table-bordered table-hover"> 
    <thead><!--<a href="javascript:" id="deleteSel" class="delete_icon">Delete</a>-->
      <tr>
      	<?php if($status !='T'){?>
        <th><center><input  type="checkbox" name="select_all"  id="select_all" ></center></th>
        <?php } ?>
        <th colspan="3"><span class="link_blak" >Actions</span></th>
        <th><a href="javascript:void(0);" class="sortlink link_blak" title="<?php echo $_REQUEST['order_by_value'];?>" rel="first_name">First Name <span class="icon-sort icon-light pull-right" title="Sort" ></span></a>
		</th>
        <th><a href="javascript:void(0);" class="sortlink link_blak" title="<?php echo $_REQUEST['order_by_value'];?>" rel="last_name">Last Name <span class="icon-sort icon-light pull-right" title="Sort" ></span></a>
		</th>
        <th><a href="javascript:void(0);" class="sortlink link_blak" title="<?php echo $_REQUEST['order_by_value'];?>" rel="email">E-mail<span class="icon-sort icon-light pull-right" title="Sort" ></span></a>
		</th>
        
        <th><a href="javascript:void(0);" class="sortlink link_blak" title="<?php echo $_REQUEST['order_by_value'];?>" rel="created_time">Join Date<span class="icon-sort icon-light pull-right" title="Sort" ></span></a>
		</th>
         <th>
		Preferences
		<!--<a href="javascript:void(0);" class="sortlink link_blak" title="<?php //echo $_REQUEST['order_by_value'];?>" rel="created_time">Join Date<span class="icon-sort icon-light pull-right" title="Sort" ></span></a>-->
		</th>
         <th>
		Feedback
		</th>
		 <th>
		Score
		</th>
         <th>
		Gender
		</th>
         <th>
		Platform
		</th>
		
      </tr> 
    </thead>
    <tbody>
	 <?php if(sizeof($userlist) > 0)  : //echo "<pre>"; print_r($userlist); exit;?>
     <?php foreach($userlist as $val) : ?>
	
      <tr>
      	<?php if($status !='T'){?>
        <td><center><input  type="checkbox" name="sel[]" value="<?php echo $val->member_id;?>"  rel="" class="chk"  /></center></td>
        <?php }?>
        
        <?php if($status !='T'){?>
        <td><center><a href="<?php echo base_url();?>admin/users/bulkAction/delete/<?php echo $val->member_id; ?>?limit=<?php echo $limit;?>&per_page=<?php echo $per_page;?>" onclick="return confirm('Are you sure you want to delete?');"><i class="glyphicon glyphicon-trash" title="Permanent delete"></i></a></center></td>
        <?php }else{ ?>
        <td><center><a href="<?php echo base_url();?>admin/users/bulkAction/Permdelete/<?php echo $val->member_id; ?>?limit=<?php echo $limit;?>&per_page=<?php echo $per_page;?>" onclick="return confirm('Are you sure you want to permanently delete the user?');"><i class="glyphicon glyphicon-trash" title="Move to trash"></i></a></center></td>
        <?php } ?>
		<td><center>

		<a href="<?php echo base_url();?>admin/users/details/<?php echo $val->member_id; ?>" class="link1">
<i class="glyphicon glyphicon-eye-open" title="View Details"></i></a></center>
        </td>
        <td> <?php  if( $val->status != 'T'){ ?>
        <div id="div_status_<?php echo $val->member_id;?>"> <center> <a href="javascript:void(0)" >
		  
		 
		  	<?php	if( $val->is_block == 'N' ){?>
				<i class="glyphicon glyphicon-ok"  title="Block" onclick="toggleblock('<?php echo $val->member_id;?>','<?php echo $val->is_block; ?>','#div_block_<?php echo $val->member_id;?>')" id="star_block_<?php echo $val->member_id;?>" ></i>
				<?php }else{?>
				<i  class="glyphicon glyphicon-remove"   title="Unblock" onclick="toggleblock('<?php echo $val->member_id;?>','<?php echo $val->is_block; ?>','#div_block_<?php echo $val->member_id;?>')" id="star_block_<?php echo $val->member_id;?>" ></i>
				<?php }?>
				</a></center></div>
         <?php } ?></td>
        <!--<td><div id="div_status_<?php echo $val->member_id;?>"> <center> <a href="javascript:void(0)" >
		  <?php  if( $val->status == 'Y'){?>
				<i class="glyphicon glyphicon-star"  title="Inactive" onclick="toggleStatus('<?php echo $val->member_id;?>','<?php echo $val->status; ?>','#div_status_<?php echo $val->member_id;?>')" id="star_active_<?php echo $val->member_id;?>" ></i>
				<?php }else{?>
				<i  class="glyphicon glyphicon-star-empty"   title="Active" onclick="toggleStatus('<?php echo $val->member_id;?>','<?php echo $val->status; ?>','#div_status_<?php echo $val->member_id;?>')" id="star_active_<?php echo $val->member_id;?>" ></i>
				<?php }?>
				</a></center></div></td>-->
   		<td><a href="<?php echo base_url();?>admin/users/details/<?php echo $val->member_id; ?>" class="link1"><?php echo stripslashes($val->first_name)?> </a></td>
        <td><a href="<?php echo base_url();?>admin/users/details/<?php echo $val->member_id; ?>" class="link1"><?php echo stripslashes($val->last_name)?></a></td>
        <td><a href="<?php echo base_url();?>admin/users/details/<?php echo $val->member_id; ?>" class="link1"><?php echo $val->email; ?></a></td>
        
        
        <td><?php echo date(getConfigValue('date_format'), strtotime($val->created_time));	 ?>
		</td>
         <td>
		   <a href="javascript:void(0)" onclick="view_preferevce('<?php echo $val->member_id;?>')"><span class="mr_2" data-toggle="modal" data-target="#myModal"  data-backdrop="static">View preferences</span></a>
		</td>
		 <td>
		   <a href="javascript:void(0)" onclick="view_feedback('<?php echo $val->member_id;?>')"><span class="mr_2" data-toggle="modal" data-target="#myModal"  data-backdrop="static">Feedback</span></a>
		</td>
		 <td><span id="point_sum_<?php echo $val->member_id; ?>"><?php echo $val->tot_score; ?></span>
         <a href="javascript:void(0)" style="color:#03F;" class="edit_point" id="add_<?php echo $val->member_id; ?>" data-attr="<?php echo $val->member_id; ?>">
		add</a>
        <span style=" display:none;" id="span_<?php echo $val->member_id; ?>">
        <input type="text" id="point_<?php echo $val->member_id; ?>" value="<?php echo $val->points; ?>"  data-attr="<?php echo $val->member_id; ?>">
        <a href="javascript:void(0)" style="color:#03F;" class="save_point" data-attr="<?php echo $val->member_id; ?>">save</a>
        </span>
         </td>
		 <td><?php echo $val->gender; ?></td>
         <td><?php echo $val->device_platform; ?></td>
		</tr>
	  <?php endforeach; ?>
      <?php else: ?>
	  <tr><td  colspan="13">No records...</td></tr>
	  <?php endif; ?>
            </tbody>
    </table>
  <div class="row" id="Table footer">
    <div class="col-lg-12 ">
	
	
	
	<div class="col-offset-1 col-lg-2 pull-right">
			<div class="input-group">
			  <span class="input-group-addon">
				<i class="glyphicon glyphicon-map-marker"></i> <?php //echo $limit;?>
			  </span>
			  <select name="limit" id="limit" class="form-control" >
				
					<option value="5" <?php if($limit == 5){?> selected="selected"<?php } ?> >5</option>
					<option value="10" <?php if($limit == 10){?> selected="selected"<?php } ?> >10</option>
					<option value="20" <?php if($limit == 20){?> selected="selected"<?php } ?> >20</option>
					<option value="50" <?php if($limit == 50){?> selected="selected"<?php } ?>>50</option>
					<option value="100" <?php if($limit == 100){?> selected="selected"<?php } ?>>100</option>
					<option value="all" <?php if($limit == 'all'){?> selected="selected"<?php } ?>>ALL</option>
				</select>
			</div><!-- /input-group -->
		</div>
		
		<div class="col-offset-1 col-lg-10">
				<div class="input-group">
					<ul class="pagination pull-right" style="margin:0px;">
					<?php 
					//$page_count = ceil($tot_prop / $limit);
					echo $this->pagination->create_links(); ?>
					</ul>
				</div>
		</div>
		
	
    </div>
  </div>
 
 
 
	
	  </form>
  

  
  
  <!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  
	 </div>
	  
  <script>
function view_preferevce(member_id)
{
	$("#myModal").html("");
	$.ajax({
	type:"post",
	url:"<?php echo base_url(); ?>admin/users/preferences",
	data:{'member_id':member_id},
	success:function(data){
		$("#myModal").html(data);
		//alert(data);
		}
	});
}
function view_feedback(member_id)
{
	$("#myModal").html("");
	$.ajax({
	type:"post",
	url:"<?php echo base_url(); ?>admin/users/feedback",
	data:{'member_id':member_id},
	success:function(data){
		$("#myModal").html(data);
		//alert(data);
		}
	});
}

</script>
<script>
  $(document).ready(function(){
    $('.full_link').click(function(){
        window.location = $(this).attr('href');
        return false;
    });
});
</script>