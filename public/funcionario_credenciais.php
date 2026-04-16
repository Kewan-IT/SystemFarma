<?php
require_once("../config/conexao.php");


$id = $_GET['id'];
$func = $conn->query("SELECT * FROM funcionarios WHERE id = $id")->fetch_assoc();
?>

<?php include("dashboard.php"); ?>

<div class="container mt-5">

<h4>Atribuir Credenciais</h4>

<form action="salvar_credenciais.php" method="POST">

<input type="hidden" name="funcionario_id" value="<?= $func['id'] ?>">

<div class="mb-3">
<label>Nome</label>
<input type="text" class="form-control" value="<?= $func['nome'] ?>" disabled>
</div>

<div class="mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control" required>
</div>

<div class="mb-3">
<label>Senha</label>
<input type="password" name="senha" class="form-control" required>
</div>

<div class="mb-3">
<label>Perfil</label>
<select name="perfil" class="form-control">
<option value="Admin">Admin</option>
<option value="Tecn">Tecn</option>
</select>
</div>

<button class="btn btn-success">Salvar</button>

</form>

</div>