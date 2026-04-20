<?php include("../../layouts/header.php"); ?>
<?php include("../../layouts/sidebar.php"); ?>
<?php require_once("../../config/conexao.php"); ?>

<div class="container mt-4">
<h4>🧾 Recibos</h4>

<?php
$res = $conn->query("SELECT * FROM vendas ORDER BY id DESC");
?>

<table class="table table-bordered">
<tr>
<th>ID</th>
<th>Total</th>
<th>Data</th>
<th>Ação</th>
</tr>

<?php while($v = $res->fetch_assoc()): ?>
<tr>
<td><?= $v['id'] ?></td>
<td><?= $v['total'] ?></td>
<td><?= $v['created_at'] ?></td>
<td>
<a href="../recibo_pdf.php?venda_id=<?= $v['id'] ?>" target="_blank" class="btn btn-sm btn-primary">
Ver PDF
</a>
</td>
</tr>
<?php endwhile; ?>

</table>
</div>

<?php include("../../layouts/footer.php"); ?>