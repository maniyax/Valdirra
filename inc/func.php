<?php

$q = $db->query("select * from `settings` where id=1 limit 1;");
$settings = $q->fetch_assoc();


//фильтрация от взломов
function ekr($a)
	{
	$db = DBC::instance();
	$a = htmlspecialchars($a);
	$a = $db->real_escape_string($a);
	return $a;
	}

function calcparam($s)
	{
	$db = DBC::instance();

	$s['krit'] = $s['inta'] * 10;
$s['uvorot'] = $s['lovka'] * 10;
$s['uron'] = intval($s['sila'] * 1.2);
$s['bron'] = $s['sila'];
$s['hpmax'] = $s['zdor'] * 10;
$s['manamax'] = $s['intel'] * 10;
if($s['hpmax'] < 10) $s['hpmax'] = 10;
if($s['manamax'] < 10) $s['manamax'] = 10;

	$q = $db->query("select * from `invent` where ((login='{$s['login']}' and flag_arenda=0) or (arenda_login='{$s['login']}' and flag_arenda=1)) and flag_rinok=0 and flag_equip=1 and (slot='prruka' or slot='lruka' or slot='dospeh' or slot='kolco' or slot='amulet' or slot='braslet' or slot='pojas' or slot='golova' or slot='nogi' or slot='plaw' or slot='perchi' or slot='serga' or slot='stup');");
	while($item = $q->fetch_assoc())
		{
		if(empty($s['art'][$item['art']])) $s['art'][$item['art']] = 0;
		$s['krit']		+= ceil($item['krit']	+ ($item['up'] * $item['krit']	/ 100));
		$s['uvorot']	+= ceil($item['uvorot']	+ ($item['up'] * $item['uvorot']	/ 100));
		$s['uron']		+= ceil($item['uron']	+ ($item['up'] * $item['uron']	/ 100));
		$s['bron']		+= ceil($item['bron']	+ ($item['up'] * $item['bron']	/ 100));
		$s['hpmax']		+= ceil($item['hp']	+ ($item['up'] * $item['hp']	/ 100));
		if(!empty($item['art'])) $s['art'][$item['art']] ++;
		}
	if($s['altar'] > 0)
		{
		if($s['altar_time'] < $_SERVER['REQUEST_TIME'])
			{
			$s['altar'] = 0;
			$s['altar_time'] = 0;
			$q = $db->query("update `users` set altar=0,altar_time=0 where id='{$s['id']}' limit 1;");
			}
		}
	if($s['altar'] > 0)
		{
		$mnozh = $s['altar'] * 0.01;
		$s['krit']		+= ceil($s['krit']		* $mnozh);
		$s['uvorot']	+= ceil($s['uvorot']	* $mnozh);
		$s['uron']		+= ceil($s['uron']		* $mnozh);
		$s['bron']		+= ceil($s['bron']		* $mnozh);
		}
	if($s['doping'] > 0)
		{
		if($s['doping_time'] < $_SERVER['REQUEST_TIME'])
			{
			$s['doping'] = 0;
			$s['doping_time'] = 0;
			$q = $db->query("update `users` set doping=0,doping_time=0 where id='{$s['id']}' limit 1;");
			}
		}
	if($s['doping'] > 0) require_once('inc/doping.php');
//	$q = $db->query("update `users` set krit={$s['krit']},uvorot={$s['uvorot']},bron={$s['bron']},uron={$s['uron']},hpmax={$s['hpmax']},manamax={$s['manamax']} where id={$s['id']} limit 1;");

	if($s['buff'] > 0)
		{
		if($s['buff_time'] < $_SERVER['REQUEST_TIME'])
			{
			$s['buff'] = 0;
			$s['buff_time'] = 0;
			$q = $db->query("update `users` set buff=0,buff_time=0 where id='{$s['id']}' limit 1;");
			}
		}
	if($s['buff'] > 0) require_once('inc/buff.php');
	$q = $db->query("update `users` set krit={$s['krit']},uvorot={$s['uvorot']},bron={$s['bron']},uron={$s['uron']},hpmax={$s['hpmax']},manamax={$s['manamax']} where id={$s['id']} limit 1;");
	return $s;
}





