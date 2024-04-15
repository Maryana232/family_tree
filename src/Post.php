<?php
namespace Mariana\FamilyTree;

use PDO;

class Post {
    private ?int $id;
    private string $title;
    private string $content;
    private string $created_at;
    private string $updated_at;

    public function __construct($id = null, $title = '', $content = '', $created_at = null, $updated_at = null) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
    }

    /**
     * Завантажити всі пости
     * @return array
     * @throws \Exception
     */
    public static function loadAll(): array {
        $sql = "SELECT * FROM posts";
        $stmt = Database::executeQuery($sql);
        $posts = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $post = new Post(
                $row['id'],
                $row['title'],
                $row['content'],
                $row['created_at'] ?? null, // Якщо в базі немає значення, то встановлюємо null
                $row['updated_at'] ?? null
            );
            $posts[] = $post;
        }

        return $posts;
    }

    /**
     * Завантажити пост по id
     * @return void
     */
    public function save(): void {
        $this->updated_at = date('Y-m-d H:i:s'); // Встановлюємо дату оновлення

        if (!$this->id) {
            $this->created_at = $this->updated_at; // Якщо id не встановлено, то це новий пост, встановлюємо дату створення
        }

        $sql = $this->id ?
            "UPDATE posts SET title = ?, content = ?, updated_at = ? WHERE id = ?" :
            "INSERT INTO posts (title, content, created_at, updated_at) VALUES (?, ?, ?, ?)";

        Database::executeQuery($sql,
            $this->id ? [$this->title, $this->content, $this->updated_at, $this->id] :
                [$this->title, $this->content, $this->created_at, $this->updated_at]);

        if (!$this->id) {
            $this->id = Database::getConnection()->lastInsertId();
        }
    }


    /**
     * Видалити по id
     * @param int $id
     * @return void
     */
    public static function delete(int $id): void {
        $sql = "DELETE FROM posts WHERE id = ?";
        Database::executeQuery($sql, [$id]);
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
