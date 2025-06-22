<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $quantidade = $_POST['quantidade'];
    $imagem_nome = '';

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $pasta_upload = 'uploads/';
        $imagem_nome = uniqid() . '_' . basename($_FILES['imagem']['name']);
        $caminho_imagem = $pasta_upload . $imagem_nome;

        // Move o arquivo para a pasta de uploads
        move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_imagem);
    }

    $stmt = $pdo->prepare("INSERT INTO produtos (nome, descricao, quantidade, imagem) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nome, $descricao, $quantidade, $imagem_nome]);

    header("Location: listar.php");
    exit();
}
?>