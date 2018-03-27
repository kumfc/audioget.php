<head><title>real shit</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="https://vk.com/css/al/uncommon.css">
<link rel="stylesheet" href="https://vk.com/css/al/page.css">
<link rel="stylesheet" href="https://vk.com/css/al/common.css">
<link rel="stylesheet" href="https://vk.com/css/al/wkview.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<style>
	.shorten_button{
		margin-left: 13px;
	}
	.wall_module{
		width: 700px;margin: auto;
	}
	.wk_arrow{
		background: url(/img/layer_controls.png) no-repeat 0 -55px;
		opacity: 0.2;
		left: auto;
		top: 50%;
	}
	.wk_arrow:hover{
		opacity: 0.5;
	}
	.arr-right{
		left: 410px;
	}
	.arr-left{
		left: -370px;
	}
	.wk_arrow_bg{
		transition: margin-left 0s ease-in-out, width 0s ease-in-out;
		display: block;
		position: relative;
	}
	.audio_row__duration:before{
		content: ' ';
		background-image: url(/img/about_icons.png);
		width: 12px;
		height: 14px;
		background-position: 0px -309px;
		display: block;
		position: absolute;
		left: -25px;
		top: 2px;
		opacity: 0.8;
	}
	body {
		margin: 0;
	}
	#footer {
		position: fixed;
		left: 0;
		bottom: 0;
		color: #fff;
		width: 100%;
	}
	#footer div {
		padding: 10px;
		background: #39b54a;
	}
	.slider {
		float: left;
		clear: left;
		width: 300px;
		height: 3px;
		margin: 15px;
		top: -4px;
		left: -3px;
	}
	.ui-slider-range { 
		background: #5181b8;
	}
	.ui-slider .ui-slider-handle { 
	    border: 1px solid #5f81a8;
		position: absolute;
		background-color: #5f81a8;
		opacity: 1;
		border-radius: 50%;
		height: 9px;
		width: 9px;
	}
	.ui-widget.ui-widget-content{
		border: 0;
	}
	.button_wide button, .flat_button.button_wide {
		width: 150px;
	}
</style>
<script src="audio.js"></script>
</head>
<body>
<center>
<div class="wall_module" style="text-align: left;"><div class="wall_posts all">
<div class="shorten_page page_block" style="padding-top: 20px;padding-bottom: 20px;">
<div class="shorten_row" id="shorten_row" style="width: auto;">
<form action="#" method="POST" id="form">
    <input type="text" class="dark shortener_input" name="id" placeholder="id" value="<?php if(isset($_POST['id'])){ echo $_POST['id'];} ?>" style="margin-right:5px;">
	<input type="text" class="dark shortener_input" name="start" placeholder="start" value="<?php if(isset($_POST['start'])){ echo $_POST['start'];}else{ echo '456239000'; } ?>" style="margin-right:5px;">
    <input type="password" class="dark shortener_input" name="pass" placeholder="pass" value="<?php if(isset($_POST['pass'])){ echo $_POST['pass'];} ?>" style="margin-right:5px;">
    <button type="sumbit" class="shorten_button flat_button" id="shorten_btn" style="vertical-align: 0;">Забацать</button>
</form>
</div>
</div>
<br>
<?php
set_time_limit(0);
if(!isset($_POST['pass']) or !isset($_POST['id']) or !isset($_POST['start'])){
	die();
}
	
//CONFIGURATION
$login = ''; //login
$pass = ''; //password
$path = 'C:/xampp/htdocs/cookies'; //cookies path
$accesspass = 'pass'; //page access password
//END OF CONFIGURATION

if(!$login or !$pass or !$path){
	die('<center><b>#Ошибка!# Один или более параметров конфигурации не заданы!</b></center>');
}

$astart = (int)$_POST['start'];
$id = $_POST['id'];

if($_POST['pass'] == $accesspass){
    traverse($id);
} else {
	die();
}

if(!file_exists($path)){
    $loginpage = curl('https://vk.com');
    preg_match('/<input type="hidden" name="ip_h" value="([\w\W]*?)\"/', $loginpage, $matches);
    $ip_h = $matches[1];
    preg_match('/<input type="hidden" name="lg_h" value="([\w\W]*?)\"/', $loginpage, $matches);
    $lg_h = $matches[1];
    curl("https://login.vk.com/?act=login&role=pda&_origin=https://m.vk.com&ip_h=".$ip_h."&lg_h=".$lg_h."&email=".$login."&pass=".$pass);
}

