<?php
    include 'Api/cors.php';
    include 'Api/conexao.php';
    include '../front_end/assets/header.html';

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

<main>
    <form action="cadastro.php" method="POST">
        <label for="username"></label>
        <input type="text" name="username" id="username" placeholder="Usuário">

        

        <label for="email"></label>
        <input type="email" name="email" id="email" placeholder="Email">

        <label for="senha"></label>
        <input type="password" name="senha" id="senha" placeholder="Senha">
        
        <label for="confirmar_senha"></label>
        <input type="password" name="confirmar_senha" placeholder="Confirmar Senha">

        <button type="submit">Enviar</button>
    </form>
</main>

<?php
    include '../front_end/assets/footer.html';
?>
