<?php
##############
# 24.12.2014 #
##############

//создание боя + получение его ид (return)
function addBoi($s=0)
	{
	$db = DBC::instance();
	$s = intval($s);	//какой бой, 0 = не кровавый
	$q = $db->query("insert into `battle` values(0,1,'','','{$_SERVER['REQUEST_TIME']}','{$_SERVER['REQUEST_TIME']}',0,{$s});");
	return $db->insert_id();
	}

function toBoi($usr,$komanda)
	{
	global $boi_id;
	$db = DBC::instance();
	$q = $db->query("insert into `combat` values(0,'{$usr['login']}',{$usr['sila']},{$usr['inta']},{$usr['lovka']},0,0,0,0,{$usr['lvl']},{$usr['hpnow']},{$usr['hpmax']},{$usr['mananow']},{$usr['manamax']},{$boi_id},0,{$komanda},0,'{$_SERVER['REQUEST_TIME']}','{$_SERVER['REQUEST_TIME']}');");
	$q = $db->query("update `users` set status=1,boi_id={$boi_id},arena_id=0,komanda=0,lastdate='{$_SERVER['REQUEST_TIME']}' where id={$usr['id']} limit 1;");
	return 0;
	}
?>
