<?php
##############
# 24.12.2014 #
##############

require_once('inc/top.php'); // вывод на экран
require_once('inc/check.php'); // вход в игру
require_once('inc/head.php');
require_once('class/items.php'); // работа с вещами

$mod = isset($_REQUEST['mod']) ? $_REQUEST['mod'] : '';
$lgn = isset($_REQUEST['lgn']) ? $_REQUEST['lgn'] : '';
$lgn2 = isset($_REQUEST['lgn2']) ? $_REQUEST['lgn2'] : '';
$go = isset($_REQUEST['go']) ? $_REQUEST['go'] : '';
$to = isset($_REQUEST['to']) ? $_REQUEST['to'] : '';
$iid = isset($_REQUEST['iid']) ? $_REQUEST['iid'] : 0;
$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
if (empty($mod))
	{
	echo '<div class="board2">';
	echo '<form action="infa.php?mod=uzinfa" method="POST">
	Введите логин:<br/>
	<input type="text" class="ptext" name="lgn" />
	<input type="submit" class="button" value="Поиск"/>
	</form></div>';
	knopka('infa.php?mod=rate', 'Рейтинг игроков', 1);
	knopka('infa.php?mod=blok', 'Заблокированные', 1);
	knopka('javascript:history.go(-1)', 'Вернуться', 1);
	fin();
	}
elseif ($mod == 'onl')
	{
	// сколько за сутки
	$timer = $t - 86400;
	$a = $db->query("select count(*) from `users` where lastdate>'{$timer}';");
	$a = $a->fetch_assoc();
	$count_sutki = $a['count(*)'];
	// сколько за 15 минут
	$timer1 = $t - 900;
	$timer2 = $t - 7200;
	$q = $db->query("select login,lvl,sex,status,rabota,klan from `users` where (lastdate>'{$timer1}' or (status=1 AND lastdate>'{$timer2}')) order by login;");
	$count_onl = $q->num_rows;
	echo '<div class="board2" style="text-align:left">';
	echo 'Онлайн: '.$count_onl.', за сутки: '.$count_sutki.'</div>';
	$count = 0;
	while ($array_onl = $q->fetch_assoc())
		{
		if ($array_onl['sex'] == 1) $color_login = $male;
		else $color_login = $female;
		$count++;
		$str = '';
		$str .= $count.'. <span style="color:'.$color_login.'">'.$array_onl['login'].' ['.$array_onl['lvl'].']</span>';
		if (!empty($array_onl['klan'])) $str .= ' ('.$array_onl['klan'].')';
		if ($array_onl['status'] == 1) $str .= ' [Б]';
		if ($array_onl['rabota'] > $_SERVER['REQUEST_TIME']) $str .= ' <span style="color:'.$female.'">[Р]</span>';
		knopka('infa.php?mod=uzinfa&lgn='.$array_onl['login'], $str);
		}


	echo '</div>';
    knopka('javascript:history.go(-1)', 'Вернуться', 1);
	fin();
	}

elseif ($mod == 'rate')
	{
		echo '<div class="board2">Топ 10:</div>';
	$count = 0; //счётчик
	$numb = 10; //кол-во игроков на странице за раз
	$q = $db->query("select count(id) from `users` where admin<3 AND flag_blok=0;");
	$a = $q->fetch_assoc();
	$allg = $a['count(id)'];
	if ($start > intval($allg / $numb)) $start = intval($allg / $numb);
	if ($start < 0) $start = 0;
	$limit = $start * $numb;
	$q = $db->query("select login,lvl,sex,exp from `users` where admin<3 AND flag_blok=0 order by lvl desc,exp desc,login limit {$limit},{$numb};");
	while ($array_onl = $q->fetch_assoc())
		{
		if ($array_onl['sex'] == 1) $color_login = $male;
		else $color_login = $female;
		$count++;
		knopka('infa.php?mod=uzinfa&lgn='.$array_onl['login'], $count.'. <span style="color:'.$color_login.'">'.$array_onl['login'].' ['.$array_onl['lvl'].']</span>');
		}
	echo '<div class="board" style="text-align:center">';
	if ($start > 0) echo '<a href="infa.php?mod=rate&start='.($start - 1).'" class="navig"><-Назад</a>';
	else echo '<a href="#" class="navig"> <-Назад</a>';
	echo ' | ';
	if ($limit + $numb < $allg) echo '<a href="infa.php?mod=rate&start='.($start + 1).'" class="navig" >Вперед-></a>';
	else echo ' <a href="#" class="navig"> Вперед-></a>';


	echo '</div>';
    knopka('javascript:history.go(-1)', 'Вернуться', 1);
	fin();
	}

