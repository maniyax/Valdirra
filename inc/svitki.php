<?php
##############
# 24.12.2014 #
##############
switch($item['ido']):
case 121:	// свиток нападения
	if(empty($f['klan'])) msg2('Вы не в клане',1);
	if(empty($komu))
		{
		echo '<div class="board" style="text-align:left">';
		echo '<form action="inv.php?mod=useitem&iid='.$iid.'" method="POST">';
		echo 'Введите логин:<br/><input type="text" name="komu"/><br/>';
		echo '<input type="submit" value="Напасть"/></form></div>';
		knopka('inv.php', 'Вернуться', 1);
		fin();
		}
	$bz = get_login($komu);
	if($f['login'] == $bz['login']) msg2('Нельзя напасть на себя',1);
	if(empty($bz['klan'])) msg2('Персонаж не в клане',1);
	if($bz['loc'] == 1 or $bz['loc'] == 37 or $bz['loc'] == 91) msg2('Нападение невозможно, персонаж под защитой лагеря',1);
if ($f['loc'] != $bz['loc']) echo'Персонаж находится не рядом с вами';
	if($bz['pvp'] == 0) msg2('Нападение невозможно, у персонажа выключен PvP статус',1);
	if($f['pvp'] == 0) msg2('Нападение невозможно, у Вас выключен PvP статус',1);
	if($f['loc'] == 1 or $f['loc'] == 37 or $f['loc'] == 91) msg2('Нападение из лагеря невозможно',1);
	if($bz['lastdate'] < $t - 300 && $bz['rabota'] < $t - 3600) msg2('Персонаж оффлайн',1);
	if($bz['hpnow'] <= 0) msg2('У персонажа отрицательное здоровье',1);
	if($bz['lvl'] < 6) msg2('Персонаж меньше 6 уровня и находится под защитой новичков',1);
	if($bz['status'] == 1)
		{
		$q = $db->query("select krov from `battle` where id={$bz['boi_id']} limit 1;");
		$a = $q->fetch_assoc();
		if($a['krov'] == 0 or $a['krov'] == 1 or $a['krov'] == 2) msg('Персонаж находится в бою с ботами, нападение невозможно.',1);
		if($a['krov'] == 4) msg('Персонаж дерется на арене, нападение невозможно.',1);
		if($bz['komanda'] == 1) $mykom = 2; else $mykom = 1;
		$q = $db->query("select count(*) from `combat` where komanda={$mykom} AND boi_id={$bz['boi_id']};");
		$count = $q->fetch_assoc();
		$count = $count['count(*)'];
		if($count >= 10) msg2('В вашей команде уже максимальное количество бойцов - 10.',1);
		$logboi = '<span style="color:'.$notice.'">'.date("H:i:s").' '.$f['login'].' использует свиток нападения и нападает на '.$bz['login'].'</span><br/>';
		$q = $db->query("insert into `battlelog` values (0,{$bz['boi_id']},'{$t}','{$logboi}');");
		$items->del_item($f['login'], $item['id'], 1);
		$boi_id = $bz['boi_id'];
		toBoi($f,$mykom);
		knopka('battle.php', 'Вы напали на '.$bz['login'], 1);
		fin();
		}
	else
		{
		$boi_id = addBoi(5);
		$logboi = '<span style="color:'.$notice.'">'.date("H:i:s").' <b>'.$f['login'].' использует свиток нападения и нападает на '.$bz['login'].'</b></span><br/>';
		$q = $db->query("insert into `battlelog` values (0,'{$boi_id}','{$t}','{$logboi}');");
		$items->del_item($f['login'], $item['id'], 1);
		$q = $db->query("update `users` set hpnow=hpmax,mananow=manamax where id={$bz['id']} limit 1;");
		$bz['hpnow'] = $bz['hpmax'];
		$bz['mananow'] = $bz['manamax'];
		toBoi($bz,1);
		toBoi($f,2);
		knopka('battle.php', 'Вы напали на '.$bz['login'],1);
		fin();
		}
break;

case 122:	// свиток развоплощения
	if(empty($f['klan'])) msg('Вы не в клане',1);
	//if(!empty($f['admin'])) msg('Вы в администрации игры, незачем распугивать игроков.',1);
	if(empty($komu))
		{
		echo '<div class="board" style="text-align:left">';
		echo '<form action="inv.php?mod=useitem&iid='.$iid.'" method="POST">';
		echo 'Введите логин:<br/><input type="text" name="komu"/><br/>';
		echo '<input type="submit" value="Напасть"/></form></div>';
		knopka('inv.php', 'Вернуться', 1);
		fin();
		}
	$bz = get_login($komu);
	if($f['login'] == $bz['login']) msg2('Нельзя напасть на себя',1);
	if(empty($bz['klan'])) msg2('Персонаж не в клане',1);
