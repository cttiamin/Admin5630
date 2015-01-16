<?php
require_once('global.php');
//  ."?a=".$a."&m=".$m
$pageurl = $_SERVER['PHP_SELF'];//地址
$adFileAction = new adFileAction();
$strInc  = new strInc();

//$rows = $adDeptAction->adDeptSelect();

if(isset($_GET['a']) == 'proPic')
{

    //echo strrchr(__FILE__, DIRECTORY_SEPARATOR);

function scand($dir)
{
    static $i = 0;
    static $d = 0; //文件夹
    $count = '';

    $dirInfo = scandir( $dir );
    foreach ( $dirInfo as $v )
    {
        if( $v != '.' && $v != '..')
        {
            $dirname = $dir . '\\' .$v;
            //echo $dirname .'<br/>';
            
            if(is_dir( $dirname))
            {
                $count['dir'] = ++$d;
                scand( $dirname );               
            }else{       
                echo strrchr($dirname, DIRECTORY_SEPARATOR) .'<br/>';
                $count['file'] = ++$i;
            }
        }
    }
    return $count;
}
    //$count = scand('../upfiles');
    // echo "文件 {$count['file']} 个,文件夹{$count['dir']}个";


    //$info = globDir(JMADMIN.'/../upfiles/product/','jpg');

    $info = $adFileAction->adFileList(JMADMIN.'/../upfiles/product/', 'jpg');


    require_once(JMADMINTPL.'/adFilePic.htm');
}
else
{
    require_once(JMADMINTPL.'/adFile.htm');
}

 /*
//修改获取
if(isset($_POST['Edit']))
{
    
    unset($_POST['Edit']);

    foreach($_POST as $p_name=>$p_values)
    {
        $strInc->inject_check($p_name);
        $strInc->inject_check($p_values);

        $mod_content = "`values`='$p_values'";
        $condition = "`name`='$p_name'";
    }

    $adDeptAction->adDeptUpdate($_POST);

   
    $adDeptAction->getMesssage($pageurl,'修改栏目操作已完毕！','success');
    //unset($adDeptAction->getMesssage);
    exit();
   
}
 */


?>
