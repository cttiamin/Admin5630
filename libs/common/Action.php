<?php
class Action 
{
    function __construct(){
        //echo "<br/>已经载入Action文件<br/>";
    }

    /* 检测路径是否存在,不存在则创建 */
    public function isDir($url)
    {
        if(is_dir($url))
        {
            return true;           
        }
        else
        {
             //以递归的方式创建
            mkdir($url, 0777, true);      // 777：最高权限
            return true;
        }

    }


   /**
    * 后台通用信息提示
    */
    public function getMesssage($url='', $show = '操作已成功！',$style='', $top='') {
		switch($style){
		case "error":
				$style = "error";
					break;
		case "success":
				$style = "success";
					break;
		case "information":
				$style = "information";
				break;
		default: $style = "attention";  
        }
        /*是否在全屏打开*/
        $windowTop = '';
        if($top == 1) 
        {
            $windowTop = '<script type="text/javascript">if(self!=top){ window.open(self.location, "_top"); } </script> ';
        }
      // <meta http-equiv="refresh" content="2; URL='.WEBURL.$url.'"/>  
	 /* <script type="text/javascript" src="/admin5630/resources/scripts/jquery-1.3.2.min.js"></script>*/
        $msg = '<!doctype html>
<html lang="en">
  <head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />		
        <title>消息提示</title>
        <link rel="stylesheet" href="/admin5630/resources/css/inc.css" type="text/css" media="screen" />		
        <meta http-equiv="refresh" content="2; URL='.WEBURL.$url.'"/>
        '.$windowTop.'
</head>
<style type="text/css">
body{  background: none repeat scroll 0 0 #fff;}
</style>
<body >
<div class="notification '.$style.' png_bg" style="width:500px; margin:100px auto">			
				<div>
                '.$show.'<br/>
                2秒后返回指定页面！<br />
				如果浏览器无法跳转，<a href="'.WEBURL.$url.'" class=parent>请点击此处</a>
				</div>
</div>

</body>
</html> ';  
		echo $msg;
		exit ();
	}


} //end class

?>
