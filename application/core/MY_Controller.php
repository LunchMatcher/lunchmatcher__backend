<?php
class MY_Controller extends CI_Controller {	
	
    public $admin_theme;
	public $frontend_theme;
	public $template_url;
	public $is_Admin = false;
	public $user;
	function __construct()
    {       
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('cookie');
		//$this->admin_theme  = getAdminTheme();
		$this->admin_theme  = 'admin_lte';
		$this->template_url = base_url('assets/'.$this->frontend_theme);
		$this->user 	= $this->session->userdata('user');
		
		
		
    }
	
	protected function _validate_cookie(){
		$this->load->helper('cookie');
		if($this->input->cookie('remember_me')){
			$autologin = json_decode($this->input->cookie('autologin'),true);
			$user = $this->user_model->get($autologin['id']);			
			if($user){			
				$cookie1 = array(
					'name'   => 'remember_me',
					'value'  => 'true',
					'expire' => '86500'					
				);					
				$this->input->set_cookie($cookie1);
				unset($user['password']);
				$cookie2 = array(
					'name'   => 'autologin',
					'value'  => json_encode($user),
					'expire' => '86500'					
				);
				$this->input->set_cookie($cookie2);
				$this->session->set_userdata($user);
			}
			else{
				delete_cookie('remember_me');
				delete_cookie('autologin');
			}			
		}
	
	}
	protected function _authenticate($userid){
	
		$user 		= $this->admin_model->getadmindetail($userid);
		
		//echo $user;exit;
		//$user		= is_object($user)?$user:$this->admin_model->getUser(array('username'=>$userid));
		if(count($user)!=0)
			return $user;
		else
			return NULL;
	}
	/*this is the place where the admin login data will be posted
	* This function is only to serve the login form submission for 
	* admin users
	*/
	public function authenticate_admin(){
		$this->load->library('form_validation');
		$this->load->model('admin_model');
		$this->form_validation->set_rules('userid','Username/Email','required');						
		$this->form_validation->set_rules('password','Password','required');	
			
		if($this->form_validation->run()){	
			$userid 	= $this->input->post('userid');
			$password	= $this->input->post('password');	
			
			$user 		= $this->_authenticate($userid);
			//print_r($user); exit;
			if(is_object($user) && $user->password === $password && $user->role  == 1){	
				unset($user->password);			
				if($this->input->post('remember_me') == 1){ 					
					$cookie1 = array(
						'name'   => 'remember_me',
						'value'  => 'true',
						'expire' => '86500'					
					);					
					$this->input->set_cookie($cookie1);
					$cookie2 = array(
						'name'   => 'autologin',
						'value'  => json_encode($user),
						'expire' => '86500'					
					);
					$this->input->set_cookie($cookie2);
					
				}
				else{
					delete_cookie('remember_me');
					delete_cookie('autologin');
				}
				$this->user 	= $user;
				$this->session->set_userdata('user',$user);
				
				if($this->input->post('isAjax')){
					echo json_encode(array('status'=>'success','message'=>'Login successfull!'));
					exit;
				}
				else{
					redirect('admin/home');
				}
				
				
				$this->session->set_flashdata('message','Login successfull!');
				if($_SERVER['HTTP_REFERER'])
					redirect($_SERVER['HTTP_REFERER']);
				else
					redirect('admin');
			}else{
				if($this->input->post('isAjax')){
					echo json_encode(array('status'=>'error','error_message'=>'Username/Email is invalid!'));
					exit;
				}
				$this->session->set_flashdata('error','Username/Email is invalid!');
				if($_SERVER['HTTP_REFERER'])
					redirect($_SERVER['HTTP_REFERER']);
				else
					redirect('admin');
				
			}
		}else{
			if($this->input->post('isAjax')){
				echo json_encode(array('status'=>'error','error_message'=>$this->form_validation->error_string()));
				exit;
			}
			$this->session->set_flashdata('error',$this->form_validation->error_string());
			if($_SERVER['HTTP_REFERER']){echo "hi"; exit;
				redirect($_SERVER['HTTP_REFERER']);
			}
			else{
				redirect('admin');
			}
		}	
			
				
	}
	public function _render_output($output = null)
	{ 		
		if($this->is_Admin){
			$template	= 'admin/'.$this->admin_theme;
			//$output['site_name']	= getConfigValue('site_name_admin');
			$output['site_name']	= 'Lunch Matcher Admin';
			
		}else{
			$template	= $this->frontend_theme;
			$output['site_name']	= getConfigValue('site_name_front');			
		}	
		$output['template_url'] = $this->template_url;	
		$this->load->view($template.'/header',$output);
		$this->load->view('view',$output);
		$this->load->view($template.'/footer',$output);
	}
	public function _setAsAdmin($flag = true){
		$this->is_Admin = $flag;
		if($this->is_Admin){
			$this->template_url = base_url('assets/'.$this->admin_theme);
		}else{
			$this->template_url = base_url('assets/'.$this->frontend_theme);
		}
		
	}
	public function logout(){
		$user = $this->session->userdata('user');
		if($user->role == '1')
			$redirect = site_url('admin');
		else
			$redirect = base_url();
			
		$this->session->set_userdata('user',NULL);
		delete_cookie('autologin');	
		delete_cookie('remember_me');	
		redirect($redirect);
	}
	public function _login(){
		$user = $this->session->userdata('user');
		if($user){
			if($user->role == '1'){
				redirect('admin/home');
				return;
			}else{
				redirect('home');
			}
		}
		$data['output'] = '';
		$this->_render_login($data);
		
	}
	public function _render_login($output = null)
	{		
		if($this->is_Admin){
			$template	= 'admin/'.$this->admin_theme;
			//$output['site_name']	= getConfigValue('site_name_admin');
			$output['site_name']	= 'Lunch Matcher Admin';
			$output['template_url'] = base_url('assets/'.$this->admin_theme);
		}else{
			$template	= $this->frontend_theme;
			//$output['site_name']	= getConfigValue('site_name_front');
			$output['site_name']	= 'Lunch Matcher';
			$output['template_url'] = base_url('assets/'.$this->frontend_theme);
		}		
		$this->load->view($template.'/static_header',$output);
		$this->load->view($template.'/login',$output);
		$this->load->view($template.'/footer',$output);
	}
	
	
		public function render(){
		//$this->_preRender();
		
		switch($this->operation){
			case 'list':
				$this->showList();
				break;
			case 'add':
				$this->showAddForm();
				break;
			case 'edit':
				$this->showEditForm();
				break;
			case 'insert':
				$insert_response = $this->db_insert();
				echo json_encode($insert_response);
				exit;
				break;
			case 'update':
				$update_response = $this->db_update();
				echo json_encode($update_response);
				exit;
				break;
			case 'switch_update':  
				echo $this->switch_update();
				exit;
				break;
			case 'delete':
				$this->deleteRecords();
				break;
		}
		return $this->view;
	}

	protected function showList($baseurl,$totalcount,$data){
		
			$page_limit = 2;
			$_REQUEST['limit'] = (!$_POST['limit'] ? ($_GET['limit'] ? $_GET['limit'] :$page_limit):$_POST['limit']);
			$params = '?t=1';
			if($_REQUEST['limit']) $params .= '&limit='.$_REQUEST['limit'];
			if($_REQUEST['key']) $params .= '&key='.$_REQUEST['key'];
			if($_REQUEST['status']) $params .= '&status='.$_REQUEST['status'];
			$this->load->library('pagination');
			// load pagination class
			$config['base_url'] = $baseurl."/".$params;
			$config['total_rows'] = count($data['userlist']);
			$config['per_page'] = $_REQUEST['limit'] == 'all' ? $config['total_rows']:$_REQUEST['limit'];
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
		return $this->load->view('admin/users/lists', $data, true);
	}	
}