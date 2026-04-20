<?php
session_start();
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

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<style>
body {
    overflow-x: hidden;
    background: #f4f6f9;
}

#sidebar {
    width: 260px;
    height: 100vh;
    position: fixed;
    background: #1e293b;
    color: white;
    transition: 0.3s;
}

#sidebar.collapsed {
    margin-left: -260px;
}

#sidebar a {
    color: #cbd5e1;
    text-decoration: none;
    display: block;
    padding: 10px 15px;
    font-size: 14px;
}

#sidebar a:hover {
    background: #334155;
    color: white;
}

.submenu {
    padding-left: 20px;
}

#content {
    margin-left: 260px;
    transition: 0.3s;
}

#content.full {
    margin-left: 0;
}

.topbar {
    background: white;
    border-bottom: 1px solid #ddd;
}

.user-box {
    position: absolute;
    bottom: 0;
    width: 100%;
    padding: 15px;
    background: #0f172a;
}

.logo {
    text-align: center;
    padding: 15px;
}

.logo img {
    max-width: 150px;
}

.search-box {
    width: 50%;
}
    .logo img {
    width: 180px;
    margin-bottom: 10px;
}
</style>

</head>
<body>

<!-- SIDEBAR -->
<div id="sidebar">

    <!-- LOGO -->
    <div class="logo">
        <img src="assets/logo.png">
    </div>

    <!-- MENU -->

    <!-- PRODUTOS -->
    <a data-bs-toggle="collapse" href="#produtos">
        <i class="bi bi-capsule"></i> Produtos / Medicamentos
    </a>
    <div class="collapse submenu" id="produtos">
        <a href="produto_create.php"><i class="bi bi-plus-circle"></i> Cadastrar Produtos</a>
        <a href="produtos_list.php"><i class="bi bi-list"></i> Lista de Produtos</a>
        <a href="#"><i class="bi bi-tags"></i> Categorias</a>
        <a href="#"><i class="bi bi-box"></i> Tipos de Produto</a>
        <a href="#"><i class="bi bi-exclamation-triangle"></i> Stock Crítico</a>
    </div>

    <!-- VENDAS -->
    <a data-bs-toggle="collapse" href="#vendas">
        <i class="bi bi-cart"></i> Vendas
    </a>
    <div class="collapse submenu" id="vendas">
        <a href="venda_create.php"><i class="bi bi-plus"></i> Nova Venda</a>
        <a href="vendas_list.php"><i class="bi bi-list"></i> Lista de Vendas</a>
        <a href="#"><i class="bi bi-receipt"></i> Recibos</a>
    </div>

    <!-- DEVOLUÇÕES -->
    <a data-bs-toggle="collapse" href="#devolucoes">
    <i class="bi bi-arrow-return-left"></i> Devoluções
</a>
<div class="collapse submenu" id="devolucoes">
    <a href="#"><i class="bi bi-plus-circle"></i> Nova Devolução</a>
    <a href="#"><i class="bi bi-hourglass-split"></i> Pedidos Pendentes</a>
    <a href="#"><i class="bi bi-clock-history"></i> Histórico</a>
</div>

    <!-- FUNCIONÁRIOS -->
    <a data-bs-toggle="collapse" href="#funcionarios">
    <i class="bi bi-people"></i> Funcionários
</a>
<div class="collapse submenu" id="funcionarios">
    <a href="funcionario_create.php"><i class="bi bi-person-plus"></i> Cadastrar</a>
    <a href="funcionarios_list.php"><i class="bi bi-list-ul"></i> Lista</a>
    <a href="funcionario_credenciais.php"><i class="bi bi-key"></i> Credenciais</a>
</div>

    <!-- USUÁRIOS -->
    <a data-bs-toggle="collapse" href="#usuarios">
    <i class="bi bi-shield-lock"></i> Usuários / Segurança
</a>
<div class="collapse submenu" id="usuarios">
    <a href="#"><i class="bi bi-person-lines-fill"></i> Lista</a>
    <a href="#"><i class="bi bi-shield-check"></i> Permissões</a>
    <a href="#"><i class="bi bi-person-x"></i> Contas Bloqueadas</a>