//функция вывода смайлов :)
function smile($s)
{
$s = str_replace('[b]', '<b>', $s);
$s = str_replace('[/b]', '</b>', $s);
$s = str_replace('[i]', '<i>', $s);
$s = str_replace('[/i]', '</i>', $s);
$s = str_replace('[u]', '<u>', $s);
$s = str_replace('[/u]', '</u>', $s);
$s = str_replace('[red]', '<font color="red">', $s);
$s = str_replace('[/red]', '</fond>', $s);
	$s = str_replace('.афтар.', '<img src="smile/aftar.gif" alt=".афтар."/>', $s);
	$s = str_replace('.бан.', '<img src="smile/ban.gif" alt=".бан."/>', $s);
	$s = str_replace('.банан.', '<img src="smile/banan.gif" alt=".банан."/>', $s);
	$s = str_replace('.банан1.', '<img src="smile/banan1.gif" alt=".банан1."/>', $s);
	$s = str_replace('.бомж.', '<img src=\'smile/bomj.gif\' alt=".бомж."/>', $s);
	$s = str_replace('.браво.', '<img src=\'smile/bravo.gif\' alt=".браво."/>', $s);
	$s = str_replace('.чмак.', '<img src=\'smile/chmak.gif\' alt=".чмак."/>', $s);
	$s = str_replace('.дедмороз.', '<img src=\'smile/dedmoroz.gif\' alt=".дедмороз."/>', $s);
	$s = str_replace('.дети.', '<img src=\'smile/deti.gif\' alt=".дети."/>', $s);
	$s = str_replace('.днюха.', '<img src=\'smile/denrojd.gif\' alt=".днюха."/>', $s);
	$s = str_replace('.добрый.', '<img src=\'smile/dobrij.gif\' alt=".добрый."/>', $s);
	$s = str_replace('.достали.', '<img src=\'smile/dostali.gif\' alt=".достали."/>', $s);
	$s = str_replace('.драка.', '<img src=\'smile/draka.gif\' alt=".драка."/>', $s);
	$s = str_replace('.дум.', '<img src=\'smile/dum.gif\' alt=".дум."/>', $s);
	$s = str_replace('.душ.', '<img src=\'smile/dush.gif\' alt=".душ."/>', $s);
	$s = str_replace('.дятел.', '<img src=\'smile/djatel.gif\' alt=".дятел."/>', $s);
	$s = str_replace('.елка.', '<img src=\'smile/elka.gif\' alt=".елка."/>', $s);
	$s = str_replace('.ёлка.', '<img src=\'smile/elka.gif\' alt=".ёлка."/>', $s);
	$s = str_replace('.фан.', '<img src=\'smile/fan.gif\' alt=".фан."/>', $s);
	$s = str_replace('.фанаты.', '<img src=\'smile/fans.gif\' alt=".фанаты."/>', $s);
	$s = str_replace('.фигасе.', '<img src=\'smile/figase.gif\' alt="фигасе."/>', $s);
	$s = str_replace('.флаг.', '<img src=\'smile/flag.gif\' alt=".флаг."/>', $s);
	$s = str_replace('.флаг1.', '<img src=\'smile/flag1.gif\' alt=".флаг1."/>', $s);
	$s = str_replace('.флуд.', '<img src=\'smile/flud.gif\' alt=".флуд."/>', $s);
	$s = str_replace('.говнецо.', '<img src=\'smile/govneco.gif\' alt=".говнецо."/>', $s);
	$s = str_replace('.грабли.', '<img src=\'smile/grabli.gif\' alt=".грабли."/>', $s);
	$s = str_replace('.грамота.', '<img src=\'smile/gramota.gif\' alt=".грамота."/>', $s);
	$s = str_replace('.сердце.', '<img src=\'smile/heart.gif\' alt=".сердце."/>', $s);
	$s = str_replace('.хор.', '<img src=\'smile/hor.gif\' alt=".хор."/>', $s);
	$s = str_replace('.истерика.', '<img src=\'smile/isterika.gif\' alt=".истерика."/>', $s);
	$s = str_replace('.яд.', '<img src=\'smile/jad.gif\' alt=".яд."/>', $s);
	$s = str_replace('.карты.', '<img src=\'smile/karty.gif\' alt=".карты."/>', $s);
	$s = str_replace('.каток.', '<img src=\'smile/katok.gif\' alt=".каток."/>', $s);
	$s = str_replace('.король.', '<img src=\'smile/king.gif\' alt=".король."/>', $s);
	$s = str_replace('.конфета.', '<img src=\'smile/konfeta.gif\' alt=".конфета."/>', $s);
	$s = str_replace('.кофе.', '<img src=\'smile/kofe.gif\' alt=".кофе."/>', $s);
	$s = str_replace('.комп.', '<img src=\'smile/komp.gif\' alt=".комп."/>', $s);
	$s = str_replace('.конфетти.', '<img src=\'smile/konfetti.gif\' alt=".конфетти."/>', $s);
	$s = str_replace('.конь.', '<img src=\'smile/konj.gif\' alt=".конь."/>', $s);
	$s = str_replace('.курю.', '<img src=\'smile/kurju.gif\' alt=".курю."/>', $s);
	$s = str_replace('.ладно.', '<img src=\'smile/ladno.gif\' alt=".ладно."/>', $s);
	$s = str_replace('.ляля.', '<img src=\'smile/ljalja.gif\' alt=".ляля."/>', $s);
	$s = str_replace('.медик.', '<img src=\'smile/medic.gif\' alt=".медик."/>', $s);
	$s = str_replace('.молоток.', '<img src=\'smile/molotok.gif\' alt=".молоток."/>', $s);
	$s = str_replace('.нефлуди.', '<img src=\'smile/nefludi.gif\' alt=".нефлуди."/>', $s);
	$s = str_replace('.новыйгод.', '<img src=\'smile/newyear.gif\' alt=".новыйгод."/>', $s);
	$s = str_replace('.небань.', '<img src=\'smile/noban.gif\' alt=".небань."/>', $s);
	$s = str_replace('.номер.', '<img src=\'smile/nomer.gif\' alt=".номер."/>', $s);
	$s = str_replace('.ох.', '<img src=\'smile/oh.gif\' alt=".ох."/>', $s);
	$s = str_replace('.пасиба.', '<img src=\'smile/pasiba.gif\' alt=".пасибо."/>', $s);
	$s = str_replace('.песочница.', '<img src=\'smile/pesochnica.gif\' alt=".песочница."/>', $s);
	$s = str_replace('.пионер.', '<img src=\'smile/pioner.gif\' alt=".пионер."/>', $s);
	$s = str_replace('.письмо.', '<img src=\'smile/pismo.gif\' alt=".письмо."/>', $s);
	$s = str_replace('.пифпаф.', '<img src=\'smile/pifpaf.gif\' alt=".пифпаф."/>', $s);
	$s = str_replace('.пиво.', '<img src=\'smile/pivo.gif\' alt=".пиво."/>', $s);
	$s = str_replace('.плак.', '<img src=\'smile/plac.gif\' alt=".плак."/>', $s);
	$s = str_replace('.плохо.', '<img src=\'smile/ploho.gif\' alt=".плохо."/>', $s);
	$s = str_replace('.плюсодин.', '<img src=\'smile/plusodin.gif\' alt=".плюсодин."/>', $s);
	$s = str_replace('.побили.', '<img src=\'smile/pobili.gif\' alt=".побили."/>', $s);
	$s = str_replace('.подарок.', '<img src=\'smile/podarok.gif\' alt=".подарок."/>', $s);
	$s = str_replace('.пока.', '<img src=\'smile/poka.gif\' alt=".пока."/>', $s);
	$s = str_replace('.попа.', '<img src=\'smile/popa.gif\' alt=".попа."/>', $s);
	$s = str_replace('.превед.', '<img src=\'smile/preved.gif\' alt=".превед."/>', $s);
	$s = str_replace('.привет.', '<img src=\'smile/privet.gif\' alt=".привет."/>', $s);
	$s = str_replace('.прыг.', '<img src=\'smile/pryg.gif\' alt=".прыг."/>', $s);
	$s = str_replace('.репка.', '<img src=\'smile/repka.gif\' alt=".репка."/>', $s);
	$s = str_replace('.ромашка.', '<img src=\'smile/romashka.gif\' alt=".ромашка."/>', $s);
	$s = str_replace('.роза.', '<img src=\'smile/roza.gif\' alt=".роза."/>', $s);
	$s = str_replace('.русский.', '<img src=\'smile/russkij.gif\' alt=".русский."/>', $s);
	$s = str_replace('.русский1.', '<img src=\'smile/russkij1.gif\' alt=".русский1."/>', $s);
	$s = str_replace('.ржу.', '<img src=\'smile/rzhu.gif\' alt=".ржу."/>', $s);
	$s = str_replace('.секас.', '<img src=\'smile/sekas.gif\' alt=".секас."/>', $s);
	$s = str_replace('.семья.', '<img src=\'smile/semja.gif\' alt=".семья."/>', $s);
	$s = str_replace('.сиськи.', '<img src=\'smile/siski.gif\' alt=".сиськи."/>', $s);
	$s = str_replace('.смех.', '<img src=\'smile/smeh.gif\' alt=".смех."/>', $s);
	$s = str_replace('.сигарета.', '<img src=\'smile/smoke.gif\' alt=".сигарета."/>', $s);
	$s = str_replace('.солнце.', '<img src=\'smile/solnce.gif\' alt=".солнце."/>', $s);
	$s = str_replace('.спам.', '<img src=\'smile/spam.gif\' alt=".спам."/>', $s);
	$s = str_replace('.стих.', '<img src=\'smile/stih.gif\' alt=".стих."/>', $s);
	$s = str_replace('.сцуко.', '<img src=\'smile/scuko.gif\' alt=".сцуко."/>', $s);
	$s = str_replace('.свадьба.', '<img src=\'smile/svadba.gif\' alt=".свадьба."/>', $s);
	$s = str_replace('.свист.', '<img src=\'smile/svist.gif\' alt=".свист."/>', $s);
	$s = str_replace('.согласен.', '<img src=\'smile/soglasen.gif\' alt=".согласен."/>', $s);
	$s = str_replace('.танцы.', '<img src=\'smile/tancy.gif\' alt=".танцы."/>', $s);
	$s = str_replace('.тема.', '<img src=\'smile/tema.gif\' alt=".тема."/>', $s);
	$s = str_replace('.тормоз.', '<img src=\'smile/tormoz.gif\' alt=".тормоз."/>', $s);
	$s = str_replace('.туса.', '<img src=\'smile/tusa.gif\' alt=".туса."/>', $s);
	$s = str_replace('.утро.', '<img src=\'smile/utro.gif\' alt=".утро."/>', $s);
	$s = str_replace('.велик.', '<img src=\'smile/velik.gif\' alt=".велик."/>', $s);
	$s = str_replace('.велком.', '<img src=\'smile/wellcome.gif\' alt=".велком."/>', $s);
	$s = str_replace('.вестерн.', '<img src=\'smile/vestern.gif\' alt=".вестерн."/>', $s);
	$s = str_replace('.винсент.', '<img src=\'smile/vinsent.gif\' alt=".винсент."/>', $s);
	$s = str_replace('.язык.', '<img src=\'smile/yazik.gif\' alt=".язык."/>', $s);
	$s = str_replace('.зяфк.', '<img src=\'smile/zjafk.gif\' alt=".зявк."/>', $s);
	return $s;
	}

