<?php
namespace App\Repository;

use App\Database;
use PDO;

final class CategoryRepository
{
    public function all(): array
    {
        return Database::pdo()
            ->query('SELECT * FROM categories ORDER BY title')
            ->fetchAll();
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM categories WHERE slug = :s');
        $stmt->execute(['s' => $slug]);
        return $stmt->fetch() ?: null;
    }

    /** Категории, у которых есть хотя бы одна статья */
    public function withArticles(): array
    {
        $sql = 'SELECT c.*, COUNT(ac.article_id) AS articles_count
                FROM categories c
                JOIN article_category ac ON ac.category_id = c.id
                JOIN articles a ON a.id = ac.article_id
                GROUP BY c.id
                ORDER BY c.title';
        return Database::pdo()->query($sql)->fetchAll();
    }
}