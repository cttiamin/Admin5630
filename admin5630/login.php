<?php
//设置文件路径
//define('LOGINDIR', ereg_replace("[/\\]{1,}", '/', dirname(__FILE__) ) ); 
define('LOGINDIR', str_replace("\\", '/', dirname(__FILE__) ) ); 
//加入配置文件
require_once(LOGINDIR."/../libs/config.php");

//配置中国时区
date_default_timezone_set('PRC');   

/*自动加载*/
function __autoload($classname) 
{
    if ($classname == "Action")
    {
        require_once(LOGINDIR."/../libs/common/Action.php");
    } 
    elseif (substr ( $classname, - 6 ) == 'Action') 
    {
        require_once(LOGINDIR."/action/".$classname.".php");
    }
    elseif ($classname == 'mysqlDb')
    { 
        require_once(LOGINDIR."/../libs/common/mysqlDb.php");
    } 
    elseif (substr ( $classname, - 3 ) == 'Inc')
    {
        require_once(LOGINDIR."/../libs/include/".$classname.".php");
    }
    else
    {
        require_once(LOGINDIR."/../libs/plus/".$classname.".php");
	}
}
//实例化登录
$loginAction = new  loginAction();
//字符处理
$strInc = new strInc();



//登陆获取
if(!empty($_POST)){
	//写入session信息,
    sessionAction::run();
	//检测用户名
    $strInc->inject_check($_POST['name']);
    $strInc->inject_check($_POST['pwd']);
	//
    $loginAction->userLogin($_POST['name'], $_POST['pwd']);
}

require_once(LOGINDIR.'/templates/login.htm');
?>
