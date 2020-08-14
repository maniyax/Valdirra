<?php
##############
# 24.12.2014 #
##############

$q = $db->query("select id from `invent` where login='{$me['login']}' and flag_arenda=0 and flag_rinok=0 and flag_equip=1 and slot='sumka' limit 1;");
if($q->num_rows == 0) msg2('У вас ничего нет в сумке',1);
$sum = $q->fetch_assoc();
$log_hp = '';
$item = $items->shmot($sum['id']);
switch($item['ido']):
case 153:
	$regen = 50;
	if($regen + $me['hpnow'] > $me['hpmax']) $regen = $me['hpmax'] - $me['hpnow'];
	$me['hpnow'] += $regen;
	$log_hp = '<span style="color:'.$notice.'">'.$me['login'].' использует '.$item['name'].'</span><br/>';
	$q = $db->query("delete from `invent` where login='{$me['login']}' and id={$sum['id']} and flag_arenda=0 and flag_rinok=0 and flag_equip=1 and slot='sumka' limit 1;");
	$q = $db->query("update `users` set hpnow={$me['hpnow']} where id={$f['id']} limit 1;");
	$q = $db->query("update `combat` set hpnow={$me['hpnow']} where id={$me['id']} limit 1;");
break;

case 154:
	$regen = 100;
	if($regen + $me['hpnow'] > $me['hpmax']) $regen = $me['hpmax'] - $me['hpnow'];
	$me['hpnow'] += $regen;
	$log_hp = '<span style="color:'.$notice.'">'.$me['login'].' использует '.$item['name'].'</span><br/>';
	$q = $db->query("delete from `invent` where login='{$me['login']}' and id={$sum['id']} and flag_arenda=0 and flag_rinok=0 and flag_equip=1 and slot='sumka' limit 1;");
	$q = $db->query("update `users` set hpnow={$me['hpnow']} where id={$f['id']} limit 1;");
	$q = $db->query("update `combat` set hpnow={$me['hpnow']} where id={$me['id']} limit 1;");
break;

case 714:
	$regen = 800;
	if($regen + $me['hpnow'] > $me['hpmax']) $regen = $me['hpmax'] - $me['hpnow'];
	$me['hpnow'] += $regen;
	$log_hp = '<span style="color:'.$notice.'">'.$me['login'].' использует '.$item['name'].'</span><br/>';
	$q = $db->query("delete from `invent` where login='{$me['login']}' and id={$sum['id']} and flag_arenda=0 and flag_rinok=0 and flag_equip=1 and slot='sumka' limit 1;");
	$q = $db->query("update `users` set hpnow={$me['hpnow']} where id={$f['id']} limit 1;");
	$q = $db->query("update `combat` set hpnow={$me['hpnow']} where id={$me['id']} limit 1;");
break;

case 155:
	$regen = 150;
	if($regen + $me['hpnow'] > $me['hpmax']) $regen = $me['hpmax'] - $me['hpnow'];
	$me['hpnow'] += $regen;
	$log_hp = '<span style="color:'.$notice.'">'.$me['login'].' использует '.$item['name'].'</span><br/>';
	$q = $db->query("delete from `invent` where login='{$me['login']}' and id={$sum['id']} and flag_arenda=0 and flag_rinok=0 and flag_equip=1 and slot='sumka' limit 1;");
	$q = $db->query("update `users` set hpnow={$me['hpnow']} where id={$f['id']} limit 1;");
	$q = $db->query("update `combat` set hpnow={$me['hpnow']} where id={$me['id']} limit 1;");
break;

case 156:
	$regen = 250;
	if($regen + $me['hpnow'] > $me['hpmax']) $regen = $me['hpmax'] - $me['hpnow'];
	$me['hpnow'] += $regen;
	$log_hp = '<span style="color:'.$notice.'">'.$me['login'].' использует '.$item['name'].'</span><br/>';
	$q = $db->query("delete from `invent` where login='{$me['login']}' and id={$sum['id']} and flag_arenda=0 and flag_rinok=0 and flag_equip=1 and slot='sumka' limit 1;");
	$q = $db->query("update `users` set hpnow={$me['hpnow']} where id={$f['id']} limit 1;");
	$q = $db->query("update `combat` set hpnow={$me['hpnow']} where id={$me['id']} limit 1;");
break;

