<?php
/** 
 *  产品栏目生成HTML
 *  Last Change: 2011-12-26 15:38
 *  Maintainer: etcphp@sohu.com
 **/

class htmProChannelAction extends htmIndexAction
{
    public $db;
    public $strInc;

    function __construct()
    {
        parent::__construct();
    }
    
    /* 产品小类列表数组获取
     * $rsArr,  小类
     * $bigArr  大类
     * */
    public function proChannelSmall($rsArr)
    {
        //小类列表查询       
        $query = $this->db->select("`$this->proBaseTable`",'`p_id`, `p_title`, `p_price`, `p_url`', "p_state =1 and pc_id=".$rsArr['pc_id']);
        $proList = '';
        while($result = $this->db->fetch_array($query))
        {
            $proList[] = array($result[0], $result[1], $result[2], $result[3]) ;           
        }
		
        return $proList;
    }

    /*
     * 产品大类列表生成 
     * $rsArr : 大类返回数组
     *
     * */
    public function proChannelBig($rsArr)
    {
        //小类查询       
        $query = $this->db->select("`$this->proClassTable`",'`pc_id`, `pc_bid`, `pc_title`, `pc_page`', "pc_bid=".$rsArr['pc_id']);
        while($result = $this->db->fetch_array($query))
        {
            $smallQuery[] = array($result[0], $result[1], $result[2], $result[3]) ;           
        }
        $this->db->free();
        $this->str = '';
        //小类循环                
        for($i=0; $i < count($smallQuery); $i++)
        {   
            //小类列表链接
            $smallUrl = $this->configDeptRow['website_url']."/".$rsArr['pc_page']."/".$smallQuery[$i][3]."/";
            //小类列表标题
            $this->str .= "<dl><dt><span>".$smallQuery[$i][2]."</span><a href=\"".$smallUrl."\" title=\"".$smallQuery[$i][2]."\">更多</a></dt>";

            //产品内容查询
            $proDisQuery = mysql_query("select `p_id`, `p_title`, `p_price` from `{$this->proBaseTable}` where pc_id = ".$smallQuery[$i][0]." and `p_state` = 1 limit 8");           

            //进入产品内容循环
            while( $proDisRow = mysql_fetch_array($proDisQuery) )
            {
                $proDisUrl = $smallUrl.$proDisRow['p_id']."/"; //产品内容路径
                
                $this->str .= "<dd><ul>
                    <li><a href=\"".$proDisUrl."\" title=\"".$proDisRow['p_url']."\"><img src=\"".$this->getProPic($proDisRow['p_id'])."\" alt=\"".$proDisRow['p_title']."\" /></a></li>
                    <li><a href=\"".$proDisUrl."\" title=\"".$proDisRow['p_url']."\">".$this->strInc->cut_str($proDisRow['p_title'],19,'')."</a></li>
                    <li>￥".$proDisRow['p_price']."元</li>
                    <li><a href=\"".$proDisUrl."\" title=\"购买".$proDisRow['p_title']."\">立即购买</a></li>
                    </ul></dd> ";
            }
            $this->str .= "</dl><div style=\"clear:both; height:20px;\"></div>";
			/*生成当前类别下,列表页面*/
			$rsSmallClass = $this->proClassArr("pc_id=".$smallQuery[$i][0], '*');
			$this->proList( $rsSmallClass );
        }
        return $this->str;     
    }

