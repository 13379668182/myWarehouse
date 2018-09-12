<?php
include 'Tpl.php';
$tpl = new Tpl();
$title = '你好';
$abc = '天气不错';
$data = ['哈哈','呵呵he'];
  $tpl ->assign('title',$title);
  $tpl->assign('data',$data);
  $tpl->assign('abc',$abc);
  $tpl->display('test.html');
?>