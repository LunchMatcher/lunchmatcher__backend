// JavaScript Document
/*
author : Robin A Thomas
		 robin@newagesmb.com
*/
jQuery("#fileToUpload").change(function(){
	   var allowed_ext = ["jpg","JPG","jpeg","JPEG","png","PNG","gif","GIF"];
	 jQuery.each(this.files,function(index,value){
				 var fileExtension = "";
				var file = value;
				 
				if (file) {
				  var fileSize = 0;
				  if (file.size > 1024 * 1024)
					fileSize = (Math.round(file.size * 100 / (1024 * 1024)) / 100).toString() + 'MB';
				  else
					fileSize = (Math.round(file.size * 100 / 1024) / 100).toString() + 'KB';
					if(file.size > 2097152){
						alert("Maximum Allowed File size is 2MB");
						this.value = '';
						return false;
					}
					if (file.name.lastIndexOf(".") > 0) {
						fileExtension = file.name.substring(file.name.lastIndexOf(".") + 1, file.name.length);
					}
					if (jQuery.inArray(fileExtension, allowed_ext) == -1) {
						alert("Allowed File formats are gif/png/jpg/jpeg");
			 
						return false;
					}
					var fd = new FormData();
					fd.append('file',file);
					fd.append('form_key', window.FORM_KEY);
					var xhr = new XMLHttpRequest();
					xhr.upload.addEventListener("progress", uploadProgress, false);
					xhr.addEventListener("load", uploadComplete, false);
					xhr.addEventListener("error", uploadFailed, false);
					xhr.addEventListener("abort", uploadCanceled, false);
					jQuery.blockUI();
					xhr.open("POST", base_url+'slicklabs/index/uploadImages?isAjax=true');
					xhr.setRequestHeader("Cache-Control", "no-cache");
					xhr.send(fd);
			
				 }
					
			});
		
	});


	function uploadProgress(evt) {
			if (evt.lengthComputable) {
			  var percentComplete = Math.round(evt.loaded * 100 / evt.total);
			  jQuery('div#percent_div #sp_div').css("width", percentComplete.toString() + '%');
			  jQuery('div#percent_div #sp_div').html(percentComplete.toString() + '%');
			 
			}
			else {
			  document.getElementById('progressNumber').innerHTML = 'unable to compute';
			}
	}
	
	
	function uploadComplete(evt) {
	jQuery.unblockUI();
	/* This event is raised when the server send back a response */
	jQuery("#fileToUpload").val("");
	jQuery('div#percent_div #sp_div').css("width", '0%');
	jQuery('div#percent_div #sp_div').html("");
	//if(evt.target.responseText == "error")
	   //alert(evt.target.responseText);
	 insert_image_png(evt.target.responseText);
	 
	}
	
	
	 
	 
		function uploadFailed(evt) {
		alert("There was an error attempting to upload the file.");
		 
		}
		
		function uploadCanceled(evt) {
		alert("The upload has been canceled by the user or the browser dropped the connection.");
		 
		}