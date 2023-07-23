<?php
$ver="23.07.22.10";
$prxs = []; #пусто
$text = isset($_POST['text'])?(string)$_POST['text']:'';
@header('Content-Type: text/html; charset=utf-8', true);
if(!empty($_POST['text']))
	foreach (array_filter(explode("\n", $text)) as $line) {
	$s_name = @stristr($line,'#'); #original server name
	if (preg_match('#^(ss|vmess|vless|trojan|sn)://.+#ui',$line)){
		$hash = md5(str_replace($s_name,'',$line)); #from dd1
	
		if (preg_match('#^sn://ssh(.+)#ui',$line)){
		$prxs[$hash] = $line;
		} #sn://ssh?eN…;
	
		if (preg_match('#^ss://([0-9A-z\_\-\+\=]+)\#.+#ui',$line)){
		$prxs["$hash"] = $line;
		} #shadowsocks?! 
	
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
		
		if (preg_match('#^vmess://(.+)#ui',$line,$v)){
		if($vmess=base64_decode($v[1])){
		$tmp = json_decode($vmess,true);
		if (is_array($tmp)) { /*FIX?!?*/
		$s_name = @$tmp["ps"];#"ps":name
		$f_hash = Array(); @ksort($tmp);
		foreach($tmp as $k=>$bla)
		if(($k=='add' || $k=='host' || $k=='id' || $k=='net' || $k=='path' || $k=='port' || $k=='sni' || $k=='type') && $bla){$f_hash[$k]=$bla;}
		$fshh=implode("&",$f_hash);
		$hash=md5($fshh);	#	❗❗
		$prxs["$hash"] = $line; 
		} #json_decoded. Array;
		} #base64
		} #vmess
		
		if(preg_match('#^(vless|trojan)://([0-9A-z\_\-\+\=]+)\@([0-9A-z\_\-\.]+)\:([0-9]+)\/?\?([0-9A-z\_\-\+\=\&\/\%\.]+)#ui',$line,$v)){
		#[0] => (string) [1] => prt [2] => bla [3] => ip_or_url [4] => port [5] => prms
		$s_name = str_replace('+','%20',@stristr($line,'#')); #server name +fix for ' '
		$prms = explode("&",@$v[5]);
		if (is_array($prms)) {
					@ksort($prms);
		$v[5]=@implode("&",$prms); }
		$hash=md5("$v[1]://$v[2]@$v[3]:$v[4]?$v[5]");
		$line="$v[1]://$v[2]@$v[3]:$v[4]?$v[5]".$s_name;
		$prxs["$hash"]=$line;
		}#VLESS|trojan

	}/*preg_*/
}/*foreach*/
if (count($prxs) > 0) {
$text = implode("\n",$prxs);
$text.="\n#" . count($prxs); }
echo '<?xml version="1.0" encoding="utf-8"?>';
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="initial-scale=1,width=device-width" />
<style type="text/css">
	html { color: RoyalBlue; padding: 1%; text-align: center; }
	body { background: WhiteSmoke; margin:auto; max-width:320px; padding: 8px; }
</style>
</head><body>
  <h2 style="margin-top: 1px;">dd2_v<?php echo $ver; ?><br/>ss,trojan,vmess,VLESS</h2>
  <div class="form">
    <form method="post" action="?">
    <textarea name="text" style="max-width:98%;min-height:13em;width:98%" placeholder="Shadowsocks,Trojan,Vmess,VLESS"><?php echo $text; ?></textarea><br />
    <input type="submit" name="send" value="try" style="font-weight: bold; font-size: 16px;" /><br/>
  </div><h5 style="color:gray;">[<a href="https://github.com/S1S13AF7/V2Ray" target="_blank">code</a>;
		<a href="https://freebestshoes.000webhostapp.com/dedub/dd1.php" target="_blank">dd1(old)</a>;
		<b style="color:RoyalBlue; text-decoration:underline;"><u>dd2</u></b>;
		<a href="/sub/2S.php" target="_blank">sub.2S</a>;
		<a href="https://4pda.to/forum/index.php?showtopic=928862" target="_blank">VPN Club</a>]
  </h5>
 </body>
</html>