elseif ($mod == 'who')
	{
	// кто с вами на одной локации
	$timer1 = $t - 300;
	$timer2 = $t - 7200;
	$q = $db->query("select login,lvl,sex,status,klan from `users` where loc='{$f['loc']}' AND login<>'{$f['login']}' AND (lastdate>'{$timer1}' or (status=1 AND lastdate>'{$timer2}')) order by lvl;");
	$count = 0;
	if ($q->num_rows == 0) msg2('Рядом никого нет', 1);
	while ($array_onl = $q->fetch_assoc())
		{
		if ($array_onl['sex'] == 1) $color_login = $male;
		else $color_login = $female;
		$count++;
		$str = '';
		$str .= $count.'. <span style="color:'.$color_login.'">'.$array_onl['login'].' ['.$array_onl['lvl'].']</span>';
		if (!empty($array_onl['klan'])) $str .= ' ('.$array_onl['klan'].')';
		if ($array_onl['status'] == 1) $str .= ' [Б]';
		knopka('infa.php?mod=uzinfa&lgn='.$array_onl['login'], $str);
		}
	fin();
	}

elseif ($mod == 'uzinfa')
	{
if(empty($go)){
	// просто анкета, которая открывается через поиск
	$l = get_login($lgn);
	$lgn = $l['login'];

	if ($l['flag_blok'] == 1)
		{
		msg('Персонаж заблокирован.<br/>
		'.$l['zachto_blok'].'<br/>');
		if ($l['ban'] > 0)  echo '<div class="board2">Блок наложен до '.Date('d.m.Y H:i</div>', $l['ban']);
		if ($l['ban'] == 0)  echo '<div class="board2">Блок наложен пожизненно';
		echo '</div>';
}


	echo ' <div class="board2">';
	echo '<b>'.$lgn.' </b><br>';
	if ($l['admin'] > 0)
		{
		echo '<span style="color:'.$male.'">Статус: ';
		if ($l['admin'] == 1) echo 'Модератор (m)';
		if ($l['admin'] == 2) echo 'Гейм мастер (gm)';
		if ($l['admin'] == 3) echo 'Администратор (a)';
		if ($l['admin'] >= 4) echo 'Технический администратор (ta)';
		if ($l['admin'] == '-2') echo 'Гейм дизайнер (gd)';
		if ($l['p_kartograf'] == 1) echo 'Картограф (k)';
}
echo'<br>Раса: ';
if($l['rasa'] == 1) echo'Человек';
if($l['rasa'] ==2) echo'Гном';
if($l['rasa'] ==3) echo'Эльф';
if($l['rasa'] ==4) echo'Орк';

		echo '</span> ';

echo '<br><span style="color:'.$male.'">Известность: ';
if ($l['slava'] < 25) echo'Неизвестный';
if ($l['slava'] < 50 and $l['slava'] >= 25) echo'Бродяга';
if ($l['slava'] < 100 and $l['slava'] >= 50) echo'Искатель приключений';
if ($l['slava'] < 250 and $l['slava'] >= 100) echo'Авантюрист';
if ($l['slava'] < 500 and $l['slava'] >= 250) echo'Известный';
if ($l['slava'] < 1000 and $l['slava'] >= 500) echo'Знаменитый Герой';
if ($l['slava'] < 5000 and $l['slava'] >= 1000) echo'Прославленный';
if ($l['slava'] < 10000 and $l['slava'] >= 5000) echo'Герой сказаний';
if ($l['slava'] >= 10000) echo'Легендарный Герой';
echo'</span>';

	if ($l['sex'] == 1) $pol = ' Был'; else $pol = ' Была';
	if ($l['lastdate'] < $t - 300) echo '<br/><u>'.$pol.' '.Date('d.m.Y H:i', $l['lastdate']).'</u>';
	else echo '<br/><span style="color:'.$notice.'"><b> В игре</b></span>';
	if (!empty($l['brak'])) echo'<br/> В браке с: <a href="infa.php?mod=uzinfa&lgn='.$l['brak'].'">'.$l['brak'].'</a>';
	if (!empty($l['dost'])) echo'<br/> Достижения: '.$l['dost'].'';


	echo '<br/><a href="infa.php?mod=lookboi&lgn='.$lgn.'">';
	if ($l['status'] == 1) echo '<span style="color:'.$female.'">В бою</span>';
	else echo '<span style="color:'.$male.'">Последний Бой</span>';
	echo '</a>';
echo '</div>';

	if ($l['rabota'] > $_SERVER['REQUEST_TIME']) echo '<div class="board">*<small><span style="color:#003333"><b>Персонаж работает</b></span></small></div>';
	if (!empty($l['altar']) and $l['altar_time'] > $_SERVER['REQUEST_TIME']) echo '<div class="board">*<small><span style="color:#003333">Персонаж под защитой алтаря</span></small></div>';
		echo '<div class="board">';
		if ($l['buff'] > 0 and $l['buff_time'] > $t)
			{
			echo 'Благославление: ';
			if ($l['buff'] == 1) echo 'Малое благославление';
}
echo'</div>';
	if ($l['doping'] > 0 and $l['doping_time'] > $t)
		{
		echo '<div class="board">*<small>Допинг: <span style="color:'.$female.'"><b>';
		if ($l['doping'] == 1) echo 'Брага';
		elseif ($l['doping'] == 2) echo 'Пиво';
		elseif ($l['doping'] == 3) echo 'Вино';
		elseif ($l['doping'] == 4) echo 'Самогон';
		elseif ($l['doping'] == 5) echo 'Рисовый шнапс';
		elseif ($l['doping'] == 6) echo 'Ловкость';
		elseif ($l['doping'] == 7) echo 'Реакция';
		elseif ($l['doping'] == 8) echo 'Жизненная энергия';
		elseif ($l['doping'] == 9) echo 'Магическая энергия';
		elseif ($l['doping'] == 10) echo 'Вышибала';
		elseif ($l['doping'] == 11) echo 'Шампанское';
		echo '</b></span></small></div>';
	}


	echo '<div class="board">';
	if ($l['sex'] == 1) $pol = 'мужской';	else $pol = 'женский';
	echo '
	Уровень: '.$l['lvl'].'<br/>
Перерождений: '.$l['pererod'].'<br>
	Имя: '.$l['name'].'<br/>
	Пол: '.$pol.'<br/>
	День рождения: '.$l['d'].'-'.$l['m'].'-'.$l['y'].'<br/>';
if($f['admin'] >=3) msg2('E-mail: '.$l['email'].'');
	if (!empty($l['klan']))
		{
		echo '
		Клан: '.$l['klan'];
		if ($l['klan_status'] > 0)
			{
			echo ' (<span style="color:'.$male.'">';
			if ($l['klan_status'] == 3) echo 'Глава';
			elseif ($l['klan_status'] == 2) echo 'Наместник';
			elseif ($l['klan_status'] == 1) echo 'Зам. главы';
			echo '</span>)';
			}
		echo '<br/>PvP-Статус: ';
		if($l['pvp'] == 1) echo '<span style="color:green"><b>On</b></span>';
		else echo '<span style="color:red"><b>Off</b></span>';
		}
	if (empty($l['klan']) and empty($l['klan_invite']) and $f['klan_status'] >= 2 and 4 <= $l['lvl'])
		{
		knopka2('klan.php?mod=priem&lgn='.$l['login'], 'Пригласить в клан');
		}

	echo '<br/>Побед/поражений: <span style="color:'.$male.'">'.$l['win'].'</span> /
	<span style="color:'.$female.'">'.$l['lost'].'</span><br/>';
	echo 'Регистрация: '.Date('d.m.Y H:i', $l['regdate']);

	echo '<br/><span style="color:'.$male.'"> Профессия: ';
if ($l['p_fishman'] < 50 and $l['p_fishman'] >= 1) echo'<br/>Рыболов-новичок';
if ($l['p_fishman'] < 100 and $l['p_fishman'] >= 50) echo'<br/>Рыболов-любитель';
if ($l['p_fishman'] < 500 and $l['p_fishman'] >= 100) echo'<br/>Рыболов-подмастерье';
if ($l['p_fishman'] < 2500 and $l['p_fishman'] >= 500) echo'<br/>Рыболов-мастер';
if ($l['p_fishman'] >= 2500) echo'<br/>Легендарный рыболов';
if ($l['p_lesorub'] < 50 and $l['p_lesorub'] >= 1) echo'<br/>Лесоруб-новичок';
if ($l['p_lesorub'] < 100 and $l['p_lesorub'] >= 50) echo'<br/>Лесоруб-умелец';
if ($l['p_lesorub'] < 500 and $l['p_lesorub'] >= 100) echo'<br/>Лесоруб-подмастерье';
if ($l['p_lesorub'] < 1000 and $l['p_lesorub'] >= 500) echo'<br/>Лесоруб-мастер';
if ($l['p_lesorub'] >= 1000) echo'<br/>Легендарный лесоруб';
if ($l['p_travnik'] > 0) echo'<br/>Травник';
if ($l['p_ohotnik'] > 0) echo'<br/>Охотник';
if ($l['p_koj'] < 50 and $l['p_koj'] >= 1) echo'<br/>Кожевник-новичок';
if ($l['p_koj'] < 100 and $l['p_koj'] >= 50) echo'<br/>Кожевник-умелец';
if ($l['p_koj'] < 500 and $l['p_koj'] >= 100) echo'<br/>Кожевник-подмастерье';
if ($l['p_koj'] < 1000 and $l['p_koj'] >= 500) echo'<br/>Кожевник-мастер';
if ($l['p_koj'] >= 1000) echo'<br/>Легендарный кожевник';
if ($l['p_alhimik'] < 50 and $l['p_alhimik'] >= 1) echo'<br/>Алхимик-новичок';
if ($l['p_alhimik'] < 100 and $l['p_alhimik'] >= 50) echo'<br/>Алхимик-умелец';
if ($l['p_alhimik'] < 500 and $l['p_alhimik'] >= 100) echo'<br/>Алхимик-подмастерье';
if ($l['p_alhimik'] < 1000 and $l['p_alhimik'] >= 500) echo'<br/>Алхимик-мастер';
if ($l['p_alhimik'] >= 1000) echo'<br/>Легендарный алхимик';
if ($l['p_pivovar'] < 50 and $l['p_pivovar'] >= 1) echo'<br/>Пивовар-новичок';
if ($l['p_pivovar'] < 100 and $l['p_pivovar'] >= 50) echo'<br/>Пивовар-умелец';
if ($l['p_pivovar'] < 500 and $l['p_pivovar'] >= 100) echo'<br/>Пивовар-подмастерье';
if ($l['p_pivovar'] < 1000 and $l['p_pivovar'] >= 500) echo'<br/>Пивовар-мастер';
if ($l['p_pivovar'] >= 1000) echo'<br/>Легендарный пивовар';

if ($l['p_rudokop'] < 25 and $l['p_rudokop'] >= 1) echo'<br/>Рудокоп-новичок';
if ($l['p_rudokop'] < 50 and $l['p_rudokop'] >= 25) echo'<br/>Рудокоп-старатель';
if ($l['p_rudokop'] < 100 and $l['p_rudokop'] >= 50) echo'<br/>Рудокоп-подмастерье';
if ($l['p_rudokop'] < 1000 and $l['p_rudokop'] >= 100) echo'<br/>Рудокоп-мастер';
if ($l['p_rudokop'] >= 1000) echo'<br/>Легендарный рудокоп';
if ($l['p_kuznec'] < 25 and $l['p_kuznec'] >= 1) echo'<br/>Кузнец-новичок';
if ($l['p_kuznec'] < 50 and $l['p_kuznec'] >= 25) echo'<br/>Кузнец-умелец';
if ($l['p_kuznec'] < 100 and $l['p_kuznec'] >= 50) echo'<br/>Кузнец-подмастерье';
if ($l['p_kuznec'] < 1000 and $l['p_kuznec'] >= 100) echo'<br/>Кузнец-мастер';
if ($l['p_kuznec'] >= 1000) echo'<br/>Легендарный кузнец';

if ($l['p_yuvelir'] < 25 and $l['p_yuvelir'] >= 1) echo'<br/>Ювелир-новичок';
if ($l['p_yuvelir'] < 50 and $l['p_yuvelir'] >= 25) echo'<br/>Ювелир-умелец';
if ($l['p_yuvelir'] < 100 and $l['p_yuvelir'] >= 50) echo'<br/>Ювелир-подмастерье';
if ($l['p_yuvelir'] < 250 and $l['p_yuvelir'] >= 100) echo'<br/>Ювелир-мастер';
if ($l['p_yuvelir'] >= 250) echo'<br/>Легендарный ювелир';

if ($l['p_run'] < 25 and $l['p_run'] >= 1) echo'<br/>Рунный новичок';
if ($l['p_run'] < 50 and $l['p_run'] >= 25) echo'<br/>Рунный умелец';
if ($l['p_run'] < 100 and $l['p_run'] >= 50) echo'<br/>Подмастерье рун';
if ($l['p_run'] < 250 and $l['p_run'] >= 100) echo'<br/>Мастер рун';
if ($l['p_run'] >= 250) echo'<br/>Легендарный мастер рун';


    echo'</span>';

	echo '</div>';

	if($lgn != $f['login'])
		{
		knopka('infa.php?mod=oruzh&lgn='.$lgn, 'Экипировка');
		knopka('pm.php?mod=dialog&lgn='.$lgn, 'Написать Письмо');
if($l['ma'] == 1) knopka('infa.php?mod=uzinfa&lgn='.$lgn.'&go=1', 'Отправить e-mail');
		knopka('inv.php?lgn='.$lgn, 'Передать Вещь');
		knopka('inv.php?mod=money&lgn='.$lgn, 'Передать Монеты');
		}
	if(1 <= $f['admin'])
		{
		knopka('adm.php?mod=ipsoft&lgn='.$lgn, 'IP/SOFT');
		knopka('adm.php?mod=blok&lgn='.$lgn, 'Бан');
knopka('adm.php?mod=logpered&lgn='.$lgn, 'Лог передач');
		knopka('adm.php?lgn='.$lgn.'', 'Управление Профилем');
		echo '<div class="board">';
echo 'Медные монеты: '.$l['cu'].'<br/>';
echo 'Серебряные монеты: '.$l['ag'].'<br/>';
echo 'Золотые монеты: '.$l['au'].'<br/>';
		if(!empty($l['bank'])) echo 'Банк: '.$l['bank'].'<br/>';

		echo '</div>';
		}
	if (!empty($l['infa'])) msg('<small><b><u>'.$lgn.' Пишет</u></b>:<br/><br/> '.$l['infa'].'</small>');
fin();
}
elseif($go==1){
	$l = get_login($lgn);
	$lgn = $l['login'];
if($l['ma'] == 0) msg2('Игрок отключил эту функцию',1);
msg2('Вы можете отправить e-mail сообщение игроку '.$lgn.' через следующую форму:');
			echo '<div class="board" style="text-align:left">';
			echo '<form action="infa.php?mod=uzinfa&lgn='.$lgn.'&go=2" method="POST">';
			//echo 'Отправка почты<br/>';

//			echo 'Получатель: <input type="hind" name="to" value="'.$l['email'].'"/>'.$l['login'].'<br/>';
echo'Получатель: '.$lgn.'<br/';
			echo 'Text: <br/>';
			echo '<textarea name="lgn2" maxlength=5000 rows="10" style="width:80%;"></textarea><br/>';
			echo '<input type="submit" value="Далее"/></form></div>';
//			knopka('adm.php', 'В админку', 1);
			fin();
			}
elseif($go==2){
	$l = get_login($lgn);
	$lgn = $l['login'];

//		if(empty($lgn)) msg2('Не ввели Email',1);
		if(empty($lgn2)) msg2('Не ввели текст сообщения',1);
		$subj = 'Сообщение из Вальдирры';
		$mess = '
Здравствуйте, '.$l['login'].'!

Игрок '.$f['login'].' отправил вам письмо через форму отправки e-mail сообщений по адресу http:///infa.php?mod=uzinfa&lgn='.$lgn.'&go=1.
E-mail адрес отправителя: '.$f['email'].'

Отправленное сообщение:
'.$lgn2.'

--
Администрация Вальдирры
admin@valdirra.ru
		';
		$from = 'noreply@valdirra.ru';
		if(mail_utf8($l['email'], $subj, $mess, $from)) msg2('Отправлено успешно', 1);
		else msg2('Ошибка отправки Email!', 1);
fin();

}
	}
elseif ($mod == 'oruzh')
	{
	echo '<div class="board2" style="text-align:left">Список Снаряжения и Статы:<br/>';
	$l = get_login($lgn);
	$lgn = $l['login'];
	echo 'HP: '.$l['hpnow'].' / '.$l['hpmax'].'<br/>';
	echo 'MP: '.$l['mananow'].' / '.$l['manamax'].'<br/>';
	echo 'Сила: '.$l['sila'].'<br/>';
	echo 'Ловкость: '.$l['lovka'].'<br/>';
	echo 'Интуиция: '.$l['inta'].'<br/>';
	echo 'Интеллект: '.$l['intel'].'<br/>';
	echo 'Здоровье: '.$l['zdor'].'<br/>';
	echo '</div>';
	$q = $db->query("select id from `invent` where ((login='{$lgn}' and flag_arenda=0) or (arenda_login='{$lgn}' and flag_arenda=1)) and flag_rinok=0 and flag_equip=1 and (slot='prruka' or slot='lruka' or slot='dospeh' or slot='kolco' or slot='amulet' or slot='braslet' or slot='pojas' or slot='golova' or slot='nogi' or slot='plaw' or slot='perchi' or slot='stup' or slot='serga');");
	if ($q->num_rows == 0) msg2('На '.$lgn.' ничего не надето!', 1);
	$count = 0;
	echo '<div class="board">';
	while ($eq = $q->fetch_assoc())
		{
		$item = $items->shmot($eq['id']);
		if(!empty($count)) echo '<br/>';
		if ($item['slot'] == 'prruka') echo 'Правая рука: ';
		if ($item['slot'] == 'lruka') echo 'Левая рука: ';
		if ($item['slot'] == 'perchi') echo 'Перчатки: ';
		if ($item['slot'] == 'dospeh') echo 'Доспех: ';
		if ($item['slot'] == 'golova') echo 'Голова: ';
		if ($item['slot'] == 'serga') echo 'Серьга: ';
		if ($item['slot'] == 'kolco') echo 'Кольцо: ';
		if ($item['slot'] == 'amulet') echo 'Амулет: ';
		if ($item['slot'] == 'nogi') echo 'Ступни: ';
		if ($item['slot'] == 'stup') echo 'Ноги: ';
		if ($item['slot'] == 'plaw') echo 'Плащ: ';
		if ($item['slot'] == 'braslet') echo 'Браслет: ';
		if ($item['slot'] == 'pojas') echo 'Пояс: ';
		$count ++;
		echo '<b><a href="infa.php?mod=iteminfa&iid='.$item['id'].'"><span style="color:#003333">'.$item['name'].'</span></a></b>';
		}
	echo '</div>';
	knopka('javascript:history.go(-1)', 'Вернуться', 1);
	fin();
	}
elseif ($mod == 'lookboi')
	{
	$l = get_login($lgn);
	$lgn = $l['login'];
	echo '<div class="board" style="text-align:left">';
	$bid = $l['boi_id'];
	$q = $db->query("select flag_boi from `battle` where id='{$bid}' limit 1;");
	$bz = $q->fetch_assoc();
	if (empty($bz['flag_boi'])) $goboi = 0;
	else $goboi = 1;
	$km1 = '';
	$km2 = '';
	$eof1 = '';
	$eof2 = '';
	$str1 = '';
	$str2 = '';
	if ($goboi == 1)
		{
		$q = $db->query("select * from `combat` where boi_id='{$bid}';");
		while ($bz = $q->fetch_assoc())
			{
			if ($bz['komanda'] == 1)
				{
				if ($bz['hpnow'] > 0) $km1 .= '<span style="color:'.$notice.'">'.$bz['login'].'</span> ['.$bz['lvl'].'] ('.$bz['hpnow'].'/'.$bz['hpmax'].') урон: '.$bz['uron_boi'].'<br/>'; //отображение живых
				$eof1 .= $bz['login'].' ('.$bz['hpnow'].'/'.$bz['hpmax'].')<br/>';
				}
			else
				{
				if ($bz['hpnow'] > 0) $km2 .= '<span style="color:'.$male.'">'.$bz['login'].'</span> ['.$bz['lvl'].'] ('.$bz['hpnow'].'/'.$bz['hpmax'].') урон: '.$bz['uron_boi'].'<br/>'; //отображение живых
				$eof2 .= $bz['login'].' ('.$bz['hpnow'].'/'.$bz['hpmax'].')<br/>';
				}
			}
		$q = $db->query("select * from `battlelog` where boi_id='{$bid}' order by id desc;");
		$str1 = $km1.'VS<br/>'.$km2.'<hr/>';
		$str2 = '<hr/>'.$eof1.'VS<br/>'.$eof2;
		}
	else
		{
		$q = $db->query("select * from `battlelog` where boi_id='{$bid}' order by id;");
		}
	$str = '';
	while ($log = $q->fetch_assoc())
		{
		$str .= $log['log'];
		}
	fin($str1.$str.$str2.'<a href="javascript:history.go(-1)">Назад</a>');
	}
elseif ($mod == 'iteminfa')
	{
	$iid = intval($iid);
	if ($iid <= 0) msg2('Вещь не найдена.', 1);
	$q = $db->query("select id from `invent` where id='{$iid}' limit 1;");
	$itm = $q->fetch_assoc();
$item = $items->shmot($itm['id']);
$day = 60*60*24;
if($item['iznosday'] + $day < $t){
$tri = $t - $item['iznosday'];
$iznos = $tri/$day;
$q = $db->query("update `invent` set iznos=iznos-{$iznos},iznosday={$t} where id={$item['id']} limit 1;");
}
if($item['iznos'] <=0){
//$items->del_base_item($item['login'], $item['ido'], 1);
$q = $db->query("delete from `invent` where id={$item['id']} limit 1;");
msg2('Вещь рассыпалась',1);
}
	echo '<div class="board2"><b>'.$item['name'].'</b>';
	if (!empty($item['art'])) echo ' [<font color=green><b>'.$item['art'].'</b></font>]';
	echo '<br/><small>[id: '.$item['id'].']</small>';
	echo '</div>';
	echo '<div class="board" style="text-align:left;">';
	if ($item['flag_arenda'] == 1) echo 'В аренде у <a href="infa.php?mod=uzinfa&lgn='.$item['arenda_login'].'">'.$item['arenda_login'].'</a><br/>';
	elseif ($item['flag_rinok'] == 1) echo 'Продается на рынке<br/>';
	elseif ($item['flag_sklad'] == 1) echo 'Лежит на складе<br/>';
	else echo 'Принадлежит: <a href="infa.php?mod=uzinfa&lgn='.$item['login'].'">'.$item['login'].'</a><br/>';
	echo 'Уровень:  '.$item['lvl'].'</b><br/>
	Цена: '.$item['price'].' <br/>';
echo'Износ: '.$item['iznos'].'<br>';
if($item['ido'] == 753) msg2('Прочность: '.$f['topor'].'');
if($item['ido'] == 754) msg2('Прочность: '.$f['serp'].'');
if($item['ido'] == 755) msg2('Прочность: '.$f['udochka'].'');
if($item['ido'] == 757) msg2('Прочность: '.$f['noj'].'');
if($item['ido'] == 759) msg2('Прочность: '.$f['kirka'].'');
if($item['ido'] == 781) msg2('Прочность: '.$f['molot'].'');
if($item['ido'] == 834) msg2('Прочность: '.$f['nabor'].'');
	if ($item['hp'] > 0)
		{
		$item['hp'] = ceil($item['hp'] + ($item['up'] * $item['hp']	/ 100));
		echo 'Жизни: <font color="red"> +'.$item['hp'].'</font>';
		}
	echo '</div>';
	if (!empty($item['info'])) msg($item['info']);
	else msg('Нет описания');

	if (!empty($item['slot']))
		{
		echo '<div class="board" style="text-align:left;">';
		echo '<b>Характеристики</b>:<br/>';
		if ($item['krit'] > 0)
			{
			$item['krit'] = ceil($item['krit'] + ($item['up'] * $item['krit'] / 100));
			echo 'Крит: '.$item['krit'].'<br/>';
			}
		if ($item['uvorot'] > 0)
			{
			$item['uvorot'] = ceil($item['uvorot'] + ($item['up'] * $item['uvorot']	/ 100));
			echo 'Уворот: '.$item['uvorot'].'<br/>';
			}
		if ($item['uron'] > 0)
			{
			$item['uron'] = ceil($item['uron'] + ($item['up'] * $item['uron'] / 100));
			echo 'Урон: '.$item['uron'].'<br/>';
			}
		if ($item['bron'] > 0)
			{
			$item['bron'] = ceil($item['bron'] + ($item['up'] * $item['bron'] / 100));
			echo 'Броня: '.$item['bron'].'<br/>';
			}
		echo '</div>';
		echo '<div class="board" style="text-align:left;">';
		echo '<b>Требования</b>:<br/>';
		if ($item['zdor'] > 0) echo 'Здоровье: '.$item['zdor'].' <font color=green> ['.$f['zdor'].']</font><br/>';
		if ($item['sila'] > 0) echo 'Сила: '.$item['sila'].' <font color=green> ['.$f['sila'].']</font><br/>';
		if ($item['inta'] > 0) echo 'Интуиция: '.$item['inta'].' <font color=green> ['.$f['inta'].'] </font> <br/>';
		if ($item['lovka'] > 0) echo 'Ловкость: '.$item['lovka'].' <font color=green> ['.$f['lovka'].'] </font> <br/>';
		if ($item['intel'] > 0) echo 'Интеллект: '.$item['intel'].' <font color=green> ['.$f['intel'].'] </font> <br/>';
		if(!empty($item['up'])) knopka2('shop.php?mod=iteminfa&iid='.$item['ido'], 'Базовые параметры вещи');
		if($item['flag_rinok'] == 0 and $item['zdor'] > $f['zdor'] or $item['inta'] > $f['inta'] or $item['sila'] > $f['sila'] or $item['lovka'] > $f['lovka'] or $item['intel'] > $f['intel'] and (($f['login'] == $item['arenda_login'] and $item['flag_arenda'] == 1) or ($f['login'] == $item['login'] and $item['flag_arenda'] == 0)))
			{
			knopka2('anketa.php?mod=stats&ok=1&stat_zdor='.$item['zdor'].'&stat_sila='.$item['sila'].'&stat_inta='.$item['inta'].'&stat_lovka='.$item['lovka'].'&stat_intel='.$item['intel'], 'Расставить статы под эту вещь');
			}
		echo '</div>';
		}
    echo '</div>';
	knopka('javascript:history.go(-1)', 'Вернуться', 1);
	fin();
	}
elseif ($mod == 'blok')
	{
	echo '<div class="board2">Список заблокированых:</div>';
	$count = 0;
	$q = $db->query("update `users` set flag_blok=0, ban=0 where (ban<'{$t}' and ban>0);");
	$q = $db->query("select lvl, login, ban from `users` where flag_blok=1 order by lastdate desc;");
	while ($Arr = $q->fetch_assoc())
		{
		$count++;
		$str = '';
		$str .= $count.'. '.$Arr['login'].' ['.$Arr['lvl'].']';
		if ($Arr['ban'] > $_SERVER['REQUEST_TIME']) $str .= ' (до '.Date('d.m.Y H:i', $Arr['ban']).')';
		knopka('infa.php?mod=uzinfa&lgn='.$Arr['login'], $str);
		}
	echo '</div>';
	knopka('javascript:history.go(-1)', 'Вернуться', 1);
	fin();
	}
elseif ($mod == 'klans')
	{
	echo '<div class="board2">Кланы:</div>';
	$count = 0;
	$q = $db->query("select * from `klans` order by lvl desc,points desc,name;");
	if ($q->num_rows == 0) msg2('Нет ни одного клана', 1);
	while ($sostav = $q->fetch_assoc())
		{
		$count++;
		knopka('infa.php?mod=sostav&lgn='.$sostav['id'], $count.'. '.$sostav['name'].' ['.$sostav['lvl'].']');
		}
	echo '</div>';
	knopka('javascript:history.go(-1)', 'Вернуться', 1);
	fin();
	}
elseif ($mod == 'boi')
	{
	$count = 0;
	$q = $db->query("select * from `battle` where (krov=0 or krov=3 or krov=4 or krov=5) and flag_boi=1 order by id desc;");
	if ($q->num_rows == 0) msg2('Нет ни одного боя', 1);
	while ($b = $q->fetch_assoc())
		{
		$str = '';
		if($b['krov'] == 3) $str = ' (Свиток развоплощения)';
		if($b['krov'] == 0) $str = ' (Бой с мобом)';
		if($b['krov'] == 4) $str = ' (Бой на арене)';
		if($b['krov'] == 5) $str = ' (Свиток нападения)';
		knopka('infa.php?mod=lookboi&lgn='.$b['login'], date('H:i:s', $b['boistart']).' - '.$b['login'].' против '.$b['login2'].$str);
		}
	fin();
	}
elseif ($mod == 'sostav')
	{
	$count = 0;
	$lgn = intval($lgn);
	if ($lgn < 1) $lgn = 1;
	$q = $db->query("select name,lvl,points from `klans` where id='{$lgn}' limit 1;");
	$kkk = $q->fetch_assoc();
	$timer = $t - 2592000;
	$q = $db->query("select login,lvl,sex,exp,klan_status,lastdate from `users` where klan='{$kkk['name']}' and lastdate>'{$timer}' order by lvl desc,exp desc,login;");
	echo '</div><div class="board2">';
	echo 'Клан:';
	echo $kkk['name'].'';
	echo '<br/> Уровень: '.$kkk['lvl'].'';
	echo '<br/>Прогресс:  ('.$kkk['points'].'/'.($kkk['lvl'] * 1000).')';
    echo '</div>';

	while ($sostav = $q->fetch_assoc())
		{
		if ($sostav['sex'] == 1) $color_login = $male;
		else $color_login = $female;
		$count++;
        echo '<div class="board" style="text-align:left;">';
		echo $count.'. ';
		echo '<a href="infa.php?mod=uzinfa&lgn='.$sostav['login'].'"><span style="color:'.$color_login.'">'.$sostav['login'].' ['.$sostav['lvl'].']</span></a> ';
		if ($sostav['klan_status'] == 3) echo '[<b>Глава</b>] ';
		elseif ($sostav['klan_status'] == 2) echo '[<b>Наместник</b>] ';
		elseif ($sostav['klan_status'] == 1) echo '[<b>Зам. Главы</b>] ';
		$raznica = $_SERVER['REQUEST_TIME'] - $sostav['lastdate'];
		if ($raznica <= 300) $onl = '<span style="color:'.$notice.'">В игре</span> ';
		elseif ($raznica <= 3600) $onl = ceil(($raznica) / 60).' мин. назад';
		elseif ($raznica <= 86400) $onl = ceil(($raznica) / 3600).' чс. назад';
		else $onl = ceil(($raznica) / 86400).' дн. назад';
		echo $onl.'<br/></div>';
		}
	echo '</div>';
	knopka('javascript:history.go(-1)', 'Вернуться', 1);
	fin();
	}
?>
