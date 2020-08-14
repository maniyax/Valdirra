<?php
##############
# 24.12.2014 #
##############

switch($f['loc']):
case 1:
	if($f['lvl'] <= 3) knopka2('act.php', '<b>Тренер</b>');
	if($f['lvl'] <= 4) knopka2('kvest.php', '<b>Бабушка-старушка</b>');
	knopka2('shop.php', 'Торговец');
//knopka2('shopeda.php', 'Продуктовая лавка');
break;

case 2:
	if($f['lvl'] <= 3)knopka2('act.php', 'Ворюга');
break;

case 5:
	knopka2('act.php', 'Напасть на падальщиков!');
break;

case 8:
	knopka2('act.php', 'Напасть!');
	if(!empty($f['klan'])) knopka2('rabota.php', 'Идти к шахте');
break;

case 9:
	knopka2('act.php', 'Бить жука!');
break;

case 11:
	knopka2('act.php', 'Атаковать!');
break;

case 13:
	knopka2('act.php', 'Напасть на шершней!');
break;

case 14:
	knopka2('act.php', 'Напасть!');
break;

case 15:
	knopka2('kvest.php', 'Рисовое поле');
break;

case 19:
	knopka2('rabota.php', 'Шахта');
        knopka2('kvest1.php', 'Старый рудокоп');
break;

case 20:
	knopka2('act.php', 'Охотиться на остеров');
break;

case 21:
	knopka2('act.php', 'Драться с гоблинами');
break;
/*
case 22:
	knopka2('act.php', 'Заглянуть в пещеру');
break;
*/
case 27:
	knopka2('act.php', 'Атаковать');
break;


case 37:
	knopka2('shop.php', 'Торговец');
        knopka2('kvest.php', 'Травница');

break;


case 39:
	knopka2('act.php', 'Напасть на шмыга');
break;

case 41:
	knopka2('act.php', 'Напасть!');
break;

case 43:
	$kvest = unserialize($f['kvest']);
	if(!empty($kvest['loc56ks']) && $kvest['loc56ks']['nagrada'] == 0 && $kvest['loc56ks']['lg'] == 0)
		{
		knopka2('act.php', 'Подойти к глыбе льда');
		}
	unset($kvest);
break;

case 44:
	knopka2('rabota.php', 'Наняться надзирателем');
break;


case 49:
	knopka2('act.php', 'Напасть!');
break;

case 51:
	knopka2('kvest.php', 'Виноградные сады');
break;

case 55:
	$kvest = unserialize($f['kvest']);
	if(!empty($kvest['loc56ks']) && $kvest['loc56ks']['nagrada'] == 0 && $kvest['loc56ks']['og'] == 0)
		{
		knopka2('act.php', 'Подойти к горе огня');
		}
	unset($kvest);
break;

case 56:
	knopka2('kvest.php', 'Зайти в башню');
break;

case 62:
	$kvest = unserialize($f['kvest']);
	if(!empty($kvest['loc56ks']) && $kvest['loc56ks']['nagrada'] == 0 && $kvest['loc56ks']['kg'] == 0)
		{
		knopka2('act.php', 'Наброситься на каменного голема');
		}
	unset($kvest);
break;

case 67:
	knopka2('act.php', 'Осмотреть гнездо');
break;

case 68:
	knopka2('kvest.php', 'Собирать солод');
break;

case 69:
	knopka2('kvest.php', 'Грабить корован');
break;

case 73:
	knopka2('act.php', 'Осмотреться');
break;

case 77:
	knopka2('kvest.php', 'Подойти к старику');
break;

case 78:
	knopka2('kvest.php', 'Собирать мёд');
break;

case 83:
	knopka2('kvest.php', 'Собирать хмель');
break;

case 89:
	knopka2('act.php', 'Бить болотожора');
break;

case 91:
	knopka2('shop.php', 'Торговец');



break;

case 92:
	knopka2('act.php', 'Вступить в бой');
break;

case 95:
	knopka2('act.php', 'Приоткрыть дверь');
break;

case 97:
	knopka2('kvest.php', 'Старый магистр');
break;


case 99:
	knopka2('kvest.php', 'Подойти к старику');
break;

case 100:
	knopka2('act.php', 'Атаковать');
break;

case 103:
	knopka2('act.php', 'Заглянуть в гнездо');
break;

case 105:
knopka2('kvest.php', 'Забросить удочку');
break;

case 106:
	knopka2('kvest.php', 'Залезть на дерево');
break;

case 107:
	knopka2('kvest.php', 'Искать наживку');
break;

case 109:
	knopka2('kvest.php', 'Подойти к куче костей');
break;

