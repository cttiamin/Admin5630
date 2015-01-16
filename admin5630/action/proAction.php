<?php
/** 2011-09-19
 * jm edit 产品管理
 **/
//define('ADARCACTION', ereg_replace("[/\\]{1,}", '/', dirname(__FILE__) ) ); 
//require_once(ADARCACTION.'/config.php');

class proAction extends Action
{
    protected $db;
    public $str;
    protected $class_arr;
    protected $table;
    protected $tableBas;
    protected $tableCon;

    function __construct()
    {
    $this->db = new mysqlDb();
    $this->str = '';
    $this->class_arr = '';
    $this->table = 'pro_class';
    $this->tableBas = 'pro_base';
    $this->tableCon = 'pro_content';
    }
	
  	/*获取栏目路径*/
	public function proChannelUrl( $cid ){
		
		$query = $this->db->select("`{$this->table}`",'`pc_page`,`pc_bid`', "`pc_del`= 0 and `pc_id` = {$cid} ");
		$row = $this->db->fetch_array($query);
		
		$this->str = isset( $this->str ) ? $this->str : '';
		$this->str = '/'.$row['pc_page'].$this->str;
		
		if( intval($row['pc_bid']) != 0 ) $this->proChannelUrl( $row['pc_bid'] );
		
		return $this->str;
		
	}


    /*select选项  查询数据总数 */
    public function proChannelSelectIn($cid){
        $query = $this->db->select("`{$this->table}`",'`pc_title`,`pc_bid`,`pc_id`,`pc_keyworld`,`pc_page`','`pc_del`=0');
        $rows = $this->db->num_rows();
        if($rows > 0){
            while($row = $this->db->fetch_array($query)){
                $this->class_arr[] = array($row['pc_id'],$row['pc_title'],$row['pc_bid'],);  
            }
        $this->str= '';  
        return $this->proChannelSelect(0, 0, $cid); 
        }else{
        return "<h1>没有栏目</h1>";
        }
        $this->db->free();
    }

    /*select选项 无限循环体  */
    private function proChannelSelect($m,$id,$index){

	$n = str_pad('',$m,'-',STR_PAD_RIGHT);
	$n = str_replace("-","&nbsp;&nbsp;",$n);
	for($i=0; $i<count($this->class_arr); $i++){
        if($this->class_arr[$i][2] == $id)
        {
            if($this->class_arr[$i][0] == $index)
            {
				$this->str.= "<option value=\"".$this->class_arr[$i][0]."\" selected=\"selected\">".$n."|--".$this->class_arr[$i][1]."</option>\n";
			}else{
                $this->str.= "<option value=\"".$this->class_arr[$i][0]."\">".$n."|--".$this->class_arr[$i][1];
                if($this->class_arr[$i][2]!=0)$this->str.=" *";
                $this->str.="</option>\n";
            }
			$this->proChannelSelect($m+1, $this->class_arr[$i][0], $index);			
		}
    }
    return $this->str;    
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

