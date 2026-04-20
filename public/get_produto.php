<?php
require_once("../config/conexao.php");

$id = $_GET['id'];

$stmt = $conn->prepare("
SELECT nome, preco_venda, quantidade, status 
FROM produtos WHERE id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();

$res = $stmt->get_result();
echo json_encode($res->fetch_assoc());