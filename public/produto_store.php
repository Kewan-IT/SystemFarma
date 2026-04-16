<?php
require_once("../config/conexao.php");

// gerar código automático
$codigo = "PROD-" . time();

$nome = $_POST['nome'];
$categoria = $_POST['categoria_id'];
$tipo = $_POST['tipo_id'];
$lote = $_POST['lote'];
$validade = $_POST['validade'];
$qtd = $_POST['quantidade'];
$desc = $_POST['descricao'];
$preco_c = $_POST['preco_compra'];
$preco_v = $_POST['preco_venda'];

$stmt = $conn->prepare("INSERT INTO produtos 
(codigo, nome, categoria_id, tipo_id, lote, data_validade, quantidade, descricao, preco_compra, preco_venda)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("ssiisssidd",
    $codigo,
    $nome,
    $categoria,
    $tipo,
    $lote,
    $validade,
    $qtd,
    $desc,
    $preco_c,
    $preco_v
);

$stmt->execute();

header("Location: produto_create.php?sucesso=1");