case 147:
	knopka2('kvest.php', 'Стражник');
break;

case 149:
        knopka2('elka.php', 'Новогодняя елка');
//        knopka2('kvest1.php', 'Мэрия');
	knopka2('lib.php?mod=straj', 'Городская стража');
	knopka2('bank.php', 'Банк');
	knopka2('lib.php', 'Библиотека Вальдирры');
        knopka2('kvest.php', 'Клановый распорядитель');
	knopka2('news.php', 'Городской архив');
        knopka2('pm.php', 'Почтовый ящик');
        //knopka2('act.php', 'Гитлер');
break;

case 151:
knopka2('act.php', 'Защищать город!');
knopka2('kvest.php', 'Камень воскрешения');
break;


case 153:
	knopka2('kvest.php', 'Домохозяйка');
knopka2('loc.php?mod=fontan', 'Фонтан');
break;

case 169:
	knopka2('taverna.php', 'Таверна');
	$q = $db->query("select count(*) from `arena` where time>'{$t}';");
	$a = $q->fetch_assoc();
	$a = $a['count(*)'];
	knopka2('arena.php', 'Арена (<b>'.$a.'</b>)');
break;

case 154:
	knopka2('shop.php', 'Палатка торговца');
//knopka2('shopeda.php', 'Продуктовая лавка');
knopka2('shoprem.php', 'Магазинчик ремесленника');
	$q = $db->query("select count(*) from `invent` where flag_rinok=1;");
	$a = $q->fetch_assoc();
	$a = $a['count(*)'];
	knopka2('rinok.php', 'Рынок (<b>'.$a.'</b>)');
break;

case 156:
	knopka2('kvest.php', 'Кузница');
        knopka2('kvest1.php', 'Подойти к Дорину');
break;

case 158:
        knopka2('kvest.php', 'Собирать хмель');
break;

case 166:
        knopka2('kvest.php', 'Собирать солод');
break;

case 168:
	if ($f['lvl'] > 3) knopka2('act.php', 'Разбойник');
break;

case 171:
	knopka2('kvest.php', 'Рубить ель');
break;

case 183:
	knopka2('kvest.php', 'Рубить дуб');
break;

case 195:
	knopka2('kvest.php', 'Рубить ель');
break;

case 173:
knopka2('kvest.php', 'Зайти в дом');
break;

case 148:
knopka2('kvest.php', 'Мясник');
break;

case 185:
	knopka2('act.php', 'Напасть на Эндов');
break;

case 197:
knopka2('act.php', 'Кабан');
break;

case 200:
knopka2('act.php', 'Дёрнуть за хвост беса');
break;

case 163:
knopka2('kvest.php', 'Собирать корень женьшеня');
break;

case 164:
knopka2('kvest.php', 'Собирать корень женьшеня');
break;

case 157:
knopka2('kvest.php', 'Собирать корень женьшеня');
break;

case 159:
knopka2('kvest.php', 'Собирать корень мандрагоры');
break;

case 162:
knopka2('kvest.php', 'Собирать корень мандрагоры');
break;

case 150:
knopka2('kvest.php', 'Алхимик');
break;

case 155:
knopka2('kvest.php', 'Имперский ювелир');
break;

case 189:
knopka2('act.php', 'Медведь');
break;

case 205:
knopka2('hram.php', 'Войти в храм');
break;

case 238:
knopka2('kvest.php', 'Подойти к мастеру рун');
break;

case 206:
knopka2('koj.php', 'Кожевенная');
knopka2('run.php', 'Войти в рунную мастерскую');
break;

case 239:
knopka2('lib.php', 'Центральная библиотека');
break;

case 24:
knopka2('act.php', 'Кроль');
break;

case 80:
knopka2('act.php', 'Напасть на бродягу');
break;

case 42:
knopka2('act.php', 'Напасть на сбежавшего шахтера');
break;

case 143:
knopka2('act.php', 'Убить ворону');
break;
case 224:
	knopka2('shop.php', 'Лавка со снаряжением');
break;

case 228:
	$q = $db->query("select count(*) from `invent` where flag_rinok=1;");
	$a = $q->fetch_assoc();
	$a = $a['count(*)'];
	knopka2('rinok.php', 'Рынок (<b>'.$a.'</b>)');
break;

case 223:
knopka2('shoprem.php', 'Лавка ремесленника');
break;


endswitch;

$q = $db->query("select name,loc,point from `klans` where loc={$f['loc']} or point={$f['loc']} limit 1;");
if($q->num_rows > 0)
	{
	$a = $q->fetch_assoc();
	knopka2('zamok.php', 'Замок клана '.$a['name']);
	}

?>