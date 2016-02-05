<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//error_reporting(E_ALL);
class Home extends My_Controller {

	public function __construct()
	{                        
		parent::__construct();
		$this->load->library('email_lib');
		$this->load->helper(array('url','cookie'));		
		$this->load->model('user_model');		
	}
		
	 
	public function index()
	{
		$this->user_model->setTable('country');
		$data['countrylist']=$this->user_model->get_all();
		//$data['Api_key']= getConfigValue('linkedin_api_key');
		$data['Api_key']= '75j6543f18uofs';
		$this->load->view('home',$data);
	}
	public function contact()
	{
		$this->load->model('user_model');
		$name 		= $this->input->post('name');
		$email	   = $this->input->post('email');
		$message	 = urldecode($this->input->post('message'));
			
				$this->load->library('email');
				$this->email->from($email, $name);
				$admin_email = $this->config->item('admin_email')?$this->config->item('admin_email'):'info@lunchmatcher.com';
				$this->email->to($admin_email); 
				$this->email->subject('One Contact Message from '.ucfirst($name));
				$this->email->message($message);	
				
				$send = $this->email->send();
				
				if($send){
					echo "success";
				}
				else{
					echo "fail";
				}
				
		exit;
	}
	public function add()
	{
		$this->load->model('user_model');
		$data['details']=array(
			'first_name'=>$this->input->post('firstname'),
			'last_name'=>$this->input->post('lastname'),
			'email'=>$this->input->post('email'),
			'auth_type'=>'general',
			'default'=>'W',
			'created_time'=>date('Y-m-d H:s')
		);
			
		
		$data['email']=$this->input->post('email');
		$data['name']=$this->input->post('firstname').' '.$this->input->post('lastname');
		$data['country']=$this->input->post('country');
		$data['industry']=$this->input->post('industry');
		$data['level']=$this->input->post('level');
		//echo "<pre>"; print_r($data);	echo "</pre>"; exit;
		$member_id	=	$this->user_model->insertEnquiryDetails($data['details']);
		$data['details2']=array(
			'member_id'=>$member_id,
			'location'=>$this->input->post('country'),
			'industry'=>$this->input->post('industry'),
			'experience_level'=>$this->input->post('level')
		);	
		
		
		
		if($member_id!="error"){
			$res	=	$this->user_model->insertEnquiryProfileDetails($data['details2']);
			$this->email_lib->registrationSuccessMailToAdmin($data);	
			$this->email_lib->registrationSuccessMailToUser($data);	
			echo "success";
		}else{
			echo "error";
		}
		/*echo "<script>alert('You are successfully registerd');window.location.href='welcome';</script>";*/
		//redirect('welcome');
		exit;
	}
	public function addLinkedin()
	{
		$this->load->model('user_model');
		$data['details']=array(
			'auth_id'=>$this->input->post('auth_id'),
			'email'=>$this->input->post('emailAddress'),
			'first_name'=>$this->input->post('firstName'),
			'last_name'=>$this->input->post('lastName'),
			'headline'=>$this->input->post('headline'),
			'auth_type'=>'linkedin',
			'picture_url'=>$this->input->post('picture_url'),
			'default'=>'N',
			'created_time'=>date('Y-m-d H:s')
		);
		
		$data['email']=$this->input->post('emailAddress');
		$data['industry']=$this->input->post('industry');
		$data['country']=$this->input->post('location');
		$data['name']=$this->input->post('firstName').' '.$this->input->post('lastName');
		
		$member_id	=	$this->user_model->insertEnquiryDetailsLinked($data['details']);
		$data['details2']=array(
			'member_id'=>$member_id,
			'location'=>$this->input->post('location'),
			'industry'=>$this->input->post('industry')
		);	
		if($member_id!="error"){
			$res	=	$this->user_model->insertEnquiryProfileDetails($data['details2']);
			$this->email_lib->registrationSuccessMailToAdmin($data);	
			$this->email_lib->registrationSuccessMailToUser($data);	
			echo "success";
		}else{
			echo "error";
		}
		/*echo "<script>alert('You are successfully registerd');window.location.href='welcome';</script>";*/
		//redirect('welcome');
		exit;
	}
	
	public function commingsoon()
	{
		$this->load->view('commingsoon');
	}	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */