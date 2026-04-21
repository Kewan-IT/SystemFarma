<?php
require_once("../config/conexao.php");

$inicio = $_GET['inicio'];
$fim = $_GET['fim'];

$res = $conn->query("
SELECT metodo_pagamento, COUNT(*) total
FROM vendas
WHERE DATE(created_at) BETWEEN '$inicio' AND '$fim'
GROUP BY metodo_pagamento
");

$labels = [];
$data = [];

while($r = $res->fetch_assoc()){
    $labels[] = $r['metodo_pagamento'];
        $data[] = $r['total'];
        }

        echo json_encode(['labels'=>$labels,'data'=>$data]);