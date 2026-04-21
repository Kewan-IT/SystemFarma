<?php include("../../layouts/header.php"); ?>
<?php include("../../layouts/sidebar.php"); ?>
<?php require_once("../../config/conexao.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container mt-4">
<h4>🧊 Produto Menos Vendido</h4>

<?php
$sql = "
SELECT p.nome, IFNULL(SUM(vi.quantidade),0) total
FROM produtos p
LEFT JOIN venda_itens vi ON vi.produto_id = p.id
GROUP BY p.id
ORDER BY total ASC
LIMIT 5
";

$res = $conn->query($sql);

$labels = [];
$data = [];
?>

<canvas id="grafico"></canvas>

<table class="table table-bordered mt-3">
<tr>
<th>Produto</th>
<th>Quantidade Vendida</th>
</tr>

<?php while($r = $res->fetch_assoc()):
$labels[] = $r['nome'];
$data[] = $r['total'];
?>
<tr class="<?= $r['total'] == 0 ? 'table-warning' : '' ?>">
<td><?= $r['nome'] ?></td>
<td><?= $r['total'] ?></td>
</tr>
<?php endwhile; ?>

</table>

<script>
new Chart(document.getElementById("grafico"), {
type:'bar',
data:{
labels: <?= json_encode($labels) ?>,
datasets:[{
label:'Menos vendidos',
data: <?= json_encode($data) ?>
}]
}
});
</script>

</div>

<?php include("../../layouts/footer.php"); ?>