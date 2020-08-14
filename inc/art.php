<?php
##############
# 24.12.2014 #
##############

//действия арт-эффектов
$art_uron = 0;
$art_hp = 0;
$art_mp = 0;
$s_art_uron = 0;
$s_art_hp = 0;
$s_art_mp = 0;
if(isset($me['art']['лечение']))
	{
	if(mt_rand(1, 100) <= 70 && $me['hpnow'] < $me['hpmax'])
		{
		$hp = mt_rand(ceil($me['lvl'] * 1.5), $me['lvl'] * 4 * $me['art']['лечение']);
		$me['hpnow'] += $hp;
		if($me['hpnow'] > $me['hpmax']) $me['hpnow'] = $me['hpmax'];
		$me['hpnow'] = $me['hpnow'];
		if($me['flag_bot'] == 0) $q = $db->query("update `users` set hpnow={$me['hpnow']} where login='{$me['login']}' limit 1;");
		$q = $db->query("update `combat` set hpnow={$me['hpnow']} where id='{$me['id']}' limit 1;");
		$art_hp += $hp;
		}
	}
if(isset($uz['art']['лечение']))
	{
	if(mt_rand(1, 100) <= 70 && $uz['hpnow'] < $uz['hpmax'])
		{
		$hp = mt_rand(ceil($uz['lvl'] * 1.5), $uz['lvl'] * 4 * $uz['art']['лечение']);
		$uz['hpnow'] += $hp;
		if($uz['hpnow'] > $uz['hpmax']) $uz['hpnow'] = $uz['hpmax'];
		if($uz['flag_bot'] == 0) $q = $db->query("update `users` set hpnow={$uz['hpnow']} where login='{$uz['login']}' limit 1;");
		$q = $db->query("update `combat` set hpnow={$uz['hpnow']} where id='{$uz['id']}' limit 1;");
		$s_art_hp += $hp;
		}
	}
if(isset($me['art']['исцеление']))
	{
	if(mt_rand(1, 100) <= 70 && $me['hpnow'] < $me['hpmax'])
		{
		$hp = mt_rand($me['lvl'] * 3, $me['lvl'] * 5 * $me['art']['исцеление']);
		$me['hpnow'] += $hp;
		if($me['hpnow'] > $me['hpmax']) $me['hpnow'] = $me['hpmax'];
		$me['hpnow'] = $me['hpnow'];
		if($me['flag_bot'] == 0) $q = $db->query("update `users` set hpnow={$me['hpnow']} where login='{$me['login']}' limit 1;");
		$q = $db->query("update `combat` set hpnow={$me['hpnow']} where id='{$me['id']}' limit 1;");
		$art_hp += $hp;
		}
	}
if(isset($uz['art']['исцеление']))
	{
	if(mt_rand(1, 100) <= 70 && $uz['hpnow'] < $uz['hpmax'])
		{
		$hp = mt_rand($uz['lvl'] * 3, $uz['lvl'] * 5 * $uz['art']['исцеление']);
		$uz['hpnow'] += $hp;
		if($uz['hpnow'] > $uz['hpmax']) $uz['hpnow'] = $uz['hpmax'];
		if($uz['flag_bot'] == 0) $q = $db->query("update `users` set hpnow='{$uz['hpnow']}' where login='{$uz['login']}' limit 1;");
		$q = $db->query("update `combat` set hpnow={$uz['hpnow']} where id='{$uz['id']}' limit 1;");
		$s_art_hp += $hp;
		}
	}
if(isset($me['art']['вампиризм']))
	{
	if(mt_rand(1, 100) <= 70)
		{
		$hp = mt_rand(ceil($me['lvl'] * 1.5), $me['lvl'] * 4 * $me['art']['вампиризм']);
		$me['hpnow'] += $hp;
		$uron_zaudar += $hp;
		if($me['hpnow'] > $me['hpmax']) $me['hpnow'] = $me['hpmax'];
		$me['hpnow'] = $me['hpnow'];
		if($me['flag_bot'] == 0) $q = $db->query("update `users` set hpnow={$me['hpnow']} where login='{$me['login']}' limit 1;");
		$q = $db->query("update `combat` set hpnow={$me['hpnow']} where id='{$me['id']}' limit 1;");
		$art_hp += $hp;
		$art_uron += $hp;
		}
	}
if(isset($uz['art']['вампиризм']))
	{
	if(mt_rand(1, 100) <= 70)
		{
		$hp = mt_rand(ceil($uz['lvl'] * 1.5), $uz['lvl'] * 4 * $uz['art']['вампиризм']);
		$uz['hpnow'] += $hp;
		$s_uron_zaudar += $hp;
		if($uz['hpnow'] > $uz['hpmax']) $uz['hpnow'] = $uz['hpmax'];
		if($uz['flag_bot'] == 0) $q = $db->query("update `users` set hpnow={$uz['hpnow']} where login='{$uz['login']}' limit 1;");
		$q = $db->query("update `combat` set hpnow={$uz['hpnow']} where id='{$uz['id']}' limit 1;");
		$s_art_hp += $hp;
		$s_art_uron += $hp;
		}
	}
if(isset($me['art']['огонь']))
	{
	if(mt_rand(1, 100) <= 60)
		{
		$uron = mt_rand($me['lvl'] * 3, $me['lvl'] * 5 * $me['art']['огонь']);
		$uron_zaudar += $uron;
		$art_uron += $uron;
		}
	}
