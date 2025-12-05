<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    header("Content-Type: application/json");

    include 'cors.php';
    include 'conexao.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["_method"]) && $_POST["_method"] == "PUT") {

        $id       = intval($_POST["id"]);
        $titulo   = $connection->real_escape_string($_POST["titulo"]);
        $conteudo = $connection->real_escape_string($_POST["conteudo"]);

        // Se houver thumb nova, atualiza o arquivo
        if (!empty($_FILES["thumb"]["name"])) {

            $nome_arquivo = time() . "_" . basename($_FILES["thumb"]["name"]);
            $destino_fisico = __DIR__ . "/../../front_end/assets/imagens/" . $nome_arquivo;

        // CAMINHO PÚBLICO (para salvar no banco)
            $destino_publico = "http://localhost/Projeto-integrador-DS-PWII-PAMI/front_end/assets/imagens/" . $nome_arquivo;

             if (!move_uploaded_file($_FILES["thumb"]["tmp_name"], $destino_fisico)) {
                echo json_encode(["status" => "erro", "msg" => "Falha ao mover arquivo"]);
                exit;
            }

            $sql = "UPDATE posts 
                    SET titulo='$titulo', conteudo='$conteudo', thumb='$destino_publico'
                    WHERE id=$id";
        } 
        else {
            // Atualiza sem alterar imagem
            $sql = "UPDATE posts 
                    SET titulo='$titulo', conteudo='$conteudo'
                    WHERE id=$id";
        }

        if ($connection->query($sql)) {
            echo json_encode(["status" => "ok"]);
        } else {
            echo json_encode(["status" => "erro", "msg" => $connection->error]);
        }

    }
?>