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
$q = $db->query("select * from `doms` where loc={$f['loc']} or point={$f['loc']} limit 1;");
if($q->num_rows == 0)
	{
	knopka('loc.php', 'Дома здесь нет', 1);
	fin();
	}
$dom = $q->fetch_assoc();
// объявим переменные чтоб не зависеть от register globals
$mod = isset($_REQUEST['mod']) ? $_REQUEST['mod'] : '';
$go = isset($_REQUEST['go']) ? $_REQUEST['go'] : 0;
$start = isset($_REQUEST['start']) ? intval($_REQUEST['start']) : 0;
$iid = isset($_REQUEST['iid']) ? intval($_REQUEST['iid']) : 0;
$lgn = isset($_REQUEST['lgn']) ? intval($_REQUEST['lgn']) : 0;

if(!empty($dom['point']) and empty($['loc']))
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
if(!empty($f['klan']) and $f['klan'] != $klan['name']) knopka('zamok.php?mod=boy', 'Атаковать',1);
if(!empty($f['klan']) and $f['klan'] == $klan['name']) knopka('zamok.php?mod=boy', 'Защищать',1);
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
		msg('Сухонький старичок поведал вам, что изготовить различные напитки в пивоварне может только истинный воин клана. Доказать свою преданность можно зарабатывая в ПвП боях очки чести!');
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
		if($f['chest'] == 0) msg2('У вас нет очков чести!',1);

		$items->del_base_item($f['login'], $need_id,1);
