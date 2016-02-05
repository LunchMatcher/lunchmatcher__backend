<?php //echo "<pre>"; print_r($all_location_details); exit;?>
<form name="cron_form" id="cron_form" method="get" action="">
		 <div class='row form-group'>
            <div class="col-lg-2"><label>Latitude :</label></div>
            <div class='col-lg-10'><input type="text" id="latitude" name="latitude" value="<?php echo $_REQUEST['latitude'];?>" /></div>
        </div>
        <div class='row form-group'>
            <div class="col-lg-2"><label>longitude :</label></div>
            <div class='col-lg-10'><input type="text" id="longitude" name="longitude" value="<?php echo $_REQUEST['longitude'];?>" /></div>
        </div>
        <!--<div class='row form-group'>
            <div class="col-lg-2"><label>keyword :</label></div>
            <div class='col-lg-10'><input type="text" id="keyword" name="keyword" value="<?php echo $_REQUEST['keyword'];?>" /></div>
        </div>-->
        <div class='row form-group'>
            <div class="col-lg-2"><label>type :</label></div>
            <div class='col-lg-10'><input type="text" id="type" name="type" value="<?php echo $_REQUEST['type'];?>" /></div>
        </div>
       <!-- <div class='row form-group'>
            <div class="col-lg-2"><label>radius :</label></div>
            <div class='col-lg-10'><input type="text" id="radius" name="radius" value="<?php echo $_REQUEST['radius'];?>" /></div>
        </div>
        -->
        <div class='row form-group'>
            <div class="col-lg-2"><label>next page :</label></div>
            <div class='col-lg-10'><input type="text" id="nextpage" name="nextpage" value="<?php echo $_REQUEST['nextpage'];?>" /></div>
        </div>
        <div class='row form-group'><div class="col-lg-2"></div><div class="col-lg-10">
        <input type="submit" name="cron_submit" value="Submit" /></div></div>
</form>
<?php echo "<pre>"; print_r($url); echo "</pre>";  ?>
<?php echo "<pre>"; print_r($result); echo "</pre>";  ?>


 