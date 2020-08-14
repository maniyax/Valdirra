<?php
##############
# 24.12.2014 #
##############
require_once('inc/top.php');		//вывод на экран
require_once('inc/check.php');		//вход в игру
require_once('inc/head.php');
require_once('class/items.php');
require_once('inc/boi.php');		//создание боя + завод игрока в бой
require_once('inc/bot.php');		//параметры бота + создание бота

$go = isset($_REQUEST['go']) ? intval($_REQUEST['go']) : 0;

if($f['status'] == 1)
	{
	knopka('battle.php', 'Вы в бою!', 1);
	fin();
	}
if($f['status'] == 2)
	{
	knopka('arena.php', 'У вас заявка на арене!', 1);
	fin();
	}
if($f['hpnow'] <= 0)
	{
	knopka('loc.php', 'Восстановите здоровье', 1);
	fin();
	}
if($f['rabota'] > $t)
	{
	knopka('loc.php', 'Вы работаете!', 1);
	fin();
	}

switch($f['loc']):
case 1:
	if($f['lvl'] > 3) msg2('Вы уже опытный воин, тренеры вам не нужны', 1);
	$boi_id = addBoi(0);
	if($f['lvl'] == 1) addBot('Младший тренер', $f['lvl']);
	elseif($f['lvl'] == 2) addBot('Тренер', $f['lvl']);
	elseif($f['lvl'] == 3) addBot('Старший тренер', $f['lvl']);
	toBoi($f,2);
break;

case 5:
	if($f['lvl'] < 4)
		{
		knopka('loc.php', 'Доступно с 4 уровня', 1);
		fin();
		}
	$boi_id = addBoi(0);						//создаем бой и получаем его ИД
	addBot('Падальщик',$f['lvl']);			//Создаем бота Падальщик, лвл +1 к нашему
	addBot('Молодой падальщик',$f['lvl']-1);		//Создаем бота Молодой падальщик, лвл = наш лвл
	toBoi($f,2);								//сами заходим в бой (наша рабочая переменная ($f),команда)
break;

case 8:
	if($f['lvl'] < 4)
		{
		knopka('loc.php', 'Доступно с 4 уровня', 1);
		fin();
		}
	$boi_id = addBoi(0);
	addBot('Кротокрыс',$f['lvl']);
	addBot('Молодой кротокрыс',$f['lvl']-1);
	toBoi($f,2);
break;

case 2:
	if($f['lvl'] > 3)
		{
		knopka('loc.php', 'Доступно до 3 уровня', 1);
		fin();
		}

	$boi_id = addBoi(0);
	addBot('Ворюга',$f['lvl']);
	toBoi($f,2);
break;

case 9:
	$boi_id = addBoi(0);
	addBot('Мясной жук',$f['lvl']);
	toBoi($f,2);
break;

case 11:
	if($f['lvl'] < 4)
		{
		knopka('loc.php', 'Доступно с 4 уровня', 1);
		fin();
		}
	$boi_id = addBoi(0);
	addBot('Стервятник',$f['lvl']);
	addBot('Молодой стервятник',$f['lvl']-1);
	toBoi($f,2);
break;

case 13:
	if($f['lvl'] < 4)
		{
		knopka('loc.php', 'Доступно с 4 уровня', 1);
		fin();
		}
	$boi_id = addBoi(1);
	addBot('Кровосос',$f['lvl']);
	addBot('Шершень',$f['lvl']-1);
	toBoi($f,2);
break;

case 14:
	if($f['lvl'] < 4)
		{
		knopka('loc.php', 'Доступно с 4 уровня', 1);
		fin();
		}
	$boi_id = addBoi(0);
	addBot('Волк',$f['lvl']+2);
	addBot('Волчица',$f['lvl']);
	toBoi($f,2);
break;

case 20:
	if($f['lvl'] < 3)
		{
		knopka('loc.php', 'Доступно с 3 уровня', 1);
		fin();
		}
	$boi_id = addBoi(1);
	addBot('Остер',$f['lvl']+1);
	toBoi($f,2);
break;

