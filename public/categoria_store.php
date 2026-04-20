<?php
require_once("../config/conexao.php");

function normalizar($t){
    return ucfirst(strtolower(trim($t)));
}

$nome = normalizar($_POST['nome']);

$res = $conn->query("SELECT id FROM categorias WHERE nome='$nome'");

if($res->num_rows){
    echo json_encode($res->fetch_assoc());
    exit;
}

$conn->query("INSERT INTO categorias(nome) VALUES('$nome')");

echo json_encode([
"id"=>$conn->insert_id,
"nome"=>$nome
]);