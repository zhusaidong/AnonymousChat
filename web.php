<?php
/**
 * WebServer demo
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */

require './vendor/autoload.php';

use Zhusaidong\AnonymousChat\WebServer;

$server = new WebServer(8000);
$server->run();
