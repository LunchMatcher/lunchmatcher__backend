<?php
class cms extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('cms_model');

	}

	public function index()
	{
		//$page=$_REQUEST['page'];

		/*$data['cms'] = $this->cms_model->get_cmspage($page);
		$this->load->view('templates/header', $data);
		$this->load->view('frontend/cms_page', $data);
		$this->load->view('templates/footer');*/
		redirect('cms/view');
	}

	public function view($page)
	{
		//$page=$_REQUEST['page'];
		$data['cms'] = $this->cms_model->get_cmspage($page);
		//$this->load->view('templates/header', $data);
		$url=base_url();
		$data['cms']  = str_replace('#BASE#',$url,$data['cms']);
		$this->load->view('tutorials',$data);
		//$this->load->view('templates/footer');
	}

	
}


?>