<?php
#мегапідписка#beta
$ver="23.07.20.6";
#v2023.7.19.6#beta
$prxs = []; #пусто
$ress = []; #пусто
$text = ''; #пусто
# collect proxy from Sasha and S__ to one sub#optional 2+ sub.
$code = 'https://github.com/S1S13AF7/V2Ray'; #❗DNT CHANGE❗OK?
$res[]='https://freebestshoes.000webhostapp.com/sub/l8.php?txt';
$res[]='https://raw.githubusercontent.com/S1S13AF7/V2Ray/main/928862_118408423.txt';
$res[]='https://raw.githubusercontent.com/sashalsk/V2Ray/main/V2Ray-list-current';
$res[]='https://raw.githubusercontent.com/S1S13AF7/V2Ray/main/928862_118408423.txt';
#сюда аналогічно можна додавать інші (and more)
foreach ($res as $link){

if ($text = file_get_contents($link)){
	#dd2_v2023.07.20.X(https://github.com/S1S13AF7/V2Ray)
	foreach (array_filter(explode("\n", $text)) as $line) {
	$s_name = @stristr($line,'#'); #original server name
	if (preg_match('#^(ss|vmess|vless|trojan|sn)://.+#ui',$line)){
		$hash = md5(str_replace($s_name,'',$line)); #from dd1
		
		if (preg_match('#^sn://ssh(.+)#ui',$line,$v)){
		$prxs[$hash] = $line;
		} #sn://ssh?eN…;
	
		if (preg_match('#^ss://([0-9A-z\_\-\+\=]+)\#.+#ui',$line)){
		$prxs["$hash"] = $line;
		} #shadowsocks?! 
	
		if (preg_match('#^vmess://(.+)#ui',$line,$v)){
		if($vmess=base64_decode($v[1])){
		$tmp = json_decode($vmess,true);
		if (is_array($tmp)) { /*FIX?!?*/
		$s_name = @$tmp["ps"];#"ps":name
		$f_hash = 'vmess://';//.=bla.bla
		unset($tmp["ps"]); ksort($tmp);
		foreach($tmp as $k=>$v){
		if(!$v)unset($tmp[$k]);}
		ksort($tmp);/*сортуємо*/
		$fshh=implode("&",$tmp);
		$hash=md5($fshh);	#	всеодно воно фіговенько працює,да;
		$prxs["$hash"] = $line; 
		} #json_decoded. Array;
		} #base64
		} #vmess
		
		if (preg_match('#^ss://([0-9A-z\_\-\+\=]+)\@([0-9A-z\_\-\.]+)\:([0-9]+)\#.+#ui',$line,$v)){
		#[0] => ss://str@str:int#bla [1] => base64 [2] => ip_or_url [3] => port
		if($mp=array_filter(explode(":",base64_decode($v[1])))){/*decode $m:$p*/
		$s_name = str_replace('+','%20',@stristr($line,'#')); #server name+fix.
		$line="ss://$v[1]@$v[2]:$v[3]" .@$s_name;
		if (count($mp)==2){
			$method=$mp[0];
			$passw0=$mp[1];
			$v[1]=base64_encode($method.':'.$passw0);
			//різні програми по різному кодували о_О;
			$hash=md5("ss://$v[1]@$v[2]:$v[3]");
			$prxs["$hash"] = $line;
		}//count($mp)===2
		}//decoded($m:$p)
		} #shadowsocks
		
		if(preg_match('#^(vless|trojan)://([0-9A-z\_\-\+\=]+)\@([0-9A-z\_\-\.]+)\:([0-9]+)\/?\?([0-9A-z\_\-\+\=\&\/\%\.]+)#ui',$line,$v)){
		#[0] => (string) [1] => prt [2] => bla [3] => ip_or_url [4] => port [5] => prms
		$s_name = str_replace('+','%20',@stristr($line,'#')); #server name +fix for ' '
		$prms = explode("&",@$v[5]);
		if (is_array($prms)) {
					@ksort($prms);
		$v[5]=@implode("&",$prms); }
		$hash=md5("$v[1]://$v[2]@$v[3]:$v[4]?$v[5]");
		$prxs["$hash"]=$line;
		}#VLESS|trojan

	}/*preg_*/
	}//foreach
}#text(link)
}/*foreach*/
$link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'?"https":"http")."://$_SERVER[HTTP_HOST]$_SERVER[SCRIPT_NAME]";
@header('Content-Type: text/plain; charset=utf-8', true);
echo "#shadowsocks #ss #vmess #VLESS #trojan
#➖➖➖➖➖➖➖➖➖➖➖➖➖➖➖➖➖➖
# sub. https://raw.githubusercontent.com/S1S13AF7/V2Ray/main/928862_118408423.txt
# sub. https://raw.githubusercontent.com/sashalsk/V2Ray/main/V2Ray-list-current
# sub. {$link}
# CODE {$code}
# ver.:{$ver}
#➖➖➖➖➖➖➖➖➖➖➖➖➖➖➖➖➖➖
";
if (count($prxs) > 0)
$text = implode("\n",$prxs);
$text.="\n#" . count($prxs);
exit($text);
?>