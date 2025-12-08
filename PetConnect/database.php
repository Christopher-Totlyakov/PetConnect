<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "petConnect_db";

$pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
