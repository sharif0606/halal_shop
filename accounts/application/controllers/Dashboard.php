<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('common_model', 'Common_model', true);
		if(MyAuth::getAccess('admin')){
			redirect('auth/auth_login');
			exit;
		}
		/*
		if(MyAuth::getPageAccess("category")){
			redirect('dashboard');
			exit;
		}*/
	}
	
	public function index()
	{
		$data['page']="dashboard";
		$this->load->view('template',$data);
	}
}
