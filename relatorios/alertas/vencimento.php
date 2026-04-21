<?php include("../../layouts/header.php"); ?>
<?php include("../../layouts/sidebar.php"); ?>
<?php require_once("../../config/conexao.php"); ?>

<div class="container mt-4">
<h4>⏳ Produtos Próximos do Vencimento</h4>

<?php
$sql = "
SELECT nome, data_validade, DATEDIFF(data_validade, CURDATE()) dias
FROM produtos
WHERE data_validade <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
ORDER BY data_validade ASC
";

$res = $conn->query($sql);
?>

<table class="table table-bordered">
<tr><th>Produto</th><th>Validade</th><th>Dias Restantes</th></tr>

<?php while($p = $res->fetch_assoc()): ?>
<tr class="<?= $p['dias'] <= 7 ? 'table-danger' : 'table-warning' ?>">
<td><?= $p['nome'] ?></td>
<td><?= $p['data_validade'] ?></td>
<td><?= $p['dias'] ?></td>
</tr>
<?php endwhile; ?>

</table>
</div>

<?php include("../../layouts/footer.php"); ?>