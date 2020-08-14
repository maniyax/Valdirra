<?php
##############
# 24.12.2014 #
##############

require_once('inc/top.php');		// вывод на экран
require_once('inc/check.php');	// вход в игру
require_once('inc/head.php');
require_once('class/items.php');	// вещи

if($f['loc'] != 154 and $f['loc'] != 228)
	{
	knopka('loc.php', 'Ошибка локации', 1);
	fin();
	}
if($f['status'] == 1)
	{
	knopka('battle.php', 'Вы в бою!', 1);
	fin();
	}
if($f['hpnow'] <= 0)
	{
	knopka('loc.php', 'Восстановите здоровье', 1);
	fin();
	}
// объявим переменные чтоб не зависеть от register globals
$mod = isset($_REQUEST['mod']) ? $_REQUEST['mod'] : '';
$num = isset($_REQUEST['num']) ? $_REQUEST['num'] : 0;
$iid = isset($_REQUEST['iid']) ? $_REQUEST['iid'] : '';
$ok = isset($_REQUEST['ok']) ? 1 : 0;
$to = isset($_REQUEST['to']) ? $_REQUEST['to'] : 0;
$slot = isset($_REQUEST['slot']) ? $_REQUEST['slot'] : 0;
$summa = isset($_REQUEST['summa']) ? $_REQUEST['summa'] : 0;
$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
$lvl = isset($_REQUEST['lvl']) ? $_REQUEST['lvl'] : 0;

require_once('inc/hpstring.php');
if(empty($mod))
	{
if($f['rasa'] ==1) msg2('Под навесом сидят несколько человек в богатых одеяниях. Судя по надписе на табличке, вы можете купить какую-либо поношеную вещь, или отдать на продажу свою.');
elseif($f['rasa'] ==2) msg2('Под навесом сидят несколько гномов в богатых одеяниях. Судя по надписе на табличке, вы можете купить какую-либо поношеную вещь, или отдать на продажу свою.');
elseif($f['rasa'] ==3) msg2('Под навесом сидят несколько эльфов в богатых одеяниях. Судя по надписе на табличке, вы можете купить какую-либо поношеную вещь, или отдать на продажу свою.');
elseif($f['rasa'] ==4) msg2('Под навесом сидят несколько орков в богатых одеяниях. Судя по надписе на табличке, вы можете купить какую-либо поношеную вещь, или отдать на продажу свою.');

msg2('[все операции производятся в медных монетах]');
	knopka('rinok.php?mod=search', 'Смотреть вещи', 1);
	knopka('rinok.php?mod=lot', 'Выставить вещи', 1);
	knopka('rinok.php?mod=myitem', 'Мои вещи на рынке', 1);
	fin();
	}
