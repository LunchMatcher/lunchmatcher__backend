 <div class="container"> <div class="row">
	   <div class="col-lg-12">
	      <legend>Address Details <?php echo isset($user->firs_tname)?$user->first_name:''; ?></legend>
		</div> 
	  <div class="col-lg-12 tabfour">
	  	<div class="col-lg-2">
	  	<div class="col-lg-12">
        <?php if($user->profile_url != '') : ?>
        <a href="<?php echo $user->profile_url;?>" target="_blank">
        <?php endif;?>
			<?php if($user->picture_url!=''){?>
				<img src="<?php echo $user->picture_url; ?>" height="" width="" />
			<?php }else{ ?>
				<img src="<?php echo base_url(); ?>assets/images/no_image.png" height="" width="" />
			<?php }?>
        <?php if($user->profile_url != '') : ?>
        </a>
        <?php endif;?>
		</div>
		</div>
		<div class="col-lg-10 mrtp">
		
		<div class="row">
    <div class="col-md-2 col-md-offset-1"><button onclick="view_popup('<?php echo $user->member_id;?>','preferences')" type="button" class="btn btn-lg btn-default btn-group-justified tabbutton btnstyle" data-toggle="modal" data-target="#myModal"  data-backdrop="static">Preferences</button></div>
    <div class="col-md-2"><button onclick="view_popup('<?php echo $user->member_id;?>','feedback')" type="button" class="btn btn-lg btn-default btn-group-justified tabbutton btnstyle" data-toggle="modal" data-target="#myModal"  data-backdrop="static">Feedbacks</button></div>
    <div class="col-md-2"><button onclick="view_popup('<?php echo $user->member_id;?>','points')" type="button" class="btn btn-lg btn-default btn-group-justified tabbutton btnstyle" data-toggle="modal" data-target="#myModal"  data-backdrop="static">Points</button></div>
    <div class="col-md-2"><button onclick="view_popup('<?php echo $user->member_id;?>','venues')" type="button" class="btn btn-lg btn-default btn-group-justified tabbutton btnstyle" data-toggle="modal" data-target="#myModal"  data-backdrop="static">Venues</button></div>
    <div class="col-md-2"><button onclick="view_popup('<?php echo $user->member_id;?>','favourites')" type="button" class="btn btn-lg btn-default btn-group-justified tabbutton btnstyle" data-toggle="modal" data-target="#myModal"  data-backdrop="static">Favourites</button></div>
