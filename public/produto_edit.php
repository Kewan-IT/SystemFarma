<?php
require_once("../config/conexao.php");

$id = $_GET['id'];

// produto
$p = $conn->query("SELECT * FROM produtos WHERE id=$id")->fetch_assoc();

// categorias
$categorias = $conn->query("SELECT * FROM categorias");

// tipos
$tipos = $conn->query("SELECT * FROM tipos_produto");
?>
<style>
.form-control, .form-select {
    border-radius: 10px;
}
</style>
<?php include("../layouts/dashboard.php"); ?>
<div class="container mt-4">
<div class="card p-4 shadow-sm">

<h5 class="mb-4">✏️ Editar Produto</h5>

<form action="produto_update.php" method="POST">

<input type="hidden" name="id" value="<?= $p['id'] ?>">

<div class="row">

<!-- Nome -->
<div class="col-md-6 mb-3">
<label class="form-label">Nome do Produto</label>
<input type="text" name="nome" class="form-control"
value="<?= $p['nome'] ?>" required>
</div>

<!-- Categoria -->
<div class="col-md-3 mb-3">
<label class="form-label">Categoria</label>
<select name="categoria_id" class="form-select">
<?php while($c = $categorias->fetch_assoc()): ?>
<option value="<?= $c['id'] ?>" <?= ($c['id']==$p['categoria_id'])?'selected':'' ?>>
<?= $c['nome'] ?>
</option>
<?php endwhile; ?>
</select>
</div>

<!-- Tipo -->
<div class="col-md-3 mb-3">
<label class="form-label">Tipo</label>
<select name="tipo_id" class="form-select">
<?php while($t = $tipos->fetch_assoc()): ?>
<option value="<?= $t['id'] ?>" <?= ($t['id']==$p['tipo_id'])?'selected':'' ?>>
<?= $t['nome'] ?>
</option>
<?php endwhile; ?>
</select>
</div>

<!-- Lote -->
<div class="col-md-3 mb-3">
<label class="form-label">Lote</label>
<input type="text" name="lote" class="form-control"
value="<?= $p['lote'] ?>">
</div>

<!-- Validade -->
<div class="col-md-3 mb-3">
<label class="form-label">Data de Validade</label>
<input type="date" name="data_validade" class="form-control"
value="<?= $p['data_validade'] ?>">
</div>

<!-- Quantidade -->
<div class="col-md-3 mb-3">
<label class="form-label">Quantidade</label>
<input type="number" name="quantidade" class="form-control"
value="<?= $p['quantidade'] ?>">
</div>

<!-- Preço Compra -->
<div class="col-md-3 mb-3">
<label class="form-label">Preço Compra</label>
<input type="number" step="0.01" name="preco_compra" class="form-control"
value="<?= $p['preco_compra'] ?>">
</div>

<!-- Preço Venda -->
<div class="col-md-3 mb-3">
<label class="form-label">Preço Venda</label>
<input type="number" step="0.01" name="preco_venda" class="form-control"
value="<?= $p['preco_venda'] ?>">
</div>

<!-- Descrição -->
<div class="col-md-12 mb-3">
<label class="form-label">Descrição</label>
<textarea name="descricao" class="form-control" rows="3"><?= $p['descricao'] ?></textarea>
</div>

</div>

<button class="btn btn-success w-100">Atualizar Produto</button>

</form>

</div>
</div>