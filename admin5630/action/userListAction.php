<?php
/** Lastchange : 2012-02-19
 *	Maintainer : 
   会员目管理
 **/

class userListAction extends Action
{
    protected $db;
    public $str;
    public $class_arr;
    protected $table;

    function __construct()
    {
		$this->db = new mysqlDb();
		$this->str = '';
		$this->class_arr = '';
		$this->table = 'user_list';
    }

    /*总条数*/
    function userListSum(){
        $query = $this->db->select("`{$this->table}`",'`uid`');
        $rows = $this->db->num_rows();
		$this->db->free();
        return  $rows; 
    }    
	//完整内容 	 	 	pass
    /*列表 */
    function userListArr( $in=0, $to=20){
        $query = $this->db->select("`{$this->table}`",'`uid`,`name`,`reg_time`', "`del`=0 order by `reg_time` desc limit {$in},{$to}");
        $rows = $this->db->num_rows();
        if($rows > 0){
            while($row = $this->db->fetch_array($query))
			{
                $this->arcArr[] = array($row['uid'],$row['name'],$row['reg_time']);  
            }
		$this->db->free();	
        return $this->arcArr;
        }else{
        return false;
        }       
    }
    public function userListEdit($id)
    {
        $query = $this->db->select("`user_list` ",'*',"uid= $id ");
        $result = $this->db->fetch_array($query); 
        return $result;
        $this->db->free();        
    }
    public function userListUpdate ($mod_content,$condition)
    { 
        $this->db->update($this->table, $mod_content, $condition );
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

