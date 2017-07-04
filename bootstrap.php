<?php

require_once realpath(__DIR__.'/vendor/autoload.php');

$settings = require_once realpath(__DIR__.'/config/settings.php');

foreach ($settings['php'] as $key => $value) {
    ini_set($key, $value);
}

define('DEBUG', $settings['debug']);