<div class="modal-dialog cust_dilog">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#00A06F;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Profile :<?php echo $user->member_id;?></h4>
        <span class="point-right">Score : <?php echo number_format($user->total_score);?></span>
      </div>
      <div class="modal-body">
        <fieldset>

          <!-- Text input-->
              <div class="form-group">
                  <div class="col-lg-5">
                    <label for="textinput" class="control-label">First Name</label>
                  </div>
              <div class="col-lg-1">:
              </div>
                    <div class="col-lg-6">
                      <label for="textinput" class="control-label"><?php echo $user->first_name;?>
                      </label>
                    </div>
              </div>
			  <div class="clearfix"></div>
          	    <div class="form-group">
                  <div class="col-lg-5">
                    <label for="textinput" class="control-label">Last Name</label>
                  </div>
              <div class="col-lg-1">:
              </div>
                    <div class="col-lg-6">
                      <label for="textinput" class="control-label"><?php echo $user->last_name;?>
                      </label>
                    </div>
              </div>
			  <div class="clearfix"></div>
              
               <div class="form-group">
                  <div class="col-lg-5">
                    <label for="textinput" class="control-label">Formatted Name</label>
                  </div>
              <div class="col-lg-1">:
              </div>
                    <div class="col-lg-6">
                      <label for="textinput" class="control-label"><?php echo $user->formatted_name;?>
                      </label>
                    </div>
              </div>
			  <div class="clearfix"></div>
               <div class="form-group">
                  <div class="col-lg-5">
                    <label for="textinput" class="control-label">Headline</label>
                  </div>
              <div class="col-lg-1">:
              </div>
                    <div class="col-lg-6">
                      <label for="textinput" class="control-label"><?php echo $user->headline;?>
                      </label>
                    </div>
              </div>
			  <div class="clearfix"></div>
               <div class="form-group">
                  <div class="col-lg-5">
                    <label for="textinput" class="control-label">Email</label>
                  </div>
              <div class="col-lg-1">:
              </div>
                    <div class="col-lg-6">
                      <label for="textinput" class="control-label"><?php echo $user->email;?>
                      </label>
                    </div>
              </div>
			  <div class="clearfix"></div>
               <div class="form-group">
                  <div class="col-lg-5">
                    <label for="textinput" class="control-label">Gender</label>
                  </div>
              <div class="col-lg-1">:
              </div>
                    <div class="col-lg-6">
                      <label for="textinput" class="control-label"><?php echo $user->gender;?>
                      </label>
                    </div>
              </div>
			  <div class="clearfix"></div>
              
               <div class="form-group">
                  <div class="col-lg-5">
                    <label for="textinput" class="control-label">Mobile</label>
                  </div>
              <div class="col-lg-1">:
              </div>
                    <div class="col-lg-6">
                      <label for="textinput" class="control-label"><?php echo $user->contact_number;?>
                      </label>
                    </div>
              </div>
			  <div class="clearfix"></div>
               <div class="form-group">
                  <div class="col-lg-5">
                    <label for="textinput" class="control-label">Location</label>
                  </div>
              <div class="col-lg-1">:
              </div>
                    <div class="col-lg-6">
                      <label for="textinput" class="control-label"><?php echo $user->location;?>
                      </label>
                    </div>
              </div>
			  <div class="clearfix"></div>
               <div class="form-group">
                  <div class="col-lg-5">
                    <label for="textinput" class="control-label">Industry</label>
                  </div>
              <div class="col-lg-1">:
              </div>
                    <div class="col-lg-6">
                      <label for="textinput" class="control-label"><?php echo $user->industry;?>
                      </label>
                    </div>
              </div>
			  <div class="clearfix"></div>
              
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
                    <div class="col-md-12">There are no companies added.</div
                    ><?php }
                    ?>						
            </div>
          
			<div class="clearfix"></div><br/>
			<div class="col-lg-12">
                  <legend>Companies Excluded</legend>
            </div>
            <div class="col-md-12">
                <?php 
                    if(count($exclude_companies)!=0){ ?>
                
                 <?php 
                        foreach($exclude_companies as $comp){
                        ?>
                        <div class="col-md-7" style="padding-bottom:4px;"><img src="<?php echo $comp['company_logourl']; ?>" alt="" width="60" height="35"/> &nbsp;
                         <?php echo $comp['company_name'];?></div>
                        <?php 
                        }
                    }else{ ?>
                    <div class="col-md-12">There are no companies</div
                    ><?php }
                    ?>						
            </div>
        </fieldset>
      </div>

    </div>
  </div>
