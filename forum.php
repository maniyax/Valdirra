<?php
##############
# 24.12.2014 #
##############

require_once('inc/top.php');	// вывод на экран
require_once('inc/check.php');	// вход в игру
require_once('inc/head.php');
//блок инициализации переменных
$razdel = isset($_REQUEST['razdel']) ? intval($_REQUEST['razdel']) : 0;
$topic = isset($_REQUEST['topic']) ? intval($_REQUEST['topic']) : 0;
$start = isset($_REQUEST['start']) ? intval($_REQUEST['start']) : 0;
$mod = isset($_REQUEST['mod']) ? $_REQUEST['mod'] : '';
$ok = isset($_REQUEST['ok']) ? $_REQUEST['ok'] : 0;
$name = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';
$mess = isset($_REQUEST['mess']) ? $_REQUEST['mess'] : '';
$write = isset($_REQUEST['write']) ? $_REQUEST['write'] : '';
$lgn = isset($_REQUEST['lgn']) ? $_REQUEST['lgn'] : '';
$cid = isset($_REQUEST['cid']) ? intval($_REQUEST['cid']) : 0;
$full = isset($_REQUEST['full']) ? 1 : 0;
//конец блока инициализации переменных

require_once('inc/hpstring.php');

if($mod == 'allclear' and 3 <= $f['admin'])
	{
	if(empty($ok))
		{
		msg2('Все темы во всех разделах будут удалены! Продолжить?');
		knopka('forum.php?mod=allclear&ok=1', 'Удалить', 1);
		knopka('forum.php', 'Вернуться', 1);
		fin();
		}
	$q = $db->query("truncate table forum_topic;");
	$q = $db->query("truncate table forum_comm;");
	}

$numb = 10;
$count = 0;
if($mod == 'opentopic')
	{
	if($f['lvl'] < 2) msg2('Создавать темы можно со 2 уровня', 1);
	if(empty($ok))
		{
		echo '<div class="board" style="text-align:left">';
		echo '<form action="forum.php?razdel='.$razdel.'&mod=opentopic&ok=1" method="POST">';
		echo 'Название темы (3..50 симв)<br/>';
		echo '<input type="text" name="name" maxlength=50 style="width:80%"/><br/>';
		echo 'Содержимое темы (3..5000 симв)<br/>';
		echo '<textarea name="mess" maxlength=5000 rows="10" style="width:80%;"></textarea><br/>';
		echo 'Раздел: ';
		echo '<select name="razdel">';
		if($f['admin'] > 1) echo '<option value="1">Администрация</option>';
		echo '<option value="2">Предложения</option>';
		echo '<option value="3">Помощь</option>';
		echo '<option value="4">Баги/Ошибки</option>';
		echo '<option value="5">Общение</option>';
		echo '<option value="6">Творчество</option>';
		echo '<input type="submit" value="Создать тему"/></form>';
		echo '<br/><br/><small>* Не забываем, что тема должна строго соответствовать тематике выбранного раздела</small></div>';
		knopka('forum.php', 'Главная форума');
		fin();
		}
	if(mb_strlen($name, 'UTF-8') < 3 or 50 < mb_strlen($name, 'UTF-8')) msg2('Ошибка заполения названия темы.',1);
	if(mb_strlen($mess, 'UTF-8') < 3 or 5000 < mb_strlen($mess, 'UTF-8')) msg2('Ошибка заполения тела темы.',1);
	if(empty($razdel) or ($razdel == 1 and $f['admin'] < 2) or ($razdel < 1 or $razdel > 6)) msg2('Ошибка выбора раздела', 1);
	$name = ekr($name);
	$mess = ekr($mess);
	$a = $db->query("SELECT * FROM `forum_topic` WHERE login='{$f['login']}' ORDER BY id DESC LIMIT 1;");
	$b = $a->fetch_assoc();
	if($name != $b['name'])
		{
		$q = $db->query("insert into `forum_topic` values(0,'{$f['login']}',{$razdel},'{$name}','{$t}','{$mess}','{$t}',0);");
		$topic = $db->insert_id();
		msg2('Тема "'.$name.'" успешно добавлена!');
		knopka('forum.php?razdel='.$razdel.'&topic='.$topic, 'Перейти в тему', 1);
		knopka('forum.php', 'Главная форума', 1);
		fin();
		}
	fin();
	}
