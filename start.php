<?php
##############
# 24.12.2014 #
##############

$title = 'Работа с аккаунтом';
require_once('inc/top.php'); // оформление

if(!empty($_SESSION['auth'])) require_once('inc/check.php');
require_once('inc/head.php');
Require_once('class/items.php');
$login = isset($_REQUEST['login']) ? $_REQUEST['login'] : '';
$pass = isset($_REQUEST['pass']) ? $_REQUEST['pass'] : '';
$ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? ekr($_SERVER['HTTP_X_FORWARDED_FOR']) : ekr($_SERVER['REMOTE_ADDR']);
$soft = isset($_SERVER['HTTP_USER_AGENT']) ? ekr($_SERVER['HTTP_USER_AGENT']) : '';
$host = isset($_SERVER['HTTP_X_OPERAMINI_PHONE']) ? ekr($_SERVER['HTTP_X_OPERAMINI_PHONE']) : '';

if(isset($_REQUEST['auth']))
	{ // авторизация
	if(!empty($_SESSION['auth'])) msg('Вы уже авторизованы.<br/><a href="loc.php">В игру</a>', 1);

	if(empty($login) or preg_match("/[^a-zA-Z0-9_а-яА-ЯёЁ]/u", $login) or empty($pass) or preg_match("/[^a-zA-Z0-9]/", $pass))
		{
		msg2('Неверный логин или пароль!');
		knopka('index.php','На главную');
		fin();
		}

	$q = $db->query("select pass,id,login,soft,host,ip from `users` where login='{$login}' limit 1;");
	if($q->num_rows == 0)
		{
		msg2('Такой логин не зарегистрирован!');
		knopka('index.php','На главную');
		fin();
		}
	$f = $q->fetch_assoc();
	if($f['pass'] != md5($pass))
		{
		msg2('Неверный пароль!');
		knopka('index.php','На главную');
		fin();
		}

	setcookie('id', '', $t - 3600, '/', '');
	setcookie('hash', '', $t - 3600, '/','');
	setcookie('lgn', '', $t - 3600, '/','');
	setcookie('id', $f['id'], $t + 86400 * 365, '/','');
	setcookie('hash', md5($pass), $t + 86400 * 365, '/','');
	setcookie('lgn', $login, $t + 86400 * 365, '/','');
	if ($f['soft'] != $soft or $f['host'] != $host or $f['ip'] != $ip)
		{
		$q = $db->query("insert into `ipsoft` values(0,'{$f['login']}','{$t}','{$ip}','{$host}','{$soft}','".session_id()."');");
		$q = $db->query("update `users` set soft='{$soft}',host='{$host}',ip='{$ip}',session='".session_id()."' where login='{$f['login']}' limit 1;");
		}
	$_SESSION['auth'] = 1;
	header("location: loc.php");
	fin();
	}

if(isset($_REQUEST['exit']) and !empty($_SESSION['auth']))
	{ // выход
	if(empty($_SESSION['auth'])) msg('Вы не авторизованы.<br/><a href="index.php">На главную</a>', 1);
	if(empty($_REQUEST['ok']))
		{
		msg2('Вы действительно хотите выйти из игры?');
		knopka('start.php?exit&ok=1', 'Выйти из игры');
		knopka('javascript:history.go(-1)', 'Вернуться');
		fin();
		}
setcookie('id', '', $t - 3600, '/', '');
	setcookie('hash', '', $t - 3600, '/','');
	setcookie('lgn', '', $t - 3600, '/','');
	session_destroy();
	unset($_COOKIE);
	unset($_SESSION);
	msg2('Вы успешно покинули игру!');
	knopka('index.php', 'На главную');
	fin();
	}