</div>

    <!-- RELATÓRIOS -->
    <a data-bs-toggle="collapse" href="#relatorios">
    <i class="bi bi-bar-chart"></i> Relatórios
</a>
<div class="collapse submenu" id="relatorios">
    <a href="relatorios.php"><i class="bi bi-pie-chart"></i> Vendas por Categoria</a>
    <a href="#"><i class="bi bi-person-badge"></i> Vendas por Usuário</a>
    <a href="#"><i class="bi bi-cash-stack"></i> Lucros</a>
    <a href="#"><i class="bi bi-graph-up"></i> Mais Vendidos</a>
</div>

    <!-- AUDITORIA -->
    <a data-bs-toggle="collapse" href="#auditoria">
    <i class="bi bi-clipboard-data"></i> Auditoria
</a>
<div class="collapse submenu" id="auditoria">
    <a href="#"><i class="bi bi-file-earmark-text"></i> Logs do Sistema</a>
    <a href="#"><i class="bi bi-box-arrow-in-right"></i> Login Logs</a>
    <a href="#"><i class="bi bi-clock"></i> Histórico</a>
</div>

    <!-- SISTEMA -->
    <a data-bs-toggle="collapse" href="#sistema">
    <i class="bi bi-gear"></i> Sistema
</a>
<div class="collapse submenu" id="sistema">
    <a href="#"><i class="bi bi-sliders"></i> Configurações</a>
    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>
    <!-- USUÁRIO -->
   <div class="user-box text-center mt-auto p-3">

<img src="/uploads/<?= $foto ?>" 
     class="rounded-circle mb-2"
     width="70" height="70"
     style="object-fit: cover; border:2px solid #22c55e;">

<div class="text-white">
<strong><?= $func['nome'] ?? 'Usuário' ?></strong><br>
<small><?= $func['apelido'] ?? '' ?></small>
</div>

<a href="logout.php" class="btn btn-sm btn-danger mt-2 w-100">
Sair
</a>

</div>

</div>

<!-- CONTENT -->
<div id="content">

    <!-- TOPBAR -->
    <nav class="navbar topbar px-3">

        <button class="btn btn-outline-primary" onclick="toggleMenu()">
            <i class="bi bi-list"></i>
        </button>

        <input type="text" class="form-control search-box mx-3" placeholder="Pesquisar...">

        <div>
            <li class="nav-item dropdown">
    <a class="nav-link position-relative" href="#" data-bs-toggle="dropdown">
        <i class="bi bi-bell fs-5"></i>
        <span id="contador-alertas" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            0
        </span>
    </a>

    <ul class="dropdown-menu dropdown-menu-end" id="lista-alertas" style="width:300px;">
        <li class="dropdown-header">Notificações</li>
    </ul>
</li>
        </div>

    </nav>

    <!-- MAIN -->
   

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
function toggleMenu() {
    document.getElementById("sidebar").classList.toggle("collapsed");
    document.getElementById("content").classList.toggle("full");
}
</script>
<script>
function carregarAlertas() {
    fetch("get_alertas.php")
    .then(res => res.json())
    .then(data => {

        let contador = document.getElementById("contador-alertas");
        let lista = document.getElementById("lista-alertas");

        if (!contador || !lista) return;

        contador.innerText = data.length;

        lista.innerHTML = '<li class="dropdown-header">Notificações</li>';

        if (data.length === 0) {
            lista.innerHTML += '<li class="dropdown-item text-muted">Sem alertas</li>';
        }

        data.forEach(alerta => {

            let cor = alerta.tipo === "stock" ? "text-danger" : "text-warning";

            lista.innerHTML += `
                <li>
                    <a class="dropdown-item ${cor}" href="#">
                        ${alerta.mensagem}
                    </a>
                </li>
            `;
        });

    });
}

// carregar ao abrir
carregarAlertas();

// atualizar automaticamente
setInterval(carregarAlertas, 10000);
</script>
</body>
</html>