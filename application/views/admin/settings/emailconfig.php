<?php //echo http_build_query( $this->input->get() );?>

<form name="nudgerMasterForm" id="nudgerMasterForm" method="post" action="">
<input type="hidden" name="actions" id="actions" value="" />
  <div class="row" id="Title">
    <div class="col-lg-12"><legend>CMS Details</legend></div>
  </div>
  
 


  <table class="table table-bordered table-hover"> 
    <thead><!--<a href="javascript:" id="deleteSel" class="delete_icon">Delete</a>-->
      <tr>
        <th>Email Title</th>
        <th width="300">Email Subject</th>
		</tr> 
    </thead>
	<tbody>
	 <?php if(sizeof($templatelist) > 0)  : //echo "<pre>"; print_r($userlist); exit;?>
     <?php foreach($templatelist as $val) : ?>
	
		<tr href="<?php echo base_url()?>admin/configuration/addemail/<?php echo $val['email_id'];?>" style="cursor:pointer">
		
			
	        <td><a href="<?php echo base_url()?>admin/configuration/addemail/<?php echo $val['email_id'];?>" class="link1">
			<?php echo $val['email_title'];?>
			</a>
			</td>
      		<td><?php echo $val['email_subject'];	 ?></td>
	  <?php endforeach; ?>
      <?php else: ?>
	  <tr><td  colspan="8">No records...</td></tr>
	  <?php endif; ?>
            </tbody>
    </table>

	  </form>
	<script>
	 $(document).ready(function(){
		$('table tr').click(function(){
			window.location = $(this).attr('href');
			return false;
		});
	});
	</script>