case 625:
	$regen = 350;
	if($regen + $me['hpnow'] > $me['hpmax']) $regen = $me['hpmax'] - $me['hpnow'];
	$me['hpnow'] += $regen;
	$log_hp = '<span style="color:'.$notice.'">'.$me['login'].' использует '.$item['name'].'</span><br/>';
	$q = $db->query("delete from `invent` where login='{$me['login']}' and id={$sum['id']} and flag_arenda=0 and flag_rinok=0 and flag_equip=1 and slot='sumka' limit 1;");
	$q = $db->query("update `users` set hpnow={$me['hpnow']} where id={$f['id']} limit 1;");
	$q = $db->query("update `combat` set hpnow={$me['hpnow']} where id={$me['id']} limit 1;");
break;

case 626:
	$regen = 500;
	if($regen + $me['hpnow'] > $me['hpmax']) $regen = $me['hpmax'] - $me['hpnow'];
	$me['hpnow'] += $regen;
	$log_hp = '<span style="color:'.$notice.'">'.$me['login'].' использует '.$item['name'].'</span><br/>';
	$q = $db->query("delete from `invent` where login='{$me['login']}' and id={$sum['id']} and flag_arenda=0 and flag_rinok=0 and flag_equip=1 and slot='sumka' limit 1;");
	$q = $db->query("update `users` set hpnow={$me['hpnow']} where id={$f['id']} limit 1;");
	$q = $db->query("update `combat` set hpnow={$me['hpnow']} where id={$me['id']} limit 1;");
break;

case 627:
	$regen = 750;
	if($regen + $me['hpnow'] > $me['hpmax']) $regen = $me['hpmax'] - $me['hpnow'];
	$me['hpnow'] += $regen;
	$log_hp = '<span style="color:'.$notice.'">'.$me['login'].' использует '.$item['name'].'</span><br/>';
	$q = $db->query("delete from `invent` where login='{$me['login']}' and id={$sum['id']} and flag_arenda=0 and flag_rinok=0 and flag_equip=1 and slot='sumka' limit 1;");
	$q = $db->query("update `users` set hpnow={$me['hpnow']} where id={$f['id']} limit 1;");
	$q = $db->query("update `combat` set hpnow={$me['hpnow']} where id={$me['id']} limit 1;");
break;

case 628:
	$regen = 1000;
	if($regen + $me['hpnow'] > $me['hpmax']) $regen = $me['hpmax'] - $me['hpnow'];
	$me['hpnow'] += $regen;
	$log_hp = '<span style="color:'.$notice.'">'.$me['login'].' использует '.$item['name'].'</span><br/>';
	$q = $db->query("delete from `invent` where login='{$me['login']}' and id={$sum['id']} and flag_arenda=0 and flag_rinok=0 and flag_equip=1 and slot='sumka' limit 1;");
	$q = $db->query("update `users` set hpnow={$me['hpnow']} where id={$f['id']} limit 1;");
	$q = $db->query("update `combat` set hpnow={$me['hpnow']} where id={$me['id']} limit 1;");
break;

case 629:
	$regen = 1500;
	if($regen + $me['hpnow'] > $me['hpmax']) $regen = $me['hpmax'] - $me['hpnow'];
	$me['hpnow'] += $regen;
	$log_hp = '<span style="color:'.$notice.'">'.$me['login'].' использует '.$item['name'].'</span><br/>';
	$q = $db->query("delete from `invent` where login='{$me['login']}' and id={$sum['id']} and flag_arenda=0 and flag_rinok=0 and flag_equip=1 and slot='sumka' limit 1;");
	$q = $db->query("update `users` set hpnow={$me['hpnow']} where id={$f['id']} limit 1;");
	$q = $db->query("update `combat` set hpnow={$me['hpnow']} where id={$me['id']} limit 1;");
break;

case 191:
	$regen = 50;
	if($regen + $me['mananow'] > $me['manamax']) $regen = $me['manamax'] - $me['mananow'];
	$me['mananow'] += $regen;
	$log_hp = '<span style="color:'.$notice.'">'.$me['login'].' использует '.$item['name'].'</span><br/>';
	$q = $db->query("delete from `invent` where login='{$me['login']}' and id={$sum['id']} and flag_arenda=0 and flag_rinok=0 and flag_equip=1 and slot='sumka' limit 1;");
	$q = $db->query("update `users` set mananow={$me['mananow']} where id={$f['id']} limit 1;");
	$q = $db->query("update `combat` set mananow={$me['mananow']} where id={$me['id']} limit 1;");
break;

case 192:
	$regen = 100;
	if($regen + $me['mananow'] > $me['manamax']) $regen = $me['manamax'] - $me['mananow'];
	$me['mananow'] += $regen;
	$log_hp = '<span style="color:'.$notice.'">'.$me['login'].' использует '.$item['name'].'</span><br/>';
	$q = $db->query("delete from `invent` where login='{$me['login']}' and id={$sum['id']} and flag_arenda=0 and flag_rinok=0 and flag_equip=1 and slot='sumka' limit 1;");
	$q = $db->query("update `users` set mananow={$me['mananow']} where id={$f['id']} limit 1;");
	$q = $db->query("update `combat` set mananow={$me['mananow']} where id={$me['id']} limit 1;");