if ($f['loc'] != $bz['loc']) echo'Персонаж находится не рядом с вами';
	if($bz['klan'] == $f['klan']) msg2('Нельзя нападать на соклан!', 1);
	if($bz['admin'] >= 3) msg('Персонаж в администрации игры, нападение невозможно.',1);
	if($bz['loc'] == 1 or $bz['loc'] == 37 or $bz['loc'] == 91) msg2('Нападение невозможно, персонаж под защитой лагеря',1);
	if($bz['pvp'] == 0) msg2('Нападение невозможно, у персонажа выключен PvP статус',1);
	if($f['pvp'] == 0) msg2('Нападение невозможно, у Вас выключен PvP статус',1);
	if($f['loc'] == 1 or $f['loc'] == 37 or $f['loc'] == 91) msg2('Нападение из лагеря невозможно',1);
	if($bz['lastdate'] < $t - 300 && $bz['rabota'] < $t - 3600) msg2('Персонаж оффлайн',1);
	if($bz['hpnow'] <= 0) msg2('У персонажа отрицательное здоровье',1);
	if($bz['lvl'] < 6) msg2('Персонаж меньше 6 уровня и находится под защитой новичков',1);
	if($bz['status'] == 1) msg2('Персонаж в бою, нападение невозможно.',1);
	else
		{
		$boi_id = addBoi(3);
		$logboi = '<span style="color:'.$male.'">'.date("H:i:s").' <b>'.$f['login'].' использует свиток развоплощения и нападает на '.$bz['login'].'</b></span><br/>';
		$q = $db->query("insert into `battlelog` values (0,'{$boi_id}','{$t}','{$logboi}');");
		$items->del_item($f['login'], $item['id'], 1);
		$q = $db->query("update `users` set hpnow=hpmax,mananow=manamax where id={$bz['id']} limit 1;");
		$bz['hpnow'] = $bz['hpmax'];
		$bz['mananow'] = $bz['manamax'];
		toBoi($bz,1);
		toBoi($f,2);
		knopka('battle.php', 'Вы напали на '.$bz['login'],1);
		fin();
		}
break;

case 153:
	$regen = 50;
	$f['hpnow'] += $regen;
	if($f['hpnow'] > $f['hpmax']) $f['hpnow'] = $f['hpmax'];
	$items->del_item($f['login'], $item['id'], 1);
	$q = $db->query("update `users` set hpnow={$f['hpnow']} where id={$f['id']} limit 1;");
	msg2('Здоровье +50',1);
break;

case 154:
	$regen = 100;
	$f['hpnow'] += $regen;
	if($f['hpnow'] > $f['hpmax']) $f['hpnow'] = $f['hpmax'];
	$items->del_item($f['login'], $item['id'], 1);
	$q = $db->query("update `users` set hpnow={$f['hpnow']} where id={$f['id']} limit 1;");
	msg2('Здоровье +100',1);
break;

case 155:
	$regen = 150;
	$f['hpnow'] += $regen;
	if($f['hpnow'] > $f['hpmax']) $f['hpnow'] = $f['hpmax'];
	$items->del_item($f['login'], $item['id'], 1);
	$q = $db->query("update `users` set hpnow={$f['hpnow']} where id={$f['id']} limit 1;");
	msg('Здоровье +150',1);
break;

case 156:
	$regen = 250;
	$f['hpnow'] += $regen;
	if($f['hpnow'] > $f['hpmax']) $f['hpnow'] = $f['hpmax'];
	$items->del_item($f['login'], $item['id'], 1);
	$q = $db->query("update `users` set hpnow={$f['hpnow']} where id={$f['id']} limit 1;");
	msg2('Здоровье +250',1);
break;

case 625:
	$regen = 350;
	$f['hpnow'] += $regen;
	if($f['hpnow'] > $f['hpmax']) $f['hpnow'] = $f['hpmax'];
	$items->del_item($f['login'], $item['id'], 1);
	$q = $db->query("update `users` set hpnow={$f['hpnow']} where id={$f['id']} limit 1;");
	msg2('Здоровье +350',1);
break;

case 626:
	$regen = 500;
	$f['hpnow'] += $regen;
	if($f['hpnow'] > $f['hpmax']) $f['hpnow'] = $f['hpmax'];
	$items->del_item($f['login'], $item['id'], 1);
	$q = $db->query("update `users` set hpnow={$f['hpnow']} where id={$f['id']} limit 1;");
	msg2('Здоровье +500',1);
break;

