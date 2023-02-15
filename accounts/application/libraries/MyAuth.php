<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MyAuth {
	
	public $CI;

	// We'll use a constructor, as you can't directly call a function
	// from a property definition.
	public function __construct()
	{
		// Assign the CodeIgniter super-object
		$this->CI =& get_instance();
	}
	
	public static function getCI(){
		return get_instance();
	}
	public static function getAccess($type){
    	$ci = self::getCI();
		if($type=='admin'){
			if(!$ci->session->userdata('admin_logged_in')){
				return true;
			}
			else{
				return false;
			}
		}
    }
	
	public static function getPageAccess($authArea){
    	$ci = self::getCI();
		if ($ci->session->userdata('admin_logged_in')['super_admin']!=1 && !in_array($authArea, $ci->session->userdata('admin_logged_in')['accessArea'])){
			
			$ci->session->set_flashdata('AccessDenied', '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a>You do not have permission to visit that page.</div>');
			return true;
		}
		else{
			return false;
		}	
    }
}
