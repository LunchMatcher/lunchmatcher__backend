
<link href="<?php echo base_url('assets/css'); ?>/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url('assets/js'); ?>/bootstrap-datepicker.js"></script>

<script type="text/javascript" src="<?php echo base_url()."assets/js/fileupload/jquery.blockUI.js" ?>"></script>
<script language="javascript" type="text/javascript">
$(document).ready(function() {
//Multiple File upload	

	$(document).on('click', '.edit_phone', function() {
		var id=jQuery(this).attr('data-attr');
		jQuery('#old_'+id).hide();
		jQuery('#span_'+id).show();
		
		
	});
	$(document).on('click', '.closeIcon', function() {
		var id=jQuery(this).attr('data-attr');
		var venue_id=jQuery(this).attr('data-venue');
		
		if (confirm("Are you sure to delete?")) {
			$.ajax({
			type:"post",
			url:"<?php echo base_url(); ?>admin/venue/removeImage",
			data:{'id':id,'venue_id':venue_id},
			success:function(data){
				//alert(data);return false;
				window.location.reload();
				}
			});
		}else{
			return false;
		}
	});
	
	$(document).on('click', '.save_phone', function() {
		var id=jQuery(this).attr('data-attr');
		var phone = jQuery('#ph_'+id).val();
		//alert(jQuery('#ph_'+id).val());
		$.ajax({
			type:"post",
			url:"<?php echo base_url(); ?>admin/venue/savephone",
			data:{'id':id,'phone':phone},
			success:function(data){
					jQuery('#old_'+id).show();
					jQuery('#span_'+id).hide();
					jQuery('#phold_'+id).html(phone);
					if(phone!='')
						jQuery('#edit_'+id).html('edit');
					
					return true;
				}
			});
			
	});
	

jQuery(".fileToUpload").change(function(){
	
	 var id=jQuery(this).attr('data-attr');
	 jQuery('#opt').val(id);
	 var allowed_ext = ["jpg","png"];
	 jQuery.each(this.files,function(index,value){
				 var fileExtension = "";
				var file = value;
				// alert("fdallll");
				if (file) {
				  var fileSize = 0;
				  //if (file.size > 1024 * 1024)
					//fileSize = (Math.round(file.size * 100 / (1024 * 1024)) / 100).toString() + 'MB';
				  //else
					//fileSize = (Math.round(file.size * 100 / 1024) / 100).toString() + 'KB';
					if(file.size > 2097152){
						alert("Maximum Allowed File size is 2MB");
						this.value = '';
						return false;
					}
					if (file.name.lastIndexOf(".") > 0) {
						fileExtension = file.name.substring(file.name.lastIndexOf(".") + 1, file.name.length);
					}
					if (jQuery.inArray(fileExtension, allowed_ext) == -1) {
						//alert("Allowed File formats are PDF Only");
			              
					}
					//var uploadify = .("(<?php echo base_url() ?> )upload/")
					var fd = new FormData();
					fd.append('file',file);
					fd.append('form_key', window.FORM_KEY);
					var xhr = new XMLHttpRequest();
					xhr.upload.addEventListener("progress", uploadProgress, false);
					xhr.addEventListener("load", uploadComplete, false);
					xhr.addEventListener("error", uploadFailed, false);
					xhr.addEventListener("abort", uploadCanceled, false);
					jQuery.blockUI();
					xhr.open("POST","<?php echo base_url()?>admin/venue/upload_sys_image/"+id);
					xhr.setRequestHeader("Cache-Control", "no-cache");
					xhr.send(fd);
			
				 }
				 
					
			});
		
	});


		function uploadProgress(evt) {
			
				if (evt.lengthComputable) {
				  var percentComplete = Math.round(evt.loaded * 100 / evt.total);
				  jQuery('div#percent_div #sp_div').css("width", percentComplete.toString() + '%');
				  jQuery('div#percent_div #sp_div').html(percentComplete.toString() + '%');
				 
				}
				else {
				  document.getElementById('progressNumber').innerHTML = 'unable to compute';
				}
		}
	
	
		function uploadComplete(evt) {
		
			var imgname=evt.target.responseText;
			var img = $('#opt').val();
			$('.output_'+img).attr('src','<?php echo site_url('uploads/venue');?>/'+imgname+'?t=<?php echo time();?>');
			jQuery.unblockUI();
			//window.location.reload();
			
		}
	
		function uploadFailed(evt) {
			alert("There was an error attempting to upload the file.");
			jQuery.unblockUI();
		 
		}
		
		function uploadCanceled(evt) {
			alert("The upload has been canceled by the user or the browser dropped the connection.");
			jQuery.unblockUI();
		 
		}
//Multiple File Upload		
});
</script>

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
	


