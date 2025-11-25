<?php
    include 'cors.php';
    include 'conexao.php';

    if ($_SERVER["REQUEST_METHOD"] == "PUT") {
        // Obtém o corpo da solicitação PUT
        $data = file_get_contents("php://input");
    
        // Decodifica o JSON para um objeto PHP
        $requestData = json_decode($data);
        
        // Agora você pode acessar os dados usando $requestData
        $codigo = $requestData->CodFun;
    
    
        $sql = "UPDATE * FROM posts WHERE CodFun = '$codigo'";
    
        $result = $connection->query($sql);
    
        if ($result->num_rows > 0) {
            $posts = [];
            while ($row = $result->fetch_assoc()) {
                array_push($posts, $row);
            }
    
            $response = [
                'posts' => $posts
            ];
    
        } else {
            $response = [
                'posts' => 'Nenhuma postagem encontrada!';
            ];
        }
    
        echo json_encode($response);
        } // Fim If
?>