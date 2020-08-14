<?php
##############
# 24.12.2014 #
##############

$title = 'Чат';
require_once('inc/top.php');
require_once('inc/check.php');
require_once('inc/head.php');
require_once('inc/hpstring.php');
// обход регистр глобаллс
if (isset($_REQUEST['room'])) $room = intval($_REQUEST['room']); else $room = 0;  //комната
if (isset($_REQUEST['start'])) $start = intval($_REQUEST['start']); else $start = 0; //страницы
if (isset($_REQUEST['mess'])) $mess = mb_substr(ekr($_REQUEST['mess']), 0, 1024, 'UTF-8'); else $mess = ''; //само сообщение
if (isset($_REQUEST['mod'])) $mod = $_REQUEST['mod']; else $mod = ''; //мод
if (isset($_REQUEST['lgn'])) $lgn = ekr($_REQUEST['lgn']); else $lgn = ''; //логин кому пишем в чате
if (isset($_REQUEST['ok'])) $ok = $_REQUEST['ok']; else $ok = '';  //ок
if (isset($_REQUEST['lich'])) $lich = 1; else $lich = 0;  //флаг привата
if (isset($_REQUEST['mid'])) $mid = intval($_REQUEST['mid']); else $mid = 0;   //ид сообщения в чате
$numb = 20;	//сообщений на странице

// определим где мы находимся
if (empty($room)) $room = $f['chatroom'];
if (empty($room)) $room = 1;
if ($room == 2 and empty($f['klan'])) $room = 1;   // если не в клане, то перенос в общий чат
if ($room == 3 and (empty($f['party']))) $room = 1;  // если не в пати, то перенос в общий чат
if ($room > 7 or $room < 1) $room = 1;
if ($room != $f['chatroom']) $q = $db->query("update `users` set chatroom={$room} where id={$f['id']} limit 1;");

// модули
if (!empty($mess) and mb_strlen($mess, 'UTF-8') > 1)
	{
	$p = 0;
	if(!empty($lgn))
		{
		$l = get_login($lgn);
		$lgn = $l['login'];
		if(!empty($lich)) $p = 1;
		}
	if ($f['ban'] > $t) msg2('У вас молча еще '.ceil(($f['ban'] - $t) / 60).' мин.', 1);
	$a = $db->query("SELECT message FROM `chat` WHERE login='{$f['login']}' and room='{$room}' ORDER BY id DESC LIMIT 1;");
	$b = $a->fetch_assoc();
	if ($mess != $b['message'])
		{
		$qq = $db->query("insert into `chat` values(0,'{$f['login']}','{$mess}','{$room}','{$lgn}','{$p}','{$f['sex']}','{$t}','{$f['klan']}','{$f['party']}','{$f['admin']}');");
		}
	$lgn = '';
	}
if ($mod == 'delete' and !empty($mid))
	{
	$q = $db->query("select * from `chat` where id={$mid} AND room={$room} and login<>'{$settings['bot']}' limit 1;");
	if($q->num_rows == 0) msg('Сообщение не найдено!', 1);
	$l = $q->fetch_assoc();
	if (!empty($l))
		{
		if ($f['login'] == $admin or $f['login'] == $l['login'] or 1 <= $f['admin'])
			{
			if(empty($ok))
				{
				msg('Вы уверены, что хотите удалить это сообщение "'.$l['message'].'" от <a href="infa.php?mod=uzinfa&lgn='.$l['login'].'">'.$l['login'].'</a>?');
				knopka('chat.php?mod=delete&mid='.$mid.'&ok=1', 'Удалить');
				knopka('chat.php', 'Вернуться');
				fin();
				}
			$q = $db->query("delete from `chat` WHERE id={$mid} AND room={$room} LIMIT 1;");
			}
		else
			{
			msg2('Вы не можете удалить это сообщение!', 1);
			}
		}
	}
if ($mod == 'clear')
	{
	if (empty($_REQUEST['ok']))
		{
		msg2('Все ваши сообщения в этой комнате будут удалены!');
		knopka('chat.php?mod=clear&ok=1', 'Продолжить');
		knopka('chat.php', 'В чат');
		fin();
		}
	$q = $db->query("delete from `chat` where login='{$f['login']}' AND room={$f['chatroom']};");
	}