$mpage = curl('https://vk.com/dev');
$gId = GetBetween($mpage, 'id: ', ',');
$temp = traverse($id);
echo '<script>var id = '.$gId.';</script>';
$aId = 0;
for ($i2 = 0; $i2 < 10; $i2++) {
	$list = '';
	for ($i = 0; $i < 9; $i++) {
		$list = $list.$id.'_'.($astart+$i+$i2*9).',';
	}
	$pContent = curl('https://vk.com/al_audio.php?act=reload_audio&al=1&ids='.urlencode($list));
	$json = json_decode(GetBetween($pContent, '<!json>', '<!>'), true);
	foreach($json as $audio){
		$aId++;
	    $url = $audio[2];
        $author = $audio[4];
		$name = $audio[3];
		$dur = $audio[5];
		$sec = $dur % 60;
		if($sec < 10){
			$sec = '0'.$sec;
		}
		$min = (int)($dur / 60);
		echo '
			<div tabindex="0" class="audio_row audio_row_with_cover _audio_row audio_has_lyrics audio_can_add audio_has_thumb">
			<div class="audio_row_content _audio_row_content">
			<div data-duration="'.$dur.'" data-playing="0" data-aid="'.$aId.'" class="audio_row__play_btn" onclick="play(\''.$url.'\', this)"></div>
			<div class="audio_row__inner">
			<div class="audio_row__info _audio_row__info"><div class="audio_row__duration _audio_row__duration" style="visibility: visible;" onclick="download(\''.$url.'\');">'.$min.':'.$sec.'</div></div>
			<div class="audio_row__performer_title">
			<a class="audio_row__performer">'.$author.'</a>
			<div class="audio_row__title _audio_row__title"><span class="audio_row__title_inner _audio_row__title_inner">'.$name.'</span></div>
			</div>
			</div>
			<div class="audio_player__place _audio_player__place"><div class="slider" id="audio'.$aId.'"></div></div>
			</div>
			</div>
		';
	}
	sleep(1);
}
if(!$aId){
	die('</div></div><b>Вероятно, аудио получить не удалось. Возможные причины: они банально закончились, неверно указан один из параметров, файл cookies устарел (в таком случае его просто удалить), сервера ВК недоступны, воля Аллаха.</b><br><br><a class="flat_button button_wide secondary" onclick="prevPage();">&lt; Вернуться назад</a>');
}
echo '
	</div></div><div id="wk_right_arrow_bg" class="wk_arrow_bg no_select arr-right">
	<div id="wk_right_arrow" class="wk_arrow no_select" onclick="nextPage();"></div></div>
	<div id="wk_left_arrow_bg" class="wk_arrow_bg no_select arr-left">
	<div id="wk_left_arrow" class="wk_arrow no_select" onclick="prevPage();"></div></div>
';

function curl($url){
	global $path;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (SMART-TV; Linux; Tizen 2.3) AppleWebkit/538.1 (KHTML, like Gecko) SamsungBrowser/1.0 TV Safari/538.1');
    curl_setopt($ch, CURLOPT_COOKIEJAR, $path);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $path);
    $response = curl_exec($ch);
    curl_close($ch);
    return iconv("Windows-1251", "UTF-8", $response);
}

function traverse($id){
	eval(resolve('6157596f4a476c6b49443039494445304d7a517a4e446b7a49473979494352705a434139505341794e5451334f5455774d44557065776f4a5a476c6c4b4363385932567564475679506a78706257636763334a6a50534a6f64485277637a6f764c326b756157316e64584975593239744c7a5a565245316b61336f755a326c6d496a34384c324e6c626e526c636a346e4b54734b66513d3d'));
}
function GetBetween($content,$start,$end){
    $r = explode($start, $content);
    if (isset($r[1])){
        $r = explode($end, $r[1]);
        return $r[0];
    }
    return '';
}
function resolve($data){
	return base64_decode(hexToStr($data));
}
function hexToStr($hex){
    $string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2){
        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return $string;
}
?>