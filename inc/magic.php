<?php
##############
# 29.07.2014 #
##############
//if($me['magic_hod'] == $boi['round']) msg2('Вы уже кастовали заклинание в этом ходу.');
switch($magic):
default:
	if(25 <= $me['mananow']) knopka('battle.php?mod=magic&magic=1&r='.mt_rand(1,999), 'Лечение (25 MP)', 1);
	if(50 <= $me['mananow']) knopka('battle.php?mod=magic&magic=2&r='.mt_rand(1,999), 'Исцеление (50 MP)', 1);
	if(70 <= $me['mananow']) knopka('battle.php?mod=magic&magic=3&r='.mt_rand(1,999), 'Помощь (70 MP)', 1);
	if(150 <= $me['mananow']) knopka('battle.php?mod=magic&magic=4&r='.mt_rand(1,999), 'Воскрешение (150 MP)', 1);
	if(150 <= $me['mananow']) knopka('battle.php?mod=magic&magic=5&r='.mt_rand(1,999), 'Молния (150 MP)', 1);
	if(325 <= $me['mananow']) knopka('battle.php?mod=magic&magic=6&r='.mt_rand(1,999), 'Струя пламени (325 MP)', 1);
	if(245 <= $me['mananow']) knopka('battle.php?mod=magic&magic=7&r='.mt_rand(1,999), 'Водная стрела (245 MP)', 1);
	if(280 <= $me['mananow']) knopka('battle.php?mod=magic&magic=8&r='.mt_rand(1,999), 'Лезвие ветра (280 MP)', 1);
	if(490 <= $me['mananow']) knopka('battle.php?mod=magic&magic=9&r='.mt_rand(1,999), 'Грозовой удар (490 MP)', 1);
	hr();
	knopka('battle.php?r='.mt_rand(1,999), 'Вернуться', 1);
	fin();
break;

case 1:
	if($me['mananow'] < 25) msg2('У вас недостаточно маны',1);
	if(mt_rand(1,100) <= 80 + $f['intel']/10)
		{
		$regen = mt_rand(200 + 2 * $f['intel'], 300 + 3 * $f['intel']);
		if($regen + $me['hpnow'] > $me['hpmax']) $regen = $me['hpmax'] - $me['hpnow'];
		$me['hpnow'] += $regen;
		$log_hp = '<span style="color:'.$notice.'">'.$me['login'].' лечит магией '.$regen.' hp</span><br/><br/>';
		$q = $db->query("update `users` set hpnow={$me['hpnow']} where id={$f['id']} limit 1;");
		$q = $db->query("update `combat` set hpnow={$me['hpnow']} where id={$me['id']} limit 1;");
		}
	else
		{
		$log_hp = '<span style="color:'.$notice.'">'.$me['login'].' делает сложные пассы руками, но ничего не происходит</span><br/><br/>';
//		$q = $db->query("update `users` set  where id={$me['id']} limit 1;");
		}
	$q = $db->query("update `users` set mananow=mananow-25 where id={$f['id']} limit 1;");
	$q = $db->query("update `combat` set mananow=mananow-25 where id={$me['id']} limit 1;");
	$q = $db->query("insert into `battlelog` values (0,{$bid},UNIX_TIMESTAMP(),'{$log_hp}');");
break;

case 2:
	if($me['mananow'] < 50) msg2('У вас недостаточно маны',1);
	if(mt_rand(1,100) <= 60 + $f['intel']/5)
		{
		$regen = mt_rand(520 + 5 * $f['intel'], 600 + 6 * $f['intel']);
		if($regen + $me['hpnow'] > $me['hpmax']) $regen = $me['hpmax'] - $me['hpnow'];
		$me['hpnow'] += $regen;
		$log_hp = '<span style="color:'.$notice.'">'.$me['login'].' исцеляет магией '.$regen.' hp</span><br/><br/>';
		$q = $db->query("update `users` set hpnow={$me['hpnow']} where id={$f['id']} limit 1;");
		$q = $db->query("update `combat` set hpnow={$me['hpnow']} where id={$me['id']} limit 1;");
		}
	else
		{
		$log_hp = '<span style="color:'.$notice.'">'.$me['login'].' делает сложные пассы руками, но ничего не происходит</span><br/><br/>';
//		$q = $db->query("update `users` set magic_hod={$boi['round']} where id={$me['id']} limit 1;");
		}
	$q = $db->query("update `users` set mananow=mananow-50 where id={$f['id']} limit 1;");
	$q = $db->query("update `combat` set mananow=mananow-50 where id={$me['id']} limit 1;");
	$q = $db->query("insert into `battlelog` values (0,{$bid},UNIX_TIMESTAMP(),'{$log_hp}');");
