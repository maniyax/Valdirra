<?php
##############
# 24.12.2014 #
##############
require_once('inc/top.php');	// вывод на экран
require_once('inc/check.php');	// вход в игру
require_once('inc/head.php');
require_once('class/items.php');	// работа с вещами
require_once('inc/boi.php');		//создание боя + завод игрока в бой
require_once('inc/bot.php');		//параметры бота + создание бота
$start = isset($_REQUEST['start']) ? intval($_REQUEST['start']) : 0;
$go = isset($_REQUEST['go']) ? intval($_REQUEST['go']) : 0;
$mod = isset($_REQUEST['mod']) ? $_REQUEST['mod'] : '';
$iid = isset($_REQUEST['iid']) ? $_REQUEST['iid'] : 0;
$summ = isset($_REQUEST['summ']) ? $_REQUEST['summ'] : 0;
$rasa = isset($_REQUEST['rasa']) ? $_REQUEST['rasa'] : 0;
//$summ = isset($_REQUEST['summ']) ? intval($_REQUEST['summ']) : 0;
//if (isset($_REQUEST['summ'])) $summ = intval($_REQUEST['summ']); else $summ = '';
$ok = isset($_REQUEST['ok']) ? $_REQUEST['ok'] : 0;
$keystring = isset($_REQUEST['keystring']) ? $_REQUEST['keystring'] : '';

// меню
if($f['status'] == 1)
	{
	knopka('battle.php', 'Вы в бою!', 1);
	fin();
	}

