<?php
##############
# 24.12.2014 #
##############

if ($f['lvl'] < 4)
	{
	knopka('loc.php', 'Доступно с 4 уровня', 1);
	fin();
	}
msg2('<b>Навозная куча</b>');
if (empty($ok))
	{
	knopka('kvest.php?ok=1', 'Поискать вокруг кучи', 1);
	fin();
	}
$rnd = mt_rand(1, 100);
$itm = 0;
if ($rnd >= 1 and $rnd <= 3) $itm = 166;   // 1-3% - гусеница 3%
elseif ($rnd >= 4 and $rnd <= 8) $itm = 167;  // 4-8% - мотыль 5%
elseif ($rnd >= 9 and $rnd <= 15) $itm = 170;  // 9-15% - опарыш 7%
elseif ($rnd >= 16 and $rnd <= 25) $itm = 171; // 16-25% - кузнечик 10%
elseif ($rnd >= 26 and $rnd <= 37) $itm = 172; // 26-37% - муха 12%
elseif ($rnd >= 38 and $rnd <= 50) $itm = 173; // 38-50% - червяк 13%
// если нашли наживку
if (!empty($itm))
	{
//		if (empty($_SESSION['count']) or $_SESSION['count'] > 1) msg('Ошибка двойного нажатия',1);
//		$_SESSION['count']++;
	$item = $items->base_shmot($itm);
	$items->add_item($f['login'], $itm);
	msg2('Порыскав вокруг кучи, вы нашли наживку!<br/>[Найдено: '.$item['name'].']');
	}
else msg2('Вы ничего не нашли!');
knopka('loc.php', 'Вернуться', 1);
fin();
?>
