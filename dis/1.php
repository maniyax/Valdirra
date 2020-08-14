<?php
/*$bgcolor = '#F0F8FF';		//цвет фона
$fontcolor = '#000000';		//цвет шрифта
$divcolor = '#990000';		//цвет полосы с часами
$acolor = '#000080';		//цвет ссылок
$avisited = '#000080';		//цвет посещенных ссылок
$ahover = '#000080';		//цвет ссылок при наведении мыши
$button = '#FF9900';		//цвет кнопок
$manacolor = '#348AE0';		//цвет маны
$logincolor = '#C61A26';	//цвет ника*/

$hpcolor = 'darkgreen';		//цвет жизней
$manacolor = '#082567';		//цвет маны
$logincolor = '#082567';	//цвет ника
$linkcolor = '#505050';	//цвет доп. ссылок
$male = '#082567';			//цвет мужского ника
$female = '#741818';			//цвет женского ника
$notice = '#505050';		//цвет предупреждений
echo '<style type="text/css" title="my_style">';
echo '
body { 
font-size:13px;
margin: 0 auto;
max-width:640px;
font-family: Verdana, Arial, sans-serif;
background:#000 url(pic/bg.png);
}
a { 
color: #fff;
text-decoration: none; 
}
img {
	vertical-align: middle;
	border: 0;
}
a:visited 
{ 
color: #fff; 
text-decoration: none; 
}

a:hover 
{ 
color: #fff; 
text-decoration: none; 
}
.menu_j a {background: #886737 url(pic/bm.png);border-top: 1px solid #dec49e;
text-decoration: none;color: #7559b5; border-bottom: 1px solid #6e5723;color: #000;
text-shadow: 0px 1px 0px #bda065;
}

.menu_j  a:hover {
background: #886737; 

}

a.top_menu_j{display:block;padding:7px;}

.r {text-align:left;
height:10px;border-top: 1px solid #946519;
background-image : url(pic/r.png);
}
textarea {
max-width:490px;
border:1px solid #AABFE8;
background:#fff;
box-shadow:inset 0 1px 2px #BCCEF2;
border-radius:4px;
margin-bottom:2px;
padding:3px;
}

select,input[type=file],input[type=text],input[type=password],input[type=email] {
border:1px solid #7e6128;
background:#dacfbf url(pic/inp.png);
margin-bottom:2px;
border-radius:4px;
padding:3px;
max-width: 250px;
}

input[type=submit] {
border:1px solid #af4035;
background:#961b0e;
border-radius:4px;
margin-left:1px;
color:#ffffff;
box-shadow:inset 0 0 2px #fff;
padding:3px 10px;
}

a.navig {
border:1px solid #af4035;
background:#961b0e;
border-radius:4px;
margin-left:1px;
color:#ffffff;
box-shadow:inset 0 0 2px #fff;
padding:3px 10px;
}

.head {background:url(pic/head.png);
color: #ffffff;
padding: 12px;
}

.menu {
background: #363636 repeat-x top;
border-bottom: 0px solid #442d25;
border-radius: 0px 0px 0px 0px;  
color: #ffffff;
border-top: 0px solid  #442d25;
border-left: 2px solid #442d25;
border-right: 2px solid #442d25;
margin: 0px;
padding: 3px 4px 4px 4px;
}

.foot {
background:  #1c1c1c repeat-x top;
border-bottom: 2px solid #442d25;
border-left: 2px solid #442d25;
border-right: 2px solid #442d25;
border-radius: 0px 0px 4px 4px;  
color: #ffffff;
border-top: 0px solid #442d25;
margin: 0px;
font-size: 9px;
padding: 3px 4px 4px 4px;
}

.board {
background:  #191510 url(pic/bm.png);  
color: #000;border-bottom: 0px solid #ceae74;  
padding: 9px;text-align: center;border-top: 1px solid #ceae74;
word-wrap: break-word;
}

.board2 {
background:  #c3a86b;  
color: #000; 
padding: 9px;text-align: center;border: 1px solid #ceae74;
word-wrap: break-word;
}

.board3 {
background:  #191510 url(pic/jkl.png);  
color: #fff; 
padding: 9px;text-align: center;border-top: 1px solid #ceae74;
word-wrap: break-word;
}

hr {
height: 2px;
border: 0px none;
border-top: dashed 1px #999;
}

.main-knopki {
	display: block;
	margin: 1px 0%;
   margin-left: -3.7px;
   margin-right: -3.7px;
	padding: 7px 12px;
	height: 14px;
	color: #ffffff;
	font-size: 12px;
	text-decoration: none;
	background: #886737 ;
	border: 0.9px solid #464451;
	border-radius: 1px;
}
.main-knopki:hover {
		color: #fffff;
		background: #886737;
	}
	.main-knopki img {
		display: block;
		float: left;
		margin-right: 5px;
		margin-top: 1px;
	}

.bodymenu {
	display: block;
	margin: 1px 0%;
   margin-left: -3.7px;
   margin-right: -3.7px;
	font-size: 13px;
	text-decoration: none;
	background: #1C1C1C ;
	border: 1px solid #442d25;
	border-radius: 5px;
}
.bodymenu:hover {
		background: #1c1c1c;
	}
	.bodymenu img {
		display: block;
		float: left;
		margin-right: 5px;
		margin-top: 1px;
	}
.verx   {
	background: #eb5b13 url(pic/verx.png)repeat-x top left;
	text-align:center;
	height:72px;
	
        }

</style>';
?>
