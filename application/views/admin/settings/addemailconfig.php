<link href="<?php echo base_url() ?>assets/css/bvalidator.css" rel="stylesheet">
<script src="<?php echo base_url() ?>assets/js/jquery.bvalidator.js"></script>
<script language="javascript" src="<?php echo base_url();?>assets/js/tiny_mce/tiny_mce.js"></script>

<div class="container"> 
	<div class="row">
    <form id="nudgeForm" class="form-horizontal" role="form" action="<?php echo base_url()?>admin/configuration/addemail" method="post">
		<input type="hidden" name="email_id" value="<?php echo $details['email_id']; ?>"  />
	   <div class="col-lg-12">
	      <legend>Welcome Details</legend>
		</div> 
	  
	    <div class="clearfix">&nbsp;</div>

		<div class="col-md-12">
			<div class="bs-docs-example">
				<div class="clearfix" style="background-color:#FFFFFF">&nbsp;</div>
				<div id="myTabContent" class="tab-content" style="background-color:#FFFFFF; padding-left:16px;">
				
						<span>Section</span>
							<fieldset>
							  <div class="form-group">
								<label class="col-sm-2 control-label" for="textinput">Email Title</label>
								<div class="col-sm-10">
									  <input type="text" name="email_title" placeholder="Email Title" class="form-control" value="<?php echo $details['email_title']; ?>">
								</div>
							  </div>
					
							   <!-- Text input-->
							  <div class="form-group">
								<label class="col-sm-2 control-label" for="textinput">
								Content
								</label>
								<div class="col-sm-10">
								 <textarea id="test" name="content" placeholder="Content" class="mceEditor form-control" style="height:250px"><?php echo $details['email_template']; ?></textarea>
								
								</div>
							  </div>
							  
							  <div class="form-group">
								<label class="col-sm-2 control-label" for="textinput">Email Subject</label>
								<div class="col-sm-10">
									  <input type="text" name="email_subject" placeholder="Email Subject" class="form-control" value="<?php echo $details['email_subject']; ?>">
								</div>
							  </div>
							  
							
							  
							</fieldset>
						 <hr/>
				</div>
			</div>
				
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
				  <div class="pull-right">
					<a href="javascript:"  class="blu_btn">
					<button type="button" class="btn btn-default">Cancel</button>
					</a>
					<button type="submit" class="btn btn-info" name="submit_btn" id="submit_btn">Save</button>
				  </div>
				</div>
			  </div>
	
		  
		 
		</div><!-- /.col-lg-12 -->
	</form>
	</div><!-- /.row -->
	

            
</div>


<script>

tinyMCE.init({
			mode : "specific_textareas",
       		editor_selector : "mceEditor",
			theme : "advanced",
		plugins : "autolink,lists,pagebreak,style,layer,table,advhr,advimage,emotions,iespell,inlinepopups,preview,media,searchreplace,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",
theme_advanced_buttons1 : "save,newdocument,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect,emotions,preview,code,undo,redo,bullist,numlist",
		theme_advanced_buttons2 : "",
			theme_advanced_buttons1_add_before : "newdocument,separator",
			theme_advanced_buttons1_add : "fontselect,fontsizeselect,separator,forecolor,backcolor",
			theme_advanced_buttons2_add : "",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			extended_valid_elements : "hr[class|width|size|noshade]",
			file_browser_callback : "ajaxfilemanager",
			paste_use_dialog : false,
			theme_advanced_resizing : true,
			theme_advanced_resize_horizontal : true,
			apply_source_formatting : true,
			force_br_newlines : true,
			force_p_newlines : false,	
			relative_urls : true
		});
		
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
</script>
