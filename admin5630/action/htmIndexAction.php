<?php
/** 
 * 首页生成HTML
 * Last Change: 2011-12-26 15:39
 * Maintainer: etcphp@sohu.com
 **/
 
//define('ADARCACTION', ereg_replace("[/\\]{1,}", '/', dirname(__FILE__) ) ); 
//require_once(ADARCACTION.'/config.php');

class htmIndexAction extends Action
{
    protected $db;              //mysql连接
    protected $rs;              //资源
    protected $str;             //返回字符
    protected $arr;       //返回数组
    protected $configDeptRow;   //网站信息
    /* 数据表 */
    protected $proClassTable;   //产品类别
    protected $proBaseTable;    //产品主表
    protected $proContentTable; //产品副表
    protected $proPicTable;     //产品图片  
    protected $miscellClassTable;       //新闻类别
    protected $miscellBaseTable;        //新闻主表
    protected $miscellContentTable;     //新闻副表
    protected $configDeptTable;         //站内信息
    protected $tagTmpTable;             //标签表
    
    protected $strInc;  //字符处理类
    protected $htmUrl;  //首页路径
    

    function __construct()
    {
        $this->db = new mysqlDb();
        $this->strInc = new strInc();

        $this->str = '';
        $this->arr = array();
        $this->configDeptRow = '';

        /*数据库表*/
        $this->proClassTable = 'pro_class';
        $this->proBaseTable = 'pro_base';
        $this->proContentTable = 'pro_content';
        $this->proPicTable = 'pro_pic';
        $this->miscellClassTable = 'miscell_class';
        $this->miscellBaseTable = 'miscell_base';
        $this->miscellContentTable = 'miscell_content';
        $this->configDeptTable = 'config_dept';
        $this->tagTmpTable = 'tag_tmp';

        $this->htmUrl = JMADMIN."/..";
        
        $this->configDept();
    }

    /*获取网站系统设置信息	*/
    private function configDept()
    {
        $query=mysql_query("select * from config_dept");    
        while($row_arr=mysql_fetch_array($query))
        {
 	        $row_dept[$row_arr['name']]=$row_arr['values'];
        }
        $this->configDeptRow = $row_dept;
    }

    /* 模板标签,单个代码内容调用,
	   @id=1   
	   @cut=''
	*/
    public function nav($id=1, $cut='')
    { 
        $query = $this->db->select("`{$this->tagTmpTable}`",'`t_content`',"t_id={$id}");
        $str = $this->db->fetch_array($query);
        $this->db->free();  
        if(empty($cut))
        { 
            return $str['t_content']; 
        }else{

            return $this->strInc->cut_str( $str['t_content'],$cut,'...');
        }    
    }    

    /*  通用类别列表分页计算
     *  $page      当前页     
     *  $lastPage  最后页
     *  $urlTo     链接 
     * */
    public function pageNav($page, $lastPage, $urlTo)
    {
		$pageStr ='';
		if( $lastPage == 1 )	//只有一页
		{ 
            $pageStr = "<a href=\"".$urlTo."/index.html\" class=\"page_previous\" > &lt;&lt; </a> <a href=\"".$urlTo."/index.html\" class=\"page_now\" >1</a> <a href=\"".$urlTo."/index.html\" class=\"page_next\" > &gt;&gt; </a>";


		}else{	//多页
			//上一页,处理
			if( $page == 1 || $page ==2 ){ 
				$pageStr = "<a href=\"".$urlTo."/index.html\" class=\"page_previous\" > &lt;&lt; </a>";				//第一页,第二页 
			}else{
				$pageStr = "<a href=\"".$urlTo."/index".($page-1).".html\" class=\"page_previous\" > &lt;&lt; </a>";//上一页 
			}
			//大于5页
			if( $lastPage > 5){ 
				$startAdd = $page+2 > $lastPage ? ($page+2)-$lastPage : 0; //开始加多少?
				$start = $page > 2 ? $page-(2+$startAdd) : 1;	//开始		
				$stopAdd = $page > 2 ? 2 : 5-$page;	//当前页小于3情况下加多少?
				$stop = $page+2 <= $lastPage ? $page+$stopAdd : $lastPage; //结束
				for( $i = $start ; $i <= $stop; $i++){
					$num = $i==1 ? '' : $i;	//如果是第一页,index
					if( $i == $page){	//是否为当前页
						$pageStr .= "<a href=\"".$urlTo.'/index'.$num.".html\" class=\"page_now\" >".$i."</a>"; 
					}
					else {
						$pageStr .= "<a href=\"".$urlTo.'/index'.$num.".html\" >".$i."</a>"; 
					} 
				}
				if( $page+2 < $lastPage){	//最后页数
					$pageStr .= "<a href=\"".$urlTo.'/index'.$lastPage.".html\">...".$lastPage."</a>";
				}
			}
			//小于5页
			else{ 
				for( $i=0; $i < $lastPage; $i++){
					$num = ($i+1)==1 ? '' : $i+1;	//如果是第一页,index
					if( $i+1 == $page){
						$pageStr .= "<a href=\"".$urlTo.'/index'.$num.".html\" class=\"page_now\" >".($i+1)."</a>"; 
					}
					else{
						$pageStr .= "<a href=\"".$urlTo.'/index'.$num.".html\" >".($i+1)."</a>"; 
					}
				}
			}
			//下一页,
			if($page == $lastPage ){
				$pageStr .= "<a href=\"".$urlTo."/index".$lastPage.".html\"  class=\"page_next\" > &gt;&gt; </a>"; //最后一页
			}else{
				$pageStr .= "<a href=\"".$urlTo."/index".($page+1).".html\" class=\"page_next\" > &gt;&gt; </a>"; //下一页
			}
		}
        return $pageStr;
    }

