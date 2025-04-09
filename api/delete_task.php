<?php
require '../config/db_connect.php';

$response = ['result' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task'] ?? null;

    if (!$task_id) {
        $response['message'] = 'ID da tarefa é obrigatório.';
        echo json_encode($response);
        exit;
    }

    $stmt = $pdo->prepare("SELECT id FROM tasks WHERE id = ?");
    $stmt->execute([$task_id]);
    if ($stmt->rowCount() == 0) {
        $response['message'] = 'Tarefa não encontrada.';
        echo json_encode($response);
        exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->execute([$task_id]);
        $affected = $stmt->rowCount();
        if ($affected > 0) {
            $response['result'] = true;
            $response['message'] = 'Tarefa excluída com sucesso!';
        } else {
            $response['message'] = 'Nenhuma tarefa foi excluída.';
        }
    } catch (PDOException $e) {
        $response['message'] = 'Erro ao excluir tarefa: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Método não permitido.';
}

echo json_encode($response);