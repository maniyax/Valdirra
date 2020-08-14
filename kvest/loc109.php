<?php
##############
# 24.12.2014 #
##############

if ($f['lvl'] < 4)
	{
	knopka('loc.php', 'Доступно с 4 уровня', 1);
	fin();
	}
if ($f['hpnow'] <= 0)
	{
	knopka('loc.php', 'Восстановите здоровье', 1);
	fin();
	}

msg2('<b>Кладбище животных</b>');

if (empty($ok))
	{
	knopka('kvest.php?ok=1', 'Осмотреть кости', 1);
	fin();
	}
$rnd = mt_rand(1, 100);
if ($rnd >= 1 and $rnd <= 25)
	{
//		if (empty($_SESSION['count']) or $_SESSION['count'] > 1) msg('Ошибка двойного нажатия',1);
//		$_SESSION['count']++;
	$items->add_item($f['login'], 165);
	msg2('Наконец вы нашли подходящий кусок кости и вырезали крючок!');
	}
elseif ($rnd >= 26 and $rnd <= 95)
	{
	msg2('Вы хорошо искали, но так не нашли подходящей по форме кости!');
	}
elseif ($rnd >= 96 and $rnd <= 100)
	{
	$q = $db->query("update `users` set hpnow=0-hpnow,mananow=0-mananow,hptime='{$t}',manatime='{$t}' where id='{$f['id']}' limit 1;");
	msg2('Вы укололись острой костью!');
	}
knopka('loc.php', 'Вернуться', 1);
fin();
?>