    /*首页产品 图片路径获取
     *@id :产品ＩＤ
     * */
    public function getProPic($id, $type='picture2')
    {   
        $picture = isset($type) ? $type : 'picture2';

        $query = mysql_query("select `pp_url` from `$this->proPicTable` where p_id = {$id} and pp_id = '{$picture}'");
        $rs = mysql_fetch_array($query);

        return $rs['pp_url'];       
    }

	//函数名: compress_html   
	//参数: $string   
	//返回值: 压缩后的$string   
	public function compress_html($string) {  
		$string = str_replace("\r\n", '', $string); //清除换行符   
		$string = str_replace("\n", '', $string); //清除换行符   
		$string = str_replace("\t", '', $string); //清除制表符   
		$pattern = array (  
						"/> *([^ ]*) *</", //去掉注释标记   
						"/[\s]+/",  
						"/<!--[^!]*-->/",  
						"/\" /",  
						"/ \"/",  
						"'/\*[^*]*\*/'"  
						);  
		$replace = array (  
						">\\1<",  
						" ",  
						" ",  
						"\" ",  
						"\" ",  
						" "  
						);  
		return preg_replace($pattern, $replace, $string);  
	}   

    /*首页产品详情路径获取 
     * $pid : 类别ID
     * $id : 产品ID
     * */
    public function indProDir($pid, $id)
    {   
        //小类
        $small_query = mysql_query("select `pc_page`,`pc_bid` from pro_class where pc_id=".$pid);
        $small_rs = mysql_fetch_array($small_query);
        //大类
        $big_query=mysql_query("select `pc_page`,`pc_bid` from pro_class where pc_id=".$small_rs['pc_bid']);
        $big_rs = mysql_fetch_array($big_query);

        $str = $this->configDeptRow['website_url'].'/'.$big_rs['pc_page'].'/'.$small_rs['pc_page'].'/'.$id.'/';
        return $str;
    }
    
    /*
     * 获取文章列表链接
     * @cid 类别ID
     * @id  文章ID
     * */
    public function getArticleUrl($cid, $id)
    {
        //echo $cid; exit();
        $query = mysql_query("select `webpage`,`b_id` from `miscell_class` where class_code=".$cid);
        $rs = mysql_fetch_array($query);
        if( $rs['b_id'] != 0 )
        {
            //小类查大类
            $big_query = mysql_query("select `webpage` from miscell_class where class_code=".$rs['b_id']);
            $big_rs=mysql_fetch_array($big_query);
            $link = $this->configDeptRow['website_url']."/".$big_rs['webpage']."/".$rs['webpage']."/".$id."/";
        }
        else
        {
            //大类查小类
            exit("htmIndexAction 生成错误, line 219");
            $link = $this->configDeptRow['website_url']."/".$rs['webpage']."/".$id."/";
        }
        return $link;
    }
	