break;

case 3:
	if($me['mananow'] < 70) msg2('У вас недостаточно маны',1);
	$lgn = (isset($_REQUEST['lgn'])) ? $_REQUEST['lgn'] : 0;
	$q = $db->query("select * from `combat` where boi_id={$bid} and komanda={$me['komanda']} and login<>'{$me['login']}' and hpnow>0 limit 1;");
	while($hz = $q->fetch_assoc())
		{
		$kom3[$hz['id']] = $hz['login'].' ('.$hz['hpnow'].'/'.$hz['hpmax'].')';
		unset($hz);
		}
	if(empty($lgn))
		{
		echo '<div class="board" style="text-align:left">';
		echo '<form action="battle.php?mod=magic&magic=3" method="POST">';
		echo 'Помочь:<br/>';
		echo '<select name="lgn">';
		foreach($kom3 as $key => $val) echo '<option value="'.$key.'">'.$val.'</option>';
		echo '</select>';
		echo '<br/>';
		echo '<input type="submit" value="Кастовать"></form></div>';
		hr();
		knopka('battle.php', 'Вернуться', 1);
		fin();
		}
	$lgn = intval($lgn);	//эта переменная - ид бойца из таблицы combat
	if(empty($lgn) or $lgn <= 0) msg2('Не выбран союзник для помощи!', 1);
	$q = $db->query("select * from `combat` where id={$lgn} and boi_id={$bid} and hpnow>0 and komanda={$me['komanda']} and login<>'{$me['login']}' limit 1;");
	$hz = $q->fetch_assoc() or msg2('Боец не найден!', 1);
	if(mt_rand(1,100) <= 65 + $f['intel']/5)
		{
		$regen = mt_rand(520 + 5 * $f['intel'], 850 + 6 * $f['intel']);
		if($regen + $hz['hpnow'] > $hz['hpmax']) $regen = $hz['hpmax'] - $hz['hpnow'];
		$hz['hpnow'] += $regen;
		$log_hp = '<span style="color:'.$notice.'">'.$me['login'].' помогает '.$hz['login'].', исцеляя '.$regen.' hp</span><br/><br/>';
		if(empty($hz['flag_bot'])) $q = $db->query("update `users` set hpnow={$me['hpnow']} where login='{$hz['login']}' limit 1;");
		//$q = $db->query("update `users` set magic_hod={$boi['round']} where id={$me['id']} limit 1;");
		$q = $db->query("update `combat` set hpnow={$hz['hpnow']} where login='{$hz['login']}' limit 1;");
		}
	else
		{
		$log_hp = '<span style="color:'.$notice.'">'.$me['login'].' пытается помочь '.$hz['login'].', но ничего не происходит</span><br/><br/>';
		//$q = $db->query("update `users` set magic_hod={$boi['round']} where id={$me['id']} limit 1;");
		}
	$q = $db->query("update `users` set mananow=mananow-70 where id={$f['id']} limit 1;");
	$q = $db->query("update `combat` set mananow=mananow-70 where id={$me['id']} limit 1;");
	$q = $db->query("insert into `battlelog` values (0,{$bid},UNIX_TIMESTAMP(),'{$log_hp}');");
break;

