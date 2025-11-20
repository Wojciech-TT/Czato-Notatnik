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

    if ($name === '') {
        http_response_code(400);
        echo json_encode(['error' => 'Name required']);
        return;
    }

    // Szukamy użytkownika po nazwisku
    $stmt = $pdo->prepare("SELECT * FROM users WHERE name = ?");
    $stmt->execute([$name]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // Domyślnie nowi użytkownicy to uczniowie
        $defaultRole = 'student';
        $stmt = $pdo->prepare("INSERT INTO users (name, role) VALUES (?, ?)");
        $stmt->execute([$name, $defaultRole]);
        $user = [
            'id' => $pdo->lastInsertId(),
            'name' => $name,
            'role' => $defaultRole
        ];
    }

    // Generujemy token JWT
    $payload = [
        'id' => $user['id'],
        'name' => $user['name'],
        'role' => $user['role'],
        'exp' => time() + 3600 * 12 // 12 godzin
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
