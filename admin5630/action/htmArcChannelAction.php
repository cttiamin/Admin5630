<?php
/** 
 * Last Change: 2011-12-29 15:39
 * Maintainer: etcphp@sohu.com
 **/
 
class htmArcChannelAction extends htmIndexAction
{

    function __construct()
    {
         parent::__construct();
    }

    /* 文章小类列表数组获取(小类标题)
     * $rsArr,  小类
     * $bigArr  大类
     * */
    public function arcChannelSmall($rsArr)
    {
        //小类列表查询       
        $query = $this->db->select("`$this->miscellBaseTable`, `miscell_content`",'miscell_base.`a_id`, miscell_base.`a_title`, miscell_base.`uptime`, miscell_base.`link_title`, miscell_base.`logo`, miscell_base.`arctype`, miscell_base.`kehutype`, miscell_content.`discription`', "miscell_content.nid = miscell_base.a_id and a_state =1 and a_del=0 and  class_id=".$rsArr['class_code']." order by uptime desc");
        $proList = '';
        while($result = $this->db->fetch_array($query))
        {
            $proList[] = array($result[0], $result[1], $result[2], $result[3], $result[4],  $result[5],$result[6], $result[7]) ;           
        }
        return $proList;
    }

    /*
     * 新闻大类列表生成 
     * $rsArr : 大类返回数组
     *
     * */
    public function arcChannelBig($rsArr)
    {
        //小类查询,压到数组里,       
        $query = $this->db->select("`$this->miscellClassTable`",'`class_code`, `b_id`, `class_title`, `webpage`', "b_id=".$rsArr['class_code']);
        while($result = $this->db->fetch_array($query))
        {
            $smallQuery[] = array($result[0], $result[1], $result[2], $result[3]) ;           
        }
		//列表字符
        $this->str = '';
        //if(empty($smallQuery))	return '大类下没有小类，请重新选择！';
        //小类循环                
        for($i=0; $i<count( $smallQuery ); $i++ )
        {   			
            //小类列表链接
            $smallUrl = $this->configDeptRow['website_url']."/".$rsArr['webpage']."/".$smallQuery[$i][3]."/";
            //小类列表标题
            //$this->str .= "<li class=\"mb10\"> <h2>".$smallQuery[$i][2]."<a href=\"".$smallUrl."\" title=\"".$smallQuery[$i][2]."\">更多</a></h2><ul>";
            //新闻标题查询
            //$arcDisQuery = mysql_query("select `a_id`, `a_title`, `uptime`,`link_title` from `{$this->miscellBaseTable}` where class_id = ".$smallQuery[$i][0]." and `a_state` = 1 and a_del=0 order by uptime desc limit 6");           
            /*进入新闻内容循环
            while( $arcDisRow = mysql_fetch_array($arcDisQuery) )
            {
                $arcDisUrl = $smallUrl.$arcDisRow['a_id']."/"; //新闻内容路径
                $this->str .= "
                    <li><a href=\"".$arcDisUrl."\" target=\"_blank\" title=\"".$arcDisRow['link_title']."\">".$this->strInc->cut_str($arcDisRow['a_title'], 39, '...')."</a><span>".date('Y-m-d', $arcDisRow['uptime'])."</span></li>";

            }
            $this->str .= "</ul></li><div style=\"clear:both; height:20px;\"></div>";
			*/
            $rsSmallClass = $this->arcClassArr('class_code = '.$smallQuery[$i][0], '*');
			$this->arcList( $rsSmallClass );
			//unset($this->arcList);
			
			//print_r( $this->str );
			//exit();
			
        }
        return $this->str;     
    }

