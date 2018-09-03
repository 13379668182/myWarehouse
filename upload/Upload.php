<?php

$up = new Upload();
$up->uploadFile('fm');
//文件上传类
class Upload
{
	//文件上传保存路径
	protected $path = './upload/';
	//允许文件上传的后缀
	protected $allowSuffix = ['jpg','jpeg','gif','wbmp','png'];
	//允许的MIME 类型
	protected $allowMime = ['image/jpeg','image/gif','image/wbmp','image/png','image/jpg'];
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
	protected function setOption($key,$value)
	{
		//得到所有的成员属性
		$keys = array_keys(get_class_vars(__CLASS__));
		//如果$key是我的成员属性，那么设置
		if(in_array($key, $keys)){
			$this->$key = $value;
		}
	}
	
	
	//文件上传函数
	//$key 就是你input框中的name属性值
	public function uploadFile($key)
	{
	 	//判断有没有设置路径 path
	 	if(empty($this->path)){
	 		$this->setOption('errorNumber','没有设置文件保存路径');
			return false;
	 	}
	 	//判断该路径是否存在,是否可写
	 	if(!$this->check()){
	 		$this->setOption('errorNumber','设置的文件保存路径不存在或不可写');
			return false;
	 	}
	 	//判断$_FILES里面的error信息是否为0，如果为0，说明文件信息在服务器端可以直接 获取，提取信息保存到成员属性中
	 	
	 	$error = $_FILES[$key]['error'];
		if($error){
			$this->setOption('errorNumber',$error);
			return false;
		}else{
			//提取文件相关信息并且保存到成员属性中
			$this->getFileInfo($key);
			
		}
		
	 	//判断文件的大小、mime、后缀是否符合
	 	if(!$this->checkSize() || !$this->checkMime() || !$this->checkSuffix()){
	 		return false;
	 	}
			
	 	//得到新的文件名字
	 	$this ->newName = $this ->createNewName();
	 	//判断是否是上传文件，并且移动上传文件	
	
	 	if(is_uploaded_file($this->tmpName)){
	 		
	 		if(move_uploaded_file($this->tmpName, $this->path.$this->newName)){
	 			return $this->path.$this->newName;
	 		}else{
	 			$this->setOption('errorNumber','文件上传失败');
	 		}
	 	}else{
	 		$this->setOption('errorNumber','不是上传文件只是个临时文件');
			return false;
	 	}
	}
	
	//检验文件夹
	protected function check()
	{
		//检验文件夹是否存在 如不存在就创建
		if(!file_exists($this->path) || !is_dir($this->path)){
			return mkdir($this->path,0777,true);
		}
		//判断文件夹是否可写
		if(!is_writeable($this->path)){
			return chmod($this->path, 0777);
		}
		
		return true;
		
	}
	
	
	protected function getFileInfo($key)
	{
		//得到文件名字
		$this->oldName = $_FILES[$key]['name'];
		//得到文件的mime类型
		$this->mime = $_FILES[$key]['type'];
		//得到文件临时路径
		$this->tmpName = $_FILES[$key]['tmp_name'];
		//得到文件的大小
		$this->size = $_FILES[$key]['size'];
	
		//得到文件后缀
		$this->suffix = pathinfo($this->oldName)['extension'];
		
	}
	
	//判断文件大小
	protected function checkSize()
	{
		
		if($this->size > $this->maxSize){
			$this-> setOption('errorNumber','上传文件大小不应超过'.$this->maxSize);
			return false;
		}else{
			return true;
		}
	}
	
	
	//判断文件是不 是mime类型
	protected function checkMime()
	{
		
		if(!in_array($this->mime, $this->allowMime)){
			$this-> setOption('errorNumber','上传的文件不是mime类型');
			return false;
		}else{
			
			return true;
		}
	}
	//判断文件的后缀是否符合
	protected function checkSuffix()
	{
		if(!in_array($this->suffix, $this->allowSuffix)){
			$this-> setOption('errorNumber','上传的文件后缀不符合规范');
			return false;
		}else{
			return true;
		}
	}
	
	//得到文件的新名字
	protected function createNewName()
	{
		if($this->isRandName){
			$name = $this->prefix.uniqid().'.'.$this->suffix;
		}else{
			$name = $this->prefix.$this->oldName;
		}
		return $name;
	}
	
}
?>