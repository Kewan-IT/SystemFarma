<?php
session_start();
require_once("../config/conexao.php");

// buscar funcionários
$usuarios = $conn->query("SELECT id, nome FROM usuarios");

// filtro
$filtro = $_GET['usuario_id'] ?? '';

// query principal (SEGURA)
$query = "
SELECT 
    f.nome AS funcionario_nome,
    f.apelido,
    p.nome AS produto,
    vi.quantidade,
    vi.preco,
    v.metodo_pagamento,
    v.criado_em
FROM venda_itens vi
LEFT JOIN vendas v ON vi.venda_id = v.id
LEFT JOIN produtos p ON vi.produto_id = p.id
LEFT JOIN usuarios u ON v.usuario_id = u.id
LEFT JOIN funcionarios f ON u.funcionario_id = f.id
";

if (!empty($filtro)) {
    $query .= " WHERE v.usuario_id = " . intval($filtro);
}

$query .= " ORDER BY v.criado_em DESC";

$result = $conn->query($query);

// total geral
$totalGeral = 0;
?>
<!-- INTERFACE -->

<?php include("dashboard.php"); ?>
<div class="container mt-4">

<h4>📊 Lista de Vendas</h4>

<!-- FILTRO -->
<form method="GET" class="mb-3">

<select name="usuario_id" class="form-control" onchange="this.form.submit()">

<option value="">Todos Funcionários</option>

<?php while($u = $usuarios->fetch_assoc()): ?>
<option value="<?= $u['id'] ?>" <?= ($filtro == $u['id']) ? 'selected' : '' ?>>
<?= $u['nome'] ?>
</option>
<?php endwhile; ?>

</select>

</form>

<!-- TABELA -->
<div class="table-responsive">

<table class="table table-bordered table-hover bg-white">

<thead class="table-dark">
<tr>
<th>Funcionário</th>
<th>Produto</th>
<th>Qtd</th>
<th>Preço</th>
<th>Total</th>
<th>Pagamento</th>
<th>Data</th>
</tr>
</thead>

<tbody>

<?php if($result->num_rows > 0): ?>

<?php while($row = $result->fetch_assoc()): 

    $totalLinha = $row['preco'] * $row['quantidade'];
    $totalGeral += $totalLinha;
?>

<tr>

<td>
<?= ($row['funcionario_nome']) 
    ? $row['funcionario_nome'] . ' ' . $row['apelido'] 
    : 'N/A' ?>
</td>
<td><?= $row['produto'] ?? 'N/A' ?></td>
<td><?= $row['quantidade'] ?></td>
<td><?= number_format($row['preco'], 2, ',', '.') ?></td>
<td><?= number_format($totalLinha, 2, ',', '.') ?></td>
<td><?= $row['metodo_pagamento'] ?? 'N/A' ?></td>
<td>
<?= $row['criado_em'] ? date("d/m/Y H:i", strtotime($row['criado_em'])) : 'N/A' ?>
</td>
</tr>

<?php endwhile; ?>

<?php else: ?>

<tr>
<td colspan="7" class="text-center text-muted">
Nenhuma venda encontrada
</td>
</tr>

<?php endif; ?>

</tbody>

</table>

</div>

<!-- TOTAL GERAL -->
<div class="mt-3">
<h5>💰 Total Geral: <?= number_format($totalGeral, 2, ',', '.') ?> MT</h5>
</div>

</div>