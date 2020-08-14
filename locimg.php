<?php

require_once('class/DBC.php');
session_start();
$id = intval($_COOKIE['id']);
if(empty($id)) exit('<title>403</title>403 Forbidden');
$q = $db->query("select loc,sex from `users` where id={$id} limit 1;"); // получили положение пользователя
$user = $q->fetch_assoc();
$q = $db->query("select map_id,X,Y from `loc` where id={$user['loc']} limit 1;");
$loc = $q->fetch_assoc();
$q = $db->query("select X,Y from `map` where id={$loc['map_id']} limit 1;"); // получили размер карты
$map = $q->fetch_assoc();

$pox = $loc['X']; // наше положение сохраним отдельно
$poy = $loc['Y'];
if($poy > $map['Y'] - 2) $poy = $map['Y'] - 2; // для того чтобы быть в центре
if($pox > $map['X'] - 2) $pox = $map['X'] - 2;
if($poy < 3) $poy = 3;
if($pox < 3) $pox = 3;
$pox1 = $pox - 2;	// какой квадрат грузим из базы.
$pox2 = $pox + 2;
$poy1 = $poy - 2;
$poy2 = $poy + 2;

header ('Content-Type: image/png');
$im = imagecreatetruecolor(120,120); // создаем холст 120*120 точек
$color = imagecolorallocate($im, 0, 0, 0); // черный цвет
imagecolortransparent($im,$color); // делаем черный цвет прозрачным.
imagefilledrectangle($im, 0, 0, 119, 119, $color); // заливаем картинку 
$q = $db->query("select id,N,E,W,S,X,Y from `loc` where map_id='{$loc['map_id']}' and (X>={$pox1} and X<={$pox2} and Y>={$poy1} and Y<={$poy2});");
while($c = $q->fetch_assoc()) // получим все локации с нашего диапазона
	{
	$name = '.png';
	if(!empty($c['W'])) $name = 'W'.$name;
	if(!empty($c['E'])) $name = 'E'.$name;
	if(!empty($c['S'])) $name = 'S'.$name;
	if(!empty($c['N'])) $name = 'N'.$name;
	$img = imagecreatefrompng('pic/'.$name); // загрузим картинку локации
	$rx = $c['X'] - $pox1; // разница от начала координат
	$ry = $c['Y'] - $poy1; // разница от начала координат
	imagecopy($im,$img,(24*$rx),(-1)*((24*$ry)-96),0,0,24,24);
	imagedestroy($img);
	}
$white = imagecolorallocate($im, 255, 255, 255);
$polx = $loc['X'] - $pox; // разницу найдем.
$poly = $loc['Y'] - $poy; // разницу найдем.
$razmer = 24; // размер тайла перса в пикселах
$p = imagecreatefrompng('pic/p.png');

$x1 = (60 - intval($razmer * 0.5)) + $polx * 24; // просто
//$x2 = (60 + intval($razmer * 0.5) - 1) + $polx * 24; // координаты
$y1 = (60 - intval($razmer * 0.5)) - $poly * 24; // положения
//$y2 = (60 + intval($razmer * 0.5) - 1) - $poly * 24; // персонажа
//imagefilledrectangle($im, $x1, $y1, $x2, $y2, $white);
if(!empty($_SESSION['lasthod']))
	{
	if($_SESSION['lasthod'] == 'sever') $positX = 0;
	if($_SESSION['lasthod'] == 'jug') $positX = 6;
	if($_SESSION['lasthod'] == 'vostok') $positX = 3;
	if($_SESSION['lasthod'] == 'zapad') $positX = 9;
	}
else $positX = 6;
if($user['sex'] == 1) $positY = 0; else $positY = 2;
imagecopy($im,$p,$x1,$y1,$positX * 24,$positY * 24,24,24);
imagepng($im);
imagedestroy($im);
exit;
?>