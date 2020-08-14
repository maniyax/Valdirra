<?php
##############
# 24.12.2014 #
##############

require_once('inc/top.php');	// вывод на экран
require_once('inc/check.php');	// вход в игру
require_once('inc/head.php');

/*
Пометка для себя... Права доступа к управлению кланом.
0 - доступа нет
1 - (зам главы) - спам по клану, доступ к клан-складу
2 - (наместник) - назначение замов, прием в клан, удаление статуса зама, спам по клану
3 - (глава) - назначение замов, наместников, удаление замов, наместников. Спам, прием в клан, исключение из клана.
*/
require_once('inc/hpstring.php');
// любое действие с кланом доступно только заму и выше!
if(empty($f['klan']) or $f['klan_status'] == 0) msg2('Вы не можете управлять кланом!',1);

// шапка
$mod = isset($_REQUEST['mod']) ? $_REQUEST['mod'] : '';
$num = isset($_REQUEST['num']) ? $_REQUEST['num'] : 0;
$start = isset($_REQUEST['start']) ? intval($_REQUEST['start']) : 0;
$go = isset($_REQUEST['go']) ? $_REQUEST['go'] : 0;
$iid = isset($_REQUEST['iid']) ? $_REQUEST['iid'] : 0;
$lgn = isset($_REQUEST['lgn']) ? $_REQUEST['lgn'] : '';
$lgn1 = isset($_REQUEST['komu']) ? $_REQUEST['komu'] : '';
$summ = isset($_REQUEST['summa']) ? $_REQUEST['summa'] : '';
$kom = isset($_REQUEST['kom']) ? $_REQUEST['kom'] : '';
$ok = isset($_REQUEST['ok']) ? $_REQUEST['ok'] : '';

$q =$db->query("select * from `klans` where name='{$f['klan']}' limit 1;");
$a = $q->fetch_assoc();
$c = $db->query("select count(*) from `users` where klan='{$f['klan']}';");
$c = $c->fetch_assoc();
$c = $c['count(*)'];

if(empty($mod))
	{

	msg2('Уровень клана: '.$a['lvl'].'<br>Баллы: '.$a['points'].'<br>Люди: '.$c.'/'.($a['lvl'] * 10).'<br>Казна: '.$a['kazna'].' монет');
	if(2 <= $f['klan_status']) knopka('klan.php?mod=priem', 'Принять в клан', 1);
	if(1 <= $f['klan_status']) knopka('klan.php?mod=spam', 'Рассылка', 1);
	if(1 <= $f['klan_status']) knopka('klan.php?mod=sostav', 'Состав клана', 1);
	if(2 <= $f['klan_status']) knopka('klan.php?mod=rekrut', 'Рекруты', 1);
if($f['klan_status'] ==3) knopka('klan.php?mod=kazna', 'Переводы из казны',1);
	if(2 <= $f['klan_status']) knopka('klan.php?mod=status', 'Статусы', 1);
	if(2 <= $f['klan_status'] and 7 <= getDay($a['nalog_time'])) knopka('klan.php?mod=nalog', 'Собрать налог', 1);
	else msg('Налог будет доступен для сбора '.date('d.m.Y', $a['nalog_time'] + 86400*7));
	if($f['loc'] != 1 and $f['loc'] != 37 and $f['loc'] != 91 and $a['loc'] == 0 and $a['point'] == 0)
		{
		$q = $db->query("select name,loc,point from `klans` where loc={$f['loc']} or point={$f['loc']} limit 1;");
		if($q->num_rows==0)
			{
			$a = $q->fetch_assoc();
			knopka('klan.php?mod=mesto','Отметить это место для постройки замка', 1);
			}
		}
	fin();
	}

