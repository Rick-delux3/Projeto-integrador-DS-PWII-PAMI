<?php
    include 'Api/cors.php';
    include 'Api/conexao.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = isset($_POST['username']) ? $_POST['username'] : exit();
        $email = isset($_POST['email']) ? $_POST['email'] : exit();
        $senha = isset($_POST['senha']) ? $_POST['senha'] : exit();
        $confirmar_senha = isset($_POST['confirmar_senha']) ? $_POST['confirmar_senha'] : exit();

        if (empty($username) || empty($email) || empty($senha) || empty($confirmar_senha)) {
            exit('Preencha todos os campos.');
        }
        if ($senha !== $confirmar_senha) {
            exit('As senhas não coincidem.');
        }

        $stmt = $connection->prepare('INSERT INTO user (username, email, senha) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $username, $email, $senha);
        $stmt->execute();
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../front_end/assets/css/cadastro.css">
    <title>Cadastro</title>
</head>

<body>

<header class="navbar">
    <div class="logo"></div>
    <nav>
        <a href="#">Home</a>
        <a href="#">Posts</a>
        <a href="#">Sobre nós</a>
    </nav>
</header>

<main class="cadastro-container">

    <section class="card-cadastro">
        <h2>Cadastro</h2>

        <form action="cadastro.php" method="POST">

            <div class="input-group">
                <input type="text" name="username" placeholder="Usuário">
            </div>

            <div class="input-group">
                <input type="email" name="email" placeholder="Email">
            </div>

            <div class="input-group senha-group">
                <input type="password" name="senha" id="senha" placeholder="Senha">
                <img src="../front_end/assets/imagens/olhos.png" 
                     class="toggle-senha-img" 
                     data-open="../front_end/assets/imagens/olhos.png"
                     data-close="../front_end/assets/imagens/olhos-fechados.png"
                     onclick="toggleSenha('senha', this)">
            </div>

            <div class="input-group senha-group">
                <input type="password" name="confirmar_senha" id="confirmar_senha" placeholder="Confirmar Senha">
                <img src="../front_end/assets/imagens/olhos.png" 
                     class="toggle-senha-img" 
                     data-open="../front_end/assets/imagens/olhos.png"
                     data-close="../front_end/assets/imagens/olhos-fechados.png"
                     onclick="toggleSenha('confirmar_senha', this)">
            </div>

            <button type="submit" class="btn-enviar">Enviar</button>

        </form>
    </section>

    <section class="imagem-lateral"></section>

</main>

<footer class="rodape">
    © Todos os direitos reservados - AutoWare
</footer>

<script>
function toggleSenha(id, icon) {
    let campo = document.getElementById(id);

    if (campo.type === "password") {
        campo.type = "text";
        icon.src = icon.dataset.close;
    } else {
        campo.type = "password";
        icon.src = icon.dataset.open;
    }
}
</script>

</body>
</html>
