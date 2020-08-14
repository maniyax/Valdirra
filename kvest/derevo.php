<?php
###########################
# 15.05.2015 StalkerSleem #
###########################

if ($f['hpnow'] <= 0)
	{
	knopka('loc.php', 'Восстановите здоровье', 1);
	fin();
}



if ($f['p_lesorub'] == 0)
	{
	knopka('loc.php', 'Вы не знаете что делать с деревом...', 1);
	fin();
	}

if ($items->count_base_item($f['login'], 753) == 0){
msg2('Без топора тут не обойтись',1);
fin();
}

$kvest = unserialize($f['kvest']);
if($f['loc'] == 171) $res = 724;
if($f['loc'] == 183) $res = 723;
if($f['loc'] == 195) $res = 724;
if (empty($kvest['loc'.$f['loc']]))
	{
	$kvest['loc'.$f['loc']]['date'] = 0;
	$f['kvest'] = serialize($kvest);
	}
$time = $kvest['loc'.$f['loc']]['date'] - $_SERVER['REQUEST_TIME'];
$item = $items->base_shmot($res);
if ($time > 0) msg2($item['name'].' подрастёт через '.ceil($time / 60).' минут.', 1);
$kvest['loc'.$f['loc']]['date'] = $_SERVER['REQUEST_TIME'] + (60 * 60 * 10);
$items->add_item($f['login'], $res);
msg2('Вы срубили '.$item['name']);
$f['kvest'] = serialize($kvest);
if($f['rasa'] ==4){ $q = $db->query("update `users` set p_lesorub=p_lesorub+2,kvest='{$f['kvest']}',topor=topor-1 where id='{$f['id']}' limit 1;");}
else{$q = $db->query("update `users` set p_lesorub=p_lesorub+1,kvest='{$f['kvest']}',topor=topor-1 where id='{$f['id']}' limit 1;");}
knopka('loc.php', 'В игру', 1);
fin();
