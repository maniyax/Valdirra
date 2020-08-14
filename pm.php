<?php
$title = 'Личные сообщения';
require_once('inc/top.php'); // вывод на экран
require_once('inc/check.php'); // вход в игру
require_once('inc/head.php');
require_once('inc/hpstring.php');
// чистка писем старше 7 суток (экономим место)
$timer = $t - 7 * 86400;
$q = $db->query("delete from `letters` where timemess<'{$timer}';");

// инициализация переменных
$mod = isset($_REQUEST['mod']) ? $_REQUEST['mod'] : '';   //мод
$kont = isset($_REQUEST['kont']) ? $_REQUEST['kont'] : '';   //мод
$delete = isset($_REQUEST['delete']) ? 1 : 0;   //удаление 1 сообщения из диалога
$start = isset($_REQUEST['start']) ? intval($_REQUEST['start']) : 0; //страница
$ok = isset($_REQUEST['ok']) ? $_REQUEST['ok'] : 0;   //ок

$lid = isset($_REQUEST['lid']) ? intval($_REQUEST['lid']) : 0;   //прошлое сообщение, заполняется при ответе на письмо
$letter_id = isset($_REQUEST['letter_id']) ? intval($_REQUEST['letter_id']) : 0; //ид письма
$lgn = isset($_REQUEST['lgn']) ? ekr($_REQUEST['lgn']) : '';   //логин кому пишем
$mess = isset($_REQUEST['mess']) ? mb_substr(ekr($_REQUEST['mess'], 'UTF-8'), 0, 2048) : ''; //само сообщение

$count = 0; //счётчик
$numb = 15; //кол-во диалогов на странице за раз
//if($f['loc'] != 149) msg2('Вы не в городе.',1);
//if($f['loc'] != 149 and $items->count_base_item($f['login'], 760) ==0) msg2('У вас нет почтового ящика.',1);



echo '<div class="board3">
<a href="pm.php">Письма</a> - 
<a href="pm.php?mod=write">Написать</a> - 
<a href="pm.php?mod=ignor">Игнор</a> - 
<a href="pm.php?mod=kont">Контакт</a> - 
<a href="pm.php?mod=clear">Чистка</a></div>';

if($mod == 'clear')
	{ // чистка прочитанных сообщений
	if(empty($ok))
		{
		msg2('Все ваши прочитанные сообщения будут удалены! Продолжить?');
		knopka('pm.php?mod=clear&ok=1', 'Удалить');
		knopka('pm.php', 'Вернуться');
		fin();
		}
	$q = $db->query("DELETE FROM `letters` WHERE (login='{$f['login']}' or login_from='{$f['login']}') AND read_flag=1;");
	msg2('Ваши прочитанные сообщения удалены.');
	knopka('pm.php', 'Вернуться');
	fin();
	}

if($mod == 'delspisok')
	{ // удаление диалога
	$l = get_login($lgn);
	$lgn = $l['login'];
	if (empty($ok))
		{
		msg2('Вы уверены, что хотите удалить ваш диалог с '.$lgn.'?');
		knopka('pm.php?mod=delspisok&lgn='.$lgn.'&ok=1', 'Удалить');
		knopka('pm.php?mod=dialog&lgn='.$lgn, 'Вернуться к диалогу');
		fin();
		}
	$q = $db->query("delete from `letters` where (login='{$f['login']}' AND login_from='{$lgn}') or (login='{$lgn}' AND login_from='{$f['login']}');");
	msg2('Диалог с персонажем '.$lgn.' успешно удален.');
	knopka('pm.php', 'Вернуться');
	fin();
	}

if($mod == 'write')
	{
	echo '<div class="board" style="text-align:left">';
	echo '<form action="pm.php?mod=dialog" method="POST">';
	echo 'Кому будем писать:<br/>';
	echo '<input type="text" name="lgn" maxlength="25" size="25" /><br/>';
	echo '<input type="submit" value="Написать"></form></div>';
	knopka('pm.php', 'Вернуться');
	fin();
	}

