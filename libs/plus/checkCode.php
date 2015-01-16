<?php
/**
 * Mainterian : etcphp@sohu.com
 * LastChange : 2012-2-4
   验证码
 */

class checkcode{
	//资源
	private $img;
	//画布宽度
	public $width=85;
	//画布高度
	public $height=25;
	//背景颜色
	public $bg_color="#ffffff";
	//验证码
	public $code;
	//验证码的随机种子
	public $code_str="329832983DSDSKDSLKQWEWQ2lkfDSFSDjfdsfdsjwlkfj93290KFDSKJFDSOIDSLK";
	//验证码长度
	public $code_len=4;
	//验证码字体
	public $font;
	//验证码字体大小
	public $font_size=16;
	//验证码字体颜色 
	public $font_color = "#000000";
	/**
	 * 构造函数
	 */
	public function __construct(){
		//设置字体
		$this->font= "font".DIRECTORY_SEPARATOR.'msyh.ttf';
	}
	/**
	 * 生成验证码
	 */
	private function create_code(){
		$code = '';
		for($i=0;$i<$this->code_len;$i++){
			$code.=$this->code_str[mt_rand(0, strlen($this->code_str)-1)];
		}
		$this->code = $code;
	}
	/**
	 * 返回验证码
	 */
	public function getcode(){
		return strtoupper($this->code);
	}
	/**
	 * 建画布
	 */
	public function getimage(){
		$w = $this->width;
		$h = $this->height;
		$bg_color= $this->bg_color;
		if(!$this->checkgd())return false;
		$img = imagecreatetruecolor($w, $h);
	    $bg_color = imagecolorallocate($img, 
	    hexdec(substr($bg_color, 1,2)), hexdec(substr($bg_color, 3,2)), hexdec(substr($bg_color, 5,2)));
	    imagefill($img, 0, 0, $bg_color);
	    $this->img = $img;
	    $this->create_font();
	    $this->create_pix();
	    $this->show_code();
	}
	/**
	 * 写入验证码文字 
	 */
    private function create_font()
    {
		$this->create_code();
		$color= $this->font_color;
		$font_color = imagecolorallocate($this->img, 
	    hexdec(substr($color, 1,2)), hexdec(substr($color, 3,2)), hexdec(substr($color, 5,2)));
	     $x = $this->width/$this->code_len;
        for($i=0;$i<$this->code_len;$i++)
        {
	    	imagettftext($this->img, $this->font_size, mt_rand(-30, 30), $x*$i+mt_rand(3,6), mt_rand($this->height/1.2,$this->height-5), $font_color, $this->font, $this->code[$i]);
	    }
	    $this->font_color = $font_color;
	}
	/**
	 * 画线
	 */
	private function create_pix(){
		$pix_color= $this->font_color;
		for($i=0;$i<100;$i++){
			imagesetpixel($this->img, mt_rand(0, $this->width),mt_rand(0, $this->height), $pix_color);
		}
		for($j=0;$j<2;$j++){
			imagesetthickness($this->img, mt_rand(1, 2));
			imageline($this->img, mt_rand(0, $this->width), mt_rand(0, $this->height),  mt_rand(0, $this->width), mt_rand(0, $this->height), $pix_color);
		}
	}
	/**
	 * 显示验证码
	 */
	private function show_code(){
		header("Content-type:image/png");
		imagepng($this->img);
		imagedestroy($this->img);
	}
	/**
	 * 验证GD库是不否打开imagepng函数是否可用
	 */
	private function checkgd(){
		return extension_loaded('gd')&&function_exists("imagepng");
	}
	

}
