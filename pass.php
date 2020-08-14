<?php
##############
# 24.12.2014 #
##############

require_once('inc/top.php');		// вывод на экран
require_once('inc/head.php');
// удалим старые записи (годны только 1 сутки)
$timer = $t - 86400;
$q = $db->query("delete from `recpass` where daterec<'{$timer}';");

// определение переменных для обхода register globalls
$go = isset($_REQUEST['go']) ? $_REQUEST['go'] : 0;
$ok = isset($_REQUEST['ok']) ? $_REQUEST['ok'] : 0;
$email = isset($_REQUEST['email'])? $_REQUEST['email'] : '';
$keystring = isset($_REQUEST['keystring']) ? $_REQUEST['keystring'] : '';
$email1 = isset($_REQUEST['email1']) ? $_REQUEST['email1'] : '';
$code1 = isset($_REQUEST['code1']) ? $_REQUEST['code1'] : '';
$c1 = isset($_REQUEST['c1']) ? $_REQUEST['c1'] : '';
$c2 = isset($_REQUEST['c2']) ? $_REQUEST['c2'] : '';
$c1 = md5($c1);

switch($go):
case 1:
	if(empty($ok))
		{
		echo '<div class="board" style="text-align:left;">';
		echo '<form method="POST" action="pass.php?go=1&ok=1">
<b>Введите ваш адрес e-mail:</b><br/><input type="text" name="email" size="20" maxlength="40"><br/><br>';
			require_once "class/captcha.php";
			$ca = new captcha();
			$ca->show();

echo'<input type="submit" value="Восстановить пароль"></form>';
		echo '</div>';
		knopka('pass.php?go=2', 'Подтвердить код', 1);
		knopka('index.php', 'На главную', 1);
		fin();
		}
		if(!isset($c2) or $c1 != $c2)
		{
		msg2('Вы ввели неверный код с картинки!');
		knopka('javascript:history.go(-1)', 'Назад', 1);
		fin();
		}
	if(!preg_match("/^[a-z0-9]+([-_\.]?[a-z0-9])+@[a-z0-9]+([-_\.]?[a-z0-9])+\.[a-z]{2,4}/i", $email) or 40 < mb_strlen($email, 'UTF-8'))
		{
		msg2('Неверно набран e-mail. Пример: <span style="color:'.$female.'"><b>admin@hmr.su</b></span>');
		knopka('javascript:history.go(-1)', 'Назад', 1);
		fin();
		}
	$q = $db->query("select * from `users` where email='{$email}' limit 1;");
	if($q->num_rows == 0) msg('Такой адрес email не зарегистрирован!',1);
	$q = $db->query("delete from `recpass` where email='{$email}' limit 1;");
	$cod = base64_encode(mt_rand(0000, 9999)) . mt_rand(0000, 9999);
	$cod = preg_replace('/[^A-z0-9]/', '', $cod);
	$subj = 'Восстановление пароля в игре '.$title.'!';
	$mess = 'Для восстановления пароля зайдите на главную страницу игры, выберите ссылку "Восстановление пароля", далее выберите "Подтвердить код"<br/>
	Ваш код: '.$cod;
    $from = 'no_reply@'.$_SERVER['SERVER_NAME'];
	$q = $db->query("insert into `recpass` values(0, '{$email}', '{$cod}', '{$t}');");
	mail_utf8($email, $subj, $mess, $from);
	msg2('На ваш e-mail отправлен код подтверждения.');
	knopka('pass.php?go=2', 'Подтвердить код', 1);
	knopka('index.php', 'На главную', 1);
	fin();
break;

case 2:
	if(empty($ok))
		{
		echo '<form method="POST" action="pass.php?go=2&ok=1">
		<b>Введите ваш адрес e-mail:</b><br/><input type="text" name="email1" size="20" maxlength="40"><br/>
		Введите код, высланный вам на e-mail:<br/><input type="text" name="code1"><br/>
		<input type="submit" value="Сгенерировать пароль"></form>';
		knopka('index.php', 'На главную', 1);
		fin();
		}
	if(!preg_match("/^[a-z0-9]+([-_\.]?[a-z0-9])+@[a-z0-9]+([-_\.]?[a-z0-9])+\.[a-z]{2,4}/i", $email1) or 40 < mb_strlen($email1, 'UTF-8'))
		{
		msg2('Неверно набран e-mail. Пример: <span style="color:'.$female.'"><b>admin@hmr.su</b></span>');
		knopka('javascript:history.go(-1)', 'Назад', 1);
		fin();
		}
	$q = $db->query("select * from `users` where email='{$email1}' limit 1;");
	if($q->num_rows == 0) msg2('Такой адрес email не зарегистрирован!', 1);

	$q = $db->query("select * from `recpass` where email='{$email1}' limit 1;");
	if($q->num_rows == 0) msg2('Для этого e-mail не был заказан код восстановления!', 1);
	$recpass = $q->fetch_assoc();
	if ($recpass['code'] != $code1) msg('Код подтверждения не верен!', 1);
	$code = base64_encode(mt_rand(0000, 9999)) . mt_rand(0000, 9999);
	$code = preg_replace('/[^A-z0-9]/', '', $code);
	$md5pass = md5($code);
	$q = $db->query("update `users` set pass='{$md5pass}' where email='{$email1}' limit 1;");
	$q = $db->query("delete from `recpass` where email='{$email1}' limit 1;");
	$subj = 'Восстановление пароля в игре «'.$title.'»';
	$mess = 'Для вашего персонажа в игре '.$title.' сгенерирован новый пароль : '.$code;
	$from = 'noreply@'.$_SERVER['SERVER_NAME'];
	mail_utf8($email1, $subj, $mess, $from);
	msg2('На ваш e-mail отправлено письмо с новым паролем!');
	knopka('index.php', 'На главную', 1);
	fin();
break;

default:
	msg2('Если вы хотите заказать код для восстановления пароля, вам <a href="pass.php?go=1" class="navig">сюда</a>. Если вы хотите ввести код подтверждения, нажмите <a href="pass.php?go=2" class="navig">здесь</a>');
break;
endswitch;
fin();
?>