if(isset($uz['art']['огонь']))
	{
	if(mt_rand(1, 100) <= 60)
		{
		$uron = mt_rand($uz['lvl'] * 3, $uz['lvl'] * 5 * $uz['art']['огонь']);
		$s_uron_zaudar += $uron;
		$s_art_uron += $uron;
		}
	}
if(isset($me['art']['пламя']))
	{
	if(mt_rand(1, 100) <= 70)
		{
		$uron = mt_rand($me['lvl'] * 5, $me['lvl'] * 8 * $me['art']['пламя']);
		$uron_zaudar += $uron;
		$art_uron += $uron;
		}
	}
if(isset($uz['art']['пламя']))
	{
	if(mt_rand(1, 100) <= 70)
		{
		$uron = mt_rand($uz['lvl'] * 5, $uz['lvl'] * 8 * $uz['art']['пламя']);
		$s_uron_zaudar += $uron;
		$s_art_uron += $uron;
		}
	}
if(isset($me['art']['ветер']))
	{
	if(mt_rand(1, 100) <= 85)
		{
		$uron = mt_rand($me['lvl'] * 3, $me['lvl'] * 5 * $me['art']['ветер']);
		$uron_zaudar += $uron;
		$art_uron += $uron;
		}
	}
if(isset($uz['art']['ветер']))
	{
	if(mt_rand(1, 100) <= 85)
		{
		$uron = mt_rand($me['lvl'] * 3, $uz['lvl'] * 5 * $uz['art']['ветер']);
		$s_uron_zaudar += $uron;
		$s_art_uron += $uron;
		}
	}
if(isset($me['art']['лед']))
	{
	if(mt_rand(1, 100) <= 70)
		{
		$uron = mt_rand($me['lvl'] * 3, $me['lvl'] * 5 * $me['art']['лед']);
		$uron_zaudar += $uron;
		$art_uron += $uron;
		}
	}
if(isset($uz['art']['лед']))
	{
	if(mt_rand(1, 100) <= 70)
		{
		$uron = mt_rand($me['lvl'] * 3, $uz['lvl'] * 5 * $uz['art']['лед']);
		$s_uron_zaudar += $uron;
		$s_art_uron += $uron;
		}
}

if(isset($me['art']['медитация']))
	{
	if(mt_rand(1, 100) <= 70 && $me['mananow'] < $me['manamax'])
		{
		$mp = mt_rand(ceil($me['lvl']/5), $me['lvl'] * $me['art']['медитация']);
		$me['mananow'] += $mp;
		if($me['mananow'] > $me['manamax']) $me['mananow'] = $me['manamax'];
		$me['mananow'] = $me['mananow'];
		if($me['flag_bot'] == 0) $q = $db->query("update `users` set mananow={$me['mananow']} where login='{$me['login']}' limit 1;");
		$q = $db->query("update `combat` set mananow={$me['mananow']} where id='{$me['id']}' limit 1;");
		$art_mp += $mp;
		}
	}
if(isset($uz['art']['медитация']))
	{
	if(mt_rand(1, 100) <= 70 && $uz['mananow'] < $uz['manamax'])
		{
		$mp = mt_rand(ceil($uz['lvl']/5), $uz['lvl'] * $uz['art']['Медитация']);
		$uz['mananow'] += $mp;
		if($uz['mananow'] > $uz['manamax']) $uz['mananow'] = $uz['manamax'];
		if($uz['flag_bot'] == 0) $q = $db->query("update `users` set mananow={$uz['mananow']} where login='{$uz['login']}' limit 1;");
		$q = $db->query("update `combat` set mananow={$uz['mananow']} where id='{$uz['id']}' limit 1;");
		$s_art_mp += $mp;
		}
	}


if(isset($me['art']['концентрация']))
	{
	if(mt_rand(1, 100) <= 91 && $me['mananow'] < $me['manamax'])
		{
		$mp = mt_rand(ceil($me['lvl']/10), $me['lvl']/2 * $me['art']['концентрация']);
		$me['mananow'] += $mp;
		if($me['mananow'] > $me['manamax']) $me['mananow'] = $me['manamax'];
		$me['mananow'] = $me['mananow'];
		if($me['flag_bot'] == 0) $q = $db->query("update `users` set mananow={$me['mananow']} where login='{$me['login']}' limit 1;");
		$q = $db->query("update `combat` set mananow={$me['mananow']} where id='{$me['id']}' limit 1;");
		$art_mp += $mp;
		}
	}
if(isset($uz['art']['концентрация']))
	{
	if(mt_rand(1, 100) <= 91 && $uz['mananow'] < $uz['manamax'])
		{
		$mp = mt_rand(ceil($uz['lvl']/10), $uz['lvl']/2 * $uz['art']['концентрация']);
		$uz['mananow'] += $mp;
		if($uz['mananow'] > $uz['manamax']) $uz['mananow'] = $uz['manamax'];
		if($uz['flag_bot'] == 0) $q = $db->query("update `users` set mananow={$uz['mananow']} where login='{$uz['login']}' limit 1;");
		$q = $db->query("update `combat` set mananow={$uz['mananow']} where id='{$uz['id']}' limit 1;");
		$s_art_mp += $mp;
		}
	}

//$art_log = '<b>'.$me['login'].'</b>, арт-урон: '.$art_uron.', арт-лечение: '.$art_hp.' / <b>'.$uz['login'].'</b>, арт-урон: '.$s_art_uron.', арт-лечение: '.$s_art_hp.'<br/>';
?>
