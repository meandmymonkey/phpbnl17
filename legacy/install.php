<?php

include 'config.php';

$query = 'CREATE DATABASE IF NOT EXISTS `phpbnl17`;';

$db->query($query);

$db->query('drop table if exists todo;');

$query  = 'CREATE TABLE IF NOT EXISTS `todo` ('."\n";
$query .= '`id` INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,';
$query .= '`title` VARCHAR(100),'."\n";
$query .= '`is_done` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0);';

$db->query($query);

$data = array(
  array('title' => 'Do the dishes', 'is_done' => 1),
  array('title' => 'Read a book', 'is_done' => 0),
  array('title' => 'Do the homework', 'is_done' => 0),
  array('title' => 'Cook some cakes for birthday', 'is_done' => 1),
);

foreach ($data as $todo) {
    $db->query("INSERT INTO todo (title, is_done) VALUES ('". $todo['title'] ."','". $todo['is_done'] ."');");
}

echo 'Installation done!';
echo "\n";
