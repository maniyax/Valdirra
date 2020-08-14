<?php
##############
# 24.12.2014 #
##############
require_once('inc/top.php');	// вывод на экран
require_once('inc/check.php');	// вход в игру
require_once('inc/head.php');
require_once('class/items.php');	// вещи

// блок условий (чтобы ХП в плюсе, лока 169 и не в бою)
if($f['status'] == 1)
	{
	knopka('battle.php', 'Вы в бою!',1);
	fin();
	}
if($f['hpnow'] <= 0)
	{
	knopka('loc.php', 'Восстановите здоровье', 1);
	fin();
	}
if($f['loc'] != 169)
	{
	knopka('loc.php', 'Ошибка локации',1);
	fin();
	}

// определяем переменные без всяких обходов register_globalls
$mod = isset($_REQUEST['mod']) ? $_REQUEST['mod'] : '';
$num = isset($_REQUEST['num']) ? $_REQUEST['num'] : '';
$iid = isset($_REQUEST['iid']) ? $_REQUEST['iid'] : '';
$go = isset($_REQUEST['go']) ? $_REQUEST['go'] : '';

if ($f['slava'] >= 25 and $f['slava'] < 50) $cenap = 50;
if ($f['slava'] >= 50 and $f['slava'] < 100) $cenap = 45;
if ($f['slava'] >= 100 and $f['slava'] < 250) $cenap = 40;
if ($f['slava'] >= 250 and $f['slava'] < 500) $cenap = 35;
if ($f['slava'] >= 500 and $f['slava'] < 1000) $cenap = 30;
if ($f['slava'] >= 1000 and $f['slava'] < 5000) $cenap = 25;
if ($f['slava'] >= 5000 and $f['slava'] < 10000) $cenap = 20;
if ($f['slava'] >= 10000) $cenap = 10;

// шапка
if(!empty($_SESSION['auth'])) require_once('inc/hpstring.php');
if(empty($mod))
	{
	echo '<div class="board">';
	echo 'Монеты: медные '.$f['cu'].' | серебряные '.$f['ag'].' | золотые '.$f['au'].'<hr/>';
	echo 'Вы зашли в здание таверны. Здесь можно приобрести различные элексиры, влияющие на ваше состояние.<hr/></div>';
    knopka('taverna.php?mod=pivo', 'Подойти к владельцу таверны',1);
knopka('taverna.php?mod=ohotnik', 'Подсесть за столик к старому охотнику',1);
	knopka('taverna.php?mod=kup&iid=1', 'Эликсир ловкости (1 серебряная монета, уворот + 100)', 1);
	knopka('taverna.php?mod=kup&iid=2', 'Эликсир реакции (1 серебряная монета, крит + 100)', 1);
	knopka('taverna.php?mod=kup&iid=3', 'Эликсир жизненной силы (1 серебряная монета, Макс. ХП + 100)', 1);
	knopka('taverna.php?mod=kup&iid=4', 'Эликсир магической силы (1 серебряная монета, Макс. МП + 100)', 1);
	knopka('taverna.php?mod=kup&iid=5', 'Эликсир вышибалы (1 серебряная монета, урон + 50)', 1);
	knopka('taverna.php?mod=kup&iid=6', 'Купить Эссенцию исцеления (10 медных монет, HP +50, 1 лвл)', 1);
	knopka('taverna.php?mod=kup&iid=7', 'Купить Вытяжку исцеления (30 медных монет, HP +100, 5 лвл)', 1);
	knopka('taverna.php?mod=kup&iid=8', 'Купить Целебный элексир (1 серебряная монета, HP +150, 10 лвл)', 1);
	knopka('taverna.php?mod=kup&iid=9', 'Купить Напиток лечения (2 серебряные монеты, HP +250, 15 лвл)', 1);
	knopka('taverna.php?mod=kup&iid=10', 'Купить Эссенцию мудрости (10 медных монет, MP +50, 1 лвл)', 1);
	knopka('taverna.php?mod=kup&iid=11', 'Купить Вытяжку мудрости (30 медных монет, MP +100, 5 лвл)', 1);
	knopka('taverna.php?mod=kup&iid=12', 'Купить Элексир мудрости (1 серебряная монета, MP +150, 10 лвл)', 1);
	knopka('taverna.php?mod=kup&iid=13', 'Купить Напиток мудрости (2 серебряные монеты, MP +250, 15 лвл)', 1);
	knopka('taverna.php?mod=kup&iid=14', 'Купить Свиток нападения (10 медных монет, 6 лвл)', 1);
	knopka('taverna.php?mod=kup&iid=15', 'Купить Свиток развоплощения (10 серебряных монет, 6 лвл)', 1);
	knopka('taverna.php?mod=kup&iid=16', 'Купить Свиток телепортации в город (2 серебряные монеты)', 1);
	fin();
	}
