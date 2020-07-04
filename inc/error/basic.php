<?php

require_once 'error.php';

function my_function() {
	print $non_exists_variable;
}

my_function();