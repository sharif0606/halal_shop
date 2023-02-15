<?php
class sql
{
    private static $ci_obj;

    public static function init()
    {
        if (!isset(self::$ci_obj)) {
            self::$ci_obj =& get_instance();
        }
        return self::$ci_obj;
    }
	
	public static function row($table, $condition = '', $fields = '*')
    {
        $obj = self::init();
        if ($condition != '') {
            $condition = " where $condition";
        }
        $sql = "select $fields from $table $condition";
        $query = $obj->db->query($sql);
        return $query->row_array();
    }

}