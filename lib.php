<?php
##############
# 24.12.2014 #
##############

require_once('inc/top.php');	// вывод на экран
if(!empty($_SESSION['auth'])) require_once('inc/check.php');	// вход в игру
require_once('inc/head.php');
$mod = isset($_REQUEST['mod']) ? $_REQUEST['mod'] : '';
if(!empty($_SESSION['auth'])) require_once('inc/hpstring.php');
switch($mod):
case 'rule':
	echo '<div class="board" style="text-align:left">';
	echo 'Данный свод законов  распространяется на всех персонажей без исключения.<br/><br/>';

echo'
1. Общие положения.<br>
1.1 Запрещено регистрировать более одного аккаунта, т.е. заниматься мультоводством.<br>
Наказание: Блок всех мультов пожизненно и штраф основному аккаунту 10000 монет.<br>
1.2 Запрещено регистрироваться в игре с никами, оскорбляющими кого-либо, чью-либо честь и/или чье-либо достоинство, нарушающие законы РФ, противоречищами нормам морали.<br>
Наказание: Блок пожизненно.<br>
1.3 Запрещено регистрироваться с никами, похожими на "Админ", "Администратор", "Модератор", "Система" и т.п.<br>
Наказание: Блок пожизненно.<br>
1.4 Игра через прокси и анонимайзеры запрещена.<br>
Наказание: На первый раз предупреждение, на второй - блок на сутки, на третий - блок на неделю со штрафом в 5000 монет.<br>
1.5 Запрещено устраивать подставные бои, т.е. бои, с заранее обговоренным результатом и проводимые для целенаправленного получения опыта и/или иных ресурсов.<br>
Наказание: Штрав с каждого игрока по 500 монет.<br>
1.6 Стражайше запрещен слив вещей и/или монет от одного игрока другому. все передачи должны быть с четкими комментариями и за вещи должна быть уплачена ее цена 100% +-10%.<br>
Наказание: Штраф игроку, получившему вещи и/или монеты, накладывается администрацией в денежном эквиваленте в размере слитой суммы. в случае слива вещей берется ее рыночная стоимость.<br>
1.7 Продажа персонажей, кланов, просто передача пароля от персонажа другому лицу.<br>
Наказание: Блок пожизненно.<br>
1.8 Обман администрации, использование багов, скрытие ошибок в коде.<br>
Наказание: На усмотрение администрации.<br>
1.9 Использование программ и/или скриптов автоматической прокачки, включение автообновления в браузерах.<br>
Наказание: Блок пожизненно.<br>
1.10 Запрещено проведение викторин, создание тотализаторов без согласования с администрацией.<br>
Наказание: Блок на сутки.<br>
1.11 В случае подозрения на мультоводство администрация вправе ограничить доступ к игре подозреваемых аккаунтов до выяснения.<br>
1.12 Администрация игры не несет какой-либо ответственности за психическое/моральное/физическое расстройство игроков.<br>
1.13 В случае совпадения IP-адресов нескольких персонажей и при подтверждении их уникальности игрокам запрещается иметь в игре любые отношения кроме общения, а именно: торговля, совместные бои, нахождение в одном клане, передача ресурсов друг другу непосредственно или через посредников. При обнаружении нарушений наказание понесут как учасники так и их помощники.<br>
1.14 Под подозрение на мультоводство подпадают игроки заходящие с одинаковых IP-адресов, использующих одни и те же устройства, имеющие бои похожие на подставные и тд. Список может быть расширен.<br>
1.15 Вещи в аренду разрешается давать только с использованием инструмента сдачи вещей в аренду.<br>
1.16 В случае подозрения на мультиводство, а так же возникновении иных спорных ситуаций администрация игры оставляет за собой право ознакомится с личной перепиской игроков.<br>

1.17 Игра предоставляется на условиях "как есть", то есть Пользователю не представляются какие-либо гарантии, что Игра будет соответствовать его требованиям.<br>


2. Общение.<br>
Эти правила не распространяются на личную переписку. если вам не нравится человек, добавляйте его в игнор-лист.<br>
2.1 Запрещен мат, в каких-либо его проявлениях.<br>
Наказание: Молча на 15 мин, на 1 час, блок на сутки.<br>
2.2 Запрещено оскорблять игроков, троллить, издеваться, как-либо унижать их честь и/или достоинство.<br>
Наказание: Молча на 30 мин, на 1 час, блок на сутки.<br>
2.3 Оскорбление и/или агрессивная критика действий модераторов и/или администрации.<br>
Наказание: Молча на 1 час, блок на 2 суток.<br>
2.4 Запрещен флуд. под флудом понимается злоупотребление капсом в сообщениях, отправка нескольких сообщений с интервалом менее, чем 30 секунд, не несущих смысла, копирование несколько раз подряд однообразного текста.<br>
Наказание: Молча на 5 мин, 30 мин.<br>
2.5 Пропаганда наркотиков, алкоголя, проституции, наркомании, разжигание межнациональной розни, споры на религиозные и/или политические темы.<br>
Наказание: Молча на 1 сутки.<br>
2.6 Реклама сторонних ресурсов и/или проектов. Примечание: упоминание таких игр, как GTA, CS и т.п. не является нарушением.<br>
Наказание: Молча на 30 мин, 1 час, блок на 1 сутки.<br><br>