case 627:
	$regen = 750;
	$f['hpnow'] += $regen;
	if($f['hpnow'] > $f['hpmax']) $f['hpnow'] = $f['hpmax'];
	$items->del_item($f['login'], $item['id'], 1);
	$q = $db->query("update `users` set hpnow={$f['hpnow']} where id={$f['id']} limit 1;");
	msg2('Здоровье +750',1);
break;

case 628:
	$regen = 1000;
	$f['hpnow'] += $regen;
	if($f['hpnow'] > $f['hpmax']) $f['hpnow'] = $f['hpmax'];
	$items->del_item($f['login'], $item['id'], 1);
	$q = $db->query("update `users` set hpnow={$f['hpnow']} where id={$f['id']} limit 1;");
	msg2('Здоровье +1000',1);
break;

case 629:
	$regen = 1500;
	$f['hpnow'] += $regen;
	if($f['hpnow'] > $f['hpmax']) $f['hpnow'] = $f['hpmax'];
	$items->del_item($f['login'], $item['id'], 1);
	$q = $db->query("update `users` set hpnow={$f['hpnow']} where id={$f['id']} limit 1;");
	msg2('Здоровье +1500',1);
break;

case 713:
	$regen = 400;
	$f['hpnow'] += $regen;
	if($f['hpnow'] > $f['hpmax']) $f['hpnow'] = $f['hpmax'];
	$items->del_item($f['login'], $item['id'], 1);
	$q = $db->query("update `users` set hpnow={$f['hpnow']} where id={$f['id']} limit 1;");
	msg2('Здоровье +400',1);
break;


case 714:
	$regen = 800;
	$f['hpnow'] += $regen;
	if($f['hpnow'] > $f['hpmax']) $f['hpnow'] = $f['hpmax'];
	$items->del_item($f['login'], $item['id'], 1);
	$q = $db->query("update `users` set hpnow={$f['hpnow']} where id={$f['id']} limit 1;");
	msg2('Здоровье +800',1);
break;


case 191:
	$regen = 50;
	$f['mananow'] += $regen;
	if($f['mananow'] > $f['manamax']) $f['mananow'] = $f['manamax'];
	$items->del_item($f['login'], $item['id'], 1);
	$q = $db->query("update `users` set mananow={$f['mananow']} where id={$f['id']} limit 1;");
	msg2('Мана +50',1);
break;

case 192:
	$regen = 100;
	$f['mananow'] += $regen;
	if($f['mananow'] > $f['manamax']) $f['mananow'] = $f['manamax'];
	$items->del_item($f['login'], $item['id'], 1);
	$q = $db->query("update `users` set mananow={$f['mananow']} where id={$f['id']} limit 1;");
	msg2('Мана +100',1);
break;

case 193:
	$regen = 150;
	$f['mananow'] += $regen;
	if($f['mananow'] > $f['manamax']) $f['mananow'] = $f['manamax'];
	$items->del_item($f['login'], $item['id'], 1);
	$q = $db->query("update `users` set mananow={$f['mananow']} where id={$f['id']} limit 1;");
	msg2('Мана +150',1);
break;

case 194:
	$regen = 250;
	$f['mananow'] += $regen;
	if($f['mananow'] > $f['manamax']) $f['mananow'] = $f['manamax'];
	$items->del_item($f['login'], $item['id'], 1);
	$q = $db->query("update `users` set mananow={$f['mananow']} where id={$f['id']} limit 1;");
	msg2('Мана +250',1);
break;

case 630:
	$regen = 350;
	$f['mananow'] += $regen;
	if($f['mananow'] > $f['manamax']) $f['mananow'] = $f['manamax'];
	$items->del_item($f['login'], $item['id'], 1);
	$q = $db->query("update `users` set mananow={$f['mananow']} where id={$f['id']} limit 1;");
	msg2('Мана +350',1);
break;

case 631:
	$regen = 500;
	$f['mananow'] += $regen;
	if($f['mananow'] > $f['manamax']) $f['mananow'] = $f['manamax'];
	$items->del_item($f['login'], $item['id'], 1);
	$q = $db->query("update `users` set mananow={$f['mananow']} where id={$f['id']} limit 1;");
	msg2('Мана +500',1);
break;

case 632:
	$regen = 750;
	$f['mananow'] += $regen;
	if($f['mananow'] > $f['manamax']) $f['mananow'] = $f['manamax'];
	$items->del_item($f['login'], $item['id'], 1);
	$q = $db->query("update `users` set mananow={$f['mananow']} where id={$f['id']} limit 1;");
	msg2('Мана +750',1);
break;

