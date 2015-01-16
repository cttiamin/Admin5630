<?php
/** 2011-09-17
 * jm edit 文章栏目管理
 **/
define('ADARCACTIONDIS', ereg_replace("[/\\]{1,}", '/', dirname(__FILE__) ) ); 
require_once(ADARCACTIONDIS.'/config.php');

class proDisplayAction extends proChannelAction
{
    private $tablePic;
    function __construct()
    {
        $this->tablePic = "pro_pic";
        parent::__construct();
        //echo $this->tableBas;
    }
    /*总条数*/
    function proDisplaySum($cid){
        $query = $this->db->select("`{$this->tableBas}`",'`p_id`',"`pc_id`= {$cid} ");
        $rows = $this->db->num_rows();
		$this->db->free();
        return  $rows;      
    }    
    /*列表*/
    function proDisplayList($cid,$in=0,$to=20){
        $query = $this->db->select("`{$this->tableBas}`",'`p_id`,`pc_id`,`p_title`,`p_flag`,`p_uptime`,`p_state`',"`pc_id`={$cid} and `p_del`=0 order by `p_ctime` desc limit {$in},{$to}");
        $rows = $this->db->num_rows();
        if($rows > 0){
            while($row = $this->db->fetch_array($query)){
                $this->arcArr[] = array($row['p_id'],$row['pc_id'],$row['p_title'],$row['p_flag'],$row['p_uptime'],$row['p_state']);  
            }
		//释放结果集
		$this->db->free();
        return $this->arcArr;
        }else{
        return false;
        }
        
    }

    /*添加*/
    function proDisplayAddBas($columnName,$value){
        $result = $this->db->insert($this->tableBas, $columnName, $value);     
        $lastId = $this->db->insert_id();
        return $lastId;
        $this->db->free();
    }

    function proDisplayAddCon($columnName,$value){
        $result = $this->db->insert($this->tableCon, $columnName, $value);   
        $this->db->free();        
    }

    /*修改获取*/
    function proDisplayEdit($id){
        $query = $this->db->select("`pro_base` as a, `pro_content` as b",'*',"a.p_id=b.pn_id and a.p_id=$id ");
        $result = $this->db->fetch_array($query); 
        return $result;
        $this->db->free();
    }

    /*修改提交*/
    function proDisplayUpdateBas($mod_content,$condition)
    {
        $this->db->update($this->tableBas, $mod_content, $condition );
        $this->db->free();
    }
    function proDisplayUpdateCon($mod_content,$condition)
    {
        $this->db->update($this->tableCon, $mod_content, $condition );
        $this->db->free();
    }

    /*删除*/
    function proDisplayDel($delId,$url){
    $this->db->update($this->tableBas,"`p_del` = 1" , "`p_id` = {$delId}" ); 
    $this->getMesssage($url,'删除文章操作已完毕！','success');
    $this->db->free();   
    }

    /*图片表检测*/   
    function proDisplayCheckPic($id)
    {
        $cquery = $this->db->select("`{$this->tablePic}`", '`pp_id` ', "`p_id` = {$id}");
        $total = $this->db->num_rows();

        if(!($total==12))
        {
			/*全部删除*/
            $this->db->delete( $this->tablePic, "p_id = {$id}");
			/*重新插入*/
            for($i=1;$i<13;$i++)
                $this->db->insert($this->tablePic, "`pp_id`,`p_id`,`pp_url`", "'picture{$i}',$id,''");

        	//$this->db->free();
        }

        $query=$this->db->findall("`pro_pic` where `p_id` = {$id}");
        while($row = $this->db->fetch_array($query)){
            $row_arr[$row['pp_id']]=$row['pp_url'];
            $row_con[$row['pp_id']]=$row['pp_con'];
        }

        $result[0] = $row_arr;
        $result[1] = $row_con;  
        return $result;     
    }

