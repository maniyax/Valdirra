<?php
##############
# 24.11.2014 #
##############

require_once('inc/top.php');
require_once('inc/check.php');	// вход в игру
require_once('inc/head.php');
require_once('class/items.php');

$mod = isset($_REQUEST['mod']) ? $_REQUEST['mod'] : '';
$magic = isset($_REQUEST['magic']) ? intval($_REQUEST['magic']) : 0;
$flag_boi = 1;	// типа бой идёт

$prazdn = 0;
$date = date('d.m');
$gd = getdate();
if($date == '29.12' or $date == '30.12' or $date == '31.12' or $date == '01.01' or $date == '02.01') $prazdn = 1;
if($date == '13.01' or $date == '14.01') $prazdn = 1;
if($date == '14.02') $prazdn = 1;
if($date == '23.02') $prazdn = 1;
if($date == '08.03') $prazdn = 1;
if($date == '23.03') $prazdn = 1;
if($date == '01.04') $prazdn = 1;
if($date == '01.05' or $date == '02.05' or $date == '03.05' or $date == '09.05') $prazdn = 1;
if($date == '01.06') $prazdn = 1;
if($date == '12.06') $prazdn = 1;
if($date == '25.05') $prazdn = 1;
if($date == '07.07') $prazdn = 1;
if($date == '02.08') $prazdn = 1;
if($date == '01.09') $prazdn = 1;
if($gd['yday'] == 255) $prazdn = 1;
if($date == '12.12') $prazdn = 1;

if($f['status'] == 2)
	{
	knopka('arena.php', 'У вас заявка на арене', 1);
	fin();
	}
//проверим, есть ли вообще бой с вашим ИД
$q = $db->query("select * from `battle` where id={$f['boi_id']} limit 1;");
if($q->num_rows == 0) $flag_boi = 0; // боя нет
$boi = $q->fetch_assoc();
$bid = $boi['id'];	//для краткости, чтобы не писать нигде $f['boi_id'] или $boi['id']
if($boi['flag_boi'] == 0) $flag_boi = 0;

// кто начал бой
if(empty($bz['login']))
	{
	$q = $db->query("update `battle` set login='{$f['login']}' where id={$bid} limit 1;");
	$bz['login'] = $f['login'];
	}

//проверим, идет наш бой или уже закончен
if(empty($flag_boi))
	{
	require_once('inc/hpstring.php');
	knopka('loc.php', 'Бой завершен', 1);
	$q = $db->query("UPDATE `users` SET status=0,lastdate='{$t}' WHERE id='{$f['id']}' LIMIT 1;");
	$q = $db->query("SELECT log FROM `battlelog` WHERE boi_id='{$bid}' ORDER BY id DESC LIMIT 3;");
	echo '<div class="board" style="text-align:left">';
	while ($stlog = $q->fetch_assoc()) echo $stlog['log'];
	echo '</div>';
	fin();
	}

//объявим некоторые переменные
$curtime = $_SERVER['REQUEST_TIME'];			//чтобы не вызывать $_SERVER['REQUEST_TIME'] по 100 раз
$curdate = Date('H:i:s');	//посмотрим, не пригодится - уберем
$final_log = '';			//что отображается при завершении боя
$komanda1 = '';				//1я команда бойцов (с тегами)
$komanda2 = '';				//2я команда бойцов (с тегами)
$kom1 = array();			//1я команда бойцов (логины)
$kom2 = array();			//2я команда бойцов (логины)
$kom1sum = 0;				//сумма бойцов 1й команды
$kom2sum = 0;				//сумма бойцов 2й команды
$logboi = '';				//для лога боя
$me = array();				//боец, который бьет
$uz = array();				//боец, который отвечает на удар
$hodtime = 60;					//защита от повторного удара по жертве (за исключением ответки)
$pkstr = '';				//строчка с шансами попасть
$ost = 5 - ($curtime - $boi['sbrospar']);
if($ost < 0) $ost = 0;

//вынесем все данные из боевой таблице о себе в отдельный массив
$q = $db->query("select * from `combat` where boi_id={$bid} and login='{$f['login']}' limit 1;");
$me = $q->fetch_assoc();
// если запрос удара есть, а в таблице его нет, добавим
if(!empty($_REQUEST['ud']) and !empty($_REQUEST['bl']) and (empty($me['kuda_udar']) or empty($me['kuda_blok'])))
	{
	$me['kuda_udar'] = intval($_REQUEST['ud']);
	$me['kuda_blok'] = intval($_REQUEST['bl']);
	$q = $db->query("update `combat` set kuda_udar={$me['kuda_udar']},kuda_blok={$me['kuda_blok']} where id={$me['id']} limit 1;");
	}
