<?php
require_once('inc/top.php');	// вывод на экран
require_once('inc/check.php');	// вход в игру
require_once('inc/head.php');
require_once('class/items.php');	// работа с вещами
require_once('inc/boi.php');		//создание боя + завод игрока в бой
require_once('inc/bot.php');		//параметры бота + создание бота

$q = $db->query("select * from `invent`");
while($item = $q->fetch_assoc()){
$t = time();
if($item['iznosday'] + 86400 < $t){
$tri = $t - $item['iznosday'];
$iznos = $tri/86400;
$x = $db->query("update `invent` set iznos=iznos-{$iznos},iznosday={$t} where id={$item['id']};");
}

if($item['iznos'] <=0){
$y = $db->query("delete from `invent` where id={$item['id']}");
}

}
fin();
?>