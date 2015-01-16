<?php
/** 2011-12-21
 * 产品图片上传
 **/
define('ADARCACTION', ereg_replace("[/\\]{1,}", '/', dirname(__FILE__) ) ); 
require_once(ADARCACTION.'/config.php');

class proUpFileAction extends Action
{
    private $db;
    private $str;
    private $class_arr;
    private $table;
    
    //上传部分
    public $fileType;   //充许上传的图片类型
        
    //水印部分
    public $watermark_on = 1;//是否开启水印
    public $water_img = "pic/logo1.jpg";//水印图片    
    public $pos = 1;//水印的位置    
    public $pct = 80;//水印透明度    
    public $quality = 80;//图像的压缩比    
    public $text = "jm088.com";//水印的文字内容
    public $text_size = 12;//字体大小
    public $color = "#000000"; //文字颜色
    public $font = "pic/msyh.ttf";//字体

    //缩略图部分
    private $thumb_on = 1;//是否开启缩略图功能
    public $thumb_type = 1;//生成缩略图的方式
    public $thumb_width = 200;//缩略图的宽度
    public $thumb_height = 200;//缩略图的高度
    public $thumb_fix = '_jm088_products';//缩略图后缀

    function __construct()
    {
		$this->db = new mysqlDb();
		$this->str = '';
		$this->class_arr = '';
		$this->table = 'pro_pic'; 
	
		$this->watermark_on = 0; 
	
		$this->thumb_on = 1;
		$this->thumb_fix = '_088';
		$this->thumb_type = 5;
		//$config['watermark']
		$this->conf ( 1 );
    }
    private function conf( $config )
    {
		$this->water_img = 'logo.png';
		//$this->pos =　1;
		$this->pct = 80;
		$this->text = '088山货';
    }

    /*判断文件上传时错误类型
     * */
    function upError()
    {
            switch ($_FILES['upfile']['error'])
            {
			case 1:
				$errorMsg = "上传超过了PHP.INI所规定的上传大小";
				break;
			case 2:
				$errorMsg = "文件大小超过了前台表单指定大小";
				break;
			case 3:
				$errorMsg = "文件上传不完整";
				break;
			case 4:
				$errorMsg = "没有上传文件";
				break;
			default :
				$merror = "其它原因";
            }
        return $errorMsg;
    }

    /*  文件上传处理
     * $i: 产品id
     *  $c: 产品类别id
     *  $dirName: 路径
     *  
     * */
    function upFile($dirName='', $i='', $c=''){

        /*对上传类型进行判断  */
        $this->fileType = empty($this->fileType) ? $this->fileType = array('.jpg','.png','.gif') :  $this->fileType;
        
		$upFileType = strtolower(strrchr($_FILES['upfile']['name'],'.'));
        if(!in_array($upFileType, $this->fileType))
        {
            echo "上传类型不合法，请选择图片类型!";
		    exit();
        }

        //创建目录
        if(empty($dirName) )
        {
            $dirName = ADARCACTION.'/../../upfiles'.date('ymd');
        }else{
            $dirName = ADARCACTION.'/../../upfiles'.$dirName;
        }   
    
		if(!is_dir($dirName)){
			mkdir($dirName);
        }

        //如果上传文件存在　
        if(is_uploaded_file($_FILES['upfile']['tmp_name']))
        {   
            //图片名称: 产品类别id　'e' 产品id 'e' 随机数字. 后缀名 
            $this->str = array();
            $this->str[0] = "$dirName";
            $this->str[1] = $c . 'e' .$i. 'e' .time();
            $this->str[2] = $upFileType;

            $toFileName = $dirName.'/'. $c . 'e' .$i. 'e' .time().$upFileType;
            //移动
            if(move_uploaded_file($_FILES['upfile']['tmp_name'], $toFileName))
            {   
                $imgInfo = getimagesize($toFileName);
                echo "<div align=\"center\">";
                echo "<h5>文件上传成功!</h5>";
                echo "文件像素：".$imgInfo[0].'*'.$imgInfo[1].'<br/>';
                echo "文件路径：".strrchr($toFileName, '/');
                echo "</div>";
			}else{
				echo "错误！ 上传移动失败";
			}
        }
        else
        {
			echo "错误！不是上传文件！";
        }
        
        return $this->str;
    }


    //(2)环境验证
    private function check($img)
    {        
        $type = array('.jpg', '.jpeg', '.png', '.gif');
        $img_type = strtolower(strrchr($img, '.')); //转成小写
        return extension_loaded('gd') && file_exists($img) && in_array($img_type, $type);
    }

