<?php include("../../layouts/header.php"); ?>
<?php include("../../layouts/sidebar.php"); ?>
<?php require_once("../../config/conexao.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container mt-4">
<h4>📊 Fluxo de Caixa</h4>

<?php
$sql = "
SELECT DATE(created_at) dia, SUM(total) total
FROM vendas
GROUP BY dia
ORDER BY dia
";

$res = $conn->query($sql);

$dias = [];
$totais = [];
?>

<canvas id="grafico"></canvas>

<table class="table mt-3">
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

<script>
new Chart(document.getElementById("grafico"), {
type:'line',
data:{ labels: <?= json_encode($dias) ?>, datasets:[{data: <?= json_encode($totais) ?>}]}
});
</script>

</div>

<?php include("../../layouts/footer.php"); ?>