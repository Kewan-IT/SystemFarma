<?php
session_start();
require_once("../config/conexao.php");

// VERIFICAR SE VEIO DO FORMULÁRIO
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // RECEBER DADOS
    $nome = $_POST['nome'];
    $apelido = $_POST['apelido'];
    $data_nascimento = $_POST['data_nascimento'];
    $nivel = $_POST['nivel'];
    $area = $_POST['area'];
    $contacto = $_POST['contacto'];
    $email = $_POST['email'];

    // CALCULAR IDADE (BACKEND)
    $nascimento = new DateTime($data_nascimento);
    $hoje = new DateTime();
    $idade = $hoje->diff($nascimento)->y;

    // 📁 PASTA DE UPLOAD
    $pasta = "uploads/";

    // =========================
    // 📄 UPLOAD PDF
    // =========================
    $pdf = $_FILES['documento'];

    if ($pdf['type'] != "application/pdf") {
        die("Erro: Apenas PDF permitido!");
    }

    $pdf_nome = uniqid() . ".pdf";
    $pdf_caminho = $pasta . $pdf_nome;

    move_uploaded_file($pdf['tmp_name'], $pdf_caminho);

    // =========================
    // 🖼️ UPLOAD FOTO
    // =========================
   $foto_nome = null;

if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {

    $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $foto_nome = uniqid() . "." . $ext;

    move_uploaded_file(
        $_FILES['foto']['tmp_name'],
        "../uploads/" . $foto_nome
    );
}

    // =========================
    // 💾 INSERIR NO BANCO
    // =========================

    $stmt = $conn->prepare("INSERT INTO funcionarios 
        (nome, apelido, data_nascimento, idade, nivel_academico, area_formacao, contacto, email, documento_pdf, foto) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "sssissssss",
        $nome,
        $apelido,
        $data_nascimento,
        $idade,
        $nivel,
        $area,
        $contacto,
        $email,
        $pdf_caminho,
        $foto_nome
    );

    if ($stmt->execute()) {
        header("Location: funcionario_create.php?sucesso=1");
        exit();
    } else {
        echo "Erro ao salvar: " . $stmt->error;
    }
}
?>