    //(6)图片尺寸,获取缩略图信息
    //@param $img_w     原图宽度
    //@param $img_h     原图高度
    //@param $t_w       缩略图的宽度
    //@param $t_h       缩略图的高度
    //@param $t_type    处理方式
    //return array      
    private function thumb_size($img_w, $img_h, $t_w, $t_h, $t_type)
    {
        //初始化缩略图
        $w = $t_w;
        $h = $t_h;
        //初始化原图尺寸
        $cut_w = $img_w;
        $cut_h = $img_h;
        //若缩略图小于原图
        if($img_w <= $t_w && $img_h <= $t_h )
        {
            $w = $img_w;
            $h = $img_h;
        }
        elseif(!empty($type) && $t_type > 0)
        {
            switch($t_type)
            {
            case 1: //固定宽度, 高度自增
                $h = $t_w / $img_w * $img_h;
                break;
            case 2: //固定高度 宽度自增
                $w = $t_h / $img_h * $img_w;
                break;
            case 3: //固定宽度 高度裁切
                $cut_h = $img_w / $t_w * $t_h;
                break;
            case 4: //固定高度 宽度裁切
                $cut_w = $img_h / $t_h * $t_w;
            case 5:
            default:    //等比缩放
                if(($img_w / $t_w) > ($img_h / $t_h))//宽
                {
                    $h = $t_w / $img_w * $img_h;
                }
                elseif(($img_w / $t_w) < ($img_h / $t_h))//长
                {
                    $w = $t_h / $img_h * $img_w;
                }
                else
                { $w = $t_w; $h = $t_h; }
            }
        }
		$arr [0] = $w;      //缩宽
		$arr [1] = $h;      //缩高
		$arr [2] = $cut_w;  //原图宽
		$arr [3] = $cut_h;  //原图高
		return $arr;
    }

    //(5)图片裁切处理部分
    //@param $img       操作的图片路径
    //@param $outfile   输出的文件路径
    //@param $t_type    裁切图片的方式   
    //@param $t_w       缩略图宽度
    //@param $t_h       缩略图高度
    //@param $string    
    public function thumb($img, $outfile='', $t_type='', $t_w='', $t_h='')
    {
        if(! $this->thumb_on || ! $this->check ( $img )) return false;
        //基础配置
        $t_type = $t_type ? $t_type : $this->thumb_type;
        $t_w = $t_w ? $t_w : $this->thumb_width;
        $t_h = $t_h ? $t_h : $this->thumb_height;
        
        //获得原图像的信息
        $img_info = getimagesize($img);
        $img_w = $img_info[0];
        $img_h = $img_info[1];
        $img_type = image_type_to_extension($img_info[2]);//获取图像后缀
        
        //获得相关尺寸
        $thumb_size = $this->thumb_size ($img_w, $img_h, $t_w, $t_h, $t_type);
        
        //原始图像资源
        $func = "imagecreatefrom".substr($img_type, 1);
        $res_img = $func($img);
        //eval("\$res_img = createfrom".substr($img_info, 1)."('$img');");

        //缩略图资源
        if($img_type =='.gif' || $img_type == ".png")
        {
            $res_thumb = imagecreate($thumb_size[0], $thumb_size[1]);   //缩宽高
            $color = imagecolorallocate($res_thumb, 255, 0, 0);         //分配颜色
        }
        else
        {   
            //imagecreate 容易出错, 创建真彩色
            $res_thumb = imagecreatetruecolor($thumb_size[0], $thumb_size[1]);
        }

        //制作缩略图
        if( !function_exists ('imagecopyresampled'))
        {
            //($缩图, $, $缩_x , $缩_y , $源_x ,$源_y , $缩_w , $缩_h , $源_w , $源_h )
             imagecopyresampled($res_thumb, $res_img, 0, 0, 0, 0, $thumb_size [0], $thumb_size [1], $thumb_size [2], $thumb_size [3] );
        }
        else
        {
            //(缩图 , $, $缩_x , $缩_y , $源_x ,$源_y , $缩_w , $缩_h , $源_w , $源_h )
            imagecopyresized($res_thumb, $res_img, 0, 0, 0, 0, $thumb_size [0], $thumb_size [1], $thumb_size [2], $thumb_size [3] );

        }
        //处理透明色
        if($img_type == '.gif' || $img_type == '.png')
        {   
            //($image , $color )            
            imagecolortransparent($res_thumb,$color);
        }
        //配置输出文件名
        $outfile = $outfile ? $outfile : substr($img, 0, strpos($img, '.')). $this->thumb_fix.$img_type;

        $func = 'image'.substr($img_type, 1);
        $func($res_thumb, $outfile);//生成新图片
        if(isset($res_thumb))imagedestroy($res_thumb);//清空资源
        if(isset($res_img))imagedestroy($res_img);

        return $outfile;
    }


