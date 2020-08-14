<?php
##############
# 24.12.2014 #
##############

require_once('inc/top.php');
require_once('inc/check.php'); // вход в игру
require_once('inc/head.php');
require_once('class/items.php');

$go = isset($_REQUEST['go']) ? $_REQUEST['go'] : 0;
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
$geter = (isset($_REQUEST['geter'])) ? 1 : 0;
if (isset($_REQUEST['sex'])) $sex = intval($_REQUEST['sex']); else $sex = '';
if (isset($_REQUEST['mod'])) $mod = $_REQUEST['mod']; else $mod = '';
if (isset($_REQUEST['mid'])) $mid = $_REQUEST['mid']; else $mid = 0;
if (isset($_REQUEST['go'])) $go = $_REQUEST['go']; else $go = '';
if (isset($_REQUEST['ok'])) $ok = $_REQUEST['ok']; else $ok = '';
if (isset($_REQUEST['lgn'])) $lgn = ekr($_REQUEST['lgn']); else $lgn = '';
if (isset($_REQUEST['lgn2'])) $lgn2 = ekr($_REQUEST['lgn2']); else $lgn2 = '';
if (isset($_REQUEST['comment'])) $comment = ekr($_REQUEST['comment']); else $comment = '';
if (isset($_REQUEST['num'])) $num = intval($_REQUEST['num']); else $num = '';
if (isset($_REQUEST['n'])) $n = intval($_REQUEST['n']); else $n = '';
if (isset($_REQUEST['s'])) $s = intval($_REQUEST['s']); else $s = '';
if (isset($_REQUEST['w'])) $w = intval($_REQUEST['w']); else $w = '';
if (isset($_REQUEST['e'])) $e = intval($_REQUEST['e']); else $e = '';
if (isset($_REQUEST['x'])) $x = intval($_REQUEST['x']); else $x = '';
if (isset($_REQUEST['y'])) $y = intval($_REQUEST['y']); else $y = '';
if (isset($_REQUEST['nameloc'])) $nameloc = ekr($_REQUEST['nameloc']); else $nameloc = '';
if (isset($_REQUEST['textloc'])) $textloc = ekr($_REQUEST['textloc']); else $textloc = '';
if (isset($_REQUEST['karta'])) $karta = intval($_REQUEST['karta']); else $karta = '';
if (isset($_REQUEST['id'])) $id = intval($_REQUEST['id']); else $id = '';
if (isset($_REQUEST['lvl'])) $lvl = intval($_REQUEST['lvl']); else $lvl = '';
if (isset($_REQUEST['tip'])) $tip = intval($_REQUEST['tip']); else $tip = '';
if (isset($_REQUEST['mysql'])) $mysql = $_REQUEST['mysql']; else $mysql = '';
if (isset($_REQUEST['start'])) $start = $_REQUEST['start']; else $start = 0;

if ($f['admin'] < 1 and $f['p_kartograf'] == 0) msg2('Недоступно для вас', 1);

//блок чистки старых логов (2 суток)
$timer = $t - 604800;
$q = $db->query("delete from `battle` where boistart<'{$timer}';");
$q = $db->query("delete from `battlelog` where timelog<'{$timer}';");
$q = $db->query("delete from `combat` where time<'{$timer}';");
$timer = $t - 7200;
$q = $db->query("update `users` set status=0,hpnow=0-100000*hpmax where status=1 and lastdate<'{$timer}';");

require_once('inc/hpstring.php');
switch ($mod):
	default:
		if (3 <= $f['admin'])
			{
			msg2('Функции админа:');
			knopka('adm.php?mod=addnews', 'Добавить новость');
			knopka('adm.php?mod=stopmsg', 'MSG: STOP');
knopka('adm.php?mod=item', 'Взять вещь');
knopka('adm.php?mod=items', 'Раздать вещь');
			knopka('adm.php?mod=listitem', 'Все вещи списком');
			knopka('adm.php?mod=add_item', 'Добавить вещь в базу');
			knopka('adm.php?mod=addklan', 'Добавить КЛАН');
knopka('adm.php?mod=locs', 'Список всех локаций');
			knopka('adm.php?mod=delklan', 'Удалить КЛАН');
			knopka('adm.php?mod=pass&lgn='.$lgn, 'Сменить пасс');
			knopka('adm.php?mod=login&lgn='.$lgn, 'Сменить логин');
			knopka('adm.php?mod=lookpers&lgn='.$lgn, 'Смотреть массив перса');
			knopka('adm.php?mod=vip', 'V.I.P.');
			knopka('adm.php?mod=up', 'Апнуться');
			knopka('adm.php?mod=null', 'Обнулиться');
			knopka('adm.php?mod=mysql', 'MySQL');
			knopka('adm.php?mod=mail', 'Mail');
			}
		if (2 <= $f['admin'])
			{
			msg2('Функции гейм мастера:');
			knopka('adm.php?mod=lookblok', 'Список кто в блоке');
			knopka('adm.php?mod=othelit', 'Вылечить всех');
knopka('adm.php?mod=givemoney&lgn='.$lgn, 'Дать монет персу/оштрафовать');
			knopka('adm.php?mod=admin&lgn='.$lgn, 'Управление статусами');
			knopka('adm.php?mod=spam', 'Рассылка сообщения');
			knopka('adm.php?mod=brak', 'Регистрация брака');
			}

		if (1 <= $f['admin'])
			{
			msg2('Функции модератора:');
			knopka('adm.php?mod=logpered&lgn='.$lgn, 'Лог передач');
			knopka('adm.php?mod=logklan', 'Лог кланов');
			knopka('adm.php?mod=lastonl', 'Дата захода (все)');
			knopka('adm.php?mod=logipsoft', 'Лог IP/SOFT совпадений');
			knopka('adm.php?mod=ipsoft&lgn='.$lgn, 'IP/SOFT (login)');
			knopka('adm.php?mod=sovp', 'Совпадения паролей');
			knopka('adm.php?mod=sovpip', 'Совпадения IP (последний заход)');
			knopka('adm.php?mod=sovpipall', 'Совпадения IP (за всё время)');
			knopka('adm.php?mod=sovpsoftall', 'Совпадения SOFT (за всё время)');
			knopka('adm.php?mod=sovpall', 'Совпадения IP/SOFT (за всё время)');
			knopka('adm.php?mod=chatunban&lgn='.$lgn, 'Снять молчу');
			knopka('adm.php?mod=blok&lgn='.$lgn, 'Заблокировать перса');
			knopka('adm.php?mod=battle2&lgn='.$lgn, 'Просмотр боев (все)');
			knopka('adm.php?mod=battle&lgn='.$lgn, 'Просмотр боев (login)');
			}
		fin();
	break;

	case 'addnews':
		if ($f['admin'] < 3) msg2('Вы не можете добавлять новости', 1);
		if (empty($go))
			{
			echo '<div class="board" style="text-align:left">';
			echo '<form action="adm.php?mod=addnews&go=1" method="POST">
		Заголовок: (2-50 символов)<br/>
		<input type="text" name="title" size="20" maxlength="50"/><br/>
		Текст новости: (5-5000 символов)<br/>
		<textarea name="news" cols=60 rows=15 maxlength=5000></textarea><br/>
		<input type="submit" value="Отправить"/></form>';
			echo '</div>';
			knopka('adm.php', 'В админку', 1);
			fin();
			}
		if (isset($_REQUEST['news'])) $news = $_REQUEST['news'];
		$news = ekr($news);
		if (empty($news) or mb_strlen($news, 'UTF-8') < 5 or mb_strlen($news, 'UTF-8') > 5000) msg2('Неверно заполнено поле для новости:<br/>1) Оно не должно быть пустым.<br/>2) Сама новость не может быть короче 5 символов и длиннее 5000 символов.', 1);
		if (isset($_REQUEST['title'])) $title = $_REQUEST['title'];
		$title = ekr($title);
		if (empty($title) or mb_strlen($title, 'UTF-8') < 2 or mb_strlen($title, 'UTF-8') > 50) msg2('Неверно заполнено поле для заголовка:<br/>1) Оно не должно быть пустым.<br/>2) Сам заголовок не может быть короче 2 символов и длиннее 50 символов.', 1);
		$login = $f['login'];
		$q = $db->query("insert into `news` values(0,'{$login}','{$title}','{$news}','{$t}');");
		$q = $db->query("update `users` set newsdate='{$t}';");
		msg2('Новость успешно добавлена!');
		knopka('adm.php', 'В админку', 1);
		fin();
		break;

case 'items':
		if ($f['admin'] < 3) msg2('Вы не можете раздавать вещи', 1);
		if(empty($id))
			{
echo <<<text
<div class="board" style="text-align:left">
<form action="adm.php?mod=items" method="POST">
Введите ID вещи, которую необходимо раздать всем игрокам<br />
<input type="text" name="id" size="20" maxlength="50" /><br />
<input type="text" name="sex" value="3"/><br>
<input type="checkbox" name="geter" value="allow" checked="checked" />Разрешить передавать эту вещь другим игрокам

<input type="submit" value="Раздать" />
</form>
</div>
text;
			knopka('adm.php', 'В админку', 1);
			fin();
			}
