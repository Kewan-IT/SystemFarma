<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once("../config/conexao.php");

$funcionario_id = $_SESSION['funcionario_id'] ?? null;
$func = null;

if ($funcionario_id) {
    $sql = "SELECT nome, apelido, foto FROM funcionarios WHERE id = $funcionario_id";
    $res = $conn->query($sql);
    $func = $res->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Kewan Farma</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>

body {
    margin:0;
    font-family: 'Segoe UI', sans-serif;
    background:#f1f5f9;
}

/* SIDEBAR */
.sidebar {
    width: 260px;
    height: 100vh;
    position: fixed;
    background: #0f172a;
    color: #fff;
    transition: 0.3s;
}

.sidebar.collapsed {
    width: 80px;
}

.sidebar .logo {
    text-align:center;
    padding:20px;
}

.sidebar .logo img {
    width: 120px;
}

.sidebar ul {
    list-style:none;
    padding:0;
}

.sidebar ul li {
    padding:10px 20px;
    cursor:pointer;
}

.sidebar ul li:hover {
    background:#1e293b;
}

.sidebar ul li i {
    margin-right:10px;
}

.submenu {
    display:none;
    background:#1e293b;
}

.submenu a {
    display:block;
    padding:8px 40px;
    font-size:14px;
    color:#cbd5e1;
    text-decoration:none;
}

.submenu a:hover {
    background:#334155;
}

/* CONTENT */
.content {
    margin-left:260px;
    transition:0.3s;
}

.content.full {
    margin-left:80px;
}

/* TOPBAR */
.topbar {
    background:#fff;
    padding:10px 20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 2px 5px rgba(0,0,0,0.05);
}

/* USER */
.user-box {
    position:absolute;
    bottom:0;
    width:100%;
    padding:15px;
    background:#020617;
    text-align:center;
}

.user-box img {
    border:2px solid #22c55e;
}

/* ANIMATION */
.rotate {
    transform: rotate(90deg);
    transition:0.3s;
}

</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">

    <div class="logo">
        <img src="assets/logo.png">
    </div>

    <ul>

        <li onclick="toggleSubmenu('produtos', this)">
            <i class="bi bi-capsule"></i> Produtos
            <i class="bi bi-chevron-right float-end"></i>
        </li>
        <div class="submenu" id="produtos">
            <a href="produto_create.php">Cadastrar</a>
            <a href="produtos_list.php">Lista</a>
        </div>

        <li onclick="toggleSubmenu('vendas', this)">
            <i class="bi bi-cart"></i> Vendas
            <i class="bi bi-chevron-right float-end"></i>
        </li>
        <div class="submenu" id="vendas">
            <a href="venda_create.php">Nova Venda</a>
            <a href="vendas_list.php">Lista</a>
        </div>

        <li onclick="toggleSubmenu('func', this)">
            <i class="bi bi-people"></i> Funcionários
            <i class="bi bi-chevron-right float-end"></i>
        </li>
        <div class="submenu" id="func">
            <a href="funcionario_create.php">Cadastrar</a>
            <a href="funcionarios_list.php">Lista</a>
        </div>

        <li onclick="toggleSubmenu('rel', this)">
            <i class="bi bi-bar-chart"></i> Relatórios
            <i class="bi bi-chevron-right float-end"></i>
        </li>
        <div class="submenu" id="rel">
            <a href="dashboard_pro.php">📊 Geral</a>
        </div>

    </ul>

    <!-- USER -->
    <div class="user-box">

        <img src="/uploads/<?= $func['foto'] ?? 'default.png' ?>"
        onerror="this.src='/uploads/default.png'"
        width="60" height="60" class="rounded-circle mb-2">

        <div>
            <strong><?= $func['nome'] ?? 'Usuário' ?></strong><br>
            <small><?= $func['apelido'] ?? '' ?></small>
        </div>

        <a href="logout.php" class="btn btn-danger btn-sm mt-2 w-100">Sair</a>

    </div>

</div>

<!-- CONTENT -->
<div class="content" id="content">

    <!-- TOPBAR -->
    <div class="topbar">

        <button class="btn btn-outline-dark" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>

        <input type="text" class="form-control w-50" placeholder="Pesquisar...">

        <i class="bi bi-bell fs-4"></i>

    </div>

    <!-- MAIN -->
  

</div>

<script>

// sidebar toggle
function toggleSidebar() {
    document.getElementById("sidebar").classList.toggle("collapsed");
    document.getElementById("content").classList.toggle("full");
}

// submenu toggle
function toggleSubmenu(id, el) {

    let menu = document.getElementById(id);

    if (menu.style.display === "block") {
        menu.style.display = "none";
        el.querySelector(".bi-chevron-right").classList.remove("rotate");
    } else {
        menu.style.display = "block";
        el.querySelector(".bi-chevron-right").classList.add("rotate");
    }
}

</script>

</body>
</html>