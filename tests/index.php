<?php

use Bubu\Router\Router;

require '../vendor/autoload.php';

Router::get('Test', 'Ok#ok', 'rr');

var_dump(get_class_vars(Router::class));
