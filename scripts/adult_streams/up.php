<?php
function getHostnamePattern($hostname) {
  $escapedHostname = str_replace(".", "\.", $hostname);
  return "@^https?://([a-z0-9-]+\.)*" . $escapedHostname . "@i";
}
function removeKeys(&$assoc, $keys2remove) {
  $keys = array_keys($assoc);
  $map = array();
  foreach ($keys as $key) {
     $map[strtolower($key)] = $key;
  }
  foreach ($keys2remove as $key) {
    $key = strtolower($key);
    if (isset($map[$key])) {
       unset($assoc[$map[$key]]);
    }
  }
}
if (!function_exists("getallheaders")) {
  function getallheaders() {
    $result = array();
    foreach($_SERVER as $key => $value) {
      if (substr($key, 0, 5) == "HTTP_") {
        $key = str_replace(" ", "-", ucwords(strtolower(str_replace("_", " ", substr($key, 5)))));
        $result[$key] = $value;
      }
    }
    return $result;
  }
}
define("PROXY_PREFIX", "http" . (isset($_SERVER['HTTPS']) ? "s" : "") . "://" . $_SERVER["SERVER_NAME"] . ($_SERVER["SERVER_PORT"] != 80 ? ":" . $_SERVER["SERVER_PORT"] : "") . $_SERVER["SCRIPT_NAME"] . "/");
function makeRequest($url) {
  $user_agent = "vsaClient/1.0.6 (Linux;Android 5.1.1) ExoPlayerLib/1.5.14";
  if (empty($user_agent)) {
    $user_agent = "Mozilla/5.0 (QtEmbedded; U; Linux; C) AppleWebKit/533.3 (KHTML, like Gecko) MAG200 stbapp ver: 4 rev: 1812 Mobile Safari/533.3";
  }
  $ch4 = curl_init();
  curl_setopt($ch4, CURLOPT_USERAGENT, $user_agent);
  $browserRequestHeaders = getallheaders();
  removeKeys($browserRequestHeaders, array(
    "Host",
    "Content-Length",
    "Accept-Encoding"
  ));
  curl_setopt($ch4, CURLOPT_ENCODING, "");
  $curlRequestHeaders = array();
  foreach ($browserRequestHeaders as $name => $value) {
    $curlRequestHeaders[] = $name . ": " . $value;
  }
  curl_setopt($ch4, CURLOPT_HTTPHEADER, $curlRequestHeaders);
  switch ($_SERVER["REQUEST_METHOD"]) {
    case "POST":
      curl_setopt($ch4, CURLOPT_POST, true);
      curl_setopt($ch4, CURLOPT_POSTFIELDS, file_get_contents('php://input'));
    break;
    case "PUT":
      curl_setopt($ch4, CURLOPT_PUT, true);
      curl_setopt($ch4, CURLOPT_INFILE, fopen('php://input', 'r'));
    break;
  }
  curl_setopt($ch4, CURLOPT_HEADER, true);
  curl_setopt($ch4, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch4, CURLOPT_RETURNTRANSFER, true);
  curl_setopt ($ch4, CURLOPT_FAILONERROR, true);
  curl_setopt($ch4, CURLOPT_URL, $url);
  $response = curl_exec($ch4);
  $responseInfo = curl_getinfo($ch4);
  $headerSize = curl_getinfo($ch4, CURLINFO_HEADER_SIZE);
  curl_close($ch4);
  $responseHeaders = substr($response, 0, $headerSize);
  $responseBody = substr($response, $headerSize);
  return array("headers" => $responseHeaders, "body" => $responseBody, "responseInfo" => $responseInfo);
}
function rel2abs($rel, $base) {
  if (empty($rel)) $rel = ".";
  if (parse_url($rel, PHP_URL_SCHEME) != "" || strpos($rel, "//") === 0) return $rel;
  if ($rel[0] == "#" || $rel[0] == "?") return $base.$rel;
  extract(parse_url($base));
  $path = isset($path) ? preg_replace('#/[^/]*$#', "", $path) : "/";
  if ($rel[0] == '/') $path = "";
  $port = isset($port) && $port != 80 ? ":" . $port : "";
  $auth = "";
  if (isset($user)) {
    $auth = $user;
    if (isset($pass)) {
      $auth .= ":" . $pass;
    }
    $auth .= "@";
  }
  $abs = "$auth$host$path$port/$rel";
  for ($n = 1; $n > 0; $abs = preg_replace(array("#(/\.?/)#", "#/(?!\.\.)[^/]+/\.\./#"), "/", $abs, -1, $n)) {} 
  return $scheme . "://" . $abs;
}
$url = substr($_SERVER["REQUEST_URI"], strlen($_SERVER["SCRIPT_NAME"]) + 1);
$response = makeRequest($url);
$rawResponseHeaders = $response["headers"];
$responseBody = $response["body"];
$responseInfo = $response["responseInfo"];
$header_blacklist_pattern = "/^Content-Length|^Transfer-Encoding|^Content-Encoding.*gzip/i";
$responseHeaderBlocks = array_filter(explode("\r\n\r\n", $rawResponseHeaders));
$lastHeaderBlock = end($responseHeaderBlocks);
$headerLines = explode("\r\n", $lastHeaderBlock);
foreach ($headerLines as $header) {
  $header = trim($header);
  if (!preg_match($header_blacklist_pattern, $header)) {
    header($header);
  }
}
header('X-Robots-Tag: noindex, nofollow');
$contentType = "";
header("Content-Length: " . strlen($responseBody));
echo $responseBody;
?>