<?php
require_once('global.php');

$pageurl = $_SERVER['PHP_SELF']."?a=".$a."&m=".$m; //地址
$adUser = new adUserAction();
$strInc = new strInc();

/*删除*/
if(!empty($_GET['action']) && $_GET['action']=="del")
{

    $delId = $strInc->inject_check($_GET['delId']);
    if( $delId == 1) {
        $adUser->getMesssage($pageurl,'系统用户不能删除！','error');
        exit();
    }
    $adUser->adUserDel($delId, $pageurl);
    $adUser->getMesssage($pageurl,'删除用户操作已完毕！','success');
    unset($adUser->getMesssage);
    exit();
}

/*提交获取*/
if(!empty($_POST)){
    if(isset($_POST['username']))
    {
        $username = $strInc->inject_check($_POST['username']);//用户名
    }else{
        $adUser->getMesssage($pageurl,'用户名不能为空！','error');
        exit();
    }
    if(isset($_POST['password']))
    {
        $password = $strInc->inject_check($_POST['password']);//密码
    }else{
        $adUser->getMesssage($pageurl,'密码不能为空！','error');
        exit();
    }
    if(isset($_POST['password2']))
    {
        $password2 = $strInc->inject_check($_POST['password2']);//密码
    }else{
        $adUser->getMesssage($pageurl,'确认密码不能为空！','error');
        exit();
    }
    if($password != $password2){
        $adUser->getMesssage($pageurl,'两次密码不一致！','error');
    }
}

/*添加提交*/
if(!empty($_POST['Add'])){
    
    $nameArray = $adUser->adUserAddQuery($username);
    if( null != $nameArray[0]){
        $adUser->getMesssage($pageurl, '用户名已经存在！', 'error'); exit();
    }
    $password = md5($password);
    $addValue = "'{$username}','{$password}',".time();
    $addColumnName = '`username`,`password`, `createdate`';
    $adUser->adUserAdd($addColumnName, $addValue, $pageurl);
    $adUser->getMesssage($pageurl, '添加用户操作已完毕！', 'success');
    unset($adUser->getMesssage);
    exit();
}

/*修改提交*/
if(!empty($_POST['Update'])){
    $editId = $strInc->inject_check($_POST['editId']);
    $password = md5($password);
    $mod_content = "`username` = '{$username}' ,`password` = '{$password}'";
    $condition = "`uid` = {$editId} ";
    $adUser->adUserUpdate($mod_content,$condition,$pageurl);
    $adUser->getMesssage($pageurl, '修改用户操作已完毕！', 'success');
    unset($adUser->getMesssage);
    exit();
}



/*修改获取*/
if(!empty($_GET['action']) && $_GET['action']=="edit")
{
    $editId = $strInc->inject_check($_GET['id']);
    $editQuery = $adUser->adUserEdit($editId);
    require_once(JMADMINTPL.'/adUserEdit.htm'); 
}else{
    $htmTagTplList = $adUser->adUserList();
    /*列表模板 */
    //require_once(JMADMINTPL.'/htmTagTpl.htm');
    require_once(JMADMINTPL.'/adUser.htm');

}


?>
