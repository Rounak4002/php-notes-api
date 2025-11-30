<?php
// src/NotesController.php
// Controller contains functions for CRUD operations.

class NotesController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function list(): void
    {
        $stmt = $this->pdo->query('SELECT id, title, content, created_at, updated_at FROM notes ORDER BY created_at DESC');
        $notes = $stmt->fetchAll();
        $this->sendJson($notes);
    }

    public function get(int $id): void
    {
        $stmt = $this->pdo->prepare('SELECT id, title, content, created_at, updated_at FROM notes WHERE id = ?');
        $stmt->execute([$id]);
        $note = $stmt->fetch();
        if (!$note) {
            $this->sendJson(['error' => 'Note not found'], 404);
            return;
        }
        $this->sendJson($note);
    }

    public function create(array $data): void
    {
        if (empty($data['title'])) {
            $this->sendJson(['error' => 'Title is required'], 400);
            return;
        }

        $stmt = $this->pdo->prepare('INSERT INTO notes (title, content) VALUES (:title, :content)');
        $stmt->execute([
            ':title' => $data['title'],
            ':content' => $data['content'] ?? null,
        ]);
        $id = (int)$this->pdo->lastInsertId();
        http_response_code(201);
        $this->sendJson(['id' => $id, 'message' => 'Note created']);
    }

    public function update(int $id, array $data): void
    {
        // Check if note exists
        $stmt = $this->pdo->prepare('SELECT id FROM notes WHERE id = ?');
        $stmt->execute([$id]);
        if (!$stmt->fetch()) {
            $this->sendJson(['error' => 'Note not found'], 404);
            return;
        }

        $fields = [];
        $params = [':id' => $id];
        if (isset($data['title'])) {
            $fields[] = 'title = :title';
            $params[':title'] = $data['title'];
        }
        if (array_key_exists('content', $data)) {
            $fields[] = 'content = :content';
            $params[':content'] = $data['content'];
        }

        if (empty($fields)) {
            $this->sendJson(['message' => 'Nothing to update']);
            return;
        }

        $sql = 'UPDATE notes SET ' . implode(', ', $fields) . ' WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $this->sendJson(['message' => 'Note updated']);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM notes WHERE id = ?');
        $stmt->execute([$id]);
        if ($stmt->rowCount() === 0) {
            $this->sendJson(['error' => 'Note not found'], 404);
            return;
        }
        $this->sendJson(['message' => 'Note deleted']);
    }

    private function sendJson($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