case 633:
	$regen = 1000;
	$f['mananow'] += $regen;
	if($f['mananow'] > $f['manamax']) $f['mananow'] = $f['manamax'];
	$items->del_item($f['login'], $item['id'], 1);
	$q = $db->query("update `users` set mananow={$f['mananow']} where id={$f['id']} limit 1;");
	msg2('Мана +1000',1);
break;

case 634:
	$regen = 1500;
	$f['mananow'] += $regen;
	if($f['mananow'] > $f['manamax']) $f['mananow'] = $f['manamax'];
	$items->del_item($f['login'], $item['id'], 1);
	$q = $db->query("update `users` set mananow={$f['mananow']} where id={$f['id']} limit 1;");
	msg2('Мана +1500',1);
break;

case 620:
	$items->del_item($f['login'], $item['id'], 1);
	$timer = $t + 3600;
	$q = $db->query("update `users` set doping=6,doping_time='{$timer}' where id={$f['id']} limit 1;");
	msg2('Вы выпили эликсир ловкости',1);
break;

case 621:
	$items->del_item($f['login'], $item['id'], 1);
	$timer = $t + 3600;
	$q = $db->query("update `users` set doping=7,doping_time='{$timer}' where id={$f['id']} limit 1;");
	msg2('Вы выпили эликсир реакции',1);
break;

case 622:
	$items->del_item($f['login'], $item['id'], 1);
	$timer = $t + 3600;
	$q = $db->query("update `users` set doping=8,doping_time='{$timer}' where id={$f['id']} limit 1;");
	msg2('Вы выпили эликсир жизненной энергии',1);
break;

case 623:
	$items->del_item($f['login'], $item['id'], 1);
	$timer = $t + 3600;
	$q = $db->query("update `users` set doping=9,doping_time='{$timer}' where id={$f['id']} limit 1;");
	msg2('Вы выпили эликсир магической энергии',1);
break;

case 624:
	$items->del_item($f['login'], $item['id'], 1);
	$timer = $t + 3600;
	$q = $db->query("update `users` set doping=10,doping_time='{$timer}' where id={$f['id']} limit 1;");
	msg2('Вы выпили эликсир вышибалы',1);
break;

case 635:
	$items->del_item($f['login'], $item['id'], 1);
	$timer = $t + 3600;
	$q = $db->query("update `users` set doping=1,doping_time='{$timer}' where id={$f['id']} limit 1;");
	msg2('Вы выпили брагу',1);
break;

case 636:
	$items->del_item($f['login'], $item['id'], 1);
	$timer = $t + 3600;
	$q = $db->query("update `users` set doping=2,doping_time='{$timer}' where id={$f['id']} limit 1;");
	msg2('Вы выпили пиво',1);
break;

case 637:
	$items->del_item($f['login'], $item['id'], 1);
	$timer = $t + 3600;
	$q = $db->query("update `users` set doping=3,doping_time='{$timer}' where id={$f['id']} limit 1;");
	msg2('Вы выпили вино',1);
break;

case 638:
	$items->del_item($f['login'], $item['id'], 1);
	$timer = $t + 3600;
	$q = $db->query("update `users` set doping=4,doping_time='{$timer}' where id={$f['id']} limit 1;");
	msg2('Вы выпили самогон',1);
break;

case 639:
	$items->del_item($f['login'], $item['id'], 1);
	$timer = $t + 3600;
	$q = $db->query("update `users` set doping=5,doping_time='{$timer}' where id={$f['id']} limit 1;");
	msg2('Вы выпили рисовый шнапс',1);
break;

case 716:	// свиток нападения

	if(empty($nloc))
		{
		echo '<div class="board" style="text-align:left">';
		echo '<form action="inv.php?mod=useitem&iid='.$iid.'" method="POST">';
		echo 'Введите номер локации:<br/><input type="number" name="nloc"/><br/>';
		echo '<input type="submit" value="Телепортироваться"/></form></div>';
		knopka('inv.php', 'Вернуться', 1);
		fin();
		}
		$items->del_item($f['login'], $item['id'], 1);

	$q = $db->query("update `users` set loc={$nloc} where id={$f['id']} limit 1;");
		knopka('loc.php', 'Вы перенеслись в локацию '.$nloc.'', 1);
		fin();

break;

case 717:
	$nloc = 149;
	$f['loc'] = $nloc;

	$items->del_item($f['login'], $item['id'], 1);
	$q = $db->query("update `users` set loc={$f['loc']} where id={$f['id']} limit 1;");
	msg2('Вы переместились в город',1);
break;


case 720:
$items->del_item($f['login'], $item['id'], 1);
$q = $db->query("update `users` set cu=cu+100 where id={$f['id']} limit 1;");
msg2('В рыбке находилось 100 монет');
break;

