<?php include("dashboard.php"); ?>

<div class="container mt-4">

<style>
body { background:#f4f6f9; }

.card {
    border-radius: 14px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.kpi {
    text-align:center;
    padding:15px;
}

.kpi h6 {
    font-size:13px;
    color:#888;
}

.kpi h3 {
    font-weight:bold;
}

.filtro-card {
    background:white;
    border-radius:12px;
}

canvas {
    max-height:250px;
}
</style>

<!-- 🔝 KPIs -->
<div class="row g-3 mb-4">

<div class="col-md-2"><div class="card kpi"><h6>Total de Vendas</h6><h3 id="total">0</h3></div></div>
<div class="col-md-2"><div class="card kpi"><h6>Lucro Total</h6><h3 id="lucroTotal">0 MT</h3></div></div>
<div class="col-md-2"><div class="card kpi"><h6>Produtos Vendidos</h6><h3 id="produtosQtd">0</h3></div></div>
<div class="col-md-2"><div class="card kpi"><h6>Funcionários Ativos</h6><h3 id="funcCount">0</h3></div></div>
<div class="col-md-2"><div class="card kpi"><h6>🏆 Funcionário do Mês</h6><h5 id="func_mes">-</h5></div></div>
<div class="col-md-2"><div class="card kpi"><h6>🎂 Aniversariantes</h6><h6 id="aniversariantes">-</h6></div></div>

</div>

<!-- 🎛️ FILTRO -->
<div class="card filtro-card p-3 mb-4">

<div class="row g-2 align-items-end">

<div class="col-md-3">
<label>Período</label>
<select id="periodo" class="form-select">
<option value="todos">Todos</option>
<option value="hoje">Hoje</option>
<option value="semana">Semana</option>
<option value="mes">Mês</option>
</select>
</div>

<div class="col-md-3">
<label>Data Início</label>
<input type="date" id="data_inicio" class="form-control">
</div>

<div class="col-md-3">
<label>Data Fim</label>
<input type="date" id="data_fim" class="form-control">
</div>

<div class="col-md-3">
<button onclick="carregarRelatorio()" class="btn btn-primary w-100">
🔍 Filtrar
</button>
</div>

</div>

<!-- BOTÕES EXPORTAÇÃO -->
<div class="mt-3 d-flex gap-2">
<button onclick="abrirExportacao('excel')" class="btn btn-success">
📊 Exportar Excel
</button>

<button onclick="abrirExportacao('pdf')" class="btn btn-danger">
📄 Exportar PDF
</button>
</div>

</div>

<!-- MODAL -->
<div class="modal fade" id="modalExportar">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header">
<h5>Escolher Relatório</h5>
</div>

<div class="modal-body">
<select id="tipo_relatorio" class="form-select">
<option value="vendas">📊 Vendas</option>
<option value="funcionario">👤 Por Funcionário</option>
<option value="produtos">🔥 Produtos</option>
<option value="lucro">💰 Lucro</option>
</select>
</div>

<div class="modal-footer">
<button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
<button onclick="confirmarExportacao()" class="btn btn-primary">Exportar</button>
</div>

</div>
</div>
</div>

<!-- 📊 GRÁFICOS -->
<div class="row g-3">

<div class="col-md-4">
<div class="card p-3">
<h6 class="text-center">👤 Vendas por Funcionário</h6>
<canvas id="graficoFuncionarios"></canvas>
</div>
</div>

<div class="col-md-4">
<div class="card p-3">
<h6 class="text-center">🔥 Produtos Mais Vendidos</h6>
<canvas id="graficoProdutos"></canvas>
</div>
</div>

<div class="col-md-4">
<div class="card p-3">
<h6 class="text-center">💰 Lucro por Produto</h6>
<canvas id="graficoLucro"></canvas>
</div>
</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
let graficoFuncionarios, graficoProdutos, graficoLucro;

// 🔹 CARREGAR RELATÓRIO
function carregarRelatorio() {

    let periodo = document.getElementById("periodo").value;
    let data_inicio = document.getElementById("data_inicio").value;
    let data_fim = document.getElementById("data_fim").value;

    fetch(`relatorio_data.php?periodo=${periodo}&data_inicio=${data_inicio}&data_fim=${data_fim}`)
    .then(res => res.json())
    .then(data => {

        // KPIs
        document.getElementById("total").innerText = data.total || 0;
        document.getElementById("lucroTotal").innerText = (data.lucroTotal || 0) + " MT";
        document.getElementById("produtosQtd").innerText = data.produtosQtd || 0;
        document.getElementById("funcCount").innerText = data.funcCount || 0;

        document.getElementById("func_mes").innerText = data.func_mes?.nome || "N/A";
        document.getElementById("aniversariantes").innerText =
            data.aniversariantes?.join(", ") || "Nenhum";

        // GRÁFICO FUNCIONÁRIOS
        let nomesF = data.funcionarios.map(f => f.nome);
        let valoresF = data.funcionarios.map(f => f.total);

        if (graficoFuncionarios) graficoFuncionarios.destroy();

        graficoFuncionarios = new Chart(document.getElementById("graficoFuncionarios"), {
            type: 'bar',
            data: { labels: nomesF, datasets: [{ data: valoresF }] }
        });

        // GRÁFICO PRODUTOS
        let nomesP = data.produtos.map(p => p.nome);
        let qtdP = data.produtos.map(p => p.total_qtd);

        if (graficoProdutos) graficoProdutos.destroy();

        graficoProdutos = new Chart(document.getElementById("graficoProdutos"), {
            type: 'pie',
            data: { labels: nomesP, datasets: [{ data: qtdP }] }
        });

        // GRÁFICO LUCRO
        let nomesL = data.lucro.map(l => l.nome);
        let lucroL = data.lucro.map(l => l.lucro);

        if (graficoLucro) graficoLucro.destroy();

        graficoLucro = new Chart(document.getElementById("graficoLucro"), {
            type: 'bar',
            data: { labels: nomesL, datasets: [{ data: lucroL }] }
        });

    });
}

// 🔹 EXPORTAÇÃO
let tipoExportacao = "";

function abrirExportacao(tipo) {
    tipoExportacao = tipo;
    new bootstrap.Modal(document.getElementById('modalExportar')).show();
}

function confirmarExportacao() {

    let tipoRelatorio = document.getElementById("tipo_relatorio").value;

    let periodo = document.getElementById("periodo").value;
    let data_inicio = document.getElementById("data_inicio").value;
    let data_fim = document.getElementById("data_fim").value;

    let url = tipoExportacao === 'excel'
        ? 'export_excel.php'
        : 'export_pdf.php';

    url += `?tipo=${tipoRelatorio}&periodo=${periodo}&data_inicio=${data_inicio}&data_fim=${data_fim}`;

    window.open(url);

    // fechar modal
    bootstrap.Modal.getInstance(document.getElementById('modalExportar')).hide();
}

// auto carregar
carregarRelatorio();
</script>