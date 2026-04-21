<?php include("../../layouts/header.php"); ?>
<?php include("../../layouts/sidebar.php"); ?>
<?php require_once("../../config/conexao.php"); ?>

<div class="container mt-4">
<h4>🏆 Ranking de Vendedores</h4>

<?php
$sql = "
SELECT f.nome, SUM(v.total) total
FROM vendas v
JOIN usuarios u ON u.id = v.usuario_id
JOIN funcionarios f ON f.id = u.funcionario_id
GROUP BY f.id
ORDER BY total DESC
";

$res = $conn->query($sql);
$pos = 1;
?>

<table class="table table-bordered">
<tr><th>Posição</th><th>Funcionário</th><th>Total</th></tr>

<?php while($r = $res->fetch_assoc()): ?>
<tr class="<?= $pos == 1 ? 'table-success' : '' ?>">
<td><?= $pos++ ?></td>
<td><?= $r['nome'] ?></td>
<td><?= number_format($r['total'],2) ?> MT</td>
</tr>
<?php endwhile; ?>

</table>
</div>

<?php include("../../layouts/footer.php"); ?>