3. Администрация игры.<br>
3.1 Модераторы и гейм мастер являются прямыми представителями администрации и действуют от ее лица.<br>
3.2 Модераторы следят за порядком в игре, они могут выдавать баны на определенное количество дней, ставить игрокам молчу в чате, удалять их посты, просматривать логи передач, кланов, боев и заходов на аккаунты.<br>
3.3 Модераторы выбираются администрацией из числа игроков-добровольцев.<br>
3.4 Гейм мастер в игре может быть только один и ему подчиняются все модераторы, в отличие от модераторов он может налагать штрафы на игроков, регламентируемые текущей редакцией правил.<br>
3.5 Игроки не имеют права заносить модераторов в игнор-лист, хамить, отказывать в содействии.<br>
3.6 Модераторы не имеют права заносить игроков в игнор-лист, хамить.<br>
3.7 Модераторы при наложении блока на игрока обязаны в комментарии к блоку указать пункт правил, по которому был выдан бан.<br>
3.8 Модераторы не имеют права вступать в полемику с наказанными игроками, все жалобы, игроки должны направлять на e-mail администрации <a href="mailto:admin@valdirra.ru">admin@valdirra.ru</a>.<br>
3.9 Никто из администрации игры не будет просить ваш пароль, администраторам он не требуется, а модераторам не нужен. В случае, если кто-то будет просить у Вас ваш пароль, прикрываясь должностью администратора/модератора/гейм мастера, сообщите об этом на адрес тех. поддержки <a href="mailto:support@valdirra.ru">support@valdirra.ru</a>.<br>
3.10 Администрация Вальдирры не несет ответственности за действия игроков.<br>
3.11 Администрация Вальдирры обязуется не читать вашу личную переписку без нарушений вами п.п.1.16, не передавать третьим лицам какие-либо ваши данные, как IP-адрес, e-mail, пароль и др.<br><br>


4. Кланы.<br>
4.1 На клан нельзя скидываться нескольким людям, глава должен сам накопить деньги.<br>
4.2 Если клан в течении недели не построит клановый замок, то он будет удален.<br>
Примечание: Деньги в этом случае не возвращаются.<br>
4.3 Запрещенно строить клановые замки в населенных пунктах и менее, чем за 5 локаций до них.<br>
4.4 За 20 золотых монет ваш клановый замок может быть перенесен на новое место, для этого пишите на ник maniyax.<br>
4.5 Любой игрок, состоящий в клане, может передать главе своего клана вещи с комментарием "На нужды клана", при этом глава может передавать эти вещи только сокланам.<br>
4.6 Глава клана может давать сокланам вещи только с возвратом, но бесплатно. комментарий должен быть также осмысленным и содержать дату возврата вещи.<br>
*При неоднократных нарушениях, наказание может быть изменено!<br>
4.7 Вещи, переданные сокланами в собственность клана, должны храниться на складе клана.<br>
4.8 Из казны клана запрещен вывод средств без осмысленного комментария и выплаты должны быть заслуженные.<br><br>

Обратите внимание: администрация вправе вносить в текущую редакцию правил изменения без согласования с игроками. После опубликования в здании городской стражи новая редакция правил вступает в силу.<br>
';


	echo '*При неоднократных нарушениях, наказание может быть изменено!';
	echo '</div>';
break;

case 'straj':
echo '<div class="board" style="text-align:left">
Здание городской стражи.<br>
За столом сидит дежурный стражник, перед ним на столе лежит свод законов Вальдирры.<br>
За его спиной висит список заключенных.<br>
<a href="lib.php?mod=rule">Прочитать свод законов Вальдирры</a><br>
<a href="infa.php?mod=blok">Посмотреть список заключенных</a></div>';
break;

