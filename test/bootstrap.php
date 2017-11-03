<?php
require_once dirname(dirname(__FILE__)) . "/vendor/autoload.php";
require_once dirname(dirname(__FILE__)) . '/config/define.php';
$console = new \Swoft\Console\Console();
$console->run();