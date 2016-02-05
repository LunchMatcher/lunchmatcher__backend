<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends My_Controller {

	public function __construct()
	{                        
		parent::__construct();
		$this->load->library('email_lib');
		$this->load->helper(array('url','cookie'));	
		$this->load->model('user_model');		
	}
		
	 
	public function index()
	{
		
		//$data['Api_key']= getConfigValue('linkedin_api_key');
		$this->user_model->setTable('country');
		$data['countrylist']=$this->user_model->get_all();
		//print_r($data['countrylist']);exit;
		$data['Api_key']= '75j6543f18uofs';
		$this->load->view('home',$data);
	}
	
	public function add()
	{
	//echo "<pre>"; print_r($data);	echo "</pre>"; exit;
		$this->load->model('user_model');
		$data['details']=array(
			'firstname'=>$this->input->post('firstname'),
			'lastname'=>$this->input->post('lastname'),
			'email'=>$this->input->post('email'),
			'country'=>$this->input->post('country'),
			'industry'=>$this->input->post('industry'),
			'experience_level 	'=>$this->input->post('level'),
			'created_date'=>date('Y-m-d H:s')
		);
		$data['email']=$this->input->post('email');
		$data['firstname']=$this->input->post('firstname');
		
		$res	=	$this->user_model->insertEnquiryDetails($data['details']);
		if($res==0){
			$this->email_lib->registrationSuccessMailToAdmin($data);	
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
			'picture_url'=>$this->input->post('picture_url'),
			'default'=>'N',
			'created_time'=>date('Y-m-d')
		);
		$data['email']=$this->input->post('email');
		$data['name']=$this->input->post('name');
		
		$res	=	$this->user_model->insertEnquiryDetailsLinked($data['details']);
		if($res==0){
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