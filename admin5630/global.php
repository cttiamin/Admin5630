<?php
//ob_start();
//@session_start();

//后台根目录
//define('JMADMIN', ereg_replace("[/\\]{1,}", '/', dirname(__FILE__) ) );

//echo dirname(__FILE__); exit();
define('JMADMIN', str_replace("\\", '/', dirname(__FILE__) ) );

//后台模板目录
define('JMADMINTPL',JMADMIN.'/templates');
/*src,href引用:后台目录名 */
define("ADNAME","admin5630");

//初始化配置
class APP{
    function __construct()
    {
        //配置中国时区
		date_default_timezone_set('PRC');   
        setlocale(LC_TIME, 'chs');
        self::_config();
        self::_include();       
	}
    static function _config()
    {
        //网站信息配置文件
        require_once(JMADMIN."/../libs/config.php");   
	}
    static function  _include()
    {
        require_once(JMADMIN."/../libs/include/strInc.php");
    }
    public function dump($content)
    {
    }
}

$app = new app();

/*自动加载*/
function __autoload($classname) 
{
    //通用动作类
    if ($classname == "Action") 
    {
        require_once(JMADMIN."/../libs/common/Action.php");
    } 
    //后台动作类
    elseif (substr ( $classname, - 6 ) == 'Action') 
    {
        require_once(JMADMIN."/action/".$classname.".php");
    }
    //数据库类
    elseif ($classname == 'mysqlDb')    
    { 
        require_once(JMADMIN."/../libs/common/mysqlDb.php");
    }
    //字符处理
    elseif (substr ( $classname, - 3 ) == 'Inc')
    {
        require_once(JMADMIN."/../libs/include/".$classname.".php");
    }
    //通用公共目录
    else
    {
        require_once(JMADMIN."/../libs/plus/".$classname.".php");
	}
}

/*管理员验证*/
sessionAction::run();

if(isset($_SESSION['ontime'])){
    $strInc =new strInc();
    $strInc->inject_check($_SESSION['uid']);
    $strInc->inject_check($_SESSION['shell']);
    $userCheck = new loginAction();
    $userCheck->userSellCheck($_SESSION['uid'], $_SESSION['shell']);
}else{
    $userCheck = new Action();
    $userCheck->getMesssage('/admin5630/login.php', '请您先登陆！', 'information');
    session_unset();
    session_destroy();
    exit();
}

/*单入口模式*/
$a = isset ( $_POST ['a'] ) ? $_POST ['a'] : '';
$m = isset ( $_POST ['m'] ) ? $_POST ['m'] : '';

/*
if(!empty($_POST['a']))
{

    $a = isset ( $_POST ['a'] ) ? $_POST ['a'] : 'index';
    $action = new $a ();
    if(!empty($_POST ['m']))
    {
        $m = isset ( $_POST ['m'] ) ? $_POST ['m'] : 'index';            
//        switch($_GET){
//        case $_GET['url'] : 
//        default :
//        }
        $url = isset ( $_POST ['url'] ) ? $_POST ['url'] : '';
        $id = isset ( $_POST ['id'] ) ? $_POST ['id'] : '';
        $action->$m($url,$id);
    }
}    
*/


?>
