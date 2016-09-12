<?php
defined('APP_START_TIME') || define('APP_START_TIME', microtime(true));

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

include __DIR__.'/app/web.php';