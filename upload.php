<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tv_id = intval($_POST["tv_id"]);
    $nome = $_POST["nome"];
    $tipo = $_POST["tipo"];

    // Diretório de upload
    $diretorio = "uploads/";

    // Verifica e move o arquivo
    if (!empty($_FILES["arquivo"]["name"])) {
        $arquivo_nome = basename($_FILES["arquivo"]["name"]);
        $caminho_arquivo = $diretorio . $arquivo_nome;

        if (move_uploaded_file($_FILES["arquivo"]["tmp_name"], $caminho_arquivo)) {
            // Salvar no banco de dados
            $stmt = $pdo->prepare("INSERT INTO conteudos (nome, caminho_arquivo, tipo, tv_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nome, $caminho_arquivo, $tipo, $tv_id]);

            echo "<p>Arquivo enviado com sucesso para a TV $tv_id!</p>";
        } else {
            echo "<p>Erro ao enviar o arquivo.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Upload de Conteúdos</title>
</head>
<body>
    <h2>Enviar Conteúdo</h2>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <label for="nome">Nome do Arquivo:</label>
        <input type="text" name="nome" required><br><br>

        <label for="tv_id">Selecione a TV:</label>
        <select name="tv_id" required>
            <option value="1">TV 1</option>
            <option value="2">TV 2</option>
            <option value="3">TV 3</option>
        </select><br><br>

        <label for="tipo">Tipo:</label>
        <select name="tipo" required>
            <option value="imagem">Imagem</option>
            <option value="video">Vídeo</option>
        </select><br><br>

        <label for="arquivo">Arquivo:</label>
        <input type="file" name="arquivo" required><br><br>

        <button type="submit">Enviar</button>
    </form>
</body>
</html>
