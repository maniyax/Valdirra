<?php
##############
# 24.12.2014 #
##############
require_once('inc/top.php');		// вывод на экран
require_once('inc/check.php');	// вход в игру
require_once('inc/head.php');
require_once('class/items.php');
require_once('inc/boi.php');		//создание боя + завод игрока в бой
require_once('inc/bot.php');		//параметры бота + создание бота

require_once('inc/hpstring.php');
$q = $db->query("select * from `klans` where loc={$f['loc']} or point={$f['loc']} limit 1;");
if($q->num_rows == 0)
	{
	knopka('loc.php', 'Замка здесь нет', 1);
	fin();
	}
$klan = $q->fetch_assoc();
// объявим переменные чтоб не зависеть от register globals
$mod = isset($_REQUEST['mod']) ? $_REQUEST['mod'] : '';
$go = isset($_REQUEST['go']) ? $_REQUEST['go'] : 0;
$start = isset($_REQUEST['start']) ? intval($_REQUEST['start']) : 0;
$iid = isset($_REQUEST['iid']) ? intval($_REQUEST['iid']) : 0;
$lgn = isset($_REQUEST['lgn']) ? intval($_REQUEST['lgn']) : 0;
$lgn1 = isset($_REQUEST['lgn1']) ? $_REQUEST['lgn1'] : '';

if(!empty($klan['point']) and empty($klan['loc']))
	{
	msg2('Недостроенный замок '.$klan['name']);
	switch($mod):
	default:
		if($f['klan'] != $klan['name']) msg2('Этот замок необходимо отстроить, а пока лишь ветер гуляет среди руин...',1);
		msg2('У вас '.$klan['kamni'].' камней.');
		knopka('zamok.php?mod=build', 'Отстроить замок (500 камней)', 1);
		if(3 <= $f['klan_status']) knopka('zamok.php?mod=drop', 'Отказаться от замка', 1);
		fin();
	break;

	case 'drop':
		if($f['klan_status'] < 3) msg('Недоступно для вас', 1);
		if(empty($go))
			{
			msg2('Вы уверены, что хотите освободить это место?');
			knopka('zamok.php?mod=drop&go=1', 'Отказаться от места', 1);
			knopka('loc.php', 'В игру', 1);
			fin();
			}
		$log = $f['login'].' ['.$f['lvl'].'] освобождает занятое для замка место.';
		$q = $db->query("insert into `klan_log` values(0,'{$f['login']}','{$log}','{$f['klan']}','{$t}');");
		$q = $db->query("update `klans` set point=0 where name='{$f['klan']}' limit 1;");
		msg2('Вы успешно отказались от места, теперь здесь может строиться любой клан.', 1);
	break;

	case 'build':
		if($klan['kamni'] < 500) msg('Недостаточно камней.', 1);
		if(empty($go))
			{
			msg2('Вы уверены, что хотите построить замок?');
			knopka('zamok.php?mod=build&go=1', 'Построить замок', 1);
			knopka('loc.php', 'В игру', 1);
			fin();
			}
		$log = $f['login'].' ['.$f['lvl'].'] строит замок для клана из '.$klan['kamni'].' камней.';
		$q = $db->query("insert into `klan_log` values(0,'{$f['login']}','{$log}','{$f['klan']}','{$t}');");
$q = $db->query("update `klans` set point=0, loc={$f['loc']},kamni=kamni-500 where name='{$f['klan']}' limit 1;");
		msg2('Вы успешно построили замок.');
		knopka('zamok.php', 'В замок', 1);
		knopka('loc.php', 'В игру', 1);
		fin();
	break;
	endswitch;
	fin();
	}

// этот код выполнится, только если тут будет замок.
msg2('Замок клана '.$klan['name']);

switch($mod):
default:
	knopka('zamok.php?mod=info', 'Информация', 1);
	if($klan['name'] == $f['klan'] and !empty($klan['altar'])) knopka('zamok.php?mod=altar', 'Алтарь', 1);
	if($klan['name'] == $f['klan'] and 2 <= $f['klan_status'] and $klan['altar'] < 5) knopka('zamok.php?mod=upaltar', 'Строить или улучшать алтарь', 1);
	if($klan['name'] == $f['klan'] and !empty($klan['pivo'])) knopka('zamok.php?mod=pivo', 'Пивоварня', 1);
	if($klan['name'] == $f['klan'] and 2 <= $f['klan_status'] and $klan['pivo'] < 5) knopka('zamok.php?mod=uppivo', 'Строить или улучшать пивоварню', 1);
	if($klan['name'] == $f['klan'] and !empty($klan['laba'])) knopka('zamok.php?mod=laba', 'Лаборатория', 1);
	if($klan['name'] == $f['klan'] and 2 <= $f['klan_status'] and $klan['laba'] < 5) knopka('zamok.php?mod=uplaba', 'Строить или улучшать лабораторию', 1);
	if($klan['name'] == $f['klan'] and !empty($klan['kuznica'])) knopka('zamok.php?mod=kuznica', 'Ювелирная', 1);
	if($klan['name'] == $f['klan'] and 2 <= $f['klan_status'] and $klan['kuznica'] < 5) knopka('zamok.php?mod=upkuznica', 'Строить или улучшать ювелирную', 1);
	if($klan['name'] == $f['klan'] and !empty($klan['oruzh'])) knopka('zamok.php?mod=oruzh', 'Оружейная мастерская', 1);
	if($klan['name'] == $f['klan'] and 2 <= $f['klan_status'] and $klan['oruzh'] < 3) knopka('zamok.php?mod=uporuzh', 'Строить или улучшать оружейную мастерскую', 1);
	if($klan['name'] == $f['klan']) knopka('zamok.php?mod=kam', 'Камень воскрешения', 1);
	if($klan['name'] == $f['klan']) knopka('zamok.php?mod=magazin', 'Магазин', 1);
	if($klan['name'] == $f['klan']) knopka('zamok.php?mod=plash', 'Магазин одежды', 1);
	break;

case 'info':
	echo '<div class="board"">';
	echo 'Сводка';
	echo '</div>';
	echo '<div class="board2" style="text-align:left">';
	echo 'Казна: '.$klan['kazna'].'<br/>';
	echo 'Камни: '.$klan['kamni'].'<br/>';
	if($klan['altar'] > 0) echo 'Уровень алтаря: '.$klan['altar'].'<br/>';
	if($klan['pivo'] > 0) echo 'Уровень пивоварни: '.$klan['pivo'].'<br/>';
	if($klan['laba'] > 0) echo 'Уровень лаборатории: '.$klan['laba'].'<br/>';
	if($klan['kuznica'] > 0) echo 'Уровень ювелирной: '.$klan['kuznica'].'<br/>';
	if($klan['oruzh'] > 0) echo 'Уровень оружейной мастерской: '.$klan['oruzh'].'<br/>';
	echo '</div>';
	if($klan['name'] == $f['klan']) knopka('zamok.php?mod=log','Дворовая книга', 1);
break;

case 'altar':
	if($f['klan'] != $klan['name']) msg2('Это не ваш замок!',1);
	if(empty($klan['altar'])) msg2('В замке нет алтаря!',1);
	msg('Небольшой постамент, на котором расположен жертвенный камень. От него ощутимо веет мощью.');
	$timer = $t + 86400;
	$f['altar'] = $klan['altar'];
	$f['altar_time'] = $timer;
	$q = $db->query("update `users` set altar={$klan['altar']},altar_time='{$timer}' where id={$f['id']} limit 1;");
	$f = calcparam($f);
	msg2('Боги довольны вами, усиление +'.$klan['altar'].'%');
break;

