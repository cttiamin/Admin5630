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
<title>ͼƬ�ϴ�</title>
<style type="text/css">
body{font-size: 9pt;}
input{background-color:#CCCCCC;border: 1px inset #CCCCCC;color:#FFFFFF; }
</style>
</head>

<body>
<p>

<?php
$watermark=0;      //�Ƿ񸽼�ˮӡ(1Ϊ��ˮӡ,����Ϊ����ˮӡ);
$watertype=1;      //ˮӡ����(1Ϊ����,2ΪͼƬ)
$waterposition=2;     //ˮӡλ��(1Ϊ���½�,2Ϊ���½�,3Ϊ���Ͻ�,4Ϊ���Ͻ�,5Ϊ����);
$waterstring="www.jm088.com";  //ˮӡ�ַ���
$waterimg="xplore.gif";  //ˮӡͼƬ
$imgpreview=1;     		 //�Ƿ�����Ԥ��ͼ(1Ϊ����,����Ϊ������);
$imgpreviewsize=1/2;    //����ͼ����
$uptypes=array(			//�����ϴ�����
    'image/jpg',
    'image/jpeg',
    'image/png',
    'image/pjpeg',
    'image/gif',
    'image/bmp',
    'image/x-png'
);

$filepath="/upfiles/";							//�����ļ��ϴ�·��
$id=$_GET['id'];
if(isset($_FILES['file']['name'])){
	$name=$_FILES['file']['name'];
	$type=$_FILES['file']['type'];
	$filesize=$_FILES['file']['size'];
	$tmp_name=$_FILES['file']['tmp_name'];  
	$up_name=time()+(3600*24*365*3);       //�����ϴ�����ļ���
	
	$image_size = getimagesize($tmp_name);		//��ȡͼƬ��Ϣ
	$pinfo=pathinfo($name);				//����·��
 	$ftype=$pinfo['extension'];		//��ȡͼƬ��׺��
	$destination = $filepath.$up_name.".".$ftype;	//��Ŀ¼��+ʱ��+����,ȷ���ļ�������

	if(!file_exists("..".$filepath)) {
        mkdir("..".$filepath);
    }	

    if(!in_array($type, $uptypes)){		//����ļ�����  
        echo "�ļ����Ͳ���!".$file["type"];
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
    echo " <font color=red>�Ѿ��ɹ��ϴ�</font><br>�ļ���:  <font color=blue>".$filepath.$up_name."</font><br>";
    echo " ���:".$image_size[0];
    echo " ����:".$image_size[1];
    echo "<br> ��С:".$filesize." bytes <br>";
 
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
            die("��֧�ֵ��ļ�����");
            exit;
        }

        imagecopy($nimage,$simage,0,0,0,0,$image_size[0],$image_size[1]);
        imagefilledrectangle($nimage,1,$image_size[1]-15,80,$image_size[1],$white);

        switch($watertype)   {
            case 1:   //��ˮӡ�ַ���
            imagestring($nimage,2,3,$image_size[1]-15,$waterstring,$black);
            break;
            case 2:   //��ˮӡͼƬ
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

        //����ԭ�ϴ��ļ�
        imagedestroy($nimage);
        imagedestroy($simage);
    }

    if($imgpreview==1)  {
    echo "<br>ͼƬԤ��:<br>";
    echo "<img src=\"".$upfile."\" width=".($image_size[0]*$imgpreviewsize)." height=".($image_size[1]*$imgpreviewsize);
    echo " alt=\"ͼƬԤ��:\r�ļ���:".$upfile."\r�ϴ�ʱ��:\">";
    }
	
	
	//echo $id;	echo "<br>";	echo $upfile;	exit();
	
	echo "<SCRIPT language=javascript>";
	echo "window.opener.document.form1.$id.value='".$upfile2."';";
	echo "window.close();";
	echo "</script>";
	}else{
	echo"��ѡ����ļ����Ͳ���ͼƬ��ʽ";
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
      <input type="submit" name="Submit" value=" �� ʼ �� �� " />
      <br />
      <br />
	�����ϴ����ļ�����Ϊ:<?php echo implode(', ',$uptypes)?>
    &nbsp;</p>
  </div>
</form>
</body>
</html>
