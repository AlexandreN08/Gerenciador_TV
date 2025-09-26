<?php
include 'config.php';

// Identificar a TV pelo parâmetro da URL (ex: index.php?tv=1)
$tv_id = isset($_GET['tv']) ? intval($_GET['tv']) : 1;

// Buscar conteúdos apenas da TV correspondente
$stmt = $pdo->prepare("SELECT * FROM conteudos WHERE tv_id = ?");
$stmt->execute([$tv_id]);
$conteudos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>TV <?= $tv_id ?> - Exibição de Conteúdos</title>
    <style>
        body { text-align: center; font-family: Arial, sans-serif; background: black; color: white; }
        img, video { max-width: 80%; height: auto; display: block; margin: auto; }
    </style>
</head>
<body>

<h1>TV <?= $tv_id ?> - Exibindo Conteúdos</h1>

<?php foreach ($conteudos as $conteudo): ?>
    <?php if ($conteudo['tipo'] == 'imagem'): ?>
        <img src="<?= $conteudo['caminho_arquivo'] ?>" alt="<?= $conteudo['nome'] ?>">
    <?php else: ?>
        <video controls autoplay loop>
            <source src="<?= $conteudo['caminho_arquivo'] ?>" type="video/mp4">
        </video>
    <?php endif; ?>
    <hr>
<?php endforeach; ?>

</body>
</html>


</body>
</html>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciador de Conteúdos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
        }
        h1 { text-align: center; }
        form {
            background: white;
            padding: 20px;
            margin: auto;
            width: 400px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
        button { background-color: #28a745; color: white; }
        button:hover { background-color: #218838; }
        .btn-remover { background-color: #dc3545; }
        .btn-remover:hover { background-color: #c82333; }
        #apresentacao {
            margin-top: 20px;
            padding: 20px;
            background: white;
            border-radius: 8px;
            text-align: center;
            max-width: 600px;
            margin: auto;
        }
        img, video { max-width: 100%; border-radius: 5px; margin-top: 10px; }
    </style>
</head>
<body>

<h1>Adicionar Playlist</h1>
<form method="POST">
    <input type="text" name="nome" placeholder="Nome da Playlist" required>
    <select name="usuario_id" required>
        <?php
        $stmt = $pdo->query("SELECT * FROM usuarios");
        while ($row = $stmt->fetch()) {
            echo "<option value='{$row['id']}'>{$row['nome']}</option>";
        }
        ?>
    </select>
    <button type="submit" name="adicionar_playlist">Adicionar Playlist</button>
</form>

<h1>Adicionar Conteúdo</h1>
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="nome" placeholder="Nome do Conteúdo" required>
    <select name="tipo" required>
        <option value="imagem">Imagem</option>
        <option value="video">Vídeo</option>
    </select>
    <input type="file" name="arquivo" required>
    <select name="playlist_id" required>
        <?php
        $stmt = $pdo->query("SELECT * FROM playlists");
        while ($row = $stmt->fetch()) {
            echo "<option value='{$row['id']}'>{$row['nome']}</option>";
        }
        ?>
    </select>
    <button type="submit" name="adicionar_conteudo">Adicionar Conteúdo</button>
</form>

<div id="apresentacao">
    <h2>Conteúdos Adicionados</h2>
    <?php
    $stmt = $pdo->query("SELECT * FROM conteudos ORDER BY id DESC");
    while ($conteudo = $stmt->fetch()):
    ?>
        <div>
            <?php if ($conteudo['tipo'] == 'imagem'): ?>
                <img src="<?= $conteudo['caminho_arquivo'] ?>" alt="<?= $conteudo['nome'] ?>">
            <?php else: ?>
                <video controls><source src="<?= $conteudo['caminho_arquivo'] ?>" type="video/mp4"></video>
            <?php endif; ?>
            <form method="POST">
                <input type="hidden" name="conteudo_id" value="<?= $conteudo['id'] ?>">
                <button type="submit" name="remover_conteudo" class="btn-remover">Remover</button>
            </form>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>
