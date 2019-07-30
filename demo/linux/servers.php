<?php
/**
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */

require '../../vendor/autoload.php';

use Zhusaidong\AnonymousChat\WebServer;
use Zhusaidong\AnonymousChat\WebSocketServer;
use Workerman\Worker;

$web       = new WebServer(8000);
$webSocket = new WebSocketServer(8001);

$web->run();
$webSocket->run();

Worker::runAll();
