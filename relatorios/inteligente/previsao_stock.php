<?php include("../../layouts/header.php"); ?>
<?php include("../../layouts/sidebar.php"); ?>
<?php require_once("../../config/conexao.php"); ?>

<div class="container mt-4">
<h4>🔮 Previsão de Stock</h4>

<?php
$sql = "
SELECT p.nome, p.quantidade,
AVG(vi.quantidade) media_venda
FROM produtos p
LEFT JOIN venda_itens vi ON vi.produto_id = p.id
GROUP BY p.id
";

$res = $conn->query($sql);
?>

<table class="table table-bordered">
<tr>
<th>Produto</th>
<th>Stock Atual</th>
<th>Média Venda</th>
<th>Dias Restantes</th>
</tr>

<?php while($r = $res->fetch_assoc()):

$media = $r['media_venda'] ?: 1;
$dias = $r['quantidade'] / $media;
?>

<tr class="<?= $dias < 5 ? 'table-danger' : '' ?>">
<td><?= $r['nome'] ?></td>
<td><?= $r['quantidade'] ?></td>
<td><?= round($media,2) ?></td>
<td><?= round($dias) ?> dias</td>
</tr>

<?php endwhile; ?>
</table>

</div>
<?php include("../../layouts/footer.php"); ?>