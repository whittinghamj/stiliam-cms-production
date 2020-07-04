<?php

//define('ERROR_EMAIL_TO', 'admin@mydomain.com');
//define('DISPLAY_ERRORS', TRUE);
// Or you can set this constants in your application

ob_start();

set_exception_handler('exception_handler');

# set up error_handler() as the new default error handling function
set_error_handler('error_handler', E_ALL);
//set_error_handler('error_handler', E_STRICT);

function exception_handler($exception) {

	$errorPage = '<div style="font: 15px/22px Arial, Sans-Serif;
		padding: 15px; text-align: left; background-color: #fff;
		margin-bottom: 20px;">';

	// General Information about Error
	$errorPage .= '<fieldset style="border: 2px solid #f00;
		background-color: #fcc; padding: 0 20px;
	 	border-radius: 10px; -moz-border-radius: 10px;
		-webkit-border-radius: 10px;">

		<legend style="font-size: 22px; border: 2px solid #f00;
			background-color: #fcc; padding: 5px 10px;
			border-radius: 10px; -moz-border-radius: 10px;
			-webkit-border-radius: 10px;">Unhandled Exception</legend>
		<h3>' . $exception->getMessage() . '</h3>';

	// File and uri information
	if (isset($_SERVER['SERVER_NAME']) && isset($_SERVER['REQUEST_URI'])) {
		$url = 'http://'.$_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		$errorPage .= '<p>';
		$errorPage .= 'URL: <a href="' . $url . '"><b>' . htmlspecialchars($url) .' </b></a><br/>';
		//$errorPage .= 'File: <strong>'.htmlentities( $file ).' [Line '.$line.']';
		$errorPage .= '</p>';
	}

	$errorPage .= '
	</fieldset>';

	// Server and environment information
	$errorPage .= '<div style="border: 2px solid #999; margin-top: 20px;
		background-color: #efefef; padding: 0 20px 20px 20px;
	 	border-radius: 10px; -moz-border-radius: 10px;
		-webkit-border-radius: 10px;">

		<h3>Server and Environment Information</h3>';
	$errorPage .= error_print_context(array(
			'Server IP'    => $_SERVER['SERVER_ADDR'],
			'Client IP'    => $_SERVER['REMOTE_ADDR'],
			'Referer'      => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL,
			'Method'       => $_SERVER['REQUEST_METHOD'],
		), 0, TRUE);

	if (count($_GET) > 0) {
		$errorPage .= '<br /><hr size="1" /><h3>GET Array</h3>';
		$errorPage .= error_print_context($_GET, 0, TRUE);
	}

	if (count($_POST) > 0) {
		$errorPage .= '<br /><hr size="1" /><h3>GET Array</h3>';
		$errorPage .= error_print_context($_POST, 0, TRUE);
	}

	$errorPage .= '<br /><hr size="1" />
		<h3>Stack Backtrace</h3>';
	$errorPage .= error_render_stack_trace(NULL, $exception->getTrace());

	$errorPage .= '
	</div>';

	$errorPage .= '</div>';


	if (!defined('DISPLAY_ERRORS')
		OR (defined('DISPLAY_ERRORS') AND DISPLAY_ERRORS)) {
		header('HTTP/1.1 500 Internal Server Error');
		print $errorPage;
	}
	else {
		if (defined("ERROR_EMAIL_TO")) {
			mail(ERROR_EMAIL_TO,
				'[Error] Unhandled Exception',
				$errorPage,
				"Content-type: text/html; charset=utf-8\r\n" .
					"From: Error Mailer <error@mydomain.com>\r\n"
			);
		}
		
		header('HTTP/1.1 500 Internal Server Error');
		readfile('error.html');
	}

	exit;
}