if ($mod == 'listchat')
	{
	$timer = $t - 300;
	$q = $db->query("select login,chatdate,chatroom,sex from `users` where chatdate>'{$timer}' order by login asc;");
	if($q->num_rows == 0) msg('В чате никого нет!', 1);
	msg2('В чате сейчас:');
	while ($a = $q->fetch_assoc())
		{
		if ($a['sex'] == 1) $color = $male; else $color = $female;
		// сюда добавить цвет логинов для модеров
		echo '<div class="board2" style="text-align:left">';
		if ($f['login'] == $a['login']) echo '<b><span style="color:'.$color.'">'.$a['login'].'</span></b> ['.date('H:i, d-m', $a['chatdate']).'] ';
		else echo '<a href="chat.php?lgn='.$a['login'].'"><b><span style="color:'.$color.'">'.$a['login'].'</span></b></a> <a href="chat.php?mod=view&lgn='.$a['login'].'">[i]</a> ['.date('H:i, d-m', $a['chatdate']).'] ';
		if ($a['chatroom'] == 1) echo '(общий)';
		else if ($a['chatroom'] == 2) echo '(клан)';
		else if ($a['chatroom'] == 4) echo '(Торговый зал)';
		else if ($a['chatroom'] == 5) echo '(курилка)';
		
		else echo '(группа)';
		echo '</div>';
		}
	knopka('chat.php', 'В чат', 1);
	fin();
	}
if ($mod == 'listroom')
	{
	$timer = $t - 300;
	if ($f['chatroom'] == 1) $q = $db->query("select login,chatdate,chatroom,sex from `users` where chatdate>'{$timer}' and chatroom='{$f['chatroom']}' order by login asc;");
	else if ($f['chatroom'] == 2) $q = $db->query("select login,chatdate,chatroom,sex from `users` where chatdate>'{$timer}' and chatroom='{$f['chatroom']}' and klan='{$f['klan']}' order by login asc;");
	else if ($f['chatroom'] == 3) $q = $db->query("select login,chatdate,chatroom,sex from `users` where chatdate>'{$timer}' and chatroom='{$f['chatroom']}' and party='{$f['party']}'order by login asc;");
	elseif ($f['chatroom'] > 3) $q = $db->query("select login,chatdate,chatroom,sex from `users` where chatdate>'{$timer}' and chatroom='{$f['chatroom']}' order by login asc;");
	if($q->num_rows == 0) msg('В этой комнате никого нет', 1);
	msg2('Эту комнату сейчас читают:');
	while ($a = $q->fetch_assoc())
		{
		echo '<div class="board2" style="text-align:left">';
		if ($a['sex'] == 1) $color = $male; else $color = $female;
		// и сюда цвета модерам
		if ($f['login'] == $a['login']) echo '<b><span style="color:'.$color.'">'.$a['login'].'</span></b> ['.date('H:i', $a['chatdate']).'] ';
		else echo '<a href="chat.php?lgn='.$a['login'].'"><b><span style="color:'.$color.'">'.$a['login'].'</span></b></a> <a href="chat.php?mod=view&lgn='.$a['login'].'">[i]</a> ['.date('H:i', $a['chatdate']).']<br/>';
		echo '</div>';
		}
	knopka('chat.php', 'В чат', 1);
	fin();
	}
if ($mod == 'view')
	{
	if(empty($lgn)) msg('Вы не ввели логин!', 1);
	$lgn = ekr($lgn);
	echo '<div class="board" style="text-align:left">';
	$l = get_login($lgn);
	$lgn = $l['login']; // для оригинального написания ника
	if ($l['sex'] == 1) $color = $manacolor; else $color = $logincolor;
	// и опять сюда цвета модеров добавить
	echo '<b>Анкета</b><br/><br/>';
	echo 'Персонаж: <b><span style="color:'.$color.'">'.$l['login'].'</span></b>';
	if ($l['lastdate'] < $t - 300) echo ' [<span style="color:red">Off</span>]<br/>'; else echo ' [<span style="color:green">On</span>]<br/>';
	echo 'Имя: '.$l['name'].'<br/>';
	echo 'Пол: ';
	if ($l['sex'] == 1) echo 'Мужской'; else echo 'Женский';
	echo '<br/>';
	echo 'Уровень: '.$l['lvl'].'<br/>';
	if (!empty($l['klan'])) echo 'Клан: '.$l['klan'];
	echo '</div>';
	knopka('chat.php?lgn='.$l['login'], 'Написать сообщение');
	knopka('pm.php?mod=dialog&lgn='.$l['login'], 'Отправить письмо');
	knopka('infa.php?mod=uzinfa&lgn='.$l['login'], 'Перейти к полной анкете');
	if (1 <= $f['admin'] and $l['admin'] <= $f['admin']) knopka('adm.php?lgn='.$l['login'], 'Управление');
	if (1 <= $f['admin'] and $l['admin'] <= $f['admin'] and $l['flag_blok'] == 0)
		{
		if ($l['ban'] > $t) knopka('adm.php?mod=chatunban&lgn='.$l['login'], 'Снять молчу');
		else knopka('adm.php?mod=chatban&lgn='.$l['login'], 'Поставить молчу');
		}
	knopka('chat.php', 'Вернуться');
	fin();
	}
