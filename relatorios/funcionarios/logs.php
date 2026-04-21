<?php include("../../layouts/header.php"); ?>
<?php include("../../layouts/sidebar.php"); ?>
<?php require_once("../../config/conexao.php"); ?>

<div class="container mt-4">
<h4>🔐 Logs de Acesso</h4>

<?php
$sql = "
SELECT f.nome, l.acao, l.data
FROM logs_acesso l
JOIN usuarios u ON u.id = l.usuario_id
JOIN funcionarios f ON f.id = u.funcionario_id
ORDER BY l.data DESC
";

$res = $conn->query($sql);
?>

<table class="table table-bordered">
<tr>
<th>Funcionário</th>
<th>Ação</th>
<th>Data</th>
</tr>

<?php while($r = $res->fetch_assoc()): ?>
<tr>
<td><?= $r['nome'] ?></td>
<td><?= $r['acao'] ?></td>
<td><?= $r['data'] ?></td>
</tr>
<?php endwhile; ?>

</table>
</div>

<?php include("../../layouts/footer.php"); ?>