<?php include("../../layouts/header.php"); ?>
<?php include("../../layouts/sidebar.php"); ?>
<?php require_once("../../config/conexao.php"); ?>

<div class="container mt-4">
<h4>🚫 Produtos Sem Stock</h4>

<?php
$res = $conn->query("SELECT nome FROM produtos WHERE quantidade = 0");
?>

<table class="table table-bordered">
<tr><th>Produto</th></tr>

<?php while($p = $res->fetch_assoc()): ?>
<tr class="table-warning">
<td><?= $p['nome'] ?></td>
</tr>
<?php endwhile; ?>

</table>
</div>

<?php include("../../layouts/footer.php"); ?>