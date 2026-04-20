<?php
session_start();
require_once("../config/conexao.php");

$data = json_decode(file_get_contents("php://input"), true);

$usuario_id = $_SESSION['usuario_id'];
$carrinho = $data['carrinho'];
$pagamento = $data['pagamento'];

$conn->begin_transaction();

try {

    $total = 0;
    $itens_validados = [];

    foreach ($carrinho as $item) {

        $id = (int)$item['id'];
        $qtd = (int)$item['qtd'];

        $stmt = $conn->prepare("
        SELECT nome, preco_venda, quantidade, status 
        FROM produtos WHERE id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();

        $produto = $res->fetch_assoc();

        if (!$produto) throw new Exception("Produto não existe");

        if ($produto['status'] != 'ativo')
            throw new Exception("Produto inativo: ".$produto['nome']);

        if ($produto['quantidade'] < $qtd)
            throw new Exception("Stock insuficiente: ".$produto['nome']);

        $preco = $produto['preco_venda'];

        $total += $preco * $qtd;

        $itens_validados[] = [
            'id'=>$id,
            'qtd'=>$qtd,
            'preco'=>$preco
        ];
    }

    $stmt = $conn->prepare("
    INSERT INTO vendas (total, metodo_pagamento, usuario_id)
    VALUES (?, ?, ?)
    ");
    $stmt->bind_param("dsi", $total, $pagamento, $usuario_id);
    $stmt->execute();

    $venda_id = $conn->insert_id;

    foreach ($itens_validados as $item) {

        $stmt = $conn->prepare("
        INSERT INTO venda_itens (venda_id, produto_id, quantidade, preco)
        VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("iiid", $venda_id, $item['id'], $item['qtd'], $item['preco']);
        $stmt->execute();

        $stmt = $conn->prepare("
        UPDATE produtos SET quantidade = quantidade - ? WHERE id = ?
        ");
        $stmt->bind_param("ii", $item['qtd'], $item['id']);
        $stmt->execute();
    }

    $conn->commit();

    echo "✅ Venda concluída com sucesso!";

} catch (Exception $e) {

    $conn->rollback();
    echo "❌ " . $e->getMessage();
}