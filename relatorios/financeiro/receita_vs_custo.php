<?php include("../../layouts/header.php"); ?>
<?php include("../../layouts/sidebar.php"); ?>
<?php require_once("../../config/conexao.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container mt-4">
<h4>⚖️ Receita vs Custo</h4>

<?php
$sql = "
SELECT 
SUM(vi.preco * vi.quantidade) receita,
SUM(p.preco_custo * vi.quantidade) custo
FROM venda_itens vi
JOIN produtos p ON p.id = vi.produto_id
";

$r = $conn->query($sql)->fetch_assoc();
?>

<canvas id="grafico"></canvas>

<script>
new Chart(document.getElementById("grafico"), {
type:'pie',
data:{
labels:['Receita','Custo'],
datasets:[{data:[<?= $r['receita'] ?>, <?= $r['custo'] ?>]}]
}
});
</script>

<?php include("../../layouts/footer.php"); ?>