case 21:
	if($f['lvl'] < 4)
		{
		knopka('loc.php', 'Доступно с 4 уровня', 1);
		fin();
		}
	$boi_id = addBoi(1);
	addBot('Черный гоблин',$f['lvl']);
	addBot('Гоблин',$f['lvl']-1);
	toBoi($f,2);
break;

case 22:
	if($f['lvl'] < 10)
		{
		knopka('loc.php', 'Доступно с 10 уровня', 1);
		fin();
		}
	if(date('H') != 19  or date('i') > 29) msg2('Зайти в бой могут только 25 человек, и только с 19:00 и до 19:30',1);
	$num = 0;	// количество чел в бою с ботом
	$bot_name = 'Тролль';
	$q = $db->query("select boi_id from `combat` where login='{$bot_name}' limit 1;");
	$bz = $q->fetch_assoc();
	$boi_id = $bz['boi_id'];
	if(isset($bz['boi_id']) and $bz['boi_id'] == 0) $q = $db->query("delete from `combat` where login='{$bot_name}' limit 1;");
	if(empty($boi_id))
		{
		$q = $db->query("update `users` set doping=0,doping_time=0 where id={$f['id']} limit 1;");
		$f = calcparam($f);
		$boi_id = addBoi(2);
		addBot($bot_name,25);
		toBoi($f,2);
		}
	else
		{
		$q = $db->query("select count(*) from `users` where boi_id='{$boi_id}';");
		$a = $q->fetch_assoc();
		$num = $a['count(*)'];
		if($num < 25)
			{
			$q = $db->query("update `users` set doping=0,doping_time=0 where id={$f['id']} limit 1;");
			$f = calcparam($f);
			toBoi($f,2);
			}
		else
			{
			msg2('Тролля окружили уже '.$num.' бойцов, вы не можете к нему протиснуться!',1);
			}
		}
break;

case 27:
	if($f['lvl'] < 4)
		{
		knopka('loc.php', 'Доступно с 4 уровня', 1);
		fin();
		}
	$boi_id = addBoi(0);
	addBot('Глорх',$f['lvl']);
	addBot('Кусач',$f['lvl']-1);
	toBoi($f,2);
break;

case 39:
	if($f['lvl'] < 3)
		{
		knopka('loc.php', 'Доступно с 3 уровня', 1);
		fin();
		}
	$boi_id = addBoi(1);
	addBot('Шмыг',$f['lvl']);
	toBoi($f,2);
break;

case 41:
	if($f['lvl'] < 4)
		{
		knopka('loc.php', 'Доступно с 4 уровня', 1);
		fin();
		}
	$boi_id = addBoi(1);
	addBot('Оборотень',$f['lvl']+1);
	addBot('Упырь',$f['lvl']);
	toBoi($f,2);
break;

case 43:
	$kvest = unserialize($f['kvest']);
	$kv = $kvest['loc56ks'];

	if($kv['nagrada'] == 1)
		{
		knopka('loc.php', 'Ошибка локации', 1);
		fin();
		}
	if($kv['lg'] == 1) msg2('Сердце ледяного голема уже у вас!',1);
	//ледяной голем
	$boi_id = addBoi(1);
	addBot('Ледяной голем',$f['lvl']+1);
	toBoi($f,2);
break;

case 49:
	if($f['lvl'] < 4)
		{
		knopka('loc.php', 'Доступно с 4 уровня', 1);
		fin();
		}
	$boi_id = addBoi(0);
	addBot('Орк-Маг',$f['lvl']+1);
	addBot('Орк-Шаман',$f['lvl']);
	toBoi($f,2);
break;

case 55:
	$kvest = unserialize($f['kvest']);
	$kv = $kvest['loc56ks'];

	if($kv['nagrada'] == 1)
		{
		knopka('loc.php', 'Ошибка локации', 1);
		fin();
		}
	if($kv['og'] == 1) msg2('Сердце огненного голема уже у вас!',1);
	//огненный голем
	$boi_id = addBoi(1);
	addBot('Огненный голем',$f['lvl']+1);
	toBoi($f,2);
