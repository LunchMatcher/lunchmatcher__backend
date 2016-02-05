<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Venue extends MY_Controller {
 
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */	
	public function __construct()
	{
		parent::__construct();
		$this->_setAsAdmin();				
		$this->load->model('admin/venue_model');
		$this->load->library('session');
		$this->user 	= $this->session->userdata('user');
		if($this->user=='')
			redirect('admin');
	}	
	function index(){
		redirect('admin/venue/lists');
	}
	public function lists(){	
	
		$data['startDate']	=	(!$this->input->post('startDate') ? ($this->input->get('startDate') ? $this->input->get('startDate') : date('Y-m-d')):$this->input->post('startDate'));
		$data['endDate']	=	(!$this->input->post('endDate') ? ($this->input->get('endDate') ? $this->input->get('endDate') : date('Y-m-d')):$this->input->post('endDate'));
		
		//print_r($_POST);exit;
		$data['venuelist']=$this->venue_model->getAllScheduledVenues($data['startDate'],$data['endDate']);
		//echo $this->user_model->db->last_query();exit;
		//echo "<pre>"; print_r($data['venuelist']); echo "</pre>"; exit;
		$output['output']=$this->load->view('admin/venue/lists', $data, true);
		$this->_render_output($output);
		
		
	}
	public function details()
	{
		$venue_id=$_POST['venue_id'];
		$data['details']=$this->venue_model->getVenueUserDetails($venue_id);
		//echo "<pre>"; print_r($data['details']); echo "</pre>"; exit;
		echo $this->load->view('admin/venue/viewDetails',$data);	
		
	}
	public function meetings()
	{
		$venue_id=$_POST['venue_id'];
		$schedule_timefrom=$_POST['schedule_timefrom'];
		$schedule_timeto=$_POST['schedule_timeto'];
		$data['details']=$this->venue_model->getVenueScheduledDetails($venue_id,$schedule_timefrom,$schedule_timeto);
	
		//echo "<pre>"; print_r($data['details']); echo "</pre>"; exit;
		echo $this->load->view('admin/venue/viewMeetings',$data);	
		
	}
	
	
	public function venuelist()
	{
		  $data['page_limit']= $this->config->item('default_pagination');
		//var_dump($_POST);exit;
		 	$data['key']	=	(!$this->input->post('key') ? ($this->input->get('key') ? $this->input->get('key') :''):$this->input->post('key'));
			//$data['status']	=	(!$this->input->post('status') ? ($this->input->get('status') ? $this->input->get('status') :''):$this->input->post('status'));
			$data['limit']	=	(!$this->input->post('limit') ? ($this->input->get('limit') ? $this->input->get('limit') :$data['page_limit']):$this->input->post('limit'));
			$data['status']	=	(!$this->input->post('status') ? ($this->input->get('status') ? $this->input->get('status') :''):$this->input->post('status'));
			
			$config['total_rows']=$this->venue_model->getVenuCount($data['key'],$data['status']);
			$config['per_page'] = $data['limit'] == 'all' ? $config['total_rows'] :$data['limit'];
			$_REQUEST['per_page'] = $_REQUEST['per_page']?$_REQUEST['per_page']:0;
			///$data['status'] = $data['status']?$data['status']:'';
			
			$data['venulist']=$this->venue_model->getVenuLists( $config['per_page'],$_REQUEST['per_page'],$data['key'],$data['status']);
	
	
	       $params = '?t=1';
			if($data['limit']!='') $params .= '&limit='.$data['limit'];
			
			if($data['key']!='') $params .= '&key='.$data['key'];
			if($data['status']!='') $params .= '&status='.$data['status'];
			$this->load->library('pagination');

			$config['base_url'] = site_url("admin/venue/venuelist")."/".$params;
			
		//--------------------------------------------------
	
		
		
		// load pagination class
			$config['page_query_string'] = TRUE;
			$config['first_link'] = 'First';
			$config['last_link'] = 'Last';
			$config['full_tag_open'] = "<ul class='pagination'>";
			$config['full_tag_close'] ="</ul>";
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
			$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
			$config['next_tag_open'] = "<li>";
			$config['next_tagl_close'] = "</li>";
			$config['prev_tag_open'] = "<li>";
			$config['prev_tagl_close'] = "</li>";
			$config['first_tag_open'] = "<li>";
			$config['first_tagl_close'] = "</li>";
			$config['last_tag_open'] = "<li>";
			$config['last_tagl_close'] = "</li>";			
			$data['page'] = $_REQUEST['per_page'];
		   $this->pagination->initialize($config);	
		   
	       	
		
		
		
		//echo "<pre>"; print_r($data['venulist']); echo "</pre>"; exit;
		
		$output['output']=$this->load->view('admin/venue/venualllist',$data,true);
		$this->_render_output($output);
		
    }
	
	public function  view()
	{
		$id=$_POST['id'];
		$data['data']=$this->venue_model->getVenueListDetails($id);
	//echo "<pre>"; print_r($data['details']); echo "</pre>"; exit;
		echo $this->load->view('admin/venue/venu_view',$data);	
		
	}
   
	public function upload_sys_image($id='')
		{								
		
				$uploadDir ="uploads/venue/";
				if(!file_exists($uploadDir))
				mkdir($uploadDir);
				
				$fname = explode(".",str_replace(" ","",$_FILES['file']['name']));
				$fileName = preg_replace('/[^A-Za-z0-9\-]/', '', $fname[0]);
				$fileName = md5(microtime());
				$fileName=$fileName.'.'.$fname[1];
				$fileParts = pathinfo($_FILES['file']['name']);
                $image_name = $id . '.' . $fileParts['extension'];				
				$uploadFile = $uploadDir.$image_name;	
				
				if(move_uploaded_file($_FILES['file']['tmp_name'],$uploadFile))
				{
					 $imagePoP=array ("image" =>$image_name);
					 $this->venue_model->upDAteImage($imagePoP,$id);
				     echo $image_name;	
				 }
				  exit;
		}
	
	
	  public function venufeedback(){
	      $id=$_POST['id'];
		  
		  $data['feedback']=$this->venue_model->getFeedbacklist($id);
	   // echo "<pre>"; print_r($data['details']); echo "</pre>"; exit;
		  echo $this->load->view('admin/venue/venue_feedback',$data);	
	   }	
	    public function meetingfeedback(){
	      $id=$_POST['id'];
		  $f_member_id=$_POST['f_member_id'];
		  $t_member_id=$_POST['t_member_id'];
		  $data['feedback']=$this->venue_model->getFeedbackMeeting($id,$f_member_id,$t_member_id);
	   // echo "<pre>"; print_r($data['details']); echo "</pre>"; exit;
		  echo $this->load->view('admin/venue/venue_feedback',$data);	
	   }	
	  public function savephone(){
		   $id=$_POST['id'];
		   $phone=$_POST['phone'];
		   $this->venue_model->setTable('venue_details');
		   $update_id = $this->venue_model->update_by(array('id'=>$id), array('phone'=>$phone));
			exit;
		  
	  }
	  public function removeImage(){
		   $id=$_POST['id'];
		   $venue_id=$_POST['venue_id'];
		   $this->venue_model->setTable('venue_details');
		   $data1 = $this->venue_model->get_by(array('id'=>$id));
		   $update_id = $this->venue_model->update_by(array('id'=>$id), array('image'=>''));
		   $data = $this->venue_model->get_by(array('id'=>$id));
		   echo $data->photo_key;
		   $imgurl ="uploads/venue/".$data1->image;
		   	
		   if(file_exists($imgurl)){
		   		unlink($imgurl);
		   }
		   exit;
		  
	  }
	  
	  public function toggleBlock(){
	  		$id		=	$_POST['id'];
	   		$is_block  =	($_POST['is_block']=='Y')?'N':'Y';
			
	   		$this->venue_model->setTable('venue_details');
	   		$update_id = $this->venue_model->update_by(array('id'=>$id), array('is_block'=>$is_block));
			echo $is_block; exit;
	  }
	  
	
}