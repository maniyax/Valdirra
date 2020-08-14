<?php
##############
# 24.12.2014 #
##############
/*$strtr = $db->query("select hpnow,hpmax,mananow,manamax,exp from `users` where id={$f['id']} limit 1;");
$arc = $strtr->fetch_assoc();
$hpperc = intval($arc['hpnow'] * 100 / $arc['hpmax']);
if($hpperc < 0) $hpperc = 0;
if($hpperc > 100) $hpperc = 100;

$manaperc = intval($arc['mananow'] * 100 / $arc['manamax']);
if($manaperc < 0) $manaperc = 0;
if($manaperc > 100) $manaperc = 100;

$expperc = intval($arc['exp'] * 100 / $exp);
if($expperc < 0) $expperc = 0;
if($expperc > 100) $expperc = 100;

$expstr = '<div style="width:100%;height:4px;border: 0px solid #4f4f4f;border-top: 0px solid #4f4f4f; position:relative;background-color:#38654B;margin: 0px 0px 0px 0px;text-align:center;">
<div style="background-color:#35644A;width:'.$expperc.'%;position:absolute;height:4px;">
</div></div>

<div style="width:100%;height:4px;border: 0px solid #333333;border-top: 1px solid #4f4f4f;position:relative;background-color:#38654B;margin: 0px 0px 0px 0px;text-align:center;">
<div style="background-color:#ee5c42;width:'.$hpperc.'%;position:absolute;height:4px;">
</div></div>

<div style="width:100%;height:4px;border: 0px solid #333333;border-top: 1px solid #4f4f4f;position:relative;background-color:#38654B;margin: 0px 0px 0px 0px;text-align:center;">
<div style="background-color:#8968cd;width:'.$manaperc.'%;position:absolute;height:4px;">
</div></div>';
echo $expstr;*/
?>