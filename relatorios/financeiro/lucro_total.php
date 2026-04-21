<?php include("../../layouts/header.php"); ?>
<?php include("../../layouts/sidebar.php"); ?>
<?php require_once("../../config/conexao.php"); ?>

<div class="container mt-4">
<h4>💰 Lucro Total</h4>

<?php
$sql = "
SELECT 
SUM(vi.preco * vi.quantidade) AS receita,
SUM(p.preco_custo * vi.quantidade) AS custo
FROM venda_itens vi
JOIN produtos p ON p.id = vi.produto_id
";

$r = $conn->query($sql)->fetch_assoc();

$lucro = $r['receita'] - $r['custo'];
?>

<div class="card p-4">
<h5>Receita: <?= number_format($r['receita'],2) ?> MT</h5>
<h5>Custo: <?= number_format($r['custo'],2) ?> MT</h5>
<h3 class="text-success">Lucro: <?= number_format($lucro,2) ?> MT</h3>
</div>

</div>

<?php include("../../layouts/footer.php"); ?>