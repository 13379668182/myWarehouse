<?php
/*
 * 分页类
 * */
 $page = new Page(5,60);
 echo '<pre>';
 var_dump($page->allUrl());
 class Page
 {
 	//每页显示多少条数据
 	protected $number;
	
	//一共有多少条数据
	protected $totalCount;

	//当前页
	protected $page;
	
	//总页数
	protected $totalPage;
	
	//url
	
	protected $url;
	
	
	
	//构造方法
	public function __construct($number,$totalCount)
	{
		$this->number = $number;
		$this->totalCount = $totalCount;
		//得到总页数
		$this->totalPage = $this->getTotalPage();
		//得到当前页数
		$this->page = $this->getPage();
		
		//得到url
		$this->url = $this->getUrl();
		echo $this->url;
	}
	
	//得到总页数
	protected function getTotalPage()
	{
		return ceil($this->totalCount / $this->number);
	}
	
	//得到当前页数
	protected function getPage()
	{
		if(empty($_GET['page'])){
			$page = 1;
		}else if($_GET['page'] > $this->totalPage){
			$page = $this->totalPage;
		}else if($_GET['page'] < 1){
			$page = 1;
		}else{
			$page = $_GET['page'];
		}
		return $page;
	}
	
	//得到URL
	protected function getUrl()
	{
		
		//得到协议
		$scheme = $_SERVER['REQUEST_SCHEME'];
		//得到主机名
		$host = $_SERVER['SERVER_NAME'];
		//得到端口号
		$port = $_SERVER['SERVER_PORT'];
		//得到路径地址和请求字符串
		$uri = $_SERVER['REQUEST_URI'];
		
		//中间做处理  将page=5等这种字符串拼接到URL中，所以如果原来的url中有page这个参数，我们首先需要将原来的page参数个清空
		$uriArray = parse_url($uri);
		$path = $uriArray['path'];
		
		if(!empty($uriArray['query'])){
			//首先将请求字符串变为关联数组
			parse_str($uriArray['query'],$array);
			//清楚关联数组中的page键值对
			unset($array['page']);
			//将剩下的参数拼接为请求字符串
			$query = http_build_query($array);
			
			//再将请求字符串拼接到路劲的后面
			if($query != ''){
				$path = $path.'?'.$query;
			}
		}
		return $scheme.'://'.$host.':'.$port.$path;
		
	}
	
	protected function setUrl($str)
	{
		if(strstr($this->url, '?')){
			$url = $this->url.'&'.$str;
		}else{
			$url = $this->url.'?'.$str;
		}
		return $url;
	}
	
	public function allUrl()
	{
		return [
			'frist' => $this->first(),
			'prev'  => $this->prev(),
			'next' => $this->next(),
			'end' => $this->end(),
		];
	}
	
	public function first()
	{
		return $this->setUrl('page=1');
	}
	
	public function next()
	{
		//根据当前page得到下一页的页码
		if($this->page +1 >$this->totalPage){
			$page = $this->totalPage;
		}else{
			$page = $this->page + 1;
		}
		return $this->setUrl('page='.$page);
	}
	
	public function prev()
	{
		if($this->page - 1 <1){
			$page = 1;
		}else{
			$page = $this->page - 1;
		}
		return $this->setUrl('page='.$page);
	}
	
	public function end()
	{
		return $this->setUrl('page='.$this->totalPage);
	}
	
	
	public function limit()
	{
		$offset = ($this->page - 1) * $this->number;
		return $offset.','.$this->number;
	}
 }
?>