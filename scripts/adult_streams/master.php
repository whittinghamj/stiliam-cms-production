<?php

/* examples

http://127.0.0.1/adults/master.php?id=brazzers-tv-europe&f=.m3u8
http://127.0.0.1/adults/master.php?id=playboy-tv&f=.m3u8
http://127.0.0.1/adults/master.php?id=pink-o-tv&f=.m3u8
http://127.0.0.1/adults/master.php?id=hustler-hd&f=.m3u8 */

$t= htmlspecialchars($_GET['id']);
$url=m3u8('http://hochu.tv/'.$t.'.html');
$php= search($url,'cdntvnet.com/','.php');
$stream=m3u82('http://cdntvnet.com/'.$php.'.php',$t);
$streamurl=search($stream,'file:"','"});');

//$streamurl=str_replace('http','http://ip-server-vps/adults/up.php/http',$streamurl);

header ("Location: $streamurl");

function m3u8($url)
{
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch,CURLOPT_ENCODING, '');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
'Accept-Encoding: gzip, deflate',
'Accept-Language: es-ES,es;q=0.9,fr;q=0.8',
'Cache-Control: max-age=0',
'Connection: keep-alive',
'Host: hochu.tv',
'Referer: http://hochu.tv/',
'Upgrade-Insecure-Requests: 1',
'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.142 Safari/537.36'
    ));
$server_output = urldecode(curl_exec($ch));
curl_close ($ch);
return $server_output;
}
function m3u82($url2,$ideee)
{
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url2);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch,CURLOPT_ENCODING, '');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
'Accept-Encoding: gzip, deflate',
'Accept-Language: es-ES,es;q=0.9,fr;q=0.8',
'Connection: keep-alive',
'Host: cdntvnet.com',
'Referer: http://hochu.tv/'.$ideee.'.html',
'Upgrade-Insecure-Requests: 1',
'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.142 Safari/537.36'

    ));
$server_output = urldecode(curl_exec($ch));
curl_close ($ch);
return $server_output;

}
function search($string, $start, $end){
	$string = " ".$string;
	$ini = strpos($string,$start);
	if ($ini == 0) return "";
	$ini += strlen($start);   
	$len = strpos($string,$end,$ini) - $ini;
	return substr($string,$ini,$len);
}
