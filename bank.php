<?php
##############
# 24.12.2014 #
##############

require_once('inc/top.php');
require_once('inc/check.php');	// вход в игру
require_once('inc/head.php');
require_once('class/items.php');
if($f['hpnow'] <= 0)
	{
	knopka('loc.php', 'Восстановите здоровье', 1);
	fin();
	}
$mod = isset($_REQUEST['mod']) ? $_REQUEST['mod'] : '';
$ok = isset($_REQUEST['ok']) ? $_REQUEST['ok'] : '';
$summa = isset($_REQUEST['summa']) ? $_REQUEST['summa'] : 0;
$tip = isset($_REQUEST['tip']) ? $_REQUEST['tip'] : 0;
$iid = isset($_REQUEST['iid']) ? intval($_REQUEST['iid']) : 0;
$start = isset($_REQUEST['start']) ? intval($_REQUEST['start']) : 0;

if($f['loc'] != 149)
	{
	knopka('loc.php', 'Ошибка локации');
	fin();
	}

if(!empty($_SERVER['auth'])) require_once('inc/hpstring.php');
$s = 'Ваши монеты: медные '.$f['cu'].' | серебряные '.$f['ag'].' | золотые '.$f['au'].'';
if(!empty($f['bank'])) $s .= ' | Ваш вклад: '.$f['bank'].' от '.Date('d.m.Y H:i',$f['bankdate']);
msg2($s);
if(empty($mod))
	{
	msg2('В банке вы можете хранить ваши медные монеты. Процент начисляется, если у вас на счету не меньше 100 монет.<br>
Также банк оказывает услуги для кланов по хранению вещей.');
if($klan['sklad'] > $t){
$date = ($klan['sklad'] - $t)/(24*60*60);
if($date <1){
$date = ($klan['sklad'] - $t)/(60*60);
msg2('Клановый склад будет арендован еще '.$date.' часов');
}
else{
$date = ceil($date);
msg2('Клановый склад будет арендован еще '.$date.' дней');
}
}
	knopka('bank.php?mod=set', 'Положить монеты', 1);
	knopka('bank.php?mod=get', 'Забрать монеты', 1);
knopka('bank.php?mod=obmen', 'Обменять монеты',1);
if ($klan['sklad'] >= $t and $f['klan_status'] > 1) knopka('bank.php?mod=setitem', 'Положить вещи на склад клана', 1);
if ($klan['sklad'] >= $t and $f['klan_status'] > 1) knopka('bank.php?mod=getitem', 'Забрать вещи со склада клана', 1);
if($klan['sklad'] < $t) knopka('bank.php?mod=arend', 'Арендавать клановый склад',1);
knopka('bank.php?mod=setitemu', 'Положить вещи в банковскую ячейку',1);
knopka('bank.php?mod=getitemu', 'Забрать вещи из банковской ячейки',1);
	if($f['bank'] > 100) knopka('bank.php?mod=check', 'Проверить процент', 1);
	knopka('loc.php', 'В игру', 1);
	fin();
	}
if($mod == 'set')
	{
	if($f['cu'] <= 0) msg2('У вас нет медных монет',1);
	if(empty($ok) or empty($summa))
		{
		echo '<div class="board" style="text-align:left">';
		echo '<form action="bank.php?mod=set&ok=1" method="POST">';
		echo 'Введите количество монет, которое вы хотите положить в банк:<br/>';
		echo '<input type="number" name="summa" value="0"/><br/>';
		echo '<input type="submit" value="Далее"/></form></div>';
		knopka('bank.php', 'Вернуться', 1);
		fin();
		}
	$summa = intval($summa);
	if($summa > $f['cu']) $summa = $f['cu'];
	if($summa < 0) msg2('Количество не может быть отрицательным',1);
	$f['cu'] -= $summa;
	$f['bank'] += $summa;
	$q = $db->query("update `users` set cu={$f['cu']}, bank={$f['bank']}, bankdate='{$t}' where login='{$f['login']}' limit 1;");
	msg2('Вы положили в банк '.$summa.' медных монет.');
	knopka('bank.php', 'Далее', 1);
	fin();
	}
if($mod == 'get')
	{
	if($f['bank'] <= 0) msg2('У вас нет монет в банке',1);
	if(empty($ok) or empty($summa))
		{
		echo '<div class="board" style="text-align:left">';
		echo '<form action="bank.php?mod=get&ok=1" method="POST">';
		echo 'Введите количество монет, которое вы хотите забрать из банка:<br/>';
		echo '<input type="number" name="summa" value="'.$f['bank'].'"/><br/>';
		echo '<input type="submit" value="Далее"/></form></div>';
		knopka('bank.php', 'Вернуться', 1);
		fin();
		}
	$summa = intval($summa);
	if($summa > $f['bank']) $summa = $f['bank'];
	if($summa < 0) msg2('Количество не может быть отрицательным',1);
	$f['bank'] -= $summa;
	$f['cu'] += $summa;
	$q = $db->query("update `users` set cu={$f['cu']}, bank={$f['bank']}, bankdate='{$t}' where login='{$f['login']}' limit 1;");
	msg2('Вы забрали из банка '.$summa.' медных монет.');
	knopka('bank.php', 'Далее', 1);
	fin();
	}
if($mod == 'setitem')
	{
if ($f['klan_status'] < 2){msg2('Вы не состаите в клане или ваших прав не достаточно', 1);
fin();}

if($klan['sklad'] < $t){msg2('У вас не арендован клановый склад',1);
fin();}

	if(empty($iid))
		{
		$numb = 15;	// сколько вещей на страницу
		$count = 0;	// обнуляем счетчик

		// нужно показать те вещи, которые не одеты, не в аренде, не на рынке, не на клан складе.
		$q = $db->query("select count(*) from `invent` where login='{$f['login']}' and flag_arenda=0 and flag_rinok=0 and flag_equip=0;");
		$all_itm = $q->fetch_assoc(); // всего вещей
		$all_itm = $all_itm['count(*)'];
		if($start > intval($all_itm / $numb)) $start = intval($all_itm / $numb);
		if($start < 0) $start = 0;
		$limit = $start * $numb;
		if($all_itm <= 0) msg2('У вас нет вещей в рюкзаке.',1);
		else msg2('Вы можете положить на склад:');
		$q = $db->query("select id,count(*) from `invent` where login='{$f['login']}' and flag_arenda=0 and flag_rinok=0 and flag_equip=0 group by ido order by time,id desc limit {$limit},{$numb};");
		while($invent = $q->fetch_assoc())
			{
			$count++;
			$item = $items->shmot($invent['id']);
			echo '<div class="board2" style="text-align:left">';
			echo $count.') <a href="infa.php?mod=iteminfa&iid='.$item['id'].'">'.$item['name'].'</a> ('.$item['lvl'].' уров.)';
			echo ' <a href="bank.php?mod=setitem&iid='.$item['id'].'">[на склад]</a>';
			if($invent['count(*)'] > 1) echo '<br/>Количество: <b>'.$invent['count(*)'].'</b>';
			echo '</div>';
			}
		if($all_itm > $numb)
			{
			echo '<div class="board">';
			if($start > 0) echo '<a href="bank.php?mod=setitem&start='.($start - 1).'" class="navig"><-Назад</a>'; else echo '<a href="#" class="navig"><-Назад</a>';
			echo ' | ';
			if($limit + $numb < $all_itm) echo '<a href="bank.php?mod=setitem&start='.($start + 1).'" class="navig">Вперед-></a>'; else echo '<a href="#" class="navig">Вперед-></a>';
			echo '</div>';
			}
		fin();
		}
	// этот блок кода сработает если уже выбрана вещь
	if($iid < 1) msg2('Вещь не найдена в вашем рюкзаке!', 1);
	// мы возьмем только ИД вещи
	$res = $db->query("select id from `invent` where id={$iid} and login='{$f['login']}' and flag_rinok=0 and flag_arenda=0 and flag_equip=0 order by time,id desc limit 1;");
	$item = $res->fetch_assoc() or msg2('Вещь не найдена в вашем рюкзаке!',1);
	// а вот тут мы посчитаем сколько всего у нас одинаковых вещей в рюкзаке
	$summ = $items->count_item($f['login'],$item['id']);
	$item = $items->shmot($item['id']);
	if(empty($summa))
		{
		echo '<div class="board" style="text-align:left">';
		echo '<form action="bank.php?mod=setitem&iid='.$iid.'" method="POST">';
		echo 'Вы собираетесь положить на склад '.$item['name'].'<br/>';
		echo '<small>У вас - '.$summ.' шт.</small><br/>';
		echo 'Количество:<br/><input type="number" name="summa" maxlenght="12" value="'.$summ.'"/><br/>';
		echo '<input type="submit" value="Далее" /></div>';
		fin();
		}
	$summa = intval($summa);
	if($summa <= 0) $summa = 1;
	if($summa > $summ) $summa = $summ;
	$numr = -1 * $summa;
$log = ''.$f['login'].' оставляет на клановом складе '.$item['name'].' в количестве '.$summa.' шт.';
$q =$db->query("insert into `klan_log` values(0,'{$f['login']}','{$log}','{$f['klan']}','{$t}');");

	if($summa == 1)	$q = $db->query("update `invent` set login='{$f['klan']}',time='{$t}' where id={$iid} and login='{$f['login']}' and flag_rinok=0 and flag_arenda=0 and flag_equip=0 limit 1;");
	else $q = $db->query("update `invent` set login='{$f['klan']}',time='{$t}' where ido={$item['ido']} and login='{$f['login']}' and flag_rinok=0 and flag_arenda=0 and flag_equip=0 order by time desc limit {$summa};");
	msg2('Вы положили на склад '.$item['name'].' ('.$summa.' шт.)');
	knopka('bank.php?mod=setitem', 'Далее',1);
	fin();

	}
if($mod == 'getitem')
{
if ($f['klan_status'] < 2){msg2('Вы не состаите в клане или ваших прав не достаточно', 1);

fin();
}
if($klan['sklad'] < $t){msg2('У вас не арендован клановый склад',1);
fin();}


	if(empty($iid))
		{
		$numb = 15;	// сколько вещей на страницу
		$count = 0;	// обнуляем счетчик

		// нужно показать те вещи, которые на складе.
		$q = $db->query("select count(*) from `invent` where login='{$f['klan']}';");
		$all_itm = $q->fetch_assoc(); // всего вещей
		$all_itm = $all_itm['count(*)'];
		if($start > intval($all_itm / $numb)) $start = intval($all_itm / $numb);
		if($start < 0) $start = 0;
		$limit = $start * $numb;
		if($all_itm <= 0) msg2('У вас нет вещей на складе.',1);
		else msg2('Вы можете забрать со склада:');
		$q = $db->query("select id,count(*) from `invent` where login='{$f['klan']}' group by ido order by time,id desc limit {$limit},{$numb};");
		while($invent = $q->fetch_assoc())
			{
			$count++;
			$item = $items->shmot($invent['id']);
			echo '<div class="board2" style="text-align:left">';
			echo $count.') <a href="infa.php?mod=iteminfa&iid='.$item['id'].'">'.$item['name'].'</a> ('.$item['lvl'].' уров.)';
			echo ' <a href="bank.php?mod=getitem&iid='.$item['id'].'">[забрать]</a>';
			if($invent['count(*)'] > 1) echo '<br/>Количество: <b>'.$invent['count(*)'].'</b>';
			echo '</div>';
			}
		if($all_itm > $numb)
			{
			echo '<div class="board">';
			if($start > 0) echo '<a href="bank.php?mod=getitem&start='.($start - 1).'" class="navig"><-Назад</a>'; else echo '<a href="#" class="navig"><-Назад</a>';
			echo ' | ';
			if($limit + $numb < $all_itm) echo '<a href="bank.php?mod=getitem&start='.($start + 1).'" class="navig">Вперед-></a>'; else echo '<a href="#" class="navig">Вперед-></a>';
			echo '</div>';
			}
		fin();
		}
	// этот блок кода сработает если уже выбрана вещь
	if($iid < 1) msg2('Вещь не найдена на складе!', 1);
	// мы возьмем только ИД вещи
	$res = $db->query("select id,ido from `invent` where id={$iid} and login='{$f['klan']}' order by time,id desc limit 1;");
	$item = $res->fetch_assoc() or msg2('Вещь не найдена на складе!', 1);
	// а вот тут мы посчитаем сколько всего у нас одинаковых вещей в складе
	$res = $db->query("select count(*) from `invent` where ido={$item['ido']} and login='{$f['klan']}';");
	$summ = $res->fetch_assoc();
	$summ = $summ['count(*)'];
	$item = $items->shmot($item['id']);
	if(empty($summa))
		{
		echo '<div class="board" style="text-align:left">';
		echo '<form action="bank.php?mod=getitem&iid='.$iid.'" method="POST">';
		echo 'Вы собираетесь забрать со склада '.$item['name'].'<br/>';
		echo '<small>У вас - '.$summ.' шт.</small><br/>';
		echo 'Количество:<br/><input type="number" name="summa" maxlenght="12" value="'.$summ.'"/><br/>';
		echo '<input type="submit" value="Далее" /></div>';
		fin();
		}
	$summa = intval($summa);
	if($summa <= 0) $summa = 1;
	if($summa > $summ) $summa = $summ;
$log = ''.$f['login'].' берёт с кланового склада '.$item['name'].' в количестве '.$summa.' шт.';
$q =$db->query("insert into `klan_log` values(0,'{$f['login']}','{$log}','{$f['klan']}','{$t}');");
	if($summa == 1)	$q = $db->query("update `invent` set login='{$f['login']}',time='{$t}' where id={$iid} and login='{$f['klan']}' limit 1;");
	else $q = $db->query("update `invent` set login='{$f['login']}',time='{$t}' where ido={$item['ido']} and login='{$f['klan']}' order by time desc limit {$summa};");
	msg2('Вы забрали со склада '.$item['name'].' ('.$summa.' шт.)');
	knopka('bank.php?mod=getitem', 'Далее',1);
	fin();
	}
if($mod == 'check')
	{
	if($f['bank'] < 100) msg2('У вас в банке меньше 100 монет.',1);
	if($f['bankdate'] + 604800 > $t) msg2('Вам будет начислен процент '.date('d.m.Y H:i',$f['bankdate'] + 604800), 1);
	$srok = $t - $f['bankdate']; // получим срок вклада в секундах
	$srok = intval($srok / 604800); // получим количество недель вклада (округл вниз)
	$oldmoney = $f['bank']; // сохраним старое количество средств
	$proc = 0.02;
	if($f['vip'] > $t) $proc = 0.03;
	for($i = $srok; $i > 0; $i--)
		{
		$bn = intval($f['bank'] * $proc);
		if($bn > 5000 && $f['vip'] > $t) $bn = 5000;
		if($bn > 3000 && $f['vip'] < $t) $bn = 3000;
		$f['bank'] += $bn;
		$q = $db->query("update `users` set bank={$f['bank']},bankdate=bankdate+604800 where id={$f['id']} limit 1;");
		}
	$sum = $f['bank'] - $oldmoney;
	msg2('Вам начислено '.$sum.' монет по проценту в банке за '.$srok.' недель.');
	$log = $f['login'].' ['.$f['lvl'].'] получает в банке '.$sum.' монет за '.$srok.' недель с вклада '.$oldmoney.' монет.';
	$q = $db->query("insert into `log_peredach` values(0,'{$f['login']}','{$log}','','{$t}');");
	knopka('bank.php', 'Далее', 1);
	fin();
}

elseif($mod=='arend'){
if(empty($ok)){
if($klan['sklad'] >= $t) msg2('Срок аренды не истек!',1);
msg2('Вы можете арендовать банковское хранилище для вещей вашего клана.<br>
Это обойдется вам в 5000 медных монет/мес.<br>
Средства взымаются из казны клана.');
knopka2('bank.php?mod=arend&ok=1', 'Арендовать',1);
knopka2('bank.php', 'Вернуться',1);
fin();
}
elseif($ok==1){
if($klan['kazna'] < 5000) msg2('В казне не достаточно средств',1);
$log = 'Игрок '.$f['login'].' арендует на месяц склад клана '.$f['klan'].' за 5000 монет!';
$q =$db->query("insert into `klan_log` values(0,'{$f['login']}','{$log}','{$f['klan']}','{$t}');");
$time = $t+(60*60*24*30);
$q=$db->query("update `klans` set sklad={$time} where name='{$f['klan']}' limit 1;");
$q =$db->query("update `klans` set kazna=kazna-5000 where name='{$f['klan']}' limit 1;");
msg2('Успешно!',1);
fin();
}
}
if($mod == 'setitemu')
	{
	if(empty($iid))
		{
		$numb = 15;	// сколько вещей на страницу
		$count = 0;	// обнуляем счетчик

		// нужно показать те вещи, которые не одеты, не в аренде, не на рынке, не на клан складе.
		$q = $db->query("select count(*) from `invent` where login='{$f['login']}' and flag_arenda=0 and flag_rinok=0 and flag_equip=0 and flag_sklad=0;");
		$all_itm = $q->fetch_assoc(); // всего вещей
		$all_itm = $all_itm['count(*)'];
		if($start > intval($all_itm / $numb)) $start = intval($all_itm / $numb);
		if($start < 0) $start = 0;
		$limit = $start * $numb;
		if($all_itm <= 0) msg2('У вас нет вещей в рюкзаке.',1);
		else msg2('Вы можете положить на склад:');
		$q = $db->query("select id,count(*) from `invent` where login='{$f['login']}' and flag_arenda=0 and flag_rinok=0 and flag_equip=0 and flag_sklad=0 group by ido order by time,id desc limit {$limit},{$numb};");
		while($invent = $q->fetch_assoc())
			{
			$count++;
			$item = $items->shmot($invent['id']);
			echo '<div class="board2" style="text-align:left">';
			echo $count.') <a href="infa.php?mod=iteminfa&iid='.$item['id'].'">'.$item['name'].'</a> ('.$item['lvl'].' уров.)';
			echo ' <a href="bank.php?mod=setitemu&iid='.$item['id'].'">[на склад]</a>';
			if($invent['count(*)'] > 1) echo '<br/>Количество: <b>'.$invent['count(*)'].'</b>';
			echo '</div>';
			}
		if($all_itm > $numb)
			{
			echo '<div class="board">';
			if($start > 0) echo '<a href="bank.php?mod=setitemu&start='.($start - 1).'" class="navig"><-Назад</a>'; else echo '<a href="#" class="navig"><-Назад</a>';
			echo ' | ';
			if($limit + $numb < $all_itm) echo '<a href="bank.php?mod=setitemu&start='.($start + 1).'" class="navig">Вперед-></a>'; else echo '<a href="#" class="navig">Вперед-></a>';
			echo '</div>';
			}
		fin();
		}
	// этот блок кода сработает если уже выбрана вещь
	if($iid < 1) msg2('Вещь не найдена в вашем рюкзаке!', 1);
	// мы возьмем только ИД вещи
	$res = $db->query("select id from `invent` where id={$iid} and login='{$f['login']}' and flag_rinok=0 and flag_arenda=0 and flag_equip=0 and flag_sklad=0 order by time,id desc limit 1;");
	$item = $res->fetch_assoc() or msg2('Вещь не найдена в вашем рюкзаке!',1);
	// а вот тут мы посчитаем сколько всего у нас одинаковых вещей в рюкзаке
	$summ = $items->count_item($f['login'],$item['id']);
	$item = $items->shmot($item['id']);
	if(empty($summa))
		{
		echo '<div class="board" style="text-align:left">';
		echo '<form action="bank.php?mod=setitemu&iid='.$iid.'" method="POST">';
		echo 'Вы собираетесь положить на склад '.$item['name'].'<br/>';
		echo '<small>У вас - '.$summ.' шт.</small><br/>';
		echo 'Количество:<br/><input type="number" name="summa" maxlenght="12" value="'.$summ.'"/><br/>';
		echo '<input type="submit" value="Далее" /></div>';
		fin();
		}
	$summa = intval($summa);
	if($summa <= 0) $summa = 1;
	if($summa > $summ) $summa = $summ;
	$numr = -1 * $summa;
	if($summa == 1)	$q = $db->query("update `invent` set flag_sklad=1,time='{$t}' where id={$iid} and login='{$f['login']}' and flag_rinok=0 and flag_arenda=0 and flag_equip=0 and flag_sklad=0 limit 1;");
	else $q = $db->query("update `invent` set flag_sklad=1,time='{$t}' where ido={$item['ido']} and login='{$f['login']}' and flag_rinok=0 and flag_arenda=0 and flag_equip=0 and flag_sklad=0 order by time desc limit {$summa};");
	msg2('Вы положили на склад '.$item['name'].' ('.$summa.' шт.)');
	knopka('bank.php?mod=setitemu', 'Далее',1);
	fin();
	}
if($mod == 'getitemu')
	{
	if(empty($iid))
		{
		$numb = 15;	// сколько вещей на страницу
		$count = 0;	// обнуляем счетчик

		// нужно показать те вещи, которые на складе.
		$q = $db->query("select count(*) from `invent` where login='{$f['login']}' and flag_sklad=1;");
		$all_itm = $q->fetch_assoc(); // всего вещей
		$all_itm = $all_itm['count(*)'];
		if($start > intval($all_itm / $numb)) $start = intval($all_itm / $numb);
		if($start < 0) $start = 0;
		$limit = $start * $numb;
		if($all_itm <= 0) msg2('У вас нет вещей на складе.',1);
		else msg2('Вы можете забрать со склада:');
		$q = $db->query("select id,count(*) from `invent` where login='{$f['login']}' and flag_sklad=1 group by ido order by time,id desc limit {$limit},{$numb};");
		while($invent = $q->fetch_assoc())
			{
			$count++;
			$item = $items->shmot($invent['id']);
			echo '<div class="board2" style="text-align:left">';
			echo $count.') <a href="infa.php?mod=iteminfa&iid='.$item['id'].'">'.$item['name'].'</a> ('.$item['lvl'].' уров.)';
			echo ' <a href="bank.php?mod=getitemu&iid='.$item['id'].'">[забрать]</a>';
			if($invent['count(*)'] > 1) echo '<br/>Количество: <b>'.$invent['count(*)'].'</b>';
			echo '</div>';
			}
		if($all_itm > $numb)
			{
			echo '<div class="board">';
			if($start > 0) echo '<a href="bank.php?mod=getitemu&start='.($start - 1).'" class="navig"><-Назад</a>'; else echo '<a href="#" class="navig"><-Назад</a>';
			echo ' | ';
			if($limit + $numb < $all_itm) echo '<a href="bank.php?mod=getitemu&start='.($start + 1).'" class="navig">Вперед-></a>'; else echo '<a href="#" class="navig">Вперед-></a>';
			echo '</div>';
			}
		fin();
		}
	// этот блок кода сработает если уже выбрана вещь
	if($iid < 1) msg2('Вещь не найдена на складе!', 1);
	// мы возьмем только ИД вещи
	$res = $db->query("select id,ido from `invent` where id={$iid} and login='{$f['login']}' and flag_sklad=1 order by time,id desc limit 1;");
	$item = $res->fetch_assoc() or msg2('Вещь не найдена на складе!', 1);
	// а вот тут мы посчитаем сколько всего у нас одинаковых вещей в складе
	$res = $db->query("select count(*) from `invent` where ido={$item['ido']} and login='{$f['login']}' and flag_sklad=1;");
	$summ = $res->fetch_assoc();
	$summ = $summ['count(*)'];
	$item = $items->shmot($item['id']);
	if(empty($summa))
		{
		echo '<div class="board" style="text-align:left">';
		echo '<form action="bank.php?mod=getitemu&iid='.$iid.'" method="POST">';
		echo 'Вы собираетесь забрать со склада '.$item['name'].'<br/>';
		echo '<small>У вас - '.$summ.' шт.</small><br/>';
		echo 'Количество:<br/><input type="number" name="summa" maxlenght="12" value="'.$summ.'"/><br/>';
		echo '<input type="submit" value="Далее" /></div>';
		fin();
		}
	$summa = intval($summa);
	if($summa <= 0) $summa = 1;
	if($summa > $summ) $summa = $summ;
	if($summa == 1)	$q = $db->query("update `invent` set flag_sklad=0,time='{$t}' where id={$iid} and login='{$f['login']}' and flag_sklad=1 limit 1;");
	else $q = $db->query("update `invent` set flag_sklad=0,time='{$t}' where ido={$item['ido']} and login='{$f['login']}' and flag_sklad=1 order by time desc limit {$summa};");
	msg2('Вы забрали со склада '.$item['name'].' ('.$summa.' шт.)');
	knopka('bank.php?mod=getitemu', 'Далее',1);
	fin();
}

elseif($mod=='obmen'){
if(empty($ok)){
msg2('Курсы обмена (одинаковые в обе стороны):<br>
1 серебряная монета = 100 медных монет.<br>
1 золотая монета = 100 серебряных монет.');
knopka2('bank.php?mod=obmen&ok=1', 'Обмен меди и серебра',1);
knopka2('bank.php?mod=obmen&ok=2', 'Обмен серебра и золота',1);
fin();
}
elseif($ok==1){
msg2('<form action="bank.php?mod=obmen&ok=12" method="post">
Введите кол-во серебра:<br>
<input type="number" name="summa"/> > <select name="tip">
<option value="1">Купить</option>
<option value="2">Продать</option>
</select> >
<input type="submit" value="ОК"/>
</form>',1);
fin();
}
elseif($ok==12){
if($tip == 1){
if($summa <1) $summa = 1;
$ser = $summa*100;
if($ser > $f['cu']) msg2('У вас нет столько меди',1);
$log = 'Игрок '.$f['login'].' обменивает в банке '.$ser.' медных на '.$summa.' серебряных монет.';
$q = $db->query("insert into `log_peredach` values(0,'{$f['login']}','{$log}','','{$t}');");
$q = $db->query("update `users` set cu=cu-{$ser},ag=ag+{$summa} where id={$f['id']} limit 1;");
msg2('Вы обменяли '.$ser.' медных на '.$summa.' серебряных монет.',1);
fin();
}
elseif($tip == 2){
if($summa <1) $summa = 1;
$ser = $summa*100;
if($summa > $f['ag']) msg2('У вас нет столько серебра',1);
$log = 'Игрок '.$f['login'].' обменивает в банке '.$summa.' серебряных на '.$ser.' медных монет.';
$q = $db->query("insert into `log_peredach` values(0,'{$f['login']}','{$log}','','{$t}');");
$q = $db->query("update `users` set cu=cu+{$ser},ag=ag-{$summa} where id={$f['id']} limit 1;");
msg2('Вы обменяли '.$summa.' серебряных на '.$ser.' медных монет.',1);
fin();
}
}
elseif($ok==2){
msg2('<form action="bank.php?mod=obmen&ok=22" method="post">
Введите кол-во золота: <input type="number" name="summa"/> > <select name="tip">
<option value="1">Купить</option>
<option value="2">Продать</option>
</select> >
<input type="submit" value="ОК"/>
</form>',1);
fin();
}
elseif($ok==22){
if($tip == 1){
if($summa <1) $summa = 1;
$ser = $summa*100;
if($ser > $f['ag']) msg2('У вас нет столько серебра',1);
$log = 'Игрок '.$f['login'].' обменивает в банке '.$ser.' серебряных на '.$summa.' золотых монет.';
$q = $db->query("insert into `log_peredach` values(0,'{$f['login']}','{$log}','','{$t}');");
$q = $db->query("update `users` set ag=ag-{$ser},au=au+{$summa} where id={$f['id']} limit 1;");
msg2('Вы обменяли '.$ser.' серебряных на '.$summa.' золотых монет.',1);
fin();
}
elseif($tip == 2){
if($summa <1) $summa = 1;
$ser = $summa*100;
if($summa > $f['au']) msg2('У вас нет столько золота',1);
$log = 'Игрок '.$f['login'].' обменивает в банке '.$summa.' золотых на '.$ser.' серебряных монет.';
$q = $db->query("insert into `log_peredach` values(0,'{$f['login']}','{$log}','','{$t}');");
$q = $db->query("update `users` set ag=ag+{$ser},au=au-{$summa} where id={$f['id']} limit 1;");
msg2('Вы обменяли '.$summa.' золотых на '.$ser.' серебряных монет.',1);
fin();
}
}
}
?>
