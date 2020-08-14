<?php
###########################
# 04.05.2015 StalkerSleem #
###########################

if ($f['hpnow'] <= 0){knopka('loc.php', 'Восстановите здоровье', 1);fin();}

$kvest = unserialize($f['kvest']);
if (empty($kvest['loc147'])){$kvest['loc147']['date'] = 0;
$f['kvest'] = serialize($kvest);
$q = $db->query("update `users` set kvest='{$f['kvest']}' where id='{$f['id']}' limit 1;");}

$time = $kvest['loc147']['date'] - $_SERVER['REQUEST_TIME'];
if ($time > 0){
    $slova = array();
	$slova[] = 'Поручений нет';
	$slova[] = 'Нечего тут стоять без дела...';
	$slova[] = 'Во имя Вальдирры, не беспокой меня!';
	$slova[] = 'Спасибо за услугу, ценю твою помощь!';
	$slova[] = 'Гр!<br/> Недовольно отвернулся в сторону';
	$slova[] = 'Когда то я тоже был искателем приключений, но стрела в колено...';
	shuffle($slova);
	msg2('- '.$slova[0].'', 1);fin();
	}

msg2('<b>Стражник</b>');

if (empty($go)){
    $slova = array();	$slova[] = 'Стой, не спеши';
	$slova[] = 'Послушай';
	$slova[] = 'Хей! Гр';
	shuffle($slova);
	msg2('- '.$slova[0].'! Не окажешь доблестную услугу для городской стражи?<br>
    - Участились нападения на путников от разбойников. Проучи негодяев! И принеси их оружие, в знак доказательства. В долгу стража не останется.');
	knopka('kvest.php?go=1', '- Услуга выполнена, вот доказательства', 1);
	knopka('loc.php', '- Сейчас разберусь', 1);fin();}

if ($items->count_base_item($f['login'], 719) < 2){
    $slova = array();
	$slova[] = 'Нужно два обломка меча, всего лишь два. Два обломка. Два...';
	$slova[] = 'Не вижу доказательств... Нужно два обломка меча';
	$slova[] = 'Два обломка меча и я тебя вознагражу';
	$slova[] = 'Во имя Вальдирры! Гр!<br/> - Принеси два обломка меча и не нервируй меня больше...';
	shuffle($slova);
	msg2('- '.$slova[0].'', 1);}

$items->del_base_item($f['login'], 719, 2);
$exp = mt_rand($f['lvl'] * 10, $f['lvl'] * 20);
addexp($f['id'], $exp);
$nagr = mt_rand(5, 8);
$f['ag'] += $nagr;
$kvest = unserialize($f['kvest']);
		$kvest['loc147']['date'] = $_SERVER['REQUEST_TIME'] + 86400;
		$f['kvest'] = serialize($kvest);

$points = 1;

$q = $db->query("update `users` set slava=slava+3,ag={$f['ag']},chest=chest+{$points},kvest='{$f['kvest']}' where id='{$f['id']}' limit 1;");

$str = '- Очень благодарен. Это достойно чести и уважения!<br>';
$str .= '- Держи, прими не большую награду.<br>';
$str .= '[Получено '.$exp.' опыта и '.$nagr.' серебряных монет.]<br/>';
$str .= '<span style="color:yellow;">[Получено '.$points.' очко чести]</span>';
msg2($str);
knopka('loc.php', '- Пока', 1);
fin();
?>