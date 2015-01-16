<?php
require_once('global.php');

$pageurl = $_SERVER['PHP_SELF']."?a=".$a."&m=".$m;//地址
$htmTagTpl = new htmTagTplAction();
$strInc = new strInc();

/*提交获取*/
if(!empty($_POST))
{
    if(empty($_POST['title']))
    {
        $htmTagTpl->getMesssage($pageurl,'名称不能为空！','error');
        exit();
    }
    $addTitle = $strInc->inject_check($_POST['title']);//标题

    if(empty($_POST['content']))
    {
        $addContent = '';
    }
    $addContent = $_POST['content'];//内容
}

/*添加提交*/
if(!empty($_POST['Add']))
{
    $addValue = "'{$addTitle}','{$addContent}'";
    $addColumnName = '`t_title`,`t_content`';

    $htmTagTpl->htmTagTplAdd($addColumnName,$addValue,$pageurl);
    unset($htmTagTpl->getMesssage);
    exit();
}

/*修改提交*/
if(!empty($_POST['Update'])){
    $editId = $strInc->inject_check($_POST['editId']);

    $mod_content = "`t_title` = '{$addTitle}' ,`t_content` = '{$addContent}'";
    $condition = "`t_id` = {$editId} ";
	
    $htmTagTpl->htmTagTplUpdate($mod_content,$condition,$pageurl);
    unset($htmTagTpl->getMesssage);
    exit();
}

/*删除*/
if(!empty($_GET['action']) && $_GET['action']=="del")
{
    $delId = $strInc->inject_check($_GET['delId']);
    $htmTagTpl->htmTagTplDel($delId,$pageurl);
    unset($htmTagTpl->getMesssage);
    exit();

}

/*修改获取*/
if(!empty($_GET['action']) && $_GET['action']=="edit")
{
    $editId = $strInc->inject_check($_GET['id']);
    $editQuery = $htmTagTpl->htmTagTplEdit($editId);

    require_once(JMADMINTPL.'/htmTagTplEdit.htm'); 
}else{
    $htmTagTplList = $htmTagTpl->htmTagTplList();
    /*列表模板 */
    require_once(JMADMINTPL.'/htmTagTpl.htm');
}

?>
