<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once("../config/conexao.php");

$inicio = $_GET['inicio'] ?? date('Y-m-01');
$fim = $_GET['fim'] ?? date('Y-m-d');
$funcionario = $_GET['funcionario'] ?? '';
$pagamento = $_GET['pagamento'] ?? '';

$where = "WHERE DATE(v.criado_em) BETWEEN '$inicio' AND '$fim'";

if ($funcionario != '') {
    $where .= " AND u.funcionario_id = '$funcionario'";
    }

    if ($pagamento != '') {
        $where .= " AND v.metodo_pagamento = '$pagamento'";
        }

        // KPIs
        $total = $conn->query("
        SELECT SUM(v.total) t FROM vendas v
        JOIN usuarios u ON u.id = v.usuario_id
        $where
        ")->fetch_assoc()['t'] ?? 0;

        $qtd_vendas = $conn->query("
        SELECT COUNT(*) c FROM vendas v
        JOIN usuarios u ON u.id = v.usuario_id
        $where
        ")->fetch_assoc()['c'];

        $ticket = $qtd_vendas ? $total / $qtd_vendas : 0;

        // Funcionários
        $funcs = $conn->query("SELECT id, nome FROM funcionarios");

        // Vendas por dia
        $res = $conn->query("
        SELECT DATE(v.criado_em) dia, SUM(v.total) total
        FROM vendas v
        JOIN usuarios u ON u.id = v.usuario_id
        $where
        GROUP BY dia
        ");

        $labels = [];
        $data = [];

        while($r = $res->fetch_assoc()){
            $labels[] = $r['dia'];
                $data[] = $r['total'];
                }
                ?>

               <?php include("../layouts/dashboard.php"); ?>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                <div class="container mt-4">

                <h3>📊 Dashboard Inteligente</h3>

                <!-- FILTROS -->
                <form class="row g-2 mb-3">

                <div class="col-md-2">
                <input type="date" name="inicio" value="<?= $inicio ?>" class="form-control">
                </div>

                <div class="col-md-2">
                <input type="date" name="fim" value="<?= $fim ?>" class="form-control">
                </div>

                <div class="col-md-3">
                <select name="funcionario" class="form-control">
                <option value="">Todos</option>
                <?php while($f = $funcs->fetch_assoc()): ?>
                <option value="<?= $f['id'] ?>"><?= $f['nome'] ?></option>
                <?php endwhile; ?>
                </select>
                </div>

                <div class="col-md-3">
                <select name="pagamento" class="form-control">
                <option value="">Todos</option>
                <option>Cash</option>
                <option>M-Pesa</option>
                <option>e-Mola</option>
                </select>
                </div>

                <div class="col-md-2">
                <button class="btn btn-primary w-100">Filtrar</button>
                </div>

                </form>

                <!-- KPIs -->
                <div class="row text-center">

                <div class="col-md-3"><div class="card p-3 shadow">
                <h6>Total</h6><h4><?= number_format($total,2) ?> MT</h4>
                </div></div>

                <div class="col-md-3"><div class="card p-3 shadow">
                <h6>Vendas</h6><h4><?= $qtd_vendas ?></h4>
                </div></div>

                <div class="col-md-3"><div class="card p-3 shadow">
                <h6>Ticket Médio</h6><h4><?= number_format($ticket,2) ?></h4>
                </div></div>

                <div class="col-md-3"><div class="card p-3 shadow">
                <h6>Período</h6><small><?= $inicio ?> → <?= $fim ?></small>
                </div></div>

                </div>

                <!-- GRÁFICOS -->
                <div class="row mt-4">

                <div class="col-md-8">
                <div class="card p-3 shadow">
                <h5>📈 Vendas</h5>
                <canvas id="linha"></canvas>
                </div>
                </div>

                <div class="col-md-4">
                <div class="card p-3 shadow">
                <h5>💳 Pagamentos</h5>
                <canvas id="pizza"></canvas>
                </div>
                </div>

                </div>

                <!-- INFO -->
                <div class="row mt-4">

                <div class="col-md-6">
                <div class="card p-3 shadow">
                <h5>🔥 Top Produtos</h5>
                <div id="top"></div>
                </div>
                </div>

                <div class="col-md-6">
                <div class="card p-3 shadow">
                <h5>⚠️ Stock Crítico</h5>
                <div id="stock"></div>
                </div>
                </div>

                </div>

                </div>

                <script>
                new Chart(document.getElementById("linha"), {
                type:'line',
                data:{ labels: <?= json_encode($labels) ?>,
                datasets:[{ data: <?= json_encode($data) ?> }] }
                });

                fetch("api_pagamentos.php?inicio=<?= $inicio ?>&fim=<?= $fim ?>")
                .then(r=>r.json())
                .then(d=>{
                new Chart(document.getElementById("pizza"), {
                type:'pie',
                data:{labels:d.labels, datasets:[{data:d.data}]}
                });
                });

                fetch("api_top_produtos.php")
                .then(r=>r.text())
                .then(t=>document.getElementById("top").innerHTML = t);

                fetch("api_stock.php")
                .then(r=>r.text())
                .then(t=>document.getElementById("stock").innerHTML = t);
                </script>

             