if($f['loc'] !=205) msg2('Вы не в храме',1);
msg2('<b>Храм богу Неспящему</b>');
if(empty($go)){
msg2('Уровень храма: '.$settings['hramlvl'].'<br>
Пожертвования: '.$settings['hrammoney'].'');
msg2('Вы можете:');
knopka2('hram.php?go=1', 'пожертвовать деньги на строительство храма',1);
knopka2('hram.php?go=8', 'подойти к алтарю',1);
//knopka2('hram.php?go=2', 'преобрести свитки с благославлениями',1);
//knopka2('hram.php?go=3', 'стать жрецом бога',1);
//knopka2('hram.php?go=4', 'подать заявку на брак',1);
//knopka2('hram.php?go=5', 'развестись',1);
//knopka2('hram.php?go=6', 'посмотреть лог брако-разводных процессов',1);
knopka2('hram.php?go=7', 'посмотреть лог пожертвований',1);
fin();
}
elseif($go==1){
if(empty($ok)){
msg2('Жертвуемые вами средства идут на развитие храма. Чем больше денег суммарно пожертвовали все игроки, тем больше в храме станет доступно благославений разного уровня.');
msg2('Сколько золотых монет вы готовы пожертвовать?<br>
<form action="hram.php?go=1&ok=1" method="post">
<input type="number" name="summ"/>
<input type="submit" value="Пожертвовать"/>
</form>',1);
knopka2('hram.php', 'Вернуться');
fin();
}
elseif($ok==1){
if($summ <1) $summ=1;
if($summ > $f['au']) msg2('У вас нет столько денег',1);
$log = 'Игрок '.$f['login'].' жертвует '.$summ.' золотых монет на развитие храма';
$f['au'] -= $summ;
$settings['hrammoney'] += $summ;
$q = $db->query("update `users` set au={$f['au']} where login='{$f['login']}' limit 1;");
$q = $db->query("update `settings` set hrammoney={$settings['hrammoney']}");
$q =$db->query("insert into `log_peredach` values(0,'{$f['login']}','{$log}','бог неспящий','{$t}');");
msg2('Вы успешно пожертвовали '.$summ.' монет на нужды храма');
knopka2('hram.php', 'Вернуться');
fin();
}
}

elseif($go==7){
	$numb = 25;			//записей на страницу
	$count = 0;
	$q = $db->query("select count(*) from `log_peredach` where login_per='бог неспящий';");
	$a = $q->fetch_assoc();
	$all_log = $a['count(*)'];
	if($start > intval($all_log / $numb)) $start = intval($all_log / $numb);
	if($start < 0) $start = 0;
	$limit = $start * $numb;
	$count = $limit;
	$q = $db->query("select * from log_peredach where login_per='бог неспящий' order by id desc limit {$limit},{$numb};");
	while($log = $q->fetch_assoc())
		{
		$count++;
		echo '<div class="board2" style="text-align:left">';
		echo $count.'. '.date('d.m.Y H:i',$log['dateper']).' - '.$log['log'];
		echo '</div>';
		}
	echo '<div class="board">';
	if($start > 0) echo '<a href="inv.php?mod=log&start='.($start - 1).'" class="navig"><-Назад</a>'; else echo '<a href="#" class="navig"> <-Назад</a>';
	echo ' | ';
	if($limit + $numb < $all_log) echo '<a href="inv.php?mod=log&start='.($start + 1).'" class="navig" >Вперед-></a>'; else echo ' <a href="#" class="navig"> Вперед-></a>';
	echo '</div>';
	fin();
}

elseif($go==8){
if(empty($ok)){
msg2('Уровень алтаря: '.$settings['hramlvl'].'');
knopka2('hram.php?go=8&ok=1', 'Получить благославление',1);
knopka2('hram.php?go=8&ok=2', 'Переродиться',1);
fin();
}
elseif($ok==1){
msg('Небольшой постамент, на котором расположен жертвенный камень. От него ощутимо веет мощью.');

$timer = $t + 86400;
//$f['altar'] = $settings['altar'];
$f['altar_time'] = $timer;
$q = $db->query("update `users` set altar={$settings['hramlvl']},altar_time='{$timer}' where id={$f['id']} limit 1;");
$f = calcparam($f);
msg2('Боги довольны вами, усиление +'.$settings['altarlvl'].'%');
knopka2('hram.php', 'Вернуться');
fin();
}
elseif($ok==2){
if($f['lvl'] <23) msg2('Минимальный уровень для перерождения 23',1);
msg2('Вы можете переродиться. Это значит, что:
<ul>
<li> ваш уровень и все ваши параметры упадут до дефолтных значений;</li>
<li> лимит изучаемых профессий увеличится на 1;</li>
<li> вы сможете выбрать расу, за какую играть (плюсы рас см. в библиотеке Вальдирры);</li>
<li> инвентарь, счет в банке, склад, монеты, руда, известность и прочее не убавятся.</li>
</ul>');
$cena = 2+1*$f['pererod'];
msg2('Стоимость перерождения для вас составит: '.$cena.' золотых');
msg2('<form action="hram.php?go=8&ok=3" method="post">
Раса: <select name="rasa">
<option value="1">Человек</option>
<option value="2">Гном</option>
<option value="3">Эльф</option>
<option value="4">Орк</option>
</select> <input type="submit" value="Переродиться"/>
</form>',1);
fin();}

elseif($ok==3){
$cena = 2+1*$f['pererod'];
if($f['au'] < $cena) msg2('У вас нет столько денег',1);

		$q = $db->query("update `users` set exp=0,zdor=1,sila=3,lovka=3,inta=3,lvl=1,intel=1,au=au-{$cena},pererod=pererod+1,rasa={$rasa},profsmax=profsmax+1 where login='{$f['login']}' limit 1;");
	$items->drop_equip_all($f['login']);
	$f = calcparam($f);

		msg2('Поздравляем!',1);
fin();
}
}

elseif($go==2){
if(empty($ok)){
knopka2('hram.php?go=2&ok=1', 'Малое благославление - 3 серебряные монеты',1);
fin();
}
elseif($ok==1){
if($f['ag'] < 3) msg2('У вас нет столько денег',1);
$q = $db->query("update `users` set ag=ag-3 where id={$f['id']} limit 1;");
$items->add_item($f['login'], 833, 1);
msg2('Вы купили малое благославление',1);
fin();
}

}
?>
