<?php
##############
# 24.12.2014 #
##############

date_default_timezone_set('Europe/Moscow');
$time_start = microtime(true);          //для подсчета времени выполнения скрипта
error_reporting(-1);                    //вывод ВСЕВОЗМОЖНЫХ ошибок
ini_set('display_errors',TRUE);
ini_set('display_startup_errors',TRUE);
session_start();

//для того чтобы не брались из кеша
Header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); //Дата в прошлом
Header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
Header('Pragma: no-cache'); // HTTP/1.1
Header('Last-Modified: '.gmdate("D, d M Y H:i:s").'GMT');
Header('Content-Type: text/html; charset=utf-8');
setlocale(LC_CTYPE, 'ru_RU.UTF-8');

$t = $_SERVER['REQUEST_TIME'];
require_once('inc/gzip.php');  //сжатие
require_once('class/DBC.php');// база
require_once('inc/func.php');       //подключим ф-ии без авторизации сразу в файл вывода
$q = $db->query("select * from `settings` where id=1 limit 1;");
$settings = $q->fetch_assoc();
if(!empty($title)) $title = $title.': '.$settings['name']; else $title = $settings['name'];

echo '<?xml version="1.0" encoding="utf-8"?>';
echo '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>
'.$title.'
</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta http-equiv="Cache-Control" content="no-cache" forua="true"/>';

//if($f['admin'] <3){
//echo'<script language="JavaScript" type="text/javascript">document.onkeydown = function (event){if ((event.ctrlKey && event.keyCode==82) || event.keyCode==116){event.preventDefault();return false;}}</script>';
//}


require_once('dis/1.php');
?>
