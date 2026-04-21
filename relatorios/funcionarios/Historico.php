<?php include("../../layouts/header.php"); ?>
<?php include("../../layouts/sidebar.php"); ?>
<?php require_once("../../config/conexao.php"); ?>

<div class="container mt-4">
<h4>📜 Histórico de Atividades (Vendas)</h4>

<?php
$sql = "
SELECT f.nome, v.total, v.created_at
FROM vendas v
JOIN usuarios u ON u.id = v.usuario_id
JOIN funcionarios f ON f.id = u.funcionario_id
ORDER BY v.created_at DESC
";

$res = $conn->query($sql);
?>

<table class="table table-bordered">
<tr>
<th>Funcionário</th>
<th>Valor</th>
<th>Data</th>
</tr>

<?php while($r = $res->fetch_assoc()): ?>
<tr>
<td><?= $r['nome'] ?></td>
<td><?= $r['total'] ?> MT</td>
<td><?= $r['created_at'] ?></td>
</tr>
<?php endwhile; ?>

</table>
</div>

<?php include("../../layouts/footer.php"); ?>