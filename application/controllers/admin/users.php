<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends MY_Controller {


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
		$this->load->model('admin/admin_model');
		$this->load->model('admin/email_model');
		$this->load->model('admin/user_model');
		$this->user 	= $this->session->userdata('user');
		if($this->user=='')
			redirect('admin');
	}	
	function index(){
		redirect('admin/users/lists');
	}
	public function lists(){	
	
			$data['page_limit']= $this->config->item('default_pagination');
			
			$data['order_by_field'] =  (!$this->input->post('order_by_field') ? ($this->input->get('order_by_field') ? $this->input->get('order_by_field') :''):$this->input->post('order_by_field'));
            $data['order_by_value']      =  (!$this->input->post('order_by_value') ? ($this->input->get('order_by_value') ? $this->input->get('order_by_value') :''):$this->input->post('order_by_value'));
				
			$data['key']	=	(!$this->input->post('key') ? ($this->input->get('key') ? $this->input->get('key') :''):$this->input->post('key'));
			$data['status']	=	(!$this->input->post('status') ? ($this->input->get('status') ? $this->input->get('status') :''):$this->input->post('status'));
			$data['limit']	=	(!$this->input->post('limit') ? ($this->input->get('limit') ? $this->input->get('limit') :$data['page_limit']):$this->input->post('limit'));
			
			$config['total_rows']=$this->user_model->getUserCount();
			$config['per_page'] = $data['limit'] == 'all' ? $config['total_rows'] :$data['limit'];
			$_REQUEST['per_page'] = $_REQUEST['per_page']?$_REQUEST['per_page']:0;
			$data['status'] = $data['status']?$data['status']:'Y';
			
			$data['userlist']=$this->user_model->getUserLists($data['status'], $config['per_page'],$_REQUEST['per_page'],
									$data['key'],$data['order_by_field'],$data['order_by_value']);
	
			//$data['userlist']=$this->user_model->get_all();

			$params = '?t=1';
			if($data['limit']!='') $params .= '&limit='.$data['limit'];
			if($data['key']!='') $params .= '&key='.$data['key'];
			if($data['status']!='') $params .= '&status='.$data['status'];
			if($data['order_by_field']!='') $params .= '&order_by_field='.$data['order_by_field'];
			if($data['order_by_value']!='') $params .= '&order_by_value='.$data['order_by_value'];
			$this->load->library('pagination');

			$config['base_url'] = site_url("admin/users/lists")."/".$params;
			
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
		
		//----------------------------------------------------------
		
		//$output['output']=$this->showList($baseurl,count($data['userlist']),$data['userlist']);
		//print_r($output['output']);exit;
		//echo $this->user_model->db->last_query();exit;
		//echo "<pre>"; print_r($data['userlist']); echo "</pre>"; exit;
		$output['output']=$this->load->view('admin/users/lists', $data, true);
		$this->_render_output($output);
	}


	
	public function details($id = ''){
		$this->load->model('user_model');
		$this->load->library('form_validation');
		if($id != ''){
			$data['user']=$this->user_model->getUserDetails($id);
			$data['company']=$this->user_model->getUserCompanyDetails($id);
			//echo "<pre>"; print_r($data['company']); echo "</pre>"; exit;
			//$data['user'] = $this->user_model->get_by(array('member_id'=>$id));
			$output['output'] = $this->load->view('admin/users/add_details',$data,true);
		}else{
			$output['output'] = $this->load->view('admin/users/add_details','',true);
		}
			$this->_render_output($output);
	}
	
	public function profile($id = ''){
		$id = $this->input->post('member_id')?$this->input->post('member_id'):$id;
		
		if($id != ''){
			$data['user']=$this->user_model->getUserDetails($id);
			$data['company']=$this->user_model->getUserCompanyDetails($id);
			$data['exclude_companies']=$this->user_model->getUserExcludedCompanies($id);
			//echo "<pre>"; print_r($data['user']); echo "</pre>"; exit;
			//$data['user'] = $this->user_model->get_by(array('member_id'=>$id));
			
			
			$output = $this->load->view('admin/users/profile_popup_view',$data,true);	
		}
			echo $output; exit;
	}
	public function history($id = ''){
		$id = $this->input->post('member_id')?$this->input->post('member_id'):$id;
		
		if($id != ''){
			$data['users']= $this->user_model->getHistory($id);
			//echo "<pre>"; print_r($data['users']); echo "</pre>"; exit;
			
			$output = $this->load->view('admin/users/match_history',$data,true);	
		}
			echo $output; exit;
	}
	
	
	public function add($id='')
	{

			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			$id = $this->input->post('id')?$this->input->post('id'):$id;
			if($id){
				$data['user']=$this->user_model->getUserDetails($id);
				//$data['user'] = $this->user_model->get_by(array('member_id'=>$id));
			}
			else{
				$data['user'] = $_POST;
			}
			if($_SERVER['REQUEST_METHOD']=='POST')
			{			
					$this->form_validation->set_rules('first_name', 'First Name', 'required');
					$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
					$this->form_validation->set_rules('gender', 'Gender', 'required');

				if ($this->form_validation->run() === TRUE){
						$data['detail1']=array(
							'first_name'=>$this->input->post('first_name'),
							'last_name'=>$this->input->post('last_name'),
							'maiden_name'=>$this->input->post('maiden_name'),
							'formatted_name'=>$this->input->post('formatted_name'),
							'headline'=>$this->input->post('headline'),
							'email'=>$this->input->post('email'),
							'gender'=>$this->input->post('gender'),
							'created_time'=>date('Y-m-d')
						);
					if($id)
					{
						$data['detail2']=array(
								'contact_number'=>$this->input->post('mobile'),
								'location'=>$this->input->post('location'),
								'industry'=>$this->input->post('industry'),
								'member_id'=>$id
							);
						$row = $this->user_model->check_user($id);
						$this->user_model->update_by(array('member_id'=>$id),$data['detail1']);

						if($row==1){
							$this->user_model->updateDtetails($data['detail2'],$id);
						}else{
							$member_id	=	$this->user_model->insertDetails($data['detail2']);
						}
						$this->session->set_flashdata('message', 'User Details Updated Successfully','SUCCESS');
						redirect('admin/users/lists/');
					}	
					else
					{
							$row = $this->user_model->get_by('email',$this->input->post('email'));
							
							if(count($row)==1)
							{
								$this->session->set_flashdata('error', 'Email Already Exists','ERROR');
							}
							else{
								$member_id	=	$this->user_model->insert($data['detail1']);
								$data['detail2']=array(
									'member_id'=>$member_id,
									'contact_number'=>$this->input->post('mobile'),
									'location'=>$this->input->post('location'),
									'industry'=>$this->input->post('industry')
								);
								$res	=	$this->user_model->insertDetails($data['detail2']);
								$this->session->set_flashdata('message', 'User Details Added Successfully','SUCCESS');
					    		redirect('admin/users/lists/');
							}
						
					}	
			
				}
			}
			
			$output['output'] = $this->load->view('admin/users/add_details',$data,true);
			$this->_render_output($output);
			
	}
		
	
	public function view($id = ''){
		$this->load->model('user_model');
		$this->load->model('general_model');
		$this->load->model('haul_model');
		if($id != ''){
			$user = $this->user_model->get($id);
			$data['user'] = $user;
			$data['user_detail'] = $this->user_model->getUserData(array('user_master.id'=>$id));
			$data['driver_profile'] = $this->user_model->getDriverProfile(array('driver_profile.user_id'=>$id));
			$data['hauls'] = $this->haul_model->get_many_by(array('user_id'=>$id));
			$data['completed_hauls'] = $this->haul_model->get_many_by(array('user_id'=>$id,'status'=>'complete','user_confirmed'=>'Y'));
			if($user->type_id == 3){
				$data['earnings'] = $this->user_model->get_earnings($id);
			}
			$output['output'] = $this->load->view('admin/user_detail',$data,true);
			$this->_render_output($output);
		}
		else
			redirect('admin/home');
	}
	
	
	
	public function bulkAction($bulkaction_list='',$user_id)
	{	
	
		
		$user_id = $this->input->post('sel');
		$bulkaction =  $this->input->post('bulkaction');
		
		$id=$this->uri->segment(5);
		$user_id = $this->input->post('sel')?$this->input->post('sel'):$id;
		if($bulkaction_list=='Permdelete')
			$bulkaction	=	'Permdelete';
		if($bulkaction=='')
			$bulkaction	=	'delete';
		if($bulkaction){
			if($user_id){
				switch($bulkaction){
					case 'delete':
						//$delete_id = $this->user_model->delete_by(array('member_id'=>$user_id));
						$update_id = $this->user_model->update_by(array('member_id'=>$user_id), array('status'=>'T'));				
						$this->session->set_flashdata('message', 'User(s) Successfully Deleted ');
						break;
					case 'inactive':
						$update_id = $this->user_model->update_by(array('member_id'=>$user_id), array('status'=>'N'));						
						if($update_id){
							if(sizeof($user_id) == 1)
								$msg = 'User details updated successfully' ;
							else
								$msg = sizeof($user_id).' User details Successfully Updated.!' ;
							$this->session->set_flashdata('message', $msg ,'SUCCESS');	
						}
						break;
					case 'Permdelete':
						$delete_id = $this->user_model->delete_by(array('member_id'=>$user_id));
						$this->session->set_flashdata('message', 'User Successfully Deleted ');
						break;
					case 'active':
						$update_id = $this->user_model->update_by(array('member_id'=>$user_id), array('status'=>'Y'));						
						if($update_id){
							if(sizeof($user_id) == 1)
								$msg = 'User details updated successfully' ;
							else
								$msg = sizeof($user_id).' User details Successfully Updated.!' ;
							$this->session->set_flashdata('message', $msg ,'SUCCESS');	
						}
						break;
				}
			}
			else{
				$this->session->set_flashdata('message', 'Please select at least one member.! ','ERROR');	
			}
		}
		redirect('admin/users/lists');
	}
	public function ajaxstatus()
	{
			$user_id	=	$this->input->get('id');
			$status		=	$this->input->get('status') == 'Y' ? 'N':'Y';
			$update_id = $this->user_model->update_by(array('member_id'=>$user_id), array('status'=>$status));						
			if($status=='N')
					$this->session->set_flashdata('message', "  User Deactivated ");
			else
					$this->session->set_flashdata('message', "  User Activated   ");
			$this->session->set_flashdata('class', "success");
			echo $status;	
			
	}	
	public function ajaxblock()
	{
			$user_id	=	$this->input->get('id');
			$block		=	$this->input->get('block') == 'Y' ? 'N':'Y';
			$update_id = $this->user_model->update_by(array('member_id'=>$user_id), array('is_block'=>$block));						
			if($block=='Y')
			{
					$details = $this->user_model->getUserListsByID($user_id);	
							
					$this->load->library('email');
					$to = $details['email'];	
					$from = getConfigValue("email_from");	

       				$email = $this->email_model->get_email_template("user_blocked");		
					$subject = $email['email_subject'];        
       				$message = $email['email_template'];
		
					$fullname=$details['first_name'].' '.$details['last_name'];
                    $message = str_replace('#FULL_NAME#', $fullname, $message);
                    $message = str_replace('#EMAIL#',$from, $message);
					
					$this->email->from($from);
					$this->email->reply_to($from);
					$this->email->to($to);
					$this->email->subject($subject);
					$this->email->message($message);
					$this->email->send();							
							
										
					$this->session->set_flashdata('message', "  User Blocked ");
			}
			else
			{
					$this->session->set_flashdata('message', "  User Unblocked   ");
			}
			$this->session->set_flashdata('class', "success");
			echo $status;	
			
	}
	public function preferences()
	{
		$member_id=$_POST['member_id'];
		$data['preference']=$this->user_model->getPreferences($member_id);
		//print_r($data['preference']);exit;
		echo $this->load->view('admin/users/viewPreference',$data);	
	}
	public function feedback()
	{
		$member_id=$_POST['member_id'];
		$data['feedback']=$this->user_model->getFeedbacks($member_id);
		echo $this->load->view('admin/users/viewFeedback',$data);	
	}
	public function points()
	{
		$member_id=$_POST['member_id'];
		$data['details']=$this->user_model->getPoints($member_id);
		echo $this->load->view('admin/points/viewDetails',$data);
	}
	public function venues()
	{    
		$member_id=$_POST['member_id'];
		$data['details']=$this->user_model->getUserVenueDetails($member_id);
	
		echo $this->load->view('admin/venue/viewDetails',$data);	
	}	
	public function favourites()
	{
		$member_id=$_POST['member_id'];
		$data['googleKey']='AIzaSyCSB01UwvVbd63eV_scq-rOD4AEirD8z9Q';
		$data['details']=$this->user_model->getUserFavouriteVenues($member_id);
		//echo "<pre>"; print_r($data['details']); echo "</pre>"; exit;
		echo $this->load->view('admin/users/viewFavourites',$data);	
	}	
	public function addpoint(){
		   $member_id		=$_POST['member_id'];
		   $points			=$_POST['points'];
		   $insert_point 	= $this->user_model->insert_fields('member_points_log',
		   														array('member_id'=>$member_id,
																	  'point_id'=>10,
																	  'created_date'=>date('Y-m-d H:i:s'),
																	  'action_date'=>date('Y-m-d'),
																	  'points'=>$points));
		   echo $points; 
		   exit;
		  
	  }
}