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
    <main class="cadastro-container">

        <!-- Card do Formulário -->
        <section class="card-cadastro">
            <h2>Cadastro</h2>

            <form action="cadastro.php" method="POST">

                <div class="input-group">
                    <input type="text" name="username" id="username" placeholder="Usuário">
                </div>

                <div class="input-group">
                    <input type="email" name="email" id="email" placeholder="Email">
                </div>

                <div class="input-group senha-group">
                    <input type="password" name="senha" id="senha" placeholder="Senha">
                    <span class="toggle-senha" onclick="toggleSenha('senha', this)">👁️</span>
                </div>

                <div class="input-group senha-group">
                    <input type="password" name="confirmar_senha" id="confirmar_senha" placeholder="Confirmar Senha">
                    <span class="toggle-senha" onclick="toggleSenha('confirmar_senha', this)">👁️</span>
                </div>

                <button type="submit" class="btn-enviar">Enviar</button>

            </form>
        </section>

        <!-- Área da Imagem -->
        <section class="imagem-lateral">
            <!-- 🖼️ coloque aqui a imagem que você quiser -->
        </section>

    </main>

<script>
function toggleSenha(id, icon) {
    let campo = document.getElementById(id);
    if (campo.type === "password") {
        campo.type = "text";
        icon.style.opacity = "0.5";
    } else {
        campo.type = "password";
        icon.style.opacity = "1";
    }
}
</script>

</body>
</html>
