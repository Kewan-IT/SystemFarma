<div class="sidebar" id="sidebar">

    <div class="logo">
        <img src="assets/logo.png">
    </div>

    <ul class="menu">

        <li onclick="toggleSub('produtos', this)">
            <div><i class="bi bi-capsule"></i> <span>Produtos</span></div>
            <i class="bi bi-chevron-right"></i>
        </li>
        <div class="submenu" id="produtos">
            <a href="produto_create.php">Cadastrar</a>
            <a href="produtos_list.php">Lista</a>
        </div>

        <li onclick="toggleSub('vendas', this)">
            <div><i class="bi bi-cart"></i> <span>Vendas</span></div>
            <i class="bi bi-chevron-right"></i>
        </li>
        <div class="submenu" id="vendas">
            <a href="venda_create.php">Nova Venda</a>
            <a href="vendas_list.php">Lista</a>
        </div>

        <li onclick="toggleSub('func', this)">
            <div><i class="bi bi-people"></i> <span>Funcionários</span></div>
            <i class="bi bi-chevron-right"></i>
        </li>
        <div class="submenu" id="func">
            <a href="funcionario_create.php">Cadastrar</a>
            <a href="funcionarios_list.php">Lista</a>
        </div>

        <li onclick="toggleSub('rel', this)">
            <div><i class="bi bi-bar-chart"></i> <span>Relatórios</span></div>
            <i class="bi bi-chevron-right"></i>
        </li>
        <div class="submenu" id="rel">
            <a href="relatorios.php">Ver Relatórios</a>
        </div>

    </ul>

    <!-- USER -->
    <div class="user-box">

        <img src="/uploads/<?= $func['foto'] ?? 'default.png' ?>"
        onerror="this.src='/uploads/default.png'"
        width="60" height="60">

        <div>
            <strong><?= $func['nome'] ?? 'Usuário' ?></strong><br>
            <small><?= $func['apelido'] ?? '' ?></small>
        </div>

        <a href="logout.php" style="color:red;">Sair</a>

    </div>

</div>

<div class="content">

    <div class="topbar">
        <button onclick="toggleSidebar()">☰</button>
        <h4>Dashboard</h4>
    </div>