<?php

$connection = null;

try {
    $connection = new PDO('mysql://hostname=localhost;dbname=php_pdo', 'root', '',
        array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ));

} catch (PDOException $e) {

}