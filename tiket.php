<?php
require_once('inc/top.php'); // вывод на экран
require_once('inc/check.php'); // вход в игру
require_once('inc/head.php');
echo '<div class="board" style="text-align:left">';
if(empty($_REQUEST['mod'])) $_REQUEST['mod'] = '';
switch($_REQUEST['mod'])
	{
	default:
	echo"Служба поддержки Вальдирры<br/>";
	echo"Вы можете задать интересующий вас вопрос в службу поддержки.
	Многие вопросы можно решить задав их игрокам в чате/форуме.<br>
Технический администратор - главный администратор игры. писать только в крайнем случае.<br>
Гейм мастер - главный модератор игры, писать по вопросам блоков, действий модераторов и т.п.<br>
Игровой администратор - администратор по работе с игроками, писать по игровым вопросам.<br><br>

";
	
	echo'<form action="tiket.php?mod=create" method="post"><br />Тема:<br />
		<select size="1" name="tema"><option value="1">Выберите направление вопроса</option>
	<option value="2">Предложения</option>
	<option value="3">Ошибки, баги</option>
	<option value="4">Ошибки оплаты</option>
	<option value="5">Нарушители, мошенничество</option>
	<option value="6">Другое</option></select><br />
	Отдел:</br>
	<select size="1" name="admin"><option value="1">Выберите технический отдел</option>
	<option value="2">Технический администратор</option>
	<option value="3">Гейм мастер [Отсутствует]</option>
<option value="4">Агент технической поддержки</option></select><br/>

	Сообщение:<br /><textarea name="text" rows=7 cols=18 wrap="off"></textarea><br /><input type="submit" value="Задать вопрос" /></form>';
	fin();
	break; 


	case 'create':
	if(empty($_REQUEST['text'])){echo"Введите текст сообщения! </br><a href=\"tiket.php?\">Назад</a>";break;}
	if(empty($_REQUEST['tema']) or $_REQUEST['tema'] == 1){echo"Не выбрана тема! </br><a href=\"tiket.php?\">Назад</a>";break;}
	elseif($_REQUEST['tema'] == 2){$tema="Предложения";}
	elseif($_REQUEST['tema'] == 3){$tema="Ошибки, баги";}
	elseif($_REQUEST['tema'] == 4){$tema="Ошибки оплаты";}
	elseif($_REQUEST['tema'] == 5){$tema="Нарушители, мошенничество";}
	elseif($_REQUEST['tema'] == 6){$tema="Другое";}
	if(empty($_REQUEST['admin']) or $_REQUEST['admin'] == 1){echo"Не выбран отдел! </br><a href=\"tiket.php?\">Назад</a>";break;}
	if($_REQUEST['admin'] == 2){$admin="maniyax";}
	if($_REQUEST['admin'] == 3){$admin="karain";}
if($_REQUEST['admin'] == 4){$admin="Eldargo";}
//if($_REQUEST['em'] == 0){$em=0;}
//if($_REQUEST['em'] == 1){$em=1;}
	$texts = ekr($_REQUEST['text']);
	$text = "<br>*Тикет*<br>Тема: *".$tema."*<br>Сообщение: ".$texts;
	$q = $db->query("INSERT INTO `letters` SET login_from='{$f['login']}',login='{$admin}',timemess={$t},read_flag=0,mess='{$text}'");
$r = mt_rand(11111, 9999999999);
/*$text = 'Тикет из Вальдирры<br><br>
Тема: '.$tema.'
Игрок: '.$f['login'].'

Обращение: <br>
'.$texts.'';*/
//$to = 'support@';
//$from = ''.$f['email'].'';
//$tema = 'ticket_valdirra_'.$r.'';
//if($em == 1) { mail_utf8($to, $tema, $text, $from);}
	echo" Тикет успешно отправлен, он будет рассмотрен в течение 5 часов! </br><a href=\"index.php?\">На главную</a>";
	fin();
	break;
	}
//require_once('inc/func.php'); // оформление
?>