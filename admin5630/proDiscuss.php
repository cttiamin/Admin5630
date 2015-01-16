<?php
require_once('global.php');

$pageurl = $_SERVER['PHP_SELF']."?a=".$a."&m=".$m;//地址
$proDiscuss = new proDiscussAction();
$test = $proDiscuss->test();















require_once(JMADMINTPL.'/proDiscuss.htm');
?>