	/* 收索类别列表选项*/
	public function searchSelect()
	{
		$query = $this->db->select("`{$this->proClassTable}`",'`pc_id`,`pc_title`',"`pc_bid` != 0");
               
        $str = '';
        while($rs = $this->db->fetch_array($query))
        {
			$str .= "<option value=\"".$rs['pc_id']."\">".$rs['pc_title']."</option>";
        }
        $this->db->free();    
        return $str;  

	}

    /*
     * 公共部分生成
     * $str, 模板代码
     * $rsArr 当前数组
     * */
    public function plusHead($str, $rsArr='')
    {
        $str=str_replace("{%nav%}", $this->nav(2), $str);     //导航
        $str=str_replace("{%top_phone%}", $this->configDeptRow['phone'], $str);
        $str=str_replace("{%nav05%}", $this->nav(5), $str);
        

		//$str=str_replace("{%car%}",$this->nav(8), $str);     		//购物车
        //$str=str_replace("{%notice%}",$this->nav(4, 999), $str);   //商品分类
		
		//$str=str_replace("{%optionSelect%}", $this->searchSelect(), $str); //searchSelect选项代码
		//$str=str_replace("{%search_key%}", $this->nav(10, 999), $str); 	//热门收搜	
		/*关于我们, 购物指南
        $str=str_replace("{%ind_news_29%}", $this->indNews(29, 12, 3), $str);   //购物指南 29类,12长,3条
        $str=str_replace("{%ind_news_30%}", $this->indNews(30, 12, 3), $str);   //配送方式
		$str=str_replace("{%ind_news_31%}", $this->indNews(31, 12, 3), $str);   //支付方式
        $str=str_replace("{%ind_news_32%}", $this->indNews(32, 12, 3), $str);   //售后服务
        */
		/*备案说明*/
        $str=str_replace("{%ICP%}", $this->nav(7), $str);
        /*关键字*/
        if($rsArr !='' )
        {
			$str=str_replace("{%keyword%}", $rsArr['keyworld'], $str);
			$str=str_replace("{%discription%}", $rsArr['content'], $str);
			$str=str_replace("{%title%}", $rsArr['pagetitle'].$this->configDeptRow['websitename'], $str);	  
		} 
		/*去除HTML格式*/
		//$str = $this->compress_html($str);
        return $str;
    }


    /*	首页左边新闻(新闻列表通用)
		$id, 		类别ID
		$cut=0, 	字符串剪切
		$limit=6	保留几条
	*/
    public function indNews($id, $cut=0, $limit=6)
    {
        $queryClass = $this->db->select(" `{$this->miscellClassTable}` ", 'b_id', "`class_code` = {$id} ");
        $rsClass = $this->db->fetch_array($queryClass);
        if(0 == $rsClass['b_id'] ){
            /*如果是大类的ID*/
            $query = $this->db->select(
                "`{$this->miscellBaseTable}` left join `{$this->miscellClassTable}` on $this->miscellBaseTable.class_id = $this->miscellClassTable.class_code",
                "miscell_base.`a_id`,  miscell_base.`a_title`, miscell_base.`link_title`, miscell_base.`class_id`",
                "$this->miscellClassTable.b_id = {$id} and $this->miscellBaseTable.a_state =1 and $this->miscellBaseTable.a_del = 0 order by  $this->miscellBaseTable.flag desc, $this->miscellBaseTable.uptime desc limit {$limit}"
            ); 
        }else{
            /*如果是小类的ID*/
            $query = $this->db->select("`{$this->miscellBaseTable}`",'`a_id`,`a_title`,`link_title`, `class_id`',"`class_id`={$id} and `a_state`=1 and `a_del`=0 order by `uptime` desc limit {$limit}");
        
        }
        $str = '';
        while($rs = $this->db->fetch_array($query)){
            $str .= "<li><a href=\"".$this->getArticleUrl($rs['class_id'], $rs['a_id'])."\" target=\"_blank\" >";
            //是否字符限制
            if(isset($cut)){
                $str .=$this->strInc->cut_str($rs['a_title'], $cut,'...')."</a></li>";
            }else{
                $str .=$rs['a_title']."</a></li>";
            }          
        }
        $this->db->free();    
        return $str;  
    }

