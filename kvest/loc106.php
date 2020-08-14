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
msg2('<b>Огромное дерево</b>');

if (empty($ok))
	{
	knopka('kvest.php?ok=1', 'Залезть на дерево', 1);
	fin();
	}
$rnd = mt_rand(1, 100);
if ($rnd >= 1 and $rnd <= 25)
	{
	$items->add_item($f['login'], 163);
	msg2('Вы успешно срезали прут с дерева!');
	}
elseif ($rnd >= 26 and $rnd <= 95)
	{
	msg2('Вы чуть не упали с дерева и не смогли срезать прут!');
	}
elseif ($rnd >= 96 and $rnd <= 100)
	{
	$q = $db->query("update `users` set hpnow=0-hpnow,mananow=0-mananow,hptime='{$t}',manatime='{$t}' where id={$f['id']} limit 1;");
	msg2('Вы упали с дерева!');
	}
knopka('loc.php', 'Вернуться', 1);
fin();
?>
