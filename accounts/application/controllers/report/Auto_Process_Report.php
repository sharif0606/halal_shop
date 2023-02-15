<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auto_Process_Report extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('common_model', 'Common_model', true);
		if(MyAuth::getAccess('admin')){
			redirect('auth/auth_login');
			exit;
		}
		
		if(MyAuth::getPageAccess("Auto_Process")){
			redirect('dashboard');
			exit;
		}
	}
	public function index()
	{
	  
	$data['report'] = $this->db->query("SELECT tbl_deposit_paid.`samity_Code`, tbl_deposit_paid.`payment_Date`, tbl_deposit_paid.`updated_on`, tbl_deposit_paid.`updated_by`, tbl_field_officers.officer_Name, tbl_samity.samity_Day, tbl_deposit_paid.proType FROM `tbl_deposit_paid`
left join tbl_samity on tbl_deposit_paid.samity_Code= tbl_samity.samity_Code
left JOIN tbl_field_officers on tbl_samity.field_Officer = tbl_field_officers.id
group by tbl_deposit_paid.`payment_Date`,tbl_deposit_paid.`samity_Code` order by tbl_deposit_paid.`samity_Code`")->result_array();
	
	   $data['samity_Lists'] = $this->db->query("SELECT tbl_samity.samity_Name, tbl_field_officers.officer_Name, tbl_samity.samity_Code,tbl_samity.samity_Day,tbl_samity.opening_Date  FROM tbl_samity INNER JOIN tbl_field_officers on tbl_samity.field_Officer = tbl_field_officers.id order by tbl_samity.id")->result_array();
    $data['page']="report/auto_process_report_Lists";
	
	$this->load->view('template',$data);
	}
	public function view_loan_Lists(){

		$data['company_Profile'] = $this->Common_model->common_result_array('tbl_company_profile');
		$date = date('Y-m-d',strtotime($this->input->get('date')));
		$data['samity_Code'] = $this->input->get('samity_Name');
		$data1['samity_Code'] = $this->input->get('samity_Name');
		$data['samity_list_By_id'] =  $this->Common_model->common_select_by_condition_row('tbl_samity','*',$data1);
		$data['page']="report/auto_Process";
		$this->load->view('template',$data);

	}
	public function view_Other_Details(){
		$postData['loan_Scheme'] = $this->input->get('loan_Scheme');
		$data=  $this->Common_model->common_select_by_condition_row('tbl_loan_scheme','product_Code,payment_Frequency,rate,loan_Amount,installment,interest_Mode,calculation_Method',$postData);
		echo json_encode($data);
	}
	/*public function search(){
		$postData= date('Y-m-d');
		//print_r($postData);
		$data=  $this->Common_model->search_Ajax('tbl_loan_assign_details','*',$postData,'first_repay_Date');
		//print_r($data);
		echo json_encode($data);
	}*/
	public function search(){
		$samity_Name = $this->input->get('samity_Name');
		$date = date('Y-m-d',strtotime($this->input->get('date')));
		$proType = $this->input->get('proType');


		$data= $this->db->query("SELECT `tbl_members`.`member_Id`,`tbl_members`.`member_Name`,`tbl_members`.`father_Name`,`tbl_members`.`v_Amt`,`tbl_members`.`amount`,tbl_members.saving_Code,tbl_members.v_product_Code,
(select sum(`tbl_deposit_paid`.`p_Amt`) from tbl_deposit_paid where tbl_deposit_paid.member_Id=tbl_members.member_Id   GROUP BY `tbl_deposit_paid`.`member_Id`) as rSav,

(select GROUP_CONCAT(`tbl_deposit_paid`.`p_Amt`) from tbl_deposit_paid where tbl_deposit_paid.member_Id=tbl_members.member_Id and  tbl_deposit_paid.payment_Date= '$date'  GROUP BY `tbl_deposit_paid`.`member_Id`) as rp_Amt,

(select GROUP_CONCAT(`tbl_deposit_paid`.`p_status`) from tbl_deposit_paid where tbl_deposit_paid.member_Id=tbl_members.member_Id and  tbl_deposit_paid.payment_Date= '$date'  GROUP BY `tbl_deposit_paid`.`member_Id`) as rp_status,

(select sum(`tbl_deposit_assign_details`.`paid_Amount`) from tbl_deposit_assign_details where tbl_deposit_assign_details.member_Id=tbl_members.member_Id  and `tbl_deposit_assign_details`.`status`='2' GROUP BY `tbl_deposit_assign_details`.`member_Id`) as dtAmt,

(select GROUP_CONCAT(`tbl_deposit_assign_details`.`number_of_Payment`) from tbl_deposit_assign_details where tbl_deposit_assign_details.member_Id=tbl_members.member_Id and  tbl_deposit_assign_details.paid_Date= '$date' GROUP BY `tbl_deposit_assign_details`.`member_Id`) as dNo,

(select GROUP_CONCAT(`tbl_deposit_assign_details`.`savings_Amount`) from `tbl_deposit_assign_details` where `tbl_deposit_assign_details`.`member_Id`=`tbl_members`.`member_Id` and  tbl_deposit_assign_details.paid_Date= '$date' GROUP BY `tbl_deposit_assign_details`.`member_Id`) as `savings_Amount`,

(select GROUP_CONCAT(`tbl_deposit_assign_details`.`paid_Amount`) from `tbl_deposit_assign_details` where `tbl_deposit_assign_details`.`member_Id`=`tbl_members`.`member_Id` and  tbl_deposit_assign_details.paid_Date= '$date' GROUP BY `tbl_deposit_assign_details`.`member_Id`) as `sP_Amt`,

(select GROUP_CONCAT(`tbl_deposit_assign_details`.`p_status`) from `tbl_deposit_assign_details` where `tbl_deposit_assign_details`.`member_Id`=`tbl_members`.`member_Id` and  tbl_deposit_assign_details.paid_Date= '$date' GROUP BY `tbl_deposit_assign_details`.`member_Id`) as `sp_status`,

(select GROUP_CONCAT(`tbl_deposit_assign_details`.`id`) from `tbl_deposit_assign_details` where `tbl_deposit_assign_details`.`member_Id`=`tbl_members`.`member_Id` and  tbl_deposit_assign_details.paid_Date= '$date' GROUP BY `tbl_deposit_assign_details`.`member_Id`) as `dps_id`,

(select GROUP_CONCAT(`tbl_deposit_assign_details`.`dps_No`) from `tbl_deposit_assign_details` where `tbl_deposit_assign_details`.`member_Id`=`tbl_members`.`member_Id` and  tbl_deposit_assign_details.paid_Date= '$date' GROUP BY `tbl_deposit_assign_details`.`member_Id`) as `dps_No`,

(select GROUP_CONCAT(`tbl_loan_assign_details`.`installment_Amount`) from tbl_loan_assign_details where tbl_loan_assign_details.member_Id=tbl_members.member_Id and  tbl_loan_assign_details.paid_Date= '$date' GROUP BY `tbl_loan_assign_details`.`member_Id`) as installment_Amount,

(select GROUP_CONCAT(`tbl_loan_assign_details`.`paid_Amount`) from tbl_loan_assign_details where tbl_loan_assign_details.member_Id=tbl_members.member_Id and  tbl_loan_assign_details.paid_Date= '$date' GROUP BY `tbl_loan_assign_details`.`member_Id`) as lp_Amt,

(select GROUP_CONCAT(`tbl_loan_assign_details`.`p_Status`) from tbl_loan_assign_details where tbl_loan_assign_details.member_Id=tbl_members.member_Id and  tbl_loan_assign_details.paid_Date= '$date' GROUP BY `tbl_loan_assign_details`.`member_Id`) as lp_Status,

(select sum(`tbl_loan_assign_details`.`installment_Amount`) from tbl_loan_assign_details where tbl_loan_assign_details.member_Id=tbl_members.member_Id   and  tbl_loan_assign_details.paid_Date= tbl_loan_assign_details.paid_Date GROUP BY `tbl_loan_assign_details`.`member_Id`) as instAmt,

(select sum(`tbl_loan_assign_details`.`paid_Amount`) from tbl_loan_assign_details where tbl_loan_assign_details.member_Id=tbl_members.member_Id  and `tbl_loan_assign_details`.`status`='3' GROUP BY `tbl_loan_assign_details`.`member_Id`) as tpAmt,

(select `tbl_loan_assign`.`total_repay_Amount` from tbl_loan_assign where tbl_loan_assign.member_Id=tbl_members.member_Id  GROUP BY `tbl_loan_assign`.`member_Id`) as tl_Repay,

(select GROUP_CONCAT(`tbl_loan_assign_details`.`id`) from tbl_loan_assign_details where tbl_loan_assign_details.member_Id=tbl_members.member_Id and  tbl_loan_assign_details.paid_Date= '$date' GROUP BY `tbl_loan_assign_details`.`member_Id`) as loan_ID,

(select GROUP_CONCAT(`tbl_loan_assign_details`.`number_of_Payment`) from tbl_loan_assign_details where tbl_loan_assign_details.member_Id=tbl_members.member_Id and tbl_loan_assign_details.paid_Date= '$date'  GROUP BY `tbl_loan_assign_details`.`member_Id`) as instNo,

(select GROUP_CONCAT(`tbl_loan_assign_details`.`loan_No`) from tbl_loan_assign_details where tbl_loan_assign_details.member_Id=tbl_members.member_Id and  tbl_loan_assign_details.paid_Date= '$date' GROUP BY `tbl_loan_assign_details`.`member_Id`) as loan_No

FROM `tbl_members` WHERE tbl_members.status=1 && tbl_members.samity_Name = '$samity_Name'")->result_array();
//echo $this->db->last_query();
		echo json_encode($data);
	
		
		
		
	}
	public function save_auto_Process(){
	    $samity_Code = $this->input->post('samity_Name');
		$date = $this->input->post('payment_Date');
		/* for loan payment table update */
				$data = $this->db->query("select payment_Date,samity_Code from tbl_deposit_paid where paid_Date='$date' and samity_Code='$samity_Code'")->result_array();
		if($data){
		$this->session->set_flashdata('message', '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a>Loan #'.$samity_Code.' Report Saved for today.</div>');
		redirect("auto_Process/Auto_Process");
		}else{
		$loan_ID=$this->input->post('loan_ID');
		$p_Amt=$this->input->post('p_Amt');
		$full=$this->input->post('full');
		$part=$this->input->post('part');
		$zero=$this->input->post('zero');
		$lone_up=array();
		foreach($loan_ID as $lk=>$lv){
		    if(isset($full[$lk]))
			    $p_Status=1;
			else if(isset($part[$lk]))
			    $p_Status=2;
		    else
			    $p_Status=3;
			$lone_up[]=array(
						'id'=>$lv,
						'paid_Amount'=>$p_Amt[$lk],
						//'status'=>1,
						'updated_By'=>$this->session->userdata('admin_logged_in')['id'],
						'updated_on' => date('Y-m-d H:i:s',time()),
						//'paid_Date'=>date('Y-m-d')
						'p_Status' => $p_Status,
					);
		}
		
		if(count($lone_up)>0)
			$this->Common_model->common_batch_update('tbl_loan_assign_details',$lone_up,'id');
		
		/* for DPS payment table update */
		$dps_id=$this->input->post('dps_id');
		$savings_Amount=$this->input->post('savings_Amount');
		/*This is for dps show in auto process all time if value is zero number of payment and status will not change*/

		$dps_up=array();
		foreach($dps_id as $dk=>$dv){
    		if($savings_Amount[$dk] == 0){
		        $status = 0;
		    }else{
		        $status = 1;
		    }
			$dps_up[]=array(
						'id'=>$dv,
						'paid_Amount'=>$savings_Amount[$dk],
						'status'=>$status,
						'updated_By'=>$this->session->userdata('admin_logged_in')['id'],
						'updated_on' => date('Y-m-d H:i:s',time()),
						//'paid_Date'=>date('Y-m-d')
					);
		}
		
		if(count($dps_up)>0)
			$this->Common_model->common_batch_update('tbl_deposit_assign_details',$dps_up,'id');
		
		
		$member_Id=$this->input->post('member_Id_v');
		$is_Present=$this->input->post('is_Present');
		$saving_Code=$this->input->post('saving_Code');
		$mandotory_Amount=$this->input->post('mandotory_Amount');/* should be paid */
		$mandotory_Amount_P=$this->input->post('mandotory_Amount_P');/* paid amount */
		
		$v_product_Code=$this->input->post('v_product_Code');
		$v_Amt=$this->input->post('v_Amt');/* should be paid */
		$v_Amt_P=$this->input->post('v_Amt_P');/* paid amount */
		$samity_Code=$this->input->post('samity_Name');/* paid amount */
		
		$payment_Date = date('Y-m-d',strtotime($this->input->post('payment_Date')));
		
		$rs=array();
		$vs=array();
		foreach($member_Id as $mID){
			
			$present=0;
			if(isset($is_Present[$mID]))
				$present=1;
			
			$rs[]=array(
						'member_Id'=>$mID,
						'samity_Code'=>$this->input->post('samity_Code'),
						'saving_Code'=>$saving_Code[$mID],
						'amt'=>$mandotory_Amount[$mID],
						'p_Amt'=>$mandotory_Amount_P[$mID],
						'is_Present'=>$present,
						'updated_By'=>$this->session->userdata('admin_logged_in')['id'],
						'paid_Date'=>date('Y-m-d'),
						'payment_Date'=>$payment_Date,
						'samity_Code'=>$samity_Code
					);
					
			if(isset($v_product_Code[$mID])){
				$vs[]=array(
						'member_Id'=>$mID,
						'v_product_Code'=>$v_product_Code[$mID],
						'v_Amt'=>$v_Amt[$mID],
						'p_Amt'=>$v_Amt_P[$mID],
						'is_Present'=>$present,
						'updated_By'=>$this->session->userdata('admin_logged_in')['id'],
						'paid_Date'=>date('Y-m-d'),
						'payment_Date'=>$payment_Date,
						'paid_Date'=>date('Y-m-d'),
					);
			}
		}
		
		if(count($rs)>0)
			$this->Common_model->common_insert_batch($rs,'tbl_deposit_paid');
		if(count($vs)>0)
			$this->Common_model->common_insert_batch($vs,'tbl_deposit_paid');
		$this->session->set_flashdata('message', '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a>Saved Successfully</div>');
		redirect("auto_Process/auto_Process");
		}
	}
}