$(document).ready(function(){

	
	// END: check all
	
	//Change Limit of pagination
	$(document).on('change', '#limit', function() {
			$("#userMasterForm").attr("action", "<?php echo site_url("admin/venue/venuelist");?>");
				$("#userMasterForm").submit();return true;
	});	
	
	
	$('#btn_search').click(
		function(){		
			$("#userMasterForm").attr("action", "<?php echo site_url("admin/venue/venuelist");?>");
				$("#userMasterForm").submit();return true;
	});
	
	$(document).on('change', '#status', function() {
			$("#userMasterForm").attr("action", "<?php echo site_url("admin/venue/venuelist");?>");
			$("#userMasterForm").submit();return true;	
	});
	// END: Change Limit of pagination
	
	// BLock Unblock
	$('.block').click(function(){
		var id = $(this).data('id');
		var selector = '#' + 'block_' + id + " " + 'img';
		var imgsrc = $(selector).attr('src');       
		var status = $(this).data('block');
		var $this  = $(this);
		$.ajax({
            type : "POST",
            url  : "<?php echo site_url('admin/venue/toggleBlock'); ?>",
            data : {is_block: status, id:id}, 
            cache : false,
            success : function(res) {
				if(res=='Y'){
				  	 $this.data('block','Y');
				 	 $(selector).attr('src',"<?php echo base_url() ?>assets/images/unblock.png");
				}
				else if(res=='N'){
					$this.data('block','N');
				 	$(selector).attr('src',"<?php echo base_url() ?>assets/images/block.png");
				}
            }
         });  
	}); //END: BLock Unblock
});

    

</script>
<form name="userMasterForm" id="userMasterForm" method="post" action="<?php echo base_url();?>admin/venue/venuelist">
<div class="row form-group">
        <div class="col-lg-6">
            
             <div class="input-group">
            
        
              <input type="text" class="form-control" placeholder="Keyword Search" 
              
              onFocus="if(this.value=='Keywords')this.value=''" onBlur="if(this.value=='')this.value=''" 
              
              name="key" id="key" value="<?php if($key != ''){ echo $key;}else{ echo '';}?>">
           
              
              <span class="input-group-btn btn_search">
              <button class="btn btn-info " id="btn_search" type="button">Go!</button>
              </span>
              <span class="input-group-btn btn_search">
              <a href="javascript:" onclick="window.location.href='<?php echo base_url()?>admin/venue/venuelist'" class="blu_btn">
              <button class="btn btn-default" type="button">Reset</button></a>
              </span>
             </div><!-- /input-group -->
             
             
            </div>
         <div class="col-offset-1 col-lg-3">
           <div class="input-group">
           <span class="input-group-addon">
           <i class="glyphicon glyphicon-map-marker"></i> 
           </span>	
           <select name="status" id="status" class="form-control" >
           <option value="" <?php if($status ==''){?> selected="selected"<?php } ?> >Filter by Status</option>
           <option value="Y" <?php if($status == 'Y'){?> selected="selected"<?php } ?> >Block</option>
           <option value="N" <?php if($status == 'N'){?> selected="selected"<?php } ?> >Unblock</option>
           </select>
             
            </div><!-- /input-group -->
          </div>   
            
</div>
  
<?php //echo http_build_query( $this->input->get() );?>
	<div class="background_loader" style="display:none;">
		<img src="<?php echo base_url() ?>assets/images/ploader.GIF" class="ajax_loader" width="75"/>
	</div>
  <div class="row" id="Title">
    <div class="col-lg-12"><legend>Venue Details</legend></div>
  </div>
  
<div class="row">
	
    </div>  
  
