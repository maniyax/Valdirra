<?php
##############
# 24.12.2014 #
##############

require_once('inc/top.php');		// вывод на экран
require_once('inc/check.php');	// вход в игру
require_once('inc/head.php');
require_once('inc/boi.php');
if($f['status'] == 1)
	{
	knopka('battle.php', 'Вы в бою!',1);
	fin();
	}
if($f['hpnow'] <= 0)
	{
	knopka('loc.php', 'Восстановите здоровье', 1);
	fin();
	}
if($f['loc'] != 169)
	{
	knopka('loc.php', 'Ошибка локации',1);
	fin();
	}
if($f['status'] == 2)
	{
	$q = $db->query("select * from `arena` where id='{$f['arena_id']}' limit 1;");
	$a = $q->fetch_assoc();
	$q1 = $db->query("select count(*) from `users` where arena_id='{$f['arena_id']}' and komanda=1;");
	$c1 = $q1->fetch_assoc();
	$q2 = $db->query("select count(*) from `users` where arena_id='{$f['arena_id']}' and komanda=2;");
	$c2 = $q2->fetch_assoc();
	if(($a['kom1'] <= $c1['count(*)'] and $a['kom2'] <= $c2['count(*)']) or ($c1['count(*)'] > 0 and $c2['count(*)'] > 0 and $a['time'] <= $t))
		{
		$boi_id = addBoi(4);
		$q = $db->query("select * from `users` where arena_id='{$a['id']}';");
		while($b = $q->fetch_assoc()) toBoi($b,$b['komanda']);
		$q = $db->query("delete ftom `arena` where id='{$a['id']}' limit 1;");
		knopka('battle.php', 'Начинаем. В бой.', 1);
		$logboi = '<span style="color:'.$notice.'">'.date("H:i:s").' '.$a['login'].' начинает бой на арене</span><br/>';
		$q = $db->query("insert into `battlelog` values (0,{$boi_id},'{$t}','{$logboi}');");
		$q = $db->query("update `battle` set login='{$a['login']}' where id={$boi_id} limit 1;");
		fin();
		}
	}
$q = $db->query("select id from `arena` where time<'{$t}';");
while($a = $q->fetch_assoc())
	{
	$qq = $db->query("update `users` set status=0,komanda=0,arena_id=0 where arena_id='{$a['id']}';");
	$qq = $db->query("delete from `arena` where id='{$a['id']}' limit 1;");
	}
$mod = isset($_REQUEST['mod']) ? $_REQUEST['mod'] : '';
$aid = isset($_REQUEST['aid']) ? intval($_REQUEST['aid']) : 0;
$kom = isset($_REQUEST['kom']) ? intval($_REQUEST['kom']) : 0;
$comm = isset($_REQUEST['comm']) ? ekr($_REQUEST['comm']) : '';
$minlvl1 = isset($_REQUEST['minlvl1']) ? intval($_REQUEST['minlvl1']) : 1;
$minlvl2 = isset($_REQUEST['minlvl2']) ? intval($_REQUEST['minlvl2']) : 1;
$maxlvl1 = isset($_REQUEST['maxlvl1']) ? intval($_REQUEST['maxlvl1']) : 1;
$maxlvl2 = isset($_REQUEST['maxlvl2']) ? intval($_REQUEST['maxlvl2']) : 1;
$kol1 = isset($_REQUEST['kol1']) ? intval($_REQUEST['kol1']) : 1;
$kol2 = isset($_REQUEST['kol2']) ? intval($_REQUEST['kol2']) : 1;
$timer = isset($_REQUEST['timer']) ? intval($_REQUEST['timer']) : 1;
$go = isset($_REQUEST['go']) ? intval($_REQUEST['go']) : 0;
$ok = isset($_REQUEST['ok']) ? 1 : 0;