if(!empty($id)) {
if($sex == 0) $q = $db->query("select `login` from `users` where sex=0;");
if($sex == 1) $q = $db->query("select `login` from `users` where sex=1;");
if($sex == 3) $q = $db->query("select `login` from `users`;");
$count = 0;
while($a = $q->fetch_assoc()) {
	$items->add_item($a['login'], $id, $geter); // 1 в конце флаг передачи, если не поставить, то игрок не сможет эту вещь передавать потом
	$count++;
}
}

		msg2("Игрокам передано $count вещей");
		knopka('adm.php', 'В админку', 1);
		fin();
		break;



	case 'stopmsg':
		if ($f['admin'] < 3) msg2('Вы не можете останавливать игру', 1);
		if (empty($go))
			{
			echo '<div class="board" style="text-align:left">';
			echo '<form action="adm.php?mod=stopmsg&go=1" method="POST">
			Текст сообщения: (0-100 символов, оставить пустым для запуска игры)<br/>
			<textarea name="news" cols=20 rows=5 maxlength=100></textarea><br/>
			<input type="submit" value="Отправить"/></form>';
			echo '</div>';
			knopka('adm.php', 'В админку', 1);
			fin();
			}
		$news = isset($_REQUEST['news']) ? ekr($_REQUEST['news']) : '';
		if (mb_strlen($news, 'UTF-8') > 100) $news = mb_substr($news, 0, 100, 'UTF-8');
		if(empty($news)) $q = $db->query("update `settings` set mess='' where id=1;");
		else $q = $db->query("update `settings` set mess='{$news}' where id=1;");
		msg2('Стоп сообщение успешно добавлено!');
		knopka('adm.php', 'В админку', 1);
		fin();
		break;

	case 'addklan':
		if ($f['admin'] < 3) msg2('Вы не можете создавать кланы');
		if (empty($lgn) or empty($lgn2))
			{
			echo '<div class="board" style="text-align:left">';
			echo '<form action="adm.php?mod=addklan" method="POST">';
			echo 'Название будущего клана:<br/>';
			echo '<input type="text" name="lgn"/><br/>';
			echo 'Ник главы:<br/>';
			echo '<input type="text" name="lgn2"/><br/>';
			echo '<input type="submit" value="Далее"/></form>';
			echo '</div>';
			knopka('adm.php', 'В админку', 1);
			fin();
			}
		if (preg_match("/[^a-zA-Zа-яА-ЯёЁ ]/u", $lgn) or mb_strlen($lgn, 'UTF-8') > 40) msg2('Неверное название клана', 1);
		$TestLGN = get_login($lgn2);
		$lgn2 = $TestLGN['login']; // для сохранения оригинального написания ника
		if (!empty($TestLGN['klan'])) msg2('Этот персонаж не может быть главой клана, так как состоит в другом клане.', 1);
		$q = $db->query("select * from `klans` where name='{$lgn}' limit 1;");
		if ($q->num_rows > 0) msg2('Такой клан уже зарегистрирован', 1);
		$q = $db->query("insert into `klans` (id,name,datereg) values(0,'{$lgn}','{$t}');");
		$q = $db->query("update `users` set klan='{$lgn}',klan_status=3,klan_time='{$t}' where login='{$lgn2}' limit 1;");
		msg2('Клан '.$lgn.' успешно создан. Глава '.$lgn2.'.');
		knopka('adm.php', 'В админку', 1);
		fin();
		break;

	case 'delklan':
		if ($f['admin'] < 3) msg2('Вы не можете удалять кланы');
		if (empty($num))
			{
			$q = $db->query("select id,name from `klans` order by id asc;");
			while ($a = $q->fetch_assoc())
				{
				knopka('adm.php?mod=delklan&num='.$a['id'], 'Удалить клан "'.$a['name'].'"', 1);
				}
			knopka('adm.php', 'В админку', 1);
			fin();
			}
		if ($num <= 0) msg2('Неверный выбор клана', 1);
		$q = $db->query("select id,name from `klans` where id='{$num}' limit 1;");
		if ($q->num_rows == 0) msg2('Такой клан не зарегистрирован', 1);
		$a = $q->fetch_assoc();
		if (empty($ok))
			{
			msg2('Вы хотите удалить клан "'.$a['name'].'", продолжить?');
			knopka('adm.php?mod=delklan&num='.$a['id'].'&ok=1', 'Продолжить', 1);
			knopka('adm.php', 'Вернуться', 1);
			fin();
			}
		$q = $db->query("update `users` set klan='',klan_status=0,klan_time=0,klan_invite='' where klan='{$a['name']}' or klan_invite='{$a['name']}';");
		$q = $db->query("delete from `klans` where id='{$a['id']}' limit 1;");
		$q = $db->query("delete from `klan_log` where name='{$a['name']}';");
		$q = $db->query("ALTER TABLE `klans` DROP `id`;");
		$q = $db->query("ALTER TABLE `klans` ADD `id` INT(12) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;");
		msg2('Клан '.$lgn.' успешно удален.');
		knopka('adm.php', 'В админку', 1);
		fin();
		break;

	case 'lookpers':
		$l = get_login($lgn);
		echo '<div class="board" style="text-align:left"><pre>';
		print_r($l);
		echo '</pre></div>';
		fin();
	break;

	case 'listitem':
		if ($f['admin'] < 3) msg2('Вы не можете просматривать список вещей', 1);
		$q = $db->query("select max(lvl) from `item`;");
		$a = $q->fetch_assoc();
		$max_lvl = $a['max(lvl)'];
		if (empty($lvl))
			{
			echo '<div class="board" style="text-align:left">';
			echo '<form action="adm.php?mod=listitem" method="POST">
			Выберите уровень<br/>
			<select name="lvl">';
			for ($i = 1; $i <= $max_lvl; $i++) echo '<option value='.$i.'>'.$i.'</option>';
			echo '<input type="submit" value="Далее"/></form>';
			echo '</div>';
			knopka('adm.php', 'В админку', 1);
			fin();
			}
		$lvl = intval($lvl);
		if ($lvl < 1) $lvl = 1;
		if ($lvl > $max_lvl) $lvl = $max_lvl;
		if (empty($go))
			{
			$q = $db->query("select * from `item` where lvl={$lvl} order by id;");
			while ($i = $q->fetch_assoc())
				{
				echo '<div class="board2" style="text-align:left">';
				echo '<a href="shop.php?mod=iteminfa&iid='.$i['id'].'"><span style="color:'.$male.'">'.$i['name'].' ('.$i['lvl'].' ур.)</span></a>';
				echo ' <a href="adm.php?mod=item&num='.$i['id'].'">[get]</a>';
				echo ' <a href="adm.php?mod=listitem&num='.$i['id'].'&go=1&lvl='.$lvl.'">[del]</a>';
				echo ' <a href="adm.php?mod=listitem&num='.$i['id'].'&go=2&lvl='.$lvl.'">[edit]</a>';
				echo '</div>';
				}
			fin();
			}
		elseif ($go == 1)
			{
			$num = intval($num);
			if ($num <= 0) msg2('Неверный ИД вещи.', 1);
			$q = $db->query("select name from `item` where id='{$num}' limit 1;");
			if ($q->num_rows == 0) msg2('Вы хотите удалить несуществующую вещь.', 1);
			$i = $q->fetch_assoc();
			if (empty($ok))
				{
				msg2('Вы действительно хотите удалить '.$i['name'].' из базы?');
				knopka('adm.php?mod=listitem&go=1&ok=1&lvl='.$lvl.'&num='.$num, 'Удалить безвозвратно', 1);
				knopka('adm.php?mod=listitem', 'Не удалять', 1);
				fin();
				}
			$q = $db->query("delete from `invent` where ido='{$num}';");
			$q = $db->query("delete from `item` where id='{$num}' limit 1;");
			msg2('Готово. Вещь "'.$i['name'].'" удалена из базы');
			knopka('adm.php', 'В админку', 1);
			fin();
			}
		elseif ($go == 2)
			{
			$num = intval($num);
			if ($num <= 0) msg2('Неверный ID вещи.', 1);
			$q = $db->query("select * from `item` where id='{$num}' limit 1;");
			if ($q->num_rows == 0) msg2('Вы хотите редактировать несуществующую вещь.', 1);
			$i = $q->fetch_assoc();
			if (empty($ok))
				{
				echo '<div class="board" style="text-align:left">';
				echo '<form action="adm.php?mod=listitem&go=2&ok=1&lvl='.$lvl.'&num='.$num.'" method="POST">';
				echo 'ID: '.$i['id'].'<br/>';
				echo 'Название: <input type="text" name="name" value="'.$i['name'].'"/><br/>';
				echo 'Уровень: <input type="text" name="lvl" value="'.$i['lvl'].'"/><br/>';
				echo 'Цена: <input type="text" name="price" value="'.$i['price'].'"/><br/>';
				echo 'Крит: <input type="text" name="krit" value="'.$i['krit'].'"/><br/>';
				echo 'Уворот: <input type="text" name="uvorot" value="'.$i['uvorot'].'"/><br/>';
				echo 'Урон: <input type="text" name="uron" value="'.$i['uron'].'"/><br/>';
				echo 'Бронь: <input type="text" name="bron" value="'.$i['bron'].'"/><br/>';
				echo 'HP: <input type="text" name="hp" value="'.$i['hp'].'"/><br/>';
				echo 'Здоровье: <input type="text" name="zdor" value="'.$i['zdor'].'"/><br/>';
				echo 'Сила: <input type="text" name="sila" value="'.$i['sila'].'"/><br/>';
				echo 'Инта: <input type="text" name="inta" value="'.$i['inta'].'"/><br/>';
				echo 'Ловка: <input type="text" name="lovka" value="'.$i['lovka'].'"/><br/>';
				echo 'Интеллект: <input type="text" name="intel" value="'.$i['intel'].'"/><br/>';
				echo 'Арт: <input type="text" name="art" value="'.$i['art'].'"/><br/>';
				echo 'Описание: <input type="text" name="info" value="'.$i['info'].'"/><br/>';
				echo 'Слот: <input type="text" name="equip" value="'.$i['equip'].'"/><br/>';
				echo '<input type="submit" value="Изменить"/></form></div>';
				fin();
				}
			$name = ekr($_REQUEST['name']);
			$price = intval($_REQUEST['price']);
			if ($price < 0) msg2('Цена не может быть меньше 0', 1);
			$lvl = intval($_REQUEST['lvl']);
			if ($lvl < 1) msg2('Уровень не может быть меньше 1', 1);
			$krit = intval($_REQUEST['krit']);
			$uvorot = intval($_REQUEST['uvorot']);
			$uron = intval($_REQUEST['uron']);
			$bron = intval($_REQUEST['bron']);
			$hp = intval($_REQUEST['hp']);
			$zdor = intval($_REQUEST['zdor']);
			if ($zdor < 0) msg2('Здоровье не может быть меньше 0', 1);
			$sila = intval($_REQUEST['sila']);
			if ($sila < 0) msg2('Сила не может быть меньше 0', 1);
			$inta = intval($_REQUEST['inta']);
			if ($inta < 0) msg2('Интуиция не может быть меньше 0', 1);
			$lovka = intval($_REQUEST['lovka']);
			if ($lovka < 0) msg2('Ловкость не может быть меньше 0', 1);
			$intel = intval($_REQUEST['intel']);
			if ($intel < 0) msg2('Интеллект не может быть меньше 0', 1);
			$art = ekr($_REQUEST['art']);
			$info = ekr($_REQUEST['info']);
			$equip = ekr($_REQUEST['equip']);
			if (!empty($equip)) if (preg_match("/[^a-zA-Z0-9]/", $equip)) msg2('Нерно заполнен слот', 1);
			$q = $db->query("update `item` set name='{$name}',lvl={$lvl},price={$price},krit={$krit},uvorot={$uvorot},uron={$uron},bron={$bron},hp={$hp},zdor={$zdor},sila={$sila},inta={$inta},lovka={$lovka},intel={$intel},art='{$art}',info='{$info}',equip='{$equip}' where id={$i['id']} limit 1;");
			msg2("Вещь ".$name." успешно отредактирована.");
			knopka('adm.php', 'В админку', 1);
			knopka('adm.php?modlistitem=', 'к списку вещей', 1);
			fin();
			}
		break;

	case 'add_item':
		if ($f['admin'] < 3) msg2('Вы не можете создавать вещи', 1);
		if (empty($ok))
			{
			echo '<div class="board" style="text-align:left">';
			echo '<form action="adm.php?mod=add_item&ok=1" method="POST">';
			echo 'Название: <input type="text" name="name" value=""/><br/>';
			echo 'Уровень: <input type="number" name="lvl" value="0"/><br/>';
			echo 'Цена: <input type="number" name="price" value="0"/><br/>';
			echo 'Крит: <input type="number" name="krit" value="0"/><br/>';
			echo 'Уворот: <input type="number" name="uvorot" value="0"/><br/>';
			echo 'Урон: <input type="number" name="uron" value="0"/><br/>';
			echo 'Бронь: <input type="number" name="bron" value="0"/><br/>';
			echo 'ХП: <input type="number" name="hp" value="0"/><br/>';
			echo 'Здоровье: <input type="number" name="zdor" value="0"/><br/>';
			echo 'Сила: <input type="number" name="sila" value="0"/><br/>';
			echo 'Инта: <input type="number" name="inta" value="0"/><br/>';
			echo 'Ловка: <input type="number" name="lovka" value="0"/><br/>';
			echo 'Интеллект: <input type="number" name="intel" value="0"/><br/>';
			echo 'Арт: <input type="text" name="art" value=""/><br/>';
			echo 'Описание: <input type="text" name="info" value=""/><br/>';
			echo 'Слот: <input type="text" name="equip" value=""/><br/>';
			echo '<input type="submit" value="Создать"/></form>';
			echo '</div>';
			knopka('adm.php', 'Админка', 1);
			fin();
			}
		$name = ekr($_REQUEST['name']);
		$price = intval($_REQUEST['price']);
		if ($price < 0) msg2('Цена не может быть меньше 0', 1);
		$lvl = intval($_REQUEST['lvl']);
		if ($lvl < 1) msg2('Уровень не может быть меньше 1', 1);
		$krit = intval($_REQUEST['krit']);
		$uvorot = intval($_REQUEST['uvorot']);
		$uron = intval($_REQUEST['uron']);
		$bron = intval($_REQUEST['bron']);
		$hp = intval($_REQUEST['hp']);
		$zdor = intval($_REQUEST['zdor']);
		if ($zdor < 0) msg2('Здоровье не может быть меньше 0', 1);
		$sila = intval($_REQUEST['sila']);
		if ($sila < 0) msg2('Сила не может быть меньше 0', 1);
		$inta = intval($_REQUEST['inta']);
		if ($inta < 0) msg2('Интуиция не может быть меньше 0', 1);
		$lovka = intval($_REQUEST['lovka']);
		if ($lovka < 0) msg2('Ловкость не может быть меньше 0', 1);
		$intel = intval($_REQUEST['intel']);
		if ($intel < 0) msg2('Интеллект не может быть меньше 0', 1);
		$art = ekr($_REQUEST['art']);
		$info = ekr($_REQUEST['info']);
		$equip = ekr($_REQUEST['equip']);
		if (!empty($equip)) if (preg_match("/[^a-zA-Z0-9]/", $equip)) msg2('Нерно заполнен слот', 1);
		$q = $db->query("insert into `item` values(0,'{$name}',{$lvl},{$price},{$krit},{$uvorot},{$uron},{$bron},{$hp},{$sila},{$zdor},{$inta},{$lovka},{$intel},'{$art}','{$info}','{$equip}');");
		msg2('Вещь '.$name.' успешно создана. (ID: '.$db->insert_id().')');
		knopka('adm.php?mod=add_item', 'Продолжить', 1);
		knopka('adm.php', 'В админку', 1);
		fin();
		break;

	case 'logpered':
		if ($f['admin'] < 1) msg2('Вы не можете просматривать лог передач', 1);
		$numb = 100;
		if (empty($lgn))
			{
			$q = $db->query("select count(*) from `log_peredach`;");
			$a = $q->fetch_assoc();
			$all_log = $a['count(*)'];
			if ($start > intval($all_log / $numb)) $start = intval($all_log / $numb);
			if ($start < 0) $start = 0;
			$limit = $start * $numb;
			$count = $limit;
			$q = $db->query("select * from log_peredach order by id desc limit {$limit},{$numb};");
			while ($log = $q->fetch_assoc())
				{
				$count++;
				echo '<div class="board2" style="text-align:left">'.$count.') <a href="adm.php?mod=logpered&lgn='.$log['login'].'">[>>>]</a> '.date('d.m.Y H:i', $log['dateper']).' - '.$log['log'].'</div>';
				}
			echo '<div class="board">';
			if ($start > 0) echo '<a href="adm.php?mod=logpered&start='.($start - 1).'" class="navig"><-Назад</a>';
			else echo '<a href="#" class="navig"> <-Назад</a>';
			echo ' | ';
			if ($limit + $numb < $all_log) echo '<a href="adm.php?mod=logpered&start='.($start + 1).'" class="navig" >Вперед-></a>';
			else echo ' <a href="#" class="navig"> Вперед-></a>';
			echo '</div>';
			fin();
			}
		$TestLGN = get_login($lgn);
		msg2('Лог передач персонажа <a href="infa.php?mod=uzinfa&lgn='.$lgn.'">'.$lgn.'</a>');
		$q = $db->query("select count(*) from `log_peredach` where login='{$lgn}' or login_per='{$lgn}';");
		$a = $q->fetch_assoc();
		$all_log = $a['count(*)'];
		if ($start > intval($all_log / $numb)) $start = intval($all_log / $numb);
		if ($start < 0) $start = 0;
		$limit = $start * $numb;
		$count = $limit;
		$q = $db->query("select * from `log_peredach` where login='{$lgn}' OR login_per='{$lgn}' order by id desc limit {$limit},{$numb};");
		while ($log = $q->fetch_assoc())
			{
			$count++;
			echo '<div class="board2 style="text-align:left">'.$count.') '.date('d.m.Y H:i', $log['dateper']).' - '.$log['log'].'</div>';
			}
		echo '<div class="board">';
		if ($start > 0) echo '<a href="adm.php?mod=logpered&lgn='.$lgn.'&start='.($start - 1).'" class="navig"><-Назад</a>';
		else echo '<a href="#" class="navig"> <-Назад</a>';
		echo ' | ';
		if ($limit + $numb < $all_log) echo '<a href="adm.php?mod=logpered&lgn='.$lgn.'&start='.($start + 1).'" class="navig" >Вперед-></a>';
		else echo ' <a href="#" class="navig"> Вперед-></a>';
		echo '</div>';
		fin();
		break;

	case 'battle':
		if ($f['admin'] < 1) msg2('Вы не можете просматривать лог боев', 1);
		$numb = 100;
		if (empty($lgn))
			{
			echo '<div class="board" style="text-align:left">';
			echo '<form action="adm.php?mod=battle" method="POST">';
			echo 'Введите логин:<br/>';
			echo '<input type="text" name="lgn"/><br/>';
			echo '<input type="submit" value="Go"/></form></div>';
			knopka('adm.php', 'В админку', 1);
			fin();
			}
		$TestLGN = get_login($lgn);
		msg2('Список боев персонажа <a href="infa.php?mod=uzinfa&lgn='.$lgn.'">'.$lgn.'</a>');
		$q = $db->query("select count(*) from `battle` where login='{$lgn}' or login2='{$lgn}';");
		$a = $q->fetch_assoc();
		$all_log = $a['count(*)'];
		if ($start > intval($all_log / $numb)) $start = intval($all_log / $numb);
		if ($start < 0) $start = 0;
		$limit = $start * $numb;
		$count = $limit;
		$q = $db->query("select * from `battle` where login='{$lgn}' or login2='{$lgn}' order by id desc limit {$limit},{$numb};");
		while ($log = $q->fetch_assoc())
			{
			$count++;
			knopka('adm.php?mod=lookbattle&num='.$log['id'], $count.' ['.date('d.m.Y H:i:s', $log['boistart']).']: '.$log['login'].' vs '.$log['login2']);
			}
		echo '<div class="board">';
		if ($start > 0) echo '<a href="adm.php?mod=battle&lgn='.$lgn.'&start='.($start - 1).'" class="navig"><-Назад</a>';
		else echo '<a href="#" class="navig"> <-Назад</a>';
		echo ' | ';
		if ($limit + $numb < $all_log) echo '<a href="adm.php?mod=battle&lgn='.$lgn.'&start='.($start + 1).'" class="navig" >Вперед-></a>';
		else echo ' <a href="#" class="navig"> Вперед-></a>';
		echo '</div>';
		fin();
	break;

	case 'battle2':
		if ($f['admin'] < 1) msg2('Вы не можете просматривать лог боев', 1);
		$numb = 100;
		msg2('Список всех боев');
		$q = $db->query("select count(*) from `battle`;");
		$a = $q->fetch_assoc();
		$all_log = $a['count(*)'];
		if ($start > intval($all_log / $numb)) $start = intval($all_log / $numb);
		if ($start < 0) $start = 0;
		$limit = $start * $numb;
		$count = $limit;
		$q = $db->query("select * from `battle` order by id desc limit {$limit},{$numb};");
		while ($log = $q->fetch_assoc())
			{
			$count++;
			knopka('adm.php?mod=lookbattle&num='.$log['id'], $count.' ['.date('d.m.Y H:i:s', $log['boistart']).']: '.$log['login'].' vs '.$log['login2']);
			}
		echo '<div class="board">';
		if ($start > 0) echo '<a href="adm.php?mod=battle2&start='.($start - 1).'" class="navig"><-Назад</a>';
		else echo '<a href="#" class="navig"> <-Назад</a>';
		echo ' | ';
		if ($limit + $numb < $all_log) echo '<a href="adm.php?mod=battle2&&start='.($start + 1).'" class="navig" >Вперед-></a>';
		else echo ' <a href="#" class="navig"> Вперед-></a>';
		echo '</div>';
		fin();
	break;

	case 'lookbattle':
		if ($f['admin'] < 1) msg2('Вы не можете просматривать лог боев', 1);
		$num = intval($num);
		if($num <= 0) msg2('Бой не найден!',1);
		echo '<div class="board" style="text-align:left">';
		$q = $db->query("select * from `battle` where id='{$num}' limit 1;");
		if($q->num_rows == 0) msg2('Бой не найден!',1);
		$q = $db->query("select * from `battlelog` where boi_id='{$num}' order by id;");
		while ($log = $q->fetch_assoc())
			{
			echo $log['log'];
			}
		echo '</div>';
		fin();
	break;

	case 'logklan':
		if ($f['admin'] < 1) msg2('Вы не можете просматривать лог кланов', 1);
		$numb = 100;
		$q = $db->query("select count(*) from `klan_log`;");
		$a = $q->fetch_assoc();
		$all_log = $a['count(*)'];
		if ($start > intval($all_log / $numb)) $start = intval($all_log / $numb);
		if ($start < 0) $start = 0;
		$limit = $start * $numb;
		$count = $limit;
		$q = $db->query("select * from `klan_log` order by id desc limit {$limit},{$numb};");
		while ($log = $q->fetch_assoc())
			{
			$count++;
			echo '<div class="board2" style="text-align:left">'.$count.') '.date('d.m.Y H:i', $log['date']).' ['.$log['klan'].'] - '.$log['log'].'</div>';
			}
		echo '<div class="board">';
		if ($start > 0) echo '<a href="adm.php?mod=logklan&start='.($start - 1).'" class="navig"><-Назад</a>';
		else echo '<a href="#" class="navig"> <-Назад</a>';
		echo ' | ';
		if ($limit + $numb < $all_log) echo '<a href="adm.php?mod=logklan&start='.($start + 1).'" class="navig" >Вперед-></a>';
		else echo ' <a href="#" class="navig"> Вперед-></a>';
		echo '</div>';
		fin();
		break;

	case 'vip':
		if ($f['admin'] < 3) msg2('Вы не можете просматривать список VIP', 1);
		$q = $db->query("select lvl,login,lastdate,vip from `users` where vip>{$t} and autoreg=0 order by vip asc;");
		$count = 0;
		while ($Arr = $q->fetch_assoc())
			{
			$count++;
			echo '<div class="board2" style="text-align:left">'.$count.') <a href="infa.php?mod=uzinfa&lgn='.$Arr['login'].'">'.$Arr['login'].' ['.$Arr['lvl'].']</a> (до '.Date('d.m.Y H:i', $Arr['vip']).')</div>';
			}
		fin();
		break;

	case 'ipsoft':
		if ($f['admin'] < 1) msg2('Просмотр данных по заходам вам недоступен.', 1);
		if (empty($lgn))
			{
			echo '<div class="board" style="text-align:left">';
			echo '<form action="adm.php?mod=ipsoft" method="POST">';
			echo 'Введите логин:<br/>';
			echo '<input type="text" name="lgn"/><br/>';
			echo '<input type="submit" value="Go"/></form></div>';
			knopka('adm.php', 'В админку', 1);
			fin();
			}
		$TestLGN = get_login($lgn);
		$lgn = $TestLGN['login'];
		$count = 0;
		msg2('login: <a href="infa.php?mod=uzinfa&lgn='.$lgn.'">'.$lgn.'</a><br/>Последний заход:');
		if (!empty($TestLGN['ip'])) $ip = $TestLGN['ip'];
		else $ip = '127.0.0.1';
		echo '<div class="board2" style="text-align:left">IP: '.$ip.'</div>';
		echo '<div class="board2" style="text-align:left">SOFT: '.$TestLGN['soft'].'</div>';
		echo '<div class="board2" style="text-align:left">HOST: '.$TestLGN['host'].'</div>';
		knopka('adm.php?mod=ipsoftall&lgn='.$lgn, 'Все заходы '.$lgn, 1);
		knopka('adm.php', 'В админку', 1);
		fin();
		break;

	case 'ipsoftall':
		if ($f['admin'] < 1) msg2('Просмотр данных по заходам вам недоступен.', 1);
		$numb = 100;
		if (empty($lgn))
			{
			echo '<div class="board" style="text-align:left">';
			echo '<form action="adm.php?mod=ipsoftall" method="POST">';
			echo 'Введите логин:<br/>';
			echo '<input type="text" name="lgn"/><br/>';
			echo '<input type="submit" value="Go"/></form></div>';
			knopka('adm.php', 'В админку', 1);
			fin();
			}
		$TestLGN = get_login($lgn);
		$lgn = $TestLGN['login'];
		$count = 0;
		msg2('login: <a href="infa.php?mod=uzinfa&lgn='.$lgn.'">'.$lgn.'</a><br/>Все заходы:');
		$q = $db->query("select count(*) from `ipsoft` where login='{$lgn}';");
		$a = $q->fetch_assoc();
		$all_log = $a['count(*)'];
		if ($start > intval($all_log / $numb)) $start = intval($all_log / $numb);
		if ($start < 0) $start = 0;
		$limit = $start * $numb;
		$count = $limit;
		$q = $db->query("select * from `ipsoft` where login='{$lgn}' order by id desc limit {$limit},{$numb};");
		while ($log = $q->fetch_assoc())
			{
			$count++;
			echo '<div class="board2" style="text-align:left">'.$count.') '.date('d.m.Y H:i', $log['date']);
			if (!empty($log['ip'])) echo '<br/>IP: '.$log['ip'];
knopka('https://www.tendence.ru/tools/whois/'.$log['ip'], 'WHOIS1');
knopka('http://www.1whois.ru/?url='.$log['ip'], 'WHOIS2');
knopka('http://speed-tester.info/ip_location.php?ip='.$log['ip'], 'ГЕО1');

knopka('http://www.ip-ping.ru/ipinfo/?ipinfo='.$log['ip'], 'ГЕО2');
			if (!empty($log['soft'])) echo 'SOFT: '.$log['soft'];
			if (!empty($log['host'])) echo '<br/>HOST: '.$log['host'];
			echo '</div>';
			}
		knopka('adm.php', 'В админку', 1);
		fin();
		break;

	case 'blok':
		if ($f['admin'] < 1) msg2('Вы не можете блокировать персонажей.', 1);
		if (empty($lgn) or empty($num) or empty($mysql))
			{
			echo '<div class="board" style="text-align:left">';
			echo '<form action="adm.php?mod=blok" method="POST">';
			echo 'Введите логин:<br/>';
			echo '<input type="text" name="lgn" value="'.$lgn.'"/><br/>';
			echo '<select name="num">
			<option selected value="1">1 Сутки</option>
			<option value="2">2 Суток</option>
			<option value="3">3 Суток</option>
			<option value="4">4 Суток</option>
			<option value="5">5 Суток</option>
			<option value="6">6 Суток</option>
			<option value="7">7 Суток</option>';
			if (2 <= $f['admin']) echo '<option value="8">Пожизненно</option>';
			echo '</select><br/>';
			echo 'Комментарий (100 симв):<br/>';
			echo '<input type="text" name="mysql" maxlength=100/><br/>';
			echo '<input type="submit" value="Go"/></form></div>';
			knopka('adm.php', 'В админку', 1);
			fin();
			}
		$TestLGN = get_login($lgn);
		if ($f['login'] == $TestLGN['login']) msg2('Нельзя отправить в блок самого себя', 1);
		if ($f['admin'] < $TestLGN['admin']) msg2('Ваш статус не позволяет блокировать этого персонажа', 1);
		if ($TestLGN['flag_blok'] == 1) msg2('Данный персонаж уже заблокирован', 1);
		$lgn = $TestLGN['login'];
		$mysql = ekr($mysql);
		$num = intval($num);
		$mysql = mb_substr($mysql, 0, 100, 'UTF-8');
  $mysql = 'Блок наложен '.$f['login'].', Причина: '.$mysql;
		if ($num == 8 and 2 <= $f['admin'])
			{
			$mess = '<b>Админка</b>: персонаж '.$f['login'].' заблокировал персонажа '.$lgn.' пожизненно.';
			$q = $db->query("insert into `letters` values(0,0,'{$t}','{$admin}','{$settings['bot']}','{$mess}',0,0);");
			$q = $db->query("update `users` set flag_blok=1,zachto_blok='{$mysql}',ban=0,lastdate='{$t}' where login='{$lgn}' limit 1;");
			msg2('Персонажу '.$lgn.' наложен пожизненный блок');
			knopka('adm.php', 'В админку', 1);
			fin();
			}
		elseif ($num == 1 or $num == 2 or $num == 3 or $num == 4 or $num == 5 or $num == 6 or $num == 7)
			{
			$ban = $t + (86400 * $num);
			$mess = '<b>Админка</b>: персонаж '.$f['login'].' заблокировал персонажа '.$lgn.' на '.$num.' суток.';
			$q = $db->query("insert into `letters` values(0,0,'{$t}','{$admin}','{$settings[bot]}','{$mess}',0,0);");
			$q = $db->query("update `users` set flag_blok=1,zachto_blok='{$mysql}',ban='{$ban}',lastdate='{$t}' where login='{$lgn}' limit 1;");
			msg2('Персонажу '.$lgn.' наложен блок на '.$num.' сут.');
			knopka('adm.php', 'В админку', 1);
			fin();
			}
		else
			{
			msg2('Нельзя отправить в блок на столько дней', 1);
			knopka('adm.php', 'В админку', 1);
			fin();
			}
		fin();
		break;

	case 'unblok':
		if ($f['admin'] < 2) msg2('Вы не можете разюлокировать персонажей', 1);
		$TestLGN = get_login($lgn);
		$lgn = $TestLGN['login'];
		if ($TestLGN['flag_blok'] == 0) msg2('Персонаж '.$lgn.' не в блоке', 1);
		$q = $db->query("update `users` set flag_blok=0,zachto_blok='',ban=0,lastdate='{$t}' where login='{$lgn}' limit 1;");
		msg2('Персонаж '.$lgn.' успешно разблокирован.');
		knopka('adm.php', 'В админку', 1);
		fin();
		break;

	case 'spam':
		if ($f['admin'] < 2) msg2('Вы не можете давать спам', 1);
		if (empty($mysql))
			{
			echo '<div class="board" style="text-align:left">';
			echo '<form action="adm.php?mod=spam" method="POST">';
			echo 'Это сообщение получат все, кто не в блоке и были в игре в ближайшие 30 дней:<br/>';
			echo '<textarea type="text" name="mysql"></textarea><br/>';
			echo '<input type="submit" value="Далее"/></form></div>';
			knopka('adm.php', 'В админку', 1);
			fin();
			}
		$srok = 68400 * 30;
		$count = 0;
		$mess = '<b><img src="pic/1429.png" alt=""/>Системное сообщение</b>: '.ekr($mysql);
		$timer = $t - $srok;
		$q = $db->query("select login from `users` where lastdate>'{$timer}' AND flag_blok=0;");
		while ($b = $q->fetch_assoc())
			{
			$count++;
			$qq = $db->query("insert into `letters` values(0,0,'{$t}','{$b['login']}','{$settings['bot']}','{$mess}',0,0);");
			}
		msg2('Отправлено '.$count.' сообщений.');
		knopka('adm.php', 'В админку', 1);
		fin();
		break;

	case 'pass':
		if ($f['admin'] < 3) msg2('Вы не можете менять пароли игрокам', 1);
		if (empty($lgn) or empty($mysql))
			{
			echo '<div class="board" style="text-align:left">';
			echo '<form action="adm.php?mod=pass" method="POST">
			Логин:<br/><input type="text" name="lgn" value="'.$lgn.'"/><br/>
			Новый пароль<br/><input type="text" name="mysql"/><br/>
			<input type="submit" value="Ок"/></form></div>';
			knopka('adm.php', 'В админку', 1);
			fin();
			}
		$TestLGN = get_login($lgn);
		$lgn = $TestLGN['login'];
		if (preg_match("/[^a-zA-Z0-9]/", $mysql)) msg2('Недопустимый пароль', 1);
		$mysql = md5($mysql);
		$q = $db->query("update `users` set pass='{$mysql}' where login='{$lgn}' limit 1;");
		msg2('Персонажу '.$lgn.' успешно изменен пароль.');
		knopka('adm.php', 'В админку', 1);
		fin();
		break;

	case 'login':
		if ($f['admin'] < 3) msg2('Вы не можете менять логины игрокам', 1);
		if (empty($lgn) or empty($mysql))
			{
			echo '<div class="board" style="text-align:left">';
			echo '<form action="adm.php?mod=login" method="POST">
			Логин:<br/><input type="text" name="lgn" value="'.$lgn.'"/><br/>
			Новый логин<br/><input type="text" name="mysql"/><br/>
			<input type="submit" value="Ок"/></form></div>';
			knopka('adm.php', 'В админку', 1);
			fin();
			}
		$a = get_login($lgn);
		$lgn = $a['login'];
		if (preg_match("/[^a-zA-Z0-9_]/", $mysql)) msg2('Недопустимый логин', 1);
		$q = $db->query("select login from `users` where login='{$mysql}' limit 1;");
		if ($q->num_rows > 0)
			{
			msg2('<span style="color:red"><b>Логин '.$mysql.' уже занят. Выберите другой.</b></span>');
			knopka('javascript:history.go(-1)', 'Назад', 1);
			fin();
			}
		$q = $db->query("update `chat` set login='{$mysql}' WHERE login='{$lgn}';");
		$q = $db->query("update `chat` set privat='{$mysql}' WHERE privat='{$lgn}';");
		$q = $db->query("update `combat` set login='{$mysql}' WHERE login='{$lgn}';");
		$q = $db->query("update `forum_comm` set login='{$mysql}' WHERE login='{$lgn}';");
		$q = $db->query("update `forum_topic` set login='{$mysql}' WHERE login='{$lgn}';");
		$q = $db->query("update `invent` set login='{$mysql}' WHERE login='{$lgn}';");
		$q = $db->query("update `invent` set arenda_login='{$mysql}' WHERE arenda_login='{$lgn}';");
		$q = $db->query("update `ipsoft` set login='{$mysql}' WHERE login='{$lgn}';");
		$q = $db->query("update `letters` set login='{$mysql}' WHERE login='{$lgn}';");
		$q = $db->query("update `letters` set login_from='{$mysql}' WHERE login_from='{$lgn}';");
		$q = $db->query("update `log_peredach` set login='{$mysql}' WHERE login='{$lgn}';");
		$q = $db->query("update `log_peredach` set login_per='{$mysql}' WHERE login_per='{$lgn}';");
		$q = $db->query("update `magic` set login='{$mysql}' WHERE login='{$lgn}';");
		$q = $db->query("update `users` set login='{$mysql}' WHERE login='{$lgn}' limit 1;");
		msg2('Персонажу '.$lgn.' успешно изменен логин на '.$mysql.'.');
		knopka('adm.php', 'В админку', 1);
		fin();
		break;

	case 'res':
		if ($f['admin'] < 3) msg2('Воскрешение вам недоступно', 1);
		$q = $db->query("update users set hpnow=hpmax,mananow=manamax where id={$f['id']} limit 1;");
		msg2('Ваши жизни и мана восстановлены!');
		knopka('adm.php', 'В админку', 1);
		knopka('loc.php', 'В игру', 1);
		fin();
		break;

	case 'brak':
		if(empty($lgn) or empty($lgn2))
			{
			echo '<div class="board" style="text-align:left">';
			echo '<form action="adm.php?mod=brak" method="POST">
			Первый логин:<br/><input type="text" name="lgn" value="'.$lgn.'"/><br/>
			Второй логин<br/><input type="text" name="lgn2" value="'.$lgn2.'"/><br/>
			<input type="submit" value="Ок"/></form></div>';
			knopka('adm.php', 'В админку', 1);
			fin();
			}
		$lgn = get_login($lgn);
		$lgn = $lgn['login'];
		$lgn2 = get_login($lgn2);
		$lgn2 = $lgn2['login'];
		$q = $db->query("update `users` set brak='{$lgn}' where login='{$lgn2}' limit 1;");
		$q = $db->query("update `users` set brak='{$lgn2}' where login='{$lgn}' limit 1;");
		msg2('Брак между '.$lgn.' и '.$lgn2.' успешно заключен!');
		knopka('adm.php', 'В админку', 1);
		knopka('loc.php', 'В игру', 1);
		fin();
		break;

	case 'up':
		if ($f['admin'] < 3) msg2('Взятие уровня вам недоступно', 1);
		$q = $db->query("update `users` set exp=0,lvl=lvl+1 where id={$f['id']} limit 1;");
		msg2('LVL +1');
		knopka('adm.php', 'В админку', 1);
		knopka('loc.php', 'В игру', 1);
		fin();
		break;

	case 'null':
		if ($f['admin'] < 3) fin('Обнуление вам недоступно', 1);
		$q = $db->query("update `users` set exp=0,zdor=1,sila=1,lovka=1,inta=1,lvl=1,intel=1 where id={$f['id']} limit 1;");
		msg2('LVL = 1');
		knopka('adm.php', 'В админку', 1);
		knopka('loc.php', 'В игру', 1);
		fin();
		break;

	case 'chatban':
		if($f['admin'] < 1) msg('Недостаточно прав!', 1);

		if(empty($lgn)) msg('Вы не ввели логин!', 1);
		$l = get_login($lgn);
		$lgn = $l['login'];

		if (empty($_REQUEST['ok']) or empty($_REQUEST['mid']))
			{
			msg2('Вы собираетесь поставить молчу персонажу '.$lgn);
			knopka('adm.php?mod=chatban&lgn='.$lgn.'&mid=1&ok=1', '5 минут');
			knopka('adm.php?mod=chatban&lgn='.$lgn.'&mid=2&ok=1', '15 минут');
			knopka('adm.php?mod=chatban&lgn='.$lgn.'&mid=3&ok=1', '30 минут');
			knopka('adm.php?mod=chatban&lgn='.$lgn.'&mid=4&ok=1', '1 час');
			knopka('adm.php?mod=chatban&lgn='.$lgn.'&mid=5&ok=1', '1 сутки');
			if (2 <= $f['admin']) knopka('adm.php?mod=chatban&lgn='.$lgn.'&mid=6&ok=1', '2 суток');
			if (3 <= $f['admin']) knopka('adm.php?mod=chatban&lgn='.$lgn.'&mid=7&ok=1', '7 суток');
			knopka('chat.php', 'В чат');
			knopka('adm.php', 'В админку');
			fin();
			}

		if ($f['admin'] < $l['admin']) msg2('Недостаточно прав.', 1);
		if ($l['ban'] > $t or !empty($l['flag_blok'])) msg2('У персонажа '.$lgn.' уже установлена молча или он в блоке.', 1);
