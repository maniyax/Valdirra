<?php
##############
# 24.12.2014 #
##############

switch($item['ido']):
case 123:
	if(empty($ok))
		{
		msg2('Вы хотите разыграть Лотерейный билет.');
		knopka('inv.php?mod=useitem&iid='.$iid.'&ok=1', 'Продолжить', 1);
		knopka('inv.php', 'Вернуться', 1);
		fin();
		}
	$min = 1;
	$max = 100;
	$rnd = mt_rand($min, $max);
	if(1 <= $rnd && $rnd <= 50)
		{
		$summ = 50;
		$f['ag'] += $summ;
		$q = $db->query("update `users` set ag='{$f['ag']}' where id='{$f['id']}' limit 1;");
		$items->del_item($f['login'], $item['id'], 1);
		msg2('Вы выиграли в лотерею '.$summ.' серебряных монет.',1);
		}
	elseif(51 <= $rnd && $rnd <= 60)
		{
		$summ = 3;
		$f['au'] += $summ;
		$q = $db->query("update `users` set au={$f['au']} where id={$f['id']} limit 1;");
		$items->del_item($f['login'], $item['id'], 1);
		msg2('Вы выиграли в лотерею '.$summ.' золотых монет.',1);
		}
	elseif(61 <= $rnd && $rnd <= 70)
		{
		$items->add_item($f['login'], $item['ido'], 1);
		msg2('Вы выиграли в лотерею еще один лотерейный билет. Старый билет остался при вас.',1);
		}
	elseif(71 <= $rnd && $rnd <= 75)
		{
		$items->del_item($f['login'], $item['id'], 1);
		$items->add_item($f['login'], 127, 1);
		msg2('Вы выиграли в лотерею Точильный камень.',1);
		}
	elseif(76 <= $rnd && $rnd <= 85)
		{
		$items->del_item($f['login'], $item['id'], 1);
		$items->add_item($f['login'], 124, 1);
		msg2('Вы выиграли в лотерею Свиток опыта 1 ступени.',1);
		}
	elseif(85 <= $rnd && $rnd <= 91)
		{
		$items->del_item($f['login'], $item['id'], 1);
		$items->add_item($f['login'], 125, 1);
		msg2('Вы выиграли в лотерею Свиток опыта 2 ступени.',1);
		}
	elseif(92 <= $rnd && $rnd <= 95)
		{
		$items->del_item($f['login'], $item['id'], 1);
		$items->add_item($f['login'], 126, 1);
		msg2('Вы выиграли в лотерею Свиток опыта 3 ступени.',1);
		}
	elseif(96 <= $rnd && $rnd <= 100)
		{
		$items->del_item($f['login'], $item['id'], 1);
		$int = $f['lvl'] + 127;
		$ido = $items->add_item($f['login'], $int, 1);
		if(date('d.m') == '30.12' or date('d.m') == '31.12') $q = $db->query("update invent set up=25 where id='{$ido}' limit 1;");
		$q = $db->query("update invent set name='Именной браслет {$f['login']}', info='Выдано персонажу {$f['login']} за выигрыш в лотерею' where id='{$ido}' limit 1;");
		msg2('Вы выиграли лотерейный браслет на '.$f['lvl'].' уровень.',1);
		}
	else
		{
		msg2('Произошла какая-то ошибка.',1);
		}
break;

case 124:
	$exxp = 7500 * $f['lvl'];
	if(empty($ok))
		{
		msg2('Вы хотите получить '.$exxp.' опыта.');
		knopka('inv.php?mod=useitem&iid='.$iid.'&ok=1', 'Продолжить', 1);
		knopka('inv.php', 'Вернуться', 1);
		fin();
		}
	addexp($f['id'],$exxp);
	$items->del_item($f['login'], $item['id'], 1);
	msg2('Вы получили '.$exxp.' опыта.',1);
break;

case 125:
	$exxp = 10000 * $f['lvl'];
	if(empty($ok))
		{
		msg2('Вы хотите получить '.$exxp.' опыта.');
		knopka('inv.php?mod=useitem&iid='.$iid.'&ok=1', 'Продолжить', 1);
		knopka('inv.php', 'Вернуться', 1);
		fin();
		}
	addexp($f['id'],$exxp);
	$items->del_item($f['login'], $item['id'], 1);
	msg2('Вы получили '.$exxp.' опыта.',1);
break;