case 'kodeks':
	echo '<div class="board" style="text-align:left">';
	echo '<b>I. Общие положения</b><br/>';
	echo '1) Модератор – игрок, назначаемый на эту должность Инквизитором.<br/>';
	echo '2) Закон Игры - игровой свод правил игры, регулирующий общение в чате, торговлю, финансовую деятельность, срок и порядок назначения наказания, осуществление наказания модераторами, запрещающий мультоводство.<br/>';
	echo '3) Структура модераторов:<br/>
		<b>•</b> Суперадмин - создатель игры. Наделен самыми большими полномочиями.<br/>
		<b>•</b> Админ - администратор. Наделен максимальными полномочиями.<br/>
		<b>•</b> Супермодер - модератор, наделенный властными полномочиями, решает сложные вопросы в отсутствие Инквизитора.<br/>
		<b>•</b> Модер - рядовой модератор, наделенный властными полномочиями.<br/><br/>';

	echo '<b>II. Права и обязанности модераторов</b><br/>';
	echo '1) Отправление правосудия осуществляется в соответствие с Законом игры.<br/>';
	echo '2) При вынесении наказания модератор руководствуется вышестоящим законом, тяжестью вины игрока.<br/>';
	echo '3) Модератор при вынесении наказания указывает причину наложения молчи, блока, штрафа.<br/>';
	echo '4) Модератор не вправе вынести наказание, превышающее максимальный срок за проступок, указанный в законе.<br/>';
	echo '5) Модератор не может наказать игрока за нападение на него, при обоюдном согласии на бой, в противном случае - блок на сутки +предупреждение.<br/>';
	echo '6) Модератору запрещено удалять посты  игроков в чате, форуме, торговом зале, если они не противоречат п.6 Закона игры.<br/>';
	echo '7) Модератору запрещено участвовать в клановых боях, нападать простым, кровавым свитком без согласия на бой, оказывать иную помощь враждующим кланам.<br/>';
	echo '8) Запрещено разглашать конфиденциальную информацию, ставшей доступной в ходе профессиональной деятельности.<br/>';
	echo '9) Модератору запрещено проводить свадьбу без согласия жениха и невесты.<br/>';
	echo '10) Модератору запрещено наказывать игрока  без состава нарушения закона.<br/>';
	echo '12) Модератор не вправе блочить игроков за схожесть айпи, если получено согласие от Админа и выше на игру с одного айпи вдвоем.<br/><br/>';

	echo '<b>III. Этика и профессиональная деятельность модератора</b><br/>';
	echo '1) Модератор обязан быть вежливым и культурным в процессе общения с игроками.<br/>';
	echo '2) Запрещено давить и угрожать игрокам, используя служебное положение.<br/>';
	echo '3) Модератору запрещено ставить игроков в игнор-лист, при наказании игрока указывается в чате причина наказания с ссылкой на ст. Закона.<br/>';
	echo '4) Модератор обязан разъяснять игрокам законы игры. Письма, не относящиеся к профессиональной деятельности модератора, а именно, разъяснение Закона Консультации и вопросы, не касающиеся игрового процесса, игнорируются.<br/>';
	echo '5) Модератор сам обязан соблюдать Законы игры.<br/>';
	echo '6) Если нарушение не указано в законе и иных документах, регулирующих игровой процесс и игровые действия; принятие решения вызывает сомнение, факты косвенно указывают на вину нарушителя; нет улик ,кроме слов свидетелей; предложение содержит слова оскорбления личности, не признанные толковыми словарями матерными - дело передается на рассмотрение Инквизитору.<br/>';
	echo '7) Модератору запрещено принимать подарки от игроков.<br/>';
	echo '8) Модератор обязан объяснить игрокам их право на обжалование действия модератора у Админа и выше.<br/>';
	echo '9) Модератор не должен провоцировать конфликты, разжигать национальную рознь, пропагандировать наркотики, насилие и иные действия, направленные против человечества, умаляющие его личность, порочащие его честь и достоинство, вероисповедание.<br/>';
	echo '10) Модератор должен быть непредвзятым, честным и справедливым ко всем игрокам при вынесении наказания.<br/>';
	echo '11) Модератор не может вмешиваться в действия другого модератора, кроме случаев, когда они противоречат закону.<br/>';
	echo '12) Модератор не вправе отказать игроку при защите его прав от противоправных действий мошенника, афериста, грубости и унижения и форс-мажорных обстоятельств.<br/>';
	echo '13) При нарушении модератором статей настоящего Кодекса и Закона игры, модератор подлежит строгому выговору с предупреждением, при рецидиве - понижение в должности или увольнение без права занимать должность модератора.';
	echo '</div>';
break;

case 'formuls':
	echo '<div class="board" style="text-align:left">';
	echo 'Формулы боя:<br/>';
	echo '1. Шанс попасть = ((крит / уворот противника) * 100)<br/>
	не более 95%, не менее 5%<br/><br/>
	2. Шанс критического удара = ((крит / уворот противника) * 100 - 95)<br/>
	не более 95%, не менее 1%<br/><br/>
	3. Шанс увернуться = 100 - шанс соперника попасть по вам<br/>
	не более 95%, не менее 5%<br/><br/>
	
	4. При обычном ударе урон = рандом(мин урон - макс урон) - броня соперника * 0.5<br/><br/>
	
	5. При крите урон = рандом(мин урон - макс урон) * 2 - броня соперника * 0.33<br/><br/>
	
	6. При попадании в блок простым ударом урон 0. Критический удар не пробивает блок.<br/><br/>';
	
	echo 'Инвентарь:<br/>';
	echo '1. При продаже в магазин любой вещи игрок получает 50% её гос цены - округление в меньшую сторону, поэтому вполне реально некоторые вещи продать за 0 монет...<br/><br/>';
	
	echo 'Рудники:<br/>';
	echo '1. На старой шахте вознаграждение составляет 6 * ваш лвл монет в час. Можно работать с 1 уровня.<br><br>
	2. На новой шахте вознаграждение составляет 15 * ваш лвл монет в час. Можно работать с 1 уровня.<br/><br/>';
	echo '</div>';
break;

case 'donate':
	echo '<div class="board" style="text-align:left">';
	echo 'Мгновенная беспроигрышная лотерея.<br/><br/>';
	echo 'Цена одного билета в лотерею:<br/>
5 золотых
	<br/>
	<small>призы появляются в рюкзаке (монеты добавляется в кошелек) сразу же после использования билета</small><br/>
	 1) 50% найти 50 серебряных монет<br/>
	 2) 10% найти 3 золотые монеты<br/>
	 3) 10% найти лотерейный билет (при этом старый билет не удалится)<br/>
	 4) 5% найти Точильный камень<br/>
	 5) 5% найти лотерейный арт-браслет<br/>
	 6) 10% найти свиток опыта 1 ступени<br/>
	 7) 6% найти свиток опыта 2 ступени<br/>
	 8) 4% найти свиток опыта 3 ступени<br/><br/>
	 - крит и уворот браслета равны 10 * лвл, урон и броня всегда равны 5.<br/>
	Претензии не принимаются. Купив билет, игрок соглашается со всем вышесказанным, включая и шансы на призы.';
	echo '</div>';
break;