//		if ($l['login'] == $f['login']) msg2('Нельзя поставить молчу самому себе, попросите админа :)', 1);
		$timeban = 0;
		if ($mid == 1) $timeban = 60 * 5;
		if ($mid == 2) $timeban = 60 * 15;
		if ($mid == 3) $timeban = 60 * 30;
		if ($mid == 4) $timeban = 60 * 60;
		if ($mid == 5) $timeban = 60 * 60 * 24;
		if ($mid == 6)
			{
			if ($f['admin'] >= 2) $timeban = 60 * 60 * 24 * 2; else msg2('Недостаточно прав', 1);
			}

		if ($mid == 7)
			{
			if ($f['admin'] >= 3) $timeban = 60 * 60 * 24 * 7; else msg2('Недостаточно прав', 1);
			}

		if ($mid < 0 or $mid > 7) msg2('Недостаточно прав', 1);

		if(empty($_REQUEST['mess']))

			{
			echo '<form action="adm.php?mod=chatban&lgn='.$lgn.'&mid='.$mid.'&ok=1" method="POST">';
			echo '<div class="board" style="text-align:left">';
			echo 'Вы собираетесь поставить молчу на '.ceil($timeban / 60).' минут персонажу '.$lgn.'. Причина:<br/>';
			echo '<input type="text" class="name" name="mess"/><br/>';
			echo '<input type="submit" class="btn" value="Продолжить"/></form>';
			echo '</div>';
			knopka('chat.php', 'В чат');
			knopka('adm.php', 'В админку');
			fin();
			}

		$mess = ekr($_REQUEST['mess']);
		$room = $f['chatroom'];
		$timeban += $t;
		$q = $db->query("update `users` set ban={$timeban},zachto_blok='{$mess}' where id='{$l['id']}' limit 1;");

