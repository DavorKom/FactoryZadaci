<?php 

require 'Models/Model.php';
require 'Models/User.php';
require 'Models/Post.php';
require 'Controllers/Controller.php';
require 'Database/Database.php';
require 'Database/QueryBuilder.php';
require 'Observers/UserObserver.php';
require 'Route.php';
require 'Router.php';

$pdo = Database::connect();

$query = QueryBuilder::getInstance($pdo);

Model::setDBQueryBuilder($query);



$router = new Router;

require 'Routes.php';

$router->direct('test');

die(var_dump($router->routes));







