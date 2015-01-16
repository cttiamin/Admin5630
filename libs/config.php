<?php
//libs当前目录
//define('LIBSDIR', ereg_replace("[/\\]{1,}", '/', dirname(__FILE__) ) ); 
define('LIBSDIR', str_replace("\\", '/', dirname(__FILE__) ) ); 
//网站当前域名链接
define("WEBURL","http://localhost");

/*数据库连接*/
define('DBHOST','127.0.0.1');
//数据库用户名
define('DBUSER','root');
//数据库密码
define('DBPASS','123456');
//数据库名
define('DBNAME','wecba_org');
//数据编码
define('DBCHARSET','UTF8');





?>
