<?php
require_once('inc/top.php');	// вывод на экран
require_once('inc/check.php');	// вход в игру
require_once('inc/head.php');
require_once('class/items.php');	// работа с вещами
require_once('inc/boi.php');		//создание боя + завод игрока в бой
require_once('inc/bot.php');		//параметры бота + создание бота

$go = isset($_REQUEST['go']) ? intval($_REQUEST['go']) : 0;

if($f['loc'] != 206) msg2('Здесь нет кожевенной',1);

msg2('<b>Кожевенная</b>');
if($f['p_koj'] ==0){msg2('Вы не кожевник',1);fin();}
if(empty($go)){
msg2('Суете шкуру - получаете вещь!<br>
<a href="koj.php?go=1">Перчатки из шкуры болотожора</a> - <a href="shop.php?mod=iteminfa&iid=748">Инф.</a> - Шкура болотожора 1 шт.<br>
<a href="koj.php?go=2">Перчатки из шкуры кабана</a> - <a href="shop.php?mod=iteminfa&iid=743">Инф.</a> - Шкура кабана 1 шт.<br>
<a href="koj.php?go=3">Перчатки из шкуры волка</a> - <a href="shop.php?mod=iteminfa&iid=747">Инф.</a> - Шкура волка 1 шт.<br>
<a href="koj.php?go=4">Перчатки из шкуры медведя</a> - <a href="shop.php?mod=iteminfa&iid=746">Инф.</a> - Шкура медведя 1 шт.<br>
<a href="koj.php?go=5">Штаны из шкуры болотожора</a> - <a href="shop.php?mod=iteminfa&iid=840">Инф.</a> - Шкура болотожора 3 шт.<br>
<a href="koj.php?go=6">Штаны из шкуры кабана</a> - <a href="shop.php?mod=iteminfa&iid=841">Инф.</a> - Шкура кабана 3 шт.<br>
<a href="koj.php?go=7">Штаны из шкуры волка</a> - <a href="shop.php?mod=iteminfa&iid=842">Инф.</a> - Шкура волка 3 шт.<br>
<a href="koj.php?go=8">Штаны из шкуры медведя</a> - <a href="shop.php?mod=iteminfa&iid=843">Инф.</a> - Шкура медведя 3 шт.',1);
fin();}
elseif($go==1){

if($items->count_base_item($f['login'], 838) ==0) msg2('У вас недостаточно шкур',1);
$items->del_base_item($f['login'], 838, 1);
$id = $items->add_item($f['login'], 748, 1);
$q = $db->query("select * from `invent` where id={$id} limit 1;");
$item = $q->fetch_assoc();
$item['info'] .= '<br>Создано кожевником '.$f['login'].'';
$q = $db->query("update `invent` set info='{$item['info']}' where id={$id} limit 1;");
if($f['rasa'] ==4){$q = $db->query("update `users` set p_koj=p_koj+2 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_koj=p_koj+1 where id={$f['id']} limit 1;");}
msg2('Вы сделали вещь');
knopka2('koj.php', 'В кожевенную');
fin();}

elseif($go==2){
if($f['p_koj'] <10) msg2('У вас не хватает мастерства',1);
if($items->count_base_item($f['login'], 835) ==0) msg2('У вас недостаточно шкур',1);
$items->del_base_item($f['login'], 835, 1);
$id = $items->add_item($f['login'], 743, 1);
$q = $db->query("select * from `invent` where id={$id} limit 1;");
$item = $q->fetch_assoc();
$item['info'] .= '<br>Создано кожевником '.$f['login'].'';
$q = $db->query("update `invent` set info='{$item['info']}' where id={$id} limit 1;");
if($f['rasa'] ==4){$q = $db->query("update `users` set p_koj=p_koj+2 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_koj=p_koj+1 where id={$f['id']} limit 1;");}
msg2('Вы сделали вещь');
knopka2('koj.php', 'В кожевенную');
fin();}

elseif($go==3){
if($f['p_koj'] <25) msg2('У вас не хватает мастерства',1);
if($items->count_base_item($f['login'], 836) ==0) msg2('У вас недостаточно шкур',1);
$items->del_base_item($f['login'], 836, 1);
$id = $items->add_item($f['login'], 747, 1);
$q = $db->query("select * from `invent` where id={$id} limit 1;");
$item = $q->fetch_assoc();
$item['info'] .= '<br>Создано кожевником '.$f['login'].'';
$q = $db->query("update `invent` set info='{$item['info']}' where id={$id} limit 1;");
if($f['rasa'] ==4){$q = $db->query("update `users` set p_koj=p_koj+2 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_koj=p_koj+1 where id={$f['id']} limit 1;");}
msg2('Вы сделали вещь');
knopka2('koj.php', 'В кожевенную');
fin();}

elseif($go==4){
if($f['p_koj'] <50) msg2('У вас не хватает мастерства',1);
if($items->count_base_item($f['login'], 837) ==0) msg2('У вас недостаточно шкур',1);
$items->del_base_item($f['login'], 837, 1);
$id = $items->add_item($f['login'], 746, 1);
$q = $db->query("select * from `invent` where id={$id} limit 1;");
$item = $q->fetch_assoc();
$item['info'] .= '<br>Создано кожевником '.$f['login'].'';
$q = $db->query("update `invent` set info='{$item['info']}' where id={$id} limit 1;");
if($f['rasa'] ==4){$q = $db->query("update `users` set p_koj=p_koj+2 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_koj=p_koj+1 where id={$f['id']} limit 1;");}
msg2('Вы сделали вещь');
knopka2('koj.php', 'В кожевенную');
fin();}

elseif($go==5){
if($f['p_koj'] <10) msg2('У вас не хватает мастерства',1);
if($items->count_base_item($f['login'], 838) <3) msg2('У вас недостаточно шкур',1);
$items->del_base_item($f['login'], 838, 3);
$id = $items->add_item($f['login'], 840, 1);
$q = $db->query("select * from `invent` where id={$id} limit 1;");
$item = $q->fetch_assoc();
$item['info'] .= '<br>Создано кожевником '.$f['login'].'';
$q = $db->query("update `invent` set info='{$item['info']}' where id={$id} limit 1;");
if($f['rasa'] ==4){$q = $db->query("update `users` set p_koj=p_koj+2 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_koj=p_koj+1 where id={$f['id']} limit 1;");}
msg2('Вы сделали вещь');
knopka2('koj.php', 'В кожевенную');
fin();}


elseif($go==6){
if($f['p_koj'] <25) msg2('У вас не хватает мастерства',1);
if($items->count_base_item($f['login'], 835) <3) msg2('У вас недостаточно шкур',1);
$items->del_base_item($f['login'], 835, 3);
$id = $items->add_item($f['login'], 841, 1);
$q = $db->query("select * from `invent` where id={$id} limit 1;");
$item = $q->fetch_assoc();
$item['info'] .= '<br>Создано кожевником '.$f['login'].'';
$q = $db->query("update `invent` set info='{$item['info']}' where id={$id} limit 1;");
if($f['rasa'] ==4){$q = $db->query("update `users` set p_koj=p_koj+2 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_koj=p_koj+1 where id={$f['id']} limit 1;");}
msg2('Вы сделали вещь');
knopka2('koj.php', 'В кожевенную');
fin();}


elseif($go==7){
if($f['p_koj'] <50) msg2('У вас не хватает мастерства',1);
if($items->count_base_item($f['login'], 836) <3) msg2('У вас недостаточно шкур',1);
$items->del_base_item($f['login'], 836, 3);
$id = $items->add_item($f['login'], 842, 1);
$q = $db->query("select * from `invent` where id={$id} limit 1;");
$item = $q->fetch_assoc();
$item['info'] .= '<br>Создано кожевником '.$f['login'].'';
$q = $db->query("update `invent` set info='{$item['info']}' where id={$id} limit 1;");
if($f['rasa'] ==4){$q = $db->query("update `users` set p_koj=p_koj+2 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_koj=p_koj+1 where id={$f['id']} limit 1;");}
msg2('Вы сделали вещь');
knopka2('koj.php', 'В кожевенную');
fin();}


elseif($go==8){
if($f['p_koj'] <100) msg2('У вас не хватает мастерства',1);
if($items->count_base_item($f['login'], 837) <3) msg2('У вас недостаточно шкур',1);
$items->del_base_item($f['login'], 837, 3);
$id = $items->add_item($f['login'], 843, 1);
$q = $db->query("select * from `invent` where id={$id} limit 1;");
$item = $q->fetch_assoc();
$item['info'] .= '<br>Создано кожевником '.$f['login'].'';
$q = $db->query("update `invent` set info='{$item['info']}' where id={$id} limit 1;");
if($f['rasa'] ==4){$q = $db->query("update `users` set p_koj=p_koj+2 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_koj=p_koj+1 where id={$f['id']} limit 1;");}
msg2('Вы сделали вещь');
knopka2('koj.php', 'В кожевенную');
fin();}

?>