<?php
##############
# 29.07.2014 #
##############
$day = 60*60*24; // раз в 24 часов
//$bonus_t = $f['bonus_time'] + $day;
if($f['bonus_time'] + $day < $_SERVER['REQUEST_TIME'])
	{
	if(($f['bonus_day'] > 0 and $f['bonus_time'] + $day * 2 < $_SERVER['REQUEST_TIME']) or ($f['bonus_day'] >= 7)) $f['bonus_day'] = 0;

$f['bonus_day'] ++;
if($f['bonus_day'] == 1) {$items->add_item($f['login'], 620); $log = 'эликсир ловкости';}
if($f['bonus_day'] == 2) {$items->add_item($f['login'], 621); $log = 'эликсир реакции';}
if($f['bonus_day'] == 3) {$items->add_item($f['login'], 622); $log = 'эликсир жизненной силы';}
if($f['bonus_day'] == 4) {$items->add_item($f['login'], 717); $log = 'свиток телепортации в город';}
if($f['bonus_day'] == 5) {$items->add_item($f['login'], 624); $log = 'эликссир вышибалы';}
if($f['bonus_day'] == 6) {$items->add_item($f['login'], 637); $log = 'вино';}

if($f['bonus_day'] == 7) {$items->add_item($f['login'], 716); $log = 'грамоту телепортации';}
	$str = '<img src="pic/bonus.png"> Бонус за ежедневное посещение! День '.$f['bonus_day'].'. Вы получаете '.$log.'.</div>';


	msg2($str);
	$q = $db->query("update `users` set bonus_time=UNIX_TIMESTAMP(),bonus_day={$f['bonus_day']} WHERE id={$f['id']} LIMIT 1;");
}
?>