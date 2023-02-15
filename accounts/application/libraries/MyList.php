<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MyList {
	protected $CI;

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

	public static function selectOptionList( $data,$selected_val=null){
        $selectOption="";

        if(is_array($data)){

            foreach($data as $key=>$d){
                $selected = $selected_val == $key ? "selected":"";
                $selectOption= $selectOption."<option ".$selected." value='".$key."'>".$d."</option>";
            }
        }
        return $selectOption;
    }
	
	public static function input(array $post){
    	 $ci = self::getCI();
    	if(is_array($post)){
    		$post_array = array();
    		foreach ($post as $key => $value) {
    			$post_array[$key] =  stripslashes(trim($ci->input->post($key,true)));
    		}
    		return $post_array;

    	}
    	return false;
    }
	
	public static function getArray(  $obj, $index,$val){
	$array = array();
    	if(is_array($obj)){
        //print_r($obj) ; exit();
    		
    		foreach ($obj as $key => $value) {
    			$array[$value->$index] = $value->$val;
    		}
    		return $array;
    	}
    	return $array;
    }
	
	public static function validate_data($data){
		$data = trim($data);
		$data = stripslashes($data);
		$data = strip_tags($data);
		$data = htmlspecialchars($data);
		return $data;    
	}
}
