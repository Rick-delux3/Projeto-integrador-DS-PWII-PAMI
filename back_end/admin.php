<?php
    include 'Api/cors.php';
    include 'Api/conexao.php';
    include '../front_end/assets/header.php';
?>

<main>
    <button id="abrir-modal">Adicionar Post</button>
    <div class="hidden">
        <form action="admin.php" method="POST" enctype="multipart/form-data">

            <label for="titulo">Título:</label>
            <input type="text" name="titulo" id="titulo" required>

            <label for="conteudo">Conteúdo:</label>
            <input type="text" name="conteudo" id="titulo" required>

            <label for="thumb">Thumb:</label>
            <input type="file" name="thumb" id="thumb">

        </form>
    </div>
</main>

<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}
?>

<?php include '../front_end/assets/footer.php'; ?>
