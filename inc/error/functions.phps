<?php

require_once 'error.php';

function my_function() {
	// Actual error
	print $non_exists_variable;
}

function second_func(){
	// other codes

	my_function();

	// other codes
}

function first_func(){
	// other codes

	second_func();

	// other codes
}

first_func();