case 'smile':
	echo '<div class="board" style="text-align:left">';
	echo '.афтар.', '<img src="smile/aftar.gif"/><hr/>';
	echo '.бан.', '<img src="smile/ban.gif"/><hr/>';
	echo '.банан.', '<img src="smile/banan.gif"/><hr/>';
	echo '.банан1.', '<img src="smile/banan1.gif"/><hr/>';
	echo '.бомж.', '<img src=\'smile/bomj.gif\'/><hr/>';
	echo '.браво.', '<img src=\'smile/bravo.gif\'/><hr/>';
	echo '.чмак.', '<img src=\'smile/chmak.gif\'/><hr/>';
	echo '.дедмороз.', '<img src=\'smile/dedmoroz.gif\'/><hr/>';
	echo '.дети.', '<img src=\'smile/deti.gif\'/><hr/>';
	echo '.днюха.', '<img src=\'smile/denrojd.gif\'/><hr/>';
	echo '.добрый.', '<img src=\'smile/dobrij.gif\'/><hr/>';
	echo '.достали.', '<img src=\'smile/dostali.gif\'/><hr/>';
	echo '.драка.', '<img src=\'smile/draka.gif\'/><hr/>';
	echo '.дум.', '<img src=\'smile/dum.gif\'/><hr/>';
	echo '.душ.', '<img src=\'smile/dush.gif\'/><hr/>';
	echo '.дятел.', '<img src=\'smile/djatel.gif\'/><hr/>';
	echo '.елка.', '<img src=\'smile/elka.gif\'/><hr/>';
	echo '.ёлка.', '<img src=\'smile/elka.gif\'/><hr/>';
	echo '.фан.', '<img src=\'smile/fan.gif\'/><hr/>';
	echo '.фанаты.', '<img src=\'smile/fans.gif\'/><hr/>';
	echo '.фигасе.', '<img src=\'smile/figase.gif\'/><hr/>';
	echo '.флаг.', '<img src=\'smile/flag.gif\'/><hr/>';
	echo '.флаг1.', '<img src=\'smile/flag1.gif\'/><hr/>';
	echo '.флуд.', '<img src=\'smile/flud.gif\'/><hr/>';
	echo '.говнецо.', '<img src=\'smile/govneco.gif\'/><hr/>';
	echo '.грабли.', '<img src=\'smile/grabli.gif\'/><hr/>';
	echo '.грамота.', '<img src=\'smile/gramota.gif\'/><hr/>';
	echo '.сердце.', '<img src=\'smile/heart.gif\'/><hr/>';
	echo '.хор.', '<img src=\'smile/hor.gif\'/><hr/>';
	echo '.истерика.', '<img src=\'smile/isterika.gif\'/><hr/>';
	echo '.яд.', '<img src=\'smile/jad.gif\'/><hr/>';
	echo '.карты.', '<img src=\'smile/karty.gif\'/><hr/>';
	echo '.каток.', '<img src=\'smile/katok.gif\'/><hr/>';
	echo '.король.', '<img src=\'smile/king.gif\'/><hr/>';
	echo '.конфета.', '<img src=\'smile/konfeta.gif\'/><hr/>';
	echo '.кофе.', '<img src=\'smile/kofe.gif\'/><hr/>';
	echo '.комп.', '<img src=\'smile/komp.gif\'/><hr/>';
	echo '.конфетти.', '<img src=\'smile/konfetti.gif\'/><hr/>';
	echo '.конь.', '<img src=\'smile/konj.gif\'/><hr/>';
	echo '.курю.', '<img src=\'smile/kurju.gif\'/><hr/>';
	echo '.ладно.', '<img src=\'smile/ladno.gif\'/><hr/>';
	echo '.ляля.', '<img src=\'smile/ljalja.gif\'/><hr/>';
	echo '.медик.', '<img src=\'smile/medic.gif\'/><hr/>';
	echo '.молоток.', '<img src=\'smile/molotok.gif\'/><hr/>';
	echo '.нефлуди.', '<img src=\'smile/nefludi.gif\'/><hr/>';
	echo '.новыйгод.', '<img src=\'smile/newyear.gif\'/><hr/>';
	echo '.небань.', '<img src=\'smile/noban.gif\'/><hr/>';
	echo '.номер.', '<img src=\'smile/nomer.gif\'/><hr/>';
	echo '.ох.', '<img src=\'smile/oh.gif\'/><hr/>';
	echo '.пасиба.', '<img src=\'smile/pasiba.gif\'/><hr/>';
	echo '.песочница.', '<img src=\'smile/pesochnica.gif\'/><hr/>';
	echo '.пионер.', '<img src=\'smile/pioner.gif\'/><hr/>';
	echo '.письмо.', '<img src=\'smile/pismo.gif\'/><hr/>';
	echo '.пифпаф.', '<img src=\'smile/pifpaf.gif\'/><hr/>';
	echo '.пиво.', '<img src=\'smile/pivo.gif\'/><hr/>';
	echo '.плак.', '<img src=\'smile/plac.gif\'/><hr/>';
	echo '.плохо.', '<img src=\'smile/ploho.gif\'/><hr/>';
	echo '.плюсодин.', '<img src=\'smile/plusodin.gif\'/><hr/>';
	echo '.побили.', '<img src=\'smile/pobili.gif\'/><hr/>';
	echo '.подарок.', '<img src=\'smile/podarok.gif\'/><hr/>';
	echo '.пока.', '<img src=\'smile/poka.gif\'/><hr/>';
	echo '.попа.', '<img src=\'smile/popa.gif\'/><hr/>';
	echo '.превед.', '<img src=\'smile/preved.gif\'/><hr/>';
	echo '.привет.', '<img src=\'smile/privet.gif\'/><hr/>';
	echo '.прыг.', '<img src=\'smile/pryg.gif\'/><hr/>';
	echo '.репка.', '<img src=\'smile/repka.gif\'/><hr/>';
	echo '.ромашка.', '<img src=\'smile/romashka.gif\'/><hr/>';
	echo '.роза.', '<img src=\'smile/roza.gif\'/><hr/>';
	echo '.русский.', '<img src=\'smile/russkij.gif\'/><hr/>';
	echo '.русский1.', '<img src=\'smile/russkij1.gif\'/><hr/>';
	echo '.ржу.', '<img src=\'smile/rzhu.gif\'/><hr/>';
	echo '.секас.', '<img src=\'smile/sekas.gif\'/><hr/>';
	echo '.семья.', '<img src=\'smile/semja.gif\'/><hr/>';
	echo '.сиськи.', '<img src=\'smile/siski.gif\'/><hr/>';
	echo '.смех.', '<img src=\'smile/smeh.gif\'/><hr/>';
	echo '.сигарета.', '<img src=\'smile/smoke.gif\'/><hr/>';
	echo '.солнце.', '<img src=\'smile/solnce.gif\'/><hr/>';
	echo '.спам.', '<img src=\'smile/spam.gif\'/><hr/>';
	echo '.стих.', '<img src=\'smile/stih.gif\'/><hr/>';
	echo '.сцуко.', '<img src=\'smile/scuko.gif\'/><hr/>';
	echo '.свадьба.', '<img src=\'smile/svadba.gif\'/><hr/>';
	echo '.свист.', '<img src=\'smile/svist.gif\'/><hr/>';
	echo '.согласен.', '<img src=\'smile/soglasen.gif\'/><hr/>';
	echo '.танцы.', '<img src=\'smile/tancy.gif\'/><hr/>';
	echo '.тема.', '<img src=\'smile/tema.gif\'/><hr/>';
	echo '.тормоз.', '<img src=\'smile/tormoz.gif\'/><hr/>';
	echo '.туса.', '<img src=\'smile/tusa.gif\'/><hr/>';
	echo '.утро.', '<img src=\'smile/utro.gif\'/><hr/>';
	echo '.велик.', '<img src=\'smile/velik.gif\'/><hr/>';
	echo '.велком.', '<img src=\'smile/wellcome.gif\'/><hr/>';
	echo '.вестерн.', '<img src=\'smile/vestern.gif\'/><hr/>';
	echo '.винсент.', '<img src=\'smile/vinsent.gif\'/><hr/>';
	echo '.язык.', '<img src=\'smile/yazik.gif\'/><hr/>';
	echo '.зяфк.', '<img src=\'smile/zjafk.gif\'/>';
	echo '</div>';
