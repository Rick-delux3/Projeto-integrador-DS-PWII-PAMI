<?php
    include 'cors.php';
    include 'conexao.php';

    if ($_SERVER["REQUEST_METHOD"] == "PUT") {

    // Lê os dados recebidos em JSON
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data || !isset($data['id'])) {
            http_response_code(400);
            echo json_encode(["erro" => "Dados inválidos"]);
            exit;
        }

        $id       = intval($data['id']);
        $titulo   = $connection->real_escape_string($data['titulo']);
        $conteudo = $connection->real_escape_string($data['conteudo']);

        // Faz o UPDATE
        $sql = "UPDATE posts SET titulo='$titulo', conteudo='$conteudo' WHERE id=$id";

        if ($connection->query($sql) === TRUE) {
            echo json_encode(["sucesso" => true, "mensagem" => "Post atualizado"]);
        } else {
            echo json_encode(["sucesso" => false, "erro" => $connection->error]);
        }

    }
?>