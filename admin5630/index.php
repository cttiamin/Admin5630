<?php
require_once('global.php');


/*用户退出*/
if(isset($_GET['SignOut']))
{
	intval($_GET['SignOut']);
	
	$action = new loginAction();
	$action->userLoginOut();
}


require_once(JMADMINTPL.'/index.htm');
?>
