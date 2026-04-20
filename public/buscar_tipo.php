<?php
require_once("../config/conexao.php");

$q=$_GET['q'];
$c=$_GET['categoria_id'];

$res=$conn->query("
SELECT id,nome FROM tipos_produto
WHERE categoria_id=$c AND nome LIKE '%$q%'
LIMIT 5");

$out=[];
while($r=$res->fetch_assoc()) $out[]=$r;

echo json_encode($out);