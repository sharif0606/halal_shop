<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Receipt_payment_report extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('common_model', 'Common_model', true);
		$this->load->model('accounts_model', 'Accounts_model', true);
		$this->load->model('acc_rep_model', 'Acc_rep_model', true);
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
	public function index(){
		$data['page']="report/receipt_payment_report/rpr";
		$this->load->view('template',$data);
	}
	
	public function grpr(){
		$data['month']=$this->input->get('rMonth');
		$data['year']=$this->input->get('rYear');
		
		
		$mainContent=$this->load->view('report/receipt_payment_report/grpr', $data, true);
			
        $result = 'success';
        $return = array('result' => $result, 'mainContent'=> $mainContent);
        print json_encode($return);
        exit;
    }
}