if($mod == 'dialog')
	{
	$l = get_login($lgn);
	$lgn = $l['login'];
	if ($lgn == $f['login']) msg2('Нельзя писать самому себе', 1);
	if(!empty($delete))
		{
		if ($letter_id <= 0) msg2('Письмо с таким ID не найдено', 1);
		$q = $db->query("DELETE FROM letters WHERE id='{$letter_id}' AND (login='{$f['login']}' OR login_from='{$f['login']}') LIMIT 1;");
		msg2('Сообщение удалено!');
		}
	$ignor = explode('|', $f['ignor']);
	$kont = explode('|', $f['kont']);
	$str = '';
	if (in_array($lgn, $ignor) and $l['admin'] == 0) $str = '<br/><br/><span style="color:'.$female.'"><b>ВНИМАНИЕ!!!<b></span> Персонаж '.$lgn.' у вас в игноре, он не сможет ответить на ваше сообщение!';
	echo '<div class="board">';
	echo '<form action="pm.php?mod=dialog&lgn='.$lgn.'" method="POST">';
	echo '<input type="text" name="mess" maxlength="1024" style="width:80%"/>';
	echo '<input type="submit" value="Ok"/><br/>';
	echo '</form>'.$str.'</div>

';
msg('<a href="pm.php?mod=dialog&lgn='.$lgn.'" accesskey="a">Обновить диалог</a>');
	knopka('lib.php?mod=smile', 'Смайлы');
	if (!empty($mess))
		{
		$ignor = explode('|', $l['ignor']);
		if (in_array($f['login'], $ignor) and $f['admin'] == 0 and $l['admin'] == 0) msg2('Вы находитесь в игнор-листе у '.$lgn.', сообщение не было отправлено.', 1);
		$a = $db->query("select mess from `letters` WHERE login_from='{$f['login']}' ORDER BY id DESC LIMIT 1;");
		$b = $a->fetch_assoc(); // защита от повторного письма
		if($b['mess'] != $mess)
			{
			$qq = $db->query("insert into `letters` values(0,'{$lid}','{$t}','{$lgn}','{$f['login']}','{$mess}',0,0);");
			}
		}
	$q = $db->query("select count(*) from `letters` where (login='{$f['login']}' AND login_from='{$lgn}') or (login='{$lgn}' AND login_from='{$f['login']}');");
	$a = $q->fetch_assoc();
	$all_log = $a['count(*)'];
	if ($start > intval($all_log / $numb)) $start = intval($all_log / $numb);
	if ($start < 0) $start = 0;
	$limit = $start * $numb;
	$count = $limit;
	$q = $db->query("select * from `letters` where (login='{$f['login']}' AND login_from='{$lgn}') or (login='{$lgn}' AND login_from='{$f['login']}') order by id desc limit {$limit},{$numb};");
	while ($lett = $q->fetch_assoc())
		{
		$count++;
		if ($lett['login'] == $f['login'] and $lett['read_flag'] == 0) $qq = $db->query("update `letters` set read_flag=1 where id={$lett['id']} limit 1;");
		echo '<div class="board2" style="text-align:left">';
		if($f['login'] == $lett['login_from'] or $lett['login_from'] == $settings['bot']) echo $lett['login_from'];
		else echo '<a href="infa.php?mod=uzinfa&lgn='.$lett['login_from'].'">'.$lett['login_from'].'</a>';
		echo '<small> ['.date('d-m-Y H:i', $lett['timemess']).']';
if($f['admin'] >=3)		echo ' <a href="pm.php?delete=1&letter_id='.$lett['id'].'&mod=dialog&lgn='.$lgn.'">[x]</a>';
		if ($lett['login_from'] == $f['login'] and $lett['read_flag'] == 0) echo ' <b>[Непрочитано]</b>';
		if ($lett['login'] == $f['login'] and $lett['read_flag'] == 0) echo ' <b>[Новое]</b>';
		echo '</small><br/>';
$lett['mess'] = link_it($lett['mess']);
if($f['grafika'] == 1 or $f['grafika'] == 2) $lett['mess'] = smile($lett['mess']);
echo $lett['mess'];
echo '</div>';
		}
	if ($count > 0) knopka('pm.php?mod=delspisok&lgn='.$lgn, 'Удалить диалог с '.$lgn);
	if (!in_array($lgn, $kont)) knopka('pm.php?mod=kont&ok=1&lgn='.$lgn, 'Добавить '.$lgn.' в контакты');
	if (!in_array($lgn, $ignor)) knopka('pm.php?mod=ignor&ok=1&lgn='.$lgn, 'Добавить '.$lgn.' в игнор');
	if($all_log > $numb)
		{
		echo '<div class="board">';
		if ($start > 0) echo '<a href="pm.php?mod=dialog&lgn='.$lgn.'&start='.($start - 1).'" class="navig"><-Назад</a>'; else echo '<a href="#" class="navig"> <-Назад</a>';
		echo ' | ';
		if ($limit + $numb < $all_log) echo '<a href="pm.php?mod=dialog&lgn='.$lgn.'&start='.($start + 1).'" class="navig" >Вперед-></a>'; else echo ' <a href="#" class="navig"> Вперед-></a>';
		echo '</div>';
		}
	fin();
	}

