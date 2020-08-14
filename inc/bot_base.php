<?php
##############
# 24.12.2014 #
##############

//делаю пока сюда, для совместимости со старыми версиями. Потом всех ботов надо будет загнать в базу
//и создать им всем статические параметры.
switch($name):
case 'Младший тренер':
	$sila	=	getSila(0.8);
	$inta	=	getInta(0.8);
	$lovka	=	getLovka(0.8);
	$hp		=	getHP();
break;

case 'Ворюга':
	$sila	=	getSila(0.9);
	$inta	=	getInta(0.3);
	$lovka	=	getLovka(0.9);
	$hp		=	getHP(1.1);
break;

case 'Тренер':
	$sila	=	getSila(0.9);
	$inta	=	getInta(0.9);
	$lovka	=	getLovka(0.9);
	$hp		=	getHP();
break;

case 'Старший тренер':
	$sila	=	getSila();
	$inta	=	getInta();
	$lovka	=	getLovka();
	$hp		=	getHP();
break;

case 'Падальщик':
	$sila	=	getSila(1.2);
	$inta	=	getInta(1.2);
	$lovka	=	getLovka(1.2);
	$hp		=	getHP(1.1);
break;

case 'Молодой падальщик':
	$sila	=	getSila(0.9);
	$inta	=	getInta(1.1);
	$lovka	=	getLovka(1.1);
	$hp		=	getHP(1.1);
break;

case 'Сильный охранник корована':
	$sila	=	getSila(1.3);
	$inta	=	getInta(1.3);
	$lovka	=	getLovka(1.3);
	$hp		=	getHP(1.2);
break;

case 'Охранник в красном':
	$sila	=	getSila(1.1);
	$inta	=	getInta(1.1);
	$lovka	=	getLovka(1.1);
	$hp		=	getHP(1.1);
break;

case 'Охранник в черном':
	$sila	=	getSila(1.1);
	$inta	=	getInta(1.1);
	$lovka	=	getLovka(1.1);
	$hp		=	getHP();
break;

case 'Кротокрыс':
	$sila	=	getSila(1.2);
	$inta	=	getInta(1.1);
	$lovka	=	getLovka(1.2);
	$hp		=	getHP(1.3);
break;

case 'Молодой кротокрыс':
	$sila	=	getSila();
	$inta	=	getInta();
	$lovka	=	getLovka();
	$hp		=	getHP();
break;

case 'Мясной жук':
	$sila	=	getSila();
	$inta	=	getInta();
	$lovka	=	getLovka();
	$hp		=	getHP();
break;

case 'Стервятник':
	$sila	=	getSila(1.2);
	$inta	=	getInta(1.2);
	$lovka	=	getLovka(1.2);
	$hp		=	getHP();
break;

case 'Молодой стервятник':
	$sila	=	getSila();
	$inta	=	getInta();
	$lovka	=	getLovka();
	$hp		=	getHP();
break;

case 'Кровосос':
	$sila	=	getSila(1.1);
	$inta	=	getInta(1.1);
	$lovka	=	getLovka();
	$hp		=	getHP();
break;

case 'Шершень':
	$sila	=	getSila();
	$inta	=	getInta();
	$lovka	=	getLovka(1.5);
	$hp		=	getHP();
break;

case 'Волк':
	$sila	=	getSila(1.5);
	$inta	=	getInta(1.7);
	$lovka	=	getLovka(1.4);
	$hp		=	getHP(1.8);
break;

case 'Волчица':
	$sila	=	getSila();
	$inta	=	getInta();
	$lovka	=	getLovka(1.3);
	$hp		=	getHP(1.1);
break;

case 'Остер':
	$sila	=	getSila();
	$inta	=	getInta();
	$lovka	=	getLovka();
	$hp		=	getHP();
break;

case 'Черный гоблин':
	$sila	=	getSila();
	$inta	=	getInta();
	$lovka	=	getLovka();
	$hp		=	getHP(0.7);