    public function indNewsVideo($id, $cut=0, $limit=6 , $flag = ''){
        $flagStr = '';  //按等级显示
        if($flag != '')
            $flagStr = "and $this->miscellBaseTable.flag = $flag";
        
        $queryClass = $this->db->select(" `{$this->miscellClassTable}` ", 'b_id', "`class_code` = {$id} ");
        $rsClass = $this->db->fetch_array($queryClass);
        if(0 == $rsClass['b_id'] ){
            /*如果是大类的ID*/
            $query = $this->db->select(
                "`{$this->miscellBaseTable}` left join `{$this->miscellClassTable}` on $this->miscellBaseTable.class_id = $this->miscellClassTable.class_code",
                "miscell_base.`a_id`,  miscell_base.`a_title`, miscell_base.`link_title`, miscell_base.`class_id` , miscell_base.`logo`",
                "$this->miscellClassTable.b_id = {$id} and $this->miscellBaseTable.a_state =1 and $this->miscellBaseTable.a_del = 0 $flagStr  order by $this->miscellBaseTable.flag desc, $this->miscellBaseTable.uptime desc limit {$limit}"
            ); 
        }else{
            /*如果是小类的ID*/
            $query = $this->db->select("`{$this->miscellBaseTable}`",'`a_id`,`a_title`,`link_title`, `class_id`, `logo`',"`class_id`={$id} and `a_state`=1 and `a_del`=0 $flagStr order by `uptime` desc limit {$limit}");
        }
        $str = '';
        while($rs = $this->db->fetch_array($query)){
            if('' == $rs['logo']){
                $rs['logo'] = "/style/img/page/page01.jpg";
            }
            $str .= "<li><a href=\"".$this->getArticleUrl($rs['class_id'], $rs['a_id'])."\" ><img src=\"".$rs['logo']."\" /></a><h4><a href=\"".$this->getArticleUrl($rs['class_id'], $rs['a_id'])."\" >";
            //是否字符限制
            if(isset($cut)){
                $str .=$this->strInc->cut_str($rs['a_title'], $cut,'...')."</a></h4></li>";
            }else{
                $str .=$rs['a_title']."</a></h4></li></h4>";
            }       
        }
        $this->db->free();    
        return $str;  

    }

    /* 商品内页
     * 左边显示
     * 相关商品
     * */
    public function leftPro($pid=1, $cut='', $limit=4)
    {
        $queryClass = $this->db->select(" `{$this->proClassTable}` ", 'pc_bid', "`pc_id` = {$pid} ");
        $rsClass = $this->db->fetch_array($queryClass);

        if( 0 == $rsClass['pc_bid'])
        {
            /*如果是大类的ID*/
            $query = $this->db->select(
                "`{$this->proBaseTable}` left join `{$this->proClassTable}` on $this->proBaseTable.pc_id = $this->proClassTable.pc_id",
                "$this->proBaseTable.`p_id`,  $this->proBaseTable.`p_title`, $this->proBaseTable.`p_url`, $this->proBaseTable.`pc_id`, $this->proBaseTable.`p_price`",
                "$this->proClassTable.pc_bid = {$pid} and $this->proBaseTable.p_state =1 and $this->proBaseTable.p_del = 0 order by  $this->proBaseTable.p_flag desc, $this->proBaseTable.p_uptime desc limit {$limit}"
            ); 
        }
        else
        {
            //如果是小类
            $query = $this->db->select("`{$this->proBaseTable}`",'`p_id`,`p_title`,`p_price`, `p_url`, `pc_id`',"`pc_id`={$pid} and `p_state`=1 and `p_del`=0 order by `p_uptime` desc limit {$limit}");
        }

       // print_r($this->db->fetch_array($query)); exit();
        $str = '';

        while($rs = $this->db->fetch_array($query))
        {
            //图片地址
            $pic = $this->getProPic($rs['p_id'], 'picture3');
            //产品链接
            $url = $this->indProDir($rs['pc_id'], $rs['p_id']);  
            //是否字符限制
            $ptitle = isset($cut) ? $this->strInc->cut_str($rs['p_title'], $cut, '.') : $rs['p_title'];            

            $str .='<p><a href="'.$url.'" title="'.$rs['p_url'].'" target="_blank"> 
                <img src="'.$pic.'" alt="'.$rs['p_url'].'"/></a>
                <a href="'.$url.'" title="'.$rs['p_url'].'" target="_blank">'.$ptitle.'</a><br/>￥'.$rs['p_price'].'元
                </p>';                                
        }

        $this->db->free();
        return $str;  
    }