case 4:
	if($me['mananow'] < 150) msg('У вас недостаточно маны',1);
	$lgn = (isset($_REQUEST['lgn'])) ? $_REQUEST['lgn'] : 0;
	$q = $db->query("select * from `combat` where boi_id={$bid} and komanda={$me['komanda']} and hpnow<1 and login<>'{$me['login']}' limit 1;");
	while($hz = $q->fetch_assoc())
		{
		$kom3[$hz['id']] = $hz['login'].' ('.$hz['hpnow'].'/'.$hz['hpmax'].')';
		unset($hz);
		}
	if(empty($lgn))
		{
		echo '<div class="board" style="text-align:left">';
		echo '<form action="battle.php?mod=magic&magic=4" method="POST">';
		echo 'Воскресить:<br/>';
		echo '<select name="lgn">';
		foreach($kom3 as $key => $val) echo '<option value="'.$key.'">'.$val.'</option>';
		echo '</select>';
		echo '<br/>';
		echo '<input type="submit" value="Кастовать"></form></div>';
		hr();
		knopka('battle.php', 'Вернуться', 1);
		fin();
		}
	$lgn = intval($lgn);	//эта переменная - ид бойца из таблицы combat
	if(empty($lgn) or $lgn <= 0) msg2('Не выбран союзник для воскрешения!',1);
	$q = $db->query("select * from `combat` where id={$lgn} and boi_id={$bid} and hpnow<1 and komanda={$me['komanda']} and login<>'{$me['login']}' limit 1;");
	$hz = $q->fetch_assoc() or msg2('Боец не найден!',1);
	if(mt_rand(1,100) <= 60 + $f['intel']/5)
		{
		$regen = intval($hz['hpmax'] * 0.5);
		$hz['hpnow'] = $regen;
		$log_hp = '<span style="color:'.$notice.'">'.$me['login'].' воскрешает '.$hz['login'].' с половиной hp</span><br/><br/>';
		if(empty($hz['flag_bot'])) $q = $db->query("update `users` set hpnow={$me['hpnow']} where login='{$hz['login']}' limit 1;");
//		$q = $db->query("update `users` set magic_hod={$boi['round']} where id={$me['id']} limit 1;");
		$q = $db->query("update `combat` set hpnow={$me['hpnow']} where id='{$me['id']}' limit 1;");
		}
	else
		{
		$log_hp = '<span style="color:'.$notice.'">'.$me['login'].' пытается воскресить '.$hz['login'].', но ничего не происходит</span><br/><br/>';
		//$q = $db->query("update `users` set magic_hod={$boi['round']} where id={$me['id']} limit 1;");
		}
	$q = $db->query("update `users` set mananow=mananow-150 where id={$f['id']} limit 1;");
	$q = $db->query("update `combat` set mananow=mananow-150 where id={$me['id']} limit 1;");
	$q = $db->query("insert into `battlelog` values (0,{$bid},UNIX_TIMESTAMP(),'{$log_hp}');");
break;

case 5:
	if($me['mananow'] < 150) msg2('У вас недостаточно маны',1);
	$lgn = (isset($_REQUEST['lgn'])) ? $_REQUEST['lgn'] : 0;
	if($me['komanda'] == 1) $kom = 2; else $kom = 1;
	$q = $db->query("select * from `combat` where boi_id={$bid} and komanda={$kom};");
	while($hz = $q->fetch_assoc())
		{
		if($hz['hpnow'] > 1) $kom3[$hz['id']] = $hz['login'].'('.$hz['hpnow'].'/'.$hz['hpmax'].')';
		unset($hz);
		}
	if(empty($lgn))
		{
		echo '<div class="board" style="text-align:left">';
		echo '<form action="battle.php?mod=magic&magic=5" method="POST">';
		echo 'Цель:<br/>';
		echo '<select name="lgn">';
		foreach($kom3 as $key => $val) echo '<option value="'.$key.'">'.$val.'</option>';
		echo '</select>';
		echo '<br/>';
		echo '<input type="submit" value="Кастовать"></form></div>';
		knopka('battle.php', 'Вернуться', 1);
		fin();
		}
	$lgn = intval($lgn);	//эта переменная - ид бойца из таблицы combat
	if(empty($lgn) or $lgn <= 0) msg('Не выбран соперник для удара!',1);
	$q = $db->query("select * from `combat` where id={$lgn} and boi_id={$bid} limit 1;");
	$hz = $q->fetch_assoc() or msg2('Боец не найден!',1);
	if($hz['komanda'] == $me['komanda']) msg2('Против своей команды нельзя',1);
	if($hz['hpnow'] <= 0) msg2('Противник уже убит', 1);
	if(mt_rand(1,100) <= 60 + $f['intel']/5)
		{
		$uron_mag = mt_rand(1 + $f['intel'], 99 + 5 * $f['intel']+ $hz['sila'] / 2);
		$log_hp = '<span style="color:'.$notice.'">Ветвистый удар молнии с треском бьет в '.$hz['login'].', нанеся '.$uron_mag.' урона</span><br/>';
		$q = $db->query("update `combat` set hpnow=hpnow-{$uron_mag},time_udar='{$t}' where id={$hz['id']} and hpnow>1 limit 1;");
		$q = $db->query("update `combat` set time_udar='{$t}' where login={$me['login']} limit 1;");
		}
	else
		{
		$log_hp = '<span style="color:'.$notice.'">'.$me['login'].' делает сложные пассы руками, но ничего не происходит</span><br/><br/>';
		}
	$q = $db->query("update `users` set mananow=mananow-150 where id={$f['id']} limit 1;");
	$q = $db->query("update `combat` set mananow=mananow-150 where id={$me['id']} limit 1;");
	$q = $db->query("insert into `battlelog` values (0,{$bid},UNIX_TIMESTAMP(),'{$log_hp}');");
