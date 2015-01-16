<?php
/** 2013-06-30
 * 用户管理
 **/
define('ADARCACTION', str_replace("[/\\]{1,}", '/', dirname(__FILE__) ) ); 
require_once(ADARCACTION.'/config.php');
//$str = new strInc();
class adUserAction extends Action
{
    protected $db;

    function __construct()
    {
        $this->table = "ad_user";
        $this->db = new mysqlDb();
        $this->str = '';
        $this->class_arr = '';
    }

    function adUserList()
    {
        $query = $this->db->select("`{$this->table}`",'*');
        print_r($query);
        $rows = $this->db->num_rows();
        if($rows > 0){
            while($row = $this->db->fetch_array($query))
            {
                $this->class_arr[] = array($row['uid'],$row['username'],$row['createdate']);  
            }
            $this->str= '';    
        return $this->class_arr; 
        }else{
        return "<h1>没有内容</h1>";
        }
        $this->db->free();
    }
    /*Add 查询 (username)*/
    function adUserAddQuery($username)
    {
        $query = $this->db->select("`{$this->table}`",'`username`, `password`, `uid`',"`username` = '{$username}'");
        $result = $this->db->fetch_array($query); 
        return $result;
        $this->db->free();
    }
    /*添加*/
    function adUserAdd($columnName, $value, $url='')
    {

        $result = $this->db->insert($this->table, $columnName, $value);     
        //$this->getMesssage($url,'添加栏目操作已完毕！','success');
        $this->db->free();
    }
    /*修改查询*/
    function adUserEdit($id)
    {
        $query = $this->db->select("`{$this->table}`",'*',"`uid`={$id}");
        $result = $this->db->fetch_array($query); 
        return $result;
        $this->db->free();
    }
    /*修改*/
    function adUserUpdate($mod_content, $condition, $url='')
    {
        $this->db->update($this->table, $mod_content, $condition );
        //$this->getMesssage($url,'修改栏目操作已完毕！','success');
        $this->db->free();
    }

    //删除
    function adUserDel($id, $url)
    {
        $this->db->delete($this->table, "uid = {$id} " );
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

