<?php
/**
 *  新闻文章生成
 *  Last Change: 2011-12-29 18:36
 *  Maintainer: etcphp@sohu.com
 **/
class htmArcArticleAction extends htmArcChannelAction
{   
    function __construct()
    {
        parent::__construct();
    }
	
	/*
	生成上一篇: @arr:类别, @arcUrl:路径,@文章时间 , 
	*/
	public function arcDisplayPre($arr, $arcUrl, $time){
		$query = $this->db->select('miscell_base', 'a_id,a_title', "class_id=".$arr['class_code']." and uptime < {$time} and a_state=1 and a_del=0 order by uptime desc limit 1");
		$rs = @$this->db->fetch_array($query);
		if( empty($rs) ){
            //$str = '<a href="'.$arcUrl.'/" title="">'.$arr['class_title'].'</a>'; //&lt;&lt;
			$str = '没有了';
		}else{
			$str = '<a href="'.$arcUrl.'/'.$rs['a_id'].'/" title="'.$rs['a_title'].'">'.$this->strInc->cut_str($rs['a_title'], 18, '').'</a>';
		}
		return $str;
	}
	/*
	生成下一篇: @arr:类别, $arcUrl: n
	*/
	public function arcDisplayNext($arr, $arcUrl, $time){
		$query = $this->db->select('miscell_base', 'a_id,a_title', "class_id=".$arr['class_code']." and uptime > {$time} and a_state=1 and a_del=0  order by uptime asc limit 1");
		$rs = @$this->db->fetch_array($query);
		if( empty($rs) ){
            //$str = '<a href="'.$arcUrl.'/" title="">'.$arr['class_title'].'</a>';
            $str = '没有了'; //&gt;&gt;
		}else{
			$str = '<a href="'.$arcUrl.'/'.$rs['a_id'].'/" title="'.$rs['a_title'].'">'.$this->strInc->cut_str($rs['a_title'], 18, '').'</a>';
		}
		return $str;
	}

    /*修改或添加生成一篇文章*/
	public function arcDisplayHtm($id)
	{
		$id = intval($id);
		/*文章查询*/
		$query = $this->db->select('miscell_base, miscell_content', '*', "miscell_content.nid = miscell_base.a_id and miscell_base.a_id = $id");
		$arcRs = $this->db->fetch_array($query);//文章
		$smallRs = $this->arcClassArr( "class_code=".$arcRs['class_id'] );//小类
		$bigRs = $this->arcClassArr( "class_code=".$smallRs['b_id'] );//大类
	
		//产品内容模板路径
        $urlTpl = $this->htmUrl."/libs/templates/news_dis.htm";   
		//获取上级大类目录
		$urlTo = $this->htmUrl.'/'.$bigRs['webpage'].'/'.$smallRs['webpage'].'/'.$arcRs['a_id'];   
        //当前小类链接路径
        $arcUrl = $this->configDeptRow['website_url'].'/'.$bigRs['webpage'].'/'.$smallRs['webpage'];
		
        //目录不存在则创建
        if(!$this->isDir( $urlTo ))   
        {
            echo '生成路径创健失败无法生成!'; exit();
        }        
        //获取模板代码
        $str = @ file_get_contents($urlTpl);
		     
        //面包屑导航
        $break ="当前位置：　<a href=\"".$this->configDeptRow['website_url']."/\">首页</a>　»　<a href=\"".$this->configDeptRow['website_url'].'/'.$bigRs['webpage']."/\">".$bigRs['class_title']."</a>　»　<a href=\"".$arcUrl."/\">".$smallRs['class_title']."</a>　»　".$arcRs['a_title'];
        $str = str_replace("{%break%}", $break, $str); 
		//创建当前文章路径
        $urlNew = $urlTo.'/index.html';
		$strNew = $str;
		
		$strNew = str_replace("{%Atitle%}", $arcRs['a_title'], $strNew);
		//更新时间
		$strNew = str_replace("{%Auptime%}", date('Y-m-d H:i:s',$arcRs['uptime']), $strNew);
		//来源
		//$strNew　= str_replace("{%Ainfo%}", $this->configDeptRow['company'], $strNew);
		//内容
		$strNew = str_replace("{%Acontent%}", $arcRs['content'], $strNew);
		//下一篇
		$strNew = str_replace("{%Anext%}", $this->arcDisplayNext($smallRs, $arcUrl, $arcRs['uptime'] ), $strNew);
		//上一篇
		$strNew = str_replace("{%Apre%}", $this->arcDisplayPre($smallRs, $arcUrl, $arcRs['uptime'] ), $strNew);
		$strNew = str_replace("{%keyword%}", $arcRs['keywrod'], $strNew); //关键字
		$strNew = str_replace("{%discription%}", $arcRs['discription'], $strNew);//描述
		$strNew = str_replace("{%title%}", $arcRs['page_title'].$this->configDeptRow['websitename'], $strNew);//标题

		//左边新闻
		$strNew=str_replace("{%ind_news_55%}", $this->indNews(18, 12, 6), $strNew);   //人参专题 18类,16长,6条
        $strNew=str_replace("{%ind_news_53%}", $this->indNews(6, 12, 6), $strNew);   //美食常识

		//生成公共内容
		$strNew = $this->plusHead( $strNew );        

        $newPage = fopen($urlNew, "w");			        //创建的方式打开新的页面
        fwrite($newPage, $strNew);						//写入新页面
        fclose($newPage);								//关闭指针
		return 1;
	}
    
