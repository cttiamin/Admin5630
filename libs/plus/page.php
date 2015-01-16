<?php

function pageft($totle,$displaypg=20,$url=''){
    global $page,$firstcount,$pagenav,$_SERVER;//声明外部变量
    $GLOBALS["displaypg"]=$displaypg;
    if(!$page) $page=1;
    if(!$url){ $url=$_SERVER["REQUEST_URI"];}
    //URL分析：
    $parse_url=parse_url($url);
    $url_query=$parse_url["query"]; //单独取出URL的查询字串
    if($url_query){
        $url_query=preg_replace("/(^|&)page=$page/i","",$url_query);   //将处理后的URL的查询字串替换原来的URL的查询字串：
        $url=str_replace($parse_url["query"],$url_query,$url); //在URL后加page查询信息，但待赋值：      
        if($url_query){$url.="&page"; }else {$url.="page";}
    }else {
        $url.="?page";
    }

    $lastpg=ceil($totle/$displaypg); //最后页，也是总页数: 页码计算：
    $page=min($lastpg,$page);
    $prepg=$page-1; //上一页
    $nextpg=($page==$lastpg ? 0 : $page+1); //下一页
    $firstcount=($page-1)*$displaypg;

    //开始分页导航条代码：
    $pagenav="显示第 <B class=red>".($totle?($firstcount+1):0)."</B>-<B class=red>".min($firstcount+$displaypg,$totle)."</B> 条记录，共 <b class=red>$totle</b> 条记录";

    //如果只有一页则跳出函数：
    if($lastpg<=1) return false;

    $pagenav.=" <a href='$url=1' class=parent>首页</a> ";
    if($prepg) $pagenav.=" <a href='$url=$prepg' class='paren't>前页</a> "; else $pagenav.=" 前页 ";
    if($nextpg) $pagenav.=" <a href='$url=$nextpg' class='parent'>后页</a> "; else $pagenav.=" 后页 ";
    $pagenav.=" <a href='$url=$lastpg' class=parent>尾页</a> ";

    //下拉跳转列表，循环列出所有页码：
    $pagenav.="　到第 <select name='topage' size='1' onchange='window.location=\"$url=\"+this.value'>\n";
    for($i=1;$i<=$lastpg;$i++){
        if($i==$page) $pagenav.="<option value='$i' selected>$i</option>\n";
        else $pagenav.="<option value='$i'>$i</option>\n";
    }
    $pagenav.="</select> 页，共 $lastpg 页";
}

?>