if(isset($_REQUEST['start']))
	{ // регистрация
	if(!empty($_SESSION['auth'])) msg('Вы уже авторизованы.<br/><a href="loc.php">В игру</a>', 1);
	if (empty($settings['reg']))
		{
		msg2('Регистрация временно отключена');
		knopka('index.php', 'На главную');
		fin();
		}
	$r = isset($_REQUEST['r']) ? intval($_REQUEST['r']) : 1;			// это рефералы, кто пришел по mirtania.tiflohelp.ru/start.php?start&r=12345
	$q = $db->query("select id from `users` where id={$r} limit 1;");	// найдем в базе такой ид реферала
	if ($q->num_rows == 0) $r = 1;										// если нет, назначим системного перса :)
	$pass = generatePassword();	// создадим пароль
	$md5pass = md5($pass);		// хеш
	$ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? ekr($_SERVER['HTTP_X_FORWARDED_FOR']) : ekr($_SERVER['REMOTE_ADDR']); // адрес прокси если есть. иначе просто ИП
	$q = $db->query("select regdate from `users` where ip='{$ip}' and regdate>'{$t}'-3600 order by regdate desc limit 1;"); // найдем регались ли в течении часа с этим ИП
//	if($q->num_rows > 0) msg2('С вашего IP адреса уже была регистрация в течении ближайшего часа, повторите позже.', 1);
	$soft = isset($_SERVER['HTTP_USER_AGENT']) ? ekr($_SERVER['HTTP_USER_AGENT']) : ''; // юзер агент. у php скриптов (типа автокача) часто пустой!
	if(empty($soft)) msg2('Можно регистрироваться только с реальных браузеров, регистрация с помощью скриптов запрещена!',1);
	$host = isset($_SERVER['HTTP_X_OPERAMINI_PHONE']) ? ekr($_SERVER['HTTP_X_OPERAMINI_PHONE']) : ''; // иногда содержит модель телефона
	$q = $db->query("insert into `users` (id,login,pass,sex,ip,soft,host,regdate,lastdate,autoreg,ref,hpnow,hpmax,chatdate,cu,sila,lovka,inta,intel,zdor) values(0,'','{$md5pass}',1,'{$ip}','{$soft}','{$host}','{$t}','{$t}','1','{$r}',10,10,'{$t}',100,3,3,3,1,1);");
	$id = $db->insert_id();
	$login = 'Путник_'.$id; // временный логин
	$q = $db->query("update `users` set login='{$login}' where id={$id} limit 1;");
	$q = $db->query("insert into `ipsoft` values(0,'{$login}','{$t}','{$ip}','{$host}','{$soft}','".session_id()."');"); // сохраним данные в лог заходов
	$iid = $items->add_item($login, 195, 1);
	$items->equip_item($login, $iid);
	$iid = $items->add_item($login, 196, 1);
	$items->equip_item($login, $iid);
	$iid = $items->add_item($login, 197, 1);
	$items->equip_item($login, $iid);
	$iid = $items->add_item($login, 198, 1);
	$items->equip_item($login, $iid);
	$iid = $items->add_item($login, 199, 1);
	$items->equip_item($login, $iid);
	$q = $db->query("select * from `users` where id='{$id}' limit 1;");
	$f = $q->fetch_assoc();
	$f = calcparam($f);
//	удалим куки на всякий случай
setcookie('id', '', $t - 3600, '/', '');
	setcookie('hash', '', $t - 3600, '/','');
	setcookie('lgn', '', $t - 3600, '/','');
	// установим новые на 365 дней
	setcookie('id', $id, $t + 86400 * 365, '/','');
	setcookie('hash', md5($pass), $t + 86400 * 365, '/','');
	setcookie('lgn', $login, $t + 86400 * 365, '/','');
	header("location: loc.php");
	fin();
	}
