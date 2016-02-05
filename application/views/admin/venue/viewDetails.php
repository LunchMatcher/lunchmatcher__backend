<div class="modal-dialog cust_dilog">
 

    <div class="modal-content">
      <div class="modal-header" style="background-color:#00A06F;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Details</h4>
      </div>
      <div class="modal-body list-group panel">
	   <?php 
	   
	   //	$i=0;
			if(count($details)!=0){ 
				foreach($details as $val){		?>
               
				<table class="list-group-item">
                <tr> <th>Matcher<th>
                <th colspan="9" >Vennue<th>
                </tr>
                <tr><td>
				<?php  
				
				 if($val['matcher_pictureurl']!=''){?>
					<img src="<?php echo $val['matcher_pictureurl'];?>"  height="25" width="25" />
				<?php }else{ ?>
					<img src="<?php echo base_url(); ?>assets/images/no_image.png" height="25" width="25" />
				<?php }
									
				?>
                </td>
                <td colspan="9"><td>
               
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
		?>&nbsp;&nbsp;
        </td>
                
                </tr>
                <tr><td>
				<b><i class="active">
			   <?php echo $val['matcher_firstname'].' '.$val['matcher_lastname'];?></i></b><br/>
	           </td>
               <td colspan="9"></td>
               <td>
               <b> <?php echo $val['name'];?></b><br/>
			    </td>
               </tr>
               <tr><td>
			   between 
			   <?php echo date(getConfigValue('time_format'), strtotime($val['schedule_timefrom']));	 ?>
			   and  
			   <?php echo date(getConfigValue('time_format'), strtotime($val['schedule_timeto']));	 ?><br />
			   on
			   <?php echo date(getConfigValue('date_format'), strtotime($val['schedule_timeto']));	 ?>
			   </td>
               <td colspan="9"></td>
               <td>
				<?php echo $val['address'];?></i>
			    </td>
               </tr>
               
               	<table>
               
	 <?php }
	 	
		}else{ 
			 echo "No restaurants here";
	 }?> 								
      </div>
	  
      
    </div>
  
  </div>
 