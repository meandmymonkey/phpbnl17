<?php

use Symfony\Component\Debug\Debug;

require_once __DIR__.'/../vendor/autoload.php';

Debug::enable(E_ALL & ~E_NOTICE);

$db = new \PDO(
    'mysql:host=localhost;dbname=phpbnl17',
    'root',
    '',
    [
        \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
    ]
);
