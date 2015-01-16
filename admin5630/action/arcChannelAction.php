<?php
/** 2011-09-15
 *  jm edit 文章栏目管理
 **/
//define('ADACTIONCONFIG', ereg_replace("[/\\]{1,}", '/', dirname(__FILE__) ) ); 
//require_once(ADACTIONCONFIG.'/config.php');

class arcChannelAction extends arcAction{
    private $cid;
    //private $table;
    //private $db;
    //private $class_arr;
    //private $str;
    private $sql;
    
    function __construct(){
        parent::__construct();
        //$this->db = new mysqlDb();
        //$this->table = 'miscell_class';
        //$this->str = '';             
    }

    function arcChannelList(){
        $query = $this->db->select("`{$this->table}`",'`class_title`,`b_id`,`class_code`,`keyworld`,`webpage`','`del`=0');
        $rows = $this->db->num_rows();
        if($rows > 0){
            while($row = $this->db->fetch_array($query)){
                $this->class_arr[] = array($row['class_code'],$row['class_title'],$row['b_id'],$row['keyworld'],$row['webpage']);  
            }
            $this->str= '';    
        return $this->arcChannerInfo(0,0); 
        }else{
        return "<h1>没有栏目</h1>";
        }
        $this->db->free();
    }
    //栏目列表无限循环分类
    private function arcChannerInfo($m,$id){    
 	if($id=="")$id=0;//顶级
	$n = str_pad('',$m,'-',STR_PAD_RIGHT);  //类名前缀
    $n = str_replace("-","&nbsp;&nbsp;",$n);
	for($i=0;$i<count($this->class_arr);$i++){
        if($this->class_arr[$i][2]==$id){
        $this->str.="<tr>\n";
        $this->str.="<td><input type=\"checkbox\" /></td>\n";//多选
        $this->str.= "<td>".$n."|--".$this->class_arr[$i][1]."</td>\n";//名称
        $this->str.= "<td><a href=\"?action=edit&amp;id=".$this->class_arr[$i][0]."\" title=\"\">".$this->class_arr[$i][0]."</a></td>\n";//ID
        $this->str.= "<td>".$this->class_arr[$i][4]."</td>\n";//生成页面
        $this->str.= "<td>".$this->class_arr[$i][3]."</td>\n";//关键字
        $this->str.= "<td><a href=\"?action=edit&amp;tplEdit=".$this->class_arr[$i][0]."\" title=\"Edit\">
            <img src=\"resources/images/icons/pencil.png\"alt=\"修改\" /></a>　
            <a href=\"?action=del&amp;delId=".$this->class_arr[$i][0]."\" title=\"Del\">
            <img src=\"resources/images/icons/cross.png\"alt=\"删除\" /></a>\n
            </td>\n ";//操作
        /*
         
            <a href=\"?action=edit_meta&amp;id=".$this->class_arr[$i][0]."\" title=\"Edit Meta\">
            <img src=\"resources/images/icons/hammer_screwdriver.png\"alt=\"改名\" /></a>\n
         */
        $this->arcChannerInfo($m+1,$this->class_arr[$i][0]);
        $this->str.="</tr>\n";     
		}
    }     
    return $this->str;
    }


    /*一级分类 未用 */
    public function arcChannelSmall(){  
        $query = $this->db->select("`{$this->table}`",'`class_title`,`b_id`,`class_code`,`keyworld`,`webpage`','`flag`=0 and del=0');
        $rows = $this->db->num_rows();
        if($rows > 0){
            while($row = $this->db->fetch_array($query)){
                    $this->class_arr[] = array($row['class_code'],$row['class_title'],$row['b_id'],$row['keyworld'],$row['webpage']);  
            }
        return $this->class_arr;
        }else{
        return "<h1>没有栏目</h1>";
        }
       $this->db->free();
    }
    /*修改查询*/
    function arcChannerEdit($id){
        $query = $this->db->select("`{$this->table}`",'*',"`del`=0 and `class_code`={$id}");
        $result = $this->db->fetch_array($query); 
        return $result;
        $this->db->free();
    }
    /*修改*/
    function arcChannerUpdate($mod_content, $condition, $url='')
	{
        $this->db->update($this->table, $mod_content, $condition );
        $this->getMesssage($url, '修改栏目操作已完毕！', 'success');
        $this->db->free();
    }
    /*添加*/
    function arcChannerAdd($columnName,$value,$url=''){
        $result = $this->db->insert($this->table, $columnName, $value);     
        $this->getMesssage($url,'添加栏目操作已完毕！','success');
        $this->db->free();
    }
    function arcChannerDel($id,$url){
    $this->db->update($this->table,"`del` = 1" , "`class_code` = {$id}" );
    $this->getMesssage($url,'删除栏目操作已完毕！','success');
    $this->db->free();   
    }

    function arcChannerDelList(){}
    function __isset($var){ }
    function __unset($c){
        unset ($this->$c); 
    }
    function __destruct() {}
        
}


?>
