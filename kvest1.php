<?php
##############
# 24.12.2014 #
##############
require_once('inc/top.php');	// вывод на экран
require_once('inc/check.php');	// вход в игру
require_once('inc/head.php');
require_once('class/items.php');	// работа с вещами
require_once('inc/boi.php');		//создание боя + завод игрока в бой
require_once('inc/bot.php');		//параметры бота + создание бота

$go = isset($_REQUEST['go']) ? intval($_REQUEST['go']) : 0;
$kv = isset($_REQUEST['kv']) ? intval($_REQUEST['kv']) : 0;
$mod = isset($_REQUEST['mod']) ? $_REQUEST['mod'] : '';
$iid = isset($_REQUEST['iid']) ? $_REQUEST['iid'] : 0;
$lvl = isset($_REQUEST['lvl']) ? $_REQUEST['lvl'] : 0;
$ok = isset($_REQUEST['ok']) ? $_REQUEST['ok'] : 0;
$keystring = isset($_REQUEST['keystring']) ? $_REQUEST['keystring'] : '';

// меню
if($f['status'] == 1)
	{
	knopka('battle.php', 'Вы в бою!', 1);
	fin();
	}
// каждый квест в отдельном файле
switch($f['loc']):
case 149:
require_once('kvest/mer.php');
break;

case 19:
require_once('kvest/loc-19.php');		//вывод на экран
break;

case 156:
require_once('kvest/loc-156.php');		//вывод на экран
break;
default: msg('<a href="loc.php">Ошибка локации</a>'); break;
endswitch;
?>
