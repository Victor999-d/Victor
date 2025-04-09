<?php
require '../config/db_connect.php';

$response = ['result' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $creator_id = $_POST['creator'] ?? null;
    $title = $_POST['title'] ?? null;
    $description = $_POST['description'] ?? null;
    $deadline = $_POST['deadline'] ?? null;
    $assigned_to = $_POST['assigned_to'] ?: null;
    $assigned_group = $_POST['assigned_group'] ?: null;
    $status = $_POST['status'] ?? 'nova';

    if (!$creator_id || !$title || !$description || !$deadline) {
        $response['message'] = 'Todos os campos obrigatórios devem ser preenchidos.';
        echo json_encode($response);
        exit;
    }

    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->execute([$creator_id]);
    if ($stmt->rowCount() == 0) {
        $response['message'] = 'Criador da tarefa não encontrado.';
        echo json_encode($response);
        exit;
    }

    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("INSERT INTO tasks (creator_id, title, description, deadline, assigned_to, assigned_group_id, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$creator_id, $title, $description, $deadline, $assigned_to, $assigned_group, $status]);
        $pdo->commit();
        $response['result'] = true;
        $response['message'] = 'Tarefa criada com sucesso!';
    } catch (PDOException $e) {
        $pdo->rollBack();
        $response['message'] = 'Erro ao criar tarefa: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Método não permitido.';
}

echo json_encode($response);