case 'upaltar':
	if($f['klan'] != $klan['name']) msg2('Это не ваш замок!',1);
	if($f['klan_status'] < 2) msg2('Вы не можете строить в замке.', 1);
	if(empty($klan['altar'])) { $money = 500; $kamni = 100;}
	elseif($klan['altar'] == 1) { $money = 1000; $kamni = 200;}
	elseif($klan['altar'] == 2) { $money = 2000; $kamni = 300;}
	elseif($klan['altar'] == 3) { $money = 5000; $kamni = 400;}
	elseif($klan['altar'] == 4) { $money = 10000; $kamni = 500;}
	else msg2('У вас алтарь максимального 5 уровня.',1);
	if(empty($go))
		{
		msg2('Вы действительно хотите построить или улучшить алтарь за '.$money.' монет и '.$kamni.' камней?');
		knopka('zamok.php?mod=upaltar&go=1', 'Продолжаем!', 1);
		knopka('zamok.php', 'Отказаться', 1);
		fin();
		}
	if($klan['kazna'] < $money) msg('В казне недостаточно денег для постройки алтаря, нужно '.$money, 1);
	if($klan['kamni'] < $kamni) msg('У клана недостаточно камней для постройки алтаря, нужно '.$kamni, 1);
	$klan['altar']++;
	$klan['kazna'] -= $money;
	$klan['kamni'] -= $kamni;
	if($klan['altar'] == 1) $log = $f['login'].' ['.$f['lvl'].'] строит алтарь клана за '.$kamni.' камней и '.$money.' монет.';
	else $log = $f['login'].' ['.$f['lvl'].'] улучшает алтарь клана до '.$klan['altar'].' уровня за '.$kamni.' камней и '.$money.' монет.';
	$q = $db->query("insert into `klan_log` values(0,'{$f['login']}','{$log}','{$f['klan']}','{$t}');");
	$q = $db->query("update `klans` set altar={$klan['altar']},kazna={$klan['kazna']},kamni={$klan['kamni']} where id={$klan['id']} limit 1;");
	if($klan['altar'] == 1) msg2('Вы построили алтарь!');
	else msg2('Вы улучшили алтарь до '.$klan['altar'].' уровня!');
	knopka('zamok.php', 'В замок', 1);
break;

case 'pivo':
	if($f['klan'] != $klan['name']) msg2('Это не ваш замок!',1);
	if(empty($klan['pivo'])) msg2('В замке нет пивоварни!',1);
if ($f['p_pivovar'] == 0) msg2('Вы не пивовар',1);
	if(empty($go))
		{
//		msg('Сухонький старичок поведал вам, что изготовить различные напитки в пивоварне может только истинный воин клана. Доказать свою преданность можно зарабатывая в ПвП боях очки чести!');
		if(1 <= $klan['pivo']) knopka('zamok.php?mod=pivo&go=1','Брага (нужен хмель)',1);
		if(2 <= $klan['pivo']) knopka('zamok.php?mod=pivo&go=2','Пиво (нужен солод)',1);
		if(3 <= $klan['pivo']) knopka('zamok.php?mod=pivo&go=3','Вино (нужен виноград)',1);
		if(4 <= $klan['pivo']) knopka('zamok.php?mod=pivo&go=4','Самогон (нужен мёд)',1);
		if(5 <= $klan['pivo']) knopka('zamok.php?mod=pivo&go=5','Рисовый шнапс (нужен рис)',1);
		}
	elseif($go == 1)
		{
		$need_id = 640;
		$res = 635;
		$need = $items->count_base_item($f['login'], $need_id);
		if($need == 0) msg2('У вас нет хмеля!',1);
if ($items->count_base_item($f['login'], 750) == 0) msg2('У тебя нет бутылки с водой',1);
//		if($f['chest'] == 0) msg2('У вас нет очков чести!',1);
if($f['rasa'] ==3){$q = $db->query("update `users` set p_pivovar=p_pivovar+1 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_pivovar=p_pivovar+1 where id={$f['id']} limit 1;");}
		$items->del_base_item($f['login'], $need_id,1);
$items->del_base_item($f['login'], 750, 1);
		$items->add_item($f['login'], $res, 1);
//		$q = $db->query("update `users` set chest=chest-1 where id='{$f['id']}' limit 1;");
		msg2('Вы приготовили брагу.');
		}
	elseif($go == 2)
		{
		if($klan['pivo'] < 2) msg('Необходима пивоварня минимум 2 уровня!',1);
		$need_id = 641;
		$res = 636;
		$need = $items->count_base_item($f['login'], $need_id);
		if($need == 0) msg2('У вас нет солода!',1);
if ($items->count_base_item($f['login'], 750) == 0) msg2('У тебя нет бутылки с водой',1);
//		if($f['chest'] == 0) msg2('У вас нет очков чести!',1);
if($f['p_pivovar'] <=50) msg2('У вас не достаточный уровень мастерства',1);
if($f['rasa'] ==3){$q = $db->query("update `users` set p_pivovar=p_pivovar+1 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_pivovar=p_pivovar+1 where id={$f['id']} limit 1;");}

		$items->del_base_item($f['login'], $need_id,1);
		$items->del_base_item($f['login'], 750, 1);
		$items->add_item($f['login'], $res, 1);
//		$q = $db->query("update `users` set chest=chest-1 where id='{$f['id']}' limit 1;");
		msg2('Вы приготовили пиво.');
		}
	elseif($go == 3)
		{
		if($klan['pivo'] < 3) msg('Необходима пивоварня минимум 3 уровня!',1);
		$need_id = 642;
		$res = 637;
		$need = $items->count_base_item($f['login'], $need_id);
		if($need == 0) msg2('У вас нет винограда!',1);
		if ($items->count_base_item($f['login'], 750) == 0) msg2('У тебя нет бутылки с водой',1);
//		if($f['chest'] == 0) msg2('У вас нет очков чести!',1);
if($f['p_pivovar'] <=100) msg2('У вас не достаточный уровень мастерства',1);
if($f['rasa'] ==3){$q = $db->query("update `users` set p_pivovar=p_pivovar+1 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_pivovar=p_pivovar+1 where id={$f['id']} limit 1;");}

		$items->del_base_item($f['login'], $need_id,1);
		$items->del_base_item($f['login'], 750, 1);
		$items->add_item($f['login'], $res, 1);
//		$q = $db->query("update `users` set chest=chest-1 where id='{$f['id']}' limit 1;");
		msg2('Вы приготовили вино.');
		}
	elseif($go == 4)
		{
		if($klan['pivo'] < 4) msg('Необходима пивоварня минимум 4 уровня!',1);
		$need_id = 643;
		$res = 638;
		$need = $items->count_base_item($f['login'], $need_id);
		if($need == 0) msg2('У вас нет мёда!',1);
		if ($items->count_base_item($f['login'], 750) == 0) msg2('У тебя нет бутылки с водой',1);
//		if($f['chest'] == 0) msg2('У вас нет очков чести!',1);
if($f['p_pivovar'] <=500) msg2('У вас не достаточный уровень мастерства',1);
if($f['rasa'] ==3){$q = $db->query("update `users` set p_pivovar=p_pivovar+1 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_pivovar=p_pivovar+1 where id={$f['id']} limit 1;");}

		$items->del_base_item($f['login'], $need_id,1);
		$items->del_base_item($f['login'], 750, 1);
		$items->add_item($f['login'], $res, 1);
//		$q = $db->query("update `users` set chest=chest-1 where id='{$f['id']}' limit 1;");
		msg2('Вы приготовили самогон.');
		}
	elseif($go == 5)
		{
		if($klan['pivo'] < 5) msg('Необходима пивоварня минимум 5 уровня!',1);
		$need_id = 644;
		$res = 639;
		$need = $items->count_base_item($f['login'], $need_id);
		if($need == 0) msg2('У вас нет риса!',1);
		if ($items->count_base_item($f['login'], 750) == 0) msg2('У тебя нет бутылки с водой',1);
//		if($f['chest'] == 0) msg2('У вас нет очков чести!',1);
if($f['p_pivovar'] <=1000) msg2('У вас не достаточный уровень мастерства',1);
if($f['rasa'] ==3){$q = $db->query("update `users` set p_pivovar=p_pivovar+1 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_pivovar=p_pivovar+1 where id={$f['id']} limit 1;");}

		$items->del_base_item($f['login'], $need_id,1);
		$items->del_base_item($f['login'], 750, 1);
		$items->add_item($f['login'], $res, 1);
//		$q = $db->query("update `users` set chest=chest-1 where id='{$f['id']}' limit 1;");
		msg2('Вы приготовили рисовый шнапс.');
		}
	else
		{
		$m = $f['lvl'] * 1000;
		$q = $db->query("update `users` set cu=cu-'{$m}' where id={$f['id']} limit 1;");
		msg2('Подменять ссылки нехорошо. С вас списан штраф '.$m.' монет за баловство.');
		}
