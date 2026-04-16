<?php
require_once("../config/conexao.php");

$categorias = $conn->query("SELECT * FROM categorias");
?>
<?php include("dashboard.php"); ?>
<div class="container mt-4">

<h4>Cadastrar Produto</h4>

<form action="produto_store.php" method="POST">

<div class="row">

<div class="col-md-4">
<label>Categoria</label>
<select name="categoria_id" id="categoria" class="form-control" required>
<option value="">Selecione</option>
<?php while($cat = $categorias->fetch_assoc()): ?>
<option value="<?= $cat['id'] ?>"><?= $cat['nome'] ?></option>
<?php endwhile; ?>
</select>
</div>

<div class="col-md-4">
<label>Tipo</label>
<select name="tipo_id" id="tipo" class="form-control" required>
<option value="">Selecione</option>
</select>
</div>

<div class="col-md-4">
<label>Nome</label>
<input type="text" name="nome" class="form-control" required>
</div>

<div class="col-md-3">
<label>Lote</label>
<input type="text" name="lote" class="form-control">
</div>

<div class="col-md-3">
<label>Validade</label>
<input type="date" name="validade" class="form-control">
</div>

<div class="col-md-3">
<label>Quantidade</label>
<input type="number" name="quantidade" class="form-control">
</div>

<div class="col-md-3">
<label>Preço Compra</label>
<input type="number" step="0.01" name="preco_compra" class="form-control">
</div>

<div class="col-md-3">
<label>Preço Venda</label>
<input type="number" step="0.01" name="preco_venda" class="form-control">
</div>

<div class="col-md-9">
<label>Descrição</label>
<textarea name="descricao" class="form-control"></textarea>
</div>

</div>

<button class="btn btn-success mt-3">Salvar</button>

</form>

</div>

<script>
document.getElementById("categoria").addEventListener("change", function() {
    let id = this.value;

    fetch("get_tipos.php?categoria_id=" + id)
    .then(res => res.text())
    .then(data => {
        document.getElementById("tipo").innerHTML = data;
    });
});
</script>