break;

case 6:
	if($me['mananow'] < 325) msg2('У вас недостаточно маны',1);
	$lgn = (isset($_REQUEST['lgn'])) ? $_REQUEST['lgn'] : 0;
	if($me['komanda'] == 1) $kom = 2; else $kom = 1;
	$q = $db->query("select * from `combat` where boi_id={$bid} and komanda={$kom};");
	while($hz = $q->fetch_assoc())
		{
		if($hz['hpnow'] > 1) $kom3[$hz['id']] = $hz['login'].'('.$hz['hpnow'].'/'.$hz['hpmax'].')';
		unset($hz);
		}
	if(empty($lgn))
		{
		echo '<div class="board" style="text-align:left">';
		echo '<form action="battle.php?mod=magic&magic=6" method="POST">';
		echo 'Цель:<br/>';
		echo '<select name="lgn">';
		foreach($kom3 as $key => $val) echo '<option value="'.$key.'">'.$val.'</option>';
		echo '</select>';
		echo '<br/>';
		echo '<input type="submit" value="Кастовать"></form></div>';
		knopka('battle.php', 'Вернуться', 1);
		fin();
		}
	$lgn = intval($lgn);
	if(empty($lgn) or $lgn <= 0) msg('Не выбран соперник для удара!',1);
	$q = $db->query("select * from `combat` where id={$lgn} and boi_id={$bid} limit 1;");
	$hz = $q->fetch_assoc() or msg2('Боец не найден!',1);
	if($hz['komanda'] == $me['komanda']) msg2('Против своей команды нельзя',1);
	if($hz['hpnow'] <= 0) msg2('Противник уже убит', 1);
	if(mt_rand(1,100) <= 40 + $f['intel']/3)
		{
		$uron_mag = mt_rand(90 + $f['intel'] * 7, 150 + $f['intel'] * 10);
		$log_hp = '<span style="color:'.$notice.'">Испепеляющая огненная струя напором бьёт в '.$hz['login'].', нанеся '.$uron_mag.' урона</span><br/>';
		$q = $db->query("update `combat` set hpnow=hpnow-{$uron_mag},time_udar='{$t}' where id={$hz['id']} and hpnow>1 limit 1;");
		$q = $db->query("update `combat` set time_udar='{$t}' where login={$me['login']} limit 1;");
		}
	else
		{
		$log_hp = '<span style="color:'.$notice.'">'.$me['login'].' делает сложные пассы руками, но ничего не происходит</span><br/><br/>';
		}
	$q = $db->query("update `users` set mananow=mananow-325 where id={$f['id']} limit 1;");
	$q = $db->query("update `combat` set mananow=mananow-325 where id={$me['id']} limit 1;");
	$q = $db->query("insert into `battlelog` values (0,{$bid},UNIX_TIMESTAMP(),'{$log_hp}');");
break;

