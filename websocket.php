<?php
/**
 * http://www.websocket-test.com/
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */

require './vendor/autoload.php';

use Zhusaidong\AnonymousChat\WebSocketServer;

$server = new WebSocketServer(8001);
$server->run();