//		$mess = '<span style="color:'.$notice.';"><img src="pic/1429.png" alt=""/><b>System:<br>Персонаж '.$lgn.' получает молчу на '.intval(($timeban - $t) / 60).' минут. Причина: '.$mess.'. Модератор: '.$f['login'].'.</b></span>';
$mess = '<span style="color:'.$notice.';"><img src="pic/1429.png" alt=""/><b>System:<br>Модератор '.$f['login'].' стреляет из бананомета бананом на '.intval(($timeban - $t) / 60).' минут в игрока '.$lgn.' с криком: '.$mess.'!</b></span>';
		$q = $db->query("insert into `chat` values(0,'{$settings['bot']}','{$mess}','{$room}','',0,1,'{$t}','',0,0);");
		msg2('Вы поставили молчу персонажу '.$lgn.' на '.ceil(($timeban - $t) / 60).' минут.');
		knopka('chat.php', 'В чат');
		knopka('adm.php', 'В админку');
		fin();
	break;

	case 'chatunban':
		if($f['admin'] < 1) msg('Недостаточно прав!', 1);
		if(empty($lgn))
			{
			echo '<form action="adm.php?mod=chatunban" method="POST">';
			echo 'Введите логин:<br/>';
			echo '<input type="text" class="name" name="lgn"/>';
			echo '<input type="submit" class="btn" value="Далее"/>';
			echo '</form>';
			fin();
			}
		$l = get_login($lgn);
		$lgn = $l['login'];
		if ($f['admin'] < $l['admin']) msg2('Недостаточно прав', 1);
		if ($l['ban'] < $t) msg2('У персонажа '.$lgn.' отсутствует молча.', 1);
		if ($l['flag_blok'] == 1) msg2('Персонаж '.$lgn.' заблокирован.', 1);
		if ($l['login'] == $f['login']) msg2('Нельзя снять молчу у себя, попросите админа :)', 1);
		if (empty($_REQUEST['ok']))
			{
			msg2('У персонажа '.$lgn.' молча за '.$l['zachto_blok'].'. Вы уверены, что хотите снять молчу?');
			knopka('adm.php?mod=chatunban&lgn='.$lgn.'&ok=1', 'Снять молчу');
			knopka('chat.php', 'В чат');
			knopka('adm.php', 'В админку');
			fin();
			}

		$room = $f['chatroom'];
		$mess = '<span style="color:'.$notice.';"><img src="pic/1429.png" alt=""/><b>System:<br>Персонажу '.$lgn.' снята молча. Модератор: '.$f['login'].'.</b></span>';
		$q = $db->query("insert into `chat` values(0,'{$settings['bot']}','{$mess}','{$room}','',0,1,'{$t}','',0,0);");
		$q = $db->query("update `users` set ban=0,zachto_blok='' where id='{$l['id']}' limit 1;");
		msg2('Вы сняли молчу персонажу '.$lgn.'.');
		knopka('chat.php', 'В чат');
		knopka('adm.php', 'В админку');
		fin();
	break;

	case 'chatclear':
		if($f['admin'] < 3) msg('Недостаточно прав!', 1);
		if (empty($_REQUEST['ok']))
			{
			msg2('Все сообщения во всех комнатах будут удалены!');
			knopka('adm.php?mod=chatclear&ok=1', 'Продолжить');
			knopka('chat.php', 'В чат');
			knopka('adm.php', 'В админку');
			fin();
			}

		$q = $db->query("truncate table `chat`;");
		msg('Все чаты очищены');
		knopka('chat.php', 'В чат');
		knopka('adm.php', 'В админку');
		fin();
	break;

	case 'chatroomclear':
		if($f['admin'] < 2) msg('Недостаточно прав!', 1);
		if (empty($_REQUEST['ok']))
			{
			msg2('Все сообщения в этой комнате будут удалены!');
			knopka('adm.php?mod=chatroomclear&ok=1', 'Продолжить');
			knopka('chat.php', 'В чат');
			knopka('adm.php', 'В админку');
			fin();
			}
		$room = $f['chatroom'];
		if ($room == 1) $q = $db->query("delete from `chat` where room={$room} and login<>'{$settings['bot']}';");
		else if ($room == 2) $q = $db->query("delete from `chat` where room={$room} and klan='{$f['klan']}' and login<>'{$settings['bot']}';");
		else if ($room == 3) $q = $db->query("delete from `chat` where room={$room} and party='{$f['party']}' and login<>'{$settings['bot']}';");
		msg('Все сообщения в комнате чата удалены.');
		knopka('chat.php', 'В чат');
		knopka('adm.php', 'В админку');
		fin();
	break;

	case 'item':
		if ($f['admin'] < 3) msg2('Недоступно для вас', 1);
		if (empty($go) and empty($num))
			{
			echo '<div class="board" style="text-align:left">';
			echo '<form action="adm.php?mod=item&go=1" method="POST">
		Введите ID вещи:<br/>
		<input type="number" name="num" value="1"/><br/>
		<input type="submit" value="Далее"/></form></div>';
			knopka('adm.php', 'В админку', 1);
			fin();
			}
		$item = $items->base_shmot($num);
		$items->add_item($f['login'], $num, 1);
		msg2('Вы получили '.$item['name']);
		knopka('adm.php', 'В админку', 1);
		fin();
		break;

	case 'othelit':
		if ($f['admin'] < 2) msg2('Вы не можете лечить игроков', 1);
		if (empty($ok))
			{
			msg2('Вы хотите вылечить всех игроков?');
			knopka('adm.php?mod=othelit&ok=1', 'Вылечить', 1);
			knopka('adm.php', 'В админку', 1);
			fin();
			}
		$q = $db->query("update `users` set hpnow=hpmax,mananow=manamax where status<>1;"); //status=0 - то есть кто не в бою
		msg2('Готово! Жизни и Мана ВСЕМ игрокам восстановлены.');
		knopka('adm.php', 'В админку', 1);
		fin();
		break;

	case 'givemoney':
		if ($f['admin'] < 2) msg2('Вы не можете начислять монеты игрокам', 1);
		if (empty($lgn) or empty($num))
			{
			echo '<div class="board" style="text-align:left">';
			echo '<form action="adm.php?mod=givemoney" method="POST">
			Введите логин получателя:<br/>
			<input type="text" name="lgn" value="'.$lgn.'"/><br/>
			Введите количество монет:<br/>
			<input type="number" name="num" value="'.$num.'"/><select name="tip"><option value="1">Медь</option>
<option value="2">Серебро</option>
<option value="3">Золото</option>
</select><br>
Введите коммент:<br>
<input type="text" name="comment" value="'.$comment.'"/><br/>
			<input type="submit" value="Далее" />
			</form></div>';
			knopka('adm.php', 'В админку', 1);
			fin();
			}
		$num = intval($num);

		$TestLGN = get_login($lgn);
		$lgn = $TestLGN['login'];

