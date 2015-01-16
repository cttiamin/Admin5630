<?php
/**
 * 文章动作类 
 * Last Change: 2011-12-29 12:24
 * Maintainer: etcphp@sohu.com
 **/
//define('ADARCACTION', ereg_replace("[/\\]{1,}", '/', dirname(__FILE__) ) ); 
//require_once(ADARCACTION.'/config.php');

class arcAction extends Action
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
    $this->table = 'miscell_class';
    }
	
  	/*获取栏目路径*/
	public function arcChannelUrl( $cid ){
		
		$query = $this->db->select("`{$this->table}`",'`webpage`,`b_id`,`class_code`', "`del`= 0 and `class_code` = {$cid} ");
		$row = $this->db->fetch_array($query);
		
		$this->str = isset( $this->str ) ? $this->str : '';
		$this->str = '/'.$row['webpage'].$this->str;
		
		if( intval($row['b_id']) != 0 ) 
		$this->arcChannelUrl( $row['b_id'] );
		
		return $this->str;
	}
		
     /*select选项  */
    public function arcChannerSelectIn($cid){
        $query = $this->db->select("`{$this->table}`",'`class_title`,`b_id`,`class_code`,`flag`','`del`=0 ');
        $rows = $this->db->num_rows();
        if($rows > 0){
            while($row = $this->db->fetch_array($query)){
                $this->class_arr[] = array($row['class_code'],$row['class_title'],$row['b_id'],$row['flag']);  
            }
        	$this->str= '';  
        	return $this->arcChannerSelect(0,0,$cid); 
        }else{
        	return "<h1>没有栏目</h1>";
        }
        $this->db->free();
    }
	
    /*select选项 循环体  */
    private function arcChannerSelect($m,$id,$index){
		//global $class_arr;
		$n = str_pad('',$m,'-',STR_PAD_RIGHT);
		$n = str_replace("-","&nbsp;&nbsp;",$n);
		
		for($i=0;$i<count($this->class_arr);$i++){
			if($this->class_arr[$i][2]==$id){
				if($this->class_arr[$i][0]==$index){
					$this->str.= "<option value=\"".$this->class_arr[$i][0]."\" selected=\"selected\">".$n."|--".$this->class_arr[$i][1]."</option>\n";
				}else{
					$this->str.= "<option value=\"".$this->class_arr[$i][0]."\">".$n."|--".$this->class_arr[$i][1];
					if($this->class_arr[$i][3]==1)$this->str.=" *";
					$this->str.="</option>\n";
				}
				$this->arcChannerSelect($m+1, $this->class_arr[$i][0], $index);
			}
		}
		return $this->str;    
	} 



}


?>
