<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$usuario = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Cadastrar Funcionário</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
body { background: #f4f6f9; }
.form-container {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
</style>
</head>

<body>
<?php if(isset($_GET['sucesso'])): ?>
<div class="alert alert-success">
    Funcionário cadastrado com sucesso!
</div>
<?php endif; ?>
<!-- IMPORTAR SUA SIDEBAR + TOPBAR -->
<?php include("dashboard.php"); ?>

<div class="container mt-4">

<div class="form-container">

<h4><i class="bi bi-person-plus"></i> Cadastrar Funcionário</h4>

<form action="funcionario_store.php" method="POST" enctype="multipart/form-data">

<div class="row">

<!-- Nome -->
<div class="col-md-6 mb-3">
<label>Nome</label>
<input type="text" name="nome" class="form-control" required>
</div>

<!-- Apelido -->
<div class="col-md-6 mb-3">
<label>Apelido</label>
<input type="text" name="apelido" class="form-control" required>
</div>

<!-- Data Nascimento -->
<div class="col-md-4 mb-3">
<label>Data de Nascimento</label>
<input type="date" name="data_nascimento" id="data_nascimento" class="form-control" required>
</div>

<!-- Idade -->
<div class="col-md-2 mb-3">
<label>Idade</label>
<input type="text" id="idade" class="form-control" readonly>
</div>

<!-- Nivel Academico -->
<div class="col-md-3 mb-3">
<label>Nível Acadêmico</label>
<select name="nivel" class="form-control" required>
<option value="">Selecione</option>
<option value="Medio">Médio</option>
<option value="Superior">Superior</option>
</select>
</div>

<!-- Área -->
<div class="col-md-3 mb-3">
<label>Área de Formação</label>
<input type="text" name="area" class="form-control">
</div>

<!-- Contacto -->
<div class="col-md-4 mb-3">
<label>Contacto</label>
<input type="text" name="contacto" class="form-control" required>
</div>

<!-- Email -->
<div class="col-md-4 mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control">
</div>

<!-- Documento PDF -->
<div class="col-md-4 mb-3">
<label>Documentos (PDF)</label>
<input type="file" name="documento" class="form-control" accept="application/pdf" required>
</div>

<!-- Foto -->
<div class="col-md-4 mb-3">
<label>Foto (tipo passe)</label>
<input type="file" name="foto" class="form-control" accept="image/*" required>
</div>

</div>

<button type="submit" class="btn btn-primary">
<i class="bi bi-save"></i> Salvar
</button>

</form>

</div>

</div>

<!-- SCRIPT IDADE AUTOMÁTICA -->
<script>
document.getElementById("data_nascimento").addEventListener("change", function() {
    let nascimento = new Date(this.value);
    let hoje = new Date();

    let idade = hoje.getFullYear() - nascimento.getFullYear();
    let mes = hoje.getMonth() - nascimento.getMonth();

    if (mes < 0 || (mes === 0 && hoje.getDate() < nascimento.getDate())) {
        idade--;
    }

    document.getElementById("idade").value = idade;
});
</script>

</body>
</html>