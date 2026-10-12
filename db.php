<?php
session_start();

$host = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'manthan';

$conn = mysqli_connect($host, $dbUser, $dbPass, $dbName);

if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');
