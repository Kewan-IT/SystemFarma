<?php include("../../layouts/header.php"); ?>
<?php include("../../layouts/sidebar.php"); ?>
<?php require_once("../../config/conexao.php"); ?>

<div class="container mt-4">
<h4>⚠️ Stock Crítico</h4>

<?php
$res = $conn->query("SELECT nome, quantidade FROM produtos WHERE quantidade <= 5");
?>

<table class="table table-bordered table-danger">
<tr><th>Produto</th><th>Quantidade</th></tr>

<?php while($p = $res->fetch_assoc()): ?>
<tr>
<td><?= $p['nome'] ?></td>
<td><?= $p['quantidade'] ?></td>
</tr>
<?php endwhile; ?>

</table>
</div>

<?php include("../../layouts/footer.php"); ?>