break;

case 'uppivo':
	if($f['klan'] != $klan['name']) msg2('Это не ваш замок!',1);
	if($f['klan_status'] < 2) msg2('Вы не можете строить в замке.', 1);
	if(empty($klan['pivo'])) { $money = 500; $kamni = 100; $derevo = 2;}
	elseif($klan['pivo'] == 1) { $money = 1000; $kamni = 250; $derevo = 3;}
	elseif($klan['pivo'] == 2) { $money = 2000; $kamni = 500; $derevo = 4;}
	elseif($klan['pivo'] == 3) { $money = 5000; $kamni = 750; $derevo = 5;}
	elseif($klan['pivo'] == 4) { $money = 10000; $kamni = 1000; $derevo = 6;}
	else msg2('У вас пивоварня максимального 5 уровня.',1);
	if(empty($go))
		{
		msg2('Вы действительно хотите построить или улучшить пивоварню за '.$derevo.' бревен, '.$money.' монет и '.$kamni.' камней?');
		knopka('zamok.php?mod=uppivo&go=1', 'Продолжаем!', 1);
		knopka('zamok.php', 'Отказаться', 1);
		fin();
		}
	if($klan['kazna'] < $money) msg('В казне недостаточно денег для постройки или улучшения пивоварни, нужно '.$money, 1);
	if($klan['kamni'] < $kamni) msg('У клана недостаточно камней для постройки или улучшения пивоварни, нужно '.$kamni, 1);
if ($items->count_base_item($f['login'], 724) < $derevo) msg2('У тебя не достаточно бревен ели, нужно '.$derevo.'', 1);
	$klan['pivo']++;
	$klan['kazna'] -= $money;
	$klan['kamni'] -= $kamni;
	if($klan['pivo'] == 1) $log = $f['login'].' ['.$f['lvl'].'] строит пивоварню клана за '.$derevo.' дерева, '.$kamni.' камней и '.$money.' монет.';
	else $log = $f['login'].' ['.$f['lvl'].'] улучшает пивоварню клана до '.$klan['pivo'].' уровня за '.$kamni.' камней и '.$money.' монет.';
	$q = $db->query("insert into `klan_log` values(0,'{$f['login']}','{$log}','{$f['klan']}','{$t}');");
$items->del_base_item($f['login'], 724, $derevo);
	$q = $db->query("update `klans` set pivo={$klan['pivo']},summ=summ+1,kazna={$klan['kazna']},kamni={$klan['kamni']} where id={$klan['id']} limit 1;");
	if($klan['pivo'] == 1) msg2('Вы построили пивоварню!');
	else msg2('Вы улучшили пивоварню до '.$klan['pivo'].' уровня!');
	knopka('zamok.php', 'В замок', 1);
break;

case 'laba':

	if($f['klan'] != $klan['name']) msg2('Это не ваш замок!',1);
	if(empty($klan['laba'])) msg2('В замке нет лаборатории!',1);
