<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

require 'db.php';

// Lógica de filtro
$filtro_nome = isset($_GET['filtro_nome']) ? $_GET['filtro_nome'] : '';
$sql = "SELECT * FROM produtos";
if (!empty($filtro_nome)) {
    $sql .= " WHERE nome LIKE ?";
}
$stmt = $pdo->prepare($sql);
if (!empty($filtro_nome)) {
    $stmt->execute(['%' . $filtro_nome . '%']);
} else {
    $stmt->execute();
}
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Lista de Produtos</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="container">
        <h2>Produtos Cadastrados</h2>

        <form method="get" class="filtro-form">
            <input type="text" name="filtro_nome" placeholder="Filtrar por nome..." value="<?= htmlspecialchars($filtro_nome) ?>">
            <button type="submit">Filtrar</button>
        </form>

        <table class="tabela-listagem">
            <thead>
                <tr>
                    <th>Imagem</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Qtd.</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($produtos) > 0): ?>
                    <?php foreach ($produtos as $produto): ?>
                        <tr>
                            <td><img src="uploads/<?= htmlspecialchars($produto['imagem']) ?>" alt="Imagem do produto" width="80"></td>
                            <td><?= htmlspecialchars($produto['nome']) ?></td>
                            <td><?= htmlspecialchars($produto['descricao']) ?></td>
                            <td><?= htmlspecialchars($produto['quantidade']) ?></td>
                            <td>
                                <a href="editar.php?id=<?= $produto['id'] ?>" class="btn-editar">Editar</a>
                                <a href="excluir.php?id=<?= $produto['id'] ?>" class="btn-excluir" onclick="return confirm('Tem certeza que deseja excluir este produto?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Nenhum produto cadastrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>