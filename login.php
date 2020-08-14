<?php
##############
# 24.12.2014 #
##############
require_once('inc/top.php');	// вывод на экран
require_once('inc/head.php');
// инициализация переменных в обход register globals
$login = isset($_REQUEST['login']) ? $_REQUEST['login'] : '';
$pass = isset($_REQUEST['pass']) ? $_REQUEST['pass'] : '';
$ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? ekr($_SERVER['HTTP_X_FORWARDED_FOR']) : ekr($_SERVER['REMOTE_ADDR']);
$soft = isset($_SERVER['HTTP_USER_AGENT']) ? ekr($_SERVER['HTTP_USER_AGENT']) : '';
$host = isset($_SERVER['HTTP_X_OPERAMINI_PHONE']) ? ekr($_SERVER['HTTP_X_OPERAMINI_PHONE']) : '';
if(empty($login) or preg_match("/[^a-zA-Z0-9_]/", $login) or empty($pass) or preg_match("/[^a-zA-Z0-9_]/", $pass))
	{
	msg2('Неверный логин или пароль!');
	knopka('index.php','На главную',1);
	fin();
	}

$q = $db->query("select pass,id,login from `users` where login='{$login}' limit 1;");
if($q->num_rows == 0)
	{
	msg2('Такой логин не зарегистрирован!');
	knopka('index.php','На главную',1);
	fin();
	}
$f = $q->fetch_assoc();
if($f['pass'] != md5($pass))
	{
	msg2('Неверный пароль!');
	knopka('index.php','На главную',1);
	fin();
	}

setcookie('id', '', Time() - 3600,'/','');
setcookie('hash', '', Time() - 3600,'/','');
setcookie('lgn', '', Time() - 3600,'/','');
setcookie('id', $f['id'], Time() + 86400 * 365,'/','');
setcookie('hash', md5($pass), Time() + 86400 * 365,'/','');
setcookie('lgn', $login, Time() + 86400 * 365,'/','');
$q = $db->query("insert into `ipsoft` values(0,'{$f['login']}','{$t}','{$ip}','{$host}','{$soft}','".session_id()."');");
$q = $db->query("update `users` set soft='{$soft}',host='{$host}',ip='{$ip}',session='".session_id()."', autoreg=0 where login='{$f['login']}' limit 1;");
header("location: loc.php");
fin();
?>
