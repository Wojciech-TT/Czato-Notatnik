<?php
function handleBoard($pdo, $method, $id)
{
    global $user;

    if ($method === 'GET') {
        $stmt = $pdo->query("SELECT content, updated_at FROM board ORDER BY id DESC LIMIT 1");
        $board = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($board ?: ['content' => '']);
        return;
    }

    if ($method === 'POST') {
        if ($user['role'] !== 'teacher') {
            http_response_code(403);
            echo json_encode(['error' => 'Only teacher can update board']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        if (!isset($input['content'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Content required']);
            return;
        }

        // Aktualizujemy lub tworzymy wpis
        $stmt = $pdo->query("SELECT id FROM board ORDER BY id DESC LIMIT 1");
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            $stmt = $pdo->prepare("UPDATE board SET content = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$input['content'], $existing['id']]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO board (content) VALUES (?)");
            $stmt->execute([$input['content']]);
        }

        echo json_encode(['success' => true]);
        return;
    }

    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