    /*图表更新*/
    public function proDisplayUpdatePic($post,$pid)
    {
        
        foreach($post as $pp_id=>$url){
            $url=explode('{%con%}', $url,2);
          
            //$mod_content = "`pp_url`='$url[0]',`pp_con`='$url[1]' "; //改图片路径
            $mod_content = "`pp_con`='$url[1]' ";
            $condition = "`pp_id`='$pp_id' and `p_id`={$pid}";

            $this->db->update($this->tablePic, $mod_content, $condition );
        }

        $this->db->free();   
        return 0;
    }

    /* 图片上传结果显示判断
     *  $pic,       图片
     *  $con,       描述
     *  $num,       个数
     *  $pageUrl,   路径
     *  $picId，    产品id
     *
     * */
    public function proDisplayPicIs($pic, $con, $num, $pageUrl, $picId, $cid='')
    {
        $str = '<td>';
        if(strstr($pic, 'jpg')) //有上传记录
        {  
            $str .= "
                <input name=\"picture{$num}\" class=\"text-input medium-input datepicker\"
                type=\"text\"  value=\"{%con%}{$con}\"/>   
                <span>$pic</span>
                ";
        }
        else    //没有上传记录
        {
            $str .="            
                <input name=\"picture{$num}\" class=\"text-input large-input datepicker\"
                type=\"text\" value=\"{$pic}{%con%}{$con}\"/>  
                ";
        }
        $str .="</td> <td>";    //第二列按扭
        if(strstr($pic, 'jpg'))
        {
            if($num == 1 || $num == 4 || $num == 7  || $num == 10 )
            {
                $str .= "<a href=\"".$pageUrl."&amp;a=picDel&amp;i=".$picId."&amp;v=".$num." \" class=\"button\"> 删除图片</a>"; 
            }
            else
            {
                $str .= "缩略图";
            }
            
        }
        else
        {
            if($num == 1 || $num == 4 || $num == 7  || $num == 10 )
            {
                $str .= "<input type=\"button\"
                    onclick=\"window.open('proUpFile.php?id=picture{$num}&amp;i=".$picId."&amp;c=".$cid."','mywin','toolbar=no,location=no,scrollbars=yes,width=550,height=200,left=120,top=70')\" name=\"Submit2\" value=\"上传图片\" class=\"button\"  />
                ";
            }
            else
            {
                $str .= "缩略图";
            }


        }
        $str .="</td><td>"; //第三列图片和像素
        $picInfo = @getimagesize(ADARCACTIONDIS.'/../..'.$pic);
        if(@getimagesize(ADARCACTIONDIS.'/../..'.$pic))
        {
            $str .="<img alt=\"\" src = \"". $pic ." \" width=\"30\" height=\"30\"> ".$picInfo[0]."*".$picInfo[1];
        }
        else
        {$str .='图片不存在'; }
    
        $str .= "</td>";
        return $str;

    }

    /*图表批量删除
     *  $i: 产品id
     *  $v: 产品名
     *
     * */
    function proDisplayPicDel($i, $v)
    {
		$str = '';
        for ($j=1; $j<=3; $j++ )
        {
            $query = $this->db->select($this->tablePic,'`pp_url`', "`pp_id`='picture".$v."' and `p_id`=".$i );
            $result = $this->db->fetch_array($query); 
            $this->db->free();
            //echo  ADARCACTIONDIS.'/../..'.$result[0]."<br/>";

            if(@unlink(ADARCACTIONDIS.'/../..'.$result[0]))
            {
                $str .= "文件".$j."删除成功_" ;
            }else{  $str .= "文件".$j."不存在!_";  } 

            $mod_content = "`pp_url`='' ";
            $condition = "`pp_id`='picture".$v."' and `p_id`=".$i;
            $this->db->update($this->tablePic, $mod_content, $condition );
            $this->db->free(); 
            $str .= "数据洗掉！";
            $v++;
        }
        return $str;
    }


    function __toString(){}
    function __isset($var){ }
    function __unset($c){unset ($this->$c); }
    function __destruct() { }


}


?>