</div>
		
		
		
		
   
		</div>
      </div>
	   <div class="clearfix">&nbsp;</div>
	  
    <div class="col-md-12">
	
      <form class="form-horizontal" role="form" action="<?php echo base_url()?>admin/users/add" method="post">
        <fieldset>

          <!-- Form Name -->
      

          <!-- Text input-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">First Name</label>
			
            <div class="col-sm-10">
              <input type="text" name="first_name" value="<?php echo isset($user->first_name)?$user->first_name:''; ?>" placeholder="Firstname" class="form-control" readonly="readonly">
			<span class="alert-danger"><?php echo form_error('first_name');?></span>
			</div>
          </div>

            <!-- Text input-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">Last Name</label>
            <div class="col-sm-10">
              <input type="text" name="last_name" value="<?php echo isset($user->last_name)?$user->last_name:''; ?>" placeholder="Lastname" class="form-control"  readonly="readonly" >
            </div>
          </div>
		    <!-- Text input-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">Maiden Name</label>
            <div class="col-sm-10">
              <input type="text" name="maiden_name" value="<?php echo isset($user->maiden_name)?$user->maiden_name:''; ?>" placeholder="Maiden Name" class="form-control"  readonly="readonly" >
            </div>
          </div>
		  
		    <!-- Text input-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">Formatted Name</label>
            <div class="col-sm-10">
              <input type="text" name="formatted_name" value="<?php echo isset($user->formatted_name)?$user->formatted_name:''; ?>" placeholder="Formatted Name" class="form-control"  readonly="readonly" >
            </div>
          </div>
		  
		    <!-- Text input-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">Headline</label>
            <div class="col-sm-10">
              <input type="text" name="headline" value="<?php echo isset($user->headline)?$user->headline:''; ?>" placeholder="Headline" class="form-control"  readonly="readonly" >
            </div>
          </div>
		  
		  		  
          <!-- Text input-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">Email</label>
            <div class="col-sm-10">
              <input type="text" name="email" value="<?php echo isset($user->email)?$user->email:''; ?>" placeholder="Email" class="form-control"  readonly="readonly" >
			<span class="alert-danger"><?php echo form_error('email');?></span>
            </div>
          </div>
		  
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">Gender </label>
            <div class="col-sm-10">
			 <div class="col-sm-3">
			<input type="radio" name="gender" value="Male" <?php if($user->gender=='Male'){?> checked="checked" <?php } ?>/> <label class="control-label">Male</label>
			</div>
			 <div class="col-sm-3">
			<input type="radio" name="gender" value="Female" <?php if($user->gender=='Female'){?> checked="checked" <?php } ?> /> <label class="control-label">Female</label>
			</div>
			<span class="alert-danger"><?php echo form_error('gender');?></span>
            </div>
          </div>
		  
		   <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">Date Of Birth</label>
            <div class="col-sm-10">
              <input type="text" name="dob" value="<?php if($user->member_dob!="0000-00-00"){echo isset($user->member_dob)?$user->member_dob:''; }?>" placeholder="DOB" class="form-control"  readonly="readonly" >
            </div>
          </div>
		  
            <!-- Text input-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">Mobile</label>
            <div class="col-sm-10">
              <input type="text" name="mobile" value="<?php echo isset($user->contact_number)?$user->contact_number:''; ?>" placeholder="Mobile" class="form-control"  readonly="readonly" >
            </div>
          </div>
			 
          
         

          <!-- Text input-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">Location</label>
            <div class="col-sm-10">
              <input type="text" name="location" value="<?php echo isset($user->location)?$user->location:''; ?>" placeholder="Location" class="form-control"  readonly="readonly" >
            </div>
          </div>
		  
		   <!-- Text input-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">Industry</label>
            <div class="col-sm-10">
              <input type="text" name="industry" value="<?php echo isset($user->industry)?$user->industry:''; ?>" placeholder="Industry" class="form-control"  readonly="readonly" >
            </div>
          </div>
          
           <!-- Text input-->
         <?php  if(($user->lunch_area)!=''){?>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">Lunch Area</label>
            <div class="col-sm-10">
              <input type="text" name="industry" value="<?php echo isset($user->lunch_area)?$user->lunch_area:''; ?>" placeholder="Lunch Area" class="form-control"  readonly="readonly" >
            </div>
          </div>
          <?php }?>
		  
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">Sign uP Via</label>
            <div class="col-sm-10">
            <input type="text" name="device_platform" value="<?php echo $user->device_platform;?> App"  class="form-control"  readonly="readonly" >
			<!--<div class="col-sm-3">
			<input type="checkbox" <?php if($user->default=='W'){?> checked="checked" <?php } ?>/> <label class="control-label">
            Sign Up via Web Form</label>
			</div>
			<div class="col-sm-3">
			<input type="checkbox" <?php if($user->default=='N'){?> checked="checked" <?php } ?>/> <label class="control-label">
            Sign Up via Linked In</label>
			</div>
			<div class="col-sm-3">
			<input type="checkbox" <?php if($user->default=='Y'){?> checked="checked" <?php } ?>/> <label class="control-label">
            Sign Up via App</label>
			</div>-->
			
            </div>
          </div>
		  
		   
		<input type="hidden" name="id" value="<?php echo isset($user->member_id)?$user->member_id:''; ?>" />
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <div class="pull-right">
				
				<a href="javascript:" onclick="window.location.href='<?php echo base_url()?>admin/users/lists'" class="blu_btn">
					<button type="button" class="btn btn-info">Back</button>
				</a>
			  	
                <!--<a href="javascript:" onclick="window.location.href='<?php echo base_url()?>admin/users/lists'" class="blu_btn">
				<button type="button" class="btn btn-default">Cancel</button>
				</a>
                <button type="submit" class="btn btn-info" name="submit_btn" id="submit_btn">Save</button>-->
              </div>
            </div>
          </div>

        </fieldset>
      </form>
    </div><!-- /.col-lg-12 -->
	<form class="form-horizontal" role="form">
	<div class="col-lg-12">
	      <legend>Company Details</legend>
	</div>
	<div class="col-md-12">
		<?php 
			if(count($company)!=0){ ?>
		<div class="col-md-3"><b>Company</b></div>
		<div class="col-md-4"><b>Position</b></div> 
		 <?php 
				foreach($company as $val){
				?>
				<div class="clearfix">&nbsp;</div>
				<div class="col-md-3"><?php echo $val['company_name'];?></div>
				<div class="col-md-4"><?php echo $val['position_title'];?></div>
				<?php 
				}
			}else{ ?>
			<div class="col-md-12">There Are No Companies</div
			><?php }
			?>						
	</div>
	
	</form>	
	</div><!-- /.row -->
	

            
</div>


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>


<script>
function view_popup(member_id,popup)
{
	$("#myModal").html("");
	$.ajax({
	type:"post",
	url:"<?php echo base_url(); ?>admin/users/"+popup,
	data:{'member_id':member_id},
	success:function(data){
		$("#myModal").html(data);
		//alert(data);
		}
	});
}
function view_popup_comming(member_id,popup)
{
	$("#myModal").html("");
	$.ajax({
	type:"post",
	url:"<?php echo base_url(); ?>admin/users/"+popup,
	data:{'member_id':member_id},
	success:function(data){
		$("#myModal").html(data);
		//alert(data);
		}
	});
}

</script>