    /* 生成小类列表
     * $rsArr : 当前类生成数组
     * */
    private function arcList($rsArr)
    {
        //大类查询
        $bigArr = $this->arcClassArr('class_code = '.$rsArr['b_id'], '*');
        if(1 == $bigArr['class_code']){
            $urlTpl = $this->htmUrl."/libs/templates/news_list.htm";     //模板路径
        }else{
            $urlTpl = $this->htmUrl."/libs/templates/pro_list.htm";     //模板路径
        }

        //获取上级大类目录
        $urlTo = $this->htmUrl.'/'.$bigArr['webpage'].'/'.$rsArr['webpage']; 
        //页面链接
        $arcUrl = $this->configDeptRow['website_url'].'/'.$bigArr['webpage'].'/'.$rsArr['webpage'];
		//目录不存在则创建
        if(!$this->isDir($urlTo)){
            return '生成路径创健失败无法生成!';
        }
		//获取模板代码
        $strTpl = @ file_get_contents($urlTpl);            
        //面包导航
        //$break ="当前位置：　<a href=\"".$this->configDeptRow['website_url']."/\">首页</a>　»　<a href=\"".$this->configDeptRow['website_url'].'/'.$bigArr['webpage']."/\">".$bigArr['class_title']."</a>　»　<a href=\"".$arcUrl."/\">".$rsArr['class_title']."</a>";
        $break ="<a href=\"".$arcUrl."/\">".$rsArr['class_title']."</a>";

        $strTpl = str_replace("{%break%}", $break, $strTpl);    
        $strTpl = str_replace("{%left_list%}", $this->nav(14), $strTpl);//左边列表

        //产品分类内容列表数据获取 (小类)
        $arcList = $this->arcChannelSmall($rsArr);
        if(empty($arcList))
        {
            return '当前类别内容为空!';
            exit();
        }
        if(1 == $bigArr['class_code']){
            $displayNum = 32; //每页*条    
        }else{
             $displayNum = 8;
        }            
        $totle = count($arcList); //总条数
        $lastPage = ceil($totle/$displayNum); //总页 
        $strList = '';	//返回字符串
        $page = isset($page) ? $page : 1;		//当前页 
		$message = '';  
	
        //分类按页数循环,
        for($i=0; $i<count($arcList); $i++)
        {   
            //小类列表链接
            $arcDisUrl = $arcUrl."/".$arcList[$i][0]."/";
            //递归生成目录
            $this->isDir($arcDisUrl);
            //字符添加每列标题
            if(1 == $bigArr['class_code']){
                $strList .="<li><a href=\"".$arcDisUrl."\" target=\"_blank\" title=\"".$arcList[$i][3]."\">".$this->strInc->cut_str( $arcList[$i][1], 43, '...')."</a><span>".date('Y-m-d', $arcList[$i][2] )."</span></li> ";
            }else{
                if('' == $arcList[$i][4] ){ $arcList[$i][4] = '/style/img/01.jpg'; }
                $strList .='<div style="height:250px"><div class="pro_cont_img"><img src="'.$arcList[$i][4].'"></div><div class="pro_cont_txt"><h2>'.$arcList[$i][1].'</h2><ul style="min-height:30px;"><li><span>项目类型：</span>'.$arcList[$i][5].'</li><li><span>服务客户：</span>'.$arcList[$i][6].'</li><li><a href="'.$arcDisUrl.'" target="_blank">'.$this->strInc->cut_str($arcList[$i][7], 50, "...").'</a></li><li class="pro_btn"><a href="'.$arcDisUrl.'" target="_blank"><img src="/style/img/newsimg/pro_btn.png"></a></li></ul></div></div> ';               
            }

            //如果够一列 或到结尾 则生成
            if( ($i+1) % $displayNum == 0 || $i == $totle-1)
            {
				//echo $page.'-'.$i."<br>";
                $urlNew =  ($page == 1) ? $urlTo.'/index.html' : $urlTo.'/index'.$page.'.html';   
				//替换
				$str = $strTpl;           
                //$str = str_replace("{%class_title%}", $rsArr['class_title'], $str);		//类别名
                $str = str_replace("{%arc_list%}", $strList, $str);   					//列表
				//(当前页数, 总页,链接当前目录 )
				$pageStr = $this->pageNav($page, $lastPage, $arcUrl);   				//生成翻页导航    
                $str = str_replace("{%page%}", $pageStr, $str);         				//分页导航
				//左边新闻
			   	//$str=str_replace("{%ind_news_55%}", $this->indNews($bigArr['class_code'], 12, 10), $str);   //热门排行 18类,16长,6条
				//$str=str_replace("{%ind_news_53%}", $this->indNews(6, 12, 6), $str);   	//美食常识
				//生成公共内容
				$str = $this->plusHead($str, $rsArr); 

                $newPage = fopen($urlNew, "w");			    //创建的方式打开新的页面
                fwrite($newPage, $str);						//写入新页面
                fclose($newPage);							//关闭指针 
				
                $message .= " <br/><b>{$bigArr['class_title']}</b> <b>{$rsArr['class_title']}</b> -> 栏目生成完毕! 生成路径".$urlNew;
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
    private function arcChannel($rsArr)
    {
        $urlTpl = $this->htmUrl."/libs/templates/news_ind.htm";          //模板路径
        $urlTo = $this->htmUrl.'/'.$rsArr['webpage'];     //生成路径 ."/index.html"
        if(!$this->isDir($urlTo))
        {
            return '生成路径创健失败无法生成!';
        }

        $urlTo .= "/index.html"; 

        $this->arcChannelBig($rsArr);

        //$str = @ file_get_contents($urlTpl);            //获取模板代码

        //面包导航
        //$break ="当前位置：　<a href=\"".$this->configDeptRow['website_url']."/\">首页</a>　»　<a href=\"".$this->configDeptRow['website_url'].'/'.$rsArr['webpage']."/\">".$rsArr['class_title']."</a>";
        //$str=str_replace("{%break%}", $break, $str);
		
        //产品分类列表
        //$str=str_replace("{%news_list%}", $this->arcChannelBig($rsArr), $str);
        //左边新闻
		//$str=str_replace("{%ind_news_55%}", $this->indNews($rsArr['class_code'], 12, 30), $str);   //人参专题 18类,16长,6条
        //$str=str_replace("{%ind_news_53%}", $this->indNews(6, 12, 6), $str);   //美食常识
		//生成公共内容
        //$str = $this->plusHead($str, $rsArr);        

        //$newPage=fopen($urlTo,"w");			        //创建的方式打开新的页面
        //fwrite($newPage,$str);						//写入新页面 
        //fclose($newPage);							//关闭指针

        return "<b>".$rsArr['class_title']."</b> -> 栏目生成完毕,生成路径:".$urlTo;

    }

    /* 获取类别中相关信息
     * $condition,  where a=b
     * $column='*'  字段
     * */
    public function arcClassArr($condition, $column='*')
    {
        $query = $this->db->select("`{$this->miscellClassTable}`", $column, $condition);
        $result = $this->db->fetch_array($query);
        //$this->db->free();
        return $result;

    }

    /* 文章栏目生成函数 
     * $id : 当前类别id
     * */
    public function main($id)
    {   
        $rsArr = $this->arcClassArr("class_code=".$id);

        if($rsArr['b_id'] == 0)
        {
            $str = $this->arcChannel($rsArr);
            return $str;
        }else{
            //$str = $this->arcList($rsArr);
            //return $str;
            return "请重新选择";
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

