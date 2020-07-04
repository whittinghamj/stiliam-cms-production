<?php

require_once 'error.php';

class foo {
	function bar () {
		$this->monkey('hidden');
	}

	function monkey ($type) {
		throw new Exception('My ' . $type . ' exception');
	}
}

$hello = new foo();
$hello->bar();