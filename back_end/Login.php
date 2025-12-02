<?php include '../front_end/assets/header.html'; ?>

<?php 
    include 'Api/conexao.php';
    include 'Api/cors.php';  
?>

    <form action="Login.php" method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Entrar</button>
    </form>

<?php
    session_start();
    
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
    
        // PEGA OS VALORES CORRETAMENTE
        $email = $_POST['email'];
        $senha = $_POST['senha'];
    
        $sql = "SELECT id, username, email FROM user WHERE email = ? AND senha = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ss", $email, $senha);
        $stmt->execute();
        $resultado = $stmt->get_result();
    
        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();
    
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['username'] = $usuario['username'];
            $_SESSION['email'] = $usuario['email'];
    
            header("Location: admin.php");
            exit;
    
        } else {
            echo "Email ou senha inválidos!";
        }
    }



?>


<?php include '../front_end/assets/footer.html'; ?>
