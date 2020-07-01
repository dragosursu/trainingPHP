<?php

session_start();

require_once 'config.php';

try {
    $conn = new PDO('mysql:host=' . SERVERNAME . ';dbname=' . DATABASE, USERNAME, PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    header('HTTP/1.1 500 SERVER ERROR');
}
function translate($label)
{
    return $label;
}
