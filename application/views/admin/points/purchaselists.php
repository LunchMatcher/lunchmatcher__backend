

<?php //echo http_build_query( $this->input->get() );?>

<form name="userMasterForm" id="userMasterForm" method="post" action="">
<input type="hidden" name="order_by_field" id="order_by_field" value="<?php //echo $_REQUEST['order_by_field'];?>" />
<input type="hidden" name="order_by_value" id="order_by_value" value="<?php //echo $_REQUEST['order_by_value'];?>" />
<input type="hidden" name="actions" id="actions" value="" />
  <div class="row" id="Title">
    <div class="col-lg-12"><legend>Registered Users</legend></div>
  </div>
  
 <div class="row" <?php if(!$message && !$error){?>style="display:none" <?php } ?> id="msg_head">
      <?php if($message){?>
      <div class="alert alert-success col-lg-12 col-offset-1"><?php echo $message;?><!--danger-info-->
        <button data-dismiss="alert" class="close" type="button">x</button>
        
      </div><?php } if($error){ ?>
      
      
      <div class="alert alert-danger col-lg-12 col-offset-1"><?php echo $error;?><!--danger-info-->
        <button data-dismiss="alert" class="close" type="button">x</button>
        
      </div><?php } ?>
  </div>
  
  <div class="row">
    <div class="col-lg-1 pull-right">
      <p>
	  <a class="add_new_icon" alt="Add New" itle="Add New" href="<?php echo base_url()?>admin/points/add"><button class="btn btn-info btn-group-justified" type="button">Add</button></a>
     
      </p>
    </div>
  </div>



  <table class="table table-bordered table-hover"> 
    <thead><!--<a href="javascript:" id="deleteSel" class="delete_icon">Delete</a>-->
      <tr>
        <th colspan="2"><span class="link_blak" >Actions</span></th>
        <th> 
		Title
		
		</th>
        
        
        <th>Point
		
		</th>
       

		 <th>
		Price
		</th>
 		<th>
		Added Date
	
		</th>
      </tr> 
    </thead>
    <tbody>
	 <?php if(sizeof($purchaselist) > 0)  : //echo "<pre>"; print_r($userlist); exit;?>
     <?php foreach($purchaselist as $val) : ?>
	
      <tr>
        <td><center><a href="<?php echo base_url();?>admin/points/bulkAction/delete/<?php echo $val['id']; ?>?limit=<?php echo $limit;?>&per_page=<?php echo $per_page;?>" onclick="return confirm('Are you sure you want to delete?');"><i class="glyphicon glyphicon-trash" title="Delete"></i></a></center></td>
        
        
		<td><center>

		<a href="<?php echo base_url();?>admin/points/purchasedetails/<?php echo $val['id']; ?>" class="link1">
<i class="glyphicon glyphicon-edit" title="Detail"></i></a></center>
        </td>
                 
   
        <td><a href="<?php echo base_url();?>admin/points/purchasedetails/<?php echo $val['id']; ?>" class="link1"><?php echo $val['title']; ?></a></td>
        <td><a href="<?php echo base_url();?>admin/points/purchasedetails/<?php echo $val['id']; ?>" class="link1"><?php echo $val['point']; ?></a></td>
        
		<td><?php echo $val['price']; ?></td>
        <td><?php echo date(getConfigValue('date_format'), strtotime($val['created_date']));	 ?>
		</td>
        
		
		
		
		</tr>
	  <?php endforeach; ?>
      <?php else: ?>
	  <tr><td  colspan="8">No records...</td></tr>
	  <?php endif; ?>
            </tbody>
    </table>
  <div class="row" id="Table footer">
    <div class="col-lg-12 ">
	
		<div class="col-offset-1 col-lg-10">
				<div class="input-group">
					<ul class="pagination pull-right" style="margin:0px;">
					<?php 
					//$page_count = ceil($tot_prop / $limit);
					echo $this->pagination->create_links(); ?>
					</ul>
				</div>
		</div>
		
	
    </div>
  </div>
 
 
 
	
	  </form>
  

  
 
<script>
  $(document).ready(function(){
    $('.full_link').click(function(){
        window.location = $(this).attr('href');
        return false;
    });
});
</script>