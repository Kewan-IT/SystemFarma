<?php
require_once("../config/conexao.php");
?>
<?php include("../layouts/dashboard.php"); ?>

<div class="container mt-4">
<style>
.form-label {
    font-weight: 500;
}

.card {
    border-radius: 12px;
}

.list-group {
    z-index: 1000;
    max-height: 200px;
    overflow-y: auto;
}
</style>
<h4 class="mb-3">📦 Cadastrar Produto</h4>

<form action="produto_store.php" method="POST">

<div class="card p-4 shadow-sm">

<div class="row g-3">

<!-- CATEGORIA -->
<div class="col-md-6 position-relative">
<label class="form-label">Categoria</label>
<input type="text" id="categoria_input" class="form-control" placeholder="Digite a categoria">
<input type="hidden" name="categoria_id" id="categoria_id">
<div id="lista_categorias" class="list-group position-absolute w-100"></div>
</div>

<!-- TIPO -->
<div class="col-md-6 position-relative">
<label class="form-label">Tipo</label>
<input type="text" id="tipo_input" class="form-control" disabled placeholder="Selecione categoria">
<input type="hidden" name="tipo_id" id="tipo_id">
<div id="lista_tipos" class="list-group position-absolute w-100"></div>
</div>

<!-- NOME -->
<div class="col-md-6">
<label class="form-label">Nome</label>
<input type="text" name="nome" class="form-control" required>
</div>

<!-- LOTE -->
<div class="col-md-3">
<label class="form-label">Lote</label>
<input type="text" name="lote" class="form-control">
</div>

<!-- VALIDADE -->
<div class="col-md-3">
<label class="form-label">Validade</label>
<input type="date" name="validade" class="form-control">
</div>

<!-- QUANTIDADE -->
<div class="col-md-3">
<label class="form-label">Quantidade</label>
<input type="number" name="quantidade" class="form-control">
</div>

<!-- PREÇO COMPRA -->
<div class="col-md-3">
<label class="form-label">Preço Compra</label>
<input type="number" step="0.01" name="preco_compra" class="form-control">
</div>

<!-- PREÇO VENDA -->
<div class="col-md-3">
<label class="form-label">Preço Venda</label>
<input type="number" step="0.01" name="preco_venda" class="form-control">
</div>

<!-- DESCRIÇÃO -->
<div class="col-md-12">
<label class="form-label">Descrição</label>
<textarea name="descricao" class="form-control" rows="2"></textarea>
</div>

</div>

<!-- BOTÃO -->

<div class="text-end mt-3">
<button id="btnSalvar" class="btn btn-success px-4">
💾 Salvar
</button>
</div>

</div>

</form>

</div>

<!-- TOAST -->
<div class="toast position-fixed top-0 end-0 m-3" id="toast">
  <div class="toast-body text-white" id="toastMsg"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>

// 🔔 TOAST
function mostrarToast(msg, tipo="success") {
    let body = document.getElementById("toastMsg");
    body.innerText = msg;
    body.className = "toast-body text-white bg-" + tipo;
    new bootstrap.Toast(document.getElementById("toast")).show();
}

// 🔍 AUTOCOMPLETE CATEGORIA
categoria_input.onkeyup = function() {

    let q = this.value;

    if(q.length < 2){
        lista_categorias.innerHTML = "";
        return;
    }

    fetch(`buscar_categoria.php?q=${q}`)
    .then(r=>r.json())
    .then(data=>{

        lista_categorias.innerHTML = "";

        let encontrou = false;

        data.forEach(c=>{

            // 🔥 se for igual ao texto digitado → auto selecionar
            if(c.nome.toLowerCase() === q.toLowerCase()){
                selecionarCategoria(c.id, c.nome);
                encontrou = true;
            }

            lista_categorias.innerHTML += `
            <a href="#" class="list-group-item list-group-item-action"
            onclick="selecionarCategoria(${c.id}, '${c.nome}')">
            ${c.nome}
            </a>`;
        });

        // se não encontrou → opção criar
        if(!encontrou){
            lista_categorias.innerHTML += `
            <a href="#" class="list-group-item text-success"
            onclick="criarCategoria('${q}')">
            ➕ Criar "${q}"
            </a>`;
        }
    });
}

// selecionar categoria
function selecionarCategoria(id,nome){
    categoria_input.value = nome;
    categoria_id.value = id;
    lista_categorias.innerHTML = "";

    tipo_input.disabled = false;
}

// criar categoria
function criarCategoria(nome){
    fetch("categoria_store.php",{
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:"nome="+encodeURIComponent(nome)
    })
    .then(r=>r.json())
    .then(d=>{
        selecionarCategoria(d.id,d.nome);
        mostrarToast("Categoria criada");
    });
}

// 🔍 AUTOCOMPLETE TIPO
tipo_input.onkeyup = function(){

    let q = this.value;
    let cat = categoria_id.value;
    if(!cat) return;

    fetch(`buscar_tipo.php?q=${q}&categoria_id=${cat}`)
    .then(r=>r.json())
    .then(data=>{
        lista_tipos.innerHTML="";

        data.forEach(t=>{
            lista_tipos.innerHTML+=`
            <a href="#" class="list-group-item"
            onclick="selecionarTipo(${t.id}, '${t.nome}')">${t.nome}</a>`;
        });

        lista_tipos.innerHTML+=`
        <a href="#" class="list-group-item text-success"
        onclick="criarTipo('${q}')">➕ Criar "${q}"</a>`;
    });
}

// selecionar tipo
function selecionarTipo(id,nome){
    tipo_input.value = nome;
    tipo_id.value = id;
    lista_tipos.innerHTML="";
}

// criar tipo
function criarTipo(nome){
    fetch("tipo_store.php",{
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:`nome=${encodeURIComponent(nome)}&categoria_id=${categoria_id.value}`
    })
    .then(r=>r.json())
    .then(d=>{
        selecionarTipo(d.id,d.nome);
        mostrarToast("Tipo criado");
    });
}

</script>
<script>
// TOAST AUTOMÁTICO
<?php if(isset($_GET['msg'])): ?>
window.onload = () => {

    let tipo = "<?= $_GET['msg'] ?>";

    if(tipo === "sucesso"){
        showToast("✅ Produto cadastrado com sucesso!");
        document.querySelector("form").reset();
    }

    if(tipo === "erro"){
        showToast("❌ Erro ao cadastrar produto", "error");
    }
}
<?php endif; ?>

// LOADER NO BOTÃO
document.querySelector("form").addEventListener("submit", function() {

    let btn = document.getElementById("btnSalvar");

    btn.disabled = true;
    btn.innerHTML = `
        <span class="spinner-border spinner-border-sm"></span>
        Salvando...
    `;
});
</script>