elseif($mod == 'lot')
	{
	if(empty($iid))
		{
		$numb = 50;	// сколько вещей на страницу
		$count = 0;
		// выставить можно только вещи из рюкзака, исключая экипированные, рыночные, клановые, непередающиеся и арендованные.
		$a = $db->query("select count(*) from `invent` where login='{$f['login']}' and flag_rinok=0 and flag_arenda=0 and flag_equip=0 and flag_sklad=0 and flag_pered=1;");
		$all_itm = $a->fetch_assoc();
		$all_itm = $all_itm['count(*)'];
		if($all_itm <= 0) msg2('У вас нет вещей на продажу.', 1);
		else msg2('Вы можете выставить на рынок:');
		if($start > intval($all_itm / $numb)) $start = intval($all_itm / $numb);
		if($start < 0) $start = 0;
		$limit = $start * $numb;
		$q = $db->query("select id from `invent` where login='{$f['login']}' and flag_rinok=0 and flag_arenda=0 and flag_equip=0 and flag_sklad=0 and flag_pered=1 order by time desc limit {$limit},{$numb};");
		while($invent = $q->fetch_assoc())
			{
			echo '<div class="board2" style="text-align:left">';
			$item = $items->shmot($invent['id']);
			echo '<a href="infa.php?mod=iteminfa&iid='.$item['id'].'">'.$item['name'].'</a>';
			echo ' <a href="rinok.php?mod=lot&iid='.$item['id'].'"><span style="color:'.$male.'">[выставить]</span></a><br/>';
			echo 'Цена '.$item['price'];
			$count++;
			echo '</div>';
			}
		if($all_itm > $numb)
			{
			echo '<div class="board">';
			if($start > 0) echo '<a href="rinok.php?mod=lot&start='.($start - 1).'" class="navig"><-Назад</a>'; else echo '<a href="#" class="navig"><-Назад</a>';
			echo ' | ';
			if($count + $numb < $all_itm) echo '<a href="rinok.php?mod=lot&start='.($start + 1).'" class="navig">Вперед-></a>'; else echo '<a href="#" class="navig">Вперед-></a>';
			echo '</div>';
			fin();
			}
		}
	// этот блок сработает только при выборе вещи на выставление
	$iid = intval($iid);
	if($iid < 1) msg2('Вещь не найдена в вашем рюкзаке!',1);
	$item = $items->shmot($iid);
	if(empty($ok))
		{
		echo '<div class="board" style="text-align:left">';
		echo '<form action="rinok.php" method="GET">';
echo'<input type="hidden" name="mod" value="lot"><input type="hidden" name="ok" value="1"><input type="hidden" name="iid" value="'.$iid.'">';
		echo 'Вы хотите выставить на продажу '.$item['name'].'.<br/>';
		echo 'Цена: <input type="number" value="'.$item['price'].'" name="summa"/><br/>';
		echo '<input type="submit" value="Далее"/></form></div>';
		knopka('rinok.php', 'Вернуться', 1);
		fin();
		}
	$summa = intval($summa);
	if($summa < 10) msg2('Минимальная цена - 10 монет',1);
	if($summa <= intval($item['price'] * 0.6)) msg2('Нельзя выставлять на продажу вещи ниже 60% от их госцены.',1);
	$q = $db->query("update `invent` set flag_rinok=1,rinok_price={$summa},time='{$t}' where id={$iid} limit 1;");
	msg2('Вы выставили на продажу '.$item['name'].' за '.$summa.' монет.');
	knopka('rinok.php?mod=lot', 'Выставить еще', 1);
	knopka('rinok.php', 'Вернуться', 1);
	fin();
	}
elseif($mod == 'myitem')
	{
	if(empty($iid))
		{
		$numb = 50;
		$count = 0;
		// мои вещи на рынке
		$a = $db->query("select count(*) from `invent` where login='{$f['login']}' and flag_rinok=1;");
		$all_itm = $a->fetch_assoc();
		$all_itm = $all_itm['count(*)'];
		if($all_itm <= 0) msg2('У вас нет вещей на рынке.', 1);
		else msg2('Ваши вещи на рынке:');
		if($start > intval($all_itm / $numb)) $start = intval($all_itm / $numb);
		if($start < 0) $start = 0;
		$limit = $start * $numb;
		$q = $db->query("select id from `invent` where login='{$f['login']}' and flag_rinok=1 order by time desc limit {$limit},{$numb};");
		while($invent = $q->fetch_assoc())
			{
			$item = $items->shmot($invent['id']);
			echo '<div class="board2" style="text-align:left"><a href="infa.php?mod=iteminfa&iid='.$item['id'].'">'.$item['name'].'</a>';
			echo ' <a href="rinok.php?mod=myitem&iid='.$item['id'].'"><span style="color:'.$male.'">[забрать]</span></a>';
			echo '<br/>Уровень: '.$item['lvl'].', Цена: '.$item['rinok_price'].'</div>';
			$count++;
			}
		if($all_itm > $numb)
			{
			echo '<div class="board">';
			if($start > 0) echo '<a href="rinok.php?mod=myitem&start='.($start - 1).'" class="navig"><-Назад</a>'; else echo '<a href="#" class="navig"><-Назад</a>';
			echo ' | ';
			if($count + $numb < $all_itm) echo '<a href="rinok.php?mod=myitem&start='.($start + 1).'" class="navig">Вперед-></a>'; else echo '<a href="#" class="navig">Вперед-></a>';
			echo '</div>';
			}
		fin();
		}
	$iid = intval($iid);
	if($iid < 1) msg2('Вещь не найдена на рынке.',1);
	$q = $db->query("select id from `invent` where id={$iid} and login='{$f['login']}' and flag_rinok=1 limit 1;");
	$itm = $q->fetch_assoc() or msg2('Вещь не найдена на рынке.',1);
	$item = $items->shmot($itm['id']);
	$q = $db->query("update `invent` set flag_rinok=0,rinok_price=0,time='{$t}' where id={$iid} limit 1;");
	msg2('Вы забрали с продажи '.$item['name']);
	knopka('rinok.php?mod=myitem', 'Забрать еще',1);
	fin();
	}
