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

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<style>
body {
    background: #f4f6f9;
}

.table thead {
    background: #1f2937;
    color: white;
}

.table tbody tr:hover {
    background: #f1f5f9;
}

.badge {
    border-radius: 8px;
    padding: 5px 10px;
}

.btn-group-sm .btn {
    border-radius: 6px;
}

.dataTables_wrapper .dataTables_filter input {
    border-radius: 8px;
    padding: 5px 10px;
}
</style>

</head>
<body>

<?php include("../layouts/dashboard.php"); ?>

<div class="container mt-4">

<h4>📦 Lista de Produtos</h4>

<a href="produto_create.php" class="btn btn-primary mb-3">
➕ Novo Produto
</a>

<div class="card p-3 shadow-sm">

<table id="tabelaProdutos" class="table table-bordered table-hover align-middle">

<thead>
<tr>
<th>Código</th>
<th>Nome</th>
<th>Categoria</th>
<th>Tipo</th>
<th>Lote</th>
<th>Validade</th>
<th>Stock</th>
<th>Preço</th>
<th>Status</th>
<th>Ações</th>
</tr>
</thead>

<tbody>

<?php while($row = $result->fetch_assoc()): 

    $hoje = new DateTime();
    $validade = new DateTime($row['data_validade']);
    $diff = $hoje->diff($validade)->days;

?>

<tr>

<td><?= $row['codigo'] ?></td>
<td><?= $row['nome'] ?></td>
<td><?= $row['categoria'] ?? '-' ?></td>
<td><?= $row['tipo'] ?? '-' ?></td>
<td><?= $row['lote'] ?></td>

<td><?= date("d/m/Y", strtotime($row['data_validade'])) ?></td>

<td><?= $row['quantidade'] ?></td>

<td><?= number_format($row['preco_venda'], 2, ',', '.') ?> MT</td>

<td>
<?php if($row['quantidade'] <= 10): ?>
<span class="badge bg-danger">Stock Baixo</span>

<?php elseif($validade > $hoje && $diff <= 30): ?>
<span class="badge bg-warning text-dark">Validade Próxima</span>

<?php else: ?>
<span class="badge bg-success">OK</span>
<?php endif; ?>
</td>

<td>
<div class="btn-group btn-group-sm">

<a href="produto_edit.php?id=<?= $row['id'] ?>" 
class="btn btn-warning" title="Editar">
<i class="bi bi-pencil"></i>
</a>

<?php if($row['status'] == 'ativo'): ?>

<a href="produto_delete.php?id=<?= $row['id'] ?>&acao=desativar"
class="btn btn-outline-danger"
onclick="return confirm('Deseja desativar este produto?')"
title="Desativar">
<i class="bi bi-trash"></i>
</a>

<?php else: ?>

<a href="produto_delete.php?id=<?= $row['id'] ?>&acao=ativar"
class="btn btn-outline-success"
title="Reativar">
<i class="bi bi-arrow-repeat"></i>
</a>

<?php endif; ?>

</div>
</td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>
</div>

<script>
$(document).ready(function() {
    $('#tabelaProdutos').DataTable({

        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-PT.json"
        },

        pageLength: 10,

        lengthMenu: [5, 10, 25, 50, 100],

        ordering: true,

        responsive: true

    });
});
</script>

</body>
</html>