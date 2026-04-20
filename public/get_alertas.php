<?php
require_once("../config/conexao.php");

$hoje = date('Y-m-d');
$limite = date('Y-m-d', strtotime('+30 days'));

$query = "
SELECT * FROM produtos 
WHERE quantidade <= 10 
OR (data_validade BETWEEN '$hoje' AND '$limite')
ORDER BY quantidade ASC, data_validade ASC
";

$result = $conn->query($query);

$alertas = [];

while($row = $result->fetch_assoc()) {

    if ($row['quantidade'] <= 10) {
        $tipo = "stock";
        $msg = "Stock baixo (".$row['quantidade']."): " . $row['nome'];
    } else {
        $tipo = "validade";
        $msg = "Validade próxima: " . $row['nome'];
    }

    $alertas[] = [
        "tipo" => $tipo,
        "mensagem" => $msg
    ];
}

echo json_encode($alertas);