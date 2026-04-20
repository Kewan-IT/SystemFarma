<?php
session_start();
require_once("../config/conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // =========================
    // 📥 RECEBER DADOS
    // =========================
    $nome = $_POST['nome'] ?? '';
    $apelido = $_POST['apelido'] ?? '';
    $data_nascimento = $_POST['data_nascimento'] ?? '';
    $nivel = $_POST['nivel'] ?? '';
    $area = $_POST['area'] ?? '';
    $contacto = $_POST['contacto'] ?? '';
    $email = $_POST['email'] ?? '';

    // =========================
    // 🎂 CALCULAR IDADE
    // =========================
    $nascimento = new DateTime($data_nascimento);
    $hoje = new DateTime();
    $idade = $hoje->diff($nascimento)->y;

    // =========================
    // 📁 PASTA
    // =========================
    $pasta = "../uploads/";

    if (!is_dir($pasta)) {
        mkdir($pasta, 0755, true);
    }

    // =========================
    // 📄 UPLOAD PDF
    // =========================
    $pdf_caminho = null;

    if (isset($_FILES['documento']) && $_FILES['documento']['error'] === 0) {

        $pdf = $_FILES['documento'];

        // validar tipo
        if ($pdf['type'] !== "application/pdf") {
            die("Erro: Apenas PDF permitido!");
        }

        // validar tamanho (máx 5MB)
        if ($pdf['size'] > 5 * 1024 * 1024) {
            die("PDF muito grande (máx 5MB)");
        }

        $pdf_nome = uniqid("doc_", true) . ".pdf";
        $pdf_caminho = $pasta . $pdf_nome;

        if (!move_uploaded_file($pdf['tmp_name'], $pdf_caminho)) {
            die("Erro ao enviar PDF.");
        }
    }

    // =========================
    // 🖼️ UPLOAD FOTO
    // =========================
    $foto_nome = "default.png";

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {

        $foto = $_FILES['foto'];

        // validar tamanho (2MB)
        if ($foto['size'] > 2 * 1024 * 1024) {
            die("Imagem muito grande (máx 2MB)");
        }

        // validar imagem real
        if (!getimagesize($foto['tmp_name'])) {
            die("Arquivo de imagem inválido");
        }

        $ext = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
        $permitidos = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $permitidos)) {
            die("Formato de imagem não permitido");
        }

        $foto_nome = uniqid("user_", true) . "." . $ext;

        if (!move_uploaded_file($foto['tmp_name'], $pasta . $foto_nome)) {
            die("Erro ao enviar imagem.");
        }
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