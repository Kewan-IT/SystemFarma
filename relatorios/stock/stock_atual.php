<?php include("../../layouts/header.php"); ?>
<?php include("../../layouts/sidebar.php"); ?>
<?php require_once("../../config/conexao.php"); ?>

<div class="container mt-4">
<h4>📦 Stock Atual</h4>

<?php
$res = $conn->query("SELECT id, nome, quantidade FROM produtos");
?>

<table class="table table-bordered table-striped">
<tr>
<th>Produto</th>
<th>Quantidade</th>
<th>Histórico</th>
</tr>

<?php while($p = $res->fetch_assoc()): ?>
<tr class="<?= $p['quantidade'] <= 5 ? 'table-danger' : '' ?>">
<td><?= $p['nome'] ?></td>
<td><?= $p['quantidade'] ?></td>
<td>
<a href="historico_produto.php?produto_id=<?= $p['id'] ?>" class="btn btn-sm btn-primary">
Ver Histórico
</a>
</td>
</tr>
<?php endwhile; ?>

</table>
</div>

<?php include("../../layouts/footer.php"); ?>