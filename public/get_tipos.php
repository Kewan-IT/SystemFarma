<?php
require_once("../config/conexao.php");

$categoria_id = $_GET['categoria_id'];

$result = $conn->query("SELECT * FROM tipos_produto WHERE categoria_id = $categoria_id");

echo '<option value="">Selecione</option>';

while($row = $result->fetch_assoc()){
    echo "<option value='{$row['id']}'>{$row['nome']}</option>";
}