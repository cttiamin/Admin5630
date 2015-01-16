<?php
// vim: set et sw=4 ts=4 sts=4 fdm=marker ff=unix fenc=utf8 nobomb:
/**
 * session.php
 * 用户自定义登陆保存session信息
 * maintainer: etcphp@sohu.com
 * LastChange: 2012-01-06
 * 
 */

class session
{
    static $db;     //数据库操作句柄
    static $table;//SESSION表
    static $max_time;//SESSION过期时间
    static $card;//客户端身份信息

    //SESSION初始化
    static function run(){
		/* 更改 php.ini 配置文件 */
        if(ini_get("session.save_handler") == "user" || ini_set("session.save_handler", "user") )
        {
            //执行顺序(开,读,写,关,垃,卸)
            session_set_save_handler( 
                array(__CLASS__, "start"),
                array(__CLASS__, "close"),
                array(__CLASS__, "read"),
                array(__CLASS__, "write"),
                array(__CLASS__, "destroy"),
                array(__CLASS__, "gc")
            );
        ob_start(); //输入到缓冲区
        self::$db = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
        mysqli_connect_errno(); //or die("数据库连接错误");
        self::$db->query("SET NAMES utf8");//编码
		self::$table="user_session"; //表名
		self::$max_time = 1000;//最大时间 16分
        self::$card = md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']); //标识
        session_start();
        }
    }
    //开
    static function start($path, $session_name)
    {
        return true;
    }
    //关
    static function close()
    {
        self::gc(self::$max_time); //垃圾回收
		self::$db->close();
		return true;
    }
    //读
    static function read($sid)
    {
        $sql  = "SELECT `data` FROM ".self::$table." WHERE `sid` ='{$sid}' AND `card`='".self::$card."'";
		
		$result = self::$db->query($sql);
        $row = $result->fetch_assoc();
		return self::$db->affected_rows > 0 ? $row['data'] : '';	//返回数据
    }
    //写
    static function write($sid,$data)
    {
        $sql  = "SELECT `sid` FROM ".self::$table." WHERE `sid` ='{$sid}' AND `card`='".self::$card."'";	
		$result = self::$db->query($sql);
		$mtime = time();
        if(self::$db->affected_rows > 0)
        {
			$sql = "UPDATE ".self::$table." SET `data` ='{$data}',`mtime` ='{$mtime}' 
                WHERE `sid`='{$sid}'";	
		}else{
			$sql = "INSERT INTO ".self::$table." (`sid`,`data`,`mtime`,`ip`,`card`) VALUES('{$sid}','{$data}','".time()."','{$_SERVER['REMOTE_ADDR']}','".self::$card."')";
        }
		return self::$db->query($sql) ? true : false;
    }
    //卸
    static function destroy($sid)
    {
        $sql = "DELETE FROM ".self::$table." WHERE `sid`='{$sid}'";
		self::$db->query($sql);
		return true;
    }
    //垃
    static function gc($max_time)
    {
		$max_time = self::$max_time;
        $sql = "DELETE FROM ".self::$table." WHERE `mtime`<'".(time()-$max_time)."'";
		self::$db->query($sql);
		return true;        
    }
}

?>
