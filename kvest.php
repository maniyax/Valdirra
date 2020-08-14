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
case 1: require_once('kvest/loc1.php'); break;
case 15: require_once('kvest/trava.php'); break;
case 158: require_once('kvest/trava.php'); break;
case 171: require_once('kvest/derevo.php'); break;
case 150: require_once('kvest/loc150.php'); break;
case 183: require_once('kvest/derevo.php'); break;
case 195: require_once('kvest/derevo.php'); break;
case 166: require_once('kvest/trava.php'); break;
case 37: require_once('kvest/loc37.php'); break;
case 173: require_once('kvest/loc173.php'); break;
case 147: require_once('kvest/loc147.php'); break;
case 149: require_once('kvest/loc149.php'); break;
case 151: require_once('kvest/kam151.php'); break;
case 148: require_once('kvest/loc148.php'); break;
case 155: require_once('kvest/loc155.php'); break;
case 156: require_once('kvest/loc156.php'); break;
case 153: require_once('kvest/loc153.php'); break;
case 51: require_once('kvest/trava.php'); break;
case 56: require_once('kvest/loc56.php'); break;
case 68: require_once('kvest/trava.php'); break;
case 69: require_once('kvest/loc69.php'); break;
case 77: require_once('kvest/loc77.php'); break;
case 78: require_once('kvest/trava.php'); break;
case 83: require_once('kvest/trava.php'); break;
case 97: require_once('kvest/loc97.php'); break;
case 99: require_once('kvest/loc99.php'); break;
case 105: require_once('kvest/loc105.php'); break;
case 106: require_once('kvest/loc106.php'); break;
case 107: require_once('kvest/loc107.php'); break;
case 163: require_once('kvest/trava.php'); break;
case 164: require_once('kvest/trava.php'); break;
case 162: require_once('kvest/trava.php'); break;
case 157: require_once('kvest/trava.php'); break;
case 159: require_once('kvest/trava.php'); break;
case 109: require_once('kvest/loc109.php'); break;
case 238: require_once('kvest/loc238.php'); break;
default: msg('<a href="loc.php">Ошибка локации</a>'); break;
endswitch;
?>