//сам удар, подгружается как можно раньше, чтобы меньше одного и того же грузить из базы (типа лога боя)
if(!empty($me['kuda_udar']) and $me['hpnow'] > 0 and !empty($me['sopernik']))
	{
	//загрузим бойца, вся красота в том, что боты и игроки теперь в одной таблице => уменьшение объема кода
	$q = $db->query("select * from `combat` where id={$me['sopernik']} and boi_id={$bid} limit 1;");
	$uz = $q->fetch_assoc();
	if($uz['hpnow'] <= 0)
		{
		$me['sopernik'] = 0;
		$q = $db->query("update `combat` set sopernik=0 where id='{$me['id']}' limit 1;");
		msg2('Ваш противник уже мертв!');
		knopka('battle.php', 'Обновить', 1);
		fin();
		}
	if(!empty($uz['kuda_udar']) and !empty($uz['kuda_blok']))
		{
		$me['intel'] = $f['intel'];
		$me['zdor'] = $f['zdor'];
		$me['doping'] = $f['doping'];
		$me['doping_time'] = $f['doping_time'];
		$me['altar'] = $f['altar'];
		$me['altar_time'] = $f['altar_time'];
		$me = calcparam($me);
		$me['ref'] = $f['ref'];
		$me['vip'] = $f['vip'];
		$me['klan'] = $f['klan'];
		if($uz['flag_bot'] == 1)
			{
			$uz['krit'] = $uz['inta'] * 10;
			$uz['uvorot'] = $uz['lovka'] * 10;
			$uz['uron'] = intval($uz['sila'] * 0.9);
			//$uz['uron'] = intval($uz['sila'] * 1.2);
			$uz['bron'] = $uz['sila'];
			$uz['ref'] = 0;
			$uz['vip'] = 0;
			$uz['intel'] = 0;
			$uz['klan'] = '';
			$uz['sopr'] = 0;
			}
		else
			{
			//дополнительный запрос, чтобы взять параметры соперника из таблицы users
			$q = $db->query("select intel,doping,doping_time,altar,altar_time,zdor,sila,lovka,inta,ref,manamax,mananow,vip,klan from `users` where login='{$uz['login']}' limit 1;");
			$uz2 = $q->fetch_assoc();
			$uz['intel'] = $uz2['intel'];
			$uz['zdor'] = $uz2['zdor'];
			$uz['doping'] = $uz2['doping'];
			$uz['doping_time'] = $uz2['doping_time'];
			$uz['altar'] = $uz2['altar'];
			$uz['altar_time'] = $uz2['altar_time'];
			$uz = calcparam($uz);
			$uz['ref'] = $uz2['ref'];
			$uz['vip'] = $uz2['vip'];
			$uz['klan'] = $uz2['klan'];
			}
		$uron_zaudar = mt_rand(intval($me['uron'] * 1.4), intval($me['uron'] * 1.6));
		$s_uron_zaudar = mt_rand(intval($uz['uron'] * 1.4), intval($uz['uron'] * 1.6));

		if($me['krit'] < 1) $me['krit'] = 1;
		if($me['uvorot'] < 1) $me['uvorot'] = 1;
		if($uz['krit'] < 1) $uz['krit'] = 1;
		if($uz['uvorot'] < 1) $uz['uvorot'] = 1;
		if(!isset($me['intel']) or $me['intel'] < 1) $me['intel'] = 1;
		if(!isset($uz['intel']) or $uz['intel'] < 1) $uz['intel'] = 1;
		$me['sopr'] = intval($me['bron'] * 0.1 + $me['intel']);
		if($me['sopr'] > 99) $me['sopr'] = 99;
		$uz['sopr'] = intval($uz['bron'] * 0.1 + $uz['intel']);
		if($uz['sopr'] > 99) $uz['sopr'] = 99;

		if(empty($me['kuda_udar'])) $me['kuda_udar'] = 1;
		if(empty($me['kuda_blok'])) $me['kuda_blok'] = 1;
		if(empty($uz['kuda_udar'])) $uz['kuda_udar'] = 1;
		if(empty($uz['kuda_blok'])) $uz['kuda_blok'] = 1;
		if($me['kuda_udar'] < 1 or 3 < $me['kuda_udar']) $me['kuda_udar'] = 1;
		if($me['kuda_blok'] < 1 or 3 < $me['kuda_blok']) $me['kuda_blok'] = 1;
		if($uz['flag_bot'] == 1)
			{
			if($me['kuda_udar'] == 1 and $uz['kuda_blok'] == 1) $uz['kuda_blok'] = 2;
			if($me['kuda_udar'] == 2 and $uz['kuda_blok'] == 2) $uz['kuda_blok'] = 3;
			if($me['kuda_udar'] == 3 and $uz['kuda_blok'] == 3) $uz['kuda_blok'] = 1;
			}
		if($me['kuda_udar'] == 1) $ud_str = 'голову';
		if($me['kuda_udar'] == 2) $ud_str = 'корпус';
		if($me['kuda_udar'] == 3) $ud_str = 'ноги';
		if($uz['kuda_udar'] == 1) $ud_str2 = 'голову';
		if($uz['kuda_udar'] == 2) $ud_str2 = 'корпус';
		if($uz['kuda_udar'] == 3) $ud_str2 = 'ноги';
		if($me['krit'] < 1) $me['krit'] = 1;
		if($me['uvorot'] < 1) $me['uvorot'] = 1;
		if($uz['krit'] < 1) $uz['krit'] = 1;
		if($uz['uvorot'] < 1) $uz['uvorot'] = 1;

		$kubik = mt_rand(-6, 6);
		$raznica = intval($me['krit'] / $uz['uvorot'] * 10);
		if($raznica < 3) $raznica = 3;
		$raznica += $kubik;
		if($raznica <= 4) $kakoy_udar = 0;
		if(4 < $raznica and $raznica < 16) $kakoy_udar = 1;
		if(16 <= $raznica and $raznica < 25) $kakoy_udar = 2;
		if(25 <= $raznica) $kakoy_udar = 3;
		$kubik = rand(-6, 6);
		$raznica = intval($uz['krit'] / $me['uvorot'] * 10);
		if($raznica < 3) $raznica = 3;
		$raznica += $kubik;
		if($raznica <= 4) $s_kakoy_udar = 0;
		if(4 < $raznica and $raznica < 16) $s_kakoy_udar = 1;
		if(16 <= $raznica and $raznica < 25) $s_kakoy_udar = 2;
		if(25 <= $raznica) $s_kakoy_udar = 3;

		$_SESSION['pkstr'] = '<small>';
		$_SESSION['pkstr'] .= 'П: '.intval(($me['krit'] / $uz['uvorot'] * 10 - 6) / 25 * 100).' - '.intval(($me['krit'] / $uz['uvorot'] * 10 + 6) / 25 * 100).' | ';
		$_SESSION['pkstr'] .= 'У: '.intval(($uz['krit'] / $me['uvorot'] * 10 - 6) / 25 * 100).' - '.intval(($uz['krit'] / $me['uvorot'] * 10 + 6) / 25 * 100);
		$_SESSION['pkstr'] .= '</small>';

		if($kakoy_udar == 0)
			{
			$uron_zaudar = 0;
			if($me['kuda_udar'] != $uz['kuda_blok'] or $uz['flag_bot'] == 1)
				{
				$udar_log = $uz['login'].' уходит от удара в '.$ud_str.'<br/>';
				}
			else
				{
				$udar_log = $me['login'].' бьет в  '.$ud_str.' '.$uz['login'].', но попадает в блок<br/>';
				}
			}
		if($kakoy_udar == 1)
			{
			if($me['kuda_udar'] != $uz['kuda_blok'] or $uz['flag_bot'] == 1)
				{
				$uron_zaudar = intval($uron_zaudar - $uz['bron'] * 0.5);
				if($uron_zaudar < 1) $uron_zaudar = 1;
				$udar_log = $me['login'].' бьет в '.$ud_str.' и наносит '.$uz['login'].' урон '.$uron_zaudar.'<br/>';
				}
			else
				{
				$uron_zaudar = 0;
				$udar_log = $me['login'].' бьет в '.$ud_str.' '.$uz['login'].', но попадает в блок<br/>';
				}
			}
		if($kakoy_udar == 2)
			{
			if($me['kuda_udar'] != $uz['kuda_blok'] or $uz['flag_bot'] == 1)
				{
				$uron_zaudar = intval($uron_zaudar * 1.8 - $uz['bron'] * 0.33);
				if($uron_zaudar < 1) $uron_zaudar = 1;
				$udar_log = $me['login'].' бьет резким ударом в '.$ud_str.' и наносит '.$uz['login'].' урон <b>'.$uron_zaudar.'</b><br/>';
				}
			else
				{
				if(mt_rand(1,100) <= 75)
					{
					$uron_zaudar = 0;
					$udar_log = $me['login'].' бьет резким ударом в '.$ud_str.' '.$uz['login'].', но попадает в блок<br/>';
					}
				else
					{
					$uron_zaudar = intval($uron_zaudar * 1.8 - $uz['bron']);
					if($uron_zaudar < 1) $uron_zaudar = 1;
					$udar_log = $me['login'].' бьет резким ударом в '.$ud_str.' '.$uz['login'].' и пробивает блок на <b>'.$uron_zaudar.'</b><br/>';
					}
				}
			}
		if($kakoy_udar == 3)
			{
			if($me['kuda_udar'] != $uz['kuda_blok'] or $uz['flag_bot'] == 1)
				{
				$uron_zaudar = intval($uron_zaudar * 2.2 - $uz['bron'] * 0.33);
				if($uron_zaudar < 1) $uron_zaudar = 1;
				$udar_log = $me['login'].' бьет критическим ударом в '.$ud_str.' и наносит '.$uz['login'].' урон <b><span style="color:red">'.$uron_zaudar.'</span></b><br/>';
				}
			if($me['kuda_udar'] == $uz['kuda_blok'])
				{
				if(mt_rand(1,100) <= 75)
					{
					$uron_zaudar = 0;
					$udar_log = $me['login'].' бьет критическим ударом в '.$ud_str.' '.$uz['login'].', но попадает в блок<br/>';
					}
				else
					{
					$uron_zaudar = intval($uron_zaudar * 2.2 - $uz['bron']);
					if($uron_zaudar < 1) $uron_zaudar = 1;
					$udar_log = $me['login'].' бьет критическим ударом в '.$ud_str.' '.$uz['login'].' и пробивает блок на <b><span style="color:red">'.$uron_zaudar.'</span></b><br/>';
					}
				}
			}

		if($s_kakoy_udar == 0)
			{
			$s_uron_zaudar = 0;
			if($uz['kuda_udar'] != $me['kuda_blok'] or $uz['flag_bot'] == 1)
				{
				$s_udar_log = $me['login'].' уходит от удара в '.$ud_str2.'<br/>';
				}
			else
				{
				$s_udar_log = $uz['login'].' бьет в  '.$ud_str2.' '.$me['login'].', но попадает в блок<br/>';
				}
			}
		if($s_kakoy_udar == 1)
			{
			if($uz['kuda_udar'] != $me['kuda_blok'] or $uz['flag_bot'] == 1)
				{
				$s_uron_zaudar = intval($s_uron_zaudar - $me['bron'] * 0.5);
				if($s_uron_zaudar < 1) $s_uron_zaudar = 1;
				$s_udar_log = $uz['login'].' бьет в '.$ud_str2.' и наносит '.$me['login'].' урон '.$s_uron_zaudar.'<br/>';
				}
			else
				{
				$s_uron_zaudar = 0;
				$s_udar_log = $uz['login'].' бьет в '.$ud_str2.' '.$me['login'].', но попадает в блок<br/>';
				}
			}
		if($s_kakoy_udar == 2)
			{
			if($uz['kuda_udar'] != $me['kuda_blok'] or $uz['flag_bot'] == 1)
				{
				$s_uron_zaudar = intval($s_uron_zaudar * 1.8 - $me['bron'] * 0.33);
				if($s_uron_zaudar < 1) $s_uron_zaudar = 1;
				$s_udar_log = $uz['login'].' бьет резким ударом в '.$ud_str2.' и наносит '.$me['login'].' урон <b>'.$s_uron_zaudar.'</b><br/>';
				}
			if($uz['kuda_udar'] == $me['kuda_blok'] or $uz['flag_bot'] == 1)
				{
				if(mt_rand(1,100) <= 75)
					{
					$s_uron_zaudar = 0;
					$s_udar_log = $uz['login'].' бьет резким ударом в '.$ud_str2.' '.$me['login'].', но попадает в блок<br/>';
					}
				else
					{
					$s_uron_zaudar = intval($s_uron_zaudar * 1.8 - $me['bron']);
					if($uron_zaudar < 1) $uron_zaudar = 1;
					$s_udar_log = $uz['login'].' бьет резким ударом в '.$ud_str2.' '.$me['login'].' и пробивает блок на <b>'.$s_uron_zaudar.'</b><br/>';
					}
				}
			}
		if($s_kakoy_udar == 3)
			{
			if($uz['kuda_udar'] != $me['kuda_blok'] or $uz['flag_bot'] == 1)
				{
				$s_uron_zaudar = intval($s_uron_zaudar * 2.2 - $me['bron'] * 0.33);
				if($s_uron_zaudar < 1) $s_uron_zaudar = 1;
				$s_udar_log = $uz['login'].' бьет критическим ударом в '.$ud_str2.' и наносит '.$me['login'].' урон <b><span style="color:red">'.$s_uron_zaudar.'</span></b><br/>';
				}
			else
				{
				if(mt_rand(1,100) <= 75)
					{
					$s_uron_zaudar = 0;
					$s_udar_log = $uz['login'].' бьет критическим ударом в '.$ud_str2.' '.$me['login'].', но попадает в блок<br/>';
					}
				else
					{
					$s_uron_zaudar = intval($suron_zaudar * 2.2 - $me['bron']);
					if($s_uron_zaudar < 1) $s_uron_zaudar = 1;
					$s_udar_log = $uz['login'].' бьет критическим ударом в '.$ud_str2.' '.$me['login'].' и пробивает блок на <b><span style="color:red">'.$s_uron_zaudar.'</span></b><br/>';
					}
				}
			}

		$boi['round'] += 1;
		require_once('inc/art.php');
		$me['hpnow'] -= $s_uron_zaudar;
		$uz['hpnow'] -= $uron_zaudar;
		$me['uron_boi'] += $uron_zaudar;
		$me['uron_boi'] += $uron_mag;
		$uz['uron_boi'] += $s_uron_zaudar;
		$uz['uron_boi'] += $s_uron_mag;
		$me['kuda_udar'] = 0;
		$me['kuda_blok'] = 0;
		$me['time_udar'] = $curtime;
		$q = $db->query("UPDATE `battle` SET round='{$boi['round']}' WHERE id='{$bid}' LIMIT 1;");
		$q = $db->query("update `combat` set uron_boi='{$me['uron_boi']}', hpnow='{$me['hpnow']}',time_udar='{$t}',kuda_udar=0,kuda_blok=0,boi_round=boi_round+1 where id='{$me['id']}' limit 1;");
		$q = $db->query("update `combat` set uron_boi='{$uz['uron_boi']}', hpnow='{$uz['hpnow']}',time_udar='{$t}',kuda_udar=0,kuda_blok=0,boi_round=boi_round+1 where id='{$uz['id']}' limit 1;");

		if($me['flag_bot'] == 0) $q = $db->query("update `users` set hpnow='{$me['hpnow']}' where login='{$me['login']}' limit 1;");
		if($uz['flag_bot'] == 0) $q = $db->query("update `users` set hpnow='{$uz['hpnow']}' where login='{$uz['login']}' limit 1;");
		$points = 0;
		$kill_log = '';
		if($me['hpnow'] <= 0 and $uz['hpnow'] > 0)
			{
			$moneyfor = mt_rand($me['lvl'] - 1, $me['lvl'] * 2 + 1);
			if($moneyfor < 1) $moneyfor = 1;
			if($moneyfor > $uz['lvl'] * 2) $moneyfor = $uz['lvl'] * 2;
			$nalog = ceil($moneyfor * 0.1);
			if($nalog < 1) $nalog = 1;
			if($uz['vip'] > $_SERVER['REQUEST_TIME']) $moneyfor = ceil(2 * $moneyfor);
			if($prazdn == 1) $moneyfor *= 3;
			if(!empty($uz['ref'])) $q = $db->query("update `users` set cu=cu+".ceil($moneyfor * 0.25)." where id='{$uz['ref']}' limit 1;");
			if(!empty($uz['klan'])) klan_points($uz['klan'],2);
			if(!empty($me['klan'])) klan_points($me['klan'],-1);
			if($uz['flag_bot'] == 0)
				{
				$kill_log = '<span style="color:'.$notice.'">'.$me['login'].' погибает. '.$uz['login'].' получает '.$moneyfor.' медных монет</span><br/>'.$kill_log;
				if(!empty($uz['klan']) and !empty($me['klan']) and $me['klan'] != $uz['klan'] and $uz['lvl'] - $me['lvl'] < 2 and $boi['krov'] == 3)
					{
					$points = mt_rand(intval($me['lvl'] / 2), $me['lvl'] + 2);
					if($points < 1) $points = 1;
					$kill_log = '<span style="color:yellow;">'.$uz['login'].' получает '.$points.' очков чести</span><br/>'.$kill_log;
					}
				$q = $db->query("update `users` set cu=cu+'{$moneyfor}'+'{$moneyfor}'*pererod*0.5,nalog=nalog+'{$nalog}',chest=chest+'{$points}' where login='{$uz['login']}' limit 1;");
				}
			else
				{
				$kill_log = '<span style="color:'.$notice.'">'.$me['login'].' погибает</span><br/>'.$kill_log;
				}
			$q = $db->query("update `combat` set sopernik=0 where id='{$uz['id']}' or id='{$me['id']}' limit 2;");
			}
		$points = 0;
		$nalog = 0;
		if($uz['hpnow'] <= 0 and $me['hpnow'] > 0)
			{
			$moneyfor = mt_rand($uz['lvl'] - 1, $uz['lvl'] * 2 + 1);
			if($moneyfor < 1) $moneyfor = 1;
			if($moneyfor > $me['lvl'] * 2) $moneyfor = $me['lvl'] * 2;
			$nalog = ceil($moneyfor * 0.1);
			if($nalog < 1) $nalog = 1;
			if($me['vip'] > $_SERVER['REQUEST_TIME']) $moneyfor = ceil(2 * $moneyfor);
			if($prazdn == 1) $moneyfor *= 3;
			if(!empty($me['ref'])) $q = $db->query("update `users` set cu=cu+".ceil($moneyfor * 0.25)." where id='{$me['ref']}' limit 1;");
			if(!empty($me['klan'])) klan_points($me['klan'],2);
			if(!empty($uz['klan'])) klan_points($uz['klan'],-1);
			$kill_log = '<span style="color:'.$notice.'">'.$uz['login'].' погибает. '.$me['login'].' получает '.$moneyfor.' медных монет</span><br/>'.$kill_log;
			if(!empty($uz['klan']) and !empty($me['klan']) and $me['klan'] != $uz['klan'] and $me['lvl'] - $uz['lvl'] < 2 and $boi['krov'] == 3)
				{
				$points = mt_rand(intval($uz['lvl'] / 2), $uz['lvl'] + 2);
				if($points < 1) $points = 1;
				$kill_log = '<span style="color:yellow;">'.$me['login'].' получает '.$points.' очков чести</span><br/>'.$kill_log;
				}
			$q = $db->query("update `users` set cu=cu+'{$moneyfor}'+'{$moneyfor}'*pererod*0.5,nalog=nalog+'{$nalog}',chest=chest+'{$points}' where login='{$me['login']}' limit 1;");
			require_once('inc/drop.php');
			$q = $db->query("update `combat` set sopernik=0 where id='{$uz['id']}' or id='{$me['id']}' limit 2;");
			}
		if($uz['hpnow'] <= 0 and $me['hpnow'] <= 0)
			{
			if(!empty($uz['klan'])) klan_points($uz['klan'],1);
			if(!empty($me['klan'])) klan_points($me['klan'],1);
			if($uz['login'] == 'Тролль') require_once('inc/drop.php');
			$kill_log = '<span style="color:'.$notice.'">'.$uz['login'].' погибает. '.$me['login'].' погибает.</span><br/>'.$kill_log;
			}
		$hp_string = '<span style="color:'.$male.'">'.$curdate.' ('.$boi['round'].'): <b>'.$me['login'].'</b> ['.$me['lvl'].'] ('.$me['hpnow'].'/'.$me['hpmax'].')';
		if($art_uron > 0) $hp_string .= ', арт: '.$art_uron;
		if($art_hp > 0) $hp_string .= ', леч: '.$art_hp;
		if($art_mp > 0) $hp_string .= ', мана: '.$art_mp;
		$hp_string .= ' VS <b>'.$uz['login'].'</b> ['.$uz['lvl'].'] ('.$uz['hpnow'].'/'.$uz['hpmax'].')';
		if($s_art_uron > 0) $hp_string .= ', арт: '.$s_art_uron;
		if($s_art_hp > 0) $hp_string .= ', леч: '.$s_art_hp;
		if($s_art_mp > 0) $hp_string .= ', мана: '.$s_art_mp;
		$hp_string .= '</span><br/>';
		$logboi_new = $hp_string.$kill_log.$s_udar_log.$udar_log.'<br/>';
		$q = $db->query("insert into `battlelog` values (0,'{$bid}','{$t}','{$logboi_new}');");
		}
	else
		{
		if($uz['time_udar'] + $hodtime > $curtime and $uz['flag_bot'] == 0)
			{
			knopka('battle.php', 'Ожидание хода противника', 1);
			$me['time_udar'] = $curtime;
			$q = $db->query("update `combat` set time_udar='{$t}' where id={$me['id']} limit 1;");
			}
		}
	}

