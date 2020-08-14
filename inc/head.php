<?php
##############
# 24.12.2014 #
##############

echo '<div class="head">';
echo '<center><b>'.date('H:i:s').'</b>';
if(!empty($_SESSION['auth']))
	{
	require_once('inc/check.php');

	if($f['status'] == 1 and $_SERVER['PHP_SELF'] != '/battle.php') echo ' <a href="battle.php"><img src="pic/boi.png" value="*"/></a>';
	echo '</center></div>';
if($f['status'] ==0){
msg('HP: <b><span style="color:'.$hpcolor.'">'.$f['hpnow'].'</span></b>/<b><span style="color:'.$hpcolor.'">'.$f['hpmax'].'</span></b> | 
MP: <b><span style="color:'.$manacolor.'">'.$f['mananow'].'</span></b>/<b><span style="color:'.$manacolor.'">'.$f['manamax'].'</span></b><br>');

}

	if(!empty($count_pm) and $_SERVER['PHP_SELF'] != '/pm.php') echo ' <a href="pm.php"><img src="pic/newletter.gif" alt="'.$count_pm.' новых писем"/></a>';
	if(!empty($f['newsdate']) and $_SERVER['PHP_SELF'] != '/news.php') knopka('news.php', '<span style="color:'.$logincolor.'">Свежие новости!</span>');
	if(!empty($f['autoreg']) and !substr_count($_SERVER['PHP_SELF'], '/start.php')) knopka('start.php?reg', '<b>Сохранить персонажа</b>');
	}
else
	{
	if($_SERVER['PHP_SELF'] != '/index.php') echo '<span style="float:right;"><a href="index.php">[Главная]</a>';
	echo '</center></div>';
	}

if(!isset($f['login'])) $f['login'] = '';
if(!isset($f['admin'])) $f['admin'] = 0;
if(!empty($settings['mess']) and !empty($f['login']) and $admin != $f['login']) msg2($settings['mess'],1);




?>
