<?php
##############
# 24.12.2014 #
##############

switch($s['doping']):
case 1:
	//брага - урон + 30%
	$s['uron'] = intval($s['uron'] * 1.3);
break;

case 2:
	//пиво - урон + 50%
	$s['uron'] = intval($s['uron'] * 1.5);
break;

case 3:
	//вино - урон + 80%
	$s['uron'] = intval($s['uron'] * 1.8);
break;

case 4:
	//самогон - урон + 50%, крит + 20%, уворот + 20%
	$s['uron'] = intval($s['uron'] * 1.5);
	$s['krit'] = intval($s['krit'] * 1.2);
	$s['uvorot'] = intval($s['uvorot'] * 1.2);
break;

case 5:
	//шнапс - урон + 80%, крит + 50%, уворот + 50%
	$s['uron'] = intval($s['uron'] * 1.8);
	$s['krit'] = intval($s['krit'] * 1.5);
	$s['uvorot'] = intval($s['uvorot'] * 1.5);
break;

case 6:
	//ловкость - уворот +100
	$s['uvurot'] = intval($s['uvorot'] + 100);
break;

case 7:
	//реакция - крит +100
	$s['krit'] = intval($s['krit'] + 100);
break;

case 8:
	//жизненная сила - ХП + 100
	$s['hpmax'] = intval($s['hpmax'] + 100);
break;

case 9:
	// магическая сила - МП + 100
	$s['manamax'] = intval($s['manamax'] + 100);
break;


case 11:
	$s['uron'] = intval($s['uron'] * 2);
	$s['krit'] = intval($s['krit'] * 2);
	$s['uvorot'] = intval($s['uvorot'] * 2);
	$s['manamax'] = intval($s['manamax'] + 200);
	$s['hpmax'] = intval($s['hpmax'] + 200);
break;

case 10:
	//вышибала - урон + 50
	$s['uron'] = intval($s['uron'] + 50);
break;
endswitch;


?>
