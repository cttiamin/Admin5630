<?php
require_once('global.php');

$proDisplay = new proDisplayAction();
$strInc = new strInc();//字符处理

/*类别判断*/
if(isset($_GET['c'])){$c=$strInc->inject_check($_GET['c']);}else{$c=5;}
switch ($c)
{
	case 1: $c = 5; break;	
	case 2: $c = 5; break;
	case 3: $c = 5; break;
	default : $c;
}
/* a:动作 
 * m:模型
 * c:类别
 * p:分页
 */
if(isset($_GET['p'])){$p=$strInc->inject_check($_GET['p']);}else{$p=1;}
//$pageUrl = $_SERVER['PHP_SELF']."?a={$a}&amp;m={$m}&amp;c={$c}&amp;p={$p}";//地址
$pageUrl = $_SERVER['PHP_SELF']."?c={$c}&amp;p={$p}";//地址
$strInc->inject_check($pageUrl);//检SQL

/*类别选项*/
$proDisplaySelectIn = $proDisplay->proChannelSelectIn($c);//select选项
unset($proDisplay->class_arr);
unset($proDisplay->str);

/*POST获取 修改,添加 */
if( !empty($_POST) && !isset($_POST['PicPost']) ){       
    //标题
    if(empty($addTitle)){$addTitle = $strInc->inject_check($_POST['title']);
    }else{$arcArticle->getMesssage($pageUrl,'标题名称不能为空！','error');
    exit();
    } 
    //淘宝链接
    if(empty($_POST['tblink'])){$addLink ='';}
    else{$addLink = $strInc->inject_check($_POST['tblink']);}
    //关键字
    if(empty($_POST['keyworld'])){$addKeyworld ='';}
    else{$addKeyworld = $strInc->inject_check($_POST['keyworld']);}
    //页面标题 
    if(empty($_POST['page-title'])){$addPageTitle ='';}
    else{$addPageTitle = $strInc->inject_check($_POST['page-title']);}
    //显示 
    $addState = $strInc->inject_check(intval($_POST['state']));
    //级别
    $addFlag = $strInc->inject_check(intval($_POST['flag']));
   //父类
    if(empty($_POST['bid'])){$addBid ='';}
    else{$addBid = $strInc->inject_check($_POST['bid']);}
   //描述 
    if(empty($_POST['discription'])){$addDisti ='';}
    else{$addDisti = $strInc->inject_check($_POST['discription']);}
    //库存
    $addStock = $strInc->inject_check(intval($_POST['stock']));   
   //库存数量
    if(empty($_POST['stock-sum']) || $_POST['stock-sum']==0 ){$addStockSum = 0;}
    else{$addStockSum = $strInc->inject_check(intval($_POST['stock-sum']));}
   //查询次数
    if(empty($_POST['cquery']) || $_POST['cquery']==0 ){$addCquery = 0;}
    else{$addCquery = $strInc->inject_check(intval($_POST['cquery']));}
   //商品编号
    if(empty($_POST['bian']) || $_POST['bian']==0 ){$addBian = 0;}
    else{$addBian = $strInc->inject_check($_POST['bian']);}
   //卖价
    if(empty($_POST['price']) ||$_POST['price']==0 ){$addPrice =0 ;}
    else{$addPrice = $strInc->inject_check($_POST['price']);}
   //进货价 
    if(empty($_POST['jprice'])){$addJprice ='';}
    else{$addJprice = $strInc->inject_check($_POST['jprice']);}
   //邮费
    if(empty($_POST['mprice'])){$addMprice ='';}
    else{$addMprice = $strInc->inject_check($_POST['mprice']);}
   //重量说明
    if(empty($_POST['weight'])){$addWeight ='';}
    else{$addWeight = $strInc->inject_check($_POST['weight']);}
   //促销信息
    if(empty($_POST['promotion'])){$addPromotion ='';}
    else{$addPromotion = $strInc->inject_check($_POST['promotion']);}
   //产地
    if(empty($_POST['address'])){$addAddress ='';}
    else{$addAddress = $strInc->inject_check($_POST['address']);}
    //内容
    if(empty($_POST['content'])){$addContent ='';}
    else{$addContent = $strInc->inject_check($_POST['content']);}
   //参数
    if(empty($_POST['parameter'])){$addParameter ='';}
    else{$addParameter = $strInc->inject_check($_POST['parameter']);}
};

/*添加*/
if(!empty($_POST['Add'])){
$addColumnName1 = '`pc_id`,`p_title`,`p_flag`,`p_state`,`p_ctime`,`p_url`,`p_stock`,`p_stockSum`,`p_query`,`p_bian`,`p_ptitle`,`p_keyworld`,`p_price`';
$addValue1 ="{$addBid},'{$addTitle}',{$addFlag},{$addState},now(),'{$addLink}',{$addStock},{$addStockSum},{$addCquery},'{$addBian}','{$addPageTitle}','{$addKeyworld}',{$addPrice}";

$lastId = $proDisplay->proDisplayAddBas($addColumnName1,$addValue1); 

$addColumnName2 = '`pn_id`,`pn_discription`,`pn_remark`,`pn_content`,`pn_jprice`,`pn_mprice`,`pn_weight`,`pn_promotion`,`pn_address`';

$addValue2 = "$lastId,'{$addDisti}','{$addParameter}','{$addContent}',{$addJprice},{$addMprice},'{$addWeight}','{$addPromotion}','{$addAddress}'";

$proDisplay->proDisplayAddCon($addColumnName2, $addValue2);

$proDisplay->getMesssage($pageUrl, '添加文章操作已执行！', 'success');
unset($proDisplay->getMesssage);
}

