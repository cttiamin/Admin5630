<?php
require_once('global.php');

$pageurl = $_SERVER['PHP_SELF']."?a=".$a."&m=".$m;//地址
$adDeptAction = new adDeptAction();
$strInc  = new strInc();

$rows = $adDeptAction->adDeptSelect();

//修改获取
if(isset($_POST['Edit']))
{
    unset($_POST['Edit']);

    foreach($_POST as $p_name=>$p_values)
    {
        $strInc->inject_check($p_name);
        $strInc->inject_check($p_values);

        $mod_content = "`values`='$p_values'";
        $condition = "`name`='$p_name'";
    }

    $adDeptAction->adDeptUpdate($_POST);
      
    $adDeptAction->getMesssage($pageurl,'修改栏目操作已完毕！','success');
    //unset($adDeptAction->getMesssage);
    exit();
}

require_once(JMADMINTPL.'/adDept.htm');

?>
