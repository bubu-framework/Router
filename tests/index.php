<?php


use Bubu\Router\Router;

require '../vendor/autoload.php';

Router::controllerNamespace('Controller');
Router::controllerSuffix('');

Router::get('/test/:id-:slug','Ok#ok');


Router::check();
echo 'ok';