if($mod == 'ignor')
	{
	if (empty($ok))
		{
		if (empty($f['ignor'])) msg2('Ваш игнор-лист пуст.');
		else
			{
			msg2('Нажмите на логин игрока, чтобы удалить его из игнор-листа:');
			$ignor = explode("|", $f['ignor']);
			natcasesort($ignor); // сортировка по алфавиту
			$razm = sizeof($ignor);
			for ($i = 0; $i < $razm; $i++)
				{
				if (!empty($ignor[$i]))
					{
					knopka('pm.php?mod=ignor&ok=2&lgn='.$ignor[$i], $ignor[$i]);
					}
				}
			}
		echo '<div class="board" style="text-align:left">
		<form action="pm.php?mod=ignor&ok=1" method="POST">
		Введите ник кого хотите добавить в игнор:<br/>
		<input type="text" name="lgn"/><br/>
		<input type="submit" value="Далее"/></form></div>';
		knopka('pm.php', 'Вернуться');
		fin();
		}
	elseif ($ok == 1)
		{
		$l = get_login($lgn);
		$lgn = $l['login'];
		if ($lgn == $f['login']) msg2('Вы не можете добавить себя в игнор', 1);
		$ign = explode("|", $f['ignor']);
		if (100 <= sizeof($ign)) msg2('Разрешено не более 100 человек', 1);
		if (in_array($lgn, $ign)) msg2($lgn.' уже у вас в игноре.', 1);
		$f['ignor'] .= $lgn.'|';
		$q = $db->query("update `users` set ignor='{$f['ignor']}' where id='{$f['id']}' limit 1;");
		msg2($lgn.' добавлен в ваш игнор-лист.');
		knopka('pm.php?mod=ignor', 'Вернуться', 1);
		fin();
		}
	elseif ($ok == 2)
		{
		$l = get_login($lgn);
		$lgn = $l['login'];
		$ignor = explode("|", $f['ignor']);
		if (!in_array($lgn, $ignor)) msg2($lgn.' не найден в вашем игнор-листе', 1);
		unset($ignor[array_search($lgn, $ignor)]);
		$f['ignor'] = implode('|', $ignor);
		$q = $db->query("update `users` set ignor='{$f['ignor']}' where id='{$f['id']}' limit 1;");
		msg2($lgn.' удален из вашего игнор-листа.');
		knopka('pm.php?mod=ignor', 'Вернуться', 1);
		fin();
		}
	fin();
	}

