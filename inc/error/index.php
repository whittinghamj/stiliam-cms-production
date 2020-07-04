<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>Errors</title>
	<style type="text/css">
		body {
			font: 18px Helvetica, Arial;
		}

		li {
			margin-bottom: 30px;
		}

		a {
			color: #00f;
		}

		.source {
			color: #777;
		}
	</style>
</head>
<body>
	<h2>Sample Errors</h2>
	<ul>
		<li>
			<div>
				<a href="basic.php">Basic PHP Error</a>
				&nbsp;&bull;&nbsp;
				<a href="basic.phps" class="source">Source</a>
			</div>
		</li>
		<li>
			<div>
				<a href="hide.php">Basic PHP Error With Generic Error Page</a>
				&nbsp;&bull;&nbsp;
				<a href="hide.phps" class="source">Source</a>
			</div>
			This is the general use, you can show the generic error page to
			users,<br /> and you can email the error report to the admins.<br />
			See project screenshots to see error report emails.
		</li>
		<li>
			<div>
				<a href="functions.php">Detailed Stack Backtrace</a>
				&nbsp;&bull;&nbsp;
				<a href="functions.phps" class="source">Source</a>
			</div>
			See the stack backtrace table to navigate to your actual error.
		</li>
		<li>
			<div>
				<a href="custom_error.php">Custom Error</a>
				&nbsp;&bull;&nbsp;
				<a href="custom_error.phps" class="source">Source</a>
			</div>
		</li>
		<li>
			<div>
				<a href="exception.php">Basic Exception</a>
				&nbsp;&bull;&nbsp;
				<a href="exception.phps" class="source">Source</a>
			</div>
			Any kind of not-catched php exception displayed with detailed backtrace.
		</li>
		<li>
			<div>
				<a href="exception_trace.php">Detailed Stack Backtrace in Exceptions</a>
				&nbsp;&bull;&nbsp;
				<a href="exception_trace.phps" class="source">Source</a>
			</div>
			See the stack backtrace table to navigate to your actual exception's location.
		</li>
		<li>
			<div>
				<a href="basic.php?get_parameter=myvalue&test=1&param_array[num1]=15&param_array[num2]=10">GET, POST Arrays</a>
			</div>
			See the $_GET array content, it's multi-dimmension array.<br />
			There is no POST data, if you have POST data in your page,<br />
			error reporter will show POST data in a separate section.
		</li>
	</ul>
</body>
</html>