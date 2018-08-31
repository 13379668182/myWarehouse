<?php
//验证码类

Code class
{
	//验证码个数
	protected $number;
	//验证码类型
	protected $codeType;
	//图像宽度
	protected $width;
	//图像高度
	protected $height;
	//图像资源
	protected $images;
	//验证码字符串
	protected $code;
	
	
	public function __construct($number,$codeType,$width,$height)
	{
		//初始化妆镜的成员属性
		$this->number = $number;
		$this->codeType = $codeType;
		$this->width = $width;
		$this->height = $height;
		
		//生成验证码函数
		$this->code = $this->createCode();
		
	}
		
	//生成验证码
	protected function createCode()
	{
		//通过你的验证码类型给你生成不同的验证码
		switch ($this->codeType) {
			case 0://纯数字
				$this->getNumberCode();
				break;
			case 1://纯字母
			$this->getCharCode();
				break;
			case 2://字母和数组组合
			$this->getNumCharCode();
				break;
			default:
				die('传递的类型为(0,1,2)这三个数,0代表纯数字,1代表纯字母,2代表数字与字母组合');
				break;
		}
	}	
	
	//纯数字的方法
	protected function getNumberCode()
	{
		
	}
	
	//纯字符的方法
	protected function getCharCode()
	{
		
	}
	
	//字符与数组的组合方法
	protected function getNumCharCode()
	{
		
	}
}
?>