case 721:
$items->del_item($f['login'], $item['id'], 1);
$q = $db->query("update `users` set cu=cu+250 where id={$f['id']} limit 1;");
msg2('В рыбке находилось 250 монет');
break;

case 722:
$items->del_item($f['login'], $item['id'], 1);
$q = $db->query("update `users` set cu=cu+400 where id={$f['id']} limit 1;");
msg2('В рыбке находилось 400 монет');
break;

case 744:	// свиток нападения

	if(empty($nloc))
		{
		echo '<div class="board" style="text-align:left">';
		echo '<form action="inv.php?mod=useitem&iid='.$iid.'" method="POST">';
		echo 'Введите номер локации:<br/><input type="number" name="nloc"/><br/>';
		echo '<input type="submit" value="Телепортироваться"/></form></div>';
		knopka('inv.php', 'Вернуться', 1);
		fin();
		}

	$q = $db->query("update `users` set loc={$nloc} where id={$f['id']} limit 1;");
		knopka('loc.php', 'Вы перенеслись в локацию '.$nloc.'', 1);
		fin();
break;

case 756:
	$nloc = $klan['loc'];
	$f['loc'] = $nloc;

	$items->del_item($f['login'], $item['id'], 1);
	$q = $db->query("update `users` set loc={$f['loc']} where id={$f['id']} limit 1;");
	msg2('Вы переместились к своему клановому замку',1);
break;


case 774:
	$items->del_item($f['login'], $item['id'], 1);
	$timer = $t + 1800;
	$q = $db->query("update `users` set doping=11,doping_time='{$timer}' where id={$f['id']} limit 1;");
	msg2('Вы выпили бутылку шампанского',1);
break;

case 741:
if($items->count_base_item($f['login'], 834) ==0) msg2('У вас нет ювелирного набора',1);
if($f['p_yuvelir'] < 250) msg2('У вас не достаточно мастерства',1);
if(empty($go)){
msg2('<form action="inv.php?mod=useitem&iid='.$iid.'" method="post">Введите id вещи, в которую собираетесь инкрустировать камень:<br><input type="text" name="go"/><br><input type="submit" value="Инкрустировать"/></form>',1);
fin();}
$q = $db->query("select * from `invent` where id={$go} limit 1;");
$a = $q->fetch_assoc();
if($a['login'] != $f['login']) msg2('Это не ваша вещь',1);
if($a['flag_rinok'] ==1) msg2('Эта вещь находится на рынке',1);
if($a['flag_equip'] ==1) msg2('Эта вещь экипирована',1);
if(empty($a['slot']) or $a['slot'] == 'sumka') msg2('Эта вещь не является предметом экипировки',1);
if($a['flag_sklad'] ==1) msg2('Эта вещь находится на складе',1);
if($a['inkrust'] > 0) msg2('В этой вещи уже есть один камень',1);
$a['krit'] *= 1.15;
$a['uvorot'] *= 1.15;
$a['uron'] *= 1.05;
$a['bron'] *= 1.05;
$a['hp'] *= 1.2;
$a['info'] .= '<br>Инкрустирован алмаз игроком '.$f['login'].'';
$q = $db->query("update `invent` set info='{$a['info']}',krit={$a['krit']},uvorot={$a['uvorot']},uron={$a['uron']},bron={$a['bron']},hp={$a['hp']},inkrust=1 where id={$go} limit 1;");
if($f['rasa'] ==2){ $q = $db->query("update `users` set p_yuvelir=p_yuvelir+2,nabor=nabor-1 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_yuvelir=p_yuvelir+1,nabor=nabor-1 where id={$f['id']} limit 1;");}

$items->del_base_item($f['login'], 741, 1);
msg2('Успех!');
knopka2('inv.php', 'В рюкзак');
fin();
break;


