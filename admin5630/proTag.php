<?php
require_once('global.php');

$pageurl = $_SERVER['PHP_SELF']."?a=".$a."&m=".$m;//地址
$proTag = new proTagAction();
$test = $proTag->test();















require_once(JMADMINTPL.'/proTag.htm');
?>
