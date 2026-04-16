<?php
$host = "127.0.0.1";
$user = "farmacia_user";
$password = "senha123";
$database = "WFarmacia";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>
