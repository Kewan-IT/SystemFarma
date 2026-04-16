<?php
session_start();
require_once("../config/conexao.php");

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$result = $conn->query("SELECT * FROM funcionarios ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Lista de Funcionários</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
body { background: #f4f6f9; }

.table img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 50%;
}
</style>

</head>
<body>

<?php include("dashboard.php"); ?>

<div class="container mt-4">

<h4><i class="bi bi-people"></i> Lista de Funcionários</h4>

<a href="funcionario_create.php" class="btn btn-primary mb-3">
    <i class="bi bi-plus"></i> Novo Funcionário
</a>

<div class="table-responsive">

<table class="table table-bordered table-hover bg-white">

<thead class="table-dark">
<tr>
    <th>#</th>
    <th>Foto</th>
    <th>Nome</th>
    <th>Contacto</th>
    <th>Email</th>
    <th>Nível</th>
    <th>Ações</th>
</tr>
</thead>

<tbody>

<?php while($row = $result->fetch_assoc()): ?>

<tr>
    <td><?php echo $row['id']; ?></td>

    <td>
        <img src="<?php echo $row['foto']; ?>">
    </td>

    <td>
        <?php echo $row['nome'] . " " . $row['apelido']; ?>
    </td>

    <td><?php echo $row['contacto']; ?></td>
    <td><?php echo $row['email']; ?></td>
    <td><?php echo $row['nivel_academico']; ?></td>

    <td>

        <a href="funcionario_show.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">
            <i class="bi bi-eye"></i>
        </a>

        <a href="funcionario_edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
            <i class="bi bi-pencil"></i>
        </a>
        <a href="funcionario_credenciais.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary btn-sm">
    <i class="bi bi-key"></i>
</a>

        <a href="funcionario_delete.php?id=<?php echo $row['id']; ?>" 
           class="btn btn-danger btn-sm"
           onclick="return confirm('Tem certeza que deseja excluir?')">
            <i class="bi bi-trash"></i>
        </a>

    </td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>

</div>

</body>
</html>