<?php
session_start();

include("inc/db.php");
include("inc/global_vars.php");
include("inc/functions.php");

log_add('logout', 'Logged off.' );

go("index.php");