    /* 生成小类列表
     * $rsArr : 当前类生成数组
     * */
    private function proList($rsArr)
    {
        //大类查询
        $bigArr = $this->proClassArr('pc_id = '.$rsArr['pc_bid'], '*');
        $urlTpl = $this->htmUrl."/libs/templates/pro_list.htm";     //模板路径
        //获取上级大类目录
        $urlTo = $this->htmUrl.'/'.$bigArr['pc_page'].'/'.$rsArr['pc_page']; 
        //链接
        $proUrl = $this->configDeptRow['website_url'].'/'.$bigArr['pc_page'].'/'.$rsArr['pc_page'];

        if(!$this->isDir($urlTo))   //目录不存在则创建
        {
            return '生成路径创健失败无法生成!';
        }
        $str = @ file_get_contents($urlTpl);            //获取模板代码
        //面包导航
        $break ="当前位置：　<a href=\"".$this->configDeptRow['website_url']."/\">首页</a>　»　<a href=\"".$this->configDeptRow['website_url'].'/'.$bigArr['pc_page']."/\">".$bigArr['pc_title']."</a>　»　<a href=\"".$this->configDeptRow['website_url'].'/'.$bigArr['pc_page'].'/'.$rsArr['pc_page']."/\">".$rsArr['pc_title']."</a>";
        $str = str_replace("{%break%}", $break,$str); 
		
        //产品分类内容列表数据获取 (小类)
        $proList = $this->proChannelSmall($rsArr);
        if(empty($proList))
        {
            return '当前类别内容为空!';
            exit();
        }
        $displayNum = 16;                       	//每页   
        $totle = count($proList);                  //总数 
        $lastPage = ceil($totle/$displayNum);      //总页
        $strList = '';
        $page = 1;        	//当前页
		$message = '';		//栏目生成消息

        //分类列循环体 
        for($i=0; $i<count($proList); $i++)
        {          
			//分页                         
            $num = ($page=1) ? ($num = '') : ($num = $page) ;
            $pageStr = $this->pageNav($page, $lastPage, $proUrl);
            //小类列表链接
			$proDisUrl = $proUrl."/".$proList[$i][0]."/";
            $strList .= "<dd><ul>
                    <li><a href=\"".$proDisUrl."\" title=\"".$proList[$i][3]."\"><img src=\"".$this->getProPic($proList[$i][0])."\" alt=\"".$proList[$i][1]."\" /></a></li>
                    <li><a href=\"".$proDisUrl."\" title=\"".$proList[$i][3]."\">".$this->strInc->cut_str($proList[$i][1],19,'')."</a></li>
                    <li>￥".$proList[$i][2]."元</li>
                    <li><a href=\"".$proDisUrl."\" title=\"买".$proList[$i][1]."\">立即购买</a></li>
                    </ul></dd> ";   		                    
            //如果够一列 或到结尾 则生成
            if( ($i+1) % $displayNum == 0 || $i == $totle-1)
            {
                $urlNew = ($page=1) ? ($urlTo = $urlTo.'/index.html') : ($urlTo = $urlTo.'/index'.$page.'.html');
                               
                $str = str_replace("{%class_title%}", $rsArr['pc_title'], $str);//类别名
                $str = str_replace("{%pro_list%}", $strList, $str);   //列表
                $str = str_replace("{%page%}", $pageStr, $str);         //分页
				//头部信息
				$str=str_replace("{%keyword%}", $rsArr['pc_keyworld'], $str);
				$str=str_replace("{%discription%}", $rsArr['pc_discription'], $str);
				$str=str_replace("{%title%}", $rsArr['pc_ptitle'].$this->configDeptRow['websitename'], $str);
				//左边新闻
				//$str=str_replace("{%ind_news_55%}", $this->indNews(18, 12, 6), $str);  //人参专题 18类,16长,6条
                //$str=str_replace("{%ind_news_53%}", $this->indNews(6, 12, 6), $str);   //美食常识

                $str=str_replace("{%ind_news_55%}",$this->indNews(12, 12, 5), $str);   //专题下 12类,16长,6条
                $str=str_replace("{%ind_news_53%}",$this->indNews(2, 12, 5), $str);   // 食谱
                //print_r($rsArr); exit();
                $str = str_replace("{%left_pro%}", $this->leftPro($rsArr['pc_id'], 12, 5), $str); //左边相关商品


				//生成公共内容
				$str = $this->plusHead($str);        

                $newPage = fopen($urlNew, "w");			    //创建的方式打开新的页面
                fwrite($newPage,$str);						//写入新页面
                fclose($newPage);							//关闭指针 
                $message .= "<br/><b>{$bigArr['pc_title']}</b> -> <b>{$rsArr['pc_title']}</b> ->栏目生成完毕! 生成路径".$urlNew;
                $strList = '';
                ++$page;
                $urlNew = '';         
            }        
        }
        return $message;
    }

    /* 生成大类
     * $rsArr : 当前类生成数组
     * */
    private function proChannel($rsArr)
    {

        $urlTpl = $this->htmUrl."/libs/templates/pro_ind.htm";          //模板路径

        $urlTo = $this->htmUrl.'/'.$rsArr['pc_page'];     //生成路径 ."/index.html"
        if(!$this->isDir($urlTo))
        {
            return '生成路径创建失败无法生成!';
        }
        $urlTo .= "/index.html";        
        $str = @ file_get_contents($urlTpl);            //获取模板代码
		//头部信息
        $str=str_replace("{%keyword%}", $rsArr['pc_keyworld'], $str);
        $str=str_replace("{%discription%}", $rsArr['pc_discription'], $str);
        $str=str_replace("{%title%}", $rsArr['pc_ptitle'].$this->configDeptRow['websitename'], $str);
        //面包导航
        $break ="当前位置：　<a href=\"".$this->configDeptRow['website_url']."/\">首页</a>　»　<a href=\"".$this->configDeptRow['website_url'].'/'.$rsArr['pc_page']."/\">".$rsArr['pc_title']."</a>";
		
        $str=str_replace("{%break%}", $break, $str);
        //产品分类列表
        $str=str_replace("{%pro_list%}", $this->proChannelBig($rsArr), $str); 
        //左边新闻
        
        $str=str_replace("{%ind_news_55%}",$this->indNews(12, 12, 5), $str);   //专题下 12类,16长,6条
        $str=str_replace("{%ind_news_53%}",$this->indNews(2, 12, 5), $str);   // 食谱
    
        $str = str_replace("{%left_pro%}", $this->leftPro($rsArr['pc_id'], 12, 5), $str); //左边相关商品


		//生成公共内容
        $str = $this->plusHead($str);     

        $newPage=fopen($urlTo, "w");			    //创建的方式打开新的页面
        fwrite($newPage, $str);						//写入新页面
        fclose($newPage);							//关闭指针

        return "<b>".$rsArr['pc_title']."</b>栏目生成完毕,生成路径:".$urlTo; 

    }



    /* 获取类别中相关信息
     * $condition,  where a=b
     * $column='*'  字段
     * */
    public function proClassArr($condition, $column='*')
    {
        $query = $this->db->select("`{$this->proClassTable}`",$column, $condition);
        $result = $this->db->fetch_array($query);
        $this->db->free();
        return $result;

    }

    /* 产品栏目生成函数 
     * $id : 当前类别id
     * */
    public function main($id)
    {   
        $rsArr = $this->proClassArr("pc_id=".$id);

        if($rsArr['pc_bid'] == 0)
        {
            $str = $this->proChannel($rsArr);
            return $str;           
        }
        else
        {
            $str = $this->proList($rsArr);
            return $str;
        }
    
    }


    function __toString(){}
    function __isset($var){ }
    function __unset($c)
	{
    	unset ($this->$c); 
    }
    function __destruct(){  }

}


?>

