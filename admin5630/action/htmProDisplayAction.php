<?php
/** 
 *  Last Change: 2011-12-28 15:38
 *  Maintainer: etcphp@sohu.com
 **/

class htmProDisplayAction extends htmProChannelAction
{

    function __construct()
    {
        parent::__construct();
    }


    /*
     * 内容公共部分生成
     * $str, 模板代码
     * $rsArr 当前数组
     * 
    private function proDisPlusHead($str, $rsArr)
    {
        //页面头部      
        $str=str_replace("{%keyword%}",$rsArr['pc_keyworld'],$str);
        $str=str_replace("{%discription%}",$rsArr['pc_discription'],$str);
        $str=str_replace("{%title%}",$rsArr['pc_ptitle'],$str);    
        $str=str_replace("{%nav%}",$this->nav(2),$str);     //导航
        $str=str_replace("{%index_logo%}",$this->nav(5), $str);     //logo
		$str=str_replace("{%optionSelect%}", $this->searchSelect(), $str); //searchSelect选项代码
        $str=str_replace("{%notice%}",$this->nav(4, 980), $str);   //商品分类 
        //新闻
        $str=str_replace("{%ind_news_55%}", $this->indNews(18, 12, 6), $str);   //人参专题 18类,16长,6条
        $str=str_replace("{%ind_news_53%}", $this->indNews(6, 12, 6), $str);   //美食常识
        // 链接和备案说明
        //$str=str_replace("{%links%}", $this->nav(6), $str);     
        $str=str_replace("{%ICP%}", $this->nav(7), $str);

        return $str;
    }
*/
    /*产品库存状态
     * */
    function proDisStock($pid){
        if($pid==1)
        {
            $str = "现货";
        }else
        {
            $str = "无货";
        }
        return $str;
    }

    /*产品图片
     * */
    function proDisPic($pid)
	{
		$str = '';
        $query=mysql_query("select * from pro_pic where p_id=".$pid);
        while($row = mysql_fetch_array($query))
		{
            $row_arr[$row['pp_id']] = $row['pp_url'];
            $row_con[$row['pp_id']] = $row['pp_con'];
        }
        $str .= "
            <a href=\"".$row_arr['picture1']."\" id=\"zoom1\" title=\"".$row_con['picture1']."\" rel=\"thumb-change: mouseover\" class=\"MagicZoom MagicThumb\"><img src=\"".$row_arr['picture2']."\" alt=\"".$row_con['picture2']."\" /></a>
            <p>
            <a href=\"".$row_arr['picture1']."\" rel=\"zoom1\" rev=\"".$row_arr['picture2']."\" title=\"".$row_con['picture1']."\"><img src=\"".$row_arr['picture3']."\" alt=\"".$row_con['picture3']."\" /></a>";

        if(!empty($row_arr['picture4']))
            $str.= "<a href=\"".$row_arr['picture4']."\" rel=\"zoom1\" rev=\"".$row_arr['picture5']."\" title=\"".$row_con['picture4']."\"><img src=\"".$row_arr['picture6']."\" alt=\"".$row_con['picture6']."\" /></a>";

        if(!empty($row_arr['picture7']))
            $str.="<a href=\"".$row_arr['picture7']."\" rel=\"zoom1\" rev=\"".$row_arr['picture8']."\" title=\"".$row_con['picture7']."\"><img src=\"".$row_arr['picture9']."\" alt=\"".$row_con['picture9']."\" /></a>";

        if(!empty($row_arr['picture10']))
            $str.="<a href=\"".$row_arr['picture10']."\" rel=\"zoom1\" rev=\"".$row_arr['picture11']."\" title=\"".$row_con['picture10']."\"><img src=\"".$row_arr['picture12']."\" alt=\"".$row_con['picture12']."\" /></a>";        
        $str.="</p>";     
		
        return $str;
    }
	