if(empty($topic))
	{
	$s = '<small>';
	if($razdel == 1) $s .= 'Администрация';
	if($razdel == 2) $s .= 'Предложения';
	if($razdel == 3) $s .= 'Помощь';
	if($razdel == 4) $s .= 'Баги/ошибки';
	if($razdel == 5) $s .= 'Общение';
	if($razdel == 6) $s .= 'Творчество';
	$s .= '</small>';
	if(!empty($razdel)) msg($s);
	if(empty($razdel)) $q = $db->query("select count(*) from `forum_topic`;");
	else $q = $db->query("select count(*) from `forum_topic` where razdel={$razdel};");
	$a = $q->fetch_assoc();
	$all = $a['count(*)'];
	if($start > intval($all / $numb)) $start = intval($all / $numb);
	if($start < 0) $start = 0;
	$limit = $start * $numb;
	$count = $limit;
	if(empty($razdel)) $q = $db->query("select * from `forum_topic` order by `lastcomm` desc limit {$limit},{$numb};");
	else $q = $db->query("select * from `forum_topic` where razdel='{$razdel}' order by `lastcomm` desc limit {$limit},{$numb};");
	while($ft = $q->fetch_assoc())
		{
		$b = $db->query("select count(*) from `forum_comm` where id_topic='{$ft['id']}';");
		$cc = $b->fetch_assoc();
		$cc = $cc['count(*)'];
		echo '<div class="board2" style="text-align:left">
		<a href="forum.php?razdel='.$razdel.'&topic='.$ft['id'].'"><b><span style="color:#363636">'.$ft['name'].'</span></b></a>';
	    if($cc > 0) echo ' ('.$cc.')';
		echo ' (by '.$ft['login'].')';
		if(empty($razdel))
			{
			echo ' [<b>';
			if($ft['razdel'] == 1) echo 'Администрация';
			if($ft['razdel'] == 2) echo 'Предложения';
			if($ft['razdel'] == 3) echo 'Помощь';
			if($ft['razdel'] == 4) echo 'Баги/ошибки';
			if($ft['razdel'] == 5) echo 'Общение';
			if($ft['razdel'] == 6) echo 'Творчество';
			echo '</b>]';
			}
		if($ft['flag_close'] == 1) echo ' <img src="/pic/key.png" alt=""/>';
		echo '<br/>';
		echo '<small>созд. '.Date('d.m.Y H:i', $ft['timetopic']);
		echo '</small>';
		echo '</div>';
		$count++;
		}
	if($all > $numb)
		{
		echo '<div class="board">';
		if($start > 0) echo '<a href="forum.php?razdel='.$razdel.'&start='.($start - 1).'" class="navig"><-Назад</a>'; else echo '<a href="#" class="navig"> <-Назад</a>';
		echo ' | ';
		if($limit + $numb < $all) echo '<a href="forum.php?razdel='.$razdel.'&start='.($start + 1).'" class="navig" >Вперед-></a>'; else echo ' <a href="#" class="navig"> Вперед-></a>';
		echo '</div>';
		}
	if(empty($razdel))
		{
		echo '<div class="board"><form action="forum.php" method="GET">';
		echo '<select name="razdel" onchange="this.form.submit()">';
		echo '<option value="1">Администрация</option>';
		echo '<option value="2">Предложения</option>';
		echo '<option value="3">Помощь</option>';
		echo '<option value="4">Баги/ошибки</option>';
		echo '<option value="5">Общение</option>';
		echo '<option value="6">Творчество</option>';
		echo '<select></form></div>';
		}
	knopka('forum.php?mod=opentopic', 'Создать тему', 1);
	knopka('forum.php', 'Главная форума', 1);
	//конец блока правил
	fin();
	}
