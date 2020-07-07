<?php

session_start();

require_once 'config.php';

try {
    $conn = new PDO('mysql:host=' . SERVERNAME . ';dbname=' . DATABASE, USERNAME, PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    header('HTTP/1.1 500 SERVER ERROR');
    exit();
}
function translate($label)
{
    return $label;
}
function getProducts(){
    global $conn;
    $in = rtrim(str_repeat('?,', count($_SESSION['cart'])), ',');
    $sql = 'SELECT * FROM products WHERE id IN (' . $in . ')';
    $stmt = $conn->prepare($sql);
    $stmt->execute(array_values($_SESSION['cart']));
    return $stmt->fetchAll(PDO::FETCH_NAMED);
}