if ($f['p_alhimik'] == 0) msg2('Вы не алхимик',1);
	if(empty($go))
		{
		msg('Создавая магическим образом лечебные зелья, вы отдаете свою силу, концентрируете ее в склянках');
		if(1 <= $klan['laba']) knopka('zamok.php?mod=laba&go=1','Великая эссенця исцеления (+350HP)',1);
		if(2 <= $klan['laba']) knopka('zamok.php?mod=laba&go=2','Великая вытяжка исцеления (+500HP)',1);
		if(3 <= $klan['laba']) knopka('zamok.php?mod=laba&go=3','Великий эликсир лечения (+750HP)',1);
		if(4 <= $klan['laba']) knopka('zamok.php?mod=laba&go=4','Великий напиток лечения (+1000HP)',1);
		if(5 <= $klan['laba']) knopka('zamok.php?mod=laba&go=5','Лечебный экстракт (+1500HP)',1);
		if(1 <= $klan['laba']) knopka('zamok.php?mod=laba&go=6','Великая эссенция мудрости (+350MP)',1);
		if(2 <= $klan['laba']) knopka('zamok.php?mod=laba&go=7','Великая вытяжка мудрости (+500MP)',1);
		if(3 <= $klan['laba']) knopka('zamok.php?mod=laba&go=8','Великий эликсир мудрости (+750MP)',1);
		if(4 <= $klan['laba']) knopka('zamok.php?mod=laba&go=9','Великий напиток мудрости (+1000MP)',1);
		if(5 <= $klan['laba']) knopka('zamok.php?mod=laba&go=10','Экстракт мудрости (+1500MP)',1);
		}
	elseif($go == 1)
{

		$res = 625;
if ($items->count_base_item($f['login'], 750) == 0) msg2('У тебя нет бутылки с водой',1);
if ($items->count_base_item($f['login'], 751) < 2) msg2('У тебя нет лечебной травы в количестве 2 шт.',1);
$items->del_base_item($f['login'], 751, 2);
$items->del_base_item($f['login'], 750, 1);
		$items->add_item($f['login'], $res, 1);
if($f['rasa'] ==3){$q = $db->query("update `users` set p_alhimik=p_alhimik+2 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_alhimik=p_alhimik+1 where id={$f['id']} limit 1;");}
		msg2('Вы приготовили зелье.');
		}
	elseif($go == 2)
		{
		if($klan['laba'] < 2) msg('Необходима лаборатория минимум 2 уровня!',1);

		$res = 626;
if ($items->count_base_item($f['login'], 750) == 0) msg2('У тебя нет бутылки с водой',1);
if ($items->count_base_item($f['login'], 751) < 3) msg2('У тебя нет лечебной травы в количестве 3 шт.',1);
if($f['p_alhimik'] <=50) msg2('У вас не достаточный уровень мастерства',1);
if($f['rasa'] ==3){$q = $db->query("update `users` set p_alhimik=p_alhimik+2 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_alhimik=p_alhimik+1 where id={$f['id']} limit 1;");}

$items->del_base_item($f['login'], 751, 3);
$items->del_base_item($f['login'], 750, 1);
		$items->add_item($f['login'], $res, 1);

		msg2('Вы приготовили зелье.');
		}
	elseif($go == 3)
		{
		if($klan['laba'] < 3) msg('Необходима лаборатория минимум 3 уровня!',1);

		$res = 627;
if ($items->count_base_item($f['login'], 750) == 0) msg2('У тебя нет бутылки с водой',1);
if ($items->count_base_item($f['login'], 751) < 4) msg2('У тебя нет лечебной травы в количестве 4 шт.',1);
if($f['p_alhimik'] <=100) msg2('У вас не достаточный уровень мастерства',1);
if($f['rasa'] ==3){$q = $db->query("update `users` set p_alhimik=p_alhimik+2 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_alhimik=p_alhimik+1 where id={$f['id']} limit 1;");}

$items->del_base_item($f['login'], 751, 4);
$items->del_base_item($f['login'], 750, 1);
		$items->add_item($f['login'], $res, 1);

		msg2('Вы приготовили зелье.');
		}
	elseif($go == 4)
		{
		if($klan['laba'] < 4) msg('Необходима лаборатория минимум 4 уровня!',1);

		$res = 628;
if ($items->count_base_item($f['login'], 750) == 0) msg2('У тебя нет бутылки с водой',1);
if ($items->count_base_item($f['login'], 751) < 5) msg2('У тебя нет лечебной травы в количестве 5 шт.',1);
if($f['p_alhimik'] <=500) msg2('У вас не достаточный уровень мастерства',1);
if($f['rasa'] ==3){$q = $db->query("update `users` set p_alhimik=p_alhimik+2 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_alhimik=p_alhimik+1 where id={$f['id']} limit 1;");}

$items->del_base_item($f['login'], 751, 5);
$items->del_base_item($f['login'], 750, 1);
		$items->add_item($f['login'], $res, 1);

		msg2('Вы приготовили зелье.');
		}
	elseif($go == 5)
		{
		if($klan['laba'] < 5) msg('Необходима лаборатория минимум 5 уровня!',1);

		$res = 629;
if ($items->count_base_item($f['login'], 750) == 0) msg2('У тебя нет бутылки с водой',1);
if ($items->count_base_item($f['login'], 751) < 6) msg2('У тебя нет лечебной травы в количестве 6 шт.',1);
if($f['p_alhimik'] <=1000) msg2('У вас не достаточный уровень мастерства',1);
if($f['rasa'] ==3){$q = $db->query("update `users` set p_alhimik=p_alhimik+2 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_alhimik=p_alhimik+1 where id={$f['id']} limit 1;");}

$items->del_base_item($f['login'], 751, 6);
$items->del_base_item($f['login'], 750, 1);
		$items->add_item($f['login'], $res, 1);

		msg2('Вы приготовили зелье.');
		}
	elseif($go == 6)
		{

		$res = 630;
if ($items->count_base_item($f['login'], 750) == 0) msg2('У тебя нет бутылки с водой',1);
if ($items->count_base_item($f['login'], 752) < 2) msg2('У тебя нет магической травы в количестве 2 шт.',1);
$items->del_base_item($f['login'], 752, 2);
$items->del_base_item($f['login'], 750, 1);
		$items->add_item($f['login'], $res, 1);
if($f['rasa'] ==3){$q = $db->query("update `users` set p_alhimik=p_alhimik+2 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_alhimik=p_alhimik+1 where id={$f['id']} limit 1;");}

		msg2('Вы приготовили зелье.');
		}
	elseif($go == 7)
		{
		if($klan['laba'] < 2) msg('Необходима лаборатория минимум 2 уровня!',1);

		$res = 631;
if ($items->count_base_item($f['login'], 750) == 0) msg2('У тебя нет бутылки с водой',1);
if ($items->count_base_item($f['login'], 752) < 3) msg2('У тебя нет магической травы в количестве 3 шт.',1);
if($f['p_alhimik'] <=50) msg2('У вас не достаточный уровень мастерства',1);
if($f['rasa'] ==3){$q = $db->query("update `users` set p_alhimik=p_alhimik+2 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_alhimik=p_alhimik+1 where id={$f['id']} limit 1;");}

$items->del_base_item($f['login'], 752, 3);
$items->del_base_item($f['login'], 750, 1);
		$items->add_item($f['login'], $res, 1);

		msg2('Вы приготовили зелье.');
		}
	elseif($go == 8)
		{
		if($klan['laba'] < 3) msg('Необходима лаборатория минимум 3 уровня!',1);

		$res = 632;
if ($items->count_base_item($f['login'], 750) == 0) msg2('У тебя нет бутылки с водой',1);
if ($items->count_base_item($f['login'], 752) < 4) msg2('У тебя нет магической травы в количестве 4 шт.',1);
if($f['p_alhimik'] <=100) msg2('У вас не достаточный уровень мастерства',1);
if($f['rasa'] ==3){$q = $db->query("update `users` set p_alhimik=p_alhimik+2 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_alhimik=p_alhimik+1 where id={$f['id']} limit 1;");}

$items->del_base_item($f['login'], 752, 4);
$items->del_base_item($f['login'], 750, 1);
		$items->add_item($f['login'], $res, 1);

		msg2('Вы приготовили зелье.');
		}
	elseif($go == 9)
		{
		if($klan['laba'] < 4) msg('Необходима лаборатория минимум 4 уровня!',1);

		$res = 633;
if ($items->count_base_item($f['login'], 750) == 0) msg2('У тебя нет бутылки с водой',1);
if ($items->count_base_item($f['login'], 752) < 5) msg2('У тебя нет магической травы в количестве 5 шт.',1);
if($f['p_alhimik'] <=500) msg2('У вас не достаточный уровень мастерства',1);
if($f['rasa'] ==3){$q = $db->query("update `users` set p_alhimik=p_alhimik+2 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_alhimik=p_alhimik+1 where id={$f['id']} limit 1;");}

$items->del_base_item($f['login'], 752, 5);
$items->del_base_item($f['login'], 750, 1);
		$items->add_item($f['login'], $res, 1);

		msg2('Вы приготовили зелье. МП -'.$need_id);
		}
	elseif($go == 10)
		{
		if($klan['laba'] < 5) msg('Необходима лаборатория минимум 5 уровня!',1);

		$res = 634;
if ($items->count_base_item($f['login'], 750) == 0) msg2('У тебя нет бутылки с водой',1);
if ($items->count_base_item($f['login'], 752) < 6) msg2('У тебя нет лечебной травы в количестве 6 шт.',1);
if($f['p_alhimik'] <=1000) msg2('У вас не достаточный уровень мастерства',1);
if($f['rasa'] ==3){$q = $db->query("update `users` set p_alhimik=p_alhimik+2 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_alhimik=p_alhimik+1 where id={$f['id']} limit 1;");}

$items->del_base_item($f['login'], 752, 6);
$items->del_base_item($f['login'], 750, 1);
		$items->add_item($f['login'], $res, 1);

		msg2('Вы приготовили зелье.');
		}
	else
		{
		$m = $f['lvl'] * 1000;
		$q = $db->query("update `users` set cu=cu-'{$m}' where id={$f['id']} limit 1;");
		msg2('Подменять ссылки нехорошо. С вас списан штраф '.$m.' монет за баловство.');
		}
break;

case 'uplaba':
	if($f['klan'] != $klan['name']) msg2('Это не ваш замок!',1);
	if($f['klan_status'] < 2) msg2('Вы не можете строить в замке.', 1);
	if(empty($klan['laba'])) { $money = 500; $kamni = 100; $derevo = 2;}
	elseif($klan['laba'] == 1) { $money = 1000; $kamni = 250; $derevo = 3;}
	elseif($klan['laba'] == 2) { $money = 2000; $kamni = 500; $derevo = 4;}
	elseif($klan['laba'] == 3) { $money = 5000; $kamni = 750; $derevo = 5;}
	elseif($klan['laba'] == 4) { $money = 10000; $kamni = 1000; $derevo = 6;}
	else msg2('У вас лаборатория максимального 5 уровня.',1);
	if(empty($go))
		{
		msg2('Вы действительно хотите построить или улучшить лабораторию за '.$derevo.' бревен, '.$money.' монет и '.$kamni.' камней?');
		knopka('zamok.php?mod=uplaba&go=1', 'Продолжаем!', 1);
		knopka('zamok.php', 'Отказаться', 1);
		fin();
		}
	if($klan['kazna'] < $money) msg('В казне недостаточно денег для постройки или улучшения лаборатории, нужно '.$money, 1);
	if($klan['kamni'] < $kamni) msg('У клана недостаточно камней для постройки или улучшения лаборатории, нужно '.$kamni, 1);
if ($items->count_base_item($f['login'], 724) < $derevo) msg2('У тебя не достаточно бревен ели, нужно '.$derevo.'', 1);
	$klan['laba']++;
	$klan['kazna'] -= $money;
	$klan['kamni'] -= $kamni;
	if($klan['laba'] == 1) $log = $f['login'].' ['.$f['lvl'].'] строит лабораторию клана за '.$kamni.' камней и '.$money.' монет.';
	else $log = $f['login'].' ['.$f['lvl'].'] улучшает лабораторию клана до '.$klan['laba'].' уровня за '.$kamni.' камней и '.$money.' монет.';
	$q = $db->query("insert into `klan_log` values(0,'{$f['login']}','{$log}','{$f['klan']}','{$t}');");
