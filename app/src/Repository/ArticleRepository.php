<?php
namespace App\Repository;

use App\Database;
use PDO;

final class ArticleRepository
{
    /** 3 последних статьи в категории */
    public function latestByCategory(int $categoryId, int $limit = 3): array
    {
        $sql = 'SELECT a.* FROM articles a
                JOIN article_category ac ON ac.article_id = a.id
                WHERE ac.category_id = :cid
                ORDER BY a.published_at DESC
                LIMIT :lim';
        $stmt = Database::pdo()->prepare($sql);
        $stmt->bindValue('cid', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue('lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** Список статей категории с сортировкой и пагинацией */
    public function paginateByCategory(int $categoryId, string $sort, int $page, int $perPage = 6): array
    {
        $orderBy = $sort === 'views' ? 'a.views DESC' : 'a.published_at DESC';
        $offset  = ($page - 1) * $perPage;

        $sql = "SELECT a.* FROM articles a
                JOIN article_category ac ON ac.article_id = a.id
                WHERE ac.category_id = :cid
                ORDER BY {$orderBy}
                LIMIT :lim OFFSET :off";

        $stmt = Database::pdo()->prepare($sql);
        $stmt->bindValue('cid', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue('lim', $perPage, PDO::PARAM_INT);
        $stmt->bindValue('off', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countByCategory(int $categoryId): int
    {
        $stmt = Database::pdo()->prepare(
            'SELECT COUNT(*) FROM article_category WHERE category_id = :cid'
        );
        $stmt->execute(['cid' => $categoryId]);
        return (int)$stmt->fetchColumn();
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM articles WHERE slug = :s');
        $stmt->execute(['s' => $slug]);
        return $stmt->fetch() ?: null;
    }

    public function incrementViews(int $id): void
    {
        $stmt = Database::pdo()->prepare('UPDATE articles SET views = views + 1 WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    /** Похожие статьи — те, что в тех же категориях, кроме текущей */
    public function similar(int $articleId, int $limit = 3): array
    {
        $sql = 'SELECT DISTINCT a.*
            FROM articles a
            JOIN article_category ac ON ac.article_id = a.id
            WHERE ac.category_id IN (
                SELECT category_id FROM article_category WHERE article_id = :aid_sub
            )
            AND a.id != :aid_main
            ORDER BY a.published_at DESC
            LIMIT :lim';
        $stmt = Database::pdo()->prepare($sql);
        $stmt->bindValue('aid_sub',  $articleId, PDO::PARAM_INT);
        $stmt->bindValue('aid_main', $articleId, PDO::PARAM_INT);
        $stmt->bindValue('lim',      $limit,     PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function categoriesFor(int $articleId): array
    {
        $sql = 'SELECT c.* FROM categories c
                JOIN article_category ac ON ac.category_id = c.id
                WHERE ac.article_id = :aid';
        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute(['aid' => $articleId]);
        return $stmt->fetchAll();
    }
}