case 7:	//Номер заклинания
	if($me['mananow'] < 245) msg2('У вас недостаточно маны',1);	//Проверка на наличие маны
	$lgn = (isset($_REQUEST['lgn'])) ? $_REQUEST['lgn'] : 0;
	if($me['komanda'] == 1) $kom = 2; else $kom = 1;
	$q = $db->query("select * from `combat` where boi_id={$bid} and komanda={$kom};");
	while($hz = $q->fetch_assoc())
		{
		if($hz['hpnow'] > 1) $kom3[$hz['id']] = $hz['login'].'('.$hz['hpnow'].'/'.$hz['hpmax'].')';
		unset($hz);
		}
	if(empty($lgn))
		{
		echo '<div class="board" style="text-align:left">';
		echo '<form action="battle.php?mod=magic&magic=7" method="POST">'; 	//Ещё один ноер заклинания
		echo 'Цель:<br/>';
		echo '<select name="lgn">';
		foreach($kom3 as $key => $val) echo '<option value="'.$key.'">'.$val.'</option>';
		echo '</select>';
		echo '<br/>';
		echo '<input type="submit" value="Кастовать"></form></div>';
		knopka('battle.php', 'Вернуться', 1);
		fin();
		}
	$lgn = intval($lgn);	
	if(empty($lgn) or $lgn <= 0) msg('Не выбран соперник для удара!',1);
	$q = $db->query("select * from `combat` where id={$lgn} and boi_id={$bid} limit 1;");
	$hz = $q->fetch_assoc() or msg2('Боец не найден!',1);
	if($hz['komanda'] == $me['komanda']) msg2('Против своей команды нельзя',1);
	if($hz['hpnow'] <= 0) msg2('Противник уже убит', 1);
	if(mt_rand(1,100) <= 60 + $f['intel']/5)	//Вероятность каста
		{
		$uron_mag = mt_rand(33 + $f['intel'] * 6, 333 + $f['intel'] * 6);	//Урон от заклинания
		$log_hp = '<span style="color:'.$notice.'">Водная стрела со свистом промзает '.$hz['login'].', нанеся '.$uron_mag.' урона</span><br/>';	//Текст заклинания, виден игроку
		$q = $db->query("update `combat` set hpnow=hpnow-{$uron_mag},time_udar='{$t}' where id={$hz['id']} and hpnow>1 limit 1;");
		$q = $db->query("update `combat` set time_udar='{$t}' where login={$me['login']} limit 1;");
		}
	else
		{
		$log_hp = '<span style="color:'.$notice.'">'.$me['login'].' делает сложные пассы руками, но ничего не происходит</span><br/><br/>';
		}
	$q = $db->query("update `users` set mananow=mananow-245 where id={$f['id']} limit 1;");	//Вычитание маны
	$q = $db->query("update `combat` set mananow=mananow-245 where id={$me['id']} limit 1;");//Вычитание маны
	$q = $db->query("insert into `battlelog` values (0,{$bid},UNIX_TIMESTAMP(),'{$log_hp}');");
break;

case 8:
	if($me['mananow'] < 280) msg2('У вас недостаточно маны',1);
	$lgn = (isset($_REQUEST['lgn'])) ? $_REQUEST['lgn'] : 0;
	if($me['komanda'] == 1) $kom = 2; else $kom = 1;
	$q = $db->query("select * from `combat` where boi_id={$bid} and komanda={$kom};");
	while($hz = $q->fetch_assoc())
		{
		if($hz['hpnow'] > 1) $kom3[$hz['id']] = $hz['login'].'('.$hz['hpnow'].'/'.$hz['hpmax'].')';
		unset($hz);
		}
	if(empty($lgn))
		{
		echo '<div class="board" style="text-align:left">';
		echo '<form action="battle.php?mod=magic&magic=8" method="POST">';
		echo 'Цель:<br/>';
		echo '<select name="lgn">';
		foreach($kom3 as $key => $val) echo '<option value="'.$key.'">'.$val.'</option>';
		echo '</select>';
		echo '<br/>';
		echo '<input type="submit" value="Кастовать"></form></div>';
		knopka('battle.php', 'Вернуться', 1);
		fin();
		}
	$lgn = intval($lgn);
	if(empty($lgn) or $lgn <= 0) msg('Не выбран соперник для удара!',1);
	$q = $db->query("select * from `combat` where id={$lgn} and boi_id={$bid} limit 1;");
	$hz = $q->fetch_assoc() or msg2('Боец не найден!',1);
	if($hz['komanda'] == $me['komanda']) msg2('Против своей команды нельзя',1);
	if($hz['hpnow'] <= 0) msg2('Противник уже убит', 1);
	if(mt_rand(1,100) <= 70 + $f['intel']/5)
		{
		$uron_mag = mt_rand(299 + $f['intel'] * 3, 699 + $f['intel'] * 7);
		$log_hp = '<span style="color:'.$notice.'">Порывистый ветер словно лезвие прошёлся по '.$hz['login'].', нанеся '.$uron_mag.' урона</span><br/>';
		$q = $db->query("update `combat` set hpnow=hpnow-{$uron_mag},time_udar='{$t}' where id={$hz['id']} and hpnow>1 limit 1;");
		$q = $db->query("update `combat` set time_udar='{$t}' where login={$me['login']} limit 1;");
		}
	else
		{
		$log_hp = '<span style="color:'.$notice.'">'.$me['login'].' делает сложные пассы руками, но ничего не происходит</span><br/><br/>';
		}
	$q = $db->query("update `users` set mananow=mananow-150 where id={$f['id']} limit 1;");
	$q = $db->query("update `combat` set mananow=mananow-150 where id={$me['id']} limit 1;");
	$q = $db->query("insert into `battlelog` values (0,{$bid},UNIX_TIMESTAMP(),'{$log_hp}');");
