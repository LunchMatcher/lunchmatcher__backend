<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Points extends MY_Controller {

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
		$this->load->model('points_model');
		$this->user 	= $this->session->userdata('user');
		if($this->user=='')
			redirect('admin');
	}	
	function index(){
		redirect('admin/points/lists');
	}
	public function lists(){	
		$data['pointslist']=$this->points_model->getAllUsersPoints();
		//$data['pointslist']=$this->points_model->getMemberList('tes');
		//echo "<pre>"; print_r($data['pointslist']); echo "</pre>"; exit;
		$output['output']=$this->load->view('admin/points/lists', $data, true);
		$this->_render_output($output);
	}
	public function details()
	{
		$member_id=$_POST['member_id'];
		$data['details']=$this->points_model->getUserPointDetails($member_id);
		echo $this->load->view('admin/points/viewDetails',$data);	
	}
	
	public function search_name(){
		unset($details);
		$name=$_POST['name'];
		$details			= 	$this->points_model->getMemberList($name); 
		//print_r($details);exit;
		foreach($details as $det){
			if($det['first_name']){
				$detail[]=$det['first_name'].' '.$det['last_name']; 
			}elseif($det['last_name']){
				$detail[]=$det['last_name']; 
			}
			
		}
		print_r(json_encode($detail));
		 exit;
	
	
	}
	
	public function search(){	
		$name=$this->input->post('search');
		$data['pointslist']=$this->points_model->getUsersPointsBySearch($name);
		//echo "<pre>"; print_r($data['pointslist']); echo "</pre>"; exit;
		$output['output']=$this->load->view('admin/points/lists', $data, true);
		$this->_render_output($output);
	}
	public function purchase(){	
	
	
			$data['page_limit']= getConfigValue('default_pagination');
			$page_limit = $data['page_limit'];
			
			$data['limit']	=	(!$this->input->post('limit') ? ($this->input->get('limit') ? $this->input->get('limit') :$page_limit):$this->input->post('limit'));
			
			$data['purchaselist']=$this->points_model->get_all_purchases();
			$config['total_rows'] = count($data['purchaselist']);
			$config['per_page'] = $data['limit'] == 'all' ? $config['total_rows'] :$data['limit'];

			
			$this->db->limit($config['per_page'],$_REQUEST['per_page']);
			
	
			//$data['userlist']=$this->points_model->get_all();

			$params = '?t=1';
			if($data['limit']!='') $params .= '&limit='.$data['limit'];
			$this->load->library('pagination');

			$config['base_url'] = site_url("admin/points/purchase")."/".$params;
			
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
		//echo $this->points_model->db->last_query();exit;
		//echo "<pre>"; print_r($data['userlist']); echo "</pre>"; exit;
		$output['output']=$this->load->view('admin/points/purchaselists', $data, true);
		$this->_render_output($output);
		
	}
	
	
	public function add($id='')
	{

			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			$id = $this->input->post('id')?$this->input->post('id'):$id;
			if($id){
				$data['purchaselist']=$this->points_model->getPurchasesByID($id);
				//$data['user'] = $this->points_model->get_by(array('member_id'=>$id));
			}
			else{
				$data['purchaselist'] = $_POST;
			}
			if($_SERVER['REQUEST_METHOD']=='POST')
			{		
			//print_r($_POST);exit;
					$this->form_validation->set_rules('title', 'First Name', 'required');
					$this->form_validation->set_rules('point', 'Point', 'required');
					$this->form_validation->set_rules('price', 'Price', 'required');

				if ($this->form_validation->run() === TRUE){
						
					if($id)
					{
						$data['detail']=array(
							'title'=>$this->input->post('title'),
							'description'=>$this->input->post('description'),
							'point'=>$this->input->post('point'),
							'price'=>$this->input->post('price'),
							'id'=>$id
						);
						$this->points_model->setTable('points_forpurchase');
						$this->points_model->update_by(array('id'=>$id),$data['detail']);

						
						$this->session->set_flashdata('message', 'Price For Purchase Details Updated Successfully','SUCCESS');
						redirect('admin/points/purchase/');
					}	
					else
					{
						$data['detail']=array(
							'title'=>$this->input->post('title'),
							'description'=>$this->input->post('description'),
							'point'=>$this->input->post('point'),
							'price'=>$this->input->post('price'),
							'created_date'=>date('Y-m-d')
						);	
								$this->points_model->setTable('points_forpurchase');

								$member_id	=	$this->points_model->insert($data['detail']);
								$this->session->set_flashdata('message', 'Price For Purchase Details Added Successfully','SUCCESS');
					    		redirect('admin/points/purchase');
					
						
					}	
			
				}
			}
			
			$output['output'] = $this->load->view('admin/points/addpurchase',$data,true);
			$this->_render_output($output);
			
	}
	public function purchasedetails($id = '')
	{
		$this->points_model->setTable('points_forpurchase');
		if($id != ''){
			$data['purchaselist']=$this->points_model->getPurchasesByID($id);
			$output['output'] = $this->load->view('admin/points/addpurchase',$data,true);
		}else{
			$output['output'] = $this->load->view('admin/points/addpurchase','',true);
		}
			$this->_render_output($output);
	}
	
	public function bulkAction($bulkaction_list='',$point_id)
	{	
		$this->points_model->setTable('points_forpurchase');
		$point_id = $this->input->post('sel');
		$bulkaction =  $this->input->post('bulkaction');
		$id=$this->uri->segment(5);
		$point_id = $this->input->post('sel')?$this->input->post('sel'):$id;
		if($bulkaction=='')
			$bulkaction	=	'delete';
		if($bulkaction){
			if($point_id){
				switch($bulkaction){
					case 'delete':
						$delete_id = $this->points_model->delete_by(array('id'=>$id));
						//$update_id = $this->points_model->update_by(array('member_id'=>$user_id), array('status'=>'T'));				
						$this->session->set_flashdata('message', 'Purchase Point Successfully Deleted ');
						break;
					case 'inactive':
						$update_id = $this->points_model->update_by(array('id'=>$point_id), array('status'=>'N'));						
						if($update_id){
							if(sizeof($point_id) == 1)
								$msg = 'Purchase Point updated successfully' ;
							else
								$msg = sizeof($point_id).' Purchase Point Successfully Updated.!' ;
							$this->session->set_flashdata('message', $msg ,'SUCCESS');	
						}
						break;
					case 'active':
						$update_id = $this->points_model->update_by(array('id'=>$point_id), array('status'=>'Y'));						
						if($update_id){
							if(sizeof($point_id) == 1)
								$msg = 'Purchase Points updated successfully' ;
							else
								$msg = sizeof($point_id).'Purchase Point Successfully Updated.!' ;
							$this->session->set_flashdata('message', $msg ,'SUCCESS');	
						}
						break;
				}
			}
			else{
				$this->session->set_flashdata('message', 'Please select at least one member.! ','ERROR');	
			}
		}
		redirect('admin/points/purchase');
	}
	
	
}