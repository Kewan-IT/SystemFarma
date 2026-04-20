<?php include("../../layouts/header.php"); ?>
<?php include("../../layouts/sidebar.php"); ?>
<?php require_once("../../config/conexao.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container mt-4">
<h4>📜 Histórico do Produto</h4>

<?php
$id = $_GET['produto_id'] ?? 0;

$sql = "
SELECT v.created_at, vi.quantidade
FROM venda_itens vi
JOIN vendas v ON v.id = vi.venda_id
WHERE vi.produto_id = $id
ORDER BY v.created_at ASC
";

$res = $conn->query($sql);

$datas = [];
$qtds = [];
?>

<canvas id="grafico"></canvas>

<table class="table table-bordered mt-3">
<tr><th>Data</th><th>Quantidade Vendida</th></tr>

<?php while($r = $res->fetch_assoc()):
$datas[] = $r['created_at'];
$qtds[] = $r['quantidade'];
?>
<tr>
<td><?= $r['created_at'] ?></td>
<td><?= $r['quantidade'] ?></td>
</tr>
<?php endwhile; ?>

</table>
</div>

<script>
new Chart(document.getElementById("grafico"), {
type:'line',
data:{
labels: <?= json_encode($datas) ?>,
datasets:[{
label:'Vendas ao longo do tempo',
data: <?= json_encode($qtds) ?>
}]
}
});
</script>

<?php include("../../layouts/footer.php"); ?>