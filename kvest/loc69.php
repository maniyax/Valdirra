<?php
if ($f['hpnow'] <= 0)
	{
	knopka('loc.php', 'Восстановите здоровье', 1);
	fin();
	}
if ($f['lvl'] < 5)
	{
	knopka('loc.php', 'Доступно с 5 уровня', 1);
	fin();
	}

if(!empty($f['kvest_now']) and $f['kvest_now'] != 1)
	{
	knopka('loc.php', 'Вы выполняете другое задание', 1);
	fin();
	}
$kvest = unserialize($f['kvest']);
if (empty($kvest['loc69']))
	{
	$kvest['loc69']['date'] = 0;
	$f['kvest'] = serialize($kvest);
	$q = $db->query("update `users` set kvest='{$f['kvest']}' where id='{$f['id']}' limit 1;");
	}
$time = $kvest['loc69']['date'] - $_SERVER['REQUEST_TIME'];
if ($time > 0) msg2('Здесь никого нет.', 1);

if(empty($f['kvest_step']))
	{
	if(empty($go))
		{
		msg('Неподалёку проходит широкая тропа, по которой несколько здоровых мужиков ведут навьюченных животных. Вы решаете:');
		knopka('kvest.php?go=1', 'Подойти поближе', 1);
		knopka('loc.php', 'Вернуться', 1);
		fin();
		}
	msg('Вы осторожно подкрадываетесь поближе, чтобы вас не заметили.');
	$q = $db->query("update `users` set kvest_now=1,kvest_step=1 where id='{$f['id']}' limit 1;");
	knopka('kvest.php', 'Далее', 1);
	fin();
	}
elseif($f['kvest_step'] == 1)
	{
	if(empty($go))
		{
		msg('Всего четверо охранников. Самый здоровый охранник идет в начале корована. Второй, в красном плаще, идет в середине. Двое в черном замыкают шествие.');
		knopka('kvest.php?go=1', 'Напасть на самого здорового охранника', 1);
		knopka('kvest.php?go=2', 'Напасть на охранника в красном плаще', 1);
		knopka('kvest.php?go=3', 'Напасть на двоих охранников в черном', 1);
		knopka('kvest.php?go=4', 'Напасть на всех', 1);
		knopka('loc.php', 'Вернуться', 1);
		fin();
		}
	elseif($go == 1)
		{
		msg('Вы напали на самого здорового охранника, придется постараться, дабы не прогирать ему.');
		$boi_id = addBoi(0);
		addBot('Сильный охранник корована', $f['lvl'] + 1);
		toBoi($f,2);
		knopka('battle.php', 'В бой', 1);
		$q = $db->query("update `users` set kvest_step=2 where id='{$f['id']}' limit 1;");
		fin();
		}
	elseif($go == 2)
		{
		msg('Вы напали на охранника в красном плаще.');
		$boi_id = addBoi(0);
		addBot('Охранник в красном',1);
		toBoi($f,2);
		knopka('battle.php', 'В бой', 1);
		$q = $db->query("update `users` set kvest_step=3 where id='{$f['id']}' limit 1;");
		fin();
		}
	elseif($go == 3)
		{
		msg('Вы напали на двух охранников в черном.');
		$boi_id = addBoi(0);
		addBot('Охранник в черном',1);
		addBot('Охранник в черном',1);
		toBoi($f,2);
		knopka('battle.php', 'В бой', 1);
		$q = $db->query("update `users` set kvest_step=4 where id='{$f['id']}' limit 1;");
		fin();
		}
	elseif($go == 4)
		{

		msg('Вы напали на всех охранников сразу. Это будет трудный бой.');
		$boi_id = addBoi(0);
		addBot('Сильный охранник корована', $f['lvl'] + 1);
		addBot('Охранник в красном',1);
		addBot('Охранник в черном',1);
		addBot('Охранник в черном',1);
		toBoi($f,2);
		knopka('battle.php', 'В бой', 1);
		$q = $db->query("update `users` set kvest_step=8 where id='{$f['id']}' limit 1;");
		fin();
		}
	else msg2('Ошибка перехода: '.$f['kvest_step'].' -> '.$go, 1);
	}
