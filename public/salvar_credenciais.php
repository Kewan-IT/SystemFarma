<?php
require_once("../config/conexao.php");

// receber dados
$email = $_POST['email'];
$senha = $_POST['senha'];
$perfil = $_POST['perfil'];
$funcionario_id = $_POST['funcionario_id'];

// 🔥 buscar nome do funcionario
$res = $conn->query("SELECT nome FROM funcionarios WHERE id = $funcionario_id");
$f = $res->fetch_assoc();

$nome = $f['nome']; // ✔️ agora vem do banco

// hash da senha
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

// inserir
$stmt = $conn->prepare("
INSERT INTO usuarios (nome, email, senha, funcionario_id, perfil)
VALUES (?, ?, ?, ?, ?)
");

$stmt->bind_param("sssis", $nome, $email, $senha_hash, $funcionario_id, $perfil);

$stmt->execute();

echo "Usuário criado com sucesso!";