// тут сделать восстановление пароля
if(!empty($_SESSION['auth']))
	{
	if(empty($_SESSION['auth'])) msg('Вы не авторизованы', 1);
	if ($f['autoreg'] == 0) msg2('Вы уже сохранили своего персонажа раньше.<br/><a href="loc.php">В игру</a>', 1);

	// определение переменных для register globalls
	$go = isset($_REQUEST['go']) ? $_REQUEST['go'] : 0;
	$pass2 = isset($_REQUEST['pass2']) ? $_REQUEST['pass2'] : '';
	$name = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';
	$email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
	$pol = isset($_REQUEST['pol']) ? $_REQUEST['pol'] : 0;

	if (empty($go))
		{
		msg2('<b>Внимание!<br />Все поля обязательны для заполнения</b>!');
		echo '<div class="board"><br /><center><form action="start.php?go=1" method="POST">
		Введите логин:<br><input type="text" placeholder="Введите Логин" name="login" size="20" maxlength="30"><br/>
		Введите пароль<br><input type="password" placeholder="Введите Пароль" name="pass" size="20" maxlength="30"><br/>
		Повторите пароль:<br><input type="password" placeholder="Повторите Пароль" name="pass2" size="20" maxlength="30"><br/>
		Введите e-mail (Нужен для востановления пароля):<br><input type="email" name="email" placeholder="Введите E-Mail" size="20" maxlength="40"><br/>
		Пол:<br><select name="pol">
			<option value="1">Мужской</option>
			<option value="2">Женский</option>
		</select><br/>
Регистрируясь в игре вы подтверждаете, что вы ознакомились с <a href="lib.php?mod=rule">правилами</a> и согласны с ними.<br>
		<input type="submit" value="Готово"></form></center></div>';
		knopka('javascript:history.go(-1)', 'Вернуться');
		fin();
		}

	// проверки
	$err = '';
	if (30 < mb_strlen($login, 'UTF-8') or mb_strlen($login, 'UTF-8') < 2) $err .= 'Логин должен быть от 2 до 30 символов!<br/><br/>';
	if (mb_substr(strtolower($login), 0, 7, 'UTF-8') == 'Путник_') $err .= 'Логин не может начинаться на "Путник_"!<br/><br/>';
	if (20 < mb_strlen($pass, 'UTF-8') or mb_strlen($pass, 'UTF-8') < 9) $err .= 'Пароль должен быть от 9 до 20 символов!<br/><br/>';
	if ($pass != $pass2) $err .= 'Пароли не совпадают!<br/><br/>';
	if (empty($login) or preg_match("/[^a-zA-Z0-9_а-яА-ЯёЁ]/u", $login)) $err .= 'Неверно набран логин. Допустимые символы <span style="color:darkgreen">а-Я a-Z 0-9 _</span><br/><br/>';
	if (empty($pass) or preg_match("/[^a-zA-Z0-9-_]/", $pass)) $err .= 'Неверно набран пароль. Допустимые символы <span style="color:darkgreen">a-Z 0-9</span><br/><br/>';
	if (!preg_match("/^[a-z0-9]+([-_\.]?[a-z0-9])+@[a-z0-9]+([-_\.]?[a-z0-9])+\.[a-z]{2,4}/i", $email) or 40 < mb_strlen($email, 'UTF-8')) $err .= 'Неверно набран e-mail. Пример: <span style="color:darkgreen"><b>admin@hmr.su</b></span><br/><br/>';

	// если есть хоть одна ошибка, вывести на экран
	if (!empty($err))
		{
		msg2('<span style="color:red"><b>'.$err.'</b></span>');
		knopka('javascript:history.go(-1)', 'Вернуться');
		fin();
		}

	if ($pol != 1 and $pol != 2) $pol = 1;

	$q = $db->query("select email from `users` where email='{$email}' limit 1;");
	if ($q->num_rows > 0)
		{
		msg2('<span style="color:red"><b>Такой адрес email уже есть в базе!</b></span>');
		knopka('javascript:history.go(-1)', 'Вернуться');
		fin();
		}
	$q = $db->query("select login from `users` where login='{$login}' limit 1;");
	if ($q->num_rows > 0)
		{
		msg2('<span style="color:red"><b>Логин '.$login.' уже занят. Выберите другой.</b></span>');
		knopka('javascript:history.go(-1)', 'Вернуться');
		fin();
		}

	$md5pass = md5($pass);
	$q = $db->query("update `chat` set login='{$login}',sex='{$pol}' WHERE login='{$f['login']}';");
	$q = $db->query("update `chat` set privat='{$login}' WHERE privat='{$f['login']}';");
	$q = $db->query("update `combat` set login='{$login}' WHERE login='{$f['login']}';");
	$q = $db->query("update `forum_comm` set login='{$login}' WHERE login='{$f['login']}';");
	$q = $db->query("update `forum_topic` set login='{$login}' WHERE login='{$f['login']}';");
	$q = $db->query("update `invent` set login='{$login}' WHERE login='{$f['login']}';");
	$q = $db->query("update `invent` set arenda_login='{$login}' WHERE arenda_login='{$f['login']}';");
	$q = $db->query("update `ipsoft` set login='{$login}' WHERE login='{$f['login']}';");
	$q = $db->query("update `letters` set login='{$login}' WHERE login='{$f['login']}';");
	$q = $db->query("update `letters` set login_from='{$login}' WHERE login_from='{$f['login']}';");
	$q = $db->query("update `log_peredach` set login='{$login}' WHERE login='{$f['login']}';");
	$q = $db->query("update `log_peredach` set login_per='{$login}' WHERE login_per='{$f['login']}';");
	$q = $db->query("update `magic` set login='{$login}' WHERE login='{$f['login']}';");
	$q = $db->query("update `users` set login='{$login}', email='{$email}', pass='{$md5pass}', sex='{$pol}',autoreg=0 WHERE id={$f['id']} limit 1;");
	$mess = '['.date('H:i:s').']
	Зарегистрирован новый пользователь.
	Его логин: '.$login; // UTF-8 only!!!
	require_once('inc/i.php'); // опопвещение по смс
	$qq = $db->query("insert into `letters` values(0,0,'{$t}','{$admin}','{$settings['bot']}','{$mess}',0,0);");
	unset($_SESSION);
	session_destroy();
setcookie('id', '', $t - 3600, '/', '');
	setcookie('hash', '', $t - 3600, '/','');
	setcookie('lgn', '', $t - 3600, '/','');
	header('location: start.php?auth&login='.$login.'&pass='.$pass);
	fin();
	}

function generatePassword($length = 4, $strength = 4)
	{
	$vowels = 'aeuy';
	$consonants = 'bdghjmnpqrstvz';
	if ($strength >= 1) $consonants .= 'BDGHJLMNPQRSTVWXZ';
	if ($strength >= 2) $vowels .= "AEUY";
	if ($strength >= 4) $consonants .= '23456789';
	if ($strength >= 8) $vowels .= '@#$%';
	// Генерируем пароль
	$password = '';
	$alt = $_SERVER['REQUEST_TIME'] % 2;
	for ($i = 0; $i < $length; $i++)
		{
		if ($alt == 1)
			{
			$password .= $consonants[(rand() % strlen($consonants))];
			$alt = 0;
			}
		else
			{
			$password .= $vowels[(rand() % strlen($vowels))];
			$alt = 1;
			}
		}
	return $password;
	}
fin();
?>
