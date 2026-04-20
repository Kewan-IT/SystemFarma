<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once("../config/conexao.php");
require_once(__DIR__ . "/../vendor/autoload.php");

use Dompdf\Dompdf;

$venda_id = $_GET['venda_id'] ?? 0;

// ==========================
// 🏥 CONFIG FARMÁCIA
// ==========================
$nome_farmacia = "Kewan_Farma";
$endereco = "Nampula - Moçambique";
$telefone = "+258 86 890 0224";
$nuit = "123456789";

// ==========================
// 📦 BUSCAR VENDA
// ==========================
$stmt = $conn->prepare("
SELECT v.*, f.nome AS funcionario
FROM vendas v
JOIN usuarios u ON v.usuario_id = u.id
JOIN funcionarios f ON u.funcionario_id = f.id
WHERE v.id = ?
");
$stmt->bind_param("i", $venda_id);
$stmt->execute();
$venda = $stmt->get_result()->fetch_assoc();

if (!$venda) {
    die("Venda não encontrada");
}

// ==========================
// 📦 BUSCAR ITENS
// ==========================
$stmt = $conn->prepare("
SELECT vi.*, p.nome 
FROM venda_itens vi
JOIN produtos p ON vi.produto_id = p.id
WHERE vi.venda_id = ?
");
$stmt->bind_param("i", $venda_id);
$stmt->execute();
$itens = $stmt->get_result();

// ==========================
// 🖼️ LOGO
// ==========================
$logo = __DIR__ . "/../public/assets/logo.png";

// ==========================
// 📄 HTML
// ==========================
ob_start();
?>

<style>
body { font-family: monospace; font-size:11px; width:260px; }
.center { text-align:center; }
hr { border-top:1px dashed #000; margin:5px 0; }
.total { font-weight:bold; font-size:13px; }
</style>

<div class="center">
    <img src="<?= $logo ?>" width="60"><br>
    <strong><?= $nome_farmacia ?></strong><br>
    <small><?= $endereco ?></small><br>
    <small>Tel: <?= $telefone ?></small><br>
    <small>NUIT: <?= $nuit ?></small>
</div>

<hr>

Recibo Nº: <?= str_pad($venda['id'], 5, "0", STR_PAD_LEFT) ?><br>
Data: <?= date("d/m/Y H:i") ?><br>
Operador: <?= $venda['funcionario'] ?><br>

<hr>

<?php while($item = $itens->fetch_assoc()): ?>
<?= $item['nome'] ?><br>
<?= $item['quantidade'] ?> x <?= number_format($item['preco'],2) ?>
= <?= number_format($item['quantidade']*$item['preco'],2) ?> MT
<br>
<?php endwhile; ?>

<hr>

<div class="total">
TOTAL: <?= number_format($venda['total'],2) ?> MT
</div>

Pagamento: <?= $venda['metodo_pagamento'] ?>

<hr>

<div class="center">
Obrigado pela preferência!
</div>

<?php
$html = ob_get_clean();

// ==========================
// 📄 GERAR PDF
// ==========================
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper([0,0,226,800]);
$dompdf->render();

$dompdf->stream("recibo.pdf", ["Attachment"=>false]);