<?php
##############
# 24.12.2014 #
##############
require_once('inc/top.php');
require_once('inc/check.php');
require_once('inc/head.php');
require_once('class/items.php');	// работа с вещами
require_once('inc/hpstring.php');
require_once('inc/boi.php');		//создание боя + завод игрока в бой
require_once('inc/bot.php');		//параметры бота + создание бота

$_SESSION['lasthod'] = '';
if(empty($f['loc']))
	{
	// перенос на локу с горным озером
	$f['loc'] = 1;
	$q = $db->query("update `users` set loc=1 where id='{$f['id']}' limit 1;");
	}
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

if($f['hpnow'] ==0){
msg2('Восстановите здоровье!');
knopka2('loc.php', 'Обновить');
fin();
}
// определение переменных в обход register globals
$mod = isset($_REQUEST['mod']) ? $_REQUEST['mod'] : '';
$go = isset($_REQUEST['go']) ? $_REQUEST['go'] : 0;
$text = isset($_REQUEST['text']) ? $_REQUEST['text'] : 0;
$chat = isset($_REQUEST['chat']) ? $_REQUEST['chat'] : 0;
$login = isset($_REQUEST['login']) ? $_REQUEST['login'] : 0;
$num = isset($_REQUEST['num']) ? $_REQUEST['num'] : 1;
$hint = isset($_REQUEST['hint']) ? $_REQUEST['hint'] : 0;
if(!isset($_SESSION['hint'])) $_SESSION['hint'] = 'no';

if($f['rabota'] > $t) msg2('Вы работаете еще '.ceil(($f['rabota'] - $t)/60).' мин.');
if($f['rabota'] > 0 and $f['rabota'] < $t) knopka('rabota.php', 'Вы отработали свое время', 1);

$q = $db->query("select * from `loc` where id={$f['loc']} limit 1;");
$loc = $q->fetch_assoc();
$x = $loc['X'];
$y = $loc['Y'];
if(!empty($_REQUEST['sever']) and $hint == $_SESSION['hint']) {$y++; $_SESSION['lasthod'] = 'sever';}
if(!empty($_REQUEST['jug']) and $hint == $_SESSION['hint']) {$y--; $_SESSION['lasthod'] = 'jug';}
if(!empty($_REQUEST['zapad']) and $hint == $_SESSION['hint']) {$x--; $_SESSION['lasthod'] = 'zapad';}
if(!empty($_REQUEST['vostok']) and $hint == $_SESSION['hint']) {$x++; $_SESSION['lasthod'] = 'vostok';}
if($y != $loc['Y'] or $x != $loc['X'])
	{
	$q = $db->query("select * from `loc` where X='{$x}' and Y='{$y}' and map_id={$loc['map_id']} limit 1;");
	$loc = $q->fetch_assoc();

if($loc['id'] > 0){
	$f['loc'] = $loc['id'];
	$q = $db->query("update `users` set loc={$f['loc']},rabota=0,kvest_step=0 where id='{$f['id']}' limit 1;");
	$f['kvest_step'] = 0;
echo'<audio autoplay>
<source src="240.mp3" type="audio/mpeg">
</audio>';

}
else{
header("location: loc.php?mod=addloc&x=$x&y=$y");
}


}
if(!empty($f['kvest_step'])) knopka('kvest.php?qv_id='.$f['kvest_now'], 'Продолжить задание', 1);
if(!empty($stats_free)) knopka('anketa.php?mod=stats', 'Вам необходимо <span style="color:red">распределить статы</span>');
/*
if(!empty($_REQUEST['get']))
{
header("location: loc.php");
}
*/
if ($mod=='fontan'){
require_once('kvest/fontan.php');
}
if($mod=='test'){
$boi_id = addBoi(0);
addBotMy('Умертвие',$f['lvl']+1);
addBot('Белая ворона',$f['lvl']+1);
toBoi($f,2);
}

