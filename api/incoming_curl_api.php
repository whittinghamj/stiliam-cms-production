<?php

/**
 * Simple request response script
 *
 * Show all incoming data, eg. to test cURL requests
 *
 */

echo 'API'. PHP_EOL . '==='. PHP_EOL;

echo 'Request Time: ' . time() . PHP_EOL;

echo 'Request Method: ' . print_r($_SERVER['REQUEST_METHOD'], true) . PHP_EOL;

if(FALSE === empty($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
	echo 'Request Header Method: ' . print_r($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'], true) . PHP_EOL;
}

echo 'Server Data: ' . print_r($_SERVER, true) . PHP_EOL;

echo 'Request Files: ' . print_r($_FILES, true) . PHP_EOL;

echo 'Request Data: ' . PHP_EOL;
// Will only work with GET & POST
echo 'GET/POST: ' . print_r($_REQUEST, true) . PHP_EOL;
// …DELETE & PUT are not converted into PHP superglobals automatically!
// Note: input stream may be accessed only once!
parse_str(file_get_contents('php://input'), $_DELETE);
echo 'DELETE/PUT: ' . print_r($_DELETE, true) . PHP_EOL;