//отправка УТФ-8 почты
function mail_utf8($to, $subject = '(No subject)', $message = '', $from)
	{
	$header = "MIME-Version: 1.0"."\n"."Content-type: text/plain; charset=UTF-8"."\n"."From: ".$from."\n";
	return mail($to, "=?UTF-8?B?".base64_encode($subject)."?=", $message, $header, '-f '.$from);
	}

function link_it($s)
	{
	$s = preg_replace("/(^|[\n ])([\w]*?)((ht|f)tp(s)?:\/\/[\w]+[^ \,\"\n\r\t<]*)/is", "$1$2<a href=\"out.php?url=$3\" >$3</a>", $s);
	$s = preg_replace("/(^|[\n ])([\w]*?)((www|ftp)\.[^ \,\"\t\n\r<]*)/is", "$1$2<a href=\"out.php?url=http://$3\" >$3</a>", $s);
	$s = preg_replace("/(^|[\n ])([a-z0-9&\-_\.]+?)@([\w\-]+\.([\w\-\ .]+)+)/i", "$1<a href=\"mailto:$2@$3\">$2@$3</a>", $s);
	return($s);
	}

function DateNews()
	{
	$db = DBC::instance();
	$q = $db->query("select datenews from `news` order by id desc limit 1;");
	if ($q->num_rows == 0) $news = 'Новостей нет';
	else
		{
		$news = $q->fetch_assoc();
		$news = 'Новости ('.Date('d.m.Y H:i', $news['datenews']).')';
		}
	return $news;
	}

