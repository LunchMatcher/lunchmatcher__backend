<?php //echo "<pre>"; print_r($all_location_details); exit;?>
<form name="cron_form" id="cron_form" method="post" action="">
		 <div class='row form-group'>
            <div class="col-lg-2"><label>Json Content :</label></div>
            <div class='col-lg-10'><textarea cols="100" rows="6" name="cron_check"></textarea></div>
        </div><div class='row form-group'><div class="col-lg-2"></div><div class="col-lg-10">
        <input type="submit" name="cron_submit" value="Submit" /></div></div>
</form>
<?php echo "<pre>"; print_r($result); echo "</pre>";  ?>


 