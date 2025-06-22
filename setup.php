<?php
try {
   
    $pdo = new PDO('sqlite:database.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Conexão com o banco de dados SQLite estabelecida com sucesso.<br>";

    
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS usuarios (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            usuario TEXT NOT NULL UNIQUE,
            senha TEXT NOT NULL
        )
    ");
    echo "Tabela 'usuarios' criada ou já existente.<br>";

    
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS produtos (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nome TEXT NOT NULL,
            descricao TEXT,
            quantidade INTEGER,
            imagem TEXT
        )
    ");
    echo "Tabela 'produtos' criada ou já existente.<br>";

    
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->execute(['admin']);
    if ($stmt->fetch() === false) {
        $senhaHash = password_hash('admin123', PASSWORD_DEFAULT);
        $pdo->exec("INSERT INTO usuarios (usuario, senha) VALUES ('admin', '$senhaHash')");
        echo "Usuário 'admin' com senha 'admin123' criado com sucesso.<br>";
    } else {
        echo "Usuário 'admin' já existe.<br>";
    }

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>