    /*首页产品
     * 0:不推 1:精品,2:热销,3:当季
     * */
    private function indPro($flag=0, $cut='', $limit=4)
    {       
        //查询
        $queryPro = $this->db->select("`{$this->proBaseTable}`",'`p_id`,`pc_id`,`p_price`,`p_title`,`p_url`',"`p_flag`={$flag} and `p_state`=1 and `p_del`=0 order by `p_uptime` desc limit {$limit}");  
        
        $this->str = '';
        while($rs = $this->db->fetch_array($queryPro))
        {
            //图片地址
            $pic = $this->getProPic($rs['p_id']);
            //产品链接
            $url = $this->indProDir($rs['pc_id'], $rs['p_id']);            

            $this->str .="
            <dd><ul>
            <li><a href=\" {$url} \" title=\"{$rs['p_url']}\" ><img src=\"". $this->configDeptRow['website_url'] . "{$pic} \" alt=\"{$rs['p_url']}\" /></a></li>
            <li><a href=\" {$url} \" title=\"{$rs['p_url']}\"> {$rs['p_title']}</a></li> 
            <li>价格:<b>￥{$rs['p_price']} 元 </b></li>
            <li><a href=\" {$url} \" title=\"{$rs['p_url']}\" > 立即购买 </a></li>
            </ul></dd>
            ";
            unset($url);
        }
      	$this->db->free();
        return $this->str;
    }
    
    /*
     * 首面产品更多页面，返回名称,生成目录
     *　0:不推 1:疯狂抢购,2:疯狂抢购,3:疯狂抢购
     * */
    public function moreTitle($id)
    {
        switch($id)
        {
        case 1:
            $title = '疯狂抢购'; 
            $page = 'qianggou';
            break;
        case 2:
            $title = '新品上架';
            $page = 'xinpin';
            break;
        case 3:
            default:
                $title = '特价专区';
                $page = 'tejia';
        }
        $arr[0] = $title;
        $arr[1] = $page;
        return $arr;
    }

    //首页推荐  特价专区 新品上架 疯狂抢购
    public function main($id)
    {
        $moreTitle = $this->moreTitle($id);
        $title = $moreTitle[0];
        $page = $moreTitle[1];

        //模板路径
        $urlTpl = $this->htmUrl."/libs/templates/pro_list.htm";     
        //生成目录
        $urlTo = $this->htmUrl.'/mores'.'/'.$page.'/';
        //目录不存在则创建
        if(!$this->isDir($urlTo))   return '生成路径创健失败无法生成!';
        //获取模板代码
        $str = @ file_get_contents($urlTpl);
		
        //页面头部
        $str=str_replace("{%keyword%}",$this->configDeptRow['website_keyword'], $str);
        $str=str_replace("{%discription%}",$this->configDeptRow['website_discription'], $str);
        $str=str_replace("{%title%}",$this->configDeptRow['website_title'].$this->configDeptRow['websitename'], $str);
        
        //新闻
        $str=str_replace("{%ind_news_55%}", $this->indNews(18, 12, 6), $str);   //人参专题 18类,16长,6条
        $str=str_replace("{%ind_news_53%}", $this->indNews(6, 12, 6), $str);   //美食常识
        //去掉相关商品
        //$str = str_replace('<h2>相关商品</h2>{%left_pro%}', 'asdf', $str);
        $str = str_replace("{%left_pro%}", $this->leftPro(3, 12, 5), $str); //左边相关商品

        //面包导航
        $break ="<a href=\"".$this->configDeptRow['website_url']."/\">首页</a> > ";
        $str = str_replace("{%break%}", $break, $str);    

        //查询
        $sql = "select a.p_id, a.p_title, a.p_price, a.pc_id  
                from `pro_base` as a, pro_class as b 
                where a.pc_id = b.pc_id 
                and a.p_flag=".$id."
                and a.p_state=1 
                and a.p_del = 0
                order by a.p_uptime desc 
                limit 36";
        
        $query = mysql_query($sql);
        $this->str = '';
        while($rsPro = mysql_fetch_array($query))
        {
            if(empty($rsPro)) return '内容为空!';
            $proDisUrl = $this->indProDir($rsPro['pc_id'], $rsPro['p_id']); 

            $this->str .= "<dd><ul>
                <li><a href=\"".$proDisUrl."\" title=\"".$rsPro['p_title']."\" target=\"_blank\"><img src=\"".$this->getProPic($rsPro['p_id'])."\" alt=\"".$rsPro['p_title']."\" /></a></li>
                <li><a href=\"".$proDisUrl."\" title=\".".$rsPro['p_title'].".\" target=\"_blank\">".$this->strInc->cut_str($rsPro['p_title'],19,'')."</a></li>
                <li>￥".$rsPro['p_price']."元</li>
                <li><a href=\"".$proDisUrl."\" title=\"".$rsPro['p_title']."\" target=\"_blank\" >查看详情</a></li>
                </ul></dd> ";                       
        }
        
        $str = str_replace("{%class_title%}", $title, $str);//类别名
        $str = str_replace("{%pro_list%}", $this->str, $str);   //列表
        $str = str_replace("{%page%}", '', $str);         //分页
		/*生成公共部份*/
		$str = $this->plusHead($str);

        $urlTo .= 'index.html';
        $newPage=fopen($urlTo, "w");			        //创建的方式打开新的页面
        fwrite($newPage, $str);						//写入新页面
        fclose($newPage);							//关闭指针

        $message = $urlTo;
        return $message;                            
    }

