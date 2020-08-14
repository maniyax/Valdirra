<?php

require_once('inc/top.php');	// вывод на экран
require_once('inc/check.php');	// вход в игру
require_once('inc/head.php');
require_once('class/items.php');	// работа с вещами

$go = isset($_REQUEST['go']) ? intval($_REQUEST['go']) : 0;

// меню
if($f['status'] == 1)
	{
	knopka('battle.php', 'Вы в бою!', 1);
	fin();
}

if($f['loc'] != 154 and $f['loc'] != 223){
msg2('Ошибка локации',1);
fin();}


if (empty($go)){
msg2('Магазинчик ремесленника<br>
Здесь вы можете преобрести всякую всячину.');
knopka2('shoprem.php?go=1', 'Пустая бутылка - 10 медных монет');
knopka2('shoprem.php?go=2', 'Грамота телепортации в замок - 2 серебренные монеты');
if($items->count_base_item($f['login'], 753) == 0) knopka2('shoprem.php?go=3', 'Топор лесоруба - 3 серебряные монеты');
knopka2('shoprem.php?go=4', 'Грамота телепортации - 15 серебряных монет');
if($items->count_base_item($f['login'], 754) == 0) knopka2('shoprem.php?go=5', 'Серп травника - 5 серебряных монет');
if($items->count_base_item($f['login'], 781) == 0) knopka2('shoprem.php?go=6', 'Кузнечный молот - 5 серебряных монет');
if($f['p_fishman'] >1 and $items->count_base_item($f['login'], 755) == 0) knopka2('shoprem.php?go=7', 'удочка рыболова - 4 серебряные монеты');
if($items->count_base_item($f['login'], 759) == 0) knopka2('shoprem.php?go=8', 'Кирка рудокопа - 5 серебряных монет');
if($items->count_base_item($f['login'], 834) == 0) knopka2('shoprem.php?go=10', 'Ювелирный набор - 6 серебряных монет');
if($items->count_base_item($f['login'], 757) == 0) knopka2('shoprem.php?go=11', 'Разделочный нож - 4 серебряные монеты');
knopka2('loc.php', 'Вернуться');
fin();
}
elseif ($go==1){
if ($f['cu'] <10) msg2('У вас недостаточно монет. требуется 10',1);
$q = $db->query("update `users` set cu=cu-10 where id={$id} limit 1;");
$items->add_item($f['login'], 749, 1);
msg2('Вы купили пустую бутылку за 10 монет.');
}
elseif ($go==2){
if ($f['ag'] <2) msg2('У вас недостаточно серебряных монет. требуется 2',1);
$q = $db->query("update `users` set ag=ag-2 where id={$id} limit 1;");
$items->add_item($f['login'], 756, 1);
msg2('Вы купили грамоту телепортации в замок за 2 монеты.');
}
elseif ($go==3){
if ($f['ag'] <3) msg2('У вас недостаточно серебряных монет. требуется 3',1);
if($items->count_base_item($f['login'], 753) > 0) msg2('У вас уже есть топор',1);
$q = $db->query("update `users` set ag=ag-3,topor=100 where id={$id} limit 1;");
$items->add_item($f['login'], 753, 1);
msg2('Вы купили топор лесоруба за 3 монет.');
}
elseif ($go==4){
if ($f['ag'] <15) msg2('У вас недостаточно серебряных монет. требуется 15',1);
$q = $db->query("update `users` set ag=ag-15 where id={$id} limit 1;");
$items->add_item($f['login'], 716, 1);
msg2('Вы купили грамоту телепортации за 15 монет.');
}
elseif ($go==5){
if ($f['ag'] <5) msg2('У вас недостаточно серебряных монет. требуется 5',1);
if($items->count_base_item($f['login'], 754) > 0) msg2('У вас уже есть серп',1);
$q = $db->query("update `users` set ag=ag-5,serp=100 where id={$id} limit 1;");
$items->add_item($f['login'], 754, 1);
msg2('Вы купили серп травника за 5 монет.');
}
elseif ($go==6){
if ($f['ag'] <5) msg2('У вас недостаточно серебряных монет. требуется 5',1);
if($items->count_base_item($f['login'], 781) > 0) msg2('У вас уже есть молот',1);
$q = $db->query("update `users` set ag=ag-5,molot=100 where id={$id} limit 1;");
$items->add_item($f['login'], 781, 1);
msg2('Вы купили кузнечный молот за 5 монет.');
}
elseif($go==7){
if ($f['ag'] <4) msg2('У вас недостаточно серебряных монет. требуется 4',1);
if($items->count_base_item($f['login'], 755) > 0) msg2('У вас уже есть удочка',1);
$q = $db->query("update `users` set ag=ag-4,udochka=400 where id={$id} limit 1;");
$items->add_item($f['login'], 755, 1);
msg2('Вы купили удочку за 4 монет.');
}
elseif($go==8){
if ($f['ag'] <5) msg2('У вас недостаточно серебряных монет. требуется 5',1);
if($items->count_base_item($f['login'], 759) > 0) msg2('У вас уже есть кирка',1);
$q = $db->query("update `users` set ag=ag-5,kirka=500 where id={$f['id']} limit 1;");
$items->add_item($f['login'], 759, 1);
msg2('Вы купили кирку за 5 монет.');
}
elseif($go==10){
if ($f['ag'] <6) msg2('У вас недостаточно серебряных монет. требуется 6',1);
if($items->count_base_item($f['login'], 834) > 0) msg2('У вас уже есть ювелирный набор',1);
$q = $db->query("update `users` set ag=ag-6,nabor=50 where id={$id} limit 1;");
$items->add_item($f['login'], 834, 1);
msg2('Вы купили ювелирный набор за 6 монет.');
}

elseif($go==11){
if ($f['ag'] <4) msg2('У вас недостаточно серебряных монет. требуется 4',1);
if($items->count_base_item($f['login'], 757) > 0) msg2('У вас уже есть разделочный нож',1);
$q = $db->query("update `users` set ag=ag-4,noj=10 where id={$id} limit 1;");
$items->add_item($f['login'], 757, 1);
msg2('Вы купили разделочный нож за 4 монеты.');
}
fin();
?>