elseif($f['kvest_step'] == 2)
	{
	if(empty($go))
		{
		msg('Осталось трое. Охранник в красном плаще, который идет в середине корована и двое в черном, которые замыкают шествие.');
		knopka('kvest.php?go=1', 'Напасть на охранника в красном плаще', 1);
		knopka('kvest.php?go=2', 'Напасть на двоих охранников в черном', 1);
		knopka('kvest.php?go=3', 'Напасть на всех', 1);
		knopka('loc.php', 'Вернуться', 1);
		fin();
		}
	elseif($go == 1)
		{
		msg('Вы напали на охранника в красном плаще.');
		$boi_id = addBoi(0);
		addBot('Охранник в красном',1);
		toBoi($f,2);
		knopka('battle.php', 'В бой', 1);
		$q = $db->query("update `users` set kvest_step=5 where id='{$f['id']}' limit 1;");
		fin();
		}
	elseif($go == 2)
		{
		msg('Вы напали на двух охранников в черном.');
		$boi_id = addBoi(0);
		addBot('Охранник в черном',1);
		addBot('Охранник в черном',1);
		toBoi($f,2);
		knopka('battle.php', 'В бой', 1);
		$q = $db->query("update `users` set kvest_step=6 where id='{$f['id']}' limit 1;");
		fin();
		}
	elseif($go == 3)
		{

		msg('Вы напали на всех охранников сразу. Это будет нелёгкий бой.');
		$boi_id = addBoi(0);
		addBot('Охранник в красном',1);
		addBot('Охранник в черном',1);
		addBot('Охранник в черном',1);
		toBoi($f,2);
		knopka('battle.php', 'В бой', 1);
		$q = $db->query("update `users` set kvest_step=8 where id='{$f['id']}' limit 1;");
		fin();
		}
	else msg2('Ошибка перехода: '.$f['kvest_step'].' -> '.$go, 1);
	}
elseif($f['kvest_step'] == 3)
	{
	if(empty($go))
		{
		msg('Осталось трое. Самый здоровый охранник идет в начале корована и двое в черном, которые замыкают шествие.');
		knopka('kvest.php?go=1', 'Напасть на самого здорового охранника', 1);
		knopka('kvest.php?go=2', 'Напасть на двоих охранников в черном', 1);
		knopka('kvest.php?go=3', 'Напасть на всех', 1);
		knopka('loc.php', 'Вернуться', 1);
		fin();
		}
	elseif($go == 1)
		{
		msg('Вы напали на самого здорового охранника, придется постараться, дабы не прогирать ему.');
		$boi_id = addBoi(0);
		addBot('Сильный охранник корована', $f['lvl'] + 1);
		toBoi($f,2);
		knopka('battle.php', 'В бой', 1);
		$q = $db->query("update `users` set kvest_step=5 where id='{$f['id']}' limit 1;");
		fin();
		}
	elseif($go == 2)
		{
		msg('Вы напали на двух охранников в черном.');
		$boi_id = addBoi(0);
		addBot('Охранник в черном',1);
		addBot('Охранник в черном',1);
		toBoi($f,2);
		knopka('battle.php', 'В бой', 1);
		$q = $db->query("update `users` set kvest_step=7 where id='{$f['id']}' limit 1;");
		fin();
		}
	elseif($go == 3)
		{

		msg('Вы напали на всех охранников сразу. Это будет нелёгкий бой.');
		$boi_id = addBoi(0);
		addBot('Сильный охранник корована',1);
		addBot('Охранник в черном',1);
		addBot('Охранник в черном',1);
		toBoi($f,2);
		knopka('battle.php', 'В бой', 1);
		$q = $db->query("update `users` set kvest_step=8 where id='{$f['id']}' limit 1;");
		fin();
		}
	else msg2('Ошибка перехода: '.$f['kvest_step'].' -> '.$go, 1);
	}
elseif($f['kvest_step'] == 4)
	{
	if(empty($go))
		{
		msg('Осталось двое. Самый здоровый охранник идет в начале корована и охранник в красном плаще в середине.');
		knopka('kvest.php?go=1', 'Напасть на самого здорового охранника', 1);
		knopka('kvest.php?go=2', 'Напасть на охранника в красном', 1);
		knopka('kvest.php?go=3', 'Напасть на всех', 1);
		knopka('loc.php', 'Вернуться', 1);
		fin();
		}
	elseif($go == 1)
		{
		msg('Вы напали на самого здорового охранника, придется постараться, дабы не прогирать ему.');
		$boi_id = addBoi(0);
		addBot('Сильный охранник корована', $f['lvl'] + 1);
		toBoi($f,2);
		knopka('battle.php', 'В бой', 1);
		$q = $db->query("update `users` set kvest_step=6 where id='{$f['id']}' limit 1;");
		fin();
		}
	elseif($go == 2)
		{
		msg('Вы напали на охранника в красном плаще.');
		$boi_id = addBoi(0);
		addBot('Охранник в красном',1);
		toBoi($f,2);
		knopka('battle.php', 'В бой', 1);
		$q = $db->query("update `users` set kvest_step=7 where id='{$f['id']}' limit 1;");
		fin();
		}
	elseif($go == 3)
		{

		msg('Вы напали на всех охранников сразу. Это будет нелёгкий бой.');
		$boi_id = addBoi(0);
		addBot('Сильный охранник корована',1+1);
		addBot('Охранник в красном',1);
		toBoi($f,2);
		knopka('battle.php', 'В бой', 1);
		$q = $db->query("update `users` set kvest_step=8 where id='{$f['id']}' limit 1;");
		fin();
		}
	else msg2('Ошибка перехода: '.$f['kvest_step'].' -> '.$go, 1);
	}
