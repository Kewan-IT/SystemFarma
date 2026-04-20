<?php
require_once("../config/conexao.php");

$termo = $_GET['q'] ?? '';

$stmt = $conn->prepare("
SELECT id, nome, preco_venda, quantidade 
FROM produtos 
WHERE nome LIKE CONCAT('%', ?, '%')
AND status='ativo'
LIMIT 10
");

$stmt->bind_param("s", $termo);
$stmt->execute();
$res = $stmt->get_result();

$dados = [];

while($row = $res->fetch_assoc()){
    $dados[] = $row;
}

echo json_encode($dados);