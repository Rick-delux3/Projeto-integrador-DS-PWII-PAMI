<?php
	include 'cors.php';
	include 'conexao.php';


if ($_SERVER["REQUEST_METHOD"] == "GET") {

    $sql = "SELECT posts.*, user.username 
        FROM posts 
        INNER JOIN user ON user.id = posts.iduser
        ORDER BY posts.id DESC";

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
            'posts' => []
        ];
    }

    echo json_encode($response);
	} // Fim If
?>