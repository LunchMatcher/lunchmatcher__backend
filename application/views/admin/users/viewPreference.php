<div class="modal-dialog cust_dilog">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#00A06F;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Preferences</h4>
      </div>
      <div class="modal-body">
        <fieldset>

          <!-- Text input-->
          <div class="form-group">
		  <div class="col-lg-5">
            <label for="textinput" class="control-label">Gender Exclude</label>
		  </div>
		  <div class="col-lg-1">:
		  </div>
            <div class="col-lg-6">
              <label for="textinput" class="control-label"><?php echo $preference[0]['gender_exclude'];?>
			  </label>
			</div>
          </div>
<div class="clearfix"></div>
            <!-- Text input-->
          <div class="form-group">
		  <div class="col-lg-5">
            <label for="textinput" class="control-label">Exclude Preference Match</label>
		</div>
		<div class="col-lg-1">:
		  </div>
			<div class="col-lg-6">
				 <label for="textinput" class="control-label">
				 <?php 
				 if($preference[0]['exclude_pre_match']!=""){
				 	if($preference[0]['exclude_pre_match'] == 'Y'){echo "Yes"; }else{ echo "No"; }
				 }?>
				 </label>
			</div>
          </div>
		  <div class="clearfix"></div>
          <!-- Text input-->
          <div class="form-group">
		  <div class="col-lg-5">
            <label for="textinput" class="control-label">Notification Time</label>
			</div>
			<div class="col-lg-1">:
		  </div>
			<div class="col-lg-6">
				 <label for="textinput" class="control-label">
				 <?php 
				 if($preference[0]['notification_time']!=""){
				 	echo date(getConfigValue('time_format'), strtotime($preference[0]['notification_time']));
				 }
				?>
				 
				 
				 </label>
            </div>
          </div>
		  <div class="clearfix"></div>
          <!-- Text input-->
          <div class="form-group">
		  <div class="col-lg-5">
            <label for="textinput" class="control-label">Companies To Exclude</label>
		</div>
		<div class="col-lg-1">:
		  </div>
			<div class="col-lg-6">
			<label for="textinput" class="control-label">
				<div class="col-lg-12" style="padding:0">
				
				
				<?php foreach($preference as $pref)
				{ ?>
                		<div class="col-lg-3" style="padding-bottom:3px; padding-left:0px;">
						<img src="<?php echo $pref[company_logourl]; ?>" alt="" width="100" height="35"/>
						<?php echo '</br> ';?>
						</div>
						<div class="col-lg-6" style="padding:0">
						<?php echo $pref[company_name]; ?>
						</div>
						<div class="clearfix"></div>
				<?php 		
				}
				
				?>
				
				
				<?php /*?><?php $array=explode(',',$preference['company']);
				
				for($i=0;$i<count($array);$i++){
					$new=explode('#',$array[$i]);
					?>
						<div class="col-lg-6" style="padding:0">
						<?php echo $new[0]; ?>
						</div>
						<div class="col-lg-3">
						<img src="<?php echo $new[1];?>" alt="" width="100" height="35"/>
						<?php echo '</br> ';?>
						</div>
					<div class="clearfix"></div><?php
				}
				
				?><?php */?>
				
				</div>
				</label>
             </div>
           
          </div>


        </fieldset>
      </div>

    </div>
  </div>