$timer = $t - 1728000;
$q = $db->query("delete from `chat` where timemess<'{$timer}';"); // удаление старых сообщений (2 суток)
// обновим время посещения чата
$q = $db->query("update `users` set chatdate='{$t}' where id='{$f['id']}' limit 1;");
if ($f['ban'] > $t)
	{
	echo '<div class="board3" align="center"><small>';
	echo '<br/>Молча еще '.ceil(($f['ban'] - $t) / 60).' мин.';
	echo '</small></div>';
	}
// форма сообщения
echo '<div class="board">';
echo '<form method="POST" action="chat.php">';
if(!empty($lgn))
	{
	$l = get_login($lgn);
	$lgn = $l['login'];
	echo 'Пишем <b>'.$lgn.'</b> <a href="chat.php">[x]</a> [<input type="checkbox" name="lich" value="1"/>Приватно]:<br/>';
	echo '<input type="hidden" name="lgn" value="'.$lgn.'">';
	}
$random = mt_rand(11111111, 99999999);
echo '<input type="text" name="mess" maxlength="1024" style="width:80%"/>';
echo '<input type="submit" value="Ok"';
if ($f['ban'] > $t) echo ' disabled="disabled" style="color:gray" ';
echo'<br/><a href="chat.php?r='.$random.'" accesskey="a">Обновить</a>';
echo'</form></div>';
//knopka('chat.php', 'Обновить');
// запросы для разных комнат разные...
if ($room == 1 or $room > 3) // общий
	{
	$a = $db->query("select count(*) from `chat` where room='{$room}' AND (flag_privat=0 OR (flag_privat=1 AND (login='{$f['login']}' OR privat='{$f['login']}')));");
	$all_chat = $a->fetch_assoc(); // всего сообщений
	$all_chat = $all_chat['count(*)'];
	if ($start > intval($all_chat / $numb)) $start = intval($all_chat / $numb);
	if ($start < 0) $start = 0;
	$limit = $start * $numb;
	$q = $db->query("select * from `chat` where room='{$room}' AND (flag_privat=0 OR (flag_privat=1 AND (login='{$f['login']}' OR privat='{$f['login']}'))) order by id desc limit {$limit},{$numb};");
	}
elseif ($room == 2) // клан зал
	{
	$a = $db->query("select count(*) from `chat` where room='{$room}' AND (flag_privat=0 OR (flag_privat=1 AND (login='{$f['login']}' OR privat='{$f['login']}'))) AND klan='{$f['klan']}';");
	$all_chat = $a->fetch_assoc(); // всего сообщений
	$all_chat = $all_chat['count(*)'];
	if ($start > intval($all_chat / $numb)) $start = intval($all_chat / $numb);
	if ($start < 0) $start = 0;
	$limit = $start * $numb;
	$q = $db->query("select * from `chat` where room='{$room}' AND (flag_privat=0 OR (flag_privat=1 AND (login='{$f['login']}' OR privat='{$f['login']}'))) AND klan='{$f['klan']}' order by id desc limit {$limit},{$numb};");
	}
elseif ($room == 3) // пати
	{
	$a = $db->query("select count(*) from `chat` where room='{$room}' AND (flag_privat=0 OR (flag_privat=1 AND (login='{$f['login']}' OR privat='{$f['login']}'))) AND party='{$f['party']}' AND lvl='{$f['lvl']}';");
	$all_chat = $a->fetch_assoc(); // всего сообщений
	$all_chat = $all_chat['count(*)'];
	if ($start > intval($all_chat / $numb)) $start = intval($all_chat / $numb);
	if ($start < 0) $start = 0;
	$limit = $start * $numb;
	$q = $db->query("select * from `chat` where room='{$room}' AND (flag_privat=0 OR (flag_privat=1 AND (login='{$f['login']}' OR privat='{$f['login']}'))) AND party='{$f['party']}' order by id desc limit {$limit},{$numb};");
	}
