<?php
/** 2011-09-19
 * jm edit 产品栏目管理
 **/
define('ADARCACTION', str_replace("[/\\]{1,}", '/', dirname(__FILE__) ) ); 
require_once(ADARCACTION.'/config.php');

class adKeyWordAction extends Action
{
    private $db;
    private $sql;
    private $table;
    private $class_arr;
    private $str;
    
    function __construct()
    {
        $this->table = "tag_keyword";
        $this->db = new mysqlDb();
        $this->str = '';
        $this->class_arr = '';

    }
    /*总条数*/
    function adKeyWordSum(){
        $query = $this->db->select("`{$this->table}`",'`k_id`');
        $rows = $this->db->num_rows();
        return  $rows;
    }
	/*列表*/
    function adKeyWordList( $in=0, $to=20 )
    {
        $query = $this->db->select("`{$this->table}`", '*', "1=1 order by k_order asc limit {$in},{$to}");
        $rows = $this->db->num_rows();
        if($rows > 0){
            while($row = $this->db->fetch_array($query))
            {
                $this->class_arr[] = array($row['k_id'], $row['k_order'], $row['k_name'], $row['k_link'], $row['k_google'], $row['k_baidu']);  
            }
			return $this->class_arr; 
        }else{
			return 0;
        }
        $this->db->free();
    }

    function adKeyWordEdit($id)
    {
        $query = $this->db->select("`{$this->table}`", '*', "`k_id`={$id}");
        $result = $this->db->fetch_array($query); 
        return $result;
        $this->db->free();
    }

    /*修改*/
    function adKeyWordUpdate($mod_content,$condition,$url='')
    {
        $this->db->update($this->table, $mod_content, $condition );
        $this->getMesssage($url,'修改关键字操作已完毕！','success');
        $this->db->free();
    }

    /*添加*/
    function adKeyWordAdd($columnName, $value, $url='')
    {
        $result = $this->db->insert($this->table, $columnName, $value);     
        $this->getMesssage($url,'添加关键字操作已完毕！','success');
        $this->db->free();
    }

    function adKeyWordDel($id,$url)
    {
        $this->db->delete($this->table, "k_id = {$id} " );
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

