<?php
require_once("../config/conexao.php");


$id = $_GET['id'];
$func = $conn->query("SELECT * FROM funcionarios WHERE id = $id")->fetch_assoc();
?>

<?php include("dashboard.php"); ?>

<div class="container mt-5">

<h4>Atribuir Credenciais</h4>

<form method="POST" action="salvar_credenciais.php">


    <select name="funcionario_id" class="form-control" required>
    <option value="">Selecionar Funcionário</option>

    <?php
    $res = $conn->query("
        SELECT f.id, f.nome, f.apelido 
        FROM funcionarios f
        LEFT JOIN usuarios u ON f.id = u.funcionario_id
        WHERE u.id IS NULL
    ");

    while($f = $res->fetch_assoc()):
    ?>
        <option value="<?= $f['id'] ?>">
            <?= $f['nome'] ?> <?= $f['apelido'] ?>
        </option>
    <?php endwhile; ?>
</select><!-- lista funcionários -->

<input type="email" name="email" required>
<input type="password" name="senha" required>

<select name="perfil">
    <option value="Admin">Admin</option>
    <option value="Tecn">Tecn</option>
</select>

<button type="submit">Salvar</button>

</form>

</div>