break;

case 'expold':
	echo '<div class="board" style="text-align:left">';
	echo '<table border=1>';
	echo '<tr><td>Уровень</td><td>До след.</td><td>Кол-во статов</td></tr>';
	echo '<tr><td>1</td><td>500</td><td>11</td></tr>';
	echo '<tr><td>2</td><td>2000</td><td>23</td></tr>';
	echo '<tr><td>3</td><td>5000</td><td>36</td></tr>';
	echo '<tr><td>4</td><td>10000</td><td>50</td></tr>';
	echo '<tr><td>5</td><td>20000</td><td>65</td></tr>';
	echo '<tr><td>6</td><td>50000</td><td>81</td></tr>';
	echo '<tr><td>7</td><td>100000</td><td>98</td></tr>';
	echo '<tr><td>8</td><td>200000</td><td>116</td></tr>';
	echo '<tr><td>9</td><td>500000</td><td>135</td></tr>';
	echo '<tr><td>10</td><td>1000000</td><td>155</td></tr>';
	echo '<tr><td>11</td><td>2000000</td><td>176</td></tr>';
	echo '<tr><td>12</td><td>5000000</td><td>198</td></tr>';
	echo '<tr><td>13</td><td>10000000</td><td>221</td></tr>';
	echo '<tr><td>14</td><td>20000000</td><td>245</td></tr>';
	echo '<tr><td>15</td><td>50000000</td><td>270</td></tr>';
	echo '<tr><td>16</td><td>100000000</td><td>296</td></tr>';
	echo '<tr><td>17</td><td>200000000</td><td>323</td></tr>';
	echo '<tr><td>18</td><td>500000000</td><td>351</td></tr>';
	echo '<tr><td>19</td><td>1000000000</td><td>380</td></tr>';
	echo '<tr><td>20</td><td>2000000000</td><td>410</td></tr>';
	echo '<tr><td>21</td><td>2000000000</td><td>441</td></tr>';
	echo '<tr><td>22</td><td>2000000000</td><td>473</td></tr>';
	echo '<tr><td>23</td><td>2000000000</td><td>506</td></tr>';
	echo '<tr><td>24</td><td>2000000000</td><td>540</td></tr>';
	echo '<tr><td>25</td><td>2000000000</td><td>575</td></tr>';
	echo '</table>';
	echo '</div>';
break;

case 'vip':
	echo '<div class="board" style="text-align:left">';
	echo 'VIP статус добавляет вашему персонажу некоторые привилегии в игре:<br/>';
	echo ' - 3% за вклад в банке в неделю.<br/>';
	echo ' - Опыт и монеты в бою +100%.<br/>';
	echo ' - Нет комиссии за передачу монет.<br/>';
	echo ' - Продажа в магазин за 60%.<br/>';
	echo ' - Больше заработок на шахтах.<br/>';

	echo '<small>Список будет пополняться.</small><br/>';
	echo '</div>';