case 740:
if($items->count_base_item($f['login'], 834) ==0) msg2('У вас нет ювелирного набора',1);
if($f['p_yuvelir'] < 100) msg2('У вас не достаточно мастерства',1);
if(empty($go)){
msg2('<form action="inv.php?mod=useitem&iid='.$iid.'" method="post">Введите id вещи, в которую собираетесь инкрустировать камень:<br><input type="text" name="go"/><br><input type="submit" value="Инкрустировать"/></form>',1);
fin();}
$q = $db->query("select * from `invent` where id={$go} limit 1;");
$a = $q->fetch_assoc();
if($a['login'] != $f['login']) msg2('Это не ваша вещь',1);
if($a['flag_rinok'] ==1) msg2('Эта вещь находится на рынке',1);
if($a['flag_equip'] ==1) msg2('Эта вещь экипирована',1); if(empty($a['slot']) or $a['slot'] == 'sumka') msg2('Эта вещь не является предметом экипировки',1);
if($a['flag_sklad'] ==1) msg2('Эта вещь находится на складе',1);
if($a['inkrust'] > 0) msg2('В этой вещи уже есть один камень',1);
$a['krit'] *= 1.10;
$a['uvorot'] *= 1.10;
$a['uron'] *= 1.03;
$a['bron'] *= 1.03;
$a['hp'] *= 1.15;
$a['info'] .= '<br>Инкрустирован змеевик игроком '.$f['login'].'';
$q = $db->query("update `invent` set info='{$a['info']}',krit={$a['krit']},uvorot={$a['uvorot']},uron={$a['uron']},bron={$a['bron']},hp={$a['hp']},inkrust=1 where id={$go} limit 1;");
if($f['rasa'] ==2){ $q = $db->query("update `users` set p_yuvelir=p_yuvelir+2,nabor=nabor-1 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_yuvelir=p_yuvelir+1,nabor=nabor-1 where id={$f['id']} limit 1;");}

$items->del_base_item($f['login'], 740, 1);
msg2('Успех!');
knopka2('inv.php', 'В рюкзак');
fin();
break;


case 739:
if($items->count_base_item($f['login'], 834) ==0) msg2('У вас нет ювелирного набора',1);
if($f['p_yuvelir'] < 50) msg2('У вас не достаточно мастерства',1);
if(empty($go)){
msg2('<form action="inv.php?mod=useitem&iid='.$iid.'" method="post">Введите id вещи, в которую собираетесь инкрустировать камень:<br><input type="text" name="go"/><br><input type="submit" value="Инкрустировать"/></form>',1);
fin();}
$q = $db->query("select * from `invent` where id={$go} limit 1;");
$a = $q->fetch_assoc();
if($a['login'] != $f['login']) msg2('Это не ваша вещь',1);
if($a['flag_rinok'] ==1) msg2('Эта вещь находится на рынке',1);
if($a['flag_equip'] ==1) msg2('Эта вещь экипирована',1); if(empty($a['slot']) or $a['slot'] == 'sumka') msg2('Эта вещь не является предметом экипировки',1);
if($a['flag_sklad'] ==1) msg2('Эта вещь находится на складе',1);
if($a['inkrust'] > 0) msg2('В этой вещи уже есть один камень',1);
$a['krit'] *= 1;
$a['uvorot'] *= 1;
$a['uron'] *= 1.02;
$a['bron'] *= 1.02;
$a['hp'] *= 1.1;
$a['info'] .= '<br>Инкрустирован амазанит игроком '.$f['login'].'';
$q = $db->query("update `invent` set info='{$a['info']}',krit={$a['krit']},uvorot={$a['uvorot']},uron={$a['uron']},bron={$a['bron']},hp={$a['hp']},inkrust=1 where id={$go} limit 1;");
if($f['rasa'] ==2){ $q = $db->query("update `users` set p_yuvelir=p_yuvelir+2,nabor=nabor-1 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_yuvelir=p_yuvelir+1,nabor=nabor-1 where id={$f['id']} limit 1;");}

$items->del_base_item($f['login'], 739, 1);
msg2('Успех!');
knopka2('inv.php', 'В рюкзак');
fin();
break;


case 738:
if($items->count_base_item($f['login'], 834) ==0) msg2('У вас нет ювелирного набора',1);
if($f['p_yuvelir'] < 25) msg2('У вас не достаточно мастерства',1);
if(empty($go)){
msg2('<form action="inv.php?mod=useitem&iid='.$iid.'" method="post">Введите id вещи, в которую собираетесь инкрустировать камень:<br><input type="text" name="go"/><br><input type="submit" value="Инкрустировать"/></form>',1);
fin();}
$q = $db->query("select * from `invent` where id={$go} limit 1;");
$a = $q->fetch_assoc();
if($a['login'] != $f['login']) msg2('Это не ваша вещь',1);
if($a['flag_rinok'] ==1) msg2('Эта вещь находится на рынке',1);
if($a['flag_equip'] ==1) msg2('Эта вещь экипирована',1); if(empty($a['slot']) or $a['slot'] == 'sumka') msg2('Эта вещь не является предметом экипировки',1);
if($a['flag_sklad'] ==1) msg2('Эта вещь находится на складе',1);
if($a['inkrust'] > 0) msg2('В этой вещи уже есть один камень',1);
$a['krit'] *= 1;
$a['uvorot'] *= 1;
$a['uron'] *= 1.01;
$a['bron'] *= 1.01;
$a['hp'] *= 1.05;
$a['info'] .= '<br>Инкрустирован малахит игроком '.$f['login'].'';
$q = $db->query("update `invent` set info='{$a['info']}',krit={$a['krit']},uvorot={$a['uvorot']},uron={$a['uron']},bron={$a['bron']},hp={$a['hp']},inkrust=1 where id={$go} limit 1;");
if($f['rasa'] ==2){ $q = $db->query("update `users` set p_yuvelir=p_yuvelir+2,nabor=nabor-1 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_yuvelir=p_yuvelir+1,nabor=nabor-1 where id={$f['id']} limit 1;");}

