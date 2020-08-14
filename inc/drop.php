<?php
##############
# 24.12.2014 #
##############
if($uz['flag_bot'] == 1)
	{
	if($uz['login'] == 'Дед Мороз' or $uz['login'] == 'Снегурочка' or $uz['login'] == 'Снеговик')
		{
		$money = 100 + 5 * $me['lvl'];
		$udar_log = '<span style="color:'.$female.'">'.$me['login'].' находит мешочек с '.$money.' серебрянными монетами</span> <br/> '.$udar_log;
		$q = $db->query("update `users` set ag=ag+{$money} where login='{$me['login']}' limit 1;");
}

	if($uz['login'] == 'Зимний дух' && mt_rand(1, 100) <= 50)
		{
		$item = $items->base_shmot(772); // тотем гарпии
		if(!empty($me['klan'])) klan_points($me['klan'],1);
		$udar_log = '<span style="color:'.$female.'">'.$me['login'].' выбивает '.$item['name'].'</span> <br/> '.$udar_log;
		$items->add_item($me['login'], $item['id'], 1);
}



	if($uz['login'] == 'Зимний дух' && mt_rand(1, 100) <= 15)
		{
		$item = $items->base_shmot(773); // тотем гарпии
		if(!empty($me['klan'])) klan_points($me['klan'],1);
		$udar_log = '<span style="color:'.$female.'">'.$me['login'].' выбивает '.$item['name'].'</span> <br/> '.$udar_log;
		$items->add_item($me['login'], $item['id'], 1);
}

/*
	if($uz['login'] == 'Зимний дух' && mt_rand(1, 100) <= 10)
		{
		$item = $items->base_shmot(774); // тотем гарпии
		if(!empty($me['klan'])) klan_points($me['klan'],1);
		$udar_log = '<span style="color:'.$female.'">'.$me['login'].' выбивает '.$item['name'].'</span> <br/> '.$udar_log;
		$items->add_item($me['login'], $item['id'], 1);
}
*/

	if($uz['login'] == 'Гарпия' && mt_rand(1, 100) <= 20)
		{
		$item = $items->base_shmot(168); // тотем гарпии
		if(!empty($me['klan'])) klan_points($me['klan'],1);
		$udar_log = '<span style="color:'.$female.'">'.$me['login'].' выбивает '.$item['name'].'</span> <br/> '.$udar_log;
		$items->add_item($me['login'], $item['id'], 1);
}



	if($uz['login'] == 'Огненная ящерица' && mt_rand(1, 100) <= 10)
		{
		$item = $items->base_shmot(758); // тотем гарпии
		if(!empty($me['klan'])) klan_points($me['klan'],1);
		$udar_log = '<span style="color:'.$female.'">'.$me['login'].' выбивает '.$item['name'].'</span> <br/> '.$udar_log;
		$items->add_item($me['login'], $item['id'], 1);
}


	if($uz['login'] == 'Кабан' && mt_rand(1, 100) <= 50)
		{
		$item = $items->base_shmot(742); // тотем гарпии
		if(!empty($me['klan'])) klan_points($me['klan'],1);
		$udar_log = '<span style="color:'.$female.'">'.$me['login'].' выбивает '.$item['name'].'</span> <br/> '.$udar_log;
		$items->add_item($me['login'], $item['id'], 1);
}
	if($uz['login'] == 'Кабан' and $f['p_ohotnik'] >0 and $f['noj'] >0){
		$udar_log = '<span style="color:'.$female.'">'.$me['login'].' снимает шкуру с трупа кабана</span> <br/> '.$udar_log;
		$items->add_item($me['login'], 835, 1);
}


	if($uz['login'] == 'Волк' and $f['p_ohotnik'] >0 and $f['noj'] >0){
		$udar_log = '<span style="color:'.$female.'">'.$me['login'].' снимает шкуру с трупа волка</span> <br/> '.$udar_log;
		$items->add_item($me['login'], 836, 1);
}

	if($uz['login'] == 'Медведь' and $f['p_ohotnik'] >0 and $f['noj'] >0){
		$udar_log = '<span style="color:'.$female.'">'.$me['login'].' снимает шкуру с трупа медведя</span> <br/> '.$udar_log;
		$items->add_item($me['login'], 837, 1);
}


	if($uz['login'] == 'Болотожор' and $f['p_ohotnik'] >0 and $f['noj'] >0){
		$udar_log = '<span style="color:'.$female.'">'.$me['login'].' снимает шкуру с трупа болотожора</span> <br/> '.$udar_log;
		$items->add_item($me['login'], 838, 1);
}
	if($uz['login'] == 'Гитлер' && mt_rand(1, 100) <= 75)
		{
		$item = $items->base_shmot(622); // тотем гарпии
		if(!empty($me['klan'])) klan_points($me['klan'],1);
		$udar_log = '<span style="color:'.$female.'">'.$me['login'].' выбивает '.$item['name'].'</span> <br/> '.$udar_log;
		$items->add_item($me['login'], $item['id']);
		}

	if($uz['login'] == 'Разбойник' && mt_rand(1, 100) <= 15)
		{
		$item = $items->base_shmot(719); // обломок меча
		if(!empty($me['klan'])) klan_points($me['klan'],1);
		$udar_log = '<span style="color:'.$female.'">'.$me['login'].' выбивает '.$item['name'].'</span> <br/> '.$udar_log;
		$items->add_item($me['login'], $item['id'], 1);
		}

	if($uz['login'] == 'Ворюга' && mt_rand(1, 100) <= 50)
		{
		$item = $items->base_shmot(708); // Кв. предмет
		if(!empty($me['klan'])) klan_points($me['klan'],1);
		$udar_log = '<span style="color:'.$female.'">'.$me['login'].' выбивает '.$item['name'].'</span> <br/> '.$udar_log;
		$items->add_item($me['login'], $item['id'], 1);
		}

	if($uz['login'] == 'Мясной жук' && mt_rand(1, 100) <= 25)
		{
		$item = $items->base_shmot(710); // Кв. предмет
		if(!empty($me['klan'])) klan_points($me['klan'],1);
		$udar_log = '<span style="color:'.$female.'">'.$me['login'].' выбивает '.$item['name'].'</span> <br/> '.$udar_log;
		$items->add_item($me['login'], $item['id'], 1);
		}




	if($uz['login'] == 'Ползун' && mt_rand(1, 100) <= 20)
		{
		$item = $items->base_shmot(169); // сломаный брас
		if(!empty($me['klan'])) klan_points($me['klan'],1);
		$udar_log = '<span style="color:'.$female.'">'.$me['login'].' выбивает '.$item['name'].'</span> <br/> '.$udar_log;
		$items->add_item($me['login'], $item['id'], 1);
		}
	if($uz['login'] == 'Остер' && mt_rand(1, 100) <= 20)
		{
		$item = $items->base_shmot(162);	// руна 1
		if(!empty($me['klan'])) klan_points($me['klan'],1);
		$udar_log = '<span style="color:'.$female.'">'.$me['login'].' выбивает '.$item['name'].'</span> <br/> '.$udar_log;
		$items->add_item($me['login'], $item['id'], 1);
		}
	if($uz['login'] == 'Черный гоблин' && mt_rand(1, 100) <= 20)
		{
		$item = $items->base_shmot(161); // руна 2
		if(!empty($me['klan'])) klan_points($me['klan'],1);
		$udar_log = '<span style="color:'.$female.'">'.$me['login'].' выбивает '.$item['name'].'</span> <br/> '.$udar_log;
		$items->add_item($me['login'], $item['id'], 1);
		}
	if($uz['login'] == 'Падальщик' && mt_rand(1, 100) <= 20)
		{
		$item = $items->base_shmot(160); // руна 3
		if(!empty($me['klan'])) klan_points($me['klan'],1);
		$udar_log = '<span style="color:'.$female.'">'.$me['login'].' выбивает '.$item['name'].'</span> <br/> '.$udar_log;
		$items->add_item($me['login'], $item['id'], 1);
		}
	if($uz['login'] == 'Кротокрыс' && mt_rand(1, 100) <= 20)
		{
		$item = $items->base_shmot(159); // руна 4
		if(!empty($me['klan'])) klan_points($me['klan'],1);
		$udar_log = '<span style="color:'.$female.'">'.$me['login'].' выбивает '.$item['name'].'</span> <br/> '.$udar_log;
		$items->add_item($me['login'], $item['id'], 1);
		}
	if($uz['login'] == 'Орк-Маг' && mt_rand(1, 100) <= 20)
		{
		$item = $items->base_shmot(158); // руна 5
		if(!empty($me['klan'])) klan_points($me['klan'],1);
		$udar_log = '<span style="color:'.$female.'">'.$me['login'].' выбивает '.$item['name'].'</span> <br/> '.$udar_log;
		$items->add_item($me['login'], $item['id'], 1);
		}
	if($uz['login'] == 'Жук-шелкопряд' && mt_rand(1, 100) <= 40)
		{
		$item = $items->base_shmot(164);	// шелковая нить
		if(!empty($me['klan'])) klan_points($me['klan'],1);
		$udar_log = '<span style="color:'.$female.'">'.$me['login'].' выбивает '.$item['name'].'</span> <br/> '.$udar_log;
		$items->add_item($me['login'], $item['id'], 1);
		}
	if($uz['login'] == 'Ледяной голем')
		{
		$kvest = unserialize($f['kvest']);
		$kvest['loc56ks']['lg'] = 1;
		$f['kvest'] = serialize($kvest);
		$udar_log = '<span style="color:'.$female.'">'.$me['login'].' выбивает Сердце ледяного голема</span> <br/> '.$udar_log;
		$q = $db->query("update `users` set kvest='{$f['kvest']}' where id={$f['id']} limit 1;");
		}
	if($uz['login'] == 'Огненный голем')
		{
		$kvest = unserialize($f['kvest']);
		$kvest['loc56ks']['og'] = 1;
		$f['kvest'] = serialize($kvest);
		$udar_log = '<span style="color:'.$female.'">'.$me['login'].' выбивает Сердце огненного голема</span> <br/> '.$udar_log;
		$q = $db->query("update `users` set kvest='{$f['kvest']}' where id={$f['id']} limit 1;");
		}
	if($uz['login'] == 'Каменный голем')
		{
		$kvest = unserialize($f['kvest']);
		$kvest['loc56ks']['kg'] = 1;
		$f['kvest'] = serialize($kvest);
		$udar_log = '<span style="color:'.$female.'">'.$me['login'].' выбивает Сердце каменного голема</span> <br/> '.$udar_log;
		$q = $db->query("update `users` set kvest='{$f['kvest']}' where id={$f['id']} limit 1;");
		}
	if($uz['login'] == 'Тролль')
		{
		$af = $q = $db->query("select login from `combat` where boi_id={$me['boi_id']} and uron_boi>999 and flag_bot=0 and komanda=2;");
		$l = array();
		while($logins = $af->fetch_assoc())
			{
			$l[] = $logins['login'];
			}
		shuffle($l);
		$winner = $l[0];
		$item = $items->base_shmot(127); // точильный камень
		$udar_log = '<span style="color:'.$female.'">'.$winner.' подбирает с распростертого тролля '.$item['name'].'</span> <br/> '.$udar_log;
		$items->add_item($me['login'], $item['id'], 1);
}


	if($uz['login'] == 'черный дракон' && mt_rand(1, 100) <= 10)
		{
		$af = $q = $db->query("select login from `combat` where boi_id={$me['boi_id']} and uron_boi>999 and flag_bot=0 and komanda=2;");
		$l = array();
		while($logins = $af->fetch_assoc())
			{
			$l[] = $logins['login'];
			}
		shuffle($l);
		$winner = $l[0];
		$item = $items->base_shmot(123); // латерейный билет
		$udar_log = '<span style="color:'.$female.'">'.$winner.' подбирает с распростертого черного дракона '.$item['name'].'</span> <br/> '.$udar_log;
		$items->add_item($me['login'], $item['id'], 1);
}
if($uz['login'] == 'черный дракон'){
$q = $db->query("update `users` set au=au+2 where boi_id={$bid};");
msg2('Все игроки получили по 2 золотые монеты');
		}

	if(mt_rand(1, 100) == 12)

		{
		$item = $items->base_shmot(121); // свиток нападения
		if(!empty($me['klan'])) klan_points($me['klan'],1);
		$udar_log = '<span style="color:'.$female.'">'.$me['login'].' выбивает '.$item['name'].'</span> <br/> '.$udar_log;
		$items->add_item($me['login'], $item['id'], 1);
		}
	if(mt_rand(1, 100) == 45)
		{
		$item = $items->base_shmot(122); // свиток развоплощения
		if(!empty($me['klan'])) klan_points($me['klan'],1);
		$udar_log = '<span style="color:'.$female.'">'.$me['login'].' выбивает '.$item['name'].'</span> <br/> '.$udar_log;
		$items->add_item($me['login'], $item['id'], 1);
}

/*
if($uz['login'] == 'Гринч Вальдирры'){
$q = $db->query("update `users` set slava=slava+100,au=au+50 where boi_id={$bid};");
msg2('Вы получили 100 очков известности и 50 золотых монет!');
}
*/

if($uz['login'] == 'Злой снеговик' && mt_rand(1, 100) <= 45)
		{
		$item = $items->base_shmot(774);
		if(!empty($me['klan'])) klan_points($me['klan'],1);
		$udar_log = '<span style="color:'.$female.'">'.$me['login'].' выбивает '.$item['name'].'</span> <br/> '.$udar_log;
		$items->add_item($me['login'], $item['id'], 1);
}


	if($uz['login'] == 'Бродяга' && mt_rand(1, 100) <= 20)
		{
		$item = $items->base_shmot(766); // тотем гарпии
		if(!empty($me['klan'])) klan_points($me['klan'],1);
		$udar_log = '<span style="color:'.$female.'">'.$me['login'].' выбивает '.$item['name'].'</span> <br/> '.$udar_log;
		$items->add_item($me['login'], $item['id'], 1);
}

	if($uz['login'] == 'Бродяга' && mt_rand(1, 100) <= 20)
		{
		$item = $items->base_shmot(769); // тотем гарпии
		if(!empty($me['klan'])) klan_points($me['klan'],1);
		$udar_log = '<span style="color:'.$female.'">'.$me['login'].' выбивает '.$item['name'].'</span> <br/> '.$udar_log;
		$items->add_item($me['login'], $item['id'], 1);
}

	if($uz['login'] == 'Бродяга' && mt_rand(1, 100) <= 20)
		{
		$item = $items->base_shmot(771); // тотем гарпии
		if(!empty($me['klan'])) klan_points($me['klan'],1);
		$udar_log = '<span style="color:'.$female.'">'.$me['login'].' выбивает '.$item['name'].'</span> <br/> '.$udar_log;
		$items->add_item($me['login'], $item['id'], 1);
}



if($uz['login'] == 'Сбежавший рудокоп' && mt_rand(1, 100) <= 5)
		{
		$item = $items->base_shmot(mt_rand(726, 734));
		if(!empty($me['klan'])) klan_points($me['klan'],1);
		$udar_log = '<span style="color:'.$female.'">'.$me['login'].' выбивает '.$item['name'].'</span> <br/> '.$udar_log;
		$items->add_item($me['login'], $item['id'], 1);
}



}
?>
