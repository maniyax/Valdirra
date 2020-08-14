<?php
/*
module name = Text Captcha
module version = 2.0
module description = module for generate text captcha
module author = Jawhien<jawhien@gmail.com>
*/

class captcha {

function show()
{
$c1 = rand(1,9);
$c2 = rand(1,9);
$value = $c1 + $c2;
$value = md5($value);
echo <<<captcha
<label for="captcha-fild">Сколько будет $c1 + $c2?</label>
<input id="captcha-fild" type="text" name="c1">
<input type="hidden" name="c2" value="$value">
captcha;
}



}
?>