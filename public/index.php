<?php
// public/index.php
// Simple router. Serve requests like:
// GET    /notes
// GET    /notes/{id}
// POST   /notes
// PUT    /notes/{id}
// DELETE /notes/{id}

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../src/NotesController.php';

$pdo = getPDO();
$controller = new NotesController($pdo);

// Basic routing using REQUEST_METHOD and path segments.
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// If serving from document root different than /, adjust by trimming base path if needed.
// $basePath = '/'; // adjust if using subfolder
// $uri = preg_replace('#^' . preg_quote($basePath, '#') . '#', '', $uri);

$segments = array_values(array_filter(explode('/', $uri))); // removes empty parts

// Route resolution
if (count($segments) === 0) {
    // root - show simple message
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Notes API - available endpoints: GET /notes, GET /notes/{id}, POST /notes, PUT /notes/{id}, DELETE /notes/{id}']);
    exit;
}

// Expect first segment 'notes'
if ($segments[0] !== 'notes') {
    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Resource not found']);
    exit;
}

// parse id if present
$id = isset($segments[1]) ? (int)$segments[1] : null;

// Read JSON body for POST/PUT
$body = null;
if (in_array($method, ['POST', 'PUT', 'PATCH'])) {
    $raw = file_get_contents('php://input');
    $body = json_decode($raw, true);
    if ($raw && $body === null) {
        // invalid JSON
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON body']);
        exit;
    }
}

switch ($method) {
    case 'GET':
        if ($id) {
            $controller->get($id);
        } else {
            $controller->list();
        }
        break;
    case 'POST':
        $controller->create($body ?? []);
        break;
    case 'PUT':
    case 'PATCH':
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID required for update']);
            exit;
        }
        $controller->update($id, $body ?? []);
        break;
    case 'DELETE':
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID required for delete']);
            exit;
        }
        $controller->delete($id);
        break;
    default:
        http_response_code(405);
        header('Allow: GET, POST, PUT, DELETE, PATCH');
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