elseif($mod == 'kup')
	{
	$iid = intval($iid);
	if($iid < 1) $iid = 1;
	switch($iid):
	case 1:
		$cena = 1;
		if($f['ag'] < $cena) msg('Вам нечем заплатить.',1);
		$q = $db->query("update `users` set ag=ag-{$cena} where id='{$f['id']}' limit 1;");
		$items->add_item($f['login'], 620, 1);
		msg('Вы купили эликсир ловкости.',1);
	break;

	case 2:
		$cena = 1;
		if($f['ag'] < $cena) msg('Вам нечем заплатить.',1);
		$q = $db->query("update `users` set ag=ag-{$cena} where id='{$f['id']}' limit 1;");
		$items->add_item($f['login'], 621, 1);
		msg('Вы купили эликсир реакции.',1);
	break;

	case 3:
		$cena = 1;
		if($f['ag'] < $cena) msg('Вам нечем заплатить.',1);
		$q = $db->query("update `users` set ag=ag-{$cena} where id='{$f['id']}' limit 1;");
		$items->add_item($f['login'], 622, 1);
		msg('Вы купили эликсир жизненной силы.',1);
	break;

	case 4:
		$cena = 1;
		if($f['ag'] < $cena) msg('Вам нечем заплатить.',1);
		$q = $db->query("update `users` set ag=ag-{$cena} where id='{$f['id']}' limit 1;");
		$items->add_item($f['login'], 623, 1);
		msg('Вы купили эликсир магической силы.',1);
	break;

	case 5:
		$cena = 1;
		if($f['ag'] < $cena) msg('Вам нечем заплатить.',1);
		$q = $db->query("update `users` set ag=ag-{$cena} where id='{$f['id']}' limit 1;");
		$items->add_item($f['login'], 624, 1);
		msg('Вы купили эликсир вышибалы.',1);
	break;

	case 6:
		$cena = 10;
		if($f['cu'] < $cena) msg('Вам нечем заплатить.',1);
		$q = $db->query("update `users` set cu=cu-{$cena} where id='{$f['id']}' limit 1;");
		$items->add_item($f['login'], 153, 1);
		msg('Вы купили Эссенцию исцеления.',1);
	break;

	case 7:
		$cena = 30;
		if($f['cu'] < $cena) msg('Вам нечем заплатить.',1);
		$q = $db->query("update `users` set cu=cu-{$cena} where id='{$f['id']}' limit 1;");
		$items->add_item($f['login'], 154, 1);
		msg('Вы купили Вытяжку исцеления.',1);
	break;

	case 8:
		$cena = 1;
		if($f['ag'] < $cena) msg('Вам нечем заплатить.',1);
		$q = $db->query("update `users` set ag=ag-{$cena} where id='{$f['id']}' limit 1;");
		$items->add_item($f['login'], 155, 1);
		msg('Вы купили Целебный элексир.',1);
	break;

	case 9:
		$cena = 2;
		if($f['ag'] < $cena) msg('Вам нечем заплатить.',1);
		$q = $db->query("update `users` set ag=ag-{$cena} where id='{$f['id']}' limit 1;");
		$items->add_item($f['login'], 156, 1);
		msg('Вы купили Напиток лечения.',1);
	break;

	case 10:
		$cena = 10;
		if($f['cu'] < $cena) msg('Вам нечем заплатить.',1);
		$q = $db->query("update `users` set cu=cu-{$cena} where id='{$f['id']}' limit 1;");
		$items->add_item($f['login'], 191, 1);
		msg('Вы купили Эссенцию мудрости.',1);
	break;

	case 11:
		$cena = 30;
		if($f['cu'] < $cena) msg('Вам нечем заплатить.',1);
		$q = $db->query("update `users` set cu=cu-{$cena} where id='{$f['id']}' limit 1;");
		$items->add_item($f['login'], 192, 1);
		msg('Вы купили Вытяжку мудрости.',1);
	break;

	case 12:
		$cena = 1;
		if($f['ag'] < $cena) msg('Вам нечем заплатить.',1);
		$q = $db->query("update `users` set ag=ag-{$cena} where id='{$f['id']}' limit 1;");
		$items->add_item($f['login'], 193, 1);
		msg('Вы купили Элексир мудрости.',1);
	break;

	case 13:
		$cena = 2;
		if($f['ag'] < $cena) msg('Вам нечем заплатить.',1);
		$q = $db->query("update `users` set ag=ag-{$cena} where id='{$f['id']}' limit 1;");
		$items->add_item($f['login'], 194, 1);
		msg('Вы купили Напиток мудрости.',1);
	break;

	case 14:
		$cena = 10;
		if($f['cu'] < $cena) msg('Вам нечем заплатить.',1);
		$q = $db->query("update `users` set cu=cu-{$cena} where id='{$f['id']}' limit 1;");
		$items->add_item($f['login'], 121, 1);
		msg('Вы купили Свиток нападения.',1);
	break;

	case 15:
		$cena = 10;
		if($f['ag'] < $cena) msg('Вам нечем заплатить.',1);
		$q = $db->query("update `users` set ag=ag-{$cena} where id='{$f['id']}' limit 1;");
		$items->add_item($f['login'], 122, 1);
		msg('Вы купили Свиток развоплощения.',1);
	break;

	case 16:
		$cena = 2;
		if($f['ag'] < $cena) msg('Вам нечем заплатить.',1);
		$q = $db->query("update `users` set ag=ag-{$cena} where id='{$f['id']}' limit 1;");
		$items->add_item($f['login'], 717, 1);
		msg('Вы купили Свиток телепортации в город.',1);
	break;




	default:
		header("location: taverna.php");
		exit;
	break;
	endswitch;
}
elseif ($mod=='pivo'){

if ($f['p_pivovar'] > 0){
knopka('loc.php', '- Вы и так знаете все тонкости изготовления напитков',1);
fin();
}

if ($f['profs'] >= $f['profsmax']){
knopka('loc.php', '- Знаток профессий! Не собираюсь Вас учить пивоварению',1);
fin();
}

if ($f['slava'] < 25){
msg2('- Не отвлекай бродяга! Либо покупай что нибудь, либо проваливай',1);
fin();
}

msg2('<b>Владелец таверны</b>');
if (empty($go)){
msg2('- Здравствуй, '.$f['login'].'.<br/>
- Я вижу Вы много хорошего сделали для империи, я могу Вас обучить ремеслу, изготавливать различные бодрящие напитки, которые никого не оставят равнодушными.<br>
- За это умение я возьму с тебя всего лишь '.$cenap.' серебряных монет!');
knopka('taverna.php?mod=pivo&go=1', '- Да, я буду тебе очень благодарен<br/>',1);
knopka('loc.php', '- Мне нужно подумать',1);
fin();
}

if ($f['ag'] < $cenap){
 msg2('- К сожалению у Вас нет столько денег...',1);
fin();}

$q = $db->query("update `users` set ag=ag-{$cenap},p_pivovar=1,profs=profs+1 where id={$f['id']} limit 1;");
msg2('- Вот Вы и стали пивоваром, не забудьте, '.$f['login'].', что для создания напитков Вам нужно запастись бутылками с водой и ингредиентами, которые может добыть травник.<br/>
- Обучиться профессии травника ты можешь у Гризельды, она сейчас проживает в новом лагере. Эх, что была в молодости за женчина!<br/>
Владелец таверны причмокнул губами и погрузился в воспоминания.<br/>
<span style="color:green;">[Изучено профессия пивовар]</span><br/>
<span style="color:red;">[Отдано '.$cenap.' серебряных монет]</span>');
if ($f['p_travnik'] == 0) knopka('loc.php', '- Спасибо тебе за ремесло и за информацию про травничество',1);
if ($f['p_travnik'] > 0) knopka('taverna.php', '- К счастью я уже травник. Спасибо за ремесло',1);
fin();
}

if($mod=='ohotnik'){
msg2('<b>Старый охотник</b>');

if(empty($go)){
msg2('Здравствуй, '.$f['login'].'.<br>
Чего хотел?');
knopka2('taverna.php?mod=ohotnik&go=1', 'Научи меня разделывать туши животных',1);
knopka2('taverna.php?mod=ohotnik&go=3', 'Хочу делать что-нибудь полезное из шкур, научишь?',1);
fin();
}


elseif($go==1){
if($f['p_ohotnik'] >0) msg2('Ты уже охотник',1);
if($f['profs'] >= $f['profsmax']) msg2('Ты и так мастер на все руки',1);
msg2('Ну хорошо. Докажи, что ты достоен стать охотником. Принеси мне 4 ноги кабана, я посмотрю, как ты справишься.');
knopka2('taverna.php?mod=ohotnik&go=2', 'Я все принес',1);
knopka2('loc.php', 'Пойду добывать',1);
fin();
}


elseif($go==2){
if($f['p_ohotnik'] >0) msg2('Ты уже охотник',1);
if($f['profs'] >= $f['profsmax']) msg2('Ты и так мастер на все руки',1);
if($items->count_base_item($f['login'], 742) <4) msg2('У тебя нет четырех ног кабана',1);
$items->del_base_item($f['login'], 742, 4);
$q = $db->query("update `users` set p_ohotnik=1,profs=profs+1 where id={$f['id']} limit 1;");
msg2('Вот ты и стал охотником, для разделки тебе потребуется специальный нож, который можно купить в клановом магазинчике.<br>
<span style="color:green;">Изучена профессия охотник</span><br>
<span style="color:red;">Отдано 4 ноги кабана</span>',1);
fin();
}

elseif($go==3){
if($f['p_koj'] >0) msg2('Ты уже кожевник',1);
if($f['profs'] >= $f['profsmax']) msg2('Ты и так мастер на все руки',1);
msg2('Я могу научить тебя ремеслу кожевника, но ты мне должен будешь заплатить 50 серебряных монет.');
knopka2('taverna.php?mod=ohotnik&go=4', 'Вот деньги',1);
knopka2('loc.php', 'Это мне не по карману (уйти)',1);
fin();
}
elseif($go==4){
if($f['p_koj'] >0) msg2('Ты уже кожевник',1);
if($f['profs'] >= $f['profsmax']) msg2('Ты и так мастер на все руки',1);
if($f['ag'] <50) msg2('У тебя нет 50 серебряных монет',1);
$q = $db->query("update `users` set ag=ag-50,p_koj=1,profs=profs+1 where id={$f['id']} limit 1;");
msg2('Поздравляю!<br>
<span style="color:green;">Изучена профессия кожевник</span><br>
<span style="color:red;">Отдано 50 серебряных монет</span>',1);
fin();
}
fin();
}

?>