break;

case 'Гоблин':
	$sila	=	getSila();
	$inta	=	getInta();
	$lovka	=	getLovka();
	$hp		=	getHP(1.4);
break;

case 'Тролль':
	$sila = 600;
	$inta = 5000;
	$lovka = 1;
	$hp = 50000;
break;

case 'черный дракон':
	$sila = 2500;
	$inta = 5000;
	$lovka = 5000;
	$hp = 100000;
break;

case 'Гитлер':
	$sila	=	getSila(0.8);
	$inta	=	getInta(0.8);
	$lovka	=	getLovka(0.8);
	$hp		=	getHP(0.9);
break;

case 'Глава стражи':
	$sila	=	getSila(2);
	$inta	=	getInta(2);
	$lovka	=	getLovka(2);
	$hp		=	getHP(3);
break;

case 'Глорх':
	$sila	=	getSila();
	$inta	=	getInta();
	$lovka	=	getLovka();
	$hp		=	getHP();
break;

case 'Кусач':
	
	$sila	=	getSila();
	$inta	=	getInta();
	$lovka	=	getLovka();
	$hp		=	getHP();
break;

case 'Шмыг':
	$sila	=	getSila(1.2);
	$inta	=	getInta();
	$lovka	=	getLovka();
	$hp		=	getHP();
break;

case 'Оборотень':
	$sila	=	getSila();
	$inta	=	getInta();
	$lovka	=	getLovka();
	$hp		=	getHP(0.5);
break;

case 'Упырь':
	$sila	=	getSila();
	$inta	=	getInta();
	$lovka	=	getLovka();
	$hp		=	getHP();
break;

case 'Орк-Маг':
	$sila	=	getSila(1.1);
	$inta	=	getInta(1.1);
	$lovka	=	getLovka(1.1);
	$hp		=	getHP();
break;

case 'Орк-Шаман':
	$sila	=	getSila();
	$inta	=	getInta();
	$lovka	=	getLovka();
	$hp	=	getHP(1.1);
break;

case 'Ледяной голем':
	$sila	=	getSila();
	$inta	=	getInta(1.5);
	$lovka	=	getLovka();
	$hp		=	getHP();
break;

case 'Огненный голем':
	$sila	=	getSila();
	$inta	=	getInta();
	$lovka	=	getLovka(1.5);
	$hp		=	getHP();
break;

case 'Каменный голем':
	$sila	=	getSila(1.5);
	$inta	=	getInta();
	$lovka	=	getLovka();
	$hp		=	getHP();
break;

case 'Гарпия':
	$sila	=	getSila(1.5);
	$inta	=	getInta(1.5);
	$lovka	=	getLovka(1.5);
	$hp		=	getHP(1.3);
break;

case 'Орк-Воин':
	$sila	=	getSila();
	$inta	=	getInta();
	$lovka	=	getLovka();
	$hp		=	getHP();
break;

case 'Орк':
	$sila	=	getSila();
	$inta	=	getInta();
	$lovka	=	getLovka();
	$hp		=	getHP(1.1);
break;

case 'Болотожор':
	$sila	=	getSila(1.3);
	$inta	=	getInta(1.3);
	$lovka	=	getLovka(1.3);
	$hp		=	getHP(1.3);
break;

case 'Огненная ящерица':
	$sila	=	getSila(1.9);
	$inta	=	getInta(1.9);
	$lovka	=	getLovka(1.9);
	$hp		=	getHP(1.9);
break;

case 'Ящерица огня':
	$sila	=	getSila(1.8);
	$inta	=	getInta(1.8);
	$lovka	=	getLovka(1.8);
	$hp		=	getHP(1.8);
break;

case 'Скелет':
	$sila	=	getSila();
	$inta	=	getInta(1.3);
	$lovka	=	getLovka();
	$hp		=	getHP();
break;

