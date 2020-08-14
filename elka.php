<?php

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

if($f['hpnow'] <=0){
knopka2('loc.php', 'Востановите здоровье',1);
fin();
}


if($f['status'] >0){
knopka2('loc.php', 'Вы в бою, или у вас есть заявка на арене',1);
fin();
}

if($f['loc'] != 149){
msg2('Вы не в локации с елкой',1);
fin();
}

msg2('<b>Новогодняя ёлка!</b><br>
<img src="https://img-fotki.yandex.ru/get/5412/136487634.0/0_6d4cb_43b82d30_S" align="center" alt="elka"/>');
if(empty($go)){
msg2('Нарядная елка Вальдирры. Под елкой лежат подарки.');
if($f['elka'] == 0) knopka2('elka.php?go=1', 'Поискать подарки');
knopka2('loc.php', 'В локацию',1);

fin();
}
elseif($go==1){
if($f['elka'] ==1) msg2('Вы уже получили свой подарок!',1);
$items->add_item($f['login'], 775);
$q = $db->query("update `users` set elka=1 where login='{$f['login']}' limit 1;");
msg2('С новым годом!<br>
[Подарок получен]');
knopka2('loc.php', 'В локацию',1);
fin();
}


?>