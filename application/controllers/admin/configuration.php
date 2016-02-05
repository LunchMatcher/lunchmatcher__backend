<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Configuration extends MY_Controller {

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
	 
	 * $this->load->library('crud');
	 * load crud library if u want to perform basic listing/add/edit and
	 * other similar stuffs
	 * $this->load->library('preferences');
	 * for showing a configuration table where users can only update fields
	 */	
	public function __construct()
	{
		parent::__construct();
		$this->_setAsAdmin();
		$this->load->model('admin/config_model');
		$this->user 	= $this->session->userdata('user');
		if($this->user=='')
			redirect('admin');		
	}
    public function index(){
		redirect('admin/configuration/settings');
	
	}
	public function settings(){
		$data['configlist']=$this->config_model->getAllEditableFields();
		//echo "<pre>"; print_r($data['configlist']); echo "</pre>"; exit;
		if($_SERVER['REQUEST_METHOD']=='POST')
		{
			 $post_aar	=	$_POST;
		 	foreach ($post_aar as $key => $value) 
			{
				$update_id = $this->config_model->update_by(array('field'=>$key), array('value'=>$value));
			}
			$this->session->set_flashdata('message', 'Preferences  Updated Successfully. ','SUCCESS');				
			redirect('admin/configuration/settings');
		} 
		$output['output']=$this->load->view('admin/settings/config', $data, true);
		$this->_render_output($output);
	}
	
	public function changePassowrd(){
		$output['output']=$this->load->view('admin/settings/changePassword','',true);
		$this->_render_output($output);
	}
	public function change(){
		$this->load->model('config_model');		
		if($_SERVER['REQUEST_METHOD']=='POST')
		{			
			$old_passord=$this->input->post('old_passord');
			$user=$this->session->userdata('user');
			if($user->username=='admin' && $user->role=='1')
			{
				$new_password=$this->input->post('new_password');
				$confirm_password=$this->input->post('confirm_password');
				$result=$this->config_model->change_password($user->username,$old_passord,$new_password);
				if($result!=0)
				{
					$this->session->set_flashdata('success_pwd', 'Password changed successfully');
					redirect('admin/configuration/changePassowrd');
				}
				else
				{
					$this->session->set_flashdata('error_pwd', 'Old Password was incorrect');
					redirect('admin/configuration/changePassowrd');
				}
			}
				
		}
		else{
			redirect('admin/home');	
		}
	}
	
	public function emailconfig(){
		$data['templatelist']=$this->config_model->getAllEmailtemplates();
		//echo "<pre>"; print_r($data['templatelist']); echo "</pre>"; exit;
		$output['output']=$this->load->view('admin/settings/emailconfig',$data,true);
		$this->_render_output($output);
	}
	public function addemail($id=''){
	$this->load->library('form_validation');
		if($_SERVER['REQUEST_METHOD']=='POST')
		{	
		$this->form_validation->set_rules('email_title', 'Email Title', 'required');
		$this->form_validation->set_rules('email_subject', 'Email Subject', 'required');
		$this->form_validation->set_rules('content', 'Content', 'required');
		if ($this->form_validation->run() === TRUE){
		
			$data=array(
				'email_title'=>$this->input->post('email_title'),
				'email_template'=>$this->input->post('content'),
				'email_subject'=>$this->input->post('email_subject')
			);
			$email_id=$this->input->post('email_id');
			$id = $this->config_model->updateEmailTemplate($email_id, $data);		
			$msg = ' Email Template successfully updated.!' ;
			$this->session->set_flashdata('message', $msg ,'SUCCESS');					
			redirect('admin/configuration/emailconfig');
		}
		else
		{
		$data['details']=array(
				'email_title'=>$this->input->post('email_title'),
				'email_template'=>$this->input->post('content'),
				'email_subject'=>$this->input->post('email_subject')
			);
			$output['output']=$this->load->view('admin/settings/addemailconfig',$data, true);
			$this->_render_output($output);
			
		}
		}
		
		else
		{
			$data['details'] = $this->config_model->getEmilDetails($id);
			//print_r($data['details']);exit;
			//print_r($data['details']);exit;
			$output['output']=$this->load->view('admin/settings/addemailconfig',$data, true);
			$this->_render_output($output);	
		}

	}		
	
}
