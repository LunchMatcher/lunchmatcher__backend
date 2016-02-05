<script type="text/javascript" src="<?php echo base_url('assets')?>/js/jquery.form.js"></script>
<script type="text/javascript">
// prepare the form when the DOM is ready 
$(document).ready(function() { 
    var options = { 
        target:        '#status',   // target element(s) to be updated with server response 
        //beforeSubmit:  showRequest,  // pre-submit callback 
        success:       showResponse,  // post-submit callback 
 		dataType:  'json'
        // other available options: 
        //url:       url         // override for form's 'action' attribute 
        //type:      type        // 'get' or 'post', override for form's 'method' attribute 
        //dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
        //clearForm: true        // clear all form fields after successful submit 
        //resetForm: true        // reset the form after successful submit 
 
        // $.ajax options can be used here too, for example: 
        //timeout:   3000 
    }; 
 
    // bind form using 'ajaxForm' 
    $('#myform1').ajaxForm(options); 
}); 
 
 
 
// post-submit callback 
function showResponse(response, statusText, xhr, $form)  { 
//console.log(response);
	if(response.redirect_url){
		location.href = response.redirect_url;
	}
    else if(response.status == 'success'){
		if(response.message)
			$("#status").removeAttr('class').addClass('alert alert-success').html(response.message).append('. Redirecting...');		
		$('#myform1')[0].reset();
		window.location.href='<?php echo site_url('admin/home');?>';
		//setTimeout(function(){
			
			//location.href='<?php //echo site_url('admin/home');?>';},1000);
	}
	else
		$("#status").removeAttr('class').addClass('alert alert-danger').html(response.error_message);
    
}

</script>
		<div class="" id="status" style="width: 600px; margin: auto;"></div>
        <div class="form-box" id="login-box">
        	
            <div class="header">Sign In</div>
            <form id="myform1" action="<?php echo site_url('admin/login/authenticate_admin');?>" method="post">
                <div class="body bg-gray">
                    <div class="form-group">
                    	<input type="hidden" name="isAjax" value="1"  />
                        <input type="text" name="userid" class="form-control" placeholder="User ID"/>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Password"/>
                    </div>          
                    <!--<div class="form-group">
                        <input type="checkbox" name="remember_me"/> Remember me
                    </div>-->
                </div>
                <div class="footer">                                                               
                    <button type="submit" class="btn btn-info btn-block">Sign me in</button>  
                                        
                   <p>&copy; Lunch Matcher All Rights Reserved</p>
                </div>
            </form>

            
        </div>


      

  