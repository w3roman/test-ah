<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Database;

$config = require __DIR__ . '/../config/config.php';
Database::init($config['db']);
$pdo = Database::pdo();

$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
$pdo->exec('TRUNCATE article_category');
$pdo->exec('TRUNCATE articles');
$pdo->exec('TRUNCATE categories');
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

$categories = [
    ['PHP', 'php', 'Статьи про PHP и его экосистему'],
    ['MySQL', 'mysql', 'Базы данных, SQL и оптимизация'],
    ['Frontend', 'frontend', 'JS, CSS и всё про браузер'],
];

$stmt = $pdo->prepare('INSERT INTO categories (title, slug, description) VALUES (?,?,?)');
$catIds = [];
foreach ($categories as [$t, $s, $d]) {
    $stmt->execute([$t, $s, $d]);
    $catIds[$s] = (int)$pdo->lastInsertId();
}

$articles = [
    ['Введение в PHP 8.1', 'php-81', 'Что нового в PHP 8.1', '<p>Readonly, enums, fibers...</p>', ['php'], 120],
    ['Smarty за 10 минут', 'smarty-10', 'Быстрый старт с шаблонизатором', '<p>Smarty — это классика...</p>', ['php','frontend'], 55],
    ['Индексы в MySQL', 'mysql-indexes', 'Как ускорить запросы', '<p>B-Tree, EXPLAIN...</p>', ['mysql'], 230],
    ['PDO и prepared statements', 'pdo-prepared', 'Безопасная работа с БД', '<p>Готовые выражения...</p>', ['php','mysql'], 90],
    ['CSS Grid на практике', 'css-grid', 'Вёрстка сеток', '<p>grid-template...</p>', ['frontend'], 41],
    ['Smarty: циклы и условия', 'smarty-loops', 'foreach, for, if', '<p>Продолжаем знакомство...</p>', ['php','frontend'], 33],
    ['MySQL: JOIN-ы', 'mysql-joins', 'INNER, LEFT, RIGHT', '<p>Разбираем соединения...</p>', ['mysql'], 78],
];

$insArticle = $pdo->prepare(
    'INSERT INTO articles (title, slug, image, short_description, content, views, published_at)
     VALUES (?,?,?,?,?,?,?)'
);
$insLink = $pdo->prepare(
    'INSERT INTO article_category (article_id, category_id) VALUES (?,?)'
);

$now = time();
foreach ($articles as $i => [$title, $slug, $short, $content, $cats, $views]) {
    $insArticle->execute([
        $title, $slug, null, $short, $content, $views,
        date('Y-m-d H:i:s', $now - $i * 86400),
    ]);
    $aid = (int)$pdo->lastInsertId();
    foreach ($cats as $cSlug) {
        $insLink->execute([$aid, $catIds[$cSlug]]);
    }
}

echo "Seeded: " . count($categories) . " categories, " . count($articles) . " articles\n";