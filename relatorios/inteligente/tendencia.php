<?php include("../../layouts/header.php"); ?>
<?php include("../../layouts/sidebar.php"); ?>
<?php require_once("../../config/conexao.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container mt-4">
<h4>📈 Tendência de Vendas</h4>

<?php
$sql = "
SELECT DATE(created_at) dia, SUM(total) total
FROM vendas
GROUP BY dia
ORDER BY dia
";

$res = $conn->query($sql);

$labels = [];
$data = [];
?>

<canvas id="grafico"></canvas>

<?php while($r = $res->fetch_assoc()):
$labels[] = $r['dia'];
$data[] = $r['total'];
endwhile; ?>

<script>
new Chart(document.getElementById("grafico"), {
type:'line',
data:{
labels: <?= json_encode($labels) ?>,
datasets:[{ label:'Tendência', data: <?= json_encode($data) ?> }]
}
});
</script>

</div>
<?php include("../../layouts/footer.php"); ?>