    //(1)水印处理类
    // @param $img 			操作的图像
	// @param $out_img		另存的图像
	// @param $water_img 	水印图片
    // @param $text			文字水印内容
	// @param $pct			透明度
	// return boolean
    public function watermark($img, $out_img='', $water_img='', $pos='', $text='', $pct='')
    {
        //验证原图
        if(! $this->check($img) || !$this->watermark_on ) return false;
        //验证水印图像
        $water_img = $water_img ? $water_img : $this->water_img;
        //if($this->check($water_img)) $waterimg_on = 1;
        $waterimg_on = $this->check($water_img) ? 1 : 0;       
        //判断另存图像
        $out_img = $out_img ? $out_img : $img;
        //水印位置
        $pos = $pos ? $pos : $this->pos;
        //水印文字
        $text = $text ? $text : $this->text;
        //水印透明度
        $pct = $pct ? $pct : $this->pct;
        //获得原图
        $img_info = getimagesize($img);
        $img_w = $img_info[0];  //宽
        $img_h = $img_info[1];  //高
        //获得水印信息
        if($waterimg_on)
        {
            //水印图像
            $w_info = getimagesize($water_img);
            $w_w = $w_info[0];
            $w_h = $w_info[1];
            
            //判断水印类型
            switch ($w_info[2])
            {
            case 1:
                $w_img = imagecreatefromgif($water_img);
                break;
            case 2:
                $w_img = imagecreatefromjpeg($water_img);
                break;
            case 3:
                $w_img = imagecreatefrompng($water_img);
                break;
            }
        }
        else
        {
            //echo $text;
            if(empty($text) || strlen($this->color) !=7) return false;
            //获得文字宽高
            $text_info = imagettfbbox($this->text_size, 0, $this->font, $text);
            $w_w = $text_info[2] - $text_info[6];
            $w_h = $text_info[3] - $text_info[7];

        }
        //建立原图资源
        if( $img_h < $w_h || $img_w < $w_w ) return false;
        switch( $img_info[2]) 
        {
        case 1:
            $res_img = imagecreatefromgif($img);
            break;
        case 2:
            $res_img = imagecreatefromjpeg($img);
            break;
        case 3:
            $res_img = imagecreatefrompng($img);
            break;
        }

        //水印位置的处理方法
        switch($pos)
        {
        case 1:
            $x = $y = 25; //固定 左上
            break;
        case 2:
            $x = ($img_w-$w_w)/2; //源图宽度减掉水印宽度, 上中
            $y = 25;
            break;
        case 3:
            $x = $img_w-$w_w; // 右上
            $y = 25;
            break;
        case 4: 
            $x = 25; //中左
            $y = ($img_h - $w_h)/2;
            break;
        case 5:
            $x = ($img_w - $w_w)/2; //  中 中
            $y = ($img_h - $w_h)/2;
            break;
        case 6:
            $x = $img_w - $w_w; //中右
            $y = ($img_h - $w_h)/2;
            break;
        case 7:
            $x = 25; // 下左
            $y = $img_h - $w_h;
            break;
        case 8:
            $x = ($img_w - $w_w)/2; // 下中
            $y = $img_h - $w_h;
            break;
        case 9:
            $x = $img_w - $w_w; // 下右
            $y = $img_h - $w_h;
            break;
        default :
            $x = mt_rand(25, $img_w - $w_w); //随机
            $y = mt_rand(25, $img_h - $w_h);
        }
        //叠加水印或文字
        if($waterimg_on)
        {
            
            if($w_info[2]==3)
            {
                //去png水印白底
                imagecopy($res_img, $w_img, $x, $y, 0, 0, $w_w, $w_h);
            }
            else
            {
                imagecopymerge($res_img, $w_img, $x, $y, 0, 0, $w_w, $w_h, $pct);
            }    
        }
        else
        {
            $r = hexdec(substr($this->color, 1, 2));
            $g = hexdec(substr($this->color, 3, 3));
            $b = hexdec(substr($this->color, 5, 2));
            //$color = imagecolorallocate($res_img, $this->text_size, 0, $x, $y, $color, $this->font, iconv('utf-8', 'gbk', $text));
            $color = imagecolorallocate($res_img, $r, $g, $b);
            imagettftext($res_img, $this->text_size, 0, $x, $y, $color, $this->font, $text);
        }
        //生成
        switch($img_info[2])
        {
        case 1:
            imagegif($res_img, $out_img);
            break;
        case 2;
            imagejpeg($res_img, $out_img, $this->quality); //压缩比
            break;
        case 3:
            imagepng($res_img, $out_img);
            break;

        }
        //清空资源
        if(isset($res_img)) imagedestroy($res_img);
        if(isset($w_img)) imagedestroy($w_img);
        return true;

    }
    //写入数据库
    function proUpFileData ($path, $id, $i)
    {   
        $id_num = intval(substr( $id, 7));
        $id_name = substr($id, 0, 7);

        foreach($path as $k=>$v)
        {         
            $mod_content = "`pp_url`='$v'";
            $condition = "`pp_id`='". $id_name.$id_num ."' and `p_id` = '$i'";

            $this->db->update($this->table, $mod_content, $condition );

            ++$id_num;
        }
        $this->db->free();
        return 0;

    }


    function __isset($var){}
    function __unset($c){unset ($this->$c);}
    function __destruct(){}




}





?>
