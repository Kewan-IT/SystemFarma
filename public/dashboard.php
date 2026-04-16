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
<title>Kewan Farma</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

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
        <a href="#"><i class="bi bi-plus"></i> Nova Venda</a>
        <a href="#"><i class="bi bi-list"></i> Lista de Vendas</a>
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
    <a href="#"><i class="bi bi-pie-chart"></i> Vendas por Categoria</a>
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
    <div class="user-box text-center">
        <img src="https://via.placeholder.com/50" class="rounded-circle mb-2">
        <div><?php echo $usuario; ?></div>
        <a href="logout.php" class="btn btn-sm btn-light mt-2">Sair</a>
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
            <i class="bi bi-bell fs-5"></i>
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

</body>
</html>