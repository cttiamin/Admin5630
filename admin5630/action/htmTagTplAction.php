<?php
/** 2011-09-19
 * jm edit 产品栏目管理
 **/
define('ADARCACTION', str_replace("[/\\]{1,}", '/', dirname(__FILE__) ) ); 
require_once(ADARCACTION.'/config.php');

class htmTagTplAction extends Action
{
    private $db;
    private $sql;
    private $table;
    private $class_arr;
    private $str;
    
    function __construct()
    {
        $this->table = "tag_tmp";
        $this->db = new mysqlDb();
        $this->str = '';
        $this->class_arr = '';

    }

    function htmTagTplList()
    {
        $query = $this->db->select("`{$this->table}`",'*');
        print_r($query);
        $rows = $this->db->num_rows();
        if($rows > 0){
            while($row = $this->db->fetch_array($query))
            {
                $this->class_arr[] = array($row['t_id'],$row['t_title'],$row['t_content'],$row['t_uptime']);  
            }
            $this->str= '';    
        return $this->class_arr; 
        }else{
        return "<h1>没有标签</h1>";
        }
        $this->db->free();
    }

    function htmTagTplEdit($id)
    {
        $query = $this->db->select("`{$this->table}`",'*',"`t_id`={$id}");
        $result = $this->db->fetch_array($query); 
        return $result;
        $this->db->free();
    }

    /*修改*/
    function htmTagTplUpdate($mod_content,$condition,$url='')
    {
        $this->db->update($this->table, $mod_content, $condition );
        $this->getMesssage($url,'修改栏目操作已完毕！','success');
        $this->db->free();
    }

    /*添加*/
    function htmTagTplAdd($columnName,$value,$url='')
    {
        $result = $this->db->insert($this->table, $columnName, $value);     
        $this->getMesssage($url,'添加栏目操作已完毕！','success');
        $this->db->free();
    }

    function htmTagTplDel($id,$url)
    {
        $this->db->delete($this->table, "t_id = {$id} " );
        $this->getMesssage($url,'删除栏目操作已完毕！','success');
        $this->db->free();   
    }

    function __toString(){}
    function __isset($var){ }
    function __unset($c){
        unset ($this->$c); 
    }
    function __destruct() {

    }

}


?>

