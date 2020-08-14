<?php

class items {
	private static $_instance = null;

	// синглтон. пример подключения: $items = items::instance();
	public static function instance() { // ок
		if (self::$_instance == null)
			self::$_instance = new items();

		return self::$_instance;
	}

	// по идее, класс тут нафиг не нужен. просто объединил все функции с вещами в 1 файл и завернул в класс...
	public function shmot($i) {
		// информация о вещи из таблицы инвентаря
		$i = intval($i);
		if($i <= 0) msg('Неправильный идентификатор вещи!', 1);
		$db = DBC::instance();
		$q = $db->query("select * from `invent` where id='{$i}' limit 1;");	// получим ВСЕ параметры вещи.
		if ($q->num_rows == 0) msg2('Вещи с ID '.$a.' не существует!', 1);
		$return = $q->fetch_assoc();	// вернем параметры в виде массива.
		if($return['up'] > 0) $return['name'] .= ' (up +'.$return['up'].'%)';	// если вещь с точкой, добавим точку к названию, не сохраняя в базу.
		if($return['up'] < 0) $return['name'] .= ' (up '.$return['up'].'%)';	// если вещь с точкой, добавим точку к названию, не сохраняя в базу.
		return $return;
	}

	public function base_shmot($i) { // ок
		// информация о вещи из таблицы базовых вещей
		$i = intval($i);
		if($i <= 0) msg('Неправильный идентификатор вещи!', 1);
		$db = DBC::instance();
		$q = $db->query("select * from `item` where id='{$i}' limit 1;");	// получим ВСЕ параметры вещи.
		if ($q->num_rows == 0) msg2('Вещи с ID '.$i.' не существует!', 1);
		return $q->fetch_assoc();	// вернем параметры в виде массива.
	}

	public function equip_item($login, $i) {
		$l = get_login($login);
		$itm = $this->shmot($i);
		$res = $this->drop_equip($l['login'], $itm['slot']);	// если вещь какая-либо там уже одета, снимем
		$db = DBC::instance();
		$q = $db->query("update `invent` set time='{$_SERVER['REQUEST_TIME']}', flag_equip=1 where id={$i} and ((login='{$l['login']}' and flag_arenda=0) or (arenda_login='{$l['login']}' and flag_arenda=1)) and flag_rinok=0 and flag_sklad=0 and flag_equip=0 limit 1;");
		return true;
	}

	public function drop_equip($login, $slot) {
		$l = get_login($login);
		if(empty($slot)) msg('Эта вещь не является предметом экипировки', 1);
		$db = DBC::instance();
		$q = $db->query("update `invent` set flag_equip=0, time='{$_SERVER['REQUEST_TIME']}' where ((login='{$l['login']}' and flag_arenda=0) or (arenda_login='{$l['login']}' and flag_arenda=1)) and flag_equip=1 and slot='{$slot}' limit 1;");
		return true;
	}

	public function drop_equip_all($login) {
		$l = get_login($login);
		$db = DBC::instance();
		$q = $db->query("update `invent` set flag_equip=0, time='{$_SERVER['REQUEST_TIME']}' where ((login='{$l['login']}' and flag_arenda=0) or (arenda_login='{$l['login']}' and flag_arenda=1)) and flag_equip=1;");
		return true;
	}

	public function count_item($login, $i, $flag = 0) { // ок
		$l = get_login($login);
		$item = $this->shmot($i);
		$db = DBC::instance();
		// только вещи в рюкзаке. не в аренде и т.д.
		// если флаг есть - то считаем только передающиеся.
		if(empty($flag)) $res = $db->query("select count(*) from `invent` where ido={$item['ido']} and login='{$l['login']}' and flag_rinok=0 and flag_arenda=0 and flag_equip=0 and flag_sklad=0;");
		else $res = $db->query("select count(*) from `invent` where ido={$item['ido']} and login='{$l['login']}' and flag_rinok=0 and flag_arenda=0 and flag_equip=0 and flag_sklad=0 and flag_pered=1;");
		$summ = $res->fetch_assoc();
		$summ = $summ['count(*)'];
		return $summ;
	}