elseif($mod == 'priem')
	{
	if($f['klan_status'] < 2) msg2('Прием в клан доступен только наместникам и главам.',1);
	if($a['lvl'] * 10 <= $c) msg2('В клане нет мест, необходимо повысить уровень клана!',1);
	if(empty($lgn))
		{
		echo '<div class="board" style="text-align:left">';
		echo '<form action="klan.php?mod=priem" method="POST">';
		echo 'Введите ник:<br/>';
		echo '<input type="text" name="lgn" style="width:80%"/><br/>';
		echo '<input type="submit" value="Далее"/></form></div>';
		knopka('klan.php', 'Вернуться', 1);
		fin();
		}
	$TestLGN = get_login($lgn);
	$lgn = $TestLGN['login'];
	if($TestLGN['lvl'] < 4) msg2('Вступать в клан можно с 4 уровня.',1);
	if($TestLGN['status'] == 1) msg2('Персонаж '.$lgn.' в бою.',1);
	if(!empty($TestLGN['klan'])) msg2('Персонаж '.$lgn.' состоит в клане <b>'.$TestLGN['klan'].'</b>.',1);
	if(!empty($TestLGN['klan_invite']))
		{
		if($TestLGN['klan_invite'] == $f['klan']) msg2('У персонажа '.$lgn.' уже есть приглашение в ваш клан.',1);
		else msg2('У персонажа '.$lgn.' уже есть приглашение в другой клан.',1);
		}
	if(empty($ok))
		{
		msg2('Вы хотите пригласить в клан персонажа '.$lgn.'. Продолжить?');
		knopka('klan.php?mod=priem&lgn='.$lgn.'&ok=1', 'Продолжить',1);
		knopka('klan.php', 'Вернуться', 1);
		fin();
		}
	$q = $db->query("update `users` set klan_invite='{$f['klan']}' where login='{$lgn}' limit 1;");
	msg2('Персонаж '.$lgn.' успешно приглашен в клан.');
	knopka('klan.php', 'Далее', 1);
	fin();
	}

elseif($mod == 'status')
	{
	if(empty($ok))
		{
		echo '<div class="board" style="text-align:left">';
		echo '<form action="klan.php?mod=status&ok=1" method="POST">
		Введите логин:<br/>
		<input type="text" name="lgn" /><br/>
		<select name="num">
		<option value="0">Без статуса</option>
		<option value="1">Зам. Главы</option>';
		if(3 <= $f['klan_status']) echo '<option value="2">Наместник</option><option value="3">Глава клана</option>';
		echo '</select>
		<br/>
		<input type="submit" value="Далее" />
		</form></div>';
		knopka('klan.php', 'Вернуться', 1);
		fin();
		}
	if($num != 0 and $num != 1 and $num != 2 and $num != 3) msg2('Неверный выбор статуса персонажа',1);
	if($num == 3 and $f['klan_status'] < 3) msg2('Главу клана может назначить только Глава клана, передав полномочия.',1);
	if($num == 2 and $f['klan_status'] < 3) msg2('Наместника может назначить только Глава клана.',1);
	$TestLGN = get_login($lgn);
	if($TestLGN['klan'] != $f['klan']) msg2('Этот персонаж не в вашем клане.',1);
	$lgn = $TestLGN['login'];
	if($lgn == $f['login']) msg2('Не стоит менять собственный статус',1);
	if($TestLGN['klan_status'] == 3) msg2('Нельзя сменить статус у главы клана.',1);
	if($TestLGN['klan_status'] == 2 and $f['klan_status'] < 3) msg2('Недостаточно прав для смены статуса.',1);
	$q = $db->query("update `users` set klan_status='{$num}' where login='{$lgn}' limit 1;");
	if($num == 3) $q = $db->query("update `users` set klan_status=2 where id='{$f['id']}' limit 1;");
	msg2('Персонажу '.$lgn.' сменен статус!');
	knopka('klan.php', 'Вернуться', 1);
	fin();
	}

