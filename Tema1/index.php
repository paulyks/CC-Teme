<?php
include 'route.php';
include 'src/home.php';
include 'src/photos.php';
include 'src/random.php';
include 'src/telegram.php';
include 'src/metrics.php';
include 'src/functions.php';

$route = new Route();

$route->add('/', 'Home'); 
$route->add('photos', 'Photos');
$route->add('random', 'Random');
$route->add('telegram', 'Telegram');
$route->add('metrics', 'Metrics');

$route->submit();
?>