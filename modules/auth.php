<?php
function handleAuth($pdo, $method, $id)
{
    global $JWT_SECRET;

    if ($method !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        return;
    }

    $input = json_decode(file_get_contents('php://input'), true);
    $name = trim($input['name'] ?? '');
    $role = trim($input['role'] ?? '');

    if ($name === '' || $role === '') {
        http_response_code(400);
        echo json_encode(['error' => 'Name and role required']);
        return;
    }

    // Sprawdzamy, czy użytkownik istnieje
    $stmt = $pdo->prepare("SELECT * FROM users WHERE name = ? AND role = ?");
    $stmt->execute([$name, $role]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // Tworzymy nowego użytkownika
        $stmt = $pdo->prepare("INSERT INTO users (name, role) VALUES (?, ?)");
        $stmt->execute([$name, $role]);
        $user = [
            'id' => $pdo->lastInsertId(),
            'name' => $name,
            'role' => $role
        ];
    }

    // Generujemy token JWT
    $payload = [
        'id' => $user['id'],
        'name' => $user['name'],
        'role' => $user['role'],
        'exp' => time() + 3600 * 12 // 12 godzin ważności
    ];
    $token = generateJWT($payload, $JWT_SECRET);

    echo json_encode([
        'token' => $token,
        'user' => [
            'id' => $user['id'],
            'name' => $user['name'],
            'role' => $user['role']
        ]
    ]);
}
