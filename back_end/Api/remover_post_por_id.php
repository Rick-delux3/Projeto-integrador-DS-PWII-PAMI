<?php
	include 'cors.php';
	include 'conexao.php';

    header("Content-Type: application/json; charset=utf-8");

// pega id (via GET)
    if (!isset($_GET['id'])) {
        echo json_encode(['success' => false, 'message' => 'ID não informado']);
        exit;
    }

    $id = intval($_GET['id']);

    // (Opcional) verificar usuário logado via session
    session_start();
    if (!isset($_SESSION['id'])) {
        echo json_encode(['success' => false, 'message' => 'Não autenticado']);
        exit;
    }
    $iduser = intval($_SESSION['id']);

    // buscar thumb atual para excluir arquivo (opcional)
    $stmt = $connection->prepare("SELECT thumb FROM posts WHERE id = ? AND iduser = ?");
    $stmt->bind_param("ii", $id, $iduser);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $thumb = $row['thumb'];
        // excluir arquivo físico se quiser (verifique se não é default)
        if ($thumb && strpos($thumb, 'default.png') === false && file_exists($thumb)) {
            @unlink($thumb);
        }
        // agora remove o registro
        $stmt2 = $connection->prepare("DELETE FROM posts WHERE id = ? AND iduser = ?");
        $stmt2->bind_param("ii", $id, $iduser);
        if ($stmt2->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => $connection->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Post não encontrado ou sem permissão.']);
    }
   
?>