
<script type="text/javascript" src="<?php echo base_url()."assets/js/fileupload/jquery.blockUI.js" ?>"></script>
<script language="javascript" type="text/javascript">
$(document).ready(function() {
//Multiple File upload	

jQuery("#fileToUpload").change(function(){
	
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
					xhr.open("POST","<?php echo base_url()?>admin/venue/upload_sys_image/<?php echo $data['id'];?>");
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
			var img = document.getElementById('output');
			$('.output').attr('src','<?php echo site_url('uploads/venue');?>/'+imgname);
			jQuery.unblockUI();
			
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

<script>
 /* var loadFile = function(event) {
    var reader = new FileReader();
    reader.onload = function(){
      var output = document.getElementById('output');
	  console.log(reader.result);
      output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  };*/
</script>
<div class="modal-dialog cust_dilog">


    <div class="modal-content">
      <div class="modal-header" style="background-color:#00A06F;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Details</h4>
      </div>
      <div class="modal-body list-group panel">
	   <?php 
	//echo "<pre>"; print_r ($details);exit;
	   
	   	$i=0;
			if(!empty($data)){ 
				
		?>
	       <div class="col-md-12">
            	<div class="col-md-4">
                <?php $var=FCPATH.'uploads/venue/'.$data['image'];if(file_exists($var)){ ?>
               		<img width="200" height="200" class="output"  src="<?php echo site_url('uploads/venue/'.$data['image']); ?>
">
                <?php } else{ ?>
            			<img width="200" height="200" class="output" src="https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&amp;photoreference=<?php echo $data['photo_key']; ?>
&amp;key=AIzaSyCSB01UwvVbd63eV_scq-rOD4AEirD8z9Q"><br/>
                 <?php  } ?>
                 <input id="fileToUpload"  type="file" multiple name="image">						
						</span>
						<!--<img title="Upload Image" width="25px" style="" height="25px" src="<?php echo base_url().'assets/images/upload.gif'; ?>">-->					
						<div id="imagediv" class="library_img ui-draggable" style="margin-top:5px; position:relative; z-index:3;">
						<input id="<?php echo $imdata;?>" type="hidden" name="imgsrc[]" value="<?php echo $imdata;?>">
						</div>
                </div>
               	<div class="col-md-8">
                	<span class="point-title"><?php echo $data['name'];?><br/><span class="gray">  <?php echo $data['address'];	 ?></span></span>
                </div>
                
            </div> 	    
     
	       <?php 
	      
		   }else{ 
			 echo "Wrong venue id selected !";
	     }?> 								
         </div>
         </div>
  
  </div>
 