    /* 生成首页方法,
	
	*/
    public function index()
    {
        $url = $this->htmUrl."/libs/templates/index.htm";   //模板路径
        $urlc = $this->htmUrl."/index.html";    //生成路径

        /*判断模板是否存在*/
        if(!file_exists($url))  
        {
            return '模板不存在或无法打开!';
            exit();
        }            
        //$fp=fopen($url,"r");	//打开模板
        $str = @ file_get_contents($url);   //获取模板代码      
       
        //页面头部      
        $str=str_replace("{%keyword%}",$this->configDeptRow['website_keyword'], $str);
        $str=str_replace("{%discription%}",$this->configDeptRow['website_discription'], $str);
        $str=str_replace("{%title%}",$this->configDeptRow['website_title'].$this->configDeptRow['websitename'], $str);
        
        $str=str_replace("{%index_banner%}", $this->nav(4), $str);   //首页广告	
        $str=str_replace("{%index_case_01%}", $this->nav(9), $str);  //首页4张
        $str=str_replace("{%index_case_02%}", $this->nav(8), $str);  //首页业务范围按钮
        $str=str_replace("{%index_case_03%}", $this->nav(10), $str);  //首页联系我们
        $str=str_replace("{%index_case_04%}", $this->indNewsVideo(2, 4, 10, 2), $str);  //首页视频(9类, 12长, 11条, 按推荐级别显示(0, 1, 2))
        $str=str_replace("{%ind_news_9%}",$this->indNews(9, 15, 11), $str);   //公司新闻列表  (9类, 12长, 11条)

        $str=str_replace("{%ind_news_11%}", $this->indNewsVideo(11, 4, 12), $str);   //合作  (9类, 12长, 11条)
        //$str=str_replace("{%ind_news_13%}", $this->indNewsVideo(13, 4, 20), $str);   
        $str=str_replace("{%index_case_06%}", $this->nav(6), $str);     //友链  (9类, 12长, 11条)
        $str=str_replace("{%index_video%}", $this->nav(13), $str);
        
        
        /*
        $str=str_replace("{%ind_news_101%}",$this->indNews(2, 12, 11), $str);   //食谱 2
        $str=str_replace("{%ind_news_53%}",$this->indNews(1, 12, 11), $str);   //资讯 1
         */

        /* 产品
         * 1:精品,2:热销,3:当季
         * 
        $str=str_replace("{%ind_pro_1%}",$this->indPro(1, 16, 8),$str);     //人参专题 1类, 16长, 8条
        $str=str_replace("{%ind_pro_2%}",$this->indPro(2, 16, 8),$str);
        $str=str_replace("{%ind_pro_3%}",$this->indPro(3, 16, 8),$str);*/
        /* 链接
        $str=str_replace("{%links%}", $this->nav(6), $str);*/
		/*生成公共部份*/
		$str = $this->plusHead($str);
		
        //fclose($fp);					//关闭模板文件
	    $cf=fopen($urlc,"w");		    //创建的方式打开新的页面
	    fwrite($cf,$str);			    //写入新页面
	    fclose($cf);				    //关闭
         
        return '首页面生成完毕, 生成路径'.$urlc;

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

