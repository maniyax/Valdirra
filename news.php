<?php
##############
# 24.12.2014 #
##############
require_once('inc/top.php');	// вывод на экран
require_once('inc/head.php');
if(!empty($_SESSION['auth'])) require_once('inc/hpstring.php');
$start = isset($_REQUEST['start']) ? intval($_REQUEST['start']) : 0;
$q = $db->query("select count(*) from `news`;");
$a = $q->fetch_assoc();
$numb = $a['count(*)'];
if(!empty($_SESSION['auth'])) $q = $db->query("update `users` set newsdate=0 where id={$f['id']} limit 1;");
if($numb <= 0) msg2('Новостей нет', 1);
$col = 5;	// новостей на странице
if($start > intval($numb / $col)) $start = intval($numb / $col);
if($start < 0) $start = 0;
$limit = $start * $col;
$count = $limit;
$q = $db->query("select * from `news` order by id desc limit {$limit},{$col};");
while($news = $q->fetch_assoc())
	{
	echo '<div class="board">Тема: <b><span style="color:#000000">'.$news['title'].'</span></b><br/>';
	echo 'Добавил: <b>'.$news['login'].'</b> ('.Date('d.m.Y H:i', $news['datenews']).')</div></div>';
	echo '<div class="board2" style="text-align:left">';
if($f['grafika'] == 1 or $f['grafika'] == 2) $news['text'] = smile($news['text']);
	echo nl2br(link_it($news['text']));
	echo '</div>';
	$count++;
	}
echo '<div class="board">';
if($start > 0) echo '<a href="news.php?start='.($start-1).'" class="navig">Назад</a>'; else echo '<a href="#" class="navig"> Назад</a>';
echo ' | ';
if($limit + $col < $numb) echo '<a href="news.php?start='.($start+1).'" class="navig">Вперед</a>'; else echo ' <a href="#" class="navig"> Вперед</a>';
echo '</div>';
fin();
?>
