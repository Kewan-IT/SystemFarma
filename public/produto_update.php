<?php
require_once("../config/conexao.php");

$id = $_POST['id'];
$nome = $_POST['nome'];
$categoria_id = $_POST['categoria_id'];
$tipo_id = $_POST['tipo_id'];
$lote = $_POST['lote'];
$data_validade = $_POST['data_validade'];
$quantidade = $_POST['quantidade'];
$descricao = $_POST['descricao'];
$preco_compra = $_POST['preco_compra'];
$preco_venda = $_POST['preco_venda'];

$stmt = $conn->prepare("
UPDATE produtos SET 
nome=?, 
categoria_id=?, 
tipo_id=?, 
lote=?, 
data_validade=?, 
quantidade=?, 
descricao=?, 
preco_compra=?, 
preco_venda=?
WHERE id=?
");

$stmt->bind_param(
    "siissidsdi",
    $nome,
    $categoria_id,
    $tipo_id,
    $lote,
    $data_validade,
    $quantidade,
    $descricao,
    $preco_compra,
    $preco_venda,
    $id
);

$stmt->execute();

header("Location: produtos_list.php");