case 'Зомби':
	$sila	=	getSila();
	$inta	=	getInta();
	$lovka	=	getLovka(1.3);
	$hp		=	getHP();
break;

case 'Ползун':
	$sila	=	getSila();
	$inta	=	getInta();
	$lovka	=	getLovka();
	$hp		=	getHP();
break;

case 'Разбойник':
	$sila	=	getSila(1.1);
	$inta	=	getInta(1.2);
	$lovka	=	getLovka(1.3);
	$hp		=	getHP(0.9);
break;

case 'Жук-шелкопряд':
	$sila	=	getSila(1.1);
	$inta	=	getInta(1.1);
	$lovka	=	getLovka(1.1);
	$hp		=	getHP();
break;

case 'Кабан':
	$sila	=	getSila(1.6);
	$inta	=	getInta(1.4);
	$lovka	=	getLovka(0.8);
	$hp		=	getHP(1.2);
break;

case 'Энд':
	$sila	=	getSila(1.8);
	$inta	=	getInta(1.1);
	$lovka	=	getLovka(1.2);
	$hp		=	getHP(1.4);
break;

case 'Старый Энд':
	$sila	=	getSila(2.0);
	$inta	=	getInta(1.1);
	$lovka	=	getLovka(1.2);
	$hp		=	getHP(1.5);
break;

case 'Злобный бес':
	$sila	=	getSila(1.1);
	$inta	=	getInta(1.9);
	$lovka	=	getLovka(2.5);
	$hp		=	getHP(0.9);
break;

case 'Замковая стража':
	$sila = 5000;
	$inta = 1000;
	$lovka = 50;
	$hp		=	100000;
break;

case 'Зимний дух':
	$sila	=	getSila(1.3);
	$inta	=	getInta(1.4);
	$lovka	=	getLovka(1.5);
	$hp		=	getHP(1.4);
break;


case 'Гринч Вальдирры':
	$sila	=	1;
	$inta	=	1;
	$lovka	=	1;
	$hp		=	500000;
break;


case 'Снеговик':
	$sila	=	getSila(0.5);
	$inta	=	getInta(1.5);
	$lovka	=	getLovka(1.0);
	$hp		=	getHP(1.0);
break;

case 'Медведь':
	$sila	=	getSila(1.9);
	$inta	=	getInta(1.9);
	$lovka	=	getLovka(1.6);
	$hp		=	getHP(1.9);
break;

case 'Умертвие':
	$sila	=	getSila(1.3);
	$inta	=	getInta(0.9);
	$lovka	=	getLovka();
	$hp		=	getHP(1.7);
break;

case 'Рыцарь смерти':
	$sila	=	getSila(1.8);
	$inta	=	getInta(1.7);
	$lovka	=	getLovka(1.6);
	$hp		=	getHP(1.8);
break;

case 'Лич':
	$sila	=	getSila(1.5);
	$inta	=	getInta(2.0);
	$lovka	=	getLovka(1.8);
	$hp		=	getHP(1.3);
break;

case 'Кроль':
$sila = getSila(0.8);
$inta = getInta(0.8);
$lovka = getLovka(1.6);
$hp = getHP(0.6);
break;

case 'Бродяга':
$sila = getSila();
$inta = getInta(1.1);
$lovka = getLovka(1.1);
$hp = getHP();
break;

case 'Гигантская стрекоза':
$sila = getSila(0.3);
$inta = getInta(0.9);
$lovka = getLovka(1.9);
$hp = getHP(0.5);
break;


case 'Сбежавший рудокоп':
$sila = getSila(1.3);
$inta = getInta(0.9);
$lovka = getLovka(1.2);
$hp = getHP(1.5);
break;

case 'Белая ворона':
$sila = getSila(0.5);
$inta = getInta(0.9);
$lovka = getLovka(2.1);
$hp = getHP(0.5);
break;


default: msg2('Нет бота '.ekr($name),1); break;
endswitch;
?>