$items->del_base_item($f['login'], 750, 1);
		$items->add_item($f['login'], $res, 1);
		$q = $db->query("update `users` set chest=chest-1 where id='{$f['id']}' limit 1;");
		msg2('Вы приготовили брагу. Очки чести -1');
		}
	elseif($go == 2)
		{
		if($klan['pivo'] < 2) msg('Необходима пивоварня минимум 2 уровня!',1);
		$need_id = 641;
		$res = 636;
		$need = $items->count_base_item($f['login'], $need_id);
		if($need == 0) msg2('У вас нет солода!',1);
if ($items->count_base_item($f['login'], 750) == 0) msg2('У тебя нет бутылки с водой',1);
		if($f['chest'] == 0) msg2('У вас нет очков чести!',1);
		$items->del_base_item($f['login'], $need_id,1);
		$items->del_base_item($f['login'], 750, 1);
		$items->add_item($f['login'], $res, 1);
		$q = $db->query("update `users` set chest=chest-1 where id='{$f['id']}' limit 1;");
		msg2('Вы приготовили пиво. Очки чести -1');
		}
	elseif($go == 3)
		{
		if($klan['pivo'] < 3) msg('Необходима пивоварня минимум 3 уровня!',1);
		$need_id = 642;
		$res = 637;
		$need = $items->count_base_item($f['login'], $need_id);
		if($need == 0) msg2('У вас нет винограда!',1);
		if ($items->count_base_item($f['login'], 750) == 0) msg2('У тебя нет бутылки с водой',1);
		if($f['chest'] == 0) msg2('У вас нет очков чести!',1);
		$items->del_base_item($f['login'], $need_id,1);
		$items->del_base_item($f['login'], 750, 1);
		$items->add_item($f['login'], $res, 1);
		$q = $db->query("update `users` set chest=chest-1 where id='{$f['id']}' limit 1;");
		msg2('Вы приготовили вино. Очки чести -1');
		}
	elseif($go == 4)
		{
		if($klan['pivo'] < 4) msg('Необходима пивоварня минимум 4 уровня!',1);
		$need_id = 643;
		$res = 638;
		$need = $items->count_base_item($f['login'], $need_id);
		if($need == 0) msg2('У вас нет мёда!',1);
		if ($items->count_base_item($f['login'], 750) == 0) msg2('У тебя нет бутылки с водой',1);
		if($f['chest'] == 0) msg2('У вас нет очков чести!',1);
		$items->del_base_item($f['login'], $need_id,1);
		$items->del_base_item($f['login'], 750, 1);
		$items->add_item($f['login'], $res, 1);
		$q = $db->query("update `users` set chest=chest-1 where id='{$f['id']}' limit 1;");
		msg2('Вы приготовили самогон. Очки чести -1');
		}
	elseif($go == 5)
		{
		if($klan['pivo'] < 5) msg('Необходима пивоварня минимум 5 уровня!',1);
		$need_id = 644;
		$res = 639;
		$need = $items->count_base_item($f['login'], $need_id);
		if($need == 0) msg2('У вас нет риса!',1);
		if ($items->count_base_item($f['login'], 750) == 0) msg2('У тебя нет бутылки с водой',1);
		if($f['chest'] == 0) msg2('У вас нет очков чести!',1);
		$items->del_base_item($f['login'], $need_id,1);
		$items->del_base_item($f['login'], 750, 1);
		$items->add_item($f['login'], $res, 1);
		$q = $db->query("update `users` set chest=chest-1 where id='{$f['id']}' limit 1;");
		msg2('Вы приготовили рисовый шнапс. Очки чести -1');
		}
	else
		{
		$m = $f['lvl'] * 1000;
		$q = $db->query("update `users` set money=money-'{$m}' where id={$f['id']} limit 1;");
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

		msg2('Вы приготовили зелье.');
		}
	elseif($go == 2)
		{
		if($klan['laba'] < 2) msg('Необходима лаборатория минимум 2 уровня!',1);

		$res = 626;
if ($items->count_base_item($f['login'], 750) == 0) msg2('У тебя нет бутылки с водой',1);
if ($items->count_base_item($f['login'], 751) < 3) msg2('У тебя нет лечебной травы в количестве 3 шт.',1);
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

		msg2('Вы приготовили зелье.');
		}
	elseif($go == 7)
		{
		if($klan['laba'] < 2) msg('Необходима лаборатория минимум 2 уровня!',1);

		$res = 631;
if ($items->count_base_item($f['login'], 750) == 0) msg2('У тебя нет бутылки с водой',1);
if ($items->count_base_item($f['login'], 752) < 3) msg2('У тебя нет магической травы в количестве 3 шт.',1);
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
$items->del_base_item($f['login'], 752, 5);
$items->del_base_item($f['login'], 750, 1);
		$items->add_item($f['login'], $res, 1);
		$q = $db->query("update `users` set mananow=mananow-'{$need_id}',manatime='{$t}' where id='{$f['id']}' limit 1;");
		msg2('Вы приготовили зелье. МП -'.$need_id);
		}
	elseif($go == 10)
		{
		if($klan['laba'] < 5) msg('Необходима лаборатория минимум 5 уровня!',1);

		$res = 634;
if ($items->count_base_item($f['login'], 750) == 0) msg2('У тебя нет бутылки с водой',1);
if ($items->count_base_item($f['login'], 752) < 6) msg2('У тебя нет лечебной травы в количестве 6 шт.',1);

$items->del_base_item($f['login'], 752, 6);
$items->del_base_item($f['login'], 750, 1);
		$items->add_item($f['login'], $res, 1);

		msg2('Вы приготовили зелье.');
		}
	else
		{
		$m = $f['lvl'] * 1000;
		$q = $db->query("update `users` set money=money-'{$m}' where id={$f['id']} limit 1;");
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
		$q = $db->query("update `users` set money=money-'{$m}' where id={$f['id']} limit 1;");
		msg2('Подменять ссылки нехорошо. С вас списан штраф '.$m.' монет за баловство.',1);
		}
	if ($f['money'] < ($start * 100)) msg2('У вас не хватает денег!', 1);
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
	$f['money'] -= ($start * 100);
	$q = $db->query("update `users` set money={$f['money']} where id={$f['id']} limit 1;");
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
		if(1 <= $klan['oruzh'])
			{
			$idd = $start + 639;
			$item = $items->base_shmot($idd);
			echo '<div class="board2" style="text-align:left">';
			echo '<a href="zamok.php?mod=oruzh&start='.$start.'&iid=1">'.$item['name'].'</a> ('.$item['price'].' монет)';
			echo ' <a href="shop.php?mod=iteminfa&iid='.$idd.'">[infa]</a>';
			echo '</div>';
			}
		if(2 <= $klan['oruzh'])
			{
			$idd = $start + 659;
			$item = $items->base_shmot($idd);
			echo '<div class="board2" style="text-align:left">';
			echo '<a href="zamok.php?mod=oruzh&start='.$start.'&iid=2">'.$item['name'].'</a> ('.$item['price'].' монет)';
			echo ' <a href="shop.php?mod=iteminfa&iid='.$idd.'">[infa]</a>';
			echo '</div>';
			}
		if(3 <= $klan['oruzh'])
			{
			$idd = $start + 679;
			$item = $items->base_shmot($idd);
			echo '<div class="board2" style="text-align:left">';
			echo '<a href="zamok.php?mod=oruzh&start='.$start.'&iid=3">'.$item['name'].'</a> ('.$item['price'].' монет)';
			echo ' <a href="shop.php?mod=iteminfa&iid='.$idd.'">[infa]</a>';
			echo '</div>';
			}
		fin();
		}
	$iid = intval($iid);
	if ($iid == 1) $num = $start + 639;
	elseif($iid == 2) $num = $start + 659;
	elseif($iid == 3) $num = $start + 679;
	else
		{
		$m = $f['lvl'] * 1000;
		$q = $db->query("update `users` set money=money-'{$m}' where id={$f['id']} limit 1;");
		msg2('Подменять ссылки нехорошо. С вас списан штраф '.$m.' монет за баловство.',1);
		}
	$item = $items->base_shmot($num);
	if ($f['money'] < $item['price']) msg2('У вас не хватает денег!', 1);
	if (empty($go))
		{
		msg2('Вы уверены, что хотите изготовить '.$item['name'].'?');
		knopka('zamok.php?mod=oruzh&start='.$start.'&iid='.$iid.'&go=1', 'Изготовить', 1);
		knopka('loc.php', 'В игру', 1);
		fin();
		}
	$items->add_item($f['login'], $num, 1);
	$f['money'] -= $item['price'];
	$q = $db->query("update `klans` set kazna=kazna+{$item['price']} where id={$klan['id']} limit 1;");
	$q = $db->query("update `users` set money={$f['money']} where id={$f['id']} limit 1;");
	$log = $f['login'].' ['.$f['lvl'].'] изготавливает '.$item['name'].' за '.$item['price'].' монет. Казна пополнена.';
	$q = $db->query("insert into `klan_log` values(0,'{$f['login']}','{$log}','{$f['klan']}','{$t}');");
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
	msg2('<form action="zamok.php?mod=log" method="GET">
Логин игрока: <input type="text" name="lgn1" /> - <input type="submit" value="Отсортировать"/></form>');

if(empty($lgn)){

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
$TestLgn = $_GET['lgn1'];
msg2('Действия игрока '.$lgn1.'');
	$numb = 25;			//записей на страницу
	$count = 0;
	$q = $db->query("select count(*) from `klan_log` where klan='{$f['klan']}' and login='{$TestLgn}';");
	$a = $q->fetch_assoc();
	$all_log = $a['count(*)'];
	if($start > intval($all_log / $numb)) $start = intval($all_log / $numb);
	if($start < 0) $start = 0;
	$limit = $start * $numb;
	$count = $limit;
	$q = $db->query("select * from `klan_log` where klan='{$f['klan']}' and login='{$TestLgn}' order by id desc limit {$limit},{$numb};");
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
knopka2('zamok.php?mod=magazin&go=1', 'Пустая бутылка - 10 монет');
knopka2('zamok.php?mod=magazin&go=2', 'Грамота телепортации в замок - 100 монет');
if($items->count_base_item($f['login'], 753) == 0) knopka2('zamok.php?mod=magazin&go=3', 'Топор лесоруба - 300 монет');
knopka2('zamok.php?mod=magazin&go=4', 'Грамота телепортации - 1500 монет');
if($items->count_base_item($f['login'], 754) == 0) knopka2('zamok.php?mod=magazin&go=5', 'Серп травника - 500 монет');
//knopka2('zamok.php?mod=magazin&go=9', 'Корманный почтовый ящик - 20000 монет');
//if($items->count_base_item($f['login'], 757) == 0) knopka2('zamok.php?mod=magazin&go=6', 'Разделочный нож - 200 монет');
if($f['p_fishman'] >1 and $items->count_base_item($f['login'], 755) == 0) knopka2('zamok.php?mod=magazin&go=7', 'удочка рыболова - 400 монет');
if($items->count_base_item($f['login'], 759) == 0) knopka2('zamok.php?mod=magazin&go=8', 'Кирка рудокопа - 500 монет');
knopka2('zamok.php', 'Вернуться');
fin();
}
elseif ($go==1){
if ($f['money'] <10) msg2('У вас недостаточно монет. требуется 10',1);
$q = $db->query("update `users` set money=money-10 where id={$id} limit 1;");
$items->add_item($f['login'], 749, 1);
msg2('Вы купили пустую бутылку за 10 монет.');
}
elseif ($go==2){
if ($f['money'] <100) msg2('У вас недостаточно монет. требуется 100',1);
$q = $db->query("update `users` set money=money-100 where id={$id} limit 1;");
$items->add_item($f['login'], 756, 1);
msg2('Вы купили грамоту телепортации в замок за 100 монет.');
}
elseif ($go==3){
if ($f['money'] <300) msg2('У вас недостаточно монет. требуется 300',1);
if($items->count_base_item($f['login'], 753) > 0) msg2('У вас уже есть топор',1);
$q = $db->query("update `users` set money=money-300,topor=100 where id={$id} limit 1;");
$items->add_item($f['login'], 753, 1);
msg2('Вы купили топор лесоруба за 300 монет.');
}
elseif ($go==4){
if ($f['money'] <1500) msg2('У вас недостаточно монет. требуется 1500',1);
$q = $db->query("update `users` set money=money-1500 where id={$id} limit 1;");
$items->add_item($f['login'], 716, 1);
msg2('Вы купили грамоту телепортации за 1500 монет.');
}
elseif ($go==5){
if ($f['money'] <500) msg2('У вас недостаточно монет. требуется 500',1);
if($items->count_base_item($f['login'], 754) > 0) msg2('У вас уже есть серп',1);
$q = $db->query("update `users` set money=money-500,serp=100 where id={$id} limit 1;");
$items->add_item($f['login'], 754, 1);
msg2('Вы купили серп травника за 500 монет.');
}
elseif ($go==6){
if ($f['money'] <200) msg2('У вас недостаточно монет. требуется 200',1);
if($items->count_base_item($f['login'], 757) > 0) msg2('У вас уже есть нож',1);
$q = $db->query("update `users` set money=money-200,noj=50 where id={$id} limit 1;");
$items->add_item($f['login'], 757, 1);
msg2('Вы купили разделочный нож за 200 монет.');
}
elseif($go==7){
if ($f['money'] <400) msg2('У вас недостаточно монет. требуется 400',1);
if($items->count_base_item($f['login'], 755) > 0) msg2('У вас уже есть удочка',1);
$q = $db->query("update `users` set money=money-400,udochka=400 where id={$id} limit 1;");
$items->add_item($f['login'], 755, 1);
msg2('Вы купили удочку за 400 монет.');
}
elseif($go==8){
if ($f['money'] <500) msg2('У вас недостаточно монет. требуется 500',1);
if($items->count_base_item($f['login'], 759) > 0) msg2('У вас уже есть кирка',1);
$q = $db->query("update `users` set money=money-500,kirka=500 where id={$f['id']} limit 1;");
$items->add_item($f['login'], 759, 1);
msg2('Вы купили кирку за 500 монет.');
}


elseif($go==9){
if ($f['money'] <20000) msg2('У вас недостаточно монет. требуется 20000',1);

$q = $db->query("update `users` set money=money-20000 where id={$id} limit 1;");
$items->add_item($f['login'], 760, 1);
msg2('Вы купили корманный почтовый ящик за 20000 монет.');
}


break;

case 'boy':
if(empty($go)){
if(!empty($f['klan']) and $f['klan'] != $klan['name']){
if(date('H') != 17 or date('i') > 59) msg2('Зайти в бой могут только 50 человек, и только с 17:00 и до 17:59',1);
//if($f['admin'] < 4)msg2('Тут никого нет',1);
	$num = 0;	// количество чел в бою с ботом
	$bot_name = 'Замковая стража';
	$q = $db->query("select boi_id from `combat` where login='{$bot_name}' limit 1;");
	$bz = $q->fetch_assoc();
	$boi_id = $bz['boi_id'];
	if(isset($bz['boi_id']) and $bz['boi_id'] == 0) $q = $db->query("delete from `combat` where login='{$bot_name}' limit 1;");
	if(empty($boi_id))
		{
//		$q = $db->query("update `users` set doping=0,doping_time=0 where id={$f['id']} limit 1;");
		$f = calcparam($f);
		$boi_id = addBoi(3);
		addBot($bot_name,20);

		toBoi($f,2);
$q = $db->query("update `klans` summ='{$f['klan']}' where name='{$klan['name']}' limit 1;");
		}
	else
		{
		$q = $db->query("select count(*) from `users` where boi_id='{$boi_id}';");
		$a = $q->fetch_assoc();
		$num = $a['count(*)'];
		if($num < 20)
			{
//			$q = $db->query("update `users` set doping=0,doping_time=0 where id={$f['id']} limit 1;");
			$f = calcparam($f);
			toBoi($f,2);
			}
		else
			{
			msg2('Привышен лимит',1);
			}
		}
}

if(!empty($f['klan']) and $f['klan'] == $klan['name']){
if(date('H') != 17 or date('i') > 59) msg2('Зайти в бой могут только 50 человек, и только с 17:00 и до 17:59',1);
//if($f['admin'] < 4)msg2('Тут никого нет',1);
	$num = 0;	// количество чел в бою с ботом
	$bot_name = 'Замковая стража';
	$q = $db->query("select boi_id from `combat` where login='{$bot_name}' limit 1;");
	$bz = $q->fetch_assoc();
	$boi_id = $bz['boi_id'];
	if(isset($bz['boi_id']) and $bz['boi_id'] == 0) $q = $db->query("delete from `combat` where login='{$bot_name}' limit 1;");
	if(empty($boi_id))
		{
//		$q = $db->query("update `users` set doping=0,doping_time=0 where id={$f['id']} limit 1;");
		$f = calcparam($f);
		$boi_id = addBoi(3);
		addBot($bot_name,20);

		toBoi($f,1);
		}
	else
		{
		$q = $db->query("select count(*) from `users` where boi_id='{$boi_id}';");
		$a = $q->fetch_assoc();
		$num = $a['count(*)'];
		if($num < 10)
			{
//			$q = $db->query("update `users` set doping=0,doping_time=0 where id={$f['id']} limit 1;");
			$f = calcparam($f);
			toBoi($f,1);
			}
		else
			{
			msg2('Привышен лимит',1);
			}
		}
}
}
elseif($go==1){
if($f['klan'] != $klan['summ'] and $f['klan_status'] != 3) knopka2('loc.php', 'Вы не являетесь главой клана или это не ваш клан напал',1);
}
break;


endswitch;
knopka('zamok.php', 'Вернуться', 1);
fin();
?>
