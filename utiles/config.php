<?php
$database = [
    "host" => "localhost",
    "username" => "hlc",
    "password" => "hlc_2223",
    "db" => "hlc_tenistas"
];

try {
    $conn = new PDO("mysql:host={$database['host']};dbname={$database['db']}", $database['username'], $database['password']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>