switch($mod):
default :
	if($f['status'] == 0) knopka('arena.php?mod=create', 'Подать заявку', 1);
	$q = $db->query("select * from `arena` where time>'{$t}' order by id desc;");
	if($q->num_rows == 0) msg('Нет заявок на арене');
	else
		{
		knopka('arena.php?r='.rand(111111,999999), 'Обновить', 1);
		msg2('Активные заявки:');
		while($a = $q->fetch_assoc())
			{
			echo '<div class="board" style="text-align:left">';
			$qq = $db->query("select login,komanda,lvl from `users` where arena_id='{$a['id']}';");
			$count1 = 0;
			$count2 = 0;
			$str1 = '';
			$str2 = '';
			while($b = $qq->fetch_assoc())
				{
				if($b['komanda'] == 1)
					{
					$count1++;
					$str1 .= $b['login'].' ['.$b['lvl'].'] ';
					}
				else
					{
					$count2++;
					$str2 .= $b['login'].' ['.$b['lvl'].'] ';
					}
				}
			echo 'Команда 1 ';
			if($count1 < $a['kom1'] and $f['status'] == 0) echo '<a href="arena.php?aid='.$a['id'].'&mod=enjoy&kom=1">>>></a> ';
			echo '(ур: '.$a['minlvl1'].'-'.$a['maxlvl1'].'; '.$count1.' из '.$a['kom1'].': '.$str1.')<br/>';
			echo 'Команда 2 ';
			if($count2 < $a['kom2'] and $f['status'] == 0) echo '<a href="arena.php?aid='.$a['id'].'&mod=enjoy&kom=2">>>></a> ';
			echo '(ур: '.$a['minlvl2'].'-'.$a['maxlvl2'].'; '.$count2.' из '.$a['kom2'].': '.$str2.')<br/>';
			echo 'До боя: '.($a['time'] - $t).' сек.<br/>';
			if(!empty($a['comment'])) echo 'Комментарий: '.$a['comment'].'<br/>';
			if($f['login'] == $a['login']) echo '<a href="arena.php?mod=del&aid='.$a['id'].'">Удалить заявку</a>';
			echo '</div>';
			}
		}
	fin();
break;

case 'del':
	if($aid <= 0) msg2('Не выбрана заявка для удаления',1);
	$q = $db->query("select * from `arena` where id={$aid} and login='{$f['login']}' limit 1;");
	if($q->num_rows == 0) msg2('Заявка не найдена',1);
	$a = $q->fetch_assoc();
	if(empty($ok))
		{
		msg2('Вы уверены, что хотите удалить заявку на арене?');
		knopka('arena.php?mod=del&aid='.$a['id'].'&ok=1','Удалить',1);
		knopka('arena.php','Вернуться',1);
		fin();
		}
	$q = $db->query("update `users` set arena_id=0,status=0,komanda=0 where arena_id='{$a['id']}';");
	$q = $db->query("delete from `arena` where id='{$a['id']}' limit 1;");
	msg2('Вы удалили заявку на арене');
	knopka('arena.php', 'Вернуться', 1);
	knopka('loc.php', 'В игру', 1);
	fin();
break;

case 'enjoy':
	if(!empty($f['status'])) msg2('У вас уже есть заявка на арене',1);
	if($aid <= 0) msg2('Заявка не найдена',1);
	$q = $db->query("select * from `arena` where id={$aid} limit 1;");
	if($q->num_rows == 0) msg2('Заявка не найдена',1);
	$a = $q->fetch_assoc();
	if($kom != 1 and $kom != 2) msg2('Не правильно выбрана команда', 1);
	$q = $db->query("select count(*) from `users` where arena_id='{$aid}' and komanda='{$kom}' and status=2;");
	$c = $q->fetch_assoc();
	if($kom == 1 and $a['kom1'] <= $c['count(*)']) msg2('В данной команде уже максимальное количество бойцов',1);
	if($kom == 1 and $f['lvl'] < $a['minlvl1']) msg2('Ваш уровень не подходит',1);
	if($kom == 1 and $a['maxlvl1'] < $f['lvl']) msg2('Ваш уровень не подходит',1);
	if($kom == 2 and $a['kom2'] <= $c['count(*)']) msg2('В данной команде уже максимальное количество бойцов',1);
	if($kom == 2 and $f['lvl'] < $a['minlvl2']) msg2('Ваш уровень не подходит',1);
	if($kom == 2 and $a['maxlvl2'] < $f['lvl']) msg2('Ваш уровень не подходит',1);
	$q = $db->query("update `users` set arena_id='{$a['id']}',komanda='{$kom}',status=2 where id='{$f['id']}' limit 1;");
	msg2('Вы приняли заявку на арене');
	knopka('arena.php', 'Вернуться', 1);
	knopka('loc.php', 'В игру', 1);
	fin();
break;