$items->del_base_item($f['login'], 724, $derevo);
	$q = $db->query("update `klans` set laba={$klan['laba']},kazna={$klan['kazna']},kamni={$klan['kamni']} where id={$klan['id']} limit 1;");
	if($klan['laba'] == 1) msg2('Вы построили лабораторию!');
	else msg2('Вы улучшили лабораторию до '.$klan['laba'].' уровня!');
	knopka('zamok.php', 'В замок', 1);
break;

case 'kuznica':
	if($f['klan'] != $klan['name']) msg2('Это не ваш замок!',1);
	if(empty($klan['kuznica'])) msg2('В замке нет ювелирной!',1);
	$q = $db->query("select max(lvl) from `item`;");
	$a = $q->fetch_assoc();
	$max_lvl = $a['max(lvl)'];
	if($klan['kuznica'] == 1) $max_lvl = 9;
	if($klan['kuznica'] == 2) $max_lvl = 13;
	if($klan['kuznica'] == 3) $max_lvl = 17;
	if($klan['kuznica'] == 4) $max_lvl = 21;
	if (empty($start))
		{
		echo '<div class="board" style="text-align:left">';
		echo '<form action="zamok.php?mod=kuznica" method="POST">
		На какой уровень желаете собрать вещи?<br/>
		<select name="start">';
		for ($i = 6; $i <= $max_lvl; $i++) echo '<option value='.$i.'>'.$i.'</option>';
		echo '</select>';
		echo '<input type="submit" value="Далее"/></form>';
		fin();
		}
	if ($start < 6) $start = 6;
	if ($start > $max_lvl) $start = $max_lvl;
	if (empty($iid))
		{
		$it1 = $items->base_shmot(168); //тотем
		$it2 = $items->base_shmot(169); //брас
		$c = $start * 100;
		msg2('Вы хотите собрать вещи на '.$start.' уровень. Цена '.$c.' монет');
		knopka('zamok.php?mod=kuznica&start='.$start.'&iid=1', 'Амулет (с) (необходимо '.$it1['name'].')', 1);
		knopka('zamok.php?mod=kuznica&start='.$start.'&iid=2', 'Амулет (к) (необходимо '.$it1['name'].')', 1);
		knopka('zamok.php?mod=kuznica&start='.$start.'&iid=3', 'Амулет (у) (необходимо '.$it1['name'].')', 1);
		knopka('zamok.php?mod=kuznica&start='.$start.'&iid=4', 'Браслет (с) (необходимо '.$it2['name'].')', 1);
		knopka('zamok.php?mod=kuznica&start='.$start.'&iid=5', 'Браслет (к) (необходимо '.$it2['name'].')', 1);
		knopka('zamok.php?mod=kuznica&start='.$start.'&iid=6', 'Браслет (у) (необходимо '.$it2['name'].')', 1);
		fin();
		}
	$iid = intval($iid);
	if ($iid == 1) $num = $start * 3 - 17;
	elseif ($iid == 2) $num = $start * 3 - 16;
	elseif ($iid == 3) $num = $start * 3 - 15;
	elseif ($iid == 4) $num = $start * 3 + 43;
	elseif ($iid == 5) $num = $start * 3 + 44;
	elseif ($iid == 6) $num = $start * 3 + 45;
	else
		{
		$m = $f['lvl'] * 1000;
		$q = $db->query("update `users` set cu=cu-'{$m}' where id={$f['id']} limit 1;");
		msg2('Подменять ссылки нехорошо. С вас списан штраф '.$m.' монет за баловство.',1);
		}
	if ($f['cu'] < ($start * 100)) msg2('У вас не хватает денег!', 1);
	$item = $items->base_shmot($num);
	if ($item['equip'] == 'braslet') $zzz = 169;
	if ($item['equip'] == 'amulet') $zzz = 168;
	$itz = $items->base_shmot($zzz);
	if ($items->count_base_item($f['login'], $zzz) == 0) msg2('У вас нет '.$itz['name'], 1);
	if (empty($go))
		{
		msg2('Вы уверены, что хотите собрать '.$item['name'].'?');
		knopka('zamok.php?mod=kuznica&start='.$start.'&iid='.$iid.'&go=1', 'Собрать', 1);
		knopka('loc.php', 'В игру', 1);
		fin();
		}
	$items->del_base_item($f['login'], $zzz, 1);
	$items->add_item($f['login'], $num, 1);
	$f['cu'] -= ($start * 100);
	$q = $db->query("update `users` set cu={$f['cu']} where id={$f['id']} limit 1;");
	msg2('Вы перековали '.$itz['name'].' в '.$item['name'].'!');
break;

case 'upkuznica':
	if($f['klan'] != $klan['name']) msg2('Это не ваш замок!',1);
	if($f['klan_status'] < 2) msg2('Вы не можете строить в замке.', 1);
	if(empty($klan['kuznica'])) { $money = 500; $kamni = 100; $derevo = 2;}
	elseif($klan['kuznica'] == 1) { $money = 1000; $kamni = 250; $derevo = 3;}
	elseif($klan['kuznica'] == 2) { $money = 2000; $kamni = 500; $derevo = 4;}
	elseif($klan['kuznica'] == 3) { $money = 5000; $kamni = 750; $derevo = 5;}
	elseif($klan['kuznica'] == 4) { $money = 10000; $kamni = 1000; $derevo = 6;}
	else msg2('У вас ювелирная максимального 5 уровня.',1);
	if(empty($go))
		{
		msg2('Вы действительно хотите построить или улучшить ювелирную за '.$derevo.' бревен, '.$money.' монет и '.$kamni.' камней?');
		knopka('zamok.php?mod=upkuznica&go=1', 'Продолжаем!', 1);
		knopka('zamok.php', 'Отказаться', 1);
		fin();
		}
	if($klan['kazna'] < $money) msg('В казне недостаточно денег для постройки или улучшения ювелирной, нужно '.$money, 1);
	if($klan['kamni'] < $kamni) msg('У клана недостаточно камней для постройки или улучшения ювелирной, нужно '.$kamni, 1);
if ($items->count_base_item($f['login'], 724) < $derevo) msg2('У тебя не достаточно бревен ели, нужно '.$derevo.'', 1);
	$klan['kuznica']++;
	$klan['kazna'] -= $money;
	$klan['kamni'] -= $kamni;
	if($klan['kuznica'] == 1) $log = $f['login'].' ['.$f['lvl'].'] строит ювелирную клана за '.$kamni.' камней и '.$money.' монет.';
	else $log = $f['login'].' ['.$f['lvl'].'] улучшает ювелирную клана до '.$klan['kuznica'].' уровня за '.$kamni.' камней и '.$money.' монет.';
	$q = $db->query("insert into `klan_log` values(0,'{$f['login']}','{$log}','{$f['klan']}','{$t}');");
$items->del_base_item($f['login'], 724, $derevo);
	$q = $db->query("update `klans` set kuznica={$klan['kuznica']},kazna={$klan['kazna']},kamni={$klan['kamni']} where id={$klan['id']} limit 1;");
	if($klan['kuznica'] == 1) msg2('Вы построили ювелирную!');
	else msg2('Вы улучшили ювелирную до '.$klan['kuznica'].' уровня!');
	knopka('zamok.php', 'В замок', 1);
break;

case 'oruzh':
if($f['p_kuznec'] == 0) msg2('Вы не кузнец',1);
if($items->count_base_item($f['login'], 781) == 0) msg2('У вас нет кузнечного молота',1);
if($start >= 6 and $start <= 8) {
$kuznec = 0;
$count = 90;
$ruda = 729;
$name_ruda = "железной";
}
elseif($start >= 9 and $start <= 11) {
$kuznec = 25;
$count = 80;
$ruda = 730;
$name_ruda = 'Мифриловой';
}
elseif($start >= 12 and $start <= 14) {
$kuznec = 50;
$count = 70;
$ruda = 731;
$name_ruda = 'Эбонитовой';
}
elseif($start >= 15 and $start <= 17) {
$kuznec = 100;
$count = 60;
$ruda = 732;
$name_ruda = 'Адамантиновой';
}
elseif($start >= 18 and $start <= 20) {
$kuznec = 250;
$count = 50;
$ruda = 733;
$name_ruda = 'Кобольтовой';
}
elseif($start >= 21 and $start <= 23) {
$kuznec = 500;
$count = 40;
$ruda = 734;
$name_ruda = 'Обсидиановой';
}
elseif($start >= 24) {
$kuznec = 1000;
$count = 30;
$ruda = 780;
$name_ruda = 'Чёрной';
}

