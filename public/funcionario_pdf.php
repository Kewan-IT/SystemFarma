<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . "/../config/conexao.php");
require_once(__DIR__ . "/../vendor/autoload.php");

use Dompdf\Dompdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

// ========================
// 🔍 RECEBER ID
// ========================
$id = $_GET['id'] ?? 0;

// ========================
// 🔍 BUSCAR FUNCIONÁRIO
// ========================
$stmt = $conn->prepare("SELECT * FROM funcionarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$res = $stmt->get_result();
$func = $res->fetch_assoc();

if (!$func) {
    die("Funcionário não encontrado");
}

// ========================
// 🖼️ FOTO
// ========================
$foto = $func['foto'] 
    ? __DIR__ . "/../uploads/" . $func['foto']
    : __DIR__ . "/../uploads/default.png";

// ========================
// 🔗 LINK DO FUNCIONÁRIO
// ========================
$url = "https://SEU-LINK/public/funcionario_view.php?id=" . $id;

// ========================
// 📱 GERAR QR CODE
// ========================
$qrPath = __DIR__ . "/qr_temp.png";

$result = Builder::create()
    ->writer(new PngWriter())
    ->data($url)
    ->size(150)
    ->margin(10)
    ->build();

$result->saveToFile($qrPath);

// ========================
// 📄 HTML DO PDF
// ========================
$html = "
<style>
body {
    font-family: Arial, sans-serif;
    font-size: 12px;
}

.header {
    text-align: center;
    margin-bottom: 10px;
}

.titulo {
    font-weight: bold;
    border: 1px solid #000;
    display: inline-block;
    padding: 5px 20px;
}

.foto-box {
    width: 120px;
    height: 140px;
    border: 1px solid #000;
}

.foto-box img {
    width: 100%;
    height: 100%;
}

.linha {
    margin-bottom: 8px;
}

.label {
    display: inline-block;
    width: 180px;
    font-weight: bold;
}

.campo {
    display: inline-block;
    border-bottom: 1px solid #000;
    width: 300px;
}

.section {
    margin-top: 15px;
    font-weight: bold;
}

.assinatura {
    display: inline-block;
    width: 40%;
    text-align: center;
}

.linha-ass {
    border-top: 1px solid #000;
    margin-top: 40px;
}
</style>

<div class='header'>
    <img src='".__DIR__."/assets/logo.png' width='80'><br>
    <div class='titulo'>FICHA DE FUNCIONÁRIO Nº ".str_pad($func['id'], 5, '0', STR_PAD_LEFT)."</div>
</div>

<table width='100%'>
<tr>

<td width='70%'>

<div class='section'>DADOS PESSOAIS</div>

<div class='linha'>
<span class='label'>Nome:</span>
<span class='campo'>{$func['nome']} {$func['apelido']}</span>
</div>

<div class='linha'>
<span class='label'>Data Nascimento:</span>
<span class='campo'>".date("d/m/Y", strtotime($func['data_nascimento']))."</span>
</div>

<div class='linha'>
<span class='label'>Contacto:</span>
<span class='campo'>{$func['contacto']}</span>
</div>

<div class='linha'>
<span class='label'>Email:</span>
<span class='campo'>{$func['email']}</span>
</div>

<div class='section'>FORMAÇÃO</div>

<div class='linha'>
<span class='label'>Nível Académico:</span>
<span class='campo'>{$func['nivel_academico']}</span>
</div>

<div class='linha'>
<span class='label'>Área:</span>
<span class='campo'>{$func['area_formacao']}</span>
</div>

<div class='section'>DOCUMENTO</div>

<div class='linha'>
<span class='label'>Documento:</span>
<span class='campo'>".($func['documento_pdf'] ? "Disponível no sistema" : "Não anexado")."</span>
</div>

</td>

<td width='30%' align='center'>

<div class='foto-box'>
<img src='{$foto}'>
</div>

<br>

<img src='{$qrPath}' width='100'><br>
<small>Scan para ver perfil</small>

</td>

</tr>
</table>

<br><br>

<div class='assinatura'>
<div class='linha-ass'></div>
Funcionário
</div>

<div class='assinatura'>
<div class='linha-ass'></div>
Administrador
</div>

<div style='text-align:right; font-size:10px; margin-top:20px;'>
Emitido em: ".date("d/m/Y H:i")."
</div>
";

// ========================
// 📄 GERAR PDF
// ========================
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper("A4", "portrait");
$dompdf->render();

$dompdf->stream("funcionario_{$id}.pdf", ["Attachment" => false]);

// limpar QR
unlink($qrPath);