$items->del_base_item($f['login'], 738, 1);
msg2('Успех!');
knopka2('inv.php', 'В рюкзак');
fin();
break;


case 737:
if($items->count_base_item($f['login'], 834) ==0) msg2('У вас нет ювелирного набора',1);

if(empty($go)){
msg2('<form action="inv.php?mod=useitem&iid='.$iid.'" method="post">Введите id вещи, в которую собираетесь инкрустировать камень:<br><input type="text" name="go"/><br><input type="submit" value="Инкрустировать"/></form>',1);
fin();}
$q = $db->query("select * from `invent` where id={$go} limit 1;");
$a = $q->fetch_assoc();
if($a['login'] != $f['login']) msg2('Это не ваша вещь',1);
if($a['flag_rinok'] ==1) msg2('Эта вещь находится на рынке',1);
if($a['flag_equip'] ==1) msg2('Эта вещь экипирована',1); if(empty($a['slot']) or $a['slot'] == 'sumka') msg2('Эта вещь не является предметом экипировки',1);
if($a['flag_sklad'] ==1) msg2('Эта вещь находится на складе',1);
if($a['inkrust'] > 0) msg2('В этой вещи уже есть один камень',1);
$a['krit'] *= 1;
$a['uvorot'] *= 1.05;
$a['uron'] *= 1;
$a['bron'] *= 1;
$a['hp'] *= 1;
$a['info'] .= '<br>Инкрустирован сапфир игроком '.$f['login'].'';
$q = $db->query("update `invent` set info='{$a['info']}',krit={$a['krit']},uvorot={$a['uvorot']},uron={$a['uron']},bron={$a['bron']},hp={$a['hp']},inkrust=1 where id={$go} limit 1;");
if($f['rasa'] ==2){ $q = $db->query("update `users` set p_yuvelir=p_yuvelir+2,nabor=nabor-1 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_yuvelir=p_yuvelir+1,nabor=nabor-1 where id={$f['id']} limit 1;");}

$items->del_base_item($f['login'], 737, 1);
msg2('Успех!');
knopka2('inv.php', 'В рюкзак');
fin();
break;


case 736:
if($items->count_base_item($f['login'], 834) ==0) msg2('У вас нет ювелирного набора',1);

if(empty($go)){
msg2('<form action="inv.php?mod=useitem&iid='.$iid.'" method="post">Введите id вещи, в которую собираетесь инкрустировать камень:<br><input type="text" name="go"/><br><input type="submit" value="Инкрустировать"/></form>',1);
fin();}
$q = $db->query("select * from `invent` where id={$go} limit 1;");
$a = $q->fetch_assoc();
if($a['login'] != $f['login']) msg2('Это не ваша вещь',1);
if($a['flag_rinok'] ==1) msg2('Эта вещь находится на рынке',1);
if($a['flag_equip'] ==1) msg2('Эта вещь экипирована',1); if(empty($a['slot']) or $a['slot'] == 'sumka') msg2('Эта вещь не является предметом экипировки',1);
if($a['flag_sklad'] ==1) msg2('Эта вещь находится на складе',1);
if($a['inkrust'] > 0) msg2('В этой вещи уже есть один камень',1);
$a['krit'] *= 1.05;
$a['uvorot'] *= 1;
$a['uron'] *= 1;
$a['bron'] *= 1;
$a['hp'] *= 1;
$a['info'] .= '<br>Инкрустирован рубин игроком '.$f['login'].'';
$q = $db->query("update `invent` set info='{$a['info']}',krit={$a['krit']},uvorot={$a['uvorot']},uron={$a['uron']},bron={$a['bron']},hp={$a['hp']},inkrust=1 where id={$go} limit 1;");
if($f['rasa'] ==2){ $q = $db->query("update `users` set p_yuvelir=p_yuvelir+2,nabor=nabor-1 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_yuvelir=p_yuvelir+1,nabor=nabor-1 where id={$f['id']} limit 1;");}

$items->del_base_item($f['login'], 736, 1);
msg2('Успех!');
knopka2('inv.php', 'В рюкзак');
fin();
break;