function error_handler($type, $error, $file, $line, $context) {
	$stop_script = FALSE;

	$project_dir   = realpath('../');
	$relative_file = substr(realpath($file), strlen($project_dir));

	# Build an appropriate error type stying
	switch ($type) {
		case E_WARNING:
			$error_type = 'SYSTEM WARNING';
			$stop_script = TRUE;
			break;
		case E_NOTICE:
			$error_type = 'SYSTEM NOTICE';
			break;
		case E_USER_ERROR:
			$error_type = "APPLICATION ERROR";
			$stop_script = TRUE;
			break;
		case E_USER_WARNING:
			$error_type = "APPLICATION WARNING";
			$stop_script = TRUE;
			break;
		case E_USER_NOTICE:
			$error_type = 'APPLICATION NOTICE';
			break;
		default:
			$error_type = 'ERROR #' . $type;
	}

	if (is_numeric($error)) {
		$error_type       .= " #" . $error;
		$error_description = NULL;
	}
	else {
		$error_description = nl2br($error);
	}

	
	$errorPage = '<div style="font: 15px/22px Arial, Sans-Serif;
		padding: 15px; text-align: left; background-color: #fff;
		margin-bottom: 20px;">';

	// General Information about Error
	$errorPage .= '<fieldset style="border: 2px solid #f00;
		background-color: #fcc; padding: 0 20px;
	 	border-radius: 10px; -moz-border-radius: 10px;
		-webkit-border-radius: 10px;">

		<legend style="font-size: 22px; border: 2px solid #f00;
			background-color: #fcc; padding: 5px 10px;
			border-radius: 10px; -moz-border-radius: 10px;
			-webkit-border-radius: 10px;">' . $error_type . '</legend>
		<h3>' . $error_description . '</h3>';

	// File and uri information
	if (isset($_SERVER['SERVER_NAME']) && isset($_SERVER['REQUEST_URI'])) {
		$url = 'http://'.$_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		$errorPage .= '<p>';
		$errorPage .= 'URL: <a href="' . $url . '"><b>' . htmlspecialchars($url) .' </b></a><br/>';
		$errorPage .= 'File: <strong>'.htmlentities( $file ).' [Line '.$line.']';
		$errorPage .= '</p>';
	}

	$errorPage .= '
	</fieldset>';

	// Server and environment information
	$errorPage .= '<div style="border: 2px solid #999; margin-top: 20px;
		background-color: #efefef; padding: 0 20px 20px 20px;
	 	border-radius: 10px; -moz-border-radius: 10px;
		-webkit-border-radius: 10px;">

		<h3>Server and Environment Information</h3>';
	$errorPage .= error_print_context(array(
			'Server IP'    => $_SERVER['SERVER_ADDR'],
			'Client IP'    => $_SERVER['REMOTE_ADDR'],
			'Referer'      => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL,
			'Method'       => $_SERVER['REQUEST_METHOD'],
		), 0, TRUE);

	if (count($_GET) > 0) {
		$errorPage .= '<br /><hr size="1" /><h3>GET Array</h3>';
		$errorPage .= error_print_context($_GET, 0, TRUE);
	}

	if (count($_POST) > 0) {
		$errorPage .= '<br /><hr size="1" /><h3>GET Array</h3>';
		$errorPage .= error_print_context($_POST, 0, TRUE);
	}

	$errorPage .= '<br /><hr size="1" />
		<h3>Stack Backtrace</h3>';
	$errorPage .= error_render_stack_trace();

	$errorPage .= '
	</div>';

	$errorPage .= '</div>';


	if (!defined('DISPLAY_ERRORS')
		OR (defined('DISPLAY_ERRORS') AND DISPLAY_ERRORS)) {
		if ($stop_script) {
			header('HTTP/1.1 500 Internal Server Error');
			print $errorPage;
			exit;
		}
		else {
			print $errorPage;
		}
	}
	else {
		if (defined("ERROR_EMAIL_TO")) {
			mail(ERROR_EMAIL_TO,
				'[Error] 500 Error in ' . $relative_file,
				$errorPage,
				"Content-type: text/html; charset=utf-8\r\n" .
					"From: Error Mailer <error@mydomain.com>\r\n"
			);
		}

		header('HTTP/1.1 500 Internal Server Error');
		readfile('error.html');
		exit;
	}
}

function error_render_stack_trace($project_dir = NULL, $exceptionStack = FALSE) {
	if (!$project_dir) {
		$project_dir = realpath(__DIR__ . '/../');
	}

	if ($exceptionStack) {
		$stack = $exceptionStack;
	}
	else {
		// Error stack
		$stack = debug_backtrace();

		// remove the call to this function
		// and error_handler from the stack trace
		array_shift($stack);
		array_shift($stack);
	}

	$content = '<table cellpadding="5" cellspacing="0">
		<tr bgcolor="#aaa">
			<th style="min-width: 250px; text-align: left;">Filename</th>
			<th style="min-width: 100px; text-align: left;">Line</th>
			<th style="min-width: 200px; text-align: left;">Function</th>
		</tr>';

	$i = 0;
	foreach ($stack as $frame) {
		$file = isset($frame['file']) ? $frame['file'] : NULL;
		$filename = substr($file, strlen($project_dir));

		$content .=  '<tr style="background-color: #' . (($i++ % 2) == 1 ? 'aaa':'eee') . '">
			<td>' . htmlentities($filename) . '</td>
			<td>' . (isset( $frame['line'] ) ? $frame['line'] : '-') . '</td>
			<td>' . $frame['function'] . '</td>
		</tr>';
	}

	$content .=  '</table>';

	return $content;
}

function error_print_context($context, $i = 0) {
	if(!is_array($context)) {
		return NULL;
	}

	$content = '<table cellpadding="5" cellspacing="0">
	<tr bgcolor="#aaa">
		<th style="min-width: 200px; text-align: left;">Variable</th>
		<th style="min-width: 250px; text-align: left;">Value</th>
		<th style="min-width: 100px; text-align: left;">Type</th>
	</tr>';

	foreach ($context as $var => $val) {
		if (!is_array($val) && !is_object($val)) {
			$type = gettype($val);


			if ($type == 'boolean') {
				
			}
			else {
				$val = htmlentities((string)$val);
			}

			// Mask Passwords
			if (strpos($var, 'password') !== false) {
				$val = str_repeat('*', strlen($var));
			}

			$content .=  '<tr style="background-color: #' .
				(($i++ % 2) == 1 ? 'aaa' : 'eee') . "\">
				<td>$var</td>
				<td>$val</td>
				<td>$type</td>
			</tr>";
		}
		else if (is_array($val) && ($var != 'GLOBALS')) {
			$content .=  '<tr><td colspan="3"><b>' . $var . '</b></td></tr>
			<tr><td colspan="3" style="padding-left: 30px;">' . error_print_context($val) . '</td></tr>';
		}
	}

	$content .= '</table>';
	return $content;
}