if($f['loc'] >=110 and $f['loc'] <=132){
 if(mt_rand(1, 100) <19){
$boi_id = addBoi(0);
addBot('Умертвие',$f['lvl']+1);
toBoi($f,2);
}
elseif(mt_rand(1, 100) <5){
$boi_id = addBoi(0);
addBot('Рыцарь смерти',$f['lvl']+2);
toBoi($f,2);
}
elseif(mt_rand(1, 100) <1){
$boi_id = addBoi(0);
addBot('Лич',$f['lvl']+3);
toBoi($f,2);
}
}
/*
if($f['lvl'] >=10 and mt_rand(1, 100) <= 1){
	$boi_id = addBoi(0);
	addBot('Снеговик',$f['lvl']+15);
	toBoi($f,2);
}
 */
if(($f['loc'] ==2 or $f['loc'] ==3 or $f['loc'] ==4 or $f['loc'] == 23 or $f['loc'] == 76) and $f['lvl'] >=4 and mt_rand(1, 100) <= 5){
	$boi_id = addBoi(0);
	addBot('Гигантская стрекоза',$f['lvl']);
	toBoi($f,2);
}


/*
if($mod=='ded'){
msg2('Новогоднему веселью мешает враг праздника, сам Гринч Вальдирры спустился с зимних гор!<br> У Гринча много здоровья, но зато он очень слабый и неуклюжий.<br>
[Допинг в бою исчезает!]');
knopka2('act.php', 'Напасть');
knopka2('loc.php', 'В локацию',1);
fin();
}
*/
if($mod=='delloc'){
if($f['p_kartograf'] ===0) msg2('Пшел вон отсель!',1);
if(empty($go)){
$id = $_REQUEST['id'];
msg2('Куда вас переместить после удаления локации '.$id.':<form action="loc.php?mod=delloc&go=1" method="POST"><input type="text" name="loc"/><input type="hidden" name="id" value="'.$id.'"><input type="submit" name="Удалить локацию"></form>',1);
}
if($go==1){
$id = $_REQUEST['id'];
$loc = $_REQUEST['loc'];
$q = $db->query("update `users` set loc={$loc} where login='{$f['login']}' limit 1;");
$q = $db->query("delete from `loc` where id={$id} limit 1;");
header("location: loc.php");
}
fin();
}