switch($mod):
case 'sumka':
	if($me['hpnow'] > 0) require_once('inc/sumka.php');
break;
case 'magic':
	if($me['hpnow'] > 0) require_once('inc/magic.php');
break;
case 'long':
	require_once('inc/hpstring.php');
	knopka('battle.php', 'Вернуться', 1);
	$q = $db->query("SELECT log FROM `battlelog` WHERE boi_id='{$bid}' ORDER BY id DESC;");
	while ($stlog = $q->fetch_assoc())
		{
		echo '<div class="board" style="text-align:left">'.$stlog['log'].'</div>';
		}
	fin();
break;
case 'sbrospar':
	if($boi['sbrospar'] < $curtime - 5 and 0 < $me['hpnow'])
		{
		$q = $db->query("UPDATE `combat` SET sopernik=0 WHERE boi_id='{$bid}';");
		$q = $db->query("UPDATE `battle` SET sbrospar='{$t}' WHERE id='{$bid}' LIMIT 1;");
		msg2('Вы сбросили пары');
		}
break;
endswitch;

//3 последние записи с лога боя, для примерного ориентирования игроков
$q = $db->query("SELECT log FROM `battlelog` WHERE boi_id='{$bid}' ORDER BY id DESC LIMIT 3;");
while ($stlog = $q->fetch_assoc())
	{
	$logboi .= $stlog['log'];
	unset($stlog);
	}

