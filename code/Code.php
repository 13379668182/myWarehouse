<?php
//验证码类

$code = new Code();
$code -> outImage();
class Code {
	//验证码个数
	protected $number;
	//验证码类型
	protected $codeType;
	//图像宽度
	protected $width;
	//图像高度
	protected $height;
	//图像资源
	protected $image;
	//验证码字符串
	protected $code;

	public function __construct($number = 5, $codeType = 2, $width = 100, $height = 40) {
		//初始化妆镜的成员属性
		$this -> number = $number;
		$this -> codeType = $codeType;
		$this -> width = $width;
		$this -> height = $height;

		//生成验证码函数
		$this -> code = $this -> createCode();

	}

	//调用对象本身没有这个成语变量的时候会调用此方法
	public function __get($name) {
		if ($name == 'code') {
			return $this -> code;
		}
		return false;
	}

	//销毁画布资源
	public function __destruct() {
		imagedestroy($this -> image);
	}

	//生成验证码
	protected function createCode() {
		//通过你的验证码类型给你生成不同的验证码
		switch ($this->codeType) {
			case 0 :
				//纯数字
				$code = $this -> getNumberCode();
				break;
			case 1 :
				//纯字母
				$code = $this -> getCharCode();
				break;
			case 2 :
				//字母和数组组合
				$code = $this -> getNumCharCode();
				break;
			default :
				die('传递的类型为(0,1,2)这三个数,0代表纯数字,1代表纯字母,2代表数字与字母组合');
				break;
		}
		return $code;
	}

	//纯数字的方法
	protected function getNumberCode() {
		//随机创建一个0到9的数组并且以字符串的形式
		$str = join('', range(0, 9));
		//随机打乱字符串并截取$this->numner个
		return substr(str_shuffle($str), 0, $this -> number);
	}

	//纯字符的方法
	protected function getCharCode() {
		//随机创建一个字符 并以字符的形式
		$str = join('', range('a', 'z'));
		//把字符串转换成大写 在拼接起来
		$str = $str . strtoupper($str);
		return substr(str_shuffle($str), 0, $this -> number);
	}

	//字符与数组的组合方法
	protected function getNumCharCode() {
		$numStr = $this -> getNumberCode();
		$charStr = $this -> getCharCode();
		$str = $numStr . $charStr;
		return substr(str_shuffle($str), 0, $this -> number);
	}

	//创建画布
	protected function createImage() {
		$this -> image = imagecreatetruecolor($this -> width, $this -> height);

	}

	//给画布填充颜色
	protected function fillBack() {
		imagefill($this -> image, 0, 0, $this -> lightColor());
	}

	//背景色
	protected function lightColor() {
		return imagecolorallocate($this -> image, mt_rand(130, 255), mt_rand(130, 255), mt_rand(130, 255));
	}

	//前景色
	protected function darkColor() {
		return imagecolorallocate($this -> image, mt_rand(0, 120), mt_rand(0, 120), mt_rand(0, 120));
	}

	//把验证码填充到画布中
	protected function drawChar() {
		$width = ceil($this -> width / $this -> number);
		for ($i = 0; $i < $this -> number; $i++) {
			$x = mt_rand($i * $width + 5, ($i + 1) * $width - 10);
			$y = mt_rand(0, $this -> height - 15);
			imagechar($this -> image, 5, $x, $y, $this -> code[$i], $this -> darkColor());
		}
	}

	//添加干扰元素
	protected function drawDisturb() {
		for ($i = 0; $i < 150; $i++) {
			$x = mt_rand(0, $this -> width);
			$y = mt_rand(0, $this -> height);
			imagesetpixel($this -> image, $x, $y, imagecolorallocate($this -> image, mt_rand(130, 255), mt_rand(130, 255), mt_rand(130, 255)));
		}
	}

	//显示
	protected function show() {
		header('Content-Type:image/png');
		imagepng($this -> image);
	}

	//输出画布的方法
	public function outImage() {
		//创建画布
		$this -> createImage();
		//填充背景色
		$this -> fillBack();
		//将验证码字符串画到画布中
		$this -> drawChar();
		//添加干扰元素
		$this -> drawDisturb();
		//输出并且显示
		$this -> show();
	}

}
?>