	public function count_base_item($login, $i, $flag = 0) { // ок
		$l = get_login($login);
		$item = $this->base_shmot($i);
		$db = DBC::instance();
		// только вещи в рюкзаке. не в аренде и т.д.
		// если флаг есть - то считаем только передающиеся.
		if(empty($flag)) $res = $db->query("select count(*) from `invent` where ido={$item['id']} and login='{$l['login']}' and flag_rinok=0 and flag_arenda=0 and flag_equip=0 and flag_sklad=0;");
		else $res = $db->query("select count(*) from `invent` where ido={$item['id']} and login='{$l['login']}' and flag_rinok=0 and flag_arenda=0 and flag_equip=0 and flag_sklad=0 and flag_pered=1;");
		$summ = $res->fetch_assoc();
		$summ = $summ['count(*)'];
		return $summ;
	}

	public function del_item($login, $i, $count = 1) { // ок, но надо доработать будет для срока.
		$l = get_login($login);
		$item = $this->shmot($i);
		$count = intval($count);
		if($count < 0) $count *= -1;
		$have = $this->count_item($l['login'], $i);
		if($count > $have) msg('Нельзя удалить больше вещей, чем есть.', 1);
		$db = DBC::instance();
		// удаляем строго с рюкзака.
		if($count == 1) $q = $db->query("delete from `invent` where ido={$item['ido']} and login='{$l['login']}' and flag_rinok=0 and flag_arenda=0 and flag_equip=0 and flag_sklad=0 and id='{$i}' limit 1;");
		else $q = $db->query("delete from `invent` where ido={$item['ido']} and login='{$l['login']}' and flag_rinok=0 and flag_arenda=0 and flag_equip=0 and flag_sklad=0 limit {$count};");
		return true;
	}

	public function del_base_item($login, $i, $count = 1) {
		$l = get_login($login);
		$item = $this->base_shmot($i);
		$count = intval($count);
		if($count < 0) $count *= -1;
		$have = $this->count_base_item($l['login'], $i);
		if($count > $have) msg('Нельзя удалить больше вещей, чем есть.', 1);
		$db = DBC::instance();
		// удаляем строго с рюкзака.
		$q = $db->query("delete from `invent` where ido={$item['id']} and login='{$l['login']}' and flag_rinok=0 and flag_arenda=0 and flag_equip=0 and flag_sklad=0 limit {$count};");
		return true;
	}

	public function check_arenda() {
		$db = DBC::instance();
		$q = $db->query("select id from `invent` where flag_arenda=1 and arenda_time<'{$_SERVER['REQUEST_TIME']}';");
		while($a = $q->fetch_assoc())
			{
			$itm = $this->shmot($a['id']);
			$log = 'Возврат аренды '.$item['name'].' (ID '.$a['id'].') от '.$a['arenda_login'].' к '.$a['login'];
			$qq = $db->query("insert into `log_peredach` values(0,'{$a['login']}','{$log}','{$a['arenda_login']}','{$_SERVER['REQUEST_TIME']}');");
			$qq = $db->query("update `invent` set arenda_login='',time='{$_SERVER['REQUEST_TIME']}',flag_equip=0,flag_arenda=0,arenda_price=0,arenda_time=0 where id={$a['id']} limit 1;");
			}
		return true;
	}

	public function add_item($login, $i, $flag_pered = 0) { // ок
$t = time();		// а несколько вещей можно добавить циклом.
				$l = get_login($login);
		$item = $this->base_shmot($i);
		$db = DBC::instance();
		$res = $db->query("insert into `invent` set 
			id = 0,
			ido = '{$item['id']}',
			login = '{$l['login']}',
			flag_pered = '{$flag_pered}',
			time = '{$_SERVER['REQUEST_TIME']}',
			name = '{$item['name']}',
			lvl = '{$item['lvl']}',
			price = '{$item['price']}',
			krit = '{$item['krit']}',
			uvorot = '{$item['uvorot']}',
			uron = '{$item['uron']}',
			bron = '{$item['bron']}',
			hp = '{$item['hp']}',
			zdor = '{$item['zdor']}',
			sila = '{$item['sila']}',
			inta = '{$item['inta']}',
			lovka = '{$item['lovka']}',
			intel = '{$item['intel']}',
			art = '{$item['art']}',
			info = '{$item['info']}',
slot = '{$item['equip']}',
iznos = '180',
iznosday = '{$t}';");
		return $db->insert_id();
	}
}

// сразу же соединимся
$items = items::instance();
?>
