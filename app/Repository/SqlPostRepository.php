<?php

namespace App\Repository;

use App\Entity\Post;
use PDO;

class SqlPostRepository extends PostRepository implements IPostRepository
{
    const DATE_FORMAT = 'Y-m-d H:i:s';
    private $pdo;

    /**
     * SqlPostRepository constructor.
     * @param  PDO  $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->initSchema();
    }

    private function initSchema(): void
    {
        $this->pdo->exec(<<<SQL
CREATE TABLE IF NOT EXISTS posts (
 id TEXT PRIMARY KEY,
 text TEXT NOT NULL,
 user_id TEXT NOT NULL,
 user_name TEXT NOT NULL,
 created_at TEXT NOT NULL
)
SQL);
    }

    public function truncate()
    {
        $this->pdo->exec('DROP TABLE posts;');
        $this->initSchema();
    }

    /**
     * @param string $userId
     * @return Post[]|array
     */
    public function getByUserId(string $userId): array
    {
        $sql = 'SELECT id, text, created_at, user_id, user_name FROM posts WHERE user_id = :user_id';

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam('user_id', $userId, SQLITE3_TEXT);
        $stmt->execute();

        return array_map(function ($row) {
            return $this->fillThePostObject($row);
        }, $stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    /**
     * @return Post[]|array
     */
    public function all(): array
    {
        $sql = 'SELECT id, text, created_at, user_id, user_name FROM posts';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return array_map(function ($row) {
            return $this->fillThePostObject($row);
        }, $stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    /**
     * @param  string  $monthNumber
     * @return Post[]|array
     */
    public function getByMonth(string $monthNumber): array
    {
        $sql = <<<SQL
SELECT id, text, user_id, user_name, created_at 
FROM posts 
WHERE strftime('%m', created_at) = :monthNumber;
SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam('monthNumber', $monthNumber);
        $stmt->execute();

        return array_map(function ($row) {
            return $this->fillThePostObject($row);
        }, $stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function update(Post $post): Post
    {
        $sql =<<<SQL
UPDATE posts
SET (text, user_id, user_name) = (:text, :user_id, :user_name) 
WHERE id = :id;
SQL;

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':text', $post->text);
        $stmt->bindParam(':user_id', $post->user_id);
        $stmt->bindParam(':user_name', $post->user_name);
        $stmt->bindParam(':id', $post->id);

        $stmt->execute();

        $post->id = $this->pdo->lastInsertId();
        return $post;
    }

    public function save(Post $post): Post
    {
        $sql =<<<SQL
INSERT INTO posts 
(id, text, user_id, user_name, created_at) 
VALUES (:id, :text, :user_id, :user_name, :created_at);
SQL;

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':id', $post->id);
        $stmt->bindParam(':text', $post->text);
        $stmt->bindParam(':user_id', $post->user_id);
        $stmt->bindParam(':user_name', $post->user_name);
        $stmt->bindParam(':created_at', $post->created_at);

        $stmt->execute();

        $post->id = $this->pdo->lastInsertId();
        return $post;
    }
}