break;

case 193:
	$regen = 150;
	if($regen + $me['mananow'] > $me['manamax']) $regen = $me['manamax'] - $me['mananow'];
	$me['mananow'] += $regen;
	$log_hp = '<span style="color:'.$notice.'">'.$me['login'].' использует '.$item['name'].'</span><br/>';
	$q = $db->query("delete from `invent` where login='{$me['login']}' and id={$sum['id']} and flag_arenda=0 and flag_rinok=0 and flag_equip=1 and slot='sumka' limit 1;");
	$q = $db->query("update `users` set mananow={$me['mananow']} where id={$f['id']} limit 1;");
	$q = $db->query("update `combat` set mananow={$me['mananow']} where id={$me['id']} limit 1;");
break;

case 194:
	$regen = 250;
	if($regen + $me['mananow'] > $me['manamax']) $regen = $me['manamax'] - $me['mananow'];
	$me['mananow'] += $regen;
	$log_hp = '<span style="color:'.$notice.'">'.$me['login'].' использует '.$item['name'].'</span><br/>';
	$q = $db->query("delete from `invent` where login='{$me['login']}' and id={$sum['id']} and flag_arenda=0 and flag_rinok=0 and flag_equip=1 and slot='sumka' limit 1;");
	$q = $db->query("update `users` set mananow={$me['mananow']} where id={$f['id']} limit 1;");
	$q = $db->query("update `combat` set mananow={$me['mananow']} where id={$me['id']} limit 1;");
break;

case 630:
	$regen = 350;
	if($regen + $me['mananow'] > $me['manamax']) $regen = $me['manamax'] - $me['mananow'];
	$me['mananow'] += $regen;
	$log_hp = '<span style="color:'.$notice.'">'.$me['login'].' использует '.$item['name'].'</span><br/>';
	$q = $db->query("delete from `invent` where login='{$me['login']}' and id={$sum['id']} and flag_arenda=0 and flag_rinok=0 and flag_equip=1 and slot='sumka' limit 1;");
	$q = $db->query("update `users` set mananow={$me['mananow']} where id={$f['id']} limit 1;");
	$q = $db->query("update `combat` set mananow={$me['mananow']} where id={$me['id']} limit 1;");
break;

case 631:
	$regen = 500;
	if($regen + $me['mananow'] > $me['manamax']) $regen = $me['manamax'] - $me['mananow'];
	$me['mananow'] += $regen;
	$log_hp = '<span style="color:'.$notice.'">'.$me['login'].' использует '.$item['name'].'</span><br/>';
	$q = $db->query("delete from `invent` where login='{$me['login']}' and id={$sum['id']} and flag_arenda=0 and flag_rinok=0 and flag_equip=1 and slot='sumka' limit 1;");
	$q = $db->query("update `users` set mananow={$me['mananow']} where id={$f['id']} limit 1;");
	$q = $db->query("update `combat` set mananow={$me['mananow']} where id={$me['id']} limit 1;");
break;

case 632:
	$regen = 750;
	if($regen + $me['mananow'] > $me['manamax']) $regen = $me['manamax'] - $me['mananow'];
	$me['mananow'] += $regen;
	$log_hp = '<span style="color:'.$notice.'">'.$me['login'].' использует '.$item['name'].'</span><br/>';
	$q = $db->query("delete from `invent` where login='{$me['login']}' and id={$sum['id']} and flag_arenda=0 and flag_rinok=0 and flag_equip=1 and slot='sumka' limit 1;");
	$q = $db->query("update `users` set mananow={$me['mananow']} where id={$f['id']} limit 1;");
	$q = $db->query("update `combat` set mananow={$me['mananow']} where id={$me['id']} limit 1;");
break;

case 633:
	$regen = 1000;
	if($regen + $me['mananow'] > $me['manamax']) $regen = $me['manamax'] - $me['mananow'];
	$me['mananow'] += $regen;
	$log_hp = '<span style="color:'.$notice.'">'.$me['login'].' использует '.$item['name'].'</span><br/>';
	$q = $db->query("delete from `invent` where login='{$me['login']}' and id={$sum['id']} and flag_arenda=0 and flag_rinok=0 and flag_equip=1 and slot='sumka' limit 1;");
	$q = $db->query("update `users` set mananow={$me['mananow']} where id={$f['id']} limit 1;");
	$q = $db->query("update `combat` set mananow={$me['mananow']} where id={$me['id']} limit 1;");
