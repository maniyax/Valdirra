case 'kuznica':
	$q = $db->query("select max(lvl) from `item`;");
	$a = $q->fetch_assoc();
	$max_lvl = $a['max(lvl)'];
	if (empty($start))
		{
		echo '<div class="board" style="text-align:left">';
		echo '<form action="zamok.php?mod=kuznica" method="GET">
		На какой уровень желаете собрать вещи?<br/>
		<select name="start">';
		for ($i = 6; $i <= $max_lvl; $i++) echo '<option value='.$i.'>'.$i.'</option>';
		echo '</select>';
		echo '<input type="submit" value="Далее"/></form>';
		fin();
		}
	if ($start < 6) $start = 6;
	if ($start > $max_lvl) $start = $max_lvl;
	if (empty($iid))
		{
		$it1 = $items->base_shmot(168); //тотем
		$it2 = $items->base_shmot(169); //брас
		$c = $start * 300;
		msg2('Вы хотите собрать вещи на '.$start.' уровень. Цена '.$c.' монет');
		knopka('zamok.php?mod=kuznica&start='.$start.'&iid=1', 'Амулет (с) (необходимо '.$it1['name'].')', 1);
		knopka('zamok.php?mod=kuznica&start='.$start.'&iid=2', 'Амулет (к) (необходимо '.$it1['name'].')', 1);
		knopka('zamok.php?mod=kuznica&start='.$start.'&iid=3', 'Амулет (у) (необходимо '.$it1['name'].')', 1);
		knopka('zamok.php?mod=kuznica&start='.$start.'&iid=4', 'Браслет (с) (необходимо '.$it2['name'].')', 1);
		knopka('zamok.php?mod=kuznica&start='.$start.'&iid=5', 'Браслет (к) (необходимо '.$it2['name'].')', 1);
		knopka('zamok.php?mod=kuznica&start='.$start.'&iid=6', 'Браслет (у) (необходимо '.$it2['name'].')', 1);
		fin();
		}
	$iid = intval($iid);
	if ($iid == 1) $num = $start * 3 - 17;
	elseif ($iid == 2) $num = $start * 3 - 16;
	elseif ($iid == 3) $num = $start * 3 - 15;
	elseif ($iid == 4) $num = $start * 3 + 43;
	elseif ($iid == 5) $num = $start * 3 + 44;
	elseif ($iid == 6) $num = $start * 3 + 45;
	else
		{
		$m = $f['lvl'] * 1000;
		$q = $db->query("update `users` set cu=cu-'{$m}' where id={$f['id']} limit 1;");
		msg2('Подменять ссылки нехорошо. С вас списан штраф '.$m.' монет за баловство.',1);
		}
	if ($f['cu'] < ($start * 300)) msg2('У вас не хватает денег!', 1);
	$item = $items->base_shmot($num);
	if ($item['equip'] == 'braslet') $zzz = 169;
	if ($item['equip'] == 'amulet') $zzz = 168;
	$itz = $items->base_shmot($zzz);
	if ($items->count_base_item($f['login'], $zzz) == 0) msg2('У вас нет '.$itz['name'], 1);
	if (empty($go))
		{
		msg2('Вы уверены, что хотите собрать '.$item['name'].'?');
		knopka('zamok.php?mod=kuznica&start='.$start.'&iid='.$iid.'&go=1', 'Собрать', 1);
		knopka('loc.php', 'В игру', 1);
		fin();
		}
	$items->del_base_item($f['login'], $zzz, 1);
	$items->add_item($f['login'], $num, 1);
	$f['cu'] -= ($start * 100);
	$q = $db->query("update `users` set cu={$f['cu']} where id={$f['id']} limit 1;");
	msg2('Вы перековали '.$itz['name'].' в '.$item['name'].'!');
break;