	/*产品内容替换
	strNew:模板
	proArr:产品内容
	rsArr:
	*/
	public function proDisContent($strNew, $proArr)
	{
            //图片
            $strNew =str_replace("{%Ppicture%}", $this->proDisPic( $proArr['p_id'] ), $strNew);
            //标题
            $strNew=str_replace("{%Ptitle%}", $proArr['p_title'], $strNew);   
            //编号
            $strNew =str_replace("{%Pbian%}", $proArr['p_bian'], $strNew);    
            //价格
            $strNew =str_replace("{%Pprice%}",$proArr['p_price'], $strNew); 
            //库存
            $strNew =str_replace("{%Pstock%}", $this->proDisStock($proArr['p_stock']),$strNew); 
            //促销信息
            $strNew =str_replace("{%Pzeng%}",$proArr['pn_promotion'],$strNew); 
            //产地
            $strNew = str_replace("{%Paddress%}",$proArr['pn_address'],$strNew); 
            //重量
            $strNew = str_replace("{%Ppage%}",$proArr['pn_weight'],$strNew); 
            //淘宝链接
            //$strNew = str_replace("{%Plogo%}", $proArr['p_url'], $strNew);
            //运费
            $strNew =str_replace("{%Pmprice%}",$proArr['pn_mprice'],$strNew); 
            //上架时间
            $strNew =str_replace("{%Puptime%}",$proArr['p_uptime'],$strNew); 
			//加入购物车Id
			$strNew =str_replace("{%Pid%}",$proArr['p_id'],$strNew);
            //说明信息
            $strNew=str_replace("{%Pcontent%}", $proArr['pn_content'], $strNew); 
            //参数
        $strNew=str_replace("{%Premark%}", $proArr['pn_remark'], $strNew);
		
		//页面头部      
		$strNew=str_replace("{%keyword%}",$proArr['p_keyworld'], $strNew);
		$strNew=str_replace("{%discription%}",$proArr['pn_discription'], $strNew);
		$strNew=str_replace("{%title%}",$proArr['p_ptitle'].$this->configDeptRow['websitename'], $strNew);
		//新闻
		//$strNew=str_replace("{%ind_news_55%}", $this->indNews(18, 12, 6), $strNew);   //人参专题 18类,16长,6条
        //$strNew=str_replace("{%ind_news_53%}", $this->indNews(6, 12, 6), $strNew);   //美食常识
        
        $strNew = str_replace("{%ind_news_55%}",$this->indNews(12, 12, 8), $strNew);   //专题下 12类,16长,6条
        $strNew = str_replace("{%ind_news_53%}",$this->indNews(2, 12, 8), $strNew);   // 食谱
    
        $strNew = str_replace("{%left_pro%}", $this->leftPro($proArr['pc_id'], 12, 5), $strNew); //左边相关商品
		
		return $strNew;
	}
	
	
    /*修改或添加生成一篇文章*/
	public function proDisplayHtm($id)
	{
		$id = intval($id);
		/*商品查询*/
		$query = $this->db->select('pro_base, pro_content', '*', " pro_content.pn_id = pro_base.p_id and pro_base.p_id = $id");
		$proArr = $this->db->fetch_array($query);	//商品pc_id
		$rsSclass = $this->proClassArr( "pc_id=".$proArr['pc_id'] ); //小类
		$rsBclass = $this->proClassArr( "pc_id=".$rsSclass['pc_bid'] ); //大类
		
        //产品内容模板路径
        $urlTpl = $this->htmUrl."/libs/templates/pro_dis.htm";     
        //获取物理路径
        $urlTo = $this->htmUrl.DIRECTORY_SEPARATOR.$rsBclass['pc_page'].DIRECTORY_SEPARATOR.$rsSclass['pc_page'].DIRECTORY_SEPARATOR.$proArr['p_id']; 
        //页面链接路径
        $proUrl = $this->configDeptRow['website_url'].'/'.$rsBclass['pc_page'].'/'.$rsSclass['pc_page'];       
        //生成目录不存在则创建
        if(!$this->isDir($urlTo))
		{
            return '生成路径创健失败无法生成!';
        }        
        //获取模板代码
        $str = @ file_get_contents( $urlTpl );
        //面包屑导航
        $break ="当前位置：　<a href=\"".$this->configDeptRow['website_url']."/\">首页</a>　»　<a href=\"".$this->configDeptRow['website_url'].'/'.$rsBclass['pc_page']."/\">".$rsBclass['pc_title']."</a>　»　<a href=\"".$this->configDeptRow['website_url'].'/'.$rsBclass['pc_page'].'/'.$rsSclass['pc_page']."/\">".$rsSclass['pc_title']."</a>";
        $str = str_replace("{%break%}", $break, $str );
		//创建当前文章路径
        $urlNew = $urlTo.'/index.html';
		$strNew = $str;
		/*商品属性替换*/
		$strNew = $this->proDisContent($strNew, $proArr);
		//生成公共内容
		$strNew = $this->plusHead( $strNew );

        $newPage = fopen($urlNew, "w");			        //创建的方式打开新的页面
        fwrite($newPage, $strNew);						//写入新页面
        fclose($newPage);								//关闭指针
		return; exit();
	}
	
	
	
