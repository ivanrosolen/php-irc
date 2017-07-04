<?php

use Xuplau\IRC\Server;

require_once realpath(__DIR__ . '/../bootstrap.php');

$ip   = isset($argv[1]) ? $argv[1] : '127.0.0.1';
$port = isset($argv[2]) ? $argv[2] : 4242;
$name = isset($argv[3]) ? $argv[3] : 'PHP IRC';

$server = new Server($ip, $port, $name);
$server->run();