function get_login($lgn = '')
	{
	$db = DBC::instance();
	if (preg_match("/[^a-zA-Z0-9_а-яА-ЯёЁ]/u", $lgn)) msg('Неверно набран логин!', 1);
	$q = $db->query("select * from `users` where login='{$lgn}' limit 1;");
	if ($q->num_rows == 0) msg('Логин не найден!', 1);
	$lgn = $q->fetch_assoc();
	return $lgn;
	}

function GetDay($s)
	{
	// ф-я возвращает разницу в днях между указанной датой и текущей.
	$another = mktime(0, 0, 0, date("m", $s), date("d", $s), date("Y", $s));
	$now = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
	$diff = $now - $another;
	$diff = intval($diff / 86400);
	if ($diff < 0) $diff *= -1;
	return $diff;
	}

function klan_points($name, $i)
	{
	$db = DBC::instance();
	$q = $db->query("select * from `klans` where name='{$name}' limit 1;");
	$a = $q->fetch_assoc();
	if ($a['points'] >= $a['lvl'] * 1000)
		{
		$a['points'] = 0;
		$a['lvl'] += 1;
		}
	if ($a['points'] + $i < 0) $a['points'] = 0;
	else $a['points'] += $i;
	$q = $db->query("update `klans` set points={$a['points']},lvl={$a['lvl']} where name='{$name}' limit 1;");
	return true;
	}