	/* 小类下的产品生成, */
    private function proDisplaySmallClass($rsArr)
    {
        //大类查询
        $bigArr = $this->proClassArr('pc_id = '.$rsArr['pc_bid'], '*');
        //产品内容模板路径
        $urlTpl = $this->htmUrl."/libs/templates/pro_dis.htm";     
        //获取上级大类目录
        $urlTo = $this->htmUrl.'/'.$bigArr['pc_page'].'/'.$rsArr['pc_page']; 
        //页面链接路径
        $proUrl = $this->configDeptRow['website_url'].'/'.$bigArr['pc_page'].'/'.$rsArr['pc_page'];       

        //目录不存在则创建
        if(!$this->isDir($urlTo))   
        {
            return '生成路径创健失败无法生成!';
        }        
        //获取模板代码
        $str = @ file_get_contents($urlTpl);
		
        //面包屑导航
        $break ="当前位置：　<a href=\"".$this->configDeptRow['website_url']."/\">首页</a>　»　<a href=\"".$this->configDeptRow['website_url'].'/'.$bigArr['pc_page']."/\">".$bigArr['pc_title']."</a>　»　<a href=\"".$this->configDeptRow['website_url'].'/'.$bigArr['pc_page'].'/'.$rsArr['pc_page']."/\">".$rsArr['pc_title']."</a>";
        $str = str_replace("{%break%}", $break, $str); 

        //查询
        $query=mysql_query("select * from `pro_base` as a, pro_content as b where a.p_id=b.pn_id and a.pc_id=".$rsArr['pc_id']." and a.p_state=1 order by a.p_uptime desc");
		
		if(mysql_num_rows($query) == 0)
		{
			return "<b>".$rsArr['pc_title']."</b> -> 栏目为空, 请重新选择!";
			exit();
		}

		//返回消息
		$message = '';
        //进入循环
        while($proArr = mysql_fetch_array($query))
        {
            
            //生成目标路径检查
            $this->isDir($urlTo.'/'.$proArr['p_id']);
            $urlNew = $urlTo.'/'.$proArr['p_id'].'/index.html';
			//新
			$strNew = $str;
			/*商品属性替换*/
			$strNew = $this->proDisContent($strNew, $proArr);
			//生成公共内容
			$strNew = $this->plusHead($strNew);

            $newPage=fopen($urlNew, "w");			    //创建的方式打开新的页面
            fwrite($newPage, $strNew);					//写入新页面
            fclose($newPage);							//关闭指针

            //返回成功信息
            $message .="<br/><b>".$bigArr['pc_title']."->".$rsArr['pc_title']."->".$proArr['p_title']."-> </b>生成页面为:/".$bigArr['pc_page']."/".$rsArr['pc_page']."/".$proArr['p_id']."/index.html ";
            //清空目标路径
            $urlNew = '';
            $strNew = '';
        }

        return $message;
    }

    /* 产品栏目内容生成
     * $id : 当前类别id
     * */
    public function main($id)
    {   
        $rsArr = $this->proClassArr("pc_id=".$id);

        if($rsArr['pc_bid'] == 0)
        {
            return '大类不能生成! 请重新选择.';           
        }
        else
        {
            $str = $this->proDisplaySmallClass($rsArr);
            return $str;
        }

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

