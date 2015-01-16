<?php
/** 2011-09-17
 * jm edit 文章栏目管理
 **/
define('ADARCACTION', str_replace("[/\\]{1,}", '/', dirname(__FILE__) ) ); 
require_once(ADARCACTION.'/config.php');

class adDeptAction extends Action
{
    private $db;
    private $str;
    private $class_arr;
    private $table;

    function __construct()
    {
		$this->db = new mysqlDb();
		$this->str = '';
		$this->class_arr = '';
		$this->table = 'config_dept';     
    }

    function adDeptSelect()
    {
        $query = $this->db->select("`{$this->table}`",'*');

        while($row = $this->db->fetch_array($query))
        {
            $this->class_arr[$row['name']] = $row['values'];          
        }
        return $this->class_arr;
        $this->db->free();
    }

    /*修改*/
    function adDeptUpdate($post)
    {
            foreach($post as $p_name=>$p_values)
            {
           
            $mod_content = "`values`='$p_values'";
            $condition = "`name`='$p_name'";

            $this->db->update($this->table, $mod_content, $condition );

            }

        $this->db->free();
        return 0;
    }

    function __isset($var){ }
    function __unset($c){
        unset ($this->$c); 
    }
    function __destruct() {}



}


?>

