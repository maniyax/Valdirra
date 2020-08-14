<?php
##############
# 24.12.2014 #
##############

$url = substr($_SERVER['QUERY_STRING'],4);
if(isset($_REQUEST['go'])) $go = $_REQUEST['go']; else $go = 0;
if (empty($go))
	{
    require_once('inc/top.php');	// оформление
	if(!empty($_SESSION['auth'])) require_once('inc/check.php');
	require_once("inc/head.php");
	if(!empty($_SESSION['auth'])) require_once('inc/hpstring.php');
	msg('ВНИМАНИЕ!<br/>Вы собираетесь покинуть сайт и перейти по внешней ссылке:<br/>
	<span style="color:red">'.$url.'</span><br/><br/>
	Администрация нашего ресурса не несёт ответственности за контент постороннего сайта.<br/>
	Рекомендуется не указывать ваши данные, имеющие отношение к <span style="color:red">http://'.$_SERVER['SERVER_NAME'].'</span> (имя пользователя, пароль), на сторонних сайтах.<br/>');
    echo '<div class="board">';
	echo '<form action="out.php?url='.$url.'" method="POST">';
	echo '<input type="submit" name="go" value="Перейти по ссылке"/></form></div>';
	knopka('javascript:history.go(-1)','Вернуться', 1);
    fin();
	}
header('Location: '.$url);
?>
