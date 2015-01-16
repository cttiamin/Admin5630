<?php
/*
 * 静态页面生成
 * Last Change: 2011-12-29 12:23
 * Maintainer: etcphp@sohu.com
*/
require_once('global.php');

$pageurl = $_SERVER['PHP_SELF']."?a=".$a."&m=".$m;//地址
$strInc = new strInc();

//提交获取
if(!empty($_POST))
{
    //生成类别Id或内容id
    $cid = isset($_POST['c']) ? $strInc->inject_check($_POST['c']) : false;
    //定义生成模块
    $mode = isset($_POST['m']) ? $strInc->inject_check($_POST['m']) : false;
}
else
{
	$cid = '';
	$mode = '';
}

/*产品下拉列表 
$proChannel = new proAction();                           //实例化
$proChannelList = $proChannel->proChannelSelectIn(0);    //栏目列表
unset($proChannel->class_arr);                      //清空
unset($proChannel->str);
 */

/*新闻下拉列表*/
$arcChannel = new arcAction();                           //实例化
$arcChannelList = $arcChannel->arcChannerSelectIn(0);    //栏目列表
unset($arcChannel->class_arr);                      //清空
unset($arcChannel->str);


/*首页生成*/
if($mode == "index")
{   
    if( $cid == 0 ){
        $htm = new htmIndexAction();
        $message = $htm->index();  //输出信息
        unset($htm);
    }
    // 1 疯狂抢购
    // 2 新品上架
    // 3 折扣
    else
    {
        $htm = new htmIndexAction();
        $message = $htm->main($cid);  //输出信息
        unset($htm);
    }  
}

/*产品栏目生成*/
if($mode == "proChannel")
{
    $htm = new htmProChannelAction();
    $message = $htm->main($cid);     //输出信息
    unset($htm);
}

/*产品内容生成*/
if($mode=="proDisplay")
{
    $htm = new htmProDisplayAction();
    $message = $htm->main($cid);     //输出信息
    unset($htm);
}

/*文章栏目生成*/
if($mode=="articleChannel")
{
    $htm = new htmArcChannelAction();
    $message = $htm->main($cid);     //输出信息
    unset($htm);
}

/*文章内容生成*/
if($mode=="article")
{
    $htm = new htmArcArticleAction();
    $message = $htm->main($cid);     //输出信息
    unset($htm); 
}
require_once(JMADMINTPL.'/htmIndex.htm');

?>
