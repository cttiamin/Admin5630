<?php
require_once('global.php');
$adKeyWord = new adKeyWordAction();
$strInc = new strInc();
//分页
if(isset($_GET['p'])){$p=$strInc->inject_check($_GET['p']);}else{$p=1;}
$pageUrl = $_SERVER['PHP_SELF']."?a={$a}&amp;m={$m}&amp;p={$p}";	//地址
$strInc->inject_check($pageUrl);		//检SQL


/* 提交获取 */
if(!empty($_POST))
{
	$keyword = $strInc->userFormCheck($_POST);//过滤所有
	
    if( empty($_POST['keyword']) )
    {
        $adKeyWord->getMesssage($pageUrl, '关键字不能为空！','error');
        exit();
    }
    $keyWord = isset($_POST['keyword']) ? $_POST['keyword'] : '';
	$order = isset( $_POST['order'] ) && !empty($_POST['order']) ? $_POST['order'] : 0;
	$link = isset($_POST['link']) && !empty($_POST['link']) ? $_POST['link'] : '';
	$deffc = isset($_POST['deffc']) && !empty($_POST['deffc']) ? $_POST['deffc'] : 0;
	$important = isset($_POST['important']) && !empty($_POST['important']) ? $_POST['important'] : 0;
	$baidu = isset($_POST['baidu']) && !empty($_POST['baidu']) ? $_POST['baidu'] : 0;
	$google = isset($_POST['google']) && !empty($_POST['google']) ? $_POST['google'] : 0;
	$remark = isset($_POST['remark']) ? $_POST['remark'] : '';
}

/*添加提交*/
if(!empty($_POST['Add']))
{
    $addValue = "{$order},'{$keyWord}','{$link}',{$deffc},{$important},{$google},{$baidu},'{$remark}'";
    $addColumnName = '`k_order`,`k_name`,`k_link`,`k_deff`,`k_important`,`k_google`,`k_baidu`,`k_remark`';
	
    $adKeyWord->adKeyWordAdd($addColumnName, $addValue, $pageUrl);
    unset($adKeyWord->getMesssage);
    exit();
}

/*修改提交*/
if(!empty( $_POST['Update'] )){
	 
	
    $editId = isset($_POST['editId']) ? $_POST['editId'] : exit("没有获取ID"); 
    $mod_content = "`k_order` = {$order} ,`k_name` = '{$keyWord}', `k_link` = '{$link}',`k_deff` = {$deffc},`k_important` = {$important},`k_google` = {$google},`k_baidu` = {$baidu},`k_remark` = '{$remark}'";
    $condition = "`k_id` = {$editId} ";
	
    $adKeyWord->adKeyWordUpdate($mod_content, $condition, $pageUrl);
    unset($adKeyWord->getMesssage);
    exit();
}

/*删除*/
if(!empty($_GET['action']) && $_GET['action']=="del")
{
    $delId = $strInc->inject_check($_GET['delId']);
    $adKeyWord->adKeyWordDel($delId, $pageUrl);
    unset($adKeyWord->getMesssage);
    exit();
}

/*修改获取*/
if( !empty($_GET['tplEdit']) )
{
    $editId = $strInc->inject_check( $_GET['tplEdit'] );
    $editQuery = $adKeyWord->adKeyWordEdit( $editId );
    require_once(JMADMINTPL.'/adKeyWordEdit.htm'); 
}
else{
	/*内容部分*/
	$adKeyWordSum = $adKeyWord->adKeyWordSum();//总条数
	//分页实例
	$pageNav = new pageAd($adKeyWordSum, 50, $pageUrl);
	//limit条件,从第几条显示多少条
	$pageAdLimit = $pageNav->pageAdLimit();
	//文章列表
	//$arcArticleList = $arcArticle->arcArticleList($c, $pageAdLimit[0], $pageAdLimit[1] );
	$adKeyWordList = $adKeyWord->adKeyWordList( $pageAdLimit[0], $pageAdLimit[1]  );
	//unset($adKeyWord->arcArr);         //清空文章对象
	$pageAdStr = $pageNav->pageAdStr(); //翻页导航字符
	$pageNav->pageClear();              //释放分页对象

    
    /*列表模板 */
    require_once(JMADMINTPL.'/adKeyWord.htm');
}

?>
