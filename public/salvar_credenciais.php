<?php
require_once("../config/conexao.php");

$funcionario_id = $_POST['funcionario_id'];
$email = $_POST['email'];
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
$perfil = $_POST['perfil'];

// usar nome do funcionário como nome do usuário
$func = $conn->query("SELECT nome FROM funcionarios WHERE id = $funcionario_id")->fetch_assoc();
$nome = $func['nome'];

$stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, funcionario_id, perfil) VALUES (?, ?, ?, ?, ?)");

$stmt->bind_param("sssis", $nome, $email, $senha, $funcionario_id, $perfil);

if ($stmt->execute()) {
    header("Location: funcionarios_list.php?sucesso=1");
} else {
    echo "Erro: " . $stmt->error;
}