break;

case 62:
	$kvest = unserialize($f['kvest']);
	$kv = $kvest['loc56ks'];

	if($kv['nagrada'] == 1)
		{
		knopka('loc.php', 'Ошибка локации', 1);
		fin();
		}
	if($kv['kg'] == 1) msg2('Сердце каменного голема уже у вас!',1);
	//каменный голем
	$boi_id = addBoi(1);
	addBot('Каменный голем',$f['lvl']+1);
	toBoi($f,2);
break;

case 67:
	$boi_id = addBoi(0);
	addBot('Гарпия',$f['lvl']);
	toBoi($f,2);
break;

case 73:
	if($f['lvl'] < 4)
		{
		knopka('loc.php', 'Доступно с 4 уровня', 1);
		fin();
		}
	$boi_id = addBoi(0);
	addBot('Орк-Воин',$f['lvl']+1);
	addBot('Орк',$f['lvl']);
	toBoi($f,2);
break;

case 89:
	if($f['lvl'] < 3)
		{
		knopka('loc.php', 'Доступно с 3 уровня', 1);
		fin();
		}
	$boi_id = addBoi(1);
	addBot('Болотожор',$f['lvl']);
	toBoi($f,2);
break;

case 92:
	if($f['lvl'] < 6)
		{
		knopka('loc.php', 'Доступно с 6 уровня', 1);
		fin();
		}
	$boi_id = addBoi(2);
	addBot('Огненная ящерица',$f['lvl']+3);
	addBot('Ящерица огня',$f['lvl']+2);
	toBoi($f,2);
break;

case 95:
	if($f['lvl'] < 3)
		{
		knopka('loc.php', 'Доступно с 3 уровня', 1);
		fin();
		}
	$boi_id = addBoi(1);
	if(mt_rand(1, 100) <= 50) addBot('Скелет',$f['lvl']+1);
	else addBot('Зомби',$f['lvl']);
	toBoi($f,2);
break;

case 100:
	$boi_id = addBoi(0);
	addBot('Ползун',$f['lvl']);
	toBoi($f,2);
break;
/*
case 149:
//if(date('H') != 16 or date('i') > 59) msg2('Зайти в бой могут только 50 человек, и только с 16:00 и до 16:59',1);
//if($f['admin'] < 3)msg2('Тут никого нет',1);
	$num = 50;	// количество чел в бою с ботом
	$bot_name = 'Гринч Вальдирры';
	$q = $db->query("select boi_id from `combat` where login='{$bot_name}' limit 1;");
	$bz = $q->fetch_assoc();
	$boi_id = $bz['boi_id'];
	if(isset($bz['boi_id']) and $bz['boi_id'] == 0) $q = $db->query("delete from `combat` where login='{$bot_name}' limit 1;");
	if(empty($boi_id))
		{
		$q = $db->query("update `users` set doping=0,doping_time=0 where id={$f['id']} limit 1;");
		$f = calcparam($f);
		$boi_id = addBoi(3);
		addBot($bot_name,100);

		toBoi($f,2);
		}
	else
		{
		$q = $db->query("select count(*) from `users` where boi_id='{$boi_id}';");
		$a = $q->fetch_assoc();
		$num = $a['count(*)'];
		if($num < 50)
			{
			$q = $db->query("update `users` set doping=0,doping_time=0 where id={$f['id']} limit 1;");
			$f = calcparam($f);
			toBoi($f,2);
			}
		else
			{
			msg2('Гринча окружили уже '.$num.' бойцов, вы не можете к нему протиснуться!',1);
			}
		}
break;
*/
case 197:
	$boi_id = addBoi(0);
	addBot('Кабан',$f['lvl']+1);
	
	toBoi($f,2);
break;



case 103:
	if($f['lvl'] < 6)
		{
		knopka('loc.php', 'Доступно с 6 уровня', 1);
		fin();
		}
	$boi_id = addBoi(0);
	addBot('Жук-шелкопряд',$f['lvl']);
	toBoi($f,2);
break;