if($tip == 1) $q = $db->query("update `users` set cu=cu+{$num} where login='{$lgn}' limit 1;");
elseif($tip == 2) $q = $db->query("update `users` set ag=ag+{$num} where login='{$lgn}' limit 1;");
elseif($tip == 3) $q = $db->query("update `users` set au=au+{$num} where login='{$lgn}' limit 1;");
if($tip ==1) $tipl = 'медных';
if($tip ==2) $tipl = 'серебряных';
if($tip ==3) $tipl = 'золотых';
if ($num >= 0) $log = $f['login'].' ['.$f['lvl'].'] начислил '.$lgn.' ['.$TestLGN['lvl'].'] '.$num.' '.$tipl.' монет. Причина: '.$comment.' - ';
if ($num < 0) $log = $f['login'].' ['.$f['lvl'].'] оштрафовал '.$lgn.' ['.$TestLGN['lvl'].'] на '.$num.' '.$tipl.' монет. Причина: '.$comment.' - ';
		$mess = '<b>Админка</b>: персонаж '.$f['login'].' передал персонажу '.$lgn.' '.$num.' '.$tipl.' монет с комментарием: '.$comment.'.';
		require_once('inc/i.php');
		$q = $db->query("insert into `letters` values(0,0,'{$t}','{$admin}','{$settings['bot']}','{$mess}',0,0);");
		$q = $db->query("insert into `log_peredach` values(0,'{$f['login']}','{$log}', '{$lgn}', '{$t}');");
		msg2('Персонажу '.$lgn.' начислено '.$num.' '.$tipl.' монет. Причина: '.$comment.'.');
		knopka('adm.php', 'В админку', 1);
		fin();
		break;




	case 'mysql':
		if ($f['admin'] < 3) msg2('Вы не можете работать с MySQL', 1);
		if (empty($mysql))
			{
			echo '<div class="board" style="text-align:left">';
			echo '<form action="adm.php?mod=mysql&go=1" method="POST">
		Введите mysql-запрос:<br/>
		<input type="text" name="mysql" /><br/>
		<input type="submit" value="Далее" />
		</form></div>';
			knopka('adm.php', 'В админку', 1);
			fin();
			}
		$q = $db->query($mysql) or msg2('Ошибка MySQLi', 1);
		msg2('Запрос '.htmlspecialchars($mysql).' выполнен успешно');
		knopka('adm.php', 'В админку', 1);
		fin();
		break;

	case 'lastonl':
		if ($f['admin'] < 1) msg2('Вы не можете просматривать полный список игроков', 1);
		$q = $db->query("select lvl,login,lastdate from `users` order by lastdate desc;");
		$count = 0;
		while ($Arr = $q->fetch_assoc())
			{
			$count++;
			echo '<div class="board2" style="text-align:left">'.$count.') <a href="infa.php?mod=uzinfa&lgn='.$Arr['login'].'">'.$Arr['login'].' ['.$Arr['lvl'].']</a> ('.Date('d.m.Y H:i', $Arr['lastdate']).')';
			echo ' <a href="adm.php?mod=ipsoft&lgn='.$Arr['login'].'">[ip/soft]</a></div>';
			}
		fin();
		break;

	case 'lookblok':
		if ($f['admin'] < 2) msg2('Вы не можете работать с заблокированными персонажами', 1);
		$q = $db->query("select lvl,login,ban from `users` where flag_blok=1 order by lastdate desc;");
		while ($Arr = $q->fetch_assoc())
			{
			echo '<div class="board2" style="text-align:left"><a href="infa.php?mod=uzinfa&lgn='.$Arr['login'].'">'.$Arr['login'].'</a> ['.$Arr['lvl'].']';
			if ($Arr['ban'] > $t) echo ' (до '.Date('d.m.Y H:i', $Arr['ban']).')';
			echo ' <a href="adm.php?mod=ipsoft&lgn='.$Arr['login'].'">[ip/soft]</a>';
			echo ' <a href="adm.php?mod=unblok&lgn='.$Arr['login'].'">[unblock]</a></div>';
			}
		fin();
		break;

	case 'sovp':
		if ($f['admin'] < 1) msg2('Совпадения паролей недоступны для вас', 1);
		msg2('Совпадение по паролю:');
		echo '<div class="board" style="text-align:left">';
		$q = $db->query("SELECT pass from `users` group by pass having count(pass)>1;");
		while ($a = $q->fetch_assoc())
			{
			echo '<table border=1>';
			if (3 <= $f['admin']) echo '<tr><td width="240" height="16">'.$a['pass'].'</td></tr>';
			else echo '<tr><td width="240" height="16">Совпадение пароля!!!</td></tr>';
			$b = $db->query("select login,lvl from `users` where pass='{$a['pass']}';");
			while ($c = $b->fetch_assoc())
				{
				echo '<tr><td><a href="infa.php?mod=uzinfa&lgn='.$c['login'].'">'.$c['login'].'</a> ['.$c['lvl'].']</td></tr>';
				}
			echo '</table><br/>';
			}
		echo '</div>';
		fin();
		break;

	case 'sovpip':
		if ($f['admin'] < 1) msg2('Совпадения IP недоступны для вас', 1);
		msg2('Совпадение по IP:');
		$q = $db->query("SELECT ip from `users` where ip<>'' group by ip having count(ip)>1;");
		if ($q->num_rows == 0) msg2('Совпадений IP не обнаружено.', 1);
		echo '<div class="board" style="text-align:left">';
		while ($a = $q->fetch_assoc())
			{
			echo '<table border=1>';
			echo '<tr><td width="240" height="16">'.$a['ip'].'</td></tr>';
			$b = $db->query("select login,lvl from `users` where ip='{$a['ip']}';");
			while ($c = $b->fetch_assoc())
				{
				echo '<tr><td><a href="infa.php?mod=uzinfa&lgn='.$c['login'].'">'.$c['login'].'</a> ['.$c['lvl'].']</td></tr>';
				}
			echo '</table><br/>';
			}
		echo '</div>';
		fin();
		break;

	case 'sovpipall':
		if ($f['admin'] < 1) msg2('Совпадения IP недоступны для вас', 1);
		if (!empty($_SESSION['ip']) and $_SESSION['ip'] > $t) msg2('Нельзя использовать данную функцию чаще, чем раз в минуту, слишком большая нагрузка на сервер!', 1);
		msg2('Совпадение по IP (архив):');
		$_SESSION['ip'] = 60 + $t;
		$q = $db->query("SELECT ip,login from `ipsoft` where ip<>'' and login<>'' group by ip having count(login)>1;");
		$count = 0;
		while ($a = $q->fetch_assoc())
			{
			$qq = $db->query("select login from `ipsoft` where ip='{$a['ip']}' and login<>'' and login<>'{$a['login']}' group by login;");
			if($qq->num_rows > 0)
				{
				$count++;
				echo '<div class="board" style="text-align:left">';
				echo '<table border=1>';
				echo '<tr><td width="240" height="16">'.$a['ip'].'</td></tr>';
				echo '<tr><td><a href="infa.php?mod=uzinfa&lgn='.$a['login'].'">'.$a['login'].'</a></td></tr>';
				while($b = $qq->fetch_assoc())
					{
					echo '<tr><td><a href="infa.php?mod=uzinfa&lgn='.$b['login'].'">'.$b['login'].'</a></td></tr>';
					}
				echo '</table><br/>';
				echo '</div>';
				}
			}
		if($count == 0) msg2('Совпадений IP не найдено', 1);
		fin();
		break;

	case 'sovpsoftall':
		if ($f['admin'] < 1) msg2('Совпадения SOFT недоступны для вас', 1);
		if (!empty($_SESSION['ip']) and $_SESSION['ip'] > $t) msg2('Нельзя использовать данную функцию чаще, чем раз в минуту, слишком большая нагрузка на сервер!', 1);
		msg2('Совпадение по SOFT (архив):');
		$_SESSION['ip'] = 60 + $t;
		$q = $db->query("SELECT soft,login from `ipsoft` where soft<>'' and login<>'' group by soft having count(login)>1;");
		$count = 0;
		while ($a = $q->fetch_assoc())
			{
			$qq = $db->query("select login from `ipsoft` where soft='{$a['soft']}' and login<>'' and login<>'{$a['login']}' group by login;");
			if($qq->num_rows > 0)
				{
				$count++;
				echo '<div class="board" style="text-align:left">';
				echo '<table border=1>';
				echo '<tr><td width="240" height="16">'.$a['soft'].'</td></tr>';
				echo '<tr><td><a href="infa.php?mod=uzinfa&lgn='.$a['login'].'">'.$a['login'].'</a></td></tr>';
				while($b = $qq->fetch_assoc())
					{
					echo '<tr><td><a href="infa.php?mod=uzinfa&lgn='.$b['login'].'">'.$b['login'].'</a></td></tr>';
					}
				echo '</table><br/>';
				echo '</div>';
				}
			}
		if($count == 0) msg2('Совпадений SOFT не найдено', 1);
		fin();
		break;

	case 'sovpall':
		if ($f['admin'] < 1) msg2('Совпадения IP/SOFT недоступны для вас', 1);
		if (!empty($_SESSION['ip']) and $_SESSION['ip'] > $t) msg2('Нельзя использовать данную функцию чаще, чем раз в минуту, слишком большая нагрузка на сервер!', 1);
		msg2('Совпадение по IP/SOFT (архив):');
		$_SESSION['ip'] = 60 + $t;
		$q = $db->query("SELECT ip,soft,login from `ipsoft` where ip<>'' and soft<>'' and login<>'' group by ip,soft having count(login)>1;");
		$count = 0;
		while ($a = $q->fetch_assoc())
			{
			$qq = $db->query("select login from `ipsoft` where ip='{$a['ip']}' and soft='{$a['soft']}' and login<>'' and login<>'{$a['login']}' group by login;");
			if($qq->num_rows > 0)
				{
				$count++;
				echo '<div class="board" style="text-align:left">';
				echo '<table border=1>';
				echo '<tr><td width="240" height="16">'.$a['ip'].'</td></tr>';
				echo '<tr><td width="240" height="16">'.$a['soft'].'</td></tr>';
				echo '<tr><td><a href="infa.php?mod=uzinfa&lgn='.$a['login'].'">'.$a['login'].'</a></td></tr>';
				while($b = $qq->fetch_assoc())
					{
					echo '<tr><td><a href="infa.php?mod=uzinfa&lgn='.$b['login'].'">'.$b['login'].'</a></td></tr>';
					}
				echo '</table><br/>';
				echo '</div>';
				}
			}
		if($count == 0) msg2('Совпадений IP/SOFT не найдено', 1);
		fin();
		break;

	case 'admin':
		if ($f['admin'] < 2) msg('Вы не можете управлять званиями', 1);
		if (empty($go))
			{
			echo '<div class="board" style="text-align:left">';
			echo '<form action="adm.php?mod=admin&go=1" method="POST">
			Введите логин:<br/>
			<input type="text" name="lgn" value="'.$lgn.'"/><br/>
			<select name="num">
			<option value="0">Игрок</option>
			<option value="1">Модер</option>
			<option value="2">Супермодер</option>';
			if ($f['admin'] >= 3) echo '<option value="3">Админ</option>';
			if ($f['admin'] >= 4) echo '<option value="4">Суперадмин</option>';
			echo '</select>
		<br/>
		<input type="submit" value="Далее" />
		</form></div>';
			knopka('adm.php', 'В админку', 1);
			fin();
			}
		if ($num != 0 and $num != 1 and $num != 2 and $num != 3 and $num != 4) msg2('Неверное значение', 1);
		if ($num == 3 and $f['admin'] < 3) msg2('Админа может назначать Админ или Суперадмин', 1);
		if ($num == 3 and $f['admin'] < 3) msg2('Суперадмина может назначать только Суперадмин', 1);
		$TestLGN = get_login($lgn);
		$lgn = $TestLGN['login'];
		if ($TestLGN['admin'] >= 4)
			{
			//FIX: первый пользователь - admin, системный персонаж.
			msg2('Нельзя забрать права суперадмина!', 1);
			}
		$q = $db->query("update `users` set admin={$num} where login='{$lgn}' limit 1;");
		if ($num == 0) $num = 'игрока';
		elseif ($num == 1) $num = 'модера';
		elseif ($num == 2) $num = 'супермодера';
		elseif ($num == 3) $num = 'админа';
		elseif ($num == 4) $num = 'суперадмина';
		$mess = '<b>Админка</b>: персонаж '.$f['login'].' сменил статус персонажу '.$lgn.'.';
		require_once('inc/i.php');
		$q = $db->query("insert into `letters` values(0,0,'{$t}','{$admin}','{$settings['bot']}','{$mess}',0,0);");
		msg2('Персонажу '.$lgn.' присвоен статус '.$num.'!');
		knopka('adm.php', 'В админку', 1);
		fin();
		break;

	case 'mail':
		if(empty($go))
			{
			echo '<div class="board" style="text-align:left">';
			echo '<form action="adm.php?mod=mail&go=1" method="POST">';
			echo 'Отправка почты<br/>';
			echo 'Email: <input type="text" name="lgn"/><br/>';
			echo 'Text: <br/>';
			echo '<textarea name="lgn2" maxlength=5000 rows="10" style="width:80%;"></textarea><br/>';
			echo '<input type="submit" value="Далее"/></form></div>';
			knopka('adm.php', 'В админку', 1);
			fin();
			}
		if(empty($lgn)) msg2('Не ввели Email',1);
		if(empty($lgn2)) msg2('Не ввели текст сообщения',1);
		$subj = 'Администрация Вальдирры';
		$mess = $lgn2;
		$from = 'NoReply@mirtania.tiflohelp.ru';
		if(mail_utf8($lgn, $subj, $mess, $from)) msg2('Отправлено успешно', 1);
		else msg2('Ошибка отправки Email!', 1);
