<?php
session_start();
require_once("../config/conexao.php");

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$query = "
SELECT p.*, c.nome AS categoria, t.nome AS tipo
FROM produtos p
LEFT JOIN categorias c ON p.categoria_id = c.id
LEFT JOIN tipos_produto t ON p.tipo_id = t.id
ORDER BY p.id DESC
";

$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Lista de Produtos</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.stock-critico {
    background-color: #f8d7da !important;
}

.validade-proxima {
    background-color: #fff3cd !important;
}
</style>

</head>
<body>

<?php include("dashboard.php"); ?>

<div class="container mt-4">

<h4>📦 Lista de Produtos</h4>

<a href="produto_create.php" class="btn btn-primary mb-3">
➕ Novo Produto
</a>

<div class="table-responsive">

<table class="table table-bordered table-hover bg-white">

<thead class="table-dark">
<tr>
<th>Código</th>
<th>Nome</th>
<th>Categoria</th>
<th>Tipo</th>
<th>Lote</th>
<th>Validade</th>
<th>Stock</th>
<th>Preço Venda</th>
<th>Status</th>
</tr>
</thead>

<tbody>

<?php while($row = $result->fetch_assoc()): 

    $classe = "";

    // 🔻 Stock crítico
    if ($row['quantidade'] <= 10) {
        $classe = "stock-critico";
    }

    // ⏰ Validade próxima (30 dias)
    $hoje = new DateTime();
    $validade = new DateTime($row['data_validade']);
    $diff = $hoje->diff($validade)->days;

    if ($validade > $hoje && $diff <= 30) {
        $classe = "validade-proxima";
    }

?>

<tr class="<?= $classe ?>">

<td><?= $row['codigo'] ?></td>
<td><?= $row['nome'] ?></td>
<td><?= $row['categoria'] ?></td>
<td><?= $row['tipo'] ?></td>
<td><?= $row['lote'] ?></td>

<td>
<?= date("d/m/Y", strtotime($row['data_validade'])) ?>
</td>

<td><?= $row['quantidade'] ?></td>

<td><?= number_format($row['preco_venda'], 2, ',', '.') ?></td>

<td>

<?php if($row['quantidade'] <= 10): ?>
<span class="badge bg-danger">Stock Baixo</span>

<?php elseif($validade > $hoje && $diff <= 30): ?>
<span class="badge bg-warning text-dark">Validade Próxima</span>

<?php else: ?>
<span class="badge bg-success">OK</span>
<?php endif; ?>

</td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>

</div>

</body>
</html>