<?php
##############
# 24.12.2014 #
##############
require_once('inc/top.php'); // оформление
$_SESSION['auth'] = 0; //если 1, то не выводим форму авторизации
//сколько всего пользователей зарегистрировано
$res = $db->query("select count(*) from `users` where autoreg=0;");
$q = $res->fetch_assoc();
$countreg = $q['count(*)'];

if (!empty($_COOKIE['id']) and !empty($_COOKIE['hash']) and !empty($_COOKIE['lgn']))
	{
	$id = $_COOKIE['id'];
	$hash = $_COOKIE['hash'];
	$login = $_COOKIE['lgn'];
	$id = intval($id);
	$login = ekr($login);
	$res = $db->query("select login,pass from `users` where id={$id} and login='{$login}' limit 1;");
	$auth = $res->fetch_assoc();
	if (empty($auth['pass'])) $auth['pass'] = '';
	if ($auth['pass'] == $hash)
		{
		$_SESSION['auth'] = 1;
		}
	else
		{
		unset($_SESSION);
		session_destroy();
		setcookie('id', '', $_SERVER['REQUEST_TIME'] - 3600,'/','');
		setcookie('hash', '', $_SERVER['REQUEST_TIME'] - 3600,'/','');
		setcookie('lgn', '', $_SERVER['REQUEST_TIME'] - 3600,'/','');
		}
	}
if (!empty($_SESSION['auth'])) require_once('inc/check.php');
echo '<div class="verx"><img src="pic/logo.png" alt=""/></div>';
if (!empty($_SESSION['auth']))
	{
	header("location: loc.php");
	fin();
	}
require_once("inc/head.php");
msg2('
Окунись в неизведанный мир Вальдирры! Убивай монстров, проходи квесты, экипируйся в лучшую броню, создай свой клан, найди свое место в этом мире. Докажи верность Императору и благославение сне зайдет на тебя!<br>
Сейчас в игре <script src="./online.php?usr=true"></script>');
echo '<div class="board">';
echo '<form action="start.php?auth" method="POST">
Логин:<br><input type="text" name="login" placeholder="Логин"/><br/>
Пароль:<br><input type="password" name="pass" placeholder="Пароль"/><br/>
<input type="submit" value="Войти"/></form>
</div>';


if ($settings['reg'] == 1)
	{
	knopka('start.php?start', 'Начать новую игру');
	}
knopka('pass.php', 'Восстановление пароля');
//knopka('news.php', DateNews());
//knopka('lib.php', 'Библиотека');
echo '<div class="board2"><small>Зарегистрировано '.$countreg.' игроков</small></div><br>';



echo'<script type="text/javascript" src="//yastatic.net/share/share.js" charset="utf-8"></script><div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="none"></div>';
fin();
?>
