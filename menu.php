<?php
##############
# 24.12.2014 #
##############

require_once('inc/top.php');	// вывод на экран
require_once('inc/check.php');	// вход в игру
require_once('inc/head.php');

if(!empty($_SESSION['auth'])) require_once('inc/hpstring.php');
knopka('news.php', '<b>'.DateNews().'</b>', 1);
$q = $db->query("select count(*) from `forum_topic`;");
$a = $q->fetch_assoc();
$q = $db->query("select count(*) from `forum_comm`;");
$b = $q->fetch_assoc();
knopka('forum.php', 'Форум <b>('.$a['count(*)'].'/'.$b['count(*)'].')</b>', 1);
$timer = $t - 300;
$q = $db->query("select count(*) from `users` where chatdate>'{$timer}';");
$a = $q->fetch_assoc();
$timer1 = $t - 900;
$timer2 = $t - 7200;
$q = $db->query("select count(id) from `users` where (lastdate>'{$timer1}' or (status=1 AND lastdate>'{$timer2}'));");
$b = $q->fetch_assoc();
knopka('chat.php', 'Чат <b>('.$a['count(*)'].')</b>', 1);
knopka('pm.php', 'Почта <b>('.$count_pm.')</b>',1);

knopka('infa.php?mod=onl', 'Онлайн <b>('.$b['count(id)'].')</b>', 1);
if(!empty($f['klan']) and $f['klan_status'] > 0) knopka('klan.php', 'Управление кланом', 1);
knopka('infa.php?mod=klans', 'Кланы', 1);

knopka('infa.php', 'Поиск игроков', 1);

knopka('inv.php', 'Рюкзак', 1);
knopka('inv.php?mod=equip', 'Экипировка', 1);
knopka('infa.php?mod=boi', 'Текущие бои', 1);

if(1 <= $f['admin']) knopka('adm.php', 'Админка', 1);
if(3 <= $f['admin']) knopka('adm.php?mod=res', 'Восстановление', 1);

fin();
 ?>
