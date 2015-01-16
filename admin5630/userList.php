<?php
require_once('global.php');

$userList = new userListAction();
$strInc = new strInc();

/*类别判断*/
if(isset($_GET['c'])){$c=$strInc->inject_check($_GET['c']);}else{$c=4;}
/* a:动作  m:模型 c:级别 p:分页 */
 
if(isset($_GET['p'])){$p=$strInc->inject_check($_GET['p']);}else{$p=1;}
$pageUrl = $_SERVER['PHP_SELF']."?a={$a}&amp;m={$m}&amp;c={$c}&amp;p={$p}";	//地址
$strInc->inject_check($pageUrl);		//检SQL

/*内容部分*/
$userListNum = $userList->userListSum();//总条数
//分页实例
$pageNav = new pageAd($userListNum, 20, $pageUrl);
//limit条件,从第几条显示多少条
$pageAdLimit = $pageNav->pageAdLimit();
//文章列表
$userListArr = $userList->userListArr($pageAdLimit[0], $pageAdLimit[1]);
unset($userList->arcArr);         //清空文章对象
$pageAdStr = $pageNav->pageAdStr(); //翻页导航字符
$pageNav->pageClear();              //释放分页对象


/*POST获取*/
if(!empty($_POST))
{
    if( empty($_POST['name']) ){
        $userList->getMesssage($pageUrl, '姓名称不能为空！','error');
        exit();
    }else{
        $name = $strInc->inject_check($_POST['name']);//姓名
    } 

    $mail = isset($_POST['mail']) ? $strInc->inject_check($_POST['mail']) : false;
    $rname = isset($_POST['rename']) ? $strInc->inject_check($_POST['rename']) : false;
    $address = isset($_POST['address']) ? $strInc->inject_check($_POST['address']) : false;
    $tel = isset($_POST['tel']) ? $strInc->inject_check($_POST['tel']) : false;
    $phone = isset($_POST['phone']) ? $strInc->inject_check($_POST['phone']) : false;
    $zip = isset($_POST['zip']) ? $strInc->inject_check($_POST['zip']) : false;
    $uptime = time();
};

/*添加*/
if(!empty($_POST['Add']))
{
    $userList->getMesssage($pageUrl,'暂时不能添加！','error'); 
    exit();
    $addValue1 = "{$addBid},{$addState},'{$addTitle}',{$addFlag},'{$addAuthor}','{$addPageTitle}',now()";
    $addColumnName1 = '`class_id`,`a_state`,`a_title`,`flag`,`author`,`page_title`,`createtime`';

    $lastId = $arcArticle->arcArticleAdd($addColumnName1,$addValue1);

    $addValue2 = "{$lastId},'{$addKeyworld}','{$addRemark}','{$addContent}'";
    $addColumnName2 = '`nid`,`keywrod`,`discription`,`content`';

    $arcArticle->arcArticleAddCon($addColumnName2,$addValue2,$pageUrl);
    unset($arcArticle->getMesssage);

}

/*册除*/
if(!empty($_GET['tpldel'])){
    $delId = $strInc->inject_check($_GET['tpldel']);
    $arcArticle->arcArticleDel($delId,$pageUrl);
    unset($arcArticle->getMesssage);
    exit();
}

/*修改*/
if(!empty($_POST['Edit']))
{
    $editId = $strInc->inject_check($_POST['editId']);

    //[name] => test5 [editId] => 9 [mail] => etcphp@sohu.com [rename] => 张佳明 [address] => 黑龙江省海林市西城区 [tel] => 18201653011 [phone] => 04537234288 [zip] => 157100 
    
    $modContent1 = "`mail`='{$mail}', `login_last_time`={$uptime}, `rname`='{$rname}',`address`='{$address}',`tel`='{$tel}',`phone`='{$phone}',`post_code`={$zip}";
    $condition1="`uid` = {$editId} ";

    $userList->userListUpdate($modContent1, $condition1);

    $userList->getMesssage($pageUrl,'修改完毕！','success'); 
    unset($userList->getMesssage); exit();
}


//修改获取查询
if(  'edit' == $_GET['action']  )
{  
    $editId = $strInc->inject_check( $_GET['id'] );
    $editQuery = $userList->userListEdit($editId);
    require_once(JMADMINTPL.'/userListEdit.htm');   
}
else
{
    require_once(JMADMINTPL.'/userList.htm');
}


?>