    /* 当前小类下文章生成,
     *
     * */
    private function arcDisplaySmallClass($rsArr)
    {              
        //大类查询
        $bigArr = $this->arcClassArr('class_code = '.$rsArr['b_id'], '*');
        //产品内容模板路径
        $authorityArray = explode('.', $rsArr['is_qx'], 6); 	//权限
        if(1 == $authorityArray[5]){
        //if(1 == $bigArr['class_code']){
            $urlTpl = $this->htmUrl."/libs/templates/pro_dis.htm";     //模板路径
        }else{
            if( '' != $rsArr['template'] )
            {
                $urlTpl = $this->htmUrl."/libs/templates/".$rsArr['template']; 
            }
            else 
            {
                $urlTpl = $this->htmUrl."/libs/templates/news_dis.htm";  //模板路径
            }
            
           
        }
        //$urlTpl = $this->htmUrl."/libs/templates/news_dis.htm";     
        //获取上级大类目录
        $urlTo = $this->htmUrl.'/'.$bigArr['webpage'].'/'.$rsArr['webpage']; 
        //当前小类链接路径
        $arcUrl = $this->configDeptRow['website_url'].'/'.$bigArr['webpage'].'/'.$rsArr['webpage'];

        //目录不存在则创建
        if(!$this->isDir( $urlTo ))   
        {
            return '生成路径创健失败无法生成!';
        }        
        //获取模板代码
        $str = @ file_get_contents($urlTpl);
		//面包屑导航
        //$break ="当前位置：　<a href=\"".$this->configDeptRow['website_url']."/\">首页</a>　»　<a href=\"".$this->configDeptRow['website_url'].'/'.$bigArr['webpage']."/\">".$bigArr['class_title']."</a>　»　<a href=\"".$arcUrl."/\">".$rsArr['class_title']."</a>";
        $break = "<a href=\"".$arcUrl."/\">".$rsArr['class_title']."</a>";
        //查询
        $query = mysql_query("select * from `miscell_base` as a, miscell_content as b where a.a_id=b.nid and a.class_id=".$rsArr['class_code']." and a.a_state=1 and a_del=0 order by a.uptime desc");
        $message = '';
		$str = str_replace("{%left_list%}",  $this->nav(14), $str);
        //进入循环
        while($arcArr = mysql_fetch_array($query))
        {   
            if( empty($arcArr) )return '此类没有文章内容';
            //定义循环里的模板内容
            $strNew = $str;
            //创建当前文章路径
            $this->isDir($urlTo.'/'.$arcArr['a_id']);
            $urlNew = $urlTo.'/'.$arcArr['a_id'].'/index.html';
			//文章面包屑导航
			$breakArc = ''; 
			//$breakArc = $break.'　»　<strong>'.$arcArr['a_title'].'</strong>';
            $strNew = str_replace("{%break%}", $break, $strNew); 
            
            //文章标题
            $strInc  = new strInc();
            $strNew = str_replace("{%Atitle%}", $strInc->cut_str($arcArr['a_title'], 26, '...'), $strNew);
            $strNew = str_replace("{title}", $arcArr['a_title'], $strNew);
			//更新时间
			//$strNew = str_replace("{%Auptime%}", date('Y-m-d H:i:s', $arcArr['uptime']), $strNew);
			//来源
			//$strNew　= str_replace("{%Ainfo%}", $this->configDeptRow['company'], $strNew);
			//内容
			$strNew = str_replace("{%Acontent%}", $arcArr['content'], $strNew);
			//下一篇
			$strNew = str_replace("{%Anext%}", $this->arcDisplayNext($rsArr, $arcUrl, $arcArr['uptime'] ), $strNew);
			//上一篇
            $strNew = str_replace("{%Apre%}", $this->arcDisplayPre($rsArr, $arcUrl, $arcArr['uptime'] ), $strNew);
            			//下一篇
			$strNew = str_replace("{%Anext_b%}", $this->arcDisplayNext($rsArr, $arcUrl, $arcArr['uptime'] ), $strNew);
			//上一篇
            $strNew = str_replace("{%Apre_b%}", $this->arcDisplayPre($rsArr, $arcUrl, $arcArr['uptime'] ), $strNew);

            $strNew = str_replace("{%upTime%}", date('Y-m-d H:i', $arcArr['uptime']), $strNew);
            $authorityArray = explode('.', $rsArr['is_qx'], 6); 	//权限
            if(1 == $authorityArray[5]){
                if(0 == $arcArr['filetype']){
                    $swf = '<embed width="580" height="459" type="application/x-shockwave-flash" src="'.$arcArr['file'].'">';
                }else if (1 ==  $arcArr['filetype'] ){ //height="193"
                    $swf = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="580" height="459">
<param name="movie" value="/style/mp4/vcastr22.swf" />
<param name="quality" value="high" />
<param name="allowFullScreen" value="true" />
<param name="FlashVars" value="vcastr_file='.$arcArr['file'].'" />
    <embed src="/style/mp4/vcastr22.swf" allowfullscreen="true" flashvars="vcastr_file='.$arcArr['file'].'" quality="high" luginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="580" height="459">
    </embed>
</object>';
                }else{
                    $swf = '';
                }
                $strNew = str_replace("{%Aflash%}", $swf, $strNew); //关键字
            }
			$strNew = str_replace("{%keyword%}", $arcArr['keywrod'], $strNew); //关键字
			$strNew = str_replace("{%discription%}", $arcArr['discription'], $strNew);//描述
			$strNew = str_replace("{%title%}", $arcArr['page_title'].$this->configDeptRow['websitename'], $strNew);//标题

			//左边新闻
			//$strNew=str_replace("{%ind_news_55%}", $this->indNews($rsArr['class_code'], 12, 16), $strNew);//当前类,12长,16条
            //$strNew=str_replace("{%ind_news_53%}", $this->indNews(6, 12, 6), $strNew);   //美食常识

			//生成公共内容
			$strNew = $this->plusHead($strNew);        

            $newPage=fopen($urlNew, "w");			        //创建的方式打开新的页面
            fwrite($newPage, $strNew);						//写入新页面
            fclose($newPage);							//关闭指针

            //返回成功信息
            $message .="<br/><b>".$bigArr['class_title']."->".$rsArr['class_title']."->".$arcArr['a_title']."-> </b>生成页面为:/".$bigArr['webpage']."/".$rsArr['webpage']."/".$arcArr['a_id']."/index.html ";
            //清空目标路径
            $urlNew = '';
            $strNew = '';
        }

        return $message;
    }

    /* 文章栏目生成函数
     * $id : 当前类别id
     * */
    public function main($id)
    {   
        $rsArr = $this->arcClassArr( "class_code=".$id );

        if($rsArr['b_id'] == 0)
        {
            return '大类不能生成! 请重新选择.';
        }
        else
        {
            $str = $this->arcDisplaySmallClass($rsArr);
            return $str;
        }

    }

    function __toString(){}
        function __isset($var){ }
        function __unset($c){
            unset ($this->$c); 
        }
    function __destruct() {}

}


?>

