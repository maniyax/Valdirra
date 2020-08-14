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
$mod = isset($_REQUEST['mod']) ? $_REQUEST['mod'] : '';
$iid = isset($_REQUEST['iid']) ? $_REQUEST['iid'] : 0;
$summ = isset($_REQUEST['summ']) ? $_REQUEST['summ'] : 0;
$ok = isset($_REQUEST['ok']) ? $_REQUEST['ok'] : 0;
$keystring = isset($_REQUEST['keystring']) ? $_REQUEST['keystring'] : '';

// меню
if($f['status'] == 1)
	{
	knopka('battle.php', 'Вы в бою!', 1);
	fin();
}

if($f['loc'] != 154 and $f['loc'] != 1){
msg2('Ошибка локации',1);
fin();}

msg2('<b>Продуктовая лавка</b>');
if(empty($go)){
knopka2('shopeda.php?go=1', 'Еда');
knopka2('shopeda.php?go=2', 'Питье');
fin();
}
elseif($go==1){
if(empty($ok)){
knopka2('shopeda.php?go=1&ok=1', 'Яблоко - 7 медных монет (-10 голода)');
knopka2('shopeda.php?go=1&ok=2', 'Пирог с капустой - 19 медных монет (-30 голода)');
knopka2('shopeda.php?go=1&ok=3', 'Кусок черного хлеба - 7 медных монет (-10 голода)');
fin();
}
elseif($ok==1){
if($f['cu'] < 7) msg2('У вас нет столько денег',1);
$q = $db->query("update `users` set cu=cu-7 where id={$f['id']} limit 1;");
$items->add_item($f['login'], 769, 1);
msg2('Вы купили яблоко',1);
fin();
}

elseif($ok==2){
if($f['cu'] < 19) msg2('У вас нет столько денег',1);
$q = $db->query("update `users` set cu=cu-19 where id={$f['id']} limit 1;");
$items->add_item($f['login'], 770, 1);
msg2('Вы купили пирог с капустой',1);
fin();
}

elseif($ok==3){
if($f['cu'] < 7) msg2('У вас нет столько денег',1);
$q = $db->query("update `users` set cu=cu-7 where id={$f['id']} limit 1;");
$items->add_item($f['login'], 771, 1);
msg2('Вы купили кусок черного хлеба',1);
fin();
}
}
elseif($go==2){
if(empty($ok)){
knopka2('shopeda.php?go=2&ok=1', 'Малая фляга с водой - 6 медных монет (-10 жажды)');
knopka2('shopeda.php?go=2&ok=2', 'Средняя фляга с водой - 12 медных монет (-20 жажды)');
knopka2('shopeda.php?go=2&ok=3', 'Большая фляга с водой - 24 медных монет (-40 жажды)');
fin();
}

elseif($ok==1){
if($f['cu'] < 6) msg2('У вас нет столько денег',1);
$q = $db->query("update `users` set cu=cu-6 where id={$f['id']} limit 1;");
$items->add_item($f['login'], 766, 1);
msg2('Вы купили малую флягу с водой',1);
fin();
}
elseif($ok==2){
if($f['cu'] < 12) msg2('У вас нет столько денег',1);
$q = $db->query("update `users` set cu=cu-12 where id={$f['id']} limit 1;");
$items->add_item($f['login'], 767, 1);
msg2('Вы купили среднюю флягу с водой',1);
fin();
}
elseif($ok==3){
if($f['cu'] < 24) msg2('У вас нет столько денег',1);
$q = $db->query("update `users` set cu=cu-24 where id={$f['id']} limit 1;");
$items->add_item($f['login'], 768, 1);
msg2('Вы купили большую флягу с водой',1);
fin();
}
}



?>