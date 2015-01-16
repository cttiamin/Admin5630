<?php
/* 文章列表
 * LastChange: 2011-12-27
 * Maintainer: etcphp@qq.com
 * */
require_once('global.php');

$arcArticle = new arcArticleAction();
$strInc = new strInc();                 //字符处理

/////////////////////////////////////////////////////
// $arcArticle->timeChange();
// exit();
/////////////////////////////////////////////////////

/*类别判断*/
if(isset($_GET['c'])){ 
    $c = $strInc->inject_check($_GET['c']); 
}else{
    $c=39;
    //默认为"最新动态"栏目
}


/* a:动作 
 * m:模型
 * c:类别
 * p:分页
 */

/* 是否加入关键字*/
if( isset( $_GET['s-text']) ){
    $articleTitle = $strInc->inject_check( $_GET['s-text'] );
}else{
    $articleTitle = '';
}

// 当前页数获取
if(isset($_GET['p'])){
    $p=$strInc->inject_check($_GET['p']);
}else{
    $p=1;
}

// current URL
$pageUrl = $_SERVER['PHP_SELF']. 
    '?a='.$a.'&amp;m='.$m.'&amp;c='.$c.'&amp;p='.$p.
    '&amp;s-text='.$articleTitle;
//"?a={$a}&amp;m={$m}&amp;c={$c}&amp;p={$p}&amp;s-text={$articleTitle}";	
// echo $pageUrl;


// Checking SQL
$strInc->inject_check($pageUrl);		


/* 类别选项(下拉列表)
 * select选项
 * */
$arcActicleSelectIn = $arcArticle->arcChannerSelectIn($c);
unset($arcArticle->class_arr);
unset($arcArticle->str);
// 释放指针


/*册除*/
if(!empty($_GET['tpldel'])){
    $delId = $strInc->inject_check($_GET['tpldel']);
    $arcArticle->arcArticleDel($delId, $pageUrl);
    $arcArticle->getMesssage($pageUrl,'删除文章操作已完毕！','success');
    //unset($arcArticle->getMesssage);
    exit();
}

/*批量删除*/
if(isset($_POST['articleId'])){
     $arrayId = $_POST['articleId'];
	 foreach($arrayId as $articleId){
         $arcArticle->arcArticleDel($articleId, $pageUrl);
     }
     $arcArticle->getMesssage($pageUrl,'删除文章操作已完毕！','success');
    unset($arcArticle->getMesssage);
    exit();
}

/**********************************************************
 * POST获取
 **/
if(!empty($_POST)){

    if(empty($addTitle)){
        $addTitle = $strInc->inject_check($_POST['title']);//标题
    }else{
        $arcArticle->getMesssage($pageUrl,'标题名称不能为空！','error');
        exit();
    } 
    
    if(isset($_POST['link_title'])){
        $linkTitle = $strInc->inject_check( $_POST['link_title'] ); 
        //友链地址
    }else{
        $linkTitle = '';
    }

    $addKeyworld = $strInc->inject_check($_POST['keyworld']);
    //关键字
    $addPageTitle = $strInc->inject_check($_POST['page-title']);
    //页面标题
    $addState = $strInc->inject_check($_POST['state']);
    //显示
    $addFlag = $strInc->inject_check($_POST['flag']);
    //推荐
    $addBid = $strInc->inject_check($_POST['bid']);
    //父类
    $addRemark = $strInc->inject_check($_POST['discription']);
    //描述
    $addContent = $strInc->inject_check($_POST['content']); 
    //内容 
    
    
    $logo = '';  //logo图片, 视频类型, 视频url, 项目类型
    $filetype = '';     
    $file = '';         
    $arctype = ''; 
    $kehutype = '';
    if(isset($_POST['logo'])) 
        $logo = $strInc->inject_check($_POST['logo']);

    if(isset($_POST['filetype']))
        $filetype = $strInc->inject_check($_POST['filetype']);

    if(isset($_POST['file']))
        $file = $strInc->inject_check($_POST['file']);

    if(isset($_POST['arctype'])) 
        $arctype = $strInc->inject_check($_POST['arctype']);

    if(isset($_POST['kehutype'])) 
        $kehutype = $strInc->inject_check($_POST['kehutype']);
}

/************************************************************
 * 添加
 **/
