<?php
require_once("../config/conexao.php");

$id = $_GET['id'];
$acao = $_GET['acao'];

if ($acao == "desativar") {
    $conn->query("UPDATE produtos SET status='inativo' WHERE id=$id");
} else {
    $conn->query("UPDATE produtos SET status='ativo' WHERE id=$id");
}

header("Location: produtos_list.php");