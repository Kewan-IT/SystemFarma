<?php include("../../layouts/header.php"); ?>
<?php include("../../layouts/sidebar.php"); ?>
<?php require_once("../../config/conexao.php"); ?>

<div class="container mt-4">
<h4>📈 Margem de Lucro</h4>

<?php
$sql = "
SELECT 
SUM(vi.preco * vi.quantidade) receita,
SUM(p.preco_custo * vi.quantidade) custo
FROM venda_itens vi
JOIN produtos p ON p.id = vi.produto_id
";

$r = $conn->query($sql)->fetch_assoc();

$lucro = $r['receita'] - $r['custo'];
$margem = ($lucro / $r['receita']) * 100;
?>

<div class="card p-4">
<h4>Margem de Lucro: <?= number_format($margem,2) ?>%</h4>
</div>

<?php include("../../layouts/footer.php"); ?>