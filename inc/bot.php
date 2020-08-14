<?php
##############
# 24.12.2014 #
##############

switch ($f['lvl']):
case 1:
	$basezdor = 3;
	$basesila = 3;
	$baselovka = 15;
	$baseinta = 15;
break;

case 2:
	$basezdor = 9;
	$basesila = 11;
	$baselovka = 30;
	$baseinta = 30;
break;

case 3:
	$basezdor = 18;
	$basesila = 22;
	$baselovka = 50;
	$baseinta = 50;
break;

case 4:
	$basezdor = 30;
	$basesila = 37;
	$baselovka = 65;
	$baseinta = 65;
break;

case 5:
	$basezdor = 45;
	$basesila = 56;
	$baselovka = 85;
	$baseinta = 85;
break;

case 6:
	$basezdor = 60;
	$basesila = 75;
	$baselovka = 100;
	$baseinta = 100;
break;

case 7:
	$basezdor = 84;
	$basesila = 105;
	$baselovka = 125;
	$baseinta = 125;
break;

case 8:
	$basezdor = 108;
	$basesila = 135;
	$baselovka = 145;
	$baseinta = 145;
break;

case 9:
	$basezdor = 135;
	$basesila = 168;
	$baselovka = 165;
	$baseinta = 165;
break;

case 10:
	$basezdor = 165;
	$basesila = 206;
	$baselovka = 190;
	$baseinta = 190;
break;

case 11:
	$basezdor = 198;
	$basesila = 247;
	$baselovka = 210;
	$baseinta = 210;
break;

case 12:
	$basezdor = 234;
	$basesila = 292;
	$baselovka = 235;
	$baseinta = 235;
break;

case 13:
	$basezdor = 273;
	$basesila = 341;
	$baselovka = 255;
	$baseinta = 255;
break;

case 14:
	$basezdor = 315;
	$basesila = 393;
	$baselovka = 280;
	$baseinta = 280;
break;

case 15:
	$basezdor = 360;
	$basesila = 450;
	$baselovka = 305;
	$baseinta = 305;
break;

case 16:
	$basezdor = 408;
	$basesila = 510;
	$baselovka = 330;
	$baseinta = 330;
break;

case 17:
	$basezdor = 459;
	$basesila = 573;
	$baselovka = 355;
	$baseinta = 355;
break;

case 18:
	$basezdor = 513;
	$basesila = 641;
	$baselovka = 380;
	$baseinta = 380;
break;

case 19:
	$basezdor = 570;
	$basesila = 712;
	$baselovka = 410;
	$baseinta = 410;
break;

case 20:
	$basezdor = 630;
	$basesila = 787;
	$baselovka = 435;
	$baseinta = 435;
break;

case 21:
	$basezdor = 693;
	$basesila = 866;
	$baselovka = 465;
	$baseinta = 465;
break;

case 22:
	$basezdor = 759;
	$basesila = 948;
	$baselovka = 495;
	$baseinta = 495;
break;

case 23:
	$basezdor = 828;
	$basesila = 1035;
	$baselovka = 520;
	$baseinta = 520;
break;

case 24:
	$basezdor = 900;
	$basesila = 1125;
	$baselovka = 555;
	$baseinta = 555;
break;

default:
	$basezdor = 975;
	$basesila = 1218;
	$baselovka = 585;
	$baseinta = 585;
break;

endswitch;
function getHP($s=1)
	{
	global $basezdor;
	return mt_rand(intval($basezdor * $s * 0.85), intval($basezdor * $s * 1.15)) * 10;
	}

function getSila($s=1)
	{
	global $basesila;
	return mt_rand(intval($basesila * $s * 0.85), intval($basesila * $s * 1.15));
	}

function getLovka($s=1)
	{
	global $baselovka;
	return mt_rand(intval($baselovka * $s * 0.85), intval($baselovka * $s * 1.15));
	}

function getInta($s=1)
	{
	global $baseinta;
	return mt_rand(intval($baseinta * $s * 0.85), intval($baseinta * $s * 1.15));
	}

function addBot($name,$lvl)
	{
	$db = DBC::instance();
	global $boi_id;
    if(!isset($boi_id)) $boi_id = 0;
	require('inc/bot_base.php');	//все данные о ботах в одном месте
	$q = $db->query("insert into `combat` values(0,'{$name}',{$sila},{$inta},{$lovka},1,0,0,0,{$lvl},{$hp},{$hp},0,0,{$boi_id},0,1,0,'{$_SERVER['REQUEST_TIME']}','{$_SERVER['REQUEST_TIME']}');");
	return 0;
	}


function addBotmy($name,$lvl)
	{
	$db = DBC::instance();
	global $boi_id;
    if(!isset($boi_id)) $boi_id = 0;
	require('inc/bot_base.php');	//все данные о ботах в одном месте
	$q = $db->query("insert into `combat` values(0,'{$name}',{$sila},{$inta},{$lovka},1,0,0,0,{$lvl},{$hp},{$hp},0,0,{$boi_id},0,2,0,'{$_SERVER['REQUEST_TIME']}','{$_SERVER['REQUEST_TIME']}');");
	return 0;
	}

?>