break;

case 634:
	$regen = 1500;
	if($regen + $me['mananow'] > $me['manamax']) $regen = $me['manamax'] - $me['mananow'];
	$me['mananow'] += $regen;
	$log_hp = '<span style="color:'.$notice.'">'.$me['login'].' использует '.$item['name'].'</span><br/>';
	$q = $db->query("delete from `invent` where login='{$me['login']}' and id={$sum['id']} and flag_arenda=0 and flag_rinok=0 and flag_equip=1 and slot='sumka' limit 1;");
	$q = $db->query("update `users` set mananow={$me['mananow']} where id={$f['id']} limit 1;");
	$q = $db->query("update `combat` set mananow={$me['mananow']} where id={$me['id']} limit 1;");
break;

case 157:
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
		echo '<form action="battle.php?mod=sumka" method="POST">';
		echo '<select name="lgn">';
		foreach($kom3 as $key => $val) echo '<option value="'.$key.'">'.$val.'</option>';
		echo '</select>';
		echo '<br/>';
		echo '<input type="submit" value="Молния судьбы"></form></div>';
		knopka('battle.php', 'Вернуться', 1);
		fin();
		}
	$lgn = intval($lgn);	//эта переменная - ид бойца из таблицы combat
	if(empty($lgn) or $lgn <= 0) msg('Не выбран соперник для удара!',1);
	$q = $db->query("select * from `combat` where id={$lgn} and boi_id={$bid} limit 1;");
	$hz = $q->fetch_assoc() or msg2('Боец не найден!',1);
	if($hz['komanda'] == $me['komanda']) msg2('Против своей команды нельзя',1);
	if($hz['hpnow'] <= 0) msg2('Противник уже убит', 1);
	$log_hp = '<span style="color:'.$notice.'">ветвистый удар молнии с треском бьет в '.$hz['login'].', оставляя 1 ХП</span><br/>';
	$q = $db->query("update `combat` set hpnow=1,time_udar='{$t}' where id={$hz['id']} and hpnow>1 limit 1;");
	$q = $db->query("update `combat` set time_udar='{$t}' where login={$me['login']} limit 1;");
	if($hz['flag_bot'] == 0) $q = $db->query("update `users` set hpnow=1 where login={$hz['login']} and hpnow>1 limit 1;");
	$q = $db->query("delete from `invent` where login='{$me['login']}' and id={$sum['id']} and flag_arenda=0 and flag_rinok=0 and flag_equip=1 and slot='sumka' limit 1;");
break;





case 772:
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
		echo '<form action="battle.php?mod=sumka" method="POST">';
		echo '<select name="lgn">';
		foreach($kom3 as $key => $val) echo '<option value="'.$key.'">'.$val.'</option>';
		echo '</select>';
		echo '<br/>';
		echo '<input type="submit" value="Хлопушка"></form></div>';
		knopka('battle.php', 'Вернуться', 1);
		fin();
		}
	$lgn = intval($lgn);	//эта переменная - ид бойца из таблицы combat
	if(empty($lgn) or $lgn <= 0) msg('Не выбран соперник для удара!',1);
	$q = $db->query("select * from `combat` where id={$lgn} and boi_id={$bid} limit 1;");
	$hz = $q->fetch_assoc() or msg2('Боец не найден!',1);
	if($hz['komanda'] == $me['komanda']) msg2('Против своей команды нельзя',1);
	if($hz['hpnow'] <= 0) msg2('Противник уже убит', 1);
$rand = mt_rand(500, 3000);
	$log_hp = '<span style="color:'.$notice.'">хлопушка с треском взрывается, нанося '.$hz['login'].' '.$rand.' урона</span><br/>';
	$q = $db->query("update `combat` set hpnow=hpnow-{$rand},time_udar='{$t}' where id={$hz['id']} and hpnow>1 limit 1;");
	$q = $db->query("update `combat` set time_udar='{$t}' where login={$me['login']} limit 1;");
	if($hz['flag_bot'] == 0) $q = $db->query("update `users` set hpnow=hpnow-{$rand} where login={$hz['login']} and hpnow>1 limit 1;");
	$q = $db->query("delete from `invent` where login='{$me['login']}' and id={$sum['id']} and flag_arenda=0 and flag_rinok=0 and flag_equip=1 and slot='sumka' limit 1;");
break;


default: msg('Неизвестная ошибка',1); break;
endswitch;
if(!empty($log_hp)) $q = $db->query("insert into `battlelog` values (0,{$bid},'{$t}','{$log_hp}');");
?>
