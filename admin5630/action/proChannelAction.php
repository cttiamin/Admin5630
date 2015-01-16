<?php
/** 2011-09-19
 * jm edit 产品栏目管理
 **/
//define('ADARCACTION', ereg_replace("[/\\]{1,}", '/', dirname(__FILE__) ) ); 
//require_once(ADARCACTION.'/config.php');
//$str = new strInc();
class proChannelAction extends proAction
{
    private $cid;
    private $sql;

    function __construct()
    {parent::__construct();
    }
    function proChannelList(){
        $query = $this->db->select("`{$this->table}`",'`pc_title`,`pc_bid`,`pc_id`,`pc_keyworld`,`pc_page`','`pc_del`=0');
        $rows = $this->db->num_rows();
        if($rows > 0){
            while($row = $this->db->fetch_array($query)){
                $this->class_arr[] = array($row['pc_id'],$row['pc_title'],$row['pc_bid'],$row['pc_keyworld'],$row['pc_page']);  
            }
            $this->str= '';    
        return $this->proChannelInfo(0,0); 
        }else{
        return "<h1>没有栏目</h1>";
        }
        $this->db->free();
    }
    //栏目列表无限循环分类
    private function proChannelInfo($m,$id){    
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
            <img src=\"resources/images/icons/pencil.png\"alt=\"修改\" /></a>\n
            <a href=\"?action=del&amp;delId=".$this->class_arr[$i][0]."\" title=\"Del\">
            <img src=\"resources/images/icons/cross.png\"alt=\"删除\" /></a>\n
            <a href=\"?action=edit_meta&amp;id=".$this->class_arr[$i][0]."\" title=\"Edit Meta\">
            <img src=\"resources/images/icons/hammer_screwdriver.png\"alt=\"改名\" /></a>\n
            </td>\n ";//操作
        $this->proChannelInfo($m+1,$this->class_arr[$i][0]);
        $this->str.="</tr>\n";     
		}
    }     
    return $this->str;
    }


    function proChannelEdit($id){
        $query = $this->db->select("`{$this->table}`",'*',"`pc_del`= 0 and `pc_id`={$id}");
        $result = $this->db->fetch_array($query); 
        return $result;
        $this->db->free();
    }


    function proChannelUpdate($mod_content,$condition,$url=''){
        $this->db->update($this->table, $mod_content, $condition );
        $this->getMesssage($url,'修改栏目操作已完毕！','success');
        $this->db->free();
    }
    function proChannelAdd($columnName,$value,$url=''){
        $result = $this->db->insert($this->table, $columnName, $value);     
        //print_r("inset into {$this->table} $columnName values $value");
        //exit();
        $this->getMesssage($url,'添加栏目操作已完毕！','success');
        $this->db->free();
    }
    function proChannelDel($id,$url){
    $this->db->update($this->table,"`pc_del` = 1" , "`pc_id` = {$id}" );
    $this->getMesssage($url,'删除栏目操作已完毕！','success');
    $this->db->free();   
    }
    function proChannelDelList(){}


    function __toString(){}
    function __isset($var){ }
    function __unset($c){
        unset ($this->$c); 
    }
    function __destruct() {

    }

}


?>

