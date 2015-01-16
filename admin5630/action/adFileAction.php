<?php
/** 2011-09-17
 * jm edit 文章栏目管理
 **/
define('ADARCACTION', ereg_replace("[/\\]{1,}", '/', dirname(__FILE__) ) ); 
require_once(ADARCACTION.'/config.php');

class adFileAction extends Action
{
    private $str = '';
    function __construct()
    {
        
    }
    //
    function adFileList($dirname, $type = '')
    {
        if($type == '')
	    {
		    $dirInfo = glob($dirname.'/*');
    	}
    	else
    	{
    		$dirInfo = glob($dirname.'/*.'.$type);
    	}
	    
    	foreach ($dirInfo as $v)
    	{    		
    		if(is_dir($v))
    		{
			globDir($v);
    		}
    	}
    	return $dirInfo;
    }

    function getimage($dir)
    {
        
        $this->str =  getimagesize($dir);
        return $this->str;
    }

    function __isset($var){ }
    function __unset($c){
        unset ($this->$c); 
    }
    function __destruct() {}



}


?>

