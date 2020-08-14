<?php
###########################
# 15.01.2016 StalkerSleem #
###########################

if ($f['hpnow'] <= 0){knopka('loc.php', 'Восстановите здоровье', 1);fin();}

msg2('<b>Кузнец Дорин</b>');

if (empty($mod)){
	 $slova = array();
	 $slova[] = 'Уважительно кивнул головой';
	 $slova[] = 'Сгромаздя молот на плечо';
	 $slova[] = 'Опёрся на накавальню';
	 shuffle($slova);
	 msg2('- Приветствую '.$f['login'].', чем могу быть любезен?<br/>*'.$slova[0].'*');
	 knopka('kvest1.php?mod=1', '- Хотел бы стать кузнецом', 1);
	 knopka('kvest1.php?mod=4', '- Есть ли какая нибудь работа?', 1);
	 knopka('loc.php', '- Ни чем, просто заглянул', 1);
     fin();}
	
elseif ($mod == 1){
	 if($f['profs'] >= $f['profsmax'])
     {msg2('- Друг мой, да ты мастер на все руки!<br/>
     [Лимит профессий исчерпан]',1);
     fin();}
	 
	 if($f['p_kuznec'] >1){if(mt_rand(1, 100) <= 45) 
	 msg2('- Мне больше не чему тебя научить.', 1);
     else msg2('- Ты уже мастер кузнечного дела!', 1);
	 fin();}

	 msg2('- Кузнецом? Почётное и не лёгкое ремесло. Хорошо, обучу, но... Докажи, что ты достоен!<br/>
     - Принеси мне 10 кусков железной руды и 10 углей.');
	 knopka('kvest1.php?mod=3', '- Но где мне это всё взять?!',1);
     knopka('kvest1.php?mod=2', '- Мастер Дорин, я принёс всё, что требовали',1);
     knopka('loc.php', '- Обязательно докажу!',1);
     fin();}
     
elseif ($mod == 2){
     if($items->count_base_item($f['login'], 729) <10) msg2('- Но ведь здесь нет 10 кусков железной руды!',1);
     if($items->count_base_item($f['login'], 728) <10) msg2('- Тут не собирётся 10 углей, не обманывай меня!',1);
     $items->del_base_item($f['login'], 729, 10);
     $items->del_base_item($f['login'], 728, 10);
     $q=$db->query("update `users` set p_kuznec=1,profs=profs+1 where id={$f['id']} limit 1;");
     msg2('- Вот, теперь я вижу, что ты достоен права стать кузнецом! Обучу в один миг');
     $str = '<span style="color:green;">[Изучено кузнечное дело]</span><br/>';
     $str .= '<span style="color:red;">[Отдано 10 железной руды]</span><br/>';
     $str .= '<span style="color:red;">[Отдано 10 угля]</span><br/>';
	 msg2($str);
     knopka('loc.php', '- Спасибо за науку, мастер',1);
     fin();}
      
elseif ($mod == 3){	 
     msg2('- А вот это, '.$f['login'].', зависит от тебя. Либо ты поторгуешься с шахтёрами и рудокопами, либо выкупишь на рынке. А может и сам сможешь добыть?');
	 knopka('loc.php', '- Понял, найду способ', 1);
     fin();}
	 
elseif ($mod == 4){	
	 $kvest = unserialize($f['kvest']);
     if (empty($kvest['loc-156'])){$kvest['loc-156']['date'] = 0;
     $f['kvest'] = serialize($kvest);
     $q = $db->query("update `users` set kvest='{$f['kvest']}' where id='{$f['id']}' limit 1;");}

     $time = $kvest['loc-156']['date'] - $_SERVER['REQUEST_TIME'];
     if ($time > 0){
     $slova = array();
	 $slova[] = 'К сожалению ни чего...';
	 $slova[] = 'Пока что не нуждаюсь в материалах';
	 $slova[] = 'Подходи попозже, уверен найду для тебя работу';
     $slova[] = 'Не сейчас '.$f['login'].'<br/> *Продолжил заниматься своими делами*';
 	 $slova[] = 'Ммм, занят немного... *Не поварачиваясь к Вам продолжает ковать оружие*';
	 shuffle($slova);
	 msg2('- '.$slova[0].'', 1);
	 fin();}
	 
     $slova = array();
	 $slova[] = 'Так-с';
	 $slova[] = 'Что ж';
	 $slova[] = 'Хм';
	 shuffle($slova);
	 msg2('- '.$slova[0].', мне не хватает материалов для работы. Было бы не плохо, если бы ты снабдил меня ими.<br>
     - И время сэкономлю, и тебе польза будет. Мне нужен мифрил, думаю 60 руды будет в самый раз. Ах, да! Совсем забыл. Принеси мне еще великую вытяжку исцеления на +500 здоровья. А то от огня страдаю часто.');
 	 knopka('kvest1.php?mod=5', '- Мастер Дорин, я принёс руду, как просил', 1);
	 knopka('loc.php', '- Хорошо *Уйти*', 1);fin();}
	 
elseif ($mod == 5){	
	 if ($items->count_base_item($f['login'], 730) < 60 and $items->count_base_item($f['login'], 626) ==0){

     $slova = array();
	 $slova[] = 'Извини, но тут не хватает, так дело не пойдёт...';
	 $slova[] = 'Ты наверно ошибся, но тут нет 60 мифриловой руды и великой вытяжки исцеления';
	 $slova[] = 'Не стоит меня обманывать, теряешь моё уважение!';
	 shuffle($slova);
	 msg2('- '.$slova[0].'', 1);}

     $items->del_base_item($f['login'], 730, 60);
$items->del_base_item($f['login'], 626, 1); 
	 $items->add_item($f['login'], 127, 1);
     $exp = mt_rand($f['lvl'] * 10, $f['lvl'] * 20);
     addexp($f['id'], $exp);
     $kvest = unserialize($f['kvest']);
	 $kvest['loc-156']['date'] = $_SERVER['REQUEST_TIME'] + 79200;
	 $f['kvest'] = serialize($kvest);

     $q = $db->query("update `users` set slava=slava+2,kvest='{$f['kvest']}' where id='{$f['id']}' limit 1;");

     $str = '- Отлично, то что надо!<br>';
     $str .= '- Возьми, редкий и сложный в изготовлении камень. Уверен он тебе пригодится<br>';
     $str .= '[Получено '.$exp.' опыта и точильный камень]<br/>';
	 $str .= '<span style="color:red;">[Отдано 60 мифриловой руды и великая вытяжка исцеления]</span><br/>';
     msg2($str);
     knopka('loc.php', '- Не за что, мастер', 1);
     fin();}
?>