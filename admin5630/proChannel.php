<?php
require_once('global.php');

$pageurl = $_SERVER['PHP_SELF']."?a=".$a."&m=".$m;//地址
$proChannel = new proChannelAction();   //实例化
$strInc = new strInc();

/*提交获取*/
if(!empty($_POST)){

$addTitle = $strInc->inject_check($_POST['title']);//标题
if(empty($addTitle)){
    $proChannel->getMesssage($pageurl,'栏目名称不能为空！','error');
    exit();
}
$addHtml = $strInc->inject_check($_POST['create-html']);//html目录
$addkeyworld = $strInc->inject_check($_POST['keyworld']);//关键字
$addPageTitle = $strInc->inject_check($_POST['page-title']);//页面标题

$addBid = $strInc->inject_check($_POST['bid']);//父类
$addContent = $strInc->inject_check($_POST['content']); //描述

if(!empty($_POST['radio1'])){//栏目类型
    $addRadio = $strInc->inject_check($_POST['radio1'])==1 ? 1 : 0;
}else{$addRadio = 0; }

if(!empty($_POST['checkbox0'])){ //添加删除
     $addIsxq[0] =$strInc->inject_check($_POST['checkbox0'])=='on' ? 1:0 ;
}else{ $addIsxq[0] = 0; }
if(!empty($_POST['checkbox1'])){ //描述
     $addIsxq[1] = $strInc->inject_check($_POST['checkbox1'])=='on' ?1:0;
}else{ $addIsxq[1] = 0; }
if(!empty($_POST['checkbox2'])){ //详情
     $addIsxq[2] = $strInc->inject_check($_POST['checkbox2'])=='on' ?1:0;
}else{$addIsxq[2] = 0; }
if(!empty($_POST['checkbox3'])){ //图片
    $addIsxq[3] = $strInc->inject_check($_POST['checkbox3'])=='on' ?1:0;
}else{ $addIsxq[3] = 0; }
if(!empty($_POST['checkbox4'])){ //链接
    $addIsxq[4] = $strInc->inject_check($_POST['checkbox4'])=='on' ?1:0;
}else{$addIsxq[4] = 0; }
if(!empty($_POST['checkbox5'])){ //标签
    $addIsxq[5] = $strInc->inject_check($_POST['checkbox5'])=='on' ?1:0;
}else{$addIsxq[5] = 0; }
$addIsxq = implode('.',$addIsxq);

}
/*修改提交*/
if(!empty($_POST['Edit'])){
    $editId = $strInc->inject_check($_POST['editId']);
    if((int)$addBid==(int)$editId){
        $proChannel->getMesssage($pageurl,'父类选择错误！','error'); exit();
    }

    //if((int)$addRadio==0){$proChannel->getMesssage($pageurl, '栏目类型更改错误错误！', 'error'); exit();}
    $mod_content = "`pc_title` = '{$addTitle}' ,`pc_page` = '{$addHtml}',`pc_keyworld` = '{$addkeyworld}',`pc_ptitle` = '{$addPageTitle}',`pc_bid` = {$addBid},`pc_discription` = '{$addContent}',`pc_flag` = $addRadio,`pc_qx` = '{$addIsxq}'";
    $condition = "`pc_id` = {$editId} ";
//echo  $mod_content,$condition;  exit();
    $proChannel->proChannelUpdate($mod_content,$condition,$pageurl);
    unset($proChannel->getMesssage);
    exit();
}

/*添加提交*/
if(!empty($_POST['Add'])){

$addValue = "'{$addTitle}','{$addHtml}','{$addkeyworld}','{$addPageTitle}',{$addBid},'{$addContent}',$addRadio,'$addIsxq'";
$addColumnName = '`pc_title`,`pc_page`,`pc_keyworld`,`pc_ptitle`,pc_bid,`pc_discription`,pc_flag,`pc_qx`';

$proChannel->proChannelAdd($addColumnName,$addValue,$pageurl);
unset($proChannel->getMesssage);
exit();
}

/*删除提交*/
if(!empty($_GET['delId'])){
    $delId = $strInc->inject_check($_GET['delId']);
    $proChannel->proChannelDel($delId,$pageurl);
    unset($proChannel->getMesssage);
    exit();
}

/*修改获取*/
if(!empty($_GET['tplEdit'])){
    $editId = $strInc->inject_check($_GET['tplEdit']);
    $editQuery = $proChannel->proChannelEdit($editId);
    $editSelect = $proChannel->proChannelSelectIn($editQuery['pc_bid']);//select选项
    unset($proChannel->class_arr);
    unset($proChannel->str);
    
    $editQueryQx = explode('.',$editQuery['pc_qx'],6);  //权限

require_once(JMADMINTPL.'/proChannelEdit.htm'); 
}else{
    $proChannelList = $proChannel->proChannelList();    //栏目列表
    unset($proChannel->class_arr); //清空
    unset($proChannel->str);   
    $listSelect = $proChannel->proChannelSelectIn(0);   //select选项(添加中)
    unset($proChannel->class_arr);//清空
    unset($proChannel->str);

require_once(JMADMINTPL.'/proChannel.htm');

}


?>
