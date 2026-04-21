<?php
session_start();
require_once("../config/conexao.php");

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// 🔎 FILTROS
$categoria_id = $_GET['categoria_id'] ?? '';
$tipo_id = $_GET['tipo_id'] ?? '';
$status = $_GET['status'] ?? '';

// 📌 BUSCAR DADOS PARA SELECT
$categorias = $conn->query("SELECT id, nome FROM categorias");
$tipos = $conn->query("SELECT id, nome FROM tipos_produto");

// 🧠 QUERY BASE
$query = "
SELECT p.*, c.nome AS categoria, t.nome AS tipo
FROM produtos p
LEFT JOIN categorias c ON p.categoria_id = c.id
LEFT JOIN tipos_produto t ON p.tipo_id = t.id
WHERE 1=1
";

// 📌 APLICAR FILTROS
if (!empty($categoria_id)) {
    $query .= " AND p.categoria_id = " . intval($categoria_id);
}

if (!empty($tipo_id)) {
    $query .= " AND p.tipo_id = " . intval($tipo_id);
}

if (!empty($status)) {
    $query .= " AND p.status = '" . $conn->real_escape_string($status) . "'";
}

$query .= " ORDER BY p.id DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Lista de Produtos</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<style>
body { background:#f4f6f9; }
.table thead { background:#1f2937; color:white; }
.badge { border-radius:8px; }
</style>
</head>

<body>

<?php include("../layouts/dashboard.php"); ?>

<div class="container mt-4">

<h4>📦 Lista de Produtos</h4>

<a href="produto_create.php" class="btn btn-primary mb-3">
➕ Novo Produto
</a>

<!-- 🔎 FILTROS -->
<form method="GET" class="row mb-3">

<div class="col-md-3">
<select name="categoria_id" class="form-control" onchange="this.form.submit()">
<option value="">Todas Categorias</option>
<?php while($c = $categorias->fetch_assoc()): ?>
<option value="<?= $c['id'] ?>" <?= ($categoria_id == $c['id']) ? 'selected' : '' ?>>
<?= $c['nome'] ?>
</option>
<?php endwhile; ?>
</select>
</div>

<div class="col-md-3">
<select name="tipo_id" class="form-control" onchange="this.form.submit()">
<option value="">Todos Tipos</option>
<?php while($t = $tipos->fetch_assoc()): ?>
<option value="<?= $t['id'] ?>" <?= ($tipo_id == $t['id']) ? 'selected' : '' ?>>
<?= $t['nome'] ?>
</option>
<?php endwhile; ?>
</select>
</div>

<div class="col-md-3">
<select name="status" class="form-control" onchange="this.form.submit()">
<option value="">Todos Status</option>
<option value="ativo" <?= ($status=='ativo')?'selected':'' ?>>Ativo</option>
<option value="inativo" <?= ($status=='inativo')?'selected':'' ?>>Inativo</option>
</select>
</div>

<div class="col-md-3">
<a href="produtos_list.php" class="btn btn-secondary w-100">Limpar Filtros</a>
</div>

</form>

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

<td><?= number_format($row['preco_venda'],2,',','.') ?> MT</td>

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

<a href="produto_edit.php?id=<?= $row['id'] ?>" class="btn btn-warning">
<i class="bi bi-pencil"></i>
</a>

<?php if($row['status']=='ativo'): ?>
<a href="produto_delete.php?id=<?= $row['id'] ?>&acao=desativar"
class="btn btn-danger"
onclick="return confirm('Desativar produto?')">
<i class="bi bi-trash"></i>
</a>
<?php else: ?>
<a href="produto_delete.php?id=<?= $row['id'] ?>&acao=ativar"
class="btn btn-success">
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
language:{ url:"//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-PT.json" },
pageLength:10
});
});
</script>

</body>
</html>