case 'create':
	if(!empty($f['status'])) msg2('У вас уже есть заявка на арене',1);
	$q = $db->query("select max(lvl) from `item`;");
	$a = $q->fetch_assoc();
	$max_lvl = $a['max(lvl)'];
	if(empty($ok))
		{
		echo '<div class="board" style="text-align:left">';
		echo '<form action="arena.php?mod=create&ok=1" method="POST">';
		echo '<b>1-я команда</b>:<br/>';
		echo 'Количество бойцов: ';
		echo '<select name="kol1">';
		for($i = 1; $i <= 20; $i ++)
			{
			echo '<option value="'.$i.'">'.$i.'</option>';
			}
		echo '</select><br/>';
		echo 'Минимальный уровень: ';
		echo '<select name="minlvl1">';
		for($i = 1; $i <= $max_lvl; $i ++)
			{
			echo '<option value="'.$i.'">'.$i.'</option>';
			}
		echo '</select><br/>';
		echo 'Максимальный уровень: ';
		echo '<select name="maxlvl1">';
		for($i = 1; $i <= $max_lvl; $i ++)
			{
			echo '<option value="'.$i.'">'.$i.'</option>';
			}
		echo '</select><br/><br/>';
		echo '<b>2-я команда</b>:<br/>';
		echo 'Количество бойцов: ';
		echo '<select name="kol2">';
		for($i = 1; $i <= 20; $i ++)
			{
			echo '<option value="'.$i.'">'.$i.'</option>';
			}
		echo '</select><br/>';
		echo 'Минимальный уровень: ';
		echo '<select name="minlvl2">';
		for($i = 1; $i <= $max_lvl; $i ++)
			{
			echo '<option value="'.$i.'">'.$i.'</option>';
			}
		echo '</select><br/>';
		echo 'Максимальный уровень: ';
		echo '<select name="maxlvl2">';
		for($i = 1; $i <= $max_lvl; $i ++)
			{
			echo '<option value="'.$i.'">'.$i.'</option>';
			}
		echo '</select><br/><br/>';
		echo '<b>Время до боя</b>: ';
		echo '<select name="timer">';
		echo '<option value="1">1 минута</option>';
		echo '<option value="2">3 минуты</option>';
		echo '<option value="3">5 минут</option>';
		echo '<option value="4">10 минут</option>';
		echo '</select><br/>';
		echo 'Комментарий (можно оставить пустым):<br/><input type="text" name="comm"/>';
		echo '<input type="submit" value="Создать заявку"/></form></div>';
		knopka('arena.php', 'Вернуться', 1);
		fin();
		}
	if($minlvl1 < 1 or $minlvl1 > $max_lvl) msg2('Неправильно выбран минимальный уровень 1 команды',1);
	if($minlvl2 < 1 or $minlvl2 > $max_lvl) msg2('Неправильно выбран минимальный уровень 2 команды',1);
	if($maxlvl1 < 1 or $minlvl1 > $max_lvl) msg2('Неправильно выбран максимальный уровень 1 команды',1);
	if($maxlvl2 < 1 or $minlvl2 > $max_lvl) msg2('Неправильно выбран максимальный уровень 2 команды',1);
	if($kol1 < 1 or $kol1 > 20) msg2('Неправильно выбрано количество бойцов 1 команды', 1);
	if($kol2 < 1 or $kol2 > 20) msg2('Неправильно выбрано количество бойцов 2 команды', 1);
	if($kol1 == 1 and ($f['lvl'] < $minlvl1 or $maxlvl1 < $f['lvl'])) $minlvl1 = $maxlvl1 = $f['lvl'];
	if($maxlvl1 < $minlvl1) $maxlvl1 = $minlvl1;
	if($maxlvl2 < $minlvl2) $maxlvl2 = $minlvl2;
	if($timer < 1 or $timer > 4) msg('Неправильно выбрано время боя', 1);
	if($timer == 1) $do = 60;
	if($timer == 2) $do = 180;
	if($timer == 3) $do = 300;
	if($timer == 4) $do = 600;
	$timer = $do + $t;
	$q = $db->query("insert into `arena` values(0,'{$f['login']}','{$timer}',{$kol1},{$kol2},{$minlvl1},{$maxlvl1},{$minlvl2},{$maxlvl2},'{$comm}');");
	$aid = $db->insert_id();
	$q = $db->query("update `users` set arena_id={$aid},komanda=1,status=2 where id='{$f['id']}' limit 1;");
	msg2('Вы создали заявку на арене');
	knopka('arena.php', 'Вернуться', 1);
	knopka('loc.php', 'В игру', 1);
	fin();
break;
endswitch;
?>
