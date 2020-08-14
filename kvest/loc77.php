<?php
##############
# 24.12.2014 #
##############

if ($f['hpnow'] <= 0)
	{
	knopka('loc.php', 'Восстановите здоровье', 1);
	fin();
	}

msg2('<b>Старик в робе мага</b>');
$q = $db->query("select count(*) from `invent` where ido=158 AND login='{$f['login']}' and flag_rinok=0 and flag_sklad=0;");
$runl = $q->fetch_assoc();
$runl = $items->count_base_item($f['login'], 158);
$runo = $items->count_base_item($f['login'], 159);
$runw = $items->count_base_item($f['login'], 160);
$runz = $items->count_base_item($f['login'], 161);
$runv = $items->count_base_item($f['login'], 162);

$kvest = unserialize($f['kvest']);
if (empty($kvest['loc77st']))
	{
	$kvest['loc77st']['date'] = 0;
	$f['kvest'] = serialize($kvest);
	$q = $db->query("update `users` set kvest='{$f['kvest']}' where id={$f['id']} limit 1;");
	}

if (date('d.m.Y', $kvest['loc77st']['date']) == date('d.m.Y')) msg2('Старик увлеченно рассматривает руны, ему явно не до вас...', 1);

if (empty($mod))
	{
	$str = 'Здравствуй, '.$f['login'].'! Я - начинающий маг, моё имя тебе ни о чем не скажет, перейдем сразу к делу.<br/>';
	$str .= 'Мне не повезло - вещи, и, самое главное, все мои магические руны оказались утеряны - я попал в довольно серьезную заварушку. Эти твари оставили мне лишь монеты - их они совершенно не заинтересовали.<br/>';
	$str .= 'Если ты принесешь мне все 5 моих магических рун, я тебя щедро вознагражу. Ну, что скажешь?';
	msg2($str);
	if (0 < $runl && 0 < $runo && 0 < $runw && 0 < $runz && 0 < $runv) knopka('kvest.php?mod=ok2', 'Вот твои руны', 1);
	else knopka('kvest.php?mod=ok', 'Хорошо, мне по душе твое задание', 1);
	knopka('loc.php', 'Отказаться', 1);
	fin();
	}
elseif ($mod == 'ok')
	{
	$str = 'Я даже не видел, в какую сторону скрылись эти грязные твари, похоже, они оглушили меня. Было уже поздно что-либо делать, когда я пришел в себя...<br/>';
	$str .= 'Я рад, что ты мне поможешь, без этих рун я могу заниматься магией только в своей башне.<br/><br/>';
	$str .= 'Вы понимаете, что старикан несет чушь (сильно же его по голове стукнули) и решаете';
	msg2($str);
	knopka('loc.php', 'Пойти на поиски рун', 1);
	fin();
	}
elseif ($mod == 'ok2')
	{
	if (0 < $runl && 0 < $runo && 0 < $runw && 0 < $runz && 0 < $runv)
		{
		$items->del_base_item($f['login'], 158, 1);
		$items->del_base_item($f['login'], 159, 1);
		$items->del_base_item($f['login'], 160, 1);
		$items->del_base_item($f['login'], 161, 1);
		$items->del_base_item($f['login'], 162, 1);
		$nagrada = mt_rand(50 * $f['lvl'], 80 * $f['lvl']);
		$f['cu'] += $nagrada;
		$kvest = unserialize($f['kvest']);
		unset($kvest['loc77st']);
		$kvest['loc77st']['date'] = $_SERVER['REQUEST_TIME'];
		$f['kvest'] = serialize($kvest);
		$q = $db->query("update `users` set slava=slava+1,cu={$f['cu']},kvest='{$f['kvest']}' where id={$f['id']} limit 1;");
		$str = 'Вы отдаете руны старику, он жадно вчитывается в надписи на камнях.<br/>';
		$str .= '- Спасибо тебе, '.$f['login'].', это действительно мои руны!<br/>';
		$str .= '[Получено '.$nagrada.' медных монет]';
		msg2($str);
		knopka('loc.php', 'В игру', 1);
		fin();
		}
	else
		{
		msg2('Ты меня обманываешь, я просил принести 5 разных рун, у тебя явно что-то не то.', 1);
		}
	}
?>
