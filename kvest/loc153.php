<?php
##############
# 24.12.2014 #
##############

msg2('<b>Домохозяйка</b>');

if (empty($go))
	{
	msg2('
    - Молодой человек, вы случайно не занимаетесь рыбалкой?<br>
    - У моего мужа сегодня праздник, а рыбы для блюда нет, не могли бы вы принести мне 3 налима, 5 лещей и 4 язя, я отблагодарю.');
	knopka('kvest.php?go=1', '- Я принес вам рыбу', 1);
    knopka('loc.php', '- Пойду ловить', 1);
	fin();
    }

if ($items->count_base_item($f['login'], 185) < 3) msg2('У тебя нет налимов', 1);
if ($items->count_base_item($f['login'], 183) < 5) msg2('У тебя нет лещей', 1);
if ($items->count_base_item($f['login'], 186) < 4) msg2('У тебя нет язей', 1);
$items->del_base_item($f['login'], 185, 3);
$items->del_base_item($f['login'], 183, 5);
$items->del_base_item($f['login'], 186, 4);
$exp = mt_rand($f['lvl'] * 24, $f['lvl'] * 43);
addexp($f['id'], $exp);
$q = $db->query("update `users` set slava=slava+2,cu=cu+400 where id='{$f['id']}' limit 1;");
$str = '- Большое спасибо, если бы не вы, то праздник был испорчен!<br/>';
$str .= '- Вот, не большую награду прими.<br/>';
$str .= '<span style="color:red;">[Отдано 3 налима, 5 лещей и 4 язя]</span><br/>';
$str .= '[Получено '.$exp.' опыта и 400 медных монет]';
msg2($str);
knopka('loc.php', 'Вернуться', 1);
fin();
?>