if($mod=='editloc'){
if($f['p_kartograf'] ==0) msg2('Пшел вон отсель!',1);
$id = $_REQUEST['id'];
$q = $db->query("select * from `loc` where id={$id} limit 1;");
$l = $q->fetch_assoc();
if(empty($go)){
msg2('Редактор локаций');
msg2('
<form action="loc.php?mod=editloc&go=1" method="POST"><br>
Название локации:<br>
<input type="text" name="nameloc" value="'.$l['name'].'"/><br>
Описание локации:<br>
<textarea name="textloc" maxlength=5000 style="width:80%;">'.$l['info'].'</textarea><br/>
X:
<input type="text" name="x" value="'.$l['X'].'"/><br>
Y:
<input type="text" name="y" value="'.$l['Y'].'"/>');
if($l['N'] ==1){ msg2('<input type="checkbox" name="n" value="1" checked/> Проход на север');}
else{ msg2('<input type="checkbox" name="n" value="1"/> Проход на север');}
if($l['E'] ==1){ msg2('<input type="checkbox" name="e" value="1" checked/> Проход на восток');}
else{ msg2('<input type="checkbox" name="e" value="1"/> Проход на восток');}
if($l['W'] ==1){ msg2('<input type="checkbox" name="w" value="1" checked/> Проход на запад');}
else{ msg2('<input type="checkbox" name="w" value="1"/> Проход на запад');}
if($l['S'] ==1){ msg2('<input type="checkbox" name="s" value="1" checked/> Проход на юг');}
else{ msg2('<input type="checkbox" name="s" value="1"/> Проход на юг');}
if($l['map_id'] ==1){ msg2('<input type="checkbox" name="karta" value="1" checked/> Отображать карту на локации');}
else{ msg2('<input type="checkbox" name="karta" value="1"/> Отображать карту на локации');}

msg2('<input type="hidden" name="id" value="'.$id.'"/>
<input type="submit" value="Далее"/></form>');
}
elseif($go == 1){
$id = $_REQUEST['id'];
if (isset($_REQUEST['n'])) $n = intval($_REQUEST['n']); else $n = '';
if (isset($_REQUEST['s'])) $s = intval($_REQUEST['s']); else $s = '';
if (isset($_REQUEST['w'])) $w = intval($_REQUEST['w']); else $w = '';
if (isset($_REQUEST['e'])) $e = intval($_REQUEST['e']); else $e = '';
if (isset($_REQUEST['x'])) $x = intval($_REQUEST['x']); else $x = '';
if (isset($_REQUEST['y'])) $y = intval($_REQUEST['y']); else $y = '';
if (isset($_REQUEST['nameloc'])) $nameloc = ekr($_REQUEST['nameloc']); else $nameloc = '';
if (isset($_REQUEST['textloc'])) $textloc = ekr($_REQUEST['textloc']); else $textloc = '';
if (isset($_REQUEST['karta'])) $karta = intval($_REQUEST['karta']); else $karta = '';
if(empty($x) or empty($y) or empty($nameloc) or empty($textloc)) msg2('Какое-то из полей не заполнено',1);
$n = (empty($_POST['n'])) ? 0 : 1;
$s = (empty($_POST['s'])) ? 0 : 1;
$e = (empty($_POST['e'])) ? 0 : 1;
$w = (empty($_POST['w'])) ? 0 : 1;
$karta = (empty($_POST['karta'])) ? 0 : 1;

$q = $db->query("update `loc` set name='{$nameloc}',info='{$textloc}',X={$x},Y={$y},N={$n},E={$e},S={$s},W={$w},map_id={$karta} where id={$id} limit 1;");
header("location: loc.php");
}
fin();
}


if($mod == 'addloc'){
if($f['admin'] < 3 and $f['p_kartograf'] == 0){ msg2('Вы не из администрации или не являетесь картографом',1);
fin();}
if(empty($go)){
$xl = $_REQUEST['x'];
$yl = $_REQUEST['y'];
msg2('Добавить новые локации:<br>

<form action="loc.php?mod=addloc&go=1" method="POST"><br>
Название локации:<br>
<input type="text" name="nameloc"/><br>
Описание локации:<br>
<textarea name="textloc" maxlength=5000 style="width:80%;"></textarea><br/>
X:
<input type="text" name="x" value="'.$xl.'"/><br>
Y:
<input type="text" name="y" value="'.$yl.'"/><br>
<input type="checkbox" name="n" value="1"/> Проход на север<br>
<input type="checkbox" name="e" value="1"/> Проход на восток<br>
<input type="checkbox" name="w" value="1"/> Проход на запад<br>
<input type="checkbox" name="s" value="1"/> Проход на юг<br>
<input type="checkbox" name="karta" value="1"/> Показывать карту на локации<br>
<input type="submit" value="Далее"/></form>');
fin();
}
elseif($go==1){
if (isset($_REQUEST['n'])) $n = intval($_REQUEST['n']); else $n = '';
if (isset($_REQUEST['s'])) $s = intval($_REQUEST['s']); else $s = '';
if (isset($_REQUEST['w'])) $w = intval($_REQUEST['w']); else $w = '';
if (isset($_REQUEST['e'])) $e = intval($_REQUEST['e']); else $e = '';
if (isset($_REQUEST['x'])) $x = intval($_REQUEST['x']); else $x = '';
if (isset($_REQUEST['y'])) $y = intval($_REQUEST['y']); else $y = '';
if (isset($_REQUEST['nameloc'])) $nameloc = ekr($_REQUEST['nameloc']); else $nameloc = '';
if (isset($_REQUEST['textloc'])) $textloc = ekr($_REQUEST['textloc']); else $textloc = '';
if (isset($_REQUEST['karta'])) $karta = intval($_REQUEST['karta']); else $karta = '';


if(empty($x) or empty($y) or empty($nameloc) or empty($textloc)) msg2('Какое-то из полей не заполнено',1);
$n = (empty($_POST['n'])) ? 0 : 1;
$s = (empty($_POST['s'])) ? 0 : 1;
$e = (empty($_POST['e'])) ? 0 : 1;
$w = (empty($_POST['w'])) ? 0 : 1;
$karta = (empty($_POST['karta'])) ? 0 : 1;

$q = $db->query("INSERT INTO `loc` values(0,'{$karta}','{$nameloc}','{$n}','{$s}','{$w}','{$e}','{$x}','{$y}',0,'{$textloc}');");
	$q = $db->query("select * from `loc` where X='{$x}' and Y='{$y}' limit 1;");
	$sucloc = $q->fetch_assoc();
$q = $db->query("update `users` set loc={$sucloc['id']} where id='{$f['id']}' limit 1;");
msg2('Локация добавлена. НЕ ОБНОВЛЯЙТЕ страницу '.$q['id'].'');
knopka2('loc.php', 'Перейти в локацию');
fin();
}
}

echo '<div class="board">';


$rd = mt_rand(999,99999);
$hint2 = md5($rd);
if($f['grafika'] == 1 or $f['grafika'] == 3) echo '<img src="locimg.php" width="120" height="120" style="border: 1px outset black;"/><br/>';

if(!empty($loc['name'])) echo '<small>'.$loc['name'].'</small><br/><br/>';


if(!empty($loc['info']) and $f['strelki'] == 0){ echo '<small>'.$loc['info'].'</small><br/><br/>';
require_once('inc/locs.php');}
if ($f['zapret'] == 0){
if($f['rabota'] >= 0 and $f['rabota'] < $t){
echo '<br><br><form action="loc.php" method="post">';
echo '<input type="hidden" name="hint" value="'.$hint2.'">';
$_SESSION['hint'] = $hint2;

if($f['vybloc'] == 1){
if(!empty($loc['N']) or $f['p_kartograf'] ==1) echo '<input type="submit" value="Идти на север" name="sever"
accesskey="w"
/><br/>';
if(!empty($loc['E']) or $f['p_kartograf'] ==1) echo '<input type="submit" value="Идти на восток" name="vostok"
accesskey="d"
/><br/>';

if(!empty($loc['W']) or $f['p_kartograf'] ==1) echo '<input type="submit" value="Идти на запад" name="zapad"
accesskey="a"
/><br>';

if(!empty($loc['S']) or $f['p_kartograf'] ==1) echo '<input type="submit" value="Идти на юг" name="jug"
accesskey="s"
 <br><br>';
}
if($f['vybloc'] ==2){
echo '<input type="submit" value="Идти на север" name="sever" accesskey="w"';
if(empty($loc['N'])) echo ' disabled="disabled" style="color:gray"';
echo '/><br/>';
echo '<input type="submit" value="Идти на восток" name="vostok" accesskey="d"';
if(empty($loc['E'])) echo ' disabled="disabled" style="color:gray"';
echo '/>';

echo '<input type="submit" value="Идти на запад" name="zapad" accesskey="a"';
if(empty($loc['W'])) echo ' disabled="disabled" style="color:gray"';
echo '/><br>';

echo '<input type="submit" value="Идти на юг" name="jug" accesskey="s"';
if(empty($loc['S'])) echo ' disabled="disabled" style="color:gray"';

echo '/><br><br>';
}
if($f['vybloc'] == 3){
echo '<input type="submit" value="&#8593;" name="sever" accesskey="w"';
if(empty($loc['N'])) echo ' disabled="disabled" style="color:gray"';
echo '/><br/>';
echo '<input type="submit" value="&#8592;" name="zapad" accesskey="a"';
if(empty($loc['W'])) echo ' disabled="disabled" style="color:gray"';
echo '/>';
echo '<input type="submit" style="background:#c3a86b;" value="X" name="get"/>';
echo '<input type="submit" value="&#8594;" name="vostok" accesskey="d"';
if(empty($loc['E'])) echo ' disabled="disabled" style="color:gray"';
echo '/><br/>';
echo '<input type="submit" value="&#8595;" name="jug" accesskey="s"';
if(empty($loc['S'])) echo ' disabled="disabled" style="color:gray"';
echo'<br><br>';
 
 

}


//echo'<input type="submit" style="background:#c3a86b;" value="Портал" name="get"/>';
echo'</form><br/><br/>';
}
else{
knopka2('loc.php?mod=rabotaoff','Прекратить работать');
}
}

if(!empty($loc['info']) and $f['strelki'] == 1) {
require_once('inc/locs.php');
echo '<br><br><small>'.$loc['info'].'</small><br/><br/>';}


if($f['admin'] >2){
if($f['p_kartograf']==0) msg2('<a href="adm.php?mod=kartograf&go=1" accesskey="k">Включить режим картографа</a>');
if($f['p_kartograf']==1){
	msg2('<a href="adm.php?mod=kartograf&go=2" accesskey="k">Выключить режим картографа</a>');
msg2('<details><summary>Меню картографа</summary>
<a href="loc.php?mod=editloc&id='.$f['loc'].'">Редактировать локацию</a><br>
<a href="loc.php?mod=delloc&id='.$f['loc'].'">Удалить локацию</a><br>
<form action="loc.php?mod=jumploc" method="POST">Введите ID локации для телепортации: <input type="text" name="idloc"/><input type="submit" name="прыгнуть"/></form></details>');


if($mod == 'jumploc'){
$idloc = $_POST['idloc'];
$q = $db->query("update `users` set loc={$idloc} where login='{$f['login']}' limit 1;");
header("location: loc.php");
}
}
}
echo"Кто с вами на одной локации:";
echo '</div>';
$timer1 = $t - 300;
$timer2 = $t - 7200;
$q = $db->query("select login,lvl,sex,status,klan from `users` where loc='{$f['loc']}' AND login<>'{$f['login']}' AND (lastdate>'{$timer1}' or (status=1 AND lastdate>'{$timer2}')) order by lvl;");

$count = 0;
if ($q->num_rows == 0) msg2('Рядом никого нет');
else while ($array_onl = $q->fetch_assoc())
	{
	if ($array_onl['sex'] == 1) $color_login = $male;
	else $color_login = $female;
	$count++;
	$str = '';
	$str .= $count.'. <span style="color:'.$color_login.'">'.$array_onl['login'].' ['.$array_onl['lvl'].']</span>';
	if (!empty($array_onl['klan'])) $str .= ' ('.$array_onl['klan'].')';
	if ($array_onl['status'] == 1) $str .= ' [Б]';
	knopka('infa.php?mod=uzinfa&lgn='.$array_onl['login'], $str);
	}
msg('Локация: '.$f['loc'].'');
if($f['chat_loc'] >0){
if(!empty($chat) and !empty($text)) $q = $db->query("insert into `chat_loc` values(0,'{$f['login']}','{$text}',{$f['loc']},{$t});");
msg('Чат локации:');
//$x = $db->query("select * from `chat_loc` where loc={$f['loc']} order by id desc limit 10;");
$x = $db->query("SELECT * FROM (SELECT * FROM `chat_loc` where loc={$f['loc']} ORDER BY id DESC LIMIT {$f['chat_loc']})end ORDER BY id ASC;");
while($c = $x->fetch_assoc()){
		$timemess = Date('H:i, d-m', $c['timemess']);
msg(''.$c['login'].' ('.$timemess.'): '.$c['message'].'');
}
msg(' <form action="loc.php" method="post">
<input type="text" name="text"/> -> <input type="submit" name="chat" value="сказать"/></form>');
}


if($mod=='rabotaoff'){
if(empty($go)){
msg2('Вы действительно хотите прекратить работу?');
knopka2('loc.php?mod=rabotaoff&go=1','Да, ппрервать работу');
}
if($go==1){
$q = $db->query("update `users` set rabota=0 where id={$f['id']} limit 1;");
header("location: loc.php");
}
}
fin();
?>