function addexp($id,$num)
	{
	$db = DBC::instance();
	$q = $db->query("update `users` set exp=exp+'{$num}'+'{$num}'*pererod*0.5 where id='{$id}' limit 1;");
	return true;
}



if ($settings['hrammoney'] >= $settings['hramlvl'] * 10)
{
$settings['hrammoney'] = 0;
$settings['hramlvl'] += 1;
$q = $db->query("update `settings` set hrammoney={$settings['hrammoney']},hramlvl={$settings['hramlvl']} limit 1;");
msg2('Уровень храма повышен до '.$settings['hramlvl'].'');
}


function msg($s = '', $stop = 0)
	{
	echo '<div class="board2">'.$s.'</div>';
	echo '<div style="width:100%;height:4px;border: 0px solid #4f4f4f; border-top: 0px solid #4f4f4f; position:relative;background-color:#38654B;margin: 0px 0px 0px 0px;text-align:center;"></div>';
	if (!empty($stop))
		{
		if(!empty($_SESSION['auth']))
			{
			knopka1('javascript:history.go(-1)', 'Вернуться');
//echo'<div class="menu_j"><a href="javascript:history.go(-1)">Вернуться</a></div>';
			knopka('loc.php', 'В игру');
			}
		fin();
		}
	return true;
	}

function msg2($s = '', $stop = 0)
	{
	echo '<div class="board"><div class="board2">'.$s.'</div></div>';
	echo '<div style="width:100%;height:4px;border: 0px solid #4f4f4f; border-top: 0px solid #4f4f4f; position:relative;background-color:#38654B;margin: 0px 0px 0px 0px;text-align:center;"></div>';
	if (!empty($stop))
		{
		if(!empty($_SESSION['auth']))
			{

			knopka1('javascript:history.go(-1)', 'Вернуться');
//echo'<div class="menu_j"><a href="javascript:history.go(-1)">Вернуться</a></div>';
			knopka('loc.php', 'В игру');
			}
		fin();
		}
	return true;
	}

function msg3($s = '', $stop = 0)
	{
	echo '<div class="board">'.$s.'</div>';
	echo '<div style="width:100%;height:4px;border: 0px solid #4f4f4f; border-top: 0px solid #4f4f4f; position:relative;background-color:#38654B;margin: 0px 0px 0px 0px;text-align:center;"></div>';
	if (!empty($stop))
		{
		if(!empty($_SESSION['auth']))
			{
knopka1('javascript:history.go(-1)', 'Вернуться');
//echo'<div class="menu_j"><a href="javascript:history.go(-1)">Вернуться</a></div>';
			knopka('loc.php', 'В игру');
			}
		fin();
		}
	return true;
	}