elseif($mod == 'sostav')
	{
	if($f['klan_status'] < 2) msg2('Вы не можете работать с составом клана',1);
	$count = 0;
	$q = $db->query("select login,lvl,sex,exp,klan_status,lastdate,nalog from `users` where klan='{$f['klan']}' order by lvl desc,exp desc,login;");
	echo '<div class="board" style="text-align:left">';
	echo '<table border="1">';
	echo '<tr>';
	echo '<td>#</td>';
	echo '<td align="center">НИК</td>';
	echo '<td align="center">СТАТУС</td>';
	echo '<td align="center">ЗАХОД</td>';
	echo '<td align="center">НАЛОГ</td>';
	echo '<td align="center">---</td>';
	echo '</tr>';
	while($sostav = $q->fetch_assoc())
		{
		echo '<tr>';
		if($sostav['sex'] == 1) $color_login = $male; else $color_login = $female;
		$count++;
		echo '<td>'.$count.'</td>';
		echo '<td><a href="infa.php?mod=uzinfa&lgn='.$sostav['login'].'"><span style="color:'.$color_login.'">'.$sostav['login'].' ['.$sostav['lvl'].']</span></a></td>';
		echo '<td>';
		if($sostav['klan_status'] == 3) echo '<b>Глава</b>';
		elseif($sostav['klan_status'] == 2) echo '<b>Наместник</b>';
		elseif($sostav['klan_status'] == 1) echo '<b>Зам. Главы</b>';
		echo '</td>';
		$raznica = $_SERVER['REQUEST_TIME'] - $sostav['lastdate'];
		if($raznica <= 300) $onl = '<span style="color:'.$notice.'">В игре</span>';
		elseif($raznica <= 3600) $onl = ceil(($raznica) / 60).' мин. назад';
		elseif($raznica <= 86400) $onl = ceil(($raznica) / 3600).' чс. назад';
		else $onl = ceil(($raznica) / 86400).' дн. назад';
		echo '<td>'.$onl.'</td>';
		echo '<td>';
		echo $sostav['nalog'];
		echo '</td>';
		echo '<td>';
		if(3 <= $f['klan_status'] and $sostav['login'] != $f['login']) echo '<a href="klan.php?mod=del&lgn='.$sostav['login'].'">Исключить</a>';
		echo '</td>';
		echo '</tr>';
		}
	echo '</table></div>';
	knopka('klan.php', 'Вернуться', 1);
	fin();
	}

elseif($mod == 'rekrut')
	{
	if($f['klan_status'] < 2) msg2('Вы не можете работать с рекрутами',1);
	$count = 0;
	$q = $db->query("select login,lvl,sex,lastdate from `users` where klan_invite='{$f['klan']}' order by login;");
	if($go == 1)
		{
		$TestLGN = get_login($lgn);
		$lgn = $TestLGN['login'];
		if($TestLGN['klan_invite'] != $f['klan']) msg2('Персонаж '.$lgn.' не состоит в рекрутах вашего клана!',1);
		$q = $db->query("update `users` set klan_invite='' where id={$TestLGN['id']};");
		msg2('Персонаж '.$lgn.' успешно удален из рекрутов вашего клана.');
		}
	echo '<div class="board" style="text-align:left">';
	echo '<table border="1">';
	echo '<tr>';
	echo '<td>#</td>';
	echo '<td align="center">НИК</td>';
	echo '<td align="center">ЗАХОД</td>';
	echo '<td align="center">ДЕЙСТВИЕ</td>';
	echo '</tr>';
	while($sostav = $q->fetch_assoc())
		{
		echo '<tr>';
		if($sostav['sex'] == 1) $color_login = $male; else $color_login = $female;
		$count++;
		echo '<td>'.$count.'</td>';
		echo '<td><a href="infa.php?mod=uzinfa&lgn='.$sostav['login'].'"><span style="color:'.$color_login.'">'.$sostav['login'].' ['.$sostav['lvl'].']</span></a></td>';
		$raznica = $_SERVER['REQUEST_TIME'] - $sostav['lastdate'];
		if($raznica <= 300) $onl = '<span style="color:'.$notice.'">В игре</span>';
		elseif($raznica <= 3600) $onl = ceil(($raznica) / 60).' мин. назад';
		elseif($raznica <= 86400) $onl = ceil(($raznica) / 3600).' чс. назад';
		else $onl = ceil(($raznica) / 86400).' дн. назад';
		echo '<td>'.$onl.'</td>';
		echo '<td>';
		echo '<a href="klan.php?mod=rekrut&go=1&lgn='.$sostav['login'].'">Убрать</a>';
		echo '</td>';
		echo '</tr>';
		}
	echo '</table></div>';
	knopka('klan.php', 'Вернуться', 1);
	fin();
	}