// формирование сообщений
$count = 0;
while ($a = $q->fetch_assoc())
	{
	$sex = $a['sex'];
	$adm = $a['admin'];
	$mess_id = $a['id'];
	$timemess = $a['timemess'];
	$login = $a['login'];
	$message = link_it($a['message']);
	if($f['grafika'] == 1 or $f['grafika'] == 2) $message = smile($message);
	$privat = $a['privat'];
	$lichn = $a['flag_privat'];
	if ($sex == 1) $color_login = $male;
	else $color_login = $female;
	// вот тут цвета ников у модеров
	if($adm == 1) $color_login = 'white';
	if($adm == 2) $color_login = 'white';
	if($adm == 3) $color_login = 'silver';
	if($adm > 3) $color_login = 'gold';
	echo '<div class="board2" style="text-align:left;">';
	if($login != $settings['bot'])
		{
		echo '['.Date('H:i, d-m', $timemess).'] ';
		if ($f['login'] == $login) echo '<b><span style="color:'.$color_login.'">'.$login.'</span></b> ';
		else echo '<a href="chat.php?mod=view&lgn='.$login.'"><b><span style="color:'.$color_login.'">'.$login.'</span></b></a>
		<a href="chat.php?lgn='.$login.'">[Ответить]</a>';
		if (mb_strtolower($f['login'], 'UTF-8') == mb_strtolower($login, 'UTF-8') or $f['login'] == $admin or 1 <= $f['admin'])
			{
			echo ' <a href="chat.php?mod=delete&mid='.$mess_id.'">[x]</a>';
			}
		echo '<br/>';
		}
	if ($lichn == 1)
		{
		echo '<b>[!]</b> <b>'.$privat.'</b>, <b><font color="#000000">';
		echo $message;
		echo '</font></b>';
		}
	else
		{
		if (!empty($privat)) echo '<b>'.$privat.'</b>, ';
		echo $message;
		}
	$count++;
	echo '</div>';
	}
$timer = $t - 300;
$q = $db->query("select count(*) from `users` where chatdate>'{$timer}';");
$res = $q->fetch_assoc();
$res = $res['count(*)'];
knopka('chat.php?mod=listchat', 'В чате: '.$res);
if ($room == 1 or $room > 3) $q = $db->query("select count(*) from `users` where chatdate>'{$timer}' and chatroom={$room};");
else if ($room == 2) $q = $db->query("select count(*) from `users` where chatdate>'{$timer}' and chatroom={$room} and klan='{$f['klan']}';");
else if ($room == 3) $q = $db->query("select count(*) from `users` where chatdate>'{$timer}' and chatroom={$room} and party='{$f['party']}';");
$res = $q->fetch_assoc();
$res = $res['count(*)'];
knopka('chat.php?mod=listroom', 'В комнате: '.$res);
if($all_chat > $numb)
	{
	echo '<div class="board">';
	if ($start > 0) echo '<a href="chat.php?start='.($start - 1).'" class="navig"><-Назад</a>'; else echo '<a href="#" class="navig"> <-Назад</a>';
	echo ' | ';
	if ($limit + $numb < $all_chat) echo '<a href="chat.php?start='.($start + 1).'" class="navig">Вперед-></a>'; else echo ' <a href="#" class="navig"> Вперед-></a>';
	echo '</div>';
	}
echo '<div class="board">';
echo '<form action="chat.php" method="POST">';
echo '<select name="room" onchange="this.form.submit()">';
echo '<option '; if($room == 1) echo 'selected '; echo 'value="1">Общий зал</option>';
if (!empty($f['klan'])) {echo '<option '; if($room == 2) echo 'selected '; echo 'value="2">Клан зал</option>';}
if (!empty($f['party'])) {echo '<option '; if($room == 3) echo 'selected '; echo 'value="3">Пати зал</option>';}
echo '<option '; if($room == 4) echo 'selected '; echo 'value="4">Торговый зал</option>';
echo '<option '; if($room == 5) echo 'selected '; echo 'value="5">Курилка</option>';
echo '</select></form></div>';
echo '<div class="menu">';
if ($f['admin'] >= 3) echo '<a href="adm.php?mod=chatclear">Чистка чатов</a> - ';
if ($f['admin'] >= 1) echo '<a href="adm.php?mod=chatroomclear">Чистка комнаты</a> - ';
echo '<a href="chat.php?mod=clear">Чистка сообщений</a> - ';
echo '<a href="lib.php?mod=smile">Справка по смайлам</a>';
echo '</div>';
fin();
?>
