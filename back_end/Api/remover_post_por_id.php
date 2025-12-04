<?php
	include 'cors.php';
	include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    // Obtém o corpo da solicitação POST
    $data = file_get_contents("php://input");

    // Decodifica o JSON para um objeto PHP
    $requestData = json_decode($data);

    // Agora você pode acessar os dados usando $requestData
    $codigo = $requestData->id;

	// CodFun é o nome da coluna que está sendo enviado pelo cliente
	$sql = "DELETE FROM posts WHERE id='$codigo'";

    if ($connection->query($sql) === true) {
        $response = [
            'mensagem' => 'Postagem apagada com sucesso!'
        ];
    } else {
        $response = [
            'mensagem' => $connection->error
        ];
    }
    echo json_encode($response);
}   
?>