elseif($mod == 'search')
	{
	$q = $db->query("select max(lvl) from `item`;");
	$a = $q->fetch_assoc();
	$max_lvl = $a['max(lvl)'];
	if(empty($ok))
		{
		echo '<div class="board" style="text-align:left">';
		echo '<form action="rinok.php" method="GET">';
echo'<input type="hidden" name="mod" value="search"><input type="hidden" name="ok" value="1">';
		echo 'Уровень вещи:<br/><select name="lvl">';
		for($i = 1; $i <= $max_lvl; $i++) echo '<option value='.$i.'>'.$i.'</option>';
		echo '</select><br/>';
		echo '<input type="checkbox" name="to" value="1"/> Поиск по всем уровням<br/>';
		echo 'Слот:<br/><select name="slot">
		<option value="1">Без сортировки</option>
		<option value="2">Правая рука</option>
		<option value="3">Левая рука</option>
		<option value="4">Доспехи</option>
		<option value="5">Шлемы</option>
		<option value="6">Кольца</option>
		<option value="7">Амулеты</option>
		<option value="8">Сапоги</option>
		<option value="9">Плащи</option>
		<option value="10">Пояса</option>
		<option value="11">В сумку</option>
		<option value="12">Браслеты</option>
		<option value="13">Остальное</option>
		</select><br/>';
		echo '<input type="submit" value="Далее"/></form></div>';
		knopka('rinok.php', 'Вернуться', 1);
		fin();
		}
	$slot = intval($slot);
	if($slot < 1 or $slot > 13) msg2('Неверный параметр слота!',1);
	$append = '';
	$numb = 15;
	$count = 0;
	if($slot == 2) $append = 'prruka';
	if($slot == 3) $append = 'lruka';
	if($slot == 4) $append = 'dospeh';
	if($slot == 5) $append = 'golova';
	if($slot == 6) $append = 'kolco';
	if($slot == 7) $append = 'amulet';
	if($slot == 8) $append = 'nogi';
	if($slot == 9) $append = 'plaw';
	if($slot == 10) $append = 'pojas';
	if($slot == 11) $append = 'sumka';
	if($slot == 12) $append = 'braslet';
	$lvl = intval($lvl);
	if($lvl < 1) $lvl = 1;
	if($max_lvl < $lvl) $lvl = $max_lvl;
	if(!empty($to))
		{
		if($slot == 1)
			{
			// вывод всех вещей на рынке
			$a = $db->query("select count(*) from `invent` where flag_rinok=1;");
			$all_itm = $a->fetch_assoc(); // всего вещей
			$all_itm = $all_itm['count(*)'];
			if($start > intval($all_itm / $numb)) $start = intval($all_itm / $numb);
			if($start < 0) $start = 0;
			$limit = $start * $numb;
			$q = $db->query("select id from `invent` where flag_rinok=1 order by time desc limit {$limit},{$numb};");
			}
		else
			{
			// вывод всех вещей определенного слота на рынке
			$a = $db->query("select count(*) from `invent` where flag_rinok=1 and slot='{$append}';");
			$all_itm = $a->fetch_assoc(); // всего сообщений
			$all_itm = $all_itm['count(*)'];
			if($start > intval($all_itm / $numb)) $start = intval($all_itm / $numb);
			if($start < 0) $start = 0;
			$limit = $start * $numb;
			$q = $db->query("select id from `invent` where flag_rinok=1 and slot='{$append}' order by time desc limit {$limit},{$numb};");
			}
		}
	else
		{
		if($slot == 1)
			{
			// вывод всех вещей определенного уровня на рынке
			$a = $db->query("select count(*) from `invent` where lvl='{$lvl}' and flag_rinok=1;");
			$all_itm = $a->fetch_assoc(); // всего сообщений
			$all_itm = $all_itm['count(*)'];
			if($start > intval($all_itm / $numb)) $start = intval($all_itm / $numb);
			if($start < 0) $start = 0;
			$limit = $start * $numb;
			$q = $db->query("select id from `invent` where lvl='{$lvl}' and flag_rinok=1 order by time desc limit {$limit},{$numb};");
			}
		else
			{
			// вывод всех вещей определенного слота и уровня на рынке
			$a = $db->query("select count(*) from `invent` where lvl='{$lvl}' AND slot='{$append}' and flag_rinok=1;");
			$all_itm = $a->fetch_assoc(); // всего сообщений
			$all_itm = $all_itm['count(*)'];
			if($start > intval($all_itm / $numb)) $start = intval($all_itm / $numb);
			if($start < 0) $start = 0;
			$limit = $start * $numb;
			$q = $db->query("select id from `invent` where lvl='{$lvl}' AND slot='{$append}' and flag_rinok=1 order by time desc limit {$limit},{$numb};");
			}
		}
	if($all_itm <= 0) msg2('Не найдено ни одной вещи на рынке.',1);
	else msg2('Найдено на рынке:');
	while($rinok = $q->fetch_assoc())
		{
		$item = $items->shmot($rinok['id']);
		$count++;
		echo '<div class="board2" style="text-align:left">'.$count.') <a href="infa.php?mod=iteminfa&iid='.$item['id'].'">'.$item['name'].'</a>';
		if($item['login'] == $f['login'])
			{
			echo ' <a href="rinok.php?mod=myitem&iid='.$item['id'].'"><span style="color:'.$male.'">[забрать]</span></a>';
			}
		else
			{
			echo ' <a href="rinok.php?mod=kup&iid='.$item['id'].'"><span style="color:'.$male.'">[купить]</span></a>';
			}
		echo '<br/>';
		echo 'Уровень: '.$item['lvl'];
		echo ' Цена: '.$item['rinok_price'];
		echo '</div>';
		}
	if($all_itm > $numb)
		{
		echo '<div class="board">';
		if($start > 0) echo '<a href="rinok.php?mod=search&lvl='.$lvl.'&slot='.$slot.'&ok=1&start='.($start - 1).'&to='.$to.'" class="navig"><-Назад</a>'; else echo '<a href="#" class="navig"><-Назад</a>';
		echo ' | ';
		if($limit + $numb < $all_itm) echo '<a href="rinok.php?mod=search&lvl='.$lvl.'&slot='.$slot.'&ok=1&start='.($start + 1).'&to= '.$to.'" class="navig">Вперед-></a>'; else echo '<a href="#" class="navig">Вперед-></a>';
		echo '</div>';
		}
	fin();
	}
