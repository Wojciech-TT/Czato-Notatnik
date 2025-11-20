<?php
function handleNotes($pdo, $method, $id)
{
    global $user;

    switch ($method) {
        case 'GET':
            $stmt = $pdo->prepare("SELECT id, content, updated_at FROM notes WHERE user_id = ?");
            $stmt->execute([$user['id']]);
            $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($notes);
            break;

        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            if (empty($input['content'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Content required']);
                return;
            }

            $stmt = $pdo->prepare("INSERT INTO notes (user_id, content) VALUES (?, ?)");
            $stmt->execute([$user['id'], $input['content']]);

            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
            break;

        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
    }
}
