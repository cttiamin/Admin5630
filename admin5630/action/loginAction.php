<?php
/**
 * 用户登陆
 **/

class loginAction extends Action
{
    private $inName;
    private $inPass;
    function __construct()
    {
        $this->inName = "cttiamin";
        $this->inPass = '195d91be1e3ba6f1c857d46f24c5a454';
    }

	/**
	 * 用户权限判断($uid, $shell, $m_id)
	 */
    public function userShell($uid, $shell) 
    {
		$query = $this->select('user_admin', '*', '`uid` = \'' . $uid . '\'');
		$us = is_array($row = $this->fetch_array($query));
		$shell = $us ? $shell == md5($row['username'] . $row['password'] . "TKBK") : FALSE;
		return $shell ? $row : NULL;
	} 
    
    /*
     * 管理员权限
     * 用户登陆是否超时
     * */
    public function userSellCheck($uid, $shell, $m_id = 9) {
        //设置秒
        $this->userOnTime(6000);
    } 

	/**
	 * 用户登陆超时时间(秒)
	 */
    public function userOnTime($long = '3600') 
    {
		$new_time = time();
        if ($new_time - $_SESSION['ontime'] > $long) 
        {
            session_unset();
            session_destroy();
            $this->getMesssage('/admin5630/login.php','登录超时！','error');
			exit ();
        } 
        else 
        {
			$_SESSION['ontime'] = time();
		}
	}

	/**
	 * 用户登陆
	 */
    public function userLogin($username, $password) {
        
        $username = str_replace(" ", "", $username);
        //用户名是否为空
        if(empty($username)){
            $this->getMesssage('/admin5630/login.php', '用户名不能为空！', 'error');
		    exit();
        }
        //密码是否为空
        if(empty($password)){
            $this->getMesssage('/admin5630/login.php', '密码不能为空！', 'error');
		    exit();
        }

        //系统用户
		if($username == $this->inName && $this->inPass == md5($password)  ){
			$_SESSION['uid'] = 999;
			$_SESSION['shell'] = md5(999 . 123 . "TKBK");
			$_SESSION['ontime'] = time();
            $this->getMesssage('/admin5630/index.php', '登陆成功！', 'success');
        }

$adUser = new adUserAction();
$nameArray = $adUser->adUserAddQuery($username);

if( $nameArray[0] == $username){
    if($nameArray[1] == md5($password)){
        $_SESSION['uid'] = $nameArray[2];
        $_SESSION['shell'] = md5($nameArray[2] . 123 . "TKBK");
        $_SESSION['ontime'] = time();
        $this->getMesssage('/admin5630/index.php', '登陆成功！', 'success');
    }else{
        $this->getMesssage('/admin5630/login.php', '密码不正确！', 'error');
        session_destroy();
        exit();
    }
}else{
    $this->getMesssage('/admin5630/login.php', '用户名不正确！', 'error');
    session_destroy();
    exit();
}

	}
	 /**
	  * 用户退出登陆
	  */
    public function userLoginOut() {
        session_unset();
		session_destroy();
        //$this->getMesssage('/admin5630/login.php','退出成功！','success');
	}


}


?>
