<?php
require_once("../config/conexao.php");

$periodo = $_GET['periodo'] ?? 'todos';
$data_inicio = $_GET['data_inicio'] ?? null;
$data_fim = $_GET['data_fim'] ?? null;

$where = "1=1";

// 🔹 filtros por período
if ($periodo == "hoje") {
    $where .= " AND DATE(v.criado_em)=CURDATE()";
} elseif ($periodo == "semana") {
    $where .= " AND YEARWEEK(v.criado_em,1)=YEARWEEK(CURDATE(),1)";
} elseif ($periodo == "mes") {
    $where .= " AND MONTH(v.criado_em)=MONTH(CURDATE())";
}

// 🔹 filtro manual
if ($data_inicio && $data_fim) {
    $where .= " AND DATE(v.criado_em) BETWEEN '$data_inicio' AND '$data_fim'";
}

// =============================
// 💰 TOTAL VENDAS
// =============================
$total = $conn->query("
SELECT SUM(total) as total 
FROM vendas v 
WHERE $where
")->fetch_assoc()['total'] ?? 0;


// =============================
// 👤 FUNCIONÁRIOS (GRÁFICO)
// =============================
$funcionarios = [];

$res = $conn->query("
SELECT u.nome, SUM(v.total) as total
FROM vendas v
LEFT JOIN usuarios u ON v.usuario_id = u.id
WHERE $where
GROUP BY u.id
");

while($row = $res->fetch_assoc()){
    $funcionarios[] = $row;
}


// =============================
// 🔥 PRODUTOS MAIS VENDIDOS
// =============================
$produtos = [];

$res = $conn->query("
SELECT p.nome, SUM(vi.quantidade) as total_qtd
FROM venda_itens vi
JOIN vendas v ON vi.venda_id = v.id
JOIN produtos p ON vi.produto_id = p.id
WHERE $where
GROUP BY p.id
ORDER BY total_qtd DESC
LIMIT 5
");

while($row = $res->fetch_assoc()){
    $produtos[] = $row;
}


// =============================
// 💰 LUCRO POR PRODUTO
// =============================
$lucro = [];

$res = $conn->query("
SELECT p.nome,
SUM((vi.preco - p.preco_compra) * vi.quantidade) as lucro
FROM venda_itens vi
JOIN vendas v ON vi.venda_id = v.id
JOIN produtos p ON vi.produto_id = p.id
WHERE $where
GROUP BY p.id
");

while($row = $res->fetch_assoc()){
    $lucro[] = $row;
}


// =============================
// 💰 LUCRO TOTAL (🔥 FALTAVA)
// =============================
$res = $conn->query("
SELECT 
SUM((vi.preco - p.preco_compra) * vi.quantidade) as lucro_total
FROM venda_itens vi
JOIN vendas v ON vi.venda_id = v.id
JOIN produtos p ON vi.produto_id = p.id
WHERE $where
");

$lucroTotal = $res->fetch_assoc()['lucro_total'] ?? 0;


// =============================
// 📦 PRODUTOS VENDIDOS (🔥 FALTAVA)
// =============================
$res = $conn->query("
SELECT SUM(vi.quantidade) as total_qtd
FROM venda_itens vi
JOIN vendas v ON vi.venda_id = v.id
WHERE $where
");

$produtosQtd = $res->fetch_assoc()['total_qtd'] ?? 0;


// =============================
// 👤 FUNCIONÁRIOS ATIVOS (🔥 FALTAVA)
// =============================
$res = $conn->query("
SELECT COUNT(*) as total 
FROM usuarios 
WHERE status='ativo'
");

$funcCount = $res->fetch_assoc()['total'] ?? 0;


// =============================
// 🏆 FUNCIONÁRIO DO MÊS
// =============================
$func_mes = $conn->query("
SELECT u.nome, SUM(v.total) as total
FROM vendas v
LEFT JOIN usuarios u ON v.usuario_id = u.id
WHERE MONTH(v.criado_em)=MONTH(CURDATE())
GROUP BY u.id
ORDER BY total DESC
LIMIT 1
")->fetch_assoc();


// =============================
// 🎂 ANIVERSARIANTES
// =============================
$aniversariantes = [];

$res = $conn->query("
SELECT nome 
FROM funcionarios 
WHERE MONTH(data_nascimento)=MONTH(CURDATE())
");

while($row = $res->fetch_assoc()){
    $aniversariantes[] = $row['nome'];
}


// =============================
// 📤 RESPOSTA FINAL
// =============================
echo json_encode([
    "total" => $total,
    "lucroTotal" => $lucroTotal,
    "produtosQtd" => $produtosQtd,
    "funcCount" => $funcCount,
    "funcionarios" => $funcionarios,
    "produtos" => $produtos,
    "lucro" => $lucro,
    "func_mes" => $func_mes,
    "aniversariantes" => $aniversariantes
]);