if($f['klan'] != $klan['name']) msg2('Это не ваш замок!',1);
	if(empty($klan['oruzh'])) msg2('В замке нет оружейной мастерской!',1);
	$q = $db->query("select max(lvl) from `item`;");
	$a = $q->fetch_assoc();
	$max_lvl = $a['max(lvl)'];
	if (empty($start))
		{
		echo '<div class="board" style="text-align:left">';
		echo '<form action="zamok.php?mod=oruzh" method="POST">
		На какой уровень желаете изготовить вещи?<br/>
		<select name="start">';
		for ($i = 6; $i <= $max_lvl; $i++) echo '<option value='.$i.'>'.$i.'</option>';
		echo '</select>';
		echo '<input type="submit" value="Далее"/></form>';
		fin();
		}
	if ($start < 6) $start = 6;
	if ($start > $max_lvl) $start = $max_lvl;
	if (empty($iid))
		{ // ДОДЕЛАТЬ ДАЛЬШЕ!!! НАДО ПРОДУМАТЬ ЦЕНУ КАК СЧИТАТЬ!!!
		msg2('Вы хотите собрать вещи на '.$start.' уровень.');
		if($klan['oruzh'] >= 1)
			{
			$idd = $start + 659;
			$item = $items->base_shmot($idd);
			echo '<div class="board2" style="text-align:left">';
			echo '<a href="zamok.php?mod=oruzh&start='.$start.'&iid=2">'.$item['name'].'</a> ('.$count.' '.$name_ruda.' руды)';
			echo ' <a href="shop.php?mod=iteminfa&iid='.$idd.'">[infa]</a>';
			echo '</div>';
			}
		if(3 <= $klan['oruzh'])
			{
			$idd = $start + 679;
			$item = $items->base_shmot($idd);
			echo '<div class="board2" style="text-align:left">';
			echo '<a href="zamok.php?mod=oruzh&start='.$start.'&iid=3">'.$item['name'].'</a> ('.$count.' '.$name_ruda.' руды)';
			echo ' <a href="shop.php?mod=iteminfa&iid='.$idd.'">[infa]</a>';
			echo '</div>';
			}
		fin();
		}
	$iid = intval($iid);
	if($iid == 2) $num = $start + 659;
	elseif($iid == 3) $num = $start + 679;
	else
		{
		$m = $f['lvl'] * 1000;
		$q = $db->query("update `users` set cu=cu-'{$m}' where id={$f['id']} limit 1;");
		msg2('Подменять ссылки нехорошо. С вас списан штраф '.$m.' монет за баловство.',1);
		}
	$item = $items->base_shmot($num);

if($items->count_base_item($f['login'], $ruda, $count) < $count) msg2('У вас не достаточно руды)',1);
if($f['p_kuznec'] <= $kuznec) msg2('У вас не достаточно мастерства',1);
	if (empty($go))
		{
		msg2('Вы уверены, что хотите изготовить '.$item['name'].'?');
		knopka('zamok.php?mod=oruzh&start='.$start.'&iid='.$iid.'&go=1', 'Изготовить', 1);
		knopka('loc.php', 'В игру', 1);
		fin();
		}
$items->del_base_item($f['login'], $ruda, $count);
$items->add_item($f['login'], $num, 1);
$log = 'Игрок '.$f['login'].' создаёт '.$item['name'].' на '.$item['lvl'].' уровень';
$q =$db->query("insert into `log_peredach` values(0,'{$f['login']}','{$log}','{$f['login']}','{$t}');");
if($f['rasa'] ==2){$q = $db->query("update `users` set p_kuznec=p_kuznec+2,molot=molot-1 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_kuznec=p_kuznec+1,molot=molot-1 where id={$f['id']} limit 1;");}
	msg2('Вы изготовили '.$item['name'].'!');
break;

case 'uporuzh':
	if($f['klan'] != $klan['name']) msg2('Это не ваш замок!',1);
	if($f['klan_status'] < 2) msg2('Вы не можете строить в замке.', 1);
	if(empty($klan['oruzh'])) { $money = 500; $kamni = 100; $derevo = 2;}
	elseif($klan['oruzh'] == 1) { $money = 5000; $kamni = 500; $derevo = 3;}
	elseif($klan['oruzh'] == 2) { $money = 10000; $kamni = 1000; $derevo = 4;}
	else msg2('У вас оружейная мастерская максимального 3 уровня.',1);
	if(empty($go))
		{
		msg2('Вы действительно хотите построить или улучшить оружейную мастерскую за '.$derevo.' бревен, '.$money.' монет и '.$kamni.' камней?');
		knopka('zamok.php?mod=uporuzh&go=1', 'Продолжаем!', 1);
		knopka('zamok.php', 'Отказаться', 1);
		fin();
		}
	if($klan['kazna'] < $money) msg('В казне недостаточно денег для постройки или улучшения оружейной мастерской, нужно '.$money, 1);
	if($klan['kamni'] < $kamni) msg('У клана недостаточно камней для постройки или улучшения оружейной мастерской, нужно '.$kamni, 1);
if ($items->count_base_item($f['login'], 724) < $derevo) msg2('У тебя не достаточно бревен ели, нужно '.$derevo.'', 1);
	$klan['oruzh']++;
	$klan['kazna'] -= $money;
	$klan['kamni'] -= $kamni;
	if($klan['oruzh'] == 1) $log = $f['login'].' ['.$f['lvl'].'] строит оружейную мастерскую клана за '.$kamni.' камней и '.$money.' монет.';
	else $log = $f['login'].' ['.$f['lvl'].'] улучшает оружейную мастерскую клана до '.$klan['altar'].' уровня за '.$kamni.' камней и '.$money.' монет.';
	$q = $db->query("insert into `klan_log` values(0,'{$f['login']}','{$log}','{$f['klan']}','{$t}');");
$items->del_base_item($f['login'], 724, $derevo);
	$q = $db->query("update `klans` set oruzh={$klan['oruzh']},kazna={$klan['kazna']},kamni={$klan['kamni']} where id={$klan['id']} limit 1;");
	if($klan['oruzh'] == 1) msg2('Вы построили оружейную мастерскую!');
	else msg2('Вы улучшили оружейную мастерскую до '.$klan['oruzh'].' уровня!');
	knopka('zamok.php', 'В замок', 1);
break;