elseif($f['kvest_step'] == 5)
	{
	if(empty($go))
		{
		msg('Осталось двое охранников в черном, которые замыкают шествие.');
		knopka('kvest.php?go=1', 'Напасть на них', 1);
		knopka('loc.php', 'Вернуться', 1);
		fin();
		}
	elseif($go == 1)
		{
		msg('Вы напали на двух охранников в черном.');
		$boi_id = addBoi(0);
		addBot('Охранник в черном',1);
		addBot('Охранник в черном',1);
		toBoi($f,2);
		knopka('battle.php', 'В бой', 1);
		$q = $db->query("update `users` set kvest_step=8 where id='{$f['id']}' limit 1;");
		fin();
		}
	else msg2('Ошибка перехода: '.$f['kvest_step'].' -> '.$go, 1);
	}
elseif($f['kvest_step'] == 6)
	{
	if(empty($go))
		{
		msg('Остался один охранник в красном плаще.');
		knopka('kvest.php?go=1', 'Напасть на него', 1);
		knopka('loc.php', 'Вернуться', 1);
		fin();
		}
	elseif($go == 1)
		{
		msg('Вы напали на охранника в красном плаще.');
		$boi_id = addBoi(0);
		addBot('Охранник в красном',1);
		toBoi($f,2);
		knopka('battle.php', 'В бой', 1);
		$q = $db->query("update `users` set kvest_step=8 where id='{$f['id']}' limit 1;");
		fin();
		}
	else msg2('Ошибка перехода: '.$f['kvest_step'].' -> '.$go, 1);
	}
elseif($f['kvest_step'] == 7)
	{
	if(empty($go))
		{
		msg('Остался один, самый здоровый охранник.');
		knopka('kvest.php?go=1', 'Напасть на него', 1);
		knopka('loc.php', 'Вернуться', 1);
		fin();
		}
	elseif($go == 1)
		{
		msg('Вы напали на самого здорового охранника, придется постараться, дабы не прогирать ему.');
		$boi_id = addBoi(0);
		addBot('Сильный охранник корована', $f['lvl'] + 1);
		toBoi($f,2);
		knopka('battle.php', 'В бой', 1);
		$q = $db->query("update `users` set kvest_step=8 where id='{$f['id']}' limit 1;");
		fin();
		}
	else msg2('Ошибка перехода: '.$f['kvest_step'].' -> '.$go, 1);
	}
elseif($f['kvest_step'] == 8)
	{
	if(empty($go))
		{
		msg('Вы расправились с охраной, самое время поживиться добычей. В одном тюке что-то ощутимо позвякивает, а недалеко лежит ветхий свиток со странными надписями.');
		knopka('kvest.php?go=1', 'Осмотреть тюки', 1);
		knopka('kvest.php?go=2', 'Взять свиток', 1);
		knopka('loc.php', 'Вернуться', 1);
		fin();
		}
	elseif($go == 1)
		{
		$nagr = rand(1,3) * $f['lvl'];
		$f['ag'] += $nagr;
		$kvest['loc69']['date'] = $_SERVER['REQUEST_TIME'] + 86400;
		$f['kvest'] = serialize($kvest);
		$q = $db->query("update `users` set ag='{$f['ag']}',kvest_now=0,kvest_step=0,kvest='{$f['kvest']}' where id='{$f['id']}' limit 1;");
		msg('Осмотрев тюки, вы находите кошелек, в котором звенит '.$nagr.' серебряных монет.');
		knopka('loc.php', 'В игру', 1);
		fin();
		}
	elseif($go == 2)
		{
		$nagr = rand($f['lvl'] *100 , $f['lvl'] * 300);
		$f['exp'] += $nagr;
		$kvest['loc69']['date'] = $_SERVER['REQUEST_TIME'] + 86400;
		$f['kvest'] = serialize($kvest);
		addexp($f['id'], $nagr);
		$q = $db->query("update `users` set kvest_now=0,kvest_step=0,kvest='{$f['kvest']}' where id='{$f['id']}' limit 1;");
		msg('Прочитав свиток, вы чувствуете себя мудрее. Получено '.$nagr.' опыта.');
		knopka('loc.php', 'В игру', 1);
		fin();
		}
	else msg2('Ошибка перехода: '.$f['kvest_step'].' -> '.$go, 1);
	}
else msg2('Ошибка перехода: '.$f['kvest_step'].' -> '.$go, 1);
?>