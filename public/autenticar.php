<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once("../config/conexao.php");

$email = $_POST['email'];
$senha = $_POST['senha'];

// Prevenir SQL Injection
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if (password_verify($senha, $user['senha'])) {
        $_SESSION['usuario'] = $user['nome'];
        $_SESSION['email'] = $user['email'];

        header("Location: dashboard.php");
        exit();
    } else {
        header("Location: index.php?erro=1");
        exit();
    }

} else {
    header("Location: index.php?erro=1");
    exit();
}
?>