case 'log':
	if($klan['name'] != $f['klan']) msg2('Смотреть дворовую книгу могут только члены клана '.$klan['name'],1);
	msg2('<form action="zamok.php" method="GET"><input type="hidden" name="mod" value="log"/> 
Логин игрока: <input type="text" name="lgn1" /> <input type="submit" value="Отсортировать"/></form>');

if(empty($lgn1)){

	$numb = 25;			//записей на страницу
	$count = 0;
	$q = $db->query("select count(*) from `klan_log` where klan='{$f['klan']}';");
	$a = $q->fetch_assoc();
	$all_log = $a['count(*)'];
	if($start > intval($all_log / $numb)) $start = intval($all_log / $numb);
	if($start < 0) $start = 0;
	$limit = $start * $numb;
	$count = $limit;
	$q = $db->query("select * from `klan_log` where klan='{$f['klan']}' order by id desc limit {$limit},{$numb};");
	while($log = $q->fetch_assoc())
		{
		$count++;
		echo '<div class="board2" style="text-align:left">';
		echo $count.'. '.date('d.m.Y H:i',$log['date']).' - '.$log['log'];
		echo '</div>';
		}
	if($all_log > $numb)
		{
		echo '<div class="board">';
		if($start > 0) echo '<a href="zamok.php?mod=log&start='.($start - 1).'" class="navig"><-Назад</a>'; else echo '<a href="#" class="navig"> <-Назад</a>';
		echo ' | ';
		if($limit + $numb < $all_log) echo '<a href="zamok.php?mod=log&start='.($start + 1).'" class="navig" >Вперед-></a>'; else echo ' <a href="#" class="navig"> Вперед-></a>';
		echo '</div>';
		}
	fin();
}
//$TestLgn = get_login($lgn);
//$TestLgn = $_GET['lgn1'];
else{

msg2('Действия игрока '.$lgn1.'');
	$numb = 25;			//записей на страницу
	$count = 0;
	$q = $db->query("select count(*) from `klan_log` where klan='{$f['klan']}' and login='{$lgn1}';");
	$a = $q->fetch_assoc();
	$all_log = $a['count(*)'];
	if($start > intval($all_log / $numb)) $start = intval($all_log / $numb);
	if($start < 0) $start = 0;
	$limit = $start * $numb;
	$count = $limit;
	$q = $db->query("select * from `klan_log` where klan='{$f['klan']}' and login='{$lgn1}' order by id desc limit {$limit},{$numb};");
	while($log = $q->fetch_assoc())
		{
		$count++;
		echo '<div class="board2" style="text-align:left">';
		echo $count.'. '.date('d.m.Y H:i',$log['date']).' - '.$log['log'];
		echo '</div>';
		}
	if($all_log > $numb)
		{
		echo '<div class="board">';
		if($start > 0) echo '<a href="zamok.php?mod=log&start='.($start - 1).'" class="navig"><-Назад</a>'; else echo '<a href="#" class="navig"> <-Назад</a>';
		echo ' | ';
		if($limit + $numb < $all_log) echo '<a href="zamok.php?mod=log&start='.($start + 1).'" class="navig" >Вперед-></a>'; else echo ' <a href="#" class="navig"> Вперед-></a>';
		echo '</div>';
		}
	fin();
}
break;

case 'kam':
if ($f['loc'] != $klan['loc']){
msg2('Ошибка локации', 1);
fin();
}
msg2('<b>Камень воскрешения</b>');

if (empty($go))
	{
	msg2('
Большой рунический камень, можно привязать свое место воскрешения к такому камню, и после смерти вы будете переноситься к нему.');
	knopka('zamok.php?mod=kam&go=1', 'Привязаться', 1);
	knopka('loc.php', 'Вернуться', 1);
	fin();
	}
elseif ($go==1){




$q = $db->query("update `users` set priv={$f['loc']} where id='{$f['id']}' limit 1;");

$str = '
Успешно!
';
$str .= '
Теперь после смерти вы будете воскрешены в локации '.$f['loc'].'.

';

msg2($str);
knopka('loc.php', 'Вернуться', 1);
fin();
}
break;

case 'magazin':
if (empty($go)){
msg2('Клановый магазинчик.<br>
Здесь вы можете преобрести всякую всячину.');
knopka2('zamok.php?mod=magazin&go=1', 'Пустая бутылка - 10 медных монет');
knopka2('zamok.php?mod=magazin&go=2', 'Грамота телепортации в замок - 2 серебренные монеты');
if($items->count_base_item($f['login'], 753) == 0) knopka2('zamok.php?mod=magazin&go=3', 'Топор лесоруба - 3 серебряные монеты');
knopka2('zamok.php?mod=magazin&go=4', 'Грамота телепортации - 15 серебряных монет');
if($items->count_base_item($f['login'], 754) == 0) knopka2('zamok.php?mod=magazin&go=5', 'Серп травника - 5 серебряных монет');
if($items->count_base_item($f['login'], 781) == 0) knopka2('zamok.php?mod=magazin&go=6', 'Кузнечный молот - 5 серебряных монет');
if($f['p_fishman'] >1 and $items->count_base_item($f['login'], 755) == 0) knopka2('zamok.php?mod=magazin&go=7', 'удочка рыболова - 4 серебряные монеты');
if($items->count_base_item($f['login'], 759) == 0) knopka2('zamok.php?mod=magazin&go=8', 'Кирка рудокопа - 5 серебряных монет');
if($items->count_base_item($f['login'], 834) == 0) knopka2('zamok.php?mod=magazin&go=10', 'Ювелирный набор - 6 серебряных монет');
if($items->count_base_item($f['login'], 757) == 0) knopka2('zamok.php?mod=magazin&go=11', 'Разделочный нож - 4 серебряные монеты');
knopka2('zamok.php', 'Вернуться');
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

break;



case 'plash':
msg2('Магазин одежды');
if(empty($go)){
msg2('Здесь вы можете купить клановые плащи. (Цены указаны в серебряных монетах).<br>
<a href="zamok.php?mod=plash&go=1">Клановый плащ 6 lvl</a> - <a href="shop.php?mod=iteminfa&iid=645">инф.</a> - 1 монет<br>
<a href="zamok.php?mod=plash&go=2">Клановый плащ 7 lvl</a> - <a href="shop.php?mod=iteminfa&iid=646">инф.</a> - 2 монет<br>
<a href="zamok.php?mod=plash&go=3">Клановый плащ 8 lvl</a> - <a href="shop.php?mod=iteminfa&iid=647">инф.</a> - 3 монет<br>
<a href="zamok.php?mod=plash&go=4">Клановый плащ 9 lvl</a> - <a href="shop.php?mod=iteminfa&iid=648">инф.</a> - 4 монет<br>
<a href="zamok.php?mod=plash&go=5">Клановый плащ 10 lvl</a> - <a href="shop.php?mod=iteminfa&iid=649">инф.</a> - 5 монет<br>
<a href="zamok.php?mod=plash&go=6">Клановый плащ 11 lvl</a> - <a href="shop.php?mod=iteminfa&iid=650">инф.</a> - 7 монет<br>
<a href="zamok.php?mod=plash&go=7">Клановый плащ 12 lvl</a> - <a href="shop.php?mod=iteminfa&iid=651">инф.</a> - 9 монет<br>
<a href="zamok.php?mod=plash&go=8">Клановый плащ 13 lvl</a> - <a href="shop.php?mod=iteminfa&iid=652">инф.</a> - 11 монет<br>
<a href="zamok.php?mod=plash&go=9">Клановый плащ 14 lvl</a> - <a href="shop.php?mod=iteminfa&iid=653">инф.</a> - 13 монет<br>
<a href="zamok.php?mod=plash&go=10">Клановый плащ 15 lvl</a> - <a href="shop.php?mod=iteminfa&iid=654">инф.</a> - 15 монет<br>
<a href="zamok.php?mod=plash&go=11">Клановый плащ 16 lvl</a> - <a href="shop.php?mod=iteminfa&iid=655">инф.</a> - 18 монет<br>
<a href="zamok.php?mod=plash&go=12">Клановый плащ 17 lvl</a> - <a href="shop.php?mod=iteminfa&iid=656">инф.</a> - 21 монет<br>
<a href="zamok.php?mod=plash&go=13">Клановый плащ 18 lvl</a> - <a href="shop.php?mod=iteminfa&iid=657">инф.</a> - 24 монет<br>
<a href="zamok.php?mod=plash&go=14">Клановый плащ 19 lvl</a> - <a href="shop.php?mod=iteminfa&iid=658">инф.</a> - 27 монет<br>
<a href="zamok.php?mod=plash&go=15">Клановый плащ 20 lvl</a> - <a href="shop.php?mod=iteminfa&iid=659">инф.</a> - 30 монет<br>
<a href="zamok.php?mod=plash&go=16">Клановый плащ 21 lvl</a> - <a href="shop.php?mod=iteminfa&iid=660">инф.</a> - 34 монет<br>
<a href="zamok.php?mod=plash&go=17">Клановый плащ 22 lvl</a> - <a href="shop.php?mod=iteminfa&iid=661">инф.</a> - 38 монет<br>
<a href="zamok.php?mod=plash&go=18">Клановый плащ 23 lvl</a> - <a href="shop.php?mod=iteminfa&iid=662">инф.</a> - 42 монет<br>
<a href="zamok.php?mod=plash&go=19">Клановый плащ 24 lvl</a> - <a href="shop.php?mod=iteminfa&iid=663">инф.</a> - 46 монет<br>
<a href="zamok.php?mod=plash&go=20">Клановый плащ 25 lvl</a> - <a href="shop.php?mod=iteminfa&iid=664">инф.</a> - 50 монет',1);

}
elseif($go==1){
$cena = 1;
$id = 645;
if($f['ag'] < $cena) msg2('У вас нет столько денег',1);
$q = $db->query("update `users` set ag=ag-{$cena} where login='{$f['login']}' limit 1;");
$items->add_item($f['login'], $id, 1);
msg2('Вы купили клановый плащ');
knopka2('zamok.php', 'В замок');}

elseif($go==2){
$cena = 2;
$id = 646;
if($f['ag'] < $cena) msg2('У вас нет столько денег',1);
$q = $db->query("update `users` set ag=ag-{$cena} where login='{$f['login']}' limit 1;");
$items->add_item($f['login'], $id, 1);
msg2('Вы купили клановый плащ');
knopka2('zamok.php', 'В замок');}

elseif($go==3){
$cena = 3;
$id = 647;
if($f['ag'] < $cena) msg2('У вас нет столько денег',1);
$q = $db->query("update `users` set ag=ag-{$cena} where login='{$f['login']}' limit 1;");
$items->add_item($f['login'], $id, 1);
msg2('Вы купили клановый плащ');
knopka2('zamok.php', 'В замок');}

elseif($go==4){
$cena = 4;
$id = 648;
if($f['ag'] < $cena) msg2('У вас нет столько денег',1);
$q = $db->query("update `users` set ag=ag-{$cena} where login='{$f['login']}' limit 1;");
$items->add_item($f['login'], $id, 1);
msg2('Вы купили клановый плащ');
knopka2('zamok.php', 'В замок');}
elseif($go==5){
$cena = 5;
$id = 649;
if($f['ag'] < $cena) msg2('У вас нет столько денег',1);
$q = $db->query("update `users` set ag=ag-{$cena} where login='{$f['login']}' limit 1;");
$items->add_item($f['login'], $id, 1);
msg2('Вы купили клановый плащ');
knopka2('zamok.php', 'В замок');}

elseif($go==6){
$cena = 7;
$id = 650;
if($f['ag'] < $cena) msg2('У вас нет столько денег',1);
$q = $db->query("update `users` set ag=ag-{$cena} where login='{$f['login']}' limit 1;");
$items->add_item($f['login'], $id, 1);
msg2('Вы купили клановый плащ');
knopka2('zamok.php', 'В замок');}

elseif($go==7){
$cena = 9;
$id = 651;
if($f['ag'] < $cena) msg2('У вас нет столько денег',1);
$q = $db->query("update `users` set ag=ag-{$cena} where login='{$f['login']}' limit 1;");
$items->add_item($f['login'], $id, 1);
msg2('Вы купили клановый плащ');
knopka2('zamok.php', 'В замок');}

elseif($go==8){
$cena = 11;
$id = 652;
if($f['ag'] < $cena) msg2('У вас нет столько денег',1);
$q = $db->query("update `users` set ag=ag-{$cena} where login='{$f['login']}' limit 1;");
$items->add_item($f['login'], $id, 1);
msg2('Вы купили клановый плащ');
knopka2('zamok.php', 'В замок');}

elseif($go==9){
$cena = 13;
$id = 653;
if($f['ag'] < $cena) msg2('У вас нет столько денег',1);
$q = $db->query("update `users` set ag=ag-{$cena} where login='{$f['login']}' limit 1;");
$items->add_item($f['login'], $id, 1);
msg2('Вы купили клановый плащ');
knopka2('zamok.php', 'В замок');}

elseif($go==10){
$cena = 15;
$id = 654;
if($f['ag'] < $cena) msg2('У вас нет столько денег',1);
$q = $db->query("update `users` set ag=ag-{$cena} where login='{$f['login']}' limit 1;");
$items->add_item($f['login'], $id, 1);
msg2('Вы купили клановый плащ');
knopka2('zamok.php', 'В замок');}

elseif($go==11){
$cena = 18;
$id = 655;
if($f['ag'] < $cena) msg2('У вас нет столько денег',1);
$q = $db->query("update `users` set ag=ag-{$cena} where login='{$f['login']}' limit 1;");
$items->add_item($f['login'], $id, 1);
msg2('Вы купили клановый плащ');
knopka2('zamok.php', 'В замок');}

elseif($go==12){
$cena = 21;
$id = 656;
if($f['ag'] < $cena) msg2('У вас нет столько денег',1);
$q = $db->query("update `users` set ag=ag-{$cena} where login='{$f['login']}' limit 1;");
$items->add_item($f['login'], $id, 1);
msg2('Вы купили клановый плащ');
knopka2('zamok.php', 'В замок');}

elseif($go==13){
$cena = 24;
$id = 657;
if($f['ag'] < $cena) msg2('У вас нет столько денег',1);
$q = $db->query("update `users` set ag=ag-{$cena} where login='{$f['login']}' limit 1;");
$items->add_item($f['login'], $id, 1);
msg2('Вы купили клановый плащ');
knopka2('zamok.php', 'В замок');}

elseif($go==14){
$cena = 27;
$id = 658;
if($f['ag'] < $cena) msg2('У вас нет столько денег',1);
$q = $db->query("update `users` set ag=ag-{$cena} where login='{$f['login']}' limit 1;");
$items->add_item($f['login'], $id, 1);
msg2('Вы купили клановый плащ');
knopka2('zamok.php', 'В замок');}


elseif($go==15){
$cena = 30;
$id = 659;
if($f['ag'] < $cena) msg2('У вас нет столько денег',1);
$q = $db->query("update `users` set ag=ag-{$cena} where login='{$f['login']}' limit 1;");
$items->add_item($f['login'], $id, 1);
msg2('Вы купили клановый плащ');
knopka2('zamok.php', 'В замок');}


elseif($go==16){
$cena = 34;
$id = 660;
if($f['ag'] < $cena) msg2('У вас нет столько денег',1);
$q = $db->query("update `users` set ag=ag-{$cena} where login='{$f['login']}' limit 1;");
$items->add_item($f['login'], $id, 1);
msg2('Вы купили клановый плащ');
knopka2('zamok.php', 'В замок');}

elseif($go==17){
$cena = 38;
$id = 661;
if($f['ag'] < $cena) msg2('У вас нет столько денег',1);
$q = $db->query("update `users` set ag=ag-{$cena} where login='{$f['login']}' limit 1;");
$items->add_item($f['login'], $id, 1);
msg2('Вы купили клановый плащ');
knopka2('zamok.php', 'В замок');}


elseif($go==18){
$cena = 42;
$id = 662;
if($f['ag'] < $cena) msg2('У вас нет столько денег',1);
$q = $db->query("update `users` set ag=ag-{$cena} where login='{$f['login']}' limit 1;");
$items->add_item($f['login'], $id, 1);
msg2('Вы купили клановый плащ');
knopka2('zamok.php', 'В замок');}

elseif($go==19){
$cena = 46;
$id = 663;
if($f['ag'] < $cena) msg2('У вас нет столько денег',1);
$q = $db->query("update `users` set ag=ag-{$cena} where login='{$f['login']}' limit 1;");
$items->add_item($f['login'], $id, 1);
msg2('Вы купили клановый плащ');
knopka2('zamok.php', 'В замок');}



elseif($go==20){
$cena = 50;
$id = 664;
if($f['ag'] < $cena) msg2('У вас нет столько денег',1);
$q = $db->query("update `users` set ag=ag-{$cena} where login='{$f['login']}' limit 1;");
$items->add_item($f['login'], $id, 1);
msg2('Вы купили клановый плащ');
knopka2('zamok.php', 'В замок');}
fin();
break;




endswitch;
knopka('zamok.php', 'Вернуться', 1);
fin();
?>
