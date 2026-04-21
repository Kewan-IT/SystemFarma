<?php include("../../layouts/header.php"); ?>
<?php include("../../layouts/sidebar.php"); ?>
<?php require_once("../../config/conexao.php"); ?>

<div class="container mt-4">
<h4>🧊 Produtos Encalhados (Sem Venda)</h4>

<?php
$sql = "
SELECT p.nome
FROM produtos p
LEFT JOIN venda_itens vi ON vi.produto_id = p.id
WHERE vi.produto_id IS NULL
";

$res = $conn->query($sql);
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