else
	{
	if($topic <= 0) msg2('Тема не найдена', 1);
	if($mod == 'edit')
		{
		$q = $db->query("select * from `forum_topic` where id='{$topic}' limit 1;");
		$ed = $q->fetch_assoc() or msg2('Тема не найдена', 1);
		if($f['admin'] < 2 and $f['login'] != $ed['login']) msg2('Вы не можете редактировать эту тему',1);
		if($ed['flag_close'] == 1 && $f['admin'] < 3) msg2('Нельзя редактировать закрытые темы',1);
		if(empty($ok))
			{
			echo '<div class="board" style="text-align:left">';
			echo '<form action="forum.php?razdel='.$razdel.'&topic='.$topic.'&mod=edit&ok=1" method="POST">';
			echo 'Название темы (3..50 симв)<br/>';
			echo '<input type="text" name="name" maxlength=50 value="'.$ed['name'].'" style="width:80%"><br/>';
			echo 'Содержимое темы (3..5000 симв)<br/>';
			echo '<textarea name="mess" maxlength=5000 rows="10" style="width:80%;">'.$ed['message'].'</textarea><br/>';
			echo '<input type="submit" value="Редактировать"/></form></div>';
			knopka('forum.php?razdel='.$razdel.'&topic='.$topic, 'Вернуться', 1);
			knopka('forum.php', 'Главная форума', 1);
			fin();
			}
		if(mb_strlen($name, 'UTF-8') < 3 or 50 < mb_strlen($name, 'UTF-8')) msg2('Ошибка заполения названия темы.',1);
		if(mb_strlen($mess, 'UTF-8') < 3 or 5000 < mb_strlen($mess, 'UTF-8')) msg2('Ошибка заполения тела темы.',1);
		$name = ekr($name);
		$mess = ekr($mess);
		$q = $db->query("update `forum_topic` set name='{$name}',message='{$mess}',lastcomm='{$t}' where razdel='{$razdel}' AND id='{$topic}' limit 1;");
		msg2('Тема успешно отредактирована!');
		knopka('forum.php?razdel='.$razdel.'&topic='.$topic, 'В тему', 1);
		knopka('forum.php', 'Главная форума', 1);
		fin();
		}
	elseif($mod == 'close')
		{
		$q = $db->query("select * from `forum_topic` where id='{$topic}' limit 1;");
		$ed = $q->fetch_assoc() or msg2('Тема не найдена',1);
		if($f['admin'] < 1 and $f['login'] != $ed['login']) msg2('Вы не можете закрыть эту тему',1);
		if($ed['flag_close'] == 1) msg2('Эта тема уже закрыта', 1);
		if(empty($ok))
			{
			msg2('Вы действительно хотите закрыть эту тему?');
			knopka('forum.php?mod=close&ok=1&razdel='.$razdel.'&topic='.$topic, 'Закрыть', 1);
			knopka('forum.php?razdel='.$razdel.'&topic='.$topic, 'Вернуться', 1);
			knopka('forum.php', 'Главная форума', 1);
			fin();
			}
		$q = $db->query("update `forum_topic` set flag_close=1 where id='{$topic}' AND razdel='{$razdel}' limit 1;");
		msg2('Тема закрыта.');
		knopka('forum.php?razdel='.$razdel.'&topic='.$topic, 'В тему', 1);
		knopka('forum.php', 'Главная форума', 1);
		fin();
		}
	elseif($mod == 'open')
		{
		$q = $db->query("select * from `forum_topic` where id='{$topic}' limit 1;");
		$ed = $q->fetch_assoc() or msg2('Тема не найдена',1);
		if($f['admin'] < 1) msg2('Вы не можете открыть эту тему',1);
		if($ed['flag_close'] == 0) msg2('Тема не закрыта',1);
		if(empty($ok))
			{
			msg2('Вы действительно хотите открыть эту тему?');
			knopka('forum.php?mod=open&ok=1&razdel='.$razdel.'&topic='.$topic, 'Открыть', 1);
			knopka('forum.php?razdel='.$razdel.'&topic='.$topic, 'В тему', 1);
			knopka('forum.php', 'Главная форума', 1);
			fin();
			}
		$q = $db->query("update `forum_topic` set flag_close=0 where id={$topic} AND razdel={$razdel} limit 1;");
		msg2('Вы открыли тему');
		knopka('forum.php?razdel='.$razdel.'&topic='.$topic, 'В тему',1);
		knopka('forum.php', 'Главная форума', 1);
		fin();
		}
	elseif($mod == 'del' and 1 <= $f['admin'])
		{
		if(empty($ok))
			{
			msg2('Вы действительно хотите удалить эту тему?');
			knopka('forum.php?mod=del&ok=1&razdel='.$razdel.'&topic='.$topic, 'Удалить', 1);
			knopka('forum.php?razdel='.$razdel.'&topic='.$topic, 'В тему', 1);
			knopka('forum.php', 'Главная форума', 1);
			fin();
			}
		$q = $db->query("delete from `forum_topic` where id='{$topic}' limit 1;");
		$q = $db->query("delete from `forum_comm` where id_topic='{$topic}';");
		msg2('Вы удалили тему');
		if(!empty($razdel)) knopka('forum.php?razdel='.$razdel, 'В раздел', 1);
		knopka('forum.php', 'Главная форума', 1);
		fin();
		}
	elseif($mod == 'delete')
		{
		$q = $db->query("select * from `forum_topic` where id='{$topic}' limit 1;");
		if($q->num_rows == 0) msg2('Тема не найдена',1);
		$FRM = $q->fetch_assoc();
		$q = $db->query("select * from `forum_comm` where id='{$cid}' AND razdel='{$razdel}' AND id_topic='{$topic}' limit 1;");
		if($q->num_rows == 0) msg2('Сообщение не найдено!',1);
		$MSG = $q->fetch_assoc();
		if((($f['login'] == $MSG['login'] or $f['login'] == $FRM['login']) and $FRM['flag_close'] == 0) or $f['admin'] > 0)
			{
			$q = $db->query("DELETE FROM `forum_comm` WHERE id='{$cid}' AND razdel='{$razdel}' AND id_topic='{$topic}' LIMIT 1;");
			header("location: forum.php?razdel=".$razdel."&topic=".$topic."&start=".$start);
			fin();
			}
		else
			{
			msg2('Вы не можете удалить это сообщение!');
			knopka('forum.php?razdel='.$razdel.'&topic='.$topic, 'Вернуться', 1);
			knopka('forum.php', 'Главная форума', 1);
			fin();
			}
		fin();
		}
	else
		{
		$q = $db->query("select * from `forum_topic` where id={$topic} limit 1;");
		$ft = $q->fetch_assoc() or msg2('Тема не существует!',1);
		if($write == 1 and 1 < mb_strlen($mess, 'UTF-8') and $ft['flag_close'] == 0)
			{
			$mess = ekr($mess);
			$mess = mb_substr($mess, 0, 512, 'UTF-8');
			$a = $db->query("SELECT * FROM `forum_comm` WHERE login='{$f['login']}' ORDER BY id DESC LIMIT 1;");
			$b = $a->fetch_assoc();
			if($mess != $b['message'])
				{
				$q = $db->query("insert into `forum_comm` values(0,'{$f['login']}','{$topic}','{$razdel}','{$mess}','{$t}');");
				$q = $db->query("update `forum_topic` set lastcomm='{$t}' where id={$topic} limit 1;");
				$b = $db->query("select count(*) from `forum_comm` where id_topic='{$topic}';");
				$cc = $b->fetch_assoc();
				$cc = $cc['count(*)'];
				$page = intval(($cc - 1) / $numb) * $numb;
				//header("location: forum.php?razdel=".$razdel."&topic=".$ft['id']);
				//fin();
				}
			}

		if(!empty($lgn) and $ft['flag_close'] == 0 and !empty($cid))
			{
			$TestLGN = get_login($lgn);
			$lgn = $TestLGN['login'];
			$cid = intval($cid);
			if($cid <= 0) msg2('Сообщение не найдено',1);
			$q = $db->query("select * from `forum_comm` where login='{$lgn}' AND id='{$cid}' AND id_topic='{$topic}' limit 1;");
			$otv = $q->fetch_assoc() or msg2('Сообщение не найдено',1);
			if(empty($mess))
				{
				echo '<div class="board" style="text-align:left">';
				echo '<form action="forum.php?razdel='.$razdel.'&topic='.$topic.'&lgn='.$lgn.'&start='.$start.'&cid='.$cid.'" method="POST">';
				echo 'Пишем <a href="infa.php?mod=uzinfa&lgn='.$lgn.'"><b>'.$lgn.'</b></a><br/>';
				echo '<input type="text" name="mess" maxlength="512" style="width:80%"/><input type="submit" value="Ответ"/>';
				echo '</form></div>';
				knopka('forum.php?razdel='.$razdel.'&topic='.$topic, 'В тему', 1);
				knopka('forum.php', 'Главная форума', 1);
				fin();
				}
			$mess = ekr($mess);
			$mess = mb_substr($mess, 0, 512, 'UTF-8');
			$mess = '<b>'.$lgn.'</b>, '.$mess;
			$a = $db->query("SELECT * FROM `forum_comm` WHERE login='{$f['login']}' ORDER BY id DESC LIMIT 1;");
			$b = $a->fetch_assoc();
			if($mess != $b['message'])
				{
				$q = $db->query("insert into `forum_comm` values(0,'{$f['login']}','{$topic}','{$razdel}','{$mess}','{$t}');");
				$q = $db->query("update `forum_topic` set lastcomm='{$t}' where id={$topic} limit 1;");
				$b = $db->query("select count(*) from `forum_comm` where id_topic={$topic};");
				$cc = $b->fetch_assoc();
				$cc = $cc['count(*)'];
				$page = intval(($cc - 1) / $numb) * $numb;
				header("location: forum.php?razdel=".$razdel."&topic=".$ft['id']."&start=".$page);
				fin();
				}
			}
		knopka('forum.php?razdel='.$razdel.'&topic='.$topic.'&start='.$start, 'Обновить', 1);
		knopka('forum.php?razdel='.$razdel, 'Вернуться в раздел', 1);
		echo '<div class="board2" style="text-align:left">';
		echo '<b>'.$ft['name'].'</b> (by '.$ft['login'].')';
		if(empty($full))
			{
			echo ' <a href="forum.php?razdel='.$razdel.'&topic='.$topic.'&full=1">[полн.просм]</a>';
			}
		else
			{
			echo ' <a href="forum.php?razdel='.$razdel.'&topic='.$topic.'&full=0">[как обычно]</a>';
			}
		if((2 <= $f['admin'] or $f['login'] == $ft['login']) and $ft['flag_close'] == 0)
			{
			echo ' <a href="forum.php?razdel='.$razdel.'&topic='.$topic.'&mod=edit">[ред.]</a>';
			}
		echo '</div>';
		if($f['grafika'] == 1 or $f['grafika'] == 2) $ft['message'] = smile(link_it(nl2br($ft['message'])));
		else $ft['message'] = link_it(nl2br($ft['message']));
		echo '<div class="board2" style="text-align:left">'.$ft['message'].'</div>';
		if($ft['flag_close'] == 0)
			{
			echo '<div class="board">';
			echo '<form action="forum.php?razdel='.$razdel.'&topic='.$topic.'&write=1&start='.$start.'&full='.$full.'" method="POST">';
			echo '<input type="text" name="mess" maxlength="512" style="width:80%"/><input type="submit" value="Ответ"/>';
			echo '</div>';
			}
		if(empty($full))
			{
			$q = $db->query("select count(*) from `forum_comm` where id_topic={$ft['id']};");
			$a = $q->fetch_assoc();
			$all = $a['count(*)'];
			if($start > intval($all / $numb)) $start = intval($all / $numb);
			if($start < 0) $start = 0;
			$limit = $start * $numb;
			$count = $limit;
			$q = $db->query("select * from `forum_comm` where id_topic={$ft['id']} order by time desc limit {$limit},{$numb};");
			}
		else
			{
			$q = $db->query("select * from `forum_comm` where id_topic={$ft['id']} order by time desc;");
			}
		while($fm = $q->fetch_assoc())
			{
			echo '<div class="board2" style="text-align:left">';
			echo '<a href="forum.php?razdel='.$razdel.'&topic='.$topic.'&start='.$start.'&lgn='.$fm['login'].'&cid='.$fm['id'].'"><b><span style="color:'.$male.'">'.$fm['login'].'</span></b></a>';
			echo ' <small>['.Date('d.m.Y H:i', $fm['time']).']</small>';
			if((($f['login'] == $ft['login'] or $fm['login'] == $f['login']) and $ft['flag_close'] == 0) or $f['admin'] > 0)
				{
				echo ' <a href="forum.php?mod=delete&cid='.$fm['id'].'&razdel='.$razdel.'&topic='.$topic.'&start='.$start.'"> [x] </a>';
				}
			echo '<br/>';
			if($f['grafika'] == 1 or $f['grafika'] == 2) echo '&nbsp;'.smile(link_it($fm['message']));
			else echo '&nbsp;'.link_it($fm['message']);
			echo '</div>';
			$count++;
			}
		if(empty($full) and $all > $numb)
			{
			echo '<div class="board">';
			if($start > 0) echo '<a href="forum.php?razdel='.$razdel.'&topic='.$ft['id'].'&start='.($start - 1).'" class="navig"><-Назад</a>'; else echo '<a href="#" class="navig"> <-Назад</a>';
			echo ' | ';
			if($limit + $numb < $all) echo '<a href="forum.php?razdel='.$razdel.'&topic='.$ft['id'].'&start='.($start + 1).'" class="navig" >Вперед-></a>'; else echo ' <a href="#" class="navig"> Вперед-></a>';
			echo '</div>';
			}
		if(!empty($razdel)) knopka('forum.php?razdel='.$razdel, 'Вернуться в раздел', 1);
		knopka('forum.php', 'Главная форума', 1);
		if((1 <= $f['admin'] or $f['login'] == $ft['login']) and $ft['flag_close'] == 0) knopka('forum.php?mod=close&razdel='.$razdel.'&topic='.$topic, 'Закрыть тему', 1);
		if((1 <= $f['admin']) and $ft['flag_close'] == 1) knopka('forum.php?mod=open&razdel='.$razdel.'&topic='.$topic, 'Открыть тему', 1);
		if(1 <= $f['admin']) knopka('forum.php?mod=del&razdel='.$razdel.'&topic='.$topic, 'Удалить тему', 1);		
		fin();
		}
	}
?>
