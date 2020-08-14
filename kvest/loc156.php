<?php
if($items->count_base_item($f['login'], 781) == 0) msg2('У вас нет кузнечного молота',1);
msg('Вы можете улучшить (ну или ухудшить, уж как получится) любой предмет экипировки. Цена 200 монет за каждый уровень вещи.');
$iid = isset($_REQUEST['iid']) ? intval($_REQUEST['iid']) : 0;
$start = isset($_REQUEST['start']) ? intval($_REQUEST['start']) : 0;
$limit = 0;
$numb = 15;
if(empty($iid))
	{
	// вещи не в аренде, не в складе, не одетые, не на рынке
	$q = $db->query("select count(*) from `invent` where login='{$f['login']}' and flag_arenda=0 and flag_rinok=0 and flag_sklad=0 and flag_equip=0 and (slot<>'' and slot<>'sumka') and up=0;");
	$a = $q->fetch_assoc();
	$all_itm = $a['count(*)']; // всего вещей
	if(empty($all_itm)) msg('У вас нет подходящих вещей для модификации', 1);
	if($start > intval($all_itm / $numb)) $start = intval($all_itm / $numb);
	if($start < 0) $start = 0;
	$limit = $start * $numb;
	$count = $limit;
	$q = $db->query("select id from `invent` where login='{$f['login']}' and flag_arenda=0 and flag_rinok=0 and flag_sklad=0 and flag_equip=0 and (slot<>'' and slot<>'sumka') and up=0 limit {$limit},{$numb};");
	while($invent = $q->fetch_assoc())
		{
		echo '<div class="board2" style="text-align:left">';
		$count++;
		$item = $items->shmot($invent['id']);
		echo $count.') <a href="infa.php?mod=iteminfa&iid='.$item['id'].'">'.$item['name'].'</a>';
		echo ' <a href="kvest.php?iid='.$item['id'].'"><span style="color:'.$male.'">Улучшить</span></a>';
		echo '<br/>уров: '.$item['lvl'].', цена: '.($item['lvl'] * 200);
		echo '</div>';
		}
	if($all_itm > $numb)
		{
		echo '<div class="board">';
		if($start > 0) echo '<a href="kvest.php?start='.($start - 1).'" class="navig"><-Назад</a>'; else echo '<a href="#" class="navig"><-Назад</a>';
		echo ' | ';
		if($limit + $numb < $all_itm) echo '<a href="kvest.php?start='.($start + 1).'" class="navig">Вперед-></a>'; else echo '<a href="#" class="navig">Вперед-></a>';
		echo '</div>';
		}
	fin();
	}
$iid = intval($iid);
if($iid <= 0) msg('Такая вещь не найдена в рюкзаке.', 1);
$item = $items->shmot($iid);
if(!empty($item['up'])) msg('Вещь уже была модифицирована ранее.', 1);
if($item['lvl'] < 6) msg('Модифицировать можно вещи с 6 уровня.', 1);
if(empty($item['slot']) or $item['slot'] == 'sumka') msg('Эта вещь не подлежит модификации', 1);
$cena = $item['lvl'] * 200;
if($cena > $f['cu']) msg('У вас недостаточно монет для модификации данной вещи.', 1);
if(empty($ok))
	{
	msg('Вы хотите модифицировать '.$item['name'].' за '.$cena.' монет?');
	knopka('kvest.php?iid='.$iid.'&ok=1', 'Продолжаем');
	knopka('loc.php', 'Да вы что, мне очень страшно! * Убежать');
	fin();
}
if($f['p_kuznec'] ==0) $upgr = mt_rand(1, 10);
if($f['p_kuznec'] >0 and $f['p_kuznec'] <=25) $upgr = mt_rand(1, 15);
if($f['p_kuznec'] >25 and $f['p_kuznec'] <=50) $upgr = mt_rand(1, 20);
if($f['p_kuznec'] >50 and $f['p_kuznec'] <=100) $upgr = mt_rand(1, 25);
if($f['p_kuznec'] >100 and $f['p_kuznec'] <=250) $upgr = mt_rand(1, 30);
if($f['p_kuznec'] >250 and $f['p_kuznec'] <=500) $upgr = mt_rand(1, 35);
if($f['p_kuznec'] >500 and $f['p_kuznec'] <=1000) $upgr = mt_rand(1, 40);
if($f['p_kuznec'] >1000) $upgr = mt_rand(1, 50);
if($f['p_kuznec']==0 and mt_rand(1, 100) <= 60) $upgr *= -1;
if($f['p_kuznec'] >=1 and $f['p_kuznec'] <25 and mt_rand(1, 100) <= 55) $upgr *= -1;
if($f['p_kuznec'] >=25 and $f['p_kuznec'] <50 and mt_rand(1, 100) <= 50) $upgr *= -1;
if($f['p_kuznec'] >=50 and $f['p_kuznec'] <100 and mt_rand(1, 100) <= 45) $upgr *= -1;
if($f['p_kuznec'] >=100 and $f['p_kuznec'] <250 and mt_rand(1, 100) <= 40) $upgr *= -1;
if($f['p_kuznec'] >=250 and $f['p_kuznec'] <500 and mt_rand(1, 100) <= 35) $upgr *= -1;
if($f['p_kuznec'] >=500 and $f['p_kuznec'] <1000 and mt_rand(1, 100) <= 30){ $upgr *= -1;}
if($f['p_kuznec'] >1000 and mt_rand(1, 100) <= 25){ $upgr *= -1;}


$item['info'] .= '<br>Улучшено кузнецом '.$f['login'].' на '.$upgr.'%';
if($upgr > 0) $up = '+'.$upgr.'%'; else $up = $upgr.'%';
$q = $db->query("update `invent` set up='{$upgr}',time='{$t}',info='{$item['info']}' where id='{$iid}' limit 1;");
$q = $db->query("update `users` set cu=cu-{$cena},molot=molot-1 where id='{$f['id']}' limit 1;");
//$log = $f['login'].' ['.$f['lvl'].'] ('.$f['klan'].') модифицирует '.$item['name'].' (id: '.$item['id'].') на '.$up;
//$q = $db->query("insert into log_peredach values(0,'{$f['login']}','{$log}','','{$t}');");
msg('Неуклюже постукав молотком, вы модифицируете '.$item['name'].' на '.$upgr.'%');
knopka('kvest.php', 'Модифицировать еще одну вещь');
knopka('loc.php', 'Уйти');
fin();
?>