if($mod == 'kont')
	{
	if (empty($ok))
		{
		if (empty($f['kont'])) msg2('Ваш контакт-лист пуст.');
		else
			{
			msg2('Ваш контакт-лист:');
			echo '<div class="board" style="text-align:left">';
			$kont = explode("|", $f['kont']);
			natcasesort($kont); // сортировка по алфавиту
			$razm = sizeof($kont);
			for ($i = 0; $i < $razm; $i++)
				{
				if (!empty($kont[$i]))
					{
					echo '<a href="pm.php?mod=kont&ok=2&lgn='.$kont[$i].'">[ x ]</a> ';
					echo '<a href="pm.php?mod=dialog&lgn='.$kont[$i].'"><b>'.$kont[$i].'</b></a><br/>';
					}
				}
			echo '</div>';
			}
		echo '<div class="board" style="text-align:left">
		<form action="pm.php?mod=kont&ok=1" method="POST">
		Введите ник кого хотите добавить в контакты:<br/>
		<input type="text" name="lgn"/><br/>
		<input type="submit" value="Далее"/></form></div>';
		knopka('pm.php', 'Вернуться');
		fin();
		}
	elseif ($ok == 1)
		{
		$l = get_login($lgn);
		$lgn = $l['login'];
		if ($lgn == $f['login']) msg2('Вы не можете добавить себя в контакты', 1);
		$ign = explode("|", $f['kont']);
		if (100 <= sizeof($ign)) msg2('Разрешено не более 100 человек', 1);
		if (in_array($lgn, $ign)) msg2($lgn.' уже у вас в контактах.', 1);
		$f['kont'] .= $lgn.'|';
		$q = $db->query("update `users` set kont='{$f['kont']}' where id='{$f['id']}' limit 1;");
		msg2($lgn.' добавлен в ваши контакты.');
		knopka('pm.php?mod=kont', 'Вернуться', 1);
		fin();
		}
	elseif ($ok == 2)
		{
		$l = get_login($lgn);
		$lgn = $l['login'];
		$kont = explode("|", $f['kont']);
		if (!in_array($lgn, $kont)) msg2($lgn.' не найден в ваших контактах', 1);
		unset($kont[array_search($lgn, $kont)]);
		$f['kont'] = implode('|', $kont);
		$q = $db->query("update `users` set kont='{$f['kont']}' where id='{$f['id']}' limit 1;");
		msg2($lgn.' удален из ваших контактов.');
		knopka('pm.php?mod=kont', 'Вернуться', 1);
		fin();
		}
	fin();
	}
$q = $db->query("select if (login='{$f['login']}',login_from,login) as log from `letters` where (login='{$f['login']}' or login_from='{$f['login']}') group by log;");
$all_inb = $q->num_rows;
if ($start > intval($all_inb / $numb)) $start = intval($all_inb / $numb);
if ($start < 0) $start = 0;
$limit = $start * $numb;
if (empty($all_inb)) msg2('Нет сообщений', 1);
$q = $db->query("select if (login='{$f['login']}',login_from,login) as log,max(id) as id from `letters` where (login='{$f['login']}' or login_from='{$f['login']}') group by log order by id desc limit {$limit},{$numb};");
while ($m = $q->fetch_assoc())
	{
	$count++;
	$qq = $db->query("select count(*) from letters where login='{$f['login']}' and login_from='{$m['log']}' and read_flag=0");
	$c = $qq->fetch_assoc();
	$st = '';
	$col = $c['count(*)'];
	if ($col > 0) $st .= '<b>';
	$st .= $m['log'];
	if ($col > 0) $st .= ' +'.$col.'</b>';
	knopka('pm.php?mod=dialog&lgn='.$m['log'], $st);
	}
echo '<div class="board">';
if ($start > 0) echo '<a href="pm.php?start='.($start - 1).'" class="navig"><-Назад</a>'; else echo '<a href="#" class="navig"><-Назад</a>';
echo ' | ';
if ($limit + $numb < $all_inb) echo '<a href="pm.php?start='.($start + 1).'" class="navig">Вперед-></a>'; else echo '<a href="#" class="navig">Вперед-></a>';
echo '</div>';
fin();

require_once('inc/function.php');

?>
