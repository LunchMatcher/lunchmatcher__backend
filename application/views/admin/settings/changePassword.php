<link href="<?php echo base_url() ?>assets/css/bvalidator.css" rel="stylesheet">
<script src="<?php echo base_url() ?>assets/js/jquery.bvalidator.js"></script>
<script>

	function submitForm(){
		$("#frmPack").submit();
	 }
 
 
		 
	function cancel()
	{
	
	window.location.href = "<?php echo base_url()?>admin/home";
	}
</script>


  <div class="container">
   
  <?php $this->load->library('session');
 
  
  ?>
  
  	 <div class="row" id="Title">
     <div class="col-lg-12"><legend>Change Password</legend></div>

 	 </div>
			 <?php if($this->session->flashdata('success_pwd')){ ?>
            <section class="alert alert-success">
             <?php echo $this->session->flashdata('success_pwd'); ?>
            </section>
            <?php } ?>
			
	  		<?php if($this->session->flashdata('error_pwd')){ ?>
            <section class="alert alert-danger">
             <?php echo $this->session->flashdata('error_pwd'); ?>
            </section>
            <?php } ?>
	 <div class="row form-group ">
	 
	
  
  <form  method="post" name="formlist"  id="formlist" action="<?php echo base_url()?>admin/configuration/change"  onsubmit="">
 
	 <input type="hidden" name="id" value="1" />
   <fieldset>
   
       
	    <div class="row form-group">
     
        
          <div class="col-lg-4">
          <label>Old Password</label>
		  </div>
		   <div class="col-lg-5">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-map-marker"></i></span>
           <input name="old_passord" value="" type="password" class="form-control fileldtheme js-placeholder" placeholder="Old Password" data-bvalidator="required" data-bvalidator-msg="Please enter your old password">
          </div>
        </div>
		 <div class="clearfix">&nbsp;</div>
          <div class="col-lg-4">
          <label>New Password</label>
		  </div>
		   <div class="col-lg-5">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-map-marker"></i></span>
             <input name="new_password" value="" id="equalto1" type="password" class="form-control fileldtheme js-placeholder" placeholder="New Password" data-bvalidator="minlength[5],required" data-bvalidator-msg="">
          </div>
        </div>
		 <div class="clearfix">&nbsp;</div>
          <div class="col-lg-4">
          <label>Confirm Password</label>
		  </div>
		   <div class="col-lg-5">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-map-marker"></i></span>
            <input name="confirm_password" value="" type="password" class="form-control fileldtheme js-placeholder" placeholder="Confirm Password" data-bvalidator="equalto[equalto1],required">
          </div>
        </div>		      
         <div class="clearfix">&nbsp;</div>
 <div class="row form-group"></div>
   <div class="row form-group">
  <div class="col-lg-3"></div>
        <div class="col-lg-3 col-offset-3"><button class="btn btn-info btn-group-justified">Submit</button></div>
        <div class="col-lg-3"><button class="btn btn-default btn-group-justified" type="button"  onclick="cancel();">Cancel</button></div>
         <div class="col-lg-3"></div>
      </div>
    </fieldset>
  </form>


</div>

<script>

	$(document).ready(function(){
		
		var options = {
			singleError: true,
       		showCloseIcon: false,					
		};
		$("#formlist").bValidator(options);
	});

</script>