<?php

$db = new \PDO(
    'mysql:host=localhost;dbname=phpbnl17',
    'root',
    '',
    [
        \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
    ]
);
