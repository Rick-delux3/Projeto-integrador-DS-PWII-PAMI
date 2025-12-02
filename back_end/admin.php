<?php
    include 'Api/cors.php';
    include 'Api/conexao.php';
    include '../front_end/assets/header.php';
?>

<?php
    session_start();
    
    if (!isset($_SESSION['id'])) {
        header("Location: login.php");
        exit;
    }
?>

<head>
    <link rel="stylesheet" href="../front_end/assets/css/admin.css">
</head>
<main>
    <button id="abrir-modal">Adicionar post</button>
    <div>
        <div id="modal"  class="hidden">
            <form id="fechar-modal" action="admin.php" method="POST" enctype="multipart/form-data">
    
                <label for="titulo">Título:</label>
                <input type="text" name="titulo" id="titulo" required>
    
                <label for="conteudo">Conteúdo:</label>
                <input type="text" name="conteudo" id="conteudo" required>
    
                <label for="thumb">Thumb:</label>
                <input type="file" name="thumb" id="thumb">
    
                <button type="submit">Enviar post</button>
            </form>
        </div>
    </div>
</main>

<script>
    const abrir_modal = document.querySelector("#abrir-modal");
    const fechar_modal = document.querySelector("#fechar-modal");
    const modal = document.querySelector("#modal");

    abrir_modal.addEventListener("click", () => {
        modal.classList.remove("hidden");
    })

    fechar_modal.addEventListener("submit", () => {
        modal.classList.add("hidden");
    })
</script>

<?php
    
?>

<?php include '../front_end/assets/footer.php'; ?>
