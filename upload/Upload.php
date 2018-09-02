<?php
//文件上传类
class Upload
{
	//文件上传保存路径
	protected $path = './upload/';
	//允许文件上传的后缀
	protected $allowSuffix = ['jpg','jpeg','gif','wbmp','png'];
	//允许的MIME 类型
	protected $allowMime = ['image/jpeg','image/gif','image/wbmp','image/png'];
	//允许上传的文件大小 2M
	protected $maxSize = 2000000;
	//是否启用随机名
	protected $isRandName = true;
	//文件前缀
	protected $prefix = 'up_';
	
	//错误号码和错误信息
	protected $errorNumber;
	protected $errorInfo;
	
	//文件的信息
	//文件名
	protected $oldName;
	//文件的后缀
	protected $suffix;
	//文件的大小
	protected $size;
	//文件的mime
	protected $mime;
	//临时文件的路径
	protected $tmpName;
	//文件的新名字
	protected $newName;
	
		
	//构造方法
	public function __construct($arr = [])
	{
		foreach ($arr as $key => $value) {
			$this->setOption($key,$value);
		}
	}
	
	//判断这个$key是不是我的成员属性 如果是，则设置
	protected function setOPtion($key,$value)
	{
		//得到所有的成员属性
		$keys = array_keys(get_class_vars(__CLASS__));
		//如果$key是我的成员属性，那么设置
		if(in_array($key, $keys)){
			$this->$key = $value;
		}
	}
	
	public function uploadFile($key)
	{
		
	}
}
?>