//данные о бойцах, вперемешку с ботами и тд, добавим в $komanda1(2) и сосчитаем в $kom1(2)sum
$q = $db->query("select * from `combat` where boi_id='{$bid}';");
while($bz = $q->fetch_assoc())
	{
	if($bz['time_udar'] < $curtime - 1800 and $bz['hpnow'] > 0)
		{
		$bz['hpnow'] = $bz['mananow'] = 0;
		if($bz['flag_bot'] == 0) $qq = $db->query("update `users` set hpnow=0,mananow=0 where login='{$bz['login']}' limit 1;");
		$qq = $db->query("UPDATE `combat` SET hpnow=0,mananow=0 WHERE boi_id={$bid} AND id={$bz['id']} LIMIT 1;");
		}
	if($bz['time_udar'] < $curtime - $hodtime and $bz['hpnow'] > 0)
		{
		$qq = $db->query("UPDATE `combat` SET kuda_udar=2, kuda_blok=2 WHERE boi_id={$bid} AND id={$bz['id']} LIMIT 1;");
		}
	if($bz['flag_bot'] == 1 and $bz['hpnow'] > 0 and (empty($bz['kuda_udar']) or empty($bz['kuda_blok'])))
		{
		$bz['kuda_udar'] = rand(1, 3);
		$bz['kuda_blok'] = rand(1, 3);
		$qq = $db->query("UPDATE `combat` SET kuda_udar={$bz['kuda_udar']}, kuda_blok={$bz['kuda_blok']} WHERE boi_id={$bid} AND id={$bz['id']} LIMIT 1;");
		}
	if($bz['hpnow'] > 0)
		{
		if($bz['komanda'] == 1)
			{
			$kom1sum++;
			if(empty($bz['sopernik'])) $kom1[] = $bz['id'];
			if($bz['flag_bot'] == 0) $komanda1 .= '<a href="infa.php?mod=uzinfa&lgn='.$bz['login'].'"><span style="color:'.$notice.'">'.$bz['login'].'</span></a> ['.$bz['lvl'].'] ('.$bz['hpnow'].'/'.$bz['hpmax'].') урон: '.$bz['uron_boi'].'<br/>';	//отображение живых
			else $komanda1 .= '<span style="color:'.$notice.'">'.$bz['login'].'</span> ['.$bz['lvl'].'] ('.$bz['hpnow'].'/'.$bz['hpmax'].') урон: '.$bz['uron_boi'].'<br/>';	//отображение живых
			}
		else
			{
			$kom2sum++;
			if(empty($bz['sopernik'])) $kom2[] = $bz['id'];
			if($bz['flag_bot'] == 0) $komanda2 .= '<a href="infa.php?mod=uzinfa&lgn='.$bz['login'].'"><span style="color:'.$male.'">'.$bz['login'].'</span></a> ['.$bz['lvl'].'] ('.$bz['hpnow'].'/'.$bz['hpmax'].') урон: '.$bz['uron_boi'].'<br/>';	//отображение живых
			else $komanda2 .= '<span style="color:'.$male.'">'.$bz['login'].'</span> ['.$bz['lvl'].'] ('.$bz['hpnow'].'/'.$bz['hpmax'].') урон: '.$bz['uron_boi'].'<br/>';
			}
		}
	}

