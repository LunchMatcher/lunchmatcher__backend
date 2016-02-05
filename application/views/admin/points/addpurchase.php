 <div class="container"> <div class="row">
	   <div class="col-lg-12">
	      <legend>Purchase Details <?php //echo isset($purchaselist->firs_tname)?$user->first_name:''; ?></legend>
		</div> 
	
	   <div class="clearfix">&nbsp;</div>
	  
    <div class="col-md-12">
	
      <form class="form-horizontal" role="form" action="<?php echo base_url()?>admin/points/add" method="post">
        <fieldset>

          <!-- Form Name -->
      

          <!-- Text input-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">Title</label>
			
            <div class="col-sm-10">
              <input type="text" name="title" value="<?php echo isset($purchaselist['title'])?$purchaselist['title']:''; ?>" placeholder="Title" class="form-control">
			<span class="alert-danger"><?php //echo form_error('title');?></span>
			</div>
          </div>
		 <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">Description</label>
			
            <div class="col-sm-10">
              <input type="text" name="description" value="<?php echo isset($purchaselist['description'])?$purchaselist['description']:''; ?>" placeholder="Description" class="form-control">
			<span class="alert-danger"><?php //echo form_error('title');?></span>
			</div>
          </div>
            <!-- Text input-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">Point</label>
            <div class="col-sm-10">
              <input type="text" name="point" value="<?php echo isset($purchaselist['point'])?$purchaselist['point']:''; ?>" placeholder="point" class="form-control">
            </div>
          </div>
		    <!-- Text input-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">Price</label>
            <div class="col-sm-10">
              <input type="text" name="price" value="<?php echo isset($purchaselist['price'])?$purchaselist['price']:''; ?>" placeholder="Price" class="form-control">
            </div>
          </div>
		  
		
       
			 
		  
		<input type="hidden" name="id" value="<?php echo isset($purchaselist['id'])?$purchaselist['id']:''; ?>" />
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <div class="pull-right">
				
				<!--<a href="javascript:" onclick="window.location.href='<?php echo base_url()?>admin/users/lists'" class="blu_btn">
					<button type="button" class="btn btn-info">Back</button>
				</a>-->
			  	
                <a href="javascript:" onclick="window.location.href='<?php echo base_url()?>admin/points/purchase'" class="blu_btn">
				<button type="button" class="btn btn-default">Cancel</button>
				</a>
                <button type="submit" class="btn btn-info" name="submit_btn" id="submit_btn">Save</button>
              </div>
            </div>
          </div>

        </fieldset>
      </form>
    </div><!-- /.col-lg-12 -->

	</div><!-- /.row -->
	

            
</div>
