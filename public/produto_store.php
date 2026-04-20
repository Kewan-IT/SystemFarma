<?php
require_once("../config/conexao.php");

// validar
if(empty($_POST['categoria_id']) || empty($_POST['tipo_id'])){
    header("Location: produto_create.php?msg=erro");
    exit;
}

// normalizar
function normalizar($t){
    return ucfirst(strtolower(trim($t)));
}

$nome = normalizar($_POST['nome']);
$categoria_id = (int) $_POST['categoria_id'];
$tipo_id = (int) $_POST['tipo_id'];

$lote = $_POST['lote'] ?? '';
$validade = !empty($_POST['validade']) ? $_POST['validade'] : null;
$quantidade = (int) ($_POST['quantidade'] ?? 0);
$preco_compra = (float) ($_POST['preco_compra'] ?? 0);
$preco_venda = (float) ($_POST['preco_venda'] ?? 0);
$descricao = $_POST['descricao'] ?? '';

// gerar código
$codigo = "PROD-" . time() . rand(100,999);

$sql = "
INSERT INTO produtos 
(codigo, nome, categoria_id, tipo_id, lote, data_validade, quantidade, preco_compra, preco_venda, descricao)
VALUES (
'$codigo',
'$nome',
$categoria_id,
$tipo_id,
'$lote',
" . ($validade ? "'$validade'" : "NULL") . ",
$quantidade,
$preco_compra,
$preco_venda,
'$descricao'
)";

if(!$conn->query($sql)){
    header("Location: produto_create.php?msg=erro");
    exit;
}

// sucesso
header("Location: produto_create.php?msg=sucesso");
exit;