function knopka($url, $name = '', $kart = 0)
	{ // кнопка
	$random = mt_rand(1, 99999);
	if (empty($url)) $url = 'http://'.$_SERVER['SERVER_NAME'];
	if (empty($name)) $name = $url;
if(preg_match("/[javascript]/u", $url)) {
	echo '<div class="menu_j"><a href="'.$url.'" class="top_menu_j">';
	if (!empty($kart)) echo '<img src="pic/k.png" alt=""/> ';
	echo $name.'</a></div>';
}
elseif(preg_match("/[?]/u", $url)) {
	echo '<div class="menu_j"><a href="'.$url.'&r='.$random.'" class="top_menu_j">';
	if (!empty($kart)) echo '<img src="pic/k.png" alt=""/> ';
	echo $name.'</a></div>';
}
else{
	echo '<div class="menu_j"><a href="'.$url.'?r='.$random.'" class="top_menu_j">';
	if (!empty($kart)) echo '<img src="pic/k.png" alt=""/> ';
	echo $name.'</a></div>';
}
	return true;
}


function knopka1($url, $name = '', $kart = 0)
	{ // кнопка
//	$random = mt_rand(1, 99999);
	if (empty($url)) $url = 'http://'.$_SERVER['SERVER_NAME'];
	if (empty($name)) $name = $url;

	echo '<div class="menu_j"><a href="'.$url.'" class="top_menu_j">';
	if (!empty($kart)) echo '<img src="pic/k.png" alt=""/> ';
	echo $name.'</a></div>';

	return true;
}



function knopka2($url, $name)
{
$random = mt_rand(1, 99999);
	if (empty($url)) $url = 'http://'.$_SERVER['SERVER_NAME'];
if (empty($name)) $name = $url;
if(preg_match("/[javascript]/u", $url)) {
echo '<a class="main-knopki" href="'.$url.'">'.$name.'</a>';
}
elseif(preg_match("/[?]/u", $url)) {
echo '<a class="main-knopki" href="'.$url.'&r='.$random.'">'.$name.'</a>';
}
else {
echo '<a class="main-knopki" href="'.$url.'?r='.$random.'">'.$name.'</a>';
}

	return true;
	}

function fin($s = '')
	{
$random = mt_rand(1, 999);
	global $time_start,$version;
	if (!empty($s)) echo $s.'<br/></div>';
if(!empty($_SESSION['auth'])) echo '<div class="board" style="text-align:left"><a href="loc.php?r='.$random.'" accesskey="l">Локация</a><br/>
<a href="anketa.php?r='.$random.'" accesskey="p">Профиль</a> | <a href="menu.php?r='.$random.'" accesskey="m">Меню</a> | <a href="lib.php?r='.$random.'">Помощь</a> | <a href="tiket.php?r='.$random.'">Тех. поддержка</a> | <a href="http://vk.com/valdirra">Группа в VK</a></div>';

	$time_end = microtime(true);
	$alltime = round($time_end - $time_start, 5);
	echo '<div class="head" align="center">';
	echo $alltime.' сек';
	echo '</div>';
	echo '<center><small>';
	echo '<font color="white">&copy; 2015';
	if(date('Y') > 2015) echo ' - '.date('Y');


	echo ' "<a href="about.php?mod=avt">Вальдирра</a>"</font>';


	echo '</small>';
if(!empty($_SESSION['auth'])) {
/*
	if(empty($_SESSION['mobtop']) or $_SESSION['mobtop'] < $_SERVER['REQUEST_TIME'])
		{
		echo '<br/><a href="http://waplog.net/c.shtml?588828"><img src="http://c.waplog.net/588828.cnt" alt="waplog" /></a> | <script type="text/javascript" src="http://mobtop.ru/c/102045.js"></script><noscript><a href="http://mobtop.ru/in/102045"><img src="http://mobtop.ru/102045.gif" alt="MobTop.Ru - Рейтинг и статистика мобильных сайтов"/></a></noscript>';
		$_SESSION['mobtop'] = $_SERVER['REQUEST_TIME'] += mt_rand(200,600);
		}
*/
}
	echo '</center></body></html>';
	exit();
	}
function hr($s = '')
{
echo '<div class="r">'.$s.'</div>';
return true;
}
?>
