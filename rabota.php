<?php
##############
# 24.12.2014 #
##############
require_once('inc/top.php');	// вывод на экран
require_once('inc/check.php');	// вход в игру
require_once('inc/head.php');
require_once('class/items.php');	// вход в игру
if($f['status'] == 1)
	{
	knopka('battle.php', 'Вы в бою!', 1);
	fin();
	}

// для обхода register globalls
$ok = isset($_REQUEST['ok']) ? $_REQUEST['ok'] : '';
$keystring = isset($_REQUEST['keystring']) ? $_REQUEST['keystring'] : '';
$c1 = isset($_REQUEST['c1']) ? $_REQUEST['c1'] : '';
$c2 = isset($_REQUEST['c2']) ? $_REQUEST['c2'] : '';
$c1 = md5($c1);

// обновим в игре
$q = $db->query("update `users` set lastdate='{$t}' where id='{$f['id']}' limit 1;");

// шапка
require_once('inc/hpstring.php');
echo '<div class="board">';
if($f['loc'] == 19)
{
//$f['lvl'] *= 4;
//$count = $f['lvl'];
	if($f['rabota'] > 0)
		{
		if($f['rabota'] > $_SERVER['REQUEST_TIME'])
			{
			$ost = $f['rabota'] - $_SERVER['REQUEST_TIME'];
			msg('Вам осталось отработать '.ceil($ost / 60).' мин.', 1);
			}
		else
            {
            if($f['p_rudokop'] > 0 and $f['p_rudokop'] <=25) $ruda = mt_rand(728, 729);
            if($f['p_rudokop'] > 25 and $f['p_rudokop'] <=50) $ruda = mt_rand(726, 729);
            if($f['p_rudokop'] > 50 and $f['p_rudokop'] <=100) $ruda = mt_rand(726, 730);
			if($f['p_rudokop'] > 100 and $f['p_rudokop'] <=250) $ruda = mt_rand(726, 731);
			if($f['p_rudokop'] > 250 and $f['p_rudokop'] <= 500) $ruda = mt_rand(726, 732);
			if($f['p_rudokop'] > 500 and $f['p_rudokop'] <= 1000) $ruda = mt_rand(726, 733);
			if($f['p_rudokop'] > 1000) $ruda = mt_rand(726, 734);

			$rnd = mt_rand(1, 100);
			$dob1 = $items->base_shmot($ruda);			if ($rnd >= 1 and $rnd <= 3) $items->add_item($f['login'], $ruda, 1);   // 15 руды
		    if ($rnd >= 1 and $rnd <= 8) $items->add_item($f['login'], $ruda, 1);  // 14 руды
			if ($rnd >= 1 and $rnd <= 15) $items->add_item($f['login'], $ruda, 1);  // 13 руды
			if ($rnd >= 1 and $rnd <= 25) $items->add_item($f['login'], $ruda, 1);  // 12 руды
			if ($rnd >= 1 and $rnd <= 37) $items->add_item($f['login'], $ruda, 1);  // 11 руды
			if ($rnd >= 1 and $rnd <= 50) $items->add_item($f['login'], $ruda, 1);  // 10 руды
			if ($rnd >= 1 and $rnd <= 58) $items->add_item($f['login'], $ruda, 1);  // 9 руды
			if ($rnd >= 1 and $rnd <= 62) $items->add_item($f['login'], $ruda, 1);  // 8 руды
            if ($rnd >= 1 and $rnd <= 66) $items->add_item($f['login'], $ruda, 1);  // 7 руды
			if ($rnd >= 1 and $rnd <= 70) $items->add_item($f['login'], $ruda, 1);  // 6 руды
            if ($rnd >= 1 and $rnd <= 74) $items->add_item($f['login'], $ruda, 1);  // 5 руды
			if ($rnd >= 1 and $rnd <= 80) $items->add_item($f['login'], $ruda, 1);  // 4 руды
			if ($rnd >= 1 and $rnd <= 85) $items->add_item($f['login'], $ruda, 1);  // 3 руды
            if ($rnd >= 1 and $rnd <= 91) $items->add_item($f['login'], $ruda, 1);  // 2 руды
			if ($rnd >= 1 and $rnd <= 100) $items->add_item($f['login'], $ruda, 1);  // 1 руды

            if($f['p_rudokop'] > 100 and mt_rand(1, 100) <= 25) $ido2=$items->add_item($f['login'], mt_rand(735, 737));
			if($f['p_rudokop'] > 500 and mt_rand(1, 100) <= 15) $ido2=$items->add_item($f['login'], mt_rand(738, 740));
			if($f['p_rudokop'] > 1000 and mt_rand(1, 100) <= 5) $ido2=$items->add_item($f['login'], 741);
			if($f['p_rudokop'] > 1000 and mt_rand(1, 100) <= 50) $ido2=$items->add_item($f['login'], 780);
			echo''.$dob1['name'].'! Труды не напрасны.<br/>';
if($f['rasa'] ==2){            $q = $db->query("update `users` set p_rudokop=p_rudokop+2,kirka=kirka-1,rabota=0,j=j+6,g=g+4 where id='{$f['id']}' limit 1;");}
else{            $q = $db->query("update `users` set p_rudokop=p_rudokop+1,kirka=kirka-1,rabota=0,j=j+6,g=g+4 where id='{$f['id']}' limit 1;");}
			echo'Вы отработали 30 минут.';
			echo '</div>';
			knopka('rabota.php', 'Работать еще', 1);
			fin();
			}
		}
	else
{
if($f['p_rudokop'] == 0){msg2('Вы не рудокоп',1);fin();}
if($items->count_base_item($f['login'], 759) == 0) {msg2('У вас нет кирки',1);fin();}
		if(empty($ok))
			{
			echo '<form action="rabota.php?ok=1" method="POST">';
			echo 'Вы видите, как несколько измученных рудокопов молотят кирками по рудной жиле. Вы решаете:<br/>';
			require_once "class/captcha.php";
			$ca = new captcha();
			$ca->show();
			echo '<input type="submit" value="Присоединиться" /></form>';
			fin();
			}
		if(!isset($c2) or $c1 != $c2)
			{
			msg('Вы ввели неправильный ответ!',1);
			}
		$rabota = $_SERVER['REQUEST_TIME'] + 1800;
		$q = $db->query("update `users` set rabota={$rabota} where id={$f['id']} limit 1;");
		msg('Вы присоединяетесь к рудокопам. Работать вам придется 30 минут, в это время перемещаться по миру нельзя, а браузер можно закрыть.',1);
		}
	}