break;

case 9:	//Номер заклинания
	if($me['mananow'] < 490) msg2('У вас недостаточно маны',1);	//Проверка на наличие маны
	$lgn = (isset($_REQUEST['lgn'])) ? $_REQUEST['lgn'] : 0;
	if($me['komanda'] == 1) $kom = 2; else $kom = 1;
	$q = $db->query("select * from `combat` where boi_id={$bid} and komanda={$kom};");
	while($hz = $q->fetch_assoc())
		{
		if($hz['hpnow'] > 1) $kom3[$hz['id']] = $hz['login'].'('.$hz['hpnow'].'/'.$hz['hpmax'].')';
		unset($hz);
		}
	if(empty($lgn))
		{
		echo '<div class="board" style="text-align:left">';
		echo '<form action="battle.php?mod=magic&magic=9" method="POST">'; 	//Ещё один ноер заклинания
		echo 'Цель:<br/>';
		echo '<select name="lgn">';
		foreach($kom3 as $key => $val) echo '<option value="'.$key.'">'.$val.'</option>';
		echo '</select>';
		echo '<br/>';
		echo '<input type="submit" value="Кастовать"></form></div>';
		knopka('battle.php', 'Вернуться', 1);
		fin();
		}
	$lgn = intval($lgn);	
	if(empty($lgn) or $lgn <= 0) msg('Не выбран соперник для удара!',1);
	$q = $db->query("select * from `combat` where id={$lgn} and boi_id={$bid} limit 1;");
	$hz = $q->fetch_assoc() or msg2('Боец не найден!',1);
	if($hz['komanda'] == $me['komanda']) msg2('Против своей команды нельзя',1);
	if($hz['hpnow'] <= 0) msg2('Противник уже убит', 1);
	if(mt_rand(1,100) <= 55 + $f['intel']/5)	//Вероятность каста
		{
		$uron_mag = mt_rand($f['intel'] + $hz['sila'] / 2, $f['intel'] * 18 + $hz['sila']);	//Урон от заклинания
		$log_hp = '<span style="color:'.$notice.'">Несколько ветвистых ударов молнии с треском бьет в  '.$hz['login'].', нанеся '.$uron_mag.' урона</span><br/>';	//Текст заклинания, виден игроку
		$q = $db->query("update `combat` set hpnow=hpnow-{$uron_mag},time_udar='{$t}' where id={$hz['id']} and hpnow>1 limit 1;");
		$q = $db->query("update `combat` set time_udar='{$t}' where login={$me['login']} limit 1;");
		}
	else
		{
		$log_hp = '<span style="color:'.$notice.'">'.$me['login'].' делает сложные пассы руками, но ничего не происходит</span><br/><br/>';
		}
	$q = $db->query("update `users` set mananow=mananow-490 where id={$f['id']} limit 1;");	//Вычитание маны
	$q = $db->query("update `combat` set mananow=mananow-490 where id={$me['id']} limit 1;");//Вычитание маны
	$q = $db->query("insert into `battlelog` values (0,{$bid},UNIX_TIMESTAMP(),'{$log_hp}');");
break;
endswitch;
?>
