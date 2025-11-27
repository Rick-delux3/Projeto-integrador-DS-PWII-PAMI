<?php include '../front_end/header.html'; ?>
<?php include 'Api/conexao.php' ?>



<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}
?>




<?php include '../front_end/footer.html'; ?>