<?php
require_once('global.php');

$proUpFile = new proUpFileAction();
$strInc = new strInc();//字符处理

//图片id的判断
$id = isset($_GET['id']) ? $strInc->inject_check($_GET['id']) : false;//字节名
$i = isset($_GET['i']) ? $strInc->inject_check($_GET['i']) : false; //id
$c = isset($_GET['c']) ? $strInc->inject_check($_GET['c']) : false; //类别ＩＤ
if(!($id && $i && $c))
{
    exit('参数错错');
}

//上传图片
if(!empty($_FILES['upfile']['name']))
{
    //上传出错
    if($_FILES['upfile']['error']>0)
    {
        echo $proUpFile->upError();
        exit();
    }
    else
    {   
        //定义上传类型
        $proUpFile->fileType = array('.jpg','.png','.gif','.bmp');
        //设置上传目录	/products
        $picAll = $proUpFile->upFile('/products/'.date('ymd'), $i, $c);
        //缩略图路径配置
        $pic =  $picAll[0].'/'.$picAll[1].$picAll[2];
        $pic_in_2 = $picAll[0].'/'.$picAll[1].'e2'.$picAll[2];
        $pic_in_3 = $picAll[0].'/'.$picAll[1].'e3'.$picAll[2];
        
        //生成缩略图
        $pic2 = $proUpFile->thumb($pic, $pic_in_2, 3, 399, 299 );   //中图
        $pic3 = $proUpFile->thumb($pic, $pic_in_3, 3, 86, 65);  //小图
        $pic1 = $proUpFile->thumb($pic, $pic, 3, 800, 600);    //大图
        
        //输出信息
        $picData = array(); //插入数库
        echo "<div align=\"center\"><h5>缩略图生成完毕！</h5>";
        echo $picData[0] = '/upfiles/products/'.date('ymd').'/'.$picAll[1].$picAll[2];
        echo "<br/>";
        echo $picData[1] = '/upfiles/products/'.date('ymd').'/'.$picAll[1].'e2'.$picAll[2];
        echo "<br/>";
        echo $picData[2] = '/upfiles/products/'.date('ymd').'/'.$picAll[1].'e3'.$picAll[2];
        echo "</div>";

        //写入数据库
        $proUpFile->proUpFileData ($picData, $id, $i);

        //输出信息
        $picData = array(); //插入数库
        echo "<div align=\"center\"><h5>数据更新完毕！</h5>";
        echo "<font color=\"red\">请关闭页面，在之间页面刷新！</font>";
        echo "</div>";
  
    
    }
}


require_once(JMADMINTPL.'/proUpFile.htm');

?>

