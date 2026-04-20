<?php include("../../layouts/header.php"); ?>
<?php include("../../layouts/sidebar.php"); ?>
<?php require_once("../../config/conexao.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container mt-4">
<h4>💳 Vendas por Pagamento</h4>

<?php
$sql = "SELECT metodo_pagamento, SUM(total) total FROM vendas GROUP BY metodo_pagamento";
$res = $conn->query($sql);

$labels = [];
$data = [];
?>

<canvas id="grafico"></canvas>

<table class="table">
<tr><th>Método</th><th>Total</th></tr>

<?php while($r = $res->fetch_assoc()):
$labels[] = $r['metodo_pagamento'];
$data[] = $r['total'];
?>
<tr>
<td><?= $r['metodo_pagamento'] ?></td>
<td><?= $r['total'] ?></td>
</tr>
<?php endwhile; ?>
</table>
</div>

<script>
new Chart(document.getElementById("grafico"), {
type:'pie',
data:{ labels: <?= json_encode($labels) ?>, datasets:[{data: <?= json_encode($data) ?>}]}
});
</script>

<?php include("../../layouts/footer.php"); ?>