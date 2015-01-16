<?php
/** 2011-09-19
 * jm edit 产品栏目管理
 **/
define('ADARCACTION', ereg_replace("[/\\]{1,}", '/', dirname(__FILE__) ) ); 
require_once(ADARCACTION.'/config.php');
//$str = new strInc();
class proDiscussAction extends Action
{
    protected $db;

    function __construct()
    {
    }
    function test(){
        return "aaaaa";
    }
    function __toString(){}
    function __isset($var){ }
    function __unset($c){
        unset ($this->$c); 
    }
    function __destruct() {

    }

}


?>

