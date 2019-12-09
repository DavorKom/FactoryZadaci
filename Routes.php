<?php

$router->get('about', 'Controller@function');
$router->get('test', 'Controller@testfunction');
$router->get('closure', function() {
    die("closure");
});