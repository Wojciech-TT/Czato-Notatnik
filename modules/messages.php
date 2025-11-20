<?php
function handleMessages($pdo, $method, $id)
{
    global $user; // z JWT

    switch ($method) {
        case 'GET':
            $lastId = isset($_GET['last_id']) ? intval($_GET['last_id']) : 0;
            $stmt = $pdo->prepare("
                SELECT m.id, m.text, m.created_at, u.name, u.role
                FROM messages m
                JOIN users u ON m.user_id = u.id
                WHERE m.id > ?
                ORDER BY m.id ASC
            ");
            $stmt->execute([$lastId]);
            $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($messages);
            break;

        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            if (empty($input['text'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Message text required']);
                return;
            }

            $stmt = $pdo->prepare("INSERT INTO messages (user_id, text) VALUES (?, ?)");
            $stmt->execute([$user['id'], $input['text']]);

            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
            break;

        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
    }
}
