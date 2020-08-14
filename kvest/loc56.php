<?php
##############
# 24.12.2014 #
##############

if ($f['hpnow'] <= 0)
	{
	knopka('loc.php', 'Восстановите здоровье', 1);
	fin();
	}
$kvest = unserialize($f['kvest']);
if (empty($kvest['loc56ks']))
	{
	$kvest['loc56ks']['date'] = 0;
	$kvest['loc56ks']['nagrada'] = 0;
	$kvest['loc56ks']['lg'] = 0;
	$kvest['loc56ks']['og'] = 0;
	$kvest['loc56ks']['kg'] = 0;
	$f['kvest'] = serialize($kvest);
	}

msg2('<b>Ксардас</b>');
if (empty($mod))
	{
	msg('- Приветствую тебя, '.$f['login'].'! Я Ксардас, один из самых сильных магов Вальдирры. Что привело тебя ко мне?');
	knopka('kvest.php?mod=rabota', '- Я насчет работы', 1);
	knopka('loc.php', '- Ой, мне пора', 1);
	fin();
	}
elseif ($mod == 'rabota')
	{
	if (empty($go))
		{
		msg('- Я предвидел твое появление в моей башне, '.$f['login'].'! Мне нужны сердца големов... Трех големов...');
		knopka('kvest.php?mod=rabota&go=1', '- Хорошо, я всё сделаю!', 1);
		knopka('kvest.php?mod=rabota&go=2', '- Вот сердца всех трех големов', 1);
		knopka('loc.php', 'Мне пора', 1);
		fin();
		}
	elseif ($go == 1)
		{
		if (date('d.m.Y', $kvest['loc56ks']['date']) == date('d.m.Y'))
			{
			if ($kvest['loc56ks']['nagrada'] == 0)
				{
				msg2('- Но я тебя уже послал за сердцами големов, почему ты еще здесь?', 1);
				}
			else
				{
				msg2('- Мне нужны сердца только раз в день. Приходи завтра.', 1);
				}
			}
		$kvest['loc56ks']['date'] = $_SERVER['REQUEST_TIME'];
		$kvest['loc56ks']['lg'] = 0;
		$kvest['loc56ks']['og'] = 0;
		$kvest['loc56ks']['kg'] = 0;
		$kvest['loc56ks']['nagrada'] = 0;
		$f['kvest'] = serialize($kvest);
		$q = $db->query("update `users` set kvest='{$f['kvest']}' where id={$f['id']} limit 1;");
		msg2('- Огненный голем находится немного восточнее моей башни, Ледяной голем стоит возле Свободной шахты, а Каменный голем охраняет мост к библиотеке.');
		knopka('loc.php', 'Мне пора', 1);
		fin();
		}
	elseif ($go == 2)
		{
		if ($kvest['loc56ks']['nagrada'] == 1) msg2('- Я тебя уже наградил, что ты хочешь еще от меня?', 1);
		if ($kvest['loc56ks']['lg'] == 0) msg2('- А где сердце Ледяного голема?', 1);
		if ($kvest['loc56ks']['og'] == 0) msg2('- А где сердце Огненного голема?', 1);
		if ($kvest['loc56ks']['kg'] == 0) msg2('- А где сердце Каменного голема?', 1);
		unset($kvest['loc56ks']);
		$kvest['loc56ks']['date'] = $_SERVER['REQUEST_TIME'];
		$kvest['loc56ks']['nagrada'] = 1;
		$f['kvest'] = serialize($kvest);
		$nagr = rand($f['lvl'] * 30, $f['lvl'] * 40);
		$f['cu'] += $nagr;
		if (0 <= $f['lvl'])
			{
			$itm1 = 153;
			$itm2 = 191;
			}
		if (5 <= $f['lvl'])
			{
			$itm1 = 154;
			$itm2 = 192;
			}
		if (10 <= $f['lvl'])
			{
			$itm1 = 155;
			$itm2 = 193;
			}
		if (15 <= $f['lvl'])
			{
			$itm1 = 156;
			$itm2 = 194;
			}
		$item1 = $items->base_shmot($itm1);
		$item2 = $items->base_shmot($itm2);
		$items->add_item($f['login'], $itm1);
		$items->add_item($f['login'], $itm2);
		$q = $db->query("update `users` set slava=slava+1,cu={$f['cu']},kvest='{$f['kvest']}' where id={$f['id']} limit 1;");
		$str = '- Спасибо тебе, '.$f['login'].', ты успешно ';
		if ($f['sex'] == 1) $str .= 'прошел';
		else $str .= 'прошла';
		$str .= ' испытания. Как я и обещал, вот твоя награда<br/>';
		$str .='[получено '.$nagr.' медных монет, '.$item1['name'].', '.$item2['name'].']';
		msg2($str);
		knopka('loc.php', 'В игру', 1);
		fin();
		}
	}
?>