break;

case 'nalog':
	echo '<div class="board" style="text-align:left">';
	echo 'Клановый налог начисляется каждому игроку, который находится в клане.<br/>';
	echo 'Налог ни к чему вас не обязывает, он не отнимает у вас ни одной монеты, даже наоборот - он начисляется дополнительными 10% к вашему заработку (с ботов и с шахт) и в дальнейшем перечисляется в казну клана (функция главы или наместника)<br/>';
	echo 'То есть, вы ничего не теряете, и вам не надо сдавать из своего кармана деньги на развитие клана.<br/>';
	echo '</div>';
break;

case 'profs':
msg2('На данный момент существует 10 профессий:<br>
Лесоруб. Травник. Пивовар. Алхимик. Рудокоп. Кузнец. Ювелир. Мастер рун. Охотник. Кожевник.<br>
А так же Рыболов, не относящийся к основным профессиям, его можно изучить сверх лимита профессий. Лимит на профессии - равен 3+кол-во перерождений.<br>
Если ваш лимит на профессии превышен, но вы хотите изучить новую, за 1 золотую монету вы можете забыть одну из уже имеющихся профессий, идущих в лимит.<br>
Лесоруб:<br>
Изучается в восточном лесу. Требуется топор для рубки деревьев. С деревьев добывается брёвна, которые используются для постройки зданий в клановом замке. Больше другого применения для данной профессии - нет.<br>
Травник:<br>
Изучается в новом лагере у Гризельды (западная часть мира). Позволяет собирать травы. Такие как рис, солод, хмель и т.д. Данные ресурсы используются пивоваром для изготовления напитков (пиво, вино, самогон, брага, шнапс) и алхимиком для изготовления эликсиров. <br>
Пивовар:<br>
Изучается у трактирщика в таверне города (нужно набрать известность). Позволяет изготавливать напитки в клановой пивоварне, для этого понадобятся травы и бутылка с водой.<br>
Алхимик:<br>
Изучается у алхимика в городе. Изготавливает эликсиры на восстановление НР и МР в клановых лабораториях. Для изготовления требуется травы и бутылка с водой.<br>
Рудокоп:<br>
Изучается у рудокопа возле шахты. Позволяет добывать разного типа руду и драгоценные камни, которые требуются кузнецам и ювелирам.<br>
Кузнец:<br>
Изучается у кузнеца в городе. Позволяет более эффективно точить оружие и создавать свое в клановых кузницах, а также чинить вещи.<br>
Ювелир:<br>
Изучается у императорского ювелира в городе. Позволяет инкрустировать камни в вещи.<br>
Мастер рун:<br>
Изучается у мастера рун на юго-востоке города. Дает возможность накладывать на экипировку арт-эффекты, которые усиливают ее.<br>
Охотник:<br>
Изучается у старого охотника в таверне, позволяет снимать шкуры с некоторых животных.<br>
Кожевник:<br>
Изучается также у охотника, дает возможность создавать перчатки и штаны.<br>
Рыболов:<br>
Изучается у рыбака, находится не далеко возле первоначального лагеря. Ловля рыбы - не плохой финансовый доход, есть возможность выловить не только рыбу (денежные сундуки)',1);
fin();
break;


case 'kamni':
msg2('Эффекты камней<br>
Изумруд
<ul>
<li> крит +3%</li>
<li> уворот +3%</li>
</ul>
Рубин
<ul>
<li> крит +5%</li>
</ul>
Сапфир
<ul>
<li> уворот +5%</li>
</ul>
Малахит
<ul>
<li> урон +1%</li>
<li> броня +1%</li>
<li> HP +5%</li>
</ul>
Амазанит
<ul>
<li> урон +2%</li>
<li> броня +2%</li>
<li> HP +10%</li>
</ul>
Змеевик
<ul>
<li> крит +10%</li>
<li> уворот +10%</li>
<li> урон +3%</li>
<li> броня +3%</li>
<li> HP +15%</li>
</ul>
Алмаз
<ul>
<li> крит +15%</li>
<li> уворот +15%</li>
<li> урон +5%</li>
<li> броня +5%</li>
<li> HP +20%</li>
</ul>',1);
break;


case 'money':
msg2('В Вальдирре существует три типа валюты:<ul>
<li> медные монеты;</li>
<li> серебряные монеты;</li>
<li> золотые монеты.</li>
</ul>
Медные монеты можно обменивать на серебряные, а серебряные на золотые по курсу 100 к 1 (курс одинаковый в обе стороны).<br>
Поговорим подробнее о каждой.
<dl>
<dt>Медные монеты</dt>
<dd>Добываются в боях, выдаются за выполнение некоторых заданий.<br>
Это, можно сказать, основная валюта игры, ибо торговля через рынок, покупка/продажа вещей у торговцев происходит в этой валюте.</dd>
<dt>Серебряные монеты</dt>
<dd>Можно получить за выполнение некоторых квестов, выбить с некоторых мобов.<br>
За них можно научиться профессиям, купить кое-какие вещи (свитки/инструменты/алхимию/...).</dd>
<dt>Золотые монеты</dt>
<dd>Их можно выбить с групповых мобов, получить в празднечных эвентах.<br>
За золотые монеты можно покупать что-нибудь в разделе VIP, улучшать храм, создать клан.</dd>
</dl>
Игрокам доступна передача между собой только медных монет.',1);
fin();
break;

case 'pererod':
msg2('В Вальдирре всего 25 уровней.<br>
Но это не повод отчаиваться, ведь при достижении 23 уровня, вы можете переродиться в храме и начать все с первого уровня.<br>
Каждое перерождение дает:<ul>
<li> возможность выбрать расу (человек/гном/эльф/орк);</li>
<li> +1 к лимиту на изучаемые профессии;</li>
<li> список может пополняться от ваших предложений.</li>',1);
fin();
break;

case 'hram':
msg2('Храм посвещен богу Неспящему и находится в восточной части города.<br>
В храме вы можете:<ul>
<li> пожертвовать золотые монеты на строительство храма;</li>
<li> переродиться;</li>
<li> получить бонус от алтаря.</li>
</ul>
Чем больше уровень храма, тем больше уровень алтаря и тем лучшие благославления можно преобрести.<br>
Уровень алтаря высчитывается по формуле 2+уровень храма, то есть на первом уровне храма уровень алтаря равен 3.<br>
Эффект от храмового алтаря ничем не отличается от кланового, за исключением того, что уровень первого не лимитирован...',1);
fin();
break;


case 'art':
msg2('Арт-эффекты рандомно срабатывают в бою и делают что-то хорошее игроку/плохое противнику.<br>
Они накладываются на вещи мастером рун. На этой странице представлен список всех арт-эффектов и их характеристики.<br>
В скобках необходимый уровень прокачки профессии для наложения.<br><br>
Лечение<br>
С шансом 70% востанавливает от 1.5*уровень до 4*уровень HP.<br>
(Мастер рун: 0+)<br>
Огонь<br>
С шансом 60% наносит врагу урон от 3*уровень до 5*уровень HP<br>
(Мастер рун: 25+)<br>
Исцеление<br>
С шансом 70% востанавливает от 3*уровень до 5*уровень HP<br>
(Мастер рун: 50+)<br>
Лед<br>
С шансом 70% наносит врагу урон от 3*уровень до 5*уровень HP<br>
(Мастер рун: 100+)<br>
Ветер<br>
С шансом 85% наносит врагу урон от 3*уровень до 5*уровень HP<br>
(Мастер рун: 250+)<br>
Пламя<br>
С шансом 70% наносит врагу урон от 5*уровень до 8*уровень HP<br>
(Мастер рун: 500+)<br>
Вампиризм<br>
С шансом 70% передает от врага от 1.5*уровень до 4*уровень HP<br>
(Мастер рун: 1000+)',1);
break;

case 'rasy':
msg2('В Вальдирре существует 4 расы:<ul>
<li> человек;</li>
<li> гном;</li>
<li> эльф;</li>
<li> орк.</li>
</ul>
При создании персонажа, автоматически определяется раса "Человек". Чтобы сменить расу, вам нужно переродиться в храме. Каждая раса дает бонус к определенным профессиям. Вот список этих бонусов.<br>
Человек:<ul>
<li> рыболов растет в два раза быстрее;</li>
<li> рунный мастер растет в два раза быстрее.</li>
</ul>
Гном:<ul>
<li> рудокоп растет в два раза быстрее;</li>
<li> кузнец растет в два раза быстрее;</li>
<li> ювелир растет в два раза быстрее.</li>
</ul>
Эльф:<ul>
<li> пивовар растет в два раза быстрее;</li>
<li> алхимик растет в два раза быстрее;</li>
<li> трава вырастает в два раза быстрее.</li>
</ul>
Орк:<ul>
<li> кожевник растет в два раза быстрее;</li>
<li> лесоруб растет в два раза быстрее.</li>
</ul>',1);
break;

case 'mobs':
msg2('На данной странице представлены параметры мобов для каждого уровня. Для каждого моба прописывается просто определенный коэффициент (например: 0.3, 1.5, 2.7 и т.д.), на который умножается определенный параметр, взятый из таблицы под требуемый уровень.<br>
Уровень, если моб не групповой, определяется в зависимости от уровня игрока. Он может быть равен, может быть меньше, а может быть больше. Кол-во разницы между уровнями влияет на опыт.');
msg2('<table>
<tr>
<th>Уровень</th>
<th>Здоровье</th>
<th>Сила</th>
<th>Ловкость</th>
<th>Интуиция</th>
</tr>
<tr>
<td>1</td>
<td>3</td>
<td>3</td>
<td>15</td>
<td>15</td>
</tr>
<tr>
<td>2</td>
<td>9</td>
<td>11</td>
<td>30</td>
<td>30</td>
</tr>
<tr>
<td>3</td>
<td>18</td>
<td>22</td>
<td>50</td>
<td>50</td>
</tr>
<tr>
<td>4</td>
<td>30</td>
<td>37</td>
<td>65</td>
<td>65</td>
</tr>
<tr>
<td>5</td>
<td>45</td>
<td>56</td>
<td>85</td>
<td>85</td>
</tr>
<tr>
<td>6</td>
<td>60</td>
<td>75</td>
<td>100</td>
<td>100</td>
</tr>
<tr>
<td>7</td>
<td>84</td>
<td>105</td>
<td>125</td>
<td>125</td>
</tr>
<tr>
<td>8</td>
<td>108</td>
<td>135</td>
<td>145</td>
<td>145</td>
</tr>
<tr>
<td>9</td>
<td>135</td>
<td>168</td>
<td>165</td>
<td>165</td>
</tr>
<tr>
<td>10</td>
<td>165</td>
<td>208</td>
<td>190</td>
<td>190</td>
</tr>
<tr>
<td>11</td>
<td>198</td>
<td>247</td>
<td>210</td>
<td>210</td>
</tr>
<tr>
<td>12</td>
<td>234</td>
<td>292</td>
<td>235</td>
<td>235</td>
</tr>
<tr>
<td>13</td>
<td>273</td>
<td>341</td>
<td>255</td>
<td>255</td>
</tr>
<tr>
<td>14</td>
<td>315</td>
<td>393</td>
<td>280</td>
<td>280</td>
</tr>
<tr>
<td>15</td>
<td>360</td>
<td>450</td>
<td>305</td>
<td>305</td>
</tr>
<tr>
<td>16</td>
<td>408</td>
<td>510</td>
<td>330</td>
<td>330</td>
</tr>
<tr>
<td>17</td>
<td>459</td>
<td>573</td>
<td>355</td>
<td>355</td>
</tr>
<tr>
<td>18</td>
<td>513</td>
<td>641</td>
<td>380</td>
<td>380</td>
</tr>
<tr>
<td>19</td>
<td>570</td>
<td>712</td>
<td>410</td>
<td>410</td>
</tr>
<tr>
<td>20</td>
<td>630</td>
<td>787</td>
<td>435</td>
<td>435</td>
</tr>
<tr>
<td>21</td>
<td>693</td>
<td>866</td>
<td>465</td>
<td>465</td>
</tr>
<tr>
<td>22</td>
<td>759</td>
<td>948</td>
<td>495</td>
<td>495</td>
</tr>
<tr>
<td>23</td>
<td>828</td>
<td>1035</td>
<td>520</td>
<td>520</td>
</tr>
<tr>
<td>24</td>
<td>900</td>
<td>1125</td>
<td>555</td>
<td>555</td>
</tr>
<tr>
<td>25</td>
<td>975</td>
<td>1218</td>
<td>585</td>
<td>585</td>
</tr>

</table>',1);
fin();
break;

case 'exp':
msg2('<table>
<tr>
<th>Уровень</th>
<th>Опыта до следующего</th>
<th>Статы</th>
</tr>
<tr>
<td>1</td>
<td>100</td>
<td>11</td>
</tr>
<tr>
<td>2</td>
<td>200</td>
<td>23</td>
</tr>
<tr>
<td>3</td>
<td>300</td>
<td>36</td>
</tr>
<tr>
<td>4</td>
<td>500</td>
<td>50</td>
</tr>
<tr>
<td>5</td>
<td>800</td>
<td>65</td>
</tr>
<tr>
<td>6</td>
<td>1300</td>
<td>81</td>
</tr>
<tr>
<td>7</td>
<td>2100</td>
<td>98</td>
</tr>
<tr>
<td>8</td>
<td>3400</td>
<td>116</td>
</tr>
<tr>
<td>9</td>
<td>5500</td>
<td>135</td>
</tr>
<tr>
<td>10</td>
<td>8900</td>
<td>155</td>
</tr>
<tr>
<td>11</td>
<td>28800</td>
<td>176</td>
</tr>
<tr>
<td>12</td>
<td>46600</td>
<td>198</td>
</tr>
<tr>
<td>13</td>
<td>75400</td>
<td>221</td>
</tr>
<tr>
<td>14</td>
<td>122000</td>
<td>245</td>
</tr>
<tr>
<td>15</td>
<td>177400</td>
<td>270</td>
</tr>
<tr>
<td>16</td>
<td>299400</td>
<td>296</td>
</tr>
<tr>
<td>17</td>
<td>476800</td>
<td>323</td>
</tr>
<tr>
<td>18</td>
<td>776200</td>
<td>351</td>
</tr>
<tr>
<td>19</td>
<td>1253000</td>
<td>380</td>
</tr>
<tr>
<td>20</td>
<td>2290200</td>
<td>410</td>
</tr>
<tr>
<td>21</td>
<td>4923300</td>
<td>441</td>
</tr>
<tr>
<td>22</td>
<td>7967100</td>
<td>473</td>
</tr>
<tr>
<td>23</td>
<td>12890400</td>
<td>506</td>
</tr>
<tr>
<td>24</td>
<td>20857500</td>
<td>540</td>
</tr>
</table>',1);
fin();
break;


default:
knopka2('lib.php?mod=rule', 'Свод законов');
knopka2('lib.php?mod=hram', 'Храм');
knopka2('lib.php?mod=pererod', 'Перерождения');
knopka2('lib.php?mod=rasy', 'Расы');
knopka2('lib.php?mod=profs', 'Профессии');
knopka2('lib.php?mod=kamni', 'Эффекты камней');
knopka2('lib.php?mod=art', 'Арт-эффекты');
knopka2('lib.php?mod=exp', 'Таблица опыта');
knopka2('lib.php?mod=mobs', 'Таблица параметров мобов');
knopka2('lib.php?mod=nalog', 'Клановый налог');
knopka2('lib.php?mod=money', 'Деньги Вальдирры');
knopka2('lib.php?mod=vip', 'VIP статус');
knopka2('lib.php?mod=donate', 'Лотерейные билеты');
break;
endswitch;
if(!empty($_SESSION['auth'])) knopka('loc.php', 'В игру', 1);
else knopka('index.php', 'На главную', 1);
fin();
?>