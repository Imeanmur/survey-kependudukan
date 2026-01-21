<?php
require_once __DIR__ . '/../includes/config.php';
header('Content-Type: application/json');
session_start();

// Ensure admin is logged in
if (!isset($_SESSION['admin'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Fetch logs
    $sql = "SELECT * FROM audit_logs ORDER BY login_time DESC";
    $result = $conn->query($sql);

    $logs = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $logs[] = $row;
        }
    }

    echo json_encode(['success' => true, 'data' => $logs]);
} elseif ($method === 'POST') {
    // Delete logs (using POST since some servers block DELETE with body, or just simple POST with action)
    // Check if it's a delete action
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? '';

    if ($action === 'delete_single') {
        $id = $data['id'] ?? 0;
        $stmt = $conn->prepare("DELETE FROM audit_logs WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => $conn->error]);
        }
        $stmt->close();
    } elseif ($action === 'delete_all') {
        if ($conn->query("TRUNCATE TABLE audit_logs")) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => $conn->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
}

$conn->close();
?>