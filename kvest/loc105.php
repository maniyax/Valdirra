<?php
##############
# 24.12.2014 #
##############
if ($f['lvl'] < 4)
	{
	knopka('loc.php', 'Доступно с 4 уровня', 1);
	fin();
	}
if ($items->count_base_item($f['login'], 755) == 0)
	{
	knopka('loc.php', 'У вас нет удочки', 1);
	fin();
	}
msg2('Навык рыболова: '.$f['p_fishman'].'');
$q = $db->query("select ido,count(*) from `invent` where login='{$f['login']}' and (ido=166 or ido=167 or ido=170 or ido=171 or ido=172 or ido=173) and flag_equip=0 and flag_rinok=0 and flag_sklad=0 and flag_arenda=0 group by ido order by ido desc;");
if ($q->num_rows == 0) msg2('Вы не можете рыбачить, сначала нужно найти наживку.', 1);
if (empty($ok) and empty($iid))
	{
	$_SESSION['count'] = 1;
	echo '<div class="board" style="text-align:left">';
	echo '<form action="kvest.php?ok=1" method="POST">';
	echo 'Выберите наживку:<br/>';
	echo '<select name="iid">';
	while ($s = $q->fetch_assoc())
		{
		$item = $items->base_shmot($s['ido']);
		echo '<option value="'.$s['ido'].'">'.$item['name'].' ('.$s['count(*)'].' шт.)</option>';
		}
	echo '</select><br/>';
	echo '<input type="submit" value="Далее" /></form></div>';
	knopka('loc.php', 'Вернуться', 1);
	fin();
	}
// есть ли выбранная наживка
$iid = intval($iid);
if ($iid != 166 and $iid != 167 and $iid != 170 and $iid != 171 and $iid != 172 and $iid != 173) msg2('Неизвестная наживка...', 1);
if ($items->count_base_item($f['login'], $iid) == 0) msg2('У вас нет такой наживки', 1);