case 735:
if($items->count_base_item($f['login'], 834) ==0) msg2('У вас нет ювелирного набора',1);

if(empty($go)){
msg2('<form action="inv.php?mod=useitem&iid='.$iid.'" method="post">Введите id вещи, в которую собираетесь инкрустировать камень:<br><input type="text" name="go"/><br><input type="submit" value="Инкрустировать"/></form>',1);
fin();}
$q = $db->query("select * from `invent` where id={$go} limit 1;");
$a = $q->fetch_assoc();
if($a['login'] != $f['login']) msg2('Это не ваша вещь',1);
if($a['flag_rinok'] ==1) msg2('Эта вещь находится на рынке',1);
if($a['flag_equip'] ==1) msg2('Эта вещь экипирована',1); if(empty($a['slot']) or $a['slot'] == 'sumka') msg2('Эта вещь не является предметом экипировки',1);
if($a['flag_sklad'] ==1) msg2('Эта вещь находится на складе',1);
if($a['inkrust'] > 0) msg2('В этой вещи уже есть один камень',1);
$a['krit'] *= 1.03;
$a['uvorot'] *= 1.03;
$a['uron'] *= 1;
$a['bron'] *= 1;
$a['hp'] *= 1;
$a['info'] .= '<br>Инкрустирован изумруд игроком '.$f['login'].'';
$q = $db->query("update `invent` set info='{$a['info']}',krit={$a['krit']},uvorot={$a['uvorot']},uron={$a['uron']},bron={$a['bron']},hp={$a['hp']},inkrust=1 where id={$go} limit 1;");
if($f['rasa'] ==2){ $q = $db->query("update `users` set p_yuvelir=p_yuvelir+2,nabor=nabor-1 where id={$f['id']} limit 1;");}
else{$q = $db->query("update `users` set p_yuvelir=p_yuvelir+1,nabor=nabor-1 where id={$f['id']} limit 1;");}

$items->del_base_item($f['login'], 735, 1);
msg2('Успех!');
knopka2('inv.php', 'В рюкзак');
fin();
break;

case 766:
$minus = 10;
$f['j'] -= $minus;
if($f['j'] <0) $f['j'] =0;
$items->del_item($f['login'], $item['id'], 1);
$q=$db->query("update `users` set j={$f['j']} where id={$f['id']} limit 1;");
msg2('Жажда -'.$minus.'');
knopka2('inv.php', 'В рюкзак',1);
break;

case 767:
$minus = 20;
$f['j'] -= $minus;
if($f['j'] <0) $f['j'] =0;
$items->del_item($f['login'], $item['id'], 1);
$q=$db->query("update `users` set j={$f['j']} where id={$f['id']} limit 1;");
msg2('Жажда -'.$minus.'');
knopka2('inv.php', 'В рюкзак',1);
break;

case 768:
$minus = 40;
$f['j'] -= $minus;
if($f['j'] <0) $f['j'] =0;
$items->del_item($f['login'], $item['id'], 1);
$q=$db->query("update `users` set j={$f['j']} where id={$f['id']} limit 1;");
msg2('Жажда -'.$minus.'');
knopka2('inv.php', 'В рюкзак',1);
break;

case 769:
$minus = 10;
$f['g'] -= $minus;
if($f['g'] <0) $f['g'] =0;
$items->del_item($f['login'], $item['id'], 1);
$q=$db->query("update `users` set g={$f['g']} where id={$f['id']} limit 1;");
msg2('Голод -'.$minus.'');
knopka2('inv.php', 'В рюкзак',1);
break;

case 770:
$minus = 30;
$f['g'] -= $minus;
if($f['g'] <0) $f['g'] =0;
$items->del_item($f['login'], $item['id'], 1);
$q=$db->query("update `users` set g={$f['g']} where id={$f['id']} limit 1;");
msg2('Голод -'.$minus.'');
knopka2('inv.php', 'В рюкзак',1);
break;

case 771:
$minus = 10;
$f['g'] -= $minus;
if($f['g'] <0) $f['g'] =0;
$items->del_item($f['login'], $item['id'], 1);
$q=$db->query("update `users` set g={$f['g']} where id={$f['id']} limit 1;");
msg2('голод -'.$minus.'');
knopka2('inv.php', 'В рюкзак',1);
break;

case 833:
	$items->del_item($f['login'], $item['id'], 1);
	$timer = $t + 3600;
	$q = $db->query("update `users` set buff=1,buff_time='{$timer}' where id={$f['id']} limit 1;");
	msg2('Вы использовали малое благославление',1);
break;



default: fin(); break;
endswitch;
?>