case 126:
	$exxp = 12500 * $f['lvl'];
	if(empty($ok))
		{
		msg2('Вы хотите получить '.$exxp.' опыта.');
		knopka('inv.php?mod=useitem&iid='.$iid.'&ok=1', 'Продолжить', 1);
		knopka('inv.php', 'Вернуться', 1);
		fin();
		}
	addexp($f['id'],$exxp);
	$items->del_item($f['login'], $item['id'], 1);
	msg2('Вы получили '.$exxp.' опыта.',1);
break;

case 127:
	if(empty($look)) // если еще не выбрали вещь для снятия апгрейда
		{
		$q = $db->query("select id from `invent` where up<>0 and flag_rinok=0 and flag_arenda=0 and flag_equip=0 and flag_sklad=0 and login='{$f['login']}';"); // все вещи с апгрейдом
		if($q->num_rows == 0) msg('У вас нет вещей, пригодных для снятия модификации', 1);
		msg('Выберите нужную вещь:');
		while($a = $q->fetch_assoc())
			{
			$item = $items->shmot($a['id']);
			knopka('inv.php?mod=useitem&iid='.$iid.'&look='.$item['id'], $item['name']);
			}
		fin();
		}
	if($look <= 0) msg('Вещь не найдена в вашем рюкзаке!', 1);
	$q = $db->query("select id from `invent` where id='{$look}' and flag_rinok=0 and flag_arenda=0 and flag_equip=0 and flag_sklad=0 limit 1;");
	if($q->num_rows == 0) msg('Вещь не найдена в вашем рюкзаке!', 1);
	$a = $q->fetch_assoc();
	$item = $items->shmot($a['id']);
	if(empty($ok))
		{
		msg('Вы уверены, что хотите снять модификацию с '.$item['name'].'?');
		knopka('inv.php?mod=useitem&iid='.$iid.'&look='.$look.'&ok=1', 'Снять модификацию');
		knopka('inv.php', 'Инвентарь');
		fin();
		}
$item['info'] .= '<br>Модефикация снята игроком '.$f['login'].'';
	$q = $db->query("update `invent` set up=0,info='{$item['info']}' where id='{$look}' and flag_rinok=0 and flag_arenda=0 and flag_equip=0 and flag_sklad=0 limit 1;");
	$items->del_item($f['login'], $iid, 1);
	msg2('Модификация с '.$item['name'].' успешно снята.', 1);
break;

case 775:
        $rnd = mt_rand(1, 100);
        if($rnd >= 1 and $rnd <= 33)
		{
		$items->del_item($f['login'], $item['id'], 1);
		$ido = $items->add_item($f['login'], 776);
		if(date('d.m') == '30.12' or date('d.m') == '31.12') $q = $db->query("update invent set up=25 where id='{$ido}' limit 1;");
		$q = $db->query("update `invent` set name='Новогодняя серьга {$f['login']} [{$f['lvl']}] (вампиризм)', info='На серьге виднеется изящная гравировка: С новым 2017 годом!',art='Вампиризм',lvl={$f['lvl']},slot='serga',hp=lvl*20,uvorot=lvl*10 where id='{$ido}' limit 1;");
		msg2('Вы вытащили именную вещь, которая у вас в рюкзаке!',1);
                }
	elseif($rnd >= 34 and $rnd <= 66)
		{
		$items->del_item($f['login'], $item['id'], 1);
		$ido = $items->add_item($f['login'], 777);
		if(date('d.m') == '30.12' or date('d.m') == '31.12') $q = $db->query("update invent set up=25 where id='{$ido}' limit 1;");
		$q = $db->query("update `invent` set name='Именные перчатки {$f['login']} [{$f['lvl']}] (исцеление)', info='Отличные перчатки с посланием: С новым 2017 годом!',art='исцеление',lvl={$f['lvl']},slot='perchi',hp=lvl*12,bron=lvl*1,uvorot=lvl*6,krit=lvl*6 where id='{$ido}' limit 1;");
		msg2('Вы вытащили именную вещь, которая у вас в рюкзаке!',1);
                }
	elseif($rnd >= 67 and $rnd <= 100)
		{
		$items->del_item($f['login'], $item['id'], 1);
		$ido = $items->add_item($f['login'], 778);
		if(date('d.m') == '30.12' or date('d.m') == '31.12') $q = $db->query("update invent set up=25 where id='{$ido}' limit 1;");
		$q = $db->query("update `invent` set name='Новогодние штаны {$f['login']} [{$f['lvl']}]', info='Красные штаны с яркими надписями: С новым 2017 годом!',lvl={$f['lvl']},slot='stup',hp=lvl*10,uvorot=lvl*20,krit=lvl*20 where id='{$ido}' limit 1;");
		msg2('Вы вытащили именную вещь, которая у вас в рюкзаке!',1);
                }

break;

endswitch;
?>