// массив с рыбой, зависит от наживки
$fish = array();
$shns = $f['p_fishman'];
switch ($iid):
	case 173:
		$shns += 3;
		$fish[] = 174;
		if ($f['p_fishman'] >= 20) $fish[] = 175;
		if ($f['p_fishman'] >= 30) $fish[] = 176;
		break;

	case 172:
		$shns += 5;
		$fish[] = 174;
		if ($f['p_fishman'] >= 20) $fish[] = 175;
		if ($f['p_fishman'] >= 30) $fish[] = 176;
		if ($f['p_fishman'] >= 50) $fish[] = 177;
		if ($f['p_fishman'] >= 80) $fish[] = 178;
		if ($f['p_fishman'] >= 110) $fish[] = 179;
		break;

	case 171:
		$shns += 7;
		$fish[] = 174;
		if ($f['p_fishman'] >= 20) $fish[] = 175;
		if ($f['p_fishman'] >= 30) $fish[] = 176;
		if ($f['p_fishman'] >= 50) $fish[] = 177;
		if ($f['p_fishman'] >= 80) $fish[] = 178;
		if ($f['p_fishman'] >= 110) $fish[] = 179;
		if ($f['p_fishman'] >= 140) $fish[] = 180;
		if ($f['p_fishman'] >= 170) $fish[] = 181;
		if ($f['p_fishman'] >= 200) $fish[] = 182;
		break;

	case 170:
		$shns += 10;
		$fish[] = 174;
		if ($f['p_fishman'] >= 20) $fish[] = 175;
		if ($f['p_fishman'] >= 30) $fish[] = 176;
		if ($f['p_fishman'] >= 50) $fish[] = 177;
		if ($f['p_fishman'] >= 80) $fish[] = 178;
		if ($f['p_fishman'] >= 110) $fish[] = 179;
		if ($f['p_fishman'] >= 140) $fish[] = 180;
		if ($f['p_fishman'] >= 170) $fish[] = 181;
		if ($f['p_fishman'] >= 200) $fish[] = 182;
		if ($f['p_fishman'] >= 250) $fish[] = 183;
		if ($f['p_fishman'] >= 400) $fish[] = 184;
		if ($f['p_fishman'] >= 500) $fish[] = 185;
		break;

	case 167:
		$shns += 12;
		$fish[] = 174;
		if ($f['p_fishman'] >= 20) $fish[] = 175;
		if ($f['p_fishman'] >= 30) $fish[] = 176;
		if ($f['p_fishman'] >= 50) $fish[] = 177;
		if ($f['p_fishman'] >= 80) $fish[] = 178;
		if ($f['p_fishman'] >= 110) $fish[] = 179;
		if ($f['p_fishman'] >= 140) $fish[] = 180;
		if ($f['p_fishman'] >= 170) $fish[] = 181;
		if ($f['p_fishman'] >= 200) $fish[] = 182;
		if ($f['p_fishman'] >= 250) $fish[] = 183;
		if ($f['p_fishman'] >= 400) $fish[] = 184;
		if ($f['p_fishman'] >= 500) $fish[] = 185;
		if ($f['p_fishman'] >= 600) $fish[] = 186;
		if ($f['p_fishman'] >= 700) $fish[] = 187;
		if ($f['p_fishman'] >= 800) $fish[] = 188;
		break;

	case 166:
		$shns += 13;
		$fish[] = 174;
		if ($f['p_fishman'] >= 20) $fish[] = 175;
		if ($f['p_fishman'] >= 30) $fish[] = 176;
		if ($f['p_fishman'] >= 50) $fish[] = 177;
		if ($f['p_fishman'] >= 80) $fish[] = 178;
		if ($f['p_fishman'] >= 110) $fish[] = 179;
		if ($f['p_fishman'] >= 140) $fish[] = 180;
		if ($f['p_fishman'] >= 170) $fish[] = 181;
		if ($f['p_fishman'] >= 200) $fish[] = 182;
		if ($f['p_fishman'] >= 250) $fish[] = 183;
		if ($f['p_fishman'] >= 400) $fish[] = 184;
		if ($f['p_fishman'] >= 500) $fish[] = 185;
		if ($f['p_fishman'] >= 600) $fish[] = 186;
		if ($f['p_fishman'] >= 700) $fish[] = 187;
		if ($f['p_fishman'] >= 800) $fish[] = 188;
		if ($f['p_fishman'] >= 900) $fish[] = 189;
		if ($f['p_fishman'] >= 1000) $fish[] = 190;
if ($f['p_fishman'] >= 250) $fish[] = 720;
if ($f['p_fishman'] >= 500) $fish[] = 721;
if ($f['p_fishman'] >= 1000) $fish[] = 722;
		break;
endswitch;
// конец массива рыбы
// расчет шансов
if ($shns < 20) $shns = 20;
if ($shns > 90) $shns = 90;
if ($f['vip'] > $_SERVER['REQUEST_TIME']) $shns = 100;
msg2('Вы забросили удочку. Шанс на удачу '.$shns.'%');
if (mt_rand(1, 100) <= $shns)
	{
	// успех
	shuffle($fish);
	$item = $items->base_shmot($fish[0]);
	$items->del_base_item($f['login'], $iid, 1);
	$items->add_item($f['login'], $fish[0]);
if($f['rasa'] ==1){ 	$q = $db->query("update `users` set p_fishman=p_fishman+2,udochka=udochka-1 where id={$f['id']} limit 1;");}
else{	$q = $db->query("update `users` set p_fishman=p_fishman+1,udochka=udochka-1 where id={$f['id']} limit 1;");}
	msg2('[Поймано: '.$item['name'].']');
	}
else
	{
	$slova = array();
	$slova[] = 'Поплавок задергался, вы подсекли, но рыба сорвалась...';
	$slova[] = 'Поплавок задергался, вы плавно потянули удилище на себя, но рыба сорвалась...';
	$slova[] = 'Поплавок задергался и резко утонул, вы дернули удочку, но рыба сорвалась...';
	$slova[] = 'Поплавок задергался и резко утонул, плавно потянули удочку, но рыба сорвалась...';
	$slova[] = 'Вы не дождались поклевки...';
	$slova[] = 'Не клюет...';
	shuffle($slova);
	msg2($slova[0]);
	}

knopka('loc.php', 'Вернуться', 1);
fin();
?>
