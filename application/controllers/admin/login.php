<?php
class Login extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_setAsAdmin();
		$this->load->model('admin/admin_model');

	}

	public function index()
	{
			$this->_login();
	}



}