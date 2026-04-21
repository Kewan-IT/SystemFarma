<?php include("../../layouts/header.php"); ?>
<?php include("../../layouts/sidebar.php"); ?>
<?php require_once("../../config/conexao.php"); ?>

<div class="container mt-4">
<h4>🔥 Produto Mais Vendido</h4>

<?php
$sql = "
SELECT p.nome, SUM(vi.quantidade) total
FROM venda_itens vi
JOIN produtos p ON p.id = vi.produto_id
GROUP BY p.id
ORDER BY total DESC
LIMIT 1
";

$r = $conn->query($sql)->fetch_assoc();
?>

<div class="card p-4">
<h3><?= $r['nome'] ?></h3>
<p>Total vendido: <?= $r['total'] ?></p>
</div>

</div>
<?php include("../../layouts/footer.php"); ?>