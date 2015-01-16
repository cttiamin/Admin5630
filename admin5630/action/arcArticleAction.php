<?php
/** 2011-09-15
 *  jm edit 文章内容
 **/

class arcArticleAction extends arcChannelAction{
    private $tableBas;
    private $tableCon;
    private $arcArr;

    function __construct(){
        parent::__construct();
        $this->tableBas = 'miscell_base';
        $this->tableCon = 'miscell_content';     
    }

    /*总条数*/
    function arcArticleSum($cid, $articleTitle){
        $articleStr = '';
        if($articleTitle != ''){
            $articleStr = ' and a_title like "%'.$articleTitle.'%" ';
        }
        $query = $this->db->select("`{$this->tableBas}`",'`a_id`',"`class_id`= {$cid} $articleStr");
        $rows = $this->db->num_rows();
        return  $rows;
        $this->db->free();
    }    

    /*列表*/
    function arcArticleList( $cid, $in=0, $to=20, $articleTitle ){

        //是否有匹配
        $articleStr = '';
        if($articleTitle != ''){
            $articleStr = ' and a_title like "%'.$articleTitle.'%" ';
        }

        $query = $this->db->select("`{$this->tableBas}`",'`a_id`,`class_id`,`a_title`,`flag`,`uptime`,`a_state`,`createtime`',"`class_id`={$cid} and `a_del`=0 $articleStr order by `createtime` desc limit {$in},{$to}");
        $rows = $this->db->num_rows();

        if($rows > 0){
            while($row = $this->db->fetch_array($query)){
                $this->arcArr[] = array($row['a_id'],$row['class_id'],$row['a_title'],$row['flag'],$row['uptime'],$row['a_state']);  
            }
            return $this->arcArr;
        }else{
            return false;
        }

        $this->db->free();
    }

    /*添加*/
    function arcArticleAdd($columnName,$value){
        $result = $this->db->insert($this->tableBas, $columnName, $value);     
        $lastId = $this->db->insert_id();
        return $lastId;
        $this->db->free();
    }

    function arcArticleAddCon($columnName, $value, $url){
        $result = $this->db->insert($this->tableCon, $columnName, $value);   
        $this->db->free();
		return;    
    }

    /**
     * 修改获取 1篇内容
     * */
    function arcArticleEdit($id){
        $query = $this->db->select("`miscell_base` as a, `miscell_content` as b",'*',"a.a_id=b.nid and a.a_id=$id ");
        $result = $this->db->fetch_array($query); 
        return $result;
        $this->db->free();
    }

    /*修改提交*/
    function arcArticleUpdateBas($mod_content,$condition){
        $this->db->update($this->tableBas, $mod_content, $condition );
        $this->db->free();
    }

    function arcArticleUpdateCon($mod_content,$condition){
        $this->db->update($this->tableCon, $mod_content, $condition );    
        $this->db->free();
    }
	
	/*获取文章页面文件地址*/
	function arcFilePath($id)
	{
		$query = $this->db->select( $this->tableBas, '`class_id`, `a_id`', "`a_id` = {$id}");
		$rsArc = $this->db->fetch_array( $query );
		$querySclass =  $this->db->select( 'miscell_class', '`b_id`,`class_code`,`webpage`', "`class_code` = {$rsArc['class_id']}");
		$rsSclass = $this->db->fetch_array($querySclass);
		$queryBclass = $this->db->select( 'miscell_class', '`b_id`,`class_code`,`webpage`', "`class_code` = {$rsSclass['b_id']}");
		$rsBclass = $this->db->fetch_array($queryBclass);
		//返回文件路径		
		return $file = JMADMIN.'/../'.$rsBclass['webpage'].DIRECTORY_SEPARATOR.$rsSclass['webpage'].DIRECTORY_SEPARATOR.$rsArc['a_id'].DIRECTORY_SEPARATOR.'index.html';
		exit();
	}
	
    /*删除*/
    function arcArticleDel($delId, $url){
		//删除物理文件
		$file = $this->arcFilePath($delId);
		if(file_exists($file))
		{
			unlink($file);
		}
		else {
            //echo "静态页面不存在!"; 
            //$this->getMesssage($url,'静态页面不存在！','error');
            //exit();
		}
		
		$this->db->delete($this->tableBas, "`a_id` = {$delId}");
		$this->db->delete($this->tableCon, "`nid` = {$delId}");
    }

	/* 获取上一篇添加文章ID (id, class_id) */
	function arcArticleLastId($id, $classId){
		$query = $this->db->select('miscell_base', 'a_id,a_title', "class_id=".$classId." and uptime < ".time()." and a_state=1 and a_del=0  order by uptime desc limit 1");
		$rs = @$this->db->fetch_array($query);	
		return $rs['a_id'];
    }

    /*获取权限*/
    public function getClassAuthority($classId){
        return 'aaa';
    }

    /**
     * 时间测试, 修改 Access 时间为 datetimes 格式
     * 华商协会, 新闻测试
     */
    public function timeChange(){

    
        $query = $this->db->select("`sp_posts`"
            ,'`key_id`,`UpTime`,`T1`,`T88`,`T83`'
            ," 1=1 order by `id` asc");

        $rows = $this->db->num_rows();

        if($rows > 0){
            while($row = $this->db->fetch_array($query)){

               // echo $row['T88'];
                if($row['T83'] != ''){
                    print_r(str_replace('/', '-', $row['T83']));

                    $time_str = str_replace('/' , '-', $row['T83']); 

                    $sql = 'update `sp_posts` set `time003` = "'.$time_str
                        .'"  where key_id='.$row['key_id'];
                    mysql_query($sql);
                }
                
                //$this->arcArr[] = array($row['a_id'],$row['class_id'],$row['a_title'],$row['flag'],$row['uptime'],$row['a_state']);  
            }
            return $this->arcArr;
        }else{
            return false;
        }

        $this->db->free();
    }

    function __toString(){}
    function __isset($var){ }
    function __unset($c){unset ($this->$c); }
    function __destruct() { }

}

?>
