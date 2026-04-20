<?php include("../../layouts/header.php"); ?>
<?php include("../../layouts/sidebar.php"); ?>
<?php require_once("../../config/conexao.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container mt-4">
<h4>🏷️ Vendas por Categoria</h4>

<?php
$sql = "
SELECT p.categoria, SUM(vi.quantidade) total
FROM venda_itens vi
JOIN produtos p ON p.id = vi.produto_id
GROUP BY p.categoria
";

$res = $conn->query($sql);

$labels = [];
$data = [];
?>

<canvas id="grafico"></canvas>

<table class="table table-bordered mt-3">
<tr><th>Categoria</th><th>Total</th></tr>

<?php while($r = $res->fetch_assoc()):
$labels[] = $r['categoria'];
$data[] = $r['total'];
?>
<tr>
<td><?= $r['categoria'] ?></td>
<td><?= $r['total'] ?></td>
</tr>
<?php endwhile; ?>
</table>
</div>

<script>
new Chart(document.getElementById("grafico"), {
type:'bar',
data:{ labels: <?= json_encode($labels) ?>, datasets:[{data: <?= json_encode($data) ?>}]}
});
</script>

<?php include("../../layouts/footer.php"); ?>