<input type="hidden" name="order_by_field" id="order_by_field" value="<?php //echo $_REQUEST['order_by_field'];?>" />
<input type="hidden" name="order_by_value" id="order_by_value" value="<?php //echo $_REQUEST['order_by_value'];?>" />
<input type="hidden" name="actions" id="actions" value="" />
        <input type="hidden" id="opt" value="">

  
  <table class="table table-bordered table-hover"> 
    <thead><!--<a href="javascript:" id="deleteSel" class="delete_icon">Delete</a>-->
      <tr>
        <th width="5%"> 
			No
		</th>
        <th width="10%">
		 	Thumb
		</th>
        <th width="65%">
			
			Venue Name
		</th>
		<!--<th>
			Venue Details
		</th>-->
        <th width="10%">
			Feedback
		</th>
        <th width="10%">
			Status
		</th>
      </tr> 
    </thead>
    <tbody>
	 <?php if(sizeof($venulist) > 0)  :  $i=$_REQUEST['per_page'];?>
     <?php foreach($venulist as $val) :  $i=$i+1;
	// print_r($val);
	 ?>
	
      <tr>
        <td width="10" title="<?php echo $val->venue_id; ?>"><?php echo $i;?></td>
        <td >
		<span style="float:left">	
		<?php
			$var=FCPATH.'uploads/venue/'.$val->image;
			if($val->image != '' && file_exists($var)){
				?>
<img width="60" height="40" class="output_<?php echo $val->venue_id; ?>"  src="<?php echo site_url('uploads/venue/'.$val->image); ?>
<?php echo "?t=".time(); ?>
">
<img class="closeIcon"  src="<?php echo site_url('assets/images/cross-button.png'); ?>" data-attr="<?php echo $val->id; ?>" data-venue="<?php echo $val->venue_id; ?>"  alt="close">

			<?php }else{ ?>
<img width="60" height="40" class="output_<?php echo $val->venue_id; ?>"   src="https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&amp;photoreference=<?php echo $val->photo_key; ?>&amp;key=AIzaSyCSB01UwvVbd63eV_scq-rOD4AEirD8z9Q">

			<?php }
		?>&nbsp;&nbsp; <br>
        <label class="filebutton">Change Image
           <!-- <img src="<?php echo base_url();?>assets/images/upload.png" class="img_up">-->
            <span> <input id="fileToUpload" class="fileToUpload"  type="file" multiple name="image" data-attr="<?php echo $val->venue_id; ?>" ></span>
		</label>
       
       		
		</span></td>
         <td >
		<span style="">	
		<b><?php echo $val->name; ?></b><br /><?php echo $val->address; ?>
		</span><br />
        <?php $num= intval($val->rating);?>
			<?php if($num==1){?>
				<img src="<?php echo base_url(); ?>assets/images/star1.png"   />
			<?php }elseif($num==2){ ?>	
				<img src="<?php echo base_url(); ?>assets/images/star2.png"   />
			<?php }elseif($num==3){ ?>
				<img src="<?php echo base_url(); ?>assets/images/star3.png"  />
			<?php }elseif($num==4){ ?>
				<img src="<?php echo base_url(); ?>assets/images/star4.png"  />
			<?php }elseif($num==5){ ?>
				<img src="<?php echo base_url(); ?>assets/images/star5.png"  />
			<?php }else{?>
				<img src="<?php echo base_url(); ?>assets/images/star0.png"  />
			<?php } ?>
        <br />
        
        <span id="old_<?php echo $val->id; ?>">Phone :
        <span id="phold_<?php echo $val->id; ?>"><?php echo $val->phone; ?>
        </span>
        
        <a href="javascript:void(0)" style="color:#03F;" class="edit_phone" id="edit_<?php echo $val->id; ?>" data-attr="<?php echo $val->id; ?>">
		<?php if($val->phone==''){ ?>add<?php }else{ ?>edit<?php } ?></a>
        </span>
        <span style=" display:none;" id="span_<?php echo $val->id; ?>">Phone :
        <input type="text" id="ph_<?php echo $val->id; ?>" value="<?php echo $val->phone; ?>"  data-attr="<?php echo $val->id; ?>">
        <a href="javascript:void(0)" style="color:#03F;" class="save_phone" data-attr="<?php echo $val->id; ?>">save</a>
        </span>
        </td>

<td><a href="javascript:void(0)"  onclick="venue_feedback('<?php echo $val->venue_id;?>')"><span class="mr_2" data-toggle="modal" data-target="#myModal2"  data-backdrop="static">Feedback</span></a></td>
			<td>	
            		  
                <a class="block" data-id="<?php echo $val->id;?>" data-block="<?php echo $val->is_block;?>" id="block_<?php echo $val->id; ?>" href="javascript: void(0)">	                <?php if($val->is_block == 'N'): ?><img src="<?php echo site_url() ?>assets/images/block.png" alt="" class="tmg25">
                <?php else: ?><img src="<?php echo site_url() ?>assets/images/unblock.png" alt="" class="tmg25"><?php endif; ?>
                 </a>
            </td>
	  
      
      
      </tr>                            <?php // echo $val->id;exit; ?>
	  <?php endforeach; ?>
      <?php else: ?>
	  <tr>
	  	<td  colspan="2">No Venues Found...</td>
	  </tr>
	  <?php endif; ?>
            </tbody>
    </table>



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
</form>		
        <div class="col-offset-1 col-lg-10">
				<div class="input-group">
					<ul class="pagination pull-right" style="margin:0px;">
					<?php 
					//$page_count = ceil($tot_prop / $limit);
					echo $this->pagination->create_links(); ?>
					</ul>
				</div>
		</div>
		
  <!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  
	 </div>
     <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  
	 </div>
	  
<script>
function view_details(venue_id)
{
	//alert(venue_id);
	$("#myModal").html("");
	$.ajax({
	type:"post",
	url:"<?php echo base_url(); ?>admin/venue/view",
	beforeSend: function(){
			$(".background_loader").show();
		},
	data:{'id':venue_id},
	
	success:function(data){
		$(".background_loader").hide();
		$("#myModal").html(data);
		//alert(data);
		}
	});
}


function venue_feedback(venue_id)
{
	$("#myModal2").html("");
	$.ajax({
	type:"post",
	url:"<?php echo base_url(); ?>admin/venue/venufeedback",
	data:{'id':venue_id},
	success:function(data){
		$("#myModal2").html(data);
		//alert(data);
		}
	});
}

  </script>
  <style>
  label.filebutton {
    width:90px;
    height:20px;
    overflow:hidden;
    position:relative;
	color:#F60;
	font-weight:normal;
	font-size:12px;
}

label span input {
    z-index: 999;
    line-height: 0;
    font-size: 50px;
    position: absolute;
    top: -2px;
    left: -700px;
    opacity: 0;
    filter: alpha(opacity = 0);
    -ms-filter: "alpha(opacity=0)";
    cursor: pointer;
    _cursor: hand;
    margin: 0;
    padding:0;
}
  
  </style>
  
  
