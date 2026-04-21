<?php
require_once("../config/conexao.php");

$res = $conn->query("
SELECT nome, quantidade 
FROM produtos 
WHERE quantidade <= 5
");

while($r = $res->fetch_assoc()){
    echo "<p>{$r['nome']} - {$r['quantidade']}</p>";
    }