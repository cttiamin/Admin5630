<?php
require_once('global.php');

$strInc = new strInc();
$db = new mysqlDb();

$pageUrl = $_SERVER['PHP_SELF']."?a={$a}&amp;m={$m}";	//地址
$cont = '';

if( isset($_POST['remark']) ) {
	$cont = $strInc->inject_check( $_POST['remark'] );
	$cont  = dhtmlchars(nl2br($cont ));//添加P 标签
	$cont  = str_replace('&lt;br /&gt;','&lt;/p&gt;&nbsp;&lt;p&gt;', $cont); //过滤长尾巴词
	$cont  = str_replace(' ',"&ampnbsp;",$cont);
	$cont  = "&lt;p &gt;".$cont."&lt;/p &gt;";
	

	//从表中提取信息的sql语. LENGTH(k_name)
	$strsql="select `k_id`,`k_name`, `k_link`,`k_remark` from tag_keyword where k_link !='' order by LENGTH(k_name) desc";
	//mysql_query('SET CHARACTER SET utf8');
    $result = mysql_query($strsql);  //设定sql查询结果变量

    $keyArr = '';
    while($row = mysql_fetch_row($result) )
    {
        //$keyArr[] = array($row['k_id'], $row['k_name'], $row['k_link'], $row['k_remark']);
        $keyArr[] = array($row[0], $row[1], $row[2], $row[3]);
    }
    //echo count($keyArr); exit();

    

    /* 在关键字上加ID标记,
     * */
    for($i=0; $i<count($keyArr); $i++)
    {
        //关键字在字符中是否存在
        if( strpos( $cont, $keyArr[$i][1] ))
        {
            //折分成数组,只替换一次
            $cont  = str_replace($keyArr[$i][1], $keyArr[$i][1]."(*)", $cont);
            $contarr = explode("(*)", $cont);
            
            //把标记替换上去
            $contarr[0]  = str_replace($keyArr[$i][1], '&lt;a href="'.$keyArr[$i][2].'" title="^'.$keyArr[$i][0].'^" target="_blank" &gt {%'.$keyArr[$i][0].'%}&lt/a&gt', $contarr[0] );
           
            //重新压缩字符串
        	$cont ="";	
			for($j=0; $j<count($contarr); $j++){
				 $cont .= $contarr[$j];
			}
        }      
    }

    /*把链接和,关键字替换*/
    for($k=0; $k<count($keyArr); $k++)
    {
        $cont = str_replace("{%".$keyArr[$k][0]."%}", $keyArr[$k][1], $cont);
        $cont = str_replace("^".$keyArr[$k][0]."^", $keyArr[$k][3], $cont);
    }

}
	
	
	
	
/*过滤字符函数*/
function dhtmlchars($string) 
{
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = dhtmlchars($val);
		}
	} 
	else {
		$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1',
		str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string));
	}
	return $string;
}

/*获取模板*/
require_once(JMADMINTPL.'/adKeyWordPost.htm');
exit();

/* 提交获取
if(!empty($_POST))
{
	$keyword = $strInc->userFormCheck($_POST);	//过滤所有
	
    if( empty($_POST['keyword']) )
    {
        $adKeyWord->getMesssage($pageUrl, '关键字不能为空！', 'error');
        exit();
    }
    $keyWord = isset($_POST['keyword']) ? $_POST['keyword'] : '';
	$order = isset($_POST['order']) ? $_POST['order'] : 0;
	$link = isset($_POST['link']) ? $_POST['link'] : '';
	$deffc = isset($_POST['deffc']) ? $_POST['deffc'] : 0;
	$important = isset($_POST['important']) ? $_POST['important'] : 0;
	$baidu = isset($_POST['baidu']) ? $_POST['baidu'] : 0;
	$google = isset($_POST['google']) ? $_POST['google'] : 0;
	$remark = isset($_POST['remark']) ? $_POST['remark'] : '';
}
 */
 
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
    $adKeyWord->adKeyWordDel($delId,$pageUrl);
    unset($adKeyWord->getMesssage);
    exit();
}


?>
