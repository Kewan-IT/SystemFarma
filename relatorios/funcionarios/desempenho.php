<?php include("../../layouts/header.php"); ?>
<?php include("../../layouts/sidebar.php"); ?>
<?php require_once("../../config/conexao.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container mt-4">
<h4>📊 Desempenho de Funcionários</h4>

<?php
$sql = "
SELECT f.nome, COUNT(v.id) total_vendas, SUM(v.total) total_valor
FROM vendas v
JOIN usuarios u ON u.id = v.usuario_id
JOIN funcionarios f ON f.id = u.funcionario_id
GROUP BY f.id
";

$res = $conn->query($sql);

$labels = [];
$data = [];
?>

<canvas id="grafico"></canvas>

<table class="table table-bordered mt-3">
<tr>
<th>Funcionário</th>
<th>Nº Vendas</th>
<th>Total Vendido</th>
</tr>

<?php while($r = $res->fetch_assoc()):
$labels[] = $r['nome'];
$data[] = $r['total_valor'];
?>
<tr>
<td><?= $r['nome'] ?></td>
<td><?= $r['total_vendas'] ?></td>
<td><?= number_format($r['total_valor'],2) ?> MT</td>
</tr>
<?php endwhile; ?>
</table>

<script>
new Chart(document.getElementById("grafico"), {
type:'bar',
data:{ labels: <?= json_encode($labels) ?>,
datasets:[{ label:'Vendas', data: <?= json_encode($data) ?> }]}
});
</script>

</div>

<?php include("../../layouts/footer.php"); ?>