<?php
require_once("../config/conexao.php");

function normalizar($t){
    return ucfirst(strtolower(trim($t)));
}

$nome = normalizar($_POST['nome']);
$categoria_id = $_POST['categoria_id'];

$res = $conn->query("
SELECT id FROM tipos_produto 
WHERE nome='$nome' AND categoria_id=$categoria_id
");

if($res->num_rows){
    echo json_encode($res->fetch_assoc());
    exit;
}

$conn->query("
INSERT INTO tipos_produto(nome,categoria_id)
VALUES('$nome',$categoria_id)
");

echo json_encode([
"id"=>$conn->insert_id,
"nome"=>$nome
]);