/*册除*/
if(!empty($_GET['del'])){
    $delId = $strInc->inject_check($_GET['del']);
    $proDisplay->proDisplayDel($delId,$pageUrl);
    unset($proDisplay->getMesssage);
    exit();
}
   
/*修改　提交　更新　*/
if(!empty($_POST['Edit'])){

    $editId = $strInc->inject_check($_POST['editId']);
    if((int)$addBid==(int)$editId){
        $proDisplay->getMesssage($pageurl,'父类选择错误！','error'); exit();
    }

    if(isset($_POST['ctime']))
        $ctime = $strInc->inject_check($_POST['ctime']);//创建时间

    $modContent1="`pc_id`={$addBid},`p_state`={$addState},`p_title`='{$addTitle}',`p_flag`={$addFlag},`p_url`='{$addLink}',`p_ptitle`='{$addPageTitle}',`p_stock`={$addStock},`p_stockSum`={$addStockSum},`p_query`={$addCquery},`p_bian`={$addBian},`p_keyworld`='{$addKeyworld}',`p_price`={$addPrice}";
    $condition1="`p_id` = {$editId} ";

    $modContent2="`pn_discription`='{$addDisti}',`pn_remark`='{$addParameter}',`pn_content`='{$addContent}',`pn_jprice`='{$addJprice}',`pn_mprice`='{$addMprice}',`pn_weight`='{$addWeight}',`pn_promotion`='{$addPromotion}',`pn_address`='{$addAddress}'";
    $condition2="`pn_id` = {$editId} ";
    
    $proDisplay->proDisplayUpdateBas($modContent1,$condition1);
    $proDisplay->proDisplayUpdateCon($modContent2,$condition2);
	/*更新html文件*/
	$proHtm = new htmProDisplayAction();
	$proHtm->proDisplayHtm($editId);
	/*弹出操作完成消息*/
    $proDisplay->getMesssage($pageUrl,'修改完毕！','success'); 
    unset($proDisplay->getMesssage);
    exit();
}

/*图片修改提交*/
if(isset($_POST['PicPost']))
{   
    //获取id
    if(empty($_GET['i']))
    {
        $proDisplay->getMesssage($pageUrl,'参数错误！','error');
        unset($proDisplay->getMesssage);
    }
    $picId = $strInc->inject_check($_GET['i']);

    //检测字符
    unset($_POST['PicPost']);
    foreach($_POST as $pp_id=>$pp_url){
        $strInc->inject_check($pp_url);
    }

    $pageUrl .= "&amp;i={$picId}&amp;a=pic";    //跳转到原页面

    $proDisplay->proDisplayUpdatePic($_POST,$picId);
    $proDisplay->getMesssage($pageUrl,'图片更新完毕！','success');
    unset($proDisplay->getMesssage);
    exit();
}

/*　图片删除 */
if( isset($_GET['a']) &&  $_GET['a']=="picDel" )
{
    
    $i = isset($_GET['i']) ? $strInc->inject_check($_GET['i']) : false;
    $c = isset($_GET['c']) ? $strInc->inject_check($_GET['c']) : false;
    $v = isset($_GET['v']) ? intval( $strInc->inject_check($_GET['v']) ) : false;

    if( !($i && $c && $v ))
    {
        $proDisplay->getMesssage($pageUrl,'参数错误！','error');
        unset($proDisplay->getMesssage);
        exit();
    }   
    $str = $proDisplay->proDisplayPicDel($i, $v);

    $pageUrl .= "&amp;a=pic&i=$i";
    $proDisplay->getMesssage($pageUrl, $str ,'success');

}

/*　图片上传 */
if( isset($_GET['a']) &&  $_GET['a']=="pic" )
{  

    if(empty($_GET['i']))
    {
        $proDisplay->getMesssage($pageUrl,'参数错误！','error');
        unset($proDisplay->getMesssage);
    }
    $picId = $strInc->inject_check($_GET['i']); //商品id
    $proUrl =$pageUrl."&amp;i={$picId}";
    
   // $c = $strInc->inject_check($_GET['c']); //类别id
   
    $rowsPic = $proDisplay->proDisplayCheckPic($picId);
    $row_arr = $rowsPic[0];
    $row_con = $rowsPic[1];
    
    require_once(JMADMINTPL.'/proDisplayPic.htm');
 
}

/* 修改　i:获取产品id */ 
else if(  !empty($_GET['i']) )
{
    $editId = $strInc->inject_check($_GET['i']);
    $editQuery = $proDisplay->proDisplayEdit($editId);
    require_once(JMADMINTPL.'/proDisplayEdit.htm');

}
/* 默认产品列表*/
else
{
	/*内容部分*/
	$proDisplayNum = $proDisplay->proDisplaySum($c);//总条数
	$pageNav = new pageAd($proDisplayNum,20,$pageUrl);//分页实例
	$pageAdLimit = $pageNav->pageAdLimit();//条件
	//print_r($pageAdLimit);
	$proDisplayList = $proDisplay->proDisplayList($c,$pageAdLimit[0],$pageAdLimit[1]);//文章列表
	unset($proDisplay->arcArr);//清空文章对象
	$pageAdStr = $pageNav->pageAdStr();//翻页导航字符
	$pageNav->pageClear();//释放分页对象
	
	require_once(JMADMINTPL.'/proDisplay.htm');
}

?>