elseif($mod == 'del')
	{
	if($f['klan_status'] < 3) msg('Вы не можете исключать игроков из клана',1);
	$TestLGN = get_login($lgn);
	$lgn = $TestLGN['login'];
	if(empty($ok))
		{
		msg2('Вы действительно хотите исключить '.$lgn.' из клана?');
		knopka('klan.php?mod=del&lgn='.$lgn.'&ok=1', 'Исключить', 1);
		knopka('klan.php?mod=sostav', 'Вернуться', 1);
		fin();
		}
	if($f['login'] == $TestLGN['login']) msg2('Нельзя исключить себя из клана',1);
	if($TestLGN['klan_status'] == 3) msg2('Нельзя исключить главу из клана',1);
	if($TestLGN['klan'] != $f['klan']) msg2('Этот персонаж не состоит в вашем клане.',1);
	if($TestLGN['status'] > 0) msg2('Персонаж в бою.',1);
	$q = $db->query("update `users` set klan='',klan_status=0,klan_time='{$t}' where login='{$lgn}' limit 1;");
	$q = $db->query("update `klans` set kazna=kazna+{$TestLGN['nalog']} where klan='{$f['klan']}' limit 1;");
	msg2('Персонаж '.$lgn.' успешно исключен из вашего клана.');
	knopka('klan.php', 'Вернуться', 1);
	fin();
	}

elseif($mod == 'spam')
	{
	if($f['klan_status'] < 1) msg2('Вы не можете давать массовую рассылку по клану.',1);
	if(empty($lgn))
		{
		echo '<div class="board" style="text-align:left">';
		echo '<form action="klan.php?mod=spam" method="POST">';
		echo 'Это сообщение получат все, кто не в блоке и были в игре в ближайшие 30 дней:<br/>';
		echo '<input type="text" name="lgn" style="width:80%"/><br/>';
		echo '<input type="submit" value="Далее"/></form></div>';
		knopka('klan.php', 'Вернуться', 1);
		fin();
		}
	$srok = 86400 * 30;
	$timer = $t - $srok;
	$count = 0;
	$mess = '<b>Далее спам</b>: '.ekr($lgn);
	$q = $db->query("select * from `users` where lastdate>'{$timer}' AND flag_blok=0 AND klan='{$f['klan']}' AND login<>'{$f['login']}';");
	while($b = $q->fetch_assoc())
		{
		$count++;
		$qq = $db->query("insert into `letters` values(0,0,'{$t}','{$b['login']}','{$f['login']}','{$mess}',0,0);");
		}
	msg2('Отправлено '.$count.' сообщений.');
	knopka('klan.php', 'Вернуться', 1);
	fin();
	}
