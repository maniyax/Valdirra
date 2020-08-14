<?php
require_once('inc/top.php'); // вывод на экран
require_once('inc/check.php'); // вход в игру
require_once('inc/head.php');

msg2('На данной странице вы можете оплатить руду с WM кошельков.<br>
После перевода вами денег на счет администрации Вальдирры может пройти какое-то время, до 24 часов, ибо зачисление руды происходит в ручном режиме.');
msg2('Обязательно сообщите игроку maniyax через функцию e-mail сообщений в профиле о платеже, не забыв указать WM-кошелек, с которого производилась оплата.');
msg2('
<form action="https://merchant.webmoney.ru/lmi/payment.asp" method="POST">
Сумма:<br>
 <input type="text" name="LMI_PAYMENT_AMOUNT" value="1.00"><br>
 <input type="hidden" name="LMI_PAYMENT_DESC_BASE64" value="0KDRg9C00LA=">
Валюта:<br>
<select name="LMI_PAYEE_PURSE">
			<option value="Z325265043749">WMZ</option>
<option value="U427343410113">WMU</option>
<option value="R320169238501">WMR</option>
			</select><br/>
<input type="submit" value="Оплатить"/>
</form>',1);
fin();
?>

