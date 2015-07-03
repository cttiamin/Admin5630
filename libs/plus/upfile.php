<?php
    /**
     * upfile
     **/
    class upfile
    {
        
        function __construct()
        {
            echo "upfile";
        }
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>图片上传</title>
<style type="text/css">
body{font-size: 9pt;}
input{background-color:#CCCCCC;border: 1px inset #CCCCCC;color:#FFFFFF; }
</style>
</head>

<body>
<p>

<?php
$watermark=0;      //是否附加水印(1为加水印,其他为不加水印);
$watertype=1;      //水印类型(1为文字,2为图片)
$waterposition=2;     //水印位置(1为左下角,2为右下角,3为左上角,4为右上角,5为居中);
$waterstring="www.jm088.com";  //水印字符串
$waterimg="xplore.gif";  //水印图片
$imgpreview=1;     		 //是否生成预览图(1为生成,其他为不生成);
$imgpreviewsize=1/2;    //缩略图比例
$uptypes=array(			//允许上传类型
    'image/jpg',
    'image/jpeg',
    'image/png',
    'image/pjpeg',
    'image/gif',
    'image/bmp',
    'image/x-png'
);

$filepath="/upfiles/";							//设置文件上传路径
$id=$_GET['id'];
if(isset($_FILES['file']['name'])){
	$name=$_FILES['file']['name'];
	$type=$_FILES['file']['type'];
	$filesize=$_FILES['file']['size'];
	$tmp_name=$_FILES['file']['tmp_name'];  
	$up_name=time()+(3600*24*365*3);       //定义上传后的文件名
	
	$image_size = getimagesize($tmp_name);		//获取图片信息
	$pinfo=pathinfo($name);				//返回路径
 	$ftype=$pinfo['extension'];		//获取图片后缀名
	$destination = $filepath.$up_name.".".$ftype;	//　目录名+时间+类型,确保文件不重名

	if(!file_exists("..".$filepath)) {
        mkdir("..".$filepath);
    }	

    if(!in_array($type, $uptypes)){		//检查文件类型  
        echo "文件类型不符!".$file["type"];
		$ok=0;
        exit;
    }else{
		$up_name.=".".$ftype;
		$ok=1;
	}
	
	if($ok==1){
	$upfile2=$filepath.$up_name;
	$upfile = "..".$upfile2;

	move_uploaded_file( $tmp_name, $upfile); //move_uploaded_file($tmp_name,"..".$upfile);
	
	$pinfo=pathinfo($upfile);
    $fname=$pinfo[basename];
    echo " <font color=red>已经成功上传</font><br>文件名:  <font color=blue>".$filepath.$up_name."</font><br>";
    echo " 宽度:".$image_size[0];
    echo " 长度:".$image_size[1];
    echo "<br> 大小:".$filesize." bytes <br>";
 
	    if($watermark==1){
        $iinfo=getimagesize($upfile,$iinfo);
        $nimage=imagecreatetruecolor($image_size[0],$image_size[1]);
        $white=imagecolorallocate($nimage,255,255,255);
        $black=imagecolorallocate($nimage,0,0,0);
        $red=imagecolorallocate($nimage,255,0,0);
        imagefill($nimage,0,0,$white);
        switch ($iinfo[2]) {
            case 1:
            $simage =imagecreatefromgif($upfile);
            break;
            case 2:
            $simage =imagecreatefromjpeg($upfile);
            break;
            case 3:
            $simage =imagecreatefrompng($upfile);
            break;
            case 6:
            $simage =imagecreatefromwbmp($upfile);
            break;
            default:
            die("不支持的文件类型");
            exit;
        }

        imagecopy($nimage,$simage,0,0,0,0,$image_size[0],$image_size[1]);
        imagefilledrectangle($nimage,1,$image_size[1]-15,80,$image_size[1],$white);

        switch($watertype)   {
            case 1:   //加水印字符串
            imagestring($nimage,2,3,$image_size[1]-15,$waterstring,$black);
            break;
            case 2:   //加水印图片
            $simage1 =imagecreatefromgif("xplore.gif");
            imagecopy($nimage,$simage1,0,0,0,0,85,15);
            imagedestroy($simage1);
            break;
        }

        switch ($iinfo[2])  {
            case 1:
            //imagegif($nimage, $destination);
            imagejpeg($nimage, $upfile);
            break;
            case 2:
            imagejpeg($nimage, $upfile);
            break;
            case 3:
            imagepng($nimage, $upfile);
            break;
            case 6:
            imagewbmp($nimage, $upfile);
            //imagejpeg($nimage, $destination);
            break;
        }

        //覆盖原上传文件
        imagedestroy($nimage);
        imagedestroy($simage);
    }

    if($imgpreview==1)  {
    echo "<br>图片预览:<br>";
    echo "<img src=\"".$upfile."\" width=".($image_size[0]*$imgpreviewsize)." height=".($image_size[1]*$imgpreviewsize);
    echo " alt=\"图片预览:\r文件名:".$upfile."\r上传时间:\">";
    }
	
	
	//echo $id;	echo "<br>";	echo $upfile;	exit();
	
	echo "<SCRIPT language=javascript>";
	echo "window.opener.document.form1.$id.value='".$upfile2."';";
	echo "window.close();";
	echo "</script>";
	}else{
	echo"你选择的文件类型不是图片格式";
	}
}
?>
</p>
<p>&nbsp;</p>
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <div align="center">
    <p>
      <input type="file" name="file" />
    </p>
    <p>
      <input type="submit" name="Submit" value=" 开 始 上 传 " />
      <br />
      <br />
	允许上传的文件类型为:<?php echo implode(', ',$uptypes)?>
    &nbsp;</p>
  </div>
</form>
</body>
</html>