elseif($mod == 'kup')
	{
	$iid = intval($iid);
	if($iid < 1) msg2('Вещь не найдена на рынке!',1);
	$q = $db->query("select id from `invent` where id='{$iid}' and flag_rinok=1 limit 1;");
	$rinok = $q->fetch_assoc() or msg2('Вещь не найдена на рынке!',1);
	$item = $items->shmot($rinok['id']);
	if($item['login'] == $f['login']) msg2('Это ваша вещь, вы не можете её купить!',1);
	if(empty($ok))
		{
		msg('Вы уверены, что хотите купить '.$item['name'].'?');
		knopka('rinok.php?mod=kup&ok=1&iid='.$iid, 'Купить', 1);
		knopka('loc.php', 'В игру', 1);
		fin();
		}
	if($f['cu'] < $item['rinok_price']) msg2('У вас недостаточно монет!', 1);
	$f['cu'] -= $item['rinok_price'];
	$q = $db->query("update `users` set cu='{$f['cu']}' where id='{$f['id']}' limit 1;");
	$q = $db->query("update `users` set cu=cu+'{$item['rinok_price']}' where login='{$item['login']}' limit 1;");
	$log = $f['login'].' ['.$f['lvl'].'] покупает на рынке '.$item['name'].' ['.$item['lvl'].'] ('.$item['price'].') за '.$item['rinok_price'].' монет. Продавец '.$item['login'];
	$q = $db->query("insert into `log_peredach` values(0,'{$f['login']}','{$log}','{$item['login']}','{$t}');");
	$q = $db->query("update `invent` set login='{$f['login']}',flag_rinok=0,rinok_price=0,time='{$t}' where id='{$iid}' limit 1;");
	msg2('Вы купили '.$item['name'].'! Осталось '.$f['cu'].' медных монет.',1);
	}
?>
