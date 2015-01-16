<?php
require_once('global.php');

$pageurl = $_SERVER['PHP_SELF']."?a=".$a."&m=".$m;//地址
$arcChannel = new arcChannelAction();
$strInc = new strInc();

/*提交获取*/
if(!empty($_POST)){
$addTitle = $strInc->inject_check($_POST['title']);//标题
if(empty($addTitle)){
    $arcChannel->getMesssage($pageurl,'栏目名称不能为空！','error');
    exit();
}
$addHtml = $strInc->inject_check($_POST['create-html']);//html目录
$addkeyworld = $strInc->inject_check($_POST['keyworld']);//关键字
$addPageTitle = $strInc->inject_check($_POST['page-title']);//页面标题

$addBid = $strInc->inject_check($_POST['bid']);//父类
$addContent = $strInc->inject_check($_POST['content']); //描述


$addTemplate =  $_POST['template'] == null ? '' : 
    $strInc->inject_check($_POST['template']) ; //模板


if(!empty($_POST['radio1'])){//栏目类型
    //print_r($_POST['radio1']);
    $addRadio = $strInc->inject_check($_POST['radio1'])==1 ? 1 : 0;
}else{$addRadio = 0; }

if(isset($_POST['is_qx'])){
    $addIsxq = $_POST['is_qx'];
}else{
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


}

/*修改提交*/
if(!empty($_POST['Edit'])){
    $editId = $strInc->inject_check($_POST['editId']);
    if((int)$addBid==(int)$editId){
        $arcChannel->getMesssage($pageurl,'父类选择错误！','error'); exit();
    }

    $mod_content = "`class_title` = '{$addTitle}' ,`webpage` = '{$addHtml}',`keyworld` = '{$addkeyworld}',`pagetitle` = '{$addPageTitle}',b_id = {$addBid},`content` = '{$addContent}', flag = {$addRadio} ,`is_qx` = '{$addIsxq}',`template` = '{$addTemplate}'";
    $condition = "`class_code` = {$editId} ";
    

    $arcChannel->arcChannerUpdate($mod_content, $condition, $pageurl);
    unset($arcChannel->getMesssage);
    exit();
}
/*添加提交*/
if(!empty($_POST['Add'])){

$addValue = "'{$addTitle}','{$addHtml}','{$addkeyworld}','{$addPageTitle}',{$addBid},'{$addContent}',$addRadio,'$addIsxq'";
$addColumnName = '`class_title`,`webpage`,`keyworld`,`pagetitle`,b_id,`content`,flag,`is_qx`';
$arcChannel->arcChannerAdd($addColumnName,$addValue,$pageurl);
unset($arcChannel->getMesssage);
exit();
}

/*删除提交*/
if(!empty($_GET['delId'])){
    $delId = $strInc->inject_check($_GET['delId']);
    $arcChannel->arcChannerDel($delId,$pageurl);
    unset($arcChannel->getMesssage);
    exit();
}
/*修改获取*/
if(!empty($_GET['tplEdit'])){
    $editId = $strInc->inject_check($_GET['tplEdit']);
    $editQuery = $arcChannel->arcChannerEdit($editId);
    $editSelect = $arcChannel->arcChannerSelectIn($editQuery['b_id']);//select选项
    unset($arcChannel->class_arr);
    unset($arcChannel->str);
    
    $editQueryQx = explode('.', $editQuery['is_qx'], 6); 	//权限
	
    /*$str = '1.2.3.4.5.6.7.8.9';
    $arStr = explode('.',$str,6);
    print_r($arStr);
    $str = implode('-',$arStr);
    echo $str; */

	require_once(JMADMINTPL.'/arcChannelEdit.htm'); 
}else{
    $listSelect = $arcChannel->arcChannerSelectIn(0);//select选项
    unset($arcChannel->class_arr);
    unset($arcChannel->str);
    $arcChannelList = $arcChannel->arcChannelList(); //栏目列表
    unset($arcChannel->class_arr); 					 //清空
    unset($arcChannel->str);    

	require_once(JMADMINTPL.'/arcChannel.htm');
}
?>