break;

case 'locs':
echo'<div class="board">Список всех локаций:<br>
<table border=1>
<tr>
<th>ID</th>
<th>Название</th>
<th>Проход на север</th>
<th>Проход на юг</th>
<th>Проход на восток</th>
<th>Проход на запад</th>
<th>X</th>
<th>Y</th>
<th>Описание</th>
<th>Редактировать</th>
<th>Удалить</th>
</tr>';
$q = $db->query("select * from `loc`");
while($l = $q->fetch_assoc()){

echo'<tr>
<td>'.$l['id'].'</td>
<td>'.$l['name'].'</td>
<td>'.$l['N'].'</td>
<td>'.$l['S'].'</td>
<td>'.$l['E'].'</td>
<td>'.$l['W'].'</td>
<td>'.$l['X'].'</td>
<td>'.$l['Y'].'</td>
<td>'.$l['info'].'</td>
<td><form action="adm.php?mod=addloc&go=3" method="post"><input type="hidden" name="id" value="'.$l['id'].'"/><input type="submit" value="'.$l['id'].'"/></form></td>
<td><form action="adm.php?mod=addloc&go=5" method="POST"><input type="hidden" name="id" value="'.$l['id'].'"/><input type="submit" value="'.$l['id'].'"/></form></td>
</tr>';
}
echo'</table></div>';
fin();
break;