while(sizeof($kom1) > 0 and sizeof($kom2) > 0)
	{
	shuffle($kom1);
	shuffle($kom2);
	$rand1 = mt_rand(0, sizeof($kom1) - 1);
	$rand2 = mt_rand(0, sizeof($kom2) - 1);
	$boeckm1 = $kom1[$rand1];
	$boeckm2 = $kom2[$rand2];
	unset($kom1[$rand1]);
	unset($kom2[$rand2]);
	$kom1 = array_values($kom1);
	$kom2 = array_values($kom2);
	$q = $db->query("UPDATE `combat` SET sopernik='{$boeckm1}' WHERE id='{$boeckm2}' LIMIT 1;");
	$q = $db->query("UPDATE `combat` SET sopernik='{$boeckm2}' WHERE id='{$boeckm1}' LIMIT 1;");
	if($boeckm1 == $me['id']) $me['sopernik'] = $boeckm2;
	if($boeckm2 == $me['id']) $me['sopernik'] = $boeckm1;
	}
if(empty($logboi))
	{
	$logboi = $me['login'].' начинает бой в '.date('H:i:s', $boi['boistart']).'<br/>';
	$q = $db->query("insert into `battlelog` values (0,'{$bid}','{$t}','{$logboi}');");
	}

//финиш
if(empty($komanda1) or empty($komanda2))
	{
	//select sum(lvl) where boi_id={$bid} and komanda=1
	//закончим бой
	$winkom = 0;	//какая команда победила, для раздачи опыта.
	$koef = 0.11;	//базовый коэфф опыта.
//	if($boi['krov'] == 2) $koef *= 3;
	if($boi['krov'] == 3 or $boi['krov'] == 4 or $boi['krov'] == 5) $koef = 0.15;
	if($prazdn == 1) $koef *= 2;
//if ($f['lvl'] <= 5) $koef = 0.33;
//if ($f['lvl'] <= 10 and $f['lvl'] >5) $koef = 0.22;
	$q = $db->query("select sum(lvl) from `combat` where boi_id={$bid} and komanda=1;");
	$lvl1 = $q->fetch_assoc();
	$lvl1 = $lvl1['sum(lvl)'];
	$q = $db->query("select sum(lvl) from `combat` where boi_id={$bid} and komanda=2;");
	$lvl2 = $q->fetch_assoc();
	$lvl2 = $lvl2['sum(lvl)'];
	if($lvl1 < 1) $lvl1 = 1;
	if($lvl2 < 1) $lvl2 = 1;
	//победа за первой командой
	if($kom1sum > 0 and $kom2sum <= 0)
		{
		$winkom = 1;
		$koef = round($lvl2 / $lvl1 * $koef, 2);
		$q = $db->query("update `combat`,`users` set `users`.`j`=`users`.`j`+5,`users`.`g`=`users`.`g`+5,`users`.`win`=`users`.`win`+1 where (`combat`.`flag_bot`=0 and `combat`.`komanda`=1 and `combat`.`boi_id`='{$bid}' and `users`.`login`=`combat`.`login`);");
		$q = $db->query("update `combat`,`users` set `users`.`lost`=`users`.`lost`+1,`users`.`doping`=0,`users`.`j`=0,`users`.`g`=0,`users`.`doping_time`=0,`users`.`rabota`=0,`users`.`loc`={$f['priv']},`users`.`kvest_now`='',`users`.`kvest_step`=0 where (`combat`.`flag_bot`=0 and `combat`.`komanda`=2 and `combat`.`boi_id`='{$bid}' and `users`.`login`=`combat`.`login`);");
		}

	//победа за второй командой
	if($kom1sum <= 0 and $kom2sum > 0)
		{
		$winkom = 2;
		$koef = round($lvl1 / $lvl2 * $koef, 2);
		$q = $db->query("update `combat`,`users` set `users`.`j`=`users`.`j`+5,`users`.`g`=`users`.`g`+5,`users`.`win`=`users`.`win`+1 where (`combat`.`flag_bot`=0 and `combat`.`komanda`=2 and `combat`.`boi_id`='{$bid}' and `users`.`login`=`combat`.`login`);");
		$q = $db->query("update `combat`,`users` set `users`.`lost`=`users`.`lost`+1,`users`.`j`=0,`users`.`g`=0,`users`.`doping`=0,`users`.`doping_time`=0,`users`.`rabota`=0,`users`.`loc`={$f['priv']},`users`.`kvest_now`='',`users`.`kvest_step`=0 where (`combat`.`flag_bot`=0 and `combat`.`komanda`=1 and `combat`.`boi_id`='{$bid}' and `users`.`login`=`combat`.`login`);");
		}

	//ничья
	elseif($kom1sum <= 0 and $kom2sum <= 0)
		{
		$final_log = '<span style="color:'.$male.'">НИЧЬЯ</span><br/>'.$final_log;
		}
	if($prazdn == 1) $final_log = '<span style="color:yellow">Праздничное увеличение опыт х2, монеты х3</span><br/>'.$final_log;
	//финальный лог первой команды
	$q = $db->query("select * from `combat` where boi_id={$bid} and komanda=1 order by uron_boi desc;");
	while($kom1 = $q->fetch_assoc())
		{
		$str = $kom1['login'].' ('.$kom1['hpnow'].'/'.$kom1['hpmax'].') (урон: '.$kom1['uron_boi'];
		if($winkom == 1)
			{
			if(empty($kom1['flag_bot']))
				{
				$qq = $db->query("select id,vip,ref from `users` where login='{$kom1['login']}' limit 1;");
				$slog = $qq->fetch_assoc();
				$exp1 = intval($koef * $kom1['uron_boi']);
				if($slog['vip'] > $_SERVER['REQUEST_TIME']) $exp1 = intval(2 * $exp1);
				if(!empty($slog['ref'])) addexp($slog['ref'], ceil($exp1 * 0.25));
				addexp($slog['id'], $exp1);
				}
			else
				{
				$exp1 = intval($koef * $kom1['uron_boi']);
				}
			$str .= ', опыт: '.$exp1;
			}
		$str .= ')<br/>';
		$final_log = $str.$final_log;
		}
	$final_log = '<small>VS.</small><br/>'.$final_log.'<br/>';
	unset($kom1);
	//финальный лог второй команды
	$q = $db->query("select * from `combat` where boi_id={$bid} and komanda=2 order by uron_boi desc;");
	while($kom2 = $q->fetch_assoc())
		{
		$str = $kom2['login'].' ('.$kom2['hpnow'].'/'.$kom2['hpmax'].') (урон: '.$kom2['uron_boi'];
		if($winkom == 2)
			{
			if(empty($kom2['flag_bot']))
				{
				$qq = $db->query("select id,vip,ref from `users` where login='{$kom2['login']}' limit 1;");
				$slog = $qq->fetch_assoc();
				$exp2 = intval($koef * $kom2['uron_boi']);
				if($slog['vip'] > $_SERVER['REQUEST_TIME']) $exp2 = intval(2 * $exp2);
				if(!empty($slog['ref'])) addexp($slog['ref'], ceil($exp2 * 0.25));
				addexp($slog['id'], $exp2);
				}
			else
				{
				$exp2 = intval($koef * $kom2['uron_boi']);
				}
			$str .= ', опыт: '.$exp2;
			}
		$str .= ')<br/>';
		$final_log = $str.$final_log;
		}
	unset($kom2);
	if(!empty($winkom)) $final_log = 'Коэффициент опыта: '.$koef.'<br/>'.$final_log;
	//лог в базу
	$q = $db->query("insert into `battlelog` values (0,'{$bid}','{$t}','{$final_log}');");
	//чистка комбат
	$q = $db->query("delete from `combat` where boi_id=0 OR boi_id={$bid};");
	$q = $db->query("UPDATE `battle` SET flag_boi=0 WHERE id={$bid} LIMIT 1;");
	//вывод из боя игроков
if($boi['krov'] != 3)	$q = $db->query("update `users` set status=0,lastdate='{$t}',hptime='{$t}',manatime='{$t}' where boi_id={$bid};");

if($boi['krov'] == 3) $q = $db->query("update `users` set status=0,lastdate='{$t}',hptime='{$t}',manatime='{$t}',hpnow=0-hpmax*5 where boi_id={$bid};");
	require_once('inc/hpstring.php');
	knopka('loc.php', 'Бой завершен', 1);
	if(!empty($_SESSION['pkstr']))
		{
		msg2($_SESSION['pkstr']);
		unset($_SESSION['pkstr']);
		}
	$logboi = '';
	$q = $db->query("SELECT * FROM `battlelog` WHERE boi_id={$bid} ORDER BY id DESC LIMIT 3;");
	echo '<div class="board" style="text-align:left">';
	while ($stlog = $q->fetch_assoc()) echo $stlog['log'];
	echo '</div>';
	fin();
	}
