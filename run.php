<?php
require_once('inc/top.php');	// вывод на экран
require_once('inc/check.php');	// вход в игру
require_once('inc/head.php');
require_once('class/items.php');	// работа с вещами
require_once('inc/boi.php');		//создание боя + завод игрока в бой
require_once('inc/bot.php');		//параметры бота + создание бота

$go = isset($_REQUEST['go']) ? intval($_REQUEST['go']) : 0;
$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
$ok = isset($_REQUEST['ok']) ? intval($_REQUEST['ok']) : 0;

if($f['loc'] != 206) msg2('Здесь нет рунной мастерской',1);

msg2('<b>Рунная мастерская</b>');
if($f['p_run'] ==0){msg2('Вы не мастер рун',1);fin();}
if(empty($go)){
msg2('За каждую модификацию вещи вам нужно будет отдать 10 серебряных монет в качестве платы за использование мастерской.');
knopka2('run.php?go=1', 'Лечение',1);
knopka2('run.php?go=2', 'Огонь',1);
knopka2('run.php?go=3', 'Исцеление',1);
knopka2('run.php?go=4', 'Лед',1);
knopka2('run.php?go=5', 'Ветер',1);
knopka2('run.php?go=6', 'Пламя',1);
knopka2('run.php?go=7', 'Вампиризм',1);
fin();}
elseif($go==1){
if(empty($ok)){
if($f['ag'] <10) msg2('У вас нет 10 серебряных монет',1);
msg2('<form action="run.php?go=1&ok=1" method="post">Введите id вещи, на которую собираетесь наложить арт-эффект:<br>
<input type="number" name="id"/><br>
<input type="submit" value="ГО"/></form>',1);
fin();
}

$q = $db->query("select * from `invent` where id={$id} limit 1;");
$a = $q->fetch_assoc();
if($a['login'] != $f['login']) msg2('Это не ваша вещь',1);
if($a['flag_rinok'] ==1) msg2('Эта вещь находится на рынке',1);
if($a['flag_equip'] ==1) msg2('Эта вещь экипирована',1);
if($a['flag_sklad'] ==1) msg2('Эта вещь находится на складе',1);
if(!empty($a['art'])) msg2('На этой вещи уже есть арт-эффект '.$a['art'].'',1);
$a['art'] = 'лечение';
$a['info'] .= '<br>Наложен арт-эффект '.$a['art'].' мастером рун '.$f['login'].'';
$q = $db->query("update `invent` set info='{$a['info']}',art='{$a['art']}' where id={$id} limit 1;");
if($f['rasa'] ==1){ $q = $db->query("update `users` set p_run=p_run+2,ag=ag-10 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_run=p_run+1,ag=ag-10 where id={$f['id']} limit 1;");}

msg2('Успех!');
fin();
}
elseif($go==3){
if(empty($ok)){
if($f['p_run'] <50) msg2('Вам это не по плечу',1);
if($f['ag'] <10) msg2('У вас нет 10 серебряных монет',1);
msg2('<form action="run.php?go=3&ok=1" method="post">Введите id вещи, на которую собираетесь наложить арт-эффект:<br>
<input type="number" name="id"/><br>
<input type="submit" value="ГО"/></form>',1);
fin();
}
$q = $db->query("select * from `invent` where id={$id} limit 1;");
$a = $q->fetch_assoc();
if($a['login'] != $f['login']) msg2('Это не ваша вещь',1);
if($a['flag_rinok'] ==1) msg2('Эта вещь находится на рынке',1);
if($a['flag_equip'] ==1) msg2('Эта вещь экипирована',1);
if($a['flag_sklad'] ==1) msg2('Эта вещь находится на складе',1);
if(!empty($a['art'])) msg2('На этой вещи уже есть арт-эффект '.$a['art'].'',1);
$a['art'] = 'исцеление';
$a['info'] .= '<br>Наложен арт-эффект '.$a['art'].' мастером рун '.$f['login'].'';
$q = $db->query("update `invent` set info='{$a['info']}',art='{$a['art']}' where id={$id} limit 1;");
if($f['rasa'] ==1){ $q = $db->query("update `users` set p_run=p_run+2,ag=ag-10 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_run=p_run+1,ag=ag-10 where id={$f['id']} limit 1;");}

msg2('Успех!');
fin();
}
elseif($go==2){
if(empty($ok)){
if($f['p_run'] <25) msg2('Вам это не по плечу',1);
if($f['ag'] <10) msg2('У вас нет 10 серебряных монет',1);
msg2('<form action="run.php?go=2&ok=1" method="post">Введите id вещи, на которую собираетесь наложить арт-эффект:<br>
<input type="number" name="id"/><br>
<input type="submit" value="ГО"/></form>',1);
fin();
}
$q = $db->query("select * from `invent` where id={$id} limit 1;");
$a = $q->fetch_assoc();
if($a['login'] != $f['login']) msg2('Это не ваша вещь',1);
if($a['flag_rinok'] ==1) msg2('Эта вещь находится на рынке',1);
if($a['flag_equip'] ==1) msg2('Эта вещь экипирована',1);
if($a['flag_sklad'] ==1) msg2('Эта вещь находится на складе',1);
if(!empty($a['art'])) msg2('На этой вещи уже есть арт-эффект '.$a['art'].'',1);
$a['art'] = 'огонь';
$a['info'] .= '<br>Наложен арт-эффект '.$a['art'].' мастером рун '.$f['login'].'';
$q = $db->query("update `invent` set info='{$a['info']}',art='{$a['art']}' where id={$id} limit 1;");
if($f['rasa'] ==1){ $q = $db->query("update `users` set p_run=p_run+2,ag=ag-10 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_run=p_run+1,ag=ag-10 where id={$f['id']} limit 1;");}

msg2('Успех!');
fin();
}
elseif($go==4){
if(empty($ok)){
if($f['p_run'] <100) msg2('Вам это не по плечу',1);
if($f['ag'] <10) msg2('У вас нет 10 серебряных монет',1);
msg2('<form action="run.php?go=4&ok=1" method="post">Введите id вещи, на которую собираетесь наложить арт-эффект:<br>
<input type="number" name="id"/><br>
<input type="submit" value="ГО"/></form>',1);
fin();
}
$q = $db->query("select * from `invent` where id={$id} limit 1;");
$a = $q->fetch_assoc();
if($a['login'] != $f['login']) msg2('Это не ваша вещь',1);
if($a['flag_rinok'] ==1) msg2('Эта вещь находится на рынке',1);
if($a['flag_equip'] ==1) msg2('Эта вещь экипирована',1);
if($a['flag_sklad'] ==1) msg2('Эта вещь находится на складе',1);
if(!empty($a['art'])) msg2('На этой вещи уже есть арт-эффект '.$a['art'].'',1);
$a['art'] = 'лед';
$a['info'] .= '<br>Наложен арт-эффект '.$a['art'].' мастером рун '.$f['login'].'';
$q = $db->query("update `invent` set info='{$a['info']}',art='{$a['art']}' where id={$id} limit 1;");
if($f['rasa'] ==1){ $q = $db->query("update `users` set p_run=p_run+2,ag=ag-10 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_run=p_run+1,ag=ag-10 where id={$f['id']} limit 1;");}

msg2('Успех!');
fin();
}
elseif($go==5){
if(empty($ok)){
if($f['p_run'] <250) msg2('Вам это не по плечу',1);
if($f['ag'] <10) msg2('У вас нет 10 серебряных монет',1);
msg2('<form action="run.php?go=5&ok=1" method="post">Введите id вещи, на которую собираетесь наложить арт-эффект:<br>
<input type="number" name="id"/><br>
<input type="submit" value="ГО"/></form>',1);
fin();
}
$q = $db->query("select * from `invent` where id={$id} limit 1;");
$a = $q->fetch_assoc();
if($a['login'] != $f['login']) msg2('Это не ваша вещь',1);
if($a['flag_rinok'] ==1) msg2('Эта вещь находится на рынке',1);
if($a['flag_equip'] ==1) msg2('Эта вещь экипирована',1);
if($a['flag_sklad'] ==1) msg2('Эта вещь находится на складе',1);
if(!empty($a['art'])) msg2('На этой вещи уже есть арт-эффект '.$a['art'].'',1);
$a['art'] = 'ветер';
$a['info'] .= '<br>Наложен арт-эффект '.$a['art'].' мастером рун '.$f['login'].'';
$q = $db->query("update `invent` set info='{$a['info']}',art='{$a['art']}' where id={$id} limit 1;");
if($f['rasa'] ==1){ $q = $db->query("update `users` set p_run=p_run+2,ag=ag-10 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_run=p_run+1,ag=ag-10 where id={$f['id']} limit 1;");}

msg2('Успех!');
fin();
}
elseif($go==6){
if(empty($ok)){
if($f['p_run'] <500) msg2('Вам это не по плечу',1);
if($f['ag'] <10) msg2('У вас нет 10 серебряных монет',1);
msg2('<form action="run.php?go=6&ok=1" method="post">Введите id вещи, на которую собираетесь наложить арт-эффект:<br>
<input type="number" name="id"/><br>
<input type="submit" value="ГО"/></form>',1);
fin();
}
$q = $db->query("select * from `invent` where id={$id} limit 1;");
$a = $q->fetch_assoc();
if($a['login'] != $f['login']) msg2('Это не ваша вещь',1);
if($a['flag_rinok'] ==1) msg2('Эта вещь находится на рынке',1);
if($a['flag_equip'] ==1) msg2('Эта вещь экипирована',1);
if($a['flag_sklad'] ==1) msg2('Эта вещь находится на складе',1);
if(!empty($a['art'])) msg2('На этой вещи уже есть арт-эффект '.$a['art'].'',1);
$a['art'] = 'пламя';
$a['info'] .= '<br>Наложен арт-эффект '.$a['art'].' мастером рун '.$f['login'].'';
$q = $db->query("update `invent` set info='{$a['info']}',art='{$a['art']}' where id={$id} limit 1;");
if($f['rasa'] ==1){ $q = $db->query("update `users` set p_run=p_run+2,ag=ag-10 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_run=p_run+1ag=ag-10 where id={$f['id']} limit 1;");}

msg2('Успех!');
fin();
}
elseif($go==7){
if(empty($ok)){
if($f['p_run'] <1000) msg2('Вам это не по плечу',1);
if($f['ag'] <10) msg2('У вас нет 10 серебряных монет',1);
msg2('<form action="run.php?go=7&ok=1" method="post">Введите id вещи, на которую собираетесь наложить арт-эффект:<br>
<input type="number" name="id"/><br>
<input type="submit" value="ГО"/></form>',1);
fin();
}
$q = $db->query("select * from `invent` where id={$id} limit 1;");
$a = $q->fetch_assoc();
if($a['login'] != $f['login']) msg2('Это не ваша вещь',1);
if($a['flag_rinok'] ==1) msg2('Эта вещь находится на рынке',1);
if($a['flag_equip'] ==1) msg2('Эта вещь экипирована',1);
if($a['flag_sklad'] ==1) msg2('Эта вещь находится на складе',1);
if(!empty($a['art'])) msg2('На этой вещи уже есть арт-эффект '.$a['art'].'',1);
$a['art'] = 'вампиризм';
$a['info'] .= '<br>Наложен арт-эффект '.$a['art'].' мастером рун '.$f['login'].'';
$q = $db->query("update `invent` set info='{$a['info']}',art='{$a['art']}' where id={$id} limit 1;");
if($f['rasa'] ==1){ $q = $db->query("update `users` set p_run=p_run+2,ag=ag-10 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_run=p_run+1,ag=ag-10 where id={$f['id']} limit 1;");}

msg2('Успех!');
fin();
}

?>