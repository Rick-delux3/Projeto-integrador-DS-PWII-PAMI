<?php 
session_start();
include 'Api/conexao.php';
include 'Api/cors.php';  
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

   
    <link rel="icon" type="image/png" href="../front_end/assets/imagens/iconEmpresa.png">

    <link rel="stylesheet" href="../front_end/assets/css/login.css">
</head>

<body>

<div class="login-container">

    <!-- CARD ESQUERDO -->
    <section class="card-login">
        <h2>Login</h2>

        <form action="login.php" method="POST" autocomplete="off">

            <div class="input-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>

            <div class="input-group senha-group">
                <input type="password" name="senha" id="senha" placeholder="Senha" required>
                <img src="../front_end/assets/imagens/olhos.png" 
                     class="toggle-senha-img"
                     onclick="toggleSenha('senha', this)"
                     data-open="../front_end/assets/imagens/olhos.png"
                     data-close="../front_end/assets/imagens/olhos-fechados.png"
                     alt="mostrar senha">
            </div>

            <div class="input-group senha-group">
                <input type="password" name="confirmar_senha" id="confirmar_senha" placeholder="Confirmar Senha">
                <img src="../front_end/assets/imagens/olhos.png" 
                     class="toggle-senha-img"
                     onclick="toggleSenha('confirmar_senha', this)"
                     data-open="../front_end/assets/imagens/olhos.png"
                     data-close="../front_end/assets/imagens/olhos-fechados.png"
                     alt="mostrar confirmar senha">
            </div>

            <button type="submit" class="btn-enviar">Entrar</button>

        </form>

        <!-- MENSAGENS (ERRO/INFO) -->
        <?php
        // Somente processa após envio
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $senha = isset($_POST['senha']) ? $_POST['senha'] : '';
            $confirmar_senha = isset($_POST['confirmar_senha']) ? $_POST['confirmar_senha'] : '';

            // Se usuário preencher confirmar_senha, validamos que sejam iguais
            if (!empty($confirmar_senha) && $senha !== $confirmar_senha) {
                echo "<p class='erro'>As senhas não coincidem.</p>";
            } else {
                // Consulta no banco (login normal)
                $sql = "SELECT id, username, email FROM user WHERE email = ? AND senha = ?";
                $stmt = $connection->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param("ss", $email, $senha);
                    $stmt->execute();
                    $resultado = $stmt->get_result();

                    if ($resultado && $resultado->num_rows === 1) {
                        $usuario = $resultado->fetch_assoc();

                        $_SESSION['id'] = $usuario['id'];
                        $_SESSION['username'] = $usuario['username'];
                        $_SESSION['email'] = $usuario['email'];

                        // Redireciona para área admin
                        header("Location: admin.php");
                        exit;
                    } else {
                        echo "<p class='erro'>Email ou senha inválidos!</p>";
                    }
                    $stmt->close();
                } else {
                    echo "<p class='erro'>Erro na consulta ao banco.</p>";
                }
            }
        }
        ?>

    </section>

    <!-- IMAGEM LATERAL -->
    <section class="imagem-lateral"></section>

</div>

<script>
function toggleSenha(id, icon) {
    const campo = document.getElementById(id);
    if (!campo) return;

    if (campo.type === "password") {
        campo.type = "text";
        if (icon && icon.dataset && icon.dataset.close) icon.src = icon.dataset.close;
    } else {
        campo.type = "password";
        if (icon && icon.dataset && icon.dataset.open) icon.src = icon.dataset.open;
    }
}
</script>

</body>
</html>
