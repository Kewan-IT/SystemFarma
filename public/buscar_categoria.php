<?php
require_once("../config/conexao.php");

$q = $_GET['q'];

$res = $conn->query("SELECT id,nome FROM categorias WHERE nome LIKE '%$q%' LIMIT 5");

$out=[];
while($r=$res->fetch_assoc()) $out[]=$r;

echo json_encode($out);