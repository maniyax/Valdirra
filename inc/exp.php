<?php
##############
# 24.12.2014 #
##############

switch($f['lvl']):
case 1:
	$exp = 100;
break;

case 2:
	$exp = 200;
break;

case 3:
	$exp = 300;
break;

case 4:
	$exp = 500;
break;

case 5:
	$exp = 800;
break;

case 6:
	$exp = 1300;
break;

case 7:
	$exp = 2100;
break;

case 8:
	$exp = 3400;
break;

case 9:
	$exp = 5500;
break;

case 10:
	$exp = 8900;
break;

case 11:
	$exp = 28800;
break;

case 12:
	$exp = 46600;
break;

case 13:
	$exp = 75400;
break;

case 14:
	$exp = 122000;
break;

case 15:
	$exp = 177400;
break;

case 16:
	$exp = 299400;
break;

case 17:
	$exp = 476800;
break;

case 18:
	$exp = 776200;
break;

case 19:
	$exp = 1253000;
break;

case 20:
$exp = 2029200;
break;

case 21:
$exp = 4923300;
break;

case 22:
$exp = 7967100;
break;

case 23:
$exp = 12890400;
break;


default:
	$exp = 20857500;
break;
endswitch;
// опыта до апа
$tolev = $exp - $f['exp'];

$all_stats = 0;
$mlvl = $f['lvl'];
if($mlvl > 25) $mlvl = 25;
for($i = 1; $i <= $mlvl; $i++)
	{
	$all_stats += (10 + $i);
	}
// свободных статов
$stat_free = $all_stats - ($f['sila'] + $f['inta'] + $f['lovka'] + $f['intel'] + $f['zdor']); //свободные статы
?>