case 168:
	if($f['lvl'] < 4)
		{
		knopka('loc.php', 'Доступно с 4 уровня', 1);
		fin();
		}

	$boi_id = addBoi(0);
	addBot('Разбойник',$f['lvl']-1);
	toBoi($f,2);
break;


case 151:
	if($f['lvl'] < 5)
		{
		knopka('loc.php', 'Доступно с 5 уровня', 1);
		fin();
		}
if(date('H') != 17 or date('i') > 59) msg2('Зайти в бой могут только 50 человек, и только с 17:00 и до 17:59',1);
//if($f['admin'] < 4)msg2('Тут никого нет',1);
	$num = 0;	// количество чел в бою с ботом
	$bot_name = 'черный дракон';
	$q = $db->query("select boi_id from `combat` where login='{$bot_name}' limit 1;");
	$bz = $q->fetch_assoc();
	$boi_id = $bz['boi_id'];
	if(isset($bz['boi_id']) and $bz['boi_id'] == 0) $q = $db->query("delete from `combat` where login='{$bot_name}' limit 1;");
	if(empty($boi_id))
		{
//		$q = $db->query("update `users` set doping=0,doping_time=0 where id={$f['id']} limit 1;");
		$f = calcparam($f);
		$boi_id = addBoi(3);
		addBot($bot_name,30);

		toBoi($f,2);
		}
	else
		{
		$q = $db->query("select count(*) from `users` where boi_id='{$boi_id}';");
		$a = $q->fetch_assoc();
		$num = $a['count(*)'];
		if($num < 50)
			{
//			$q = $db->query("update `users` set doping=0,doping_time=0 where id={$f['id']} limit 1;");
			$f = calcparam($f);
			toBoi($f,2);
			}
		else
			{
			msg2('черного дракона окружили уже '.$num.' бойцов, вы не можете к нему протиснуться!',1);
			}
		}
break;

case 185:
	if($f['lvl'] < 10)
		{
		knopka('loc.php', 'Доступно с 10 уровня', 1);
		fin();
		}
	$boi_id = addBoi(0);
	addBot('Энд',$f['lvl']-2);
	addBot('Энд',$f['lvl']-1);
	if(mt_rand(1, 100) <= 40) addBot('Старый Энд',$f['lvl']+3);
	else addBot('Энд',$f['lvl']-1);
	toBoi($f,2);
break;

case 200:
	if($f['lvl'] < 15)
		{
		knopka('loc.php', 'Доступно с 15 уровня', 1);
		fin();
		}
	$boi_id = addBoi(0);
	if(mt_rand(1, 100) <= 50) addBot('Злобный бес',$f['lvl']+1);
	else addBot('Злобный бес',$f['lvl']);
	toBoi($f,2);
break;

case 145:
	$boi_id = addBoi(0);
	addBot('Злой снеговик',$f['lvl']+5);
	
	toBoi($f,2);
break;

case 189:
	$boi_id = addBoi(0);
	addBot('Медведь',$f['lvl']+3);
	
	toBoi($f,2);
break;

case 24:
if($f['lvl'] <2){
msg2('Доступно с 2 уровня',1);
fin();
}
	$boi_id = addBoi(0);
	addBot('Кроль',$f['lvl']);
	toBoi($f,2);
break;

case 80:
if($f['lvl'] <4){
msg2('Доступно с 4 уровня',1);
fin();
}
	$boi_id = addBoi(0);
	addBot('Бродяга',$f['lvl']);
	toBoi($f,2);
break;


case 42:
if($f['lvl'] <8){
msg2('Доступно с 8 уровня',1);
fin();
}
	$boi_id = addBoi(0);
	addBot('Сбежавший рудокоп',$f['lvl']+1);
	toBoi($f,2);
break;


case 143:
	$boi_id = addBoi(0);
	addBot('Белая ворона',$f['lvl']-2);
	toBoi($f,2);
break;

default:
	knopka('loc.php', 'Ошибка локации', 1);
	fin();
endswitch;

header('location: battle.php');
//knopka('battle.php', 'В бой', 1);
fin();
?>
