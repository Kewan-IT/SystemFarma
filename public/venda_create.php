<?php
session_start();
require_once("../config/conexao.php");
?>
<?php include("dashboard.php"); ?>

<div class="container mt-4">
<h4>💊 Nova Venda</h4>

<div class="row">

<!-- BUSCA PRODUTO -->
<div class="col-md-5">

<div class="mb-2 position-relative">
<input type="text" id="busca" class="form-control" placeholder="🔍 Digite o nome do produto">
<div id="resultados" class="list-group position-absolute w-100"></div>
</div>

<input type="number" id="qtd" class="form-control mb-2" placeholder="Quantidade">

<button onclick="addCarrinhoManual()" class="btn btn-primary w-100">
Adicionar
</button>

</div>

<!-- CARRINHO -->
<div class="col-md-7">

<table class="table">
<thead>
<tr>
<th>Produto</th>
<th>Qtd</th>
<th>Preço</th>
<th>Total</th>
<th></th>
</tr>
</thead>

<tbody id="carrinho"></tbody>
</table>

<h4>Total: <span id="total">0</span> MT</h4>

<select id="pagamento" class="form-control mb-2">
<option value="Cash">Cash</option>
<option value="e-Mola">e-Mola</option>
<option value="M-Pesa">M-Pesa</option>
</select>

<button onclick="finalizarVenda()" class="btn btn-success w-100">
Finalizar Venda
</button>

</div>

</div>

<style>
#resultados {
    max-height: 200px;
    overflow-y: auto;
    z-index: 999;
}
</style>

<script>
let carrinho = [];
let produtoSelecionado = null;

// 🔍 BUSCA EM TEMPO REAL
document.getElementById("busca").addEventListener("keyup", async function () {

    let termo = this.value;

    if (termo.length < 2) {
        document.getElementById("resultados").innerHTML = "";
        return;
    }

    let res = await fetch("buscar_produto.php?q=" + termo);
    let dados = await res.json();

    let html = "";

    dados.forEach(p => {
        html += `
        <a href="#" class="list-group-item list-group-item-action"
        onclick="selecionarProduto(${p.id}, '${p.nome}', ${p.preco_venda}, ${p.quantidade})">
        ${p.nome} - ${p.preco_venda} MT (Stock: ${p.quantidade})
        </a>`;
    });

    document.getElementById("resultados").innerHTML = html;
});

// selecionar produto
function selecionarProduto(id, nome, preco, stock) {
    produtoSelecionado = { id, nome, preco, stock };
    document.getElementById("busca").value = nome;
    document.getElementById("resultados").innerHTML = "";
}

// adicionar carrinho
function addCarrinhoManual() {

    if (!produtoSelecionado) return alert("Selecione um produto");

    let qtd = parseInt(document.getElementById("qtd").value);

    if (!qtd || qtd <= 0) return alert("Quantidade inválida");

    if (qtd > produtoSelecionado.stock)
        return alert("Stock insuficiente!");

    let existente = carrinho.find(p => p.id === produtoSelecionado.id);

    if (existente) {
        existente.qtd += qtd;
    } else {
        carrinho.push({
            id: produtoSelecionado.id,
            nome: produtoSelecionado.nome,
            preco: produtoSelecionado.preco,
            qtd: qtd
        });
    }

    render();

    document.getElementById("busca").value = "";
    document.getElementById("qtd").value = "";
    produtoSelecionado = null;
}

// render carrinho
function render() {

    let tabela = document.getElementById("carrinho");
    let total = 0;

    tabela.innerHTML = "";

    carrinho.forEach((item, i) => {

        let subtotal = item.preco * item.qtd;
        total += subtotal;

        tabela.innerHTML += `
        <tr>
            <td>${item.nome}</td>
            <td>${item.qtd}</td>
            <td>${item.preco}</td>
            <td>${subtotal}</td>
            <td><button onclick="remover(${i})">X</button></td>
        </tr>`;
    });

    document.getElementById("total").innerText = total.toFixed(2);
}

function remover(i) {
    carrinho.splice(i,1);
    render();
}

// finalizar venda
function finalizarVenda() {

    if (carrinho.length === 0) return alert("Carrinho vazio!");

    fetch("venda_store.php", {
        method: "POST",
        credentials: "same-origin",
        headers: {"Content-Type":"application/json"},
        body: JSON.stringify({
            carrinho: carrinho,
            pagamento: document.getElementById("pagamento").value
        })
    })
    .then(res => res.text())
    .then(res => {
        alert(res);
        location.reload();
    });
}
</script>

</div>