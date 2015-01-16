<?php
/**
 * 后台通用翻页通用类
 * LastChange: 2011-12-27
 * Maintainer: etcphp@sohu.com
 **/
class pageAd
{
    private $page;          //当前
    private $firstCount;    //从几
    private $pageNav;       //字符
    private $totle;         //总数
    private $displayNum=10; //每页
    private $url;           //路径
    private $lastPage;      //总页
    private $prePage;       //上页
    private $nextPage;      //下页
    private $limit=array(); //条件

    function __construct($totleGet, $displayNumGet, $urlGet)
    {
        $this->totle = intval($totleGet);
        $this->displayNum = intval($displayNumGet);
        $this->url = $urlGet;
        $this->pageNav = '';
        $this->urlCheck();
    }
    /*路径*/
    private function urlCheck()
    {
        if( empty( $_GET['p'] ) )
        {
            $this->page = 1;
        }else{
            $this->page = intval( $_GET['p'] );
        }

        if(empty($this->url))$this->url = $_SERVER["REQUEST_URI"];

        $parse_url=parse_url($this->url);
        $url_query = @$parse_url["query"]; //取出Get字符
        if($url_query)
        {
            $url_query=preg_replace("/(^|&)p=$this->page/i","",$url_query); //滤掉p
            $this->url=str_replace($parse_url["query"],$url_query,$this->url); //在URL后加p

            if($url_query)
            {$this->url.="&amp;p"; }
            else
            {$this->url.="p";}
        }else {
            $this->url.="?p";
        }

    }
    /*条件*/
    function pageAdLimit()
    {
		//如果总记录为空
		if($this->totle == 0)
		{
        	$this->limit[0] = 0;
        	$this->limit[1] = 1;
			return $this->limit;
		}
		
        $this->lastPage = ceil($this->totle/$this->displayNum); //最后页
        $this->page = min($this->lastPage,$this->page);//取最小
        $this->prePage = $this->page-1; //上一页
        $this->nextPage = ($this->page == $this->lastPage ? 0 : $this->page+1); //下一页
        $this->firstCount = ($this->page-1)*$this->displayNum; //从几
        $this->limit[0] = $this->firstCount;
        $this->limit[1] = $this->displayNum;
		
        return $this->limit; 

    }
    /*字符串*/
    function pageAdStr(){

        $this->pageNav .= "<div class=\"pagination\">";
        $this->pageNav .= "共{$this->totle}条，第".$this->firstCount." －".min($this->firstCount+$this->displayNum,$this->totle)."条，";
        $this->pageNav .="<a href=\"{$this->url}=1\" title=\"First Page\">&laquo;首页</a>";
        if($this->prePage)
        {
            $this->pageNav .="<a href=\"{$this->url}={$this->prePage}\" title=\"Previous Page\">&laquo; 上页</a>";
        }
        else
        {$this->pageNav .="<a href=\"#\" title=\"Previous Page\">&laquo; 上页</a>";}

        if($this->page<=2){
            $forin = 1 ;$forto = 4;
        }
        elseif($this->page+2>=$this->lastPage)
        {
            $forin = $this->page-2 ;$forto = $this->lastPage;
        }
        else
        {
            $forin = $this->page-2; $forto = $this->page+2;
        }

        for($i=$forin; $i<=$forto; $i++){
            $this->pageNav .="<a href=\"{$this->url}={$i}\" class=\"number ";
            if($this->page==$i)$this->pageNav .="current\"";
            $this->pageNav .=" title=\"{$i}\" >{$i}</a>";
            if($i==$this->lastPage)break;
        }

        if($this->nextPage)
        {$this->pageNav .= "<a href=\"{$this->url}={$this->nextPage}\" title=\"Next Page\">下页&raquo;</a>";}
        else
        {$this->pageNav .= "<a href=\"#\" title=\"Next Page\">下页&raquo;</a>";}

        $this->pageNav .= "<a href=\"{$this->url}={$this->lastPage}\" title=\"Last Page\">尾页&raquo;</a>";             
        $this->pageNav .= "</div>";
        return $this->pageNav;
    }
    //释放
    function pageClear(){        
        unset($this->page);
        unset($this->firstCount);
        unset($this->pageNav);
        unset($this->totle);
        unset($this->displayNum);
        unset($this->url);
        unset($this->lastPage);
        unset($this->prePage);
        unset($this->nextPage);
        unset($this->limit);
    }

}


?>