elseif($f['loc'] == 44)
	{
//	if($f['lvl'] < 10) msg2('Вы недостаточно сильны, приходите когда будете хотябы 10 уровня', 1);
	$nagrada = $f['lvl']*mt_rand(3, 6);
	if($nagrada < 10) $nagrada = 10;
	if($f['vip'] > $_SERVER['REQUEST_TIME'])
		{
		$nagrada = $f['lvl']*mt_rand(6, 10);
		}
	if($f['rabota'] > 0)
		{
		if($f['rabota'] > $_SERVER['REQUEST_TIME'])
			{
			$ost = $f['rabota'] - $_SERVER['REQUEST_TIME'];
			msg('Вам осталось отработать '.ceil($ost / 60).' мин.',1);
			}
		else
			{
			$f['cu'] += $nagrada;
			$nalog = ceil($nagrada * 0.1);
			$f['nalog'] += $nalog;
			$q = $db->query("update `users` set cu='{$f['cu']}',nalog={$f['nalog']}, rabota=0  where id='{$f['id']}' limit 1;");
			$q = $db->query("update `users` set cu={$f['cu']}, rabota=0 where id='{$f['id']}' limit 1;");
			msg('Получено монет: '.$nagrada);
			echo '</div>';
			knopka('rabota.php', 'Работать еще', 1);
			fin();
			}
		}
	else
		{
		if(empty($ok))
			{
			echo '<form action="rabota.php?ok=1" method="POST">';
			echo 'Вы хотите наняться охранять шахту.<br>';
			require_once "class/captcha.php";
			$ca = new captcha();
			$ca->show();

			echo '<input type="submit" value="Присоединиться" /></form>';
			fin();
			}
		if(!isset($c2) or $c1 != $c2)
			{
			msg('Вы ввели неверный код с картинки!',1);
			}
		$rabota = $_SERVER['REQUEST_TIME'] + 1800;
		$q = $db->query("update `users` set rabota='{$rabota}' where id='{$f['id']}' limit 1;");
		msg('Вы присоединились к надзирателям, в вашу задачу входит обеспечивать порядок на шахте в течении 30 минут. Браузер можно закрыть, перемещаться по миру нельзя.',1);
		}
	}
elseif($f['loc'] == 8 and !empty($f['klan']))
	{
	$nagrada = rand(ceil($f['lvl']/2),$f['lvl']);
	if($f['rabota'] > 0)
		{
		if($f['rabota'] > $_SERVER['REQUEST_TIME'])
			{
			$ost = $f['rabota'] - $_SERVER['REQUEST_TIME'];
			msg('Вам осталось отработать '.ceil($ost / 60).' мин.', 1);
			}
		else
			{
			$money = $nagrada * mt_rand(10,15);
			echo 'Вы добыли '.$nagrada.' камней для своего клана<br/>Казна получает '.$money.' монет<br/>';
			$log = $f['login'].' ['.$f['lvl'].'] добывает для клана '.$nagrada.' камней и '.$money.' монет.';
			$q = $db->query("insert into `klan_log` values(0,'{$f['login']}','{$log}','{$f['klan']}','{$t}');");
			$q = $db->query("update `klans` set kamni=kamni+'{$nagrada}',kazna=kazna+'{$money}' where name='{$f['klan']}' limit 1;");
			$q = $db->query("update `users` set rabota=0 where id={$f['id']} limit 1;");
			echo '</div>';
			knopka('rabota.php', 'Добывать еще', 1);
			fin();
			}
		}
	else
		{
		if(empty($ok))
			{
			echo '<form action="rabota.php?ok=1" method="POST">';
			echo 'В этой заброшенной шахте до сих пор попадаются камни для строительства<br/>';
			require_once "class/captcha.php";
			$ca = new captcha();
			$ca->show();

			echo '<input type="submit" value="Добывать камни" /></form>';
			fin();
			}
		if(!isset($c2) or $c1 != $c2)
			{
			msg('Вы ввели неверный код с картинки!',1);
			}
		$rabota = $_SERVER['REQUEST_TIME'] + 600;
		$q = $db->query("update `users` set rabota={$rabota} where id={$f['id']} limit 1;");
		msg('Добывать камни вам придется 10 минут, в это время перемещаться по миру нельзя, а браузер можно закрыть.',1);
		}
	}
else
	{
	msg('<a href="loc.php">Ошибка локации</a>',1);
	}
?>
