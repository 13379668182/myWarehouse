<?php
header("Content-type:text/html; charset=utf-8");
	//1.链接数据库

	$link = mysqli_connect('gz-cdb-orsuxagx.sql.tencentcdb.com:62156','root','tata1000+!');
		
	//2 判断是否连接成功
	if(!$link){
		exit('数据库连接失败');
	}

	//3 设置字符集
	mysqli_set_charset($link, 'utf8');
	
	//4 选择数据库
	mysqli_select_db($link, "test");
	//5 准备sql语句
	$sql  = "select * from user";
	
	//6发送sql语句
	$res = mysqli_query($link, $sql);
	//var_dump($res);
	
	//7 处理结果
	while($row  = mysqli_fetch_assoc($res)){
		echo '<pre>';
		var_dump($row);
	}
	
	//8 关闭数据库(释放资源)
	mysqli_close($link);


?>