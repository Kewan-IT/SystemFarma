<?php
require_once("../config/conexao.php");

$res = $conn->query("
SELECT p.nome, SUM(vi.quantidade) total
FROM venda_itens vi
JOIN produtos p ON p.id = vi.produto_id
GROUP BY p.id
ORDER BY total DESC LIMIT 5
");

while($r = $res->fetch_assoc()){
    echo "<p>{$r['nome']} - {$r['total']}</p>";
    }