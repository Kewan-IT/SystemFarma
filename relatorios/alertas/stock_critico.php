<?php include("../../layouts/header.php"); ?>
<?php include("../../layouts/sidebar.php"); ?>
<?php require_once("../../config/conexao.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container mt-4">
<h4>⚠️ Stock Crítico</h4>

<?php
$res = $conn->query("SELECT nome, quantidade FROM produtos WHERE quantidade <= 5");

$labels = [];
$data = [];
?>

<canvas id="grafico"></canvas>

<table class="table table-danger mt-3">
<tr><th>Produto</th><th>Quantidade</th></tr>

<?php while($p = $res->fetch_assoc()):
$labels[] = $p['nome'];
$data[] = $p['quantidade'];
?>
<tr>
<td><?= $p['nome'] ?></td>
<td><?= $p['quantidade'] ?></td>
</tr>
<?php endwhile; ?>

</table>

<script>
new Chart(document.getElementById("grafico"), {
type:'bar',
data:{ labels: <?= json_encode($labels) ?>,
datasets:[{ data: <?= json_encode($data) ?> }] }
});
</script>

</div>

<?php include("../../layouts/footer.php"); ?>