<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Savings_report extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
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
	$first_date    = date('Y-m-01');
    $last_date     = date('Y-m-t');
		
	$date = date('y-m-d',strtotime('-1 month'));

		$data['lm_rs_savings'] = $this->db->query("select (SELECT sum(tbl_deposit_paid.amt) from tbl_deposit_paid join tbl_members on tbl_members.member_Id = tbl_deposit_paid.member_Id and tbl_members.gender=2 WHERE payment_Date = '$date') as lm_female_sav_res, (SELECT sum(tbl_deposit_paid.amt) from tbl_deposit_paid join tbl_members on tbl_members.member_Id = tbl_deposit_paid.member_Id and tbl_members.gender=1 WHERE payment_Date = $date) as lm_male_sav_res")->row_array();
		$data['cur_rs_savings'] = $this->db->query("SELECT (select sum(tbl_deposit_paid.p_Amt) from tbl_deposit_paid join tbl_members on tbl_members.member_Id = tbl_deposit_paid.member_Id and tbl_members.gender=2 WHERE payment_Date >= '$first_date' and payment_Date <='$last_date') as female_sav_cur,(select sum(tbl_deposit_paid.p_Amt) from tbl_deposit_paid join tbl_members on tbl_members.member_Id = tbl_deposit_paid.member_Id and tbl_members.gender=1 WHERE payment_Date >= '$first_date' and payment_Date <='$last_date') as male_sav_cur")->row_array();
		
		$data['lm_share_capital'] = $this->db->query("select (SELECT sum(tbl_members.admission_Fee) from tbl_members WHERE gender=2 and application_Date <= '$date') as lm_female_share_capital, (SELECT sum(tbl_members.admission_Fee) from tbl_members WHERE gender=1 and application_Date <= '$date') as lm_male_share_capital")->row_array();
		$data['cur_share_capital'] = $this->db->query("select (SELECT sum(tbl_members.admission_Fee) from tbl_members WHERE gender=2 and application_Date >= '$first_date' and application_Date <= '$last_date') as cur_female_share_capital, (SELECT sum(tbl_members.admission_Fee) from tbl_members WHERE gender=1 and application_Date >= '$first_date' and application_Date <= '$last_date') as cur_male_share_capital")->row_array();
		$data['page']="report/savings_report";
		$this->load->view('template',$data);
	}
	public function view_member_info_by_samity(){
		$samity_Name = $this->input->get('samity_Name');
		//print_r($postData);
		$data=  $this->db->query("SELECT tbl_members.member_Name, tbl_members.member_Id, tbl_members.present_Address, tbl_deposit_assign.status from tbl_members left join tbl_deposit_assign on tbl_deposit_assign.member_Id = tbl_members.member_Id WHERE tbl_members.samity_Name ='$samity_Name' && tbl_members.status=1 ORDER by tbl_members.id")->result_array();
		echo json_encode($data);
	}
	public function search(){
		$member_Id= $this->input->get('member_Id');
		//print_r($postData);
		$data= $this->db->query("SELECT tbl_members.member_Id, tbl_members.member_Name from tbl_members where tbl_members.member_Id='$member_Id'")->row();
		echo json_encode($data);
	}
	public function dpsInfo(){
	    $postData['member_Id']= $this->input->get('member_Id');
    	$postData['status'] = 0;
		$data=  $this->Common_model->common_select_by_condition('tbl_deposit_assign','dps_No',$postData);
		echo json_encode($data);
	}
	public function paidDpsBalance(){
		$dps_No = $this->input->get('dps_No');
		/*$data=  $this->db->query("select (select 
					sum(paid_Amount) from tbl_deposit_assign_details where dps_No = '$dps_No') -
					(select COALESCE(sum(w_Amt),0) from tbl_dps_withdraw_details where dps_No = '$dps_No') as total")->row();*/
		$data=  $this->db->query("select (select 
					sum(paid_Amount) from tbl_deposit_assign_details where dps_No = '$dps_No') as total")->row();
		//echo $this->db->last_query();
    	echo json_encode($data);
	}
	public function dpsAllInfo(){
		$postData['dps_No'] = $this->input->get('dps_No');
		$data=  $this->Common_model->common_select_by_condition_row('tbl_deposit_assign','*',$postData);
		//echo $this->db->last_query();
    	echo json_encode($data);
	}
	/*public function dpsInfoByDpsNo(){
    	$postData['dps_No']= $this->input->get('dps_No');
    	$postData['status'] = 0;
		$data=  $this->Common_model->common_select_by_condition('tbl_deposit_assign_details','id,dps_No,number_of_Payment',$postData);
		//echo $this->db->last_query();
		echo json_encode($data);
	}
	public function dpsInfoByByPaymentNo(){
    	$postData['dps_No']= $this->input->get('dps_No');
    	$postData['id'] = $this->input->get('id');
    	$data=  $this->Common_model->common_select_by_condition_row('tbl_deposit_assign_details','*',$postData);
    	//echo json_encode($this->db->last_query());
		echo json_encode($data);
	}*/
	public function dpsClosing(){
		$dps_No = $this->input->post('dps_No');
		$dep_bal_Avil=  $this->db->query("select (select 
					sum(paid_Amount) from tbl_deposit_assign_details where dps_No = '$dps_No') -
					(select COALESCE(sum(w_Amt),0) from tbl_dps_withdraw_details where dps_No = '$dps_No') as total")->row();
		$w_Amt = $this->input->post('w_Amt');
		if($dep_bal_Avil->total==0){
			$this->session->set_flashdata('message', '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a>No Balance Available & DPS is Inactive.</div>');
			redirect('closing/Closing_Dps');
		}else if($w_Amt>$dep_bal_Avil->total){
			$this->session->set_flashdata('message', '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a>Withdrawn Amount exceeds Available Balance.</div>');
			redirect('closing/Closing_Dps');
		}else{
	    $data = $this->input->post(NULL, TRUE);
		$data['created_by']=$this->session->userdata('admin_logged_in')['id'];
		$data['created_on']=date('Y-m-d H:i:s',time());
		$result = $this->Common_model->common_insert($data,'tbl_dps_withdraw_details');
		/*To Check Baklance Available*/
		$dep_last_bal=  $this->db->query("select (select 
					sum(paid_Amount) from tbl_deposit_assign_details where dps_No = '$dps_No') -
					(select COALESCE(sum(w_Amt),0) from tbl_dps_withdraw_details where dps_No = '$dps_No') as total")->row();
		$this->session->set_flashdata('message', '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a>Last Balance '.$dep_last_bal->total.'.</div>');
			redirect('closing/Closing_Dps');
		}
	}
}
