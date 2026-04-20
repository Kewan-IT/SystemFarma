<?php
require_once("../config/conexao.php");

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=relatorio.xls");

$tipo = $_GET['tipo'] ?? 'vendas';
$periodo = $_GET['periodo'] ?? 'todos';
$data_inicio = $_GET['data_inicio'] ?? null;
$data_fim = $_GET['data_fim'] ?? null;

$where = "1=1";

if ($periodo == "hoje") {
    $where .= " AND DATE(v.criado_em)=CURDATE()";
} elseif ($periodo == "mes") {
    $where .= " AND MONTH(v.criado_em)=MONTH(CURDATE())";
}

if ($data_inicio && $data_fim) {
    $where .= " AND DATE(v.criado_em) BETWEEN '$data_inicio' AND '$data_fim'";
}

// ESCOLHER QUERY
if ($tipo == "vendas") {

    echo "<table border='1'><tr>
    <th>ID</th><th>Usuário</th><th>Total</th><th>Pagamento</th><th>Data</th></tr>";

    $sql = "
    SELECT v.id, u.nome as usuario, v.total, v.metodo_pagamento, v.criado_em
    FROM vendas v
    LEFT JOIN usuarios u ON v.usuario_id = u.id
    WHERE $where
    ";

} elseif ($tipo == "funcionario") {

    echo "<table border='1'><tr>
    <th>Funcionário</th><th>Total</th></tr>";

    $sql = "
    SELECT u.nome, SUM(v.total) as total
    FROM vendas v
    LEFT JOIN usuarios u ON v.usuario_id = u.id
    WHERE $where
    GROUP BY u.id
    ";

} elseif ($tipo == "produtos") {

    echo "<table border='1'><tr>
    <th>Produto</th><th>Quantidade</th></tr>";

    $sql = "
    SELECT p.nome, SUM(vi.quantidade) as quantidade
    FROM venda_itens vi
    JOIN vendas v ON vi.venda_id = v.id
    JOIN produtos p ON vi.produto_id = p.id
    WHERE $where
    GROUP BY p.id
    ";

} else {

    echo "<table border='1'><tr>
    <th>Produto</th><th>Lucro</th></tr>";

    $sql = "
    SELECT p.nome,
    SUM((vi.preco - p.preco_compra) * vi.quantidade) as lucro
    FROM venda_itens vi
    JOIN vendas v ON vi.venda_id = v.id
    JOIN produtos p ON vi.produto_id = p.id
    WHERE $where
    GROUP BY p.id
    ";
}

$res = $conn->query($sql);

while($row = $res->fetch_assoc()){
    echo "<tr>";
    foreach($row as $col){
        echo "<td>$col</td>";
    }
    echo "</tr>";
}

echo "</table>";