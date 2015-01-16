<?php
require_once('global.php');

$userOrder = new userOrderAction();
$strInc = new strInc();

/*类别判断*/
if(isset($_GET['c'])){$c=$strInc->inject_check($_GET['c']);}else{$c=4;}
/* a:动作  m:模型 c:级别 p:分页 */
 
if(isset($_GET['p'])){$p=$strInc->inject_check($_GET['p']);}else{ $p=1; }
$pageUrl = $_SERVER['PHP_SELF']."?a={$a}&amp;m={$m}&amp;c={$c}&amp;p={$p}";	//地址
$strInc->inject_check($pageUrl);		//检URL





/*POST获取*/
if(!empty($_POST)){

$arr = $strInc->userFormCheck($_POST);
if(isset($_POST['update'])) $userOrder->userOrderUpdate($arr);

$userOrder->getMesssage($pageUrl,'修改完毕！','success'); 
	
/*	if(empty($addTitle)){
        $addTitle = $strInc->inject_check($_POST['title']);//标题
    }else{
        $arcArticle->getMesssage($pageUrl,'标题名称不能为空！','error');
        exit();
    } 
    if(isset($_POST['author']))
    {
        $addAuthor = $strInc->inject_check($_POST['author']);//来源  
    }
    else
    {
        $addAuthor = '';
    }

    $addKeyworld = $strInc->inject_check($_POST['keyworld']);//关键字
    $addPageTitle = $strInc->inject_check($_POST['page-title']);//页面标题
    $addState = $strInc->inject_check($_POST['state']);//显示
    $addFlag = $strInc->inject_check($_POST['flag']);//推荐
    $addBid = $strInc->inject_check($_POST['bid']);//父类
    $addRemark = $strInc->inject_check($_POST['discription']);//描述
    $addContent = $strInc->inject_check($_POST['content']); //内容
*/

}
/*修改*/
if(!empty($_POST['update']) ){
/*    $editId = $strInc->inject_check($_POST['editId']);
    if((int)$addBid==(int)$editId){
        $arcArticle->getMesssage($pageUrl,'父类选择错误！','error'); exit();
    }
*/	
		
    //$ctime = $strInc->inject_check($_POST['ctime']);//创建时间
  // $modContent1="`class_id`={$addBid},`a_state`={$addState},`a_title`='{$addTitle}',`flag`={$addFlag},`author`='{$addAuthor}',`page_title`='{$addPageTitle}',`createtime`='{$ctime}'";
    //$condition1="`a_id` = {$editId} ";

    //$modContent2="`keywrod`='{$addKeyworld}',`discription`='{$addRemark}',`content`='{$addContent}'";
    //$condition2="`nid` = {$editId} ";;

/*    $arcArticle->arcArticleUpdateBas($modContent1,$condition1);
    $arcArticle->arcArticleUpdateCon($modContent2,$condition2);
    $arcArticle->getMesssage($pageUrl,'修改完毕！','success'); 
    unset($arcArticle->getMesssage); exit();
*/
}


/*册除
if(!empty($_GET['tpldel'])){
    $delId = $strInc->inject_check($_GET['tpldel']);
    $arcArticle->arcArticleDel($delId,$pageUrl);
    unset($arcArticle->getMesssage);
    exit();
}*/



/*内容部分*/
$userOrderNum = $userOrder->userOrderSum(); //总条数

//分页实例
$pageNav = new pageAd($userOrderNum, 20, $pageUrl);
//print_r($pageNav);
//limit条件,从第几条显示多少条
$pageAdLimit = $pageNav->pageAdLimit();
//文章列表
$userOrderArr = $userOrder->userOrderArr( $pageAdLimit[0], $pageAdLimit[1]);

$pageAdStr = $pageNav->pageAdStr(); //翻页导航字符
$pageNav->pageClear();              //释放分页对象


//获取查询
if( !empty($_GET['Edit']) ){ 
 	$id = intval($_GET['Edit']);
	$edit =  $userOrder->userOrderAll( $id );
	//print_r($edit);
    require_once(JMADMINTPL.'/userOrderEdit.htm');   
}
else if ( isset($_GET['alipay_send']) )
{
    print_r($_GET);
   

    require_once(JMADMINTPL.'/userOrderAlipay.htm');
}
else{
    require_once(JMADMINTPL.'/userOrder.htm');
}


?>
