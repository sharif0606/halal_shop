<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cornjob_run extends CI_Controller {
	public function __construct(){
		parent::__construct();
		if(MyAuth::getAccess('admin')){
			redirect('auth/auth_login');
			exit;
		}
	}
	public function index(){
		$data['page']="report/cornjob/run_r";
		$this->load->view('template',$data);
	}
}