//конец финиша

require_once('inc/hpstring.php');

if(($kom1sum > 1 and $me['komanda'] == 2) or ($kom2sum > 1 and $me['komanda'] == 1) or empty($me['sopernik']) or $me['hpnow'] < 0)
	{
	echo '<div class="board" style="text-align:left;">'.$komanda1.'VS<br/>'.$komanda2.'</div>';
	}
$q = $db->query("select id from `invent` where login='{$me['login']}' and flag_equip=1 and slot='sumka' limit 1;");
if($q->num_rows == 1) $sum = 1; else $sum = 0;
//кнопка ударить
if(!empty($me['sopernik']))
	{
	if($me['komanda'] == 1)
		{
		$col1 = $notice;
		$col2 = $male;
		}
	if($me['komanda'] == 2)
		{
		$col1 = $male;
		$col2 = $notice;
		}
	$q = $db->query("select * from `combat` where id='{$me['sopernik']}' limit 1;");
	$uz = $q->fetch_assoc();
	if(empty($bz['login2']))
		{
		$q = $db->query("update `battle` set login2='{$uz['login']}' where id={$bid} limit 1;");
		$bz['login'] = $uz['login'];
		}
	echo '<div class="board" style="text-align:left;">';
	echo '<span style="color:'.$col1.'"><b>'.$me['login'].'</b></span> ['.$me['lvl'].'] ('.$me['hpnow'].'/'.$me['hpmax'].') урон: '.$me['uron_boi'].'<br/><small>VS.</small><br/>';
	if($uz['flag_bot'] == 1)
		{
		echo '<span style="color:'.$col2.'"><b>'.$uz['login'].'</b></span> ['.$uz['lvl'].'] ('.$uz['hpnow'].'/'.$uz['hpmax'].') урон: '.$uz['uron_boi'];
		}
	else
		{
		$tajm = $curtime - $uz['time_udar'];
		$tajm = $hodtime - $tajm;
		if($tajm < 0) $tajm = 0;
		echo '<span style="color:'.$col2.'"><b><a href="infa.php?mod=uzinfa&lgn='.$uz['login'].'">'.$uz['login'].'</a></b></span> ['.$uz['lvl'].'] ('.$uz['hpnow'].'/'.$uz['hpmax'].') урон: '.$uz['uron_boi'].', тайм: '.$tajm;
		unset($tajm);
		}
	echo '</div>';
	if((empty($me['kuda_udar']) or empty($me['kuda_blok'])) and 0 < $me['hpnow'])
		{
		echo '<div class="board" style="text-align:left;">';
		echo '<form action="battle.php?r='.mt_rand(1111,9999).'" method="POST">';
		$rndblok = mt_rand(1,3);
		$catbl = array(1=>'',2=>'',3=>'');
		$catbl[$rndblok] = 'selected';
		echo '<select name="bl">';
		echo '<option value="2" '.$catbl[2].'>блок корпуса</option>';
		echo '<option value="1" '.$catbl[1].'>блок головы</option>';
		echo '<option value="3" '.$catbl[3].'>блок ног</option>';
		echo '</select><br>';
		$rndblok = mt_rand(1,3);
		$catud = array(1=>'',2=>'',3=>'');
		$catud[$rndblok] = 'selected';
		echo '<select name="ud">';
		echo '<option value="2" '.$catud[2].'>удар в корпус</option>';
		echo '<option value="1" '.$catud[1].'>удар в голову</option>';
		echo '<option value="3" '.$catud[3].'>удар по ногам</option>';
		echo '</select><br>';
		echo '<input type="submit" value="Удар"></form>';
		echo '</div>';
		}
	}
$tajm = $curtime - $me['time_udar'];
$tajm = $hodtime - $tajm;
if($tajm < 0) $tajm = 0;
echo '<div class="board3" style="text-align:left;">
<a href="battle.php?r='.mt_rand(1,999).'">Обновить (<b>'.$tajm.'</b>)</a> ';
if($ost == 0) echo '<br>- <a href="battle.php?mod=sbrospar">Сброс</a> ';
if($sum > 0 and $me['hpnow'] > 0) echo '<br>- <a href="battle.php?mod=sumka">Сумка</a> ';
if($me['manamax'] > 99) echo '<br>- <a href="battle.php?mod=magic">Магия</a> ';
if(!empty($_SESSION['pkstr']))
	{
	echo $_SESSION['pkstr'];
	unset($_SESSION['pkstr']);
	}
echo '</div>';
echo '<div class="board" style="text-align:left;">'.$logboi.'</div>';
echo '<div class="menu">';

echo '<a href="battle.php?mod=long">Длинный лог боя</a> ';
echo '- <a href="battle.php?mod=whowhere">Кто в бою</a> ';
echo '</div>';
fin();
?>
