<?php
require_once("../config/conexao.php");

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

// TITULO + QUERY
if ($tipo == "vendas") {

    $titulo = "Relatório de Vendas";

    $sql = "
    SELECT v.id, u.nome as usuario, v.total, v.metodo_pagamento, v.criado_em
    FROM vendas v
    LEFT JOIN usuarios u ON v.usuario_id = u.id
    WHERE $where
    ";

} elseif ($tipo == "funcionario") {

    $titulo = "Vendas por Funcionário";

    $sql = "
    SELECT u.nome, SUM(v.total) as total
    FROM vendas v
    LEFT JOIN usuarios u ON v.usuario_id = u.id
    WHERE $where
    GROUP BY u.id
    ";

} elseif ($tipo == "produtos") {

    $titulo = "Produtos Mais Vendidos";

    $sql = "
    SELECT p.nome, SUM(vi.quantidade) as quantidade
    FROM venda_itens vi
    JOIN vendas v ON vi.venda_id = v.id
    JOIN produtos p ON vi.produto_id = p.id
    WHERE $where
    GROUP BY p.id
    ";

} else {

    $titulo = "Relatório de Lucro";

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
$totalGeral = 0;
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Relatório</title>


<style>
body {
    font-family: Arial;
    margin: 30px;
    background: #f9fbfd;
}

.header {
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-bottom: 3px solid #0d6efd;
    padding-bottom: 10px;
}

.logo {
    height: 60px;
}

h2 {
    color: #0d6efd;
    margin-top: 15px;
}

.info {
    margin-top: 10px;
    font-size: 14px;
    color: #555;
}

/* 🔥 TABELA BONITA */
table {
    width:100%;
    border-collapse: collapse;
    margin-top: 20px;
    background: white;
    border-radius: 10px;
    overflow: hidden;
}

/* Cabeçalho */
th {
    background: linear-gradient(45deg, #0d6efd, #0b5ed7);
    color: white;
    padding: 10px;
    font-size: 14px;
}

/* Linhas */
td {
    padding: 10px;
    border-bottom: 1px solid #eee;
}

/* Zebra */
tr:nth-child(even) {
    background-color: #f2f6fc;
}

/* Hover efeito */
tr:hover {
    background-color: #e9f2ff;
}

/* TOTAL */
.total {
    background: #0d6efd;
    color: white;
    font-weight: bold;
    font-size: 16px;
}

/* FOOTER */
.footer {
    margin-top: 60px;
    display:flex;
    justify-content:space-between;
}

.assinatura {
    text-align:center;
    width:200px;
}

.linha {
    border-top: 2px solid #000;
    margin-top: 40px;
}
</style>


</head>

<body onload="window.print()">

<div class="header">
    <img src="../assets/logo.png" class="logo">

    <div style="text-align:right">
        <h3 style="margin:0;">Farmácia</h3>
        <small>Sistema de Gestão</small>
    </div>
</div>

<h2><?= $titulo ?></h2>

<p>
Período: <?= $data_inicio && $data_fim ? "$data_inicio até $data_fim" : $periodo ?>
<br>Emitido em: <?= date("d/m/Y H:i") ?>
</p>

<table>
<tr>
<?php
if ($tipo == "vendas") echo "<th>ID</th><th>Usuário</th><th>Total</th><th>Pagamento</th><th>Data</th>";
elseif ($tipo == "funcionario") echo "<th>Funcionário</th><th>Total</th>";
elseif ($tipo == "produtos") echo "<th>Produto</th><th>Qtd</th>";
else echo "<th>Produto</th><th>Lucro</th>";
?>
</tr>

<?php while($row = $res->fetch_assoc()): ?>
<tr>
<?php foreach($row as $v): ?>
<td><?= $v ?></td>
<?php $totalGeral += is_numeric($v) ? $v : 0; ?>
<?php endforeach; ?>
</tr>
<?php endwhile; ?>

<tr class="total">
<td colspan="100%">
TOTAL GERAL: <?= number_format($totalGeral,2) ?> MT
</td>
</tr>

</table>

<div class="footer">
<div>
<div class="linha"></div>
Responsável
</div>

<div>
<div class="linha"></div>
Gerência
</div>
</div>

</body>
</html>