case 'mailm';
msg2('Разослать всем мыло!');
$q = $db->query("select * from `users`");
while($k = $q->fetch_assoc()){

$from = 'noreply@';
$sub = 'Новогодний эвент';
$mess = 'Здравствуйте, '.$k['login'].'!

В Вальдирре запущен новогодний эвент, присоединяйтесь!

Перейти к игре вы можете по ссылке:
http:///

Если вы забыли свой пароль, то перейдите по ссылке:
http:///pass.php
и востановите его.

--
С уважением,
Администрация Вальдирры
admin@';
$to = $k['email'];
mail_utf8($to, $sub, $mess, $from);
}

fin();
break;

	case 'logipsoft':
		if ($f['admin'] < 1) msg2('Вы не можете просматривать лог IP/SOFT', 1);
		$numb = 10;	//сообщений на странице
		$q = $db->query("select count(*) from `log_ipsoft`;");
		$all_log = $q->fetch_assoc();
		$all_log = $all_log['count(*)'];
		if($all_log <= 0) msg('Лог IP/SOFT пуст!', 1);
		if ($start > intval($all_log / $numb)) $start = intval($all_log / $numb);
		if ($start < 0) $start = 0;
		$limit = $start * $numb;
		$count = $limit;
		$q = $db->query("select * from `log_ipsoft` limit {$limit},{$numb};");
		while ($a = $q->fetch_assoc())
			{
			$count ++;
			echo '<div class="board2" style="text-align:left;">';
			echo $count.') <b>'.date('d.m.Y H:i', $a['date']).'</b> - <a href="infa.php?mod=uzinfa&lgn='.$a['login'].'">'.$a['login'].'</a>';
			//echo ' <a href="adm.php?mod=delipsoft&iid='.$a['id'].'">[x]</a>';
			echo '<br/>';
			echo $a['log'];
			echo '</div>';
			}
		echo '<div class="board">';
		if ($start > 0) echo '<a href="adm.php?mod=logipsoft&start='.($start - 1).'" class="navig"><-Назад</a>';
		else echo '<a href="#" class="navig"> <-Назад</a>';
		echo ' | ';
		if ($limit + $numb < $all_log) echo '<a href="adm.php?mod=logipsoft&start='.($start + 1).'" class="navig" >Вперед-></a>';
		else echo ' <a href="#" class="navig"> Вперед-></a>';
		echo '</div>';
		fin();
break;

case 'kartograf':
if($f['admin'] <2) msg2('Пшел нахрен!',1);
if($go==1){
$q = $db->query("update `users` set p_kartograf=1 where login='{$f['login']}' limit 1;");
header("location: loc.php");
}
if($go==2){
$q = $db->query("update `users` set p_kartograf=0 where login='{$f['login']}' limit 1;");
header("location: loc.php");
}
break;

case 'dropinvent':
$q = $db->query("select * from `invent`");
while($item = $q->fetch_assoc()){
//if($item['iznosday'] + 86400 < $t){
$tri = $t - $item['iznosday'];
$iznos = $tri/86400;
$q = $db->query("update `invent` set iznos=iznos-{$iznos},iznosday={$t} where id={$item['id']};");
//}
/*
if($item['iznos'] <=0){
$q = $db->query("delete from `invent` where id={$item['id']} limit 1;");
msg2('Рассыпалась '.$item['id'].'');
}
*/
}
fin();
break;
endswitch;
?>
