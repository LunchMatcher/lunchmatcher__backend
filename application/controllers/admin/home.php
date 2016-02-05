<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class home extends MY_Controller {

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
		$this->user 	= $this->session->userdata('user');
		if($this->user=='')
			redirect('admin');	
		$this->load->model('admin/user_model');		
		$this->load->model('venue_model');		
	}
	
	public function index()
	{	

		$data['template_url']     = $this->template_url ;
		$data['userscount']	   = $this->user_model->count_all(array('status'=>'Y'));
		
		$data['pointscount']	  = $this->user_model->getPointedUsers();
		//$this->user_model->setTable('general_config');		
		//$data['config_count']	 = $this->user_model->count_all();
		$data['availablecount']   = $this->user_model->getTodaysAvailabilityCount(date("Y-m-d"));
		
		$this->user_model->setTable('venue_details');	
		$data['venuecount']=$this->user_model->count_all();
		//echo "<pre>"; print_r($data['venuelist']); echo "</pre>"; exit;
				
		
		
		
		
		
		
		$ouput['output']		= $this->load->view('admin/'.$this->admin_theme.'/home',$data,true);		
		$this->_render_output($ouput);
	}
	
	
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */