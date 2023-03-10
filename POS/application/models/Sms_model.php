<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sms_model extends CI_Model {
	public function xss_html_filter($input){
		return $this->security->xss_clean(html_escape($input));
	}
	//UPDATE SMS API
	public function api_update(){
		extract($this->xss_html_filter(array_merge($this->data,$_POST,$_GET)));
		//print_r($this->xss_html_filter(array_merge($this->data,$_POST,$_GET)));exit();
		
		$this->db->trans_begin();
		if($hidden_rowcount>0){
		$this->db->query("delete from db_smsapi");
			for($i=1; $i<=$hidden_rowcount; $i++){
				if(isset($_POST['info_'.$i])){
					$info 	 	= $_POST['info_'.$i];
					$key 	 	= $_POST['key_'.$i];
					$key_value 	= $_POST['key_val_'.$i];
					
					$q1=$this->db->query("insert into db_smsapi(
								info,`key`,key_value)
								values(
								'$info',
								'$key',
								'$key_value')");
					if(!$q1){
						return "failed";
					}

				}//if end()
			}//for end()	
		}

		$q2=$this->db->query("update db_company set sms_status=$sms_status where id=1");
		if(!$q2){
			return "failed";
		}

			$this->session->set_flashdata('success', 'Record Successfully Saved!!');
			$this->db->trans_commit();
		    return "success";
	}
	//Send Messagr
	public function send_sms($mobile,$message){
		$sms_status=$this->db->query("select sms_status from db_company where id=1")->row()->sms_status;
		if($sms_status==1){
			$q1=$this->db->query("select * from db_smsapi");
			if($q1->num_rows()>0){
				$api=array();
				foreach($q1->result() as $res1){
					if($res1->info =='message'){
						$api = array_merge($api, [$res1->key => $message]);
					}
					else if($res1->info =='mobile'){
						$api = array_merge($api, [$res1->key => $mobile]);
					}
					else{
						$api = array_merge($api, [$res1->key => $res1->key_value]);
					}
				}
				/*For Special characters need to set unicode Ex: Currency Symbols*/
				$api = array_merge($api, ['unicode' => '1']);
				
				//print_r($api);exit();


				// init the resource
				$ch = curl_init();
				curl_setopt_array($ch, array(
				    CURLOPT_URL => $api['weblink'],
				    CURLOPT_RETURNTRANSFER => true,
				    CURLOPT_POST => true,
				    CURLOPT_POSTFIELDS => $api
				    //,CURLOPT_FOLLOWLOCATION => true
				));


				//Ignore SSL certificate verification
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


				//get response
				$output = curl_exec($ch);

				//Print error if any
				if(curl_errno($ch))
				{
				    //return 'error:' . curl_error($ch);
				    return 'failed';
				}

				curl_close($ch);
				return 'success';
				//return $output;
			}
			else{
				return "API Not Available";
			}
		}
		else{
			return "Sorry! Can't Send.Please Enable SMS";
		}

	}
	
//Send Messagr
	public function send_sms_bulk($msisdns, $messageBody){
		$sms_status=$this->db->query("select sms_status from db_company where id=1")->row()->sms_status;
		if($sms_status==1){
			$q1=$this->db->query("select * from db_smsapi");
			if($q1->num_rows()>0){
				$api_token=$domain="";
				foreach($q1->result() as $res1){
					if($res1->key =='authkey'){
						$api_token = $res1->key_value;
					}
					else if($res1->key =='weblink'){
						$domain = $res1->key_value;
					}
				}
				
				
				$params = [
                    "api_token" => $api_token,
                    "msisdn" => $msisdns,
                    "sms" => $messageBody
                ];
                $url = trim($domain, '/')."/sendsms";
                //$params = json_encode($params);
                //print_r($this->callApi($url, $params)['response']);
                //die();
                if ($this->callApi($url, $params)['status']=="200") {
        			return 'SMS Sent Successfully.';
        		} else {
            		return 'SMS Sent Fail.';
        		}
			}
			else{
				return "API Not Available";
			}
		}
		else{
			return "Sorry! Can't Send.Please Enable SMS";
		}

	}
	
    public function sms_balance(){
		$sms_status=$this->db->query("select sms_status from db_company where id=1")->row()->sms_status;
		if($sms_status==1){
			$q1=$this->db->query("select * from db_smsapi");
			if($q1->num_rows()>0){
				$api_token=$domain="";
				foreach($q1->result() as $res1){
					if($res1->key =='authkey'){
						$api_token = $res1->key_value;
					}
					else if($res1->key =='weblink'){
						$domain = $res1->key_value;
					}
				}
				
				
				$params = [
                    "api_token" => $api_token,
                ];
                $url = trim($domain, '/')."/smsbalance";
                
                if ($this->callApi($url, $params)['status']=="200") {
        			return $this->callApi($url, $params)['response']->data;
        		} else {
            		return $this->callApi($url, $params)['response']->errors[0];
        		}
			}
			else{
				return "API Not Available";
			}
		}
		else{
			return "Sorry! Can't Send.Please Enable SMS";
		}

	}
    
	public function callApi($url, $params){
        
        // use key 'http' even if you send the request to https://...
        $options = array(
                        'http' => array(
                                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                                    'method'  => 'POST',
                                    'content' => http_build_query($params),
                                    'ignore_errors' => true
                                )
                    );
        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        
        $status_line = $http_response_header[0];
        
        preg_match('{HTTP\/\S*\s(\d{3})}', $status_line, $match);
        
        $status = $match[1];
        
        $response=array('status'=>$status,'response'=>json_decode($response));
        return $response;

    }

}

/* End of file Sms_model.php */
/* Location: ./application/models/Sms_model.php */