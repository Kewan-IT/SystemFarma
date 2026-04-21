<?php include("../../layouts/header.php"); ?>
<?php include("../../layouts/sidebar.php"); ?>
<?php require_once("../../config/conexao.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container mt-4">
<h4>🕒 Horário com Mais Vendas</h4>

<?php
$sql = "
SELECT HOUR(created_at) hora, COUNT(*) total
FROM vendas
GROUP BY hora
ORDER BY hora
";

$res = $conn->query($sql);

$labels = [];
$data = [];
?>

<canvas id="grafico"></canvas>

<?php while($r = $res->fetch_assoc()):
$labels[] = $r['hora'] . ":00";
$data[] = $r['total'];
endwhile; ?>

<script>
new Chart(document.getElementById("grafico"), {
type:'bar',
data:{
labels: <?= json_encode($labels) ?>,
datasets:[{ label:'Vendas por hora', data: <?= json_encode($data) ?> }]
}
});
</script>

</div>
<?php include("../../layouts/footer.php"); ?>y