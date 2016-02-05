
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
     <div class="col-lg-12"><legend>Preferences Details</legend></div>

 	 </div>
	 
	 
	 <div class="row form-group ">
	 
	
  
  <form  method="post" name="formlist"  id="formlist" action=""  onsubmit="">
 
	 <input type="hidden" name="id" value="1" />
   <fieldset>
   
       
	    <?php $i=0; foreach($configlist as $pref){?>
		<?php if ($i==0){?>    <div class="row form-group">  <?php }$i++; ?>
     
        
          <div class="col-lg-6">
          <label><?php echo $pref->title; ?></label>
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-cog"></i></span>
            <?php switch($pref->in_type){
				case 'textbox':
				default:
				?>
                <input type="text" placeholder="<?php echo $pref->title; ?>" class="form-control" name="<?php echo $pref->field; ?>" id="<?php echo $pref->field; ?>"  value="<?php echo $pref->value; ?>">
                <?php
				break;
				case 'textarea':
				?>
                <textarea cols="40" rows="6" class="form-control" placeholder="<?php echo $pref->title; ?>" id="<?php echo $pref->field; ?>" name="<?php echo $pref->field; ?>"><?php echo $pref->value; ?></textarea>
                <?php
				break;
			}?>
            
          </div>
        </div>
      
        
        
         <?php if ($i==2){ $i=0;?>   </div> <?php } ?> 
		
		 <?php } ?>
        
 <div class="row form-group"></div>
   <div class="row form-group">
  <div class="col-lg-3"></div>
        <div class="col-lg-3 col-offset-3"><button class="btn btn-success btn-group-justified">Submit</button></div>
        <div class="col-lg-3"><button class="btn btn-default btn-group-justified" type="button"  onclick="cancel();">Cancel</button></div>
         <div class="col-lg-3"></div>
      </div>
    </fieldset>
  </form>


</div>