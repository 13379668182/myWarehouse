<?php
$config = include 'config.php';

 $m = new Model($config);
 echo '<pre>';
print_r($m->limit('0,20')->table('user')->select());
//$data = ['name'=>'小填','gender'=>'男','age'=>23];
//$id = $m->table('user')->insert($data,true);
var_dump($id);

 echo $m->sql;
 
 
/*数据库连接操作基类 */
class Model
{
	/*成员变量*/
	//主机名
	protected $hosts;
	//用户名
	protected $user;
	//密码
	protected $pwd;
	//数据库名
	protected $dbname;
	//字符集
	protected $charset;
	//数据表前缀
	protected $prefix;
	//数据库连接资源
	protected $link;
	//数据表名    可以自己指定表名
	protected $tablename;		
	//sql语句
	protected $sql;
	//操作数组 存放所有的查询条件
	protected $options;
	
	//构造方法，对成员变量进行初始化
	function __construct($config)
	{
		// 对成员变量一一进行初始化	
		$this->hosts = $config['DB_HOSTS'];
		$this->user = $config['DB_USER'];
		$this->pwd = $config['DB_PWD'];
		$this->dbname = $config['DB_NAME'];
		$this->charset = $config['DB_CHARDET'];
		$this->prefix = $config['DB_PREFIX'];
		//连接数据库
		$this->link = $this->connect();
		
		//得到数据表名
		$this->tablename  = $this->getTableName();
		
		//初始化options数组
		$this->initOptions();
	}
	
	/*连接数据库方法
	 * 
	 * */
	protected function connect()
	{
	
		$link = mysqli_connect($this->hosts, $this->user, $this->pwd);
		if(!$link){
			die('数据库连接失败');
		}
		//选择数据库
		mysqli_select_db($link,$this->dbname);
		//设置字符集
		mysqli_set_charset($link, $this->charset);
		return $link;
	}
	
	/*得到表名的方法
	 * 分两种方法
	 * 一种是自己设置
	 * 一种是根据类名得到
	 * */
	protected function getTableName()
	{
		//第一种 ，设置了成员变量 通过成员变量得到
		if(!empty($this->table->tablename)){
			return  $this->prefix.$this->tablename;
		}
		//第二种 没有设置成员变量 通过类名来得到表名  
		$className = get_class($this);
		$table = strtolower(substr($className, 0,-5));
		return $this->prefix.$table;
	}
	
	//初始化options数组
	protected function initOptions()
	{
		$arr = ['where','table','field','order','order','group','having','limit'];
		// 将 options 数组中这些健对应的值全部清空
		foreach ($arr as  $value) {
			$this->options[$value] = '';
			//将table默认设置为tablename
			if($value == 'table'){
				$this->options[$value] = $this->tablename; 
			}
		}
	}
	/*filed 方法
	 * param 可以是字符串或者数组
	 * 
	 * */ 
	function field($field)
	{
		if(!empty($field)){
			if(is_string($field)){
				$this->optins['field']  = $field;
			}else if(is_array($field)){
				$this->options['field']  = join(',',$field);
			}
		}
		return $this;
	}
	
	//table 方法
	function table($table)
	{
		if(!empty($table)){
			$this->options['table'] = $table;
		}
		return $this;
	}
	
	//where 方法
	function where($where)
	{
		if(is_string($where)){
			$this->options['where'] = 'where '.$where;
		}
		return $this;
	}
	
	//group 方法
	function group($group)
	{
		if(!empty($group)){
			$this->options['group'] = 'group by '.$group;
		}
		return $this;
	}
	
	//having 方法
	function having($having)
	{
		if(!empty($having)){
			$this->options['having'] = 'having '.$having;
		}
		return $this;
	}
	
	//order 方法
	function order($order)
	{
		if(!empty($order)){
			$this->options['order']  = 'order by '.$order;
		}
		return $this;
	}
	//limit 方法
	function limit($limit)
	{
		if(is_string($limit)){
			if(!empty($limit)){
			$this->options['limit'] = 'limit '.$limit; 
 			}
		}else if(is_array($limit)){
			$this->options['limit'] = 'limit '.join(',',$limit);
		}
		
		return $this;
	}
	
	//select 方法
	function select()
	{
		 //先预写一个带有占位符的sql语句
		 $sql = 'select %FIELD% from %TABLE% %WHERE% %GROUP% %HAVING% %ORDER% %LIMIT%';
		 //将options对应的值一次替换成上面的占位符
		$sql = str_replace(
		['%FIELD%','%TABLE%','%WHERE%','%GROUP%','%HAVING%','%ORDER%','%LIMIT%'],
		[$this->options['field']==''?'*':$this->options['field'],$this->options['table'],$this->options['where'],$this->options['group'],$this->options['having'],$this->option['order'],$this->options['limit']],
		  $sql);
		 
		  //保存一份SQL语句到成员的变量中
		  $this->sql = $sql;
		  //执行sql语句
		  return $this->query($sql);
	}
	
	//query方法
	 function query($sql)
	 {
	 	//执行sql语句
	 	$result = mysqli_query($this->link, $sql);
		if($result && mysqli_affected_rows($this->link)){//判断返回结果和影响行数
			while($data = mysqli_fetch_assoc($result)){
				$newData[] = $data;
			}
		}
		return $newData;
	 }
	//exec
	function exec($sql,$isInsert)
	{
		// 执行sql语句
		$result = mysqli_query($this->link, $sql);
		if($result && mysqli_affected_rows($this->link)){
			if($isInsert){ //如果你需要返回插入的ID 就写两个参数
				return mysqli_insert_id($this->link);
			}else{//返回受影响的行数
				return mysqli_affected_rows($this->link);
			}
		};
		return false;
	}
	//调用自己的sql语句的魔术方法
	function  __get($name){
		if($name == 'sql'){
			return $this->sql;
		}else{
			return FALSE;
		}
	}
	
	//insert函数  $data给关联数组
	function insert($data,$isInsert = false)
	{
		//处理值是字符串问题
		$data = $this->parseValue($data);
		//提取所有的健，即字段
		$keys = array_keys($data);
		//提取所有的值
		$vals = array_values($data);
		$sql = 'insert into %TABLE%(%FIELD%) values(%VALUES%)';
		$sql = str_replace(
		['%TABLE%','%FIELD%','%VALUES%'], 
		[$this->options['table'],join(',',$keys),join(',',$vals)], $sql);
		$this->sql = $sql;
		return $this->exec($sql,$isInsert);
		
	}
	//处理插入插入数据的数组
	protected function parseValue($data)
	{
		foreach ($data as $key => $value) {
			if(is_string($value)){
				$value = '"'.$value.'"';	
			}
			$newData[$key] = $value; 
		}
		return $newData;
	}
	
	//删除函数
	function delete()
	{
		$sql = 'delete from %TABLE% %WHERE%';
		$sql = str_replace(
		['%TABLE%','%WHERE%'], 
		[$this->options['table'],$this->options['where']], $sql);
		//保存sql语句
		$this->sql = $sql;
		//执行sql语句
		return $this->exec($sql);
	}
}
?>