if(!empty($_POST['Add']))
{
    $addValue1 = "{$addBid},{$addState},'{$addTitle}',{$addFlag},'{$linkTitle}','{$addPageTitle}',".time().",".time().", '{$logo}', {$filetype}, '{$file}', '{$arctype}', '{$kehutype}'";
    $addColumnName1 = '`class_id`,`a_state`,`a_title`,`flag`,`link_title`,`page_title`,`createtime`,`uptime`, `logo`, `filetype`, `file`, `arctype`, `kehutype`';

    $lastId = $arcArticle->arcArticleAdd($addColumnName1,$addValue1);

    $addValue2 = "{$lastId},'{$addKeyworld}','{$addRemark}','{$addContent}'";
    $addColumnName2 = '`nid`,`keywrod`,`discription`,`content`';

    $arcArticle->arcArticleAddCon($addColumnName2, $addValue2, $pageUrl);

	if( $addState ==1 )
	{
		/*更新html
		$arcHtm = new htmArcArticleAction();
		$arcHtm->arcDisplayHtm( $lastId );*/
		/*更新上一篇
		$upId = $arcArticle->arcArticleLastId($lastId, $addBid);
		$arcHtm->arcDisplayHtm( $upId );*/
	}
	$arcArticle->getMesssage($pageUrl, '添加文章操作已执行！', 'success');

}

/*******************************************************
 * 修改
 **/
if(!empty($_POST['Edit']))
{
    $editId = $strInc->inject_check($_POST['editId']);
    if((int)$addBid==(int)$editId){
        $arcArticle->getMesssage($pageUrl,'父类选择错误！','error'); exit();
    }

    if( isset($_POST['ctime']) )
        $ctime = $strInc->inject_check($_POST['ctime']);//创建时间

    $modContent1="`class_id`={$addBid},`a_state`={$addState},`a_title`='{$addTitle}',`flag`={$addFlag},`link_title`='{$linkTitle}',`page_title`='{$addPageTitle}',`uptime`=".time()." ,`logo`='{$logo}', `filetype`={$filetype}, `file`='{$file}' ,`arctype`='{$arctype}', `kehutype`='{$kehutype}' ";
    $condition1="`a_id` = {$editId} ";

    $modContent2="`keywrod`='{$addKeyworld}',`discription`='{$addRemark}',`content`='{$addContent}'";
    $condition2="`nid` = {$editId} ";;

    $arcArticle->arcArticleUpdateBas($modContent1,$condition1);
    $arcArticle->arcArticleUpdateCon($modContent2,$condition2);
	if( $addState ==1 )
	{
		/*更新html
		$arcHtm = new htmArcArticleAction();
		$arcHtm->arcDisplayHtm( $editId ); */
		/*更新上一篇
		$upId = $arcArticle->arcArticleLastId($editId, $addBid);
		$arcHtm->arcDisplayHtm( $upId ); */
	}
	/*弹出消息*/
    $arcArticle->getMesssage($pageUrl, '修改完毕！', 'success');
    unset($arcArticle->getMesssage); exit();
}


//获取查询
if(!empty($_GET['tplEdit']))
{  
    $editId = $strInc->inject_check( $_GET['tplEdit'] );
    $editQuery = $arcArticle->arcArticleEdit($editId);

    //获取类信息
    $classArray = $arcArticle->arcChannerEdit($c);
    $authorityArray = explode('.', $classArray['is_qx'], 6); 	//权限

    //引入修改模板
    require_once(JMADMINTPL.'/arcArticleEdit.htm');
}
else
{
    
    $classArray = $arcArticle->arcChannerEdit($c); //获取类信息
    $authorityArray = explode('.', $classArray['is_qx'], 6);//权限
	/*内容部分*/
    $arcArticleNum = $arcArticle->arcArticleSum($c, $articleTitle);//总条数

    if ( $arcArticleNum == 0 )  // 文章内容为空
    {   
        $pageNav = '';
        $pageAdStr = '';
        $arcArticleList = '';
    } 
    else        
    {
        //分页实例
	    $pageNav = new pageAd($arcArticleNum, 20, $pageUrl);
	    //limit条件,从第几条显示多少条
	    $pageAdLimit = $pageNav->pageAdLimit();
	    //文章列表
	    $arcArticleList = $arcArticle->arcArticleList($c, $pageAdLimit[0], $pageAdLimit[1], $articleTitle);
	    //unset($arcArticle->arcArr);         //清空文章对象
	    $pageAdStr = $pageNav->pageAdStr(); //翻页导航字符
	    $pageNav->pageClear();              //释放分页对象    
    } 
	
    require_once(JMADMINTPL.'/arcArticle.htm');
    //引入模板
}
?>


