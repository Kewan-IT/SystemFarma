<?php include("../../layouts/header.php"); ?>
<?php include("../../layouts/sidebar.php"); ?>
<?php require_once("../../config/conexao.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container mt-4">
<h4>👨‍💼 Vendas por Funcionário</h4>

<?php
$sql = "
SELECT f.nome, SUM(v.total) total
FROM vendas v
JOIN usuarios u ON u.id = v.usuario_id
JOIN funcionarios f ON f.id = u.funcionario_id
GROUP BY f.id
ORDER BY total DESC
";

$res = $conn->query($sql);

$labels = [];
$data = [];
?>

<canvas id="grafico"></canvas>

<table class="table">
<tr><th>Funcionário</th><th>Total</th></tr>

<?php while($r = $res->fetch_assoc()):
$labels[] = $r['nome'];
$data[] = $r['total'];
?>
<tr>
<td><?= $r['nome'] ?></td>
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