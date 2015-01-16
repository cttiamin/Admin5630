<?php
require_once('global.php');

$pageurl = $_SERVER['PHP_SELF']."?a=".$a."&m=".$m;//地址
$arcTag = new arcTagAction();
//$test = $arcTag->test();










require_once(JMADMINTPL.'/arcTag.htm');
?>
