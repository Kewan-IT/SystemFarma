<?php include("../../layouts/header.php"); ?>
<?php include("../../layouts/sidebar.php"); ?>
<?php require_once("../../config/conexao.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container mt-4">
<h4>📅 Vendas por Período</h4>

<?php
$inicio = $_GET['inicio'] ?? date('Y-m-01');
$fim = $_GET['fim'] ?? date('Y-m-d');

$sql = "
SELECT DATE(created_at) as dia, SUM(total) total
FROM vendas
WHERE DATE(created_at) BETWEEN '$inicio' AND '$fim'
GROUP BY dia
";

$res = $conn->query($sql);

$dias = [];
$totais = [];
?>

<form class="mb-3">
<input type="date" name="inicio" value="<?= $inicio ?>">
<input type="date" name="fim" value="<?= $fim ?>">
<button class="btn btn-primary btn-sm">Filtrar</button>
</form>

<canvas id="grafico"></canvas>

<table class="table table-bordered mt-3">
<tr><th>Dia</th><th>Total</th></tr>

<?php while($r = $res->fetch_assoc()):
$dias[] = $r['dia'];
$totais[] = $r['total'];
?>
<tr>
<td><?= $r['dia'] ?></td>
<td><?= $r['total'] ?></td>
</tr>
<?php endwhile; ?>
</table>
</div>

<script>
new Chart(document.getElementById("grafico"), {
type: 'line',
data: {
labels: <?= json_encode($dias) ?>,
datasets: [{
label: 'Vendas',
data: <?= json_encode($totais) ?>
}]
}
});
</script>

<?php include("../../layouts/footer.php"); ?>