elseif($mod == 'nalog')
	{
	if($f['klan_status'] < 2) msg2('Вы не можете собирать налог.',1);
	if(getDay($a['nalog_time']) < 7) msg2('Нельзя собирать налог чаще, чем раз в 7 дней. Будет доступно '.date('d.m.Y',$a['nalog_time'] + 86400 * 7),1);
	$q = $db->query("select sum(nalog) from `users` where klan='{$f['klan']}';");
	$b = $q->fetch_assoc();
	$nalog = $b['sum(nalog)'];
	$log = $f['login'].' ['.$f['lvl'].'] собирает налог в размере '.$nalog.' монет.';
	$q = $db->query("insert into `klan_log` values(0,'{$f['login']}','{$log}','{$f['klan']}','{$t}');");
	$q = $db->query("update `klans` set kazna=kazna+{$nalog},nalog_time='{$t}' where name='{$f['klan']}' limit 1;");
	$q = $db->query("update users set nalog=0 where klan='{$f['klan']}';");
	msg2('Собран налог в размере '.$nalog.' монет');
	knopka('klan.php', 'Вернуться', 1);
	fin();
	}
elseif($mod == 'mesto')
	{
	if($f['loc'] == 1 or $f['loc'] == 37 or $f['loc'] == 91) msg2('Нельзя построить замок в этом месте!');
	$q = $db->query("select name,loc,point from `klans` where loc={$f['loc']} or point={$f['loc']} limit 1;");
	$a = $q->fetch_assoc();
	if($q->num_rows==0)
		{
		if(empty($ok))
			{
			msg2('Вы уверены, что хотите построить клановый замок здесь?');
			knopka('klan.php?mod=mesto&ok=1','Отметить место для постройки', 1);
			knopka('loc.php', 'В игру', 1);
			fin();
			}
		$log = $f['login'].' ['.$f['lvl'].'] отмечает на карте место для постройки кланового замка.';
		$q = $db->query("insert into `klan_log` values(0,'{$f['login']}','{$log}','{$f['klan']}','{$t}');");
		$q = $db->query("update `klans` set point={$f['loc']} where name='{$f['klan']}' limit 1;");
		msg2("Вы отметили место для постройки кланового замка. Теперь вам нужно добывать камни для постройки.");
		knopka('loc.php', 'В игру', 1);
		fin();
		}
	else
		{
		msg2('Это место уже занято кланом '.$a['name'],1);
		}
}

elseif($mod=='kazna'){
if($f['klan_status'] <3) msg2('Не достаточно прав',1);
if(empty($go)){
msg2('Вы можете снять средства из казны на счет любого из соклан.<br>
<b>Внимание!!!</b> Комментарии должны быть осмысленными и выплаты должны быть заслуженные.');
msg2('<form action="klan.php?mod=kazna&go=1" method="post">
Сумма:<br>
<input type="text" name="summa" value="1"/><br>
Кому:<br>
<input type="text" name="komu"/><br>
Комментарий:<br>
<input type="text" name="kom"/><br>
<input type="submit" value="Перечислить"/></form>',1);
}
elseif($go==1){
if(empty($kom)) msg2('Не введен комментарий',1);
if($summ <0) $summ = 1;
if($klan['kazna'] < $summ) msg2('В казне не достаточно средств!',1);
$q = $db->query("select * from `users` where login='{$lgn1}' limit 1;");
$l = $q->fetch_assoc();
 if($l['klan'] != $f['klan']) msg2('Этот человек не является вашем сокланом!',1);
 
$log = 'Игрок '.$f['login'].' перевел на счет игрока '.$l['login'].' '.$summ.' монет из казны клана '.$f['klan'].' с комментарием: '.$kom.'';
$q =$db->query("insert into `klan_log` values(0,'{$f['login']}','{$log}','{$f['klan']}','{$t}');");
$q=$db->query("update `klans` set kazna=kazna-{$summ} where name='{$f['klan']}' limit 1;");
$q =$db->query("insert into `log_peredach` values(0,'{$f['login']}','{$log}','{$l['login']}','{$t}');");
$q=$db->query("update `users` set cu=cu+{$summ} where login='{$l['login']}' limit 1;");
msg2('На счет игрока '.$l['login'].' успешно переведено '.$summ.' монет из казны клана!',1);
}
}

fin();



?>
