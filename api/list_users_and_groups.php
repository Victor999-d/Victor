<?php
require '../config/db_connect.php';

$response = ['result' => false, 'message' => '', 'users' => [], 'groups' => []];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page = $_POST['page'] ?? 1;
    $limit = 20;
    $offset = ($page - 1) * $limit;

    try {
        $stmt = $pdo->prepare("SELECT id, nome as name FROM users LIMIT ? OFFSET ?");
        $stmt->execute([$limit, $offset]);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("SELECT id, name FROM groups LIMIT ? OFFSET ?");
        $stmt->execute([$limit, $offset]);
        $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response['result'] = true;
        $response['users'] = $users;
        $response['groups'] = $groups;
    } catch (PDOException $e) {
        $response['message'] = 'Erro ao listar usuários e grupos: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Método não permitido.';
}

echo json_encode($response);