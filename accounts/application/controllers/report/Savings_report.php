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
		$data['page']="report/savings_report/sr";
		$this->load->view('template',$data);
	}
	public function gsrm()
	{
		
	$data['month']=$this->input->get('rMonth');
	$data['year']=$this->input->get('rYear');
		
		/*$date = new DateTime( $year.'-'.$month.'-01');
		$cml= $date->format("Y-m-t");
		$cmf= $date->format("Y-m-d");*/
		
	/*$first_date    = date('Y-m-01');
    $last_date     = date('Y-m-t');*/
		
	//$date = date('y-m-d',strtotime('-1 month'));
	
/*	$lmf = $date->sub(new DateInterval('P1M'))->modify('first day of this month')->format('yy-m-d');
	$lml = $date->sub(new DateInterval('P1M'))->modify('last day of this month')->format('yy-m-d');*/


	//	$data['lm_rs_savings'] = $this->db->query("select (SELECT sum(tbl_deposit_paid.amt) from tbl_deposit_paid join tbl_members on tbl_members.member_Id = tbl_deposit_paid.member_Id and tbl_members.gender=2 WHERE payment_Date between '$lmf' and '$lml') as lm_female_sav_res, (SELECT sum(tbl_deposit_paid.amt) from tbl_deposit_paid join tbl_members on tbl_members.member_Id = tbl_deposit_paid.member_Id and tbl_members.gender=1 WHERE payment_Date between '$lmf' and '$lml') as lm_male_sav_res")->row_array();
		//echo $this->db->last_query();die();

		//$data['cur_rs_savings'] = $this->db->query("SELECT (select sum(tbl_deposit_paid.p_Amt) from tbl_deposit_paid join tbl_members on tbl_members.member_Id = tbl_deposit_paid.member_Id and tbl_members.gender=2 WHERE payment_Date >= '$cmf' and payment_Date <='$cml') as female_sav_cur,(select sum(tbl_deposit_paid.p_Amt) from tbl_deposit_paid join tbl_members on tbl_members.member_Id = tbl_deposit_paid.member_Id and tbl_members.gender=1 WHERE payment_Date >= '$cmf' and payment_Date <='$cml') as male_sav_cur")->row_array();
		
		//$data['lm_share_capital'] = $this->db->query("select (SELECT sum(tbl_members.admission_Fee) from tbl_members WHERE gender=2 and application_Date <= '$lmf') as lm_female_share_capital, (SELECT sum(tbl_members.admission_Fee) from tbl_members WHERE gender=1 and application_Date <= '$lml') as lm_male_share_capital")->row_array();
	//	$data['cur_share_capital'] = $this->db->query("select (SELECT sum(tbl_members.admission_Fee) from tbl_members WHERE gender=2 and application_Date >= '$cmf' and application_Date <= '$cml') as cur_female_share_capital, (SELECT sum(tbl_members.admission_Fee) from tbl_members WHERE gender=1 and application_Date >= '$cmf' and application_Date <= '$cml') as cur_male_share_capital")->row_array();
		
		
		$mainContent=$this->load->view('report/savings_report/gsrm', $data, true);
			
        $result = 'success';
        $return = array('result' => $result, 'mainContent'=> $mainContent);
        print json_encode($return);
        exit;
		
		//$data['page']="report/savings_report/savings_report";
		//$this->load->view('template',$data);
	}
	public function glsrd(){
		$month=$this->input->get('rMonth');
		$year=$this->input->get('rYear');
		
		$date = new DateTime( $year.'-'.$month.'-01');
		$cml= $date->format("Y-m-t");
		$cmf= $date->format("Y-m-d");

		$data['data']=$this->db->query("SELECT `paid_Date`,
										round(( SELECT sum(`paid_Amount`) from tbl_deposit_assign_details tlad WHERE tlad.`dps_No` in (SELECT `tbl_deposit_assign`.`dps_No` FROM `tbl_deposit_assign` where member_Id in (select member_Id from tbl_members WHERE tbl_members.gender=2)) and tlad.`paid_Date`=tbl_deposit_assign_details.`paid_Date`)) as pafm,
										round(( SELECT sum(`paid_Amount`) from tbl_deposit_assign_details tlad WHERE tlad.`dps_No` in (SELECT `tbl_deposit_assign`.`dps_No` FROM `tbl_deposit_assign` where member_Id in (select member_Id from tbl_members WHERE tbl_members.gender=1)) and tlad.`paid_Date`=tbl_deposit_assign_details.`paid_Date`)) as pam
										FROM `tbl_deposit_assign_details` WHERE `paid_Date` >= '$cmf' and `paid_Date` <= '$cml' group by `paid_Date`")->result_array();

		//echo $this->db->last_query();
		$mainContent=$this->load->view('report/savings_report/glsrd', $data, true);
			
        $result = 'success';
        $return = array('result' => $result, 'mainContent'=> $mainContent);
        print json_encode($return);
        exit;
    }
	public function gsrbd(){
		$month=$this->input->get('rMonth');
		$year=$this->input->get('rYear');
		
		$date = new DateTime( $year.'-'.$month.'-01');
		$cml= $date->format("Y-m-t");
		$cmf= $date->format("Y-m-d");

		$data['user_info'] = $this->Common_model->common_select_by_condition('tbl_auth_supper','id,name');
		$data['data']=$this->db->query("SELECT *
										FROM `tbl_deposit_assign_details` WHERE `paid_Date` >= '$cmf' and `paid_Date` <= '$cml' and paid_Amount<>0")->result_array();
										//echo $this->db->last_query();die();
										
		$mainContent=$this->load->view('report/savings_report/gsrbd', $data, true);
			
        $result = 'success';
        $return = array('result' => $result, 'mainContent'=> $mainContent);
        print json_encode($return);
        exit;
	}
	public function grsrd(){
		$month=$this->input->get('rMonth');
		$year=$this->input->get('rYear');
		
		$date = new DateTime( $year.'-'.$month.'-01');
		$cml= $date->format("Y-m-t");
		$cmf= $date->format("Y-m-d");

		$data['data']=$this->db->query("SELECT `payment_Date`,
										round(( SELECT sum(`p_Amt`) from tbl_deposit_paid tlad WHERE tlad.`saving_Code` in (SELECT `tbl_deposit_paid`.`saving_Code` FROM `tbl_deposit_paid` where member_Id in (select member_Id from tbl_members WHERE tbl_members.gender=2)) and  tlad.`payment_Date`=tbl_deposit_paid.`payment_Date`)) as pafm,
										round(( SELECT sum(`p_Amt`) from tbl_deposit_paid tlad WHERE tlad.`saving_Code` in (SELECT `tbl_deposit_paid`.`saving_Code` FROM `tbl_deposit_paid` where member_Id in (select member_Id from tbl_members WHERE tbl_members.gender=1)) and tlad.`payment_Date`=tbl_deposit_paid.`payment_Date`)) as pam
										FROM `tbl_deposit_paid` WHERE `payment_Date` >= '$cmf' and `payment_Date` <= '$cml' and payment_Date <> 0000-00-00 group by `payment_Date`")->result_array();


		$mainContent=$this->load->view('report/rs_report/grsrd', $data, true);
			
        $result = 'success';
        $return = array('result' => $result, 'mainContent'=> $mainContent);
        print json_encode($return);
        exit;
    }
	public function grsbdw(){
		$month=$this->input->get('rMonth');
		$year=$this->input->get('rYear');
		
		$date = new DateTime( $year.'-'.$month.'-01');
		$cml= $date->format("Y-m-t");
		$cmf= $date->format("Y-m-d");

		$data['user_info'] = $this->Common_model->common_select_by_condition('tbl_auth_supper','id,name');
		$data['data']=$this->db->query("SELECT *
										FROM `tbl_deposit_paid` WHERE `payment_Date` >= '$cmf' and `payment_Date` <= '$cml' and p_Amt<>0")->result_array();
										//echo $this->db->last_query();die();
										
		$mainContent=$this->load->view('report/rs_report/grsbdw', $data, true);
			
        $result = 'success';
        $return = array('result' => $result, 'mainContent'=> $mainContent);
        print json_encode($return);
        exit;
	}
	public function grstotal(){

		$data['user_info'] = $this->Common_model->common_select_by_condition('tbl_auth_supper','id,name');	
	
		$data['data']=$this->db->query("SELECT * FROM `tbl_deposit_paid`")->result_array();
										//echo $this->db->last_query();die();
										
		$mainContent=$this->load->view('report/rs_report/grstotal', $data, true);
			
        $result = 'success';
        $return = array('result' => $result, 'mainContent'=> $mainContent);
        print json_encode($return);
        exit;
	}
}
