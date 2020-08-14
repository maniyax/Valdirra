<?php

##############
# 29.07.2014 #
##############
/*require_once('inc/smsru.php'); // операции с смс
$api_id = '82c943da-6b89-d644-392f-43e9c79ecb5f'; // авторизация,дают при регистрации в sms.ru
$sms = new \Zelenin\smsru($api_id); // вызов класса в переменную $sms
$phone = '79231237203'; // номер без плюса
if (!empty($mess))
	{
	$mess = str_replace('<br/>', '
	', $mess);
	$mess = mb_substr($mess, 0, 60, 'UTF-8');
	$s = $sms->sms_send($phone, $mess); // сама отправка сообщения
	}*/
$from = 'noreply@valdirra.ru';
mail_utf8('admin@b-g-group.ru', 'Вальдирра', $mess, $from);
?>
